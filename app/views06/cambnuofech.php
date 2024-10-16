<?
    include ("../../lib/jfunciones.php");
    sesion();
	$ranfecha1=$_GET['rangfe1'];
	$ranfecha2=$_GET['rangfe2'];
	$elprove=$_GET['cualprove'];
	$facactual=$_GET['factuactu'];
	$facnueva=$_GET['factunuev'];
	$ffechanueva=$_GET['facfechnu'];
	$sucusal=$_GET['fasucursal'];
	$estpro=$_GET['faestpro'];
        if(empty($ffechanueva)){
         $queryff='';
        }else{
          $queryff=",fecha_factura_final='$ffechanueva'";
         }
       $actualizafac=("update 
procesos set factura_final='$facnueva' $queryff
where 
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.fecha_factura_final between '$ranfecha1' and '$ranfecha2' and
procesos.factura_final='$facactual' and gastos_t_b.id_proveedor=$elprove and 
procesos.id_estado_proceso=$estpro and
procesos.id_admin=admin.id_admin and
admin.id_sucursal=$sucusal");
$repactualizfa=ejecutar($actualizafac); 
      //echo"|$elprove|-|$sucusal|-|$ffechanueva|-|$facnueva|-|$facactual|-|$estpro|-|$ranfecha2|-|$ranfecha1|";
?>	
<h1>Proceso culminado exitosamente</h1>
