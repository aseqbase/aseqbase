<?php
try{
    if(($res = \_::$INFO->User->ReceiveActivationLink()) != null):
        if($res == true){
            echo "<div class='result success'>".__("Dear ".\_::$INFO->User->TemporaryName.", Your account activated successfully!")."</div>";
            PART("access");
        }
        else {
            echo "<div class='result error'>".__("Your account is not activate!")."</div>";
            PART("access");
        }
    elseif(\_::$INFO->User->SendActivationEmail()):
        echo "<div class='result success'>".__("An activation email sent successfully to your account ('".\_::$INFO->User->TemporaryEmail."')! Please check your email and click on the link.")."</div>";
            PART("access");
    else:
        echo "<div class='result error'>".__("There occoured a problem!")."</div>
            <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
    endif;
}catch (\Exception $ex){
    echo "<div class='result error'>".__($ex->getMessage())."</div>
        <a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
}
?>