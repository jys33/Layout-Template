document.addEventListener('DOMContentLoaded', () => {
    
    // if (document.querySelector('.alert')) {
    //     var s = document.querySelector('.alert').style;
    //     s.opacity = 7;
    //     (function fade(){(s.opacity-=.1)<0?s.display="none":setTimeout(fade,80)})();
    // }

    const form = document.querySelector('.needs-validation');
    if (form) {
        const email = form.email;
        const usuario = form.usuario;
        const nombre = form.nombre;
        const apellido = form.apellido;
        const password = form.password;
        const confirm_password = form.confirm_password;

        // Si el formulario es de registro
        if (email && usuario && nombre && apellido && password && confirm_password) {
            nombre.onpaste = evt => false;
            nombre.ondragover = evt => false;
            apellido.onpaste = evt => false;
            apellido.ondragover = evt => false;

            nombre.addEventListener('focusout', checkFirstName);
            apellido.addEventListener('focusout', checkLastName);
            usuario.addEventListener('focusout', checkUsername);
            email.addEventListener('focusout', checkEmail);
            password.addEventListener('focusout', checkPassword);
            confirm_password.addEventListener('focusout', checkConfirmPassword);
            confirm_password.addEventListener('input', checkConfirmPassword);

            form.onsubmit = (evt) => {
                if (
                    !(
                        checkFirstName() &&
                        checkLastName() &&
                        checkUsername() &&
                        checkEmail() &&
                        checkPassword() &&
                        checkConfirmPassword()
                        )
                    ) {
                    inputFocus();
                    evt.preventDefault();
                }
            }
        // Si el formulario es de login
        } else if (email && password) {
            email.addEventListener('input', validateEmail);
            // email.addEventListener('focusout', validateEmail);
            password.addEventListener('input', validatePassword);
            // password.addEventListener('focusout', validatePassword);

            form.onsubmit = (evt) => {
                if( !(validateEmail() && validatePassword()) ){
                    inputFocus();
                    evt.preventDefault();
                }
            }
        // Si el formulario es de forgot contraseña
        } else if (email) {
            email.addEventListener('input', validateEmail);
            email.addEventListener('focusout', validateEmail);

            form.onsubmit = (evt) => {
                if(!validateEmail()){
                    inputFocus();
                    evt.preventDefault();
                }
            }

        // Si el formulario es de reset contraseña
        } else if (password && confirm_password){
            password.addEventListener('focusout', checkPassword);
            confirm_password.addEventListener('focusout', checkConfirmPassword);
            confirm_password.addEventListener('input', checkConfirmPassword);
            form.onsubmit = (evt) => {
                if( !(checkConfirmPassword() && checkPassword()) ){
                    inputFocus();
                    evt.preventDefault();
                }
            }
        }

        function validateEmail(){
            if(checkInput(email)) return;
            if(!containsCharacters(email,5)) return;
            return true;
        }
        
        function validatePassword(){
            if(checkInput(password)) return;
            return true;
        }

        function inputFocus(){
            let length = form.elements.length;
            for(let i = 0; i < length; i++){
                if (form.elements[i].classList.contains('is-invalid')) {
                    form.elements[i].focus();
                    break;
                }
                // if (form.elements[i].classList.contains('invalid')) {
                //     form.elements[i].focus();
                //     break;
                // }
            }
        }

        function checkEmail(){
            if(checkIsEmpty(email)) return;
            if(!containsCharacters(email,5)) return;
            return true;
        }

        function checkUsername(){
            if(checkIsEmpty(usuario)) return;
            if (!meetLength(usuario, 2, 20)) return;
            if (!containsCharacters(usuario, 6)) return;
            return true;
        }

        function checkFirstName() {
            if(checkIsEmpty(nombre)) return;
            if (!lettersOnly(nombre)) return;
            if(!meetLength(nombre, 3, 32)) return;
            return true;
        }
        
        function checkLastName() {
            if(checkIsEmpty(apellido)) return;
            if (!lettersOnly(apellido)) return;
            if(!meetLength(apellido, 3, 32)) return;
            return true;
        }

        function checkPassword(){
            if(checkIsEmpty(password)) return;
            if (!meetLength(password, 8, 32)) return;
            if (!containsCharacters(password, 4)) return;
            return true;
        }

        function checkConfirmPassword(){
            // if (!password.classList.contains('valid')) {
            //     setInvalid(confirm_password, 'La contraseña debe ser válida.');
            //     return;
            // }
            if (password.classList.contains('is-invalid')) {
                setInvalid(confirm_password, 'La contraseña debe ser válida.');
                return;
            }
            // If they match
            if (password.value !== confirm_password.value) {
                setInvalid(confirm_password, 'Las contraseñas no coinciden.');
                return;
            } else {
                setValid(confirm_password,'');
            }
            return true;
        }

        function allowSubmission() {
            if (canSubmit(password, confirm_password)) {
                form.send_btn.removeAttribute('disabled');
            } else {
                form.send_btn.setAttribute("disabled", "disabled");
            }
        }

        // Funciones Útiles
        function checkIsEmpty(field){
            if (isEmpty(field.value.trim())) {
                if (field.type == 'password') {
                    setInvalid(field,'Crea una contraseña.');
                } else {
                    setInvalid(field,`El ${field.name} es requerido.`);
                }
                return true;
            } else {
                setValid(field,'');
                return false;
            }
        }

        function checkInput(field){
            if (isEmpty(field.value.trim())) {
                setInvalid(field, 'Completa este campo.');
                return true;
            } else {
                setValid(field,'');
                return false;
            }
        }

        function canSubmit(pass1, pass2){
            return (pass1.value == pass2.value);
        }

        function meetLength(field, min, max){
            if (field.value.length >= min && field.value.length <= max) {
                setValid(field,'');
                return true;
            } else if(field.value.length < min){//'This name is too short (at least 3 chars.)'
                if (field.type == 'password') {
                    setInvalid(field, 'La contraseña debe incluir al menos ' + min + ' caracteres.');
                } else {
                    setInvalid(field, `El ${field.name} debe incluir al menos ` + min + ` caracteres.`);
                }
                return false;
            } else{//This name is too long (at most 32 chars.)
                if (field.type == 'password') {
                    setInvalid(field, 'La contraseña debe incluir como máximo ' + max + ' caracteres.');
                } else {
                    setInvalid(field, `El ${field.name} debe incluir como máximo ` + max + ` caracteres.`);
                }
                return false;
            }
        }

        function lettersOnly(field){
            if (/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]+$/.test(field.value)) {
                setValid(field, '');
                return true;
            } else {
                setInvalid(field, `El ${field.name} no puede incluir símbolos, números o signos de puntuación.`);
                return false;
            }
        }

        function numbersOnly(evt) {
            return evt.charCode === 0 || /\d/.test(String.fromCharCode(evt.charCode));
        }

        function isEmpty(value){
            if (value == null || value.length == 0 || /^\s+$/.test(value)) return true;
            return false;
        }

        function setInvalid(field, message){
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            field.nextElementSibling.innerHTML = message;

            // field.className = 'invalid';
            // field.nextElementSibling.innerHTML = message;
            // field.nextElementSibling.style.color = 'red';
        }

        function setValid(field, message){
            //field.classList.add('is-valid');
            field.classList.remove('is-invalid');
            field.nextElementSibling.innerHTML = message;

            // field.className = 'valid';
            // field.nextElementSibling.innerHTML = message;
            //field.nextElementSibling.style.color = green;
        }
        
        function containsCharacters(field, code) {
            let regEx;
            switch (code) {
                case 1:
                    // letters
                    regEx = /(?=.*[a-zA-Z])/;
                    return matchWithRegEx(regEx, field, 'Must contain at least one letter');
                case 2:
                    // letter and numbers
                    regEx = /(?=.*\d)(?=.*[a-zA-Z])/;
                    return matchWithRegEx(
                        regEx,
                        field,
                        'Must contain at least one letter and one number'
                    );
                case 3:
                    // uppercase, lowercase and number
                    regEx = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/;
                    return matchWithRegEx(
                        regEx,
                        field,
                        'Must contain at least one uppercase, one lowercase letter and one number'
                    );
                case 4:
                    // uppercase, lowercase, number and special char
                    regEx = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)/;
                    return matchWithRegEx(
                        regEx,
                        field,
                        'La contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un carácter especial.'
                    );
                case 5:
                    // Email pattern
                    // Según la especificación HTML5
                    // regEx = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
                    regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return matchWithRegEx(regEx, field, 'El email no es válido.');
                case 6:
                    // pattern
                    regEx = /^[a-zA-Z@]+\d*$/;
                    return matchWithRegEx(
                        regEx,
                        field,
                        'Los nombres de usuario no pueden contener espacios y no deben empezar por un número o subrayado.');
                default:
                    return false;
            }
        }

        function matchWithRegEx(regEx, field, message) {
            if (field.value.match(regEx)) {
                setValid(field, '');
                return true;
            } else {
                setInvalid(field, message);
                return false;
            }
        }
    }
});

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