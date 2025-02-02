<?php
namespace MiMFa\Module;

use DateTime;
use MiMFa\Library\HTML;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\Translate;
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
MODULE("Post");
class Forum extends Post
{
     public $Root = "/forum/";

     public $CommentTitle = "Leave Your Thought";
     public $CommentDescription = "Tell us what is your idea!";
     public $CommentMultipleAttach = true;
     public $CommentAttachType = "text";
     public DateTime|null $ShowCommentStartTime = null;
     public DateTime|null $ShowCommentEndTime = null;
     public DateTime|null $LeaveCommentStartTime = null;
     public DateTime|null $LeaveCommentEndTime = null;

     function __construct()
     {
          parent::__construct();
          $this->LeaveComment = \_::$CONFIG->AllowWriteComment;
          $this->LeaveCommentAccess = \_::$CONFIG->WriteCommentAccess;
          $this->ShowComments = \_::$CONFIG->AllowReadComment;
          $this->ShowCommentsAccess = \_::$CONFIG->ReadCommentAccess;
     }

     public function UpdateOptions($relatedID, $metadata){
          parent::UpdateOptions($relatedID, $metadata);
          $this->LeaveComment = getValid($metadata,"LeaveComment", $this->LeaveComment);
          $this->LeaveCommentAccess = getValid($metadata,"LeaveCommentAccess", $this->LeaveCommentAccess);
          $this->ShowComments = getValid($metadata,"ShowComments", $this->ShowComments);
          $this->ShowCommentsAccess = getValid($metadata,"ShowCommentsAccess", $this->ShowCommentsAccess);

          $this->ShowCommentStartTime = getValid($metadata,"ShowCommentStartTime", $this->ShowCommentStartTime);
          $this->ShowCommentEndTime = getValid($metadata,"ShowCommentEndTime", $this->ShowCommentEndTime);
          $this->LeaveCommentStartTime = getValid($metadata,"LeaveCommentStartTime", $this->LeaveCommentStartTime);
          $this->LeaveCommentEndTime = getValid($metadata,"LeaveCommentEndTime", $this->LeaveCommentEndTime);
     
          $this->CommentTitle = getValid($metadata,"CommentTitle", $this->CommentTitle);
          $this->CommentDescription = getValid($metadata,"CommentDescription", $this->CommentDescription);
          $this->CommentAttachType = getValid($metadata,"CommentAttachType", $this->CommentAttachType);
          $this->CommentMultipleAttach = getValid($metadata,"CommentMultipleAttach", $this->CommentMultipleAttach);
     }

     public function GetCommentsCollection($relatedID)
     {
          if($this->ShowCommentStartTime !== null && \_::$CONFIG->GetDateTime() < $this->ShowCommentStartTime) return null;
          if($this->ShowCommentEndTime !== null && \_::$CONFIG->GetDateTime() > $this->ShowCommentEndTime) return null;
          MODULE("CommentCollection");
          $cc = new CommentCollection();
          $cc->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix . "Comment", "*",
           "Relation=:rid AND ".\MiMFa\Library\User::GetAccessCondition()." ".$this->CommentsLimitation, [":rid" => $relatedID]);
          if (count($cc->Items) > 0)
               return HTML::$HorizontalBreak . $cc->Capture();
          return null;
     }
     public function GetCommentForm($relatedID)
     {
          if($this->LeaveCommentStartTime !== null && \_::$CONFIG->GetDateTime() < $this->LeaveCommentStartTime) return null;
          if($this->LeaveCommentEndTime !== null && \_::$CONFIG->GetDateTime() > $this->LeaveCommentEndTime) return null;
          MODULE("CommentForm");
          $cc = new CommentForm();
          $cc->Template = "v";
          $cc->Relation = $relatedID;
          $cc->Title = $this->CommentTitle;
          $cc->Description = $this->CommentDescription;
          $cc->AttachType = $this->CommentAttachType;
          $cc->MultipleAttach = $this->CommentMultipleAttach;
          $cc->NameLabel = "Name";
          $cc->ContactLabel = "Email";
          $cc->SubjectLabel = "Title";
          $cc->SubjectPlaceHolder = "The title of your thought";
          $cc->MessageLabel = "Description";
          $cc->MessagePlaceHolder = "Describe your thought details here...";
          $cc->AttachLabel = $cc->MultipleAttach?"Attachments":"Attachment";
          $cc->AttachPlaceHolder = "Attach something";
          return HTML::$HorizontalBreak . $cc->Capture();
     }
}
?>