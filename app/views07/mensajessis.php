<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$elid=$_SESSION['id_usuario_'.empresa];
$busmensaje=("select messages.mensaje_id,messages.id_admin,admin.nombres,admin.apellidos,messages.fecha_creado,messages.mensaje,sucursales.sucursal
  from
    admin,messages,sucursales
  where
    messages.id_admin=admin.id_admin and
    messages.estatus=0 and
    admin.id_sucursal=sucursales.id_sucursal
  order by messages.fecha_creado;");
$repumensaje=ejecutar($busmensaje);
$repumensaje1=ejecutar($busmensaje);
 while($cntsmsj=asignar_a($repumensaje1,NULL,PGSQL_ASSOC)){
   $idmsj=$cntsmsj[mensaje_id];
   $fecham=$cntsmsj[fecha_creado];
   $adm=$cntsmsj[fecha_creado];
   $eladmin=$cntsmsj[id_admin];
   if($fecha>$fecham){
	   $actmsj=("update messages set admin_sistema=$eladmin,fecha_leido='$fecha',estatus=1 where mensaje_id=$idmsj;");
       $repactmsj=ejecutar($actmsj);
   }
 }	 
	 
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
 <tr>
    <td class="tdcamposc">Usuario</td>
    <td class="tdcamposc">Sucursal</td>
    <td class="tdcamposc">Mensaje</td>
    <td class="tdcamposc">Fecha</td>
    <td class="tdcamposc">Opci&oacute;n</td>
 </tr>   
 
 <? $incr=1;  
   while($losmsj=asignar_a($repumensaje,NULL,PGSQL_ASSOC)){
   $caja="msj$incr";	   
 ?>
   <tr>
      <td class="tdcamposcc"><?php echo "$losmsj[nombres] $losmsj[apellidos]"?></td>
      <td class="tdcamposcc"><?php echo "$losmsj[sucursal]"?></td>
      <td class="tdcamposcc"><?php echo "$losmsj[mensaje]"?></td>
      <td class="tdcamposcc"><?php echo "$losmsj[fecha_creado]"?></td>
      <td class="tdcamposcc"><input type="checkbox" id="<? echo $caja?>" value="<? echo $losmsj[mensaje_id]?>" onclick="Cualmsj('<? echo $losmsj[mensaje_id]?>','<? echo $elid?>')"></td>
       
   </tr>
 <?$incr++;
 }?>
