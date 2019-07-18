document.addEventListener("DOMContentLoaded", () => {
	// Ejemplo 1: Traductor
	let headingEl = document.querySelector('#translate');
	let temp = headingEl.textContent;
	let btnEl = document.querySelector('.translate-btn');

	function translateTitle(event) {
	    //console.log(this);
	    if (headingEl.textContent == 'Usuarios Registrados') {
	        headingEl.textContent = temp;
	    } else {
	       headingEl.textContent = 'Usuarios Registrados'; 
	    }
	}

	btnEl.onclick = translateTitle;

	// Ejemplo 2: Validación de formulario
	let form = document.querySelector('#registration');

	function isValidate(){
	    if (!form.lastname.value) {
	        alert('Por favor dinos tu apellido.');
	        return false;
	    }
	    else if (!form.firstname.value) {
	        alert('Por favor dinos tu nombre.');
	        return false;
	    }
	    else if (!form.email.value) {
	        alert('Por favor dinos tu email.');
	        return false;
	    }
	    else if (!form.password.value) {
	        alert('Por favor crea una contraseña.');
	        return false;
	    }
	    else if (form.password.value != form.confirmPassword.value)
	    {
	        alert("Las contraseñas no coinciden.");
	        return false;
	    }
	    return true;
	}
	function testInput(event){
	    if (!isValidate()) {
	        event.preventDefault();
	    }
	}
	//form.onsubmit = testInput;

	// Ejemplo 3: Conteo regresivo

	// Step 1. What element do we want to animate?
	var countdown = document.getElementById("countdown");
	// Step 2. What function will change it each time?
	var countItDown = function() {
	    var currentTime = parseFloat(countdown.textContent);
	    if (currentTime > 0) {
	        countdown.textContent = currentTime - 1;   
	    } else {
	        window.clearInterval(timer);
	        countdown.parentNode.textContent = "Kaboommmm!";
	    }
	};
	// Step 3: Call that on an interval
	var timer = window.setInterval(countItDown, 1000);

	// Ejemplo 4: Show/Hide Password
	let showPassword = document.querySelector('#show-password');
	let input = document.getElementById("Password");
	function showHide() {
	    if (input.type === "password") {
	        input.type = "text";
	        showPassword.textContent = "Ocultar";
	    } else {
	        input.type = "password";
	        showPassword.textContent = "Mostrar";
	    }
	}
	function checkInput(){
	    if(input.value.length > 0){
	        showPassword.style.display = "block";
	    } else {
	        showPassword.style.display = "none";
	    }
	}
	
	input.onfocus = checkInput;
	input.oninput = checkInput;
	showPassword.onclick = showHide;


	// objeto window
	console.log(window.location.href);
	console.log(window.outerWidth + 'px x ' + window.outerHeight + 'px');
})