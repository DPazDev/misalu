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
$registrar= $_REQUEST['registrar'];
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
$dateField5 = $_REQUEST['dateField5'];
$forma_pago = $_REQUEST['forma_pago'];
$tipo_moneda = $_REQUEST['tipo_moneda'];
$nom_tarjeta = $_REQUEST['nom_tarjeta'];
$banco = $_REQUEST['banco'];
$no_cheque = $_REQUEST['no_cheque'];
$nombre_p_pago=$_REQUEST['nombre_p_pago'];
$cedula_p_pago=$_REQUEST['cedula_p_pago'];
$telf_p_pago=$_REQUEST['telf_p_pago'];
$correo_p_pago=$_REQUEST['correo_p_pago'];
$concepto = utf8_decode(strtoupper($_REQUEST['concepto']));
$no_factura = $_REQUEST['factura'];
$serie = $_REQUEST['serie'];
$controlfactura = $_REQUEST['controlfactura'];
$monto = $_REQUEST['monto'];
$descuento = $_REQUEST['descuento'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$sucursal = $_REQUEST['sucursal'];
$servicios = $_REQUEST['servicios'];
////CONSULTA CAMBIO DEL DOLAR DEL DIAS
$CambioDolar= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='2' order by id_moneda_cambio desc,fecha_cambio desc limit 1");
$DolarActual=ejecutar($CambioDolar);
$CambioDolar=asignar_a($DolarActual);
 $IdCambioDivisa=$CambioDolar['id_moneda_cambio'];
////ID_CAMBIO DEL DOLAR

$partidas = $_REQUEST['partidas'];
/* **** verifico si hago la busqueda tomando en cuenta la partida si la facturacion es para la gobernacion **** */
if ($partidas>0) {
    $tpartida="titulares.tipo_partida='$partidas' and";

    }
    else
    {
        $tpartida="";
        }
/* **** fin verifico si hago la busqueda tomando en cuenta la partida si la facturacion es para la gobernacion **** */

/* *** variables operaciones multiples*** */
$forma_pago1    = $_REQUEST['forma_pago1'];
$nom_tarjeta1   = $_REQUEST['nom_tarjeta1'];
$banco1         = $_REQUEST['banco1'];
$no_cheque1     = $_REQUEST['no_cheque1'];
$tipo_moneda1   = $_REQUEST['tipo_moneda1'];
$monto1         = $_REQUEST['monto1'];
$nombre_p_pago1 = $_REQUEST['nombre_p_pago1'];
$cedula_p_pago1 = $_REQUEST['cedula_p_pago1'];
$telf_p_pago1   = $_REQUEST['telf_p_pago1'];
$correo_p_pago1 = $_REQUEST['correo_p_pago1'];

$forma_pago2   = $_REQUEST['forma_pago2'];
$nom_tarjeta2  = $_REQUEST['nom_tarjeta2'];
$banco2        = $_REQUEST['banco2'];
$no_cheque2    = $_REQUEST['no_cheque2'];
$tipo_moneda2  = $_REQUEST['tipo_moneda2'];
$monto2        = $_REQUEST['monto2'];
$nombre_p_pago2 = $_REQUEST['nombre_p_pago2'];
$cedula_p_pago2 = $_REQUEST['cedula_p_pago2'];
$telf_p_pago2   = $_REQUEST['telf_p_pago2'];
$correo_p_pago2 = $_REQUEST['correo_p_pago2'];

$forma_pago3   = $_REQUEST['forma_pago3'];
$nom_tarjeta3  = $_REQUEST['nom_tarjeta3'];
$banco3        = $_REQUEST['banco3'];
$no_cheque3    = $_REQUEST['no_cheque3'];
$tipo_moneda3  = $_REQUEST['tipo_moneda3'];
$monto3        = $_REQUEST['monto3'];
$nombre_p_pago3 = $_REQUEST['nombre_p_pago3'];
$cedula_p_pago3 = $_REQUEST['cedula_p_pago3'];
$telf_p_pago3   = $_REQUEST['telf_p_pago3'];
$correo_p_pago3 = $_REQUEST['correo_p_pago3'];


$forma_pago4   = $_REQUEST['forma_pago4'];
$nom_tarjeta4  = $_REQUEST['nom_tarjeta4'];
$banco4        = $_REQUEST['banco4'];
$no_cheque4    = $_REQUEST['no_cheque4'];
$tipo_moneda4  = $_REQUEST['tipo_moneda4'];
$monto4        = $_REQUEST['monto4'];
$nombre_p_pago4 = $_REQUEST['nombre_p_pago4'];
$cedula_p_pago4 = $_REQUEST['cedula_p_pago4'];
$telf_p_pago4   = $_REQUEST['telf_p_pago4'];
$correo_p_pago4 = $_REQUEST['correo_p_pago4'];


/* *** fin de variables operaciones multiples*** */
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

if ($forma_pago<>6){

if(!empty($dateField5) && $forma_pago==2){
	$Cfecha_credito="fecha_credito,";
	$Vfecha_credito="'$dateField5',";
	$Cestado_factura="id_estado_factura,";
	$Vestado_factura="'2', ";

}


if(!empty($banco) && !empty($no_cheque) && ($forma_pago==3 || $forma_pago==5 || $forma_pago==4 || $forma_pago==10 || $forma_pago==7)){
	$Cbanco="id_banco,";
	$Vbanco="'$banco', ";
	$Ccheque="numero_cheque,";
	$Vcheque="'$no_cheque',";

}

}

$codigo=time();

//busco si ya existe esa factura en la bd.
$q="select * from tbl_facturas where numero_factura='$no_factura' and id_serie=$serie;";
$r=ejecutar($q) or die($q);
$q_factura="";
if(num_filas($r)==0){
	//no existe, hay que insertarla.
	$fecha_emision=$dateField3;

	
    /* **** se compara si registrar es igual a uno es porque se va aregistrar una factura que pertene a un cuadro de recibo de prima *****/
if ($registrar==1){

	$monto = $_REQUEST['tprima'];
	$id_recibo_contrato = $_REQUEST['id_recibo_contrato'];
	$q_ente = "(select
					*
				from
					tbl_contratos_entes,
					tbl_recibo_contrato
				where
					tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
					tbl_recibo_contrato.id_recibo_contrato='$id_recibo_contrato')";
	$r_ente=ejecutar($q_ente);
	$f_ente=asignar_a($r_ente);
    $r_factura="insert into tbl_facturas (id_serie,
                            id_admin,
                            id_estado_factura,
                            condicion_pago,
                            fecha_emision,
                            codigo,
                            concepto,
                            numero_factura,
                            con_ente,
							id_nom_tarjeta,
							numcontrol,
                            id_recibo_contrato,id_moneda_cambio)
					values('$serie',
                            '$id_admin',
                            '1',
                            '8',
                            '$fecha_emision',
                            '$codigo',
                            '$concepto',
                            '$no_factura',
                            '$f_ente[id_ente]',
                            '0',
                            '$controlfactura',
                            '$id_recibo_contrato','$IdCambioDivisa');";
	$f_factura=ejecutar($r_factura);
	$q_factura="(select id_factura from tbl_facturas where codigo='$codigo' and id_admin='$id_admin')";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];

    	$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto)
					values('0',
					       '0',
					       '$id_factura',
							'$monto');";

		$f_procesos_claves=ejecutar($r_procesos_claves);

    $r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       id_nom_tarjeta,
                            numero_cheque,
						    monto,
							condicion_pago)
					values('$id_factura',
						    '0',
					        '0',
						    '0',
					        '$monto',
							'8'	);";
	$r_facturaom=ejecutar($r_facturaom);
    }
    else
    {
        }
/* **** frin de registrar  una factura que pertene a un cuadro de recibo de prima *****/

if ($forma_pago<>6){
    if ($servicios=="*")
    {
        $servicios=0;
        }
	$r_factura="insert into tbl_facturas (id_serie,
	 				       numero_factura,
					       condicion_pago,
					       fecha_emision,
					       $Cfecha_credito
					      $Cestado_factura
					       id_admin,
							codigo,
							concepto,
							con_ente,
							id_nom_tarjeta,
							numcontrol,
                            tipo_ente,
                            servicio,id_moneda_cambio,descuento)
					values('$serie',
					       '$no_factura',
					       '$forma_pago',
					       '$fecha_emision',
					       $Vfecha_credito
					       $Vestado_factura
					       '$id_admin',
					       '$codigo',
							'$concepto',
							'$entes',
						'$nom_tarjeta',
						'$controlfactura',
                        '$tipo_ente',
                        '$servicios','$IdCambioDivisa','$descuento');";
	$f_factura=ejecutar($r_factura);
	$q_factura="(select id_factura from tbl_facturas where codigo='$codigo' and id_admin='$id_admin')";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];

   $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];



 $r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       $Cbanco
					       $Ccheque
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
							nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago)
					values('$id_factura',
						    $Vbanco
					        $Vcheque
						    '$nom_tarjeta',
					        '$monto',
							'$forma_pago',
							'$tipo_moneda',
							'$id_moneda_cambio',
							'$nombre_p_pago',
							'$cedula_p_pago',
							'$telf_p_pago',
							'$correo_p_pago'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	else
	{
		 if ($servicios=="*")
    {
        $servicios=0;
        }

		$r_factura="insert into tbl_facturas (id_serie,
	 				       numero_factura,
					       condicion_pago,
					       fecha_emision,
					       id_admin,
							codigo,
							concepto,
							con_ente,
							numcontrol,
                            tipo_ente,
                            servicio,id_moneda_cambio,descuento)
					values('$serie',
					       '$no_factura',
					       '$forma_pago',
					       '$fecha_emision',
					       '$id_admin',
					       '$codigo',
						   '$concepto',
						   '$entes',
						   '$controlfactura',
                            '$tipo_ente',
                            '$servicios','$IdCambioDivisa','$descuento');";
	$f_factura=ejecutar($r_factura);
	$q_factura="(select id_factura from tbl_facturas where codigo='$codigo' and id_admin='$id_admin')";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];

		if ($monto1>0){
			if ($forma_pago1==1){
				$no_cheque1=0;
				$nom_tarjeta1=0;
				}
  $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda1' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

	$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
							nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago)
					values('$id_factura',
						    '$banco1',
					        '$no_cheque1',
						    '$nom_tarjeta1',
					        '$monto1',
							'$forma_pago1',
							'$tipo_moneda1',
                            '$id_moneda_cambio',
                            '$nombre_p_pago1',
                            '$cedula_p_pago1',
                            '$telf_p_pago1',
                            '$correo_p_pago1'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto2>0){
			if ($forma_pago2==1){
				$no_cheque2=0;
				$nom_tarjeta2=0;
				}
     $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda2' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

	$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
						    nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago)
					values('$id_factura',
						    '$banco2',
					        '$no_cheque2',
						    '$nom_tarjeta2',
					        '$monto2',
							'$forma_pago2',
							'$tipo_moneda2',
							'$id_moneda_cambio',
							'$nombre_p_pago2',
							'$cedula_p_pago2',
							'$telf_p_pago2',
							'$correo_p_pago2'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto3>0){
			if ($forma_pago3==1){
				$no_cheque3=0;
				$nom_tarjeta3=0;
				}
	$moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda3' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];
		 $r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
		                    nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago)
					values('$id_factura',
						    '$banco3',
					        '$no_cheque3',
						    '$nom_tarjeta3',
					        '$monto3',
							'$forma_pago3',
							'$tipo_moneda3',
							'$id_moneda_cambio',
					        '$nombre_p_pago3',
							'$cedula_p_pago3',
							'$telf_p_pago3',
							'$correo_p_pago3');";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto4>0){
			if ($forma_pago4==1){
				$no_cheque4=0;
				$nom_tarjeta4=0;
				}
    $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda4' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
							nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago)
					values('$id_factura',
						    '$banco4',
					        '$no_cheque4',
						    '$nom_tarjeta4',
					        '$monto4',
							'$forma_pago4',
							'$tipo_moneda4',
							'$id_moneda_cambio',
						    '$nombre_p_pago4',
							'$cedula_p_pago4',
							'$telf_p_pago4',
							'$correo_p_pago4'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
		}
}else{

	$f_factura=asignar_a($r);
	$id_factura=$f_factura['id_factura'];
}

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
				         $q = "select procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso,procesos.pro_deducible,count(gastos_t_b.id_proceso)
From procesos,admin,titulares,gastos_t_b where procesos.fecha_ent_pri>='$dateField1' and procesos.fecha_ent_pri<='$dateField2'
and procesos.id_titular=titulares.id_titular and titulares.id_ente='$entes' $servicios1 $sucursal1
group by procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso,procesos.pro_deducible";

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

$log="REGISTRO la Factura numero $no_factura";
logs($log,$ip,$id_admin);
/* **** Fin de lo que hizo el usuario **** */
/* **** verifico si los procesos guardados en tbl_procesos_claves estan en aprobado operador si  esta se coloca en candidato a pago **** */
$q_pro_clave=("select
								procesos.id_proceso
						from
								procesos,
								tbl_procesos_claves
						where
								procesos.id_estado_proceso=2 and
								procesos.id_proceso=tbl_procesos_claves.id_proceso and
								tbl_procesos_claves.id_factura='$id_factura'
								");
$r_pro_clave=ejecutar($q_pro_clave);
while($f_pro_clave = asignar_a($r_pro_clave)){


$mod_fpro="update
									procesos
						set
									id_estado_proceso=7
						where
									procesos.id_proceso=$f_pro_clave[id_proceso]";
$fmod_fpro=ejecutar($mod_fpro);

}
/* **** fin verifico si los procesos guardados en tbl_procesos_claves estan en aprobado operador si  esta se coloca en candidato a pago**** */

?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">

<tr><td colspan=4  class="titulo_seccion"> Datos de la Factura
<?php $url="'views04/ifactura.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Cliente"> Formato 1     <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/ifactura2.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Ente si no Escogio el Ente al Registrarla "> Formato 2    <?php echo "$no_factura Serie $serie" ?></a>


<?php $url="'views04/ifactura2USD.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Cliente USD"> Formato USD</a>

<?php $url="'views04/ifactura_iva.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con IVA"> Formato IVA</a>

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
