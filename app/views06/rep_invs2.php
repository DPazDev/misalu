<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_tipo_insumo,$tipo_insumo)=explode("@",$_REQUEST['tipo_insumo']);

$letra = $_REQUEST['letra'];
$signo = $_REQUEST['signo'];
$cantidad = $_REQUEST['cantidad'];
$dependencia=$_REQUEST['dependencia'];
$elid=$_SESSION['id_usuario_'.empresa];
$id_laboratorio = $_REQUEST['laboratorio'];
$tipo_insumo = $_REQUEST['tipo_insumo'];
$monto = $_REQUEST['monto'];
$noestaendepen=0;
$versiesdoctor=("select departamentos.id_departamento from departamentos,admin where (departamentos.id_departamento=4 or departamentos.id_departamento=3 or departamentos.id_departamento=15 ) and
departamentos.id_departamento=admin.id_departamento and
admin.id_admin=$elid");
$repesdoctor=ejecutar($versiesdoctor);
$datesdoctor=assoc_a($repesdoctor);
$cualdepen=$datesdoctor['id_departamento'];
if($cualdepen==4 || $cualdepen==3 || $cualdepen==15) {
  $noestaendepen=1;
}
$quedependecia=("select * from tbl_admin_dependencias where id_admin=$elid order by id_dependencia;");
$repquedependicia=ejecutar($quedependecia);
while($lasdepen=asignar_a($repquedependicia,NULL,PGSQL_ASSOC)){
	$estaendepen=$lasdepen['id_dependencia'];
	if($estaendepen == $dependencia){
		$noestaendepen=1;
		break;
		}
}
if($noestaendepen == 0){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">No esta autorizado para ver Inventario de otras dependencias!!!</td>
     </tr>
</table>
<?}else{

$q_dependencia=("select * from tbl_dependencias where id_dependencia=$dependencia");
$r_dependencia=ejecutar($q_dependencia);
$f_dependencia=asignar_a($r_dependencia);

$condicion_tipo_insumo="";
if($id_tipo_insumo!=0){
	$condicion_tipo_insumo=" tbl_tipos_insumos.id_tipo_insumo=$id_tipo_insumo and";
}

$condicion_letra="";
if($letra!="0"){
	$condicion_letra = "tbl_insumos.insumo like '$letra%' and";
}

$condicion_cantidad="";
if(is_numeric($cantidad) && $signo!="0"){
	$condicion_cantidad="tbl_insumos_almacen.cantidad $signo $cantidad and";
}

$condicion_laboratorio="";
if($id_laboratorio!="0"){
	$condicion_laboratorio="tbl_insumos.id_laboratorio=$id_laboratorio and";
}

$q_inventario = "select tbl_insumos_almacen.monto_unidad_publico,tbl_insumos_almacen.id_moneda,tbl_insumos.id_insumo,tbl_insumos.insumo,
                          tbl_insumos.id_insumo,tbl_insumos_almacen.cantidad, tbl_laboratorios.laboratorio,
						tbl_tipos_insumos.id_tipo_insumo
                   from tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos
                      where
                   tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio and
                $condicion_tipo_insumo tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
                $condicion_laboratorio $condicion_cantidad $condicion_letra tbl_insumos_almacen.id_dependencia=$dependencia
                and tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo
              order by tbl_insumos.insumo, tbl_tipos_insumos.tipo_insumo asc;";
$r_inventario = ejecutar($q_inventario);

?>


<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 align="center" class="titulo_seccion">Inventario de Dependencia <?php echo $f_dependencia[dependencia] ?></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
	<tr>
		<?php
		if(num_filas($r_inventario)==0){
		echo "
	<td colspan=4 align=\"center\" class=\"titulo_seccion\">No hay resultados con esos parametros.</td>
		</tr>
		";
		}else{
		?>
	<table id='colortable'  border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
		<tr>
			<td class="tdcamposc"><b>Nombre</b></td>
			<td class="tdcamposc"><b>Cantidad</b></td>
			<td class="tdcamposc"><b>Costo <br>Por Unidad</td>
			<td class="tdcamposc"><b>Costo <br>Por Unidad BS</b></td>
			<td class="tdcamposc"><b>Marca</b></td>
		</tr>
		<?php
		$monto_total_final = 0;
		$monto_unidad_final = 0;
		$cantidad_final = 0;
		while($f=pg_fetch_assoc($r_inventario)){
			$total = $f['cantidad'] * $f['monto_unidad_cs'];

			$monto_total_final += $total;
			$monto_unidad_final += $f['monto_unidad_cs'];
			$cantidad_final += $f['cantidad'];

			$total = montos_print($total);
			$monto_unidad = $f['monto_unidad_publico'];
      $idMoneda = $f['id_moneda'];


      if ($f[id_tipo_insumo]==2 and $dependencia<>89){ //SUMINISTRO HOSPITALARIO
        ///1)Buscar el precio Almacen Control de precios id=2
      			$q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=2");
      			$r_precio=ejecutar($q_precio);
        ///2) Si no se encuentra busca el precio en el Almacen temporal merida
      				if(num_filas($r_precio)==0){
      					   $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=89");
      			       $r_precio=ejecutar($q_precio);
      					}
        ///3) Si no se encuentra busca el precio del almacen actual
   	           if(num_filas($r_precio)==0){
      					   $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.id_dependencia=$dependencia");
      			       $r_precio=ejecutar($q_precio);
      					}

      			$f_precio=asignar_a($r_precio);
            ///////Aplicar CAmbio

      			$monto_unidad = $f_precio['monto_unidad_publico'];
            $idMoneda = $f_precio['id_moneda'];
      }

			if ($f['id_tipo_insumo']==1 and $dependencia<>64){//MEDICAMENTOS
        ///1)Buscar el precio Almacen Control de precios id=2
            $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=2");
            $r_precio=ejecutar($q_precio);
        ///2) Si no se encuentra busca el precio en el FARMACIA merida
              if(num_filas($r_precio)==0){
                   $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=64");
                   $r_precio=ejecutar($q_precio);
                }
        ///3) Si no se encuentra busca el precio del almacen actual
               if(num_filas($r_precio)==0){
                   $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.id_dependencia=$dependencia");
                   $r_precio=ejecutar($q_precio);
                }
      $f_precio=asignar_a($r_precio);

			$monto_unidad = $f_precio['monto_unidad_publico'];
      $idMoneda = $f_precio['id_moneda'];
			}
      ////APLICAR CONTROL cambiario
      ///CALCULO DEL CAMBIO
      $idMoneda=$idMoneda;///MONEDA EN LA QUE ESTA EXPRESADA LAS CANTIDADES DE ALMACEN
      $CosnuSQLCAmbio=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas where tbl_monedas.id_moneda=$idMoneda;");
      $cambioMonedaData=ejecutar($CosnuSQLCAmbio);
      $CacmbioMoneda=assoc_a($cambioMonedaData);
      $valorCambio=$CacmbioMoneda['valor'];///Ultimo CAmbio de moneda
      $monedaCambio=$CacmbioMoneda['moneda'];///Moneda de Cambio
      $SimboloCambio=$CacmbioMoneda['simbolo'];///Simbolo de la moneda
      ///cambio a usar segun la poliza
      $ejecutarCambio=1;
      ///APLICAR CONTROL DE CAMBIO
      $precioBase=$monto_unidad;
        if($idMoneda>=1){ //Combio
          $monto_unidad=Formato_Numeros($monto_unidad*$valorCambio);
        }else{//BS
          $monto_unidad=Formato_Numeros($monto_unidad);
        }

          //FIN CONTROL DE PRECIO



		echo "
		<tr>
			<td class=\"tdcamposcc\" style=\"text-align:left; padding-left: 50px;\">$f[insumo]</td>
			<td class=\"tdcamposcc\" align=\"center\">$f[cantidad]</td>
			<td class=\"tdcamposcc\" style=\"text-align:right; padding-right: 2%;\"> $precioBase $SimboloCambio</td>
			<td class=\"tdcamposcc\" style=\"text-align:right; padding-right: 2%;\"> $monto_unidad </td>
			<td class=\"tdcamposcc\" align=\"center\">$f[laboratorio]</td>
		</tr>";
		}

		?>
		<tr>
			<td>&nbsp;</td>
			<td class="tdcamposcc"><b>Cantidad</b></td>
			<td class="tdcamposcc"><b></b></td>
			<td class="tdcamposcc">&nbsp;</td>
		</tr>
		<?php
		echo "
		<tr>
			<td class=\"tdcamposc\" align=\"right\">TOTALES</td>
			<td class=\"tdcamposc\" align=\"center\">$cantidad_final</td>
			<td class=\"tdcamposc\" align=\"center\"></td>
			<td class=\"tdcamposc\" align=\"center\">&nbsp;</td>
			<td class=\"tdcamposc\" align=\"center\">&nbsp;</td>
		</tr>
			";

		?>
		</table>
		<?php
		}
		?>
		</td>
	</tr>
	<tr>
    <td colspan=4 class="titulo_seccion"> <?php
$url="'views06/irep_invs2.php?letra=$letra&signo=$signo&cantidad=$cantidad&dependencia=$dependencia&laboratorio=$id_laboratorio&tipo_insumo=$tipo_insumo&monto=$monto'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Imprimir </a></td>
</tr>
</table>
<?php
echo pie();
}
?>
