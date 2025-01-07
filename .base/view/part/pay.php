<?php
use \MiMFa\Library\Convert;
if(!isValid(\_::$INFO->Payment)) return;
$jsd = is_string(\_::$INFO->Payment)? Convert::FromJSON(mb_convert_encoding(\_::$INFO->Payment??"{}", "UTF-8")):Convert::ToSequence(\_::$INFO->Payment);
if(isEmpty($jsd)) return;
MODULE("PaymentForm");
$ts = array();
foreach ((is_array(first($jsd))?$jsd:[$jsd]) as $key=>$value){
    $t = new MiMFa\Module\Transaction();
    foreach ($value as $k=>$v) $t->$k = $v;
    $ts[] = $t;
}
$module = new MiMFa\Module\PaymentForm(...$ts);
$module->Draw();
?>