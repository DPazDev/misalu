<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$escoguer=$_POST['escogue'];
if($escoguer<>6){
$misdependecias=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia from tbl_dependencias,tbl_admin_dependencias where
tbl_admin_dependencias.id_admin=$elid and
tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
tbl_dependencias.activo=0 and
(tbl_admin_dependencias.activar=1 or tbl_admin_dependencias.activar=2) order by tbl_dependencias.dependencia");
$repuesmisdepend=ejecutar($misdependecias);
}else{
	$misdependecias=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia from tbl_dependencias order by tbl_dependencias.dependencia");
$repuesmisdepend=ejecutar($misdependecias);
	}
?>

			     <select id="misdependen" class="campos"  style="width: 230px;" >
			        <option value=""></option>
                    <?php  while($lasdepend=asignar_a($repuesmisdepend,NULL,PGSQL_ASSOC)){?>
					<option value="<?php echo $lasdepend[id_dependencia]?>"> <?php echo "$lasdepend[dependencia]"?>
					</option>
			      <?php }?>
				 </select>
