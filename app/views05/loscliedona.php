<?
include ("../../lib/jfunciones.php");
sesion();
$elclient=$_POST['tipodeclien'];
if ($elclient==1){
	 echo "<table class=\"tabla_citas\"   cellpadding=0 cellspacing=0>
                 <tr>
                     <td class=\"tdtitulos\" >Ingrese n&uacute;mero de c&eacute;dula:</td>
                     <input type=\"hidden\" id=\"tipodecliedon\" value=\"$elclient\">
		     <td class=\"tdcampos\"><input type=\"text\" id=\"ceducliendona\" size=12> </td>
                     <td  title=\"Buscar cliente afiliado\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"DonaCliente()\" >Buscar</label></td>
                  </tr>
                </table>
               ";?>
	       <div id='datoscliendon' style='display: none'>
                 
              </div>		   
	<?}else{
                echo"
                    <table class=\"tabla_citas\"   cellpadding=0 cellspacing=0> 
                    <tr>
                     <td class=\"tdtitulos\" >Ingrese n&uacute;mero de c&eacute;dula:</td>
		              <td class=\"tdcampos\"><input class=\"campos\" type=\"text\" id=\"ceduclienoafi\" size=12> </td>
                     <td class=\"tdtitulos\" >Ingrese nombre completo:</td>
                     <td class=\"tdcampos\"><input class=\"campos\" type=\"text\" id=\"nombrnoafi\" size=25> </td>  
                     </tr>
                     <tr>
                     <td class=\"tdtitulos\" >Ingrese n&uacute;mero de tel&eacute;fono:</td>
		            <td class=\"tdcampos\"><input class=\"campos\" type=\"text\" id=\"telenoafi\" size=12> </td>
                  </tr>
                </table>
               ";
                    
               }?>
<input type='hidden' id='cualclien' value='<?echo $elclient?>'>