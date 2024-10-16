<?
include ("../../lib/jfunciones.php");
sesion();
$lapolizaes=$_POST['nombrepoliz'];
$idpoliza=$_POST['polizaid'];
$laspropiedades=("select propiedades_poliza.cualidad from propiedades_poliza group by propiedades_poliza.cualidad order by propiedades_poliza.cualidad;");
$replaspropiedades=ejecutar($laspropiedades);
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Cargar las propiedades para la p&oacute;liza <?echo $lapolizaes ?></td>
	</tr>	 
 </table>	
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
             <td class="tdtitulos">Propiedad de la p&oacute;liza:</td>
             <td class="tdcampos">
			  <select id="propiedapolizas" class="campos"  style="width: 230px;" >
			        <option value=""></option>
              <?php  
			         while($laspolizas=asignar_a($replaspropiedades,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $laspolizas[cualidad]?>"> <?php echo "$laspolizas[cualidad]"?></option>
			      <?}?>
				    <option value="<?php echo $_SESSION['nuevapropiedadpo']?>"> <?php echo "$_SESSION[nuevapropiedadpo]"?></option>   
		     </select>  
			</td>
			<td colspan=4 title="Crear nueva propiedad de p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="nueva_pro_polizas()" >Registrar</label></td>
            </tr>
			<input type='hidden' id='lapolizap' value='<?echo $lapolizaes?>'>
             <input type='hidden' id='idpolizap' value='<?echo $idpoliza?>'>

			<tr>
             <td class="tdtitulos">Monto para la propiedad de la p&oacute;liza:</td>
             <td class="tdcampos"><input type="text" id="montopropi" class="campos" size="35"></td>
            </tr>
			<tr>
		      <td class="tdtitulos">Descripcion de la propiedad de la p&oacute;liza:</td>  
	          <td class="tdtitulos"><TEXTAREA COLS=65 ROWS=3 id="descripropoli" class="campos"></TEXTAREA></td>           </tr>
			<tr>
             <td class="tdtitulos"></td>
             <td class="tdcampos"></td>
            </tr>
			<tr>
             <td  title="Procesar la propiedad p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="guardapropiedadpoliza(),limpiardata(); return false;" >Procesar</label></td>   
            </tr>  
</table>		
<img alt="spinner" id="spinnerPPP" src="../public/images/esperar.gif" style="display:none;" />  
<div id='laspropiedadesp'></div>
<div id='nuevaspropiedadespo'></div>