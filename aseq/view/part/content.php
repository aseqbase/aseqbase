<?php
module("Page");
$module = new \MiMFa\Module\Page();
$module->Content = isValid(\_::$Address->UrlRoute) ?
	page(normalizePath(\_::$Address->UrlRoute), alternative: "404", print: false) :
	page("home", alternative: "404", print: false);
pod($module, $data);
$module->Render();
script("
	function viewInternal(link, anim = 'fade', cls = null, selector = '#internal') {
	{$module->MainClass}_ViewInternal(link, anim, cls, selector);
	}
	function viewExternal(link, anim = 'fade', cls = null, selector = '#external') {
	{$module->MainClass}_ViewExternal(link, anim, cls, selector);
	}
	function viewEmbed(link, anim = 'fade', cls = null, selector = '#embed') {
	{$module->MainClass}_ViewEmbed(link, anim, cls, selector);
	}

	function injectInternal(link, anim = 'fade', cls = null, selector = '#internal') {
	{$module->MainClass}_InjectInternal(link, anim, cls, selector);
	}

	function injectExternal(link, anim = 'fade', cls = null, selector = '#external') {
	{$module->MainClass}_InjectExternal(link, anim, cls, selector);
	}

	function embedExternal(link, anim = 'fade', cls = null, selector = '#embed') {
	{$module->MainClass}_EmbedExternal(link, anim, cls, selector);
	}");