function verContratante(opcion, cedulaTitular) {

  // Limpiar el contenedor de verificación (para que no se muestre el mensaje anterior)
  $("verificacionDocumento").update("");

  new Ajax.Updater("contenidocontratante", "views01/contenidocontratante.php", {
    parameters: {
      opcion: opcion,
      cedulaTitular: cedulaTitular,
    },
    onCreate: function () {
      // Muestra el spinner
      $("spinnerP1").show();
    },
    onComplete: function () {
      // Oculta el spinner al finalizar la carga
      $("spinnerP1").hide();
    },
  });
}

function verificarDocumento(numDocumento, tipoDocumento, titular, opcion) {
  if (numDocumento == titular && opcion != "1") {
    alert("Si el contratante es el mismo titular, seleccione titular en la opción Contratante.");
    document.getElementById("cedula_contratante").value = "";
    return;
  }

  new Ajax.Request("views01/verificar_documento.php", {
    parameters: { 
      numDocumento: numDocumento, 
      tipoDocumento: tipoDocumento 
    },
    onCreate: function () {
      $("spinnerP1").show();
    },
    onSuccess: function(response) {
      $("spinnerP1").hide();
      try {
        let result = response.responseText.evalJSON();
        // Actualizamos el contenedor con el HTML completo
        $("verificacionDocumento").update(result.html);
        if (!result.success) {
          // Si hay error, limpiamos el input
          document.getElementById("cedula_contratante").value = "";
        }
      } catch (e) {
        console.error("Error al procesar la respuesta JSON: ", e);
      }
    },
    onFailure: function() {
      $("spinnerP1").hide();
      console.error("Error en la solicitud AJAX");
    }
  });
}

