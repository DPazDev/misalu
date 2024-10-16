<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
?>
<script language="JavaScript">

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}
</script>
<?php

list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

if ($tipo_ente==3) {
    $titularesvar="procesos.id_beneficiario=0 and";
    }
    else
    {
        $titularesvar="procesos.id_beneficiario>=0 and";
        }
$clave = $_REQUEST['clave'];
$proceso = $_REQUEST['proceso'];
$planilla = $_REQUEST['planilla'];
$entes = $_REQUEST['entes'];
$dateField1 = $_REQUEST['dateField1'];
$dateField2 = $_REQUEST['dateField2'];
$dateField3 = $_REQUEST['dateField3'];
$dateField4 = $_REQUEST['dateField4'];

 $concepto = utf8_decode(strtoupper($_REQUEST['concepto']));
$no_factura = intval($_REQUEST['factura']);
$serie = $_REQUEST['serie'];
$estado_fac = $_REQUEST['estado_fac'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$sucursal = $_REQUEST['sucursal'];
$servicios = $_REQUEST['servicios'];
$controlfactura = $_REQUEST['controlfactura'];


$partidas = $_REQUEST['partidas'];
/* **** verifico si hago la busqueda tomando en cuenta la partida si la facturacion es para la gobernacion **** */
if ($partidas>0) {
    $tpartida=" titulares.tipo_partida='$partidas' and";

    }
    else
    {
        $tpartida="";
        }
/* **** fin verifico si hago la busqueda tomando en cuenta la partida si la facturacion es para la gobernacion **** */
if ($sucursal=='**'){
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal<>'2'";
}
else
{
if ($sucursal=='*'){
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal>0";
}
else
{
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal=$sucursal";
}
}

if ($servicios=='*'){
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio>0";
}
else
{
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio=$servicios";

}






if(!empty($dateField5) && $forma_pago==2){
	$Cfecha_credito="fecha_credito='$dateField5',";
	$Cestado_factura="id_estado_factura='2',";
}


if(!empty($banco) && !empty($no_cheque) && ($forma_pago==3 || $forma_pago==4 || $forma_pago==5 || $forma_pago==7 )){
	$Cbanco="id_banco='$banco',";
	$Ccheque="numero_cheque='$no_cheque',";

}

$codigo=time();
$fecha_emision=$dateField3;
//busco si ya existe esa factura en la bd.
		 if ($servicios=="*")
    {
        $servicios=0;
        }
$mod_factura="update tbl_facturas set

					       fecha_emision='$fecha_emision',
					       $Cfecha_credito
					        concepto='$concepto',
							con_ente='$entes',
                            tipo_ente='$tipo_ente',
                            servicio='$servicios',
							numcontrol='$controlfactura',
                            comen_fact='$comen_fact'
where tbl_facturas.numero_factura='$no_factura' and tbl_facturas.id_serie='$serie'
";
	$fmod_factura=ejecutar($mod_factura);


	$q_factura="(select id_factura from tbl_facturas where tbl_facturas.numero_factura='$no_factura' and tbl_facturas.id_serie=$serie)";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];
	$eliminar_procesos="delete from tbl_procesos_claves where tbl_procesos_claves.id_factura=$id_factura";
	$reliminar_procesos=ejecutar($eliminar_procesos);

	//verifico los procesos.
	if (!empty($proceso))
	{
	$q = "select * From procesos where  procesos.id_proceso='$proceso'";
	}
	else
	{


 if (!empty($clave))
                {
                        if ($dateField1<>""){
                                $fecha="and procesos.fecha_ent_pri>='$dateField1' and procesos.fecha_ent_pri<='$dateField2'";
                                }
                                else
                                {
                                        $fecha="";
                                        }

                $q = "select * From procesos where  procesos.no_clave='$clave' $fecha";
                }
		else
		{
			if (!empty($planilla))
			{
			$q = "select * From procesos where  procesos.nu_planilla='$planilla'";
			}
			else
			{
				if (!empty($entes))
				{
				$q = "select procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso,  procesos.pro_deducible,
count(gastos_t_b.id_proceso)
From procesos,admin,titulares,gastos_t_b where procesos.fecha_ent_pri>='$dateField1' and procesos.fecha_ent_pri<='$dateField2'
and procesos.id_titular=titulares.id_titular and titulares.id_ente='$entes' $servicios1 $sucursal1
group by procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso,  procesos.pro_deducible";
}
                else
                {
                	if (!empty($tipo_ente))
				{
                /* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==4) {
$q= "select
                                      procesos.id_proceso,
                                    count(gastos_t_b.id_proceso)
                            from
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin
                            where
                                    procesos.id_proceso=gastos_t_b.id_proceso and
                                    gastos_t_b.id_servicio=$servicios and
                                    gastos_t_b.fecha_cita>='$dateField1' and
                                    gastos_t_b.fecha_cita<='$dateField2' and
                                    procesos.id_titular=titulares.id_titular and
                                    titulares.id_ente=entes.id_ente and
                                    entes.id_tipo_ente=$tipo_ente and
                                    procesos.id_admin=admin.id_admin and
                                    admin.id_sucursal=$sucursal and
                                    $titularesvar
$tpartida
                                    (procesos.id_estado_proceso=7 or
                                    procesos.id_estado_proceso=2 or
                                    procesos.id_estado_proceso=10 or
                                    procesos.id_estado_proceso=11 or
                                    procesos.id_estado_proceso=15 or
                                    procesos.id_estado_proceso=16 )
                            group by
                                    procesos.id_proceso";
}
/*fin de  comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/
/* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==6 || $servicios==9) {

$q= "select
                                    procesos.nu_planilla,
                                    count(procesos.nu_planilla)
                            from
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin
                            where
                                    procesos.id_proceso=gastos_t_b.id_proceso and
                                    gastos_t_b.id_servicio=$servicios and
                                    procesos.fecha_recibido>='$dateField1 ' and
                                    procesos.fecha_recibido<='$dateField2' and
                                    procesos.id_titular=titulares.id_titular and
                                    titulares.id_ente=entes.id_ente and
                                    entes.id_tipo_ente=$tipo_ente and
                                    procesos.id_admin=admin.id_admin and
                                    admin.id_sucursal=$sucursal and
                                    procesos.nu_planilla>'0' and
                                    $titularesvar
$tpartida
                                    (procesos.id_estado_proceso=7 or
                                    procesos.id_estado_proceso=2 or
                                    procesos.id_estado_proceso=10 or
                                    procesos.id_estado_proceso=11 or
                                    procesos.id_estado_proceso=15 or
                                    procesos.id_estado_proceso=16 )
                            group by
                                    procesos.nu_planilla";
}
}
				}
			}
		}
	}


	$r_procesos = ejecutar($q);
//Busco los procesos que estan afiliados a la clave.
	pg_result_seek($r_procesos,0);
 if (!empty($tipo_ente))
    {
          if ($servicios==4) {
	while($f_proceso = asignar_a($r_procesos)){
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_proceso[id_proceso]");

	$r_gastos=ejecutar($q_gastos);
			$monto=0;
			while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}

		$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto)
					values('$f_proceso[id_proceso]',
					       '$f_proceso[no_clave]',
					       '$id_factura',
							'$monto');";

		$f_procesos_claves=ejecutar($r_procesos_claves);
		}
}

 if ($servicios==6 || $servicios==9) {


	while($f_proceso = asignar_a($r_procesos)){


        $q_pro_num= "select
                                    procesos.id_proceso,
                                    procesos.no_clave,
                                    procesos.pro_deducible
                            from

                                    procesos

                            where
                                   procesos.nu_planilla='$f_proceso[nu_planilla]' and
                                    (procesos.id_estado_proceso=7 or
                                    procesos.id_estado_proceso=2 or
                                    procesos.id_estado_proceso=10 or
                                    procesos.id_estado_proceso=11 or
                                    procesos.id_estado_proceso=15 or
                                    procesos.id_estado_proceso=16 )
                            ";

        $r_pro_num = ejecutar($q_pro_num);

        	while($f_pro_num = asignar_a($r_pro_num)){
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_pro_num[id_proceso]");

	$r_gastos=ejecutar($q_gastos);
			$monto=0;
			while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}

		$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto,
                            fac_deducible)
					values('$f_pro_num[id_proceso]',
					       '$f_pro_num[no_clave]',
					       '$id_factura',
							'$monto',
                            '$f_pro_num[pro_deducible]' );";

		$f_procesos_claves=ejecutar($r_procesos_claves);
		}
        }
}
        }
        else
        {
	while($f_proceso = asignar_a($r_procesos)){
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_proceso[id_proceso]");

	$r_gastos=ejecutar($q_gastos);
			$monto=0;
			while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}
		$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto,
                            fac_deducible)
					values('$f_proceso[id_proceso]',
					       '$f_proceso[no_clave]',
					       '$id_factura',
							'$monto',
                            '$f_proceso[pro_deducible]');";

		$f_procesos_claves=ejecutar($r_procesos_claves);
		}
		}
		/* **** Se registra lo que hizo el usuario**** */

$log="Actualizo la Factura numero $no_factura forma de pago $forma_pago
";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */

?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
	<tr><td colspan=4  class="titulo_seccion"> Datos de la Factura
<?php $url="'views04/ifactura.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Cliente"> Formato 1     <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/ifactura2.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Ente si no Escogio el Ente al Registrarla "> Formato 2    <?php echo "$no_factura Serie $serie" ?></a>



<?php $url="'views04/irelacion.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Relacion de Gastos de la Factura"> Imprimir Relacion</a>

</td>
	</tr>
    <tr>
		<td align="right" colspan=4  class="titulo_seccion">Formatos Gobernacion
<?php $url="'views04/ifacturagob.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura"> Formato 1 <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/ifacturagob2.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura Formato Clinica">  Formato 2</a>

<?php $url="'views04/ifacturagob3.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura Formato Clinica cuentas por tercero">  Formato   3</a>

<?php $url="'views04/irelafacturagob.php?factura=$no_factura&serie=$serie&servicios=$servicios','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Relacion de Factura"> Relacion  Gobernacion<?php echo "$no_factura Serie $serie" ?></a>

</td>
	</tr>


	</table>
