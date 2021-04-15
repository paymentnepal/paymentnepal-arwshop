<?php
// Copyright (c) Forcode

class paymentnepal{

var $is_debug = true;

function payment_form($order_id){
global $language, $engineconf, $pmmod_conf;
$order_data = get_order_data($order_id);

 if(is_paid_order($order_id)){
 return "<h3>$language[paid_successfully]</h3>";
 }

return <<<HTMLDATA
$language[final_total] {$order_data[order][final_total_pc]} {$order_data[order][currency_brief]}
<h3>$language[go_to_payment]</h3>
$language[after_submit]<br>
<form method="POST" accept-charset="$engineconf[charset]" action="https://pay.paymentnepal.com/alba/input/">
  <input type="hidden" name="key" value="$pmmod_conf[key]" />
  <input type="hidden" name="cost" value="{$order_data[order][final_total_pc]}" />
  <input type="hidden" name="name" value="$language[order_n] {$order_data[order][orderid]}" />
  <input type="hidden" name="default_email" value="{$order_data[order][email]}" />
  <input type="hidden" name="order_id" value="{$order_data[order][orderid]}" />
  <input type="submit" value="$language[continue]" /><br>
</form>
HTMLDATA;
}


function payment_success(){
global $language, $pmmod_conf;
$order_data = get_order_data($_GET['order_id']);
$order_status_name = paid_status_name();
$order_id = intval($order_data['order']['orderid']);

$err='';

$my_crc = $this->control_signature($_GET);

 if($my_crc !== $_GET['check']){
 $err.='Error: Invalid hash.<br>';
 }

 if($_GET['system_income'] < $order_data['order']['final_total_pc']){
 $err.='Error: Invalid sum.<br>';
 }

 if($err){
 return "<h3 class=\"red\">$language[payment_not_made]</h3><p class=\"red\"><b>$language[payment_errors]:</b><br>$err";
 }
 else{
 return <<<HTMLDATA
<h3>$language[paid_successfully]</h3>
$language[thank_you]!<br>
$language[order_number]: {$order_data[order][orderid]}<br>
$language[order_status]: $order_status_name<br>
$language[paid_sum]: {$order_data[order][final_total_pc]} {$order_data[order][currency_brief]}<br><br>
$language[order_is_sended]<br><br>
HTMLDATA;
 }
}


function payment_fail(){
global $language;
return "<h3>$language[payment_not_made]</h3>";
}


function payment_result(){
global $pmmod_conf;
$order_data = get_order_data($_POST['order_id']);
$order_id = intval($order_data['order']['orderid']);

$err='';

$my_crc = $this->control_signature($_POST);

 if($my_crc !== $_POST['check']){
 return $this->debug_info('Invalid hash.');
 }

 if($_POST['system_income'] < $order_data['order']['final_total_pc']){
 return $this->debug_info('Invalid sum.');
 }

set_order_paid($order_id);
return $this->debug_info('OK');
}


function control_signature($in_arr){
global $pmmod_conf;
$in_data = array(  'tid'            =>  $in_arr['tid'],
                   'name'           =>  $in_arr['name'], 
                   'comment'        =>  $in_arr['comment'],
                   'partner_id'     =>  $in_arr['partner_id'],
                   'service_id'     =>  $in_arr['service_id'],
                   'order_id'       =>  $in_arr['order_id'],
                   'type'           =>  $in_arr['type'],
                   'partner_income' =>  $in_arr['partner_income'],
                   'system_income'  =>  $in_arr['system_income'],
                   'test'           =>  $in_arr['test']
                );
return md5(implode('', array_values($in_data)) . $pmmod_conf['secret_key']);
}


function debug_info($error){
 if($this->is_debug){
 return $error;
 }
return '';
}


function loadlng($admin = false){
global $engineconf, $language;
 if($admin){
 $admin_dir = 'admin/';
 }
 else{
 $admin_dir = '';
 }
$default_language = $engineconf['lang'];
 if(! file_exists(PM_MODULES_DIR."/paymentnepal/$admin_dir$default_language".'_lang.lng')){
 $default_language = 'eng';
 }
 if(! file_exists(PM_MODULES_DIR."/paymentnepal/$admin_dir$default_language".'_lang.lng')){
 echo "Invalid language!";
 return false;
 }
$fh=fopen(PM_MODULES_DIR."/paymentnepal/$admin_dir$default_language".'_lang.lng', "r") or die('Can\'t load language file!');
 while(! feof($fh)){
 $language_str=explode('=', fgets($fh, 2048), 2);
 $language_str[0]=trim($language_str[0]);
 if($language_str[0]){$language["$language_str[0]"]=trim($language_str[1]);}
 }
fclose($fh);
return true;
}


}
?>
