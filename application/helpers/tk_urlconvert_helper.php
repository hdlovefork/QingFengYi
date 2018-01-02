<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-10
 * Time: 1:24
 */
function get_word($html,$star,$end){
    $wd = null;
    $pat = '/'.$star.'(.*?)'.$end.'/s';
    if(!preg_match_all($pat, $html, $mat)) {
    }else{
        $wd= $mat[1][0];
    }
    return $wd;
}

function convertTbkUrl($num_id, $pid, $cookie)
{
    $zym_19 = $cookie;
    $zym_20 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
    $zym_24 = array("","","","","");
    $zym_19 = str_replace($zym_20, $zym_24, $zym_19);
    $zym_23 = get_word($zym_19,'_tb_token_=',';');
    $zym_22 = get_word($zym_19,'t=',';');
    $zym_21 = get_word($zym_19,'cna=',';');
    $zym_13 = get_word($zym_19,'l=',';');
    $zym_12 = get_word($zym_19,'mm-guidance3',';');
    $zym_5 = get_word($zym_19,'_umdata=',';');
    $zym_4 = get_word($zym_19,'cookie2=',';');
    $zym_3 = get_word($zym_19,'cookie32=',';');
    $zym_1 = get_word($zym_19,'cookie31=',';');
    $zym_2 = get_word($zym_19,'alimamapwag=',';');
    $zym_6 = get_word($zym_19,'login=',';');
    $zym_7 = get_word($zym_19,'alimamapw=',';');
    $zym_11 = 't='.$zym_22.';cna='.$zym_21.';l='.$zym_13.';mm-guidance3='.$zym_12.';_umdata='.$zym_5.';cookie2='.$zym_4.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_3.';cookie31='.$zym_1.';alimamapwag='.$zym_2.';login='.$zym_6.';alimamapw='.$zym_7;
    $zym_10 = microtime(true)*1000;
    $zym_10 = explode('.', $zym_10);
    $zym_29 = $pid;
    $zym_27 = explode('_',$zym_29);
    $zym_28 = $zym_27[2];
    $zym_32 = $zym_27[3];
    // $zym_31 = get_client_ip();
    $zym_31 = "127.0.0.1";
    $zym_30 = '50_'.$zym_31.'_15881_1468693605455';
    $zym_33 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$num_id.'&adzoneid='.$zym_32.'&siteid='.$zym_28.'&t='.$zym_10[0].'&pvid='.$zym_30.'&_tb_token_='.$zym_23.'&_input_charset=utf-8';

    // print_r($zym_33);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zym_33);
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_11.'}', ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $rs = curl_exec($ch);
    curl_close($ch);
    return json_decode($rs,true);
}

/**
 *这是加入淘客计划的商品id
 */
//$num_id = isset($_GET['id'])?($_GET['id']):'537154174717';
//$url = "http://item.taobao.com/item.htm?id=".$num_id;
//
///**
// *这是你的pid
// */
//$pid = "mm_32805119_40744564_164568086";
//
///**
// *这是你的阿里妈妈的cookie
// */
//$cookie = "t=cffce85203f8ab29e8f796e22511b64e; cna=HWaeEmEMciMCASoxYrEWWSAr; _umdata=2FB0BDB3C12E491DBC83196CDF6FCE01FE773ED01F17262EEC5E846A22A5691204D73340F5E6093CCD43AD3E795C914CC82E94AFE139CF3C4B371B031A3B3EAB; cookie2=16aefab1b412f68f6c4d9efb054fb35f; v=0; _tb_token_=77531113ef8ae; alimamapwag=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzYyLjAuMzIwMi45NCBTYWZhcmkvNTM3LjM2; cookie32=d2cfc00b42999947b511d47cc563abae; alimamapw=XQZaDkBRBwtAWmpUBgYGAgVVAlJUVAENUwdUAQBXBFEBVlVVBAQCUFJRAw%3D%3D; cookie31=MzI4MDUxMTksaGRsb3ZlZm9yayxoZGxvdmVmb3JrQDE2My5jb20sVEI%3D; login=V32FPkk%2Fw0dUvg%3D%3D; rurl=aHR0cHM6Ly9wdWIuYWxpbWFtYS5jb20v; isg=AkhIIzOJhkci8Or4y1T4yt_FGbaaWa2cv4A6KAL5k0O23ehHqgF8i97TKYNW";


//$data = (convertTbkUrl($num_id, $pid, $cookie));