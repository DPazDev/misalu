<?php
include ("../../lib/jfunciones.php");
sesion();
$idcliente=$_REQUEST['elidclien'];
$correocli=$_REQUEST['elcorreo'];
$ceducli=$_REQUEST['lacedula'];
$nomcli=strtoupper($_REQUEST['elnombr']);
$apellcli=strtoupper($_REQUEST['elapelli']);
$thab=$_REQUEST['tehab'];
$tcelu=$_REQUEST['tecelu'];
$direcli=strtoupper($_REQUEST['ladirecc']);
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$actualclien=("update clientes set nombres='$nomcli',apellidos='$apellcli',email='$correocli',direccion_hab='$direcli',telefono_hab='$thab',
                                                      celular='$tcelu',cedula='$ceducli' where id_cliente=$idcliente;");
$repactualclien=ejecutar($actualclien);                                                      
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado los datos basicos del cliente con id_cliente  $idcliente";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de los registros en la tabla logs;
//**********************************//           
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">El cliente ha sido actualizado!!</td>  
     </tr>
</table>	 