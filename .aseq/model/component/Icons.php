<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
class Icons
{
	public static $Local = true;
	public static $Initialized = false;
	public static $DefaultRoot = "body";

	public static function Render($root = null)
	{
		echo (self::$Initialized?"":self::GetInitial()).self::GetStyle($root) . self::GetTechnologyStyle($root);
	}

	public static function GetInitial()
	{
		self::$Initialized = true;
		if(self::$Local)
			return Struct::Style(null, asset(\_::$Router->PackageDirectory, "Icons/style/Style.css", optimize: false), ["async"]).
		Struct::Script(null, asset(\_::$Router->PackageDirectory, "Icons/script/Script.js", optimize: false), ["async"]);
		else return Struct::Style(null, "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css").
			Struct::Script(null, "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/js/all.min.js", ["async"]);
	}
	public static function GetStyle($root = null)
	{
		$root = $root ?? self::$DefaultRoot;
		return Struct::Style("
			$root .fa:not(.icon) {
				aspect-ratio: 1;
				padding: 20px;
				min-width: 60px;
				text-align: center;
				text-decoration: none;
				margin: 5px 2px;
				opacity: 0.9;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			$root .icon {
				aspect-ratio: 1;
				padding: 20px;
				text-align: center;
				text-decoration: none;
				margin: 5px 2px;
				opacity: 0.9;
				width: unset;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			$root :is(.fa, .icon):hover {
				opacity: 0.95;
				color: var(--back-color-input);
				background-color: var(--fore-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
		");
	}

	public static function GetTechnologyStyle($root = null)
	{
		$root = $root ?? self::$DefaultRoot;
		return Struct::Style("
			$root :is(.fa, .icon):hover {
				opacity: 1;
				color: var(--back-color-input);
				background-color: var(--fore-color-input);
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			$root .fa-facebook:hover {
			background-color: #3B5998;
			}

			$root .fa-twitter:hover {
			background-color: #55ACEE;
			}

			$root .fa-google:hover {
			background-color: #dd4b39;
			}

			$root .fa-linkedin:hover {
			background-color: #007bb5;
			}

			$root .fa-youtube:hover {
			background-color: #bb0000;
			}

			$root .fa-instagram:hover {
			background-color: #125688;
			}

			$root .fa-pinterest:hover {
			background-color: #cb2027;
			}

			$root .fa-snapchat-ghost:hover {
			background-color: #fffc00;
			}

			$root .fa-skype:hover {
			background-color: #00aff0;
			}

			$root .fa-whatsapp:hover {
			background-color: #2cd61c;
			}

			$root .fa-android:hover {
			background-color: #a4c639;
			}

			$root .fa-dribbble:hover {
			background-color: #ea4c89;
			}

			$root .fa-vimeo:hover {
			background-color: #45bbff;
			}

			$root .fa-tumblr:hover {
			background-color: #2c4762;
			}

			$root .fa-comments:hover {
			background-color: #00b489;
			}

			$root .fa-telegram:hover {
			background-color: #45bbff;
			}

			$root .fa-stumbleupon:hover {
			background-color: #eb4924;
			}

			$root .fa-flickr:hover {
			background-color: #f40083;
			}

			$root .fa-envelope:hover {
			background-color: #430297;
			}

			$root .fa-soundcloud:hover {
			background-color: #ff5500;
			}

			$root .fa-reddit:hover {
			background-color: #ff5700;
			}

			$root .fa-github:hover {
			background-color: #8957e5;
			}

			$root .fa-gitlab:hover {
			background-color: #8957e5;
			}

			$root .fa-rss:hover {
			background-color: #ff6600;
			}
			$root .fa-fax:hover {
			background-color: #ed2476;
			}
			$root .fa-phone:hover {
			background-color: #38E54D;
			}
			$root .fa-map-marker:hover {
			background-color: #bd081c;
			}
		");
	}
}

if(!Icons::$Initialized) \_::$Front->Libraries[] = Icons::GetInitial();