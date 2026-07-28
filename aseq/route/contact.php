<?php
$data = $data ?? [];
(new Router())
    ->Get(view(\_::$Front->DefaultViewName))
    ->Default(fn() => compute(\_::$Address->UrlRoute, $data))
->Handle();