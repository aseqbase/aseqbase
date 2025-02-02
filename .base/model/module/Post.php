<?php
namespace MiMFa\Module;
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
class Post extends Module
{
     public $Capturable = true;
     public $Tag = "article";

     /**
      * The whole document Item
      * @var object|array|null
      */
     public $Item = null;
     /**
      * The root directory or path
      * @var string|null
      */
     public $Root = "/post/";
     public $CompressPath = false;

     /**
      * The default Path for more button reference
      * @var string|null
      */
     public $Path = null;
     /**
      * The featured image
      * @var string|null
      */
     public $Image = null;
     /**
      * The post buttons
      * @var string|null
      */
     public $Buttons = null;

     /**
      * The Width of Image
      * @var string
      */
     public $ImageWidth = "auto";
     /**
      * The Height of thumbnail preshow
      * @var string
      */
     public $ImageHeight = "auto";
     /**
      * The Minimum Width of Image
      * @var string
      */
     public $ImageMinWidth = "auto";
     /**
      * The Minimum Height of Image
      * @var string
      */
     public $ImageMinHeight = "10vmax";
     /**
      * The Maximum Width of Image
      * @var string
      */
     public $ImageMaxWidth = "100%";
     /**
      * The Maximum Height of thumbnail preshow
      * @var string
      */
     public $ImageMaxHeight = "40vh";

     /**
      * @var string|null
      * @category Part
      */
     public $Animation = "flip-up";

     /**
      * @var bool
      * @category Parts
      */
     public $ShowTitle = true;
     /**
      * Read more through clicking on the title
      * @var bool
      * @category Part
      */
     public $LinkedTitle = true;

     /**
      * @var bool
      * @category Parts
      */
     public $ShowMetaData = true;
     /**
      * @var bool
      * @category Parts
      */
     public $ShowRoute = true;

     /**
      * @var bool
      * @category Parts
      */
     public $ShowTags = true;
     /**
      * The label text of Tags
      * @var string|null
      * @example ["News"=>"Find More: ", "Article"=>"Keywords: ", "Document: "=>"Keywords: ", "Post"=>"", "default"=>"Tags: "]
      * @category Parts
      */
     public $TagsLabel = ["News" => "<h6>Find More: </h6>", "Article" => "<h6>Keywords: </h6>", "Document: " => "<h6>Keywords: </h6>", "Post" => "", "default" => "<h6>Tags: </h6>"];
     /**
      * Order of Tags to show
      * @var array|int
      * @example ["News"=>10, "default"=>5]
      * @category Parts
      */
     public $TagsOrder = ["News" => "CreateTime DESC", "Post" => "CreateTime DESC", "default" => ""];
     /**
      * Maximum number of Tags to show
      * @var array|int
      * @example ["News"=>10, "default"=>5]
      * @category Parts
      */
     public $TagsCount = ["News" => 20, "Article" => 10, "default" => 15];

     /**
      * @var bool
      * @category Parts
      */
     public $ShowRelateds = true;
     /**
      * The label text of Related posts
      * @var string|null
      * @example ["News"=>"Read Also: ", "default"=>"Relateds: "]
      * @category Parts
      */
     public $RelatedsLabel = ["News" => "<h5>Read Also: </h5>", "default" => "<h5>Relateds: </h5>"];
     /**
      * Order of Related posts to show
      * @var array|int
      * @example ["News"=>10, "default"=>5]
      * @category Parts
      */
     public $RelatedsOrder = ["News" => "CreateTime DESC", "Post" => "CreateTime DESC", "default" => "UpdateTime DESC"];
     /**
      * Maximum number of Related posts to show
      * @var array|int
      * @example ["News"=>10, "default"=>5]
      * @category Parts
      */
     public $RelatedsCount = ["News" => 10, "default" => 5];

     /**
      * @var bool
      * @category Parts
      */
     public $ShowComments = true;
     /**
      * @var int
      * @category Parts
      */
     public $ShowCommentsAccess = 0;
     /**
      * @var bool
      * @category Parts
      */
     public $LeaveComment = true;
     /**
      * @var bool
      * @category Parts
      */
     public $LeaveCommentAccess = 0;
	/**
     * @var string|null
     * @category Management
     */
	public $CommentsLimitation = "ORDER BY CreateTime DESC";

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
     public $ShowButtons = true;
     /**
      * @var bool
      * @category Parts
      */
     public $ShowAttaches = true;
     /**
      * The label text of Attaches part
      * @var string|null
      * @example ["News"=>"Read Also: ", "default"=>"Relateds: "]
      * @category Parts
      */
     public $AttachesLabel = ["default" => "<h5>Attaches:</h5>"];
     /**
      * @var bool
      * @category Parts
      */
     public $ShowImage = true;
     /**
      * @var bool
      * @category Parts
      */
     public $ShowContent = true;
     /**
      * @var bool
      * @category Parts
      */
     public $ShowDescription = true;
     /**
      * @var bool
      * @category Excerption
      */
     public $ShowExcerpt = false;
     /**
      * Selected Excerpts text automatically
      * @var bool
      * @category Excerption
      */
     public $AutoExcerpt = true;
     /**
      * Allow to analyze all text and linking categories and tags to their descriptions, to improve the website's SEO
      * @var mixed
      */
     public $AutoRefering = true;
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
      * @category Excerption
      */
     public $ShowMoreButton = true;
     /**
      * The label text of More button
      * @var string|null
      * @example ["News"=>"Source","Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","default"=>"Visit"]
      * @category Excerption
      */
     public $MoreButtonLabel = ["News" => "Source", "Post" => "Refer", "Text" => "Refer", "File" => "Download File", "Document" => "Download Document", "Video" => "Watch", "Audio" => "Listen", "Image" => "Look", "default" => "Visit"];

     public $Template = ["default" => null];


     function __construct()
     {
          parent::__construct();
          $this->LeaveComment = \_::$CONFIG->AllowWriteComment;
          $this->LeaveCommentAccess = \_::$CONFIG->WriteCommentAccess;
          $this->ShowComments = \_::$CONFIG->AllowReadComment;
          $this->ShowCommentsAccess = \_::$CONFIG->ReadCommentAccess;
     }

     public function GetStyle()
     {
          $ralign = Translate::$Direction == "RTL" ? "left" : "right";
          return HTML::Style("
			.{$this->Name} {
				height: fit-content;
				background-Color: var(--BackColor-0);
				color: var(--ForeColor-0);
                margin-top: var(--Size-3);
                margin-bottom: var(--Size-3);
            	padding: var(--Size-4);
				font-size:  var(--Size-0);
				box-shadow:  var(--Shadow-1);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}

			.{$this->Name} .head{
				margin-bottom: var(--Size-2);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}

			.{$this->Name} .title{
                padding: 0px;
                margin: 0px;
				text-align: unset;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .metadata{
				font-size: var(--Size-0);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .metadata .route{
				padding-$ralign: var(--Size-0);
			}
			.{$this->Name} .more{
				text-align: $ralign;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .more>a{
            	opacity: 0;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name}:hover .more>a{
            	opacity: 1;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .tags a{
                line-height: 100%;
				background-Color: inherit;
				color: inherit;
            	padding: calc(var(--Size-0) / 5) calc(var(--Size-0) / 4);
				border-radius: var(--Radius-1);
            	margin: calc(var(--Size-0) / 3);
			}
			.{$this->Name} .relateds a{
                display: block;
				background-Color: inherit;
				color: inherit;
				text-align: initial;
            	padding: calc(var(--Size-0) / 3) calc(var(--Size-0) / 2);
            	margin: calc(var(--Size-0) / 3) 0px;
			}
			/* Style the images inside the grid */
			.{$this->Name} .image {
				width: $this->ImageWidth;
				height: $this->ImageHeight;
				min-height: $this->ImageMinHeight;
				min-width: $this->ImageMinWidth;
				max-height: $this->ImageMaxHeight;
				max-width: $this->ImageMaxWidth;
				overflow: hidden;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .description{
            	font-size: var(--Size-2);
                gap: var(--Size-2);
            	text-align: justify;
				position: relative;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .content{
                font-size: var(--Size-1);
                text-align: justify;
            	background-Color: var(--BackColor-0);
				padding-top: var(--Size-3);
				padding-bottom: var(--Size-3);
			}
        ");
     }

     public function PreCapture()
     {
          $item = $this->Item;
          $p_type = getValid($item, 'Type');
          $p_class = getValid($item, 'Class');
          $this->Class = "$p_type $p_class container";
          if ($this->Animation)
               $this->Attributes = "data-aos='{$this->Animation}'";
     }

     public function Get()
     {
          return Convert::ToString(function () {
               $item = $this->Item;
               $p_access = getValid($item, 'Access', 0);
               $p_status = intval(getValid($item, 'Status', 1));
               if ($p_status < 1 || !getAccess($p_access))
                    return;
               MODULE("Image");
               $p_type = getValid($item, 'Type');
               $p_id = getValid($item, 'ID');
               $p_image = getValid($item, 'Image', $this->Image);
               $p_name = getValid($item, 'Name') ?? getValid($item, 'Title', $this->Title);
               $p_title = getValid($item, 'Title', $p_name);
               $p_description = getValid($item, 'Description', $this->Description);
               $p_content = getValid($item, 'Content', $this->Content);
               $p_tags = Convert::FromJSON(getValid($item, 'TagIDs'));
               $p_attaches = Convert::FromJSON(getValid($item, 'Attach'));

               if ($this->ShowRoute)
                    MODULE("Route");
               $p_meta = getValid($item, 'MetaData', null);
               if ($p_meta !== null)
                    $p_meta = Convert::FromJSON($p_meta);
               $this->UpdateOptions($p_id, $p_meta);
               $p_meta = null;
               $p_showexcerpt = $this->ShowExcerpt;
               $p_showcontent = $this->ShowContent;
               $p_showdescription = $this->ShowDescription;
               $p_showimage = $this->ShowImage;
               $p_showtitle = $this->ShowTitle;
               $p_showmeta = $this->ShowMetaData;
               $p_inselflink = $this->Root . ($p_name ?? $p_id);
               if (!$this->CompressPath) {
                    LIBRARY("Query");
                    $catDir = \MiMFa\Library\Query::GetContentCategoryDirection($item);
                    if (isValid($catDir))
                         $p_inselflink = $this->Root . trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
               }
               $p_path = (!$p_showcontent && (!$p_showexcerpt || !$p_showdescription)) ? [$p_inselflink] : Convert::FromJSON(getValid($item, 'Path', $this->Path));
               $hasl = !isEmpty($p_path);
               $p_showmorebutton = $hasl && $this->ShowMoreButton;
               $p_morebuttontext = $p_showmorebutton ? __(Convert::FromSwitch($this->MoreButtonLabel, $p_type)) : "";
               $p_showtags = $this->ShowTags && !isEmpty($p_tags);
               $p_tagstext = $p_showtags ? __(Convert::FromSwitch($this->TagsLabel, $p_type)) : "";
               $p_tagscount = $p_showtags ? __(Convert::FromSwitch($this->TagsCount, $p_type)) : "";
               $p_tagsorder = $p_showtags ? __(Convert::FromSwitch($this->TagsOrder, $p_type)) : "";
               $p_showattaches = $this->ShowAttaches && !isEmpty($p_attaches);
               $p_attachestext = $p_showattaches ? __(Convert::FromSwitch($this->AttachesLabel, $p_type)) : "";
               $p_showcommentsaccess = $this->ShowCommentsAccess;
               $p_showcomments = getAccess($p_showcommentsaccess) && $this->ShowComments;
               $p_leavecommentaccess = $this->LeaveCommentAccess;
               $p_leavecomment = getAccess($p_leavecommentaccess) && $this->LeaveComment;
               $p_showrelateds = $this->ShowRelateds && !isEmpty($p_tags);
               $p_relatedstext = $p_showrelateds ? __(Convert::FromSwitch($this->RelatedsLabel, $p_type)) : "";
               $p_relatedscount = $p_showrelateds ? __(Convert::FromSwitch($this->RelatedsCount, $p_type)) : "";
               $p_relatedsorder = $p_showrelateds ? __(Convert::FromSwitch($this->RelatedsOrder, $p_type)) : "";
               $p_refering = $this->AutoRefering;
               $p_excerpt = null;
               $p_template = Convert::FromSwitch($this->Template, $p_type) ?? $p_type;
               if ($p_showexcerpt && $this->AutoExcerpt)
                    $p_excerpt = __(Convert::ToExcerpt(
                         $p_content,
                         0,
                         $this->ExcerptLength,
                         $this->ExcerptSign
                    ), refering: $p_refering);

               $authorName = null;
               $createTime = getValid($item, 'CreateTime');
               $modifyTime = getValid($item, 'UpdateTime');
               if ($p_showmeta) {
                    if ($this->ShowAuthor)
                         doValid(
                              function ($val) use (&$p_meta) {
                                   $authorName = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix . "User", "Signature , Name", "ID=:ID", [":ID" => $val]);
                                   if (!isEmpty($authorName))
                                        $p_meta .= " " . HTML::Link($authorName["Name"], "/user/" . $authorName["Signature"], ["class" => "Author"]);
                              },
                              $item,
                              'AuthorID'
                         );
                    if ($this->ShowCreateTime)
                         if (isValid($createTime))
                              $p_meta .= " <span class='CreateTime'>$createTime</span>";
                    if ($this->ShowUpdateTime)
                         if (isValid($modifyTime))
                              $p_meta .= " <span class='UpdateTime'>$modifyTime</span>";
                    if ($this->ShowStatus)
                         doValid(
                              function ($val) use (&$p_meta) {
                                   if (isValid($val))
                                        $p_meta .= " <span class='Status'>$val</span>";
                              },
                              $item,
                              'Status'
                         );
                    if ($this->ShowButtons)
                         doValid(
                              function ($val) use (&$p_meta) {
                                   if (isValid($val))
                                        $p_meta .= " " . $val;
                                   else
                                        $p_meta .= " " . $this->Buttons;
                              },
                              $item,
                              'Buttons'
                         );
               }

               COMPONENT("JSONLD");
               $mod = new \MiMFa\Component\JSONLD();
               yield $mod->GetArticle(
                    __($p_title, styling: false),
                    __($p_description, styling: false),
                    $p_image,
                    author: ["name" => $authorName],
                    datePublished: explode(" ", $createTime)[0],
                    dateModified: explode(" ", $modifyTime)[0]
               );

               yield HTML::Rack(
                    HTML::MediumSlot(function () use ($p_showtitle, $hasl, $p_inselflink, $p_title, $p_meta, $p_showmeta) {
                         $lt = $this->LinkedTitle && $hasl;
                         if ($p_showtitle)
                              yield HTML::ExternalHeading($p_title, $lt ? $p_inselflink : null, ['class' => 'title']);
                         if ($p_showmeta && isValid($p_meta)) {
                              yield "<sub class='metadata'>";
                              if ($this->ShowRoute) {
                                   $route = new \MiMFa\Module\Route($p_inselflink);
                                   $route->Tag = "span";
                                   $route->Class = "route";
                                   yield $route->ReCapture();
                              }
                              yield $p_meta . "</sub>";
                         }
                    }) .
                    ($p_showmorebutton ? HTML::SmallSlot(
                         loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                              return HTML::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn btn-outline"]); })
                         ,
                         ["class" => "more col-3 md-hide"]
                    ) : "")
                    ,
                    ["class" => "head"]
               );
               yield HTML::Rack(
                    HTML::MediumSlot(function () use ($p_description, $p_excerpt, $p_showdescription, $p_showexcerpt, $p_refering) {
                         if ($p_showdescription)
                              yield __($p_description, refering: $p_refering);
                         if ($p_showexcerpt)
                              yield $p_excerpt;
                    }, ["class" => "excerpt"]) .
                    ($p_showimage && isValid($p_image) ? HTML::Division(HTML::Image($p_title, $p_image), ["class" => "col-lg-5", "style" => "text-align: center;"]) : "")
                    ,
                    ["class" => "description"]
               );
               if ($p_showcontent && isValid($p_content))
                    yield HTML::Division(__($p_content, refering: $p_refering), ["class" => "content"]);
               switch ($p_template) {
                    case "Media":
                    case "Image":
                    case "Audio":
                    case "Video":
                    case "Course":
                         MODULE("MediaFrame");
                         if ($p_showmorebutton)
                              yield join(PHP_EOL, loop($p_path, function ($k, $v, $i) use ($p_image, $p_morebuttontext) {
                                   return (new MediaFrame($v, logo: $p_image, name: is_numeric($k) ? $p_morebuttontext : $k))->DoCapture();
                              })) . HTML::Division(loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                                   return HTML::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn btn-block btn-outline"]); }), ["class" => "more md-show"]);
                         break;
                    default:
                         if ($p_showmorebutton)
                              yield HTML::Division(loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                                   return HTML::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn btn-block btn-outline"]); }), ["class" => "more md-show"]);
                         break;
               }
               if ($p_showattaches)
                    yield HTML::Division($p_attachestext . Convert::ToHTML($p_attaches));
               if ($p_showtags) {
                    $tags = DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix . "Tag", "Name", "Title", "`ID` IN (" . join(",", $p_tags) . ") " . (isEmpty($p_tagsorder) ? "" : "ORDER BY $p_tagsorder") . " LIMIT $p_tagscount");
                    if (count($tags) > 0)
                         yield HTML::$HorizontalBreak . HTML::Division($p_tagstext . join(PHP_EOL, loop(
                              $tags,
                              function ($k, $v, $i) {
                                   return HTML::Link(
                                        isValid($v)
                                        ? __(strtolower(preg_replace("/\W*/", "", $k)) != strtolower(preg_replace("/\W*/", "", $v)) ? "$v ($k)" : $v, styling: false)
                                        : $k
                                        ,
                                        "/tag/$k",
                                        ["class" => "btn"]
                                   );
                              }
                         )), ["class" => "tags"]);
               }
               if ($p_showcomments)
                    yield $this->GetCommentsCollection($p_id);
               if ($p_leavecomment)
                    yield $this->GetCommentForm($p_id);
               if ($p_showrelateds) {
                    $rels = DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix . "Content", "ID", "Title", "`ID`!=$p_id AND (`Title` IS NOT NULL OR `Title`!='') AND `TagIDs` REGEXP '(\"" . join("\")|(\"", $p_tags) . "\")' " . (isEmpty($p_tagsorder) ? "" : "ORDER BY $p_relatedsorder") . " LIMIT $p_relatedscount");
                    if (count($rels) > 0)
                         yield HTML::$HorizontalBreak . HTML::Division($p_relatedstext . join(PHP_EOL, loop(
                              $rels,
                              function ($k, $v, $i) {
                                   return HTML::Link(isValid($v) ? $v : $k, "/post/$k", ["class" => "btn"]); }
                         )), ["class" => "relateds"]);
               }
          });
     }

     public function UpdateOptions($relatedID, $metadata)
     {
          $this->ShowExcerpt = getValid($metadata, "ShowExcerpt", $this->ShowExcerpt);
          $this->ShowContent = getValid($metadata, "ShowContent", $this->ShowContent);
          $this->ShowDescription = getValid($metadata, "ShowDescription", $this->ShowDescription);
          $this->ShowImage = getValid($metadata, "ShowImage", $this->ShowImage);
          $this->ShowTitle = getValid($metadata, "ShowTitle", $this->ShowTitle);
          $this->ShowMetaData = getValid($metadata, "ShowMetaData", $this->ShowMetaData);
          $this->ShowMoreButton = getValid($metadata, "ShowMoreButton", $this->ShowMoreButton);
          $this->MoreButtonLabel = getValid($metadata, "MoreButtonLabel", $this->MoreButtonLabel);
          $this->ShowTags = getValid($metadata, "ShowTags", $this->ShowTags);
          $this->TagsLabel = getValid($metadata, "TagsLabel", $this->TagsLabel);
          $this->TagsCount = getValid($metadata, "TagsCount", $this->TagsCount);
          $this->TagsOrder = getValid($metadata, "TagsOrder", $this->TagsOrder);
          $this->ShowAttaches = getValid($metadata, "ShowAttaches", $this->ShowAttaches);
          $this->AttachesLabel = getValid($metadata, "AttachesLabel", $this->AttachesLabel);
          $this->ShowCommentsAccess = getValid($metadata, "ShowCommentsAccess", $this->ShowCommentsAccess);
          $this->ShowComments = getValid($metadata, "ShowComments", $this->ShowComments);
          $this->LeaveCommentAccess = getValid($metadata, "LeaveCommentAccess", $this->LeaveCommentAccess);
          $this->LeaveComment = getValid($metadata, "LeaveComment", $this->LeaveComment);
          $this->ShowRelateds = getValid($metadata, "ShowRelateds", $this->ShowRelateds);
          $this->RelatedsLabel = getValid($metadata, "RelatedsLabel", $this->RelatedsLabel);
          $this->RelatedsCount = getValid($metadata, "RelatedsCount", $this->RelatedsCount);
          $this->RelatedsOrder = getValid($metadata, "RelatedsOrder", $this->RelatedsOrder);
          $this->AutoRefering = getValid($metadata, "AutoRefering", $this->AutoRefering);
          $this->Template = getValid($metadata, "Template", $this->Template);
     }
     public function GetCommentsCollection($relatedID)
     {
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
          MODULE("CommentForm");
          $cc = new CommentForm();
          $cc->Relation = $relatedID;
          $cc->SubjectLabel =
               $cc->AttachLabel =
               null;
          return HTML::$HorizontalBreak . $cc->Capture();
     }
}
?>