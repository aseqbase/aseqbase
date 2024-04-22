alive = () => (new Live(this)).each((act) => act(...arguments));

class Live extends Array {
    constructor(query = null, mode = null, source = null) {
        super();
        this.clear();
        this.from(query, mode, source);
        return new Proxy(this, {
            get: function (obj, prop) {
                if (obj.hasOwnProperty(prop)) return obj[prop];
                return Array.from((function* () {
                    for (const elem of obj)
                        if (elem.hasOwnProperty(prop))
                            if (typeof elem !== "function") yield elem[prop];
                            else yield (...args) => elem[prop](...args);
                })());
            }
        });
    }

    from = function (query = null, mode = null, source = null) {
        if (query === null && source === null && mode === null) return this;
        let isArrQuery = (query !== null && query instanceof Array && query.length === 2 && query[0].isInteger());
        if (query !== null) {
            if (typeof query != "string" && !isArrQuery && source === null && mode === null) return this.clear().merge(query);
            else if (typeof query[Symbol.iterator] === 'function') return this.clear().merge(query);
        }
        for (const src of (source === null ? [document] : new Live(source, mode))) {
            switch ((mode ?? (query.match(/^\s*\//) ? "xpath" : isArrQuery? "location" : "")).toLowerCase()) {
                case "id":
                    this.merge([src.getElementById(query)]);
                    break;
                case "name":
                    this.merge(src.getElementsByName(query));
                    break;
                case "tag":
                    this.merge(src.getElementsByTagName(query));
                    break;
                case "class":
                    this.merge(src.getElementsByClassName(query));
                    break;
                case "location":
                    if (isArrQuery) this.merge(src.elementsFromPoint(query[0], query[1]));
                    else this.merge(src.elementsFromPoint(query));
                    break;
                case "query":
                    this.merge(src.querySelectorAll(query));
                    break;
                case "xpath":
                    this.merge(src.evaluate(query, document, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null));
                    break;
                case "pure":
                default:
                    if (typeof query === 'string') this.merge(src.querySelectorAll(query));
                    else this.merge(query);
                    break;
            }
        }
        return this;
    }
    select = function (query = null, mode = null) {
        return new Live(query, mode, this);
    }

    //get = function (name) {
    //    return new Live(Array.from((function* () {
    //        for (const elem of this) yield elem[name];
    //    })()));
    //}
    //set = function (name, value) {
    //    return new Live(Array.from((function* () {
    //        for (const elem of this) yield elem[name] = value;
    //    })()));
    //}
    //call = function (name, ...args) {
    //    return new Live(Array.from((function* () {
    //        for (const elem of this) yield elem[name].apply(elem, args);
    //    })()));
    //}

    merge = function () {
        if (arguments.length > 0)
            for (const arg of arguments)
                if (arg !== undefined)
                    if (Array.isArray(arg)) this.merge(...arg);
                    else if (arg !== null && typeof arg[Symbol.iterator] === 'function')
                        this.merge(...Array.from((function* () {
                            let current = null;
                            while (current = arg.iterateNext()) yield current;
                        })()));
                    else if (typeof arg === 'function') this.merge(arg(this, arg));
                    else this.push(arg);
        return this;
    }

    clear = function () {
        this.length = 0;
        return this;
    }

    where = function (condition, ...args) {
        return new Live(this.whereIteration(condition, ...args));
    }
    whereIteration = function* (condition, ...args) {
        let i = 0;
        for (const elem of this)
            if (condition(elem, i++, ...args))
                yield elem;
    }

    each = function (action, ...args) {
        return new Live(this.eachIteration(action, ...args));
    }
    eachIteration = function* (action, ...args) {
        let i = 0;
        for (const elem of this)
            yield action(elem, i++, ...args);
    }

    setHeightAuto = function (maxHeight = 10) {
        return this.each((tag, i, maxHeight) => {
            if (!Number.isInteger(maxHeight)) {
                tag.style.maxHeight = maxHeight;
                maxHeight = 999999999;
            }
            switch (document.body.tagName.toLowerCase()) {
                case "textarea":
                    tag.rows = Math.min(maxHeight, tag.value.split("\n").length);
                    document.getElementById(id).oninput = () => {
                        this.rows = Math.min(maxHeight, this.value.split("\n").length);
                    }
                    break;
                default:
                    tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length) * 10) + "px";
                    document.getElementById(id).oninput = () => {
                        this.style.height = (Math.min(maxHeight, this.value.split("\n").length) * 10) + "px";
                    }
                    break;
            }
        }, maxHeight);
    }
    setHeight = function (maxHeight = 10) {
        return this.each((tag, i, maxHeight) => {
            if (!Number.isInteger(maxHeight)) {
                tag.style.maxHeight = maxHeight;
                maxHeight = 999999999;
            }
            switch (document.body.tagName.toLowerCase()) {
                case "textarea":
                    tag.rows = Math.min(maxHeight, tag.value.split("\n").length);
                    break;
                default:
                    tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length) * 10) + "px";
                    break;
            }
        }, maxHeight);
    }
    setWidthAuto = function (maxWidth = 10) {
        return this.each((tag, i, maxWidth) => {
            if (!Number.isInteger(maxWidth)) {
                tag.style.maxWidth = maxWidth;
                maxWidth = 999999999;
            }
            switch (document.body.tagName.toLowerCase()) {
                default:
                    tag.style.Width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
                    document.getElementById(id).oninput = () => {
                        this.style.Width = (Math.min(maxWidth, this.value.split("\n").length) * 10) + "px";
                    }
                    break;
            }
        }, maxWidth);
    }
    setWidth = function (maxWidth = 10) {
        return this.each((tag, i, maxWidth) => {
            if (!Number.isInteger(maxWidth)) {
                tag.style.maxWidth = maxWidth;
                maxWidth = 999999999;
            }
            switch (document.body.tagName.toLowerCase()) {
                default:
                    tag.style.Width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
                    break;
            }
        }, maxWidth);
    }
}

_ = (query = null, mode = null, source = null) => new Live(query, mode, source);