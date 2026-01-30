<?php
$data = $data ?? [];
(new Router())
    ->Default(fn() => compute(\_::$User->Direction, $data))
->Handle();