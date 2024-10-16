<?
    include ("../../lib/jfunciones.php");
    sesion();
	$ranfecha1=$_REQUEST['rangfe1'];
	$ranfecha2=$_REQUEST['rangfe2'];
	$elprove=$_REQUEST['cualprove'];
    $proestad=$_REQUEST['estado'];
	$sucusal=$_REQUEST['fasucursal'];
    $servicio=$_REQUEST['elservi'];
    if($servicio<100){
	  $queryservi="and gastos_t_b.id_servicio=$servicio";
	}  else{
		   $queryservi="and (gastos_t_b.id_servicio=4 or gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9 or gastos_t_b.id_servicio=11)";
		} 
     if($sucusal==100){
      $querysucursal="";      
    }else{
         $querysucursal="and admin.id_sucursal=$sucusal";
     }    
	
     $actualizafac=("update procesos set 
          id_estado_proceso=16
from 
            gastos_t_b,admin
where 
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.fecha_factura_final between '$ranfecha1' and '$ranfecha2' and
gastos_t_b.id_proveedor=$elprove and 
procesos.id_estado_proceso=$proestad and
procesos.id_admin=admin.id_admin $queryservi
 $querysucursal");
$repactualizfa=ejecutar($actualizafac); 
      //echo"|$elprove|-|$sucusal|-|$ffechanueva|-|$facnueva|-|$facactual|-|$estpro|-|$ranfecha2|-|$ranfecha1|";
?>	
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="titulo_seccion" colspan="7">Proceso culminado exitosamente</td>     
     </tr>
</tale>
