<?php
/* Nombre del Archivo: reg_telefono.php
   Descripción: Formulario para solicitar los datos para INSERTAR o MODIFICAR un NUMERO TELEFONICO en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="mod_telefono.php" method="post" id="tel" name="tel">
<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo_seccion">Registrar o Modificar Números Telefónicos</td>
	</tr>	
<tr><td>&nbsp;</td></tr>
	<tr>
                <td colspan=1 class="tdtitulos">* Verificar N&uacute;mero de C&eacute;dula</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" name="ci" maxlength=150 size=25 value=""></td>
	


		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_telefono();" class="boton">Modificar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
</table>
<div id="mod_telefono"></div>
</from>
