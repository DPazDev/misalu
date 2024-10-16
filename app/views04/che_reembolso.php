<?php
include ("../../lib/jfunciones.php");
sesion();
$q_ente=("select * from entes  order by entes.nombre");
$r_ente=ejecutar($q_ente);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Cheques de Reembolso</td>	</tr>	
	<tr>
		<td class="tdtitulos">* C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" id="cedula" name="cedula" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos">Seleccione el Ente</td>
		<td class="tdcampos"><select id="ente" name="ente" class="campos" style="width: 200px;"  >
                <?php
while($f_ente=asignar_a($r_ente,NULL,PGSQL_ASSOC)){
			?>
			<option value="<?php echo $f_ente[id_ente]?>"><?php echo "$f_ente[nombre]"?></option>
<?php 
}
?>
</select><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="bus_che_reem();" class="boton" title="Buscar Reembolsos">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="bus_che_reem"></div>

</form>
