<?php
/* Nombre del Archivo: reg_depart.php
   Descripción: Solicita los datos para REGISTRAR o MODIFICAR un DEPARTAMENTO en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$q_tipo_comi = "select tipo_comisionado.* from tipo_comisionado order by tipo_comisionado.tipo_comisionado";
$r_tipo_comi = ejecutar($q_tipo_comi);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="guardar_tipo_comi.php" method="post" name="comisionado">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>	<td colspan=4 class="titulo_seccion">Registrar o Modificar Tipo de Vendedor</td>	</tr>	
	<tr><td>&nbsp;</td></tr>
	<tr>

	<tr>
		 <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Verificar Tipo de Vendedor </td>
	<td colspan=2 class="tdcamposcc" ><select id="tipo" name="tipo" class="campos" style="width: 200px;" >
		<option value="0">-- Seleccione un Tipo     --</option>
		<?php
		while($f_tipo_comi = asignar_a($r_tipo_comi,NULL,PGSQL_ASSOC)){?>
			<option value="<?php echo $f_tipo_comi[id_tipo_comisionado] ?>"> <?php echo "$f_tipo_comi[tipo_comisionado]"?></option>
				     <?php }?> 
		

		</td>

		<td colspan=1 class="tdcampos"><a href="#" OnClick="mod_tipo_vendedor();" class="boton">Modificar</a></td>
		
        </tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Tipo de  Vendedor</td>

		<td colspan=2 class="tdcampos"><input class="campos" type="text" name="tipo1" maxlength=128 size=20 value=""></td>
	</tr>	
	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_tipo_vendedor();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

		</tr>
	<tr><td>&nbsp;</td></tr>
</table>




</form>
