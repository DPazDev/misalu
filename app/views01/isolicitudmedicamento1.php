<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_date=("select current_date;");
$r_date=ejecutar($q_date);
$f_date=asignar_a($r_date);

$q_hora=("select current_time;");
$r_hora=ejecutar($q_hora);
$f_hora=asignar_a($r_hora);

$nu_planilla=$_REQUEST['nu_planilla'];
$nombret=$_REQUEST['nombret'];
$cedulat=$_REQUEST['cedulat'];
$nombreb=$_REQUEST['nombreb'];
$cedulab=$_REQUEST['cedulab'];
$ente=$_REQUEST['ente'];
$fechaimpreso=date("d-m-Y");
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">

</tr>

<tr>
<td colspan=1 class="titulo2">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>



<tr>
<td colspan=4 class="titulo3"> PLANILLA DE SOLICTUD DE MEDICAMENTOS O SUMINISTROS 

</td>
</tr>
<tr>
<td colspan=4>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td class="datos_cliente">Con Factura Num </td>
<td class="datos_cliente"><input type="text"></input></td>
<td align="right"  class="datos_cliente"></td>
<td align="right"  class="datos_cliente">Numero Planilla: <?php echo $nu_planilla?></td>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> Datos del Paciente

</td>
</tr>
<tr>
<td class="datos_cliente">Paciente</td>
<td class="datos_cliente"><?php if ($nombreb=="")
{
	echo "$nombret";
	}
	else
	{
		echo "$nombreb";
		}
?></td>
<td class="datos_cliente">Cedula</td>
<td class="datos_cliente"><?php if ($nombreb=="")
{
	echo "$cedulat";
	}
	else
	{
		echo "$cedulab";
		}
?></td>
</tr>

<td colspan=1 class="datos_cliente">Ente</td>
<td colspan=3 class="datos_cliente"><?php 
	echo "$ente " ?>
</td>

</td>
</tr>
</tr>
<td class="datos_cliente">Titular</td>
<td class="datos_cliente"><?php 
	echo $nombret ?>
</td>
<td class="datos_cliente">Beneficiario </td>
<td class="datos_cliente"><?php echo $nombreb?></td>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> Datos del Enfermero

</td>
</tr>
<tr>
<td class="datos_cliente">Enfermero(a) </td>
<td class="datos_cliente"><input type="text"> </input></td>
<td class="datos_cliente">Turno </td>
<td class="datos_cliente"><select id="turno" name="turno" class="campos" style="width: 100px;"  >
		
		<option value="0" >Ma&ntilde;ana</option>
		<option value="1" >Tarde</option>
		<option value="2" >Nocturno</option>
	</select></td>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> Motivo

</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente">Emergencia<input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente">Hospitalizacion <input type="checkbox"></input>
</td>
<td colspan=1 class="datos_cliente">Procedimiento <input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=2 class="datos_cliente">Pos Operatorio <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente">Respon. Social <input type="checkbox"></input>
</td>
<td colspan=1 class="datos_cliente">Donativo<input type="checkbox"></input></td>

</tr>
<tr>
<td colspan=1 class="datos_cliente">Autorizado Por</td>
<td colspan=3  class="datos_cliente"><input maxlength=128 size=60 type="text"></input></td>

</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
</table>
<table border="1" class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo3"> Medicamentos y Soluciones
		</td>
	</tr>
	<tr>
		<td class="datos_cliente">Cantidad </td>
		<td class="datos_cliente">Nombre del Medicamento </input>
		</td>
		<td class="datos_cliente">Cantidad</td>
		<td class="datos_cliente">Nombre del Medicamento </td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	</table>
	
	<table border="1" class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo3"> Material Fungible
		</td>
	</tr>
	<tr>
		<td class="datos_cliente">Cantidad </td>
		<td class="datos_cliente">Nombre del Material </input>
		</td>
		<td class="datos_cliente">Cantidad</td>
		<td class="datos_cliente">Nombre del Material </td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	<tr>
		<td class="datos_cliente">. </td>
		<td class="datos_cliente">.
		</td>
		<td class="datos_cliente">.</td>
		<td class="datos_cliente">.</td>
	</tr>
	</table>
<table border="1" class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="datos_cliente"> Observaciones y Otros Procedimientos:
		</td>
	</tr>
	<tr>
		<td colspan=4 class="datos_cliente">.
		</td>
	</tr>
	<tr>
		<td colspan=4 class="datos_cliente">.
		</td>
	</tr>
	<tr>
		<td colspan=4 class="datos_cliente">.
		</td>
	</tr>
	<tr>
		<td colspan=4 class="datos_cliente">.
		</td>
	</tr>
	</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
		<td colspan=4 class="titulo3"> 
		<br>
		</br>
		</td>
	</tr>
<tr>
<td class="titulo3">_____________
</td>
<td class="titulo3">_____________
</td>
<td class="titulo3">_____________
</td>
<td class="titulo3"><?php echo "Impreso el $f_date[date] por $f_admin[nombres] $f_admin[apellidos] a las  $f_hora[timetz]";  ?>
</td>
</tr>
<tr>
<td class="titulo3">Firma y Sello del Medico
</td>
<td class="titulo3">Firma Enfermero
</td>
<td class="titulo3">Firma Farmaceuta
</td>
<td class="titulo3">Operador
</td>
</tr>

</table>


