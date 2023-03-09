<?php namespace MiMFa\Module;
class Content extends Module{
	public $AllowTitle = true;
	public $AllowDescription = true;
	public $AllowImage = true;
	public $Content = null;
	public $Tag = "content";
	public $Attributes = " onclick='viewSideMenu(false)'";


	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				width:100%;
				Height:max-content;
			}
			.<?php echo $this->Name; ?>>.page{
				width:100%;
				Height:max-content;
				padding: 0px;
				margin: 0px;
			}
			.<?php echo $this->Name; ?>>.page>.frame{
				width:100%;
				Height:max-content;
				padding: 0px;
				margin: 0px;
			}
			.<?php echo $this->Name; ?>>.page.active{
				opacity:1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?>>.page:not(.active){
				opacity:0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?>>.page>div.frame{
				padding: var(--Size-0) var(--Size-3);
			}
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoTitle();
		$this->EchoDescription();
		?>
		<div class="page external-page" id="external" style="display: none;">
			<div class="frame"></div>
		</div>
		<div class="page internal-page active" id="internal">
			<div class="frame"><?php
							   if(!isValid($this->Content))
								   if(isValid($_GET, \_::$CONFIG->PathKey))
										PAGE(NormalizePath($_GET[\_::$CONFIG->PathKey]));
								   else PAGE("home");
							   elseif(is_string($this->Content)) echo "".$this->Content;
							   else ($this->Content)(); ?></div>
		</div>
		<div class="page embed-page" id="embed" style="display: none;">
			<iframe class="frame" ></iframe>
		</div>
		<?php
	}

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script>
			var ReadyHTML = {
				load: (data="")=> [
					`<style>
					.load {
						background-image: var(--Url-WaitSymbolPath);
						background-size: 70% 70%;
						background-repeat: no-repeat;
						background-position: center;
						background-color: var(--BackColor-1);
						color: var(--ForeColor-1);
						position: absolute;
						top: calc(50% - 5VMAX);
						left: calc(50% - 5VMAX);
						width: 10VMAX;
						height: 10VMAX;
						border-radius: 50%;  
						animation: load 1.5s infinite ease-in-out;
					}
					@keyframes load {
						0% {
						transform: scale(0) rotate(0);
						}
						50% {
						transform: scale(1) rotate(120);
						}
						100% {
						transform: scale(2) rotate(240);
						opacity: 0;
						}
					}
					</style>`,"<div class='load'>",data,"</div>"].join("\r\n"),
				wait: (data="")=>[
					`<style>
					.wait{
						background-size: 15vmax auto;
						background-repeat: no-repeat;
						background-position: center;
						padding-top: 10%;
						height: 95vh;
						max-height: 100%;
						width: 100vw;
						max-width: 100%;
						text-align: center;
						background-image: var(--Url-ProcessSymbolPath);
					}
					</style>`,"<div class='wait'>",data,"</div>"].join("\r\n"),
				error: (data="")=>[
					`<style>
					.error {
						background-size: 15vmax auto;
						background-repeat: no-repeat;
						background-position: center;
						padding-top: 10%;
						height: 95vh;
						max-height: 100%;
						width: 100vw;
						max-width: 100%;
						text-align: center;
						background-image: var(--Url-ErrorSymbolPath);
					}
					</style>`,"<div class='error'>",data,"</div>"].join("\r\n"),
				connectionError: (data="") => [
					`<style>
					.error {
						background-size: 15vmax auto;
						background-repeat: no-repeat;
						background-position: center;
						padding-top: 10%;
						height: 95vh;
						max-height: 100%;
						width: 100vw;
						max-width: 100%;
						text-align: center;
						background-image: var(--Url-ErrorSymbolPath);
					}
					</style>`,"<div class='error'>",data,"</div>"].join("\r\n")
			};
			function <?php echo $this->Name."_"; ?>ShowFrame(selector = ".<?php echo $this->Name; ?>"){
				$(".<?php echo $this->Name; ?>>.page").removeClass("active");
				$(".<?php echo $this->Name; ?>>.page").hide();
				$(".<?php echo $this->Name; ?> "+selector).addClass("active");
				$(".<?php echo $this->Name; ?> "+selector).show();
			}
			function <?php echo $this->Name."_"; ?>ViewInternal(link,anim=null,cls=null, selector = "#internal"){
				<?php echo $this->Name."_"; ?>InjectInternal(link,anim,cls, selector);
				<?php echo $this->Name."_"; ?>ShowFrame(selector);
			}
			function <?php echo $this->Name."_"; ?>ViewExternal(link,anim=null,cls=null, selector = "#external"){
				<?php echo $this->Name."_"; ?>InjectExternal(link,anim,cls, selector);
				<?php echo $this->Name."_"; ?>ShowFrame(selector);
			}
			function <?php echo $this->Name."_"; ?>ViewEmbed(link,anim=null,cls=null, selector = "#embed"){
				<?php echo $this->Name."_"; ?>EmbedExternal(link,anim,cls, selector);
				<?php echo $this->Name."_"; ?>ShowFrame(selector);
			}

			function <?php echo $this->Name."_"; ?>InjectInternal(link, anim=null, cls=null, selector = "#internal"){
				selector += ">.frame";
				const frame = $(selector)[0];
				frame.innerHTML = ReadyHTML.load();
				if(!isEmpty(cls)) frame.addClass(cls);
				if(!isEmpty(anim)) frame.setAttribute("data-aos",(isEmpty(anim)?"":anim));
				$(selector).load("/private.php?<?php echo \_::$CONFIG->PathKey."=page".(isEmpty(\_::$QUERY)?"":("&".\_::$QUERY)); ?>", {name:link,animation:anim,class:cls},
					function(data){
						if(!data) frame.innerHTML = ReadyHTML.connectionError("Please check your connection...");
					},
					function(data){
						frame.innerHTML = ReadyHTML.error(data.statusText);
					}
				);
			}

			function <?php echo $this->Name."_"; ?>InjectExternal(link,anim=null, cls=null, selector = "#external"){
				const frame = $(selector)[0];
				frame.innerHTML = ReadyHTML.load();
				frame.innerHTML = `<iframe is="x-frame" data-loading-page="`+ReadyHTML.load()+`" data-aos="`+anim+`" class="frame `+cls+`" src="`+link+`"></iframe>`;
			}

			function <?php echo $this->Name."_"; ?>EmbedExternal(link,anim=null, cls=null, selector = "#embed"){
				const frame = $(selector+" .frame")[0];
				frame.src = link;
			}

			// $(document).ready(function(){
			// 	var purl = "<?php echo isset($_GET[\_::$CONFIG->PathKey])?$_GET[\_::$CONFIG->PathKey]:'home'; ?>";
			// 	if(purl.search(/(^https?\:\/\/.*)|(^www\..*)/i) < 0) <?php echo $this->Name."_"; ?>ViewInternal(purl);
			// 	else <?php echo $this->Name."_"; ?>ViewEmbed(purl);
			// });
		</script>
		<?php
	}
}
?>