<?
include ("../../lib/jfunciones.php");
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>  
    <td colspan=8 class="titulo_seccion">Reimprimir Informe M&eacute;dico</td>   
   </tr> 
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">No. Proceso:</td>
        <td class="tdcampos"><input type="text" id="proceinforme" class="campos" size="12"></td>
        <td  title="Buscar clientes"><label class="boton" style="cursor:pointer" onclick="Bus_ReInforme()" >Buscar</label></td>
     </tr>
</table>	
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='rinfomedico'></div>
