<?php namespace MiMFa\Module;

use \MiMFa\Library\Html;
use \MiMFa\Library\Convert;

/**
 * A module to display content within different frames (internal, external, embed).
 * @copyright All rights are reserved for MiMFa Development Group
 * @author Mohammad Fathi
 * @see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 * @link https://github.com/aseqbase/aseqbase/wiki/Modules#Content See the Documentation
 */
class Page extends Module
{
    public $AllowTitle = true;
    public $AllowDescription = true;
    public $AllowImage = true;
    public $Content = null;
    public $Tag = "main";
    public $Attributes = " onclick='viewSideMenu(false)'";

    public function GetStyle()
    {
        return Html::Style("
            .{$this->Name} {
                width: 100%;
                height: max-content;
            }

            .{$this->Name} > .page {
                width: 100%;
                height: max-content;
                padding: 0;
                margin: 0;
                opacity: 0; /* Initially hide all pages */
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} > .page > .frame {
                width: 100%;
                height: max-content;
                padding: 0;
                margin: 0;
            }

            .{$this->Name} > .page.active {
                opacity: 1;
            }
        ");
    }
	
    public function Get()
    {
        return Convert::ToString(function () {
            yield $this->GetTitle();       // Handle and yield the title
            yield $this->GetDescription();  // Handle and yield the description

            // Internal Page
            yield Html::Division(
				Html::Division(
					Convert::ToString($this->Content)
				, ["class"=>"frame"]),
			["class"=> "page internal-page active", "Id" => "internal"]);

            // External Page (using iframe)
            yield Html::Division(Html::Embed("", null, ["class"=> "frame"]), ["class"=> "page external-page", "Id" => "external", "style" => "display:none;"]);

            // Embed Page (using iframe)
            yield Html::Division(Html::Embed("",  null,["class"=> "frame"]), ["class"=> "page embed-page", "Id" => "embed", "style" => "display:none;"]);
        });
    }

	public function GetScript(){
		return parent::GetScript().Html::Script("
			var ReadyHtml = {
				load: (data=``)=> [
					`<style>
					.load {
						background-image: var(--wait-symbol-path-url);
						background-size: 70% 70%;
						background-repeat: no-repeat;
						background-position: center;
						background-color: var(--back-color-1);
						color: var(--fore-color-1);
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
					</style>`,`<div class='load'>`,data,`</div>`].join(`\r\n`),
				wait: (data=``)=>[
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
						background-image: var(--process-symbol-path-url);
					}
					</style>`,`<div class='wait'>`,data,`</div>`].join(`\r\n`),
				error: (data=``)=>[
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
						background-image: var(--error-symbol-path-url);
					}
					</style>`,`<div class='error'>`,data,`</div>`].join(`\r\n`),
				connectionError: (data=``) => [
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
						background-image: var(--error-symbol-path-url);
					}
					</style>`,`<div class='error'>`,data,`</div>`].join(`\r\n`)
			};
			function {$this->Name}_ShowFrame(selector = `.{$this->Name}`){
				$(`.{$this->Name}>.page`).removeClass(`active`);
				$(`.{$this->Name}>.page`).hide();
				$(`.{$this->Name} `+selector).addClass(`active`);
				$(`.{$this->Name} `+selector).show();
			}
			function {$this->Name}_ViewInternal(link,anim=null,cls=null, selector = `#internal`){
				{$this->Name}_InjectInternal(link,anim,cls, selector);
				{$this->Name}_ShowFrame(selector);
			}
			function {$this->Name}_ViewExternal(link,anim=null,cls=null, selector = `#external`){
				{$this->Name}_InjectExternal(link,anim,cls, selector);
				{$this->Name}_ShowFrame(selector);
			}
			function {$this->Name}_ViewEmbed(link,anim=null,cls=null, selector = `#embed`){
				{$this->Name}_EmbedExternal(link,anim,cls, selector);
				{$this->Name}_ShowFrame(selector);
			}

			function {$this->Name}_InjectInternal(link, anim=null, cls=null, selector = `#internal`){
				selector += `>.frame`;
				const frame = $(selector)[0];
				frame.innerHTML = ReadyHtml.load();
				if(!isEmpty(cls)) frame.addClass(cls);
				if(!isEmpty(anim)) frame.setAttribute(`data-aos`,(isEmpty(anim)?``:anim));
				$(selector).load(`/private.php?".(isEmpty(\Req::$Query)?"":(\Req::$Query))."`".", {name:link,animation:anim,class:cls},
					function(data){
						if(!data) frame.innerHTML = ReadyHtml.connectionError(`Please check your connection...`);
					},
					function(data){
						frame.innerHTML = ReadyHtml.error(data.statusText);
					}
				);
			}

			function {$this->Name}_InjectExternal(link,anim=null, cls=null, selector = `#external`){
				const frame = $(selector)[0];
				frame.innerHTML = ReadyHtml.load();
				frame.innerHTML = `<iframe is='x-frame' data-loading-page=\``+ReadyHtml.load()+`\` data-aos='`+anim+`' class='frame `+cls+`' src='`+link+`'></iframe>`;
			}

			function {$this->Name}_EmbedExternal(link,anim=null, cls=null, selector = `#embed`){
				const frame = $(selector+` .frame`)[0];
				frame.src = link;
			}

		");
	}
}
?>