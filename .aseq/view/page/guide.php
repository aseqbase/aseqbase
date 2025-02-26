<?php
module("PrePage");
$module= new MiMFa\Module\PrePage();
$module->Title = "Guide";
$module->Render();
?>
<div class="container">
    <div class="row">
        <div class="side-image col-md-4" style="background-image: url('<?php echo \MiMFa\Library\Local::GetUrl("/asset/logo/logo.png") ?>')"></div>
        <div class="col-md-8">
            <?php part("guide"); ?>
        </div>
    </div>
</div>