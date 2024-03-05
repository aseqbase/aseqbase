<?php
namespace MiMFa\Component;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;

class JSONLD extends Component{
	public $HasWebsite = true;

	public function Echo(){
		if($this->HasWebsite) $this->EchoWebsite();
	}
	public function Get(){
		if($this->HasWebsite) $this->GetWebsite();
	}

	public function EchoWebsite($name = null, $url = null, $searchUrl = null){
		echo $this->GetWebsite($name, $url, $searchUrl);
	}
	public function EchoArticle($title = null, $excerpt = null, $image = null, $author = ["name" => null, "url" =>null, "image"=>null],$publisher = ["name" => null, "url" =>null, "image"=>null], $datePublished = null, $dateModified = null, $type="Article"){
		echo $this->GetArticle($title, $excerpt, $image, $author,$publisher, $datePublished, $dateModified, $type);
	}


	public function GetWebsite($name = null, $url = null, $searchUrl = null){
        return "<!---prepend:<\/head>--->".HTML::Script(
			"{
			  '@context': 'https://schema.org/',
			  '@type': 'WebSite',
			  'name': `".($name??\_::$INFO->FullName)."`,
			  'url': `".($url??\_::$HOST)."`,
			  'potentialAction': {
				'@type': 'SearchAction',
				'target': `".($searchUrl??(\_::$HOST.'/search?q={search_term_string}'))."`,
				'query-input': 'required name=search_term_string'
			  }
			}"
		,['type'=>'application/ld+json'])."<!---prepend--->";
	}
	public function GetArticle($title = null, $excerpt = null, $image = null, $author = ["name" => null, "url" =>null, "image"=>null],$publisher = ["name" => null, "url" =>null, "image"=>null], $datePublished = null, $dateModified = null, $type="Article"){
        return "<!---prepend:<\/head>--->".HTML::Script(
			"{
			  '@context': 'https://schema.org',
			  '@type': `$type`,
			  'headline': `".Convert::ToExcerpt($title??\_::$INFO->FullName,0,110)."`,
			  'description': `".($excerpt??\_::$INFO->Description)."`,
			  'image': `".($image??\_::$INFO->FullLogoPath)."`,
			  'author': {
				'@type': 'Person',
				'name': `".getValid($author,'name')."`,
				'url': `".getValid($author,'url',(\_::$HOST.(isValid($author,'name')?'':'/'.getValid($author,'name'))))."`
			  },
			  'publisher': {
				'@type': 'Organization',
				'name': `".getValid($publisher,'name')."`,
				'url': `".getValid($publisher,'url',(\_::$HOST.(isValid($publisher,'name')?'':'/'.getValid($publisher,'name'))))."`
				'logo': {
				  '@type': 'ImageObject',
				  'url': `".getValid($publisher,'url',\_::$INFO->LogoPath)."`
				}
			  },
			  'datePublished': `$datePublished`,
			  'dateModified': `$dateModified`
			}"
		,['type'=>'application/ld+json'])."<!---prepend--->";
	}
}
?>