class Live extends Array {
    static _animations = new WeakMap();

    constructor(query = null, mode = null, source = null) {
        super();
        this.clear();
        return this.from(query, mode, source);
    }

    toObject() {
        const IS_PROXY = Symbol("isProxy");
        if (this[IS_PROXY]) return this;
        const proxy = new Proxy(this, {
            get: (obj, prop) => {
                if (prop in obj) return obj[prop];
                return Array.from((function* () {
                    for (const elem of obj) {
                        if (prop in elem) {
                            if (typeof elem[prop] !== "function") {
                                yield elem[prop];
                            } else {
                                yield (...args) => elem[prop].apply(elem, args);
                            }
                        }
                    }
                })());
            }
        });
        proxy[IS_PROXY] = true;
        return proxy;
    }
    toString(separator = "") {
        return this.flatMap(el => el.textContent || "").join(separator);
    }

    from(query = null, mode = null, source = null) {
        if (!query && !source && !mode) return this;

        const isArrQuery = Array.isArray(query) && query.length === 2 && Number.isInteger(query[0]);
        if (query && typeof query !== "string") {
            if (!isArrQuery && !source && !mode) return this.clear().merge(query);
            else if (Array.isArray(query)) return this.clear().merge(...query);
            else if (typeof query[Symbol.iterator] === 'function') return this.clear().merge(query);
            else return this.clear().merge(query);
        }

        const sources = source ? (new Live(source, mode)).toObject() : [document];
        for (const src of sources) {
            switch ((mode ?? (query.match(/^\s*\//) ? "xpath" : isArrQuery ? "location" : "query")).toLowerCase()) {
                case "id":
                    const element = document.getElementById(query);
                    if (element) this.merge([element]);
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
                    this.merge(src.evaluate(query, src, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null));
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
        return (new Live(query, mode, this)).toObject();
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
        return (new Live(this.eachIteration(action, ...args))).toObject();
    }

    *eachIteration(action, ...args) {
        let i = 0;
        for (const elem of this) {
            yield action(elem, i++, ...args);
        }
    }

    trigger(eventName, detail = {}) {
        return this.each((elem) => {
            const evt = new CustomEvent(eventName, { bubbles: true, detail });
            elem.dispatchEvent(evt);
        });

    }
    on(eventName, action, ...args) {
        return this.each((elem) => {
            if (!action) return;
            else if (elem.addEventListener)
                elem.addEventListener(eventName, (e) => {
                    action(elem, e, ...args);
                });
            else if (elem.attachEvent)
                elem.attachEvent('on' + eventName, (e) => {
                    action(elem, e, ...args);
                });
            else elem['on' + eventName] = (e) => {
                action(elem, e, ...args);
            };
        }, ...args);
    }
    off(eventName) {
        return this.each((elem) => {
            elem.removeEventListener(eventName);
        });
    }

    ready(action, ...args) {
        if (document.readyState !== 'loading') action(document, ...args);
        else document.addEventListener('DOMContentLoaded', () => {
            action(document, ...args);
        });
        return this;
    }
    submit(action, ...args) {
        return this.on('submit', action, ...args);
    }
    click(action, ...args) {
        return this.on('click', action, ...args);
    }
    dblclick(action, ...args) {
        return this.on('dblclick', action, ...args);
    }
    hover(actionIn, actionOut, ...args) {
        return this.on('mouseenter', actionIn, ...args).on('mouseleave', actionOut, ...args);
    }
    focus(action, ...args) {
        return this.on('focus', action, ...args);
    }
    blur(action, ...args) {
        return this.on('blur', action, ...args);
    }

    parent() {
        return (new Live(this.each((elem) => elem.parentNode).flat())).toObject();
    }
    children() {
        return (new Live(this.each((elem) => Array.from(elem.children)).flat())).toObject();
    }
    siblings() {
        return (new Live(this.each((elem) => (elem.parentNode ? Array.from(elem.parentNode.children) : []).filter(child => child !== elem)).flat())).toObject();
    }
    first() {
        if (this.length === 0) return (new Live()).toObject();
        return (new Live([this[0]])).toObject();
    }
    last() {
        if (this.length === 0) return (new Live()).toObject();
        return (new Live([this[this.length - 1]])).toObject();
    }

    find(query = null, mode = null) {
        return this.each((elem) => (new Live(query, mode, elem)).toObject()).flat();
    }

    /**
     * The content from the provided URL will be loaded into the selected elements.
     * @param {string} url The URL to load content from
     * @param {string} method The HTTP method to use (GET, POST, etc.)
     */
    load(url, method = 'GET', data = null, callback = null, headers = {}) {
        return fetch(url, {
            method: method ?? 'GET',
            headers: headers,
            body: data
        }).then(response => response.text())
            .then(html => {
                this.each(elem => {
                    (new Live(elem)).html(html);
                    if (typeof callback === "function") callback(elem, html);
                });
            });
    }
    /**
     * The scripts within the selected elements will be reloaded and executed.
     */
    rebuild() {
        return this.each((elem) => {
            if (elem instanceof Element) {
                if (elem.tagName.toLowerCase() === 'script') {
                    const newScript = document.createElement('script');
                    Array.from(elem.attributes).forEach(attr => {
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    if (elem.src) newScript.src = elem.src;
                    newScript.textContent = elem.textContent;
                    elem.parentNode.replaceChild(newScript, elem);
                }
                else {
                    const scripts = elem.querySelectorAll('script');
                    for (const oldScript of scripts) {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        if (oldScript.src) newScript.src = oldScript.src;
                        newScript.textContent = oldScript.textContent;
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    }
                }
            }
        });
    }
    /**
     * The provided element(s) will be appended to the selected elements.
     * @param {Live | HTMLElement | Array<HTMLElement> | any} element To be appended to selected elements
     */
    append(element) {
        if (element instanceof Live) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.append(...element);
                l.rebuild();
            }
        });
        else if (element instanceof Element) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.append(element);
                l.rebuild();
            }
        });
        else if (Array.isArray(element)) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.append(...element);
                l.rebuild();
            }
        });
        else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = String(element);
            return this.append(Array.from(tempDiv.children));
        }
    }
    /**
     * The provided element(s) will be prepended to the selected elements.
     * @param {Live | HTMLElement | Array<HTMLElement> | any} element To be prepended to selected elements
     */
    prepend(element) {
        if (element instanceof Live) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.prepend(...element);
                l.rebuild();
            }
        });
        else if (element instanceof Element) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.prepend(element);
                l.rebuild();
            }
        });
        else if (Array.isArray(element)) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.prepend(...element);
                l.rebuild();
            }
        });
        else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = String(element);
            return this.prepend(Array.from(tempDiv.children));

        }
    }
    replace(element) {
        if (element instanceof Live) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.replaceWith(...element);
                l.rebuild();
            }
        });
        else if (element instanceof Element) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.replaceWith(element);
                l.rebuild();
            }
        });
        else if (Array.isArray(element)) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.replaceWith(...element);
                l.rebuild();
            }
        });
        else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = String(element);
            return this.replace(Array.from(tempDiv.children));
        }
    }
    after(element) {
        if (element instanceof Live) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.after(...element);
                l.rebuild();
            }
        });
        else if (element instanceof Element) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.after(element);
                l.rebuild();
            }
        });
        else if (Array.isArray(element)) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.after(...element);
                l.rebuild();
            }
        });
        else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = String(element);
            return this.after(Array.from(tempDiv.children));
        }
    }
    before(element) {
        if (element instanceof Live) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.before(...element);
                l.rebuild();
            }
        });
        else if (element instanceof Element) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.before(element);
                l.rebuild();
            }
        });
        else if (Array.isArray(element)) return this.each(tag => {
            if (tag instanceof Element) {
                const l = new Live(element);
                tag.before(...element);
                l.rebuild();
            }
        });
        else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = String(element);
            return this.before(Array.from(tempDiv.children));
        }
    }
    remove() {
        return this.each(tag => {
            if (tag instanceof Element)
                tag.remove();
        });
    }

    html(html = null) {
        if (html === null) return this.each(tag => {
            if (tag instanceof Element)
                return tag.innerHTML;
        }).toString();
        html = String(html);
        return this.each(tag => {
            if (tag instanceof Element)
                tag.innerHTML = html;
            (new Live(tag)).rebuild();
        });
    }
    text(text = null) {
        if (text === null) return this.each(tag => {
            if (tag instanceof Element)
                return tag.textContent;
        }).toString();
        text = String(text);
        return this.each(tag => {
            if (tag instanceof Element) tag.textContent = text;
        });
    }
    val(value = null) {
        if (value === null) {
            if (this.length === 0) return null;
            const el = this[0];
            return el && "value" in el ? el.value : null;
        }
        value = String(value);
        return this.each(tag => {
            if (tag && "value" in tag) tag.value = value;
        });
    }

    attr(name, value = null) {
        if (value === null) {
            if (this.length === 0) return null;
            const el = this[0];
            return el instanceof Element ? el.getAttribute(name) : null;
        }
        value = String(value);
        return this.each(tag => {
            if (tag instanceof Element) tag.setAttribute(name, value);
        });
    }
    css(property, value = null) {
        if (value === null) {
            if (this.length === 0) return null;
            const el = this[0];
            return el instanceof Element ? window.getComputedStyle(el)[property] : null;
        }
        value = String(value);
        return this.each(tag => {
            if (tag instanceof Element) tag.style[property] = value;
        });
    }

    addClass(className) {
        return this.each(tag => {
            if (tag instanceof Element) tag.classList.add(className);
        });
    }
    removeClass(className) {
        return this.each(tag => {
            if (tag instanceof Element) tag.classList.remove(className);
        });
    }
    toggleClass(className) {
        return this.each(tag => {
            if (tag instanceof Element) tag.classList.toggle(className);
        });
    }
    hasClass(className) {
        if (this.length === 0) return false;
        return this[0] instanceof Element && this[0].classList.contains(className);
    }

    offset() {
        if (this.length === 0) return null;
        const el = this[0];
        if (!(el instanceof Element)) return null;
        const rect = el.getBoundingClientRect();
        return {
            top: rect.top + window.pageYOffset,
            left: rect.left + window.pageXOffset,
            width: rect.width,
            height: rect.height
        };
    }
    position() {
        if (this.length === 0) return null;
        const el = this[0];
        if (!(el instanceof Element)) return null;
        return {
            top: el.offsetTop,
            left: el.offsetLeft
        };
    }
    size() {
        if (this.length === 0) return null;
        const el = this[0];
        if (!(el instanceof Element)) return null;
        return {
            width: el.offsetWidth,
            height: el.offsetHeight
        };
    }
    scrollTop(value = null) {
        if (value === null) {
            if (this.length === 0) return null;
            const el = this[0];
            if (el === window || el === document)
                return window.pageYOffset || document.documentElement.scrollTop || 0;
            return el instanceof Element ? el.scrollTop : null;
        }
        const v = Number(value) || 0;
        return this.each(tag => {
            if (tag === window || tag === document) {
                window.scrollTo(window.pageXOffset || 0, v);
            } else if (tag instanceof Element) {
                tag.scrollTop = v;
            }
        });
    }
    scrollLeft(value = null) {
        if (value === null) {
            if (this.length === 0) return null;
            const el = this[0];
            if (el === window || el === document)
                return window.pageXOffset || document.documentElement.scrollLeft || 0;
            return el instanceof Element ? el.scrollLeft : null;
        }
        const v = Number(value) || 0;
        return this.each(tag => {
            if (tag === window || tag === document) {
                window.scrollTo(v, window.pageYOffset || 0);
            } else if (tag instanceof Element) {
                tag.scrollLeft = v;
            }
        });
    }
    scrollIntoView(alignToTop = true) {
        return this.each(tag => {
            if (tag instanceof Element) tag.scrollIntoView(alignToTop);
        });
    }

    /**
     * Animates the selected elements with the provided properties.
     * @param {Object} properties The CSS properties or Numeric element's properties to animate with their target startValues or endValues
     * @param {int} duration The duration of the animation in milliseconds
     * @param {string} easing The easing function to use (linear, inQuad, outQuad, inOutQuad, inCubic, outCubic, inOutCubic, inQuart, outQuart, inOutQuart, inQuint, outQuint, inOutQuint)
     * @param {Function} callback The function to call when the animation is complete
     */
    animate(properties, duration = 400, easing = 'linear', callback = null) {
        const easings = {
            linear: t => t,
            inQuad: t => t * t,
            outQuad: t => t * (2 - t),
            inOutQuad: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
            inCubic: t => t * t * t,
            outCubic: t => (--t) * t * t + 1,
            inOutCubic: t => t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2,
            inQuart: t => t * t * t * t,
            outQuart: t => 1 - (--t) * t * t * t,
            inOutQuart: t => t < 0.5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t,
            inQuint: t => t * t * t * t * t,
            outQuint: t => 1 + (--t) * t * t * t * t,
            inOutQuint: t => t < 0.5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t
        };
        const easeFunc = easings[easing] || easings.linear;
        const dur = Math.max(0, Number(duration) || 0);

        this.each((el) => {
            if (!(el instanceof Element) && el !== window && el !== document) return;

            const prev = Live._animations.get(el);
            if (prev && prev.rafId) cancelAnimationFrame(prev.rafId);

            const startValues = {};
            const endValues = {};
            const units = {};

            for (const prop in properties) {
                const targetRaw = properties[prop];
                if (prop in el && typeof el[prop] === 'number') {
                    const start = Number(el[prop]) || 0;
                    const end = Number(targetRaw);
                    startValues[prop] = start;
                    endValues[prop] = isNaN(end) ? start : end;
                    units[prop] = '';
                    continue;
                }

                const computed = window.getComputedStyle(el)[prop];
                const startNum = parseFloat(computed);
                const startIsNum = !isNaN(startNum);
                const targetStr = String(targetRaw);
                const targetNum = parseFloat(targetStr);
                const targetIsNum = !isNaN(targetNum);

                const unitMatch = targetStr.match(/[a-z%]+$/i);
                const unit = unitMatch ? unitMatch[0] : (computed ? computed.replace(/^[\d.\-+]+/, '') : 'px');

                startValues[prop] = startIsNum ? startNum : 0;
                endValues[prop] = targetIsNum ? targetNum : startValues[prop];
                units[prop] = unit || 'px';
            }

            const startTime = performance.now();

            function step(now) {
                const elapsed = now - startTime;
                const progress = dur === 0 ? 1 : Math.min(elapsed / dur, 1);
                const eased = easeFunc(progress);

                for (const prop in properties) {
                    const s = startValues[prop];
                    const e = endValues[prop];
                    const val = s + (e - s) * eased;
                    if (prop in el && typeof el[prop] === 'number') {
                        el[prop] = val;
                    } else {
                        try {
                            el.style[prop] = val + (units[prop] || '');
                        } catch (err) {
                        }
                    }
                }

                if (progress < 1) {
                    const rafId = requestAnimationFrame(step);
                    Live._animations.set(el, { rafId });
                } else {
                    Live._animations.delete(el);
                    if (typeof callback === 'function') callback(el);
                }
            }

            const rafId = requestAnimationFrame(step);
            Live._animations.set(el, { rafId });
        });

        return this;
    }
    stop() {
        return this.each((el) => {
            const anim = Live._animations.get(el);
            if (anim && anim.rafId) {
                cancelAnimationFrame(anim.rafId);
                Live._animations.delete(el);
            }
        });
    }

    hide() {
        return this.each(tag => {
            tag.__live_display = window.getComputedStyle(tag).display;
            if (tag instanceof Element)
                tag.style.display = 'none';
        });
    }
    show(display = null) {
        return this.each(tag => {
            const prevDisplay = display || tag.__live_display || window.getComputedStyle(tag).display || 'block';
            if (tag instanceof Element)
                tag.style.display = prevDisplay === 'none' ? 'block' : prevDisplay;
        });
    }

    fadeIn(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const l = new Live(tag);
            l.stop();
            const prevDisplay = tag.__live_display || window.getComputedStyle(tag).display || 'block';
            tag.style.display = prevDisplay === 'none' ? 'block' : prevDisplay;
            tag.style.opacity = tag.style.opacity || window.getComputedStyle(tag).opacity || 0;
            l.animate({ opacity: 1 }, duration, easing, function (...args) {
                tag.style.opacity = '';
                if (typeof callback === 'function') callback(...args);
            });
        });
    }
    fadeOut(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const l = new Live(tag);
            l.stop();
            tag.__live_display = window.getComputedStyle(tag).display;
            l.animate({ opacity: 0 }, duration, easing, function (...args) {
                tag.style.display = 'none';
                tag.style.opacity = '';
                if (typeof callback === 'function') callback(...args);
            });
        });
    }
    toggle(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const isHidden = window.getComputedStyle(tag).display === 'none';
            const l = new Live(tag);
            if (isHidden) l.fadeIn(duration, easing, callback);
            else l.fadeOut(duration, easing, callback);
        });
    }

    slideUp(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const l = new Live(tag);
            l.stop();
            const startH = tag.offsetHeight;
            tag.style.overflow = 'hidden';
            tag.style.boxSizing = tag.style.boxSizing || window.getComputedStyle(tag).boxSizing;
            l.animate({ height: 0 }, duration, easing, function (...args) {
                tag.style.display = 'none';
                tag.style.height = '';
                tag.style.overflow = '';
                if (typeof callback === 'function') callback(...args);
            });
        });
    }
    slideDown(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const l = new Live(tag);
            l.stop();
            const prevDisplay = tag.__live_display || window.getComputedStyle(tag).display || 'block';
            tag.style.display = prevDisplay === 'none' ? 'block' : prevDisplay;
            tag.style.overflow = 'hidden';
            const target = tag.scrollHeight;
            tag.style.height = '0px';
            l.animate({ height: target }, duration, easing, function (...args) {
                tag.style.height = '';
                tag.style.overflow = '';
                if (typeof callback === 'function') callback(...args);
            });
        });
    }
    slideRight(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (tag instanceof Element) {
                const originalWidth = tag.offsetWidth;
                (new Live(tag)).animate({ width: 0 }, duration, easing, () => {
                    tag.style.display = 'none';
                    tag.style.width = originalWidth + 'px';
                    if (typeof callback === "function") callback.call(tag);
                });
            }
        });
    }
    slideLeft(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (tag instanceof Element) {
                tag.style.display = 'inherit';
                const targetWidth = tag.offsetWidth;
                tag.style.width = '0px';
                (new Live(tag)).animate({ width: targetWidth }, duration, easing, () => {
                    tag.style.width = '';
                    if (typeof callback === "function") callback.call(tag);
                }
                );
            }
        });
    }
    slideToggle(duration = 400, easing = 'linear', callback = null) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            const l = new Live(tag);
            const isHidden = window.getComputedStyle(tag).display === 'none';
            if (isHidden) l.slideDown(duration, easing, callback);
            else l.slideUp(duration, easing, callback);
        });
    }

    /**
     * The height of the selected elements will be set based on their content, up to a maximum height.
     * @param {int} maxHeight The maximum height to set
     */
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
    /**
     * The width of the selected elements will be set based on their content, up to a maximum width.
     * @param {int} maxWidth The maximum width to set
     */
    setWidth(maxWidth = 10) {
        return this.each((tag, i, maxWidth) => {
            if (!Number.isInteger(maxWidth)) {
                tag.style.maxWidth = maxWidth;
                maxWidth = 999999999;
            }
            tag.style.width = (Math.min(maxWidth, tag.value.split("\n").length) * 10) + "px";
        }, maxWidth);
    }
    setHeightAuto(maxHeight = Infinity) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            if (typeof maxHeight !== 'number') {
                tag.style.maxHeight = String(maxHeight);
                maxHeight = Infinity;
            }
            if (tag.tagName.toLowerCase() === 'textarea') {
                const resize = () => {
                    tag.style.height = 'auto';
                    const h = Math.min(maxHeight, tag.scrollHeight);
                    tag.style.height = h + 'px';
                };
                resize();
                tag.addEventListener('input', resize);
            } else {
                const resize = () => {
                    tag.style.height = 'auto';
                    const h = Math.min(maxHeight, tag.scrollHeight);
                    tag.style.height = h + 'px';
                };
                resize();
                // attach input/DOM mutation observer if needed
            }
        });
    }
    setWidthAuto(maxWidth = Infinity) {
        return this.each(tag => {
            if (!(tag instanceof Element)) return;
            if (typeof maxWidth !== 'number') {
                tag.style.maxWidth = String(maxWidth);
                maxWidth = Infinity;
            }
            const resize = () => {
                tag.style.width = 'auto';
                const w = Math.min(maxWidth, tag.scrollWidth);
                tag.style.width = w + 'px';
            };
            resize();
        });
    }
}

// Utility function for creating Live instances or executing a function on DOM ready
const _ = (query = null, mode = null, source = null) => {
    if (typeof query === "function") {
        if (document.readyState !== 'loading') query();
        else document.addEventListener('DOMContentLoaded', query);
        query = null;
    }
    return (new Live(query, mode, source)).toObject();
};