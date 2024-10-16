<?php
include ("../../lib/jfunciones.php");
sesion();
$tbaremo=$_POST['tbaremo'];
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	
	<?php
	if  ($tbaremo==0)
{
	
	?>
	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Baremo.</td>
<td  class="tdcampos">
		<select name="baremo1" id="baremo1" class="campos" >
		<?php $q_baremo=("select * from tipos_imagenologia_bi  order by tipo_imagenologia_bi");
		$r_baremo=ejecutar($q_baremo);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo "$f_baremo[id_tipo_imagenologia_bi]@$f_baremo[tipo_imagenologia_bi]"?>"> <?php echo "$f_baremo[tipo_imagenologia_bi]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="rep_baremo2();" class="boton">Buscar</a>
        <a href="#" OnClick="bus_rep_excel_baremo();"> <img border="0" src="../public/images/excel.jpg"></a> 
        <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
		
		<?php
		
		}
		?>
		<?php
	if  ($tbaremo==1)
{
	
	?>
	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Baremo.</td>
<td  class="tdcampos">
		<select name="baremo1" id="baremo1" class="campos" >
		<?php $q_baremo=("select * from tipos_examenes_bl  where id_tipo_examen_bl<>4 order by tipo_examen_bl");
		$r_baremo=ejecutar($q_baremo);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo "$f_baremo[id_tipo_examen_bl]@$f_baremo[tipo_examen_bl]"?>"> <?php echo "$f_baremo[tipo_examen_bl]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="rep_baremo2();" class="boton">Buscar</a>
        <a href="#" OnClick="bus_rep_excel_baremo();"> <img border="0" src="../public/images/excel.jpg"></a> 
        <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
		
		<?php
		
		}
		?>
			<?php
	if  ($tbaremo==2)
{
	
	?>
	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Baremo.</td>
<td  class="tdcampos">
		<select name="baremo1" id="baremo1" class="campos" >
		
		<option value="1@ESPECIALIDAD MEDICA"> Especialidad Medica</option>
	
		</select>
		<a href="#" OnClick="rep_baremo2();" class="boton">Buscar</a>
        <a href="#" OnClick="bus_rep_excel_baremo();"> <img border="0" src="../public/images/excel.jpg"></a> 
        <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
		
		<?php
		}
if  ($tbaremo==3)
{
	
	?>
	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Baremo.</td>
<td  class="tdcampos">
		<select name="baremo1" id="baremo1" class="campos" >
		<?php $q_baremo=("select * from tbl_tipos_insumos  where id_tipo_insumo<3 order by tipo_insumo");
		$r_baremo=ejecutar($q_baremo);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo "$f_baremo[id_tipo_insumo]@$f_baremo[tipo_insumo]"?>"> <?php echo "$f_baremo[tipo_insumo]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="rep_baremo2();" class="boton">Buscar</a>
        <a href="#" OnClick="bus_rep_excel_baremo();"> <img border="0" src="../public/images/excel.jpg"></a> 
        <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
		
		<?php
		
		}
		
        if  ($tbaremo==4)
{
	
	?>
	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Baremo.</td>
<td  class="tdcampos">
	<select name="baremo1" id="baremo1" class="campos" >
		
		<option value="4@SERVICIOS"> Servicios</option>
	
		</select>
		<a href="#" OnClick="rep_baremo2();" class="boton">Buscar</a>
        <a href="#" OnClick="bus_rep_excel_baremo();"> <img border="0" src="../public/images/excel.jpg"></a> 
        <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
		
		<?php
		
		}
		?>

</table>
	<div id="bus_rep_baremo2"></div>

