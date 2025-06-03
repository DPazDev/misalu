<?php

/**
 * Archivo: registro_masivo_contrato.php
 * 
 * Descripción:
 *   Genera contratos de planes de salud de forma masiva a partir de un archivo Excel.
 *   Cada fila del archivo representa un contrato a procesar automáticamente.
 * 
 * Autor: Daniel Paz
 * Fecha: 09/04/2025
 * 
 * Uso:
 *   1. Subir un archivo Excel con los datos requeridos.
 *   2. El sistema procesará cada fila y generará los contratos correspondientes.
 * 
 * Consideraciones:
 *   - El archivo Excel debe estar ordenado con las siguientes columnas (en este orden):
 *       Apellido, Nombre, Género, Fecha de Nacimiento, Edad, Cédula,
 *       Beneficiario / Titular, Parentesco, Estado Civil, Dirección de Habitación,
 *       Teléfono Móvil, Email, Comentario
 *   - Todos los campos son obligatorios excepto Email, Comentario y Teléfono Móvil.
 *   - Asegúrese de que no haya filas vacías ni datos mal formateados.
 */


include ("../../../lib/jfunciones.php");
require_once "../../../lib/Excel/SimpleXLSX.php";

use Shuchkin\SimpleXLSX;




/////////////// Variables a modificar ///////////////
$archivo = 'CAJA DE AHORRO DE LOS BOMBEROS.xlsx';
$idPoliza = 232; // PLAN DE SALUD COLECTIVO EMPRESARIAL
$fechaInicioContratos = '2025-06-01';
$fechFinContratos = '2026-06-01';
$id_comisionado = 49; // Armando
$rifEnteContratante = "J-09018769-3"; // CAJA DE AHORRO DEL PERSONAL DEL CUERPO DE BOMBERO MERIDA (CABOME))	
$idSubdivision = 272; // CABOME


if ( !$xlsx = SimpleXLSX::parse($archivo) ) {
    echo SimpleXLSX::parseError();
    exit; // Termina la ejecución del script si hay un error al leer el archivo
}


$fechaActual = date('Y-m-d');
$anioActual = date('Y', strtotime($fechaActual));
$horaCreado = '00:00:00';
$idCiudad = 1; // ID Merida
$idAdmin = '935'; //ID Daniel Paz
$idTipoEnte = 7; // CLIENTES INDIVIDUALES MEDICINA PREPAGADA
$idSucursal = 22; // SERIE J VENTAS
$idEstadoCliente = 4; // Activo

$contadorTitulares = 0;
$contadorBeneficiarios = 0;


/**
 * ================================
 * Buscamos propiedades de la póliza
 * ================================
 */
$propiedadesPolizas = [];

$sqlPropiedadesPoliza = "SELECT
        id_propiedad_poliza, organo as id_organo, monto_nuevo 
    FROM
        propiedades_poliza
    WHERE
        id_poliza = '{$idPoliza}'";
$resultPropiedadesPoliza = ejecutar($sqlPropiedadesPoliza);

// Si no se encuentran propiedades de la póliza, se termina la ejecución del script
if (!$resultPropiedadesPoliza) {
    echo "Error al buscar las propiedades de la póliza. Con id $idPoliza";
    exit;
}

while ($filaPropiedadPoliza = asignar_a($resultPropiedadesPoliza)) {
    $propiedadPoliza = [
        'id_propiedad_poliza' => $filaPropiedadPoliza['id_propiedad_poliza'],
        'id_organo' => $filaPropiedadPoliza['id_organo'],
        'monto' => $filaPropiedadPoliza['monto_nuevo']
    ];
    $propiedadesPolizas[] = $propiedadPoliza;
}
/**
 * ================================
 * Fin Busqueda propiedades de la póliza
 * ================================
 */




/**
 * ================================
 * Buscamos Primas de la Póliza
 * ================================
 */
$primasPoliza = []; // Contendrá prima de titular masculino y femenino

$sqlPrimaTitularFemenino = "SELECT 
        pr.id_prima
    FROM polizas AS po
    INNER JOIN primas AS pr 
        ON po.id_poliza = pr.id_poliza
    INNER JOIN parentesco AS pa 
        ON pr.id_parentesco = pa.id_parentesco
    WHERE 
        po.id_poliza = '{$idPoliza}' AND
        pa.id_parentesco = 17
    LIMIT 1;";
$resultPrimaTitularFemenino = ejecutar($sqlPrimaTitularFemenino);
$primaTitularFemenino = assoc_a($resultPrimaTitularFemenino)['id_prima'];

$sqlPrimaTitularMasculino = "SELECT 
        pr.id_prima
    FROM polizas AS po
    INNER JOIN primas AS pr 
        ON po.id_poliza = pr.id_poliza
    INNER JOIN parentesco AS pa 
        ON pr.id_parentesco = pa.id_parentesco
    WHERE 
        po.id_poliza = '{$idPoliza}' AND
        pa.id_parentesco = 18
    LIMIT 1;";
$resultPrimaTitularMasculino = ejecutar($sqlPrimaTitularMasculino);
$primaTitularMasculino = assoc_a($resultPrimaTitularMasculino)['id_prima'];

/**
 * ================================
 * Fin Busqueda Primas Póliza
 * ================================
*/



/**
 * ================================
 * Filas del Excel
 * ================================
*/

$filas = $xlsx->rows(); // Se obtienen las filas del archivo Excel
$encabezados = array_shift($filas); // la primera fila son encabezados, se ignora
$titulares = []; // Aquí se irán guardando los registros de los titulares con sus beneficiarios asociados
 
foreach ($filas as $fila) {

    $tipoRegistro = strtoupper(trim($fila[6])); // Columna "Beneficiario / Titular"

    if ($tipoRegistro === 'TITULAR') {
        // Comienza un nuevo registro para el titular
        // Guarda toda la información que necesites, por ejemplo:
        $registroActual = [
            'apellido'              => $fila[0],
            'nombre'                => $fila[1],
            'genero'                => $fila[2],
            'fechaNacimiento'       => $fila[3],
            'edad'                  => $fila[4],
            'cedula'                => $fila[5],
            'tipoCliente'           => $tipoRegistro, // Titular
            "estadoCivil"           => $fila[8], // Columna Estado Civil
            "direccionResidencia"   => $fila[9], // Columna Dirección Residencia
            "telefono"              => $fila[10], // Columna Teléfono
            "email"                 => $fila[11], // Columna Email
            "comentarios"           => $fila[12], // Columna Comentarios
            'beneficiarios'         => [] // Aquí se irán agregando los beneficiarios
        ];

        $registroActual["genero"] = $registroActual["genero"] == "M" ? "1" : "0";

        // Limpiar espacios en blanco
        $registroActual["fechaNacimiento"] = trim($registroActual["fechaNacimiento"]);
        $registroActual["cedula"] = trim($registroActual["cedula"]);
        $registroActual["edad"] = trim($registroActual["edad"]);
        $registroActual["estadoCivil"] = trim($registroActual["estadoCivil"]);
        $registroActual["tipoCliente"] = trim($registroActual["tipoCliente"]);
        
        // Agregar el registro a la lista de registros
        $titulares[] = $registroActual;

    } elseif ($tipoRegistro === 'BENEFICIARIO') {
        // Si es beneficiario, se asocia al último titular leído
        if (!empty($titulares)) {
            $beneficiario = [
                'apellido'              => $fila[0],
                'nombre'                => $fila[1],
                'genero'                => $fila[2],
                'fechaNacimiento'       => $fila[3],
                'edad'                  => $fila[4],
                'cedula'                => $fila[5],
                'tipoCliente'           => $tipoRegistro, // Beneficiario
                'parentesco'            => $fila[7], // Columna Parentesco
                "estadoCivil"           => $fila[8], // Columna Estado Civil
                "direccionResidencia"   => $fila[9], // Columna Dirección Residencia
                "telefono"              => $fila[10], // Columna Teléfono
                "email"                 => $fila[11], // Columna Email
                "comentarios"           => $fila[12], // Columna Comentarios
                'tipo'                  => $tipoRegistro, // Beneficiario
            ];

            $beneficiario["genero"] = $beneficiario["genero"] == "M" ? "1" : "0";

            // Limpiar espacios en blanco
            $beneficiario["fechaNacimiento"] = trim($beneficiario["fechaNacimiento"]);
            $beneficiario["cedula"] = trim($beneficiario["cedula"]);
            $beneficiario["edad"] = trim($beneficiario["edad"]);
            $beneficiario["estadoCivil"] = trim($beneficiario["estadoCivil"]);
            $beneficiario["tipoCliente"] = trim($beneficiario["tipoCliente"]);
            $beneficiario["parentesco"] = trim($beneficiario["parentesco"]);
            
            // Se agrega al último titular en la lista
            $ultimoIndice = count($titulares) - 1;
            $titulares[$ultimoIndice]['beneficiarios'][] = $beneficiario;
        }
    }  else {
        // Opción: manejar error o registro huérfano
        echo "<br> Cliente no es titular ni beneficiario. Cédula: " . $fila[5];
        exit();
    }
}
/**
 * ================================
 * FIN Recorrido Filas del Excel
 * ================================
*/




// ** Iniciar la transacción **
ejecutar("BEGIN");

/**
 * ================================
 * Recorremos los registros de los titulares
 * ================================
*/

try {
    foreach ($titulares as &$titular) {

        echo "<br> Fecha nacimiento: " . $titular['fechaNacimiento'];

        $contadorTitulares++;

        $cedulaTitular = $titular['cedula'];

        // Buscar si el cliente titular ya existe en la base de datos
        $sqlClienteTitular = "SELECT id_cliente FROM clientes WHERE cedula = '{$cedulaTitular}'";

        $resultConsultaClienteTitular = ejecutar($sqlClienteTitular);
        $filaClienteTitular = assoc_a($resultConsultaClienteTitular);

        // Si no existe, insertar el cliente titular
        if (!$filaClienteTitular) {

            $sqlInsertClienteTitular = "INSERT INTO
                clientes (
                    apellidos,
                    nombres,
                    sexo,
                    fecha_nacimiento,
                    edad,
                    cedula,
                    direccion_hab,
                    telefono_hab,
                    email,
                    comentarios,
                    estado_civil,
                    fecha_creado,
                    hora_creado,
                    id_ciudad
                ) VALUES (
                    '{$titular['apellido']}',
                    '{$titular['nombre']}',
                    '{$titular['genero']}',
                    '{$titular['fechaNacimiento']}',
                    '{$titular['edad']}',
                    '{$cedulaTitular}',
                    '{$titular['direccionResidencia']}',
                    '{$titular['telefono']}',
                    '{$titular['email']}',
                    '{$titular['comentarios']}',
                    '{$titular['estadoCivil']}',
                    '{$fechaInicioContratos}',
                    '{$horaCreado}',
                    '{$idCiudad}'
                )
                RETURNING id_cliente";
            $resultInsertarClienteTitular = ejecutar($sqlInsertClienteTitular);

            if (!$resultInsertarClienteTitular) {
                throw new Exception("Error al insertar el cliente titular -$cedulaTitular-. SQL:\n $sqlInsertClienteTitular");
            }
            $filaClienteTitular = assoc_a($resultInsertarClienteTitular);
                
        } else {
            ///////////// Si el cliente titular ya existe, se actualiza la edad ///////////////////////
            $sqlUpdateEdad = "UPDATE clientes SET edad = '{$titular['edad']}' WHERE id_cliente = '{$filaClienteTitular['id_cliente']}'";
            $resultUpdateEdad = ejecutar($sqlUpdateEdad);
            if (!$resultUpdateEdad) {
                throw new Exception("Error al actualizar la edad del cliente titular -$cedulaTitular-. SQL:\n $sqlUpdateEdad");
            }
        }
        $id_cliente_titular = $filaClienteTitular['id_cliente'];


        $sqlEnte = "INSERT INTO
            entes(
                nombre,
                telefonos,
                direccion,
                email,
                email_contacto,
                rif,
                fecha_creado,
                hora_creado,
                id_ciudad,
                fecha_inicio_contrato,
                fecha_renovacion_contrato,
                id_sucursal,
                id_tipo_ente,
                fecha_inicio_contratob,
                fecha_renovacion_contratob,
                es_med_pre
            ) VALUES (
                '{$titular['nombre']} {$titular['apellido']}',
                '{$titular['telefono']}',
                '{$titular['direccionResidencia']}',
                '{$titular['email']}',
                '{$titular['email']}',
                '{$cedulaTitular}',
                '{$fechaActual}',
                '{$horaCreado}',
                '{$idCiudad}',
                '{$fechaInicioContratos}',
                '{$fechFinContratos}',
                '{$idSucursal}',
                '{$idTipoEnte}',
                '{$fechaInicioContratos}',
                '{$fechFinContratos}',
                '1'
            ) RETURNING id_ente";
        $resultEnte = ejecutar($sqlEnte);
        if (!$resultEnte) {
            throw new Exception("Error al insertar el ente -$cedulaTitular-. SQL: </br> $sqlEnte </br>");
        }
        $id_ente = assoc_a($resultEnte)['id_ente'];


        $sqlTitular = "INSERT INTO
            titulares (
                id_cliente,
                fecha_ingreso_empresa,
                fecha_creado,
                hora_creado,
                id_ente,
                id_admin,
                maternidad,
                tipocliente
            ) VALUES (
                '{$id_cliente_titular}',
                '{$fechaInicioContratos}',
                '{$fechaActual}',
                '{$horaCreado}',
                '{$id_ente}',
                '{$idAdmin}',
                '0',
                '1'
            ) RETURNING id_titular;";
        $resultTitular = ejecutar($sqlTitular);
        if (!$resultTitular) {
            throw new Exception("Error al insertar titular -$cedulaTitular-. SQL: </br> $sqlTitular </br>");
        }
        $id_titular = assoc_a($resultTitular)['id_titular'];


        $sqlTitularesPolizas = "INSERT INTO
            titulares_polizas (
                id_titular,
                id_poliza,
                fecha_creado,
                hora_creado
            ) VALUES (
                '{$id_titular}',
                '{$idPoliza}',
                '{$fechaActual}',
                '{$horaCreado}'
            )
        ";
        $resultTitularesPolizas = ejecutar($sqlTitularesPolizas);
        if (!$resultTitularesPolizas) {
            throw new Exception("Error al insertar titulares_polizas -$cedulaTitular-. SQL: </br> $sqlTitularesPolizas </br>");
        }


        $sqlTitularesSubdivision = "INSERT INTO
            titulares_subdivisiones (
                id_titular,
                id_subdivision
            ) VALUES (
                '{$id_titular}',
                '{$idSubdivision}'
            )
        ";
        $resultTitularesSubdivision = ejecutar($sqlTitularesSubdivision);
        if (!$resultTitularesSubdivision) {
            throw new Exception("Error al insertar titulares_subdivisiones -$cedulaTitular-. SQL: </br> $sqlTitularesSubdivision </br>");
        }


        $sqlPolizasEntes = "INSERT INTO
            polizas_entes (
                id_ente,
                id_poliza
            ) VALUES (
                '{$id_ente}',
                '{$idPoliza}'
            )";
        $resultPolizasEntes = ejecutar($sqlPolizasEntes);
        if (!$resultPolizasEntes) {
            throw new Exception("Error al insertar polizas_entes -$cedulaTitular-. SQL: </br> $sqlPolizasEntes </br>");
        }


        $sqlEnteComisionado = "INSERT INTO
            entes_comisionados (
                id_ente,
                id_comisionado,
                fecha_creado,
                hora_creado,
                descuento_recargo
            ) VALUES (
                '{$id_ente}',
                '{$id_comisionado}',
                '{$fechaActual}',
                '{$horaCreado}',
                '.'
            )";
        $resultEnteComisionado = ejecutar($sqlEnteComisionado);
        if (!$resultEnteComisionado) {
            throw new Exception("Error al insertar entes_comisionados -$cedulaTitular-. SQL: </br> $sqlEnteComisionado </br>");
        }


        $sqlBaremoEnte = "INSERT INTO
            tbl_baremos_entes (
                id_ente,
                id_baremo,
                fecha_creado
            ) VALUES (
                '{$id_ente}',
                '113',
                '{$fechaActual} {$horaCreado}'
            )";
        $resultBaremoEnte = ejecutar($sqlBaremoEnte);
        if (!$resultBaremoEnte) {
            throw new Exception("Error al insertar tbl_baremos_entes -$cedulaTitular-. SQL: </br> $sqlBaremoEnte </br>");
        }


        
        // Obtener el último ID de contrato ente y calcular el nuevo numero_contrato
        $sqlUltimoIdContratoEnte = "SELECT id_contrato_ente FROM tbl_contratos_entes ORDER BY id_contrato_ente DESC LIMIT 1;";
        $resultIdContratoEnte = ejecutar($sqlUltimoIdContratoEnte);
        $idContratoEnte = assoc_a($resultIdContratoEnte)['id_contrato_ente'] + 1;

        // Si tuviera maternidad sería HCM en vez de HC
        $numeroContrato = "HC-$anioActual-$idSucursal-$idTipoEnte-$idContratoEnte";

        $sqlContratosEntes = "INSERT INTO
            tbl_contratos_entes (
                id_ente,
                estado_contrato,
                fecha_creado,
                comentario,
                fecha_final_pago,
                numero_contrato,
                cuotacon,
                inicialcon
            ) VALUES (
                '{$id_ente}',
                '1',
                '{$fechaActual}',
                'Contrato creado por registro masivo',
                '{$fechFinContratos}',
                '{$numeroContrato}',
                '0',
                '0'
            )";
        $resultContratosentes = ejecutar($sqlContratosEntes);
        if (!$resultContratosentes) {
            throw new Exception("Error al insertar tbl_contratos_entes -$cedulaTitular-. SQL: </br> $sqlContratosEntes </br>");
        }



        // Buscamos el último id Contrato Ente para poder crear el nuevo num_recibo_prima
        $sqlUltimoIdReciboContrato = "SELECT id_recibo_contrato FROM tbl_recibo_contrato ORDER BY id_recibo_contrato DESC LIMIT 1;";
        $resultIdReciboContrato = ejecutar($sqlUltimoIdReciboContrato);
        $idReciboContrato = assoc_a($resultIdReciboContrato)['id_recibo_contrato'] + 1;
        $numReciboPrima = "$anioActual-$idReciboContrato";

        $sqlReciboContrato = "INSERT INTO
            tbl_recibo_contrato (
                id_contrato_ente,
                num_recibo_prima,
                fecha_ini_vigencia,
                fecha_fin_vigencia,
                fecha_creado,
                hora_emision,
                id_comisionado,
                direccion_cobro,
                tipo_contratante,
                cedula_contratante
            ) VALUES (
                '{$idContratoEnte}',
                '{$numReciboPrima}',
                '{$fechaInicioContratos}',
                '{$fechFinContratos}',
                '{$fechaActual}',
                '{$horaCreado}',
                '{$id_comisionado}',
                'MERIDA',
                '2',
                '{$rifEnteContratante}'
            )";
        $resulReciboContrato = ejecutar($sqlReciboContrato);
        if (!$resulReciboContrato) {
            throw new Exception("Error al insertar tbl_recibo_contrato -$cedulaTitular-. SQL: </br> $sqlReciboContrato </br>");
        }


        if ($titular["genero"] == "0") {
            $prima = $primaTitularFemenino;
        } elseif ($titular["genero"] == "1") {
            $prima = $primaTitularMasculino;
        } else {
            echo "Titular de cédula {$titular['cedula']} no tiene género válido.";
            exit;
        }
        $sqlCaractReciboPrima = "INSERT INTO
            tbl_caract_recibo_prima (
                id_recibo_contrato,
                id_titular,
                id_beneficiario,
                id_prima,
                fecha_creado,
                monto_prima,
                genera_comision
            ) VALUES (
                '{$idReciboContrato}',
                '{$id_titular}',
                '0',
                '{$prima}',
                '{$fechaActual} {$horaCreado}',
                '0',
                '1'
            )";
        $resultCaractReciboPrima = ejecutar($sqlCaractReciboPrima);
        if (!$resultCaractReciboPrima) {
            throw new Exception("Error al insertar tbl_caract_recibo_prima -$cedulaTitular-. SQL: </br> $sqlCaractReciboPrima </br>");
        }


        $sqlRegistrosExclusiones = "INSERT INTO
            registros_exclusiones (
                fecha_inclusion,
                fecha_exclusion,
                id_titular,
                id_beneficiario,
                fecha_creado,
                id_estado_cliente,
                comentario,
                id_admin
            ) VALUES (
                '{$fechaActual}',
                '{$fechaActual}',
                '{$id_titular}',
                '0',
                '{$fechaActual}',
                '{$idEstadoCliente}',
                '',
                '{$idAdmin}'
            )";
        $resultRegistrosExclusiones = ejecutar($sqlRegistrosExclusiones);
        if (!$resultRegistrosExclusiones) {
            throw new Exception("Error al insertar registros_exclusiones -$cedulaTitular-. SQL: </br> $sqlRegistrosExclusiones </br>");
        }

        
        $sqlEstadosTB = "INSERT INTO
            estados_t_b(
                id_estado_cliente,
                id_titular,
                id_beneficiario,
                fecha_creado,
                hora_creado
            ) VALUES (
                '{$idEstadoCliente}',
                '{$id_titular}',
                '0',
                '{$fechaActual}',
                '{$horaCreado}'
            )";
        $resultEstadosTB = ejecutar($sqlEstadosTB);
        if (!$resultEstadosTB) {
            throw new Exception("Error al insertar estados_t_b -$cedulaTitular-. SQL: </br> $sqlEstadosTB </br>");
        }


        foreach ($propiedadesPolizas as $propiedadPoliza) {

            $sqlCoberturasTB = "INSERT INTO
                coberturas_t_b(
                    id_propiedad_poliza,
                    id_organo,
                    monto_actual,
                    monto_previo,
                    id_titular,
                    id_beneficiario,
                    fecha_creado,
                    hora_creado
                ) VALUES (
                    '{$propiedadPoliza['id_propiedad_poliza']}',
                    '{$propiedadPoliza['id_organo']}',
                    '{$propiedadPoliza['monto']}',
                    '{$propiedadPoliza['monto']}',
                    '{$id_titular}',
                    '0',
                    '{$fechaActual}',
                    '{$horaCreado}'
                )";
            $resultCoberturasTB = ejecutar($sqlCoberturasTB);
            if (!$resultCoberturasTB) {
                throw new Exception("Error al insertar coberturas_t_b -$cedulaTitular-. SQL: </br> $sqlCoberturasTB </br>");
            }
        }

        // Buscar Beneficiarios
        foreach ($titular["beneficiarios"] as &$beneficiario) {

            $contadorBeneficiarios++;
            $cedulaBeneficiario = $beneficiario['cedula'];

            // Buscar Beneficiario en la base de datos
            $sqlClienteBeneficiario = "SELECT * FROM clientes WHERE cedula = '{$beneficiario['cedula']}'";
            $resultConsultaBeneficiario = ejecutar($sqlClienteBeneficiario);
            $filaClienteBeneficiario = assoc_a($resultConsultaBeneficiario);

            // Si no existe, insertar el cliente beneficiario
            if (!$filaClienteBeneficiario) {

                $sqlInsertClienteBeneficiario = "INSERT INTO
                clientes (
                    apellidos,
                    nombres,
                    sexo,
                    fecha_nacimiento,
                    edad,
                    cedula,
                    direccion_hab,
                    telefono_hab,
                    email,
                    comentarios,
                    estado_civil,
                    fecha_creado,
                    hora_creado,
                    id_ciudad
                ) VALUES (
                    '{$beneficiario['apellido']}',
                    '{$beneficiario['nombre']}',
                    '{$beneficiario['genero']}',
                    '{$beneficiario['fechaNacimiento']}',
                    '{$beneficiario['edad']}',
                    '{$cedulaBeneficiario}',
                    '{$beneficiario['direccionResidencia']}',
                    '{$beneficiario['telefono']}',
                    '{$beneficiario['email']}',
                    '{$beneficiario['comentarios']}',
                    '{$beneficiario['estadoCivil']}',
                    '{$fechaInicioContratos}',
                    '{$horaCreado}',
                    '{$idCiudad}'
                )
                RETURNING id_cliente";
                $resultInsertClienteBeneficiario = ejecutar($sqlInsertClienteBeneficiario);
                if (!$resultInsertClienteBeneficiario) {
                    throw new Exception("Error al insertar cliente beneficiario -$cedulaBeneficiario-. SQL: </br> $sqlInsertClienteBeneficiario </br>");
                }
                $filaClienteBeneficiario = assoc_a($resultInsertClienteBeneficiario);
                

            }  else {
                ///////////// Si el cliente beneficiario ya existe, se actualiza la edad ///////////////////////
                $sqlUpdateEdad = "UPDATE clientes SET edad = '{$beneficiario['edad']}' WHERE id_cliente = '{$filaClienteBeneficiario['id_cliente']}'";
                $resultUpdateEdad = ejecutar($sqlUpdateEdad);
                if (!$resultUpdateEdad) {
                    throw new Exception("Error al actualizar la edad del cliente beneficiario -$cedulaBeneficiario-. SQL:\n $sqlUpdateEdad");
                }
            }

            $idClienteBeneficiario = $filaClienteBeneficiario['id_cliente'];


            $idParentesco = "SELECT id_parentesco FROM parentesco WHERE UPPER(parentesco) = UPPER('{$beneficiario['parentesco']}') LIMIT 1;";
            $resultParentesco = ejecutar($idParentesco);

            $idParentesco = assoc_a($resultParentesco)['id_parentesco'];

            // Si no se encuentra el parentesco, se asigna el parentesco por defecto Hijo
            if (!$idParentesco) {
                echo "Error al buscar el parentesco del beneficiario $beneficiario[cedula] : $beneficiario[parentesco]</br>";
                $idParentesco = 3; // Hijo
            }

            $sqlBeneficiario = "INSERT INTO
                beneficiarios (
                    id_cliente,
                    id_titular,
                    id_parentesco,
                    fecha_creado,
                    hora_creado,
                    id_tipo_beneficiario
                ) VALUES (
                    '{$idClienteBeneficiario}',
                    '{$id_titular}',
                    '{$idParentesco}',
                    '{$fechaActual}',
                    '{$horaCreado}',
                    '7'
                ) RETURNING id_beneficiario";
            $resultBeneficiario = ejecutar($sqlBeneficiario);
            if (!$resultBeneficiario) {
                throw new Exception("Error al insertar beneficiarios -$cedulaBeneficiario-. SQL: </br> $sqlBeneficiario </br>");
            }
            $id_beneficiario = assoc_a($resultBeneficiario)['id_beneficiario'];


            $sqltiposBBeneficiarios = "INSERT INTO
                tipos_b_beneficiarios (
                    id_tipo_beneficiario,
                    id_beneficiario,
                    fecha_creado,
                    hora_creado
                ) VALUES (
                    '7',
                    '{$id_beneficiario}',
                    '{$fechaActual}',
                    '{$horaCreado}'
                )";
            $resultTiposBeneficiarios = ejecutar($sqltiposBBeneficiarios);
            if (!$resultTiposBeneficiarios) {
                throw new Exception("Error al insertar tipos_b_beneficiarios -$cedulaBeneficiario-. SQL: </br> $sqltiposBBeneficiarios </br>");
            }


            $sqlEstadosTB = "INSERT INTO
                estados_t_b(
                    id_estado_cliente,
                    id_titular,
                    id_beneficiario,
                    fecha_creado,
                    hora_creado
                ) VALUES (
                    '{$idEstadoCliente}',
                    '{$id_titular}',
                    '{$id_beneficiario}',
                    '{$fechaActual}',
                    '{$horaCreado}'
                )";
            $resultEstadosTB = ejecutar($sqlEstadosTB);
            if (!$resultEstadosTB) {
                throw new Exception("Error al insertar estados_t_b -$cedulaBeneficiario-. SQL: </br> $sqlEstadosTB </br>");
            }


            $sqlRegistrosExclusiones = "INSERT INTO
                registros_exclusiones (
                    fecha_inclusion,
                    fecha_exclusion,
                    id_titular,
                    id_beneficiario,
                    fecha_creado,
                    id_estado_cliente,
                    comentario,
                    id_admin
                ) VALUES (
                    '{$fechaActual}',
                    '{$fechaActual}',
                    '{$id_titular}',
                    '{$id_beneficiario}',
                    '{$fechaActual}',
                    '{$idEstadoCliente}',
                    '',
                    '{$idAdmin}'
                )";
            $resultRegistrosExclusiones = ejecutar($sqlRegistrosExclusiones);
            if (!$resultRegistrosExclusiones) {
                throw new Exception("Error al insertar registros_exclusiones -$cedulaBeneficiario-. SQL: </br> $sqlRegistrosExclusiones </br>");
            }

            foreach ($propiedadesPolizas as $propiedadPoliza) {

                $sqlCoberturasTB = "INSERT INTO
                    coberturas_t_b(
                        id_propiedad_poliza,
                        id_organo,
                        monto_actual,
                        monto_previo,
                        id_titular,
                        id_beneficiario,
                        fecha_creado,
                        hora_creado
                    ) VALUES (
                        '{$propiedadPoliza['id_propiedad_poliza']}',
                        '{$propiedadPoliza['id_organo']}',
                        '{$propiedadPoliza['monto']}',
                        '{$propiedadPoliza['monto']}',
                        '{$id_titular}',
                        '{$id_beneficiario}',
                        '{$fechaActual}',
                        '{$horaCreado}'
                    )";
                $resultCoberturasTB = ejecutar($sqlCoberturasTB);
                if (!$resultCoberturasTB) {
                    throw new Exception("Error al insertar coberturas_t_b -$cedulaBeneficiario-. SQL: </br> $sqlCoberturasTB </br>");
                }
            }
        }

    }
    /**
     * ================================
     * Fin Recorrido de los registros de los titulares
     * ================================
    */


    // ** Si todo salió bien, confirmar la transacción **
    ejecutar("COMMIT");
    
    echo "</br> Se han insertado $contadorTitulares titulares y $contadorBeneficiarios beneficiarios. </br>";
    echo "Cantidad de registros: " . ($contadorTitulares + $contadorBeneficiarios);
    

} catch (Exception $e) {
    // ** Si hubo algún error, revertir todo **
    ejecutar("ROLLBACK");
    echo "Se ha producido un error: " . $e->getMessage();
}

echo "terminado";



?>