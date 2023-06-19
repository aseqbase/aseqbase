<?php
MODULE("SignUpForm");
$mod = new \MiMFa\Module\SignUpForm();
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