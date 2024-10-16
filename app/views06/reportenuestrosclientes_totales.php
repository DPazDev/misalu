<?php
include ("../../lib/jfunciones.php");
sesion();
list($tipo_ente,$nom_tipo_ente)=explode("@",$_POST['tipo_ente']);
list($ente,$nom_ente)=explode("@",$_POST['ente']);

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}
	
		if  ($ente==0){
	$entes="entes.id_ente>0";
	}
	else
	{
		$entes="entes.id_ente=$ente";
	}

	$q_ente=("select entes.nombre, entes.id_ente from entes where $entes $tipo_entes order by	 entes.nombre");
	$r_entes=ejecutar($q_ente);
 
?>
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
	<tr>
		<td  colspan="7"  class="titulo_seccion" >TOTAL TITULARES POR ENTES </td>
	</tr>

	<tr>
		<td class="tdcamposc">Ente</td>
		<td class="tdcamposc">Total Titulares Activos</td>
		<td class="tdcamposc">Total Titulares Excluidos</td>
		<td class="tdcamposc">Total Beneficiarios Activos</td>
		<td class="tdcamposc">Total Beneficiarios En Lapso de Espera</td>
		<td class="tdcamposc">Total Beneficiarios Excluidos</td>
	</tr>
	<?php
	$total_col_1=0;
	$total_col_2=0;
	$total_col_3=0;
	$total_col_4=0;
	$total_col_5=0;
   
	while($f_ente=asignar_a($r_entes)){

	?>
	<tr>
		<td class="tdcamposcc"><?php echo $f_ente['nombre']; ?></td>
	<?php
		$q_titular=("
		select 
		count(titulares.id_titular) as total_titulares
		from 
		clientes, 
		estados_clientes, 
		estados_t_b, 
		titulares 
		where 
		clientes.id_cliente=titulares.id_cliente and 
		titulares.id_ente=$f_ente[id_ente] and 
		titulares.id_titular=estados_t_b.id_titular and 
		estados_t_b.id_beneficiario='0' and 
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
		estados_t_b.id_estado_cliente=4;");
		$r_titular=ejecutar($q_titular);
		$f_titular=asignar_a($r_titular);
		$total_col_1+=$f_titular['total_titulares'];
	?>
		<td class="tdcamposcc"><?php echo $f_titular['total_titulares']; ?></td>

	<?php
		$q_titular2=("
		select 
		count(titulares.id_titular) as total_titulares
		from 
		clientes, 
		estados_clientes, 
		estados_t_b, 
		titulares 
		where 
		clientes.id_cliente=titulares.id_cliente and 
		titulares.id_ente=$f_ente[id_ente] and 
		titulares.id_titular=estados_t_b.id_titular and 
		estados_t_b.id_beneficiario='0' and 
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
		estados_t_b.id_estado_cliente=5;");
		$r_titular2=ejecutar($q_titular2);
		$f_titular2=asignar_a($r_titular2);
		$total_col_2+=$f_titular2['total_titulares'];
	?>
		<td class="tdcamposcc"> <?php echo $f_titular2['total_titulares']; ?></td>
	<?php
		$q_beneficiario=("
		select 
		beneficiarios.id_beneficiario
		from clientes, estados_clientes, beneficiarios, estados_t_b, parentesco , titulares
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_ente=$f_ente[id_ente] and
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
		estados_t_b.id_estado_cliente=4 and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
		parentesco.id_parentesco=beneficiarios.id_parentesco");
		$r_beneficiario=ejecutar($q_beneficiario);
		$no_beneficiarios=num_filas($r_beneficiario);
		$total_col_3+=$no_beneficiarios;
	?>
		<td class="tdcamposcc"><?php echo $no_beneficiarios; ?></td>

	<?php 
		$q_beneficiario2=("
		select 
		beneficiarios.id_beneficiario
		from clientes, estados_clientes, beneficiarios, estados_t_b, parentesco, titulares 
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_ente=$f_ente[id_ente] and
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
		estados_t_b.id_estado_cliente=1 and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
		parentesco.id_parentesco=beneficiarios.id_parentesco");
		$r_beneficiario2=ejecutar($q_beneficiario2);
		$no_beneficiarios2=num_filas($r_beneficiario2);
		$total_col_4+=$no_beneficiarios2;
	?>
		<td class="tdcamposcc"><?php echo $no_beneficiarios2; ?></td>

	<?php 
		$q_beneficiario3=("
		select 
		beneficiarios.id_beneficiario
		from clientes, estados_clientes, beneficiarios, estados_t_b, parentesco, titulares 
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_ente=$f_ente[id_ente] and
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
		estados_t_b.id_estado_cliente=5 and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
		parentesco.id_parentesco=beneficiarios.id_parentesco");
		$r_beneficiario3=ejecutar($q_beneficiario3);
		$no_beneficiarios3=num_filas($r_beneficiario3);
		$total_col_5+=$no_beneficiarios3;
	?>
		<td class="tdcamposcc"><?php echo $no_beneficiarios3; ?></td>
	</tr>
<?php
	}	
?>
	<tr>
		<td class="tdcamposc">Totales </strong></td>
		<td class="tdcamposc"><?php echo $total_col_1; ?></td>
		<td class="tdcamposc"><?php echo $total_col_2; ?></td>
		<td class="tdcamposc"> <?php echo $total_col_3; ?></td>
		<td class="tdcamposc"><?php echo $total_col_4; ?></td>
		<td class="tdcamposc"><?php echo $total_col_5; ?></td>
	</tr>
</table>

