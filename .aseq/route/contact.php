<?php
$data = $data ?? [];
(new Router())
    ->Default(fn() => compute(\_::$Address->Direction, $data))
->Handle();