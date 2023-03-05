<?php
use \MiMFa\Library\DataBase;
MODULE("PrePage");
$module= new MiMFa\Module\PrePage();
$module->Title = "Guide";
$module->Draw();
?>
<div class="container">
    <div class="row">
        <div class="side-image col-md-4" style="background-image: url('/file/logo/logo.png')"></div>
        <div class="col-md-8">
            <?php
            $arr = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Guide");
            if(count($arr)>0)
                foreach ($arr as $k=>$value)
                    echo (isValid($value["Content"])?__($value["Content"]):"")
                    .(isValid($value["Path"])?"<a href='".$value["Path"]."' class='btn btn-lg btn-block ".$value["Class"]."' data-aos='fade-left'>".__($value["Title"])."</a><br />":"");
            ?>
        </div>
    </div>
</div>