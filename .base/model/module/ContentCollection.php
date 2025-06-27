<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
module("Collection");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class ContentCollection extends Collection{
     public $RootRoute = null;
     public $CollectionRoute = null;
     public $TitleTag = "h1";

    /**
    * A Check Access function
    */
    public $CheckAccess = null;

	public $MaximumColumns = 2;

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
     * @example ["News"=>"Source" ,"Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","Default"=>"Visit"]
     * @category Excerption
     */
	public $PathButtonLabel = ["News"=>"Source" ,"Post"=>"Refer","Text"=>"Refer","File"=>"Download","Document"=>"Download","Video"=>"Watch","Audio"=>"Listen","Image" =>"Look","Default"=>"Visit"];

	function __construct($items = null){
        parent::__construct($items);
        $this->RootRoute = $this->RootRoute??\_::$Address->ContentRoute;
        $this->CollectionRoute = $this->CollectionRoute??\_::$Address->ContentRoute;
        $this->CheckAccess = fn($item)=>auth(getValid($item, 'Access' , 0));
    }

	public function GetStyle(){
		return Html::Style("
			.{$this->Name}>*>.item {
				height: fit-content;
				max-width: calc(100% - 2 * var(--size-2));
				background-Color: var(--back-color-3);
				Color: var(--fore-color-3);
				margin: var(--size-2);
            	padding: var(--size-3);
				font-size: var(--size-0);
				box-shadow: var(--shadow-1);
				border-radius: var(--radius-2);
            	border: var(--border-1) var(--back-color-1);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover{
				box-shadow: var(--shadow-2);
				border-radius:  var(--radius-1);
				border-color: var(--back-color-4);
				background-Color: #88888818;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}

			.{$this->Name}>*>.item .head{
				margin-bottom: var(--size-2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}

			.{$this->Name}>*>.item .title{
                padding: 0px;
                margin: 0px;
				font-size: var(--size-3);
				text-align: unset;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .title{
				font-size: var(--size-3);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item .metadata{
				font-size: var(--size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item .metadata>*{
				padding-inline-end: calc(var(--size-0) / 2);
			}
			.{$this->Name}>*>.item .more{
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap: calc(var(--size-0) / 2);
                text-align: end;
                align-items: flex-start;
                flex-direction: row-reverse;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item .more>*{
				opacity: 0;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .more>*{
            	opacity: 1;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
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
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover .image{
				opacity: 0.6;
				".(\MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")"))."
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item .description{
                gap: var(--size-0);
            	font-size: var(--size-2);
				position: relative;
                margin-bottom: var(--size-0);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name}>*>.item .content{
                font-size: var(--size-1);
            	text-align: justify;
				padding-top: var(--size-3);
				padding-bottom: var(--size-3);
			}
        ");
	}

	public function Get(){
        return join(PHP_EOL, iterator_to_array((function(){
		    module("Image" );
		    $img = new Image();
		    $img->Class = "image";
		    yield $img->GetStyle();
            $rout = null;
            if($this->ShowRoute){
                module("Route");
                $rout = new \MiMFa\Module\Route();
                $rout->Class = "route";
                $rout->Tag = "span";
            }
		    $i = 0;
		    yield $this->GetTitle();
		    yield $this->GetDescription();
		    foreach(Convert::ToItems($this->Items) as $k=>$item) {
                if(!($this->CheckAccess)($item)) continue;
                $p_meta = getValid($item,'MetaData' ,null);
			    if($p_meta !==null) {
                    $p_meta = Convert::FromJson($p_meta);
                    swap( $this, $p_meta);
                }
                $p_meta = null;
			    $p_id = get($item,'Id' );
			    $p_type = get($item,'Type' );
			    $p_image = getValid($item,'Image' , $this->DefaultImage);
			    $p_name = getBetween($item,'Name','Title')?? $this->DefaultTitle;
			    $p_title = getValid($item,'Title' , $p_name);
			    $p_description = getValid($item,'Description' , $this->DefaultDescription);
			    $p_content = getValid($item,'Content' ,$this->DefaultContent);
			    $p_class = get($item,'Class');

			    $p_showexcerpt = $this->ShowExcerpt;
			    $p_showcontent = $this->ShowContent;
			    $p_showdescription = $this->ShowDescription;
			    $p_showimage =  $this->ShowImage;
			    $p_showtitle = $this->ShowTitle;
                $p_showmeta =$this->ShowMetaData;
                $p_refering =$this->AutoRefering;
                $p_inselflink = (!$p_showcontent&&(!$p_showexcerpt||!$p_showdescription))? (getBetween($item, "Reference")??$this->RootRoute.getValid($item,'Name' ,$p_id)):null;
                if(!$this->CompressPath) {
                    $catDir = \_::$Back->Query->GetContentCategoryRoute($item);
                    if(isValid($catDir)) $p_inselflink = $this->CollectionRoute.trim($catDir,"/\\")."/".($p_name??$p_id);
                }
                $p_path = first(Convert::FromJson(getValid($item,'Path' , $this->DefaultPath)));
                if($this->ShowRoute) $rout->Set($p_inselflink);
			    $hasl = isValid($p_inselflink);
			    $p_showmorebutton = $hasl && $this->ShowMoreButton;
                $p_morebuttontext = Convert::FromSwitch($this->MoreButtonLabel, $p_type);
			    $p_showpathbutton = isValid($p_path) && $this->ShowPathButton;
                $p_pathbuttontext = Convert::FromSwitch($this->PathButtonLabel, $p_type);

                $p_excerpt = null;
			    if($this->AutoExcerpt){
                    $p_description = __(Convert::ToExcerpt(
                        Convert::ToText($p_description),
						    0,
						    $this->ExcerptLength,
						    $this->ExcerptSign
					    ), refering:$p_refering);
                    if($p_showexcerpt)
                        $p_excerpt = __(Convert::ToExcerpt(
						    Convert::ToText($p_content),
						    0,
						    $this->ExcerptLength,
						    $this->ExcerptSign
					    ), refering:$p_refering);
                }
                else $p_description = __($p_description, refering:$p_refering);

			    if($p_showmeta){
				    if($this->ShowAuthor)
                        doValid(
                            function($val) use(&$p_meta){
                                $authorName = table("User")->SelectRow("Signature , Name","Id=:Id",[":Id"=>$val]);
                                if(!isEmpty($authorName)) $p_meta .=  " ".Html::Link($authorName["Name" ],\_::$Address->UserRoute.$authorName["Signature" ],["class"=>"author"]);
                            },
                            $item,
                            'AuthorId'
                        );
                    if($this->ShowCreateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= Html::Span(Convert::ToShownDateTimeString($val), ["class"=>'createtime' ]);
                            },
                            $item,
                            'CreateTime'
                        );
                    if($this->ShowUpdateTime)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= Html::Span(Convert::ToShownDateTimeString($val), ["class"=>'updatetime' ]);
                            },
                            $item,
                            'UpdateTime'
                        );
                    if($this->ShowStatus)
                        doValid(
                            function($val) use(&$p_meta){
                                if(isValid($val)) $p_meta .= " <span class='status'>$val</span>";
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
                yield "<article class='item $p_type $p_class col-lg'". ($this->Animation? " data-aos-delay='".($i % $this->MaximumColumns*\_::$Front->AnimationSpeed)."' data-aos='{$this->Animation}'":"").">";
                yield "<div class='head row'>";
                    yield "<div class='col-lg'>";
                        $lt = $this->LinkedTitle && $hasl;
                        if($p_showtitle) yield Html::SuperHeading($p_title, $lt?$p_inselflink:null, ['class'=>'title']);
                        if($p_showmeta && isValid($p_meta)) {
                            yield "<sub class='metadata'>";
                            if($this->ShowRoute) yield $rout->ToString();
                            yield $p_meta."</sub>";
                        }
                    yield "</div>";
                    if($p_showmorebutton || $p_showpathbutton) {
                        yield "<div class='more col col-3 md-hide'>";
                        if($p_showmorebutton)
                            yield Html::Button($p_morebuttontext, $p_inselflink, ["class"=>'btn primary']);
                        if($p_showpathbutton)
                            yield Html::Button($p_pathbuttontext, $p_path, ["class"=>'btn outline']);
                        yield "</div>";
                    }
                yield "</div>";
                yield "<div class='description row'>";
                yield "<div class='excerpt col-md'>";
                if($p_showdescription && !isEmpty($p_description)) yield $p_description;
                if($p_showexcerpt) yield $p_excerpt;
                yield "</div>";
                if($p_showimage && isValid($p_image))
                    yield "<div class='col-lg col-lg-3'>".$img->ToString()."</div>";
                yield "</div>";
                if($p_showcontent && isValid($p_content))
                    yield "<div class='content'>".__($p_content, refering:$p_refering)."</div>";
                if($p_showmorebutton || $p_showpathbutton) {
                    yield "<div class='more md-show'>";
                    if($p_showmorebutton)
                        yield Html::Button($p_morebuttontext, $p_inselflink, ["class"=>'btn primary']);
                    if($p_showpathbutton)
                        yield Html::Button($p_pathbuttontext, $p_path, ["class"=>'btn outline']);
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