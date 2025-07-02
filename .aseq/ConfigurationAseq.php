<?php
class ConfigurationAseq extends ConfigurationBase {
	/**
	* The Date Time Format
	* @var string
	* @example: "Y-m-d H:i:s" To show like 2018-08-10 14:46:45
	* @category Time
	*/
    public $DateTimeFormat = 'H:i, d M y';

	/**
      * 0: Not show Errors; 1: To show Errors
      * @field int
      * @var int|null
      * @category Debug
      */
	  public $DisplayError = null;
	  /**
	   * 0: Not show startup Errors; 1: To show startup Errors
	   * @field int
	   * @var int|null
	   * @category Debug
	   */
	  public $DisplayStartupError = null;
	  /**
	   * E_ error flags
	   * @field int
	   * @var int|null
	   * @category Debug
	   */
	  public $ReportError = null;
	  /**
	   * Database Errors
	   * @field int
	   * @var int|null
	   * @category Debug
	   */
	  public $DataBaseError = null;

	
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