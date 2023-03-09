<!DOCTYPE HTML>
<html lang="en">
<head>
	<title><?php echo $StaticTitle; ?></title>
	<link rel="icon" href="/file/logo/logo.png" fetchpriority="high">
	<link rel="preload" href="/file/general/process.gif" fetchpriority="high">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7CPoppins:400,500" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.classycountdown@1.0.1/css/jquery.classycountdown.min.css">
	<link href="/static/style/styles.css" rel="stylesheet">
	<link href="/static/style/responsive.css" rel="stylesheet">
	
</head>
<body>
	
	<div class="main-area">
		<div class="container full-height position-static">
			
			<section class="left-section full-height">
		
				<a class="logo" href="#"><img src="/file/logo/logo.png" alt="Logo"></a>
				
				<div class="display-table">
					<div class="display-table-cell">
						<div class="main-content">
							<h1 class="title"><b><?php echo $StaticTitle; ?></b></h1>
							<?php echo $StaticDescription; ?>

							<div class="email-input-area">
								<form method="post">
									<input class="email-input" name="email" type="text" placeholder="Enter your email"/>
									<button class="submit-btn" name="submit" type="submit"><b>NOTIFY US</b></button>
								</form>
							</div>
							
							<p class="post-desc">Sign up now to get early notification of our launch date!</p>
						</div>
					</div>
				</div>
				
				<ul class="footer-icons">
					<li>Stay in touch : </li>
					<?PHP
					     foreach($StaticContacts as $item)
					          echo "<li>$item</li>";
					?>
				</ul>
		
			</section>
		
			<section class="right-section" style="background-image: url('/static/file/side.png')">
			
				<div class="display-table center-text">
					<div class="display-table-cell">
						<div id="rounded-countdown">
							<div class="countdown" data-remaining-sec="<?PHP echo $StaticValue->format('U') - (new DateTime())->format('U'); ?>"></div>
						</div>
					</div>
				</div>
				
			</section>
		
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js" integrity="sha512-X7kCKQJMwapt5FCOl2+ilyuHJp+6ISxFTVrx+nkrhgplZozodT9taV2GuGHxBgKKpOJZ4je77OuPooJg9FJLvw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery.classycountdown@1.0.2/js/jquery.classycountdown.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-knob@1.2.11/dist/jquery.knob.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js" integrity="sha512-JZSo0h5TONFYmyLMqp8k4oPhuo6yNk9mHM+FY50aBjpypfofqtEWsAgRDQm94ImLCzSaHeqNvYuD9382CEn2zw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="/static/code/scripts.js"></script>
	
</body>
</html>