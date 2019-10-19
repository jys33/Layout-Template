document.addEventListener("DOMContentLoaded", () => {
	
	// Back to Top - by CodyHouse.co
	var backTop = document.getElementsByClassName('js-cd-top')[0],
	    // browser window scroll (in pixels) after which the "back to top" link is shown
	    offset = 20,
	    //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
	    offsetOpacity = 1200,
	    scrollDuration = 600,
	    scrolling = false;
	if( backTop ) {
	    //update back to top visibility on scrolling
	    window.addEventListener("scroll", function(event) {
	        if( !scrolling ) {
	            scrolling = true;
	            (!window.requestAnimationFrame) ? setTimeout(checkBackToTop, 250) : window.requestAnimationFrame(checkBackToTop);
	        }
	    });
	    //smooth scroll to top
	    backTop.addEventListener('click', function(event) {
	        event.preventDefault();
	        (!window.requestAnimationFrame) ? window.scrollTo(0, 0) : scrollTop(scrollDuration);
	    });
	}

	function checkBackToTop() {
	    var windowTop = window.scrollY || document.documentElement.scrollTop;
	    ( windowTop > offset ) ? addClass(backTop, 'cd-top--show') : removeClass(backTop, 'cd-top--show', 'cd-top--fade-out');
	    ( windowTop > offsetOpacity ) && addClass(backTop, 'cd-top--fade-out');
	    scrolling = false;
	}
	
	function scrollTop(duration) {
	    var start = window.scrollY || document.documentElement.scrollTop,
	        currentTime = null;
	        
	    var animateScroll = function(timestamp){
	        if (!currentTime) currentTime = timestamp;        
	        var progress = timestamp - currentTime;
	        var val = Math.max(Math.easeInOutQuad(progress, start, -start, duration), 0);
	        window.scrollTo(0, val);
	        if(progress < duration) {
	            window.requestAnimationFrame(animateScroll);
	        }
	    };

	    window.requestAnimationFrame(animateScroll);
	}

	Math.easeInOutQuad = function (t, b, c, d) {
	    t /= d/2;
	    if (t < 1) return c/2*t*t + b;
	    t--;
	    return -c/2 * (t*(t-2) - 1) + b;
	};

	//class manipulations - needed if classList is not supported
	function hasClass(el, className) {
	    if (el.classList) return el.classList.contains(className);
	    else return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
	}
	function addClass(el, className) {
	    var classList = className.split(' ');
	    if (el.classList) el.classList.add(classList[0]);
	    else if (!hasClass(el, classList[0])) el.className += " " + classList[0];
	    if (classList.length > 1) addClass(el, classList.slice(1).join(' '));
	}
	function removeClass(el, className) {
	    var classList = className.split(' ');
	    if (el.classList) el.classList.remove(classList[0]);    
	    else if(hasClass(el, classList[0])) {
	        var reg = new RegExp('(\\s|^)' + classList[0] + '(\\s|$)');
	        el.className=el.className.replace(reg, ' ');
	    }
	    if (classList.length > 1) removeClass(el, classList.slice(1).join(' '));
	}
	// End Back to Top
	

    checkTheme();
	
});

function checkTheme() {
	if(!localStorage.theme){
		localStorage.theme = '';
	}

	querySelector('body').setAttribute('class', localStorage.theme);
	if(querySelector('body').classList.contains('dark')){
		querySelector('.theme-icon').setAttribute('src', sunIcon);
		querySelector('.theme-icon').setAttribute('alt', 'Light Mode');

		querySelector('.navbar').classList.add('navbar-dark');
		querySelector('.navbar').classList.add('dark');
		var labels = document.getElementsByTagName('label');
		for(var i = 0; i < labels.length; i++){
			labels[i].classList.add('dark');
		}
	}

	// opcion button
	querySelector('.dark-switcher').onclick = darkLight;
}

function darkLight() {
	var icon, alt;
	if(localStorage.theme != 'dark'){
		localStorage.theme = 'dark';
		icon = sunIcon;
		alt = 'Light Mode';
		querySelector('.navbar').classList.add('navbar-dark');
		querySelector('.navbar').classList.add('dark');
		var labels = document.getElementsByTagName('label');
		for(var i = 0; i < labels.length; i++){
			labels[i].classList.add('dark');
		}
	} else {
		icon = moonIcon;
		alt = 'Dark Mode';
		localStorage.theme = '';
		querySelector('.navbar').classList.remove('dark');
		var labels = document.getElementsByTagName('label');
		for(var i = 0; i < labels.length; i++){
			labels[i].classList.remove('dark');
		}
	}
	querySelector('.theme-icon').setAttribute('alt', alt);
	querySelector('.theme-icon').setAttribute('src', icon);
	querySelector('body').setAttribute('class', localStorage.theme);
}

function allow(elEvento, permitidos) {
    // Variables que definen los caracteres permitidos
    var numeros = '0123456789';
    var caracteres = ' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    var numeros_caracteres = numeros + caracteres;
    var teclas_especiales = [8, 37, 39, 46];
    // 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha

    // Seleccionar los caracteres a partir del parámetro de la función
    switch (permitidos) {
        case 'num':
            permitidos = numeros;
            break;
        case 'car':
            permitidos = caracteres;
            break;
        case 'num_car':
            permitidos = numeros_caracteres;
            break;
    }

    // Obtener la tecla pulsada
    var evento = elEvento || window.event;
    var codigoCaracter = evento.charCode || evento.keyCode;
    var caracter = String.fromCharCode(codigoCaracter);

    // Comprobar si la tecla pulsada es alguna de las teclas especiales
    // (teclas de borrado y flechas horizontales)
    var tecla_especial = false;
    for (var i in teclas_especiales) {
        if (codigoCaracter == teclas_especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    // Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
    // o si es una tecla especial
    return permitidos.indexOf(caracter) != -1 || tecla_especial;
}