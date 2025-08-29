<?php namespace MiMFa\Component;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;

class JsonLD{
	public static function Create($data){
		if(!isEmpty($data)) 
        return Html::Script(is_iterable($data)?
			("{
			  '@context': `".(grab($data, "@context")??"https://schema.org")."`,
			  '@type': `".(grab($data, "@type")??"Article")."`,
			  ".loop($data, fn($v, $k)=>'"'.$k.'":'.(is_iterable($v)?Convert::ToJson($v):'"'.$v.'"'))."
			}"):$data
		, ['type' =>'application/ld+json']);
	}

	public static function Website($name = null, $url = null, $searchUrl = null){
        return "<!---prepend:<\/head>--->".Html::Script(
			"{
			  '@context': 'https://schema.org/',
			  '@type': 'WebSite',
			  'name': `".Convert::ToText($name??\_::$Info->FullName)."`,
			  'url': `".($url??\_::$Host)."`,
			  'potentialAction': {
				'@type': 'SearchAction',
				'target': `".($searchUrl??(\_::$Host.'/search?q={search_term_string}'))."`,
				'query-input': 'required name=search_term_string'
			  }
			}"
		,['type' =>'application/ld+json'])."<!---prepend--->";
	}
	public static function Article($title = null, $excerpt = null, $image = null, $author = ["Name" => null, "Url" =>null, "Image" =>null],$publisher = ["Name" => null, "Url" =>null, "Image" =>null], $datePublished = null, $dateModified = null, $type="Article"){
        return "<!---prepend:<\/head>--->".Html::Script(
			"{
			  '@context': 'https://schema.org',
			  '@type': `$type`,
			  'headline': `".Convert::ToExcerpt(Convert::ToText($title??\_::$Info->FullName),0,110)."`,
			  'description': `".Convert::ToText($excerpt??\_::$Info->Description)."`,
			  'image': `".($image??\_::$Info->FullLogoPath)."`,
			  'author': {
				'@type': 'Person',
				'name': `".get($author,'Name' )."`,
				'url': `".getValid($author,'Url',(\_::$Host.(isValid($author,'Name' )?'':'/'.get($author,'Name' ))))."`
			  },
			  'publisher': {
				'@type': 'Organization',
				'name': `".get($publisher,'Name' )."`,
				'url': `".getValid($publisher,'Url',(\_::$Host.(isValid($publisher,'Name' )?'':'/'.get($publisher,'Name' ))))."`
				'logo': {
				  '@type': 'ImageObject',
				  'url': `".getValid($publisher,'Url',\_::$Info->LogoPath)."`
				}
			  },
			  'datePublished': `$datePublished`,
			  'dateModified': `$dateModified`
			}"
		, ['type' =>'application/ld+json'])."<!---prepend--->";
	}
}

JsonLD::Create($data??null);
\_::$Front->Libraries[] = \MiMFa\Component\JsonLD::Website();