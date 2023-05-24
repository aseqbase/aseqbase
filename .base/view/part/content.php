<?php
MODULE("Content");
$module = new \MiMFa\Module\Content();
$module->Content = function(){
	if(isValid(\_::$DIRECTION))
		PAGE(NormalizePath(\_::$DIRECTION));
	else PAGE("home");
};
$module->Draw();
?>
<script>
function viewInternal(link, anim="fade", cls=null, selector = "#internal"){
	<?php echo $module->Name."_"; ?>ViewInternal(link,anim,cls, selector);
}
function viewExternal(link,anim="fade",cls=null, selector = "#external"){
	<?php echo $module->Name."_"; ?>ViewExternal(link,anim,cls, selector);
}
function viewEmbed(link,anim="fade",cls=null, selector = "#embed"){
	<?php echo $module->Name."_"; ?>ViewEmbed(link,anim,cls, selector);
}

function injectInternal(link, anim="fade", cls=null, selector = "#internal"){
	<?php echo $module->Name."_"; ?>InjectInternal(link,anim,cls, selector);
}

function injectExternal(link,anim="fade", cls=null, selector = "#external"){
	<?php echo $module->Name."_"; ?>InjectExternal(link,anim,cls, selector);
}

function embedExternal(link,anim="fade", cls=null, selector = "#embed"){
	<?php echo $module->Name."_"; ?>EmbedExternal(link,anim,cls, selector);
}

</script>