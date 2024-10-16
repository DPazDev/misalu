<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
/* **** busco el admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
list($condicion,$tipoexamen)=explode("@",$_POST['examenes']);

if ($condicion=='0'){
}
else
{
if ($condicion=='*'){
$q_examen=("select * from examenes_bl where examenes_bl.id_tipo_examen_bl=3 order by examenes_bl.examen_bl");
$r_examen=ejecutar($q_examen);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
       <?php       
$i=0;
$ban="";
 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;
	
	?>
    	<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>

	<tr>
		<td colspan=2 class="tdtitulos"><?php echo $f_examen[examen_bl]?></td>
		<td colspan=1 class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength=128 size=20 value="<?php echo $f_examen[id_examen_bl]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength=128 size=20 value="<?php echo $f_examen[examen_bl]?>">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="examen" maxlength=128 size=10 value="<?php echo $f_examen[honorarios]?>"  OnChange="return validarNumero(this);" >
		<input class="campos" type="hidden" id="coment_<?php echo $i?>"  name="coment" maxlength=128 size=20 value="">
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""></td>

		
	</tr>
	<?php
	}
	echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";
	?>
		<tr> <td>&nbsp;</td></tr>
	
<?php
}
else
{
$examenes=$_POST['examenes'];
$q_examen=("select * from imagenologia_bi where imagenologia_bi.id_tipo_imagenologia_bi='$condicion' order by imagenologia_bi.imagenologia_bi");
$r_examen=ejecutar($q_examen);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
       <?php       
$i=0;
$ban="";

 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;

	?>
	<tr> <td>&nbsp;</td></tr>
	<tr>


		<td colspan=2  class="tdtitulos"><?php echo $f_examen[imagenologia_bi]?>-------------------------></td>
		<td colspan=2  class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamen" maxlength=128 size=20 value="<?php echo $f_examen[id_imagenologia_bi]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examen" maxlength=128 size=20 value="<?php echo $f_examen[imagenologia_bi]?>"><input class="campos" type="text" id="honorarios_<?php echo $i?>" name="examen" maxlength=128 size=20 value="<?php echo $f_examen[honorarios]?>" OnChange="return validarNumero(this);"  >
		<select  id="coment_<?php echo $i?>"  name="coment" class="campos" >
		
				<option value="iNFORMADA"> INFORMADA</option>
				<option value="">NO INFORMADA</option>
				

		</select>

		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkdes" maxlength=128 size=20 value=""></td>

		
</tr>
	
	<?php
	}
	echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";
	?>
	<tr> <td>&nbsp;</td></tr>


	 
<?php
}
?>
<tr>
				
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" id="monto" name="monto" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);"  ></td>
					
	<td><a href="javascript: sumar(this);"  class="boton">      Calcular Monto</a>
	</td>

	</tr>
<tr>
<td colspan=2>
                <select  style="width: 200px;" id="proveedor" name="proveedor" OnChange="ver_gua_ord_par(this);" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.activar=1 and proveedores.tipo_proveedor=1 and clinicas_proveedores.id_ciudad=$f_admin[id_ciudad] order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="0"> Seleccione la Clinica</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
</td>
<td><a style="visibility:hidden" id="eti_gua_ord_par" OnClick="gua_ord_par_rap();"  class="boton">      Guardar</a>
	</td>		
</tr>
</table>
<?php
}
?>

