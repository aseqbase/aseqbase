<footer>
    <?php
    module("Shortcuts");
    $module = new \MiMFa\Module\Shortcuts();
    $module->Items = \_::$Info->Contacts;
    swap($module, $data);
    $module->Render();
    module("TemplateButton");
    $module = new MiMFa\Module\TemplateButton();
    $module->Render();
    part("copyright", $data);
    ?>
</footer>