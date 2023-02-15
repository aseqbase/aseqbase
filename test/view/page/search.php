<?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Search";
	$module->Draw();
?>
<style>
	.search-part * {
	box-sizing: border-box;
	}

	.search-part .top-bar{
		text-align: left;
		margin:0px !important;
		padding: 10px;
		box-shadow: <?php echo \_::$TEMPLATE->Shadow(2) ?>;
		border-bottom: <?php echo \_::$TEMPLATE->Border(1) ?> <?php echo \_::$TEMPLATE->ForeColor(1) ?>;
	}
	.search-part.container-fluid {
		padding: 0px;
	}
	.search-part .top-bar .logo{
		text-align: center;
		height: 52px;
		align-items: center;
		background-image: url('/file/logo/JobSearch.png');
		background-repeat: no-repeat;
		background-size: auto 40px;
		background-position: center;
	}
	.search-part .sources{
		text-align: left;
		align-items: center;
		padding: 0px 10px;
	}
	.search-part .sources .source{
		font-size: 16px;
		padding: 4px 7px;
		margin: 8px 1px;
		box-shadow: <?php echo \_::$TEMPLATE->Shadow(1) ?>;
		border: <?php echo \_::$TEMPLATE->Border(1) ?> <?php echo \_::$TEMPLATE->BackColor(2) ?>;
		background-color: <?php echo \_::$TEMPLATE->BackColor(2)."55" ?>;
	}
	.search-part .sources .falcundco:hover{
		background-color: <?php echo \_::$TEMPLATE->ForeColor(2) ?>;
	}
	.search-part .sources .stepstone:hover{
		background-color: #4088ee;
	}
	.search-part .sources .gehalt:hover{
		background-color: #464d94;
	}
	.search-part .sources .indeed:hover{
		background-color: #085ff7;
	}
	.search-part .sources .monster:hover{
		background-color: #6e46ae;
	}
	.search-part .sources .source:hover{
		color: <?php echo \_::$TEMPLATE->BackColor(2) ?>;
	}  	box-shadow: <?php echo \_::$TEMPLATE->Shadow(2) ?>;

	.search-part #myInput {
	width: 100%;
	max-width: 600px;
	font-size: 16px;
	padding: 12px 20px 12px 40px;
	border: <?php echo \_::$TEMPLATE->Border(1) ?> <?php echo \_::$TEMPLATE->BackColor(1) ?>;
	}
	.search-part #app-results {
	list-style-type: none;
	}

	.search-part #app-results li {
		margin: 20px;
		background-color: <?php echo \_::$TEMPLATE->BackColor(2)."55" ?>;
		padding: 12px;
	box-shadow: <?php echo \_::$TEMPLATE->Shadow(1) ?>;
		<?php echo MiMFa\Library\Style::UniversalProperty('transition', 'all 0.5s');?>
	}
	.search-part #app-results li:hover {
		background-color: <?php echo \_::$TEMPLATE->BackColor(2) ?>;
	box-shadow: <?php echo \_::$TEMPLATE->Shadow(2) ?>;
		<?php echo MiMFa\Library\Style::UniversalProperty('transition', 'all 0.5s');?>
	}
	.search-part #app-results li a, .search-part #app-results li a:visited {
		color: <?php echo \_::$TEMPLATE->ForeColor(0) ?> ;
	text-decoration: none;
	display: block;
	}
	.search-part #app-results li a:hover {
		color:  <?php echo \_::$TEMPLATE->ForeColor(2) ?> ;
		background-color: transparent;
	}
	.search-part .title{
		font-weight: bold;
		padding: 0px 12px;
	}
	.search-part .details{
		color: #999;
		padding: 0px 12px;
	display: block;
	}
	.search-part #app-results li .logo{
		width: 50px;
	}

	.search-part td {
		vertical-align: middle;
	}

	.search-part #ext-results iframe{
		height: calc(100vh-120px);
	}
</style>
<script>
	function SearchOnKeyUp(){
				var input, filter, ul, li, a, i, txtValue;
				input = document.getElementById('myInput');
				filter = input.value.toUpperCase();
				ul = document.getElementById('app-results');
				li = ul.getElementsByTagName('li');
				for (i = 0; i < li.length; i++) {
					a = li[i].getElementsByTagName('a')[0];
					txtValue = a.textContent || a.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						li[i].style.display = '';
					} else {
						li[i].style.display = 'none';
					}
				}
	}
	function FalkUndCoSearch(){
		$(".search-part #ext-results").hide();
		$(".search-part #app-results").show();
	}
	function StepStoneSearch(){
		const word = $("#myInput").val();
		ViewExternal('https://www.stepstone.de/5/job-search-simple.html?ke='+word);
		// $(".search-part #app-results").hide();
		// $(".search-part #ext-results").show();
	}
	function GehaltSearch(){
		const word = $("#myInput").val();
		ViewExternal('https://www.gehalt.de/einkommen/suche/'+word);
		// $(".search-part #app-results").hide();
		// $(".search-part #ext-results").show();
	}
	function IndeedSearch(){
		const word = $("#myInput").val();
		ViewExternal('https://www.indeed.com/jobs?q='+word);
		// $(".search-part #app-results").hide();
		// $(".search-part #ext-results").show();
	}
	function MonsterSearch(){
		const word = $("#myInput").val();
		ViewExternal('https://www.monster.de/jobs/suche/?q='+word+'&cy=DE&rad=20&intcid=swoop_HeroSearch_DE');
		// $(".search-part #app-results").hide();
		// $(".search-part #ext-results").show();
	}
</script>

<div class="search-part container-fluid">
	<div class="top-bar row" data-aos='fade-down'>
		<div  class="logo col-md-2"></div>
		<input class="col-md-6" type="text" id="myInput" onkeyup="SearchOnKeyUp()" placeholder="Search your job.." title="Type in a property of your job">
		<div class="sources col-md-4">
			<span>Search in:</span>
			<!--<button class="btn source falcundco" onclick="FalkUndCoSearch()">Falk&Co</button>-->
			<button class="btn source stepstone" onclick="StepStoneSearch()">StepStone</button>
			<button class="btn source gehalt" onclick="GehaltSearch()">Ghalt</button>
			<button class="btn source indeed" onclick="IndeedSearch()">Indeed</button>
			<!--<button class="btn source monster" onclick="MonsterSearch()">Monster</button>-->
		</div>
	</div>
	<div class="results">
		<ul id="app-results" class="container">
			<div class="row">
				<li data-aos='fade-right' class="col-md">
					<table>
						<tr>
							<td rowspan="2"><img class="logo" src="/test/GitHub.png"></td>
							<td class="title"><a href="#">Software Developer</a></td>
						</tr>
						<tr class="details">
							<td>GitHub / Munich, Bavaria, Germany</td>
						</tr>
					</table>
				</li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/Intel.png"></td><td class="title"><a href="#">Software Engineer - Accessibility</a></td></tr><tr class="details"><td >Intel Corporation / Munich, Bavaria, Germany</td></tr></table></li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/thryve.png"></td><td class="title"><a href="#">Senior Web Developer (Remote) - $60,000/year USD</a></td></tr><tr class="details"><td>thryve / Munich, Bavaria, Germany</td></tr></table></li>
			</div>
			<div class="row">
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/Intel.png"></td><td class="title"><a href="#">Software Development Engineer .Net (f/m/d)</a></td></tr><tr class="details"><td>Intel Corporation / Munich, Bavaria, Germany</td></tr></table></li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/Yelp.png"></td><td class="title"><a href="#">Web Developer</a></td></tr><tr class="details"><td>Yelp / Canada / Remote</td></tr></table></li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/Yelp.png"></td><td class="title"><a href="#">Dotnet Developer</a></td></tr><tr class="details"><td>Yelp / Canada / Remote</td></tr></table></li>
			</div>
			<div class="row">
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/Intel.png"></td><td class="title"><a href="#">Software Engineer - Accessibility</a></td></tr><tr class="details"><td >Intel Corporation / Munich, Bavaria, Germany</td></tr></table></li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/thryve.png"></td><td class="title"><a href="#">Senior Web Developer (Remote) - $60,000/year USD</a></td></tr><tr class="details"><td>thryve / Munich, Bavaria, Germany</td></tr></table></li>
				<li data-aos='fade-right' class="col-md"><table><tr><td rowspan="2"><img class="logo" src="/test/thryve.png"></td><td class="title"><a href="#">Full Stack Developer</a></td></tr><tr class="details"><td>thryve / Munich, Bavaria, Germany</td></tr></table></li>
			</div>
		</ul>
		<!--<div id="ext-results" class="" style="display: none;"></div>-->
	</div>
</div>
