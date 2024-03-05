<?php
use MiMFa\Library\HTML;
echo HTML::Page(function(){
    echo "<center>";
    if(!getAccess(\_::$CONFIG->UserAccess)):
        echo HTML::Success("You signed out successfully!");
        PART("access");
    elseif(\_::$INFO->User->SignOut()):
        echo HTML::Success("You signed out successfully!");
        PART("access");
        load(\_::$HOST);
    else:
        echo HTML::Error("There a problem is occured in signing out!");
        PART("access");
    endif;
    echo "</center>";
});
?>