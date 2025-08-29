<?php
namespace MiMFa\Module;
use DateTime;
use MiMFa\Library\Html;
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
     public $RootRoute = "/forum/";

     public $CommentTitle = "Leave Your Thought";
     public $CommentDescription = "Tell us what is your idea!";
     public $CommentType = "content";
     public $CommentAttachType = "array";
     public DateTime|null $ShowCommentStartTime = null;
     public DateTime|null $ShowCommentEndTime = null;
     public DateTime|null $LeaveCommentStartTime = null;
     public DateTime|null $LeaveCommentEndTime = null;

     function __construct()
     {
          parent::__construct();
          $this->CommentForm->Access = \_::$Config->WriteCommentAccess;
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

     public function Get()
     {
          $val = parent::Get();
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
          if ($this->AllowComments && auth($this->AllowCommentsAccess)) {
               if ($this->ShowCommentStartTime && Convert::ToDateTime() < $this->ShowCommentStartTime)
                    return null;
               if ($this->ShowCommentEndTime && Convert::ToDateTime() > $this->ShowCommentEndTime)
                    return null;
               module("CommentCollection");
               $cc = new CommentCollection();
               $cc->Items = table("Comment")->Select(
                    "*",
                    "Relation=:rid AND " . \_::$Back->GetAccessCondition(checkStatus: false) . " " . $this->CommentsLimitation,
                    [":rid" => get($this->Item, 'Id')]
               );
               if (count($cc->Items) > 0)
                    return Html::$BreakLine . $cc->ToString();
          }
          return null;
     }

     public function GetMessage($subject, $message, $icon = "clock")
     {
          return Html::Division(Html::Image(null, $icon) . Html::Heading($subject) . Html::Result($message), ["class" => "be center"]);
     }

}
?>