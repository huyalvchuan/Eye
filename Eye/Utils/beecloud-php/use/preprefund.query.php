<?php
require_once("../loader.php");
require_once("config.php");

$data["timestamp"] = time() * 1000;
$data["need_approval"] = true;
$data["limit"] = 10;

$type = $_GET['type'];
switch($type){
    case 'ALI' :
        $title = "支付宝";
        $data["channel"] = "ALI";
        break;
    case 'BD' :
        $title = "百度";
        $data["channel"] = "BD";
        break;
    case 'JD' :
        $title = "京东";
        $data["channel"] = "JD";
        break;
    case 'WX' :
        $title = "微信";
        $data["channel"] = "WX";
        break;
    case 'UN' :
        $title = "银联";
        $data["channel"] = "UN";
        break;

}
?>
<?php
    try {
        //设置app id, app secret, master secret, test secret
        $api->registerApp(APP_ID, APP_SECRET, MASTER_SECRET, TEST_SECRET);

        $result = $api->refunds($data);
        if ($result->result_code != 0 || $result->result_msg != "OK") {
            print_r($result);
            exit();
        }
        $refunds = $result->refunds;
        $str = "<tr><th>选择<input name='checkAll' type='checkbox'/></th><th>ID</th><th>退款是否成功</th><th>退款创建时间</th><th>退款号</th><th>订单金额(分)</th><th>退款金额(分)</th><th>渠道</th><th>子渠道</th><th>订单号</th><th>退款是否完成</th><th>订单标题</th></tr>";
        foreach($refunds as $list) {
            $result = $list->result ? "成功" : "失败";
            $create_time = $list->create_time ? date('Y-m-d H:i:s',$list->create_time/1000) : '';
            $finish = $list->finish ? "完成" : "未完成";
            $checkout = $list->result ? '' : "<input name='approve' type='checkbox' value='{$list->id}'/>";

            $str .= "<tr><td align='center'>$checkout</td><td>{$list->id}</td><td>$result</td><td>$create_time</td><td>{$list->refund_no}</td><td>{$list->total_fee}</td>
                    <td>{$list->refund_fee}</td><td>{$list->channel}</td><td>{$list->sub_channel}</td><td>{$list->bill_no}</td><td>$finish</td><td>{$list->title}</td></tr>";
        }
        echo $str;

        unset($data["limit"]);
        $result = $api->refunds_count($data);
        if ($result->result_code != 0 || $result->result_msg != "OK") {
            print_r($result);
            exit();
        }
        $count = $result->count;
        echo '<tr><td colspan="2">退款订单总数:</td><td colspan="10">'.$count.'</td></tr>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
