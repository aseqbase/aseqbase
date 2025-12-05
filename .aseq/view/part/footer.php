<footer><?php
    use MiMFa\Library\Style;
    use MiMFa\Library\Struct;
    $module = new (module("Shortcuts"))();
    $module->Items = \_::$Info->Contacts;
    pod($module, $data);
    $module->Render();
    if (\_::$Front->AllowTranslate) {
        $module = new (module("Translator"))();
        $module->Items = \_::$Front->Translate->GetLanguages();
        $module->AllowLabel = true;
        $module->AllowImage = false;
        $module->Style = new Style();
        $module->Style->TextAlign = "center";
        $module->Render();
    }
    $module = new (module("TemplateButton"))();
    $module->Style = new Style();
    $module->Style->Position = "absolute";
    $module->Style->Left = "var(--size-1)";
    $module->Render();
    response(
        Struct::Icon("arrow-up", "scrollThere('head');", [
            "class" => "view top-hide",
            "style" => "
                display: var(--display-scrollThere, inline-flex);
                border: none;
                position: fixed;
                right: var(--size-1);
                bottom: calc(var(--size-5) + var(--size-0));
            "]
        )
    );
    part("copyright");
?></footer>