<?php
use MiMFa\Library\Html;
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Payment";
echo Html::Page($module->ToString(), ["class"=> "page-pay"]);
part("pay", $data);
?>