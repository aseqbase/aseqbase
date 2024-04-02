<?php
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
use MiMFa\Library\HTML;
use MiMFa\Library\Style;
use MiMFa\Library\Translate;
echo HTML::Style("
	.page-home {
    	margin-top: -60px;
		padding: 0px 0px var(--Size-5);
	}
	.page-home .frame .part-home:nth-child(odd){
    	background-color: var(--BackColor-0);
		color: var(--ForeColor-0);
	}
	.page-home .frame .part-home:nth-child(even){
    	background-color: var(--BackColor-1);
		color: var(--ForeColor-1);
	}
	.page-home .frame .part-home .heading {
    	text-align: center;
		align-content: center;
		margin: 0px;
		padding: var(--Size-5);
		".(Translate::$Direction == "RTL"?"border-left: var(--Border-1) #88888828;":"border-right: var(--Border-1) #88888828;")."
	}
	.page-home .frame .part-home .superheading {
    	margin: 0px;
		padding: 0px;
	}
	.page-home .frame .part-home .heading .button {
		font-size: var(--Size-3);
	}
	.page-home .frame .part-home .items {
    	text-align: initial;
	}
	.page-home .frame .part-home .items a {
		display: flex;
		font-size: var(--Size-1);
		flex-direction: row;
		flex-wrap: nowrap;
		align-content: center;
		justify-content: space-between;
		align-items: center;
		padding: calc(var(--Size-0) / 3);
    	".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
	}
	.page-home .frame .part-home .items a>.icon {
		height: var(--Size-3);
		width: var(--Size-3);
		box-shadow: var(--Shadow-1);
		border-radius: var(--Radius-5);
    	opacity: 0;
    	".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
	}
	.page-home .frame .part-home .items a:nth-child(odd) {
    	background-color: #88888808;
    	".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
	}
	.page-home .frame .part-home .items a:hover {
    	background-color: #88888820;
    	".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
	}
	.page-home .frame .part-home .items a:hover>.icon {
    	opacity: 1;
    	".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
	}
	@media screen and (max-width: 992px) {
		.page-home .frame .part-home .items a>.icon {
    		opacity: 1;
		}
	}
");
echo "<div class='page-home'>";
	MODULE("PostCollection");
	$module = new \MiMFa\Module\PostCollection();
	$module->Style = new \MiMFa\Library\Style();
	$module->Style->Padding = "var(--Size-5)";
	$module->Class = "container-fluid";
	$module->MaximumColumns = 3;
	$module->ShowRoute = false;
	$module->DefaultImage = \_::$INFO->FullLogoPath;

	//PART("slide-show");
	PART("about");
	$table_content = \_::$CONFIG->DataBasePrefix."Content";
	echo HTML::LargeFrame(function() use($module, $table_content){
        $num = 30;
        foreach (preg_find_all('/(?<=(\'|\"))[^\'\"\,]+(?=\1)/', DataBase::DoSelectValue("INFORMATION_SCHEMA.COLUMNS","`COLUMN_TYPE`", "`TABLE_NAME`='$table_content' AND `COLUMN_NAME`='Type'")) as $value)
            if(!isEmpty($items = DataBase::DoSelect($table_content,"*",
                User::GetAccessCondition()." AND `Type`='$value' ORDER BY `Priority` DESC, `UpdateTime` DESC LIMIT $num"))
            ){
				switch ($value)
                {
                	case "File":
                	case "Book":
                	case "Article":
                        $module->PathButtonLabel = "Download";
						break;
                	case "News":
                	case "Post":
                	case "Text":
                        $module->PathButtonLabel = "Refer";
						break;
                	case "Product":
                        $module->PathButtonLabel = "Visit";
						break;
                	default:
                        $module->PathButtonLabel = "Visit";
						break;
                }
				$module->Items = array_slice($items, 0, 9);
                yield HTML::Rack(
					HTML::LargeSlot(
						HTML::SuperHeading($value,"/search?type=$value").
						HTML::Division(
							loop($items, function($k,$v){ return HTML::Link(
												 HTML::Span($v["Title"])." ".HTML::Icon($v["Image"])
												 //.HTML::Tooltip(HTML::Icon($v["Image"])." ".HTML::Big($v["Title"]).": ".HTML::Span($v["Description"]))
											 ,"/post/".$v["Name"]);})
						, ["class"=>"items"]).
						HTML::$NewLine.HTML::Button(count($items)===$num? "See More..." : "Read More...","/search?type=$value")
					, ["class"=>"heading col-lg-2"]).
                    HTML::LargeSlot($module->DoCapture(), ["class"=>"col-lg-10"])
				, ["class"=>"part-home"]);
            }
    });
echo "</div>";
?>
