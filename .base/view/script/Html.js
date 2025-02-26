//Html Tag Creator Function
const Html = function (content = null, tagName = "div", attributes = {}) {
    let isSingle = typeof tagName === 'object';
    if (isSingle) {
        attributes = tagName;
        tagName = content;
    }
    tagName = tagName.toLowerCase();

    let attrs = "";
    for (let x in attributes) {
        let val = attributes[x] + "";
        let sp = val.includes("'") ? '"' : "'";
        attrs += [" ", x.toLowerCase(), "=", sp, val, sp].join("");
    }

    if (isSingle) return ["<", tagName, attrs, "/>"].join("");
    else return ["<", tagName, attrs, ">", Array.isArray(content) ? content.join("") : isArray(content) ? each(content).join("") : content, "</", tagName, ">"].join("");
}

Html.stylePath = "";
Html.stylePresetPath = "";
Html.styleResetPath = "";
Html.scriptPath = "";
Html.maxFloatDecimals = 2;
Html.maxValueLength = 10;
Html.usedHeadItems = [];
Html.charset = "UTF-8";
Html.newLine = "<br/>";

Html.toText = (obj) => textConvertor.Done(obj);
Html.tryToText = (obj) => textConvertor.TryDone(obj);
Html.subject = (content, attributes = {}) => Html(content, "Title" , attributes);
Html.source = (source, attributes = {}) => {
    const srci = Html("", "script", { ...{ SRC: source }, ...attributes });
    Html.usedHeadItems.push(srci);
    return srci;
}

Html.script = (content, source = null, attributes = {}) => {
    const srci = Html(content, "script", source === null ? attributes : { ...{ SRC: source }, ...attributes });
    Html.usedHeadItems.push(srci);
    return srci;
}
Html.script.code = (value = null, attributes = {}) => {
    Html.script(value == null ? "" : value + ";", null, attributes);
}
Html.script.var = (name, value = null, attributes = {}) => {
    Html.script.code((name == null ? "" : "var " + name + (value == null ? "" : " = ")) + value, attributes);
}
Html.script.let = (name, value = null, attributes = {}) => {
    Html.script.code((name == null ? "" : "let " + name + (value == null ? "" : " = ")) + value, attributes);
}
Html.script.const = (name, value = null, attributes = {}) => {
    Html.script.code((name == null ? "" : "const " + name + (value == null ? "" : " = ")) + value, attributes);
}

Html.style = (content, source = null, attributes = {}) => {
    if (source === null)
        return Html(content, "style", attributes);
    const srci = Html("Link", { ...{ REL: "stylesheet", HREF: source ?? content }, ...attributes });
    Html.usedHeadItems.push(srci);
    return srci;
}
Html.style.code = (value = null, attributes = {}) => {
    Html.style(value == null ? "" : value + "", null, attributes);
}
Html.style.on = (selector, value = null, attributes = {}) => {
    Html.style.code((selector == null ? "*" : selector) + "{" + (value == null ? "" : value) + "}", attributes);
}

Html.pages = (title, content = null, description = null, attributes = {}, head = "", styles = [], sources = []) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    if (!isArray(content)) return Html.page(title, content, description, attributes, head, styles, sources);
    const c = count(content);
    if (c == 0) return Html.page(title, null, description, attributes, head, styles, sources);
    if (c == 1) return Html.page(title, content[0], description, attributes, head, styles, sources);

    const p = Math.slice(c, 100, 100, 20, 33);
    let ls = [];
    for (let item of content) {
        ls.push([`<iframe src="" srcdoc="`,
            (item + "").replace("\"", "&quot;"),
            `" class="embed" style="width:`, p.x, `vw;height:`, p.y,
            `vh;" marginwidth="0" marginheight="0" align="top" frameborder="0" hspace="0" vspace="0"></iframe>`].join(""));
    }
    return Html.page(title, ls.join("\r\n\r\n"), description, attributes, head, styles, sources);
}
Html.page = (title, content = null, description = null, attributes = {}, head = "", styles = [], sources = []) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    let body = Html(
        Html(
            [
                isNull(title) ? "" : Html.superTitle(title),
                isArray(content) ? (each(content, (item, i) => Html.part(null, item))).join("\r\n") : content,
                isNull(title) ? "" : Html.caption(description)
            ].join("\r\n"),
            "div",
            { ...{ CLASS: "page" }, ...attributes }
        ),
        "body",
        { LANG: System.Lang, CHARSET: System.CharSet }
    );

    if (Html.usedHeadItems.length > 0) {
        head = Array.distinct(Html.usedHeadItems).join("\r\n") + "\r\n" + head + "\r\n";
        for (const x of Html.usedHeadItems)
            body = body.replace(x, "");
        Html.reset();
    }

    return "<!DOCTYPE HTML>" +
        Html(
            [
                Html(
                    [
                        Html.subject(title),
                        Html("meta", { CHARSET: Html.charset }),
                        Html("Link", { HREF: Html.styleResetPath, REL: "stylesheet" }),
                        Html("Link", { HREF: Html.stylePresetPath, REL: "stylesheet" }),
                        Html("Link", { HREF: Html.stylePath, REL: "stylesheet" }),
                        Html("", "script", { SRC: Html.scriptPath }),
                        styles.length > 0 ?
                            each(styles, (item, i) => Html.style("", item)).join("") :
                            "",
                        sources.length > 0 ?
                            each(sources, (item, i) => Html.source(item)).join("") :
                            "",
                        head === null ? "" : "\r\n" + head
                    ].join("\r\n"),
                    "head"
                ),
                body
            ].join("\r\n"),
            "html"
        );
}
Html.part = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Html(
        [
            (isHollow(title) ? "" : Html.title(title)),
            isArray(content) ? (each(content, (item, i) => Html.panel(null, item))).join("\r\n") : content,
            (isHollow(description) ? "" : Html.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "part" }, ...attributes }
    );
}
Html.panel = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Html(
        [
            (isHollow(title) ? "" : Html.title(title)),
            isArray(content) ? (each(content, (item, i) => Html.box(null, item))).join("\r\n") : content,
            (isHollow(description) ? "" : Html.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "panel" }, ...attributes }
    );
}
Html.box = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Html(
        [
            (isHollow(title) ? "" : Html.title(title)),
            isArray(content) ? (each(content, (item, i) => Html.element(item))).join("\r\n") : content,
            (isHollow(description) ? "" : Html.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "box" }, ...attributes }
    );
}
Html.element = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "element" }, ...attributes });

Html.superTitle = (content, attributes = {}) => Html(content, "h1", { ...{ CLASS: "supertitle" }, ...attributes });
Html.title = (content, attributes = {}) => Html(content, "h2", { ...{ CLASS: "Title" }, ...attributes });
Html.subTitle = (content, attributes = {}) => Html(content, "h3", { ...{ CLASS: "SubTitle" }, ...attributes });
Html.caption = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "caption" }, ...attributes });
Html.footNote = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "footnote" }, ...attributes });
Html.context = (content, attributes = {}) => Html(content, "p", { ...{ CLASS: "context" }, ...attributes });
Html.label = (content, attributes = {}) => Html(content, "span", { ...{ CLASS: "label" }, ...attributes });
Html.bold = (content, attributes = {}) => Html(content, "b", { ...{ CLASS: "bold" }, ...attributes });
Html.italic = (content, attributes = {}) => Html(content, "i", { ...{ CLASS: "italic" }, ...attributes });
Html.big = (content, attributes = {}) => Html(content, "span", { ...{ CLASS: "big" }, ...attributes });
Html.small = (content, attributes = {}) => Html(content, "span", { ...{ CLASS: "small" }, ...attributes });
Html.result = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "result", ondblclick: "this.style.display = 'none'" }, ...attributes });
Html.message = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "result message" , ondblclick: "this.style.display = 'none'"}, ...attributes });
Html.success = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "result success" , ondblclick: "this.style.display = 'none'"}, ...attributes });
Html.error = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "result error" , ondblclick: "this.style.display = 'none'"}, ...attributes });
Html.warning = (content, attributes = {}) => Html(content, "div", { ...{ CLASS: "result warning" , ondblclick: "this.style.display = 'none'"}, ...attributes });

Html.link = (link, anchor = null, attributes = {}) => Html(anchor ?? link, "a", { ...{ HREF: link, CLASS: "Link" }, ...attributes });

Html.image = (source, alt = null, attributes = {}) => Html("img", { ...{ SRC: source, ALT: alt, CLASS: "Image" }, ...attributes });

Html.center = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => Html.center(item, attributes)) : content, "div", { ...{ CLASS: "center" }, ...attributes });

Html.table = function (title, content = null, description = null, attributes = {}, colHeads = [0], rowHeads = []) {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    let isen = isArray(content);
    let arr = isen ? each(content) : content;
    if (!isen) {
        colHeads = [];
        rowHeads = [];
    }
    return Html.box(
        title,
        Html(
            Html.rows(arr, {}, colHeads, rowHeads),
            "table",
            { ...{ BORDER: "1", CLASS: "table" }, ...attributes }
        ),
        description,
        {}
    );
}
Html.tables = (content, attributes = {}) =>
    each(content, (item, i) => Html.table(item, attributes)).join("");
Html.col = (content, attributes = {}) =>
    Html(isArray(content) ? Html.headers(content) : content, "tr", { ...{ CLASS: "col" }, ...attributes });
Html.cols = (content, attributes = {}) =>
    each(content, (item, i) => Html.col(item, attributes)).join("\r\n");
Html.row = (content, attributes = {}, cellHeads = []) =>
    Html(isArray(content) ? Html.cells(content, {}, cellHeads) : content, "tr", { ...{ CLASS: "row" }, ...attributes });
Html.rows = function (content, attributes = {}, colHeads = [], cellHeads = []) {
    if (count(colHeads) > 0)
        return each(content, (item, i) => colHeads.includes(i) ? Html.col(item, attributes) : Html.row(item, attributes, cellHeads)).join("\r\n");
    else return each(content, (item, i) => Html.row(item, attributes, cellHeads)).join("\r\n");
}
Html.header = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join(Html.newLine) : content, "th", { ...{ CLASS: "header" }, ...attributes });
Html.headers = (content, attributes = {}) =>
    each(content, (item, i) => Html.header(item, attributes)).join("");
Html.cell = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join(Html.newLine) : content, "td", { ...{ CLASS: "cell" }, ...attributes });
Html.cells = function (content, attributes = {}, cellHeads = []) {
    if (count(cellHeads) > 0)
        return each(content, (item, i) => cellHeads.includes(i) ? Html.header(item, attributes) : Html.cell(item, attributes)).join("");
    else return each(content, (item, i) => Html.cell(item, attributes)).join("");
}

Html.value = (content, attributes = {}) => {
    if (isFloat(content)) return Html.abbr(content, Html(+parseFloat(content).toFixed(Html.maxFloatDecimals), "span", { ...{ CLASS: "Value" }, ...attributes }));
    if (isText(content) && content.length > Html.maxValueLength) return Html.abbr(content.substring(Html.maxValueLength), Html(content.substring(0, Html.maxValueLength) + "⋯", "span", { ...{ CLASS: "Value" }, ...attributes }));
    return Html(content, "span", { ...{ CLASS: "Value" }, ...attributes });
}
Html.values = (content, attributes = {}) =>
    isArray(content) ? each(content, (item, i) => Html.value(item, attributes)).join(", ") : Html.value(content, attributes);

Html.color = (content, color = "blue", attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join(", ") : content, "span", { ...{ CLASS: "color", STYLE: "color:" + color }, ...attributes });
Html.abbr = (title, content = "", attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join(", ") : content, "abbr", { ...{ CLASS: "abbr", TITLE: title }, ...attributes });
Html.between = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join("-") : content, "span", { ...{ CLASS: "between" }, ...attributes });
Html.pair = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => item).join(":") : content, "span", { ...{ CLASS: "pair" }, ...attributes });
Html.list = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => Html.items(item)).join("\r\n") : content, "ol", { ...{ CLASS: "list" }, ...attributes });
Html.collection = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => Html.items(item)).join("\r\n") : content, "ul", { ...{ CLASS: "collection" }, ...attributes });
Html.item = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => Html.values(item)).join(Html.newLine) : content, "li", { ...{ CLASS: "Item" }, ...attributes });
Html.items = (content, attributes = {}) =>
    Html(isArray(content) ? each(content, (item, i) => Html.item(item)).join("\r\n") : content, "li", { ...{ CLASS: "Item" }, ...attributes });
Html.usedChartNumber = 0;
Html.chart = function (title, content = null, description = null, attributes = {}, type = "column", axisXTitle = "X", axisYTitle = "Y", options = null) {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    let isen = isArray(content);
    let isobj = isObject(content);
    let datachart = null;
    const id = "Chart" + Html.usedChartNumber++;
    if (!isen && !isobj) datachart = content;
    else {
        if (!isen && isobj) {
            let rows = between(content["matrix"], content["table"], content["rows"], content["columns"]);
            if (isHollow(rows))
                rows = between(content["Source"], content["document"], document).Rows.Skip(between(content["Source"], document).RowsLabelsIndex);
            let arr = [];
            let l = between(content["labels"], content["label"], -1);
            let xs = between(content["axisX"], content["xs"], content["x"], []);
            let ys = between(content["axisY"], content["ys"], content["y"], []);
            let ct = 0;
            if (l > -1)
                if (xs.length > 0)
                    if (ys.length > 0)
                        for (const row of rows)
                            arr.push([
                                row.ElementAt(l),
                                xs.length == 1 ? forceParseFloat(row.ElementAt(xs[0])) : each(xs, i => forceParseFloat(row.ElementAt(i))),
                                ys.length == 1 ? forceParseFloat(row.ElementAt(ys[0])) : each(ys, i => forceParseFloat(row.ElementAt(i)))
                            ]);
                    else
                        for (const row of rows)
                            arr.push([
                                row.ElementAt(l),
                                xs.length == 1 ? forceParseFloat(row.ElementAt(xs[0])) : each(xs, i => forceParseFloat(row.ElementAt(i))),
                                ct++
                            ]);
                else if (ys.length > 0)
                    for (const row of rows)
                        arr.push([
                            row.ElementAt(l),
                            ct++,
                            ys.length == 1 ? forceParseFloat(row.ElementAt(ys[0])) : each(ys, i => forceParseFloat(row.ElementAt(i)))
                        ]);
                else
                    for (const row of rows)
                        arr.push([
                            forceParseFloat(row.ElementAt(l))
                        ]);
            else if (xs.length > 0)
                if (ys.length > 0)
                    for (const row of rows)
                        arr.push([
                            xs.length == 1 ? forceParseFloat(row.ElementAt(xs[0])) : each(xs, i => forceParseFloat(row.ElementAt(i))),
                            ys.length == 1 ? forceParseFloat(row.ElementAt(ys[0])) : each(ys, i => forceParseFloat(row.ElementAt(i)))
                        ]);
                else
                    for (const row of rows)
                        arr.push([
                            xs.length == 1 ? forceParseFloat(row.ElementAt(xs[0])) : each(xs, i => forceParseFloat(row.ElementAt(i))),
                            ct++
                        ]);
            else if (ys.length > 0)
                for (const row of rows)
                    arr.push([
                        ct++,
                        ys.length == 1 ? forceParseFloat(row.ElementAt(ys[0])) : each(ys, i => forceParseFloat(row.ElementAt(i)))
                    ]);
            content = arr;
        }
        let lci = -1, xci = -1, yci = -1;
        let arr = new Array();
        const isten = isArray(type);
        if (isen && isArray(content, 3))
            arr = each(content, (cnt, i) => ["{", `Type: "`, isten ? type[i] : type, `"`, `, dataPoints: [`, Html.points(cnt), `]}`].join(""));
        else if (isten)
            arr = each(type, (ty, i) => ["{", ty.includes(":") ? ty : `Type: "` + ty + `"`, `, dataPoints: [`, Html.points(isen ? content[i] : content), `]}`].join(""));
        else arr.push([`{type: "`, type, `", dataPoints: [`, Html.points(content), `]}`].join(""));

        datachart = "[" + arr.join(",") + "]";
    }

    return Html.box(
        title,
        [
            Html.source("https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/CanvasJS.min.js"),
            Html.script([
                `
                window.addEventListener("load", function() 
                    {
                        var chart = new CanvasJS.Chart("`, id, `", {
							zoomEnabled: true,
							legend: {
								horizontalAlign: "center",
								verticalAlign: "top",
		                        fontFamily: "defaultFont"
							},
                            axisX:{
                                title: "`, axisXTitle, `",
                                crosshair: { 
                                    enabled: true
                                },
		                        labelTextAlign: "`, System.HorizontalAlign, `",
		                        labelFontFamily: "defaultFont",
		                        titleFontFamily: "defaultFont"
                            },
                            axisY:{
                                title: "`, axisYTitle, `",
                                crosshair: { 
                                    enabled: true
                                },
		                        labelTextAlign: "`, System.HorizontalAlign, `",
		                        labelFontFamily: "defaultFont",
		                        titleFontFamily: "defaultFont"
                            },
                            toolTip: {
                                shared: true,
		                        fontFamily: "defaultFont"
                            },
                            /*
                            title:{
                                text: "`, title, `",
                                verticalAlign: "top",
                                horizontalAlign: "center"
                            },
                            subtitles:{
                                text: "`, description, `",
                                verticalAlign: "bottom",
                                horizontalAlign: "center"
                            },
                            */
                            data: `, datachart, `
                            `, (options == null ? "" : "," + options), `
                        });
                        chart.render();
                    });`
            ].join(""), null, { TYPE: "text/javascript" }),
            Html.center(Html("", "div", { Id: id, CLASS: "chart" }))
        ].join("\r\n"),
        description,
        { ...{}, ...attributes }
    );
}

Html.point = (row, index = 0) => {
    const cc = count(row);
    if (cc > 3) {
        var res = [];
        res.push(`{ `);
        res.push(`label: `, Html.parameters(row[0]));
        res.push(`,x:`, Html.numbers(row[1]));
        res.push(`,y:`, Html.numbers(row[2]));
        let len = count(row);
        for (let i = 3; i < len; i++) res.push("," + row[i]);
        res.push(` }`);
        return res.join("");
    }
    else if (cc > 2)
        return [
            `{ label: `, Html.parameters(row[0]),
            `,x:`, Html.numbers(row[1]),
            `,y:`, Html.numbers(row[2]),
            `}`
        ].join("");
    else if (cc > 1)
        return [
            `{x:`, Html.numbers(row[0]),
            `,y:`, Html.numbers(row[1]),
            `}`
        ].join("");
    else if (cc > 0) {
        return [
            `{x:`,
            index,
            `,y:`, Html.numbers(row),
            `}`
        ].join("");
    }
    else return null;
}

Html.points = (content) => {
    return each(content, (row, i) => Html.point(row, i)).join(",");
}

Html.numbers = (arr) => {
    const isarr = isArray(arr);
    const val = isarr ? (Array.isArray(arr) ? arr : each(arr)).join(", ").replace(/^\s*,\s+/gm, "0, ").replace(/,\s*$/gm, ", 0").replace(/,\s+,/gm, ", 0,") : arr;
    return isHollow(val) ? "0" : isarr ? "[" + val + "]" : val;
}

Html.parameters = (arr) => {
    return isHollow(arr) ? "" :
        isArray(arr) ? ["[", each(arr, o => Html.parameters(o)).join(", "), "]"].join("") :
            isString(arr) ? ['"', arr, '"'].join("") :
                isObject(arr) ? JSON.stringify(arr) :
                    arr + "";
}

Html.handle = (data, selector = "body :nth-child(1)", reload = false) => {
    if (data.includes('result')) {
        $(selector+` .result`).remove();
        $(selector).prepend(data);
        if (reload) load();
    }
    else {
        $(selector).append(data);
    }
}

Html.reset = () => {
    Html.usedHeadItems = [];
    Html.usedChartNumber = 0;
}
