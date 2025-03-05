<?php
header('Content-Type: application/json');
include ("../../lib/jfunciones.php");

$numDocumento = $_POST['numDocumento'];
$tipoDocumento = $_POST['tipoDocumento'];

// Validar que haya ingresado un número de documento
if ($numDocumento == '') {

    // Mensaje de error según el tipo de documento
    if ($tipoDocumento == 'cedula') {
        $mensajeIngresar = "Debe ingresar la cédula";
    } else {
        $mensajeIngresar = "Debe ingresar la cédula o rif";
    }

    // Devolver el error en tablas para seguir el formato del html
    echo json_encode(array(
      "success" => false, 
      "html" => "<td colspan='2' class='mensaje'>$mensajeIngresar</td>"
    ));
    exit();
} else {
    if ($tipoDocumento == 'cedula') {
        $query = "SELECT nombres, apellidos FROM clientes WHERE cedula = '$numDocumento'";
        $mensajeError = "No existe el cliente con la cédula ingresada."; // Para mostrar en caso de no encontrar el cliente
    } elseif ($tipoDocumento == 'rif') {
        $query = "SELECT nombre FROM entes WHERE rif = '$numDocumento'";
        $mensajeError = "No existe el ente con el rif ingresado."; // Para mostrar en caso de no encontrar el ente
    }

    $ejecutarQuery = ejecutar($query);
    $resultadoQuery = num_filas($ejecutarQuery);

    // Si no se encontró el cliente o ente, devolver el mensaje de error
    if ($resultadoQuery == 0) {
        echo json_encode(array(
          "success" => false, 
          "html" => "<td colspan='2' class='mensaje'>$mensajeError</td>"
        ));
        exit();

    } else {
        $fila = asignar_a($ejecutarQuery);
        $nombreCompleto = ($tipoDocumento == 'cedula') 
            ? "$fila[nombres] $fila[apellidos]" 
            : $fila['nombre'];
        // Generar el HTML que incluya la celda del título y la celda con el nombre
        $htmlOutput = "<td colspan='1' class='tdtitulos'>Verificar Contratante: </td>
                       <td colspan='1' class='tdcampos'>$nombreCompleto</td>";
        echo json_encode(array(
            "success" => true,
            "html" => $htmlOutput
        ));
    }
}
?>
