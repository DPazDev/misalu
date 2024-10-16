<?php
/* Nombre del Archivo: mod_tipo_vendedor.php
   Descripción: Solicita los datos para MODIFICAR un TIPO DE VENDEDOR en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$id_tipo_comi = $_REQUEST['tipo'];


/*echo "///////";
echo $id_tipo_comi;
echo "-------";*/

$q_tipo_comi = "select tipo_comisionado.* from tipo_comisionado where tipo_comisionado.id_tipo_comisionado='$id_tipo_comi';";
$r_tipo_comi = ejecutar($q_tipo_comi);
$f_tipo_comi = asignar_a($r_tipo_comi);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="guardar_tipo_comi.php" method="post" name="comisionado">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>	<td colspan=4 class="titulo_seccion">Modificar Tipo de Vendedor</td>	</tr>	
	<tr><td>&nbsp;</td></tr>
	<tr>
                <td colspan=1 class="tdtitulos">* Nuevo Tipo de Vendedor </td>
      		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="tipo" maxlength=128 size=20 value="<?php echo $f_tipo_comi['tipo_comisionado'];?>"></td>
		<input type="hidden" name="id_tipo2" value="<?php echo $id_tipo_comi;?>">

		<td colspan=1 class="tdcampos"><a href="#" OnClick="guardar_tipo_vendedor1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
        </tr>

	<tr><td>&nbsp;</td></tr>

	
</table>





</form>
