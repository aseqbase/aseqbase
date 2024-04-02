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
	public $ImageHeight = "100%";
	/**
     * The Minimum Width of Image
     * @var string
     */
	public $ImageMinWidth = "auto";
	/**
     * The Minimum Height of Image
     * @var string
     */
	public $ImageMinHeight = "10vh";
    /**
     * The Maximum Width of Image
     * @var string
     */
	public $ImageMaxWidth = "100%";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $ImageMaxHeight = "50vh";

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
     * @category Excerption
     */
	public $MoreButtonLabel = "See More...";

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
				border-radius:  var(--Radius-1);
				border:  var(--Border-1) var(--BackColor-4);
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
				padding-top: var(--Size-2);
				padding-bottom: var(--Size-2);
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
            $p_id = getValid($item,'ID');
            $p_image = getValid($item,'Image', $this->Image);
            $p_name = getValid($item,'Name')??getValid($item,'Title', $this->Title);
            $p_title = getValid($item,'Title', $p_name);
            $p_description = getValid($item,'Description', $this->Description);
            $p_content = getValid($item,'Content',$this->Content);

            if($this->ShowRoute) MODULE("Route");
            $p_meta = getValid($item,'MetaData',null);
            if($p_meta !==null) $p_meta = json_decode($p_meta);
            $p_showexcerpt = $this->ShowExcerpt || getValid($p_meta,"ShowExcerpt",false);
            $p_showcontent = $this->ShowContent || getValid($p_meta,"ShowContent",false);
            $p_showdescription = $this->ShowDescription || getValid($p_meta,"ShowDescription",false);
            $p_showimage = $this->ShowImage || getValid($p_meta,"ShowImage",false);
            $p_showtitle = $this->ShowTitle || getValid($p_meta,"ShowTitle",false);
            $p_showmeta = $this->ShowMetaData || getValid($p_meta,"ShowMeta",false);
            $p_inselflink = $this->Root.($p_name??$p_id);
            if(!$this->CompressPath) {
                LIBRARY("Query");
                $catDir = \MiMFa\Library\Query::GetContentCategoryDirection($item);
                if(isValid($catDir)) $p_inselflink = $this->Root.trim($catDir,"/\\")."/".($p_name??$p_id);
            }
            $p_path = (!$p_showcontent&&(!$p_showexcerpt||!$p_showdescription))? $p_inselflink : getValid($item,'Path', $this->Path);
            $hasl = isValid($p_path);
            $p_showmorebutton = $hasl && ($this->ShowMoreButton || getValid($p_meta,"ShowMoreButton",false));
            $p_morebuttontext = getValid($p_meta, "MoreButtonLabel", $this->MoreButtonLabel);
            $p_excerpt = null;
            if($this->ShowExcerpt && $this->AutoExcerpt)
                $p_excerpt = Convert::ToExcerpt(
                        __($p_content??$p_description),
                        0,
                        $this->ExcerptLength,
                        $this->ExcerptSign
                    );

            $p_meta = null;
            $authorName = null;
            $createTime = getValid($item,'CreateTime');
            $modifyTime = getValid($item,'UpdateTime');
            if($p_showmeta){
                if($this->ShowAuthor)
                    doValid(
                        function($val) use(&$p_meta){
                            $authorName = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."User","Signature , Name","ID=:ID",[":ID"=>$val]);
                            if(!isEmpty($authorName)) $p_meta .= HTML::Link($authorName["Name"],"/user/".$authorName["Signature"],["class"=>"Author"]);
                        },
                        $item,
                        'AuthorID'
                    );
                if($this->ShowCreateTime)
                    if(isValid($createTime))
                        $p_meta .= "<span class='CreateTime'>$createTime</span>";
                if($this->ShowUpdateTime)
                    if(isValid($modifyTime))
                        $p_meta .= "<span class='UpdateTime'>$modifyTime</span>";
                if($this->ShowStatus)
                    doValid(
                        function($val) use(&$p_meta){
                            if(isValid($val)) $p_meta .= "<span class='Status'>$val</span>";
                        },
                        $item,
                        'Status'
                    );
                if($this->ShowButtons)
                    doValid(
                        function($val) use(&$p_meta){
                            if(isValid($val)) $p_meta .= $val;
                            else $p_meta .= $this->Buttons;
                        },
                        $item,
                        'Buttons'
                    );
            }

            COMPONENT("JSONLD");
            $mod = new \MiMFa\Component\JSONLD();
            yield $mod->GetArticle(__($p_title),__($p_description),$p_image,
                author:["name"=>$authorName],datePublished: explode(" ", $createTime)[0], dateModified:explode(" ", $modifyTime)[0]);
            yield HTML::Rack(
			    HTML::MediumSlot(function() use($p_showtitle,$hasl,$p_inselflink,$p_title,$p_meta,$p_showmeta){
                    $lt = $this->LinkedTitle && $hasl;
                    if($p_showtitle) yield ($lt?"<a href='$p_inselflink'>":"")."<h2 class='title'>".__($p_title)."</h2>".($lt?"</a>":"");
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
				    HTML::Link(__($p_morebuttontext),$p_path,["class"=>"btn btn-outline"])
			    ,["class"=>"more col-3 md-hide"]):"")
		    ,["class"=>"head"]);
            yield HTML::Rack(
			    HTML::MediumSlot(function()use($p_description,$p_excerpt,$p_showdescription,$p_showexcerpt){
                    if($p_showdescription) yield __($p_description);
                    if($p_showexcerpt) yield $p_excerpt;
                },["class"=>"excerpt"]).
                ($p_showimage && isValid($p_image)? HTML::Image($p_title,$p_image,["class"=>"col-5"]):"")
		    ,["class"=>"description"]);
            if($p_showcontent && isValid($p_content)) yield HTML::Division(__($p_content),["class"=>"content"]);
            if($p_showmorebutton) yield HTML::Division(HTML::Link(__($p_morebuttontext),$p_path,["class"=>"btn btn-block btn-outline"]),["class"=>"more md-show"]);
        });
	}
}
?>