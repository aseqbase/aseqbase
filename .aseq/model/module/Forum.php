<?php
namespace MiMFa\Module;

use DateTime;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
module("Post");
class Forum extends Post
{
     public $RootPath = "/forum/";

     public $CommentTitle = "Leave Your Thought";
     public $CommentDescription = "Tell us what is your idea!";
     public $CommentType = "content";
     public $CommentAttachType = "array";
     public DateTime|null $ShowCommentStartTime = null;
     public DateTime|null $ShowCommentEndTime = null;
     public DateTime|null $LeaveCommentStartTime = null;
     public DateTime|null $LeaveCommentEndTime = null;

     public function UpdateOptions($relatedId, $metadata){
          parent::UpdateOptions($relatedId, $metadata);
          $this->LeaveComment = findValid($metadata,"LeaveComment", $this->LeaveComment);
          $this->LeaveCommentAccess = findValid($metadata,"LeaveCommentAccess", $this->LeaveCommentAccess);
          $this->ShowComments = findValid($metadata,"ShowComments", $this->ShowComments);
          $this->ShowCommentsAccess = findValid($metadata,"ShowCommentsAccess", $this->ShowCommentsAccess);

          $this->ShowCommentStartTime = findValid($metadata,"ShowCommentStartTime", $this->ShowCommentStartTime);
          $this->ShowCommentEndTime = findValid($metadata,"ShowCommentEndTime", $this->ShowCommentEndTime);
          $this->LeaveCommentStartTime = findValid($metadata,"LeaveCommentStartTime", $this->LeaveCommentStartTime);
          $this->LeaveCommentEndTime = findValid($metadata,"LeaveCommentEndTime", $this->LeaveCommentEndTime);
     
          $this->CommentTitle = findValid($metadata,"CommentTitle", $this->CommentTitle);
          $this->CommentDescription = findValid($metadata,"CommentDescription", $this->CommentDescription);
          $this->CommentAttachType = findValid($metadata,"CommentAttachType", $this->CommentAttachType);
     }

     public function GetCommentsCollection($relatedId)
     {
          if($this->ShowCommentStartTime !== null && Convert::ToDateTime() < $this->ShowCommentStartTime) return null;
          if($this->ShowCommentEndTime !== null && Convert::ToDateTime() > $this->ShowCommentEndTime) return null;
          module("CommentCollection");
          $cc = new CommentCollection();
          $cc->Items = table("Comment")->DoSelect("*",
           "Relation=:rid AND ".\MiMFa\Library\User::GetAccessCondition(false)." ".$this->CommentsLimitation, [":rid" => $relatedId]);
          if (count($cc->Items) > 0)
               return Html::$HorizontalBreak . $cc->ToString();
          return null;
     }
     public function GetCommentForm($relatedId)
     {
          if($this->LeaveCommentStartTime !== null && Convert::ToDateTime() < $this->LeaveCommentStartTime) return null;
          if($this->LeaveCommentEndTime !== null && Convert::ToDateTime() > $this->LeaveCommentEndTime) return null;
          module("CommentForm");
          $cc = new CommentForm();
          $cc->Relation = $relatedId;
          $cc->Title = $this->CommentTitle;
          $cc->Description = $this->CommentDescription;
          $cc->MessageType = $this->CommentType;
          $cc->AttachType = $this->CommentAttachType;
          $cc->NameLabel = "Name";
          $cc->ContactLabel = "Email";
          $cc->SubjectLabel = "Title";
          $cc->SubjectPlaceHolder = "The title of your thought";
          $cc->MessageLabel = "Description";
          $cc->MessagePlaceHolder = "Describe your thought details here...";
          $cc->AttachLabel = "Attachment";
          $cc->AttachPlaceHolder = "Attach something";
          return Html::$HorizontalBreak . $cc->ToString();
     }
}
?>