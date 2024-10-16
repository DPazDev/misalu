<?
include ("../../lib/jfunciones.php");
sesion();
header('Content-type: application/vnd.ms-excel');
$numealeatorio=rand(2,99);
header("Content-Disposition: attachment; filename=ordentresj.xls");
header("Pragma: no-cache");
header("Expires: 0");
$iddelpedido=$_REQUEST['numpedido'];
$idpedido=("select tbl_ordenes_entregas.id_orden_entrega 
from tbl_ordenes_entregas,tbl_ordenes_pedidos 
where tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
tbl_ordenes_pedidos.id_orden_pedido=$iddelpedido;");
$reppedido=ejecutar($idpedido);
$datpedido=assoc_a($reppedido);
$idorentrega=$datpedido['id_orden_entrega'];
$laentrega=("select tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo,tbl_insumos.id_laboratorio,
       tbl_laboratorios.laboratorio,tbl_tipos_insumos.tipo_insumo,
       tbl_insumos.insumo,tbl_insumos_ordenes_entregas.cantidad,
       tbl_insumos_almacen.monto_unidad_publico,tbl_insumos_almacen.id_dependencia
from 
       tbl_insumos,tbl_laboratorios,tbl_tipos_insumos,
       tbl_insumos_ordenes_entregas,tbl_insumos_almacen
where
     tbl_insumos_ordenes_entregas.id_orden_entrega=$idorentrega and
     tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and
     tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
     tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
     tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
     (tbl_insumos_almacen.id_dependencia=89 or tbl_insumos_almacen.id_dependencia=64) order by tbl_insumos.insumo;");
$repentrega=ejecutar($laentrega);     
?>
<table   cellpadding=0 cellspacing=0>
  			<?
               
			    $linea=1;   
			    while($insumos=asignar_a($repentrega,NULL,PGSQL_ASSOC)){
				$costoarti=number_format($insumos[monto_unidad_publico], 2, ",", "");	
                $tipoinsumoes=$insumos['id_tipo_insumo'];
                $ladepenes=$insumos['id_dependencia'];
                if(($tipoinsumoes>1)and($ladepenes==89)){
                    
                    ?>
				
        <tr>
			<td class="tdcampos"><?echo $linea?></td>
             <td class="tdcampos"><?echo $insumos[id_insumo]?></td>
			<td class="tdcampos"><?echo $insumos[id_tipo_insumo]?></td>
			<td class="tdcampos"><?echo $insumos[id_laboratorio]?></td>
			<td class="tdcampos"><?echo $insumos[laboratorio]?></td>
            <td class="tdcampos"><?echo $insumos[tipo_insumo]?></td>
            <td class="tdcampos"><?echo $insumos[insumo]?></td>
			<td class="tdcampos"><?echo $insumos[cantidad]?></td>
            <td class="tdcampos"><?echo $costoarti?></td>
        </tr>
        <?}
        if(($tipoinsumoes==1)and($ladepenes==64)){?>
			
        <tr>
			<td class="tdcampos"><?echo$linea?></td>
             <td class="tdcampos"><?echo$insumos[id_insumo]?></td>
			<td class="tdcampos"><?echo$insumos[id_tipo_insumo]?></td>
			<td class="tdcampos"><?echo$insumos[id_laboratorio]?></td>
			<td class="tdcampos"><?echo$insumos[laboratorio]?></td>
            <td class="tdcampos"><?echo$insumos[tipo_insumo]?></td>
            <td class="tdcampos"><?echo$insumos[insumo]?></td>
			<td class="tdcampos"><?echo$insumos[cantidad]?></td>
            <td class="tdcampos"><?echo$costoarti?></td>
        </tr>
		<?
        }
		$linea++;
		   
	}?>  
 </table>   
