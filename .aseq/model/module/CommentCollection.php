<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Script;

module("Collection");
/**
 * To show comments in its collection
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class CommentCollection extends Collection
{
    public $TitleTag = "h5";

    public $MaximumColumns = 1;

    /**
     * The comments of a special realation id, leave null for all
     * @var string|null
     */
    public $Relation = null;
    public $Action = null;

    /**
     * The Width of thumbnail preshow
     * @var string
     */
    public $AuthorImageWidth = "var(--size-4)";
    /**
     * The Height of thumbnail preshow
     * @var string
     */
    public $AuthorImageHeight = "var(--size-4)";

    /**
     * @var bool
     * @category Parts
     */
    public $AllowMetaData = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowCreateTime = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowUpdateTime = false;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowAuthor = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowAuthorImage = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowStatus = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowImage = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowSubject = true;
    /**
     * @var bool
     * @category Parts
     */
    public $AllowMessage = true;
    /**
     * @var bool
     * @category Excerption
     */
    public $AllowAttach = true;
    /**
     * Allow to show replies
     * @var mixed
     */
    public $AllowReplies = true;
    /**
     * Allow to analyze all text and linking categories and tags to their messages, to improve the website's SEO
     * @var mixed
     */
    public $AutoReferring = true;
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
    public $AllowButtons = true;
    /**
     * The label text of Save button in Edit mode
     * @var array|string|null
     * @category Management
     */
    public $UpdateButtonLabel = "<i class='icon fa fa-save'><span class='tooltip'>Save the changes</span></i>";
    /**
     * The label text of cancel button
     * @var array|string|null
     * @category Management
     */
    public $CancelButtonLabel = "<i class='icon fa fa-close'><span class='tooltip'>Cancel the process</span></i>";
    /**
     * The label text of Reply button
     * @var array|string|null
     * @category Management
     */
    public $ReplyButtonLabel = "<i class='icon fa fa-reply'><span class='tooltip'>Reply to this message</span></i>";
    /**
     * The label text of Edit button
     * @var array|string|null
     * @category Management
     */
    public $EditButtonLabel = "<i class='icon fa fa-pencil'><span class='tooltip'>Edit the message</span></i>";
    /**
     * The label text of Delete button
     * @var array|string|null
     * @category Management
     */
    public $DeleteButtonLabel = "<i class='icon fa fa-trash'><span class='tooltip'>Remove the message for ever</span></i>";
    /**
     * The label text of waiting to approve message
     * @var array|string|null
     * @category Management
     */
    public $WaitingLabel = "<i class='icon fa fa-check'><span class='tooltip'>The message is received but not showed</span></i>";
    /**
     * The label text of approved message
     * @var array|string|null
     * @category Management
     */
    public $PublishedLabel = "<i class='icon fa fa-eye'><span class='tooltip'>The message is shown</span></i>";

    public function GetStyle()
    {
        return Struct::Style("
			.{$this->Name} div.item {
				height: fit-attach;
                width: -webkit-fill-available;
				width: fit-content;
				background-Color: #88888808;
                margin: calc(var(--size-1) / 2);
            	padding: var(--size-2);
				font-size: var(--size-0);
				box-shadow: var(--shadow-1);
				border-radius: var(--radius-2);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item:hover{
				box-shadow: var(--shadow-2);
				border-radius:  var(--radius-1);
				background-Color: #88888818;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item.deactive {
				background-Color: #88888844;
				box-shadow: var(--shadow-0);
				border-radius: var(--radius-0);
            	border: none;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}

			.{$this->Name} div.item .subject{
                margin-top: 0px;
				font-size: var(--size-3);
                text-transform: none;
				text-align: unset;
                position: relative;
                z-index: 1;
                overflow-wrap: break-word;
                flex-flow: wrap;
                text-wrap-mode: wrap;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item:hover .subject{
				font-size: var(--size-3);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item .author{
                display: flex;
                align-items: center;
			}
			.{$this->Name} div.item .author .author-name{
                font-weight: bold;
			}
			.{$this->Name} div.item .author .author-name::after{
				content: ': ';
				padding-inline-end: calc(var(--size-0) / 2);
			}
			.{$this->Name} .item .sidebtn{
                position: absolute;
                margin-top: calc(-1 * var(--size-3));
                margin-inline-start: calc(-1 * var(--size-3));
                width: 100%;
				text-align: end;
				opacity: 0;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
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
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} .item .sidebtn>*:hover{
                outline: none;
                border: none;
				opacity: 1;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item .author .author-image {
				background-color: var(--back-color-output);
				color: var(--fore-color-output);
				opacity: 0.6;
                aspect-ratio: 1;
                box-shadow: var(--shadow-1);
                border-radius: var(--radius-5);
                line-height: {$this->AuthorImageWidth};
				width: {$this->AuthorImageWidth};
				height: {$this->AuthorImageHeight};
                margin: 0px;
                margin-inline-end: calc(var(--size-0) / 3);
                padding: 0px;
                overflow: hidden;
                display: inline-flex;
                justify-content: center;
                align-items: center;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item:hover .author .author-image{
				opacity: 1;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item .message{
                gap: var(--size-0);
            	font-size: var(--size-1);
				position: relative;
                overflow-wrap: break-word;
                flex-flow: wrap;
                text-wrap-mode: wrap;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} div.item .message :is(.excerpt, .full){
                padding-inline-end: calc(var(--size-0) / 2);
			}
			.{$this->Name} div.item .attach{
                font-size: var(--size-1);
            	text-align: justify;
			}
			.{$this->Name} div.item .metadata{
				font-size: calc(var(--size-0) * 0.8);
                opacity: 0.8;
			}
			.{$this->Name} div.item .metadata>*{
				padding-inline-end: calc(var(--size-0) / 2);
                display: inline-block;
			}
			.{$this->Name} div.item .replies{
                gap: var(--size-0);
            	font-size: var(--size-1);
				position: relative;
                border-top: var(--border-1) var(--back-color-input);
                padding-inline-start: var(--size-0);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
        ");
    }
    public function Get($items = null)
    {
        return join(PHP_EOL, iterator_to_array((function () use ($items) {
            module("Image");
            $img = new Image();
            $img->Class = "image";
            yield $img->GetStyle();
            $i = 0;
            yield $this->GetTitle();
            yield $this->GetDescription();
            $adminaccess = \_::$User->HasAccess(\_::$User->AdminAccess);
            foreach (Convert::ToItems($items ?? $this->Items) as $k => $item) {
                $p_userid = get($item, "UserId");
                $p_groupid = get($item, "GroupId");
                $p_status = get($item, "Status");
                if (
                    isValid($item, "RootId") ||
                    (
                        (
                            !$p_status
                            || (isValid($p_groupid) && $p_groupid != \_::$User->GroupId)
                        )
                        && !$adminaccess && (!$p_userid || $p_userid != \_::$User->Id)
                    )
                )
                    continue;
                $p_access = getValid($item, 'Access', \_::$User->VisitAccess);
                if (!\_::$User->HasAccess($p_access))
                    continue;
                if (isValid($this->Relation) && $this->Relation != get($item, 'Relation'))
                    continue;
                $p_meta = getValid($item, 'MetaData', null);
                if ($p_meta !== null) {
                    $p_meta = Convert::FromJson($p_meta);
                    pod($this, $p_meta);
                }
                $p_meta = null;
                $p_id = get($item, 'Id');
                $p_name = get($item, 'Name');
                $p_image = getValid($item, 'Image', $this->DefaultImage);
                $p_subject = getValid($item, 'Subject', $this->DefaultTitle);
                $p_message = getValid($item, 'Content', $this->DefaultDescription);
                $p_attach = Convert::FromJson(getValid($item, 'Attach', $this->DefaultContent));
                $p_email = get($item, 'Contact');

                $p_showexcerpt = isValid($p_message) && $this->AutoExcerpt;
                $p_showsubject = isValid($p_subject) && $this->AllowSubject;
                $p_showmessage = $this->AllowMessage;
                $p_showattach = $this->AllowAttach;
                $p_showimage = isValid($p_image) && $this->AllowImage;
                $p_showmeta = $this->AllowMetaData;

                $p_replyes = $this->AllowReplies;
                if ($p_replyes) {
                    $p_replyes = [];
                    foreach (Convert::ToItems($this->Items) as $k1 => $item1)
                        if ($item1["RootId"] == $p_id) {
                            $p_replyes[] = $item1;
                            $p_replyes[count($p_replyes) - 1]["RootId"] = null;
                        }
                } else
                    $p_replyes = [];
                $p_referring = $this->AutoReferring;
                $updateaccess = $adminaccess || ($p_email && get(\_::$User, "Email") == $p_email) || ($p_userid && get(\_::$User, "UserId") == $p_userid);
                $p_replybuttontext = !$this->AllowButtons ? null : __($this->ReplyButtonLabel);
                $p_showreplybutton = !isEmpty($p_replybuttontext);
                $p_editbuttontext = !$updateaccess || !$this->AllowButtons ? null : __($this->EditButtonLabel);
                $p_showeditbutton = !isEmpty($p_editbuttontext);
                $p_deletebuttontext = !$updateaccess || !$this->AllowButtons ? null : __($this->DeleteButtonLabel);
                $p_showdeletebutton = !isEmpty($p_deletebuttontext);
                $p_statustext = !$updateaccess || !$this->AllowStatus ? null : __($p_status ? $this->PublishedLabel : $this->WaitingLabel);
                $p_showstatus = !isEmpty($p_statustext);
                $uid = "c_" . getId();

                $p_excerpt = $p_showexcerpt ?
                    Convert::ToExcerpt(
                        Convert::ToText($p_message),
                        0,
                        $this->ExcerptLength,
                        $this->ExcerptSign
                    ) :
                    $p_message;

                if ($p_showmeta) {
                    if ($this->AllowCreateTime)
                        doValid(
                            function ($val) use (&$p_meta) {
                                if (isValid($val))
                                    $p_meta .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'createtime']);
                            },
                            $item,
                            'CreateTime'
                        );
                    if ($this->AllowUpdateTime)
                        doValid(
                            function ($val) use (&$p_meta) {
                                if (isValid($val))
                                    $p_meta .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'updatetime']);
                            },
                            $item,
                            'UpdateTime'
                        );
                    if ($p_showstatus)
                        $p_meta .= $p_statustext;
                    if ($this->AllowButtons)
                        doValid(
                            function ($val) use (&$p_meta) {
                                if (isValid($val))
                                    $p_meta .= " " . $val;
                                else
                                    $p_meta .= " " . $this->DefaultButtons;
                            },
                            $item,
                            'Buttons'
                        );
                }
                $img->Source = $p_image;
                if ($i % $this->MaximumColumns === 0)
                    yield "<div class='row'>";
                yield "<div id='$uid' class='item col-lg " . ($p_status ? "" : "deactive") . "'" . ($this->Animation ? " data-aos-delay='" . ($i % $this->MaximumColumns * \_::$Front->AnimationSpeed) . "' data-aos='{$this->Animation}'" : "") . ">";
                if ($p_showreplybutton || $p_showeditbutton || $p_showdeletebutton)
                    yield Struct::Division(
                        ($p_showdeletebutton ? Struct::Button(
                            $p_deletebuttontext,
                            "{$this->Name}_Delete(this, '.{$this->Name} #{$uid}', $p_id);"
                        ) : null) .
                        ($p_showeditbutton ? Struct::Button(
                            $p_editbuttontext,
                            "{$this->Name}_Edit(this, '.{$this->Name} #{$uid}', $p_id);",
                        ) : null) .
                        ($adminaccess ? Struct::Button(
                            $p_status ? $this->WaitingLabel : $this->PublishedLabel,
                            "{$this->Name}_Status(this, '.{$this->Name} #{$uid}', $p_id, $p_status?false:true);",
                        ) : null) .
                        ($p_showreplybutton ? Struct::Button(
                            $p_replybuttontext,
                            "{$this->Name}_Reply(this, '.{$this->Name} #{$uid}', $p_id);",
                        ) : null),
                        ["class" => 'sidebtn']
                    );
                if ($p_showsubject)
                    yield "<h2 class='subject'>" . $p_subject . "</h2>";
                yield "<div class='message'>";
                $author = $this->AllowAuthorImage || $this->AllowAuthor ? table("User")->SelectRow("Signature, Name, Image", "Email=:Email", [":Email" => get($item, 'Contact')]) : [];
                yield Struct::OpenTag("div", ["class" => "author"]);
                if ($this->AllowAuthorImage) {
                    $aimg = get($author, "Image");
                    if (!isEmpty($author))
                        yield Struct::Media($aimg ? " " : strtoupper(substr(getValid($author, "Name", $p_name), 0, 1)), $aimg ?? \_::$User->DefaultImagePath, ["class" => "author-image"]);
                }
                yield Struct::OpenTag("div", ["class" => "author-details"]);
                if ($this->AllowAuthor) {
                    $au = getValid($author, "Name", $p_name);
                    if (isEmpty($author)){
                        if($au) yield Struct::Span($au, null, ["class" => "author-name"]);
                    }else
                        yield Struct::Link($au, \_::$Address->UserRoot . get($author, "Signature"), ["class" => "author-name"]);
                }
                if ($p_showmeta && isValid($p_meta))
                    yield "<div class='metadata'>$p_meta</div>";
                yield Struct::CloseTag();
                yield Struct::CloseTag();
                if ($p_showexcerpt)
                    yield "<div class='excerpt view parent-hover-hide'>$p_excerpt</div>";
                if ($p_showmessage && !isEmpty($p_message))
                    yield "<div class='full view parent-hover-show'>" . __(Struct::Convert($p_message), referring: $p_referring) . "</div>";
                if ($p_showimage && isValid($p_image))
                    yield "<div class='col-lg-3'>" . $img->ToString() . "</div>";
                if ($p_showattach && isValid($p_attach))
                    yield "<div class='attach'>" . Struct::Convert($p_attach) . "</div>";
                yield "</div>";
                if (!isEmpty($p_replyes))
                    yield Struct::Division(Struct::Division("", ["class" => "reply-box"]) . $this->Get($p_replyes), ["class" => "replies"]);
                else
                    yield Struct::Division("", ["class" => "reply-box"]);
                yield "</div>";
                if (++$i % $this->MaximumColumns === 0)
                    yield "</div>";
            }
            if ($i % $this->MaximumColumns !== 0)
                yield "</div>";
        })()));
    }
    public function GetScript()
    {
        $action = $this->Action?Script::Convert($this->Action):"null";
        return Struct::Script(
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
                    sendPut($action, data, selector, function (data, err) {
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
            }" . "
            function {$this->Name}_Delete(btn, selector, forid) {
                if(confirm(`" . __("Are you sure to delete this command?") . "`))
                    sendDelete(
                        $action,
                        {Id:forid},
                        selector,
                        (data, err)=>{
                            if(!err) $(selector).html(data);
                        }
                    );
            }" .
            "
            {$this->Name}_status = null;
            function {$this->Name}_Status(btn, selector, forid, status) {
                    sendPatch(
                        $action,
                        {Id:forid, Status:{$this->Name}_status = {$this->Name}_status===0 || status?1:0},
                        selector,
                        (data, err)=>{
                            if(!err)
                                if({$this->Name}_status) {
                                    btn.innerHTML = `{$this->WaitingLabel}`;
                                    document.querySelector(selector).classList.remove('deactive');
                                }
                                else {
                                    btn.innerHTML = `{$this->PublishedLabel}`;
                                    document.querySelector(selector).classList.add('deactive');
                                }
                        }
                    );
            }" .
            "
            function {$this->Name}_Reply(btn, selector, forid) {
                rbox = document.querySelector(selector + ' .reply-box');
                if(!rbox.querySelector('form')) {
                    sendPatch(
                        $action,
                        {Root:forid},
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