<?php
MODULE("PrePage");
$module= new MiMFa\Module\PrePage();
$module->Title = "Guide";
$module->Draw();
?>
<div class="container">
    <div class="row">
        <div class="side-image col-md-4" style="background-image: url('<?php \MiMFa\Library\Local::GetUrl("/file/logo/logo.png") ?>')"></div>
        <div class="col-md-8">
            <?php PART("guide"); ?>
        </div>
    </div>
</div>