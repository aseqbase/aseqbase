<?php
if(isset($_REQUEST["username"])) {}
else {
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() use($mod){
        echo "<div class='page'>";

        echo "</form>
        </div>";
    };
    $templ->Draw();
}
?>