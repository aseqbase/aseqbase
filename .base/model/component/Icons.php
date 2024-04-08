<?php
namespace MiMFa\Component;
use MiMFa\Library\HTML;
class Icons extends Component{
    public $DefaultRoot = "body";
    public $Version = "6.4.2";

	public function __construct(){
		parent::__construct();
		append("REGION", "initial", "<script src='https://kit.fontawesome.com/e557f8d9f4.js' crossorigin='anonymous'></script>");
        //append("REGION", "initial", "
        //    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$this->Version}/css/all.min.css' integrity='sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==' crossorigin='anonymous' referrerpolicy='no-referrer' />
        //    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$this->Version}/css/fontawesome.min.css' integrity='sha512-siarrzI1u3pCqFG2LEzi87McrBmq6Tp7juVsdmGY1Dr8Saw+ZBAzDzrGwX3vgxX1NkioYNCFOVC0GpDPss10zQ==' crossorigin='anonymous' referrerpolicy='no-referrer' />
        //    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$this->Version}/js/all.min.js' integrity='sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==' crossorigin='anonymous' referrerpolicy='no-referrer'></script>
        //    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$this->Version}/js/fontawesome.min.js' integrity='sha512-64O4TSvYybbO2u06YzKDmZfLj/Tcr9+oorWhxzE3yDnmBRf7wvDgQweCzUf5pm2xYTgHMMyk5tW8kWU92JENng==' crossorigin='anonymous' referrerpolicy='no-referrer'></script>
        //    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$this->Version}/js/conflict-detection.min.js' integrity='sha512-tOK04OMrEtOhHdTJSiClQ6wlAHB4BizsjbKbt8KRLLfN1xeCl84CdOW0B++kTNYPp+VDlgJg+jrWX6FuCDx7kg==' crossorigin='anonymous' referrerpolicy='no-referrer'></script>
        //    ");
	}

	public function Echo($root=null){
		echo $this->Get($root);
	}
	public function Get($root=null){
		return $this->GetStyle($root).$this->GetTechnologyStyle($root);
	}

	public function EchoStyle($root=null){
		echo $this->GetStyle($root);
    }
	public function GetStyle($root=null){
		$root = $root??$this->DefaultRoot;
		return HTML::Style("
			$root .fa {
				padding: 20px;
				min-width: 60px;
				text-align: center;
				text-decoration: none;
				margin: 5px 2px;
				opacity: 0.9;
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			$root .fa:hover {
				opacity: 0.95;
				color: var(--BackColor-1);
				background-color: var(--ForeColor-1);
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}

	public function EchoTechnologyStyle($root=null){
		echo $this->GetTechnologyStyle($root);
    }
	public function GetTechnologyStyle($root=null){
		$root = $root??$this->DefaultRoot;
		return HTML::Style("
			$root .fa:hover {
				opacity: 1;
				color: var(--BackColor-1);
				background-color: var(--ForeColor-1);
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
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
?>