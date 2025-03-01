<?php
use \MiMFa\Library\Router;
$isuser = auth(\_::$Config->UserAccess);
(new Router())
    ->Route("sign/in")->Get(fn()=> view("part", ["Name" => "sign/in"]))
    ->Route("sign/up")->Get(fn()=> view("part", ["Name" => "sign/up"]))
    ->Route("sign/recover")->Get(fn()=> view("part", ["Name" => "sign/recover"]))
    ->if($isuser)
    ->Route("sign/out")
        ->Get(function(){
            if(logic("sign/out")) \Res::Load(\MiMFa\Library\User::$InHandlerPath);
            else return view("part", ["Name" => "access"]);
        })
        ->Default(function(){
            if(logic("sign/out")) \Res::Reload();
            else \Res::Load(\MiMFa\Library\User::$InHandlerPath);
        })
    ->if(!isEmpty(\Req::$Direction))
    ->Route()->Get(fn()=> view("part", ["Name" => \Req::$Direction]))
    ->if(!$isuser && \Req::Receive("username"))
    ->Route("sign/up")->Default(function(){
        if(logic("sign/up")) \Res::Reload();
    })
    ->if(!$isuser)
    ->Route("sign/in")->Default(function(){
        if(logic("sign/in")) \Res::Reload();
    })
    ->Route("sign/recover")->Default(function(){
        if(logic("sign/recover")) \Res::Reload();
    })
    ->else()
    ->Route("sign/edit")->Default(function(){
        if(logic("sign/edit")) \Res::Reload();
    })
    ->if(!isEmpty(\Req::$Direction))
    ->Route()->Default(fn()=> logic(\Req::$Direction))
    ->else()
    ->Route()->Default(fn()=> view("part", ["Name" => "access"]))
    ->Handle();
?>