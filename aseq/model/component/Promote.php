<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;

class Promote{
	public static function Create($data){
		if(!isEmpty($data)) 
        return Struct::Script(is_iterable($data)?
			("{
			  '@context': `".(pop($data, "@context")??"https://schema.org")."`,
			  '@type': `".(pop($data, "@type")??"Article")."`,
			  ".loop($data, fn($v, $k)=>'"'.$k.'":'.(is_iterable($v)?Convert::ToJson($v):'"'.$v.'"'))."
			}"):$data
		, ['type' =>'application/ld+json']);
	}

	public static function Website($name = null, $url = null, $searchUrl = null){
        return "<!---prepend:<\/head>--->".Struct::Script(
			"{
			  '@context': 'https://schema.org/',
			  '@type': 'WebSite',
			  'name': `".Convert::ToText($name??\_::$Front->FullName)."`,
			  'url': `".($url??\_::$User->Host)."`,
			  'potentialAction': {
				'@type': 'SearchAction',
				'target': `".($searchUrl??(\_::$User->Host.'/search?q={search_term_string}'))."`,
				'query-input': 'required name=search_term_string'
			  }
			}"
		,['type' =>'application/ld+json'])."<!---prepend--->";
	}
	public static function Article($title = null, $excerpt = null, $image = null, $author = ["Name" => null, "Url" =>null, "Image" =>null],$publisher = ["Name" => null, "Url" =>null, "Image" =>null], $datePublished = null, $dateModified = null, $type="Article"){
        return "<!---prepend:<\/head>--->".Struct::Script(
			"{
			  '@context': 'https://schema.org',
			  '@type': `$type`,
			  'headline': `".Convert::ToExcerpt(Convert::ToText($title??\_::$Front->FullName),0,110)."`,
			  'description': `".Convert::ToText($excerpt??\_::$Front->Description)."`,
			  'image': `".($image??\_::$Front->FullLogoPath)."`,
			  'author': {
				'@type': 'Person',
				'name': `".get($author,'Name' )."`,
				'url': `".getValid($author,'Url',(\_::$User->Host.(isValid($author,'Name' )?"":'/'.get($author,'Name' ))))."`
			  },
			  'publisher': {
				'@type': 'Organization',
				'name': `".get($publisher,'Name' )."`,
				'url': `".getValid($publisher,'Url',(\_::$User->Host.(isValid($publisher,'Name' )?"":'/'.get($publisher,'Name' ))))."`
				'logo': {
				  '@type': 'ImageObject',
				  'url': `".getValid($publisher,'Url',\_::$Front->LogoPath)."`
				}
			  },
			  'datePublished': `$datePublished`,
			  'dateModified': `$dateModified`
			}"
		, ['type' =>'application/ld+json'])."<!---prepend--->";
	}
}

Promote::Create($data??null);
\_::$Front->Libraries[] = Promote::Website();