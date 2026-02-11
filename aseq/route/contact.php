<?php
$data = $data ?? [];
(new Router())
    ->Default(fn() => compute(\_::$Address->UrlRoute, $data))
->Handle();