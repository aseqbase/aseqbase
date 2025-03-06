<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Post extends Module
{
     public $RootPath = null;

     public $Tag = "article";

     /**
      * The whole document Item
      * @var object|array|null
      */
     public $Item = null;
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
      * @example ["News"=>"Find More: ", "Article"=>"Keywords: ", "Document: "=>"Keywords: ", "Post"=>"", "Default"=>"Tags: "]
      * @category Parts
      */
     public $TagsLabel = ["News" => "<h6>Find More: </h6>", "Article" => "<h6>Keywords: </h6>", "Document: " => "<h6>Keywords: </h6>", "Post" => "", "Default" => "<h6>Tags: </h6>"];
     /**
      * Order of Tags to show
      * @var array|int
      * @example ["News"=>10, "Default"=>5]
      * @category Parts
      */
     public $TagsOrder = ["News" => "`CreateTime` DESC", "Post" => "`CreateTime` DESC", "Default" => ""];
     /**
      * Maximum number of Tags to show
      * @var array|int
      * @example ["News"=>10, "Default"=>5]
      * @category Parts
      */
     public $TagsCount = ["News" => 20, "Article" => 10, "Default" => 15];

     /**
      * @var bool
      * @category Parts
      */
     public $ShowRelateds = true;
     /**
      * The label text of Related posts
      * @var string|null
      * @example ["News"=>"Read Also: ", "Default"=>"Relateds: "]
      * @category Parts
      */
     public $RelatedsLabel = ["News" => "<h5>Read Also: </h5>", "Default" => "<h5>Relateds: </h5>"];
     /**
      * Order of Related posts to show
      * @var array|int
      * @example ["News"=>10, "Default"=>5]
      * @category Parts
      */
     public $RelatedsOrder = ["News" => "`CreateTime` DESC", "Post" => "`CreateTime` DESC", "Default" => "`UpdateTime` DESC"];
     /**
      * Maximum number of Related posts to show
      * @var array|int
      * @example ["News"=>10, "Default"=>5]
      * @category Parts
      */
     public $RelatedsCount = ["News" => 10, "Default" => 5];

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
	public $CommentsLimitation = "ORDER BY `CreateTime` DESC";
     public $CommentType = "texts";

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
      * @example ["News"=>"Read Also: ", "Default"=>"Relateds: "]
      * @category Parts
      */
     public $AttachesLabel = ["Default" => "<h5>Attaches:</h5>"];
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
      * @example ["News"=>"Source" ,"Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","Default"=>"Visit"]
      * @category Excerption
      */
     public $MoreButtonLabel = ["News" => "Source", "Post" => "Refer", "Text" => "Refer", "File" => "Download File", "Document" => "Download Document", "Video" => "Watch", "Audio" => "Listen", "Image" => "Look", "Default" => "Visit"];

     public $Template = ["Default" => null];


     function __construct()
     {
          parent::__construct();
          $this->LeaveComment = \_::$Config->AllowWriteComment;
          $this->LeaveCommentAccess = \_::$Config->WriteCommentAccess;
          $this->ShowComments = \_::$Config->AllowReadComment;
          $this->ShowCommentsAccess = \_::$Config->ReadCommentAccess;
          $this->RootPath = $this->RootPath??\_::$Address->ContentPath;
     }

     public function GetStyle()
     {
          $ralign = \_::$Back->Translate->Direction == "RTL" ? "left" : "right";
          return Html::Style("
			.{$this->Name} {
				height: fit-content;
				background-Color: var(--back-color-0);
				color: var(--fore-color-0);
                    margin-top: var(--size-3);
                    margin-bottom: var(--size-3);
                    pa`dding: var(--size-4);
				font-size:  var(--size-0);
				box-shadow:  var(--shadow-1);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

			.{$this->Name} .head{
				margin-bottom: var(--size-2);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

			.{$this->Name} .title{
                padding: 0px;
                margin: 0px;
				text-align: unset;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .metadata{
				font-size: var(--size-0);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .metadata .route{
				padding-$ralign: var(--size-0);
			}
			.{$this->Name} .more{
				text-align: $ralign;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .more>a{
            	opacity: 0;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name}:hover .more>a{
            	opacity: 1;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .tags a{
                line-height: 100%;
				background-Color: inherit;
				color: inherit;
            	padding: calc(var(--size-0) / 5) calc(var(--size-0) / 4);
				border-radius: var(--radius-1);
            	margin: calc(var(--size-0) / 3);
			}
			.{$this->Name} .relateds a{
                display: block;
				background-Color: inherit;
				color: inherit;
				text-align: initial;
            	padding: calc(var(--size-0) / 3) calc(var(--size-0) / 2);
            	margin: calc(var(--size-0) / 3) 0px;
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
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .description{
            	font-size: var(--size-2);
               gap: var(--size-2);
            	text-align: justify;
				position: relative;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} .content{
               font-size: var(--size-1);
               text-align: justify;
            	background-Color: var(--back-color-0);
				padding-top: var(--size-3);
				padding-bottom: var(--size-3);
			}
        ");
     }

     public function BeforeHandle()
     {
          $item = $this->Item;
          $p_type = get($item, 'Type' );
          $p_class = get($item, 'class');
          $this->Class = "$p_type $p_class container";
          if ($this->Animation)
               $this->Attributes = "data-aos='{$this->Animation}'";
     }

     public function Get()
     {
          return Convert::ToString(function () {
               $item = $this->Item;
               $p_access = findValid($item, 'Access' , 0);
               $p_status = intval(findValid($item, 'Status' , 1));
               if ($p_status < 1 || !auth($p_access))
                    return;
               module("Image" );
               $p_type = get($item, 'Type' );
               $p_id = get($item, 'Id' );
               $p_image = findValid($item, 'Image' , $this->Image);
               $p_name = findBetween($item, 'Name', 'Title')?? $this->Title;
               $p_title = findValid($item, 'Title' , $p_name);
               $p_description = findValid($item, 'Description' , $this->Description);
               $p_content = findValid($item, 'Content' , $this->Content);
               $p_tags = Convert::FromJson(get($item, 'TagIds' ));
               $p_attaches = Convert::FromJson(get($item, 'Attach' ));

               if ($this->ShowRoute)
                    module("Route");
               $p_meta = findValid($item, 'MetaData' , null);
               if ($p_meta !== null)
                    $p_meta = Convert::FromJson($p_meta);
               $this->UpdateOptions($p_id, $p_meta);
               $p_meta = null;
               $p_showexcerpt = $this->ShowExcerpt;
               $p_showcontent = $this->ShowContent;
               $p_showdescription = $this->ShowDescription;
               $p_showimage = $this->ShowImage;
               $p_showtitle = $this->ShowTitle;
               $p_showmeta = $this->ShowMetaData;
               $p_inselflink = $this->RootPath . ($p_name ?? $p_id);
               if (!$this->CompressPath) {
                    $catDir = \_::$Back->Query->GetContentCategoryDirection($item);
                    if (isValid($catDir))
                         $p_inselflink = $this->RootPath . trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
               }
               $p_path = (!$p_showcontent && (!$p_showexcerpt || !$p_showdescription)) ? [$p_inselflink] : Convert::FromJson(findValid($item, 'Path' , $this->Path));
               $hasl = !isEmpty($p_path);
               $p_showmorebutton = $hasl && $this->ShowMoreButton;
               $p_morebuttontext = $p_showmorebutton ? __(Convert::FromSwitch($this->MoreButtonLabel, $p_type)) : "";
               $p_showtags = $this->ShowTags && !isEmpty($p_tags);
               $p_tagstext = $p_showtags ? __(Convert::FromSwitch($this->TagsLabel, $p_type)) : "";
               $p_tagscount = $p_showtags ? Convert::FromSwitch($this->TagsCount, $p_type) : "";
               $p_tagsorder = $p_showtags ? Convert::FromSwitch($this->TagsOrder, $p_type) : "";
               $p_showattaches = $this->ShowAttaches && !isEmpty($p_attaches);
               $p_attachestext = $p_showattaches ? __(Convert::FromSwitch($this->AttachesLabel, $p_type)) : "";
               $p_showcomments = auth($this->ShowCommentsAccess) && $this->ShowComments;
               $p_leavecomment = $this->LeaveComment;
               $p_showrelateds = $this->ShowRelateds && !isEmpty($p_tags);
               $p_relatedstext = $p_showrelateds ? __(Convert::FromSwitch($this->RelatedsLabel, $p_type)) : "";
               $p_relatedscount = $p_showrelateds ? Convert::FromSwitch($this->RelatedsCount, $p_type) : "";
               $p_relatedsorder = $p_showrelateds ? Convert::FromSwitch($this->RelatedsOrder, $p_type) : "";
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
               $createTime = get($item, 'CreateTime' );
               $modifyTime = get($item, 'UpdateTime' );
               if ($p_showmeta) {
                    if ($this->ShowAuthor)
                         doValid(
                              function ($val) use (&$p_meta) {
                                   $authorName = table("User")->DoSelectRow("Signature , Name", "Id=:Id", [":Id" => $val]);
                                   if (!isEmpty($authorName))
                                        $p_meta .= " " . Html::Link($authorName["Name" ], \_::$Address->UserPath . $authorName["Signature" ], ["class"=> "author"]);
                              },
                              $item,
                              'AuthorId'
                         );
                    if ($this->ShowCreateTime)
                         if (isValid($createTime))
                              $p_meta .= " <span class='createtime'>".Convert::ToShownDateTimeString($createTime)."</span>";
                    if ($this->ShowUpdateTime)
                         if (isValid($modifyTime))
                              $p_meta .= " <span class='updatetime'>".Convert::ToShownDateTimeString($modifyTime)."</span>";
                    if ($this->ShowStatus)
                         doValid(
                              function ($val) use (&$p_meta) {
                                   if (isValid($val))
                                        $p_meta .= " <span class='status'>$val</span>";
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

               component("JsonLD");
               yield \MiMFa\Component\JsonLD::Article(
                    title: __($p_title, styling: false),
                    excerpt: __($p_description, styling: false),
                    image: $p_image,
                    author: ["Name" => $authorName],
                    datePublished: explode(" ", $createTime)[0],
                    dateModified: explode(" ", $modifyTime)[0]
               );

               yield Html::Rack(
                    Html::MediumSlot(function () use ($p_showtitle, $hasl, $p_inselflink, $p_title, $p_meta, $p_showmeta) {
                         $lt = $this->LinkedTitle && $hasl;
                         if ($p_showtitle)
                              yield Html::ExternalHeading($p_title, $lt ? $p_inselflink : null, ['class'=> 'title']);
                         if ($p_showmeta && isValid($p_meta)) {
                              yield "<sub class='metadata'>";
                              if ($this->ShowRoute) {
                                   $route = new \MiMFa\Module\Route($p_inselflink);
                                   $route->Tag = "span";
                                   $route->Class = "route";
                                   yield $route->ToString();
                              }
                              yield $p_meta . "</sub>";
                         }
                    }) .
                    ($p_showmorebutton ? Html::SmallSlot(
                         loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                              return Html::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class"=> "btn btn-outline"]); })
                         ,
                         ["class"=> "more col-3 md-hide"]
                    ) : "")
                    ,
                    ["class"=> "head"]
               );
               yield Html::Rack(
                    Html::MediumSlot(function () use ($p_description, $p_excerpt, $p_showdescription, $p_showexcerpt, $p_refering) {
                         if ($p_showdescription)
                              yield __($p_description, refering: $p_refering);
                         if ($p_showexcerpt)
                              yield $p_excerpt;
                    }, ["class"=> "excerpt"]) .
                    ($p_showimage && isValid($p_image) ? Html::Division(Html::Image($p_title, $p_image), ["class"=> "col-lg-5", "style" => "text-align: center;"]) : "")
                    ,
                    ["class"=> "description"]
               );
               if ($p_showcontent && isValid($p_content))
                    yield Html::Division(__($p_content, refering: $p_refering), ["class"=> "content"]);
               switch ($p_template) {
                    case "Media":
                    case "Image":
                    case "Audio":
                    case "Video":
                    case "Course":
                         module("MediaFrame");
                         if ($p_showmorebutton)
                              yield join(PHP_EOL, loop($p_path, action: function ($k, $v, $i) use ($p_image, $p_morebuttontext) {
                                   return (new MediaFrame($v, logo: $p_image, name: is_numeric($k) ? $p_morebuttontext : $k))->DoRender();
                              })) . Html::Division(loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                                   return Html::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class"=> "btn btn-block btn-outline"]); }), ["class"=> "more md-show"]);
                         break;
                    default:
                         if ($p_showmorebutton)
                              yield Html::Division(loop($p_path, function ($k, $v, $i) use ($p_morebuttontext) {
                                   return Html::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class"=> "btn btn-block btn-outline"]); }), ["class"=> "more md-show"]);
                         break;
               }
               if ($p_showattaches)
                    yield Html::Division($p_attachestext . Html::Convert($p_attaches));
               if ($p_showtags) {
                    $tags = table("Tag")->DoSelectPairs("Name" , "Title" , "`Id` IN (" . join(",", $p_tags) . ") " . (isEmpty($p_tagsorder) ? "" : "ORDER BY $p_tagsorder") . " LIMIT $p_tagscount");
                    if (count($tags) > 0)
                         yield Html::$HorizontalBreak . Html::Division($p_tagstext . join(PHP_EOL, loop(
                              $tags,
                              function ($k, $v, $i) {
                                   return Html::Link(
                                        isValid($v)
                                        ? __(strtolower(preg_replace("/\W*/", "", $k)) != strtolower(preg_replace("/\W*/", "", $v)) ? "$v ($k)" : $v, styling: false)
                                        : $k
                                        ,
                                        \_::$Address->TagPath.$k,
                                        ["class"=> "btn"]
                                   );
                              }
                         )), ["class"=> "tags"]);
               }
               if ($p_showcomments)
                    yield $this->GetCommentsCollection($p_id);
               if ($p_leavecomment)
                    yield $this->GetCommentForm($p_id);
               if ($p_showrelateds) {
                    $rels = table("Content" )->DoSelectPairs("Id" , "Title" , "`Id`!=$p_id AND (`Title` IS NOT NULL AND `Title`!='') AND `TagIds` REGEXP '\\D(" . join("|", $p_tags) . ")\\D'" . (isEmpty($p_relatedsorder) ? "" : " ORDER BY $p_relatedsorder") . " LIMIT $p_relatedscount");
                    if (count($rels) > 0)
                         yield Html::$HorizontalBreak . Html::Division($p_relatedstext . join(PHP_EOL, loop(
                              $rels,
                              function ($k, $v, $i) {
                                   return Html::Link(isValid($v) ? $v : $k, $this->RootPath.$k, ["class"=> "btn"]); }
                         )), ["class"=> "relateds"]);
               }
          });
     }

     public function UpdateOptions($relatedId, $metadata)
     {
          $this->ShowExcerpt = findValid($metadata, "ShowExcerpt", $this->ShowExcerpt);
          $this->ShowContent = findValid($metadata, "ShowContent", $this->ShowContent);
          $this->ShowDescription = findValid($metadata, "ShowDescription", $this->ShowDescription);
          $this->ShowImage = findValid($metadata, "ShowImage", $this->ShowImage);
          $this->ShowTitle = findValid($metadata, "ShowTitle", $this->ShowTitle);
          $this->ShowMetaData = findValid($metadata, "ShowMetaData", $this->ShowMetaData);
          $this->ShowMoreButton = findValid($metadata, "ShowMoreButton", $this->ShowMoreButton);
          $this->MoreButtonLabel = findValid($metadata, "MoreButtonLabel", $this->MoreButtonLabel);
          $this->ShowTags = findValid($metadata, "ShowTags", $this->ShowTags);
          $this->TagsLabel = findValid($metadata, "TagsLabel", $this->TagsLabel);
          $this->TagsCount = findValid($metadata, "TagsCount", $this->TagsCount);
          $this->TagsOrder = findValid($metadata, "TagsOrder", $this->TagsOrder);
          $this->ShowAttaches = findValid($metadata, "ShowAttaches", $this->ShowAttaches);
          $this->AttachesLabel = findValid($metadata, "AttachesLabel", $this->AttachesLabel);
          $this->ShowCommentsAccess = findValid($metadata, "ShowCommentsAccess", $this->ShowCommentsAccess);
          $this->ShowComments = findValid($metadata, "ShowComments", $this->ShowComments);
          $this->LeaveCommentAccess = findValid($metadata, "LeaveCommentAccess", $this->LeaveCommentAccess);
          $this->LeaveComment = findValid($metadata, "LeaveComment", $this->LeaveComment);
          $this->CommentType = findValid($metadata, "CommentType", $this->CommentType);
          $this->ShowRelateds = findValid($metadata, "ShowRelateds", $this->ShowRelateds);
          $this->RelatedsLabel = findValid($metadata, "RelatedsLabel", $this->RelatedsLabel);
          $this->RelatedsCount = findValid($metadata, "RelatedsCount", $this->RelatedsCount);
          $this->RelatedsOrder = findValid($metadata, "RelatedsOrder", $this->RelatedsOrder);
          $this->AutoRefering = findValid($metadata, "AutoRefering", $this->AutoRefering);
          $this->Template = findValid($metadata, "Template", $this->Template);
     }
     public function GetCommentsCollection($relatedId)
     {
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
          module("CommentForm");
          $cc = new CommentForm();
          $cc->MessageType = $this->CommentType;
          $cc->Access = $this->LeaveCommentAccess;
          $cc->Relation = $relatedId;
          $cc->SubjectLabel =
               $cc->AttachLabel =
               null;
          return Html::$HorizontalBreak . $cc->Handle();
     }
}
?>