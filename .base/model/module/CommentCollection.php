<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\Translate;
MODULE("Collection");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class CommentCollection extends Collection{
	public $Capturable = true;
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
	public $ShowStatus = false;
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
     * The label text of Edit button
     * @var array|string|null
     * @category Management
     */
	public $EditButtonLabel = "<i class='fa fa-pencil'></i>";
	/**
     * The label text of Reply button
     * @var array|string|null
     * @category Management
     */
	public $ReplyButtonLabel = "<i class='fa fa-reply'></i>";

	function __construct(){
        parent::__construct();
    }

	public function GetStyle(){
		return HTML::Style("
			.{$this->Name}>*>.item {
				height: fit-attach;
				max-width: calc(100% - 2 * var(--Size-2));
				background-Color: #88888808;
				margin: var(--Size-1);
            	padding: var(--Size-2);
				font-size: var(--Size-0);
				box-shadow: var(--Shadow-1);
				border-radius: var(--Radius-2);
            	border: var(--Border-1) var(--BackColor-5);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover{
				box-shadow: var(--Shadow-2);
				border-radius:  var(--Radius-1);
				border-color: var(--BackColor-4);
				background-Color: #88888818;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}

			.{$this->Name}>*>.item .head{
				margin-bottom: calc(var(--Size-0) / 2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}

			.{$this->Name}>*>.item .subject{
                padding: 0px;
                margin: 0px;
				font-size: var(--Size-3);
				text-align: unset;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .subject{
				font-size: var(--Size-3);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .metadata{
				font-size: var(--Size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .sidebtn{
				text-align: end;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .sidebtn>*{
                margin: 0px calc(var(--Size-0) / 2);
                padding: calc(var(--Size-0) / 2);
				opacity: 0;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name} :hover>*>.sidebtn>*{
            	opacity: 1;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			/* Style the images inside the grid */
			.{$this->Name}>*>.item .image {
				opacity: 1;
				width: {$this->ThumbnailWidth};
				height: {$this->ThumbnailHeight};
				min-height: {$this->ThumbnailMinHeight};
				min-width: {$this->ThumbnailMinWidth};
				max-height: {$this->ThumbnailMaxHeight};
				max-width: {$this->ThumbnailMaxWidth};
				overflow: hidden;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .image{
				opacity: 0.6;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .message{
                gap: var(--Size-0);
            	font-size: var(--Size-1);
				position: relative;
                margin-bottom: var(--Size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .attach{
                font-size: var(--Size-1);
            	text-align: justify;
                margin: 0px;
			}
			.{$this->Name}>*>.item .replies{
                gap: var(--Size-0);
            	font-size: var(--Size-1);
				position: relative;
                border-top: var(--Border-1) var(--BackColor-1);
                padding-inline-start: var(--Size-4);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
        ");
	}

	public function Get($items = null){
        return join(PHP_EOL, iterator_to_array((function() use($items){
		    MODULE("Image");
		    $img = new Image();
		    $img->Class = "image";
		    yield $img->GetStyle();
		    $i = 0;
		    yield $this->GetTitle();
		    yield $this->GetDescription();
		    foreach(Convert::ToItems($items??$this->Items) as $k=>$item) {
                $p_userid = getValid($item,"UserID");
                $p_groupid = getValid($item,"GroupID");
                if(isValid($item,"ReplyID") || (isValid($p_groupid) && isValid(\_::$INFO->User) && $p_groupid != \_::$INFO->User->GroupID && $p_userid != \_::$INFO->User->ID)) continue;
                $p_access = getValid($item,'Access',0);
                if(!getAccess($p_access)) continue;
                if(isValid($this->Relation) && $this->Relation != getValid($item,'Relation')) continue;
			    $p_id = getValid($item,'ID');
			    $p_image = getValid($item,'Image', $this->DefaultImage);
			    $p_subject = getValid($item,'Subject', $this->DefaultTitle);
			    $p_message = getValid($item,'Content', $this->DefaultDescription);
			    $p_attach = getValid($item,'Attach',$this->DefaultContent);
			    $p_email = getValid($item,'Contact');

			    $p_meta = getValid($item,'MetaData',null);
			    if($p_meta !==null) $p_meta = Convert::FromJSON($p_meta);
			    $p_showexcerpt = isValid($p_message) && getValid($p_meta,"AutoExcerpt",$this->AutoExcerpt);
			    $p_showsubject = getValid($p_meta,"ShowSubject",$this->ShowSubject);
			    $p_showmessage = getValid($p_meta,"ShowMessage",$this->ShowMessage);
			    $p_showattach = getValid($p_meta,"ShowAttach",$this->ShowAttach);
			    $p_showimage = isValid($p_image) && getValid($p_meta,"ShowImage", $this->ShowImage);
                $p_showmeta = getValid($p_meta,"ShowMetaData", $this->ShowMetaData);
               
                $p_replyes = getValid($p_meta,"ShowReplies", $this->ShowReplies);
                if($p_replyes) {
                    $p_replyes = [];
                    foreach(Convert::ToItems($this->Items) as $k1=>$item1)
                        if($item1["ReplyID"] == $p_id) {
                            $p_replyes[] = $item1;
                            $p_replyes[count($p_replyes)-1]["ReplyID"] = null;
                        }
                } else $p_replyes = [];
                $p_refering = getValid($p_meta,"AutoRefering", $this->AutoRefering);
                $p_editbuttontext = (getValid(\_::$INFO->User, "Email") != $p_email && getValid(\_::$INFO->User, "UserID") != $p_userid) || !$this->ShowButtons?null:__(getValid($p_meta,"EditButtonLabel",$this->EditButtonLabel));
			    $p_showeditbutton = isValid($p_editbuttontext);
                $p_replybuttontext = !$this->ShowButtons?null:__(getValid($p_meta,"ReplyButtonLabel",$this->ReplyButtonLabel));
			    $p_showreplybutton = isValid($p_replybuttontext);
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
				    if($this->ShowAuthor)
                        doValid(
                            function($val) use(&$p_meta,$item){
                                $author = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."User","Signature , Name","Email=:Email",[":Email"=>$val]);
                                if(isEmpty($author)) $p_meta .=  " ".HTML::Span(getValid($item,"Name"), null, ["class"=>"Author"]);
                                else $p_meta .=  " ".HTML::Link(getValid($author,"Name"),"/user/".getValid($author,"Signature"),["class"=>"Author"]);
                            },
                            $item,
                            'Contact'
                        );
                    if($this->ShowCreateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='CreateTime'>$val</span>";
                            },
                            $item,
                            'CreateTime'
                        );
                    if($this->ShowUpdateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='UpdateTime'>$val</span>";
                            },
                            $item,
                            'UpdateTime'
                        );
                    if($this->ShowStatus)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='Status'>$val</span>";
                            },
                            $item,
                            'Status'
                        );
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
                yield "<div id='$uid' class='item col-lg'". ($this->Animation? " data-aos-delay='".($i % $this->MaximumColumns*\_::$TEMPLATE->AnimationSpeed)."' data-aos='{$this->Animation}'":"").">";
                    yield "<div class='head row'>";
                        yield "<div class='col-lg'>";
                            if($p_showsubject) yield "<h2 class='subject'>".$p_subject."</h2>";
                            if($p_showmeta && isValid($p_meta)) yield "<sub class='metadata'>$p_meta</sub>";
                        yield "</div>";
                        if($p_showeditbutton || $p_showreplybutton) 
                            yield HTML::Division(
                                ($p_showeditbutton?HTML::Button($p_editbuttontext, "
                                    selector = '.{$this->Name} #{$uid}';
                                    sbjbox = document.querySelector(selector+' .subject');
                                    msgbox = document.querySelector(selector+' .message .full');
                                    attbox = document.querySelector(selector+' .message .attach');
                                    if(msgbox.getAttribute('role') == 'textbox') {
                                        data = { ID:$p_id, Content:msgbox.innerText };
                                        if(sbjbox) data.Subject = sbjbox.innerText;
                                        if(attbox) data.Attach = attbox.innerText;
                                        postData(null, data, selector);
                                    } else {
                                        divinp = {
                                            style:'border: var(--Border-1)',
                                            role:'textbox',
                                            contenteditable:'true'
                                        };
                                        msgbox.setAttribute('class', 'full');
                                        for(attr in divinp) msgbox.setAttribute(attr, divinp[attr]);
                                        if(sbjbox) for(attr in divinp) sbjbox.setAttribute(attr, divinp[attr]);
                                        if(attbox) for(attr in divinp) attbox.setAttribute(attr, divinp[attr]);
                                        exbox = document.querySelector(selector+' .message .excerpt');
                                        if(exbox) exbox.remove();
                                        this.innerHTML = `$this->UpdateButtonLabel`;
                                        msgbox.focus();
                                    }
                                ", ["class"=>'btn']):null).
                                    ($p_showreplybutton?HTML::Button($p_replybuttontext, "postData(null, 'Reply=$p_id', '.{$this->Name} .item', (data, err)=>{document.querySelector('.{$this->Name} #{$uid} .reply-box').innerHTML = data; if(!err) this.remove();});", ["class"=>'btn']):null),
                                ["class"=>'sidebtn col-sm col-2']);
                    yield "</div>";
                    yield "<div class='message row'>";
                        if($p_showexcerpt) yield "<div class='excerpt col-md hover-hide'>$p_excerpt</div>";
                        if($p_showmessage && !isEmpty($p_message)) yield "<div class='full col-md hover-show'>".__(Convert::ToHTML($p_message), refering:$p_refering)."</div>";
                        if($p_showimage && isValid($p_image))
                            yield "<div class='col-lg-3'>".$img->ReCapture()."</div>";
                        if($p_showattach && isValid($p_attach))
                            yield "<div class='attach'>".Convert::ToHTML($p_attach)."</div>";
                    yield "</div>";
                    if(!isEmpty($p_replyes))
                        yield HTML::Division(HTML::Division("",["class"=>"reply-box"]).$this->Get($p_replyes), ["class"=>"replies"]);
                    else yield HTML::Division("",["class"=>"reply-box"]);
                yield "</div>";
                if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
		    if($i % $this->MaximumColumns !== 0)  yield "</div>";
        })()));
	}
}
?>