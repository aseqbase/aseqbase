<?php
namespace MiMFa\Module;

use MiMFa\Library\Struct;
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
        return Struct::Style("
            .{$this->MainClass} .items {
                gap: var(--size-1);
            }
            .{$this->MainClass} .teammember {
                background-color: var(--back-color) var(--overlay-url-0);
                background-size: 100% auto;
                text-align: center;
                border: var(--border-1) var(--fore-color-output);
                border-radius: var(--radius-1);
                box-shadow: var(--shadow-1);
                margin-top: 150px;
                margin-bottom: 50px;
                padding-bottom: 15px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->MainClass} .teammember:hover {
                background-color: var(--back-color-input);
                box-shadow: var(--shadow-2);
                border: var(--border-1) var(--fore-color-input);
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->MainClass} .teammember .image {
                overflow: visible;
                text-align: center;
            }

            .{$this->MainClass} .teammember div.image img {
                background-color: var(--back-color);
                border-radius: 100%;
                width: 200px;
                max-width: 75vmin;
                border: var(--border-1) var(--fore-color-output);
                margin-top: -100px;
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->MainClass} .teammember:hover div.image img {
                background-color: var(--back-color-input);
                border: var(--border-1) var(--fore-color-input);
                " . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . ";
            }

            .{$this->MainClass} .teammember .title {
                text-align: center;
                margin-top: 30px !important;
                margin-bottom: 30px !important;
            }

            .{$this->MainClass} .teammember .title * {
                font-size: var(--size-1);
                margin: -5px !important;
            }

            .{$this->MainClass} .teammember .title div {
                font-size: var(--size-2);
                font-size: 150%;
            }

            .{$this->MainClass} .teammember .features {
                text-align: center;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .{$this->MainClass} .teammember .features div {
                background-color: var(--back-color);
                border-radius: var(--radius-2);
                border: var(--border-1) var(--fore-color-output);
                margin: 5px;
                padding: 0px 10px;
                display: inline-block;
            }

            .{$this->MainClass} .teammember .list-group {
                margin: 2vmax 3vmax;
                border: none;
            }

            .{$this->MainClass} .teammember .list-item {
                background-color: transparent;
                padding: 1vmin 0px;
                margin: initial;
                border: none;
            }

            .{$this->MainClass} .teammember .list-item a {
                text-wrap: wrap;
                overflow-wrap: anywhere;
            }

            .{$this->MainClass} .teammember .badge {
                font-size: inherit;
                font-weight: normal;
                background-color: var(--back-color);
                color: var(--fore-color);
            }

            .{$this->MainClass} .teammember .badge.main {
                background-color: var(--fore-color);
                color: var(--back-color);
            }

            .{$this->MainClass} .teammember .badge:hover {
                font-weight: bold;
                background-color: var(--back-color-output);
                color: var(--fore-color-output);
            }
        ");
    }

    public function GetInner()
    {
        $menu = $this->Items;
        $count = count($menu);
        if ($count > 0) {
            // Start the container div
            return Struct::Container(
                Struct::Rack(function () use ($menu, $count) {
                    for ($i = 0; $i < $count; $i++) {
                        // Start a column for each team member
                        yield Struct::MediumSlot(function () use ($menu, $i) {
                            // Image
                            yield Struct::Division(Struct::Image(null, get($menu[$i], 'Image')), ["class" => "image"]);

                            // Title (Name and Titles)
                            yield Struct::Division(function () use ($menu, $i) {
                                yield Struct::Super(get($menu[$i], 'PreName'));
                                yield Struct::Division(
                                    Struct::Strong(__(get($menu[$i], 'FirstName')) . " " . __(get($menu[$i], 'MiddleName')) . " " . __(get($menu[$i], 'LastName')))
                                );
                                yield Struct::Sub(get($menu[$i], 'PostName'));
                            }, ["class" => "title"]);

                            // Features (Assignees)
                            yield Struct::Division(function () use ($menu, $i) {
                                foreach (getValid($menu[$i], 'Assignees', []) as $assignee)
                                    yield Struct::Division(__($assignee)) . Struct::$Break;
                            }, ["class" => "features"]);

                            // List of Items (Details)
                            yield Struct::Items(function () use ($menu, $i) {
                                foreach (getValid($menu[$i], 'Items', []) as $item) {
                                    yield Struct::Item(
                                        Struct::Italic(
                                            __(get($item, 'Key')) . __(":"),
                                            null,
                                            ["class" => 'fa ' . get($item, "class"), "aria-hidden" => 'true']
                                        ) . __(getValid($item, 'Value', "")),
                                        ["class" => "list-item d-flex justify-content-between align-items-center"]
                                    );
                                }
                            }, ["class" => "list-group"]);

                            // "Read More" Link
                            yield Struct::Button($this->MoreButtonLabel, get($menu[$i], "Path"), ["target" => "blank"]);
                        }, ["class" => "teammember", "data-aos" => "down"]); // Close the column div
                    }
                }, ["class"=>"items"])
            );
        }
        return null;
    }
}