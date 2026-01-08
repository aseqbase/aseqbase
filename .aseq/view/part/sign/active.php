<?php
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;

try {
    $activationTokenKey = "at";
    $email = received("Email");
    if (!$email && $email = received("Signature"))
        $email = \_::$User->GetValue("Email", $email);
    $sign = \_::$User->DecryptToken($activationTokenKey, received($activationTokenKey));
    if (
        $email && \_::$User->SendTokenEmail(
            \_::$Front->SenderEmail,
            $email ?? \_::$User->TemporaryEmail,
            "Account Activation Request",
            'Hello dear $NAME,<br><br>
We received an account activation request on $HOSTLINK for $EMAILLINK.<br>
Thank you for registration, This email address is associated with an account but is not activated yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link to active your account!<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK',
            "CLICK ON THIS LINK",
            \_::$User->ActiveHandlerPath,
            $activationTokenKey
        )
    ) {
        success("An activation email sent successfully to your account ('" . Convert::ToSecret(\_::$User->TemporaryEmail, "*", 2, 5) . "')! Please check your email and click on the link.");
        part("access");
    } elseif (empty($sign)) {
        error("Your account is not activate!");
        part("access");
    } elseif (
        received($activationTokenKey) && \_::$User->DataTable->Update(
            "`Signature`=:Signature",
            [
                ":Signature" => $sign,
                ":Status" => \_::$User->ActiveStatus
            ]
        )
    ) {
        success("Dear '" . \_::$User->TemporaryName . "', Your account activated successfully!");
        part("access");
    } else
        error("A problem is occoured here!") .
            Struct::Form(
                [
                    Struct::Field("Email", "Email", $email, attributes: ["wrapper" => ["class" => "be flex justify middle", "style" => "gap:var(--size-0);"]]),
                    Struct::SubmitButton("submit", __(["Try again", ", ", "Send the 'activation email'!"]), ["class" => "main"])
                ],
                \_::$User->ActiveHandlerPath
                ,
                attributes: [
                    "class" => "be flex middle center justify vertical",
                    "style" => "gap:var(--size-0); padding:var(--size-0);"
                ]
            );
} catch (\Exception $ex) {
    error($ex) .
        Struct::Form(
            [
                Struct::Field("Email", "Email", $email, attributes: ["wrapper" => ["class" => "be flex justify middle", "style" => "gap:var(--size-0);"]]),
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