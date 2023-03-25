<?php
MODULE("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Introduction";
$module->Draw();
COMPONENT("FontAwesome");
$module = new MiMFa\Component\FontAwesome();
$module->EchoStyle(".caselist");
MODULE("Image");
$module = new MiMFa\Module\Image();
$module->MinHeight = "6vh";
$module->EchoStyle();
?>
<style>
    .caselist .fa{
		width: 100%;
		text-align: center;
		font-size: var(--Size-5);
	}
    .lead, .lead .content{
		text-align: justify;
		padding: var(--Size-0) 0px;
	}
    .lead li{
    		content: initial;
		text-indent: var(--Size-2);
	}
</style>
<div class="lead center-lead container">
    <?php if(isValid(\_::$INFO,"IntroductionVideoPath")){
              MODULE("EmbededYoutube");
              $ytm = new MiMFa\Module\EmbededYoutube();
              $ytm->Source = \_::$INFO->IntroductionVideoPath;
              $ytm->Draw();
          }?>
    <div class="description content">
        <?php echo \_::$INFO->Description; ?>
    </div>
    <div class="ownerdescription content">
        <?php echo \_::$INFO->OwnerDescription; ?>
    </div>
    <div class="fulldescription content">
        <?php echo \_::$INFO->FullDescription; ?>
    </div>
    <div class="services content row">
        <?php
        foreach (\_::$INFO->Services as $value)
        {
            $module->Image = getValid($value,"Image");
            $p = getValid($value,"Path")??getValid($value,"Link");
            $isp = isValid($p);
        ?>
        <div class="col-md">
            <?php
            if($isp) echo "<a href=\"$p\">";
            if(isValid($value,"Icon")) echo "<i class='".$value["Icon"]."'></i>";
            if(isValid($module->Image)) $module->ReDraw();
            ?>
            <h3 class="title">
                <?php echo getValid($value,"Title")??getValid($value,"Name"); ?>
            </h3>
            <?php if($isp) echo "</a>"; ?>
            <div class="description">
                <?php echo getValid($value,"Description"); ?>
                <?php echo getValid($value,"Button")??getValid($value,"More"); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>