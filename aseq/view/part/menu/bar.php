<?php
module("BarMenu");
$module = new \MiMFa\Module\BarMenu();
$module->Items = \_::$Front->Shortcuts;
pod($module, $data);
$module->Render();