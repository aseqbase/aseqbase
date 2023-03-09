<?php
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->Content = function() {
    PART("content");
};
$templ->Draw();
?>
