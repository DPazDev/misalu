<?
include ("../../lib/jfunciones.php");
sesion();
//destruimos las variables de conteo para evitar duplicidad

$opera1=$_SESSION['toperacion'];
$fechapro=$_POST['fechaop'];
$enfermedad=$_POST['laenferme'];
$elcoment=$_POST['elcoment'];
$proveeid=$_POST['elprovee'];
$ToBcober=$_POST['cobToB'];
$IdDependencia=$_POST['IdDependencia'];
$fechaActual=date('Y-m-d');
$especialidadm=("select especialidades_medicas.id_especialidad_medica,
                  especialidades_medicas.especialidad_medica
               from especialidades_medicas order by especialidades_medicas.especialidad_medica;");
$repespecialidam=ejecutar($especialidadm);
$tipodeoperacion=$_SESSION['toperacion'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
 $tipodeoper=0;
 if($opera1=='interna'){
	$tipodeoper=1;
	$buscarlosartideladmin=("select tbl_insumos.insumo,tbl_insumos.id_insumo,
   tbl_admin_dependencias.id_dependencia,
   tbl_laboratorios.laboratorio,tbl_insumos_almacen.cantidad,tbl_dependencias.dependencia
   from
      tbl_insumos,tbl_admin_dependencias,tbl_insumos_almacen,tbl_laboratorios,tbl_dependencias
   where
   tbl_admin_dependencias.id_dependencia='$IdDependencia' and
   tbl_admin_dependencias.activar<>'4' and
   tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
   tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
   tbl_insumos_almacen.id_dependencia=tbl_admin_dependencias.id_dependencia and
   tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and
   tbl_admin_dependencias.id_admin=$elid and
   tbl_insumos_almacen.cantidad>0 and
   tbl_admin_dependencias.id_admin=$elid and (tbl_insumos.id_tipo_insumo=1 or tbl_insumos.id_tipo_insumo=2) order by tbl_insumos.insumo;");

   $repbucarlosartideladmin=ejecutar($buscarlosartideladmin);
   $buscardepen=("select tbl_admin_dependencias.id_dependencia from tbl_admin_dependencias where
                               tbl_admin_dependencias.id_admin=$elid");
	$repbuscardepen=ejecutar($buscardepen);
	$datbuscardepen=assoc_a($repbuscardepen);
	$_SESSION['ladepenusu']=$datbuscardepen['id_dependencia'];
}
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
	      <?if ($tipodeoper==0){?>
	       <td class="tdtitulos">C&oacute;digo de barra:</td>
           <td class="tdcampos"><input class="campos" type="text" id="codbarra"   ></td>
		  <?}else{?>
		    <td class="tdtitulos">Nombre del articulo:</td>
            <td class="tdcampos">
			    <select id="codbarra" class="campos"  style="width: 230px;" >
				<option value=""></option>
			        <? while($losarticulos=asignar_a($repbucarlosartideladmin,NULL,PGSQL_ASSOC)){
                // \"$losarticulos[id_insumo]-$losarticulos[id_dependencia]-$losarticulos[cantidad]\
					echo"<option value=\"$losarticulos[id_insumo]-$losarticulos[id_dependencia]-$losarticulos[cantidad]-$losarticulos[laboratorio]\">
						$losarticulos[insumo]---$losarticulos[laboratorio]---($losarticulos[cantidad])--<label style=\"color:#DF0101\" >(Depen: $losarticulos[dependencia])</label>
				         </option>";
			          }

				echo"</select>";?>
		<?}?>
	  </tr>
	  <tr>
	       <td class="tdtitulos">Tratamiento continuo:</td>
           <td class="tdcampos"><input class="campos" type="checkbox" id="tramiento" value="1"></td>
	  </tr>
	  <tr>
	       <td class="tdtitulos">Fecha fin de tratamiento:</td>
           <td class="tdcampos">
		   <input readonly type="text" size="10" id="Fini" class="campos" maxlength="10" value="<?php echo $fechaActual;?>">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	  </tr>
	   <tr>
	       <td class="tdtitulos">Servicio:</td>
		   <td class="tdcampos">
		          <select id="especialidame" class="campos"  style="width: 230px;" >
			            <option value=""></option>
			         <?while($espmedic=asignar_a($repespecialidam,NULL,PGSQL_ASSOC)){
					   echo"<option value=\"$espmedic[id_especialidad_medica]\">
						$espmedic[especialidad_medica]
				         </option>";
			          }?>
		         </select></td>
	   </tr>
	   <tr>
	       <td class="tdtitulos">Cantidad:</td>
           <td class="tdcampos"><input class="campos" type="text" id="cantidad" size=5  onblur="ProcepmediC()" ></td>
	  </tr>
	<br>
</table>
     <input type=hidden id='cualenferm' value='<?echo $enfermedad;?>'>
	 <input type=hidden id='cualcoment' value='<?echo $elcoment;?>'>
	 <input type=hidden id='cualproved' value='<?echo $proveeid;?>'>
	<input type=hidden id='lacobertura' value='<?echo $ToBcober;?>'>
	<input type=hidden id='lafechaproc' value='<?echo $fechapro;?>'>

<div id="losartifarmacia"></div>
