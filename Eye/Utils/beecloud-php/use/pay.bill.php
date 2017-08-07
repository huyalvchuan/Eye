<?php

require_once("../loader.php");
require_once("config.php");

class Pay {
	
	function _construct($Data){
		
try {
    /* registerApp fun need four params:
     * @param(first) $app_id beecloud平台的APP ID
     * @param(second) $app_secret  beecloud平台的APP SECRET
     * @param(third) $master_secret  beecloud平台的MASTER SECRET
     * @param(fouth) $test_secret  beecloud平台的TEST SECRET, for sandbox
     */
    $api->registerApp(APP_ID, APP_SECRET, MASTER_SECRET, TEST_SECRET);
    //Test Model,只提供下单和支付订单查询的Sandbox模式,不写setSandbox函数或者false即live模式,true即test模式
    //$api->setSandbox(false);

    //\beecloud\rest\api::registerApp(APP_ID, APP_SECRET, MASTER_SECRET, TEST_SECRET);
    //\beecloud\rest\api::setSandbox(false);
}catch(Exception $e){
    die($e->getMessage());
}
$data = array();
$data["timestamp"] = time() * 1000;
//total_fee(int 类型) 单位分
$data["total_fee"] = $Data["total_fee"];
$data["bill_no"] = "bcdemo" . $data["timestamp"];
//title UTF8编码格式，32个字节内，最长支持16个汉字
$data["title"] = 'PHP '.$_GET['type'].'支付测试';
//渠道类型:ALI_WEB 或 ALI_QRCODE 或 UN_WEB或JD_WAP或JD_WEB时为必填
$data["return_url"] = RETURN_URL;
//选填 optional
$data["optional"] = (object)array("tag"=>"msgtoreturn");
$data["notify_url"]=WEBHOOK;
//选填 订单失效时间bill_timeout
//必须为非零正整数，单位为秒，建议最短失效时间间隔必须大于360秒
//京东(JD*)不支持该参数。
//$data["bill_timeout"] = 360;

/**
 * notify_url 选填，该参数是为接收支付之后返回信息的,仅适用于线上支付方式, 等同于在beecloud平台配置webhook，
 * 如果两者都设置了，则优先使用notify_url。配置时请结合自己的项目谨慎配置，具体请
 * 参考demo/webhook.php
 */
//$data['notify_url'] = 'http://xxx/webhook.php';

$type = $_GET['type'];
switch($type){
    case 'ALI_WEB' :
        $title = "支付宝及时到账";
        $data["channel"] = "ALI_WEB";
        break;
    case 'ALI_WAP' :
        $title = "支付宝移动网页";
        $data["channel"] = "ALI_WAP";
        //非必填参数,boolean型,是否使用APP支付,true使用,否则不使用
        //$data["use_app"] = true;
        break;
    case 'ALI_QRCODE' :
        $title = "支付宝扫码支付";
        $data["channel"] = "ALI_QRCODE";
        //qr_pay_mode必填 二维码类型含义
        //0： 订单码-简约前置模式, 对应 iframe 宽度不能小于 600px, 高度不能小于 300px
        //1： 订单码-前置模式, 对应 iframe 宽度不能小于 300px, 高度不能小于 600px
        //3： 订单码-迷你前置模式, 对应 iframe 宽度不能小于 75px, 高度不能小于 75px
        $data["qr_pay_mode"] = "0";
        break;
    case 'ALI_OFFLINE_QRCODE' :
        $data["channel"] = "ALI_OFFLINE_QRCODE";
        $title = "支付宝线下扫码";
        require_once 'ali.offline.qrcode/index.php';
        exit();
        break;
    
    
    case 'UN_WEB' :
        $data["channel"] = "UN_WEB";
        $title = "银联网页";
        break;
    case 'UN_WAP' : //由于银联做了适配,需在移动端打开,PC端仍显示网页支付
        $data["channel"] = "UN_WAP";
        $title = "银联移动网页";
        break;
    case 'WX_NATIVE':
        $data["channel"] = "WX_NATIVE";
        $title = "微信扫码";
        require_once 'wx/wx.native.php';
        exit();
        break;
    case 'WX_JSAPI':
        $data["channel"] = "WX_JSAPI";
        $title = "微信H5网页";
        require_once 'wx/wx.jsapi.php';
        exit();
        break;
     $api->bill($data);
}
try {
        //设置app id, app secret, master secret, test secret
        $api->registerApp(APP_ID, APP_SECRET, MASTER_SECRET, TEST_SECRET);
        //Test Model,只提供下单和支付订单查询的Sandbox模式,不写setSandbox函数或者false即live模式,true即test模式
        //$api->setSandbox(false);

        $result = $api->bills($data);
        if ($result->result_code != 0 || $result->result_msg != "OK") {
            print_r($result);
            exit();
        }
        $bills = $result->bills;
      	 foreach($bills as $list) {
            $refund = true;
            $pre_refund = true;
            $type = trim($list->channel);
            if(in_array($list->sub_channel, array('ALI_OFFLINE_QRCODE', 'ALI_SCAN', 'WX_SCAN', 'WX_NATIVE'))){
                $type = trim($list->sub_channel);
                $pre_refund = false;
            }
            if($type == 'BC' || $type == 'PAYPAL' || $api->getSandbox()){
                $refund = false;
                $pre_refund = false;
            }
            $strParams = "agree.refund.php?type=$type&refund_no=".$refund_no."&bill_no=".$list->bill_no."&refund_fee=".$list->total_fee;
            $agree_refund = $list->spay_result&&!$list->refund_result&&$refund ? "<a href='".$strParams."' target='_blank'>退款</a>" : "";
            $prep_refund = $list->spay_result&&!$list->refund_result&&$pre_refund ? "<a href='".$strParams."&need_approval=true' target='_blank'>预退款</a>" : "";
            $spay_result = $list->spay_result ? ($list->refund_result ? '已退款' : '支付') : '未支付';
            $create_time = $list->create_time ? date ( 'Y-m-d H:i:s', $list->create_time / 1000 ) : '';
            $str .= "<tr><td>$list->id</td><td>$agree_refund</td><td>$prep_refund</td><td>$spay_result</td><td>$create_time</td><td>{$list->total_fee}</td><td>{$list->sub_channel}</td>
            	<td>{$list->bill_no}</td><td>{$list->title}</td></tr>";
        }
         

        unset($data["limit"]);
        $result = $api->bills_count($data);
        if ($result->result_code != 0 || $result->result_msg != "OK") {
            print_r($result);
            exit();
        }
        $count = $result->count;
        
    } catch (Exception $e) {
        echo $e->getMessage();
    }
		
	}



}
?>