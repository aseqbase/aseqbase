<?php
use \MiMFa\Library\User;
use \MiMFa\Library\Router;
$isuser = auth(\_::$Config->UserAccess);
(new Router())
    ->Route("sign/in")->Get(fn() => view("part", ["Name" => "sign/in"]))
    ->Route("sign/up")->Get(fn() => view("part", ["Name" => "sign/up"]))
    ->Route("sign/recover")->Get(fn() => view("part", ["Name" => "sign/recover"]))
    ->Route("sign/active")->Get(fn() => view("part", ["Name" => "sign/active"]))
    ->if($isuser)
        ->Route("sign/out")
        ->Get(function () {
            if (compute("sign/out"))
                \Res::Load(User::$InHandlerPath);
            else
                return view("part", ["Name" => "access"]);
        })
        ->Default(function () {
            if (compute("sign/out"))
                \Res::Reload();
            else
                \Res::Load(User::$InHandlerPath);
        })
    ->else(!isEmpty(\Req::$Direction))
        ->Route->Get(fn() => view("part", ["Name" => \Req::$Direction]))
    ->else(!$isuser && \Req::Receive("Signature"))
        ->Route("sign/up")->Default(fn () => compute("sign/up"))
    ->else(!$isuser)
        ->Route("sign/in")->Default(function () {
            if (compute("sign/in"))
                \Res::Reload();
        })
        ->Route("sign/recover")->Default(function () {
            if (compute("sign/recover"))
                \Res::Load(User::$InHandlerPath);
        })
    ->else
        ->Route("sign/edit")->Default(function () {
            if (compute("sign/edit"))
                \Res::Reload();
        })
    ->else(!isEmpty(\Req::$Direction))
        ->Route->Default(fn() => compute(\Req::$Direction))
    ->else
        ->Route->Default(fn() => view("part", ["Name" => "access"]))
    ->Handle();
?>