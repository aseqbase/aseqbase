<?php
use \MiMFa\Library\Convert;
if (!isValid(\_::$Info->Payment))
    return;
$jsd = is_string(\_::$Info->Payment) ? Convert::FromJson(mb_convert_encoding(\_::$Info->Payment ?? "{}", "UTF-8")) : Convert::ToSequence(\_::$Info->Payment);
if (isEmpty($jsd))
    return;
module("PaymentForm");
$ts = array();
foreach ((is_array(first($jsd)) ? $jsd : [$jsd]) as $key => $value) {
    $t = new MiMFa\Module\Transaction();
    foreach ($value as $k => $v)
        $t->$k = $v;
    $ts[] = $t;
}
$module = new MiMFa\Module\PaymentForm(...$ts);
swap($module, $data);
$module->Render();
?>