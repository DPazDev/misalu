<?php
include ("../../lib/jfunciones.php");
sesion();
$formp1=$_REQUEST['formp1'];
$monto=$_REQUEST['monto'];
$tiposerv=$_REQUEST['tiposerv'];
$servicio=$_REQUEST['servicio'];
$tp=$_REQUEST['tp'];
if ($monto==0)
{
	echo "El Monto no puede ser cero (0)";
	}
	else
	{
$q_cobertura="select * from entes,titulares,coberturas_t_b where entes.id_ente=titulares.id_ente and titulares.id_titular=coberturas_t_b.id_titular and coberturas_t_b.id_cobertura_t_b='$formp1'";
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);

if ($monto>$f_cobertura[monto_actual])
{
	?>
	<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4 >
El Monto a Cargar Sobrepasa La Cobertura 
             </td> 
</tr>
</table>         
	<?php
	
	}
else
{


?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<?php
if (($servicio==1) || ($servicio==14 and $tp==18))
{

	?>
<tr>
	<td class="tdtitulos"><input class="campos" type="hidden" name="proveedor" maxlength=128 size=20 value="0"   >
	<input class="campos" type="hidden" name="numpre" maxlength=128 size=20 value="0"   >
	</td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardarra('clientes');" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>
<?php 
}
else
{
	
	
	if (($servicio==6 && $tiposerv==9 ) || ($servicio==9 && $tiposerv==13) || ($servicio==6 && $tiposerv==20 ) || ($servicio==6 && $tiposerv==25 ) || ($servicio==9 && $tiposerv==21)){
	?>

<tr>
		<td class="tdtitulos"><input class="campos" type="hidden" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td class="tdtitulos"></td>		
		<td  class="tdtitulos"><a href="#" OnClick="guardareme('clientes');" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>

<?php
	}
	else
	{
	
	
	
	
	
if ($tiposerv==6  || $tiposerv==8 || $tiposerv==12){
	?>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroa('clientes');" class="boton">Guardar</a></td>
<?php }
else 
{
	
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) ){
		
	?>
	<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroac('clientes');" class="boton">Guardar </a></td>
	<?php }
	else
	{
		?>
	<input class="campos" type="hidden" name="numpro" maxlength=128 size=20 value="0"   >
	<input class="campos" type="hidden" name="numpre" maxlength=128 size=20 value="0"   >
	<input class="campos" type="hidden" id="honorarios_100"  name="montoo" maxlength=128 size=20 value="0"   >
	<input class="campos" type="hidden" id="honorarios_200" name="montog" maxlength=128 size=20 value="0"   >
	<input class="campos" type="hidden" id="honorarios_300" name="montoh" 
					maxlength=128 size=20 value="0"   >
	<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroac('clientes');" class="boton">Guardar</a></td>
	
	<?php
	}
	}
	?>
</tr>
</table>
<?php
}
}
}
}
?>

