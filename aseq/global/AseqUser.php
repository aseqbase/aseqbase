<?php
class AseqUser extends UserBase
{
	/**
	 * Allow signing in and up to the guests
	 * @var bool
	 * @category Security
	 */
	public $AllowSigning = true;
	
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

	/**
	 * Default site key for Captcha Identifying
	 * Completely Automated Public Turing test to tell Computers and Humans Apart
	 * @var string
	 * @category Security
	 */
	public $CaptchaKey = null;
	
}