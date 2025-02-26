
<footer>
    <?php
    use MiMFa\Library\Style;
    use MiMFa\Library\HTML;
    module("Shortcuts");
    $module = new \MiMFa\Module\Shortcuts();
    $module->Items = \_::$Info->Contacts;
    $module->Render();
    if(\_::$Config->AllowTranslate){
        module("Translator");
        $module = new MiMFa\Module\Translator();
        $module->Items = array(
                "EN"=>array(
                    "Title" =>"English",
                    "Image" =>"https://flagcdn.com/16x12/gb.png",
                    "Direction"=>"LTR",
                    "Encoding"=>"UTF-8"
                ),
                "FA"=>array(
                    "Title" =>"فارسی",
                    "Image" =>"https://flagcdn.com/16x12/ir.png",
                    "Direction"=>"RTL",
                    "Encoding"=>"UTF-8"
                )
            );
        $module->Style = new Style();
        $module->Style->TextAlign = "center";
        $module->Render();
    }
    module("TemplateButton");
    $module = new MiMFa\Module\TemplateButton();
    //$module->DarkLabel = "Dark Mode";
    //$module->LightLabel = "Light Mode";
    $module->Style = new Style();
    $module->Style->Position = "absolute";
    $module->Style->Left = "var(--size-1)";
    $module->Render();
    module("Simple");
    (new MiMFa\Module\Simple(Html::Icon("arrow-up","scrollTo('body :nth-child(1)');",["style"=>"border: none; position: absolute; right: var(--size-1);"])))->Render();
    part("copyright");
    ?>
</footer>