/* document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginFormAdmin");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(loginForm);

            fetch("../php/login-admin.php", { // Enviar datos al script específico de administradores
                method: "POST",
                body: formData
            })
            .then(response => response.json()) // Parsear la respuesta como JSON
            .then(data => {
                if (data.success) {
                    // Inicio de sesión exitoso
                    Swal.fire("Éxito", data.message, "success").then(() => {
                        window.location.href = "../html/Administrador/Admin.html"; // Redirige a la página de administración (ajusta el nombre si es necesario)
                    });
                } else {
                    // Muestra un mensaje de error si no se encontró el usuario o si la contraseña es incorrecta
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error en la solicitud:", error);
                Swal.fire("Error", "Ocurrió un error en la autenticación. Inténtalo de nuevo.", "error");
            });
        });
    }
});*/

$(document).ready(function(){
    $('select').formSelect();
  });

  $(document).ready(function(){
    $('.modal').modal();
  });

  $(document).ready(function(){
    // Initialize DataTables
    $('#example').DataTable({
        "paging": true,
        "searching": true,
        "order": [[0, "asc"]], // Order by first column
        "responsive": true
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems, options);
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.tooltipped').tooltip();
  });$(document).ready(function(){
    // Initialize DataTables
    $('#example').DataTable({
        "paging": true,
        "searching": true,
        "order": [[0, "asc"]], // Order by first column
        "responsive": true
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems, options);
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.tooltipped').tooltip();
  });


  $(document).ready(function(){
    $('.collapsible').collapsible();
  });

  $(document).ready(function(){
    $('.sidenav').sidenav();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems, {
      hover: true // Activa el dropdown con hover
    });
  });
