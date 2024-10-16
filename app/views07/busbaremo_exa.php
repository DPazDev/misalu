<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$baremo=$_REQUEST['baremo'];
$tbaremo=$_REQUEST['tbaremo'];
$examen=strtoupper($_REQUEST['examen']);
$monto=$_REQUEST['monto'];
$monto2=$_REQUEST['monto2'];
$control=$_REQUEST['control'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* aqui se registra un examen para algun tipo de baremo */
if ($control==1){
	
		if  ($tbaremo==0)
{
	
	$r_examen="insert into imagenologia_bi (id_tipo_imagenologia_bi ,imagenologia_bi,honorarios,fecha_creado,hora_creado,hono_privados) 
values ('$baremo','$examen','$monto','$fechacreado','$hora','$monto2');";
$f_examen=ejecutar($r_examen);

/* **** buscamos el examen que se ingreso **** */
$q_bus_examen = "select 
							* 
					from 
							imagenologia_bi 
					where
							imagenologia_bi.imagenologia_bi='$examen' ";
$r_bus_examen = ejecutar($q_bus_examen);
$f_bus_examen = asignar_a($r_bus_examen);

/* **** buscamos el nombre de los baremos de precio **** */
$q_baremo_pre = "select 
							tbl_baremos.id_baremo,
							tbl_baremos.nombre_bar 
					from 
							tbl_baremos 
					order by 
							tbl_baremos.nombre_bar";
$r_baremo_pre = ejecutar($q_baremo_pre);

	while($f_baremo_pre=asignar_a($r_baremo_pre,NULL,PGSQL_ASSOC)){
		
			$r_baremos_precios ="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$f_baremo_pre[id_baremo]',
                                '0',
								'$f_bus_examen[id_imagenologia_bi]',
								'0',
                                '$f_bus_examen[honorarios]');";
			$f_baremos_precios=ejecutar($r_baremos_precios);
		
		}


}

		if  ($tbaremo==1)
{
	
	$r_examen="insert into examenes_bl (id_tipo_examen_bl ,examen_bl,honorarios,fecha_creado,hora_creado,hono_privados) 
values ('$baremo','$examen','$monto','$fechacreado','$hora','$monto2');";
$f_examen=ejecutar($r_examen);

/* **** buscamos el examen que se ingreso **** */
$q_bus_examen = "select 
							* 
					from 
							examenes_bl 
					where
							examenes_bl.examen_bl='$examen' ";
$r_bus_examen = ejecutar($q_bus_examen);
$f_bus_examen = asignar_a($r_bus_examen);

/* **** buscamos el nombre de los baremos de precio **** */
$q_baremo_pre = "select 
							tbl_baremos.id_baremo,
							tbl_baremos.nombre_bar 
					from 
							tbl_baremos 
					order by 
							tbl_baremos.nombre_bar";
$r_baremo_pre = ejecutar($q_baremo_pre);

	while($f_baremo_pre=asignar_a($r_baremo_pre,NULL,PGSQL_ASSOC)){
		
			$r_baremos_precios ="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$f_baremo_pre[id_baremo]',
                                '$f_bus_examen[id_examen_bl]',
								'0',
								'0',
                                '$f_bus_examen[honorarios]');";
			$f_baremos_precios=ejecutar($r_baremos_precios);
		
		}



}

		if  ($tbaremo==2)
{
	
	$r_examen="insert into especialidades_medicas (especialidad_medica,monto,fecha_creado,hora_creado,hono_privados) 
values ('$examen','$monto','$fechacreado','$hora',$monto2);";
$f_examen=ejecutar($r_examen);



/* **** buscamos el examen que se ingreso **** */
$q_bus_examen = "select 
							* 
					from 
							especialidades_medicas 
					where
							especialidades_medicas.especialidad_medica='$examen' ";
$r_bus_examen = ejecutar($q_bus_examen);
$f_bus_examen = asignar_a($r_bus_examen);

/* **** buscamos el nombre de los baremos de precio **** */
$q_baremo_pre = "select 
							tbl_baremos.id_baremo,
							tbl_baremos.nombre_bar 
					from 
							tbl_baremos 
					order by 
							tbl_baremos.nombre_bar";
$r_baremo_pre = ejecutar($q_baremo_pre);

	while($f_baremo_pre=asignar_a($r_baremo_pre,NULL,PGSQL_ASSOC)){
		
			$r_baremos_precios ="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$f_baremo_pre[id_baremo]',
                                '0',
								'0',
								'$f_bus_examen[id_especialidad_medica]',
                                '$f_bus_examen[monto]');";
			$f_baremos_precios=ejecutar($r_baremos_precios);
		
		}



}

	?>
	
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">Examenen Registrado Satisfactoriamente</td></tr>
</table>
	
	<?php
	}
	
/* fin de  registrar un examen para algun tipo de baremo */

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">Examenes</td></tr>

<?php
	if  ($tbaremo==0)
{
	


$q_baremo="select * from imagenologia_bi where imagenologia_bi.id_tipo_imagenologia_bi='$baremo' order by imagenologia_bi";
$r_baremo=ejecutar($q_baremo);

?>
<tr>
<td  colspan=2 class="tdtitulos">Examenes</td>
<td  colspan=1 class="tdtitulos">Monto CliniSalud</td>
<td  colspan=1 class="tdtitulos">Monto Privado</td>
</tr>
		<?php		
		$i=0;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
<tr>
	
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="id_imagenologia_bi_<?php echo $i?>" name="id_imagenologia_bi" maxlength=128 size=5 value="<?php echo $f_baremo[id_imagenologia_bi]?>"    >
<input class="campos" type="text" id="imagenologia_bi_<?php echo $i?>" name="imagenologia_bi" maxlength=128 size=70 value="<?php echo $f_baremo[imagenologia_bi]?>"    ><input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos"><input size=5 class="campos"  type="text" id="honorarios_<?php echo $i?>" name="honorarios" cols=70 rows=3 value="<?php echo $f_baremo[honorarios]?>"></td>
	 <td colspan=1 class="tdcampos">
<input size=5 class="campos"  type="text" id="honorarios_pri_<?php echo $i?>"
 name="honorarios_pri" cols=70 rows=3 value="<?php echo $f_baremo[hono_privados]?>"></td>

		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>
		
		<?php
	if  ($tbaremo==2)
{
	


$q_baremo="select * from especialidades_medicas order by especialidades_medicas.especialidad_medica";
$r_baremo=ejecutar($q_baremo);

?>
<tr>
<td  colspan=2 class="tdtitulos">Examenes</td>
<td  colspan=1 class="tdtitulos">Monto CliniSalud</td>
<td  colspan=1 class="tdtitulos">Monto Privado</td>

</tr>
		<?php		
		$i=0;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
<tr>
	
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="id_imagenologia_bi_<?php echo $i?>" name="id_imagenologia_bi" maxlength=128 size=5 value="<?php echo $f_baremo[id_especialidad_medica]?>"    >
<input class="campos" type="text" id="imagenologia_bi_<?php echo $i?>" name="imagenologia_bi" maxlength=128 size=70 value="<?php echo $f_baremo[especialidad_medica]?>"    ><input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos"><input size=5 class="campos"  type="text" id="honorarios_<?php echo $i?>" name="honorarios" cols=70 rows=3 value="<?php echo $f_baremo[monto]?>"></td>
	 <td colspan=1 class="tdcampos">
<input size=5 class="campos"  type="text" id="honorarios_pri_<?php echo $i?>"
 name="honorarios_pri" cols=70 rows=3 value="<?php echo $f_baremo[hono_privados]?>"></td>

		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>
		
			<?php
	if  ($tbaremo==1)
{
	


$q_baremo="select * from examenes_bl where examenes_bl.id_tipo_examen_bl='$baremo' order by examenes_bl.examen_bl";
$r_baremo=ejecutar($q_baremo);

?>
<tr>
<td  colspan=2 class="tdtitulos">Examenes</td>
<td  colspan=1 class="tdtitulos">Monto CliniSalud</td>
<td  colspan=1 class="tdtitulos">Monto Privado</td>
</tr>
		<?php		
		$i=0;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
<tr>
	
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="id_imagenologia_bi_<?php echo $i?>" name="id_imagenologia_bi" maxlength=128 size=5 value="<?php echo $f_baremo[id_examen_bl]?>"    >
<input class="campos" type="text" id="imagenologia_bi_<?php echo $i?>" name="imagenologia_bi" maxlength=128 size=70 value="<?php echo $f_baremo[examen_bl]?>"    ><input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos">
<input size=5 class="campos"  type="text" id="honorarios_<?php echo $i?>"
 name="honorarios" cols=70 rows=3 value="<?php echo $f_baremo[honorarios]?>"></td>
	
 <td colspan=1 class="tdcampos">
<input size=5 class="campos"  type="text" id="honorarios_pri_<?php echo $i?>" 
 name="honorarios_pri" cols=70 rows=3 value="<?php echo $f_baremo[hono_privados]?>"></td>
		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>
		
		
<tr>
		<td colspan=2 class="tdtitulos"> <a href="#"  OnClick="act_baremo();" class="boton"> Actualizar </a>
			</td>
			<td colspan=2 class="tdtitulos"><a href="#"  OnClick="reg_baremo3();" class="boton"> Registar </a>
			</td>
</tr>

</table>
	<div id="reg_baremo3"></div>





