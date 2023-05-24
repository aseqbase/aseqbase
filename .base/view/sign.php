<?php
$path = \_::$DIRECTION;
if(isValid($path)){
    $p = strtolower(implode("/", array_slice(explode("/",\_::$DIRECTION),1)));
    switch($p){
        case "in":
		    MODULE("SignInForm");
		    $mod = new \MiMFa\Module\SignInForm();
		    $mod->InternalMethod = "get";
            //print_r($_REQUEST);
			if(isset($_REQUEST["username"]))
		        $mod->Action();
		    else {
                TEMPLATE("Main");
                $templ = new \MiMFa\Template\Main();
                $templ->Content = function() use($path, $mod){
                    echo "<div class='page'>";
                    $mod->Draw();
                    echo "</div>";
                };
                $templ->Draw();
            }
        break;

        case "up":
		    MODULE("SignUpForm");
		    $mod = new \MiMFa\Module\SignUpForm();
		    $mod->InternalMethod = "get";
            //print_r($_REQUEST);
			if(isset($_REQUEST["username"]))
		        $mod->Action();
		    else {
                TEMPLATE("Main");
                $templ = new \MiMFa\Template\Main();
                $templ->Content = function() use($path, $mod){
                    echo "<div class='page'>";
                    $mod->Draw();
                    echo "</div>";
                };
                $templ->Draw();
            }
        break;

        case "active":
		    PART("sign/active");
        break;

        default:
            TEMPLATE("Main");
            $templ = new \MiMFa\Template\Main();
            $templ->Content = function() use($path){
                echo "<div class='page'>";
                PART($path);
                echo "</div>";
            };
            $templ->Draw();
        break;
    }
}
else VIEW("404");
?>