<?php
namespace MiMFa\Module;
module("CommentForm");
use MiMFa\Library\Struct;
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
     public $Root = null;
     public $CollectionRoot = null;

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
     public $AllowTitle = true;
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
     public $AllowDetails = true;
     /**
      * @var bool
      * @category Parts
      */
     public $AllowRoot = true;

     /**
      * @var bool
      * @category Parts
      */
     public $AllowTags = true;
     /**
      * The label text of Tags
      * @var string|null
      * @example ["News"=>"Find More: ", "Article"=>"Keywords: ", "Document: "=>"Keywords: ", "Post"=>"", "Default"=>"Tags: "]
      * @category Parts
      */
     public $TagsLabel = ["News" => "'Find More': ", "Article" => "'Keywords': ", "Document" => "'Keywords': ", "Post" => "", "Default" => "'Tags': "];
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
     public $AllowRelateds = true;
     /**
      * The label text of Related posts
      * @var string|null
      * @example ["News"=>"Read Also: ", "Default"=>"Relateds: "]
      * @category Parts
      */
     public $RelatedsLabel = ["News" => "'Read Also': ", "Default" => "'Relateds': "];
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
     public $AllowComments = true;
     /**
      * @var int
      * @category Parts
      */
     public $AllowCommentsAccess = 0;
     /**
      * @var string|null
      * @category Management
      */
     public $CommentsLimitation = "ORDER BY `CreateTime` ASC, `UpdateTime` ASC";

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
     public $AllowStatus = false;
     /**
      * @var bool
      * @category Parts
      */
     public $AllowButtons = true;
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
     public $AllowAttaches = true;
     /**
      * The label text of Attaches part
      * @var string|null
      * @example ["News"=>"Read Also: ", "Default"=>"Relateds: "]
      * @category Parts
      */
     public $AttachesLabel = ["Default" => "'Attaches':"];
     /**
      * @var bool
      * @category Parts
      */
     public $AllowImage = true;
     /**
      * @var bool
      * @category Parts
      */
     public $AllowContent = true;
     /**
      * @var bool
      * @category Parts
      */
     public $AllowDescription = true;
     /**
      * @var bool
      * @category Excerption
      */
     public $AutoExcerpt = false;
     /**
      * Allow to analyze all text and linking categories and tags to their descriptions, to improve the website's SEO
      * @var mixed
      */
     public $AutoReferring = true;
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
          $this->AllowComments = \_::$Config->AllowReadComment;
          $this->AllowCommentsAccess = \_::$Config->ReadCommentAccess;
          $this->Root = $this->Root ?? \_::$Address->ContentRoot;
          $this->CollectionRoot = $this->CollectionRoot ?? \_::$Address->ContentRoot;
          $this->CommentForm = new CommentForm();
          $this->CommentForm->MessageType = "texts";
          $this->CommentForm->Access = \_::$Config->WriteCommentAccess;
          $this->CommentForm->SubjectLabel =
               $this->CommentForm->AttachLabel =
               null;
          $this->CheckAccess = fn($item) => \_::$User->HasAccess(getValid($item, 'Access', 0));
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
          $ralign = \_::$Front->Translate->Direction == "rtl" ? "left" : "right";
          return Struct::Style("
			.{$this->Name} {
				height: fit-content;
				background-Color: var(--back-color-special);
				color: var(--fore-color-special);
                    margin-top: var(--size-3);
                    margin-bottom: var(--size-3);
                    padding: var(--size-4) max(2vw,var(--size-3));
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
                    return part(\_::$User->InHandlerPath, print: false);

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
          $p_id = get($this->Item, 'Id');
          $p_name = getValid($this->Item, 'Name') ?? $p_id ?? $this->Title;
          $nameOrId = $p_id ?? $p_name;
          if (!$this->CompressPath) {
               $catDir = \_::$Back->Query->GetContentCategoryRoute($this->Item);
               if (isValid($catDir))
                    $nameOrId = trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
          }
          return Struct::Rack(
               Struct::MediumSlot(
                    ($this->AllowTitle ? Struct::Heading1(getValid($this->Item, 'Title', $this->Title), $this->LinkedTitle ? $this->Root . $nameOrId : null, ['class' => 'heading']) : "") .
                    $this->GetDetails($this->CollectionRoot . $nameOrId)
               ) .
               $this->GetButtons(),
               ["class" => "title"],
               $attributes
          );
     }
     public function GetDescription($attributes = null)
     {
          return Struct::Rack(
               ($this->AllowDescription = ($this->AllowDescription ? $this->GetExcerpt() : null)) . $this->GetImage(),
               ["class" => "description"]
               ,
               $attributes
          );
     }
     public function GetContent($attributes = null)
     {
          if (!$this->AllowContent)
               return null;
          $p_content = getValid($this->Item, 'Content', $this->Content);
          return (isValid($p_content) ? Struct::Division(__(Struct::Convert($p_content), styling: true, referring: $this->AutoReferring), ["class" => "content"], $attributes) : null);
     }
     public function GetSpecial()
     {
          $paths = Convert::FromJson(getValid($this->Item, 'Path', $this->Path));
          if (isEmpty($paths))
               return null;
          $p_type = get($this->Item, 'Type');
          $p_morebuttontext = __(Convert::FromSwitch($this->ButtonsLabel, get($this->Item, 'Type')));
          $p_image = getValid($this->Item, 'Image', $this->Image);
          $p_showmorebutton = $this->AllowButtons && !isEmpty($paths);
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
                              return (new MediaFrame($v, logo: $p_image, name: is_numeric($k) ? $p_morebuttontext : $k))->Render();
                         })) . Struct::Division(loop($paths, function ($v, $k) use ($p_morebuttontext) {
                              return Struct::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn block btn outline"]);
                         }), ["class" => "more view md-show"]);
                    break;
               default:
                    if ($p_showmorebutton)
                         return Struct::Division(loop($paths, function ($v, $k) use ($p_morebuttontext) {
                              return Struct::Link(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn block btn outline"]);
                         }), ["class" => "more view md-show"]);
                    break;
          }
     }
     public function GetExcerpt()
     {
          $excerpt = Struct::Convert(getValid($this->Item, 'Description') ?? (
               $this->AutoExcerpt ? Convert::ToExcerpt(
                    Convert::ToText(getValid($this->Item, 'Content', $this->Content)),
                    0,
                    $this->ExcerptLength,
                    $this->ExcerptSign
               ) : $this->Description));
          return $excerpt ? Struct::MediumSlot(
               __(
                    $excerpt,
                    styling: true,
                    referring: $this->AutoReferring
               )
               ,
               ["class" => "excerpt"]
          ) : null;
     }
     public function GetImage()
     {
          if (!$this->AllowImage)
               return null;
          $p_image = getValid($this->Item, 'Image', $this->Image);
          return isValid($p_image) ? Struct::Division(Struct::Image(getValid($this->Item, 'Title', $this->Title), $p_image), ["class" => "col-lg" . ($this->AllowDescription ? "-4" : null), "style" => "text-align: center;"]) : "";
     }
     public function GetDetails($path = null)
     {
          $authorName = null;
          $createTime = get($this->Item, 'CreateTime');
          $modifyTime = get($this->Item, 'UpdateTime');
          $p_meta = null;
          if ($this->AllowRoot) {
               module("Route");
               $route = new \MiMFa\Module\Route($path);
               $route->Tag = "span";
               $route->Class = "route";
               $p_meta = $route->ToString();
          }
          if ($this->AllowDetails) {
               if ($this->AllowAuthor)
                    doValid(
                         function ($val) use (&$p_meta) {
                              $authorName = table("User")->SelectRow("Signature , Name", "Id=:Id", [":Id" => $val]);
                              if (!isEmpty($authorName))
                                   $p_meta .= " " . Struct::Link($authorName["Name"], \_::$Address->UserRoot . $authorName["Signature"], ["class" => "author"]);
                         },
                         $this->Item,
                         'AuthorId'
                    );
               if ($this->AllowCreateTime)
                    if (isValid($createTime))
                         $p_meta .= " <span class='createtime'>" . Convert::ToShownDateTimeString($createTime) . "</span>";
               if ($this->AllowUpdateTime)
                    if (isValid($modifyTime))
                         $p_meta .= " <span class='updatetime'>" . Convert::ToShownDateTimeString($modifyTime) . "</span>";
               if ($this->AllowStatus)
                    doValid(
                         function ($val) use (&$p_meta) {
                              if (isValid($val))
                                   $p_meta .= " <span class='status'>$val</span>";
                         },
                         $this->Item,
                         'Status'
                    );
          }
          return \MiMFa\Component\Promote::Article(
               title: __(getValid($this->Item, 'Title', $this->Title)),
               excerpt: __(getValid($this->Item, 'Description', $this->Description)),
               image: getValid($this->Item, 'Image', $this->Image),
               author: ["Name" => $authorName],
               datePublished: $createTime ? explode(" ", $createTime)[0] : null,
               dateModified: $modifyTime ? explode(" ", $modifyTime)[0] : null
          ) .
               ($p_meta ? Struct::Sub($p_meta, null, ["class" => "details"]) : "");
     }
     public function GetButtons()
     {
          if (!$this->AllowButtons)
               return null;
          $paths = Convert::FromJson(getValid($this->Item, 'Path', $this->Path));
          $p_morebuttontext = __(value: Convert::FromSwitch($this->ButtonsLabel, get($this->Item, 'Type')));
          return Struct::SmallSlot(
               loop($paths, function ($v, $k, $i) use ($p_morebuttontext) {
                    return Struct::Button(is_numeric($k) ? $p_morebuttontext : $k, $v, ["class" => "btn " . ($i < 1 ? "main" : "outline")]);
               }),
               attributes: ["class" => "buttons col-md-3 view md-hide"]
          );
     }
     public function GetAttaches()
     {
          if (!$this->AllowAttaches)
               return null;
          $p_attaches = Convert::FromJson(get($this->Item, 'Attach'));
          if (!isEmpty($p_attaches))
               return Struct::Division(
                    Struct::Heading5(Convert::FromSwitch($this->AttachesLabel, get($this->Item, 'Type'))) .
                    Struct::Convert($p_attaches)
               );
     }
     public function GetTags()
     {
          if (!$this->AllowTags)
               return null;
          $p_tags = Convert::FromJson(get($this->Item, 'TagIds'));
          if (!isEmpty($p_tags)) {
               $p_type = get($this->Item, 'Type');
               $p_tagscount = Convert::FromSwitch($this->TagsCount, $p_type);
               $p_tagsorder = Convert::FromSwitch($this->TagsOrder, $p_type);
               $tags = table("Tag")->SelectPairs("Name", "Title", "`Id` IN (" . join(",", $p_tags) . ") " . (isEmpty($p_tagsorder) ? "" : "ORDER BY $p_tagsorder") . " LIMIT $p_tagscount");
               if (count($tags) > 0)
                    return Struct::$BreakLine . Struct::Division(
                         Struct::Heading6(Convert::FromSwitch($this->TagsLabel, $p_type)) .
                         join(PHP_EOL, loop(
                              $tags,
                              function ($v, $k) {
                                   return Struct::Link(
                                        isValid($v)
                                        ? __(strtolower(preg_replace("/\W*/", "", $k)) != strtolower(preg_replace("/\W*/", "", $v)) ? "$v ($k)" : $v)
                                        : $k
                                        ,
                                        \_::$Address->TagRoot . $k,
                                        ["class" => "btn"]
                                   );
                              }
                         )),
                         ["class" => "tags"]
                    );
          }
     }
     public function GetRelateds()
     {
          if (!$this->AllowRelateds)
               return null;
          $p_tags = Convert::FromJson(get($this->Item, 'TagIds'));
          if (isEmpty($p_tags))
               return null;
          $p_type = get($this->Item, 'Type');
          $p_relatedstext = __(Convert::FromSwitch($this->RelatedsLabel, $p_type));
          $p_relatedscount = Convert::FromSwitch($this->RelatedsCount, $p_type) ?? 5;
          $p_relatedsorder = Convert::FromSwitch($this->RelatedsOrder, $p_type);
          $rels = table("Content")->SelectPairs("Id", "Title", "Id!=" . get($this->Item, 'Id') . " AND `TagIds` REGEXP '\\\\D(" . join("|", $p_tags) . ")\\\\D'" . (\_::$Front->Translate->Language ? " AND (MetaData IS NULL OR JSON_CONTAINS(MetaData, '\"" . \_::$Front->Translate->Language . "\"', '$.lang'))" : "") . (isEmpty($p_relatedsorder) ? "" : " ORDER BY $p_relatedsorder") . " LIMIT $p_relatedscount");
          if (count($rels) > 0)
               return Struct::$BreakLine . Struct::Division($p_relatedstext . join(PHP_EOL, loop(
                    $rels,
                    function ($v, $k) {
                         return Struct::Link(isValid($v) ? $v : $k, $this->Root . $k, ["class" => "btn"]);
                    }
               )), ["class" => "relateds"]);
     }
     public function GetCommentsCollection()
     {
          if ($this->AllowComments && \_::$User->HasAccess($this->AllowCommentsAccess)) {
               module("CommentCollection");
               $cc = new CommentCollection();
               $cc->Items = table("Comment")->Select(
                    "*",
                    "Relation=:rid AND " . authCondition(checkStatus: false) . " " . $this->CommentsLimitation,
                    [":rid" => get($this->Item, 'Id')]
               );
               if (!$this->LeaveComment) {
                    $cc->ReplyButtonLabel =
                         $cc->DeleteButtonLabel = null;
               }
               if (!$this->ModifyComment) {
                    $cc->EditButtonLabel =
                         $cc->DeleteButtonLabel = null;
               }
               switch (strtolower(get($this->Item, 'Type') ?? "")) {
                    case "merchandise":
                    case "course":
                    case "query":
                    case "forum":
                         $cc->ShowMedia = true;
                         break;
                    default:
                         $cc->ShowMedia = false;
                         break;
               }
               if (count($cc->Items) > 0)
                    return Struct::$BreakLine . $cc->ToString();
          }
     }
     public function GetCommentForm()
     {
          if (!$this->LeaveComment)
               return null;
          $this->CommentForm->Relation = get($this->Item, 'Id');
          return Struct::$BreakLine . $this->CommentForm->Handle();
     }
}