<?php
module("BarMenu");
$module = new \MiMFa\Module\BarMenu();
$module->Items = \_::$Info->Shortcuts;
pod($module, $data);
$module->Render();