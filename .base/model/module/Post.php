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
class Post extends Module{
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
     * @var bool
     * @category Part
     */
	public $AllowAnimation = true;

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
	public $TagsLabel = ["News"=>"<h6>Find More: </h6>", "Article"=>"<h6>Keywords: </h6>", "Document: "=>"<h6>Keywords: </h6>", "Post"=>"", "default"=>"<h6>Tags: </h6>"];
	/**
     * Order of Tags to show
     * @var array|int
     * @example ["News"=>10, "default"=>5]
     * @category Parts
     */
	public $TagsOrder = ["News"=>"CreateTime DESC", "Post"=>"CreateTime DESC", "default"=>""];
    /**
     * Maximum number of Tags to show
     * @var array|int
     * @example ["News"=>10, "default"=>5]
     * @category Parts
     */
	public $TagsCount = ["News"=>20, "Article"=>10, "default"=>15];

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
	public $RelatedsLabel = ["News"=>"<h5>Read Also: </h5>", "default"=>"<h5>Relateds: </h5>"];
    /**
     * Order of Related posts to show
     * @var array|int
     * @example ["News"=>10, "default"=>5]
     * @category Parts
     */
	public $RelatedsOrder = ["News"=>"CreateTime DESC", "Post"=>"CreateTime DESC", "default"=>"UpdateTime DESC"];
    /**
     * Maximum number of Related posts to show
     * @var array|int
     * @example ["News"=>10, "default"=>5]
     * @category Parts
     */
	public $RelatedsCount = ["News"=>10, "default"=>5];

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
	public $MoreButtonLabel = ["News"=>"Source","Post"=>"Refer","Text"=>"Refer","File"=>"Download File","Document"=>"Download Document","Video"=>"Watch","Audio"=>"Listen","Image"=>"Look","default"=>"Visit"];


	function __construct(){
        parent::__construct();
    }

	public function GetStyle(){
		$ralign = Translate::$Direction=="RTL"?"left":"right";
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
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name} .head{
				margin-bottom: var(--Size-2);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name} .title{
                padding: 0px;
                margin: 0px;
				text-align: unset;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .metadata{
				font-size: var(--Size-0);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .metadata .route{
				padding-$ralign: var(--Size-0);
			}
			.{$this->Name} .more{
				text-align: $ralign;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .more>a{
            	opacity: 0;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover .more>a{
            	opacity: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
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
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .description{
            	font-size: var(--Size-2);
                gap: var(--Size-2);
            	text-align: justify;
				position: relative;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
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

    public function PreCapture(){
	    $item = $this->Item;
	    $p_type = getValid($item,'Type');
	    $p_class = getValid($item,'Class');
        $this->Class = "$p_type $p_class container";
        if($this->AllowAnimation) $this->Attributes =  "data-aos='zoom-up' data-aos-offset='-500'";
    }

	public function Get(){
        return Convert::ToString(function(){
            $item = $this->Item;
            $p_access = getValid($item,'Access',0);
            $p_status = intval(getValid($item,'Status',1));
            if($p_status < 1 || !getAccess($p_access)) return;
            MODULE("Image");
            $p_type = getValid($item,'Type');
            $p_id = getValid($item,'ID');
            $p_image = getValid($item,'Image', $this->Image);
            $p_name = getValid($item,'Name')??getValid($item,'Title', $this->Title);
            $p_title = getValid($item,'Title', $p_name);
            $p_description = getValid($item,'Description', $this->Description);
            $p_content = getValid($item,'Content',$this->Content);
            $p_tags = Convert::FromJSON(getValid($item,'TagIDs'));

            if($this->ShowRoute) MODULE("Route");
            $p_meta = getValid($item,'MetaData',null);
            if($p_meta !==null) $p_meta = Convert::FromJSON($p_meta);
            $p_showexcerpt = getValid($p_meta,"ShowExcerpt",$this->ShowExcerpt);
            $p_showcontent = getValid($p_meta,"ShowContent",$this->ShowContent);
            $p_showdescription = getValid($p_meta,"ShowDescription",$this->ShowDescription);
            $p_showimage = getValid($p_meta,"ShowImage",$this->ShowImage);
            $p_showtitle = getValid($p_meta,"ShowTitle",$this->ShowTitle);
            $p_showmeta = getValid($p_meta,"ShowMetaData",$this->ShowMetaData);
            $p_inselflink = $this->Root.($p_name??$p_id);
            if(!$this->CompressPath) {
                LIBRARY("Query");
                $catDir = \MiMFa\Library\Query::GetContentCategoryDirection($item);
                if(isValid($catDir)) $p_inselflink = $this->Root.trim($catDir,"/\\")."/".($p_name??$p_id);
            }
            $p_path = (!$p_showcontent&&(!$p_showexcerpt||!$p_showdescription))? [$p_inselflink] : Convert::FromJSON(getValid($item,'Path', $this->Path));
            $hasl = !isEmpty($p_path);
            $p_showmorebutton = $hasl && getValid($p_meta,"ShowMoreButton",$this->ShowMoreButton);
            $p_morebuttontext = $p_showmorebutton?__(Convert::FromSwitch(getValid($p_meta, "MoreButtonLabel", $this->MoreButtonLabel), $p_type)):"";
            $p_showtags = getValid($p_meta,"ShowTags",$this->ShowTags) && !isEmpty($p_tags);
            $p_tagstext = $p_showtags?__(Convert::FromSwitch(getValid($p_meta, "TagsLabel", $this->TagsLabel), $p_type)):"";
            $p_tagscount = $p_showtags?__(Convert::FromSwitch(getValid($p_meta, "TagsCount", $this->TagsCount), $p_type)):"";
            $p_tagsorder = $p_showtags?__(Convert::FromSwitch(getValid($p_meta, "TagsOrder", $this->TagsOrder), $p_type)):"";
            $p_showrelateds = getValid($p_meta,"ShowRelateds", $this->ShowRelateds) && !isEmpty($p_tags);
            $p_relatedstext = $p_showrelateds?__(Convert::FromSwitch(getValid($p_meta, "RelatedsLabel", $this->RelatedsLabel), $p_type)):"";
            $p_relatedscount = $p_showrelateds?__(Convert::FromSwitch(getValid($p_meta, "RelatedsCount", $this->RelatedsCount), $p_type)):"";
            $p_relatedsorder = $p_showrelateds?__(Convert::FromSwitch(getValid($p_meta, "RelatedsOrder", $this->RelatedsOrder), $p_type)):"";
            $p_refering = getValid($p_meta,"AutoRefering", $this->AutoRefering);
            $p_excerpt = null;
            if($p_showexcerpt && $this->AutoExcerpt)
                $p_excerpt = __(Convert::ToExcerpt(
                        $p_content,
                        0,
                        $this->ExcerptLength,
                        $this->ExcerptSign
                    ), refering:$p_refering);

            $p_meta = null;
            $authorName = null;
            $createTime = getValid($item,'CreateTime');
            $modifyTime = getValid($item,'UpdateTime');
            if($p_showmeta){
                if($this->ShowAuthor)
                    doValid(
                        function($val) use(&$p_meta){
                            $authorName = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."User","Signature , Name","ID=:ID",[":ID"=>$val]);
                            if(!isEmpty($authorName)) $p_meta .= " ".HTML::Link($authorName["Name"],"/user/".$authorName["Signature"],["class"=>"Author"]);
                        },
                        $item,
                        'AuthorID'
                    );
                if($this->ShowCreateTime)
                    if(isValid($createTime))
                        $p_meta .= " <span class='CreateTime'>$createTime</span>";
                if($this->ShowUpdateTime)
                    if(isValid($modifyTime))
                        $p_meta .= " <span class='UpdateTime'>$modifyTime</span>";
                if($this->ShowStatus)
                    doValid(
                        function($val) use(&$p_meta){
                            if(isValid($val)) $p_meta .= " <span class='Status'>$val</span>";
                        },
                        $item,
                        'Status'
                    );
                if($this->ShowButtons)
                    doValid(
                        function($val) use(&$p_meta){
                            if(isValid($val)) $p_meta .= " ".$val;
                            else $p_meta .=  " ".$this->Buttons;
                        },
                        $item,
                        'Buttons'
                    );
            }

            COMPONENT("JSONLD");
            $mod = new \MiMFa\Component\JSONLD();
            yield $mod->GetArticle(__($p_title, styling:false),__($p_description, styling:false),$p_image,
                author:["name"=>$authorName],datePublished: explode(" ", $createTime)[0], dateModified:explode(" ", $modifyTime)[0]);
            yield HTML::Rack(
			    HTML::MediumSlot(function() use($p_showtitle,$hasl,$p_inselflink,$p_title,$p_meta,$p_showmeta){
                    $lt = $this->LinkedTitle && $hasl;
                    if($p_showtitle) yield HTML::ExternalHeading($p_title,$lt?$p_inselflink:null,['class'=>'title']);
                    if($p_showmeta && isValid($p_meta)){
                        yield "<sub class='metadata'>";
                        if($this->ShowRoute){
                            $route = new \MiMFa\Module\Route($p_inselflink);
                            $route->Tag = "span";
                            $route->Class = "route";
                            yield $route->ReCapture();
                        }
                        yield $p_meta."</sub>";
                    }
			    }).
                ($p_showmorebutton?HTML::SmallSlot(
				    loop($p_path, function($k,$v,$i) use($p_morebuttontext) { return HTML::Link(is_numeric($k)?$p_morebuttontext:$k, $v,["class"=>"btn btn-outline"]);})
			    ,["class"=>"more col-3 md-hide"]):"")
		    ,["class"=>"head"]);
            yield HTML::Rack(
			    HTML::MediumSlot(function()use($p_description,$p_excerpt,$p_showdescription,$p_showexcerpt, $p_refering){
                    if($p_showdescription) yield __($p_description, refering:$p_refering);
                    if($p_showexcerpt) yield $p_excerpt;
                },["class"=>"excerpt"]).
                ($p_showimage && isValid($p_image)? HTML::Division(HTML::Image($p_title,$p_image),["class"=>"col-lg-5", "style"=>"text-align: center;"]):"")
		    ,["class"=>"description"]);
            if($p_showcontent && isValid($p_content)) yield HTML::Division(__($p_content, refering:$p_refering),["class"=>"content"]);
            if($p_showmorebutton) yield HTML::Division(loop($p_path, function($k,$v,$i) use($p_morebuttontext) { return HTML::Link(is_numeric($k)?$p_morebuttontext:$k, $v,["class"=>"btn btn-block btn-outline"]);}),["class"=>"more md-show"]);
            if($p_showtags) yield HTML::$HorizontalBreak.HTML::Division($p_tagstext.join(PHP_EOL, loop(DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix."Tag","Name","Title","`ID` IN (".join(",",$p_tags).") ".(isEmpty($p_tagsorder)?"":"ORDER BY $p_tagsorder")." LIMIT $p_tagscount"),
                function($k,$v,$i) {
                    return HTML::Link(isValid($v)
                       ?__(strtolower(preg_replace("/\W*/","", $k))!=strtolower(preg_replace("/\W*/","", $v))? "$v ($k)" : $v, styling:false)
                       :$k
                    , "/tag/$k",["class"=>"btn"]);
                })), ["class"=>"tags"]);
            if($p_showrelateds) yield HTML::$HorizontalBreak.HTML::Division($p_relatedstext.join(PHP_EOL, loop(DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix."Content","ID","Title","`ID`!=$p_id AND (`Title` IS NOT NULL OR `Title`!='') AND `TagIDs` REGEXP '(\"".join("\")|(\"",$p_tags)."\")' ".(isEmpty($p_tagsorder)?"":"ORDER BY $p_relatedsorder")." LIMIT $p_relatedscount"),
                function($k,$v,$i) {return HTML::Link(isValid($v)?$v:$k, "/post/$k",["class"=>"btn"]);})), ["class"=>"relateds"]);
        });
	}
}
?>