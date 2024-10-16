<?php

include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');

$admin= $_SESSION['id_usuario_'.empresa];

   $fechaini=$_REQUEST['fecha1'];
   $fechafin=$_REQUEST['fecha2'];
   $sucursal=$_REQUEST['sucursal'];


        if ($sucursal==0)
{
    $id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
    $serie="Todas Las Series";
    }
    else
    {
    $id_serie="and tbl_series.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
    $q_serie=("select  * from tbl_series where id_serie=$sucursal");
    $r_serie=ejecutar($q_serie);
    $f_serie=asignar_a($r_serie);
    $serie=$f_serie[nomenclatura];
        }

 $rep_fac_igtf= pg_query(" select tbl_facturas.numero_factura,
                                  tbl_facturas.id_factura,
                                  tbl_facturas.fecha_emision,
                                  tbl_facturas.id_estado_factura,
                                  tbl_series.nomenclatura,
                                  tbl_facturas.id_serie,
                                  tbl_oper_multi.id_moneda,
                                  tbl_oper_multi.monto 
                             from 
                                  tbl_facturas,                           
                                  tbl_series,
                                  tbl_oper_multi
                            where     
                                  tbl_facturas.id_factura=tbl_oper_multi.id_factura
                             and  tbl_facturas.id_serie=tbl_series.id_serie 
                                  $id_serie 
                             and  tbl_facturas.id_estado_factura=1     
                             and  tbl_facturas.fecha_emision>='$fechaini' 
                             and  tbl_facturas.fecha_emision<='$fechafin' 
                             and  id_moneda>1 

                       group by  tbl_facturas.numero_factura,                                         
                                 tbl_facturas.id_factura,                        
                                 tbl_facturas.fecha_emision,
                                 tbl_facturas.id_estado_factura,
                                 tbl_series.nomenclatura,
                                 tbl_series.nombre, 
                                 tbl_oper_multi.id_moneda,
                                 tbl_oper_multi.monto,                                           
                                 tbl_facturas.id_serie ");


$b_IGTF= ("select * from variables_globales where  nombre_var='IGTF' "); 
$t_IGTF=ejecutar($b_IGTF);
$q_IGTF=asignar_a($t_IGTF);
$IGTF= $q_IGTF['cantidad'];
$porcientoIGTF= $q_IGTF['comprasconfig'].' %';
   
    ?>

<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>

  <tr>
  <td height="21" colspan="12" class="titulo_seccion"><div align="center"><strong>Relacion de facturas con IGTF </strong></div></td>
    </tr>

      <tr>         
         <td  class="tdcamposc" ></td>
         <td  class="tdcamposc" ><strong>Serie</strong></td>
         <td  class="tdcamposc" ><strong>Factura</td>
         <td  class="tdcamposc" ><strong>Monto</td> 
         <td  class="tdcamposc" ><strong>Porcentaje IGTF</td>
         <td  class="tdcamposc" ><strong>Monto Total facturado </td>
         <td  class="tdcamposc" ><strong>Fecha de emision </td>
     </tr>
     
<?php 
$contador=0;
while($f_rep_fac_igtf=pg_fetch_array($rep_fac_igtf, NULL, PGSQL_ASSOC)) 
{

    $facturaigtf= ("select sum(tbl_oper_multi.monto)  from tbl_oper_multi where id_factura=$f_rep_fac_igtf[id_factura] and id_moneda>1;");
     $monto_facturaigtf = ejecutar($facturaigtf);
     $monto_total_igtf   = asignar_a($monto_facturaigtf);
    
     $monto_t= $monto_total_igtf[sum];
 

     $montoigtf = $monto_total_igtf[sum] * $IGTF;

 
     $monto_total_fac=("select sum(tbl_oper_multi.monto)
                      from tbl_oper_multi 
                     where tbl_oper_multi.id_factura=$f_rep_fac_igtf[id_factura]");
 $monto_total_f = ejecutar($monto_total_fac);
 $monto_t_f   = asignar_a($monto_total_f);
 $monto_t_factura=$monto_t_f[sum];

 $monto_t_sum_igt = (($monto_t_factura) + ($montoigtf));

$contador++;
?>

   <tr>
    
    <td  class="tdcamposc" ><?php echo $contador ?></td> 
    <td  class="tdcamposc" ><?php echo $f_rep_fac_igtf[nomenclatura] ?></td>
    <td  class="tdcamposc" ><?php echo $f_rep_fac_igtf[numero_factura]?></td>
    <td  class="tdcamposc" ><?php echo montos_print ($monto_t)?> </strong></td>
    <td  class="tdcamposc" ><?php echo montos_print ($montoigtf)?> </strong></td>
    <td  class="tdcamposc" ><?php echo montos_print  ($monto_t_sum_igt)?> </strong></td>
    <td  class="tdcamposc" ><?php echo $f_rep_fac_igtf[fecha_emision]?> </strong></td>

<?php
}?>

  </table>
       <tr>
          <td class="tdcampos" title="Generar Excel de Facturas con IGTF"><label class="boton" style="cursor:pointer" onclick="rep_excel_igtf()" >Generar Excel</label>
          <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>        




