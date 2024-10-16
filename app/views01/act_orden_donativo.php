<?php
include ("../../lib/jfunciones.php");

$proceso=$_POST['proceso'];
$donativo=strtoupper($_POST['donativo']);
if ($donativo==1){
    $tipo_donativo="Donativo por Responsabilidad Social";
    }
    else
    {
    $tipo_donativo="Donativo por CiniSalud";    
        }

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** busco el usuario admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* **** **** */
/* **** Actualizo la tabla procesos los procesos a candidato a pago **** */
$mod_pro="update procesos set donativo=$donativo  where procesos.id_proceso='$proceso'";
$fmod_pro=ejecutar($mod_pro);


  
/* **** Se registra lo que hizo el usuario**** */
$log="Se Actualiza el donativo proceso $proceso a $tipo_donativo el dia $fecha";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Modificado con Exito el tipo de Donativo</td>	</tr>
<tr>
		<td class="tdtitulos">
			
<a href="#" OnClick="ir_principal();" class="boton">salir</a>
			</td>
	</tr>

</table>


