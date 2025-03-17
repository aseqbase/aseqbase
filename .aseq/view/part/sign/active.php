<?php
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\User;
try {
    $email = \Req::Receive("Email");
    if(!$email && $email = \Req::Receive("Signature")) $email = \_::$Back->User->GetValue("Email", $email);

    if($email && \_::$Back->User->SendActivationEmail($email)) {
        echo Html::Success("An activation email sent successfully to your account ('" . Convert::ToSecret(\_::$Back->User->TemporaryEmail, "*", 2, 5) . "')! Please check your email and click on the link.");
        part("access");
    } elseif (\Req::Receive(User::$ActivationTokenKey) && ($res = \_::$Back->User->ReceiveActivationEmail()) != null){
        if ($res) {
            echo Html::Success("Dear " . \_::$Back->User->TemporaryName . ", Your account activated successfully!");
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
        ],User::$ActiveHandlerPath);
} catch (\Exception $ex) {
    echo Html::Error($ex) . 
    HTML::Form([
        HTML::Field("Email", "Email", $email),
        HTML::SubmitButton("Try to send the activation email again!", $email)
    ],User::$ActiveHandlerPath);
}
?>