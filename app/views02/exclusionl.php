<?php
include ("../../lib/jfunciones.php");
sesion();
$entes=("select entes.id_ente,entes.nombre from entes order by entes.nombre");
$repente=ejecutar($entes);
$estclien=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente 
                            from estados_clientes 
               where (id_estado_cliente=5 or id_estado_cliente=1 or id_estado_cliente=8 or id_estado_cliente=9 or id_estado_cliente=10 ) 
               order by estados_clientes.estado_cliente");
 $repesclien=ejecutar($estclien);              
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">M&oacute;dulo de Exclusi&oacute;n</td>  
     </tr>
</table>	
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">Exclusi&oacute;n a:</td>
      <td class="tdcampos">
        <select id="tipoexclu" class="campos" >
               <option value=""></option>
               <option value="1">Todos</option>
               <option value="2">Titulares</option>
               <option value="3">Beneficiarios</option>
        </select>
       </td>
     </tr> 
     <tr>
      <td class="tdtitulos">Ente:</td>
      <td class="tdcampos">
       <select id="losente" class="campos" >
                <option value=""> </option>
                <?php  while($losente=asignar_a($repente,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $losente[id_ente]?>"> <?php echo "$losente[nombre]---$losente[id_ente]"?></option>
                <?php
                 }?>
         </select>
       </td>
     </tr> 
     <tr>
      <td class="tdtitulos">Pasar a:</td>
      <td class="tdcampos">
       <select id="estaclien" class="campos" >
                <option value=""> </option>
                <?php  while($losesclien=asignar_a( $repesclien,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $losesclien[id_estado_cliente]?>"> <?php echo "$losesclien[estado_cliente]"?></option>
                <?php
                 }?>
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
	       <td title="Generar cambio"><label id="titularboton" class="boton" style="cursor:pointer" onclick="exclulote()" >Guardar</label>
                <td title="Cargar archivo"><label id="titularboton" class="boton" style="cursor:pointer" onclick="CargarArchi()" >Cargar Archivo</label>
           <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
	</tr>
</table>     
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='exclusiones'></div>
