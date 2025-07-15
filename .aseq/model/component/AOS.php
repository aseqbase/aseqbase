<?php
use \MiMFa\Library\Html;
\_::$Front->Initials[] = 
	Html::Style(null,'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css').
	Html::Script(null,'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', ['crossorigin'=>'anonymous']);
\_::$Front->Finals[] = Html::Script("
			AOS.init({
				easing: 'ease-in-out-sine'
			});
			$(document).ready(function(){
				Evaluate.URL();
			});");