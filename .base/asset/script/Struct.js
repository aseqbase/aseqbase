//Html Tag Creator Function
const Struct = function (content = null, tagName = "div", attributes = {}) {
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

Struct.stylePath = "";
Struct.stylePresetPath = "";
Struct.styleResetPath = "";
Struct.scriptPath = "";
Struct.maxFloatDecimals = 2;
Struct.maxValueLength = 10;
Struct.usedHeadItems = [];
Struct.charset = "UTF-8";
Struct.newLine = "<br/>";

Struct.toText = (obj) => textConvertor.Done(obj);
Struct.tryToText = (obj) => textConvertor.TryDone(obj);
Struct.subject = (content, attributes = {}) => Struct(content, "Title", attributes);
Struct.source = (source, attributes = {}) => {
    const srci = Struct("", "script", { ...{ SRC: source }, ...attributes });
    Struct.usedHeadItems.push(srci);
    return srci;
}

Struct.script = (content, source = null, attributes = {}) => {
    const srci = Struct(content, "script", source === null ? attributes : { ...{ SRC: source }, ...attributes });
    Struct.usedHeadItems.push(srci);
    return srci;
}
Struct.script.async = (content, source = null, attributes = {}, callback = null) => {
    var script = document.createElement('script');
    script.src = source;
    script.innerHTML = content;
    script.async = true;
    for (const item in attributes) script.setAttribute(item, attributes[item]);
    if (callback) {
        script.onload = (ev) => callback(ev, null);
        script.onerror = (ev, src, ln, n, e) => callback(ev, e, src, ln, n);
    }
    document.head.appendChild(script);
}
Struct.script.load = (content, source = null, attributes = {}) => {
    var script = document.createElement('script');
    script.src = source;
    script.innerHTML = content;
    for (const item in attributes) script.setAttribute(item, attributes[item]);
    document.head.appendChild(script);
}
Struct.script.code = (value = null, attributes = {}) => {
    Struct.script(value == null ? "" : value + ";", null, attributes);
}
Struct.script.var = (name, value = null, attributes = {}) => {
    Struct.script.code((name == null ? "" : "var " + name + (value == null ? "" : " = ")) + value, attributes);
}
Struct.script.let = (name, value = null, attributes = {}) => {
    Struct.script.code((name == null ? "" : "let " + name + (value == null ? "" : " = ")) + value, attributes);
}
Struct.script.const = (name, value = null, attributes = {}) => {
    Struct.script.code((name == null ? "" : "const " + name + (value == null ? "" : " = ")) + value, attributes);
}

Struct.style = (content, source = null, attributes = {}) => {
    if (source === null)
        return Struct(content, "style", attributes);
    const srci = Struct("Link", { ...{ REL: "stylesheet", HREF: source ?? content }, ...attributes });
    Struct.usedHeadItems.push(srci);
    return srci;
}
Struct.style.load = (content, source = null, attributes = {}) => {
    if (source) {
        var style = document.createElement('link');
        style.rel = 'stylesheet';
        style.href = source;
        for (const item in attributes) style.setAttribute(item, attributes[item]);
        document.head.appendChild(style);
    }
    if (content) {
        var style = document.createElement('style');
        style.innerHTML = content;
        for (const item in attributes) style.setAttribute(item, attributes[item]);
        document.head.appendChild(style);
    }
}
Struct.style.code = (value = null, attributes = {}) => {
    Struct.style(value == null ? "" : value + "", null, attributes);
}
Struct.style.on = (selector, value = null, attributes = {}) => {
    Struct.style.code((selector == null ? "*" : selector) + "{" + (value == null ? "" : value) + "}", attributes);
}

Struct.pages = (title, content = null, description = null, attributes = {}, head = "", styles = [], sources = []) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    if (!isArray(content)) return Struct.page(title, content, description, attributes, head, styles, sources);
    const c = count(content);
    if (c == 0) return Struct.page(title, null, description, attributes, head, styles, sources);
    if (c == 1) return Struct.page(title, content[0], description, attributes, head, styles, sources);

    const p = Math.slice(c, 100, 100, 20, 33);
    let ls = [];
    for (let item of content) {
        ls.push([`<iframe src="" srcdoc="`,
            (item + "").replace("\"", "&quot;"),
            `" class="embed" style="width:`, p.x, `vw;height:`, p.y,
            `vh;" marginwidth="0" marginheight="0" align="top" frameborder="0" hspace="0" vspace="0"></iframe>`].join(""));
    }
    return Struct.page(title, ls.join("\r\n\r\n"), description, attributes, head, styles, sources);
}
Struct.page = (title, content = null, description = null, attributes = {}, head = "", styles = [], sources = []) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    let body = Struct(
        Struct(
            [
                isNull(title) ? "" : Struct.superTitle(title),
                isArray(content) ? (each(content, (item, i) => Struct.part(null, item))).join("\r\n") : content,
                isNull(title) ? "" : Struct.caption(description)
            ].join("\r\n"),
            "div",
            { ...{ CLASS: "page" }, ...attributes }
        ),
        "body",
        { LANG: System.Lang, CHARSET: System.CharSet }
    );

    if (Struct.usedHeadItems.length > 0) {
        head = Array.distinct(Struct.usedHeadItems).join("\r\n") + "\r\n" + head + "\r\n";
        for (const x of Struct.usedHeadItems)
            body = body.replace(x, "");
        Struct.reset();
    }

    return "<!DOCTYPE HTML>" +
        Struct(
            [
                Struct(
                    [
                        Struct.subject(title),
                        Struct("meta", { CHARSET: Struct.charset }),
                        Struct("Link", { HREF: Struct.styleResetPath, REL: "stylesheet" }),
                        Struct("Link", { HREF: Struct.stylePresetPath, REL: "stylesheet" }),
                        Struct("Link", { HREF: Struct.stylePath, REL: "stylesheet" }),
                        Struct("", "script", { SRC: Struct.scriptPath }),
                        styles.length > 0 ?
                            each(styles, (item, i) => Struct.style("", item)).join("") :
                            "",
                        sources.length > 0 ?
                            each(sources, (item, i) => Struct.source(item)).join("") :
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
Struct.part = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Struct(
        [
            (isHollow(title) ? "" : Struct.title(title)),
            isArray(content) ? (each(content, (item, i) => Struct.panel(null, item))).join("\r\n") : content,
            (isHollow(description) ? "" : Struct.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "part" }, ...attributes }
    );
}
Struct.panel = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Struct(
        [
            (isHollow(title) ? "" : Struct.title(title)),
            isArray(content) ? (each(content, (item, i) => Struct.box(null, item))).join("\r\n") : content,
            (isHollow(description) ? "" : Struct.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "panel" }, ...attributes }
    );
}
Struct.box = (title, content = null, description = null, attributes = {}) => {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    return Struct(
        [
            (isHollow(title) ? "" : Struct.title(title)),
            isArray(content) ? (each(content, (item, i) => Struct.element(item))).join("\r\n") : content,
            (isHollow(description) ? "" : Struct.caption(description))
        ].join("\r\n"),
        "div",
        { ...{ CLASS: "box" }, ...attributes }
    );
}
Struct.element = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "element" }, ...attributes });

Struct.superTitle = (content, attributes = {}) => Struct(content, "h1", { ...{ CLASS: "supertitle" }, ...attributes });
Struct.title = (content, attributes = {}) => Struct(content, "h2", { ...{ CLASS: "Title" }, ...attributes });
Struct.subTitle = (content, attributes = {}) => Struct(content, "h3", { ...{ CLASS: "SubTitle" }, ...attributes });
Struct.caption = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "caption" }, ...attributes });
Struct.footNote = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "footnote" }, ...attributes });
Struct.context = (content, attributes = {}) => Struct(content, "p", { ...{ CLASS: "context" }, ...attributes });
Struct.label = (content, attributes = {}) => Struct(content, "span", { ...{ CLASS: "label" }, ...attributes });
Struct.bold = (content, attributes = {}) => Struct(content, "b", { ...{ CLASS: "bold" }, ...attributes });
Struct.italic = (content, attributes = {}) => Struct(content, "i", { ...{ CLASS: "italic" }, ...attributes });
Struct.big = (content, attributes = {}) => Struct(content, "span", { ...{ CLASS: "big" }, ...attributes });
Struct.small = (content, attributes = {}) => Struct(content, "span", { ...{ CLASS: "small" }, ...attributes });
Struct.result = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "result" }, ...attributes });
Struct.message = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "result message", ondblclick: "this.style.display = 'none'" }, ...attributes });
Struct.success = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "result success", ondblclick: "this.style.display = 'none'" }, ...attributes });
Struct.error = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "result error", ondblclick: "this.style.display = 'none'" }, ...attributes });
Struct.warning = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "result warning", ondblclick: "this.style.display = 'none'" }, ...attributes });

Struct.division = (content, attributes = {}) => Struct(content, "div", { ...{ CLASS: "division" }, ...attributes });

Struct.link = (link, anchor = null, attributes = {}) => Struct(anchor ?? link, "a", { ...{ HREF: link, CLASS: "Link" }, ...attributes });

Struct.image = (source, alt = null, attributes = {}) => Struct("img", { ...{ SRC: source, ALT: alt, CLASS: "Image" }, ...attributes });

Struct.center = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => Struct.center(item, attributes)) : content, "div", { ...{ CLASS: "center" }, ...attributes });

Struct.table = function (title, content = null, description = null, attributes = {}, colHeads = [0], rowHeads = []) {
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
    return Struct.box(
        title,
        Struct(
            Struct.rows(arr, {}, colHeads, rowHeads),
            "table",
            { ...{ BORDER: "1", CLASS: "table" }, ...attributes }
        ),
        description,
        {}
    );
}
Struct.tables = (content, attributes = {}) =>
    each(content, (item, i) => Struct.table(item, attributes)).join("");
Struct.col = (content, attributes = {}) =>
    Struct(isArray(content) ? Struct.headers(content) : content, "tr", { ...{ CLASS: "col" }, ...attributes });
Struct.cols = (content, attributes = {}) =>
    each(content, (item, i) => Struct.col(item, attributes)).join("\r\n");
Struct.row = (content, attributes = {}, cellHeads = []) =>
    Struct(isArray(content) ? Struct.cells(content, {}, cellHeads) : content, "tr", { ...{ CLASS: "row" }, ...attributes });
Struct.rows = function (content, attributes = {}, colHeads = [], cellHeads = []) {
    if (count(colHeads) > 0)
        return each(content, (item, i) => colHeads.includes(i) ? Struct.col(item, attributes) : Struct.row(item, attributes, cellHeads)).join("\r\n");
    else return each(content, (item, i) => Struct.row(item, attributes, cellHeads)).join("\r\n");
}
Struct.header = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join(Struct.newLine) : content, "th", { ...{ CLASS: "header" }, ...attributes });
Struct.headers = (content, attributes = {}) =>
    each(content, (item, i) => Struct.header(item, attributes)).join("");
Struct.cell = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join(Struct.newLine) : content, "td", { ...{ CLASS: "cell" }, ...attributes });
Struct.cells = function (content, attributes = {}, cellHeads = []) {
    if (count(cellHeads) > 0)
        return each(content, (item, i) => cellHeads.includes(i) ? Struct.header(item, attributes) : Struct.cell(item, attributes)).join("");
    else return each(content, (item, i) => Struct.cell(item, attributes)).join("");
}

Struct.value = (content, attributes = {}) => {
    if (isFloat(content)) return Struct.abbr(content, Struct(+parseFloat(content).toFixed(Struct.maxFloatDecimals), "span", { ...{ CLASS: "Value" }, ...attributes }));
    if (isText(content) && content.length > Struct.maxValueLength) return Struct.abbr(content.substring(Struct.maxValueLength), Struct(content.substring(0, Struct.maxValueLength) + "⋯", "span", { ...{ CLASS: "Value" }, ...attributes }));
    return Struct(content, "span", { ...{ CLASS: "Value" }, ...attributes });
}
Struct.values = (content, attributes = {}) =>
    isArray(content) ? each(content, (item, i) => Struct.value(item, attributes)).join(", ") : Struct.value(content, attributes);

Struct.color = (content, color = "blue", attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join(", ") : content, "span", { ...{ CLASS: "color", STYLE: "color:" + color }, ...attributes });
Struct.abbr = (title, content = "", attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join(", ") : content, "abbr", { ...{ CLASS: "abbr", TITLE: title }, ...attributes });
Struct.between = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join("-") : content, "span", { ...{ CLASS: "between" }, ...attributes });
Struct.pair = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => item).join(":") : content, "span", { ...{ CLASS: "pair" }, ...attributes });
Struct.list = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => Struct.items(item)).join("\r\n") : content, "ol", { ...{ CLASS: "list" }, ...attributes });
Struct.collection = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => Struct.items(item)).join("\r\n") : content, "ul", { ...{ CLASS: "collection" }, ...attributes });
Struct.item = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => Struct.values(item)).join(Struct.newLine) : content, "li", { ...{ CLASS: "Item" }, ...attributes });
Struct.items = (content, attributes = {}) =>
    Struct(isArray(content) ? each(content, (item, i) => Struct.item(item)).join("\r\n") : content, "li", { ...{ CLASS: "Item" }, ...attributes });
Struct.usedChartNumber = 0;
Struct.chart = function (title, content = null, description = null, attributes = {}, type = "column", axisXTitle = "X", axisYTitle = "Y", options = null) {
    if (content === null) {
        content = title;
        title = null;
    }
    if (content === null) return null;
    let isen = isArray(content);
    let isobj = isObject(content);
    let datachart = null;
    const id = "Chart" + Struct.usedChartNumber++;
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
            arr = each(content, (cnt, i) => ["{", `Type: "`, isten ? type[i] : type, `"`, `, dataPoints: [`, Struct.points(cnt), `]}`].join(""));
        else if (isten)
            arr = each(type, (ty, i) => ["{", ty.includes(":") ? ty : `Type: "` + ty + `"`, `, dataPoints: [`, Struct.points(isen ? content[i] : content), `]}`].join(""));
        else arr.push([`{type: "`, type, `", dataPoints: [`, Struct.points(content), `]}`].join(""));

        datachart = "[" + arr.join(",") + "]";
    }

    return Struct.box(
        title,
        [
            Struct.source("https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/CanvasJS.min.js"),
            Struct.script([
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
            Struct.center(Struct("", "div", { Id: id, CLASS: "chart" }))
        ].join("\r\n"),
        description,
        { ...{}, ...attributes }
    );
}

Struct.point = (row, index = 0) => {
    const cc = count(row);
    if (cc > 3) {
        var res = [];
        res.push(`{ `);
        res.push(`label: `, Struct.parameters(row[0]));
        res.push(`,x:`, Struct.numbers(row[1]));
        res.push(`,y:`, Struct.numbers(row[2]));
        let len = count(row);
        for (let i = 3; i < len; i++) res.push("," + row[i]);
        res.push(` }`);
        return res.join("");
    }
    else if (cc > 2)
        return [
            `{ label: `, Struct.parameters(row[0]),
            `,x:`, Struct.numbers(row[1]),
            `,y:`, Struct.numbers(row[2]),
            `}`
        ].join("");
    else if (cc > 1)
        return [
            `{x:`, Struct.numbers(row[0]),
            `,y:`, Struct.numbers(row[1]),
            `}`
        ].join("");
    else if (cc > 0) {
        return [
            `{x:`,
            index,
            `,y:`, Struct.numbers(row),
            `}`
        ].join("");
    }
    else return null;
}

Struct.points = (content) => {
    return each(content, (row, i) => Struct.point(row, i)).join(",");
}

Struct.numbers = (arr) => {
    const isarr = isArray(arr);
    const val = isarr ? (Array.isArray(arr) ? arr : each(arr)).join(", ").replace(/^\s*,\s+/gm, "0, ").replace(/,\s*$/gm, ", 0").replace(/,\s+,/gm, ", 0,") : arr;
    return isHollow(val) ? "0" : isarr ? "[" + val + "]" : val;
}

Struct.parameters = (arr) => {
    return isHollow(arr) ? "" :
        isArray(arr) ? ["[", each(arr, o => Struct.parameters(o)).join(", "), "]"].join("") :
            isString(arr) ? ['"', arr, '"'].join("") :
                isObject(arr) ? JSON.stringify(arr) :
                    arr + "";
}

Struct.handle = (data, selector = "body :nth-child(1)", reload = false) => {
    if (data.includes('result')) {
        $(selector + ` .result`).remove();
        $(selector).prepend(data);
        if (reload) load();
    }
    else {
        $(selector).append(data);
    }
}

Struct.reset = () => {
    Struct.usedHeadItems = [];
    Struct.usedChartNumber = 0;
}
