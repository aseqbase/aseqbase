<?php
    $payment = between(\_::$INFO->PaymentContent,\_::$INFO->PaymentPath);
    if(!isValid($payment)) return;
    MODULE("PaymentForm");
    $module = new MiMFa\Module\PaymentForm();
    $module->Content = $payment;
    $module->Draw();
?>