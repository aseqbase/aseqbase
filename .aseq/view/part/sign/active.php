<?php
use MiMFa\Library\Html;
use MiMFa\Library\Convert;

try {
    $email = getReceived("Email");
    if(!$email && $email = getReceived("Signature")) $email = \_::$User->GetValue("Email", $email);

    if($email && \_::$User->SendActivationEmail($email)) {
        echo Html::Success("An activation email sent successfully to your account ('" . Convert::ToSecret(\_::$User->TemporaryEmail, "*", 2, 5) . "')! Please check your email and click on the link.");
        part("access");
    } elseif (getReceived(\_::$User->ActivationTokenKey) && ($res = \_::$User->ReceiveActivationEmail()) != null){
        if ($res) {
            echo Html::Success("Dear " . \_::$User->TemporaryName . ", Your account activated successfully!");
            part("access");
        } else {
            echo Html::Error("Your account is not activate!");
            part("access");
        }
    } else
    echo Html::Error("A problem is occoured here!") . 
        HTML::Form([
            HTML::Field("Email", "Email", $email),
            HTML::SubmitButton("Try to send the activation email again!", $email)
        ],\_::$User->ActiveHandlerPath);
} catch (\Exception $ex) {
    echo Html::Error($ex) . 
    HTML::Form([
        HTML::Field("Email", "Email", $email),
        HTML::SubmitButton("Try to send the activation email again!", $email)
    ],\_::$User->ActiveHandlerPath);
}
?>