let scrollThere = function (selector = "head", time = 1000) {
	$('html, body').animate({
		scrollTop: parseInt($(selector).offset().top)
	}, time);
};

let submitForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	let form = null;
	if (isString(selector)) form = document.querySelector(selector);
	else {
		form = selector;
		selector = getQuery(form);
	}
	if (!form) return null;
	let fd = new FormData(form);
	let sb = form.querySelector("[type='submit']");
	if (sb) {
		fd.append(sb.name, sb.value);
		return send(sb.getAttribute("method") ?? form.getAttribute("method"), sb.getAttribute("action") ?? form.getAttribute("action"), fd, selector, success, error, ready, progress, timeout);
	}
	return send(form.getAttribute("method"), form.getAttribute("action"), new FormData(form), selector, success, error, ready, progress, timeout);
};

let handleForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	let form = null;
	if (isString(selector)) form = document.querySelector(selector);
	else {
		form = selector;
		selector = getQuery(form);
	}

	let ClickedFormButton = null;
	form.querySelectorAll("[type='submit']")?.forEach(button =>
		button.addEventListener("click", (e) =>
			ClickedFormButton = e.target
		)
	);

	if (form) form.onsubmit = function (e) {
		e.preventDefault();
		let fd = new FormData(form);
		let sb = ClickedFormButton ?? form.querySelector("[type='submit']");
		if (sb) {
			fd.append(sb.name, sb.value);
			return send(sb.getAttribute("method") ?? form.getAttribute("method"), sb.getAttribute("action") ?? form.getAttribute("action"), fd, selector, success, error, ready, progress, timeout);
		}
		return send(form.getAttribute("method"), form.getAttribute("action"), fd, selector, success, error, ready, progress, timeout);
	}
};