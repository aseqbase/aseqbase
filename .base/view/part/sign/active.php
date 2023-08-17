<?php
use MiMFa\Library\Convert;
use MiMFa\Library\HTML;
echo "<center class='page'>";
try{
    try{ \_::$INFO->User->ReceiveActivationEmail();}
    catch(\Exception $ex){ doValid(function($signature) {\_::$INFO->User->Find($signature);},$_REQUEST,"Signature"); }
    if(($res = \_::$INFO->User->ReceiveActivationLink()) != null):
        if($res == true){
            echo HTML::Success("Dear ".\_::$INFO->User->TemporaryName.", Your account activated successfully!");
            PART("access");
        }
        else {
            echo HTML::Error("Your account is not activate!");
            PART("access");
        }
    elseif(\_::$INFO->User->SendActivationEmail()):
        echo HTML::Success("An activation email sent successfully to your account ('".Convert::ToSecret(\_::$INFO->User->TemporaryEmail,"*",2,5)."')! Please check your email and click on the link.");
            PART("access");
    else:
        echo HTML::Error("There occoured a problem!")."
            <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
    endif;
}catch (\Exception $ex){
    echo HTML::Error($ex)."
            <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
}
echo "</center>";
?>