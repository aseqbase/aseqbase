class Live extends Array {
    constructor(query = null, mode = null, source = null) {
        super();
        this.clear();
        this.from(query, mode, source);
        return new Proxy(this, {
            get: (obj, prop) => {
                if (obj.hasOwnProperty(prop)) return obj[prop];
                return Array.from((function* () {
                    for (const elem of obj) {
                        if (elem.hasOwnProperty(prop)) {
                            if (typeof elem[prop] !== "function") yield elem[prop];
                            else yield (...args) => elem[prop].apply(elem, args);
                        }
                    }
                })());
            }
        });
    }

    from(query = null, mode = null, source = null) {
        if (!query && !source && !mode) return this;

        const isArrQuery = Array.isArray(query) && query.length === 2 && Number.isInteger(query[0]);
        if (query) {
            if (typeof query !== "string" && !isArrQuery && !source && !mode) return this.clear().merge(query);
            else if (typeof query[Symbol.iterator] === 'function') return this.clear().merge(query);
        }

        const sources = source ? new Live(source, mode) : [document];
        for (const src of sources) {
            switch ((mode ?? (query.match(/^\s*\//) ? "xpath" : isArrQuery ? "location" : "query")).toLowerCase()) {
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
                    this.merge(isArrQuery ? src.elementsFromPoint(query[0], query[1]) : src.elementsFromPoint(query));
                    break;
                case "xpath":
                    this.merge(src.evaluate(query, document, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null));
                    break;
                case "query":
                case "pure":
                default:
                    this.merge(typeof query === 'string' ? src.querySelectorAll(query) : query);
                    break;
            }
        }
        return this;
    }

    select(query = null, mode = null) {
        return new Live(query, mode, this);
    }

    merge(...args) {
        for (const arg of args) {
            if (arg !== undefined) {
                if (Array.isArray(arg)) {
                    this.merge(...arg);
                } else if (arg !== null && typeof arg[Symbol.iterator] === 'function' && !(arg instanceof Node)) {
                    if (arg instanceof XPathResult) {
                        this.merge(...Array.from((function* () {
                            let current;
                            while ((current = arg.iterateNext())) yield current;
                        })()));
                    } else {
                        this.merge(...Array.from(arg));
                    }
                } else if (typeof arg === 'function') {
                    this.merge(arg(this, arg));
                } else {
                    this.push(arg);
                }
            }
        }
        return this;
    }

    clear() {
        this.length = 0;
        return this;
    }

    where(condition, ...args) {
        return new Live(this.whereIteration(condition, ...args));
    }

    *whereIteration(condition, ...args) {
        let i = 0;
        for (const elem of this) {
            if (condition(elem, i++, ...args)) yield elem;
        }
    }

    each(action, ...args) {
        return new Live(this.eachIteration(action, ...args));
    }

    *eachIteration(action, ...args) {
        let i = 0;
        for (const elem of this) {
            yield action(elem, i++, ...args);
        }
    }

    appendContent(htmlString) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        this.each(tag => {
            while (tempDiv.firstChild) {
                tag.appendChild(tempDiv.firstChild);
            }
        });
    }

    prependContent(htmlString) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        this.each(tag => {
            while (tempDiv.firstChild) {
                tag.prepend(tempDiv.firstChild);
            }
        });
    }

    replaceContent(htmlString) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        this.each(tag => {
            tag.innerHTML = '';
            while (tempDiv.firstChild) {
                tag.appendChild(tempDiv.firstChild);
            }
        });
    }

    setHeightAuto(maxHeight = 10) {
        return this.each((tag, i, maxHeight) => {
            if (!Number.isInteger(maxHeight)) {
                tag.style.maxHeight = maxHeight;
                maxHeight = 999999999;
            }
            switch (tag.tagName.toLowerCase()) {
                case "textarea":
                    tag.rows = Math.min(maxHeight, tag.value.split("\n").length);
                    tag.oninput = () => {
                        tag.rows = Math.min(maxHeight, tag.value.split("\n").length);
                    };
                    break;
                default:
                    tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length) * 10) + "px";
                    tag.oninput = () => {
                        tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length) * 10) + "px";
                    };
                    break;
            }
        }, maxHeight);
    }

    setHeight(maxHeight = 10) {
        return this.each((tag, i, maxHeight) => {
            if (!Number.isInteger(maxHeight)) {
                tag.style.maxHeight = maxHeight;
                maxHeight = 999999999;
            }
            switch (tag.tagName.toLowerCase()) {
                case "textarea":
                    tag.rows = Math.min(maxHeight, tag.value.split("\n").length);
                    break;
                default:
                    tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length) * 10) + "px";
                    break;
            }
        }, maxHeight);
    }

    setWidthAuto(maxWidth = 10) {
        return this.each((tag, i, maxWidth) => {
            if (!Number.isInteger(maxWidth)) {
                tag.style.maxWidth = maxWidth;
                maxWidth = 999999999;
            }
            tag.style.width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
            tag.oninput = () => {
                tag.style.width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
            };
        }, maxWidth);
    }

    setWidth(maxWidth = 10) {
        return this.each((tag, i, maxWidth) => {
            if (!Number.isInteger(maxWidth)) {
                tag.style.maxWidth = maxWidth;
                maxWidth = 999999999;
            }
            tag.style.width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
        }, maxWidth);
    }
}

// Utility function for creating Live instances
const _ = (query = null, mode = null, source = null) => new Live(query, mode, source);
