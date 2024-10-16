<?php
/* Nombre del Archivo: guardar_telefono.php
   Descripción: GUARDA en la base de datos los Números Telefonicos modificados, para utilizarlo posteriormente
*/

include("../../lib/jfunciones.php");
//USUARIO DEL SISTEMA
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$usuario=$f_admin['login'];
///RECEPCION DE DATOS
$id_cli = $_REQUEST['idcli'];
$correo = $_REQUEST['correo'];
$telef1 = $_REQUEST['telef'];
$telef2 = $_REQUEST['celular1'];
$telef3 = $_REQUEST['celular2'];


$q_tel = "update clientes set email='$correo',telefono_hab='$telef2',telefono_otro='$telef3',celular='$telef1' where clientes.id_cliente='$id_cli'";
$r_tel = ejecutar($q_tel);
//registrar Modificaciones
$log="$usuario,HA MODIFICADO AL CLIENTE CON EL ID_CLIENTE $id_cli DATOS DE CONTACTO";
logs($log,$ip,$admin);

////CONSULTAR DATOS NUEVOS
$qcliente=("select
							clientes.id_cliente,
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							clientes.telefono_hab,
							clientes.telefono_otro,
							clientes.fecha_nacimiento,
							clientes.celular,
							clientes.email

					from
							clientes
					where
							clientes.id_cliente='$id_cli'");

$rg_cliente=ejecutar($qcliente);
$datoCliente=asignar_a($rg_cliente) ;
?>

<?php
//TELEFONO
$tlf1=trim($datoCliente['celular']);
$tlf2=trim($datoCliente['telefono_hab']);
$tlf3=trim($datoCliente['telefono_otro']);
$tlf='';
$t=0;
if($tlf1==''){$t++;$sp='';}else{$tlf=$tlf1;$sp=' , ';}
if($tlf2==''){$t++;}else{$tlf.=$sp.$tlf2;$sp=' , ';}
if($tlf3==''){$t++;}else{$tlf.=$sp.$tlf2;}

if($tlf=='') {
	$tlf='<span class="tdcamposr"><h2>NO REGISTRADO</h2></span>';
}else{
	$tlf="$tlf";
}
//CORREO
$correo=$datoCliente['email'];
$Email=trim($correo);
if($Email=='') {
	$Email='<span class="tdcamposr"><h2>NO REGISTRADO</h2></span>';
}

?>
<table class="" width='100%' border="0" cellpadding=0 cellspacing=0>
<tr>
	<td class="tdtitulos">Número de telefono:</td>
	<td class="tdcampos"><?php echo $tlf;?></td>
	<td class="tdtitulos">correo electronico</td>
	<td class="tdcampos"><?php echo $Email;?></td>
</tr>
</table>
