<?php module("SideMenu");
$module = new MiMFa\Module\SideMenu();
$module->Title = \_::$Info->Product;
$module->Description = \_::$Info->Owner;
$module->Items = \_::$Info->SideMenus;
$module->Image = \_::$Info->LogoPath;
$module->Shortcuts = \_::$Info->Contacts;
$module->Render();
?>
<script type="text/javascript">
	function viewSideMenu(show){
		<?php echo $module->Name."_ViewSideMenu(show);"; ?>
	}
</script>