<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Router;

$path = implode("/", array_slice(explode("/", \_::$Direction), 1));
$parent = compute( "category/get", ["Direction" =>$path]);
if(isEmpty($parent)) view(\_::$Config->DefaultViewName, ["Name" =>404]);
else {
    $data = $data??[];
    (new Router())
        ->Get(function ($router) use ($parent, $data, $path) {
            $viewData = get($data, "View")??[];
            if(!is_array($viewData)) return Convert::By($viewData, $doc);
            $c = get($viewData, "ErrorHandler");
            if($c) return Convert::By($c, $router);
            $computeData = grab($data, "Compute") ?? [];
            $items = compute( "category/all", [
                "Direction" =>$path,
                ...$computeData??[]
            ]);
            
            $viewData = get($data, "View") ?? [];

            if(!is_array($viewData))  $viewData = Convert::By($viewData, $items);

            if (isEmpty($items)) {
                $c = get($viewData, "ErrorHandler");
                if ($c)
                    return Convert::By($c, \_::$Back->Router);
            } elseif(is_array($items)) array_shift($items);
            else $items = [];
            view(grab($viewData, "ViewName") ?? "category", [
                "RootRoute" => grab($viewData, "RootRoute") ?? \_::$Address->CategoryRoute,
                "Items" => $items,
                ...$viewData??[], 
                ...$parent??[]
            ]);
        })
        ->Handle();
}