<?php
include ("../../lib/jfunciones.php");
sesion();

$ente=$_REQUEST[ente];
$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];

if ($forma_pago=='*'){
	$tipo_pago="and tbl_notacredito.edo_notacredito>=0";
	}
	else
	{
	$tipo_pago="and tbl_notacredito.edo_notacredito=$forma_pago";
	}
	
	
	
if ($ente==0)
{
	$elente="and titulares.id_ente>=$ente";
	}
	else
	{
		$elente="and titulares.id_ente=$ente";
		}
		if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
	}
	else
	{
	$id_serie="and tbl_series.id_serie=$sucursal";
		}
?>





<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>


    <tr>
   
      <?php 
$r_ente=pg_query("select  * from entes where id_ente=$ente");
($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))
?>
       
    </tr>
   
     
   <tr>
  <td height="21" colspan="12" class="titulo_seccion"><div align="center"><strong>Relacion de facturas de <?php echo $f_ente[nombre]?>, Atendidos por consultas, Laboratorios, Radiologia, Estudios especiales y Servicio de emergencias.</strong></div></td>
    </tr>
    
   	 <tr>
    <td  width="12">&nbsp;</td>
	<td   class="tdcamposc" >NC</td>
    <td  class="tdcamposc" >Fecha NC</td>
	<td   class="tdcamposc" >Monto NC</td>
	<td   class="tdcamposc" >Factura Afectada</td>
    <td class="tdcamposc" >Fecha Emision</td>
	<td class="tdcamposc" >Monto Factura</td>
	 <td  class="tdcamposc" >Serie</td>
	</tr>

 <?php
 $r_factura=pg_query("select 
                                            tbl_facturas.fecha_emision,
                                            tbl_facturas.numero_factura,
                                            tbl_facturas.id_factura,
                                            tbl_facturas.id_estado_factura,
                                            tbl_notacredito.num_notacredito,
                                            tbl_notacredito.montonc,
                                            tbl_notacredito.fecha_creado,
                                            tbl_notacredito.numcontrolnc,
                                            tbl_notacredito.edo_notacredito,
											tbl_series.nomenclatura,
                                            count(tbl_procesos_claves.id_factura) 
                                    from 
                                            tbl_facturas,
                                            tbl_notacredito,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
											tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_notacredito.id_factura and
											tbl_facturas.id_serie=tbl_series.id_serie and
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular $elente and 
                                            tbl_notacredito.fecha_creado>='$fechaini' and 
                                            tbl_notacredito.fecha_creado<='$fechafin'  
                                            $id_serie $tipo_pago 
                                    group by
                                            tbl_facturas.fecha_emision,
                                            tbl_facturas.numero_factura,
                                            tbl_facturas.id_factura,
                                            tbl_facturas.id_estado_factura,
                                            tbl_notacredito.num_notacredito,
                                            tbl_notacredito.montonc,tbl_notacredito.fecha_creado,
                                            tbl_notacredito.numcontrolnc,tbl_notacredito.edo_notacredito,
											tbl_series.nomenclatura
                                    order by tbl_notacredito.num_notacredito")
?>



   
    <?php    
$contador=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	 $q_factura_afectada="select tbl_facturas.numero_factura,sum(tbl_procesos_claves.monto) 
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves
                                    where 
                                           tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and 
                                           tbl_facturas.id_factura=$f_factura[id_factura]
                                    group by
                                            tbl_facturas.numero_factura";
	$r_factura_afectada=ejecutar($q_factura_afectada);
	$f_factura_afectada=asignar_a($r_factura_afectada);
	$contador++;

?>
   
    

      <tr>
	<td  class="tdcamposcc" ><?php echo $contador ?></td>  
        <td   class="tdcamposcc" ><?php echo "00$f_factura[num_notacredito]"?></td>
        <td   class="tdcamposcc" ><?php echo $f_factura[fecha_creado]; ?></td>
		<td   class="tdcamposcc"> <?php echo montos_print($f_factura[montonc]); ?></td>
		<td  class="tdcamposcc"> <?php echo $f_factura[numero_factura];?></td>
		<td   class="tdcamposcc"> <?php echo $f_factura[fecha_emision];?> </td>
		<td   class="tdcamposcc"> <?php echo montos_print($f_factura_afectada[sum]);?> </td>
		<td   class="tdcamposcc"> <?php echo $f_factura[nomenclatura];?> </td>
      </tr>
   
   
   
    <?php 

}
pg_free_result($r_factura);
if($contador==1){
	$contador="(Una Nota de Credito)";
}else{
	$contador="($contador Notas de Credito)";
}
?>
<tr>
	<td colspan=8  class="tdcamposc"></td>
	<td class="tdcamposc" align="right"></td>
</tr>
    <tr>
 
      <td class="tdcamposc" colspan="12" ><?php echo $contador; ?></td>
    </tr>

<tr>
    <td colspan="12" class="titulo_seccion"> <?php
$url="'views06/irep_notacredito.php?dateField1=$fechaini&dateField2=$fechafin&sucursal=$sucursal&forma_pago=$forma_pago&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Imprimir </a></td>
</tr>
    
  </table>

