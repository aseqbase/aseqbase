<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\Translate;
MODULE("Collection");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class PostCollection extends Collection{
	public $Capturable = true;
	public $TitleTag = "h1";

	public $MaximumColumns = 2;

	/**
     * The root directory or path
     * @var string|null
     */
	public $Root = "/post/";
	public $CompressPath = true;

	/**
     * The default Content HTML
     * @var string|null
     */
	public $DefaultContent = null;
	/**
     * The default Path for more button reference
     * @var string|null
     */
	public $DefaultPath = null;
	/**
     * The size of Blur effect
     * @var string
     */
	public $BlurSize = "1px";

	/**
     * The Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailWidth = "auto";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailHeight = "100%";
	/**
     * The Minimum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinWidth = "auto";
	/**
     * The Minimum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinHeight = "10vh";
    /**
     * The Maximum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxWidth = "100%";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxHeight = "50vh";

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
	public $ShowAuthor = false;
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
	public $ShowDescription = true;
	/**
     * @var bool
     * @category Parts
     */
	public $ShowContent = false;
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
     * @var array|string|null
     * @category Excerption
     */
	public $MoreButtonLabel = "More...";
	/**
     * @var bool
     * @category Excerption
     */
	public $ShowPathButton = true;
	/**
     * The label text of Path button
     * @var array|string|null
     * @example ["News"=>"Source","Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","default"=>"Visit"]
     * @category Excerption
     */
	public $PathButtonLabel = ["News"=>"Source","Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","Video"=>"Watch","Audio"=>"Listen","Image"=>"Look","default"=>"Visit"];

	function __construct(){
        parent::__construct();
    }

	public function GetStyle(){
		$ralign = Translate::$Direction=="RTL"?"left":"right";
		return HTML::Style("
			.{$this->Name}>*>.item {
				height: fit-content;
				background-Color: #88888808;
				margin: var(--Size-2);
            	padding: var(--Size-3);
				font-size: var(--Size-0);
				box-shadow: var(--Shadow-1);
				border-radius: var(--Radius-2);
            	border: var(--Border-1) var(--BackColor-5);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover{
				box-shadow: var(--Shadow-2);
				border-radius:  var(--Radius-1);
				border-color: var(--BackColor-4);
				background-Color: #88888818;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}

			.{$this->Name}>*>.item .head{
				margin-bottom: var(--Size-2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}

			.{$this->Name}>*>.item .title{
                padding: 0px;
                margin: 0px;
				font-size: var(--Size-3);
				text-align: unset;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .title{
				font-size: var(--Size-3);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .metadata{
				font-size: var(--Size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .metadata .route{
				padding-{$ralign}: var(--Size-0);
			}
			.{$this->Name}>*>.item .more{
                gap: var(--Size-0);
				text-align: {$ralign};
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .more>a{
				opacity: 0;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .more>a{
            	opacity: 1;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			/* Style the images inside the grid */
			.{$this->Name}>*>.item .image {
				opacity: 1;
				width: {$this->ThumbnailWidth};
				height: {$this->ThumbnailHeight};
				min-height: {$this->ThumbnailMinHeight};
				min-width: {$this->ThumbnailMinWidth};
				max-height: {$this->ThumbnailMaxHeight};
				max-width: {$this->ThumbnailMaxWidth};
				overflow: hidden;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .image{
				opacity: 0.6;
				".(\MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")"))."
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .description{
                gap: var(--Size-0);
            	font-size: var(--Size-2);
				position: relative;
                margin-bottom: var(--Size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item .content{
                font-size: var(--Size-1);
            	text-align: justify;
				padding-top: var(--Size-3);
				padding-bottom: var(--Size-3);
			}
        ");
	}

	public function Get(){
        return join(PHP_EOL, iterator_to_array((function(){
		    MODULE("Image");
		    $img = new Image();
		    $img->Class = "image";
		    yield $img->GetStyle();
            $rout = null;
            if($this->ShowRoute){
                MODULE("Route");
                $rout = new \MiMFa\Module\Route();
                $rout->Class = "route";
                $rout->Tag = "span";
            }
		    $i = 0;
		    yield $this->GetTitle();
		    yield $this->GetDescription();
		    foreach(Convert::ToItems($this->Items) as $k=>$item) {
                $p_access = getValid($item,'Access',0);
                if(!getAccess($p_access)) continue;
			    $p_id = getValid($item,'ID');
			    $p_type = getValid($item,'Type');
			    $p_image = getValid($item,'Image', $this->DefaultImage);
			    $p_name = getValid($item,'Name')??getValid($item,'Title', $this->DefaultTitle);
			    $p_title = getValid($item,'Title', $p_name);
			    $p_description = getValid($item,'Description', $this->DefaultDescription);
			    $p_content = getValid($item,'Content',$this->DefaultContent);
			    $p_class = getValid($item,'Class');

			    $p_meta = getValid($item,'MetaData',null);
			    if($p_meta !==null) $p_meta = Convert::FromJSON($p_meta);
			    $p_showexcerpt = getValid($p_meta,"ShowExcerpt",$this->ShowExcerpt);
			    $p_showcontent = getValid($p_meta,"ShowContent",$this->ShowContent);
			    $p_showdescription = getValid($p_meta,"ShowDescription",$this->ShowDescription);
			    $p_showimage = getValid($p_meta,"ShowImage", $this->ShowImage);
			    $p_showtitle = getValid($p_meta,"ShowTitle",$this->ShowTitle);
                $p_showmeta = getValid($p_meta,"ShowMetaData", $this->ShowMetaData);
                $p_refering = getValid($p_meta,"AutoRefering", $this->AutoRefering);
                $p_inselflink = (!$p_showcontent&&(!$p_showexcerpt||!$p_showdescription))? (getBetween($item, "Reference")??$this->Root.getValid($item,'Name',$p_id)):null;
                if(!$this->CompressPath) {
                    LIBRARY("Query");
                    $catDir = \MiMFa\Library\Query::GetContentCategoryDirection($item);
                    if(isValid($catDir)) $p_inselflink = $this->Root.trim($catDir,"/\\")."/".($p_name??$p_id);
                }
                $p_path = first(Convert::FromJSON(getValid($item,'Path', $this->DefaultPath)));
                if($this->ShowRoute) $rout->SetValue($p_inselflink);
			    $hasl = isValid($p_inselflink);
			    $p_showmorebutton = $hasl && getValid($p_meta,"ShowMoreButton",$this->ShowMoreButton );
                $p_morebuttontext = __(Convert::FromSwitch(getValid($p_meta,"MoreButtonLabel",$this->MoreButtonLabel), $p_type));
			    $p_showpathbutton = isValid($p_path) && getValid($p_meta,"ShowPathButton",$this->ShowPathButton);
                $p_pathbuttontext = __(Convert::FromSwitch(getValid($p_meta,"PathButtonLabel",$this->PathButtonLabel), $p_type));

                $p_excerpt = null;
			    if($this->AutoExcerpt){
                    $p_description = __(Convert::ToExcerpt(
						    $p_description,
						    0,
						    $this->ExcerptLength,
						    $this->ExcerptSign
					    ), refering:$p_refering);
                    if($p_showexcerpt)
                        $p_excerpt = __(Convert::ToExcerpt(
						    $p_content,
						    0,
						    $this->ExcerptLength,
						    $this->ExcerptSign
					    ), refering:$p_refering);
                }
                else $p_description = __($p_description, refering:$p_refering);

			    $p_meta = null;
			    if($p_showmeta){
				    if($this->ShowAuthor)
                        doValid(
                            function($val) use(&$p_meta){
                                $authorName = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."User","Signature , Name","ID=:ID",[":ID"=>$val]);
                                if(!isEmpty($authorName)) $p_meta .=  " ".HTML::Link($authorName["Name"],"/user/".$authorName["Signature"],["class"=>"Author"]);
                            },
                            $item,
                            'AuthorID'
                        );
                    if($this->ShowCreateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='CreateTime'>$val</span>";
                            },
                            $item,
                            'CreateTime'
                        );
                    if($this->ShowUpdateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='UpdateTime'>$val</span>";
                            },
                            $item,
                            'UpdateTime'
                        );
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
                                if(isValid($val)) $p_meta .=  " ".$val;
                                else $p_meta .=  " ".$this->DefaultButtons;
                            },
                            $item,
                            'Buttons'
                        );
                }
			    $img->Source = $p_image;
			    if($i % $this->MaximumColumns === 0) yield "<div class='row'>";
                yield "<article class='item $p_type $p_class col-lg'". ($this->Animation? " data-aos-delay='".($i % $this->MaximumColumns*\_::$TEMPLATE->AnimationSpeed)."' data-aos='{$this->Animation}'":"").">";
                yield "<div class='head row'>";
                    yield "<div class='col-lg'>";
                        $lt = $this->LinkedTitle && $hasl;
                        if($p_showtitle) yield ($lt?"<a href='$p_inselflink'>":"")."<h2 class='title'>".__($p_title, styling:false)."</h2>".($lt?"</a>":"");
                        if($p_showmeta && isValid($p_meta)) {
                            yield "<sub class='metadata'>";
                            if($this->ShowRoute) yield $rout->ReCapture();
                            yield $p_meta."</sub>";
                        }
                    yield "</div>";
                    if($p_showmorebutton || $p_showpathbutton) {
                        yield "<div class='more col-sm col-3 md-hide'>";
                        if($p_showmorebutton)
                            yield "<a class='btn btn-outline' href='$p_inselflink'>".$p_morebuttontext."</a>";
                        if($p_showpathbutton)
                            yield "<a class='btn btn-outline' href='$p_path'>".$p_pathbuttontext."</a>";
                        yield "</div>";
                    }
                yield "</div>";
                yield "<div class='description row'>";
                yield "<div class='excerpt col-md'>";
                if($p_showdescription && !isEmpty($p_description)) yield $p_description;
                if($p_showexcerpt) yield $p_excerpt;
                yield "</div>";
                if($p_showimage && isValid($p_image))
                    yield "<div class='col-lg-3'>".$img->ReCapture()."</div>";
                yield "</div>";
                if($p_showcontent && isValid($p_content))
                    yield "<div class='content'>".__($p_content, refering:$p_refering)."</div>";
                if($p_showmorebutton || $p_showpathbutton) {
                    yield "<div class='more md-show'>";
                    if($p_showmorebutton)
                        yield "<a class='btn btn-outline' href='$p_inselflink'>".$p_morebuttontext."</a>";
                    if($p_showpathbutton)
                        yield "<a class='btn btn-outline' href='$p_path'>".$p_pathbuttontext."</a>";
                    yield "</div>";
                }
                yield "</article>";
                if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
		    if($i % $this->MaximumColumns !== 0)  yield "</div>";
        })()));
	}
}
?>