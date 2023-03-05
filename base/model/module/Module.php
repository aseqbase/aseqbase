<?php namespace MiMFa\Module;
//Guide for commenting
//
//NAME:  KEY = VALUE;
//
//	NAME		//The name or wildcards pattern of the variable (started with $) or function
//
//	KEY			//The name of below parameters
//				//	Access:[read,write] = read,write,not;
//				//	Type:[string] = bool,int,float,string,file,directory,email,url,number,location,name,tagname,attribute,html,css,js,code,array(*);
//				//	Options:[null] = array(options);
//
//	VALUE		//The indicated value

class Module extends \Base{
	///$*Direction: Options = array(null,"ltr","rtl");
	///$*Id: Type = name;
	///$*Name: Access = read;
	///$*Name: Type = name;
	///$*Class: Type = name;
	///$*Content: Type = array(html);
	///$*Tag: Type = tagname;
	///$*Attributes: Type = array(attribute);
	///$*Styles: Type = array(css);
	///$*Scripts: Type = array(js);
	///$*ScreenSize: Options = array("xxlg","xxlg-range","xlg","xlg-range","lg","lg-range","md","md-range","sm","sm-range","xsm","xsm-range","xxsm","xxsm-range");
	///$Allow*: Type = bool;

	public $Id = null;
	public $Name = null;
	public $Class = null;
	public $Title = null;
	public $Description = null;
	public $Content = null;
	public $Tag = "div";
	public $TitleTag = "h3";
	public $DescriptionTag = "div";
	public $ContentTag = null;
	public $Attributes = null;
	public $Styles = null;
	public $Scripts = null;
	public $ShowFromScreenSize = null;
	public $HideFromScreenSize = null;
	public $VisibleFromScreenSize = null;
	public $InvisibleFromScreenSize = null;
	
	public function __construct(){
		$this->Set_Defaults();
	}

	public function EchoStartTag($tag=null){
		if(isValid($tag??$this->Tag)) echo "<".($tag??$this->Tag??"div")." ".$this->GetDefaultAttributes().">";
	}
	public function EchoEndTag($tag=null){
		if(isValid($tag??$this->Tag)) echo "</".($tag??$this->Tag??"div").">";
	}
	public function GetDefaultAttributes(){
		return 
		$this->GetAttribute("id",$this->Id).
		$this->GetAttribute(" class",$this->Name.' '.$this->Class.
			(isValid($this->VisibleFromScreenSize)?" ".$this->VisibleFromScreenSize."-visible":"").
			(isValid($this->InvisibleFromScreenSize)?" ".$this->InvisibleFromScreenSize."-invisible":"").
			(isValid($this->ShowFromScreenSize)?" ".$this->ShowFromScreenSize."-show":"").
			(isValid($this->HideFromScreenSize)?" ".$this->HideFromScreenSize."-hide":"")
		).
		(isValid($this->Attributes)?" ".$this->Attributes:"");
	}
	public function GetAttribute($name,$value){
		return isValid($value)?("$name=\"$value\""):"";
	}

	public function EchoStyle(){ }

	public function EchoScript(){ }

	public function Echo(){
		if(isValid($this->Title)) echo (isValid($this->TitleTag)?"<".$this->TitleTag.">":"").__($this->Title).(isValid($this->TitleTag)?"</".$this->TitleTag.">":""); 
		if(isValid($this->Description)) echo (isValid($this->DescriptionTag)?"<".$this->DescriptionTag.">":"").__($this->Description).(isValid($this->DescriptionTag)?"</".$this->DescriptionTag.">":"");
		if(isValid($this->Content)) echo (isValid($this->ContentTag)?"<".$this->ContentTag.">":"").$this->Content.(isValid($this->ContentTag)?"</".$this->ContentTag.">":"");
	}

	public function Draw(){
		$this->PreDraw();
		$this->Styles??$this->EchoStyle();
		$this->EchoStartTag();
		$this->Echo();
		$this->EchoEndTag();
		$this->Scripts??$this->EchoScript();
		$this->PostDraw();
	}
	public function ReDraw(){
		$this->PreDraw();
		$this->EchoStartTag();
		$this->Echo();
		$this->EchoEndTag();
		$this->PostDraw();
	}

	public function PreDraw(){ }
	public function PostDraw(){ }
}?>