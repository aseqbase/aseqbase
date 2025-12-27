<?php
module("Gallery");
$gallery = new MiMFa\Module\Gallery(get($data, "Items"));
$gallery->Render();