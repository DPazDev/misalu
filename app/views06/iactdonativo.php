<?php

include ("../../lib/jfunciones.php");
sesion();

$proceso=$_REQUEST['donativo'];

$dia=date("d");
$mes=date("m");
$ano=date("Y");
if($mes == '01')
  $mes = "Enero";
elseif($mes == '02')
  $mes = "Febrero";
elseif($mes == '03')
  $mes = "Marzo";
elseif($mes == '04')
  $mes = "Abril";
elseif($mes == '05')
  $mes = "Mayo";
elseif($mes == '06')
  $mes = "Junio";
elseif($mes == '07')
  $mes = "Julio";
elseif($mes == '08')
  $mes = "Agosto";
elseif($mes == '09')
  $mes = "Septiembre";
elseif($mes == '10')
  $mes = "Octubre";
elseif($mes == '11')
  $mes = "Noviembre";
elseif($mes == '12')
  $mes = "Diciembre";
  


/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


//CONSULTA DATOS DEL PROCESO	
$busqueda_pro=("select procesos.comentarios,admin.nombres,admin.apellidos,servicios.servicio,estados_procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.descripcion ,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.monto_aceptado,procesos.comentarios,coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario from coberturas_t_b,gastos_t_b,procesos,servicios,estados_procesos,admin where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso' and procesos.id_estado_proceso=estados_procesos.id_estado_proceso and gastos_t_b.id_servicio=servicios.id_servicio
and admin.id_admin=procesos.id_admin");
$ProcesoGeneral=ejecutar($busqueda_pro);
$RegGe=asignar_a($ProcesoGeneral);

$id_titular=$RegGe['id_titular'];
/* *** CONSULTA  SI ES TITULAR *** */
$titular=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.direccion_hab,estados_clientes.estado_cliente,titulares.id_titular
from clientes,titulares,estados_t_b,estados_clientes
where
titulares.id_titular='$id_titular'
and clientes.id_cliente=titulares.id_cliente 
and titulares.id_titular=estados_t_b.id_titular 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
and estados_t_b.id_beneficiario=0");
$b_titular=ejecutar($titular);
$Regtitular=asignar_a($b_titular);

if($RegGe['id_beneficiario']>0) {
/* *** CONSULTA SI ES BENEFICIARIOS *** */
$id_beneficiario=$RegGe['id_beneficiario'];
$beneficiario=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,estados_clientes.estado_cliente,clientes.cedula,clientes.direccion_hab,beneficiarios.id_beneficiario
from clientes,beneficiarios,estados_t_b,estados_clientes
where beneficiarios.id_beneficiario='$id_beneficiario'
and clientes.id_cliente=beneficiarios.id_cliente 
and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
and estados_t_b.id_beneficiario<>0");
$b_beneficiario=ejecutar($beneficiario);
$r_beneficiario=asignar_a($b_beneficiario);

$datos_beneficiario=true;
}

$monto_g=("select gastos_t_b.id_cobertura_t_b,gastos_t_b.id_gasto_t_b,gastos_t_b.descripcion as descrip,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.enfermedad,gastos_t_b.monto_reserva,gastos_t_b.monto_aceptado,gastos_t_b.factura,procesos.fecha_recibido,procesos.comentarios from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso'");		
$b_monto_g=ejecutar($monto_g);




?>


<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>


<tr>
<td colspan=5 class="titulo3">
  CONTRATO DE DONACIÓN DE SERVICIO
</td>
</tr>

<tr>
<td colspan=5 class="titulo3">
 <br> Reunidos </br>
</td>
</tr>


<?php       
if($datos_beneficiario==true) {?>
 
<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br>Yo <b>Guillermo Guerrero</b>, titular de la  <b>C.I.V-14.255.011</b>, de estado civil soltero, mayor de edad, civilmente hábil, en mi Carácter de presidente de <b>Clinisalud Medicina Prepagada S.A.</b> que el presente contrato se denominara el donante y por la otra parte EL DONATARIO: <b> <?php echo $r_beneficiario['nombres']?>  <?php echo $r_beneficiario['apellidos'] ?> </b> titular de la C.I.V- <b> <?php echo $r_beneficiario['cedula'] ?> </b> con domicilio en <?php echo $r_beneficiario['direccion_hab'] ?>, ambas partes celebran este contrato de donación que se regirá por las siguientes cláusulas:</br>
</td>
</tr>

<?php;}
else{
?>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br>Yo <b>Guillermo Guerrero</b>, titular de la  <b> C.I.V-14.255.011</b>, de estado civil soltero, mayor de edad, civilmente hábil, en mi Carácter de presidente de <b>Clinisalud Medicina Prepagada S.A.</b> que el presente contrato se denominara el donante y por la otra parte EL DONATARIO:<b> <?php echo $Regtitular['nombres']?> <?php echo $Regtitular['apellidos']?> </b> titular de la <b>C.I.V-<?php echo $Regtitular['cedula'] ?></b> con domicilio en <?php echo $Regtitular['direccion_hab'] ?>, ambas partes celebran este contrato de donación que se regirá por las siguientes cláusulas:</br>
</td>
</tr>

<?php;}?>


<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Primera.-</b> Que El Donante es propietario del bien o servicio:</br>
</td>

<?php

   while($r_monto_g=asignar_a($b_monto_g,NULL,PGSQL_ASSOC)){
    $totalmonto=$totalmonto+ $r_monto_g['monto_aceptado'];
    $descri=$r_monto_g[descrip];
?>

<td colspan=4>    
<tr>
        <td colspan=4 class="tdcampos"><?php echo $r_monto_g[nombre] ?>: <?php echo $r_monto_g[descrip]?></td>

</td>
</tr>
<?php;}?>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Segunda.-</b> Que El Donatario ha sido informado que el servicio se encuentra libre de toda carga impositiva y limitación.</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Tercera.-</b> Que los firmantes quieren formalizar a través de este contrato la donación descrita en la cláusula primera.</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Cuarta.-</b> El Donante dona libre y gratuitamente al Donatario el bien o servicio.</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Quinta.-</b> El Donatario acepta el bien de forma expresa y por escrito en este contrato, y agradece al Donante este acto de liberalidad.
</br>
</td>
</tr>


<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Sexta.-</b> La donación reflejada en este documento se perfecciona y comienza a producir su efecto desde la misma fecha de la firma de este contrato.</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Séptima.-</b> El Donatario se hace cargo desde la firma de este contrato de todos los gastos, que procedan luego de recibir la donación.
</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente" style="text-align: justify">
<br><b>Octava.-</b> Ambas partes acuerdan realizar la donación en <?php echo $f_admin['direccion_suc']; ?>
</br>
</td>
</tr>


<tr>
<td colspan=4 class="titulo5" style="text-align: center">
<br>Asi lo decidimos y firmamos por esta vía en la ciudad de Mérida a los  días <?php echo $dia ?> del mes de <?php echo $mes ?> del año <?php echo $ano ?> Firman
</br>
</td>
</tr>


<tr>
<td colspan=1 class="titulo3">
<br><br><br><br>FIRMA DEL DONANTE</br></br></br></br>
</td>

<td colspan=1 class="titulo3">
<br><br><br><br>FIRMA DEL DONATARIO</br></br></br></br>
</td>

</table>