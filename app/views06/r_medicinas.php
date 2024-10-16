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
         <td colspan=7 class="titulo_seccion">Opciones de ordenes de med&iacute;camentos</td>
     </tr>
          <br>
        <tr>
              <td  title="med&iacute;camentos con mayor salida"><label class="boton" style="cursor:pointer" onclick="medimayor()" >Med. con mayor salida</label></td>
              <td  title="med&iacute;camentos continuos"><label class="boton" style="cursor:pointer" onclick="mediconti()" >Med. en General</label></td>
             <td  title="Control de medicamentos"><label class="boton" style="cursor:pointer" onclick="mediestadis()" >Control de Med</label></td>
             <td  title="Estad&iacute;stica del uso de ordenes de medicamentos externa"><label class="boton" style="cursor:pointer" onclick="estadisormeex()" >Estad&iacute;stica</label></td> 
             <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>
<div id="opcionemedica"></div>

