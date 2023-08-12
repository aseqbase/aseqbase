<?php
use MiMFa\Library\HTML;
MODULE("ResetPasswordForm");
$mod = new \MiMFa\Module\ResetPasswordForm();
if(isset($_REQUEST["username"]) || isset($_REQUEST["password"])) $mod->Action();
else {
    try{
        \_::$INFO->User->ReceiveRecoveryEmail();
        $mod->Description = "Recover account for ".\_::$INFO->User->TemporaryEmail;
    }
    catch(\Exception $ex){
        $mod->Description = "Send recovery email for the acoount";
    }
    $mod->Title = "Account Recovery";
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() use($mod){
        echo "<div class='page'>";
        $mod->Draw();
        echo "</div>";
    };
    $templ->Draw();
}
?>