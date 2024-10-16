<?php
/* Nombre del Archivo: reg_baremo_precio.php
   Descripción:Formulario para registrar nombre de baremos de precios y sus caracteristicas 
*/
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$nombre_bar=strtoupper($_POST['nombre_bar']);
$variable1=$_POST['variable1'];
$id_baremo=$_POST['id_baremo'];
$id_ente=$_POST['id_ente'];
$admin= $_SESSION['id_usuario_'.empresa];
/* **** si la variable es 1 registro nombre del baremo de precios **** */

if ($variable1==1)
{
	

$q_regbaremo = "insert into tbl_baremos (nombre_bar) values('$nombre_bar')";
$r_regbaremo = ejecutar($q_regbaremo);


$log=" registro nombre $nombre_bar del baremo de precios  ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}
/* **** si la variable es 2 modifico nombre del baremo de precios **** */
if ($variable1==2)
{
	
$q_actbaremo = "update tbl_baremos set nombre_bar='$nombre_bar' where tbl_baremos.id_baremo='$id_baremo' ";
$r_actbaremo = ejecutar($q_actbaremo);



$log=" modifico nombre $nombre_bar del baremo de precios";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

/* **** si la variable es 4 y id_baremo >0  borro precios viejos al nombre del baremo y registro precios nuevo  al nombre del baremo  **** */
if ($variable1==4 and $id_baremo>0)
{
	
		$r_examen_del="delete 
										from
											tbl_baremos_precios 
										where
											tbl_baremos_precios.id_baremo=$id_baremo;
										";
$r_examen_del=ejecutar($r_examen_del);
	
$q_baremoe="select 
							* 
					from 
							examenes_bl 
					where
							examenes_bl.id_tipo_examen_bl<>4
					order by 
							examenes_bl.examen_bl";
$r_baremoe=ejecutar($q_baremoe);

	while($f_baremoe=asignar_a($r_baremoe,NULL,PGSQL_ASSOC)){

	$r_examen="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$id_baremo',
                                '$f_baremoe[id_examen_bl]',
								'0',
								'0',
                                '$f_baremoe[honorarios]');";
$f_examen=ejecutar($r_examen);
}

$q_baremoi="select 
							* 
					from 
							imagenologia_bi
					order by 
							imagenologia_bi.imagenologia_bi";
$r_baremoi=ejecutar($q_baremoi);

	while($f_baremoi=asignar_a($r_baremoi,NULL,PGSQL_ASSOC)){
	$r_exameni="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$id_baremo',
								'0',
                                '$f_baremoi[id_imagenologia_bi]',
								'0',
                                '$f_baremoi[honorarios]');";
$f_exameni=ejecutar($r_exameni);
}


$q_baremoes="select 
							* 
					from 
							especialidades_medicas
					order by 
							especialidades_medicas.especialidad_medica";
$r_baremoes=ejecutar($q_baremoes);

	while($f_baremoes=asignar_a($r_baremoes,NULL,PGSQL_ASSOC)){
	$r_espe="insert into 
                                tbl_baremos_precios 
                                (id_baremo,
                                id_examen_bl,
								id_imagenologia_bi,
								id_especialidad_medica,
                                precio) 
                        values 
                                ('$id_baremo',
								'0',
                               	'0',
								'$f_baremoes[id_especialidad_medica]',
                                '$f_baremoes[monto]');";
$f_espe=ejecutar($r_espe);
}

$log=" borro precios viejos al nombre del baremo y registro precios nuevo  al nombre del baremo";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

/* **** si la variable es 5 asigno baremo de precios a un ente **** */

if ($variable1==5 and $id_baremo>0 and $id_ente>0)
{

			$q_baremo_ente_del="delete 
										from
											tbl_baremos_entes 
										where
											tbl_baremos_entes.id_ente=$id_ente;
										";
			$q_baremo_ente_del=ejecutar($q_baremo_ente_del);

$q_baremo_ente = "insert into 
										tbl_baremos_entes 
										(id_baremo,
										id_ente)
								values
										('$id_baremo',
										'$id_ente')";
$r_baremo_ente = ejecutar($q_baremo_ente);



$log="Asigno Baremo de Precios con id $id_baremo a un ente con id $id_ente";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

/* **** si la variable es 6 Actualizo la lista de precios de baremo de precios **** */

if ($variable1==6)
{
	$conexa=$_POST['conexa'];
	$examen_bl_1=$_REQUEST['examen_bl_1'];
	$precio_1=$_REQUEST['precio_1'];
	
	$examen_bl_2=split("@",$examen_bl_1);
	$precio_2=split("@",$precio_1);


$q="
begin work;
";

for($i=0;$i<=$conexa;$i++){
	
	$examen_bl=$examen_bl_2[$i];
	$precio=$precio_2[$i];
	

	if(!empty($examen_bl) && $examen_bl>0){
	
		$q.="update 
                        tbl_baremos_precios 
                set 
                        precio='$precio'
                where  
                        tbl_baremos_precios.id_baremo='$id_baremo' and
						tbl_baremos_precios.id_baremo_precio='$examen_bl';";
	}
	
	}
$q.="
commit work;
";
$r=ejecutar($q);

$log="Actualizo el Baremo de precios con id $id_baremo ";
logs($log,$ip,$admin);

	
	}


/* **** buscamos el nombre de los baremos de precio **** */
$q_baremo = "select 
							tbl_baremos.id_baremo,
							tbl_baremos.nombre_bar 
					from 
							tbl_baremos 
					order by 
							tbl_baremos.nombre_bar";
$r_baremo = ejecutar($q_baremo);

/* **** buscamos el nombre de los entes **** */
$q_ente = "select 
							* 
					from 
							entes,
							tbl_tipos_entes 
					where 
							entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente  and
							(tbl_tipos_entes.id_tipo_ente=4 or
							tbl_tipos_entes.id_tipo_ente=6  or
							tbl_tipos_entes.id_tipo_ente=8 or
							tbl_tipos_entes.id_tipo_ente=7)
					order by 
							tbl_tipos_entes.tipo_ente,entes.nombre ";
$r_ente = ejecutar($q_ente);

?>
<link HREF="../../public/stylesheets/estilos1.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js">

</script>

<form action="POST" method="POST" name="banca1" id="formp">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr> <td colspan=4 class="titulo_seccion"><b>Tipos de Baremos</b></td>	
	</tr>
	<tr>
		<td colspan=4>&nbsp;</td>
	</tr> 
	<tr>
	<td colspan=1 class="tdtitulos">Nombre del Baremo</td>
	<td colspan=1>
	<select id="id_baremo" name="id_baremo" class="campos" style="width: 200px;" OnChange="gua_nombre_bar(3);">
		<option value="0">-- Seleccione un Baremo --</option>
		<?php
		while($f_baremo = asignar_a($r_baremo)){
			$nombre_bar=$f_baremo['nombre_bar'];
			?>
			
	<option value="<?php echo $f_baremo['id_baremo'];?>"  
	<?php if ($f_baremo[id_baremo]==$id_baremo)
	{ 
	echo "selected";
	}   
	?> 
	> 
	<?php echo $f_baremo['nombre_bar'];?> 
	</option>
			
		<?php
		}
		?>

		</select>
		</td>
	<td colspan=1 class="tdtitulos">* Nombre Baremo</td>
		<td colspan=1 class="tdcampos"><input type="text" size="30" class="campos" id="nombre_bar" name="nombre_bar">
		</td>
	
	</tr> 
	
	
	<tr>
		<td colspan=4>&nbsp;</td>
	</tr> 
	<tr>
	<td colspan=1 class="tdtitulos"> Ente</td>
	<td colspan=1>
	<select id="id_ente" name="id_ente" class="campos" style="width: 200px;">
		<option value="0">-- Seleccione un Ente para Asignarle o Modificarle el Baremo --</option>
		<?php
		while($f_ente = asignar_a($r_ente)){
			?>
	
		 <option  <?php if ($f_ente[id_tipo_ente]==8) {?> class="option" <?php } ?>
<?php if ($f_ente[id_tipo_ente]==4) {?> class="option2" <?php } ?>
<?php if ($f_ente[id_tipo_ente]==6) {?> class="option1" <?php } ?>
value="<?php echo "$f_ente[id_ente]"?>"> <?php echo "$f_ente[nombre] ($f_ente[tipo_ente])"?></option>
		<?php
		}
		?>
		</select>
		</td>
	<td colspan=1 class="tdtitulos"> </td>
		<td colspan=1 class="tdcampos"></td>
	
	</tr> 
	<tr>
		<td colspan=4 class="tdcampos">
		<hr></hr>
		</tr>
	<tr>
		<td colspan=4 class="titulo_botones">
		<a href="#" OnClick="gua_nombre_bar(5);" class="boton" title="Asignar Baremo al Ente">Asignar Baremo al Ente</a>
		<a href="#" OnClick="gua_nombre_bar(4);" class="boton" title="Asignar Examenes al Nombre del Baremo">Asignar Baremo Inicial</a>
		<a href="#" OnClick="gua_nombre_bar(1);" class="boton" title="Guardar Nombre del Baremo de Precios">Guardar Nombre de Baremo</a>
				
		<!-- <a href="#" OnClick="gua_nombre_bar(2);" class="boton" title="Actualizar Nombre del Baremo de Precios">Actualizar Nombre de Baremo
		 </a> -->
		  <a href="#" title="Ir al Inicio" OnClick="ir_principal();" class="boton">Salir </a>
		  
		</td>
	</tr>
			<tr>
		<td colspan=4 class="tdcampos">
		<hr></hr>
		</tr>
    
</table>	
<?php
 if ($variable1==5  and $id_baremo>0)
{

?>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr> <td colspan=4 class="titulo_seccion"><b>Se Asigno con Exito el Baremo de Precios al Ente</b></td>	
	</tr>
	
	</table>
<?php
}
?>
<?php if (($variable1==3 ||$variable1==4 ||$variable1==6 ) and $id_baremo>0)
{
	/* **** buscamos la lista de precio que esta asignado a un baremo de precios **** */
$q_baremo_exa = "select 
							* 
					from 
							tbl_baremos_precios,
							examenes_bl,
							tipos_examenes_bl
					where 
							tbl_baremos_precios.id_baremo='$id_baremo' and
							tbl_baremos_precios.id_examen_bl=examenes_bl.id_examen_bl  and
							examenes_bl.id_tipo_examen_bl=tipos_examenes_bl.id_tipo_examen_bl

					order by 
							tipos_examenes_bl.tipo_examen_bl,examenes_bl.examen_bl";
$r_baremo_exa = ejecutar($q_baremo_exa);


$q_baremo_exa1 = "select 
							* 
					from 
							tbl_baremos_precios,
							imagenologia_bi,
							tipos_imagenologia_bi
					where 
							tbl_baremos_precios.id_baremo='$id_baremo' and
							tbl_baremos_precios.id_imagenologia_bi=imagenologia_bi.id_imagenologia_bi  and
							imagenologia_bi.id_tipo_imagenologia_bi=tipos_imagenologia_bi.id_tipo_imagenologia_bi

					order by 
							tipos_imagenologia_bi.tipo_imagenologia_bi,imagenologia_bi.imagenologia_bi";
$r_baremo_exa1 = ejecutar($q_baremo_exa1);

$q_baremo_exa2 = "select 
							* 
					from 
							tbl_baremos_precios,
							especialidades_medicas
					where 
							tbl_baremos_precios.id_baremo='$id_baremo' and
							tbl_baremos_precios.id_especialidad_medica=especialidades_medicas.id_especialidad_medica

					order by 
							especialidades_medicas.especialidad_medica";
$r_baremo_exa2 = ejecutar($q_baremo_exa2);
	?>
	
	
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr> <td colspan=4 class="titulo_seccion"><b>Precios del  Baremo</b></td>	
	</tr>
	
	<?php		
		$i=0;
		while($f_baremo_exa=asignar_a($r_baremo_exa,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
		<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>
<tr onMouseOver="this.className='botonimagen2'"  onMouseOut="this.className='botonimagen'">
	<td   colspan=1 class="tdcampos"> <?php echo $f_baremo_exa[tipo_examen_bl]?>
  
			</td>
			
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="examen_bl_<?php echo $i?>" name="examen_bl_" maxlength=128 size=70 value="<?php echo $f_baremo_exa[id_baremo_precio]?>"    > <?php echo $f_baremo_exa[examen_bl]?>

<input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos"><input size=5 class="campos"  OnChange="return validarNumero(this);" type="text" id="precio_<?php echo $i?>" name="precio_" cols=70 rows=3 value="<?php echo $f_baremo_exa[precio]?>"></td>
	 
	</tr>
				<?php
		}
		?>
		<?php	
		while($f_baremo_exa1=asignar_a($r_baremo_exa1,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
		<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>
<tr onMouseOver="this.className='botonimagen2'"  onMouseOut="this.className='botonimagen'">
	<td   colspan=1 class="tdcampos"> <?php echo $f_baremo_exa1[tipo_imagenologia_bi]?>
  
			</td>
			
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="examen_bl_<?php echo $i?>" name="examen_bl_" maxlength=128 size=70 value="<?php echo $f_baremo_exa1[id_baremo_precio]?>"    > <?php echo $f_baremo_exa1[imagenologia_bi]?>

<input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos"><input size=5 class="campos"  OnChange="return validarNumero(this);" type="text" id="precio_<?php echo $i?>" name="precio_" cols=70 rows=3 value="<?php echo $f_baremo_exa1[precio]?>"></td>
	 
	</tr>
				<?php
		}
		
		while($f_baremo_exa2=asignar_a($r_baremo_exa2,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
		<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>
<tr onMouseOver="this.className='botonimagen2'"  onMouseOut="this.className='botonimagen'">
	<td   colspan=1 class="tdcampos"> ESPECIALIDAD MEDICA
  
			</td>
			
<td   colspan=2 class="tdcampos">
<input class="campos" type="hidden" id="examen_bl_<?php echo $i?>" name="examen_bl_" maxlength=128 size=70 value="<?php echo $f_baremo_exa2[id_baremo_precio]?>"    > <?php echo $f_baremo_exa2[especialidad_medica]?>

<input class="campos" type="checkbox" checked style="visibility:hidden" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  
			</td>
			<td colspan=1 class="tdcampos"><input size=5 class="campos"  OnChange="return validarNumero(this);" type="text" id="precio_<?php echo $i?>" name="precio_" cols=70 rows=3 value="<?php echo $f_baremo_exa2[precio]?>"></td>
	 
	</tr>
				<?php
		}
		
		echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";
		
		?>
	<tr>
		<td colspan=4 class="tdcampos">
		<hr></hr>
		</tr>
	<tr>
		<td colspan=1 class="tdcampos"> 	</td>
			<td colspan=1 class="tdcampos"> 	</td>
				<td colspan=1 class="tdcampos"> 	</td>
		<td colspan=1 class="tdcampos"> 
         <a href="#"  OnClick="act_baremo_precio(6);" title="Actualizar Baremo de Precios" class="boton">
        Act Baremo</a>
		
		</td>
			</tr>
	</table>
	
	
	<?php

$log=" Asigno la lista de precios al  $nombre_bar ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

?>


