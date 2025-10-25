<div class="page page-introduction">
    <?php

use MiMFa\Library\Html;

    module("PrePage");
    $module = new MiMFa\Module\PrePage();
    $module->Title = "Introduction";
    dip($module, $data);
    $module->Render();
    component("Icons");
    echo MiMFa\Component\Icons::GetStyle(".caselist");
    module("Image");
    ?>
    <style>
        .page-introduction .services {
            margin-top: var(--size-max);
        }
        .page-introduction .services h3 {
            width: 100%;
            text-align: center;
        }
        .page-introduction .services h3 .icon {
            margin: var(--size-0);
            display: block;
            font-size: var(--size-max);
            width: calc(100% - 2 * var(--size-0));
        }
        .page-introduction .services h3 .image {
            margin: var(--size-0);
            display: block;
            width: calc(100% - 2 * var(--size-0));
        }
        .page-introduction .services h3 .image img {
            height: var(--size-max);
        }
    </style>
    <div class="center-lead container">
        <?php if (isValid(\_::$Info, "IntroductionVideoPath")) {
            module("EmbededYoutube");
            $ytm = new MiMFa\Module\EmbededYoutube();
            $ytm->Source = takeValid(\_::$Info, "IntroductionVideoPath");
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
                $p = get($value, "Path");
                $isp = isValid($p);
                ?>
                <div class="col-md">
                    <h3 class="title">
                    <?php
                    if ($isp)
                        echo Html::OpenTag("a", ["href"=>$p]);
                    if ($v = get($value, "Icon"))
                        response(Html::Icon($v));
                    if (isValid($src = get($value, "Image")))
                        response(Html::Image($src));
                    ?>
                        <?php echo getBetween($value, "Title", "Name"); ?>
                    </h3>
                    <?php if ($isp)
                        echo Html::CloseTag(); ?>
                    <div class="description">
                        <?php echo get($value, "Description"); ?>
                        <?php response(Html::Center(getBetween($value, "Button", "More"))); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>