let isNull = function (obj) {
	return obj === null;
};

// const isNaN = function (obj) {
// 	return typeof obj != "string" && obj + "" === "NaN";
// }

let isEmpty = function (obj) {
	return obj === null || obj === "" || obj == {} || obj == [];
};

let isDefined = function (obj) {
	return !isUndefined(obj);
};

let isUndefined = function (obj) {
	return obj === undefined;
};

let isHollow = function (obj) {
	return obj === undefined || isNaN(obj) || isEmpty(obj) || (obj + "").trim() === "";
};

let isSet = function (obj) {
	return !isUnset(obj);
};

let isUnset = function (obj) {
	return obj === undefined || obj === null || isNaN(obj);
};

let isString = function (obj) {
	return typeof obj === 'string';
};

let isText = function (obj) {
	return isString(obj) && !obj.trimStart().startsWith("<");
};

let isObject = function (obj) {
	return typeof obj === 'object';
};

let isNumber = function (obj) {
	return parseFloat(obj) + "" === obj + "";
};

let isInt = function (n) {
	return Number(n) === n && n % 1 === 0;
};

let isFloat = function (n) {
	return Number(n) === n && n % 1 !== 0;
};

let isArray = function (obj, dim = 1) {
	if (isHollow(obj)) return false;
	let objt = typeof obj;
	if (objt === 'string') return false;
	if (Array.isArray(obj)) return --dim === 0 || isArray(obj[0], dim);
	if (objt === 'object' && (obj["count"] !== undefined || obj["Length"] !== undefined))
		return --dim === 0 || isArray(obj[0], dim);
	return false;
};

let isIterable = function (obj) {
	// checks for null and undefined
	if (obj == null) return false;
	return typeof obj[Symbol.iterator] === 'function';
}

let apply = function (func, args, maxArgsNum = 1) {
	if (isArray(args))
		if (count(args) > maxArgsNum)
			return each(args, item => apply(func, item, maxArgsNum));
		else return func.apply(null, args);
	else return func(args);
};

let between = function () {
	for (const val of arguments)
		if (isSet(val)) return val;
	return arguments[arguments.length - 1];
};

let expand = function* () {
	for (const val of arguments)
		if (Array.isArray(val))
			for (const v of expand.apply(null, val))
				yield v;
		else if (isArray(val))
			for (const v of expand(each(val)))
				yield v;
		else yield val;
};

let array = function (arr) {
	if (isArray(arr))
		if (count(arr) === 1)
			return array(arr[0]);
		else if (Array.isArray(obj)) return arr;
		else return each(arr);
	else return [arr];
};

let each = function (arr, func = (o, i) => o) {
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

let eachIn = function (arr, func = (o, i) => o) {
	return Array.from(
		(function* () {
			let i = 0;
			for (const item in arr)
				yield func(item, i++);
		})()
	);
};

let where = function (arr, condition = (o, i) => true, func = null) {
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

let any = function (arr, condition = (o, i) => true, func = null) {
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

let select = function (arr, func = (o, i) => o) {
	if (isArray(arr)) return each(arr, func);
	else return eachIn(arr, func);
};

let loop = function (i, len = null, func = i => i) {
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

let count = function () {
	return each.apply(null, arguments).length;
};

let first = function () {
	if (arguments.length == 0) return null;
	if (arguments.length == 1)
		if (isArray(arguments[0])) return arguments[0][0];
		else return arguments[0];
	if (arguments.length > 1) return arguments[0];
}

let last = function () {
	if (arguments.length == 0) return null;
	if (arguments.length == 1)
		if (isArray(arguments[0])) return arguments[0][arguments[0].length - 1];
		else return arguments[0];
	if (arguments.length > 1) return arguments[0];
}

let sort = function () {
	if (arguments.length > 2) {
		let func = arguments[2];
		return each.apply(null, arguments).sort((a, b) => func(a) < func(b) ? -1 : func(a) > func(b));
	}
	return each.apply(null, arguments).sort((a, b) => a < b ? -1 : a > b);
};

let distinct = function () {
	if (arguments.length > 2) return each.apply(null, arguments).filter((v, i, a) => arguments[2](v, i, a));
	return each.apply(null, arguments).filter((v, i, a) => a.indexOf(v) === i);
};

let tryParseFloat = function (val) {
	return isEmpty(val) ? 0 : between(parseFloat(val), val);
};

let tryParseInt = function (val) {
	return isEmpty(val) ? 0 : between(parseInt(val), val);
};

let forceParseFloat = function (val, def = 0) {
	return parseFloat(val ?? def) ?? def;
};

let forceParseInt = function (val, def = 0) {
	return parseInt(val ?? def) ?? def;
};

let wait = function (milliseconds) {
	const date = Date.now();
	let currentDate = null;
	do {
		currentDate = Date.now();
	} while (currentDate - date < milliseconds);
};

let copy = function (val) {
	navigator.clipboard.writeText(val);
};
let paste = function () {
	var val = null;
	navigator.clipboard.readText().then(v => val = v);
	return val;
};
let copyFrom = function (id) {
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
let pasteInto = function (id) {
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
let clearText = function (id, focus = false) {
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
let isVisible = (element, partiallyVisible = true) => {
	const { top, left, bottom, right } = element.getBoundingClientRect();
	const { innerHeight, innerWidth } = window;
	return partiallyVisible
		? ((top > 0 && top < innerHeight) ||
			(bottom > 0 && bottom < innerHeight)) &&
		((left > 0 && left < innerWidth) || (right > 0 && right < innerWidth))
		: top >= 0 && left >= 0 && bottom <= innerHeight && right <= innerWidth;
};
let isFocused = (element) => document.activeElement === element;

// Function to generate a unique Path selector for an element
let getPath = (element) => {
	let tagName = element.tagName;
	if (!tagName) return null;
	tagName = tagName.toLowerCase();
	if (tagName === 'html') return 'html';
	if (element.parentElement) return `${getPath(element.parentElement)}>${tagName}:nth-child(${(Array.from(element.parentElement.children).indexOf(element) + 1)})`;
	return tagName;
}
// Function to generate a unique XPath selector for an element
let getXPath = (element) => {
	let tagName = element.tagName;
	if (!tagName) return null;
	tagName = tagName.toLowerCase();
	if (tagName === 'html') return 'html';
	if (element.parentElement) return `${getPath(element.parentElement)}/${tagName}[${(Array.from(element.parentElement.children).indexOf(element))}]`;
	return tagName;
}
// Function to generate a unique CSS selector for an element
let getQuery = (element) => {
	if (element.id) return "#" + element.id;
	let tagName = element.tagName;
	if (!tagName) return null;
	tagName = tagName.toLowerCase();
	if (tagName === 'html') return 'html';
	if (element.parentElement) return `${getPath(element.parentElement)}>${tagName}:nth-child(${(Array.from(element.parentElement.children).indexOf(element) + 1)})`;
	return tagName;
}

let relocate = function (url = null) {
	window.history.pushState(null, null, url ?? location.href);
};
let locate = function (url = null) {
	window.history.replaceState(null, null, url ?? location.href);
};
let reload = function () {
	window.location.reload();
};
let load = function (url = null, target = null) {
	if (target === null) window.location.href = url ?? window.location.href;
	else window.open(url ?? location.href, target === true ? "_blank" : (target === false ? "_self" : target));
};
let share = function (url = null, path = null) {
	open('sms://' + path + '?body=' + (url ?? location.href), '_blank');
};
let message = function (url = null, path = null) {
	open('sms://' + path + '?body=' + (url ?? location.href), '_blank');
};

let mailTo = function (email) {
	open('mailto:' + email, '_blank');
};


let setMemo = function (key, value, expires = 0, path = "/", $secure = false) {
	time = "";
	if (expires) {
		var date = new Date();
		date.setTime(date.getTime() + expires);
		time = "; expires=" + date.toUTCString();
	}
	if ($secure) $secure = "; Secure";
	else $secure = "";
	document.cookie = `${encodeURIComponent(key)}=${encodeURIComponent(value || "")}${time}${$secure}; path=${path}`;
};
let getMemo = function (key) {
	let nameEQ = encodeURIComponent(key) + "=";
	let ca = document.cookie.split(';');
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
	}
	return null;
};
let popMemo = function (key, path = "/") {
	document.cookie = `${encodeURIComponent(key)}=; Max-Age=-99999999; Secure; path=${path}`;
};
let clearMemos = function () {
	let cookies = document.cookie.split(";");
	for (let i = 0; i < cookies.length; i++) {
		let cookie = cookies[i];
		let eqPos = cookie.indexOf("=");
		let key = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
		document.cookie = key + "=; Max-Age=-99999999; Secure; path=/";
	}
};


let send = function (
	method = 'POST',
	url = null,
	data = null,
	selector = 'body+:nth-child(1)',
	success = null,
	error = null,
	ready = null,
	progress = null,
	timeout = null,
	async = true) {

	noEffects = false;
	if (noEffects = !document.querySelector(selector)) selector = 'body';

	const btns = noEffects ? [] : document.querySelectorAll(selector + ' :is(button, [onclick], [ondblclick], input:is([type=button],[type=submit],[type=image],[type=reset]))');
	const elems = noEffects ? [] : document.querySelectorAll(selector);
	const opacity = noEffects ? 1 : document.querySelector(selector).style.opacity;
	url = url ? url : location.href;
	timeout = timeout || 60000;
	method = (method || "POST").toUpperCase();
	let isForm = false;

	let contentType = 'application/x-www-form-urlencoded; charset=utf-8';
	switch (method) {
		case "GET":
		case "POST":
		case "PATCH":
		case "PUT":
		case "DELETE":
		case "OPTIONS":
			break;
		default:
			if (data) {
				if (isForm = data instanceof FormData)
					data.append("__METHOD", method);
				else if (contentType && (typeof data === 'object') || Array.isArray(data))
					data.__METHOD = method;
			}
			else data = { __METHOD: method };
			method = "POST";
			break;
	}

	if (data) {
		if (method === "GET") {
			url += (url.includes('?') ? '&' : '?') + new URLSearchParams(data).toString();
			contentType = false;
			data = null;
		}
		else if (isForm = data instanceof FormData)
			contentType = false;
		else if (contentType && (typeof data === 'object') || Array.isArray(data)) {
			data = JSON.stringify(data);
			contentType = 'application/json; charset=utf-8';
		}
	}

	success = success ?? function (result = null, err = null) {
		if (err) error(result, err);
		else {
			try { document.querySelector(selector + ' .result').remove(); } catch { }
			if (!isEmpty(result)) {
				result += "";
				result = ((typeof (result) === 'object') ? result.statusText : result) ?? 'Form submitted successfully!';

				// If server returned a full HTML document, replace the whole page instead of appending it.
				// This prevents browsers (notably Firefox) from appending full pages into containers
				// and causing "chained pages" behavior.
				if (typeof result === 'string' && /<!doctype\s+html|<\s*html|<\s*body/i.test(result)) {
					document.open();
					document.write(result);
					document.close();
					return;
				}
				if (!isEmpty(result)) {
					if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Struct.result(result);
					if (isForm) _(selector).append(result);
					else _(selector).prepend(result);
				}
			}
		}
	};

	error = error ?? function (result = null, err = null) {
		try { document.querySelector(selector + ' .result').remove(); } catch { }
		if (!isEmpty(result)) {
			result = ((typeof (result) === 'object') ? result.statusText : result) ?? 'There was a problem!';
			if (!isEmpty(result)) {
				result += "";
				if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Struct.result(result);
				if (isForm) _(selector).append(result);
				else _(selector).prepend(result);
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
				result += "";
				try { document.querySelector(selector + ' .result').remove(); } catch { }
				{
					if (!result.match(/class\s*\=\s*("|')[\s\S]*\bresult\b[\s\S]*\1/)) result = Struct.result(result);
					if (isForm) _(selector).append(result);
					else _(selector).prepend(result);
				}
			}
		}
	};

	const xhr = new XMLHttpRequest();
	xhr.open(method, url, async);

	if (contentType) xhr.setRequestHeader('Content-Type', contentType);

	if (async) xhr.timeout = timeout;

	xhr.upload.addEventListener('progress', progress);

	xhr.onload = function () {
		btns.forEach(btn => btn.classList.remove('prevent-events'));
		elems.forEach(elem => elem.style.opacity = opacity);

		if (xhr.status >= 200 && xhr.status < 300) {
			try {
				let response = xhr.response;
				try { response = JSON.parse(response); } catch { }
				success(response);
			} catch (e) {
				error("There was a problem on retrieving data!<br>" + e.message, xhr.status);
			}
		} else {
			error(xhr.response, xhr.status);
		}
	};

	xhr.onerror = function () {
		btns.forEach(btn => btn.classList.remove('prevent-events'));
		elems.forEach(elem => elem.style.opacity = opacity);
		error('Network Error' + (xhr.statusText ? "<br>" + xhr.statusText : ""), xhr.status);
	};

	xhr.ontimeout = function () {
		btns.forEach(btn => btn.classList.remove('prevent-events'));
		elems.forEach(elem => elem.style.opacity = opacity);
		error('Timeout' + (xhr.statusText ? "<br>" + xhr.statusText : ""), xhr.status ?? 'timeout');
	};

	xhr.onloadstart = function () {
		ready(xhr.statusText, xhr.status);
		btns.forEach(btn => btn.classList.add('prevent-events'));
		elems.forEach(elem => elem.style.opacity = '.5');
	};

	xhr.send(data || null);

	return xhr;
};
// Retry wrapper for transient network errors (ERR_NETWORK_CHANGED, Network Error, timeouts)
let sendWithRetry = function (
	method = 'POST',
	url = null,
	data = null,
	selector = 'body+:nth-child(1)',
	success = null,
	error = null,
	ready = null,
	progress = null,
	timeout = null,
	retryOptions = null,
	async = true) {
	const opts = Object.assign({ retries: 3, backoff: 300 }, retryOptions || {});
	let attempt = 0;

	function isTransientError(resp, status) {
		const txt = (typeof resp === 'string') ? resp : (resp && resp.statusText ? resp.statusText : '');
		return status === 0 || status === 'timeout' || /network|ERR_NETWORK_CHANGED/i.test(txt) || /Network Error/i.test(txt);
	}

	function tryOnce(resolve, reject) {
		attempt++;
		const onSuccess = function (res, st) {
			if (success) try { success(res, st); } catch (e) { /* ignore callback errors */ }
			resolve(res);
		};
		const onError = function (res, st) {
			if (isTransientError(res, st) && attempt <= opts.retries) {
				const delay = opts.backoff * Math.pow(2, attempt - 1);
				setTimeout(() => tryOnce(resolve, reject), delay);
			} else {
				if (error) try { error(res, st); } catch (e) { /* ignore callback errors */ }
				reject({ response: res, status: st });
			}
		};

		// call underlying send (keeps original behavior)
		try {
			send(method, url, data, selector, onSuccess, onError, ready, progress, timeout, async);
		} catch (e) {
			// if send itself throws, treat as transient and retry if possible
			if (attempt <= opts.retries) setTimeout(() => tryOnce(resolve, reject), opts.backoff * Math.pow(2, attempt - 1));
			else reject(e);
		}
	}

	// If caller provided callbacks (success/error) we behave callback-style and return undefined
	if (isDefined(success) && success !== null) {
		tryOnce(() => { }, () => { });
		return;
	}

	// Promise-style
	return new Promise((resolve, reject) => tryOnce(resolve, reject));
};
let sendGet = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('GET', url, data, selector, success, error, ready, progress, timeout);
};
let sendPost = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('POST', url, data, selector, success, error, ready, progress, timeout);
};
let sendPut = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('PUT', url, data, selector, success, error, ready, progress, timeout);
};
let sendPatch = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('PATCH', url, data, selector, success, error, ready, progress, timeout);
};
let sendDelete = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('DELETE', url, data, selector, success, error, ready, progress, timeout);
};
let sendFile = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	if (data) return send('POST', url, data, selector, success, error, ready, progress, timeout);
	else {
		input.setAttribute('type', 'file');
		res = null;
		input.onchange = evt => {
			const [file] = input.files;
			if (file) {
				const reader = new FileReader();
				reader.addEventListener('load', (event) => {
					res = send('POST', url, encodeURIComponent(event.target.result), selector, success, error, ready, progress, timeout);
				});
				reader.readAsDataURL(file);
			}
		}
		_(input).trigger('click');
		return res;
	}
};
let sendStream = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('STREAM', url, data, selector, success, error, ready, progress, timeout);
};
let sendInternal = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('INTERNAL', url, data, selector, success, error, ready, progress, timeout);
};
let sendExternal = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return send('EXTERNAL', url, data, selector, success, error, ready, progress, timeout);
};

let sendRequest = function (
	method = 'POST',
	url = null,
	data = null,
	selector = 'body+:nth-child(1)',
	success = null,
	error = null,
	ready = null,
	progress = null,
	timeout = null) {
	return send(method, url, data, selector, success, error, ready, progress, timeout, false);
};
let trySendRequest = function (
	method = 'POST',
	url = null,
	data = null,
	selector = 'body+:nth-child(1)',
	success = null,
	error = null,
	ready = null,
	progress = null,
	timeout = null,
	retryOptions = null) {
	// keep parity with sendRequest which sets async to false by default
	return trySend(method, url, data, selector, success, error, ready, progress, timeout, retryOptions, false);
};
let sendGetRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('GET', url, data, selector, success, error, ready, progress, timeout);
};
let sendPostRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('POST', url, data, selector, success, error, ready, progress, timeout);
};
let sendPutRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('PUT', url, data, selector, success, error, ready, progress, timeout);
};
let sendPatchRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('PATCH', url, data, selector, success, error, ready, progress, timeout);
};
let sendDeleteRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('DELETE', url, data, selector, success, error, ready, progress, timeout);
};
let sendFileRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
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
		_(input).trigger('click');
		return res;
	}
};
let sendStreamRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('STREAM', url, data, selector, success, error, ready, progress, timeout);
};
let sendInternalRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('INTERNAL', url, data, selector, success, error, ready, progress, timeout);
};
let sendExternalRequest = function (url = null, data = null, selector = 'body+:nth-child(1)', success = null, error = null, ready = null, progress = null, timeout = null) {
	return sendRequest('EXTERNAL', url, data, selector, success, error, ready, progress, timeout);
};