<?php

use MiMFa\Library\Struct;
$items = get($data, "Items");
$animation = get($data, "Animation") ?? "fade";
$columns = get($data, "ColumnsCount") ?? 4;
style(get($data, "Style") ?? "
");
response(Struct::MediumFrame(
    [
        Struct::Rack(
            Struct::Division(Struct::Small(get($data, "SuperTitle")) . get($data, "Attachs"), ["class" => "be flex justify"]) .
            Struct::Heading(get($data, "Title"))
        , ["class"=>"header"]),
        Struct::Division(is_countable($items) ? join(PHP_EOL, loop(group($items, fn($v,$k,$i)=>$i && !($i % $columns), true), function ($itms) use ($animation) {
            return Struct::Rack(
                loop($itms, fn($v)=>
                    Struct::Link(Struct::Icon($v["Icon"]??"arrow-up-right-from-square")." ". $v["Title"]??null , $v["Path"]??null) .
                    Struct::Paragraph($v["Description"]??null, ["class" => "be small"]),
                )
            );
        })) : $items, ["class"=>"content"])
    ],
    ["class" => "list-links", "data-aos" => $animation]
));