class Live extends array {
    constructor(query = null, source = null, mode = null) {
        super();
        this.from(query, source, mode);
    }

    from(query = null, source = null, mode = null) {
        if (query === null && source === null && mode === null) return this.get();
        if (query !== null && typeof query !== "string" && source === null && mode === null) return this.set(query);
        for (const src of (source === null ? [document] : new Live(source))) {
            switch ((mode ?? "").toLowerCase()) {
                case "id":
                    this.join(src.getElementById(query));
                case "name":
                    this.join(src.getElementsByName(query));
                case "tag":
                    this.join(src.getElementsByTagName(query));
                case "class":
                    this.join(src.getElementsByClassName(query));
                case "location":
                    this.join(src.elementsFromPoint(query));
                case "query":
                    this.join(src.querySelectorAll(query));
                case "xpath":
                    this.join(Array.from(src.evaluate(query, document, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null)));
                case "pure":
                default:
                    if (typeof query === 'string') this.join(src.querySelectorAll(query));
                    else this.join(query);
            }
        }
        return this;
    }
    select(query = null, mode = null) {
        return new Live(query, this, mode);
    }

    join() {
        if (arguments.length > 0)
            if (Array.isArray(arguments[0]))
                for (const elem of elements)
                    this.join(elem);
            else this.push(arguments[0]);
        return this;
    }

    get() {
        return this;
    }
    set() {
        this.splice(0, this.length);
        this.push.apply(null, arguments);
        return this;
    }

    where(condition, ...args) {
        return new Live(Array.from(this.whereIteration(condition, ...args)));
    }
    * whereIteration(condition, ...args) {
        for (const elem of this)
            if (condition(elem, ...args))
                yield elem;
    }

    each(action, ...args) {
        return new Live(new Live(Array.from(this.eachIteration(action, ...args)));
    }
    * eachIteration(action, ...args) {
        for (const elem of this)
		    yield action(elem, ...args);
    }

    autoHeight(maxHeight = 10) {
        return this.each((tag, maxHeight) => {
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
                    tag.style.height = (Math.min(maxHeight, tag.value.split("\n").length)*10)+"px";
                    document.getElementById(id).oninput = () => {
                        this.style.height = (Math.min(maxHeight, this.value.split("\n").length)*10)+"px";
                    }
                    break;
            }
        }, maxHeight);
    }
    setHeight(maxHeight = 10) {
        return this.each((tag, maxHeight) => {
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
    autoWidth(maxWidth = 10) {
        return this.each((tag, maxWidth) => {
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
    setWidth(maxWidth = 10) {
        return this.each((tag, maxWidth) => {
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