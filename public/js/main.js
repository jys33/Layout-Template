document.addEventListener("DOMContentLoaded", () => {
	// Ejemplo 1: Traductor
	// let headingEl = document.querySelector('#translate');
	// let temp = headingEl.textContent;
	// let btnEl = document.querySelector('.translate-btn');

	// function translateTitle(event) {
	//     //console.log(this);
	//     if (headingEl.textContent == 'Usuarios Registrados') {
	//         headingEl.textContent = temp;
	//     } else {
	//        headingEl.textContent = 'Usuarios Registrados'; 
	//     }
	// }

	// btnEl.onclick = translateTitle;

	// Ejemplo 4: Show/Hide Password
	let showPassword = document.querySelector('#show-password');
	let input = document.getElementById("password");
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

	if (input) {
		if (input.classList.contains('is-invalid')) {
			document.getElementById("show-password").style.borderBottom = '1px solid #dc3545';
		}
		input.onfocus = checkInput;
		input.oninput = checkInput;
		showPassword.onclick = showHide;
	}
});