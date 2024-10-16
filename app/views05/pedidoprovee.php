<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Crear Pedido</td>
     </tr>
          <br>
        <tr>
              <td  title="Crear pedido a una dependencia"><label class="boton" style="cursor:pointer" onclick="pedidodep1()" >Crear pedido dependencia </label></td>
             <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>
<div id="pedidoprodepen"></div>
<div id="pedidoarticulos"></div>
