<?
include ("../../lib/jfunciones.php");
sesion();
$elidart=$_POST['idartic'];
$elbloque=$_POST['elbloqu'];
$elnombre=strtoupper($_POST['nombaprox']);
$elgrupo=$_POST['elgruparti'];
$ellaborat=$_POST['loslabora'];
$labarra=strtoupper($_POST['elcobarra']);
$elnomactual=strtoupper($_POST['elnomactu']);
$fecha=date("Y-m-d");
if($labarra=='' or $labarra==null or $labarra=='0' ){
  ///el codigo de barra esta en blanco
  $CodeBarraactivo=0;
}else{
//buscamos que ningun otro articulo tenga el mismo
$versiexisteCodebarra=("select tbl_insumos.id_insumo from tbl_insumos where codigo_barras='$labarra' and tbl_insumos.id_insumo<>'$elidart'");
$verisexistebarra=ejecutar($versiexisteCodebarra);
$CodeBarraCuantos=num_filas($verisexistebarra);
if($CodeBarraCuantos>=1)
{ $CodeBarraactivo=1;}else{ $CodeBarraactivo=0;}
}

$versiexiste=("select tbl_insumos.id_insumo from tbl_insumos where insumo='$elnombre' and id_laboratorio=$ellaborat and
                       id_tipo_insumo=$elgrupo and tbl_insumos.id_insumo<>'$elidart'");
$repverisexiste=ejecutar($versiexiste);
$cuantoshay=num_filas($repverisexiste);
if($cuantoshay>=1 or $CodeBarraactivo==1){?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
   <tr>
     <td colspan=4 class="titulo_seccion">Error existe un insumo con las mismas caracter&iacute;sticas!!! o el mismo codigo de barra</td>
     <td  title="Regresar al modulo"><label class="boton" style="cursor:pointer" onclick="modif_articulo()" >Regresar</label></td>
   </tr>
</table>
<?}else{
$actualiarti=("update tbl_insumos set activo=$elbloque,insumo='$elnombre',id_laboratorio=$ellaborat,id_tipo_insumo=$elgrupo,codigo_barras='$labarra' where
tbl_insumos.id_insumo=$elidart;");
$repactualiarti=ejecutar($actualiarti);
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$mensaje="El usuario $elus ha modificado el articulo que tenia el nombre $elnomactual con id_articulo=$elidart al nuevo nombre $elnombre";
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	$repactualizoellog=ejecutar($actualizoellog);
?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
   <tr>
     <td colspan=4 class="titulo_seccion">El art&iacute;culo ha sido actualizado exitosamente!!!</td>
     <td  title="Regresar al modulo"><label class="boton" style="cursor:pointer" onclick="modif_articulo()" >Regresar</label></td>
   </tr>
</table>
<?}?>
