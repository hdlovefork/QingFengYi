<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-06
 * Time: 13:50
 */
include_once APPPATH . 'models/service/Remote_model.php';

class Taoapi_model extends Remote_model
{
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new \libraries\huoxing\Client();
    }

    /**
     * 获得淘口令
     * @param $data array ['activity','tbid','pid']
     * @return array|null ['gmtip','guanlink','tkl']
     */
    public function get_tkl($data)
    {
        //1：获取二合一地址
        $two_one_url = $this->get_two_one($data);
        if (!$two_one_url) return null;
        //转换成高佣链接
        $this->load->library('token');
        $pid = $this->token->get_data('tb_pid') ?: 'mm_32805119_40744564_164568086';
        $tbid = $data['tbid'];
        $chaozhi_token = $this->token->get_data('chaozhi_token');
        $chaozhi_session = $this->token->get_data('chaozhi_session');
        $request = new libraries\chaozhi\request\GaoYongGet();
        $request->addCookie('PHPSESSID', $chaozhi_session);
        $request->setPid($pid)->setToken($chaozhi_token)->setTbID($tbid);
        $client = new \libraries\chaozhi\Client();
        $res = $client->execute($request);
        if (!$res['result']['data']['coupon_click_url']) {
            log_message('DEBUG', '获取高佣失败');
        } else {
            $two_one_url = $res['result']['data']['coupon_click_url'];
        }
        // $two_one_url =  $res['result']['data']['coupon_click_url'] ?: $two_one_url;
        //获取商品信息
        $logo = $data['logo'];
        $title = $data['title'];
        //没有传入主图和宝贝标题则访问淘宝服务器获取，该获取不一定能成功
        //淘宝报错：请检查是否使用了代理软件或 VPN 哦~
        if (!$logo && !$title) {
            $tao_data = curl_get("https://hws.m.taobao.com/cache/wdetail/5.0/?id={$data['tbid']}", $http_code);
            log_message('DEBUG', var_export($tao_data, TRUE));
            if ($tao_data && ($tao_data = json_decode($tao_data, TRUE))) {
                $logo = $tao_data['data']['itemInfoModel']['picsPath'][0];
                $title = $tao_data['data']['itemInfoModel']['title'];
                log_message('DEBUG', "logo:{$logo}\ntitle:{$title}");
            }
        }
        //2：转换成淘口令
        $request = new \libraries\huoxing\request\TaobaokeToolTaoBaoPasswordGet();
        $request->setClickUrl($two_one_url);
        //3：生成淘口令LOGO
        ($logo && $request->setLogo($logo)) OR $request->setLogo('https://img.alicdn.com/imgextra/i3/2613443097/TB2Ewm2XnnI8KJjSszbXXb4KFXa_!!2613443097.png');
        //4：生成淘口令文字
        ($title && $request->setText($title)) OR $request->setText('粉丝福利购，立即领券');
        $res = $this->client->execute($request);
        //5：执行出错
        if (!$res || !$res['success']) return null;
        return [
            'gmtip' => '复制成功！打开手机淘宝下单即可',
            'quanLink' => $two_one_url,
            'tkl' => $res['data']
        ];
    }

    /**
     * 获取评论数据
     * @param $data array ['tbid','page']
     * @return null|array
     */
    public function get_rate($data)
    {
        //1：从商品简介中获取userNumId，作为第2步获取评论的参数
        $jie = $this->get_jie($data['tbid']);
        $user_num_id = $jie['data']['seller']['userNumId'];
        if (!$user_num_id) return NULL;
        //2：获取评论列表
        $url = "https://rate.tmall.com/list_detail_rate.htm?itemId={$data['tbid']}&sellerId={$user_num_id}&order=3&currentPage={$data['page']}";
        $res = curl_get($url);
        //补全JSON数据的格式
        $res = '{' . $res . '}';
        //淘宝是GB2312编码，所以需要转换
        $res = mb_convert_encoding($res, 'UTF-8', 'GB18030');
        if ($res && ($data = json_decode($res, TRUE))) {
            return $data['rateDetail'];
        }
        return NULL;
    }

    /**
     * 获取淘宝简介
     * @param $tbid string
     * @return null|array
     */
    public function get_jie($tbid)
    {
        $url = "https://hws.m.taobao.com/cache/wdetail/5.0/?id={$tbid}";
        $res = curl_get($url);
        if ($res && ($data = json_decode($res, TRUE))) {
            return $data;
        }
        return NULL;
    }
}