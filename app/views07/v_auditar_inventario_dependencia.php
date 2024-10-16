<?php
include ("../../lib/jfunciones.php");
sesion();

$r_tipo_insumo=pg_query("select * from tbl_tipos_insumos  order by tipo_insumo");
$r_dependencias=pg_query("select * from tbl_dependencias  order by dependencia");
?>

<style type="text/css">
div.menuf {margin:0; text-align:center; position:fixed; top:20px; left:20px; width:10.5em; z-index:5; color:#000000; background-color:#FED}
div.menuf a {display:block; padding:1px; font-weight:bold; margin:1px; text-decoration:none; color:#ff0000; background-color:#86a8b8}
div.menuf a:hover {color:#ff0000; background-color:#f08976}
div.menuf p.menuf {margin:0; padding:0.1em 0.2em; border:thin outset #999; color:#000; background-color:#FED; font-family:Tahoma, Arial}
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
.Estilo2 {color: #FFFFFF}
</style>

<script language="JavaScript">
<!--
function enviar(){
		
		document.inventario_dependencia.submit();
}

-->
</script>

<form action="v_auditar_inventario_dependencia2.php" method="POST" name="inventario_dependencia" target="_blank">
<table <?php echo propiedades_tabla; ?>>
	<tr>		<td colspan=2 align="center" class="titulo_seccion">Modulo para Auditar Inventario de las Dependencias</td>	</tr>
	
	<tr>
	<td colspan=2><div align="center"><span class="titulos"></span></div></td>
	</tr>

	<tr>
	  <td align="right" colspan=2><span class="titulos"></span></td>
  </tr>
	<tr>
	  <td align="right" colspan=2><div align="center"><span class="titulos"></span></div></td>
  </tr>
	<tr>
	<td width="180" align="right" colspan=2>
	
      <div align="left" class="titulos"><strong>        </strong></div></td>
</tr>
<tr>
	<td width="180" align="right" colspan=2 class="titulos">
	  <div align="left" >   Seleccione la Dependencia
	    <select name="dependencia" class="campos">
	<option></option>
	<?php
	while($f_dependencias=pg_fetch_array($r_dependencias, NULL, PGSQL_ASSOC))
		echo "<option value=\"$f_dependencias[id_dependencia]\">$f_dependencias[dependencia] </option>";
	?>
          </select>
      </div></td>
	</tr>
	<tr>
	<td width="180" align="right" colspan=2 class="titulos">
	  <div align="left" >   Seleccione el Tipo de Insumo
	    <select name="tipo_insumo" class="campos">
	<option value="0@Todos los Insumos">Todas los Insumos</option>
	<?php
	while($f_tipo_insumo=pg_fetch_array($r_tipo_insumo, NULL, PGSQL_ASSOC))
		echo "<option value=\"$f_tipo_insumo[id_tipo_insumo]\">$f_tipo_insumo[tipo_insumo] </option>";
	?>
          </select>
      </div></td>
	</tr>
	
	
	<tr>
			<td align="left"></td>
		<td align="right"><a href="javascript: enviar();">enviar</a></td>
	</tr>	
</table>
</form>
