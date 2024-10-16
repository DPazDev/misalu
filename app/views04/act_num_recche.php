<?php
include ("../../lib/jfunciones.php");

$codigo=$_REQUEST['codigo'];
$actnumche=strtoupper($_REQUEST['actnumche']);

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** busco el usuario admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


/* **** Actualizo la tabla facturas_procesos los procesos a anulados **** */
$mod_fpro="update facturas_procesos set numero_cheque='$actnumche' where  facturas_procesos.codigo='$codigo'";
$fmod_fpro=ejecutar($mod_fpro);


	
/* **** Se registra lo que hizo el usuario**** */
$log="Se Actualizo  el  Cheque  con el  codigo numero $codigo numero de cheque $actnumch";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>





<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Cheque o Recibo Actualizado Con Exito</td>	</tr>
<tr>
		<td class="tdtitulos">
			
			<a href="#" OnClick="che_reembolso();" class="boton" title="Ir a Crear Cheques Reembolsos">Crear Otro Cheque de Reembolso</a><a href="#" OnClick="bus_cheque();" class="boton" title="Ir a Buscar Cheques Reembolsos">Buscar Cheque de Reembolso</a><a href="#" OnClick="ir_principal();" class="boton">salir</a>
			</td>
	</tr>

</table>


