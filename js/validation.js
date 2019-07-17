document.addEventListener("DOMContentLoaded", () => {
    let firstName = document.querySelector("#firstName");
    let lastName = document.querySelector("#lastName");
    let email = document.querySelector("#email");
    let password = document.querySelector("#password");
    let confirmPassword = document.querySelector('#confirmPassword');

    let form = document.querySelector(".myForm");

    function validatePassword() {
        // Empty check
        if (checkIfEmpty(password)) return;
        // Must of in certain length
        if (!meetLength(password, 8, 50)) return;
        // check password against our character set
        // 1- a
        // 2- a 1
        // 3- A a 1
        // 4- A a 1 @
        if (!containsCharacters(password, 4)) return;
        // Devolvemos el tipo de campo
        password.name = "password";
        return true;
    }
    function validateConfirmPassword() {
        if (!password.classList.contains('is-valid')) {
            setInvalid(confirmPassword, 'La contraseña debe ser válida.');
            return;
        }
        // If they match
        if (password.value !== confirmPassword.value) {
            setInvalid(confirmPassword, 'Las contraseñas no coinciden.');
            return;
        } else {
            setValid(confirmPassword);
        }
        return true;
    }
    function validateFirstname(){
        if (checkIfEmpty(firstName)) return;
        if (!checkIfOnlyLetters(firstName)) return;
        if (!meetLength(firstName, 3, 50)) return;
        return true;
    }
    function validateLastname(){
        if (checkIfEmpty(lastName)) return;
        if (!checkIfOnlyLetters(lastName)) return;
        if (!meetLength(lastName, 3, 50)) return;
        return true;
    }
    function validateEmail() {
        if (checkIfEmpty(email)) return;
        if (!containsCharacters(email, 5)) return;
        return true;
    }
    // Utility functions
    function checkIfEmpty(field) {
        if (isEmpty(field.value.trim())) {
            // set field invalid
            if(field.name == "password" || field.name == "contraseña"){
                setInvalid(field, `Crea una contraseña.`);
            } else {
                setInvalid(field, `Por favor dinos tu ${field.name}.`);
            }
            return true;
        } else {
            // set field valid
            setValid(field);
            return false;
        }
    }
    function checkIfOnlyLetters(field) {
        if (/^[a-zA-ZñÑ ]+$/.test(field.value)) {
            setValid(field);
            return true;
        } else {
            setInvalid(field, `El ${field.name} no puede incluir símbolos, números o signos de puntuación.`);
            return false;
        }
    }
    function meetLength(field, minLength, maxLength) {
        if (field.name == "password") {
            field.name = "contraseña";
        }
        if (field.value.length >= minLength && field.value.length < maxLength) {
            setValid(field);
            return true;
        } else if (field.value.length < minLength) {
            setInvalid(
                field,
                `El campo ${field.name} debe incluir al menos ${minLength} caracteres de longitud.`
            );
            return false;
        } else {
            setInvalid(
                field,
                `El campo ${field.name} debe incluir como máximo ${maxLength} caracteres de longitud.`
            );
            return false;
        }
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
                regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return matchWithRegEx(regEx, field, 'El email no es válido.');
            default:
                return false;
        }
    }

    function matchWithRegEx(regEx, field, message) {
        if (field.value.match(regEx)) {
            setValid(field);
            return true;
        } else {
            setInvalid(field, message);
            return false;
        }
    }
    function isEmpty(value) {
        if (value == null || value.length == 0 || /^\s+$/.test(value)) return true;
        return false;
    }
    function setInvalid(field, message) {
        field.classList.remove('is-valid');
        field.classList.add("is-invalid");
        field.nextElementSibling.innerHTML = message;
    }
    function setValid(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        field.nextElementSibling.innerHTML = '';
    }
    firstName.addEventListener("focusout", validateFirstname);
    lastName.addEventListener("focusout", validateLastname);
    email.addEventListener("focusout", validateEmail);
    password.addEventListener("focusout", validatePassword);
    confirmPassword.addEventListener("focusout", validateConfirmPassword);

    confirmPassword.addEventListener("input", validateConfirmPassword);
    
    // firstName.addEventListener("input", validateFirstname);
    // lastName.addEventListener("input", validateLastname);
    // email.addEventListener("input", validateEmail);
    // password.addEventListener("input", validatePassword);
    form.onsubmit = (e) => {
        if(!(
            validateEmail() && 
            validateFirstname() && 
            validateLastname() && 
            validatePassword() &&
            validateConfirmPassword())
            ){
            e.preventDefault();
        }
    }
});

function permite(elEvento, permitidos) {
    // Variables que definen los caracteres permitidos
    var numeros = "0123456789";
    var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
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