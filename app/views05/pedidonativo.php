<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Crear Pedido Donativo</td>
     </tr>
          <br>
        <tr>
              <td><label title="Crear pedido donativo"class="boton" style="cursor:pointer" onclick="creadonativo()" >Crear pedido donativo</label></td>  
              <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>
<div id="misdonativos"></div>
