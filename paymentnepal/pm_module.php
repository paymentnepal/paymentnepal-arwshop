<?php
// Copyright (c) Forcode

 if(! defined('SYS_LOADER')){
 die();
 }


global $engineconf, $rficb, $pmmod_conf;
$engineconf = engine_conf();
 if(! file_exists(PM_MODULES_DIR."/paymentnepal/pmmod_conf.php")){
 die('Платежный модуль не настроен. Описание настройки этого модуля в файле '.PM_MODULES_DIR."/paymentnepal/README.TXT");
 }
require_once(PM_MODULES_DIR."/paymentnepal/pmmod_conf.php");
require_once(PM_MODULES_DIR."/paymentnepal/paymentnepal.php");
$paymentnepal=new paymentnepal;
$paymentnepal->loadlng();

 switch($_GET['act']){

 case 'result':
 echo $paymentnepal->payment_result();
 break;

 case 'fail':
 echo $paymentnepal->payment_fail();
 break;
 
 case 'success':
 echo $paymentnepal->payment_success();
 break;

 default:
 $order_id = get_order_id();
  if($order_id){
  echo $paymentnepal->payment_form($order_id);
  }
 }

?>
