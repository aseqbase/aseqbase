<?php
namespace MiMFa\Module;
use MiMFa\Library\DataBase;
use MiMFa\Library\Convert;
use MiMFa\Library\HTML;
/**
 * To show data as pages
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Page extends Module{
	/**
     * The whole document Item
     * @var object|null
     */
	public $Item = null;
	/**
     * The root directory or path
     * @var string|null
     */

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
     * @var string|null
     * @category Part
     */
	public $Animation = "zoom-up";

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

    public function GetStyle()
    {
        return Html::Style("
            .{$this->Name} {
                height: fit-content;
                margin-top: var(--size-3);
                margin-bottom: var(--size-3);
                padding: var(--size-4);
                font-size: var(--size-0);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .head {
                margin-bottom: var(--size-2);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .title {
                padding: 0px;
                margin: 0px;
                text-align: unset;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .metadata {
                font-size: var(--size-0);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .metadata .route {
                padding-inline-end: var(--size-0); 
            }

            .{$this->Name} .more {
                text-align: end;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .more > a {
                opacity: 0;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name}:hover .more > a {
                opacity: 1;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .image {
                width: {$this->ImageWidth};
                height: {$this->ImageHeight};
                min-height: {$this->ImageMinHeight};
                min-width: {$this->ImageMinWidth};
                max-height: {$this->ImageMaxHeight};
                max-width: {$this->ImageMaxWidth};
                overflow: hidden;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .description {
                font-size: var(--size-2);
                padding-top: var(--size-2);
                padding-bottom: var(--size-2);
                text-align: justify;
                position: relative;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .content {
                font-size: var(--size-1);
                text-align: justify;
                color: var(--fore-color-1);
                background-color: var(--back-color-1);
                padding-top: var(--size-3);
                padding-bottom: var(--size-3);
            }
        ");
    }

    public function Get()
    {
        $item = $this->Item;
        $p_access = get($item, 'Access' );

        if (!inspect($p_access, die: false)) return null; // Or return an appropriate empty HTML string

        module("Image" ); // Assuming this function makes the Image class available
        $img = new \MiMFa\Module\Image(); // Make sure the correct namespace is used
        $img->Class = "image";

        $p_id = get($item, 'Id' );
        $p_type = get($item, 'Type' );
        $p_image = findValid($item, 'Image' , $this->Image);
        $p_name = findBetween($item, 'Name', 'Title')?? $this->Title;
        $p_title = findValid($item, 'Title' , $p_name);
        $p_description = findValid($item, 'Description' , $this->Description);
        $p_content = findValid($item, 'Content' , $this->Content);
        $p_class = get($item, 'class');

        if ($this->ShowRoute) {
            module("Route"); // Assuming this makes the Route class available
        }

        $p_meta = findValid($item, 'MetaData' , null);
        if ($p_meta !== null) {
            $p_meta = Convert::FromJson($p_meta);
        }

        $p_showcontent = $this->ShowContent || findValid($p_meta, "ShowContent", false);
        $p_showdescription = $this->ShowDescription || findValid($p_meta, "ShowDescription", false);
        $p_showimage = $this->ShowImage || findValid($p_meta, "ShowImage", false);
        $p_showtitle = $this->ShowTitle || findValid($p_meta, "ShowTitle", false);
        $p_showmeta = $this->ShowMetaData || findValid($p_meta, "ShowMeta", false);
        $p_path = findValid($item, 'Path' , $this->Path);
        $hasl = isValid($p_path);
        $p_showmorebutton = $hasl && ($this->ShowMoreButton || findValid($p_meta, "ShowMoreButton", false));
        $p_morebuttontext = findValid($p_meta, "MoreButtonLabel", $this->MoreButtonLabel);
        $p_meta = null;


        if ($p_showmeta) {
            if ($this->ShowAuthor) {
                doValid(function ($val) use (&$p_meta) {
                    $authorName = table("User")->DoSelectValue("Name" , "Id=:Id", [":Id" => $val]);
                    if (isValid($authorName)) {
                        $p_meta .= Html::Span($authorName, ["class"=> "author"]); // Use Html::Tag
                    }
                }, $item, 'AuthorId' );
            }

            // ... (Similar conversions for ShowCreateTime, ShowUpdateTime, ShowStatus, ShowButtons using Html::Tag)
            if ($this->ShowCreateTime) {
                doValid(function ($val) use (&$p_meta) {
                    if (isValid($val)) {
                        $p_meta .= Html::Span($val, ["class"=> "createtime"]);
                    }
                }, $item, 'CreateTime' );
            }

            if ($this->ShowUpdateTime) {
                doValid(function ($val) use (&$p_meta) {
                    if (isValid($val)) {
                        $p_meta .= Html::Span($val, ["class"=> "updatetime"]);
                    }
                }, $item, 'UpdateTime' );
            }

            if ($this->ShowStatus) {
                doValid(function ($val) use (&$p_meta) {
                    if (isValid($val)) {
                        $p_meta .= Html::Span($val, ["class"=> "status"]);
                    }
                }, $item, 'Status' );
            }

            if ($this->ShowButtons) {
                doValid(function ($val) use (&$p_meta) {
                    if (isValid($val)) {
                        $p_meta .= $val; // Assuming $val is already HTML
                    } else {
                        $p_meta .= $this->Buttons; // Assuming $this->Buttons is already HTML
                    }
                }, $item, 'Buttons');
            }
        }

        $img->Source = $p_image;

        return Html::Container( // Main container
            function () use ($this, $p_showtitle, $p_title, $p_showcontent, $hasl, $p_path, $p_showmeta, $p_meta, $p_showmorebutton, $p_morebuttontext, $p_showdescription, $p_description, $p_showimage, $img, $p_content) {
                yield $img->GetStyle(); // Use GetStyle() 
                {
                    $headContent = Html::MediumSlot(function() use ($p_showtitle, $p_title, $hasl, $p_path, $p_showmeta, $p_meta){
                    if($p_showtitle) yield Html::SuperHeading(__($p_title),($hasl && $this->LinkedTitle) ? $p_path:null, ["class"=> "title"]);

                    if($p_showmeta && isValid($p_meta)){
                        yield Html::Sub($p_meta, ["class"=> "metadata"]);
                        if ($this->ShowRoute) {
                            $route = new \MiMFa\Module\Route($p_path);
                            $route->Tag = "span";
                            $route->Class = "route";
                            yield $route->ToString(); // Assuming ReDraw returns HTML
                        }
                    }
                    });

                    $moreButtonHead = "";
                    if ($p_showmorebutton) {
                        $moreButtonHead = Html::Division(Html::Link(__($p_morebuttontext), $p_path, ["class"=> "btn btn-outline"]), ["class"=> "more col-sm col-3 md-hide"]);
                    }

                    yield Html::Rack($headContent . $moreButtonHead, ["class"=>"head"]);
                }
                yield Html::Rack(function() use ($p_showdescription, $p_description, $p_showimage, $img){
                    $descriptionContent = "";
                    if ($p_showdescription) {
                        $descriptionContent .= __($p_description);
                    }
                    $imageContent = "";
                    if ($p_showimage && isValid($img->Source)) { //Check if image source is valid
                        $imageContent .= Html::Division($img->ToString(), ["class"=> "col-5"]);
                    }
                    return $descriptionContent . $imageContent;
                }, ["class"=>"description" ]);

                if ($p_showcontent && isValid($p_content))
                    yield Html::Division(__($p_content), ["class"=> "content"]);
                elseif ($p_showmorebutton)
                    yield Html::Division(Html::Link(__($p_morebuttontext), $p_path, ["class"=> "btn btn-block btn-outline"]), ["class"=> "more md-show"]);
            },
            ["class"=> "$p_type $p_class", ...($this->Animation ? ["data-aos"=>$this->Animation] : [])]
        );
    }
}
?>