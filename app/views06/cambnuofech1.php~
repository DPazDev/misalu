<?
    include ("../../lib/jfunciones.php");
    sesion();
	$ranfecha1=$_GET['rangfe1'];
	$ranfecha2=$_GET['rangfe2'];
	$elprove=$_GET['cualprove'];
	$facactual=$_GET['factuactu'];
	$facnueva=$_GET['factunuev'];
	$ffechanueva=$_GET['facfechnu'];
    $contfactura=$_GET['contfac'];
	$sucusal=$_GET['fasucursal'];
     if($sucusal==100){
      $querysucursal="";      
    }else{
         $querysucursal="and admin.id_sucursal=$sucusal";
     }    
	$estpro=$_GET['faestpro'];
     $actualizafac=("update procesos set 
          factura_final='$facnueva',fecha_emision_factura='$ffechanueva',control_factura='$contfactura'
from 
            gastos_t_b,admin
where 
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.fecha_factura_final between '$ranfecha1' and '$ranfecha2' and
procesos.factura_final='$facactual' and gastos_t_b.id_proveedor=$elprove and 
procesos.id_estado_proceso=$estpro and
procesos.id_admin=admin.id_admin 
 $querysucursal");
 $repactualizfa=ejecutar($actualizafac); 
      //echo"|$elprove|-|$sucusal|-|$ffechanueva|-|$facnueva|-|$facactual|-|$estpro|-|$ranfecha2|-|$ranfecha1|";
?>	
<h1>Proceso culminado exitosamente</h1>
