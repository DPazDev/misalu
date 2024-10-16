<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Reimprimir cuadro recibo</td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">C&eacute;dula del titular:</td>
        <td class="tdcampos"><input type="text" id="cedulatitu" class="campos" size="15"></td>  
    </tr>
    <tr>
	   <td  title="Reimprimir cuadro recibo"><label class="boton" style="cursor:pointer" onclick="buscontratos()" >Buscar</label></td> 
	</tr>
	   	
</table>
<div id="loscontratos"></div>
