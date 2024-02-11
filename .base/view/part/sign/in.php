<?php
MODULE("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
if(isset($_REQUEST["Signature"])) $mod->Action();
else {
    echo "<div class='page'>";
    $mod->Title = "Sign In";
    $mod->Image = "sign-in";
    $mod->Class = "container";
    $mod->ContentClass = "col-lg-5";
    $mod->Draw();
    echo "</div>";
}
?>