<?php
use \MiMFa\Library\User;
use \MiMFa\Library\Router;
$isuser = auth(\_::$Config->UserAccess);
$data = $data??[];
(new Router())
    ->On("sign/in")->Get(function() use($data) { return view("part", ["Name" => "sign/in",...\Req::ReceiveGet(),...$data]);})
    ->On("sign/up")->Get(function() use($data) { return view("part", ["Name" => "sign/up",...\Req::ReceiveGet(),...$data]);})
    ->On("sign/recover")->Get(function() use($data) { return  view("part", ["Name" => "sign/recover",...\Req::ReceiveGet(),...$data]);})
    ->On("sign/active")->Get(function() use($data) { return  view("part", ["Name" => "sign/active",...\Req::ReceiveGet(),...$data]);})
    ->if($isuser)
        ->On("sign/out")
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
        ->On()->Get(fn() => view("part", ["Name" => \Req::$Direction]))
    ->else(!$isuser && \Req::Receive("Signature"))
        ->On("sign/up")->Default(fn () => compute("sign/up"))
    ->else(!$isuser)
        ->On("sign/in")->Default(function () {
            if (compute("sign/in"))
                \Res::Reload();
        })
        ->On("sign/recover")->Default(function () {
            if (compute("sign/recover"))
                \Res::Load(User::$InHandlerPath);
        })
    ->else()
        ->On("sign/edit")->Default(function () {
            if (compute("sign/edit"))
                \Res::Reload();
        })
    ->else(!isEmpty(\Req::$Direction))
        ->On()->Default(fn() => compute(\Req::$Direction))
    ->else()
        ->On()->Default(fn() => view("part", ["Name" => "access"]))
    ->Handle();
?>