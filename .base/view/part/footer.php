<footer>
<?php
MODULE("Shortcuts");
$module = new \MiMFa\Module\Shortcuts();
$module->Items = \_::$INFO->Contacts;
$module->Draw();
PART("copyright");
?>
</footer>