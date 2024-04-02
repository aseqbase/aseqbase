<?php
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
if(!isValid($path)){
    if(\_::$DIRECTION === "home" || isEmpty(\_::$DIRECTION)){
        TEMPLATE("Main");
        $templ = new \MiMFa\Template\Main();
        $templ->Content = function(){
            PAGE("home", defaultName:"404");
        };
    }
    else {
        \_::$DIRECTION = "post/".\_::$DIRECTION;
        VIEW("post");
    }
} else {
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() use($path){
        PAGE(normalizePath($path), default:function(){
            {
                \_::$DIRECTION = "post/".\_::$DIRECTION;
                VIEW("post");
            }
        });
    };
    $templ->Draw();
}
?>