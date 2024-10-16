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
$lassubdi=("select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivisiones.subdivision;");
$replasubd=ejecutar($lassubdi);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Calcular nomina</td>          
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
           <td colspan=2 class="tdtitulos">* Seleccione fecha inicio:
	       <input readonly type="text" size="10" id="Fini" class="campos" maxlength="10">
	       <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
          <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	      <td colspan=2 class="tdtitulos">* Seleccione fecha final:
	      <input readonly type="text" size="10" id="Fifi" class="campos" maxlength="10">
	      <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')" title="Ver calendario">
	      <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
   </tr>
   <tr>
             <td class="tdtitulos">Ente:</td>
             <td class="tdcampos">
                    <select id="lente" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                    <?php  
			         while($ventes=asignar_a($replosente,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $ventes[id_ente]?>"> <?php echo "$ventes[nombre]"?></option>
			      <?}?>
		        </select>  
             </td>
              <td class="tdtitulos">Subdivisi&oacute;n:</td>  
	          <td class="tdtitulos">
                 <select id="lasubdivi" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                   <option value="0">TODAS</option>
                    <?php  
			         while($subdi=asignar_a($replasubd,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $subdi[id_subdivision]?>"> <?php echo "$subdi[subdivision]"?></option>
			      <?}?>
		        </select> 
              </td>           
    </tr>  
    <tr>
             <td class="tdtitulos">Estatus:</td>
             <td class="tdcampos">
                <select id="lestat" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                   <option value="100">ACTIVO Y LAPSO DE ESPERA</option>
                    <?php  
			         while($nesta=asignar_a($respestcliente,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $nesta[id_estado_cliente]?>"> <?php echo "$nesta[estado_cliente]"?></option>
			      <?}?>
		        </select> 
             </td>
             <td class="tdtitulos">Con c&oacute;digo:</td>
             <td class="tdcampos">
                <input type="radio" name="group1" id="cods" value="1" checked>Si 
                <input type="radio" name="group1" id="codn" value="2"> No<br>
             </td>
    </tr>  
    <tr>
        <td class="tdtitulos">Forma de pago:</td>  
	          <td class="tdtitulos">
                      <select class="campos" id="forma_pago">
                        <option value=""></option>
                        <option value="ANUAL">Anual</option>
                        <option value="SEMESTRAL">Semestral</option>
                        <option value="TRIMESTRAL">Trimestral</option>
                        <option value="MENSUAL">Mensual</option>
                        </select>
              </td> 
              <td class="tdtitulos">Cobrar prima titular?</td>
              <td class="tdcampos">
                <input type="radio" name="groupt1" id="cobprn" value="1" checked> No
                <input type="radio" name="groupt1" id="cobprs" value="2"> Si<br>
             </td>
    </tr>
    <tr>
              <td><label title="Calcular nomina" id="enteguardado1" class="boton" style="cursor:pointer" onclick="calcularnom()" >Generar Nomina</label></td>  
              <td><label title="Calcular nomina especial" id="enteguardado1" class="boton" style="cursor:pointer" onclick="calcularnom1()" >Generar Nomina - Especial</label></td>  
    </tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  