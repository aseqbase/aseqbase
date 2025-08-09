<?php
namespace MiMFa\Module;
module("CommentForm");
use MiMFa\Library\Html;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
use MiMFa\Module\CommentForm;
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Content extends Module
{
     public $RootRoute = null;
     public $CollectionRoute = null;

     public $Tag = "article";
     public $Class = "container";
     public $CommentForm = null;
     /**
      * A Check Access function
      */
     public $CheckAccess = null;

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
     public $ShowDetails = true;
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
     public $RelatedsOrder = ["News" => "`CreateTime` DESC", "Post" => "`Priority` DESC, `CreateTime` DESC", "Default" => "`UpdateTime` DESC"];
     /**
      * Maximum number of Related posts to show
      * @var array|int
      * @example ["News"=>10, "Default"=>5]
      * @category Parts
      */
     public $RelatedsCount = ["News" => 10, "Default" => 5];

     public $LeaveComment = true;

     public $ModifyComment = true;
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
      * @var string|null
      * @category Management
      */
     public $CommentsLimitation = "ORDER BY `CreateTime` ASC, `UpdateTime` ASC";

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
      * The label text of More button
      * @var string|null
      * @example ["News"=>"Source" ,"Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","Default"=>"Visit"]
      * @category Excerption
      */
     public $ButtonsLabel = ["News" => "Source", "Post" => "Refer", "Text" => "Refer", "File" => "Download File", "Document" => "Download Document", "Video" => "Watch", "Audio" => "Listen", "Image" => "Look", "Default" => "Visit"];

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
     public $AutoExcerpt = false;
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
     public $Template = ["Default" => null];


     function __construct()
     {
          parent::__construct();
          $this->LeaveComment = \_::$Config->AllowWriteComment;
          $this->ShowComments = \_::$Config->AllowReadComment;
          $this->ShowCommentsAccess = \_::$Config->ReadCommentAccess;
          $this->RootRoute = $this->RootRoute ?? \_::$Address->ContentRoute;
          $this->CollectionRoute = $this->CollectionRoute ?? \_::$Address->ContentRoute;
          $this->CommentForm = new CommentForm();
          $this->CommentForm->MessageType = "texts";
          $this->CommentForm->Access = \_::$Config->WriteCommentAccess;
          $this->CommentForm->SubjectLabel =
               $this->CommentForm->AttachLabel =
               null;
          $this->CheckAccess = fn($item) => auth(getValid($item, 'Access', 0));
     }

     public function BeforeHandle()
     {
          $item = $this->Item;
          $p_type = get($item, 'Type');
          $p_class = get($item, 'class');
          $this->Class .= " $p_type $p_class";
          if ($this->Animation)
               $this->Attributes = "data-aos='{$this->Animation}'";
     }

     public function Set()
     {
          $p_meta = getValid($this->Item, 'MetaData', null);
          if ($p_meta !== null) {
               $p_meta = Convert::FromJson($p_meta);
               set($this, $p_meta);
               return true;
          }
          return false;
     }

     public function GetStyle()
     {
          $ralign = \_::$Back->Translate->Direction == "rtl" ? "left" : "right";
          return Html::Style("
			.{$this->Name} {
				height: fit-content;
				background-Color: var(--back-color-special);
				color: var(--fore-color-special);
                    margin-top: var(--size-3);
                    margin-bottom: var(--size-3);
                    padding: var(--size-4) var(--size-3);
				font-size:  var(--size-0);
				box-shadow:  var(--shadow-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name} .title{
				margin-bottom: var(--size-2);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name} .heading{
                    padding: 0px;
                    margin: 0px;
				text-align: unset;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .details{
				font-size: var(--size-0);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .details .route{
				padding-$ralign: var(--size-0);
			}
			.{$this->Name} .buttons{
				text-align: $ralign;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .buttons>.button{
            	opacity: 0;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}:hover .buttons>.button{
            	opacity: 1;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
			.{$this->Name} .description .image {
				width: $this->ImageWidth;
				height: $this->ImageHeight;
				min-height: $this->ImageMinHeight;
				min-width: $this->ImageMinWidth;
				max-height: $this->ImageMaxHeight;
				max-width: $this->ImageMaxWidth;
				overflow: hidden;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .description{
            	font-size: var(--size-2);
               gap: var(--size-2);
            	text-align: justify;
				position: relative;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .content{
               font-size: var(--size-1);
               text-align: justify;
				padding-top: var(--size-3);
				padding-bottom: var(--size-3);
			}
        ");
     }

     public function Get()
     {
          return Convert::ToString(function () {
               $this->Set();
               if (!$this->GetAccess())
                    return;

               yield $this->GetTitle();
               yield $this->GetDescription();
               yield $this->GetContent();
               yield $this->GetSpecial();
               yield $this->GetAttaches();
               yield $this->GetRelateds();
               yield $this->GetCommentsCollection();
               yield $this->GetTags();
               yield $this->GetCommentForm();
          });
     }

     public function GetAccess()
     {
          $p_status = intval(getValid($this->Item, 'Status', 1));
          return !($p_status < 1 || !($this->CheckAccess)($this->Item));
     }
     public function GetTitle($attributes = null)
     {
          $p_id = get( $this->Item, 'Id');
          $p_name = getValid( $this->Item, 'Name') ?? $p_id ?? $this->Title;
          $nameOrId = $p_id ?? $p_name;
          if (!$this->CompressPath) {
               $catDir = \_::$Back->Query->GetContentCategoryRoute( $this->Item);
               if (isValid($catDir))
                    $nameOrId = trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
          }
          return Html::Rack(
               Html::MediumSlot(
                    ($this->ShowTitle ? Html::ExternalHeading(getValid($this->Item, 'Title', $this->Title), $this->LinkedTitle ? $this->RootRoute . $nameOrId : null, ['class' => 'heading']) : "") .
                    $this->GetDetails($this->CollectionRoute . $nameOrId)
               ) .
               $this->GetButtons(),
               ["class" => "title"], $attributes
          );
     }
     public function GetDescription($attributes = null)
     {
          return Html::Rack(
               ($this->ShowDescription ? $this->GetExcerpt() : "") . $this->GetImage(),
               ["class" => "description"]
          , $attributes);
     }
     public function GetContent($attributes = null)
     {
          if (!$this->ShowContent)
               return null;
          $p_content = getValid($this->Item, 'Content', $this->Content);
          return (isValid($p_content) ? Html::Division(__(Html::Convert($p_content), refering: $this->AutoRefering), ["class" => "content"], $attributes) : null);
     }
     public function GetSpecial()
     {
          $paths = Convert::FromJson(getValid($this->Item, 'Path', $this->Path));
          if (isEmpty($paths))
               return null;
          $p_type = get($this->Item, 'Type');
          $p_morebuttontext = __(Convert::FromSwitch($this->ButtonsLabel, get($this->Item, 'Type')));
          $p_image = getValid($this->Item, 'Image', $this->Image);
          $p_showmorebutton = $this->ShowButtons && !isEmpty($paths);
          $p_template = Convert::FromSwitch($this->Template, $p_type) ?? $p_type;
          switch ($p_template) {
               case "Media":
               case "Image":
               case "Audio":
               case "Video":
               case "Course":
                    module("MediaFrame");
                    if ($p_showmorebutton)
                         return join(PHP_EOL, loop($paths, action: function ($v, $k) use ($p_image, $p_morebuttontext) {
                              return (new MediaFrame($v, logo: $p_image, name: is_numeric($k) ? $p_morebuttontext : $k))->DoRender();
                         })) . Html::Division(loop($paths, function ($v, $k) use ($p_morebuttontext) {
                              return Html::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn block btn outline"]);
                         }), ["class" => "more view md-show"]);
                    break;
               default:
                    if ($p_showmorebutton)
                         return Html::Division(loop($paths, function ($v, $k) use ($p_morebuttontext) {
                              return Html::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn block btn outline"]);
                         }), ["class" => "more view md-show"]);
                    break;
          }
     }
     public function GetExcerpt()
     {
          return Html::MediumSlot(
               __(
                    Html::Convert(getValid($this->Item, 'Description') ?? (
                         $this->AutoExcerpt ? Convert::ToExcerpt(
                              Convert::ToText(getValid($this->Item, 'Content', $this->Content)),
                              0,
                              $this->ExcerptLength,
                              $this->ExcerptSign
                         ) : $this->Description)),
                    refering: $this->AutoRefering
               )
               ,
               ["class" => "excerpt"]
          );
     }
     public function GetImage()
     {
          if (!$this->ShowImage)
               return null;
          $p_image = getValid($this->Item, 'Image', $this->Image);
          return isValid($p_image) ? Html::Division(Html::Image(getValid($this->Item, 'Title', $this->Title), $p_image), ["class" => "col-lg-5", "style" => "text-align: center;"]) : "";
     }
     public function GetDetails($path = null)
     {
          $authorName = null;
          $createTime = get($this->Item, 'CreateTime');
          $modifyTime = get($this->Item, 'UpdateTime');
          $p_meta = null;
          if ($this->ShowRoute) {
               module("Route");
               $route = new \MiMFa\Module\Route($path);
               $route->Tag = "span";
               $route->Class = "route";
               $p_meta = $route->ToString();
          }
          if ($this->ShowDetails) {
               if ($this->ShowAuthor)
                    doValid(
                         function ($val) use (&$p_meta) {
                              $authorName = table("User")->SelectRow("Signature , Name", "Id=:Id", [":Id" => $val]);
                              if (!isEmpty($authorName))
                                   $p_meta .= " " . Html::Link($authorName["Name"], \_::$Address->UserRoute . $authorName["Signature"], ["class" => "author"]);
                         },
                         $this->Item,
                         'AuthorId'
                    );
               if ($this->ShowCreateTime)
                    if (isValid($createTime))
                         $p_meta .= " <span class='createtime'>" . Convert::ToShownDateTimeString($createTime) . "</span>";
               if ($this->ShowUpdateTime)
                    if (isValid($modifyTime))
                         $p_meta .= " <span class='updatetime'>" . Convert::ToShownDateTimeString($modifyTime) . "</span>";
               if ($this->ShowStatus)
                    doValid(
                         function ($val) use (&$p_meta) {
                              if (isValid($val))
                                   $p_meta .= " <span class='status'>$val</span>";
                         },
                         $this->Item,
                         'Status'
                    );
          }
          component("JsonLD");
          return \MiMFa\Component\JsonLD::Article(
               title: __(getValid($this->Item, 'Title', $this->Title), styling: false),
               excerpt: __(getValid($this->Item, 'Description', $this->Description), styling: false),
               image: getValid($this->Item, 'Image', $this->Image),
               author: ["Name" => $authorName],
               datePublished: $createTime ? explode(" ", $createTime)[0] : null,
               dateModified: $modifyTime ? explode(" ", $modifyTime)[0] : null
          ) .
               ($p_meta ? Html::Sub($p_meta, null, ["class" => "details"]) : "");
     }
     public function GetButtons()
     {
          if (!$this->ShowButtons)
               return null;
          $paths = Convert::FromJson(getValid($this->Item, 'Path', $this->Path));
          $p_morebuttontext = __(value: Convert::FromSwitch($this->ButtonsLabel, get($this->Item, 'Type')));
          return Html::SmallSlot(
               loop($paths, function ($v, $k, $i) use ($p_morebuttontext) {
                    return Html::Button(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn " . ($i < 1 ? "main" : "outline")]);
               }),
               attributes: ["class" => "buttons col-md-3 view md-hide"]
          );
     }
     public function GetAttaches()
     {
          if (!$this->ShowAttaches)
               return null;
          $p_attaches = Convert::FromJson(get($this->Item, 'Attach'));
          if (!isEmpty($p_attaches))
               return Html::Division(__(Convert::FromSwitch($this->AttachesLabel, get($this->Item, 'Type'))) . Html::Convert($p_attaches));
     }
     public function GetTags()
     {
          if (!$this->ShowTags)
               return null;
          $p_tags = Convert::FromJson(get($this->Item, 'TagIds'));
          if (!isEmpty($p_tags)) {
               $p_type = get($this->Item, 'Type');
               $p_tagstext = __(Convert::FromSwitch($this->TagsLabel, $p_type));
               $p_tagscount = Convert::FromSwitch($this->TagsCount, $p_type);
               $p_tagsorder = Convert::FromSwitch($this->TagsOrder, $p_type);
               $tags = table("Tag")->SelectPairs("Name", "Title", "`Id` IN (" . join(",", $p_tags) . ") " . (isEmpty($p_tagsorder) ? "" : "ORDER BY $p_tagsorder") . " LIMIT $p_tagscount");
               if (count($tags) > 0)
                    return Html::$BreakLine . Html::Division($p_tagstext . join(PHP_EOL, loop(
                         $tags,
                         function ($v, $k) {
                              return Html::Link(
                                   isValid($v)
                                   ? __(strtolower(preg_replace("/\W*/", "", $k)) != strtolower(preg_replace("/\W*/", "", $v)) ? "$v ($k)" : $v, styling: false)
                                   : $k
                                   ,
                                   \_::$Address->TagRoute . $k,
                                   ["class" => "btn"]
                              );
                         }
                    )), ["class" => "tags"]);
          }
     }
     public function GetRelateds()
     {
          if (!$this->ShowRelateds)
               return null;
          $p_tags = Convert::FromJson(get($this->Item, 'TagIds'));
          if (isEmpty($p_tags))
               return null;
          $p_type = get($this->Item, 'Type');
          $p_relatedstext = __(Convert::FromSwitch($this->RelatedsLabel, $p_type));
          $p_relatedscount = Convert::FromSwitch($this->RelatedsCount, $p_type) ?? 5;
          $p_relatedsorder = Convert::FromSwitch($this->RelatedsOrder, $p_type);
          $rels = table("Content")->SelectPairs("Id", "Title", "`Id`!=" . get($this->Item, 'Id') . " AND `TagIds` REGEXP '\\\\D(" . join("|", $p_tags) . ")\\\\D'" . (isEmpty($p_relatedsorder) ? "" : " ORDER BY $p_relatedsorder") . " LIMIT $p_relatedscount");
          if (count($rels) > 0)
               return Html::$BreakLine . Html::Division($p_relatedstext . join(PHP_EOL, loop(
                    $rels,
                    function ($v, $k) {
                         return Html::Link(isValid($v) ? $v : $k, $this->RootRoute . $k, ["class" => "btn"]);
                    }
               )), ["class" => "relateds"]);
     }
     public function GetCommentsCollection()
     {
          if ($this->ShowComments && auth($this->ShowCommentsAccess)) {
               module("CommentCollection");
               $cc = new CommentCollection();
               $cc->Items = table("Comment")->Select(
                    "*",
                    "Relation=:rid AND " . \_::$Back->GetAccessCondition(checkStatus: false) . " " . $this->CommentsLimitation,
                    [":rid" => get($this->Item, 'Id')]
               );
               if (!$this->LeaveComment){
                    $cc->ReplyButtonLabel = 
                    $cc->DeleteButtonLabel = null;
               }
               if (!$this->ModifyComment){
                    $cc->EditButtonLabel = 
                    $cc->DeleteButtonLabel = null;
               }
               if (count($cc->Items) > 0)
                    return Html::$BreakLine . $cc->ToString();
          }
     }
     public function GetCommentForm()
     {
          if (!$this->LeaveComment)
               return null;
          $this->CommentForm->Relation = get($this->Item, 'Id');
          return Html::$BreakLine . $this->CommentForm->Handle();
     }
}
?>