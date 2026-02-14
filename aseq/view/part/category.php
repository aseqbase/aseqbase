<?php
use MiMFa\Library\Struct;
$Name = pop($data, 'Name');
$Title = pop($data, 'Title');

module("PrePage");
$module = new \MiMFa\Module\PrePage();
$module->AllowCover = true;
$module->Class = "prepage";
$module->Title = between($Title, $Name);//!isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->Description = get($data, 'Description');
$module->Image = get($data, 'Image');
$Root = pop($data, 'Root') ?? \_::$Address->CategoryRootUrlPath;
$items = pop($data, "Items");
$name = $module->MainClass;// To do not change the name of module
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
if ($items) {
    $module->Content .= Struct::Style("
        .$name .categories{
            background-color: #8882;
            padding:var(--size-0);
            display:flex;
            justify-content: center;
            align-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align:center;
            gap:var(--size-0);
        }
        .$name .categories .button{
            border: none;
        }
    ").Struct::Division(loop($items, function ($v) use ($Root) {
                $n = get($v, "Name");
                $t = get($v, "Title") ?? $n;
                $p = getBetween($v, "Path", "Route") ?? $n;
                $tt = get($v, "Description");
                return Struct::Button($t, $Root . ltrim($p, "/\\"), [
                    ...($tt ? ["ToolTip" => $tt] : [])
                ]);
            }),
        ["class" => "categories"]
    );
}
$module->Render();
part("content/all", ["Items" => compute("content/all", ["Filter" => ["Category" => get($data, 'Id') ?? $Name]])]);