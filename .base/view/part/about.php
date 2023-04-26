<?php
    MODULE("Part");
    MODULE("Separator");
    $module = new \MiMFa\Module\Part();
    $module->Style = new \MiMFa\Library\Style();
    $separator = new \MiMFa\Module\Separator();
    $separator->Style = new \MiMFa\Library\Style();
    
    $module->Style->TextAlign = "justify";
    $module->Style->Padding = "calc(2*var(--Size-5))";
    $module->Style->BackColor =
    $separator->Style->BackColor = "var(--Color-5)";
    $module->Style->ForeColor =
    $separator->Style->ForeColor = "#fffe";
    $separator->Style->BorderRadius = "0px 0px 100% 100%";
    $separator->MergeTop = true;
    $separator->MergeBottom = true;
    $module->Image = \_::$INFO->FullLogoPath??\_::$INFO->LogoPath;
    $module->Title = \_::$INFO->FullName;
    $module->Description = \_::$INFO->FullSlogan;
    $module->Draw();
    $separator->Draw();

    $module->Set_Defaults();
    $separator->Set_Defaults();
    $module->Style->BackColor =
    $separator->Style->BackColor = "var(--Color-4)";
    $separator->MergeBottom = false;
    $module->Title = "Who We Are?";
    $module->Image = null;
    $module->Description = \_::$INFO->FullDescription;
    $module->Draw();
    $separator->Draw();
?>