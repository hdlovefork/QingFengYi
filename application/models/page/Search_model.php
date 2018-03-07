<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-28
 * Time: 20:18
 */

require_once('Page_model.php');

class Search_model extends Page_model
{
    const BASE_URL = 'https://public.immmmmm.com/wxapp/dabaicai.php?search=%s&page=%s';

    public function search($keyword = '女装', $page = 1)
    {
        $this->load->helper('http');
        $url = sprintf(self::BASE_URL, $keyword, $page);
        return $this->to_array(curl_get($url));
        //{
        //    cid:0,
        //    goods:[
        //            {
        //                cid:10
        //                coupon_link_m:"http://shop.m.taobao.com/shop/coupon.htm?seller_id=2113877590&activity_id=4c182df4824749b78bb5856404141202&v=0&pds=wapedition%23h%23",
        //                coupon_link_pc:"https://taoquan.taobao.com/coupon/unify_apply.htm?activityId=4c182df4824749b78bb5856404141202&sellerId=2113877590",
        //                coupon_num:0,
        //                coupon_price:"3",
        //                id:447445,
        //                imgpath:"",
        //                imgs:"https://img.alicdn.com/tfscom/i3/2113877590/TB2iB2opxXkpuFjy0FiXXbUfFXa_!!2113877590.jpg|https://img.alicdn.com/tfscom/i1/2113877590/TB22GLCprtlpuFjSspoXXbcDpXa_!!2113877590.jpg|https://img.alicdn.com/tfscom/i2/2113877590/TB2eYfRsypnpuFjSZFIXXXh2VXa_!!2113877590.jpg|https://img.alicdn.com/tfscom/i1/2113877590/TB2DBeVrYJmpuFjSZFwXXaE4VXa_!!2113877590.jpg_430x430q90.jpg",
        //                imgurl:"https://img.alicdn.com/tfscom/i1/TB1L.m9RXXXXXaSaXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg",
        //                istop:0,
        //                list_status,:2,
        //                mtime:1514455677,
        //                nowprice:"9.80",
        //                oprice:"12.50",
        //                otitle:"小米盒子遥控器1代2代3代增强版通用 小米电视机遥控器 红外使用",
        //                past_rate:"40.30",
        //                pprice:"6.8",
        //                prom_status:0,
        //                promotion:3,
        //                ptime:0,
        //                ptime_str:null,
        //                seq:7456553.01087,
        //                slogan:"小米盒子1代2代3代增强版通用遥控器，小米电视机遥控器，红外使用，一年换新按键柔软直接使用！",
        //                tag:"",
        //                taosec:"￥5WHw0Q8X793￥",
        //                target_id:278,
        //                tbdesc:null,
        //                tbid:"524232865249",
        //                tbprice:"12.12",
        //                tburl:"https://s.click.taobao.com/FpngsVw",
        //                title:"小米盒子1代2代3代遥控器",
        //                uplist_time:1505181163
        //            }
        //        ],
        //    lastPage:452,
        //    page:"1"
        //}


    }

    /**
     * 热门搜索关键字
     * @return mixed|null
     */
    public function reso(){
        $this->load->model("service/{$this->service}/query_model");
        return $this->query_model->reso();
    }
}