<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$cedula = $_POST['cedula'];
$numero_contrato = $_POST['numero_contrato'];
$forma_pago = $_POST['forma_pago'];
$id_recibo_contrato = $_POST['id_recibo_contrato'];
$nom_tarjeta = $_POST['nom_tarjeta'];
$concepto = $_POST['concepto'];
$monto = $_POST['monto'];
$contado = $_POST['contado'];
$tprima = $_POST['tprima'];
$serie = $_POST['serie'];
$numero_recibo = $_POST['factura'];
$banco = $_POST['banco'];
$no_cheque = $_POST['no_cheque'];
$registrar=$_POST['registrar'];
$actinicuo=$_POST['actinicuo'];
$anular=$_POST['anular'];
$modificar=$_POST['modificar'];
$fecha_pago=date("Y-m-d");
$hora_creado=date("h:i:s A");
$inicialcon=$_POST['inicialcon'];
$cuotacon=$_POST['cuotacon'];
$id_recibo_pago= $_POST['id_recibo_pago'];
$precioDolar = $_SESSION["valorcambiario"];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** verifico de tiene activado el permiso registrar recibo pago y actualizar cuotas **** */
$q_reg_pag=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=17");
$r_reg_pag=ejecutar($q_reg_pag);
$f_reg_pag=asignar_a($r_reg_pag);



	/* **** verifico de tiene activado el permiso de Modificar la fecha efectiva del recibo de pago **** */
$q_mod_edo=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=16");
$r_mod_edo=ejecutar($q_mod_edo);
$f_mod_edo=asignar_a($r_mod_edo);

/* ****verifico si desea modificar la fecha efectiva de pago **** */
if ($modificar==1)
{
    $fechaefectivam=$_POST['fechaefectivam'];

    $q_mod="update 
                tbl_recibo_pago
            set 
                fecha_efec_pago='$fechaefectivam'
            where 
                tbl_recibo_pago.id_recibo_pago='$id_recibo_pago'";

    $f_mod=ejecutar($q_mod);

    /* **** Se registra lo que hizo el usuario**** */

    $log="Se modifica el id recibo_pago num $id_recibo_pago concepto fecha efectiva de pago $fechaefectivam  
    ";
    logs($log,$ip,$id_admin);

    /* **** Fin de lo que hizo el usuario **** */
}

/* ****verifico si desea anular un recibo de pago **** */
if ($anular==1) {
    
    $q_anu="update 
    tbl_recibo_pago
    set
    concepto='$conceptoa',
    monto='0',
    estado_recibo='0',
    monto_bs='0'
    where 
    tbl_recibo_pago.id_recibo_pago='$id_recibo_pago'";
    $f_anu=ejecutar($q_anu);
    /* **** Se registra lo que hizo el usuario**** */
    

    // Registro logs
    $conceptoa="$concepto (Recibo Anulado por $f_admin[nombres] $f_admin[apellidos])";

    $log="Se anula el id recibo_pago num $id_recibo_pago concepto $conceptoa";
    logs($log,$ip,$id_admin);
}


/* **** Actualizar cantidad de cuotas**** */
if ($actinicuo==1){
    
    $fecha_emision=$_POST['fecha_emision'];
    list($anoe,$mese,$diae)=explode("-",$fecha_emision);
    $ano=$anoe;
    $mes=$mese;
    $dia=$diae;

    $mes2=$mes+$cuotacon;
    if ($mes2>=13) {
        $mes2=$mes2 - 10;
        $ano=$ano+1;
    }

    if ($mes2<10){
        $cero='0';
    } else {
        $cero='';
    }

    $fecha_final="$ano-$cero$mes2-$dia";

    $mod_inicuo="update 
    tbl_contratos_entes
    set 
    fecha_final_pago='$fecha_final',
    cuotacon='$cuotacon' 
    from 
    tbl_recibo_contrato 
    where 
    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and 
    tbl_recibo_contrato.id_recibo_contrato=$id_recibo_contrato";
    $fmod_inicuo=ejecutar($mod_inicuo);


    // Registro de logs
    $conceptoa = "(Actualizado por $f_admin[nombres] $f_admin[apellidos])";

    $log="Se actualiza el numero de cuotas del contrato $numero_contrato a $cuotacon cuotas $conceptoa";
    logs($log,$ip,$id_admin);

}    /* **** fin de actualizar inicial o cuota antes de realizar el primer pago del recibo**** */


    /* **** registrar el pago del recibo**** */
if ($registrar==1){
    /* *** variables operaciones multiples*** */
    $forma_pago1 = $_POST['forma_pago1'];
    $nom_tarjeta1 = $_POST['nom_tarjeta1'];
    $banco1 = $_POST['banco1'];
    $no_cheque1 = $_POST['no_cheque1'];
    $monto1 = $_POST['monto1'];
    $forma_pago2 = $_POST['forma_pago2'];
    $nom_tarjeta2 = $_POST['nom_tarjeta2'];
    $banco2 = $_POST['banco2'];
    $no_cheque2 = $_POST['no_cheque2'];
    $monto2 = $_POST['monto2'];
    $forma_pago3 = $_POST['forma_pago3'];
    $nom_tarjeta3 = $_POST['nom_tarjeta3'];
    $banco3 = $_POST['banco3'];
    $no_cheque3= $_POST['no_cheque3'];
    $monto3 = $_POST['monto3'];
    $forma_pago4 = $_POST['forma_pago4'];
    $nom_tarjeta4 = $_POST['nom_tarjeta4'];
    $banco4 = $_POST['banco4'];
    $no_cheque4 = $_POST['no_cheque4'];
    $monto4 = $_POST['monto4'];
    $fecha_emision=$_POST['fecha_emision'];
    $fechaefectivap=$_POST['fechaefectivap'];
    /* *** fin de variables operaciones multiples*** */


    /* **** calculo del proximo pago **** */
    list($anoe,$mese,$diae)=explode("-",$fecha_emision);

    $q_recpa="(select * 
    from 
        tbl_recibo_pago 
    where 
        tbl_recibo_pago.id_recibo_contrato=$id_recibo_contrato and 
        tbl_recibo_pago.estado_recibo=1
    order by 
        tbl_recibo_pago.id_recibo_pago desc
    )";
    $r_recpa=ejecutar($q_recpa);
    $num_filasb=num_filas($r_recpa);


    if($num_filasb==0){
        $ano=$anoe;
        $mes=$mese;
        $dia=$diae;
        $mes2=$mes+1;
    } else {
        $ano=$anoe;
        $mes=$mese;
        $dia=$diae;

        $mes2=$mes+$num_filasb +1;
    }

    if ($mes2==13) {
        $mes2=1;
        $ano=$ano+1;
    }

    if ($mes<10 and $mes2<10){
        $cero='0';
    } else {
        $cero='';
    }

    if ($mes2>13) {
        $mes2=$mes2 - 12;

        if($mes2>12) {
            $mes2=$mes2-12;
        }		
        $ano=$ano+1;
    }


    if ($dia==30 and $mes2==2) {
        $dia=28;
    }

    $fecha_proxima="$ano-$cero$mes2-$dia";
    /* **** fin de calculo del proximo pago ****  */



    $saldo_favor=($tprima-($contado-$monto));
    $saldo_deudor=($contado-$monto);
    $monto_bs = $monto * $precioDolar;

    // Si se está realizando el pago total:
    if ($saldo_favor - $saldo_deudor == $saldo_favor) {
        $montocuota= $saldo_deudor / ($cuotacon);
        $montocuota=number_format($montocuota,2,'.','');
        $descripcion="Queda Pendiente 0 Cuotas";

    } else if ($num_filasb==0) {
        $montocuota= $saldo_deudor / ($cuotacon);
        $montocuota=number_format($montocuota,2,'.','');
        $descripcion="Queda Pendiente $cuotacon  Cuotas de  $montocuota$"; 

    } else {
        $montocuota= $saldo_deudor / ($cuotacon - ($num_filasb));
        $montocuota=number_format($montocuota,2,'.','');
        $cuotapen= $cuotacon - $num_filasb;
        $descripcion="Queda pendiente  $cuotapen Cuotas de  $montocuota$";
    }


    /* **** busco las factura para saber cual es la ultima**** */
    $q_factura="select * 
    from
        tbl_recibo_pago
    where
        tbl_recibo_pago.id_serie=$serie
    order by
        tbl_recibo_pago.id_recibo_pago desc limit 1;";
    $r_factura=ejecutar($q_factura);


    if(num_filas($r_factura)==0){
        $numero_recibo="0001";
    
    } else {
        $f_factura=asignar_a($r_factura);
        $numero_recibo=(int)$f_factura[numero_recibo];

        if($numero_recibo<=10){
            $numero_recibo++;
            if($numero_recibo==10)	{
                $numero_recibo="00$numero_recibo";
            } else {
                $no_factura="000$numero_recibo";
            }

        }else if($numero_recibo>10 && $numero_recibo<=100){
            $numero_recibo++;

            if($numero_recibo==100)     $numero_recibo="0$numero_recibo";
            else                        $numero_recibo="00$numero_recibo";
            
        }else if($numero_recibo>100 && $numero_recibo<=1000){
            $numero_recibo++;

            if($numero_recibo==1000)     $numero_recibo="$numero_recibo";                 
            else                      $numero_recibo="0$numero_recibo";
        }else {
            $numero_recibo++;
        }
    }

    $r_rec_pago="insert into tbl_recibo_pago 
                    (id_recibo_contrato,
                    id_tipo_pago,
                    concepto,
                    monto,
                    saldo_favor,
                    saldo_deudor,
                    fecha_pago,
                    fecha_proxima_pago,
                    id_serie,
                    numero_recibo,
                    estado_recibo,
                    descripcion,
                    fecha_efec_pago,
                    monto_bs) 
                values 
                    ('$id_recibo_contrato',
                    '$forma_pago',
                    '$concepto',
                    '$monto',
                    '$saldo_favor',
                    '$saldo_deudor',
                    '$fecha_pago',
                    '$fecha_proxima',
                    '$serie',
                    '$numero_recibo',
                    '1',
                    '$descripcion',
                    '$fechaefectivap',
                    '$monto_bs');";
    $f_rec_pago=ejecutar($r_rec_pago);

    if ($forma_pago<>6){
        
        if(!empty($dateField5) && $forma_pago==2){

            $Cfecha_credito="fecha_credito,";
            $Vfecha_credito="'$dateField5',";
            $Cestado_factura="id_estado_factura,";
            $Vestado_factura="'2', ";	
        }


        if(!empty($banco) && !empty($no_cheque) && ($forma_pago==3 || $forma_pago==5 || $forma_pago==4 || $forma_pago==7)) {
            $Cbanco="id_banco,";
            $Vbanco="'$banco', ";
            
            $Ccheque="numero_cheque,";
            $Vcheque="'$no_cheque',";
        }
        
        $q_rec_pa="(select
                        id_recibo_pago
                    from
                        tbl_recibo_pago
                    where
                        tbl_recibo_pago.numero_recibo='$numero_recibo'
                    and
                        tbl_recibo_pago.id_serie='$serie')";
        $r_rec_pa=ejecutar($q_rec_pa);
        $f_rec_pa=asignar_a($r_rec_pa);
        $id_recibo_pago=$f_rec_pa['id_recibo_pago'];

        $r_facturaom=   "insert into
                            tbl_oper_multi (id_factura,
                            $Cbanco
                            $Ccheque
                            id_nom_tarjeta,
                            monto,
                            condicion_pago,
                            id_recibo_pago) 
                        values('0',
                            $Vbanco
                            $Vcheque
                            '$nom_tarjeta',
                            '$monto',
                            '$forma_pago',
                            '$id_recibo_pago'
                            );";
        $r_facturaom=ejecutar($r_facturaom);
    } else {
         $q_rec_pa="(select id_recibo_pago from tbl_recibo_pago where tbl_recibo_pago.numero_recibo='$numero_recibo' and tbl_recibo_pago.id_serie='$serie')";
        $r_rec_pa=ejecutar($q_rec_pa);
        $f_rec_pa=asignar_a($r_rec_pa);
        $id_recibo_pago=$f_rec_pa['id_recibo_pago'];

        if ($monto1>0){
            
			if ($forma_pago1==1){
				$no_cheque1=0;
				$nom_tarjeta1=0;
            }
		    $r_facturaom=   "insert into tbl_oper_multi (id_factura,
                                id_banco,
                                numero_cheque,
                                id_nom_tarjeta,
                                monto,
                                condicion_pago,
                                id_recibo_pago) 
					        values('0',
                                '$banco1',
                                '$no_cheque1',
                                '$nom_tarjeta1',
                                '$monto1',
                                '$forma_pago1',
                                '$id_recibo_pago'
                                );";
	        $r_facturaom=ejecutar($r_facturaom);
        }

	    if ($monto2>0) {
			if ($forma_pago2==1){
				$no_cheque2=0;
				$nom_tarjeta2=0;
            }

		    $r_facturaom =  "insert into tbl_oper_multi (id_factura,
                                id_banco,
                                numero_cheque,
                                id_nom_tarjeta,
                                monto,
                                condicion_pago,
                                id_recibo_pago) 
					        values('0',
                                '$banco2',
                                '$no_cheque2',
                                '$nom_tarjeta2',
                                '$monto2',
                                '$forma_pago2',
                                '$id_recibo_pago'
					      	    );";
	        $r_facturaom=ejecutar($r_facturaom);
	    }


	    if ($monto3>0){
            echo "monto3";

			if ($forma_pago3==1){
				$no_cheque3=0;
				$nom_tarjeta3=0;
            }

		    $r_facturaom =  "insert into tbl_oper_multi (id_factura,
                                id_banco,
                                numero_cheque,
                                id_nom_tarjeta,
                                monto,
                                condicion_pago,
                                id_recibo_pago) 
					        values('0',
                                '$banco3',
                                '$no_cheque3',
                                '$nom_tarjeta3',
                                '$monto3',
                                '$forma_pago3',
                                '$id_recibo_pago'
                                );";
	        $r_facturaom=ejecutar($r_facturaom);
	    }

        if ($monto4>0){
            echo "monto4";
                if ($forma_pago4==1){
                        $no_cheque4=0;
                        $nom_tarjeta4=0;
                }
            $r_facturaom =  "insert into tbl_oper_multi (id_factura,
                                id_banco,
                                numero_cheque,
                                id_nom_tarjeta,
                                monto,
                                condicion_pago,
                                id_recibo_pago) 
                            values('0',
                                '$banco4',
                                '$no_cheque4',
                                '$nom_tarjeta4',
                                '$monto4',
                                '$forma_pago4',
                                '$id_recibo_pago'
                            );";
            $r_facturaom=ejecutar($r_facturaom);
        }
    }


    // Registro de logs
    $conceptoa = "(Creado por $f_admin[nombres] $f_admin[apellidos])";

    $log="Se creo el recibo de pago id_recibo_pago: $id_recibo_pago $conceptoa";
    logs($log,$ip,$id_admin);
    

}/* **** fin registrar el pago del recibo**** */
    

if ($numero_contrato) {
    $cedula = "";
}


if ($cedula) {
	$q_busc = "select 
                    tbl_contratos_entes.*,
                    entes.*,
                    tbl_recibo_contrato.*,
                    polizas.porcentaje,
                    polizas.cuota
                from 
                    tbl_contratos_entes,
                    entes ,
                    tbl_recibo_contrato,
                    polizas,
                    polizas_entes
                where 
                    entes.rif='$cedula' and 
                    tbl_contratos_entes.id_ente=entes.id_ente and
                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                    entes.id_ente=polizas_entes.id_ente and
                    polizas_entes.id_poliza=polizas.id_poliza and
                    polizas.maternidad=0";
	$r_busc = ejecutar($q_busc);
	?>

	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


        <tr>
            <td colspan=4 class="titulo_seccion">Contratos</td>
        </tr>
	
	    <?php
        // Variable que almacenara cada uno de los números de contrato, numeros de recibo prima y el estado de todos los contratos que tenga el cliente buscado.
        $contratos = [];

	    while($f_busc=asignar_a($r_busc,NULL,PGSQL_ASSOC))
        {

            array_push($contratos, [$f_busc[numero_contrato], $f_busc[num_recibo_prima], $f_busc[estado_contrato]]);

            /* **** busco los contratos para verificar si tienen alguna deuda **** */
            $q_buscon ="select 
                        tbl_contratos_entes.*,
                        entes.*,
                        tbl_recibo_contrato.*,
                        polizas.porcentaje,
                        polizas.cuota
                    from 
                        tbl_contratos_entes,
                        entes ,
                        tbl_recibo_contrato,
                        polizas,
                        polizas_entes
                    where 
                        tbl_recibo_contrato.num_recibo_prima='$f_busc[id_recibo_contrato]' and 
                        tbl_contratos_entes.id_ente=entes.id_ente and
                        tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                        entes.id_ente=polizas_entes.id_ente and
                        polizas_entes.id_poliza=polizas.id_poliza and
                        polizas.maternidad=0";
                        
                        $r_buscon = ejecutar($q_buscon);
                        $f_buscon = asignar_a($r_buscon);


            /* **** sumo el monto total de la prima**** */
            $q_buscon_caract= "select 
                                    sum(monto_prima) 
                                from 
                                    tbl_caract_recibo_prima 
                                where 
                                    tbl_caract_recibo_prima.id_recibo_contrato=  $f_busc[id_recibo_contrato]";
            $r_buscon_caract = ejecutar($q_buscon_caract);
            $f_buscon_caract = asignar_a($r_buscon_caract);

            /* **** sumo el monto total de los pagos de los recibo de prima**** */

            $q_buscon_pagos =   "select 
                                    sum(monto) 
                                from 
                                    tbl_recibo_pago 
                                where 
                                    tbl_recibo_pago.id_recibo_contrato=  $f_busc[id_recibo_contrato]";
            $r_buscon_pagos = ejecutar($q_buscon_pagos);
            $f_buscon_pagos = asignar_a($r_buscon_pagos);
            ?>
            <tr>
                <?php

                // Si el contrato esta anulado:
                if ($f_busc[estado_contrato] == 0) {
                    ?>

                    <td class="tdtitulos">Numero de Contrato Anulado</td>

                    <?php
                } else {
                    ?>
                    <td class="tdtitulos">Numero de Contrato</td>
                    <?php
                }
                ?>

                    <td class="tdcampos"><a href="#" OnClick="bus_reg_rec_pagocon(<?php echo 	"'$f_busc[num_recibo_prima]'"?>);" class="boton" title="Ir al Contrato"><?php echo 	"$f_busc[numero_contrato] Cuadro Recibo de Prima $f_busc[num_recibo_prima]";?></a>
		            </td>


                <td colspan=1 class="tdtitulos">Deuda</td>
                <td colspan=1 class="tdcamposr"><?php echo number_format($f_buscon_caract[sum] - $f_buscon_pagos[sum]  ,2,',','');?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
									
}



if (!empty($numero_contrato)) {

    $q_bus =    "select 
                    tbl_contratos_entes.*,
                    entes.*,
                    tbl_recibo_contrato.*,
                    polizas.porcentaje,
                    polizas.cuota
                from 
                    tbl_contratos_entes,
                    entes ,
                    tbl_recibo_contrato,
                    polizas,
                    polizas_entes
                where 
                    tbl_recibo_contrato.num_recibo_prima='$numero_contrato' and 
                    tbl_contratos_entes.id_ente=entes.id_ente and
                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                    entes.id_ente=polizas_entes.id_ente and
                    polizas_entes.id_poliza=polizas.id_poliza and
                    polizas.maternidad=0";
}

$r_bus = ejecutar($q_bus);
$f_bus = asignar_a($r_bus);


$q_bus_caract = "select 
                    sum(monto_prima) 
                from 
                    tbl_caract_recibo_prima 
                where 
                    tbl_caract_recibo_prima.id_recibo_contrato=  $f_bus[id_recibo_contrato]";
$r_bus_caract = ejecutar($q_bus_caract);
$f_bus_caract = asignar_a($r_bus_caract);


$q_bus_pagos =  "select 
                    sum(monto) 
                from 
                    tbl_recibo_pago 
                where 
                    tbl_recibo_pago.id_recibo_contrato= $f_bus[id_recibo_contrato]";
$r_bus_pagos = ejecutar($q_bus_pagos);
$f_bus_pagos = asignar_a($r_bus_pagos);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>
    <td colspan=4 class="titulo_seccion">Datos del Contratante</td>
</tr>

<tr>
    <td class="tdtitulos">Usuario:</td>
    <td class="tdcampos"><?php echo $f_bus[nombre]?></td>
    <td colspan=1 class="tdtitulos">Numero de Contrato</td>
    <td colspan=1 class="tdcampos">
        <?php echo $f_bus[numero_contrato]?>
        <input class="campos" type="hidden" id="numero_contrato1" name="numero_contrato1" maxlength=128 size=20 value="<?php echo $f_bus[num_recibo_prima]?>">
    </td>
</tr>


<tr>
    <td class="tdtitulos">Cedula / Rif</td>
    <td class="tdcampos">
        <?php echo $f_bus[rif]?>
        <input class="campos" type="hidden" id="cedula1" name="cedula1" maxlength=128 size=20 value="<?php echo $f_bus[rif]?>">
    </td>
    <td colspan=1 class="tdtitulos">Numero Recibo
        <input class="campos" type="hidden" id="id_recibo_contrato" name="id_recibo_contrato" maxlength=128 size=20 value="<?php echo $f_bus[id_recibo_contrato]?>">
    </td>
    <td colspan=1 class="tdcampos">
        <?php echo $f_bus[num_recibo_prima]?>
	</td>
</tr>


<tr>
    <td class="tdtitulos">Direccion</td>
    <td class="tdcampos"><?php echo $f_bus[direccion]?></td>
    <td colspan=1 class="tdtitulos">Fecha Vigencia</td>
    <td colspan=1 class="tdcampos">
        <?php echo "$f_bus[fecha_ini_vigencia] Hasta $f_bus[fecha_fin_vigencia]"?>
        <input class="campos" type="hidden" id="fecha_emision" name="fecha_emision" maxlength=128 size=20 value="<?php echo $f_bus[fecha_ini_vigencia]?>">
    </td>
</tr>


<tr>
    <td class="tdtitulos"></td>
    <td class="tdcampos"></td>
    <td colspan=1 class="tdtitulos"><hr></hr></td>
    <td colspan=1 class="tdcampos"><hr></hr></td>
</tr>


<tr>
    <td class="tdtitulos"></td>
    <td class="tdcampos"></td>
    <td colspan=1 class="tdtitulos">Total Prima</td>
    <td colspan=1 class="tdcampos">
        <?php echo number_format($f_bus_caract[sum]  ,2,',','');?>
        <input class="campos" type="hidden" id="tprima" name="tprima" maxlength=10 size=5 value="<?php echo $f_bus_caract[sum];?>">
    </td>
</tr>


<tr>
    <td class="tdtitulos"></td>
    <td class="tdcampos"></td>
    <td colspan=1 class="tdtitulos">Prima Cancelada</td>

    <td colspan=1 class="tdcampos">
        <?php echo number_format($f_bus_pagos[sum]  ,2,',','');?>
    </td>
</tr>


<tr>
    <td class="tdtitulos"></td>
    <td class="tdcampos"></td>
    <td colspan=1 class="tdtitulos">Prima en Deuda</td>
    <td colspan=1 class="tdcamposr">
        <?php echo number_format($f_bus_caract[sum] - $f_bus_pagos[sum]  ,2,',','');
        $i=0;

            $q_bus_rec_pagos=   "select 
                                    *
                                from 
                                    tbl_recibo_pago,tbl_series
                                where 
                                    tbl_recibo_pago.id_recibo_contrato=$f_bus[id_recibo_contrato] and tbl_recibo_pago.id_serie=tbl_series.id_serie order by tbl_recibo_pago.id_recibo_pago";
            $r_bus_rec_pagos = ejecutar($q_bus_rec_pagos);

            ?>
    </td>
</tr>
        	

            <?php
       
            while($f_bus_rec_pagos=asignar_a($r_bus_rec_pagos,NULL,PGSQL_ASSOC))
            {
                if ($f_bus_rec_pagos[estado_recibo]==1){
                    $i++;
                    $fecha_prox_cuota=$f_bus_rec_pagos[fecha_proxima_pago];
                }

                $j++;
					
                  
                ?>
                <tr>
                    <td class="tdtitulos"></td>
                    <td class="tdcampos"></td>
                    <td colspan=1 class="tdtitulos"> <hr> </hr> </td>
                    <td colspan=1 class="tdcampos"> <hr> </hr> </td>
                </tr>


                <tr>
                    <td class="tdtitulos"></td>
                    <td class="tdcampos"></td>
                    <td colspan=1 class="tdtitulos"> </td>
                    <td colspan=1 class="tdcampos">
                        <a href="#" OnClick="imprimir_recibo_pago('<?php echo $f_bus[nombre]?>','<?php echo $f_bus[rif]?>','<?php echo $f_bus[numero_contrato]?>','<?php echo $f_bus[num_recibo_prima]?>','<?php echo $f_bus_rec_pagos[concepto]?>','<?php echo $f_bus_rec_pagos[monto]?>','<?php echo $f_bus_rec_pagos[saldo_favor]?>','<?php echo $f_bus_rec_pagos[saldo_deudor]?>','<?php echo $f_bus_rec_pagos[fecha_pago]?>','<?php echo $f_bus_rec_pagos[fecha_proxima_pago]?>','<?php echo $f_bus_rec_pagos[nomenclatura]?>','<?php echo $f_bus_rec_pagos[numero_recibo]?>','<?php echo $f_bus_caract[sum]?>','<?php echo $f_bus[cuotacon] ?>','<?php echo $f_bus_rec_pagos[id_recibo_pago] ?>', '<?php echo $f_bus[direccion_cobro] ?>', '<?php echo $f_bus[id_comisionado] ?>');" id="imprimirrecibo"  title="Ver o Imprimir Recibo de Pago "class="boton"><?php echo $j?> Imprimir Recibo Num<?php echo $f_bus_rec_pagos[numero_recibo]?> </a>

                        <?php 
                        if ($f_mod_edo[permiso]=='1' ) {
                            ?>

                            <input type="text" size="10" id="fechaefectivam<?php echo $i ?>" name="fechaefectivam<?php echo $i ?>" class="campos" maxlength="10" value="<?php echo $f_bus_rec_pagos[fecha_efec_pago]?>" ><a href="#" OnClick="mod_fec_pago(<?php echo "$f_bus_rec_pagos[id_recibo_pago],$i " ?>);" id=",mod_fec_pag"  title="Modificar la Fecha Efectiva de Pago"class="boton">     M </a>

                            <?php
                        }
                        ?>

                        <?php list($fecha_creador)=explode(" ",$f_bus_rec_pagos[fecha_creado]);

                        if ($fecha_pago==$fecha_creador and $f_bus_rec_pagos[estado_recibo]==1 ) {
                            ?>
                            <a href="#" OnClick="anu_rec_pago(<?php echo $f_bus_rec_pagos[id_recibo_pago]  ?>,'<?php echo $f_bus_rec_pagos[concepto]?>');" id="anularrecibo"  title="Anular  Recibo de Pago Siempre y Cuando se el Mismo dia de Creacion"class="boton">     A </a>

                            <?php 
                        }

                        if ($f_bus_rec_pagos[estado_recibo]==0 ){
                            echo "Anulado";
                        }

                        ?>
                    </td>
                </tr>
      
      
                <?php
            }


// Recorremos todos los contratos buscados
foreach ($contratos as $contrato) {

    // Verificamos si el contrato es el seleccionado
    $es_contrato_seleccionado = ($contrato[1] == $numero_contrato);

    // Verificamos si está anulado
    $contrato_anulado = ($contrato[2] == 0);

    // Si el contrato seleccionado está anulado, terminamos la ejecución restante del código. Asi no se pueden generar mas recibos de pagos si el contrato está anulado.
    if ($es_contrato_seleccionado && $contrato_anulado) {
        exit();
    }
    
}


if ($f_bus_caract[sum] - $f_bus_pagos[sum]>0){
    ?>
    <tr>
        <td class="tdtitulos"></td>
        <td class="tdcampos"></td>
        <td colspan=1 class="tdtitulos"> <hr></hr></td>
        <td colspan=1 class="tdcampos"> <hr></hr></td>
    </tr>

    
		<?php 
    if ($f_reg_pag[permiso]=='1'){
        ?>
        <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>

            <td colspan=1 class="tdtitulos">Número de Cuotas
                <input class="campos" type="text" id="cuotacon" name="cuotacon" maxlength=10 size=5 value="<?php 
                echo  $f_bus[cuotacon]?>">
            </td>
            <td colspan=1 class="tdcampos">
                <a href="#" OnClick="act_ini_cuo();" id="act_ini_cuo" title="Actualizar la cantidad de cuotas de pagos escogido por un cliente"class="boton">Actualizar</a>
            </td>

        </tr>
    
        <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>
            <td colspan=1 class="tdtitulos"><hr></hr></td>
            <td colspan=1 class="tdcampos"> <hr></hr></td>
        </tr>

        <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>
            <td colspan=1 class="tdtitulos">Pago de Contado </td>
            <td colspan=1 class="tdcamposr">
                <a href="#" OnClick="pago_recibo(<?php echo ($f_bus_caract[sum] - $f_bus_pagos[sum] );?>);"  title="Seleccionar este Monto para Cancelar "class="boton_2"><?php echo number_format($f_bus_caract[sum] - $f_bus_pagos[sum]  ,2,',','');?>    </a>
                    
                <input class="campos" type="hidden" id="contado" name="contado" maxlength=10 size=5 value="<?php echo number_format($f_bus_caract[sum] - $f_bus_pagos[sum]  ,2,'.','');?>">   
            </td>
        </tr>

        <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>
            <td colspan=1 class="tdtitulos"></td>
            <td colspan=1 class="tdcamposr"> <hr></hr> </td>
        </tr>

        <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>

        <?php
        if (($f_bus_caract[sum] - $f_bus_pagos[sum]) < $f_bus_caract[sum]) {
            ?>

            <td colspan=1 class="tdtitulos">Pago por Cuota</td>

            <td colspan=1 class="tdcamposr">
                <a href="#" OnClick="pago_recibo(<?php echo  (($f_bus_caract[sum] - $f_bus_pagos[sum])/($f_bus[cuotacon]-($i-1)))?>);"  
                title="Seleccionar este Monto para Cancelar "class="boton_2"><?php 
                echo  number_format(($f_bus_caract[sum] - $f_bus_pagos[sum])/($f_bus[cuotacon]-($i-1))  ,2,',','')?>  </a>
                <input class="campos" type="hidden" id="cuota" name="cuota" maxlength=10 size=5 value="<?php 
                echo  number_format(($f_bus_caract[sum] - $f_bus_pagos[sum])/($f_bus[cuotacon]-($i-1)) ,2,'.','')?>">

                <?php
        } else {
            ?>
            <td colspan=1 class="tdtitulos">Pago con <?php echo $f_bus[inicialcon]?> % de Inicial </td>

            <td colspan=1 class="tdcamposr">
                <a href="#" OnClick="pago_recibo(<?php 
                $inicial=(($f_bus_caract[sum] * ($f_bus[inicialcon]/100)));
                echo  $inicial?>);"  title="Seleccionar este Monto para Cancelar "class="boton_2"><?php 
                echo  number_format($inicial  ,2,',','')?>  </a>
                <input class="campos" type="hidden" id="cuota" name="cuota" maxlength=10 size=5 value="<?php 
                echo  number_format($inicial  ,2,'.','')?>">
            </td>
            
            <?php
        }
        ?>
        
            </tr>
            </tr>


            <tr>
                <td class="tdtitulos"></td>
                <td class="tdcampos"></td>
                <td colspan=1 class="tdtitulos"></td>
                <td colspan=1 class="tdcamposr">
                <hr></hr> </td>
            </tr>


            <tr>
                <td class="tdtitulos"></td>
                <td class="tdcampos"></td>
                <td colspan=1 class="tdtitulos">Pago Mayor a una Cuota</td>
                <td colspan=1 class="tdcamposr">
                    <input class="campos" type="text" id="monto" name="monto" maxlength=10 size=8 value="">   
                    <a href="#" OnClick="bus_reg_rec_pago2();" id="cancelar"  title="Opcion para Abonar  Cuotas de un Recibo de Prima "class="boton">Pagar</a>
                </td>
            </tr>
            <?php
    }
}
    ?>
		
</table>
<div id="bus_regrec_pago2"></div>