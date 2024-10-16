<?
include ("../../lib/jfunciones.php");
sesion();
$cedbenfi=$_POST['lacedulaclien'];
$buscclibenf=("select * from clientes where cedula='$cedbenfi';");
$resbuccliben=ejecutar($buscclibenf);
$cuaclient=num_filas($resbuccliben);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
<tr>
<br>
  <td class="tdtitulos">* C&eacute;dula del titular:
  <input class="campos" type="text" name="cedultibenf" id="cedultibenfi"   >   
  <label class="boton" title="Buscar el titular" style="cursor:pointer" onclick="$('dataclienteTB').hide(),$('clientenuevo').appear(),$('datacl').hide(),nuevobenf(); return false;" >Buscar</label>  </td>
</tr>  
</table>  
