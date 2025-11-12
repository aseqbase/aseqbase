<?php
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;

try {
    $email = getReceived("Email");
    if (!$email && $email = getReceived("Signature"))
        $email = \_::$User->GetValue("Email", $email);

    if ($email && \_::$User->SendActivationEmail($email)) {
        echo Struct::Success("An activation email sent successfully to your account ('" . Convert::ToSecret(\_::$User->TemporaryEmail, "*", 2, 5) . "')! Please check your email and click on the link.");
        part("access");
    } elseif (getReceived(\_::$User->ActivationTokenKey) && ($res = \_::$User->ReceiveActivationEmail()) != null) {
        if ($res) {
            echo Struct::Success("Dear '" . \_::$User->TemporaryName . "', Your account activated successfully!");
            part("access");
        } else {
            echo Struct::Error("Your account is not activate!");
            part("access");
        }
    } else
        echo Struct::Error("A problem is occoured here!") .
            Struct::Form(
                [
                    Struct::Field("Email", "Email", $email, attributes: ["wrapper"=>["class" => "be flex justify middle", "style" => "gap:var(--size-0);"]]),
                Struct::SubmitButton("submit", __(["Try again",", ","Send the 'activation email'!"]), ["class" => "main"])
                ],
                \_::$User->ActiveHandlerPath
                ,
                attributes: [
                    "class" => "be flex middle center justify vertical",
                    "style" => "gap:var(--size-0); padding:var(--size-0);"
                ]
            );
} catch (\Exception $ex) {
    echo Struct::Error($ex) .
        Struct::Form(
            [
                    Struct::Field("Email", "Email", $email, attributes: ["wrapper"=>["class" => "be flex justify middle", "style" => "gap:var(--size-0);"]]),
                Struct::SubmitButton("submit", "'Try again', 'Send the 'activation email''!", ["class" => "main"])
            ],
            \_::$User->ActiveHandlerPath
            ,
            attributes: [
                "class" => "be flex middle center justify vertical",
                "style" => "gap:var(--size-0); padding:var(--size-0);"
            ]
        );
}
?>