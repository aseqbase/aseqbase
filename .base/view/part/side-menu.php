<?php MODULE("SideMenu");
$module = new MiMFa\Module\SideMenu();
$module->Title = \_::$INFO->Product;
$module->Description = \_::$INFO->Owner;
$module->Items = \_::$INFO->SideMenus;
$module->Image = \_::$INFO->LogoPath;
$module->Shortcuts = \_::$INFO->Contacts;
$module->Draw();
?>
<script type="text/javascript">
	function viewSideMenu(show){
		<?php echo $module->Name."_ViewSideMenu(show);"; ?>
	}
</script>