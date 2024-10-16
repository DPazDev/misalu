<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$losramos=("select ramos.id_ramo,ramos.ramo from ramos order by ramos.ramo;");
$replosramos=ejecutar($losramos);
$tipobeni=("select tipos_beneficiarios.id_tipo_beneficiario,tipos_beneficiarios.tipo from tipos_beneficiarios order by tipos_beneficiarios.tipo;");
$reptipobeni=ejecutar($tipobeni);



?>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Registrar P&oacute;liza</td>  
        <td colspan=1 class="titulo_seccion" title="Ver las p&oacute;lizas creadas"><label class="boton" style="cursor:pointer" onclick="ver_polizas()" >Ver p&oacute;lizas</label></td> 
	</tr>
  </table>
  <div id='crearpolizas'>
      <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
             <td class="tdtitulos">Nombre de la P&oacute;liza:</td>
             <td class="tdcampos"><input type="text" id="nompoliza" class="campos" size="35"></td>
            </tr>
           <tr>
		      <td class="tdtitulos">Descripcion de la P&oacute;liza:</td>  
	          <td class="tdtitulos"><TEXTAREA COLS=65 ROWS=3 id="descripoliz" class="campos"></TEXTAREA></td>           </tr>   
			<tr>
             <td class="tdtitulos">Ramo:</td>
             <td class="tdcampos">
			  <select id="ramos" class="campos"  style="width: 230px;" >
			        <option value=""></option>
              <?php  
			         while($hayramos=asignar_a($replosramos,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $hayramos[id_ramo]?>"> <?php echo "$hayramos[ramo]"?></option>
			      <?php }?>
		     </select>  
			</td>
			<td  title="Crear un nuevo ramo"><label class="boton" style="cursor:pointer" onclick="nuevo_ramo(); return false;" >Nuevo ramo</label></td>
            </tr> 
   <!-- --------MONEDA PARA CALCULAR POLIZA--- -->         
			<tr>
             <td class="tdtitulos">¿MONTOS EXPRESADOS EN?:</td>
             <td class="tdcampos">
<?php		
	//////NOMESCLATURAS DE MONEDADAS ///
$SqlMonedas=("select id_moneda,moneda,nombre_moneda,pais from tbl_monedas ORDER BY id_moneda ASC;");
$monedas=ejecutar($SqlMonedas);
?>		
			<select id="moneda" class="campos"  style="width: 230px;" >
			               <?php  
			         while($CodMoneda=asignar_a($monedas,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $CodMoneda[id_moneda]?>"> <?php echo "$CodMoneda[moneda]-$CodMoneda[nombre_moneda]($CodMoneda[pais])"?></option>
			      <?php }?>
		     </select>  
			
			
			</td>
            </tr>            
            
            
            
            
			<tr>
             <td class="tdtitulos">Primas personalizadas?:</td>
             <td class="tdcampos">
			 <form id='formulario'>  
			         <input type='radio' id='opc1' name='opciones' value='1' >SI 
                     <input type='radio' id='opc2' name='opciones' value='0' checked>No 
			</form>		
			</td>
            </tr>
			<tr>
             <td class="tdtitulos">P&oacute;liza por grupo familiar?:</td>
             <td class="tdcampos">
			 <form id='formulario2'> 
					<INPUT TYPE=RADIO id='pg1' NAME="poligrupo" VALUE='1'>Si
                    <INPUT TYPE=RADIO id='pg2' NAME="poligrupo" VALUE='0' checked>No
			 </form>		
			</td>
            </tr>
			<tr>
             <td class="tdtitulos">P&oacute;liza con lapsos de espera?:</td>
             <td class="tdcampos">
			<form id='formulario3'> 
					<INPUT TYPE=RADIO id='ple1' NAME="poliespera" VALUE=1 checked>Si
                    <INPUT TYPE=RADIO id='ple2' NAME="poliespera" VALUE=0>No
			</form>		
			</td>
            </tr>
			<tr>
             <td class="tdtitulos"></td>
             <td class="tdcampos"></td>
            </tr>
			<tr>
             <td  title="Guardar datos de la nueva p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="guardarpoliza(); return false;" >Guardar</label></td>   
            </tr>
	  </table>   
	  <div id='nuevoramo'></div>
			
	  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
	  <div id='poliguardad'></div>  
  </div>
  <img alt="spinner" id="spinnerP2" src="../public/images/esperar.gif" style="display:none;" />  
  <div id='laspolizas'>  
  </div> 
    