<?php
$isuser = auth(\_::$Config->UserAccess);
$data = $data??[];
(new Router())
    ->On("sign/in")->Get(function() use($data) { return view("part", ["Name" => "sign/in",...receiveGet(),...$data]);})
    ->On("sign/up")->Get(function() use($data) { return view("part", ["Name" => "sign/up",...receiveGet(),...$data]);})
    ->On("sign/recover")->Get(function() use($data) { return  view("part", ["Name" => "sign/recover",...receiveGet(),...$data]);})
    ->On("sign/active")->Get(function() use($data) { return  view("part", ["Name" => "sign/active",...receiveGet(),...$data]);})
    ->if($isuser)
        ->On("sign/out")
            ->Get(function () {
                if (compute("sign/out"))
                    load(\User::$InHandlerPath);
                else
                    return view("part", ["Name" => "access"]);
            })
            ->Default(function () {
                if (compute("sign/out"))
                    reload();
                else
                    load(\User::$InHandlerPath);
            })
    ->else(!isEmpty(\_::$Base->Direction))
        ->On()->Get(fn() => view("part", ["Name" => \_::$Base->Direction]))
    ->else(!$isuser && receive("Signature"))
        ->On("sign/up")->Rest(fn () => compute("sign/up"))
    ->else(!$isuser)
        ->On("sign/in")->Rest(function () {
            if (compute("sign/in"))
                reload();
        })
        ->On("sign/recover")->Rest(function () {
            if (compute("sign/recover"))
                load(\User::$InHandlerPath);
        })
    ->else()
        ->On("sign/edit")->Rest(function () {
            if (compute("sign/edit"))
                reload();
        })
    ->else(!isEmpty(\_::$Base->Direction))
        ->On()->Default(fn() => response(compute(\_::$Base->Direction)))
    ->else()
        ->On()->Default(fn() => view("part", ["Name" => "access"]))
    ->Handle();