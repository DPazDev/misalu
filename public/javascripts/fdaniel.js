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

function reporte_siniestralidad_contratantes(){
  var contenedor;
  contenedor = document.getElementById('clientes');
  ajax=nuevoAjax();
  ajax.open("GET", "views06/reporte_siniestro_contratantes.php",true);
  ajax.onreadystatechange=function() {
    if (ajax.readyState==4) {
      contenedor.innerHTML = ajax.responseText
    }
  }
  ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  ajax.send(null)
}

function verContratante2(opcion) {

  // Limpiar el contenedor de verificación (para que no se muestre el mensaje anterior)
  $("verificacionDocumento").update("");

  new Ajax.Updater("contenidocontratante2", "views06/contenidocontratante2.php", {
    parameters: {
      opcion: opcion,
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

function verificarDocumento2(numDocumento, tipoDocumento) {


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

function tipos_fechas (tipoFecha) {
  // 0 = Todas las fechas
  // 1 = Rango de fechas

  new Ajax.Updater("contenidoFechas", "views06/contenido_fechas.php", {
    parameters: {
      tipoFecha: tipoFecha,
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

function imp_siniestro_contratantes(){
  
  let tipoContratante = $("contratanteSelect").value; // 2 = Ente, 3 = Persona
  let cedulaContratante = $("cedula_contratante")?.value.trim(); // Obtenido de contenidocontratante2.php

  let tipoServicio = $("servicio").value; // 0 = Todos los servicios
  let nombreTipoServicio = document.getElementById('servicio').options[document.getElementById('servicio').selectedIndex].text;

  let estadoProceso = $("proceso").value; // 0 = Todos los estados
  let nombreEstadoProceso = document.getElementById('proceso').options[document.getElementById('proceso').selectedIndex].text;

  let tipoCliente = $("tipo_cliente").value; // 0 = Todos los clientes, 1 = Titulares, 2 = Beneficiarios

  let fechaSeleccionada = $("fecha_seleccionada")?.value.trim(); // 0 = Todas las fechas, 1 = Rango de fechas
  let fechaInicio = "";
  let fechaFin = "";

  if (fechaSeleccionada == 1) {
    fechaInicio = $("dateField1").value;
    fechaFin = $("dateField2").value;

    if (fechaInicio == "" || fechaFin == "") {
      alert("Debe seleccionar un rango de fechas");
      return;
    }
  }

  if (!cedulaContratante || !tipoServicio || !estadoProceso || !tipoCliente || !fechaSeleccionada || !tipoContratante) {
    alert("Debe completar todos los campos.");
    return;
  }

  let url = 'views06/imp_reporte_siniestro_contratantes.php?cedulaContratante=' + cedulaContratante + 
  '&tipoServicio=' + tipoServicio + 
  '&estadoProceso=' + estadoProceso + 
  '&tipoCliente=' + tipoCliente + 
  '&fechaSeleccionada=' + fechaSeleccionada + 
  '&tipoContratante=' + tipoContratante +
  '&nombreTipoServicio=' + encodeURIComponent(nombreTipoServicio) +
  '&nombreEstadoProceso=' + encodeURIComponent(nombreEstadoProceso);

  // Si fechaSeleccionada es 1, agregar las fechas al URL
  if (fechaSeleccionada == 1) {
    url += '&fechaInicio=' + encodeURIComponent(fechaInicio) + '&fechaFin=' + encodeURIComponent(fechaFin);
  }
  
  imprimir(url);
}