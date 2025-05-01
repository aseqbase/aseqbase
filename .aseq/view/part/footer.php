<footer>
    <?php
    use MiMFa\Library\Style;
    use MiMFa\Library\Html;
    module("Shortcuts");
    $module = new \MiMFa\Module\Shortcuts();
    $module->Items = \_::$Info->Contacts;
    swap($module, $data);
    $module->Render();
    if (\_::$Config->AllowTranslate) {
        module("Translator");
        $module = new MiMFa\Module\Translator();
        $module->Items = array(
            "en" => array(
                "Title" => "English",
                "Image" => "https://flagcdn.com/16x12/gb.png",
                "Direction" => "ltr",
                "Encoding" => "utf-8"
            ),
            "fa" => array(
                "Title" => "فارسی",
                "Image" => "https://flagcdn.com/16x12/ir.png",
                "Direction" => "rtl",
                "Encoding" => "utf-8"
            )
        );
        $module->Style = new Style();
        $module->Style->TextAlign = "center";
        $module->Render();
    }
    module("TemplateButton");
    $module = new MiMFa\Module\TemplateButton();
    $module->Style = new Style();
    $module->Style->Position = "absolute";
    $module->Style->Left = "var(--size-1)";
    $module->Render();
    \Res::Render(Html::Icon("arrow-up", "scrollTo('body :nth-child(1)');", ["style" => "border: none; position: absolute; right: var(--size-1);"]));
    part("copyright");
    ?>
</footer>