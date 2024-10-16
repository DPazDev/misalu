<?
include ("../../lib/jfunciones.php");
sesion();
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <td class="tdtitulos">C&oacute;digo de barra:</td>
        <td class="tdcampos"><input class="campos" type="text" id="codbarranu"   ></td>
      </tr>
       <tr>
        <td class="tdtitulos">Nombre del art&iacute;culo:</td>
        <td class="tdcampos"><input class="campos" type="text" id="artinu"   ></td>
      </tr>
       <tr>
        <td class="tdtitulos">Costo del art&iacute;culo:</td>
        <td class="tdcampos"><input class="campos" type="text" id="cosanu"   ></td>
      </tr>
      <tr>
        <td  title="Guardar el  m&eacute;dicamento al sistema"><label class="boton" style="cursor:pointer" onclick="inclMED1(); return false;" >Guardar</label></td> 
      </tr>
</table>
<div id='seguardome'></div>
