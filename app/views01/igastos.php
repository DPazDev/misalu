<?php
include ("../../lib/jfunciones.php");
sesion();

$servicio=$_REQUEST[id_servicio];
$titular=$_REQUEST[titular];
$id_titular=$_REQUEST[id_titular];
$id_beneficiario=$_REQUEST[id_beneficiario];
$cedula=$_REQUEST[cedula];
$textfechainicio=$_REQUEST[fechainicio];
$textfechafinal=$_REQUEST[fechafinal];

if ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==10 || $servicio==13)
{
	$textfechainicio="2005-01-01";
	}

	$r_cliente=pg_query("select clientes.id_cliente,clientes.apellidos,clientes.nombres,clientes.cedula from clientes where clientes.cedula='$cedula'");
	($f_cliente=pg_fetch_array($r_cliente, NULL, PGSQL_ASSOC));
	?>

<p class="titulo_seccion">Relacion de Gastos desde <?php echo $textfechainicio ?> hasta <?php echo $textfechafinal ?>&nbsp;</p>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
 
  <?php if ($titular=='1'){
	$r_clientet=pg_query("select clientes.id_cliente,clientes.apellidos,clientes.nombres,clientes.cedula,
titulares.id_titular,entes.nombre from clientes,titulares,entes where clientes.cedula='$cedula'
 and clientes.id_cliente=titulares.id_cliente 
and titulares.id_titular='$id_titular' and titulares.id_ente=entes.id_ente");
	($f_clientet=pg_fetch_array($r_clientet, NULL, PGSQL_ASSOC));
?>
 
  <tr>
    <td colspan="5" class="tdcamposc">Nombre y Apellido del Titular: <?php echo $f_clientet[apellidos]?> <?php echo $f_clientet[nombres]?>  Codigo: <?php echo $f_clientet[id_titular]?>  Cedula: <?php echo $f_clientet[cedula]?> Ente: <?php echo $f_clientet[nombre]?> </td>
  </tr>
 <?php } 
 
 else
{
 
	$r_clienteb=pg_query("select clientes.id_cliente,clientes.apellidos,clientes.nombres,clientes.cedula,beneficiarios.id_beneficiario,beneficiarios.id_titular from clientes,beneficiarios,titulares where clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_titular=titulares.id_titular and beneficiarios.id_titular='$id_titular'");
	($f_clienteb=pg_fetch_array($r_clienteb, NULL, PGSQL_ASSOC));
	?>
 <?php 
	$r_clientetitular=pg_query("select clientes.id_cliente,clientes.apellidos,clientes.nombres,clientes.cedula,titulares.id_titular,entes.nombre,entes.id_ente from clientes,titulares,entes,beneficiarios where clientes.id_cliente=titulares.id_cliente and titulares.id_ente=entes.id_ente and beneficiarios.id_titular=titulares.id_titular and beneficiarios.id_beneficiario='$f_clienteb[id_beneficiario]'");
	($f_clientetitular=pg_fetch_array($r_clientetitular, NULL, PGSQL_ASSOC));
	?>
	
 <tr>
    <td colspan="5"  class="tdcamposc">Nombre y Apellido del Titular: <?php echo $f_clientetitular[apellidos] ?> <?php echo $f_clientetitular[nombres]?> Codigo del Titular: <?php echo $f_clientetitular[id_titular] ?> Cedula: <?php echo $f_clientetitular[cedula] ?> Ente: <?php echo $f_clientetitular[nombre] ?> </td>
  </tr>
  <tr>
    <td colspan="5"  class="tdcamposc">Nombre y Apellido del Beneficiario: <?php echo $f_clienteb[apellidos]?>  <?php echo $f_clienteb[nombres]?> Codigo del Beneficiario: <?php echo $f_clienteb[id_beneficiario]?> Cedula: <?php echo $f_clienteb[cedula]?></td>
  </tr>
  
  <?php }?>
  <tr>
    <td colspan="5">-------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
  </tr>
  <tr>
    <td class="tdcamposc">Procesos</td>
    <td class="tdcamposc">Nombre del Servicio</td>
    <td class="tdcamposc">Fecha Recibido </td>
    <td class="tdcamposc">Descripcion del Gasto</td>
    <td class="tdcamposc">Monto Aceptado</td>
  </tr>
  <?php if ($titular=='1'){

		$q_gastos_t_b1=("select admin.nombres,procesos.id_proceso,servicios.servicio,procesos.fecha_recibido,
	gastos_t_b.id_cobertura_t_b,estados_procesos.estado_proceso,procesos.comentarios,count(gastos_t_b.id_proceso) 
from procesos,gastos_t_b,estados_procesos,admin,servicios where procesos.id_titular='$f_clientet[id_titular]' and procesos.id_beneficiario=0 and
 servicios.id_servicio='$servicio' and procesos.fecha_recibido>='$textfechainicio'
 and procesos.fecha_recibido<='$textfechafinal' and gastos_t_b.id_proceso=procesos.id_proceso and
 gastos_t_b.id_servicio=servicios.id_servicio
 and procesos.id_estado_proceso=estados_procesos.id_estado_proceso and procesos.id_admin=admin.id_admin 
group by admin.nombres,procesos.id_proceso,servicios.servicio,procesos.fecha_recibido,estados_procesos.estado_proceso,
gastos_t_b.id_cobertura_T_b,procesos.comentarios order by procesos.fecha_recibido");
		$r_gastos_t_b1=ejecutar($q_gastos_t_b1);



		?>
		<?php 
		while($f_gastos_t_b1=asignar_a($r_gastos_t_b1,NULL,PGSQL_ASSOC))
		{
		?>
  <tr>
   <td class="tdcamposcc"><?php echo $f_gastos_t_b1[id_proceso]?> Estado: <?php echo $f_gastos_t_b1[estado_proceso]?>&nbsp;</td>
    <td class="tdcamposcc"><?php echo $f_gastos_t_b1[servicio]?>&nbsp;</td>
    <td class="tdcamposcc"><?php echo $f_gastos_t_b1[fecha_recibido]?>&nbsp;</td>
    <td colspan="2" class="tdcamposcc">&nbsp;</td>
  </tr>
    
 <?php 
	$q_gastos_t_b=("select * from gastos_t_b where gastos_t_b.id_proceso='$f_gastos_t_b1[id_proceso]' and 
	gastos_t_b.id_cobertura_t_b='$f_gastos_t_b1[id_cobertura_t_b]'");
	$r_gastos_t_b=ejecutar($q_gastos_t_b); 
?>
			<?php 
			 $totalmontres=0;
			 $totalmontpag=0;
			while ($f_gastos_t_b=asignar_a($r_gastos_t_b,NULL,PGSQL_ASSOC))
			{
			
			$totalmontres = $totalmontres + ($f_gastos_t_b[monto_reserva]);
			$totalmontpag =	$totalmontpag + ($f_gastos_t_b[monto_aceptado]);
			$totalmontres1 = $totalmontres1 + ($f_gastos_t_b[monto_reserva]);
			$totalmontpag1 =	$totalmontpag1 + ($f_gastos_t_b[monto_aceptado]);
			?>

<?php 
  $r_proveedor_persona=pg_query("select * from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and  proveedores.id_proveedor='$f_gastos_t_b[id_proveedor]'");
  ($f_proveedor_persona=pg_fetch_array($r_proveedor_persona, NULL, PGSQL_ASSOC));
  ?>

  <?php 
  $r_proveedor_clinica=pg_query("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and  proveedores.id_proveedor='$f_gastos_t_b[id_proveedor]'");
  ($f_proveedor_clinica=pg_fetch_array($r_proveedor_clinica, NULL, PGSQL_ASSOC));
  ?>
  
  <tr>
    <td colspan="3" class="tdcamposcc">&nbsp;</td>
    <td width="399" class="tdcamposcc"><?php echo $f_gastos_t_b[nombre]?>  (<?php echo $f_gastos_t_b[descripcion]?>)  Proveedor:<?php echo$f_proveedor_persona[nombres_prov]?> <?php echo$f_proveedor_persona[apellidos_prov]?> <?php echo$f_proveedor_clinica[nombre] ?>&nbsp;</td>
    <td class="tdcamposcc"><?php echo $f_gastos_t_b[monto_pagado]?></td>
  </tr>
 
		
 
  			<?php 
			}
  		?>
		    <tr>
		      <td colspan="3" class="tdcamposc">&nbsp;</td>
		      <td class="tdcamposc">Total&nbsp;</td>
		      <td class="tdcamposcc"><?php echo formato_montos($totalmontpag)?>&nbsp;</td>
  </tr>
   <tr>
    <td colspan="5" class="tdcamposcc">Analisis Tecnico: <?php echo $f_gastos_t_b1[comentarios] ?> Analista:( <?php echo $f_gastos_t_b1[nombres] ?> )</td>
  </tr>
	     <tr>
              <td colspan="5">--------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
  </tr>

 </tr>

  
		<?php 
		}
  		?>


		
			 <tr>
              <td colspan="3">&nbsp;</td>
              <td class="tdcamposc">Total Final</td>
              <td class="tdcamposcc"><?php echo formato_montos($totalmontpag1)?>&nbsp;</td>
            </tr>
   <?php }

else
{
$r_gastos_t_b1=pg_query("select admin.nombres,procesos.id_proceso,servicios.servicio,procesos.fecha_recibido,
	gastos_t_b.id_cobertura_t_b,estados_procesos.estado_proceso,procesos.comentarios,count(gastos_t_b.id_proceso) 
from procesos,gastos_t_b,estados_procesos,servicios,admin where procesos.id_titular='$f_clientetitular[id_titular]' and procesos.id_beneficiario='$f_clienteb[id_beneficiario]'  and
 servicios.id_servicio='$servicio' and procesos.fecha_recibido>='$textfechainicio'
 and procesos.fecha_recibido<='$textfechafinal' and gastos_t_b.id_proceso=procesos.id_proceso and
 gastos_t_b.id_servicio=servicios.id_servicio
 and procesos.id_estado_proceso=estados_procesos.id_estado_proceso and procesos.id_admin=admin.id_admin 
group by admin.nombres,procesos.id_proceso,servicios.servicio,procesos.fecha_recibido,estados_procesos.estado_proceso,
gastos_t_b.id_cobertura_T_b,procesos.comentarios order by procesos.fecha_recibido")?>
		<?php while($f_gastos_t_b1=pg_fetch_array($r_gastos_t_b1, NULL, PGSQL_ASSOC))
		{
		?>
 
  <tr>
   <td  class="tdcamposcc"><?php echo $f_gastos_t_b1[id_proceso]?>  estado <?php echo $f_gastos_t_b1[estado_proceso]?>&nbsp;</td>
    <td  class="tdcamposcc"><?php echo $f_gastos_t_b1[servicio]?>&nbsp;</td>
    <td  class="tdcamposcc"><?php echo $f_gastos_t_b1[fecha_recibido]?>&nbsp;</td>
    <td colspan="2"  class="tdcamposcc">&nbsp;</td>
  </tr>
  
 <?php $r_gastos_t_b=pg_query("select * from gastos_t_b where gastos_t_b.id_proceso='$f_gastos_t_b1[id_proceso]' and gastos_t_b.id_cobertura_t_b='$f_gastos_t_b1[id_cobertura_t_b]' ")
 ?>
			<?php 
			 $totalmontres=0;
			 $totalmontpag=0;
			while ($f_gastos_t_b=pg_fetch_array($r_gastos_t_b, NULL, PGSQL_ASSOC))
			{
			
			$totalmontres = $totalmontres + ($f_gastos_t_b[monto_reserva]);
			$totalmontpag =	$totalmontpag + ($f_gastos_t_b[monto_aceptado]);
			$totalmontres1 = $totalmontres1 + ($f_gastos_t_b[monto_reserva]);
			$totalmontpag1 =	$totalmontpag1 + ($f_gastos_t_b[monto_aceptado]);
			?>

 <?php 
  $r_proveedor_persona=pg_query("select * from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and  proveedores.id_proveedor='$f_gastos_t_b[id_proveedor]'");
  ($f_proveedor_persona=pg_fetch_array($r_proveedor_persona, NULL, PGSQL_ASSOC));
  ?>
 
   <?php 
  $r_proveedor_clinica=pg_query("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and  proveedores.id_proveedor='$f_gastos_t_b[id_proveedor]'");
  ($f_proveedor_clinica=pg_fetch_array($r_proveedor_clinica, NULL, PGSQL_ASSOC));
  ?>

  <tr>
    <td colspan="3"  class="tdcamposcc">&nbsp;</td>
    <td width="399"  class="tdcamposcc"><?php echo $f_gastos_t_b[nombre]?> (<?php echo $f_gastos_t_b[descripcion]?>)  Proveedor:<?php echo $f_proveedor_persona[nombres_prov]?> <?php echo $f_proveedor_persona[apellidos_prov]?> <?php echo $f_proveedor_clinica[nombre] ?></td>
    <td  class="tdcamposcc"><?php echo $f_gastos_t_b[monto_pagado]?></td>
  </tr>
 
		
 
  			<?php 
			}
	
  		?>
		    <tr>
		      <td colspan="3"  class="tdcamposc">&nbsp;</td>
		      <td  class="tdcamposc">Total&nbsp;</td>
		      <td  class="tdcamposcc"><?php echo formato_montos($totalmontpag)?></td>
  </tr>
  
 
  
  </tr>
   <tr>
    <td colspan="5"  class="tdcamposcc">Analisis Tecnico: <?php echo $f_gastos_t_b1[comentarios] ?>  Analista:( <?php echo $f_gastos_t_b1[nombres] ?>)</td>
  </tr>
	     <tr>
              <td colspan="5">--------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
  </tr>

	<?php 
		}

  		?>






    <td colspan="3">&nbsp;</td>
              <td  class="tdcamposc">Total Final</td>
              <td  class="tdcamposcc"><?php echo formato_montos($totalmontpag1)?></td>
  </tr>
			
   <?php
	 
		
			
  //fin del ultimo de lo contrario
}
?>
</table><?php
echo pie();
?>
