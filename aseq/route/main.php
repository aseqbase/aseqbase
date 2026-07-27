<?php
$viewData = pop($data, "View");
if (\_::$Address->UrlRoute === "home" || isEmpty(\_::$Address->UrlRoute))
    return view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? "home", "Title" => pop($viewData, "Title") ?? \_::$Front->FullName, ...($viewData ?? [])]);
else {
    $route = null;
    $paths = preg_split("/[\/\\\]/", urldecode(\_::$Address->UrlRoute));
    for ($i = count($paths); $i > 0; $i--)
        if (path(\_::$Address->RouteRootDirectory . ($route = join(DIRECTORY_SEPARATOR, array_slice($paths, 0, $i))), null))
            return route($route, $data);
}
$computeData = pop($data, "Compute");
$doc = compute(pop($computeData, "ComputeName") ?? "content/get", ["Name" => pop($computeData, "Name") ?? urldecode(\_::$Address->UrlRoute), ...($computeData ?? [])]);
if (isEmpty($doc))
    return view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? urldecode(\_::$Address->UrlRoute), ...($viewData ?? [])]);
else
    return view(pop($viewData, "ViewName") ?? "content", $doc);
?>