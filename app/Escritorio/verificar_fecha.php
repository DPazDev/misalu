<?php
include ("../../lib/jfunciones.php");
sesion();
$fechacita=$_REQUEST['fechacita'];
$fechacita=$_REQUEST['fechacita'];
$id_proveedor=$_REQUEST['id_proveedor'];
$dias=array("domingo","lunes","martes","miercoles" ,"jueves","viernes","sabado");
		$dia=substr($fechacita,8,2);
$mes=substr($fechacita,5,2);
$anio=substr($fechacita,0,4);
		$pru=strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);

			$q_p=("select * from  s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and proveedores.id_proveedor=$id_proveedor");
		$r_p=ejecutar($q_p);
		$f_p=asignar_a($r_p);
	
		if ($pru=="LUNES")
		{
			if ($f_p[lunes]==0)
			{
				echo "EL DIA LUNES NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
		if ($pru=="MARTES")
		{
			if ($f_p[martes]==0)
			{
				echo "EL DIA MARTES NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
			if ($pru=="MIERCOLES")
		{
			if ($f_p[miercoles]==0)
			{
				echo "EL DIA MIERCOLES NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
			if ($pru=="JUEVES")
		{
		if ($f_p[jueves]==0)
			{
				echo "EL DIA JUEVES NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
		if ($pru=="VIERNES")
		{
			if ($f_p[viernes]==0)
			{
				echo "EL DIA VIERNES NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
		if ($pru=="SABADO")
		{
			if ($f_p[sabado]==0)
			{
				echo "EL DIA SABADO NO PASA CONSULTA ESTE PROVEEDOR";
			}
		}
		if ($pru=="DOMINGO")
		{
			if ($f_p[domingo]==0)
			{
				echo "EL DIA DOMINGO NO PASA CONSULTA ESTE PROVEEDOR";
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

?>
