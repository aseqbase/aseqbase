<?php
namespace MiMFa\Module;

use MiMFa\Library\HTML;
use MiMFa\Library\Convert;

class Members extends Module
{
	public $Items = null;
	public $DefaultIcon = null;
	public $DefaultName = null;
	public $DefaultDescription = null;
	public $DefaultDetails = null;
	public $DefaultLink = null;
	public $MoreButtonLabel = "Read More...";

	public function GetStyle()
	{
		return Html::Style("
            .{$this->Name} .teammember {
                background-color: " . \_::$Front->BackColor(0) . "99 var(--overlay-url-0);
                background-size: 100% auto;
                text-align: center;
                border: var(--border-1) var(--fore-color-2);
                border-radius: var(--radius-1);
                box-shadow: var(--shadow-1);
                margin-top: 150px;
                margin-bottom: 50px;
                padding-bottom: 15px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .teammember:hover {
                background-color: " . \_::$Front->BackColor(1) . "99;
                box-shadow: var(--shadow-2);
                border: var(--border-1) var(--fore-color-1);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .teammember div.image {
                text-align: center;
            }

            .{$this->Name} .teammember div.image img {
                background-color: " . \_::$Front->BackColor(0) . "99;
                border-radius: 100%;
                width: 200px;
                max-width: 75vmin;
                border: var(--border-1) var(--fore-color-2);
                margin-top: -100px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .teammember:hover div.image img {
                background-color: var(--back-color-1);
                border: var(--border-1) var(--fore-color-1);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .teammember .title {
                text-align: center;
                margin-top: 30px !important;
                margin-bottom: 30px !important;
            }

            .{$this->Name} .teammember .title * {
                font-size: var(--size-1);
                margin: -5px !important;
            }

            .{$this->Name} .teammember .title div {
                font-size: var(--size-2);
                font-size: 150%;
            }

            .{$this->Name} .teammember .features {
                text-align: center;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .{$this->Name} .teammember .features div {
                background-color: var(--back-color-0);
                border-radius: var(--radius-2);
                border: var(--border-1) var(--fore-color-2);
                margin: 5px;
                padding: 0px 10px;
                display: inline-block;
            }

            .{$this->Name} .teammember .list-group {
                margin: 2vmax 3vmax;
                border: none;
            }

            .{$this->Name} .teammember .list-item {
                background-color: transparent;
                padding: 1vmin 0px;
                margin: initial;
                border: none;
            }

            .{$this->Name} .teammember .list-item a {
                text-wrap: wrap;
                overflow-wrap: anywhere;
            }

            .{$this->Name} .teammember .badge-primary {
                background-color: var(--fore-color-0);
                color: var(--back-color-0);
            }

            .{$this->Name} .teammember .badge-primary:hover {
                background-color: var(--back-color-2);
                color: var(--fore-color-2);
            }
        ");
	}

	public function Get()
	{
		$menu = $this->Items;
		$count = count($menu);
		if ($count > 0) {
			// Start the container div
			return Html::Container(
				Html::Rack(function () use ($menu, $count) {
				$rowContent = "";
				for ($i = 0; $i < $count; $i++) {
					// Start a column for each team member
					$rowContent .= Html::MediumSlot(function () use ($menu, $i) {
						$memberContent = "";

						// Image
						$memberContent .= Html::Division(Html::Image(get($menu[$i], 'Image' )), ["class"=> "image"]);

						// Title (Name and Titles)
						$memberContent .= Html::Division(function () use ($menu, $i) {
							$titleContent = "";
							$titleContent .= Html::Super(__(get($menu[$i], 'PreName'), styling: false));
							$titleContent .= Html::Division(
								Html::Strong(__(get($menu[$i], 'FirstName'), styling: false) . " " . __(get($menu[$i], 'MiddleName'), styling: false) . " " . __(get($menu[$i], 'LastName'), styling: false))
							);
							$titleContent .= Html::Sub(__(get($menu[$i], 'PostName'), styling: false));
							return $titleContent;
						}, ["class"=> "title"]);

						// Features (Assignees)
						$memberContent .= Html::Division(function () use ($menu, $i) {
							$featuresContent = "";
							foreach (findValid($menu[$i], 'Assignees', []) as $assignee) {
								$featuresContent .= Html::Division(__($assignee, styling:false)) . Html::$NewLine;
							}
							return $featuresContent;
						}, ["class"=> "features"]);

						// List of Items (Details)
						$memberContent .= Html::Items(function () use ($menu, $i) {
							$listContent = "";
							foreach (findValid($menu[$i], 'Items', []) as $item) {
								$listContent .= Html::Item(
									Html::Italic(
										__(get($item, 'Key' ), styling: false) . __(":", styling: false) . __(findValid($item, 'Value' , ''), styling:false),
										null,[ "class"=>'fa ' . get($item, "class"), "aria-hidden"=>'true']),
									["class"=> "list-item d-flex justify-content-between align-items-center"]);
							}
							return $listContent;
						}, ["class"=> "list-group"]);

						// "Read More" Link
						$memberContent .= Html::Link(__($this->MoreButtonLabel, false), get($menu[$i], 'Link'), ["class"=> "btn", "target" => "blank"]);

						return $memberContent;
					}, ["data-aos" => "down"]); // Close the column div
				}
				return $rowContent; // Return the generated row content
				})
			);
		}
		return null;
	}
}
?>