<?php
use MiMFa\Library\Convert;
use MiMFa\Library\HTML;
echo "<center class='page'>";
try{
    try{ \_::$Back->User->ReceiveActivationEmail();}
    catch(\Exception $ex){ doValid(function($signature) {\_::$Back->User->Find($signature);}, \Req::Receive("Signature") ); }
    if(($res = \_::$Back->User->ReceiveActivationLink()) != null):
        if($res == true){
            echo Html::Success("Dear ".\_::$Back->User->TemporaryName.", Your account activated successfully!");
            part("access" );
        }
        else {
            echo Html::Error("Your account is not activate!");
            part("access" );
        }
    elseif(\_::$Back->User->SendActivationEmail()):
        echo Html::Success("An activation email sent successfully to your account ('".Convert::ToSecret(\_::$Back->User->TemporaryEmail,"*",2,5)."')! Please check your email and click on the link.");
            part("access" );
    else:
        echo Html::Error("There occoured a problem!")."
            <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
    endif;
}catch (\Exception $ex){
    echo Html::Error($ex)."
            <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
}
echo "</center>";
?>