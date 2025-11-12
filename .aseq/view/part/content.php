<?php
module("Page");
$module = new \MiMFa\Module\Page();
$module->Content = isValid(\_::$Address->Direction) ?
	page(normalizePath(\_::$Address->Direction), alternative: "404", print: false) :
	page("home", alternative: "404", print: false);
pod($module, $data);
$module->Render();
script("
	function viewInternal(link, anim = 'fade', cls = null, selector = '#internal') {
	{$module->Name}_ViewInternal(link, anim, cls, selector);
	}
	function viewExternal(link, anim = 'fade', cls = null, selector = '#external') {
	{$module->Name}_ViewExternal(link, anim, cls, selector);
	}
	function viewEmbed(link, anim = 'fade', cls = null, selector = '#embed') {
	{$module->Name}_ViewEmbed(link, anim, cls, selector);
	}

	function injectInternal(link, anim = 'fade', cls = null, selector = '#internal') {
	{$module->Name}_InjectInternal(link, anim, cls, selector);
	}

	function injectExternal(link, anim = 'fade', cls = null, selector = '#external') {
	{$module->Name}_InjectExternal(link, anim, cls, selector);
	}

	function embedExternal(link, anim = 'fade', cls = null, selector = '#embed') {
	{$module->Name}_EmbedExternal(link, anim, cls, selector);
	}");