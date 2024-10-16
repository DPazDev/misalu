<?php

/* Nombre del Archivo: r_seguir_proceso.php
   Descripción: Formulario para solicitar los datos para realizar reporte del seguimiento de un proceso o una factura (la cantidad de veces que se ha actualizado un proceso o factura) 
*/

include ("../../lib/jfunciones.php");
sesion();


$q_usuario=("select admin.nombres, admin.apellidos, admin.id_admin from admin order by admin.nombres");
$r_usuario=ejecutar($q_usuario);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="seguir_proceso.php" method="post" id="usuario" name="usuario">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">  Seguir Proceso</td>
	</tr>	



<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * N&uacute;mero de Proceso</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="orden" name="orden" maxlength=150 size=25 value=""></td>

	</tr>

<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="ordenes();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * N&uacute;mero de Factura</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="factura" name="factura" maxlength=150 size=25 value=""></td>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Serie</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="serie" name="serie" maxlength=150 size=25 value=""></td>
	</tr>

<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="ordenes();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
</table>
<div id="ver_proceso"></div>
</from>
