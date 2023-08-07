<?php
MODULE("ResetPasswordForm");
$mod = new \MiMFa\Module\ResetPasswordForm();
if(isset($_REQUEST["username"]) || isset($_REQUEST["password"])) $mod->Action();
else {
    $mod->Title = "Remember Password";
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