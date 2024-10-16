<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');

list($baremo,$nom_baremo)=explode("@",$_POST['baremo1']);

list($tbaremo,$nom_tbaremo)=explode("@",$_POST['tbaremo']);
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
	

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>


<?php
	if  ($tbaremo==0)
{
	


$q_baremo="select * from imagenologia_bi where imagenologia_bi.id_tipo_imagenologia_bi='$baremo' order by imagenologia_bi";
$r_baremo=ejecutar($q_baremo);

?>
<tr>

<td  colspan=1 class="tdtitulos">Codigo</td>
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
	<td   colspan=1 class="tdcampos"><?php echo "$tbaremo-$baremo-$f_baremo[id_imagenologia_bi]"?>

			</td>
<td   colspan=2 class="tdcampos"><?php echo $f_baremo[imagenologia_bi]?></td>
			<td colspan=1 class="tdcampos"><?php echo $f_baremo[honorarios]?></td>
	 <td colspan=1 class="tdcampos"><?php echo $f_baremo[hono_privados]?></td>

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

<td  colspan=1 class="tdtitulos">Codigo</td>
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
		<td   colspan=1 class="tdcampos"><?php echo "$tbaremo-$baremo-$f_baremo[id_especialidad_medica]"?>

			</td>
<td   colspan=2 class="tdcampos">
<?php echo $f_baremo[especialidad_medica]?></td>
			<td colspan=1 class="tdcampos"><?php echo $f_baremo[monto]?></td>
	 <td colspan=1 class="tdcampos"><?php echo $f_baremo[hono_privados]?></td>

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

<td  colspan=1 class="tdtitulos">Codigo</td>
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
	<td   colspan=1 class="tdcampos"><?php echo "$tbaremo-$baremo-$f_baremo[id_examen_bl]"?>

			</td>	
<td   colspan=2 class="tdcampos"><?php echo $f_baremo[examen_bl]?>  
			</td>
			<td colspan=1 class="tdcampos"><?php echo $f_baremo[honorarios]?></td>
	
 <td colspan=1 class="tdcampos"><?php echo $f_baremo[hono_privados]?>
 </td>
		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>
		
		<?php
	if  ($tbaremo==3)
{
	
    if ($baremo==1)
    {
        $id_dependencia='64';
        }
        else
        {
            $id_dependencia='89';
            }


$q_baremo="select  
                            tbl_insumos.id_insumo,
                            tbl_insumos.insumo,
                            tbl_insumos_almacen.monto_unidad_publico,
                            tbl_laboratorios.laboratorio  
                    from 
                            tbl_insumos,
                            tbl_insumos_almacen,
                            tbl_laboratorios
                    where 
                            tbl_insumos.id_tipo_insumo='$baremo' and
                            tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and 
                            tbl_insumos_almacen.id_dependencia=$id_dependencia  and 
                            tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio
                    order by 
                            tbl_insumos.insumo";
$r_baremo=ejecutar($q_baremo);

?>
<tr>

<td  colspan=1 class="tdtitulos">Codigo</td>
<td  colspan=2 class="tdtitulos">Medicamento o Insumo</td>
<td  colspan=1 class="tdtitulos">Monto CliniSalud</td>
<td  colspan=1 class="tdtitulos">Monto Privado</td>
</tr>
		<?php		
		$i=0;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
<tr>
	<td   colspan=1 class="tdcampos"><?php echo "$tbaremo-$baremo-$f_baremo[id_insumo]"?>

			</td>	
<td   colspan=2 class="tdcampos"><?php echo "$f_baremo[insumo] $f_baremo[laboratorio]"?>  
			</td>
			<td colspan=1 class="tdcampos"><?php echo number_format($f_baremo[monto_unidad_publico],2,'.','')?></td>
	
 <td colspan=1 class="tdcampos"><?php echo number_format($f_baremo[monto_unidad_publico],2,'.','')?>
 </td>
		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>
		<?php
	if  ($tbaremo==4)
{
	


$q_baremo="select * from servicios where id_servicio<>12 order by servicios.servicio";
$r_baremo=ejecutar($q_baremo);

?>
<tr>

<td  colspan=2 class="tdtitulos">Codigo</td>
<td  colspan=2 class="tdtitulos">Examenes</td>

</tr>
		<?php		
		$i=0;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
			
		?>
<tr>
		<td   colspan=2 class="tdcampos"><?php echo "$f_baremo[codigo]"?>

			</td>
<td   colspan=2 class="tdcampos">
<?php echo $f_baremo[servicio]?></td>

		</tr>
				<?php
		}
		echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
		}
		?>

</table>
	<div id="reg_baremo3"></div>





