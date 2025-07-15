<?php
module("BarMenu");
$module = new \MiMFa\Module\BarMenu();
$module->Items = \_::$Info->Shortcuts;
swap($module, $data);
$module->Render();