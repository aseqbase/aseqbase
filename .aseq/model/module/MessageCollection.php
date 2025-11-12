<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Script;

module("Collection");
/**
 * To show messages in an advanced messenger-like style collection
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class MessageCollection extends Collection
{
    // --- General Properties (Mostly retained/adapted) ---
    public $TitleTag = "h5";
    public $MaximumColumns = 1;
    public $Animation = null;

    public $Relation = null;
    public $Action = null;
    public $AuthorImageWidth = "var(--size-3)";
    public $AuthorImageHeight = "var(--size-3)";

    public $AllowMetaData = true;
    public $AllowCreateTime = true;
    public $AllowUpdateTime = true;
    public $AllowAuthor = true;
    public $AllowAuthorImage = true;
    public $AllowStatus = true;
    public $AllowImage = true;
    public $AllowContent = true;
    public $AllowAttach = true;
    /**
     * Used for Quoting a parent message ID (RootId) inside the bubble.
     * @var mixed
     */
    public $AllowReplies = true;
    public $AutoReferring = true;
    public $AutoExcerpt = true;
    public $ExcerptLength = 150;
    public $ExcerptSign = "...";
    public $AllowButtons = true;

    // --- Labels (Adapted for chat context) ---
    public $UpdateButtonLabel = "<i class='icon fa fa-save'><span class='tooltip'>Save</span></i>";
    public $CancelButtonLabel = "<i class='icon fa fa-close'><span class='tooltip'>Cancel</span></i>";
    public $ReplyButtonLabel = "<i class='icon fa fa-reply'><span class='tooltip'>Reply</span></i>";
    public $EditButtonLabel = "<i class='icon fa fa-pencil'><span class='tooltip'>Edit</span></i>";
    public $DeleteButtonLabel = "<i class='icon fa fa-trash'><span class='tooltip'>Delete</span></i>";
    // Using a double checkmark for 'read' and single for 'sent/received'
    public $WaitingLabel = "<i class='icon fa fa-clock-o'><span class='tooltip'>Pending</span></i>"; // Clock for pending
    public $PublishedLabel = "<i class='icon fa fa-check'><span class='tooltip'>Sent</span></i>"; // Single check for sent/delivered

    // --- New/Updated Method Implementations ---

    public function GetStyle()
    {
        return Struct::Style("
            .{$this->Name} {
                display: flex;
                flex-direction: column;
                padding: var(--size-2) var(--size-1);
                gap: var(--size-0);
                overflow-y:scroll;
                overflow-x:hidden;
                max-height:inherit;
            }
            .{$this->Name} div.item {
                display: flex;
                flex-direction: column;
                max-width: 70%;
                width: fit-content;
                padding: var(--size-1);
                position: relative;
                font-size: var(--size-1);
                box-shadow: var(--shadow-1);
                border-radius: var(--radius-1);
            }
            .{$this->Name} div.item:not(.sender) {
                align-self: flex-start;
                background-color: var(--back-color-special);
                color: var(--fore-color-special);
                margin-inline-start: auto;
                border-top-right-radius: var(--size-0); /* Smaller radius for the tail side */
            }
            .{$this->Name} div.item.sender {
                align-self: flex-end;
                background-color: var(--color-green);
                color: var(--color-white);
                margin-inline-end: auto;
                border-top-left-radius: var(--size-0); /* Smaller radius for the tail side */
            }
            .{$this->Name} div.item::before {
                content: '';
                position: absolute;
                top: 0;
                left: calc( var(--size-1) * -1); 
                right: calc( var(--size-1) * -1); 
                width: var(--size-3);
                height: var(--size-3);
                background: linear-gradient(135deg, var(--color-light) 0%, var(--color-light) 50%, transparent 50%, transparent);
            }
            .{$this->Name} div.item.deactive {
                opacity: 0.7;
            }

            .{$this->Name} div.item .message {
                margin-bottom: var(--size-0);
                padding-inline-end: var(--size-4); /* Space for timestamp/status */
                overflow-wrap: break-word;
            }
            .{$this->Name} div.item .author {
                font-size: var(--size-0);
                font-weight: bold;
                display: block;
                border-bottom: var(--border-2) #8884;
                padding-bottom: 2px;
                margin-bottom: 2px;
            }
            .{$this->Name} div.item .content {
                font-size: var(--size-1);
            }
            .{$this->Name} div.item .quote {
                opacity: 0.6;
                border-inline-start: 4px solid var(--color-gray); /* Messenger quote bar */
                padding: calc(var(--size-0) / 2) var(--size-0);
                margin-bottom: calc(var(--size-0) / 2);
                margin-inline-start: calc(-1 * var(--size-0));
                margin-inline-end: calc(-1 * var(--size-0));
                padding-inline-start: var(--size-0);
                background-color: rgba(0, 0, 0, 0.2);
                border-radius: var(--radius-0);
                font-size: calc(var(--size-1) * 0.9);
                overflow: hidden;
                max-height: calc(var(--size-1) * 5);
            }
            .{$this->Name} div.item .quote .content {
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 4;
                -webkit-box-orient: vertical;
            }
            .{$this->Name} div.item .image {
                border-radius: var(--radius-1);
                overflow: hidden;
                max-width: 100%;
                height: auto;
                margin-bottom: var(--size-0);
            }

            .{$this->Name} div.item .metadata {
                position: absolute;
                bottom: 3px;
                right: 6px;
                font-size: calc(var(--size-0) * 0.75);
                opacity: 0.7;
                display: flex;
                align-items: center;
                white-space: nowrap;
            }
            .{$this->Name} div.item .metadata .status-icon {
                margin-inline-start: 4px;
                font-size: calc(var(--size-0) * 0.8);
            }
            .{$this->Name} div.item:not(.sender) .metadata .status-icon {
                 color: var(--color-gray); 
            }
            .{$this->Name} .item .sidebtn{
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                padding: var(--size-0);
                opacity: 0;
                background-color: rgba(0, 0, 0, 0.1);
                border-radius: var(--radius-3);
                z-index: 10;
            }
            .{$this->Name} .item:not(.sender) .sidebtn {
                right: -50px; /* Position to the left of sent message */
            }
            .{$this->Name} .item.sender .sidebtn {
                left: -50px; /* Position to the right of received message */
            }
            
            /* Show buttons on bubble hover */
            .{$this->Name} div.item:hover > .sidebtn{
                opacity: 1;
            }
            .{$this->Name} .item .sidebtn > * {
                background-color: transparent;
                padding: 4px;
                font-size: var(--size-1);
                opacity: 0.8;
            }
            .{$this->Name} .item .sidebtn > *:hover {
                opacity: 1;
            }
            .{$this->Name} div.item .replies {
                margin-top: var(--size-1);
                padding-top: var(--size-1);
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                align-self: stretch;
            }
        ");
    }

    public function Get($items = null)
    {
        return join(PHP_EOL, iterator_to_array((function () use ($items) {
            $i = 0;
            yield $this->GetTitle();
            yield $this->GetDescription();
            $adminaccess = \_::$User->GetAccess(\_::$User->AdminAccess);
            $current_user_id = \_::$User->Id;
            $items = Convert::ToItems($items ?? $this->Items);

            foreach ($items as $k => $item) {
                $p_userid = get($item, "UserId");
                $p_status = get($item, "Status");
                $is_sender = $p_userid && $p_userid == $current_user_id;

                if (!\_::$User->GetAccess(getValid($item, 'Access', \_::$User->VisitAccess)))
                    continue;
                if (isValid($this->Relation) && $this->Relation != get($item, 'Relation'))
                    continue;

                $p_id = get($item, 'Id');
                $p_image = getValid($item, 'Image', $this->DefaultImage);
                $p_message = getValid($item, 'Content', $this->DefaultDescription);
                $p_attach = Convert::FromJson(getValid($item, 'Attach', $this->DefaultContent));
                $p_email = get($item, 'Contact');
                $p_rootid = get($item, 'RootId'); // For quoting/replying

                $p_showmessage = $this->AllowContent;
                $p_showattach = $this->AllowAttach;
                $p_showimage = isValid($p_image) && $this->AllowImage;
                $p_showmeta = $this->AllowMetaData;
                $p_referring = $this->AutoReferring;

                $updateaccess = $adminaccess || ($p_email && get(\_::$User, "Email") == $p_email) || ($p_userid && $p_userid == $current_user_id);

                // Button Labels (same as before)
                $p_replybuttontext = !$this->AllowButtons ? null : __($this->ReplyButtonLabel);
                $p_showreplybutton = !isEmpty($p_replybuttontext);
                $p_editbuttontext = !$updateaccess || !$this->AllowButtons ? null : __($this->EditButtonLabel);
                $p_showeditbutton = !isEmpty($p_editbuttontext);
                $p_deletebuttontext = !$updateaccess || !$this->AllowButtons ? null : __($this->DeleteButtonLabel);
                $p_showdeletebutton = !isEmpty($p_deletebuttontext);

                // Status Icon: Check-double for 'read' (Status=1) vs Check-single for 'sent' (Status=0)
                $p_status_icon = $this->AllowStatus && $is_sender ?
                    Struct::Span($p_status ? "<i class='fa fa-check-double'></i>" : "<i class='fa fa-check'></i>", ["class" => "status-icon"]) :
                    null;
                $uid = "m_$p_id";

                // --- 1. START ITEM DIV ---
                if ($i % $this->MaximumColumns === 0)
                    yield "<div>";

                yield "<div id='$uid' class='item " . ($p_status ? "" : "deactive") . " " . ($is_sender ? "sender" : "") . "'" . ($this->Animation ? " data-aos='{$this->Animation}'" : "") . ">";

                // --- 2. SIDE BUTTONS (Hidden/Hovered Context Menu) ---
                if ($p_showreplybutton || $p_showeditbutton || $p_showdeletebutton)
                    yield Struct::Division(
                        ($p_showdeletebutton ? Struct::Button($p_deletebuttontext, "{$this->Name}_Delete(this, '.{$this->Name} #{$uid}', $p_id);") : null) .
                        ($p_showeditbutton ? Struct::Button($p_editbuttontext, "{$this->Name}_Edit(this, '.{$this->Name} #{$uid}', $p_id);") : null) .
                        ($p_showreplybutton ? Struct::Button($p_replybuttontext, "{$this->Name}_Reply(this, '.{$this->Name} #{$uid}', $p_id);") : null),
                        ["class" => 'sidebtn']
                    );

                // --- 3. QUOTED REPLY (If RootId is present) ---
                if ($this->AllowReplies && $p_rootid) {
                    $parent_message = take($items, fn($v) => $v["Id"] == $p_rootid) ?? ["Id" => $p_rootid, "Name" => "Unknown User", "Content" => "Original message not found."];
                    $quoted_author = get($parent_message, "Name");
                    $quoted_text = Convert::ToExcerpt(Convert::ToText(getValid($parent_message, "Content", "")), 0, 50, "...");

                    yield Struct::Span(
                        ($this->AllowAuthor && $quoted_author? Struct::Span($quoted_author, null, ["class" => "author"]) : "") .
                        Struct::Span($quoted_text, null, ["class" => "content"]),
                        "#m_$p_rootid",
                        ["class" => "quote"]
                    );
                }

                // --- 4. MESSAGE CONTENT ---
                yield "<div class='message'>";
                if ($this->AllowAuthor && $p_author = get($item, "Name"))
                    yield Struct::Span($p_author, null, ["class" => "author"]);

                // Attached Image
                if ($p_showimage && isValid($p_image)) {
                    yield Struct::Division(Struct::Image($p_id, $p_image), ["class" => 'media-container']);
                }

                // Main Text Content
                if ($p_showmessage && !isEmpty($p_message))
                    yield "<div class='full'>" . __(Struct::Convert($p_message), referring: $p_referring) . "</div>";

                // Attached Content
                if ($p_showattach && isValid($p_attach))
                    yield "<div class='attach'>" . Struct::Convert($p_attach) . "</div>";

                yield "</div>";

                // --- 5. METADATA (Time & Status Icon) ---
                if ($p_showmeta) {
                    $p_meta_content = null;
                    if ($this->AllowUpdateTime)
                        doValid(
                            function ($val) use (&$p_meta_content) {
                                if (isValid($val))
                                    $p_meta_content .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'createtime']);
                            },
                            $item,
                            'UpdateTime'
                        );
                    elseif ($this->AllowCreateTime)
                        doValid(
                            function ($val) use (&$p_meta_content) {
                                if (isValid($val))
                                    $p_meta_content .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'createtime']);
                            },
                            $item,
                            'CreateTime'
                        );
                    if ($p_status_icon)
                        $p_meta_content .= $p_status_icon;

                    if (isValid($p_meta_content))
                        yield Struct::Division($p_meta_content, ["class" => 'metadata']);
                }

                // --- 6. REPLY FORM BOX (Always present for Reply button logic) ---
                yield Struct::Division("", ["class" => "reply-box"]);

                // --- 7. END ITEM DIV ---
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
        $action = $this->Action ? Script::Convert($this->Action) : "null";
        return Struct::Script(
            "
            {$this->Name} = document.querySelector('.{$this->Name}');
            {$this->Name}.scrollTo(0,{$this->Name}.scrollHeight)
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