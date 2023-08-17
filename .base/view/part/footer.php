
<footer>
    <?php
    use MiMFa\Library\Style;
    use MiMFa\Library\HTML;
    MODULE("Shortcuts");
    $module = new \MiMFa\Module\Shortcuts();
    $module->Items = \_::$INFO->Contacts;
    $module->Draw();
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