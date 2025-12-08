<?php
use MiMFa\Library\Struct;
$Name = pop($data, 'Name');
$Title = pop($data, 'Title');

module("PrePage");
$module = new \MiMFa\Module\PrePage();
$module->Title = !isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title " . ($Name ? "($Name)" : "") : between($Title, $Name);
$module->Description = get($data, 'Description');
$module->Image = get($data, 'Image');

$Root = pop($data, 'Root') ?? \_::$Address->CategoryRoot;
$items = pop($data, "Items");
$name = $module->Name;// To do not change the name of module
pod($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
if ($items) {
    response(Struct::Style("
        .categories{
            display:flex;
            justify-content: center;
            align-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align:center;
            gap:var(--size-0);
            border-bottom:var(--border-1) var(--fore-color);
        }
        .categories .span{
            background-color:var(--back-color-special);
            color:var(--fore-color-special);
            padding:calc(var(--size-0) / 2) var(--size-0);
            box-shadow:var(--shadow-2);
        }
        .categories .span:hover{
            box-shadow:var(--shadow-3);
        }
    ").Struct::Division(loop($items, function ($v) use ($Root) {
                $n = get($v, "Name");
                $t = get($v, "Title") ?? $n;
                $p = getBetween($v, "Path", "Route") ?? $n;
                $tt = get($v, "Description");
                return Struct::Span($t, $Root . ltrim($p, "/\\"), [
                    ...($tt ? ["ToolTip" => $tt] : [])
                ]);
            }),
        ["class" => "categories"]
    ));
}
part("content/all", ["Items" => compute("content/all", ["Filter" => ["Category" => get($data, 'Id') ?? $Name]])]);