<?php
include ("../../lib/jfunciones.php");
sesion();

$cedula=$_REQUEST['cedula'];
$ente=$_REQUEST['ente'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* busco el titular y su estado */
$q_titular=("select entes.nombre,estados_clientes.estado_cliente,clientes.nombres,clientes.apellidos,clientes.comentarios,clientes.cedula,titulares.id_titular from estados_clientes,clientes,entes,titulares,estados_t_b where clientes.cedula='$cedula' and clientes.id_cliente=titulares.id_cliente and titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario=0 and titulares.id_ente=entes.id_ente and entes.id_ente=$ente
");
$r_titular=ejecutar($q_titular);
$num_filas=num_filas($r_titular);
if ($num_filas==0){?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Este Cliente no Existe o no Esta Registrado con el Ente Seleccionado</td>	</tr>
</table>
<?php
	
	}
	else
	{
$f_titular=asignar_a($r_titular);

/* **** busco las procesos de servicio reembolso *** */
$q_morbilidad=("select gastos_t_b.id_servicio,procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,procesos.id_estado_proceso,procesos.fecha_recibido,clientes.nombres,clientes.apellidos,clientes.cedula,count(gastos_t_b.id_proceso) from procesos,clientes,gastos_t_b,titulares where procesos.id_proceso=gastos_t_b.id_proceso and (gastos_t_b.id_servicio=1 or gastos_t_b.id_servicio=10) and procesos.id_estado_proceso=7 and procesos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and clientes.cedula='$cedula' group by gastos_t_b.id_servicio,procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,procesos.id_estado_proceso,procesos.fecha_recibido,clientes.nombres,clientes.apellidos,clientes.cedula
");
$r_morbilidad=ejecutar($q_morbilidad);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
		<td class="tdtitulos">Titular</td>
		<td  colspan=2 class="tdcampos"><?php echo "$f_titular[nombres] $f_titular[apellidos]"?></td>
		<td class="tdtitulos">Estado</td>
		<td class="tdcamposr"><?php echo "$f_titular[estado_cliente]"?></td>
		
		
</tr>
<tr>
		<td class="tdtitulos">Ente</td>
		<td  colspan=4 class="tdcampos"><?php echo "$f_titular[nombre]"?></td>
		<td class="tdcampos"></td>
		<td class="tdcampos"></td>
</tr>
<tr>
		<td class="tdtitulos">Comentarios</td>
		<td  colspan=6 class="tdcampos"><?php echo "$f_titular[comentarios]"?></td>
		
</tr>
<tr>		<td colspan=7 class="titulo_seccion">Relacion de  Reembolsos</td>	</tr>	
<tr>
		<td class="tdcamposc">Orden</td>
		<td class="tdcamposc">Fecha Emision</td>
		<td class="tdcamposc">Titular</td>
		<td class="tdcamposc">Beneficiario</td>
		<td class="tdcamposc">Cedula</td>
		<td class="tdcamposc">Monto</td>
		<td class="tdcamposc">Seleccion</td>
		
</tr>
	<?php		
	$i=0;
		while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){
					$i++;
			$q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
$r_benerficiario=ejecutar($q_benerficiario);
$f_benerficiario=asignar_a($r_benerficiario);

$q_ordenes=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_morbilidad[id_proceso]");
$r_ordenes=ejecutar($q_ordenes);
$monto=0;
while($f_ordenes=asignar_a($r_ordenes,NULL,PGSQL_ASSOC)){
	$monto=$monto + $f_ordenes[monto_aceptado];
	}
?>
		
		<tr>
		<td class="tdcamposcc"><?php echo $f_morbilidad[id_proceso]?>
		<input class="campos" type="hidden" id="proceso_<?php echo $i?>"  name="proceso" maxlength=128 size=10 value="<?php echo $f_morbilidad[id_proceso]?>" >
		<input class="campos" type="hidden" id="servicio_<?php echo $i?>"  name="servicio" maxlength=128 size=10 value="<?php echo $f_morbilidad[id_servicio]?>" >
		<input class="campos" type="hidden" id="factura_<?php echo $i?>"  name="factura" maxlength=128 size=10 value="<?php echo $f_morbilidad[id_servicio]?>" >
		</td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[fecha_recibido]?></td>
		<td class="tdcamposcc"><?php echo "$f_morbilidad[nombres] $f_morbilidad[apellidos]"?></td>
		<td class="tdcamposcc"><?php echo "$f_benerficiario[nombres] $f_benerficiario[apellidos]"?></td>
		<td class="tdcamposcc"><?php echo $f_benerficiario[cedula]?></td>
		
		<td class="tdcamposcc"><?php echo $monto?>
		<input class="campos" type="hidden" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="<?php echo $monto?>"   >
		</td>
		<td class="tdcamposcc"><input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value=""> </td>
	</tr>
		<?php
		}
		echo "<input type=\"hidden\" id=\"conexa\"name=\"conexa\" value=\"$i\">";
		?>
	
	
	<tr>
		<td class="tdcamposc"></td>
		<td class="tdcamposc"></td>
		<td class="tdcamposc"></td>
		<td class="tdcamposc"></td>
		<td  class="tdtitulos">Total Monto</td>
		<td class="tdcamposc"><input class="campos" type="text" id="monto" size=4 name="monto" value=""></td>
			<td  class="tdtitulos"><a href="javascript: sumar(this);" class="boton">      Cal</a></td>
	</tr>
	<tr>		<td colspan=7 class="titulo_seccion">Datos para Realizar el Cheque</td>	</tr>	
<tr>
                <td class="tdcamposc">A Nombre</td>
                <td class="tdcamposc">
		<select id="nombre" name="nombre" class="campos" style="width: 100px;"  >
                <option value="<?php echo $f_titular[cedula]?>"> <?php echo "$f_titular[nombres] $f_titular[apellidos]"?></option>
            <?php $q_benerficiario1=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento from beneficiarios,clientes,estados_t_b where beneficiarios.id_titular=$f_titular[id_titular] and beneficiarios.id_cliente=clientes.id_cliente and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and estados_t_b.id_estado_cliente=4");
$r_benerficiario1=ejecutar($q_benerficiario1);
while($f_benerficiario1=asignar_a($r_benerficiario1,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_benerficiario1[cedula]?>"><?php echo "$f_benerficiario1[nombres] $f_benerficiario1[apellidos] "?></option>
<?php 
}
?>
</select>


</td>
                <td class="tdcamposc">Del Banco </td>
                <td class="tdcamposc"><select id="banco" name="banco" class="campos" style="width: 100px;"  >
                  <?php $q_banco=("select tbl_bancos.*,bancos.* from tbl_bancos,bancos where tbl_bancos.id_ban=bancos.id_ban");
$r_banco=ejecutar($q_banco);
while($f_banco=asignar_a($r_banco,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_banco[id_banco]?>"><?php echo "$f_banco[nombanco] $f_banco[numero_cuenta] "?></option>
<?php 
}
?>
</select>
</td>
<td class="tdcamposc">Num Che</td>
                        <td  class="tdcampos"><input class="campos" type="text" id="numcheque" size=10 name="numcheque" value=""></td>
                <td  class="tdtitulos"><a href="#" OnClick="gua_che_reem();" class="boton" title="Guardar Cheque">Guardar</a></td>
                
        </tr>
</table>
<?php
}
?>
