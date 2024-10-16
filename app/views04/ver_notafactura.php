<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=utf-8');
///RECEBIR variables de otros modulos
if(isset($_POST['numnota']))
{ $NumNota= $_POST['numnota'];
	$TipoNota= $_POST['tiponota'];
	if($TipoNota==1){
		$notCredSelec="selected='selected'";
		$notDebiSelec="";

	}
	else{
		$notCredSelec="";
		$notDebiSelec="selected='selected'";
	}
}
else{
		$NumNota="";
		$notCredSelec="";
		$notDebiSelec="";
}


?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="cita">
	<table class="tabla_cabecera3"  border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td colspan=3 class="titulo_seccion">Ver o Modificar Notas de Factura</td>
		</tr>
		<tr>
			<td class="tdtitulos">* Tipo de Nota</td>
			<td class="tdcampos">
				<select id="TipoNota" name="TipoNota" class="campos" style="width: 200px;"  >
					<option value="1" <?php echo $notCredSelec;?> >Nota de Crédito</option>
					<option value="2" <?php echo $notDebiSelec;?> >Nota de Débito</option>
				</select>
			</td>
			<td class="tdcampos" rowspan=3>
				<fieldset id='listarNotas' dir='ltr'>
					<legend class="titulo_seccion">Lista Todas las Notas</legend>
					<a href="#" OnClick="listanotafactura();" class="boton">lista de notas</a>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td class="tdtitulos">* Numero de Nota de Credito</td>
			<td class="tdcampos"><input class="campos" type="text" id="NunNota" name="nunNota" maxlength=128 size=20 value="<?php echo $NumNota;?>"     onkeypress="return event.keyCode!=13"></td>
		</tr>
		<tr>
			<td class="tdcampos"></td>
			<td class="tdtitulos"></br></br>
				<input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value="">
				<a href="#" OnClick="buscarnotafactura();" class="boton">Buscar</a>
				<a href="#" OnClick="ir_principal();" class="boton">Salir</a>
			</td>
		</tr>

	</table>
<div id="buscarnota"></div>

</form>
