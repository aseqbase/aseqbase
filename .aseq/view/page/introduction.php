<div class="page page-introduction">
    <?php
    module("PrePage");
    $module = new MiMFa\Module\PrePage();
    $module->Title = "Introduction";
    swap($module, $data);
    $module->Render();
    component("Icons");
    echo MiMFa\Component\Icons::GetStyle(".caselist");
    module("Image");
    $module = new MiMFa\Module\Image();
    $module->MinHeight = "6vh";
    echo $module->GetStyle();
    ?>
    <style>
        .caselist .fa {
            width: 100%;
            text-align: center;
            font-size: var(--size-5);
        }
    </style>
    <div class="center-lead container">
        <?php if (isValid(\_::$Info, "IntroductionVideoPath")) {
            module("EmbededYoutube");
            $ytm = new MiMFa\Module\EmbededYoutube();
            $ytm->Source = getValid(\_::$Info, "IntroductionVideoPath");
            $ytm->Render();
        } ?>
        <div class="description content">
            <?php echo \_::$Info->Description; ?>
        </div>
        <div class="ownerdescription content">
            <?php echo \_::$Info->OwnerDescription; ?>
        </div>
        <div class="fulldescription content">
            <?php echo \_::$Info->FullDescription; ?>
        </div>
        <div class="services content row">
            <?php
            foreach (\_::$Info->Services as $value) {
                $module->Image = get($value, "Image");
                $p = get($value, "Path");
                $isp = isValid($p);
                ?>
                <div class="col-md">
                    <?php
                    if ($isp)
                        echo "<a href=\"$p\">";
                    if ($v = get($value, "Icon"))
                        echo "<i class='$v'></i>";
                    if (isValid($module->Image))
                        $module->ReRender();
                    ?>
                    <h3 class="title">
                        <?php echo findBetween($value, "Title", "Name"); ?>
                    </h3>
                    <?php if ($isp)
                        echo "</a>"; ?>
                    <div class="description">
                        <?php echo get($value, "Description"); ?>
                        <?php echo findBetween($value, "Button", "More"); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>