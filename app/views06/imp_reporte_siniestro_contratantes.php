<?php


include ("../../lib/jfunciones.php");

$tipoContratante = $_GET['tipoContratante']; // 2 = Ente, 3 = Persona
$cedulaContratante = $_GET['cedulaContratante'];
$tipoServicio = $_GET['tipoServicio']; // 0 = Todos los servicios
$nombreTipoServicio = $_GET['nombreTipoServicio'];
$estadoProceso = $_GET['estadoProceso']; // 0 = Todos los estados
$nombreEstadoProceso = $_GET['nombreEstadoProceso'];
$tipoCliente = $_GET['tipoCliente']; // 0 = Todos los clientes, 1 = Titulares, 2 = Beneficiarios
$fechaSeleccionada = $_GET['fechaSeleccionada']; // 0 = Todas las fechas, 1 = Rango de fechas

if ($fechaSeleccionada == '1') {
    $fechaInicio = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];
}


/////////////////////////////////////////////////////////////////
///////////// Preparar los valores para la consulta /////////////
/////////////////////////////////////////////////////////////////

$filtroEstadoProceso = ($estadoProceso == '0') ? '' : "AND p.id_estado_proceso = $estadoProceso";
$filtroTipoServicio = ($tipoServicio == '0') ? '' : "AND g.id_servicio = $tipoServicio";

if ($tipoContratante == '2') {
    $filtroTipoContratante = "AND rc.tipo_contratante = 2"; // Ente
} elseif ($tipoContratante == '3') {
    $filtroTipoContratante = "AND rc.tipo_contratante IN (1, 3)"; // 1 TITULAR Y 3 PERSONA
} else {
    $filtroTipoContratante = ''; // En caso de un valor inesperado, no aplicar filtro
}

// Filtro de tipo de cliente
if ($tipoCliente == '0') {
    $filtroTipoCliente = ''; // No aplicar filtro
} elseif ($tipoCliente == '1') {
    $filtroTipoCliente = "AND p.id_beneficiario = 0"; // Titulares
} elseif ($tipoCliente == '2') {
    $filtroTipoCliente = "AND p.id_beneficiario > 0"; // Beneficiarios
} else {
    $filtroTipoCliente = ''; // En caso de un valor inesperado, no aplicar filtro
}

// Filtro de fecha
if ($fechaSeleccionada == '0') {
  $filtroFecha = ''; // No aplicar filtro
} elseif ($fechaSeleccionada == '1') {
  $filtroFecha = "AND p.fecha_creado BETWEEN '$fechaInicio' AND '$fechaFin'";
} else {
  $filtroFecha = ''; // En caso de un valor inesperado, no aplicar filtro
}




// 1. Construye la consulta unificada
$sql = "SELECT 
    p.id_proceso,
    p.fecha_creado,
    p.id_estado_proceso,
    ep.estado_proceso,
    c_titular.cedula AS titular_cedula,
    CONCAT(c_titular.nombres, ' ', c_titular.apellidos) AS titular_nombre,
    c_benef.cedula AS beneficiario_cedula,
    CONCAT(c_benef.nombres, ' ', c_benef.apellidos) AS beneficiario_nombre,
    g.nombre AS gasto_nombre,
    g.descripcion AS gasto_descripcion,
    g.monto_aceptado AS gasto_monto
  FROM
    tbl_recibo_contrato rc
  INNER JOIN tbl_contratos_entes ce 
    ON rc.id_contrato_ente = ce.id_contrato_ente
  INNER JOIN titulares t 
    ON ce.id_ente = t.id_ente
  INNER JOIN procesos p 
    ON t.id_titular = p.id_titular
  INNER JOIN estados_procesos ep 
    ON p.id_estado_proceso = ep.id_estado_proceso
  INNER JOIN clientes c_titular 
    ON t.id_cliente = c_titular.id_cliente
  INNER JOIN gastos_t_b g 
    ON p.id_proceso = g.id_proceso
  LEFT JOIN beneficiarios b 
    ON p.id_beneficiario = b.id_beneficiario
  LEFT JOIN clientes c_benef 
    ON b.id_cliente = c_benef.id_cliente
  WHERE rc.cedula_contratante = '$cedulaContratante'
    $filtroTipoContratante 
    $filtroTipoCliente 
    $filtroFecha 
    $filtroEstadoProceso 
    $filtroTipoServicio 
  ORDER BY p.id_proceso;
";

// 2. Ejecuta la consulta
$res = ejecutar($sql);

// 3. Crea un array para agrupar
$datos = [];

while ($row = asignar_a($res, NULL, PGSQL_ASSOC)) :
  $idProceso = $row['id_proceso'];

  // Si todavía no está creado, inicializa la estructura para este proceso
  if (!isset($datos[$idProceso])) {
      $datos[$idProceso] = [
          'id_proceso'       => $idProceso,
          'fecha_creado'     => $row['fecha_creado'],
          'estado_proceso'   => $row['estado_proceso'],

          'titular_cedula'   => $row['titular_cedula'],
          'titular_nombre'   => $row['titular_nombre'],

          'beneficiario_cedula'  => $row['beneficiario_cedula'],
          'beneficiario_nombre'  => $row['beneficiario_nombre'],

          // Array para varios gastos
          'gastos' => []
      ];
  }

  // Si hay un gasto en esta fila, agrégalo al array de gastos
  if (!empty($row['gasto_nombre'])) {
      $datos[$idProceso]['gastos'][] = [
          'nombre'       => $row['gasto_nombre'],
          'descripcion'  => $row['gasto_descripcion'],
          'monto_aceptado'=> $row['gasto_monto']
      ];
  }
endwhile;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte Siniestralidad</title>
  <style>

    p {
      margin: 0;
    }

    .negrita {
      font-weight: bold;
      font-size:20px;
    }

    .centrar {
      text-align: center;
    }

    h1 {
      text-align: center;
    }

    .grilla1 {
      display:grid;
      grid-template-columns:20% 60% 20%;
    }

    .grilla2 {
      margin: 30px 0;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
    }

    .grilla2__filtros {
      grid-row:span 2;
      font-size: 33px;
      font-weight: bold;
    }

    .item {
      padding: 10px;
      display: flex;
      flex-direction: column;

    }

    .border-end {
      border-right: 1px solid black;
    }

    table {
      margin-top:15px;
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size:12px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 5px;
      text-align: left;
    }
    thead {
      background-color: #007bff;
      color: white;
      font-weight: bold;
      position: sticky;
      top: 0;
    }
    tfoot {
      background-color: #e0e0e0;
      font-weight: bold;
    }
    .fila-par {
      background-color: #f2f2f2;
    }
    .fila-impar {
      background-color:rgb(217, 237, 247);
    }
    .total-row {
      font-weight: bold;
    }

    .error {
      color: red;
      font-weight: bold;
      font-size: 50px;
    }

  </style>
</head>
<body>
<?php
// Si no hay datos, muestra un mensaje de error y termina
if (empty($datos)) {
  echo "<p class='error'>No se encontraron ordenes con los filtros seleccionados</p>";
  exit;
}
?>

<div class="grilla1">
  <img src="../../public/images/head.png" alt="logo_clinisalud">
  <h1>Reporte Siniestralidad por Contratantes</h1>
</div>

  <div class="grilla2">
    <div class="item grilla2__filtros border-end">
      Filtros<br>Aplicados
    </div>
    <div class="item border-end">
      <p class="negrita">Tipo de Contratante:</p>
      <p><?php echo $tipoContratante == 2 ? "Ente" : "Persona" ?></p>
    </div>
    <div class="item border-end">
      <p class="negrita">Cédula/Rif Contratante:</p>
      <p><?php echo $cedulaContratante ?></p>
    </div>
    <div class="item">
      <p class="negrita">Tipo de Servicio:</p>
      <p><?php echo $nombreTipoServicio ?></p>
    </div>
    <div class="item border-end">
      <p class="negrita">Estado del Proceso:</p>
      <p><?php echo $nombreEstadoProceso ?></p>
    </div>
    <div class="item border-end">
      <p class="negrita">Tipo de Cliente:</p>
      <p><?php echo $tipoCliente == 0 ? "Titulares y Beneficiarios" : ($tipoCliente == 1 ? "Titulares" : "Beneficiarios") ?></p>
    </div>
    <div class="item">
      <p class="negrita">Fecha Seleccionada:</p>
      <?php
      if ($fechaSeleccionada == 0) {
        echo "<p>Todas las fechas</p>";
      } else {
        echo "
          <p>Desde: $fechaInicio Hasta $fechaFin</p>
        ";
      }
      ?>
    </div>
  </div>

  <p class="negrita">Número de procesos encontrados: <?php echo count($datos) ?></p>

  <table>
    <thead>
      <tr>
        <th>ORDEN</th>
        <th>TITULAR</th>
        <th>CÉDULA TITULAR</th>
        <th>BENEFICIARIO</th>
        <th>CÉDULA BENEFICIARIO</th>
        <th>FECHA CREADO</th>
        <th>ESTADO PROCESO</th>
        <th>NOMBRE DEL GASTO</th>
        <th>DESCRIPCIÓN</th>
        <th>MONTO ACEPTADO</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $gastosTotales = 0;
      $alternadorClase = false; // Para alternar colores

      foreach ($datos as $proceso) :
        $numGastos = count($proceso['gastos']) + 2; // +1 por la fila principal y +1 por la fila total
        $claseFila = $alternadorClase ? 'fila-par' : 'fila-impar'; // Alternar clases

        echo "<tr class='$claseFila'>
                <td rowspan='{$numGastos}'>{$proceso['id_proceso']}</td>
                <td rowspan='{$numGastos}'>{$proceso['titular_nombre']}</td>
                <td rowspan='{$numGastos}'>{$proceso['titular_cedula']}</td>
                <td rowspan='{$numGastos}'>{$proceso['beneficiario_nombre']}</td>
                <td rowspan='{$numGastos}'>{$proceso['beneficiario_cedula']}</td>
                <td rowspan='{$numGastos}'>{$proceso['fecha_creado']}</td>
                <td rowspan='{$numGastos}'>{$proceso['estado_proceso']}</td>
                <td colspan='3'></td>
              </tr>";

        // Acumula gastos de este proceso
        $gastoProceso = 0;

        foreach ($proceso['gastos'] as $gasto) {
          $gastoProceso += $gasto['monto_aceptado'];
          echo "<tr class='$claseFila'>
                  <td>{$gasto['nombre']}</td>
                  <td>{$gasto['descripcion']}</td>
                  <td>{$gasto['monto_aceptado']}</td>
                </tr>";
        }

        echo "<tr class='total-row $claseFila'>
          <td colspan='2' class='centrar'>TOTAL GASTOS</td>
          <td>" . number_format($gastoProceso, 2, ",", ".") . "</td>
        </tr>";

          $gastosTotales += $gastoProceso;

          // Alternar clase para la siguiente iteración
          $alternadorClase = !$alternadorClase;
      endforeach;
      ?>
  </tbody>
      <tfoot>
        <tr>
          <td colspan="9" class="negrita centrar">GASTOS TOTALES</td>
          <td><?php echo number_format($gastosTotales, 2, ",", "."); ?></td>
        </tr>
      </tfoot>
  </table>


</body>
</html>