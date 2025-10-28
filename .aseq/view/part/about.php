<?php
module("Part");
module("Separator");
$module = new \MiMFa\Module\Part();
$module->Style = new \MiMFa\Library\Style();
$separator = new \MiMFa\Module\Separator();
$separator->Style = new \MiMFa\Library\Style();

$module->Style->TextAlign = "justify";
$module->Style->Padding = "calc(2*var(--size-5)) var(--size-5)";
$module->Style->BackgroundColor =
    $separator->Style->BackgroundColor = "var(--color-white)";
$module->Style->Color =
    $separator->Style->Color = "#fffe";
$separator->Style->BorderRadius = "0px 0px 100% 100%";
$separator->MergeTop = true;
$separator->MergeBottom = true;
$module->Image = \_::$Info->FullLogoPath ?? \_::$Info->LogoPath;
$module->Title = \_::$Info->FullName;
$module->Description = \_::$Info->FullSlogan;
$module->Render();
$separator->Render();

$module->Set_Defaults();
$separator->Set_Defaults();
$module->Style->BackgroundColor =
    $separator->Style->BackgroundColor = "var(--color-white)";
$separator->MergeBottom = false;
$module->Title = "Who We Are?";
$module->Image = null;
$module->Description = \_::$Info->FullDescription;
$module->Render();
pod($module, $data);
$separator->Render();
?>