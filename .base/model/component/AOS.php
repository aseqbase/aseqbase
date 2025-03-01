<?php
\_::$Front->Initials[] = "
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css'>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js' crossorigin='anonymous'></script>
	";
\_::$Front->Finals[] = "
		<script>
			AOS.init({
				easing: 'ease-in-out-sine'
			});
			$(document).ready(function(){
				Evaluate.URL();
			});
		</script>
";
?>