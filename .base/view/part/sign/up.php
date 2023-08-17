<?php
MODULE("SignUpForm");
$mod = new \MiMFa\Module\SignUpForm();
if(isset($_REQUEST["UserName"])) $mod->Action();
else {
    echo "<div class='page'>";
    $mod->Title = "Sign Up";
    $mod->Image = "user";
    $mod->Draw();
    echo "</div>";
}
?>