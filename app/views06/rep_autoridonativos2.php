<?php
/* Nombre del Archivo: reporte_entpriv.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación Entes Privados
*/ 

include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];

   $fechaini=$_REQUEST['fecha1'];
   $fechafin=$_REQUEST['fecha2'];


$rep_autoridontavos=("select  procesos_donativos.id_proceso,
                              procesos.id_proceso,
                              procesos.id_estado_proceso,
                              admin.nombres,
                              admin.apellidos,
                              tbl_responsables_donativos.id_responsable_donativo,
                              tbl_responsables_donativos.responsable                                
                      from    procesos_donativos,
                              procesos,
                              admin,       
                              tbl_responsables_donativos  
                      where   procesos_donativos.id_admin=admin.id_admin
                        and   procesos.id_proceso=procesos_donativos.id_proceso
                        and   procesos_donativos.id_responsable_donativo=tbl_responsables_donativos.id_responsable_donativo
                        and   procesos_donativos.fecha_donativo>='$fechaini' 
                        and   procesos_donativos.fecha_donativo<='$fechafin'
       ");
 
$b_rep_autoridonativos = ejecutar($rep_autoridontavos);




?>


<table class="tabla_citas"  cellpadding=0 cellspacing=0>      <td  width="40">&nbsp;</td>

	<tr>
		<td class="titulo_seccion" colspan="4">Reporte de Donativos realizados </td>     
	</tr>
</table>	

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
      <td  width="40">&nbsp;</td>
		<td class="tdcamposcc"><strong>Proceso</strong></td>   
		<td class="tdcamposcc"><strong>Autorizado por</strong></td>     
		<td class="tdcamposcc"><strong>Realizado por</strong></td> 
      <td class="tdcamposcc"><strong>Monto de la orden</strong></td> 
      <td class="tdcamposcc"><strong>Gastos</strong></td> 
	</tr>



<?php 

$contador=0;
 while ($f_rep_auto = asignar_a($b_rep_autoridonativos)){
   $contador++;

   $donativo=$f_rep_auto[id_proceso];  
   
$monto_donativo=("select sum(cast(gastos_t_b.monto_aceptado as double precision)) as sum_monto_a,
                         sum(cast(gastos_t_b.monto_reserva as double precision)) as sum_monto_r
                   from  gastos_t_b
                  where  id_proceso='$f_rep_auto[id_proceso]'");

$monto_do=ejecutar($monto_donativo);
$monto_d=asignar_a($monto_do);

$sum_monto_a=$monto_d[sum_monto_a];
$sum_monto_r=$monto_d[sum_monto_r];

$monto_total_d=$monto_total_d + $sum_monto_a;

?>

<tr>
               <td class="titulosa"><?php echo $contador ?></td> 
               <td><label title="Ver proceso"   class="tdcamposcc" style="cursor:pointer"  onclick="ver_proceso_d(<?php echo $donativo?>)"><?php echo $donativo?></label></td>  
             	<td class="tdcamposc"><?php echo $f_rep_auto[responsable]?></td>
               <td class="tdcamposc"><?php echo $f_rep_auto[nombres].$f_rep_auto[apellidos]?></td> 
               <td class="tdcamposc"><?php echo formato_montos($sum_monto_a);?></td>

               <?php if($f_rep_auto[id_estado_proceso]==15){?>
               <td class="tdcamposc"><?php echo  formato_montos($sum_monto_r);?>
               <?php;}else{ ?> <td class="tdcamposc">   </td><?php;}?>


               <td><label title="Imprimir acta" class="tdcamposcc" style="cursor:pointer" onclick="impactadonativo(<?php echo $donativo?>)" >Acta</label></td>  
               <td  width="40">&nbsp;</td> 
</tr>

<?php;

switch ($f_rep_auto[id_responsable_donativo]) {

    case ($f_rep_auto[id_responsable_donativo]==1):
            $cantidad1++;
        break;               
                
    case ($f_rep_auto[id_responsable_donativo]==3):
            $cantidad2++;
        break; 

    case ($f_rep_auto[id_responsable_donativo]==4):
            $cantidad3++;
        break; 

    case ($f_rep_auto[id_responsable_donativo]==5):
            $cantidad4++;
        break;               
     }

} 


?>

</table>


<table>
            <tr>
             <td class="titulosa"><strong>Monto total</strong></td>      
             <td class="tdcamposc"><?php echo formato_montos($monto_total_d)?></td>
            </tr>

         <td>&nbsp;</td>

 <?php 
     if($cantidad1!=0){
     ?>
     <tr>
 <td colspan=2 class="titulosa"> Cantidad de donaciones por Antonio Guerrero: <?php echo $cantidad1 ?></td>
     </tr>
     <?php;}

     if($cantidad2!=0){
     ?>
     <tr>
 <td colspan=2 class="titulosa"> Cantidad de donaciones por Zulay Agelvis: <?php echo $cantidad2 ?></td>
   </tr>
     <?php;}

         if($cantidad3!=0){
     ?>
     <tr>
 <td colspan=2 class="titulosa"> Cantidad de donaciones por Guillermo Guerrero <?php echo $cantidad3 ?></td>
    </tr>
     <?php;}

         if($cantidad4!=0){
     ?>
     <tr>
 <td colspan=2 class="titulosa"> Cantidad de donaciones por Zulymar Matheus: <?php echo $cantidad4 ?></td>
     </tr>
     <?php;}?>

      <td>&nbsp;</td>

</table>

