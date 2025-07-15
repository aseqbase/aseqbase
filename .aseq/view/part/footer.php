<footer>
    <?php
    use MiMFa\Library\Style;
    use MiMFa\Library\Html;
    $module = new (module("Shortcuts"))();
    $module->Items = \_::$Info->Contacts;
    swap($module, $data);
    $module->Render();
    if (\_::$Config->AllowTranslate) {
        $module = new (module("Translator"))();
        $module->Items = \_::$Back->Translate->GetLanguages();
        $module->Style = new Style();
        $module->Style->TextAlign = "center";
        $module->Render();
    }
    $module = new (module("TemplateButton"))();
    $module->Style = new Style();
    $module->Style->Position = "absolute";
    $module->Style->Left = "var(--size-1)";
    $module->Render();
    \Res::Render(Html::Icon("arrow-up", "scrollTo('body :nth-child(1)');", ["style" => "border: none; position: absolute; right: var(--size-1);"]));
    part("copyright");
    ?>
</footer>