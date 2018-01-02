<?php

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * @haguo
 */
/**
 * 淘宝API接口范例
 *
 * @author haguo <2017-12-7 02:14:37>
 *
 */

if (!defined("TOP_AUTOLOADER_PATH")) {
    define("TOP_AUTOLOADER_PATH", dirname(__FILE__));
}

$filename = TOP_AUTOLOADER_PATH . "/Client.class.php";
if (is_file($filename)) {
    include $filename;
}
$filenames = TOP_AUTOLOADER_PATH . "/request/*.class.php";
foreach (glob($filenames) as $filename) {
    if (is_file($filename)) {
        include $filename;
    }
}

/**
 * DEMO
 * https://uland.taobao.com/coupon/edetail?e=wk47WeF3ToQ8Clx5mXPEKmNIBXTbTF8cknBUhHMbgSx2pj%2BrIzI8SLxhxCo59%2FbGyqPMPTA1R3GB9XIcjNiueYVUDvhDJNf29h0V5GdJyUiyFfeb5yGeZGqqL5IL2kNQmyM802S0%2FcG2%2FwVislvJlBjbpO%2BcmZVNhZrIc7jaIgutYW0YMGFDnw%3D%3D
 *->setFields("afterCouponPrice,afterCouponPrice,biz30day,clickUrl,couponPrice,currentPrice,freeShipping,itemId,pictUrl,provcity,sellerId,sellerNick,title,userType")
 */

function index()
{


    /*$req = (new TaobaokeItemListGet)
        ->setPid("mm_109504678_36556626_142518263")
        ->setQ("女装")
        ->setSort("2");*/
    /*$req = (new TaobaokeCouponItemListGet)
        ->setPid("mm_109504678_36556626_142518263")
        ->setQ("女装")
        ->setPageNo(1)
        ->setPageSize(30);*/
    /*$req = (new TaobaokeCouponItemDetailGet)
        ->setPid("mm_109504678_36556626_142518263")
        ->setUlandUrl("https://uland.taobao.com/coupon/edetail?e=wk47WeF3ToQ8Clx5mXPEKmNIBXTbTF8cknBUhHMbgSx2pj%2BrIzI8SLxhxCo59%2FbGyqPMPTA1R3GB9XIcjNiueYVUDvhDJNf29h0V5GdJyUiyFfeb5yGeZGqqL5IL2kNQmyM802S0%2FcG2%2FwVislvJlBjbpO%2BcmZVNhZrIc7jaIgutYW0YMGFDnw%3D%3D");*/
    //$req = (new TaobaokeToolGenerateUrlGet)
    //->setPid('mm_109504678_36556626_142518263')
    //->setitemUrl("https://uland.taobao.com/coupon/edetail?activityId=7c0038881ee643c8859861b7dfb4f59a")
    //->setitemId('18696820802');
    /*$req = (new TaobaokeToolTaoBaoPasswordGet)
        ->setClickUrl("https://s.click.taobao.com/qDyviWw")
        ->setText("好物介绍给你")
        ->setLogo("好物介绍给你");*/
    /*$req = (new TaobaokeToolmergeUrlGet)
        ->setPid('mm_109504678_36556626_142518263')
        ->setUlandUrl("http://shop.m.taobao.com/shop/coupon.htm?seller_id=2106328030&activityId=8d2ae05efac64075b78d8accb95e7a7a")
        ->setitemUrl('https://detail.tmall.com/item.htm?id=560436999768');*/
    /*$req = (new TaobaokeCouponItemGet)
        ->setPid('mm_109504678_36556626_142518263')
        ->setitemId('18696820802');*/


    //$req = (new Client)->execute($req);
    //return $req;
    /*读取csv文件用 */

}