// Contact Form Scripts

$(function() {

    $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var name = $("input#name").val();
            var phone = $("input#phone").val();
            var ciudad = $("input#ciudad").val();
            var message = $("textarea#message").val();
            var firstName = name; // For Success/Failure Message
            // Check for white space in name for Success/Fail message
            if (firstName.indexOf(' ') >= 0) {
                firstName = name.split(' ').slice(0, -1).join(' ');
            }
            // Deshabilitar botón durante el envío
            var $submitBtn = $('button[type="submit"]');
            var originalBtnText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

            $.ajax({
                url: "./mail/contact_me.php",
                type: "POST",
                data: {
                    name: name,
                    phone: phone,
                    ciudad: ciudad,
                    message: message
                },
                cache: false,
                dataType: 'json',
                success: function(response) {
                    // Restaurar botón
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    if (response.success) {
                        // Success message
                        $('#success').html("<div class='alert alert-success'>");
                        $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#success > .alert-success')
                            .append("<strong>Su mensaje fue enviado con éxito. En breve nos comunicaremos contigo.</strong>");
                        $('#success > .alert-success')
                            .append('</div>');

                        //clear all fields
                        $('#contactForm').trigger("reset");
                    } else {
                        // Error message from server
                        $('#success').html("<div class='alert alert-danger'>");
                        $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#success > .alert-danger').append("<strong>" + (response.message || "Error al enviar el mensaje. Por favor, inténtelo de nuevo más tarde.") + "</strong>");
                        $('#success > .alert-danger').append('</div>');
                    }
                },
                error: function(xhr, status, error) {
                    // Restaurar botón
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    // Fail message
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    
                    var errorMsg = "Lo sentimos " + firstName + ", el servidor no responde. Por favor, inténtelo de nuevo más tarde.";
                    
                    // Intentar parsear respuesta JSON si existe
                    if (xhr.responseText) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch(e) {
                            // Si no es JSON, usar mensaje por defecto
                        }
                    }
                    
                    $('#success > .alert-danger').append("<strong>" + errorMsg + "</strong>");
                    $('#success > .alert-danger').append('</div>');
                },
            });
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});

