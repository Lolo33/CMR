<?php

if(isset($_GET['msg_id'])){
	$msg_id = $_GET['msg_id'];
}
else{
	$msg_id = "64739264111277880";
}

require 'vendor/autoload.php';
use \Mailjet\Resources;

$apikey = 'bbe7791eafdc50b5f065a49f19fe5dfa';
$apisecret = 'c421b84dfb234768ba81575e7b1c9020';

$mj = new \Mailjet\Client($apikey, $apisecret);
$response = $mj->get(Resources::$Message, ['id' => $msg_id]);

//var_dump($response);
//var_dump($response->status);

$result = $mj->get(Resources::$Messagesentstatistics, ['id' => $msg_id]);
    //var_dump($result);
   // var_dump($result);
    $datas = $result->getData();
   // var_dump($datas);
    var_dump($datas[0]["Status"]);
?>