<?php
namespace MiMFa\Module;

use MiMFa\Library\Html;
class Members extends Module
{
    public $Items = null;
    public $DefaultIcon = null;
    public $DefaultName = null;
    public $DefaultDescription = null;
    public $DefaultDetails = null;
    public $DefaultPath = null;
    public $MoreButtonLabel = "Read More...";

    public function GetStyle()
    {
        return Html::Style("
            .{$this->Name} .items {
                gap: var(--size-1);
            }
            .{$this->Name} .teammember {
                background-color: var(--back-color) var(--overlay-url-0);
                background-size: 100% auto;
                text-align: center;
                border: var(--border-1) var(--fore-color-outside);
                border-radius: var(--radius-1);
                box-shadow: var(--shadow-1);
                margin-top: 150px;
                margin-bottom: 50px;
                padding-bottom: 15px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->Name} .teammember:hover {
                background-color: var(--back-color-inside);
                box-shadow: var(--shadow-2);
                border: var(--border-1) var(--fore-color-inside);
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->Name} .teammember div.image {
                text-align: center;
            }

            .{$this->Name} .teammember div.image img {
                background-color: var(--back-color);
                border-radius: 100%;
                width: 200px;
                max-width: 75vmin;
                border: var(--border-1) var(--fore-color-outside);
                margin-top: -100px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->Name} .teammember:hover div.image img {
                background-color: var(--back-color-inside);
                border: var(--border-1) var(--fore-color-inside);
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
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
                background-color: var(--back-color);
                border-radius: var(--radius-2);
                border: var(--border-1) var(--fore-color-outside);
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
                background-color: var(--fore-color);
                color: var(--back-color);
            }

            .{$this->Name} .teammember .badge-primary:hover {
                background-color: var(--back-color-outside);
                color: var(--fore-color-outside);
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
                    for ($i = 0; $i < $count; $i++) {
                        // Start a column for each team member
                        yield Html::MediumSlot(function () use ($menu, $i) {
                            // Image
                            yield Html::Division(Html::Image(null, get($menu[$i], 'Image')), ["class" => "image"]);

                            // Title (Name and Titles)
                            yield Html::Division(function () use ($menu, $i) {
                                yield Html::Super(__(get($menu[$i], 'PreName'), styling: false));
                                yield Html::Division(
                                    Html::Strong(__(get($menu[$i], 'FirstName'), styling: false) . " " . __(get($menu[$i], 'MiddleName'), styling: false) . " " . __(get($menu[$i], 'LastName'), styling: false))
                                );
                                yield Html::Sub(__(get($menu[$i], 'PostName'), styling: false));
                            }, ["class" => "title"]);

                            // Features (Assignees)
                            yield Html::Division(function () use ($menu, $i) {
                                foreach (getValid($menu[$i], 'Assignees', []) as $assignee)
                                    yield Html::Division(__($assignee, styling: false)) . Html::$Break;
                            }, ["class" => "features"]);

                            // List of Items (Details)
                            yield Html::Items(function () use ($menu, $i) {
                                foreach (getValid($menu[$i], 'Items', []) as $item) {
                                    yield Html::Item(
                                        Html::Italic(
                                            __(get($item, 'Key'), styling: false) . __(":", styling: false),
                                            null,
                                            ["class" => 'fa ' . get($item, "class"), "aria-hidden" => 'true']
                                        ) . __(getValid($item, 'Value', ''), styling: false),
                                        ["class" => "list-item d-flex justify-content-between align-items-center"]
                                    );
                                }
                            }, ["class" => "list-group"]);

                            // "Read More" Link
                            yield Html::Link(__($this->MoreButtonLabel, false), get($menu[$i], 'Link'), ["class" => "btn", "target" => "blank"]);
                        }, ["class" => "teammember", "data-aos" => "down"]); // Close the column div
                    }
                }, ["class"=>"items"])
            );
        }
        return null;
    }
}
?>