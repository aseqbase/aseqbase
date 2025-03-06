<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
module("Collection");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class CommentCollection extends Collection{
     public $TitleTag = "h5";

	public $MaximumColumns = 1;

	/**
     * The comments of a special realation id, leave null for all
     * @var string|null
     */
	public $Relation = null;

	/**
     * The Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailWidth = "auto";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailHeight = "100%";
	/**
     * The Minimum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinWidth = "auto";
	/**
     * The Minimum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinHeight = "10vh";
    /**
     * The Maximum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxWidth = "100%";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxHeight = "50vh";

	/**
     * @var bool
     * @category Parts
     */
	public $ShowMetaData = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowCreateTime = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowUpdateTime = false;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowAuthor = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowStatus = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowImage = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowSubject = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowMessage = true;
	/**
     * @var bool
     * @category Excerption
     */
	public $ShowAttach = true;
	/**
     * Allow to show replies
	 * @var mixed
	 */
	public $ShowReplies = true;
	/**
     * Allow to analyze all text and linking categories and tags to their messages, to improve the website's SEO
	 * @var mixed
	 */
	public $AutoRefering = true;
	/**
     * Selected Excerpts text automatically
     * @var bool
     * @category Excerption
     */
	public $AutoExcerpt = true;
	/**
     * The length of selected Excerpt text characters
     * @var int
     * @category Excerption
     */
	public $ExcerptLength = 150;
	/**
     * @var string
     * @category Excerption
     */
	public $ExcerptSign = "...";
	/**
     * @var bool
     * @category Parts
     */
	public $ShowButtons = true;
	/**
     * The label text of Save button in Edit mode
     * @var array|string|null
     * @category Management
     */
	public $UpdateButtonLabel = "<i class='fa fa-save'></i>";
	/**
     * The label text of cancel button
     * @var array|string|null
     * @category Management
     */
	public $CancelButtonLabel = "<i class='fa fa-close'></i>";
	/**
     * The label text of Reply button
     * @var array|string|null
     * @category Management
     */
	public $ReplyButtonLabel = "<i class='fa fa-reply'></i>";
	/**
     * The label text of Edit button
     * @var array|string|null
     * @category Management
     */
	public $EditButtonLabel = "<i class='fa fa-pencil'></i>";
	/**
     * The label text of Delete button
     * @var array|string|null
     * @category Management
     */
	public $DeleteButtonLabel = "<i class='fa fa-trash'></i>";
    /**
     * The label text of waiting to approve message
     * @var array|string|null
     * @category Management
     */
	public $WaitingLabel = "<i class='fa fa-spinner'></i>";
    /**
     * The label text of approved message
     * @var array|string|null
     * @category Management
     */
	public $PublishedLabel = "<i class='fa fa-eye'></i>";

	function __construct(){
        parent::__construct();
    }

	public function GetStyle(){
		return Html::Style("
			.{$this->Name} .item {
				height: fit-attach;
				background-Color: #88888808;
                margin: calc(var(--size-1) / 2);
            	padding: var(--size-2);
				font-size: var(--size-0);
				box-shadow: var(--shadow-1);
				border-radius: var(--radius-2);
            	border: var(--border-1) var(--back-color-5);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item:hover{
				box-shadow: var(--shadow-2);
				border-radius:  var(--radius-1);
				border-color: var(--back-color-4);
				background-Color: #88888818;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item.deactive {
				background-Color: #88888844;
				box-shadow: var(--shadow-0);
				border-radius: var(--radius-0);
            	border: none;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}

			.{$this->Name} .item .subject{
                padding: 0px;
                margin: 0px;
				font-size: var(--size-3);
                text-transform: none;
				text-align: unset;
                position: relative;
                z-index: 1;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item:hover .subject{
				font-size: var(--size-3);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item .author{
                font-weight: bold;
			}
			.{$this->Name} .item .author::after{
				content: ': ';
				padding-inline-end: calc(var(--size-0) / 2);
			}
			.{$this->Name} .item .sidebtn{
                position: absolute;
                margin: calc(-1 * var(--size-2));
                margin-bottom: 0;
                width: 100%;
				text-align: end;
				opacity: 0;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} :hover>*>.sidebtn{
                padding: calc(var(--size-0) / 2);
            	opacity: 1;
			}
			.{$this->Name} .item .sidebtn>*{
                aspect-ration: 1;
                background-color: transparent;
                margin: 0px calc(var(--size-0) / 2);
                padding: calc(var(--size-0) / 4);
                position: relative;
				opacity: 0.8;
				font-size: calc(var(--size-0) * 0.8);
                z-index: 9;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item .sidebtn>*:hover{
                outline: none;
                border: none;
				opacity: 1;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			/* Style the images inside the grid */
			.{$this->Name} .item .image {
				opacity: 1;
				width: {$this->ThumbnailWidth};
				height: {$this->ThumbnailHeight};
				min-height: {$this->ThumbnailMinHeight};
				min-width: {$this->ThumbnailMinWidth};
				max-height: {$this->ThumbnailMaxHeight};
				max-width: {$this->ThumbnailMaxWidth};
				overflow: hidden;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item:hover .image{
				opacity: 0.6;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item .message{
                gap: var(--size-0);
            	font-size: var(--size-1);
				position: relative;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .item .message .excerpt{
                padding-inline-end: calc(var(--size-0) / 2);
			}
			.{$this->Name} .item .attach{
                font-size: var(--size-1);
            	text-align: justify;
			}
			.{$this->Name} .item .metadata{
				font-size: calc(var(--size-0) * 0.8);
                display: inline-block;
                opacity: 0.8;
			}
			.{$this->Name} .item .metadata>*{
				padding-inline-end: calc(var(--size-0) / 2);
                display: inline-block;
			}
			.{$this->Name} .item .replies{
                gap: var(--size-0);
            	font-size: var(--size-1);
				position: relative;
                border-top: var(--border-1) var(--back-color-1);
                padding-inline-start: var(--size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
        ");
	}
	public function Get($items = null){
        return join(PHP_EOL, iterator_to_array((function() use($items){
		    module("Image" );
		    $img = new Image();
		    $img->Class = "image";
		    yield $img->GetStyle();
		    $i = 0;
		    yield $this->GetTitle();
		    yield $this->GetDescription();
            $adminaccess = auth(\_::$Config->AdminAccess);
		    foreach(Convert::ToItems($items??$this->Items) as $k=>$item) {
                $p_userid = get($item,"UserId" );
                $p_groupid = get($item,"GroupId" );
                $p_status = get($item,"Status" );
                if(
                    isValid($item,"ReplyId" ) || 
                    (
                        (
                            !$p_status 
                            || (isValid($p_groupid) && $p_groupid != \_::$Back->User->GroupId)
                        )
                        && !$adminaccess && (!$p_userid || $p_userid != \_::$Back->User->Id)
                    )
                ) continue;
                $p_access = findValid($item, 'Access' ,0);
                if(!auth($p_access)) continue;
                if(isValid($this->Relation) && $this->Relation != get($item,'Relation' )) continue;
			    $p_id = get($item,'Id' );
			    $p_name = get($item,'Name');
			    $p_image = findValid($item,'Image' , $this->DefaultImage);
			    $p_subject = findValid($item,'Subject' , $this->DefaultTitle);
			    $p_message = findValid($item,'Content' , $this->DefaultDescription);
			    $p_attach = findValid($item,'Attach' ,$this->DefaultContent);
			    $p_email = get($item,'Contact');

			    $p_meta = findValid($item,'MetaData' ,null);
			    if($p_meta !==null) $p_meta = Convert::FromJson($p_meta);
			    $p_showexcerpt = isValid($p_message) && findValid($p_meta,"AutoExcerpt",$this->AutoExcerpt);
			    $p_showsubject = isValid($p_subject) && findValid($p_meta,"ShowSubject",$this->ShowSubject);
			    $p_showmessage = findValid($p_meta,"ShowMessage",$this->ShowMessage);
			    $p_showattach = findValid($p_meta,"ShowAttach",$this->ShowAttach);
			    $p_showimage = isValid($p_image) && findValid($p_meta,"ShowImage", $this->ShowImage);
                $p_showmeta = findValid($p_meta,"ShowMetaData", $this->ShowMetaData);
                $p_showauthor = findValid($p_meta,"ShowAuthor", $this->ShowAuthor);
               
                $p_replyes = findValid($p_meta,"ShowReplies", $this->ShowReplies);
                if($p_replyes) {
                    $p_replyes = [];
                    foreach(Convert::ToItems($this->Items) as $k1=>$item1)
                        if($item1["ReplyId" ] == $p_id) {
                            $p_replyes[] = $item1;
                            $p_replyes[count($p_replyes)-1]["ReplyId" ] = null;
                        }
                } else $p_replyes = [];
                $p_refering = findValid($p_meta,"AutoRefering", $this->AutoRefering);
                $updateaccess = ($p_email && get(\_::$Back->User, "Email") == $p_email) || ($p_userid && get(\_::$Back->User, "UserId" ) == $p_userid);
                $p_replybuttontext = !$this->ShowButtons?null:__(findValid($p_meta,"ReplyButtonLabel",$this->ReplyButtonLabel));
			    $p_showreplybutton = isValid($p_replybuttontext);
                $p_editbuttontext = !$updateaccess || !$this->ShowButtons?null:__(findValid($p_meta,"EditButtonLabel",$this->EditButtonLabel));
			    $p_showeditbutton = isValid($p_editbuttontext);
                $p_deletebuttontext = !$updateaccess || !$this->ShowButtons?null:__(findValid($p_meta,"DeleteButtonLabel",$this->DeleteButtonLabel));
			    $p_showdeletebutton = isValid($p_deletebuttontext);
                $p_statustext = !$updateaccess || !$this->ShowStatus?null:__($p_status?findValid($p_meta,"PublishedLabel",$this->PublishedLabel):findValid($p_meta,"WaitingLabel",$this->WaitingLabel));
                $p_showstatus = isValid($p_statustext);
                $uid = "c_".getId();

                $p_excerpt = $p_showexcerpt?
                    Convert::ToExcerpt(
                            $p_message,
                            0,
                            $this->ExcerptLength,
                            $this->ExcerptSign
                        ):
                    $p_message;

			    $p_meta = null;
			    if($p_showmeta){
                    if($this->ShowCreateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= Html::Span(Convert::ToShownDateTimeString($val), ["class"=>'createtime' ]);
                            },
                            $item,
                            'CreateTime'
                        );
                    if($this->ShowUpdateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= Html::Span(Convert::ToShownDateTimeString($val), ["class"=>'updatetime' ]);
                            },
                            $item,
                            'UpdateTime'
                        );
                    if($p_showstatus)
                        $p_meta .= $p_statustext;
                    if($this->ShowButtons)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .=  " ".$val;
                                else $p_meta .=  " ".$this->DefaultButtons;
                            },
                            $item,
                            'Buttons'
                        );
                }
			    $img->Source = $p_image;
			    if($i % $this->MaximumColumns === 0) yield "<div class='row'>";
                yield "<div id='$uid' class='item col-lg ".($p_status?"":"deactive")."'". ($this->Animation? " data-aos-delay='".($i % $this->MaximumColumns*\_::$Front->AnimationSpeed)."' data-aos='{$this->Animation}'":"").">";
                    if($p_showreplybutton || $p_showeditbutton || $p_showdeletebutton) 
                        yield Html::Division(
                            ($p_showdeletebutton?Html::Button(
                                $p_deletebuttontext,
                                "{$this->Name}_Delete(this, '.{$this->Name} #{$uid}', $p_id);"
                            ):null).
                            ($p_showeditbutton?Html::Button(
                                $p_editbuttontext,
                                "{$this->Name}_Edit(this, '.{$this->Name} #{$uid}', $p_id);",
                            ):null).
                            ($p_showreplybutton?Html::Button(
                                $p_replybuttontext,
                                "{$this->Name}_Reply(this, '.{$this->Name} #{$uid}', $p_id);",
                            ):null),
                            ["class"=>'sidebtn']);
                    if($p_showsubject) yield "<h2 class='subject'>".$p_subject."</h2>";
                    yield "<div class='message'>";
                        if($p_showauthor)
                            yield doValid(
                                    function($val) use($p_name){
                                        $author = table("User")->DoSelectRow("Signature , Name","Email=:Email",[":Email"=>$val]);
                                        $au = findValid($author,"Name", $p_name);
                                        if(isEmpty($author)) return  Html::Span($au, null, ["class"=>"author"]);
                                        return Html::Link($au,\_::$Address->UserPath.get($author,"Signature" ),["class"=>"author"]);
                                    },
                                    $item,
                                    'Contact'
                                );
                        if($p_showexcerpt) yield "<div class='excerpt hover-hide'>$p_excerpt</div>";
                        if($p_showmessage && !isEmpty($p_message)) yield "<div class='full hover-show'>".__(Html::Convert($p_message), refering:$p_refering)."</div>";
                        if($p_showmeta && isValid($p_meta)) yield "<div class='metadata'>$p_meta</div>";
                        if($p_showimage && isValid($p_image))
                            yield "<div class='col-lg-3'>".$img->ToString()."</div>";
                        if($p_showattach && isValid($p_attach))
                            yield "<div class='attach'>".Html::Convert($p_attach)."</div>";
                    yield "</div>";
                    if(!isEmpty($p_replyes))
                        yield Html::Division(Html::Division("",["class"=>"reply-box"]).$this->Get($p_replyes), ["class"=>"replies"]);
                    else yield Html::Division("",["class"=>"reply-box"]);
                yield "</div>";
                if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
		    if($i % $this->MaximumColumns !== 0)  yield "</div>";
        })()));
	}
	public function GetScript(){
        return Html::Script(
            "
            function {$this->Name}_Edit(btn, selector, forid) {
                sbjbox = document.querySelector(selector+'>.subject');
                msgbox = document.querySelector(selector+'>.message .full');
                attbox = document.querySelector(selector+'>.message .attach');
                divinp = {
                    style:'border: var(--border-1)',
                    role:'textbox',
                    contenteditable:'true'
                };
                if(msgbox.getAttribute('role') == 'textbox') {
                    data = { Id:forid, Content:msgbox.innerText };
                    if(sbjbox) data.Subject = sbjbox.innerText;
                    if(attbox) data.Attach = attbox.innerText;
                    sendPut(null, data, selector, function (data, err) {
                        try{document.querySelector(selector .result).remove();}catch{}
                        $(selector).prepend(data);
                        if(!err){
                            for(attr in divinp) msgbox.removeAttribute(attr);
                            if(sbjbox) for(attr in divinp) sbjbox.removeAttribute(attr);
                            if(attbox) for(attr in divinp) attbox.removeAttribute(attr);
                            btn.innerHTML = `$this->EditButtonLabel`;
                        }
                    });
                } else {
                    msgbox.setAttribute('class', 'full');
                    for(attr in divinp) msgbox.setAttribute(attr, divinp[attr]);
                    if(sbjbox) for(attr in divinp) sbjbox.setAttribute(attr, divinp[attr]);
                    if(attbox) for(attr in divinp) attbox.setAttribute(attr, divinp[attr]);
                    exbox = document.querySelector(selector+' .message .excerpt');
                    if(exbox) exbox.remove();
                    btn.innerHTML = `$this->UpdateButtonLabel`;
                    msgbox.focus();
                }
            }"."
            function {$this->Name}_Delete(btn, selector, forid) {
                if(confirm(`".__("Are you sure to delete this command?", styling:false)."`))
                    sendDelete(
                        null,
                        {Id:forid},
                        selector,
                        (data, err)=>{
                            if(!err) $(selector).html(data);
                        }
                    );
            }".
            "
            function {$this->Name}_Reply(btn, selector, forid) {
                rbox = document.querySelector(selector + ' .reply-box');
                if(!rbox.querySelector('form')) {
                    sendPatch(
                        null,
                        {Reply:forid},
                        selector,
                        (data, err)=>{
                            $(rbox).html(data);
                            if(rbox.querySelector('form')) btn.innerHTML = `$this->CancelButtonLabel`;
                        },
                        (data, err)=>{
                            $(rbox).html(data);
                            if(rbox.querySelector('form')) btn.innerHTML = `$this->CancelButtonLabel`;
                        }
                    );
                } else {
                    if(rbox.style.display == 'none'){
                        rbox.style.display = 'initial';
                        btn.innerHTML = `$this->CancelButtonLabel`;
                    }
                    else {
                        rbox.style.display = 'none';
                        btn.innerHTML = `$this->ReplyButtonLabel`;
                    }
                }
            }"
        );
    }
}
?>