<?php
\_::$User->Authorize = function(){
    eraseResponse();
    return view("part",["Name"=>\_::$User->InHandlerPath]);
};