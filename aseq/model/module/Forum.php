<?php
namespace MiMFa\Module;
use DateTime;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
module("Content");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Forum extends Content
{
     public $Root = "/forum/";

     public $CommentTitle = "Leave Your Thought";
     public $CommentDescription = "Tell us what is your idea!";
     public $CommentType = "content";
     public $CommentAttachType = "array";
     public DateTime|null $ShowCommentStartTime = null;
     public DateTime|null $ShowCommentEndTime = null;
     public DateTime|null $LeaveCommentStartTime = null;
     public DateTime|null $LeaveCommentEndTime = null;
     /**
      * @var string|null
      * @category Management
      */
     public $CommentsLimitation = "ORDER BY `CreateTime` DESC, `UpdateTime` DESC";


     function __construct()
     {
          parent::__construct();
          $this->CommentForm->Access = \_::$User->WriteCommentAccess;
          $this->CommentForm->Title = $this->CommentTitle;
          $this->CommentForm->Description = $this->CommentDescription;
          $this->CommentForm->MessageType = $this->CommentType;
          $this->CommentForm->AttachType = $this->CommentAttachType;
          $this->CommentForm->NameLabel = "Name";
          $this->CommentForm->ContactLabel = "Email";
          $this->CommentForm->SubjectLabel = "Title";
          $this->CommentForm->SubjectPlaceHolder = "The title of your thought";
          $this->CommentForm->MessageLabel = "Description";
          $this->CommentForm->MessagePlaceHolder = "Describe your thought details here...";
          $this->CommentForm->AttachLabel = "Attachment";
          $this->CommentForm->AttachPlaceHolder = "Attach something";
     }

     public function GetInner()
     {
          $val = parent::GetInner();
          if ($this->LeaveCommentStartTime && Convert::ToDateTime() < $this->LeaveCommentStartTime)
               return $this->GetMessage(
                    Convert::ToShownDateTimeString() . " < " . Convert::ToShownDateTimeString($this->LeaveCommentStartTime),
                    "This forum will open later!"
               );
          if ($this->LeaveCommentEndTime && Convert::ToDateTime() > $this->LeaveCommentEndTime)
               return $this->GetMessage(
                    Convert::ToShownDateTimeString() . " > " . Convert::ToShownDateTimeString($this->LeaveCommentEndTime),
                    "This forum is not active anymore!"
               );
          return $val;
     }

     public function GetCommentsCollection()
     {
          if ($this->AllowComments && \_::$User->HasAccess($this->AllowCommentsAccess)) {
               if ($this->ShowCommentStartTime && Convert::ToDateTime() < $this->ShowCommentStartTime)
                    return null;
               if ($this->ShowCommentEndTime && Convert::ToDateTime() > $this->ShowCommentEndTime)
                    return null;
               module("CommentCollection");
               $cc = new CommentCollection();
               $cc->Items = table("Comment")->Select(
                    "*",
                    "Relation=:rid AND " . authCondition(checkStatus: false) . " " . $this->CommentsLimitation,
                    [":rid" => get($this->Item, 'Id')]
               );
               if (count($cc->Items) > 0)
                    return Struct::$BreakLine . $cc->ToString();
          }
          return null;
     }

     public function GetMessage($subject, $message, $icon = "clock")
     {
          return Struct::Division(Struct::Image(null, $icon) . Struct::Heading3($subject) . Struct::Message($message), ["class" => "be center"]);
     }

}