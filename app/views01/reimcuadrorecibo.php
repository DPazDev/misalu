<?php
include ("../../lib/jfunciones.php");

$cedula=$_REQUEST['cedula'];
$numcontr=$_REQUEST['recibo'];

// Buscar datos del plan
$sqlDatosPlan = "SELECT 
        clientes.cedula,
        clientes.id_cliente,
        clientes.nombres,
        clientes.apellidos,
        titulares.id_titular,
        tbl_contratos_entes.numero_contrato,
        tbl_contratos_entes.id_ente,
        tbl_contratos_entes.inicialcon,
        tbl_contratos_entes.cuotacon,
        tbl_contratos_entes.fecha_creado,
        tbl_recibo_contrato.id_comisionado,
        tbl_recibo_contrato.direccion_cobro,
        tbl_recibo_contrato.num_recibo_prima
    FROM 
        clientes,
        titulares,
        tbl_contratos_entes,
        tbl_recibo_contrato,
        tbl_caract_recibo_prima
    WHERE 
        clientes.id_cliente = titulares.id_cliente AND
        titulares.id_titular = tbl_caract_recibo_prima.id_titular AND
        tbl_caract_recibo_prima.id_recibo_contrato = tbl_recibo_contrato.id_recibo_contrato AND
        tbl_recibo_contrato.id_contrato_ente = tbl_contratos_entes.id_contrato_ente AND
        clientes.cedula = '$cedula' AND 
        tbl_recibo_contrato.id_recibo_contrato = '$numcontr' AND
        tbl_recibo_contrato.id_contrato_ente = tbl_contratos_entes.id_contrato_ente;";

$consultaDatosPlan=ejecutar($sqlDatosPlan);
$datosPlan=assoc_a($consultaDatosPlan);

// Variables de los datos del plan
$idEnte = $datosPlan[id_ente];
$idContrato=$datosPlan[numero_contrato];
$idRecibo=$datosPlan[num_recibo_prima];
$idTitular=$datosPlan[id_titular];
$laCotizacion=$_REQUEST[lacotiza];
$nomCompleto="$datosPlan[nombres] $datosPlan[apellidos]";
$porcentajeInicial = $datosPlan[inicialcon];
$numCuotas = $datosPlan[cuotacon];
$idComisionado = $datosPlan[id_comisionado];
$direccionCobro = $datosPlan[direccion_cobro];
$fechaCreado = $datosPlan[fecha_creado];


// Datos del ente
$sqlDatosEnte = "select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,sucursales.sucursal from
entes,sucursales
where entes.id_ente=$idEnte and
entes.id_sucursal=sucursales.id_sucursal";

$consultaDatosEnte=ejecutar($sqlDatosEnte);
$datosEnte=assoc_a($consultaDatosEnte);


// Datos del titular
$sqlDatosTitular="select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.direccion_hab,
clientes.telefono_hab,clientes.celular,ciudad.ciudad,estados.estado,clientes.fecha_nacimiento, titulares.tipocliente, clientes.edad, clientes.sexo 
from
    clientes,titulares,ciudad,estados
where
    clientes.id_cliente=titulares.id_cliente and
    clientes.id_ciudad=ciudad.id_ciudad and
    ciudad.id_estado=estados.id_estado and
    titulares.id_titular=$idTitular;";

$consultaDatosTitular=ejecutar($sqlDatosTitular);     
$datosTitular=assoc_a($consultaDatosTitular);


// Buscamos beneficiarios
$sqlVerBeneficiarios = "select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,clientes.sexo,
parentesco.parentesco,clientes.fecha_nacimiento  
from
clientes,beneficiarios,parentesco,estados_t_b
where
beneficiarios.id_titular=$idTitular and
beneficiarios.id_cliente=clientes.id_cliente and
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_estado_cliente=4 and
beneficiarios.id_parentesco=parentesco.id_parentesco;";

$consultaVerBeneficiarios = ejecutar($sqlVerBeneficiarios);



// Bucar poliza
$sqlBuscarPoliza = "SELECT polizas.deducible, polizas.id_poliza, nombre_poliza 
FROM polizas_entes
JOIN polizas ON polizas_entes.id_poliza = polizas.id_poliza 
WHERE polizas_entes.id_ente='$idEnte';";

$consultaBuscarPoliza = ejecutar($sqlBuscarPoliza);
$buscarPoliza = assoc_a($consultaBuscarPoliza);

// Variables de la poliza
$deducible = $buscarPoliza[deducible];
$idPoliza = $buscarPoliza[id_poliza];

// Volvemos a la primera fila del array para luego mostrar las coberturas sin problema
pg_result_seek($consultaBuscarPoliza, 0);


// Monedas
$sqlMoneda = "select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$idPoliza';";

$consultaMoneda = ejecutar($sqlMoneda);
$moneda = assoc_a($consultaMoneda);


// Selecciona el precio del plan buscando el titular de ese plan.
$sqlPrecioPlan = "SELECT SUM(monto_prima) AS suma_total
FROM tbl_caract_recibo_prima
WHERE id_titular = '$idTitular' AND id_recibo_contrato = '$numcontr' AND id_beneficiario = 0";

$consultaPrecioPlan = ejecutar($sqlPrecioPlan);
$precioPlan = assoc_a($consultaPrecioPlan)[suma_total];

$pagoInicial = $precioPlan * ($porcentajeInicial / 100);
$precioXCuota = round (($precioPlan - $pagoInicial) / $numCuotas, 2);


//Buscar comisionado
$sqlComisionado="select
        comisionados.nombres,
        comisionados.apellidos,
        comisionados.codigo
    from
        comisionados 
    where
        comisionados.id_comisionado=$idComisionado;";

$consultaComisionado = ejecutar($sqlComisionado);
$comisionado = assoc_a($consultaComisionado);

// Variables del comisionado
$comisionadoNombre = "$comisionado[nombres] $comisionado[apellidos]";
$comisionadoCodigo = $comisionado[codigo];


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimprimir cuadro recibo</title>
    <style>
        body {
            margin: 20px auto;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        h1 {
            width: 80%;
            margin: 0 auto 12px auto;
        }

        p {
            margin: 0;
        }

        .letra-pequenia {
            font-size: 9.6px;
        }

        .letra-grande {
            font-size: 14px;
        }

        .negrita {
            font-weight: bolder;
        }

        .mb-5 {
            margin-bottom: 12px;
        }

        .contenedor-principal{
            margin: 0 auto;
            width: 768px;
        }

        .cabecera {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .derecha {
            text-align: right;
        }

        .centro {
            text-align: center;
        }

        .area1 {
            display: grid;
            grid-template-columns: 1fr auto;
        }

        .bordes {
            border: 1px solid grey;
        }

        .columna3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .columna7 {
            display: grid;
            grid-template-columns: 4.5% 1fr 11% 15% 9% 10% 12%;
        }

        .columna6 {
            display: grid;
            grid-template-columns: 3.5% 1fr 15% 9% 10% 12%;
            justify-content: center;
        }

        /* las dos últimas columnas miden lo mismo que en la de columna6 */
        .columna3-columna6 {
            display:grid;
            grid-template-columns:1fr 10% 12%;
        }

        .centrar-vertical {
            display: flex;
            justify-content:center;
            align-items: center;
        }

        .columna2 {
            display: grid;
            grid-template-columns: 1fr 33%;
        }

        .altura {
            height:40px;
        }

    </style>
</head>
<body>
    <div class="contenedor-principal">
        <div class="cabecera">
            <div>
                <img src="../../public/images/head.png" alt="logo_clinisalud">
                <p class="letra-pequenia">Rif: J-31180863-9</p>
            </div>
            <div>
                <p class="letra-pequenia"><span class="negrita">Contrato Nº: </span><?php echo $idContrato;?></p>
                <hr>
                <p class="letra-pequenia"><span class="negrita">Fecha: </span><?php echo $fechaCreado; ?></p>
            </div>
        </div>
        <h1 class="centro letra-grande">CONTRATO DE SERVICIOS DE MEDICINA PREPAGADA
            HOSPITALIZACIÓN, CIRUGÍA Y MATERNIDAD
            CUADRO RECIBO DE SERVICIOS</h1>
        <div class="informacion-contrato">
            <div class="bordes">
                <p class="centro letra-grande negrita">DATOS DEL CONTRATANTE Y AFILIADO TITULAR</p>
            </div>
            <div class="area1">
                <p class="bordes"><span class="negrita">Contratante: </span><?php echo $nomCompleto;?></p>
                <p class="bordes"><span class="negrita">Cédula/R.I.F: </span><?php echo $cedula;?></p>
            </div>
            <div class="area1">
                <p class="bordes"><span class="negrita">Titular:</span> <?php echo $nomCompleto;?></p>
                <p class="bordes"><span class="negrita">Cédula/R.I.F: </span><?php echo $cedula;?></p>
            </div>
            <div class="columna3">
                <p class="bordes"><span class="negrita">Estado: </span><?php echo $datosTitular["estado"] ?></p>
                <p class="bordes centro"><span class="negrita">Ciudad: </span><?php echo $datosTitular["ciudad"] ?></p>
                <p class="bordes derecha"><span class="negrita">Télefono: </span><?php echo $datosTitular["telefono_hab"] ?></p>
            </div>
            <div class="bordes">
                <p class="centro letra-grande negrita">DATOS DEL CONTRATO</p>
            </div>
            <div class="columna3">
                <p class="bordes"><span class="negrita">Vigencia desde: </span><?list($anocon,$mescon,$diacon)=explode("-",$datosEnte[fecha_inicio_contrato]);
                echo "$diacon-$mescon-$anocon a las 12:00 M";?></p>
                <p class="bordes centro"><span class="negrita">Hasta: </span><?list($anocon,$mescon,$diacon)=explode("-",$datosEnte[fecha_renovacion_contrato]); echo "$diacon-$mescon-$anocon      a las 12:00 M";?></p>
                <p class="bordes derecha"><span class="negrita">Fecha de emisión: </span> <?php echo $fechaCreado ?> </p>
            </div>
            <div class="columna3">
                <p class="bordes"><span class="negrita">Sucursal / Oficina </span><?php echo $datosEnte["sucursal"] ?></p>
                <p class="bordes centro"><span class="negrita">Frecuencia de pago: </span><?php echo $numCuotas; ?></p>
                <p class="bordes derecha"><span class="negrita">Moneda: </span><?php echo $moneda[moneda]; ?></p>
            </div>
            <div class="bordes">
                <p class="centro letra-grande negrita">GRUPO AFILIADO</p>
            </div>
            <div class="columna7">
                <p class="bordes negrita centro">Nro.</p>
                <p class="bordes negrita centro">Nombres y Apellidos</p>
                <p class="bordes negrita centro">Cédula</p>
                <p class="bordes negrita centro">Fecha de Nac.</p>
                <p class="bordes negrita centro">Edad</p>
                <p class="bordes negrita centro">Sexo</p>
                <p class="bordes negrita centro">Parentesco</p>
            </div>
            <?php

            $contador = 0;
            // Ingresamos los datos del titular
            if ($datosTitular["tipocliente"] == 1) {
                echo '<div class="columna7">';
                    echo '<p class="bordes centro">' . "Titular" . '</p>';
                    echo '<p class="bordes">' . $nomCompleto . '</p>';
                    echo '<p class="bordes">' . $cedula . '</p>';
                    echo '<p class="bordes">' . $datosTitular["fecha_nacimiento"] . '</p>';
                    echo '<p class="bordes">' . $datosTitular[edad] . '</p>';
                    if ($datosTitular[sexo] == 1) {
                        echo '<p class="bordes">' . 'M' . '</p>';
                    } elseif ($datosTitular[sexo] == 0) {
                        echo '<p class="bordes">' . 'F' . '</p>';
                    }

                    echo '<p class="bordes"></p>';
                echo '</div>';
            };
            
            

            while($beneficiario = asignar_a($consultaVerBeneficiarios,NULL,PGSQL_ASSOC)) {
                $contador++;
                echo '<div class="columna7">';
                    echo '<p class="bordes centro">' . $contador . '</p>';
                    echo '<p class="bordes">' . $beneficiario[nombres] . " "  . $beneficiario[apellidos] .  '</p>';
                    echo '<p class="bordes">' . $beneficiario[cedula] . '</p>';
                    echo '<p class="bordes">' . $beneficiario[fecha_nacimiento] . '</p>';
                    echo '<p class="bordes">' . $beneficiario[edad] . '</p>';
                    if ($beneficiario[sexo] == 1) {
                        echo '<p class="bordes">' . 'M' . '</p>';
                    } elseif ($beneficiario[sexo] == 0) {
                        echo '<p class="bordes">' . 'F' . '</p>';
                    }
                    echo '<p class="bordes">' . $beneficiario[parentesco] . '</p>';
                echo '</div>';
            }

                ?>
            
            <div class="bordes">
                <p class="centro letra-grande negrita">COBERTURAS</p>
            </div>
            <div class="columna6">
                <p class="bordes negrita centrar-vertical">Nro.</p>
                <p class="bordes negrita centrar-vertical centro">Descripción de coberturas</p>
                <p class="bordes negrita centro">Límite de responsabilidad / Moneda</p>
                <p class="bordes negrita centrar-vertical centro">Deducible / Moneda</p>
                <p class="bordes negrita centrar-vertical centro">Cuota Anual / Moneda</p>
                <p class="bordes negrita centro">Cuota según frecuencia de pago / Moneda</p>
            </div>
            
            <?php

            $linea = 1;

            while($poliza=assoc_a($consultaBuscarPoliza,NULL,PGSQL_ASSOC)) {

                $idPoliza = $poliza[id_poliza];

                $sqlPropiedadesPoliza = "select propiedades_poliza.cualidad,propiedades_poliza.descripcion,propiedades_poliza.monto
                from propiedades_poliza
                where id_poliza='$idPoliza'";

                $consultaPropiedadesPoliza = ejecutar($sqlPropiedadesPoliza);

                while ($propiedadesPoliza = assoc_a($consultaPropiedadesPoliza, NULL, PGSQL_ASSOC)) {

                    echo '<div class="columna6">';
                    echo '<p class="bordes centro">' . $linea . '</p>';
                    echo '<p class="bordes">'. $propiedadesPoliza[descripcion] .'</p>';
                    echo '<p class="bordes centrar-vertical">'. $propiedadesPoliza[monto] . " " . $moneda[moneda] . '</p>';

                    if ($linea == 1) {
                        echo '<p class="bordes centrar-vertical">' . $deducible . " " .$moneda[moneda] . '</p>';
                        echo '<p class="bordes centrar-vertical">'. $precioPlan . " " . $moneda[moneda] . '</p>';
                        echo '<p class="bordes centrar-vertical">' . $precioXCuota . " " . $moneda[moneda] . '</p>';
                    } else {
                        echo '<p class="bordes"></p>';
                        echo '<p class="bordes"></p>';
                        echo '<p class="bordes"></p>';
                    }
                    echo '</div>';
                    $linea ++;
                }
            }

            ?>

            <div class="columna3-columna6">
                <p class="bordes derecha negrita">TOTAL </p>
                <p class="bordes centro">
                    <?php echo $precioPlan . " " . $moneda[moneda]?>
                </p>
                <p class="bordes centro">
                    <?php echo $precioXCuota . " " . $moneda[moneda]; ?>
                </p>
            </div>

            <div class="bordes">
                <p class="centro letra-grande negrita">OBSERVACIONES</p>
            </div>
            <div class="bordes altura">

            </div>
            <div class="bordes">
                <p class="centro letra-grande negrita">CENTRAL DE ATENCIÓN / MECANISMOS PARA COMUNICARSE CON CLINISALUD</p>
            </div>
            <div class="bordes">
                <p class="centro">0274-2510092 / 0424-7083394</p>
            </div>
            <div class="bordes">
                <p class="centro letra-grande negrita">INTERMEDIARIOS DE LA ACTIVIDAD ASEGURADORA</p>
            </div>
            <div class="columna3">
                <p class="bordes negrita">Intermediario.</p>
                <p class="bordes negrita centro">Código.</p>
                <p class="bordes negrita derecha">Dirección de Cobro</p>
            </div>
            <?php
            
            if (!empty($idComisionado)) {
                echo '<div class="columna3">';
                    echo '<p class="bordes">'. $comisionadoNombre .'</p>';
                    echo '<p class="bordes centro">'. $comisionadoCodigo .'</p>';

                    // Para contratos antiguos que no tenían dirección de cobro se muestra Mérida, mientras que para los demas si se muestra la dirección de cobro
                    echo '<p class="bordes derecha">'. ($direccionCobro==null ? "Mérida" : $direccionCobro) .'</p>';
                echo '</div>';
            }

            ?>
            <div class="bordes">
                <p class="centro letra-grande negrita">DECLARACIÓN DEL CONTRATANTE</p>
            </div>
            <div class="bordes">
                <p class="letra-pequenia">Yo, <?php echo $nomCompleto;?>, titular de la cédula de identidad Nº <?php echo $cedula; ?>, en mi carácter de Contratante, certifico que el dinero utilizado para el pago de la cuota proviene de una fuente lícita y su origen no guarda relación alguna con capitales, bienes, haberes, valores, títulos u operaciones producto de actividades ilícitas o que provengan de los delitos de Delincuencia Organizada u otras conductas tipificadas en la legislación Venezolana.</p>
            </div>
            <div class="columna2">
                <div class="bordes">
                    <p class="letra-pequenia mb-5">El Contratante</p>
                    <p class="letra-pequenia mb-5">Nombre y Apellido/Denominación Social (Colocar datos del representante legal si el Contratante es persona Jurídica)</p>
                    <p class="letra-pequenia mb-5">C.I / R.I.F:</p>
                    <p class="letra-pequenia mb-5">Firma:</p>
                </div>
                <div class="bordes">
                    <p class="letra-pequenia mb-5">Por la Empresa de Medicina Prepagada Representante Nombre y Apellido:</p>
                    <p class="letra-pequenia mb-5">Cargo:</p>
                    <p class="letra-pequenia mb-5">Firma:</p>
                </div>
            </div>
            <div class="bordes">
                <p class="centro">En __________________________ a los ________________________ del mes de ______________________ del año _____________</p>
            </div>
            <div class="bordes letra-pequenia">
                <p>Este Cuadro Recibo de Servicios será entregado al Contratante conjuntamente con las Condiciones Generales, las Condiciones Particulares y Anexos, si los hubiere, copia de la
                    solicitud de Servicios y demás documentos que formen parte del contrato. En la renovación la obligación procederá para los nuevos documentos o para aquellos que hayan sido
                    modificados</p>
            </div>
            <div class="bordes letra-pequenia">
                <p>Si el Contratante, Afiliado o Beneficiario siente vulnerados sus derechos y requiere presentar cualquier denuncia, queja, reclamo o solicitud de asesoría, surgida con ocasión de este
                    contrato de seguro, puede acudir a la Oficina de la Defensoria del Asegurado de la Superintendencia de la Actividad Aseguradora o comunicarlo a través de la página web:
                    http://wwww.sudeaseg.gob.ve</p>
            </div>
            <div class="bordes">
                <p class="centro letra-pequenia">
                    <span class="negrita">CLINISALUD MEDICINA PREPAGADA S.A., RIF J-311808639</span><br>Autorizada por la Superintendencia de la Actividad Aseguradora con el Nº 9, según providencia Nº 9 SAA-07-01873-2023 de fecha 23 de Agosto del 2023<br>DOMICILIO FISCAL: Calle 25 entre Avenidas 7 y 8, Edif. El Cisne 3er Piso, Mérida. Edo. Mérida. Telf.: (0274) 2510092<br>SEDE QUIROFANO: Zona Industrial Los Curos, Sector Campo Claro, Hospital San Juan de Dios, Telf.: (0274) 2715226<br>SEDE EL VIGIA: Av. Bolívar, Esquina con Av. 12, Calle 6 Edificio Liegos. El Vigía. Edo Mérida. Telf.: (0275) 8814608
                </p>
            </div>
        </div>
    </div>
</body>
</html>