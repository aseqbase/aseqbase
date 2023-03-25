<?php
    MODULE("PagePart");
    $module = new \MiMFa\Module\PagePart();
    $module->Image = \_::$INFO->FullLogoPath??\_::$INFO->LogoPath;
    $module->Title = \_::$INFO->FullName;
    $module->Description = \_::$INFO->FullSlogan;
    $module->Content = "<div>".\_::$INFO->FullDescription."<div>";
    $module->BottomSeparatorContent = "<hr>";
    $module->Draw();
?>