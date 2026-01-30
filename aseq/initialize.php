<?php
\_::$User->Authorize = function(){
    eraseResponse();
    return view("part",["Name"=>\_::$User->InHandlerPath]);
};


// --- CORS / Preflight & Method-Override helper (drop in early in request pipeline) ---
\_::$Front->Headers["Access-Control-Allow-Origin"] = ($_SERVER['HTTP_ORIGIN'] ?? '*');
\_::$Front->Headers["Access-Control-Allow-Credentials"] = "true";
\_::$Front->Headers["Access-Control-Allow-Methods"] = "GET, POST, PUT, PATCH, DELETE, OPTIONS";
\_::$Front->Headers["Access-Control-Allow-Headers"] = "X-Custom-Method, X-Custom-Method-Override, X-HTTP-Method-Override, Content-Type, X-Requested-With, Authorization";
// if (getMethodName() === "GET" && \_::$Front->DetectMode && is_null(\_::$Front->SwitchMode)) {
//     request(
//         "window.matchMedia('(prefers-color-scheme: dark)').matches ? -1 : 1",
//         function ($mode) {
//             $cmode = \_::$Front->GetMode();
//             if (($mode > 0 && $cmode < 0) || ($mode < 0 && $cmode > 0)) {
//                 setMemo(\_::$Front->SwitchRequest, true);
//                 \_::$Front->SwitchMode = true;
//             }
//         }
//     );
// }

// \_::$Router->On("public|asset/.*\.(js|css|flv|ico|pdf|avi|mov|ppt|doc|mp3|wmv|wav|gif|jpg|jpeg|png|swf|webm|webp)")
//     ->Default(fn() => deliverFile(normalizePath(\_::$User->Request)));
\_::$Router->On("contents")->Default("contents");
\_::$Router->On("content")->Default("content");
\_::$Router->On("posts")->Default("posts");
\_::$Router->On("post")->Default("post");
\_::$Router->On("newses")->Default("newses");
\_::$Router->On("news")->Default("news");
\_::$Router->On("forums")->Default("forums");
\_::$Router->On("forum")->Default("forum");
\_::$Router->On("(categor(ies|y))|(cats?)")->Default("category");
\_::$Router->On("tags?")->Default("tag");
\_::$Router->On("query")->Default("query");
\_::$Router->On("search")->Default("search");
\_::$Router->On("sign")->Default("sign");
\_::$Router->On("user")->Default("user");
\_::$Router->On("group")->Default("group");
\_::$Router->On("contact/.+")->Default("contact");