let scrollThere = function (selector = "head", time = 1000) {
	$('html, body').animate({
		scrollTop: parseInt($(selector).offset().top)
	}, time);
};

let submitForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	form = null;
	if(isString(selector)) form = document.querySelector(selector);
	else {
		form = selector;
		selector = 'form';
	}
	if (!form) return null;
	return sendRequest(form.getAttribute('method'), form.getAttribute('action'), new FormData(form), selector, success, error, ready, progress, timeout);
};
let handleForm = function (selector = 'form', success = null, error = null, ready = null, progress = null, timeout = null) {
	form = null;
	if(isString(selector)) form = document.querySelector(selector);
	else {
		form = selector;
		selector = 'form';
	}
	if (form) form.onsubmit = function (e) {
		e.preventDefault();
		return sendRequest(form.getAttribute('method'), form.getAttribute('action'), new FormData(form), selector, success, error, ready, progress, timeout);
	};
};