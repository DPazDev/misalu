<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_tipo_insumo,$tipo_insumo)=explode("@",$_REQUEST['tipo_insumo']);

$letra = $_REQUEST['letra'];
$dateField1 = $_REQUEST['dateField1'];
$dateField2 = $_REQUEST['dateField2'];
$dependencia=$_REQUEST['dependencia'];
$codigo=time();


$q_dependencia=("select * from tbl_dependencias where id_dependencia=$dependencia");
$r_dependencia=ejecutar($q_dependencia);
$f_dependencia=asignar_a($r_dependencia);

$condicion_tipo_insumo="";
if($id_tipo_insumo!=0){
	$condicion_tipo_insumo=" tbl_tipos_insumos.id_tipo_insumo=$id_tipo_insumo";
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

if ($id_tipo_insumo==1){
    /* **** creamos la tabla temporal para realizar la consulta**** */
 $q_tabla_tem = "create table tabla_gastos_medi_$codigo as select 
                                gastos_t_b.id_insumo,
                                gastos_t_b.unidades,
                                procesos.id_estado_proceso,
                                procesos.id_admin   
                                
                        from 
                                gastos_t_b,
                                procesos,
                                tbl_admin_dependencias 
                        where 
                                gastos_t_b.id_insumo>0 and
                                gastos_t_b.id_proceso=procesos.id_proceso and 
                                procesos.id_estado_proceso!=14 and
                                procesos.fecha_recibido>='$dateField1' and 
                                procesos.fecha_recibido<='$dateField2' and
                                procesos.id_admin=tbl_admin_dependencias.id_admin and
                                tbl_admin_dependencias.id_dependencia=$dependencia;";
$r_tabla_tem = ejecutar($q_tabla_tem);
    }

$q_inventario = "select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_laboratorios.laboratorio,
                                count(tbl_insumos_ordenes_pedidos.id_insumo) 
                        from
                                tbl_ordenes_pedidos,tbl_insumos_ordenes_pedidos,
                                tbl_tipos_insumos,
                                tbl_insumos,
                                tbl_laboratorios
                        where 
                                tbl_ordenes_pedidos.id_dependencia_saliente=$dependencia  and 
                                tbl_ordenes_pedidos.fecha_pedido>='$dateField1' and 
                                tbl_ordenes_pedidos.fecha_pedido<='$dateField2' and 
                                tbl_insumos_ordenes_pedidos.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
                                tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
                                $condicion_letra
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                $condicion_tipo_insumo 
                        group by 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_laboratorios.laboratorio
                        order by  
                                tbl_insumos.insumo;";

$r_inventario = ejecutar($q_inventario);

?>


<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=6 align="center" class="titulo_seccion">Inventario de Dependencia <?php echo $f_dependencia[dependencia] ?></td>
	</tr>
	<tr>
		<td colspan=6 class="tdcamposc">&nbsp;</td>
	</tr>
	<tr>
		<?php 
		if(num_filas($r_inventario)==0){ 
		echo "
	<td colspan=6 align=\"center\" class=\"titulo_seccion\">No hay resultados con esos parametros.</td>
		</tr>
		";
		}
        else
        {
		?>
	<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
		<tr>
        	<td class="tdcamposc">id</td>
			<td class="tdcamposc">Nombre</td>
            <td class="tdcamposc">Marca</td>
			<td class="tdcamposc">Cantidad Pedida</td>
			<td class="tdcamposc">Cantidad Recibida</td>
			<td class="tdcamposc">Inv. Muerto</td>
            <td class="tdcamposc">Cant. Despachada</td>
            <td class="tdcamposc">Cant. por OM</td>
            <td class="tdcamposc">Inventario</td>
		</tr>
		<?php
		$monto_total_final = 0;
		$monto_unidad_final = 0;
		$cantidad_final = 0;
		while($f_inventario=pg_fetch_assoc($r_inventario)){
            
            /* ****  buscamos cantidad de insumos pedidos **** */
            $q_inventariop = "select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_insumos_ordenes_pedidos.cantidad
                        from
                                tbl_ordenes_pedidos,tbl_insumos_ordenes_pedidos,
                                tbl_tipos_insumos,
                                tbl_insumos 
                        where 
                                tbl_ordenes_pedidos.id_dependencia_saliente=$dependencia  and 
                                tbl_ordenes_pedidos.fecha_pedido>='$dateField1' and 
                                tbl_ordenes_pedidos.fecha_pedido<='$dateField2' and 
                                tbl_insumos_ordenes_pedidos.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
                                tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                tbl_insumos.id_insumo=$f_inventario[id_insumo];";

$r_inventariop = ejecutar($q_inventariop);
		
        $cantidadpedida=0;
		while($f_inventariop=pg_fetch_assoc($r_inventariop)){
            $cantidadpedida=$cantidadpedida + $f_inventariop[cantidad];
            }
            /* **** fin de buscar cantidad de insumos pedidos **** */
            
                        /* ****  buscamos cantidad de insumos pedidos para una compra **** */
            $q_inventariopc = "select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_insumos_ordenes_pedidos.cantidad
                        from
                                tbl_ordenes_pedidos,tbl_insumos_ordenes_pedidos,
                                tbl_tipos_insumos,
                                tbl_insumos,
                                tbl_admin_dependencias
                        where 
                                tbl_ordenes_pedidos.id_admin=tbl_admin_dependencias.id_admin and
                                tbl_admin_dependencias.id_dependencia=$dependencia  and 
                                tbl_ordenes_pedidos.id_dependencia_saliente=0 and 
                              tbl_ordenes_pedidos.fecha_pedido>='$dateField1' and 
                                tbl_ordenes_pedidos.fecha_pedido<='$dateField2' and  
                                tbl_insumos_ordenes_pedidos.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
                                tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                tbl_insumos.id_insumo=$f_inventario[id_insumo];";

$r_inventariopc = ejecutar($q_inventariopc);
		
        $cantidadpedidac=0;
		while($f_inventariopc=pg_fetch_assoc($r_inventariopc)){
            $cantidadpedidac=$cantidadpedidac + $f_inventariopc[cantidad];
            }

            
            
       /* **** buscar cantidad de insumos recibidos **** */           
            $q_inventarioe= "select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_insumos_ordenes_entregas.cantidad
                        from
                                tbl_ordenes_entregas,
                                tbl_insumos_ordenes_entregas,
                                tbl_tipos_insumos,
                                tbl_insumos 
                        where 
                                tbl_ordenes_entregas.id_dependencia=$dependencia  and 
                                tbl_ordenes_entregas.fecha_emision>='$dateField1' and 
                                tbl_ordenes_entregas.fecha_emision<='$dateField2' and 
                                tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and
                                tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                tbl_insumos.id_insumo=$f_inventario[id_insumo];";

$r_inventarioe = ejecutar($q_inventarioe);
		
        $cantidadentregada=0;
		while($f_inventarioe=pg_fetch_assoc($r_inventarioe)){
            $cantidadentregada=$cantidadentregada + $f_inventarioe[cantidad];
            }
            
            /* **** fin de buscar cantidad de insumos recibidos **** */
            
            /* **** buscamos si hay entregas  de insumos en inventario muerto **** */
            
             $q_inventariom= "
                        select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_insumos_ordenes_entregas.cantidad
                        from
                                tbl_ordenes_entregas,
                                tbl_insumos_ordenes_entregas,
                                tbl_tipos_insumos,
                                tbl_insumos,
                                tbl_ordenes_pedidos
                        where 
                                tbl_ordenes_entregas.id_dependencia=5  and 
                                tbl_ordenes_entregas.fecha_emision>='$dateField1' and 
                                tbl_ordenes_entregas.fecha_emision<='$dateField12' and 
                                tbl_ordenes_pedidos.id_orden_pedido=tbl_ordenes_entregas.id_orden_pedido and 
                                tbl_ordenes_pedidos.id_dependencia=$dependencia and
                                tbl_ordenes_pedidos.id_dependencia_saliente=5 and 
                                tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and
                                tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                tbl_insumos.id_insumo=$f_inventario[id_insumo];";

$r_inventariom = ejecutar($q_inventariom);
		
        $cantidadinvm=0;
		while($f_inventariom=pg_fetch_assoc($r_inventariom)){
            $cantidadinvm=$cantidadinvm + $f_inventariom[cantidad];
            }
            
                        /* **** buscamos si hay entregas  de insumos en otras dependencia **** */
            
             $q_inventariod= "
                        select 
                                tbl_insumos.insumo,
                                tbl_insumos.id_insumo,
                                tbl_insumos_ordenes_entregas.cantidad
                        from
                                tbl_ordenes_entregas,tbl_insumos_ordenes_entregas,
                                tbl_tipos_insumos,
                                tbl_insumos,
                                tbl_ordenes_pedidos
                        where 
                                tbl_ordenes_entregas.id_dependencia!=5  and 
                                tbl_ordenes_entregas.fecha_emision>='$dateField1' and 
                                tbl_ordenes_entregas.fecha_emision<='$dateField2' and 
                                tbl_ordenes_pedidos.id_orden_pedido=tbl_ordenes_entregas.id_orden_pedido and 
                                tbl_ordenes_pedidos.id_dependencia=$dependencia and
                                tbl_ordenes_pedidos.id_dependencia_saliente!=5 and 
                                tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and
                                tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and 
                                tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and 
                                tbl_insumos.id_insumo=$f_inventario[id_insumo];";

$r_inventariod = ejecutar($q_inventariod);
		
        $cantidadinvd=0;
		while($f_inventariod=pg_fetch_assoc($r_inventariod)){
            $cantidadinvd=$cantidadinvd + $f_inventariod[cantidad];
         
            }
            
            
               /* **** buscamos si hay entregas  de insumos en ordenes de atencion **** */
            
             $q_inventariodoa= "
                        select 
                                tabla_gastos_medi_$codigo.unidades,
                                tabla_gastos_medi_$codigo.id_estado_proceso 
                        from 
                                 tabla_gastos_medi_$codigo
                        where 
                                 tabla_gastos_medi_$codigo.id_insumo=$f_inventario[id_insumo];";

$r_inventariodoa = ejecutar($q_inventariodoa);
		
        $cantidadinvdoa=0;
        while($f_inventariodoa = asignar_a($r_inventariodoa)){
            $cantidadinvdoa=$cantidadinvdoa + $f_inventariodoa[unidades];
         
            }
            
            
            
             /* **** buscamos si hay entregas  de insumos por ordenes de compras **** */
            
             $q_inventarioc= "
                        select 
                                tbl_insumos_ordenes_compras.* 
                        from
                                tbl_insumos_ordenes_compras,
                                tbl_ordenes_compras,
                                tbl_admin_dependencias 
                        where
                                tbl_insumos_ordenes_compras.id_orden_compra=tbl_ordenes_compras.id_orden_compra and 
                                tbl_insumos_ordenes_compras.id_insumo=$f_inventario[id_insumo] and 
                                tbl_ordenes_compras.fecha_compra>='$dateField1' and 
                                tbl_ordenes_compras.fecha_compra<='$dateField2' and 
                                tbl_ordenes_compras.id_admin=tbl_admin_dependencias.id_admin and 
                                tbl_admin_dependencias.id_dependencia=$dependencia;";

$r_inventarioc = ejecutar($q_inventarioc);
		
        $cantidadinvc=0;
		while($f_inventarioc=pg_fetch_assoc($r_inventarioc)){
            $cantidadinvc=$cantidadinvc + $f_inventarioc[cantidad];
         
            }
            
            /* **** busco lo que realmente hay en el dependencia **** */
                         $q_inventarioalma= "
                                        select
                                                * 
                                        from 
                                                tbl_insumos_almacen 
                                        where 
                                                id_dependencia=$dependencia and
                                                id_insumo=$f_inventario[id_insumo] ;";

$r_inventarioalma = ejecutar($q_inventarioalma);
$f_inventarioalma = asignar_a($r_inventarioalma);
            
            
$cantidadreal=($cantidadentregada + $cantidadinvc) - ($cantidadinvm + $cantidadinvd + $cantidadinvdoa) ;
$cantidadrecibida=$cantidadentregada + $cantidadinvc;
$cantidadpedidadc=$cantidadpedidac+$cantidadpedida;
$cantidaddespachada=$cantidadinvdoa + $cantidadinvd;
?>
<tr>
        	<td colspan=9 class="tdcamposc"><hr></hr></td>
</tr>
		<tr>
            <td class="tdcamposc"><?php echo $f_inventario[id_insumo]; ?></td>
			<td class="tdcamposc"><?php echo $f_inventario[insumo]; ?></td>
            <td class="tdcamposc"><?php echo $f_inventario[laboratorio]; ?></td>
			<td class="tdcamposc"><?php echo $cantidadpedidadc; ?></td>
			<td class="tdcamposc"><?php echo $cantidadrecibida; ?></td>
			<td class="tdcamposc"><?php echo $cantidadinvm; ?></td>
            <td class="tdcamposc"><?php echo $cantidadinvd; ?></td>
            <td class="tdcamposc">
            <?php
				    $url="views06/iver_om_invxcorte.php?fechainicio=$dateField1&fechafinal=$dateField2&id_dependencia=$dependencia&tipo_insumo=$tipo_insumo&id_insumo=$f_inventario[id_insumo]&insumo=$f_inventario[insumo]";
                        ?> <a href="<?php echo $url; ?>" title="Relacion de Ordenes con Insumos o Medicamentos"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;" class="boton"><?php echo $cantidadinvdoa;?></a>
						
            </td>
			<td class="tdcamposr"><?php echo $cantidadreal; ?></td>
            
            
		
		
</tr>
		<?php
        }
        ?>
        </table>
		<?php 
    /* **** Eliminar tabla temporal**** */

    $e_tabla_tem = "drop table tabla_gastos_medi_$codigo";
    $re_tabla_tem = ejecutar($e_tabla_tem);
		} 

		?>
		</td>
	</tr>
	
</table>
