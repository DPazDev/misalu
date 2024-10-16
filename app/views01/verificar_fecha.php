<?php
include ("../../lib/jfunciones.php");
sesion();
$fechacita=$_REQUEST['fechacita'];
$horacita=$_REQUEST['horacita'];
$id_proveedor=$_REQUEST['id_proveedor'];
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$permisos=0;
///PERMISOLOGIA DE USAURIO ALTA GERENCIA
$permisos=("select permisos.* from admin,permisos where permisos.id_admin=admin.id_admin  and permisos.permiso='1' and id_modulo=2 and admin.id_admin='$elid';");
$permiso=ejecutar($permisos);
$num_permisos=num_filas($permiso);
if($num_permisos>0){
	$per=asignar_a($permiso);
	$permisos=$per[permiso];
}


$dias=array("domingo","lunes","martes","miercoles" ,"jueves","viernes","sabado");
		$dia=substr($fechacita,8,2);
$mes=substr($fechacita,5,2);
$anio=substr($fechacita,0,4);
$errorc=0;
		$pru=strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);///DIA SELECIONADO
		//datos del proveedor y horarios
		$q_p=("select * from  s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and proveedores.id_proveedor=$id_proveedor");
		$r_p=ejecutar($q_p);
		$f_p=asignar_a($r_p);
		//Cantidad de consultas por dia
	    $cuantlunes  =  $f_p[nplunes];
	    $cuantmartes =  $f_p[npmartes];
	    $cuantmierco =  $f_p[npmiercole];
	    $cuantjueves =  $f_p[npjueve];
	    $cuantviernes = $f_p[npviernes];
	    $cuantsabado =  $f_p[npsabado];
	    $cuantdoming =  $f_p[npdomingo];
			$limitepacientes=999;
			////Ejecutar consulta de citas del dia selecionado
			$cuatcita=("select count(gastos_t_b.id_proceso) as cuantcita from gastos_t_b,procesos where gastos_t_b.id_proveedor=$id_proveedor and gastos_t_b.fecha_cita='$fechacita' and
			procesos.id_proceso = gastos_t_b.id_proceso and
			procesos.id_estado_proceso <>14 and (gastos_t_b.descripcion<>'SERVICIOS POR TERCEROS' and gastos_t_b.nombre<>'SERVICIOS POR TERCEROS');");
			$repcuantcita=ejecutar($cuatcita);

//Si el dia Selecionado es LUNES
		if ($pru=="LUNES")
		{
			if ($f_p[lunes]==0)
			{
				echo "EL DIA LUNES NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}


			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantlunes==0 and $f_p[lunes]==1){$cuantlunes=$limitepacientes; }
			if(($totalcital >= $cuantlunes) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
//Si el dia Selecionado es MARTES
		if ($pru=="MARTES")
		{
			if ($f_p[martes]==0)
			{
				echo "EL DIA MARTES NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$repcuantcita=ejecutar($cuatcita);
			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantmartes==0 and $f_p[martes]==1){$cuantmartes=$limitepacientes; }
			if(($totalcital >= $cuantmartes) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
//Si el dia Selecionado es MIERCOLES
			if ($pru=="MIERCOLES")
		{
			if ($f_p[miercoles]==0)
			{
				echo "EL DIA MIERCOLES NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantmierco==0 and $f_p[miercoles]==1){$cuantmierco=$limitepacientes; }

			if(($totalcital >= $cuantmierco) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
//Si el dia Selecionado es JUEVES
			if ($pru=="JUEVES")
		{
		if ($f_p[jueves]==0)
			{
				echo "EL DIA JUEVES NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantjueves==0 and $f_p[jueves]==1){$cuantjueves=$limitepacientes; }

			if(($totalcital >= $cuantjueves) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
//Si el dia Selecionado es VIERNES
		if ($pru=="VIERNES")
		{
			if ($f_p[viernes]==0)
			{
				echo "EL DIA VIERNES NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$repcuantcita=ejecutar($cuatcita);
			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			if($cuantviernes==0 and $f_p[viernes]==1){$cuantviernes=$limitepacientes; }

			if(($totalcital >= $cuantviernes) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
//Si el dia Selecionado es SABADO
		if ($pru=="SABADO")
		{
			if ($f_p[sabado]==0)
			{
				echo "EL DIA SABADO NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$repcuantcita=ejecutar($cuatcita);
			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantsabado==0 and $f_p[sabado]==1){$cuantsabado=$limitepacientes; }

			if(($totalcital >= $cuantsabado) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}
		if ($pru=="DOMINGO")
		{
			if ($f_p[domingo]==0)
			{
				echo "EL DIA DOMINGO NO PASA CONSULTA ESTE PROVEEDOR";
				$errorc=1;
			}

			$repcuantcita=ejecutar($cuatcita);
			$datacuantcita=assoc_a($repcuantcita);
			$totalcital=$datacuantcita[cuantcita];
			///si el dia esta activo y la cantidad es 0 la consulta no tine limite diario
			if($cuantdoming==0 and $f_p[domingo]==1){$cuantdoming=$limitepacientes; }

			if(($totalcital >= $cuantdoming) and ($totalcital >0)){
			  echo "No hay citas disponible para el d&iacute;a ($pru <-> $fechacita No. CITAS RESERVADAS ($totalcital)) CUPOS NO DISPONIBLE!!!!";
			  $errorc=1;
			}
		}

$q_fechas="select * from tbl_fechas order by tbl_fechas.id_tipo_fecha";
$r_fechas=ejecutar($q_fechas);

while($f_fechas=asignar_a($r_fechas,NULL,PGSQL_ASSOC))
{
if (($fechacita>="$anio-$f_fechas[mes_inicio]-$f_fechas[dia_inicio]" and $fechacita<="$anio-$f_fechas[mes_final]-$f_fechas[dia_final]" and $f_fechas[id_tipo_fecha]==1)  || ($fechacita>="$anio-$f_fechas[mes_inicio]-$f_fechas[dia_inicio]" and $fechacita<="$anio-$f_fechas[mes_final]-$f_fechas[dia_final]" and $f_fechas[id_tipo_fecha]>=2 and $f_fechas[id_persona]==$id_proveedor)  )
{

	 if ($f_fechas[id_tipo_fecha]==1)
	{
		$tipo_fecha="Dias Feriados";
		}
		if ($f_fechas[id_tipo_fecha]==2)
	{
		$tipo_fecha="Permisos";
		}
		if ($f_fechas[id_tipo_fecha]==3)
	{
		$tipo_fecha="Vacaciones";
		}
echo " $tipo_fecha DESDE $f_fechas[dia_inicio]-$f_fechas[mes_inicio] HASTA $f_fechas[dia_final]-$f_fechas[mes_final] ($f_fechas[comentarios])";
	}
else
	{
	}
}

if($permisos==1){
 $errorc=0;
}

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<br>
 <tr>
 <br>
     <td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <?php if($errorc==0){?>
     <td class="tdtitulos"> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" OnClick="guardar_cita();" class="boton">Guardar Citas</a><br></td>
    <?php }?>
 </tr>
</table>
