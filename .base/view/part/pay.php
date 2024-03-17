<?php
if(!isValid(\_::$INFO->Payment)) return;
$jsd = json_decode(utf8_decode(\_::$INFO->Payment??"{}"), flags:JSON_OBJECT_AS_ARRAY);
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