<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Retomar Orden en Espera</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero de Orden</td>
		<td class="tdcampos"><input class="campos" type="text" name="proceso" maxlength=128 size=20 value=""   onkeyUp="return ValNumero(this);" onkeypress="return event.keyCode!=13" ></td>
		
		
		<?php
if ($f_admin[id_tipo_admin]==11 || $f_admin[id_tipo_admin]==7)
{
?>

<td class="tdtitulos"  colspan=1 align="left">Tipo Donativo</td>
		<td class="titulos" colspan=1 align="left">
		
		
		<select id="donativo" name="donativo" class="campos" style="width: 200px;"  >
		
		<option value="0" >Sin Donativo</option>
		<option value="1" >Donativo por Responsabilidad Social</option>
		<option value="2" >Donativo VIP</option>
	</select>
	
	<?php
	}
	else
	{
	?>
	<input class="campos" type="hidden" id="donativo" name="donativo" value="0">
		<?php
	}
	?>
	
	<input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="ret_espera2();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
		</tr>
</table>
<div id="retespera"></div>

</form>
