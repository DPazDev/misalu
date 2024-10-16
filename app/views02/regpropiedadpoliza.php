<?
include ("../../lib/jfunciones.php");
include ("arreglos.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$lapolizaes=$_POST['lapropipoliza'];
$elmontopolizaes=$_POST['montopoli'];
$ladescripolizaes=$_POST['descripoli'];
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];
$elidpoliza=$_SESSION['elidpoliza'];
$lamatix=cargarMatriprimero($matriz,$paso,$lapolizaes,$elmontopolizaes,$ladescripolizaes,$elidpoliza); 
$cuantomatriz=count($lamatix);
if($cuantomatriz>=1){
$idpolizaes=$_POST['idpoliza'];
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
                          <td class=\"tdcampos\">$monto</td> 
                        </tr>";
							 
				$lin++;	   
				}
               }  
}?>			  
</table>
<input type='hidden' id='idpolizafinal' value='<?echo $idpolizaes?>'>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=1 class="titulo_seccion" title="Guardar las propiedades de la p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="guarda_poliza_fina()" >Guardar</label></td>  
	</tr>	 
 </table>
<img alt="spinner" id="spinnerPFin" src="../public/images/esperar.gif" style="display:none;" />   