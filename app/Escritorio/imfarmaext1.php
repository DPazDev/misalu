<?php
include ("../../lib/jfunciones.php");
sesion();
$a=$_POST['operacion'];
$_SESSION['operaelimina']=$a;
?>
<input type="hidden" id="miopcion"  value="<?echo $a?>">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>
    <td colspan=8 class="titulo_seccion">Orden de m&eacute;dicamento</td>
   </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">No. proceso:</td>
        <td class="tdcampos"><input type="text" id="noproceso" class="campos" size="12"></td>
        <td  title="Buscar orden"><label class="boton" style="cursor:pointer" onclick="Buscaroet();MostraFarma('<?php echo $a;?>')" >Buscar</label></td>
        
     </tr>
</table>
<div id='estadoproceso'></div>
<div id='ordeexiste'></div>
<div id='datafarmaex'></div>
