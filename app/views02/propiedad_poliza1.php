<?
include ("../../lib/jfunciones.php");
include ("arreglos.php");
sesion();
$lapolizaes=$_POST['nombrepoliz'];
$idpoliza=$_POST['polizaid'];
$laspropiedades=("select propiedades_poliza.cualidad from propiedades_poliza group by propiedades_poliza.cualidad order by propiedades_poliza.cualidad;");
$replaspropiedades=ejecutar($laspropiedades);

//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$idpoliza';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=' '.$Moneda['simbolo'].' ('.$Moneda['moneda'].')';


?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Cargar las propiedades para la p&oacute;liza <?php echo $lapolizaes ?></td>
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
<div id='nuevaspropiedadespo'></div>
<img alt="spinner" id="spinnerPPP" src="../public/images/esperar.gif" style="display:none;" /> 
 
<div id='laspropiedadesp'>
<?
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];

$policar=("select propiedades_poliza.* from propiedades_poliza where propiedades_poliza.id_poliza=$idpoliza;");
$reppoli=ejecutar($policar);
 while($hapoliza=asignar_a($reppoli,NULL,PGSQL_ASSOC)){
     $nompro=$hapoliza['cualidad'];
     $montpoliz=$hapoliza['monto'];
     $descpoliz=$hapoliza['descripcion'];
    $lamatix=cargarMatriprimero($matriz,$paso,$nompro,$montpoliz,$ladescripolizaes,$idpoliza); 
    $paso++;
}
  $_SESSION['pasopedido']=$paso;
  $_SESSION['matriz']=$lamatix;
  $cuantomatriz=count($_SESSION['matriz']);
  if($cuantomatriz>=1){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
       <br> 
         <td colspan=4 class="titulo_seccion">Propiedades cargada a la p&oacute;liza</td>    
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">Propiedad p&oacute;liza.</th>
                 <th class="tdtitulos">Monto.</th>
			  </tr>
			<?  
			   $lin=1;
			      for($i=0;$i<=$cuantomatriz;$i++){
                          $nom=$lamatix[$i][0];
                          $monto=$lamatix[$i][1];
                      if($monto>1){  
                   echo"<tr>
                          <td class=\"tdcampos\">$lin</td>
                          <td class=\"tdcampos\">$nom</td>  
                          <td class=\"tdcampos\">$monto $moneda</td> 
                        </tr>";
							 
				$lin++;	   
				}
               }  
}?>			  
</table>
</div>