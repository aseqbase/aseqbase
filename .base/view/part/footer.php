<footer>
    <?php
    use MiMFa\Library\Style;
    use MiMFa\Library\HTML;
    MODULE("Shortcuts");
    $module = new \MiMFa\Module\Shortcuts();
    $module->Items = \_::$INFO->Contacts;
    $module->Draw();
    if(\_::$CONFIG->AllowTranslate){
        MODULE("Translator");
        $module = new MiMFa\Module\Translator();
        $module->Items = array(
                "EN"=>array(
                    "Title"=>"English",
                    "Image"=>"https://flagcdn.com/16x12/gb.png",
                    "Direction"=>"LTR",
                    "Encoding"=>"UTF-8"
                ),
                "FA"=>array(
                    "Title"=>"فارسی",
                    "Image"=>"https://flagcdn.com/16x12/ir.png",
                    "Direction"=>"RTL",
                    "Encoding"=>"UTF-8"
                )
            );
        echo HTML::Center($module->Capture());
    }
    MODULE("TemplateButton");
    $module = new MiMFa\Module\TemplateButton();
    //$module->DarkLabel = "Dark Mode";
    //$module->LightLabel = "Light Mode";
    $module->Style = new Style();
    $module->Style->Position = "absolute";
    $module->Style->Left = "var(--Size-1)";
    $module->Draw();
    echo HTML::Icon("arrow-up","scrollTo('body :nth-child(1)');",["style"=>"border: none; position: absolute; right: var(--Size-1);"]);
    PART("copyright");
    ?>
</footer>