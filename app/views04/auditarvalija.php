<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Audirar Valija</td>
     </tr>
          <br>
        <tr>
              <td class="tdtitulos" colspan="1">Estado de Valija:</td>
              <td class="tdcampos"  colspan="1">
			   <select id="estadovalija" class="campos"  style="width: 200px;" >
			    <option value=0></option>   
			    <option value=1>Creada</option>
				<option value=2>Enviada</option>
				<option value=3>Recibida</option>
				<option value=4>Entregada</option>
                <option value=5>Devuelta</option>				
				</select></td> 
			  <td><label title="Buscar pedidos"  class="boton" style="cursor:pointer" onclick="quevalija()" >Buscar</label></td>  
              <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="misvalija"></div>
