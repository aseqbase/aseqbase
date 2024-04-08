<?php
use \MiMFa\Library\Convert;
if(!isValid(\_::$INFO->Payment)) return;
$jsd = Convert::FromJSON(utf8_decode(\_::$INFO->Payment??"{}"));
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