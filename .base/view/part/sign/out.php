<?php
if(\_::$INFO->User->SignOut()):
    echo "<center><div class='success'>".__("You signed out successfully!")."</div>";
    PART("access");
    echo "</center>";
else:
    echo "<center><div class='error'>".__("You signed out successfully!")."</div>";
    PART("access");
    echo "</center>";
endif;
?>