<?php
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->Content = function(){
    echo "<div class='page'>";
    if(!ACCESS(1)):
        echo "<center><div class='result success'>".__("You signed out successfully!")."</div>";
        PART("access");
        echo "</center>";
    elseif(\_::$INFO->User->SignOut()):
        echo "<center><div class='result success'>".__("You signed out successfully!")."</div>";
        PART("access");
        echo "</center>";
        load();
    else:
        echo "<center><div class='result error'>".__("There a problem is occured in signing out!")."</div>";
        PART("access");
        echo "</center>";
    endif;
    echo "</div>";
};
$templ->Draw();
?>