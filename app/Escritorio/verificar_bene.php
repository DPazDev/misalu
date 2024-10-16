<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$cedula=$_POST['cedula'];
$id_titular=$_POST['id_titular'];
	

/* **** busco el id_cliente registrado **** */
$q_clienteb=("select clientes.nombres,clientes.apellidos,clientes.id_cliente
		from
		clientes
		where 
		clientes.cedula='$cedula'");
$r_clienteb=ejecutar($q_clienteb);
$num_filas1=num_filas($r_clienteb);

	if ($num_filas1>=1) 
	{


$f_clienteb=asignar_a($r_clienteb);


$q_beneficiario=("select * from beneficiarios,clientes 
		where 
		beneficiarios.id_cliente=clientes.id_cliente and 
		clientes.id_cliente=$f_clienteb[id_cliente] and
		beneficiarios.id_titular=$id_titular
		");
$r_beneficiario=ejecutar($q_beneficiario);
$num_filas2=num_filas($r_beneficiario);



echo "CEDULA REGISTRADA A"; 
$url3="views01/bsc_cliente.php?cedulaclien=$cedula";
?>
						<input class="campos" type="hidden" id="id_cliente" name="id_cliente" maxlength=128 size=20 value="<?php echo $f_clienteb[id_cliente] ?>">
						

		<input class="campos" type="hidden"  id="nombreb" name="nombreb" maxlength=128 size=20 value="<?php echo $f_clienteb[nombres]?>"><?php echo $f_clienteb[nombres]?>
		
		<input class="campos" type="hidden" id="apellidob" name="apellidob" maxlength=128 size=20 value="<?php echo $f_clienteb[apellidos]?>"><?php echo $f_clienteb[apellidos]?>
<a href="<?php echo $url3; ?>" title="Ver Información del Cliente" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;">Ver Data</a>
<?php 
if ($num_filas2==0) {
?>
<a  OnClick="reg_orden_part3(2);"  id="eti_boton" title="Registrar a Este Titular como Beneficiario Particular"  class="boton">Registrar</a></td>

<?php
}
}
else
{
?>
<input class="campos" type="hidden" id="id_cliente" name="id_cliente" maxlength=128 size=20 value="0"> Nombre
<input class="campos" type="text"  id="nombreb" name="nombreb" maxlength=128 size=20 value="<?php echo $f_clienteb[nombres]?>"> Apellido
		
		<input class="campos" type="text" id="apellidob" name="apellidob" maxlength=128 size=20 value="<?php echo $f_clienteb[apellidos]?>">
</a>

<a  OnClick="reg_orden_part3(2);"  id="eti_boton" title="Registrar a Este Titular como Beneficiario Particular"  class="boton">Registrar</a></td>
<?php
}
?>
