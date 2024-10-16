<?php
include ("../../lib/jfunciones.php");
sesion();
$cedclien=$_POST["cedulati"];
$nomop=$_POST["lanombre"];
$apeop=$_POST["laape"];
$laciudad=("select ciudad.id_ciudad,ciudad.ciudad from ciudad order by ciudad.ciudad;");
$relaciudad=ejecutar($laciudad);
$fecha=date("Y-m-d");
$entetitu=$_POST['elenteti'];
$elcliente=$_POST['clienid'];
$verelente=("select titulares.id_ente from titulares,clientes where clientes.id_cliente=titulares.id_cliente and
                titulares.id_cliente=$elcliente and titulares.id_ente=$entetitu");               
$respverente=ejecutar($verelente); 
$cuantenete=num_filas($respverente);
if ($cuantenete<=0){  
?>
 <input type="hidden" id="cliencedula1" value="<? echo $cedclien?>" >  
 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">C&eacute;dula:</td>
      <td class="tdcampos"><? echo "$cedclien"?></td>
    </tr>
     <tr>
	   <td class="tdtitulos" colspan="1">Ente:</td>
       <td class="tdcampos"  colspan="1">Particular</td> 
	   <td class="tdtitulos" colspan="1">Sub-divisi&oacute;n:</td>
       <td class="tdcampos"  colspan="1">Particular</td>  
	 </tr> 
     <tr>
	   <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="cliennombre1" class="campos" size="30" value="<?echo $nomop?>"></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clienapellido1"  class="campos" size="30" value="<?echo $apeop?>"></td>
	 </tr>   
	<tr>
	   <td class="tdtitulos" colspan="1">Genero:</td>
        <td class="tdcampos"  colspan="1"><select name="cliengenero1" id="cliengenero1" class="campos" style="width: 100px;">
							<option value=""></option>
                            <option value="0">Femenino</option>
							<option value="1">Masculino</option>
						</select>	                    
		</td>  				
       <td class="tdtitulos" colspan="1">Tel&eacute;fono habitaci&oacute;n:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clientfn1"  class="campos" size="15" onkeypress="return SoloNumeros(event)"></td>	
	 </tr>
     <tr>
	    <td class="tdtitulos" colspan="1">Fecha de nacimiento:</td>
       <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="fnaci1" class="campos" maxlength="10" value="<?echo $fecha;?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fnaci1', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>   			                     
       <td class="tdtitulos" colspan="1">Ciudad:</td>
       <td class="tdcampos" colspan="1"><select id="clienciudad1" class="campos" style="width: 130px;">
	                           <option value=""></option>  
                              <?php  while($verciudad=asignar_a($relaciudad,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verciudad[id_ciudad]?>"> <?php echo "$verciudad[ciudad]"?></option>
			   <?php
			  }
			  ?>    
						</select>	</td>
	 </tr> 
     <tr> 
	   <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliendirr1" class="campos"></TEXTAREA></td>
	</tr> 
	<tr>
	   <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliencoment1" class="campos"></TEXTAREA></td>
	</tr>
  </table>  
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
           <tr>
               <td><img alt="spinner" id="spinnerPT" src="../public/images/esperar.gif" style="display:none;" /></td>
           </tr>
	     <tr>
	       <td title="Guardar cliente"><label id="titularboton" class="boton" style="cursor:pointer" onclick="validclientetitular()" >Guardar</label>
	      </tr>
          </table>
<?}else{?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
		 <br>
           <tr>  
		       <td colspan=8 class="titulo_seccion">El Titular ya esta registrado en el ente seleccionado!!</td>   
		   </tr> 
          </table>
<?}?>
