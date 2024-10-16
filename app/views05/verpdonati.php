<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Control de donativos realizados</td>
     </tr>
          <br>
         <tr>
              <td class="tdtitulos" colspan="1">Estado del pedido:</td>
              <td class="tdcampos"  colspan="1">
			   <select id="estadopedi" class="campos"  style="width: 200px;" onChange="quetoco()">
			        <option value=0></option>   
			        <option value=1>Pendiente</option>
				<option value=2>Procesado</option>
			  </select></td> 
			  <td class="tdtitulos" colspan="1">Mis dependencias:</td>
               <td class="tdcampos"  colspan="1"><div id='nohay'><select class="campos" style="width: 200px;" ></select></div><div id='segunpedido'></div></td>
			  <td><label title="Buscar pedidos"  class="boton" style="cursor:pointer" onclick="buscardona()" >Buscar</label></td>  
              <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>	
<div id='losdonativos'></div>
