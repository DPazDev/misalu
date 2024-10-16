<?php
include ("../../lib/jfunciones.php");
sesion();
$narticulo=$_REQUEST['elnarticulo'];
$nomarticulo=strtoupper($_REQUEST['elnombre']);
$descriarticulo=strtoupper($_REQUEST['eldescriarticulo']);
$desley=strtoupper($_REQUEST['elnomley']);
$numeroarti=$_REQUEST['eliddarti'];
$busprimero=("select count(articulocontrato.id_articulocon) from articulocontrato where id_articulocon=$numeroarti");
$repbusprimero=ejecutar($busprimero);
$dataprimero=assoc_a($repbusprimero);
$cuantosprimero=$dataprimero['count'];
if($cuantosprimero<=0){
 $guararti=("insert into articulocontrato(nombre_articulo,concepto,numarticulo,nombreley) values('$nomarticulo','$descriarticulo','$narticulo','$desley')");
 $repguararti=ejecutar($guararti);
 $articulos=("select * from articulocontrato order by numarticulo");
 $reparticulos=ejecutar($articulos);?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">El Art&iacute;culo se ha registrado exitosamente!!</td>          
	</tr>
</table>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Art&iacute;culos creados</td>          
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">#Art&iacute;culo.</th>  
			     <th class="tdtitulos">Ley.</th>  
                 <th class="tdtitulos" align="center">Nombre.</th>
                 <th class="tdtitulos" align="center">Descripci&oacute;n.</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>  
              </tr>  
           <? 
              while($datolosarti=asignar_a($reparticulos,NULL,PGSQL_ASSOC)){
           ?> 
             <tr>
              <td class="tdcampos" align="justify"><?echo $datolosarti['numarticulo']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['nombreley']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['nombre_articulo']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['concepto']?></td>   
              <td  title="Modificar art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="Modifarti('<?echo $datolosarti[id_articulocon]?>'); return false;" >Modificar</label></td>   
             </tr> 
              <tr><td colspan=5><hr></td></tr>
           <?}?>   
           
</table>                 
                       
<?}else{
  $actarti=("update articulocontrato set nombre_articulo='$nomarticulo',concepto='$descriarticulo',numarticulo='$narticulo',nombreley='$desley'  where	id_articulocon=$numeroarti");	
  $repactarti=ejecutar($actarti);
  $fecha=date("Y-m-d");
  $hora=date("H:i:s");
  $elid=$_SESSION['id_usuario_'.empresa];
  $elus=$_SESSION['nombre_usuario_'.empresa];
  //Guardar los datos en la tabla logs;
  $mensaje="$elus, ha modificado el articulo con el id_articulocon=$numeroarti";
  $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
  $inrelo=ejecutar($relog);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">El Art&iacute;culo se ha modificado exitosamente!!</td>          
	</tr>
</table>
<?}?>
