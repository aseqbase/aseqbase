<?php
namespace MiMFa\Component;
use MiMFa\Library\Convert;

class JSONLD extends Component{
	public $HasWebsite = true;

	public function Echo(){
		if($this->HasWebsite) $this->EchoWebsite();
	}
	
	public function EchoWebsite($name = null, $url = null, $searchUrl = null){
		?>
		<!---prepend:<\/head>--->
		<script type="application/ld+json">
			{
			  "@context": "https://schema.org/",
			  "@type": "WebSite",
			  "name": "<?php echo $name??\_::$INFO->FullName; ?>",
			  "url": "<?php echo $url??\_::$HOST; ?>",
			  "potentialAction": {
				"@type": "SearchAction",
				"target": "<?php echo $searchUrl??(\_::$HOST."/search?q={search_term_string}"); ?>",
				"query-input": "required name=search_term_string"
			  }
			}
		</script>
		<!---prepend--->
		<?php
	}
	public function EchoArticle($title = null, $excerpt = null, $image = null, $author = ["name" => null, "url" =>null, "image"=>null],$publisher = ["name" => null, "url" =>null, "image"=>null], $datePublished = null, $dateModified = null, $type="Article"){
		?>
		<!---prepend:<\/head>--->
		<script type="application/ld+json">
			{
			  "@context": "https://schema.org",
			  "@type": "<?php echo $type;?>",
			  "headline": "<?php echo Convert::ToExcerpt($title??\_::$INFO->FullName,0,110); ?>",
			  "description": "<?php echo $excerpt??\_::$INFO->Description; ?>",
			  "image": "<?php echo $image??\_::$INFO->FullLogoPath; ?>",  
			  "author": {
				"@type": "Person",
				"name": "<?php echo getValid($author,"name"); ?>",
				"url": "<?php echo getValid($author,"url",(\_::$HOST.(isValid($author,"name")?"":"/".getValid($author,"name")))); ?>"
			  },  
			  "publisher": {
				"@type": "Organization",
				"name": "<?php echo getValid($publisher,"name"); ?>",
				"url": "<?php echo getValid($publisher,"url",(\_::$HOST.(isValid($publisher,"name")?"":"/".getValid($publisher,"name")))); ?>"
				"logo": {
				  "@type": "ImageObject",
				  "url": "<?php echo getValid($publisher,"url",\_::$INFO->LogoPath); ?>"
				}
			  },
			  "datePublished": "<?php echo $datePublished; ?>",
			  "dateModified": "<?php echo $dateModified; ?>"
			}
		</script>
		<!---prepend--->
		<?php
	}
}
?>