<?php

/* Nombre del Archivo: mod_servicio.php
   Descripción: Formulario para solicitar los datos para MODIFICAR el servicio en una orden especifica en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();
?>


<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="guardar_servicio.php" method="post" id="servicio" name="servicio">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Modificar Servicio en Orden</td>
	</tr>	
<tr><td>&nbsp;</td></tr>
	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * N&uacute;mero de Orden</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="orden" name="orden" maxlength=150 size=25 value=""></td>




		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_servicio1();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
</table>
<div id="mod_servicio1"></div>
</from>
