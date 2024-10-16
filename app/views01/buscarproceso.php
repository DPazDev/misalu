<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
//header('Content-Type: text/xml; charset=ISO-8859-1');
$proceso=$_REQUEST['proceso'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/**** buscar si este proceso se ha facturado ****/
$q_factura=("select 
								* 
						from 
								tbl_facturas,
								tbl_procesos_claves,
								tbl_series 
						where 
								tbl_facturas.id_factura=tbl_procesos_claves.id_factura and
								tbl_procesos_claves.id_proceso=$proceso and 
								tbl_facturas.id_serie=tbl_series.id_serie and 
								tbl_facturas.id_estado_factura<>3");
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);

 
	if ($num_filasf>0 )
	{
	?>	
    <link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">Esta Orden esta Facturada  Para ser Algun Cambio Notificar al Departamento Administrativo de su Sucursal</td>
      </tr>
	  <tr>
				<td colspan=1 class="tdtitulos">Numero Factura</td>
					<td colspan=1 class="tdtitulos">Numero Control</td>
              	<td colspan=1 class="tdtitulos">Estado Factura y serie</td>
				<td colspan=1 class="tdtitulos">Fecha Creado</td>
	</tr>	
	<?php 
	while($f_factura=asignar_a($r_factura,NULL,PGSQL_ASSOC)){

	if($f_factura['id_estado_factura']==1)
	{
	$estado="Pagada";
	}
	 if($f_factura['id_estado_factura']==2) 
	{
		$estado="Por Cobrar";
		}
		if($f_factura['id_estado_factura']==3)
		{
			$estado="Anulada";
			}
	?>
	<tr>
				<td colspan=1 class="tdcampos"><?php echo $f_factura[numero_factura] ?></td>
              	<td colspan=1 class="tdcampos"><?php echo $f_factura[numcontrol] ?></td>
				<td colspan=1 class="tdcampos"><?php echo "$estado  Serie $f_factura[nomenclatura]" ?></td>
				<td colspan=1 class="tdcampos"><?php echo $f_factura[fecha_hora_creado] ?></td>
	</tr>	
	<?php
		}
		}
        else
        {
	?>
    </table>
<?php
/* **** busco si es titular **** */
$q_cliente=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								clientes.telefono_hab,
								clientes.telefono_otro,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente
					from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes,
								procesos
					where 
								procesos.id_proceso='$proceso' and 
								procesos.id_titular=titulares.id_titular and procesos.id_beneficiario=0 and 
								clientes.id_cliente=titulares.id_cliente and 
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and 
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
								estados_clientes.id_estado_cliente>=4");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								clientes.telefono_hab,
								clientes.telefono_otro,
								estados_clientes.estado_cliente,
								beneficiarios.id_beneficiario,
								beneficiarios.id_titular,
								entes.nombre 
						from 
								clientes,
								estados_clientes,
								beneficiarios,
								entes,
								procesos,
								estados_t_b,
								titulares  
						where 
								procesos.id_proceso='$proceso' and 
								procesos.id_beneficiario=beneficiarios.id_beneficiario and 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
								beneficiarios.id_titular=titulares.id_titular and 
								titulares.id_ente=entes.id_ente and 
								estados_clientes.id_estado_cliente>=4");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de orden no existe </td>
      </tr>

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="reg_cita();" class="boton">Registrar Citas</a></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
	</tr>
</table>
<?php
}
else
{
$q_cobertura=("select 
									gastos_t_b.id_cobertura_t_b,
									coberturas_t_b.monto_actual,
									coberturas_t_b.id_organo,
									propiedades_poliza.cualidad
						from 
									gastos_t_b,
									coberturas_t_b,
									propiedades_poliza 
						where 
									gastos_t_b.id_proceso='$proceso' and
									gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and
									coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza");
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);

/*$q_organo=("select organos.organo from organos where organos.id_organo=$f_cobertura[id_organo]");
$r_organo=ejecutar($q_organo);
$f_organo=asignar_a($r_organo);*/

$q_medico=("select 
								sucursales.sucursal,
								especialidades_medicas.especialidad_medica,
								proveedores.id_proveedor,
								personas_proveedores.*,
								s_p_proveedores.*
					from 
								especialidades_medicas,personas_proveedores,
								s_p_proveedores,proveedores,sucursales,ciudad,gastos_t_b 
					where 
								proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
								s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 				
								s_p_proveedores.nomina=1 and 
								s_p_proveedores.id_ciudad=ciudad.id_ciudad and 
								s_p_proveedores.id_sucursal=sucursales.id_sucursal and
								especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
								gastos_t_b.id_proveedor=proveedores.id_proveedor and 
								gastos_t_b.id_proceso=$proceso 
					order by 
								personas_proveedores.nombres_prov");
                $r_medico=ejecutar($q_medico);
				$f_medico=asignar_a($r_medico);	
	
	
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<?php if ($titular==1) {

/* ***** repita para buscar al titular **** */
$i=0;
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
$i++;
$q_coberturat=("select 
										coberturas_t_b.id_cobertura_t_b,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,
										coberturas_t_b.id_organo
							from 
										propiedades_poliza,
										coberturas_t_b 
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
										coberturas_t_b.id_beneficiario=0 and
										coberturas_t_b.id_titular='$f_cliente[id_titular]' and 
										coberturas_t_b.id_organo<=1 
										order by propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?> <input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular</td>
		<td class="tdcampos"><?php echo $f_cliente[nombres]?> <?php echo $f_cliente[apellidos]?></td>
		<td class="tdtitulos">Cedula</td>
                <td class="tdcampos"><?php echo $f_cliente[cedula]?><input class="campos" type="hidden" name="cedula" maxlength=128 size=20 value="<?php echo $f_cliente[cedula]?>"   ></td>
	</tr>		
	 <tr>
	        <td class="tdtitulos">Telefono 1</td>
                <td class="tdcampos"><?php echo $f_cliente[telefono_hab]?></td>
                <td class="tdtitulos">Telefono 2</td>
                <td class="tdcampos"><?php echo $f_cliente[telefono_otro]?></td>
	</tr>
	<tr> 
	<td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
	</tr>
		<tr> 
	<td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_cliente[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
	</tr>


<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">

                <select id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                              <option value="<?php echo $f_cobertura[id_cobertura_t_b] ?>" > <?php echo "$f_cobertura[cualidad] Cobertura Dsiponible $f_cobertura[monto_actual] " ?></option>
                <?php
                while($f_coberturat=asignar_a($r_coberturat,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_coberturat[id_cobertura_t_b]?>"> <?php echo "$f_coberturat[cualidad]  Cobertura Dsiponible $f_coberturat[monto_actual] " ?>
                </option>
                <?php
                }
                ?>
                </select>
                </td>
        </tr>
		
		
<?php
 }

$q_clienteb=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								beneficiarios.id_beneficiario,
								beneficiarios.id_titular,
								entes.nombre
					from 
								clientes,
								estados_clientes,
								beneficiarios,
								entes,
								procesos,
								estados_t_b,
								titulares
					where
								procesos.id_proceso='$proceso' and
								procesos.id_beneficiario=beneficiarios.id_beneficiario and 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								beneficiarios.id_titular=titulares.id_titular and 
								titulares.id_ente=entes.id_ente and 
								estados_clientes.id_estado_cliente>=4");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);

if ($num_filasb==0){
}
else
{

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
echo $i;
$q_clientet=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente
					from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes
					where 
								titulares.id_titular=$f_clienteb[id_titular] and 
								clientes.id_cliente=titulares.id_cliente and
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturabe=("select 
										coberturas_t_b.id_cobertura_t_b,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,
										coberturas_t_b.id_organo
							from 
										propiedades_poliza,
										coberturas_t_b 
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and
										coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and 
										coberturas_t_b.id_organo<=1 and
										coberturas_t_b.id_titular='$f_clientet[id_titular]' 
							order by 
											propiedades_poliza.cualidad");
$r_coberturabe=ejecutar($q_coberturabe) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo "Este Cliente Tambien es Beneficiario "?><input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>


<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
        </tr>


        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="campos1"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>
		        <tr>
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_clientet[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="campos1"></td>
        </tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos" ><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select  id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                <option value="<?php echo $f_cobertura[id_cobertura_t_b]?>"> <?php echo "$f_cobertura[cualidad] $f_organo[organo]  Cobertura Dsiponible $f_cobertura[monto_actual] " ?></option>
                <?php
                while($f_coberturabe=asignar_a($r_coberturabe,NULL,PGSQL_ASSOC)){
              
                ?>
                <option value="<?php echo $f_coberturabe[id_cobertura_t_b]?>"> <?php echo "$f_coberturabe[cualidad] Cobertura Disponible  $f_coberturabe[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
              
  </td>
 

        </tr>


<?php
}
}
?>

<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>


<tr>
                <td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=2 class="tdcampos">
                <select style="width: 400px;" name="proveedor" class="campos">
                <?php $q_p=("select 
											sucursales.sucursal,
											especialidades_medicas.
											especialidad_medica,
											proveedores.id_proveedor,
											personas_proveedores.*,
											s_p_proveedores.* 
								from 
											especialidades_medicas,personas_proveedores,
											s_p_proveedores,
											proveedores,
											sucursales
								where 
											proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
											s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
											s_p_proveedores.nomina=1 and 
											s_p_proveedores.id_sucursal=sucursales.id_sucursal and 
											especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
											s_p_proveedores.activar=1 
											order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                <option value="<?php echo $f_medico[id_proveedor]?>"> <?php echo "$f_medico[nombres_prov] $f_medico[apellidos_prov] $f_medico[sucursal]
                $f_medico[id_proveedor]"?></option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>  
                <option  <?php if ($f_p[id_sucursal]==3 || $f_p[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p[id_sucursal]==7) {?> class="option1" <?php } ?>
value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] ($f_p[especialidad_medica]) $f_p[sucursal]
                $f_p[id_proveedor]"?></option>
                <?php
                }
                ?>
                </select>
                </td>
                 <td class="tdtitulos"></td>
        </tr>
		</tr>
               	<td></td>
		 <td class="tdtitulos"><a href="#" OnClick="con_paciente();" class="boton">Consultar Citas Paciente</a></td>
                <td class="tdtitulos"><a href="#" OnClick="con_medico();" class="boton">Consultar Citas Medicos</a></td>
	<td class="tdtitulos"></td>
        </tr>
<tr>
<td colspan=4>
<div id="con_medico"></div>         

</td>
</tr> 



<?php
}
else
{
if ($titular==0)
{

/* **** repita para beneficiario **** */
$q_cobertura=("select 
									gastos_t_b.id_cobertura_t_b,
									coberturas_t_b.monto_actual,
									coberturas_t_b.id_organo,
									propiedades_poliza.cualidad            
						from 
									gastos_t_b,coberturas_t_b,
									propiedades_poliza 
						where 
									gastos_t_b.id_proceso='$proceso' and 
									gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and 
									coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza");
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);


while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
$q_clientet=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								clientes.telefono_hab,
								clientes.telefono_otro,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente
					from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes
                where 
								titulares.id_titular=$f_clienteb[id_titular] and 
								clientes.id_cliente=titulares.id_cliente and 
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturab=("select 
										coberturas_t_b.id_cobertura_t_b,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,
										coberturas_t_b.id_organo 
							from
										 propiedades_poliza,
										coberturas_t_b 
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
										coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
										coberturas_t_b.id_titular='$f_clientet[id_titular]' and 
										coberturas_t_b.id_organo<=1 
							order by 
										propiedades_poliza.cualidad");
$r_coberturab=ejecutar($q_coberturab) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?><input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
        </tr>
 <tr>
	        <td class="tdtitulos">Telefono 1</td>
                <td class="tdcampos"><?php echo $f_cliente[telefono_hab]?></td>
                <td class="tdtitulos">Telefono 2</td>
                <td class="tdcampos"><?php echo $f_cliente[telefono_otro]?></td>
	</tr>

        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>
    <tr>
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_clientet[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
        </tr>
<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos"><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>
<tr>
<td class="tdtitulos">Cedula Beneficiario</td>
                <td class="tdcampos"><?php echo $f_clienteb[cedula]?><input class="campos" type="hidden" name="cedula" maxlength=128 size=20 value="<?php echo $f_clienteb[cedula]?>"   ></td>
				</tr>
 <tr>
	        <td class="tdtitulos">Telefono 1</td>
                <td class="tdcampos"><?php echo $f_clienteb[telefono_hab]?></td>
                <td class="tdtitulos">Telefono 2</td>
                <td class="tdcampos"><?php echo $f_clienteb[telefono_otro]?></td>
	</tr>


<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                <option value="<?php echo $f_cobertura[id_cobertura_t_b]?>"> <?php echo "$f_cobertura[cualidad]  Cobertura Disponible $f_cobertura[monto_actual] " ?></option>
                <?php
                while($f_coberturab=asignar_a($r_coberturab,NULL,PGSQL_ASSOC)){
              
                ?>
                <option value="<?php echo $f_coberturab[id_cobertura_t_b]?>"> <?php echo "$f_coberturab[cualidad]  Cobertura Disponible $f_coberturab[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
                </td>

	
        </tr>

<?php
}
?>
<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>

		
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=2 class="tdcampos">
		<select style="width: 400px;" name="proveedor" class="campos">
		<?php $q_p=("select 
											sucursales.sucursal,
											especialidades_medicas.
											especialidad_medica,
											proveedores.id_proveedor,
											personas_proveedores.*,
											s_p_proveedores.* 
								from 
											especialidades_medicas,personas_proveedores,
											s_p_proveedores,
											proveedores,
											sucursales
								where 
											proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
											s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
											s_p_proveedores.nomina=1 and 
											s_p_proveedores.id_sucursal=sucursales.id_sucursal and 
											especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
											s_p_proveedores.activar=1 
											order by personas_proveedores.nombres_prov");
		$r_p=ejecutar($q_p);
		?>
		<option value="<?php echo $f_medico[id_proveedor]?>"> <?php echo "$f_medico[nombres_prov] $f_medico[apellidos_prov] $f_medico[sucursal]
                $f_medico[id_proveedor]"?></option>
		<?php		
		while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){
		?>
		<option  <?php if ($f_p[id_sucursal]==3 || $f_p[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p[id_sucursal]==7) {?> class="option1" <?php } ?>
 
		value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] ($f_p[especialidad_medica]) $f_p[sucursal] 
		$f_p[id_proveedor]"?></option>
		<?php
		}
		?>
		</select>
		</td>
             
        </tr>
		
</tr>
               	<td></td>
		 <td class="tdtitulos"><a href="#" OnClick="con_paciente();" class="boton">Consultar Citas Paciente</a></td>
                <td class="tdtitulos"><a href="#" OnClick="con_medico();" class="boton">Consultar Citas Medicos</a></td>
	<td class="tdtitulos"></td>
        </tr>
<tr>
<td colspan=4>
<div id="con_medico"></div>         

</td>
</tr> 

</table>


<?php
}
}
?>

<?php
}
}
?>

