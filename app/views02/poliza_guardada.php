<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$lanuevapoliza=strtoupper($_POST['lapoliza']);
$ladescripoliza=strtoupper($_POST['ladescripoliza']);
$ramopoliza=$_POST['elramo'];
$id_moneda=$_POST['lamoneda'];
$primaper=$_POST['primperso'];
$poligrupof=$_POST['polizgrupo'];
$poliespera=$_POST['polizenespera'];
   echo $nuevapoliza=("insert into polizas(nombre_poliza,descripcion,fecha_creado,hora_creado,personal,id_ramo,grupal,
                                       lapso_espera,id_moneda) values('$lanuevapoliza','$ladescripoliza','$fecha','$hora','$primaper',
									   $ramopoliza,'$poligrupof','$poliespera','$id_moneda');");
	$repnuevapoliza=ejecutar($nuevapoliza);								   
	$buscarlpoliza=("select polizas.id_poliza from polizas where nombre_poliza='$lanuevapoliza' and hora_creado='$hora' and fecha_creado='$fecha';");
	$repbuscarpoli=ejecutar($buscarlpoliza);
	$datopoliz=assoc_a($repbuscarpoli);
	$elidpoliz=$datopoliz['id_poliza'];
	$mensaje="El usuario $elus ha creado una nueva poliza con el nombre $lanuevapoliza"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	$repactualizoellog=ejecutar($actualizoellog);  
	$_SESSION['matriz']=array();
	$_SESSION['pasopedido']=0;
    $_SESSION['matriz1']=array();
	$_SESSION['pasopedido1']=1;
	$_SESSION['elidpoliza']=$elidpoliz;
	$_SESSION['nuevapropiedadpo'];    
?>
<input type='hidden' id='lapoliza' value='<?echo $lanuevapoliza?>'>
<input type='hidden' id='idpoliza' value='<?echo $elidpoliz?>'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">La p&oacute;liza <?php echo $lanuevapoliza ?> se ha registrado exitosamente</td>  
		 <td colspan=1 class="titulo_seccion" title="Cargar las propiedades de la p&oacute;lica"><label class="boton" style="cursor:pointer" onclick="propiedades_poliza()" >Propiedades</label></td>  
	</tr>	 
 </table>

