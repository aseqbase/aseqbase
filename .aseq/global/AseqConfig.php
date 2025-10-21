<?php
class AseqConfig extends ConfigBase
{
	public $DateTimeFormat = 'H:i, d M y';

	public $DisplayError = null;
	public $DisplayStartupError = null;
	public $ReportError = null;

	public $AllowReduceSize = true;
	public $AllowTextAnalyzing = true;
	public $AllowContentReferring = true;
	public $AllowCategoryReferring = false;
	public $AllowTagReferring = true;
	public $AllowUserReferring = false;

	public $EncryptNames = true;
	public $AllowSelecting = true;
	public $AllowContextMenu = true;

	/**
	 * Allow to leave comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowWriteComment = true;
	/**
	 * Access level to leave comments on posts
	 * @var int
	 * @category Content
	 */
	public $WriteCommentAccess = 1;
	/**
	 * Allow to read comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowReadComment = true;
	/**
	 * Access level to read comments on posts
	 * @var int
	 * @category Content
	 */
	public $ReadCommentAccess = 0;
	/**
	 * Default status of new comments on posts
	 * @var int
	 * @category Content
	 */
	public $DefaultCommentStatus = 0;
}
?>