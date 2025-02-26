const isNull = function (obj) {
	return obj === null;
};

// const isNaN = function (obj) {
// 	return typeof obj != "string" && obj + "" === "NaN";
// }

const isEmpty = function (obj) {
	return obj === null || obj === "" || obj == {} || obj == [];
};


const isDefined = function (obj) {
	return !isUndefined(obj);
};

const isUndefined = function (obj) {
	return obj === undefined;
};

const isHollow = function (obj) {
	return obj === undefined || isNaN(obj) || isEmpty(obj) || (obj + "").trim() === "";
};

const isSet = function (obj) {
	return !isUnset(obj);
};

const isUnset = function (obj) {
	return obj === undefined || obj === null || isNaN(obj);
};

const isString = function (obj) {
	return typeof obj === 'string';
};

const isText = function (obj) {
	return isString(obj) && !obj.trimStart().startsWith("<");
};

const isObject = function (obj) {
	return typeof obj === 'object';
};

const isNumber = function (obj) {
	return parseFloat(obj) + "" === obj + "";
};

const isInt = function (n) {
	return Number(n) === n && n % 1 === 0;
};

const isFloat = function (n) {
	return Number(n) === n && n % 1 !== 0;
};

const isArray = function (obj, dim = 1) {
	if (isHollow(obj)) return false;
	let objt = typeof obj;
	if (objt === 'string') return false;
	if (Array.isArray(obj)) return --dim === 0 || isArray(obj[0], dim);
	if (objt === 'object' && (obj["count"] !== undefined || obj["Length"] !== undefined))
		return --dim === 0 || isArray(obj[0], dim);
	return false;
};

const isIterable = function (obj) {
	// checks for null and undefined
	if (obj == null) return false;
	return typeof obj[Symbol.iterator] === 'function';
}

const apply = function (func, args, maxArgsNum = 1) {
	if (isArray(args))
		if (count(args) > maxArgsNum)
			return each(args, item => apply(func, item, maxArgsNum));
		else return func.apply(null, args);
	else return func(args);
};

const between = function () {
	for (const val of arguments)
		if (isSet(val)) return val;
	return arguments[arguments.length - 1];
};

const expand = function* () {
	for (const val of arguments)
		if (Array.isArray(val))
			for (const v of expand.apply(null, val))
				yield v;
		else if (isArray(val))
			for (const v of expand(each(val)))
				yield v;
		else yield val;
};

const array = function (arr) {
	if (isArray(arr))
		if (count(arr) === 1)
			return array(arr[0]);
		else if (Array.isArray(obj)) return arr;
		else return each(arr);
	else return [arr];
};

const each = function (arr, func = (o, i) => o) {
	if (isArray(arr))
		return Array.from(
			(function* () {
				let i = 0;
				for (const item of arr)
					yield func(item, i++);
			})()
		);
	else return [func(arr, 0)];
};

const eachIn = function (arr, func = (o, i) => o) {
	return Array.from(
		(function* () {
			let i = 0;
			for (const item in arr)
				yield func(item, i++);
		})()
	);
};

const where = function (arr, condition = (o, i) => true, func = null) {
	if (func === null) {
		if (isArray(arr))
			return Array.from(
				(function* () {
					let i = 0;
					for (const item of arr)
						if (condition(item, i++)) yield item;
				})()
			);
		else if (condition(arr, 0)) return [arr];
	} else {
		if (isArray(arr))
			return Array.from(
				(function* () {
					let i = 0;
					for (const item of arr)
						if (condition(item, i)) yield func(item, i++);
						else i++;
				})()
			);
		else if (condition(arr, 0)) return [func(arr, 0)];
	}
	return [];
};

const any = function (arr, condition = (o, i) => true, func = null) {
	if (func === null) {
		if (isArray(arr))
			for (const item of arr)
				if (condition(item, i++))
					return item;
				else if (condition(arr, 0)) return arr;
	} else {
		if (isArray(arr))
			for (const item of arr)
				if (condition(item, i))
					return func(item, i++);
				else i++;
		else if (condition(arr, 0)) return func(arr, 0);
	}
};

const select = function (arr, func = (o, i) => o) {
	if (isArray(arr)) return each(arr, func);
	else return eachIn(arr, func);
};

const loop = function (i, len = null, func = i => i) {
	if (len == null) {
		len = i;
		i = 0;
	}
	return Array.from(
		(function* () {
			for (i; i < len; i++)
				yield func(i);
		})()
	);
};

const count = function () {
	return each.apply(null, arguments).length;
};

const first = function () {
	if (arguments.length == 0) return null;
	if (arguments.length == 1)
		if (isArray(arguments[0])) return arguments[0][0];
		else return arguments[0];
	if (arguments.length > 1) return arguments[0];
}

const last = function () {
	if (arguments.length == 0) return null;
	if (arguments.length == 1)
		if (isArray(arguments[0])) return arguments[0][arguments[0].length - 1];
		else return arguments[0];
	if (arguments.length > 1) return arguments[0];
}

const sort = function () {
	if (arguments.length > 2) {
		let func = arguments[2];
		return each.apply(null, arguments).sort((a, b) => func(a) < func(b) ? -1 : func(a) > func(b));
	}
	return each.apply(null, arguments).sort((a, b) => a < b ? -1 : a > b);
};

const distinct = function () {
	if (arguments.length > 2) return each.apply(null, arguments).filter((v, i, a) => arguments[2](v, i, a));
	return each.apply(null, arguments).filter((v, i, a) => a.indexOf(v) === i);
};

const tryParseFloat = function (val) {
	return isEmpty(val) ? 0 : between(parseFloat(val), val);
};

const tryParseInt = function (val) {
	return isEmpty(val) ? 0 : between(parseInt(val), val);
};

const forceParseFloat = function (val, def = 0) {
	return parseFloat(val ?? def) ?? def;
};

const forceParseInt = function (val, def = 0) {
	return parseInt(val ?? def) ?? def;
};

const wait = function (milliseconds) {
	const date = Date.now();
	let currentDate = null;
	do {
		currentDate = Date.now();
	} while (currentDate - date < milliseconds);
};

const copy = function (val) {
	navigator.clipboard.writeText(val);
};
const paste = function () {
	var val = null;
	navigator.clipboard.readText().then(v => val = v);
	return val;
};
const copyFrom = function (id) {
	var input = document.getElementById(id);
	const color = input.style.color;
	const bcolor = input.style.backgroundColor;
	input.style.color = bcolor;
	input.style.backgroundColor = color;
	input.select();
	input.setSelectionRange(0, 999999999);
	navigator.clipboard.writeText(input.value);
	input.style.color = color;
	input.style.backgroundColor = bcolor;
};
const pasteInto = function (id) {
	var input = document.getElementById(id);
	const color = input.style.color;
	const bcolor = input.style.backgroundColor;
	input.style.color = bcolor;
	input.style.backgroundColor = color;
	input.select();
	input.setSelectionRange(0, 999999999);
	navigator.clipboard.readText().then(v => input.value = v);
	input.style.color = color;
	input.style.backgroundColor = bcolor;
};
const clearText = function (id, focus = false) {
	var input = document.getElementById(id);
	const color = input.style.color;
	const bcolor = input.style.backgroundColor;
	input.style.color = bcolor;
	input.style.backgroundColor = color;
	if (isUndefined(input.value))
		input.innerText = "";
	else input.value = "";
	if (focus) input.focus();
	input.style.color = color;
	input.style.backgroundColor = bcolor;
};
const isVisible = (element, partiallyVisible = true) => {
	const { top, left, bottom, right } = element.getBoundingClientRect();
	const { innerHeight, innerWidth } = window;
	return partiallyVisible
		? ((top > 0 && top < innerHeight) ||
			(bottom > 0 && bottom < innerHeight)) &&
		((left > 0 && left < innerWidth) || (right > 0 && right < innerWidth))
		: top >= 0 && left >= 0 && bottom <= innerHeight && right <= innerWidth;
};
const isFocused = (element) => document.activeElement === element;

const scrollTo = function (selector = "body :nth-child(1)", time = 1000) {
	$('html, body').animate({
		scrollTop: parseInt($(selector).offset().top)
	}, time);
};

const reload = function () {
	window.location.assign(location.href);
};
const load = function (url = null) {
	window.location.assign(url ?? location.href);
};
const open = function (url = null, target = '_blank') {
	window.open(url ?? location.href, target);
};
const share = function (url = null, path = null) {
	open('sms://' + path + '?body=' + (url ?? location.href), '_blank');
};
const message = function (url = null, path = null) {
	open('sms://' + path + '?body=' + (url ?? location.href), '_blank');
};

const mailTo = function (email) {
	open('mailto:' + email, '_blank');
};

const sendRequest = function (
	method = 'POST',
	url = null,
	data = null,
	selector = 'body :nth-child(1)',
	success = null,
	error = null,
	ready = null,
	progress = null,
	timeout = null) {

	const btns = document.querySelectorAll(selector + ' :is(button, .btn, .icon, input:is([type=button],[type=submit],[type=image],[type=reset]))');
	const elems = document.querySelectorAll(selector);
	const opacity = document.querySelector(selector).style.opacity;
	url = url ?? location.href;
	timeout = timeout ?? 30000;
	method = (method ?? "POST").toUpperCase();
	let isForm = false;
	let contentType = 'application/x-www-form-urlencoded; charset=utf-8';

	if (data)
		if (isForm = data instanceof FormData) {
			contentType = false;
		} else if (method === "GET") {
			url += (url.includes('?') ? '&' : '?');
			if (typeof data === 'object' || Array.isArray(data))
				url += new URLSearchParams(data).toString();
			else url += data;
			contentType = false;
			data = null;
		} else if (typeof data === 'object' || Array.isArray(data)) {
			data = JSON.stringify(data);
			contentType = 'application/json; charset=utf-8';
		} else if (typeof data === 'string') {
			contentType = 'application/x-www-form-urlencoded; charset=utf-8';
		}

	success = success ?? function (result = null, err = null) {
		if (err) error(result, err);
		else {
			try { document.querySelector(selector + ' .result').remove(); } catch { }
			if (!isEmpty(result)) {
				result = ((typeof (result) === 'object') ? result.statusText : result) ?? 'Form submitted successfully!';
				if (!isEmpty(result)) {
					if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Html.success(result);
					if (isForm) $(selector).append(result);
					else $(selector).prepend(result);
				}
			}
		}
	};

	error = error ?? function (result = null, err = null) {
		try { document.querySelector(selector + ' .result').remove(); } catch { }
		if (!isEmpty(result)) {
			result = ((typeof (result) === 'object') ? result.statusText : result) ?? 'There was a problem!';
			if (!isEmpty(result)) {
				if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Html.error(result);
				if (isForm) $(selector).append(result);
				else $(selector).prepend(result);
			}
		}
	};

	ready = ready ?? function (result = null, err = null) {
		try { document.querySelector(selector + ' .result').remove(); } catch { }
	};

	progress = progress ?? function (result = null, err = null) {
		if (!isEmpty(result)) {
			result = (typeof (result) === 'object') ? result.statusText : result;
			if (result) {
				try { document.querySelector(selector + ' .result').remove(); } catch { }
				{
					if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Html.message(result);
					if (isForm) $(selector).append(result);
					else $(selector).prepend(result);
				}
			}
		}
	};

	const xhr = new XMLHttpRequest();
	xhr.open(method, url, true);

	if (contentType) xhr.setRequestHeader('Content-Type', contentType);

	xhr.timeout = timeout;

	xhr.upload.addEventListener('progress', progress);

	xhr.onload = function () {
		btns.forEach(btn => btn.classList.remove('hide'));
		elems.forEach(elem => elem.style.opacity = opacity);

		if (xhr.status >= 200 && xhr.status < 300) {
			try {
				const response = xhr.getResponseHeader('Content-Type')?.includes('application/json') ? JSON.parse(xhr.response) : xhr.response;
				success(response);
			} catch (e) {
				error("There was a problem on retrieving data!<br>" + e.message, xhr.status);
			}
		} else {
			error(xhr.response, xhr.status);
		}
	};

	xhr.onerror = function () {
		btns.forEach(btn => btn.classList.remove('hide'));
		elems.forEach(elem => elem.style.opacity = opacity);
		error('Network Error' + (xhr.statusText ? "<br>" + xhr.statusText : ""), xhr.status);
	};

	xhr.ontimeout = function () {
		btns.forEach(btn => btn.classList.remove('hide'));
		elems.forEach(elem => elem.style.opacity = opacity);
		error('Timeout' + (xhr.statusText ? "<br>" + xhr.statusText : ""), xhr.status ?? 'timeout');
	};

	xhr.onloadstart = function () {
		ready(xhr.statusText, xhr.status);
		btns.forEach(btn => btn.classList.add('hide'));
		elems.forEach(elem => elem.style.opacity = '.5');
	};

	if (data) xhr.send(data);
	else xhr.send();

	return xhr;
};

const sendGet = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('GET', url, data, selector, success, error, ready, progress, timeout);
};
const sendPost = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('POST', url, data, selector, success, error, ready, progress, timeout);
};
const sendPut = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('PUT', url, data, selector, success, error, ready, progress, timeout);
};
const sendPatch = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('PATCH', url, data, selector, success, error, ready, progress, timeout);
};
const sendDelete = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('DELETE', url, data, selector, success, error, ready, progress, timeout);
};
const sendFile = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	if (data) return sendRequest('POST', url, data, selector, success, error, ready, progress, timeout);
	else {
		input.setAttribute('type', 'file');
		res = null;
		input.onchange = evt => {
			const [file] = input.files;
			if (file) {
				const reader = new FileReader();
				reader.addEventListener('load', (event) => {
					res = sendRequest('POST', url, encodeURIComponent(event.target.result), selector, success, error, ready, progress, timeout);
				});
				reader.readAsDataURL(file);
			}
		}
		$(input).trigger('click');
		return res;
	}
};
const sendInternal = function (url = null, data = null, selector = 'body :nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('INTERNAL', url, data, selector, success, error, ready, progress, timeout);
};

const submitForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	form = document.querySelector(selector);
	if (!form) return null;
	return sendRequest(form.getAttribute('method'), form.getAttribute('action'), new FormData(form), selector, success, error, ready, progress, timeout);
};
const handleForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	form = document.querySelector(selector);
	if (form) form.onsubmit = function (e) {
		e.preventDefault();
		return sendRequest(form.getAttribute('method'), form.getAttribute('action'), new FormData(form), selector, success, error, ready, progress, timeout);
	};
};