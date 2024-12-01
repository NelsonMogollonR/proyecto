// script.js


document.addEventListener("DOMContentLoaded", () => {
    const cartButtons = document.querySelectorAll(".add-to-cart");
    const cartCount = document.getElementById("cart-count");
    const cartItems = [];

    // Cargar productos del carrito desde localStorage
    const savedCartItems = JSON.parse(localStorage.getItem("cartItems"));
    if (savedCartItems) {
        cartItems.push(...savedCartItems);
        updateCartCount();
    }

    cartButtons.forEach(button => {
        button.addEventListener("click", () => {
            const product = button.closest('.card').querySelector('.card-title').textContent;
            cartItems.push(product);
            localStorage.setItem("cartItems", JSON.stringify(cartItems));
            updateCartCount();
            alert(`${product} agregado al carrito`);
        });
    });

    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", (event) => {
            event.preventDefault();
            alert("Gracias por tu mensaje. Nos pondremos en contacto pronto.");
            contactForm.reset();
        });
    }

    function updateCartCount() {
        cartCount.textContent = cartItems.length;
    }
});
// Arreglo para almacenar los usuarios registrados
let usuariosRegistrados = [];

// Función para validar y guardar el nuevo usuario
document.getElementById("registroForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Evita el envío del formulario para validar
    event.stopPropagation();

    const form = event.target;
    if (!form.checkValidity()) {
        form.classList.add("was-validated");
        return;
    }

    // Obtener valores de los campos
    const nombres = document.getElementById("nombres").value;
    const apellidos = document.getElementById("apellidos").value;
    const edad = document.getElementById("edad").value;
    const sexo = document.querySelector("input[name='sexo']:checked").value;
    const tipoDocumento = document.getElementById("tipoDocumento").value;
    const numeroDocumento = document.getElementById("numeroDocumento").value;
    const direccion = document.getElementById("direccion").value;
    const telefono = document.getElementById("telefono").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Verificar que el nombre de usuario o email no estén ya registrados
    const usuarioExistente = usuariosRegistrados.some(
        (usuario) => usuario.username === username || usuario.email === email
    );

    if (usuarioExistente) {
        alert("El nombre de usuario o el correo electrónico ya están registrados.");
        return;
    }

    // Crear un nuevo objeto usuario y agregarlo al arreglo
    const nuevoUsuario = {
        nombres,
        apellidos,
        edad,
        sexo,
        tipoDocumento,
        numeroDocumento,
        direccion,
        telefono,
        email,
        username,
        password
    };

    usuariosRegistrados.push(nuevoUsuario);

    // Confirmación y reseteo del formulario
    alert("Usuario registrado con éxito.");
    form.reset();
    form.classList.remove("was-validated");
    console.log(usuariosRegistrados); // Muestra los usuarios en la consola para verificar
});
