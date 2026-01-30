<?php

use MiMFa\Library\Struct;
$items = get($data, "Items");
$animation = get($data, "Animation") ?? "fade";
style(get($data, "Style") ?? "
    .list-vertical-image {
        background-image: linear-gradient(to top, #111d, #333d);
        color: #fefefe;
        padding: var(--size-3) var(--size-max) var(--size-5);
        border-radius: var(--radius-3);
        box-shadow: var(--shadow-3);
        display: flex;
        gap: var(--size-2);
        flex-direction: column;
    }
    .list-vertical-image .item {
        border: var(--border-2);
        border-radius: var(--radius-2);
    }
    .list-vertical-image .item>.image {
        font-size: var(--size-3);
        padding: var(--size-3);
        display: flex;
        align-content: center;
        justify-content: center;
        align-items: center;
    }
    .list-vertical-image .item>.content {
        padding:var(--size-0);
    }
    .list-vertical-image .item>.content * {
        padding:0px;
        margin:0px;
    }
    .list-vertical-image .item>.content .small {
        padding-top: calc(var(--size-0) * 0.5);
        line-height:100%;
    }
    .list-vertical-image .item>.content .top-controls {
        color: var(--color-gray);
        padding: 0 var(--size-0);
        display:block;
        width:100%;
        text-align:end;
    }
    .list-vertical-image .item>.content .top-controls .icon{
        position: absolute;
    }
");
response(Struct::MediumFrame(
    Struct::Center(get($data, "Title")) .
    (
        is_countable($items) ? join(PHP_EOL, loop($items, function ($v, $k, $i) use ($animation) {
            return Struct::Link(Struct::Rack(
                Struct::Slot(
                    Struct::Image($v["Title"], $v["Image"]),
                    ["class" => "image col-3", "style" => "background-color:{$v["Color"]};"]
                ) .
                Struct::Slot(
                    Struct::Division(
                        Struct::Icon("arrow-up-right-from-square", $v["Path"]),
                        ["class" => "top-controls"]
                    ) .
                    Struct::Heading5($v["Title"]) .
                    Struct::Division($v["Description"], ["class" => "be small"]),
                    ["class" => "content"]
                ),
                ["class" => "item", "style" => "border-color:{$v["Color"]};"]
            ), $v["Path"], ["data-aos" => $animation, "data-aos-delay" => ($i + 1) * 100]);
        })) : $items
    ),
    ["class" => "list-vertical-image", "data-aos" => $animation]
));