<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-16
 * Time: 20:22
 */
include_once APPPATH . 'third_party/wx/wxBizMsgCrypt.php';

/**
 * 本类供微信平台调用
 * Class Weixin
 */
class Weixin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * 接收微信授权码
     */
    public function wxticket()
    {
        $data['GET'] = $this->input->get();
        $data['RAW'] = $this->input->raw_input_stream;
        log_message('DEBUG', "wxticket----->\n" . var_export($data,TRUE));

        $msg_sign = $this->input->get('msg_signature');
        $time_stamp = $this->input->get('timestamp');
        $nonce = $this->input->get('nonce');

        if(!isset($msg_sign,$time_stamp,$nonce)){
            redirect();
        }
        $this->load->config('wx');
        $token = $this->config->item('wx_third_token');
        $appId = $this->config->item('wx_third_app_id');
        $encodingAesKey = $this->config->item('wx_third_encodingaeskey');
        //解密数据
        $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $errCode = $pc->decryptMsg($msg_sign, $time_stamp, $nonce, $this->input->raw_input_stream, $msg);
        if ($errCode == 0) {
            log_message('DEBUG', "解密后: " . $msg);
        } else {
            log_message('DEBUG', "\$errCode--->{$errCode}");
            redirect();
        }
        //解析数据拿出ticket
        $xml_tree = new DOMDocument();
        $xml_tree->loadXML($msg);
        $array_i = $xml_tree->getElementsByTagName('InfoType');
        $infoType = $array_i->item(0)->nodeValue;
        $infoType === 'component_verify_ticket' OR DIE;
        $array_c = $xml_tree->getElementsByTagName('ComponentVerifyTicket');
        $ticket = $array_c->item(0)->nodeValue;
        //保存到缓存中
        $this->load->driver('cache', ['adapter' => 'apc', 'backup' => 'file']);
        $this->cache->save('wx_ticket',$ticket,1000);
        log_message('DEBUG', "ticket--->{$ticket}");
        echo 'success';
    }

    /**
     * 接收微信消息与事件
     */
    public function wxmsg($appid)
    {
        echo 'success';
        $data['appid'] = $appid;
        $data['GET'] = $_GET;
        $data['POST'] = $_POST;
        $data['RAW'] = $this->input->raw_input_stream;
        log_message('DEBUG', 'wxmsg----->\n' . var_export($data, TRUE));
    }
}