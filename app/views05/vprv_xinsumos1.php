<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
/*scrip para mostrar los provedores de un product, precio y tipo de producto*/
include ("../../lib/jfunciones.php");
sesion();


$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;

$busqueda=trim($_POST['binsumo']);

$sqlvercod="SELECT 
  tbl_insumos.insumo, 
  tbl_tipos_insumos.tipo_insumo, 
  tbl_insumos.id_insumo, 
  tbl_insumos_almacen.cantidad, 
  tbl_insumos_almacen.id_dependencia, 
  tbl_tipos_insumos.id_tipo_insumo, 
  tbl_laboratorios.laboratorio, 
  proveedores.id_s_p_proveedor, 
  proveedores.id_clinica_proveedor, 
  tbl_insumos_ordenes_compras.monto_unidad, 
  tbl_ordenes_compras.fecha_compra
FROM 
  public.tbl_insumos, 
  public.tbl_tipos_insumos, 
  public.tbl_insumos_almacen, 
  public.tbl_laboratorios, 
  public.tbl_ordenes_compras, 
  public.tbl_insumos_ordenes_compras, 
  public.proveedores
WHERE 
  tbl_insumos.id_insumo = tbl_insumos_ordenes_compras.id_insumo AND
  tbl_tipos_insumos.id_tipo_insumo = tbl_insumos.id_tipo_insumo AND
  tbl_insumos_almacen.id_insumo = tbl_insumos.id_insumo AND
  tbl_laboratorios.id_laboratorio = tbl_insumos.id_laboratorio AND
  tbl_ordenes_compras.id_orden_compra = tbl_insumos_ordenes_compras.id_orden_compra AND
  tbl_ordenes_compras.id_proveedor_insumo = proveedores.id_proveedor AND
  concat(tbl_insumos_almacen.id_dependencia,'-',tbl_insumos.id_insumo,'-',tbl_tipos_insumos.id_tipo_insumo) = '$busqueda'
ORDER BY
  tbl_ordenes_compras.fecha_compra DESC, 
  tbl_insumos.insumo ASC;";//la funcion CONCAT permite la concatenacion de varios campos y caracteres
 
$codinsumqr=ejecutar($sqlvercod) ;//ejecutar registros
$numconsulcod=num_filas($codinsumqr);//numero de registros 

if($numconsulcod>0){
 $sql=$codinsumqr;
}
else {

$busq_prv_insm=("SELECT
tbl_insumos.id_insumo, 
  tbl_tipos_insumos.tipo_insumo as tipo, 
  tbl_insumos.insumo, 
  tbl_laboratorios.laboratorio, 
  tbl_insumos_ordenes_compras.cantidad, 
  proveedores.id_s_p_proveedor as idpprov, 
  proveedores.id_clinica_proveedor as idpclin,
  tbl_ordenes_compras.fecha_compra,
 tbl_insumos_ordenes_compras.monto_unidad
FROM 
  public.tbl_laboratorios, 
  public.tbl_insumos, 
  public.tbl_insumos_ordenes_compras, 
  public.proveedores, 
  public.tbl_tipos_insumos, 
  public.tbl_ordenes_compras
WHERE 

  tbl_insumos.id_laboratorio = tbl_laboratorios.id_laboratorio AND
  tbl_insumos.id_tipo_insumo = tbl_tipos_insumos.id_tipo_insumo AND
  tbl_insumos_ordenes_compras.id_insumo = tbl_insumos.id_insumo AND
  tbl_ordenes_compras.id_orden_compra = tbl_insumos_ordenes_compras.id_orden_compra AND
  tbl_ordenes_compras.id_proveedor_insumo = proveedores.id_proveedor AND
  UPPER(tbl_insumos.insumo) like UPPER('%$busqueda%') ORDER BY
  tbl_ordenes_compras.fecha_compra DESC,tbl_insumos.insumo DESC;");//la funcion UPPER permite la transformacion en mayusculas una cadena
$sql=ejecutar($busq_prv_insm) ;
$numbinsumo=num_filas($sql);//numero de registros 	
	
	}
	

?>
<?php
if(($numbinsumo==0)&&($numconsulcod==0)) {
	?>
	
	<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">No hay información!!!!</td>
     </tr>
    </table>
    
 <?php
    
}else
{
	?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Provedores por Insumos</td>
     </tr>
     <tr>
<!--<th class="tdtitulos"> C&Cacute;DIGO		</th>-->
<th class="tdtitulos"> INSUMO				</th>
<th class="tdtitulos"> TIPO DE INSUMO 	</th> 
<th class="tdtitulos"> MARCA 				</th> 
<th class="tdtitulos"> PROVEDOR 			</th> 
<th class="tdtitulos"> FECHA	 			</th> 
<th class="tdtitulos"> PRECIO				</th>   


<!-- ---- RESULTADOS PARA MOSTRAR ---------------------------- --> 
     </tr>
  <?php  $n=0; while($prov=asignar_a($sql,NULL,PGSQL_ASSOC)){//siclo de repeticion para muestra de resultados ?>
	
			               
    <tr>
        <!--<td class="tdcampos"> <?php echo "$prov[id_insumo]";?></td><!--nombre del insumo-->
        <td class="tdcampos"> <?php echo "$prov[insumo]";?></td><!--nombre del insumo-->
        <td class="tdcampos"> <?php echo " $prov[tipo] ";?> </td> <!--Tipo  del insumo-->
        <td class="tdcampos"> <?php echo" $prov[laboratorio] ";?></td> <!--MARCA DEL insumo-->
        <td class="tdcampos"> <!--nombre del del provedor de insumo puede ser una clinica o una persona-->
               
        <?php
        $idpers=$prov[idpprov];//id de personas provedores
        $idclin=$prov[idpclin];//ide de clinicas prvedores
        
             
         if($idpers>0) {	
         	$bnomprove=("SELECT 
        				 concat(personas_proveedores.nombres_prov, 
  						personas_proveedores.apellidos_prov) as nomperspr
						FROM 
						public.s_p_proveedores,public.personas_proveedores
						WHERE 
						s_p_proveedores.id_persona_proveedor = personas_proveedores.id_persona_proveedor AND
						s_p_proveedores.id_s_p_proveedor = '$idpers';");
						
        	$nomprov=ejecutar($bnomprove);
        	$pprove=asignar_a($nomprov);
        	echo $pprove[nompersprv];
        	}
        else  
        { 
        $nbclinica=("SELECT 
  clinicas_proveedores.nombre
FROM 
  public.proveedores, 
  public.clinicas_proveedores
WHERE 
  proveedores.id_clinica_proveedor = clinicas_proveedores.id_clinica_proveedor AND
  clinicas_proveedores.id_clinica_proveedor = '$idclin';");
        
       	$enclini=ejecutar($nbclinica);
        	$nclini=asignar_a($enclini);
        	echo $nclini[nombre];
        	 }
        ?>
        
        
        
        
        </td> 
        <td class="tdcampos"><?php echo "($prov[fecha_compra])"; ?></td><!-- fecha de adquisición del producto --> 
        <td class="tdcampos"><?php echo " $prov[monto_unidad] "; ?></td><!--monto o precio de adquisición del producto --> 
         
        </tr>
 <?php			             
 }//fin del while 
 
 } //if de validacion?>             
              
              
              
</table>
