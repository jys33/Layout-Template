'use strict';

function getElementById(el) {
	return document.getElementById(el);
}

function querySelector(el) {
	return document.querySelector(el);
}

function disabledBtn() {
	getElementById('btn').setAttribute('disabled', true);
}
