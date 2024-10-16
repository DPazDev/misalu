<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$losente=("select entes.id_ente,entes.nombre from entes order by entes.nombre;");
$replosente=ejecutar($losente);
$estclientes=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente 
                      from estados_clientes order by estados_clientes.estado_cliente;");
$respestcliente=ejecutar($estclientes);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Historial de Nominas</td>          
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           
   <tr>
             <td class="tdtitulos">Ente:</td>
             <td class="tdcampos">
                    <select name="lente" id="lente" class="campos"  style="width: 230px;" onChange="cualnomente()">
			       <option value=""></option>
                   <option value="0">TODOS</option>
                    <?php  
			         while($ventes=asignar_a($replosente,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $ventes[id_ente]?>"> <?php echo "$ventes[nombre]"?></option>
			      <?}?>
		        </select>  
             </td>
   </tr>          
 </table>  
  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
 <div id='lasnominasente'></div>