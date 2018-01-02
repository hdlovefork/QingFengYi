<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-22
 * Time: 9:11
 */

/**
 * 采集数据基类
 * Class Base_collect
 */
abstract class Base_collect extends CI_Model
{
    protected $table = 'goods';

    /**
     * 服务器返回非200状态码时自动重试次数
     * @var int
     */
    protected $auto_reconnect_count = 3;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('http');
    }

    /**
     * 清除指定间隔时间内的商品
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     */
    public function clean_expire($year = 0, $month = 0, $day = 0, $hour = 0, $minute = 60, $second = 0)
    {
        date_default_timezone_set('PRC');
        $this->db->delete($this->table, ['quan_time <' => $this->_get_interval_time($year, $month, $day, $hour, $minute, $second)]);
    }

    /**
     * 根据间隔时间参数计算出最终时间字符串
     * @param $year
     * @param $month
     * @param $day
     * @param $hour
     * @param $minute
     * @param $second
     * @return string
     */
    private function _get_interval_time($year, $month, $day, $hour, $minute, $second)
    {
        $dt = new DateTime('now', new DateTimeZone('PRC'));
        $dt->add(new DateInterval("P{$year}Y{$month}M{$day}DT{$hour}H{$minute}M{$second}S"));
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * 开始采集数据
     * @param $req_url
     * @return mixed
     * @throws Exception
     */
    public function collect($req_url)
    {
        $failed_count = 0;
        do {
            //请求远程API数据
            $res = curl_get($req_url, $status);
            if ($status === 200 && $res) {
                //转换数据格式
                $formatted = $this->transform_data($res);
                //持久化保存到数据库
                return $this->persistent($formatted);
            }
            //失败延迟1秒后重试
            sleep(1);
        } while ($failed_count++ < $this->auto_reconnect_count);
        throw new Exception("请求：{$req_url}失败" . PHP_EOL . "状态码:{$status}");
    }

    /**
     * 将远程服务器返回的数据格式进行转换
     * @param string $res 远程服务器返回的数据
     * @return mixed|array 返回转换后的数据格式
     */
    abstract function transform_data($res);

    /**
     * 持久化保存到数据库
     * @param array $formatted 关联数组
     * @return mixed 返回成功保存的记录数
     */
    abstract function persistent($formatted);
}