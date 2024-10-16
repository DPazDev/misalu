<?
include ("../../lib/jfunciones.php");
$operacion=$_POST['operacion'];
$_SESSION['toperacion']=$operacion;
sesion();
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>
    <td colspan=8 class="titulo_seccion">Orden de m&eacute;dicamento</td>
   </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<input type="hidden" id="miopcion"  value="<?echo $operacion?>">
<br>
     <tr>
        <td class="tdtitulos">C&eacute;dula:</td>
        <td class="tdcampos"><input type="text" id="cedulaclien" class="campos" size="12"></td>
        <td class="tdtitulos">Tipo de cliente:</td>
         <td class="tdtitulos">
                     <select class="campos" id="tipcliente">
                          <option value="1">Afiliado</option>
                          <option value="2">Particular</option>
                     </select>
             </td>
        <td  title="Buscar clientes"><label class="boton" style="cursor:pointer" onclick="BuscarclienO()" >Buscar</label></td>
     </tr>
</table>
<div id='datosclientes'></div>
