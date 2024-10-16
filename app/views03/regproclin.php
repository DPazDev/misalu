<?php
  include ("../../lib/jfunciones.php");
   sesion();
   $elid=$_SESSION['id_usuario_'.empresa];
   $elus=$_SESSION['nombre_usuario_'.empresa];

   if (empty($elid) or empty($elus)){
     echo " <script language='JavaScript'>
	       alert('JavaScript dentro de PHP');
            </script>";
   }else{ ?>
     <form method="get" onsubmit="return false;" name="procli" id="procli">
        <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	     <tr>
	         <td colspan=4 class="titulo_seccion">Registrar Proveedor Cl&iacute;nica</td>
	     </tr>
	     <tr>
	       <td class="tdtitulos">* RIF &oacute; Nombre</td>
	       <td class="tdcampos"><input class="campos" type="text" name="rifp" id="rifp"   ></td>
	       <td class="tdcampos" title="Buscar Proveedor"><label class="boton" style="cursor:pointer" onclick="buscarprocli()" >Buscar</label>
	       <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
             </tr>
	</table>
    <div id="inprove"></div>
  </form>
<?}?>  
