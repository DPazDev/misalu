<?php
header("Content-Type: text/html;charset=utf-8");
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


<tr>		<td colspan=4 class="titulo_seccion">Registrar Orden </td>	</tr>	
	<tr>
		<td class="tdtitulos">* C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=128 size=20 value=""    onkeypress="return event.keyCode!=13"></td>
<?php
if ($f_admin[id_tipo_admin]==11 || $f_admin[id_tipo_admin]==7)
{
?>

<td class="tdtitulos"  colspan=1 align="left">Tipo Donativo</td>
		<td class="titulos" colspan=1 align="left">
		
		
		<select id="donativo" name="donativo" class="campos" style="width: 200px;"  >
		
		<option value="0" >Sin Donativo</option>
		<option value="1" >Donativo por Responsabilidad Social</option>
        <option value="3" >Donativo por CiniSalud</option>
        <option value="2" >Por Exceso de Cobertura</option>
        
	</select>
		</td>
	<?php
	}
	else
	{
	?>
	<input class="campos" type="hidden" id="donativo" name="donativo" value="0">
		<?php
	}
	?>
</tr>
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Cliente.</td>
<td  class="tdcampos">
		<select name="tipo_cliente" class="campos" OnChange="tipos_clientes();">
		<option value="*"> Seleccione el Tipo de Cliente</option>
		<option value="0"> Afiliado</option>
		<option value="1"> Particular</option>
		
		</select>
		</td>
		<td  colspan=2 class="tdtitulos">	</td>
		</tr>

</table>
	<div id="tipo_cliente"></div>

</form>
