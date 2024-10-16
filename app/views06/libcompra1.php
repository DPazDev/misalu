<?php
  include ("../../lib/jfunciones.php");
   sesion();
   $elid=$_SESSION['id_usuario_'.empresa];
   $elus=$_SESSION['nombre_usuario_'.empresa];
?>
     <form method="get" onsubmit="return false;" name="procli" id="procli">
        <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	     <tr>
	         <td colspan=4 class="titulo_seccion">Generar Libro de Compra</td>
	     </tr>
	     <tr>
	       <td class="tdtitulos">Fecha Inicio</td>
	       <td class="tdcampos"><input  type="text" size="10" id="feini" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feini', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	                 
	       <td class="tdtitulos">Fecha Fin</td>
	       <td class="tdcampos"><input  type="text" size="10" id="feinf" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feinf', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	                 
	     </tr>
	     <tr>
	      
	      
	       <td class="tdcampos" title="Generar Libro de Compra"><label class="boton" style="cursor:pointer" onclick="GLibroCompra()" >Generar Libro</label>
	       <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
             </tr>
	</table>
    <div id="libcompra"></div>
  </form>

