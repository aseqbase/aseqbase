<?php

use MiMFa\Library\Convert;

$path = implode("/", array_slice(explode("/", \_::$Address->UrlRoute), 1));
$parent = compute( "category/get", ["Direction" =>$path]);
if(isEmpty($parent)) view(\_::$Front->DefaultViewName, ["Name" =>404]);
else {
    $data = $data??[];
    (new Router())
        ->Get(function ($router) use ($parent, $data, $path) {
            $viewData = get($data, "View")??[];
            if(!is_array($viewData)) return Convert::By($viewData, $doc);
            $c = get($viewData, "ErrorHandler");
            if($c) return Convert::By($c, $router);
            $computeData = pop($data, "Compute") ?? [];
            $items = compute( "category/all", [
                "Direction" =>$path,
                ...$computeData??[]
            ]);
            
            $viewData = get($data, "View") ?? [];

            if(!is_array($viewData))  $viewData = Convert::By($viewData, $items);

            if (isEmpty($items)) {
                $c = get($viewData, "ErrorHandler");
                if ($c)
                    return Convert::By($c, \_::$Router);
            } elseif(is_array($items)) array_shift($items);
            else $items = [];
            view(pop($viewData, "ViewName") ?? "category", [
                "Root" => pop($viewData, "Root") ?? \_::$Address->CategoryRootUrlPath,
                "Items" => $items,
                ...$viewData??[], 
                ...$parent??[]
            ]);
        })
        ->Handle();
}