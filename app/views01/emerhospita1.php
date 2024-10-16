<?php
include ("../../lib/jfunciones.php");
sesion();
$opera1=$_SESSION['toperacion'];
$fechapro=$_POST['fechaop'];
$numdpresu=$_POST['numpres'];
$elcoment=$_POST['elcoment'];
$proveeid=$_POST['elprovee'];
$ToBcober=$_POST['cobToB'];
$elservi=$_POST['tiposervi'];
$IdDependencia=$_POST['IdDependencia'];
list($vartiposrvi,$varservi)=explode('-',$elservi);
$tipodeoperacion=$_SESSION['toperacion'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
    $verplanilla=("select entes.nombre from procesos,titulares,entes
    where
       procesos.nu_planilla='$numdpresu' and
       procesos.id_titular=titulares.id_titular and
       titulares.id_ente=entes.id_ente group by entes.nombre");
    $repverplanilla=ejecutar($verplanilla);
    $cuantplanilla=num_filas($repverplanilla);
    if($cuantplanilla>1){?>
     <input type="hidden" id="nopresupuesto" value="<?echo $numdpresu?>">
     <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
      <tr>
        <td colspan=8 class="titulo_seccion">Error: La planilla esta en distintos entes!! <label class="boton" style="cursor:pointer" onclick="PlanillaUso(); return false;" >Ver planilla</label></td>
      </tr>
     </table>
   <?}else{
   $buscarlosartideladmin=("select tbl_insumos.insumo,tbl_insumos.id_insumo,
   tbl_admin_dependencias.id_dependencia,
   tbl_laboratorios.laboratorio,tbl_insumos_almacen.cantidad,tbl_dependencias.dependencia,tbl_insumos_almacen.id_dependencia
   from
      tbl_insumos,tbl_admin_dependencias,tbl_insumos_almacen,tbl_laboratorios,tbl_dependencias
   where
   tbl_admin_dependencias.id_dependencia='$IdDependencia' and
   tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
   tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
   tbl_insumos_almacen.id_dependencia=tbl_admin_dependencias.id_dependencia and
   tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and
   tbl_dependencias.activo <> 1 and
   tbl_admin_dependencias.id_admin=$elid and tbl_admin_dependencias.activar<>4 and
   tbl_insumos_almacen.cantidad>0 and
   tbl_admin_dependencias.id_admin=$elid and (tbl_insumos.id_tipo_insumo=1 or tbl_insumos.id_tipo_insumo=2) order by tbl_insumos.insumo;");
   $repbucarlosartideladmin=ejecutar($buscarlosartideladmin);
  //echo $buscarlosartideladmin;

   $buscardepen=("select tbl_admin_dependencias.id_dependencia from tbl_admin_dependencias where
                               tbl_admin_dependencias.id_admin=$elid");
	$repbuscardepen=ejecutar($buscardepen);
	$datbuscardepen=assoc_a($repbuscardepen);
	$_SESSION['ladepenusu']=$datbuscardepen['id_dependencia'];

//echo "$fechapro-------$enfermedad----------$elcoment------$proveeid---$ToBcober";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
      <tr>
         <td colspan=8 class="titulo_seccion">Carga de m&eacute;dicamentos</td>
       </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
       <br>
      <tr>
	    <td class="tdtitulos">Nombre del articulo:</td>
            <td class="tdcampos">
			    <select id="codbarra" class="campos"  style="width: 230px;" >
				<option value=""></option>
			        <?php while($losarticulos=asignar_a($repbucarlosartideladmin,NULL,PGSQL_ASSOC)){
					echo"<option value=\"$losarticulos[id_insumo]-$losarticulos[id_dependencia]-$losarticulos[cantidad]\">
						$losarticulos[insumo]---$losarticulos[laboratorio]---($losarticulos[cantidad])--<label style=\"color:#DF0101\" >(Depen: $losarticulos[dependencia])</label>
				         </option>";
			          }

				echo"</select>";?>

	  </tr>
	 <tr>
	       <td class="tdtitulos">Cantidad:</td>
           <td class="tdcampos"><input class="campos" type="text" id="cantidad" size=5  onblur="ProcepmediCEH()" ></td>
	  </tr>
	<br>
</table>
  <input type=hidden id='cualnumproce' value='<?echo $numdpresu;?>'>
	<input type=hidden id='cualcoment' value='<?echo $elcoment;?>'>
	<input type=hidden id='cualproved' value='<?echo $proveeid;?>'>
	<input type=hidden id='lacobertura' value='<?echo $ToBcober;?>'>
	<input type=hidden id='lafechaproc' value='<?echo $fechapro;?>'>
        <input type=hidden id='cualtiposervi' value='<?echo $vartiposrvi;?>'>
	<input type=hidden id='cualservici' value='<?echo $varservi;?>'>
<div id="losartifarmacia"></div>

<?php } ?>
