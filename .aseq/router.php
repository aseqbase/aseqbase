<?php
\_::$Back->Router->Route("(public|asset|(view/(script|style)))/.*\.(js|css|flv|ico|pdf|avi|mov|ppt|doc|mp3|wmv|wav|gif|jpg|jpeg|png|swf|webm|webp)")
    ->Default(fn()=>\Res::SendFile(normalizePath(\Req::$Request)));
\_::$Back->Router->Route("posts?")->Default("post");
\_::$Back->Router->Route("forums?")->Default("forum");
\_::$Back->Router->Route("(categor(ies|y))|(cats?)")->Default("category");
\_::$Back->Router->Route("tags?")->Default("tag");
\_::$Back->Router->Route("query")->Default("query");
\_::$Back->Router->Route("search")->Default("search");
\_::$Back->Router->Route("sign")->Default("sign");
\_::$Back->Router->Route("user")->Default("user");
\_::$Back->Router->Route("group")->Default("group");
\_::$Back->Router->Route()->Default(\_::$Config->DefaultRouteName);
?>