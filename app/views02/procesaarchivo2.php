<?
include ("../../lib/jfunciones.php");
sesion();
$archivop=$_REQUEST['elarchivo'];
$estados=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente 
           from estados_clientes 
           where id_estado_cliente<>4 and id_estado_cliente<>3 order by estado_cliente;");
$repestados=ejecutar($estados);           
?>
<input type="hidden" id="nombrearchivo" value="<? echo $archivop?>" > 
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Exclusi&oacute;n por lote del archivo <?echo $archivop?></td>  
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">Pasar a:</td>
      <td class="tdcampos"><select id="clienestado" class="campos" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($estado=asignar_a($repestados,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $estado[id_estado_cliente]?>"> <?php echo "$estado[estado_cliente]"?></option>
			   <?php
			  }
			  ?>
			 </select>
	 </td>
	 </tr>
	  <tr>
        <td class="tdtitulos" colspan="1">Fecha para cambio:</td>
         <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="inempre" class="campos" maxlength="10">
	     <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'inempre', 'yyyy-mm-dd')" title="Ver calendario">
	    <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
     </tr>   
     <tr> 
	   <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="comentario" class="campos"></TEXTAREA></td>
	</tr>
    <tr>
	       <td title="Procesar cambio"><label id="titularboton" class="boton" style="cursor:pointer" onclick="excluloteArchivo()" >Procesar</label>
	</tr>

</table>	   
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='exclusionarchivo'></div>
