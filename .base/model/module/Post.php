<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\Translate;
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Modules See the Documentation
 */
class Post extends Module{
    public $Tag = "article";

	/**
     * The whole document Item
     * @var object|null
     */
	public $Item = null;
	/**
     * The root directory or path
     * @var string|null
     */
	public $Root = "/post/";

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

	public function EchoStyle(){
		$ralign = Translate::$Direction=="RTL"?"left":"right";
?>
		<style>
			.<?php echo $this->Name; ?> {
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
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}

			.<?php echo $this->Name; ?> .head{
				margin-bottom: var(--Size-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}

			.<?php echo $this->Name; ?> .title{
                padding: 0px;
                margin: 0px;
				text-align: unset;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .metadata{
				font-size: var(--Size-0);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .metadata .route{
				padding-<?php echo $ralign; ?>: var(--Size-0);
			}
			.<?php echo $this->Name; ?> .more{
				text-align: <?php echo $ralign; ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .more>a{
            	opacity: 0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?>:hover .more>a{
            	opacity: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			/* Style the images inside the grid */
			.<?php echo $this->Name; ?> .image {
				width: <?php echo $this->ImageWidth; ?>;
				height: <?php echo $this->ImageHeight; ?>;
				min-height: <?php echo $this->ImageMinHeight; ?>;
				min-width: <?php echo $this->ImageMinWidth; ?>;
				max-height: <?php echo $this->ImageMaxHeight; ?>;
				max-width: <?php echo $this->ImageMaxWidth; ?>;
				overflow: hidden;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .description{
            	font-size: var(--Size-2);
				padding-top: var(--Size-2);
				padding-bottom: var(--Size-2);
            	text-align: justify;
				position: relative;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .content{
                font-size: var(--Size-1);
                text-align: justify;
            	background-Color: var(--BackColor-0);
				padding-top: var(--Size-3);
				padding-bottom: var(--Size-3);
			}
	</style>
	<?php
	}
   
    public function PreDraw(){
	    $item = $this->Item;
	    $p_type = getValid($item,'Type');
	    $p_class = getValid($item,'Class');
        $this->Class = "$p_type $p_class container";
        if($this->AllowAnimation) $this->Attributes =  "data-aos='zoom-up' data-aos-offset='-500'";
    }

	public function Echo(){
		$item = $this->Item;
		$p_access = getValid($item,'Access');
		if(!ACCESS($p_access,false)) return;
		MODULE("Image");
		$img = new Image();
		$img->Class = "image";
		$img->EchoStyle();
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
        $p_inselflink =  $this->Root.($p_name??$p_id);
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
		if($p_showmeta){
            if($this->ShowAuthor)
                doValid(
                    function($val) use(&$p_meta){
                        $authorName = DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix,"Name","ID=:ID",[":ID"=>$val]);
                        if(isValid($authorName)) $p_meta .= "<span class='Author'>$authorName</span>";
                    },
                    $item,
                    'AuthorID'
                );
            if($this->ShowCreateTime)
                doValid(
                    function($val) use(&$p_meta){
                        if(isValid($val)) $p_meta .= "<span class='CreateTime'>$val</span>";
                    },
                    $item,
                    'CreateTime'
                );
            if($this->ShowUpdateTime)
                doValid(
                    function($val) use(&$p_meta){
                        if(isValid($val)) $p_meta .= "<span class='UpdateTime'>$val</span>";
                    },
                    $item,
                    'UpdateTime'
                );
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

		$img->Source = $p_image;?>
		<div class="head row">
			<div class="col-md">
                <?php
				$lt = $this->LinkedTitle && $hasl;
				if($p_showtitle) echo ($lt?"<a href='$p_inselflink'>":"")."<h2 class='title'>".__($p_title)."</h2>".($lt?"</a>":"");
				if($p_showmeta && isValid($p_meta)){
                    echo "<sub class='metadata'>";
                    if($this->ShowRoute){
                        $route = new \MiMFa\Module\Route($p_inselflink);
                        $route->Tag = "span";
                        $route->Class = "route";
                        $route->ReDraw();
                    }
                    echo $p_meta."</sub>";
                }?>
			</div>
			<?php if($p_showmorebutton) {?>
			<div class="more col-sm col-3 md-hide">
				<a class="btn btn-outline" href="<?php echo $p_path; ?>">
                    <?php echo __($p_morebuttontext); ?>
				</a>
			</div>
			<?php } ?>
		</div>
		<div class="description row">
			<div class="excerpt col-md">
				<?php if($p_showdescription) echo __($p_description); ?>
                <?php if($p_showexcerpt) echo $p_excerpt; ?>
			</div>
            <?php if($p_showimage && isValid($p_image)){
                echo "<div class='col-5'>";
                $img->ReDraw();
                echo "</div>";
            }?>
		</div>
        <?php if($p_showcontent && isValid($p_content)): ?>
		<div class="content">
			<?php echo __($p_content); ?>
		</div>
		<?php elseif($p_showmorebutton):?>
		<div class="more md-show">
			<a class="btn btn-block btn-outline" href="<?php echo $p_path; ?>">
                <?php echo __($p_morebuttontext); ?>
			</a>
		</div>
		<?php endif; ?>
		<?php
	}
}
?>