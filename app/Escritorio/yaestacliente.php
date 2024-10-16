<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $cedclien=$_REQUEST["cedula"];  
  $enteid=$_REQUEST["enteid"];
  $busco=("select titulares.id_titular from titulares,clientes
where 
clientes.id_cliente=titulares.id_cliente and
clientes.cedula='$cedclien' and
titulares.id_ente='$enteid';");
$repbusco=ejecutar($busco);
$cuantosbus=num_filas($repbusco);
if($cuantosbus>=1){
?>
<input type="hidden" id="siexistecliente" value="3000" > 
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
      <tr>
	   <td colspan=4 class="titulo_seccion"><label style="color: #FF0000; font-size: 18pt">El titular ya esta registrado en el ente seleccionado!!!</label></td>
         </tr>
      </table>
<?}else{?>      
<input type="hidden" id="siexistecliente" value="0" > 
<?}?>

