<?php
use MiMFa\Module\PaymentForm;
$payment = between(\_::$INFO->PaymentContent,\_::$INFO->PaymentPath);
if(!isValid($payment)) return;
MODULE("PaymentForm");
$module = new PaymentForm($payment);
$module->Draw();
?>