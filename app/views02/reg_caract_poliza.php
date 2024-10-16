<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$q_polizas=("select * from polizas where activar='1' and id_poliza<>'37' order by nombre_poliza");
$r_polizas=ejecutar($q_polizas);
$q_tipo_caract=("select * from tbl_tipo_caract order by tipo_caract");
$r_tipo_caract=ejecutar($q_tipo_caract);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Registrar Caracteristicas del Plan</td>          
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           
   <tr>
             <td class="tdtitulos">Seleccione Plan:</td>
             <td class="tdcampos">
                    <select name="id_poliza" id="id_poliza" class="campos"  style="width: 230px;" onChange="cualnomente()">
			                          <?php  
			         while($f_polizas=asignar_a($r_polizas,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $f_polizas[id_poliza]?>"> <?php echo "$f_polizas[nombre_poliza]"?></option>
			      <?}?>
		        </select>  
                
             </td>
             <td class="tdtitulos">Seleccione Tipo Caracteristica:</td>
             <td class="tdcampos">
                    <select name="id_tipo_caract" id="id_tipo_caract" class="campos"  style="width: 200px;" onChange="cualnomente()">
			                          <?php  
			         while($f_tipo_caract=asignar_a($r_tipo_caract,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $f_tipo_caract[id_tipo_caract]?>"> <?php echo "$f_tipo_caract[tipo_caract]"?></option>
			      <?}?>
		        </select>  
                <a href="#" OnClick="bus_caract_poliza1();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
             </td>
   </tr>          
 </table>  
  <div id='bus_caract_poliza'></div>