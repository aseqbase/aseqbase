<?php
if(\_::$INFO->User->SendActivationEmail()):
    echo "<center>
        <div class='success'>".__("An activation email sent successfully to your account ('".\_::$INFO->User->Email."')! Please check your email and click on the link.")."</div>";
        PART("access");
    echo "</center>";
else:
    echo "<center>
        <div class='error'>".__("There occoured a problem!")."</div>
        <a href='".\_::$INFO->User->ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>";
    echo "</center>";
endif;
?>