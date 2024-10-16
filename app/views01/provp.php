<?php
include ("../../lib/jfunciones.php");
sesion();
$formp1=$_REQUEST['formp1'];
$monto=$_REQUEST['monto'];
$tiposerv=$_REQUEST['tiposerv'];
$donativo=$_REQUEST['donativo'];
$ClasifiaProveedor=$_REQUEST['clasificaproveedor']; //clasificaproveedor  intramural(1) extramural(0) TODOS(2)

//echo "formp1: $formp1 tiposerv: $tiposerv clasificacion: $ClasifiaProveedor";
$q_cobertura="select * from entes,titulares,coberturas_t_b where entes.id_ente=titulares.id_ente and titulares.id_titular=coberturas_t_b.id_titular and coberturas_t_b.id_cobertura_t_b='$formp1'";
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);

if ($monto>$f_cobertura[monto_actual] && $donativo==0)
{
	?>
	<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4  class="titulo_seccion">
El Monto a Cargar Sobrepasa La Cobertura
             </td>
</tr>
</table>
	<?php

	}
else
{
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4 >
                <select style="width: 200px;" id='Proveedor-Persona' onchange="ConsultaDisponibilidad();" name="proveedor" class="campos">
                <?php
						if($ClasifiaProveedor=='0' || $ClasifiaProveedor=='1') { //FILTRAR POR INTRAMURAL O ESTRAMURAL
								$q_p=("select especialidades_medicas.especialidad_medica,proveedores.*,
                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
                s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor
                and
                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
                and s_p_proveedores.activar=1 and tipo_proveedor=$ClasifiaProveedor order by personas_proveedores.nombres_prov");

							}else{

								$q_p=("select especialidades_medicas.especialidad_medica,proveedores.*,
                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
                s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor
                and
                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
                and s_p_proveedores.activar=1 order by personas_proveedores.nombres_prov");
							}

                $r_p=ejecutar($q_p);
                ?>
                <option   value=""> Seleccione el Dr(a).</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] ($f_p[especialidad_medica]) $f_p[direccion_prov]"?></option>
                <?php
                }
                ?>
                </select>
 </td>
</tr>
<tr>
<td class="tdtitulos"><center><br><div id='disponibilidad-horario'></div><br><center></td>
</tr>



<!--------------------------------------------------------GUARDAR PROCESO--------------------->
<tr >

<?php if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12)){
	?>
<td colspan=4 align="center" ><a href="#" OnClick="guardaroa('clientes');" class="boton">Guardar</a></td>
<?php }
else
{
	if(($tiposerv==25)){
		?>
		<td colspan=4 align="center" ><a href="#" OnClick="guardareme('reg_oa2');" class="boton">Guardar</a></td>
		<?php
		}
			else{
	?>
	<td colspan=4 align="center" ><a href="#" OnClick="guardaroac('clientes');" class="boton">Guardar</a></td>
	<?php
	}
}
	?>
</tr>

<tr>
<td class="titulo_seccion"><center><br><br><center>Recomendamos consultar la disponibilidad del proveedor<br><center></td>
</tr>


</table>


<?php
}
?>
