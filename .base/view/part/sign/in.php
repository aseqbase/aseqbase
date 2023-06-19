<?php
MODULE("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
if(isset($_REQUEST["username"])) $mod->Action();
else {
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