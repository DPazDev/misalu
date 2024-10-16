<?php
include ("../../lib/jfunciones.php");
sesion();

$cedula=$_REQUEST['cedula'];
$especialidad=$_REQUEST['especialidad'];
$tipo_cliente=$_REQUEST['tipo_cliente'];

if ($tipo_cliente==0){
	$tipo_clientes="and entes.id_tipo_ente<>4 and entes.id_tipo_ente<>6 and entes.id_tipo_ente<>8";
	}
	else
	{

		$tipo_clientes="and (entes.id_tipo_ente=4 or entes.id_tipo_ente=6  or entes.id_tipo_ente=8)";
		}
if ($especialidad==0){
	$especialidad1 ="and especialidades_medicas.id_especialidad_medica>0";
	}
	else
	{
		$especialidad1 ="and especialidades_medicas.id_especialidad_medica=$especialidad";
		}
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

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
							tbl_tipos_entes.tipo_ente,
							titulares.tipocliente
					from 
							clientes,
							titulares,
							estados_t_b,
							estados_clientes,
							entes,
							tbl_tipos_entes
                where 
							clientes.cedula='$cedula' and 
							clientes.id_cliente=titulares.id_cliente and 
							titulares.id_titular=estados_t_b.id_titular and 
							estados_t_b.id_beneficiario='0' and 
							estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
							titulares.id_ente=entes.id_ente and 
							estados_clientes.id_estado_cliente='4'  and
							entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente
							$tipo_clientes");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;
$nomostar = 0;
$paso=0;
/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select 
								clientes.id_cliente,
								clientes.telefono_hab,
								clientes.telefono_otro,
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
								estados_t_b,
								titulares  
						where 
								clientes.cedula='$cedula' and 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								beneficiarios.id_titular=titulares.id_titular and 
								titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente=4 
								$tipo_clientes");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){
    
    $q_clien_t=("select
                                clientes.nombres,
                                clientes.apellidos,
                                entes.nombre,
                                tbl_tipos_entes.tipo_ente,
                                estados_clientes.estado_cliente
                        from 
                                clientes,
                                titulares,
                                entes,
                                tbl_tipos_entes,
                                estados_clientes,
                                estados_t_b
                        where
                                clientes.cedula='$cedula' and 
                                clientes.id_cliente=titulares.id_cliente and 
                                titulares.id_ente=entes.id_ente and 
                                entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                titulares.id_titular=estados_t_b.id_titular and
                                estados_t_b.id_beneficiario=0 and
                                estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente");
$r_clien_t=ejecutar($q_clien_t);
$num_filast=num_filas($r_clien_t);

    $q_clien_b=("select
                                clientes.nombres,
                                clientes.apellidos,
                                entes.nombre,
                                tbl_tipos_entes.tipo_ente,
                                estados_clientes.estado_cliente
                        from 
                                clientes,
                                titulares,
                                beneficiarios,
                                entes,
                                tbl_tipos_entes,
                                estados_clientes,
                                estados_t_b
                        where
                                clientes.cedula='$cedula' and 
                                clientes.id_cliente=beneficiarios.id_cliente and 
                                beneficiarios.id_titular=titulares.id_titular and 
                                titulares.id_ente=entes.id_ente and 
                                entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                                estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente");
$r_clien_b=ejecutar($q_clien_b);
$num_filasbb=num_filas($r_clien_b);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>


      
<?php
if ($num_filast==0 and $num_filasbb==0){
    ?>
    <tr> <td colspan=4 class="titulo_seccion">
El Numero de Cedula no existe </td>
	</tr>
    <?php
    }
    else
    {
        ?>
		<tr> <td colspan=4 class="titulo_seccion">
El Usuario se Encuentra Registrado es en otro tipo de Cliente</td>
		</tr>
      	<tr>
        <td class="tdtitulos" colspan=1 ></td>
		<td class="tdtitulos" colspan=1 >Estado</td>
		<td class="tdtitulos" colspan=1 >Ente</td>
		<td class="tdtitulos" colspan=1 >Tipo de Ente</td>
		</tr>
<?php
        while($f_clien_t=asignar_a($r_clien_t,NULL,PGSQL_ASSOC)){
?>
	<tr>
    	<td class="tdcampos" colspan=1 >Titular</td>
    	<td class="tdcamposr" colspan=1 ><?php echo  $f_clien_t[estado_cliente] ?></td>
		<td class="tdcampos" colspan=1 ><?php echo  $f_clien_t[nombre] ?></td>
		<td class="tdcampos" colspan=1 ><?php echo  $f_clien_t[tipo_ente] ?></td>
	</tr>
    
    <?php
    }
            
        while($f_clien_b=asignar_a($r_clien_b,NULL,PGSQL_ASSOC)){
?>
	<tr>
    	<td class="tdcampos" colspan=1 >Beneficiario</td>
    	<td class="tdcamposr" colspan=1 ><?php echo  $f_clien_b[estado_cliente] ?></td>
		<td class="tdcampos" colspan=1 ><?php echo  $f_clien_b[nombre] ?></td>
		<td class="tdcampos" colspan=1 ><?php echo  $f_clien_b[tipo_ente] ?></td>
	</tr>
    
    
    <?php
    }
    }
    ?>
</table>
<?php
}
else
{
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
									coberturas_t_b.id_beneficiario='0' and
									coberturas_t_b.id_titular='$f_cliente[id_titular]'  and 
									coberturas_t_b.id_organo<=1 and 
            (propiedades_poliza.cualidad='GASTOS AMBULATORIOS' or propiedades_poliza.cualidad='PLAN BASICO' or propiedades_poliza.cualidad='PLAN EXCESO')
							order by 
									propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?></td></tr>

<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular</td>
		<td class="tdcampos"><?php echo "$f_cliente[nombres] $f_cliente[apellidos]";?>
											<?php
												 if ($f_cliente[tipocliente]=='0'){?>
												<a class="tdcamposr"><?php echo " TOMADOR"; ?> </a>
												<?php 
													}
												?></td>
		<td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
	</tr>	
	<tr>
		<td class="tdtitulos">Tipo Ente</td>
		<td class="tdcamposr"><?php echo $f_cliente[tipo_ente]?></td>
		<td class="tdtitulos"></td>
                <td class="tdcampos"></td>
	</tr>
<?php if ($i==1) {?>	
	 <tr>
                <td class="tdtitulos">Telefono 1</td>
                <td class="tdcampos"><input class="campos" name="tlf1" value="<?php echo $f_cliente[telefono_hab]?>"   onkeyUp="return ValNumero(this);" ></td>
		 <td class="tdtitulos">Telefono 2</td>
                <td class="tdcampos"><input class="campos" name="tlf2" value="<?php echo $f_cliente[telefono_otro]?>"   onkeyUp="return ValNumero(this);" ></td>

        </tr>
<?php }?>
	<tr> 
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
  <td class="tdtitulos"></td>
                <td class="tdcamposr">
		</td>
	</tr>




<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura.</td>
<td colspan=3 class="tdcampos">

                <select style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                              <option value="" > Seleccione una Cobertura</option>
                <?php
                while($f_coberturat=asignar_a($r_coberturat,NULL,PGSQL_ASSOC)){
                     $qcualidad = $f_coberturat[cualidad];
 		     $montocualidad = $f_coberturat[monto_actual];
		if(($qcualidad == 'PLAN BASICO') && ($montocualidad > '1500')){
		  $nomostar = 1;
                  $paso++;
		}
               if(($qcualidad == 'PLAN BASICO') && ($montocualidad <= '500')){
	         $nomostar = 0;
                 $paso++; 
		}
                if($paso>=2){
                   $nomostar = 0; 
                 } 
                if(($nomostar == 1 ) &&($qcualidad == 'PLAN EXCESO')){
 		 $f_coberturat[cualidad] = '';
 		 $f_coberturat[monto_actual] = '';
		 $f_coberturat[id_cobertura_t_b] = 0;
		 $mensajecober="";
		}else{
		 $mensajecober="Cobertura Disponible";
		}
                ?>
                <option value="<?php echo $f_coberturat[id_cobertura_t_b]?>"> <?php echo "$f_coberturat[cualidad]  $mensajecober $f_coberturat[monto_actual] " ?>
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
								titulares,
								estados_t_b  
					where
								clientes.cedula='$cedula' and 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 		
								beneficiarios.id_titular=titulares.id_titular and 
								titulares.id_ente=entes.id_ente and 
								estados_clientes.id_estado_cliente=4 $tipo_clientes");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);

if ($num_filasb==0){
}
else
{

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
	
$q_clientet=("select 
							clientes.id_cliente,
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							estados_clientes.estado_cliente,
							titulares.id_titular,
							titulares.id_ente,
							entes.nombre,
							tbl_tipos_entes.tipo_ente,
							titulares.tipocliente
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
							tbl_tipos_entes.id_tipo_ente=entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturabe=("select 
										coberturas_t_b.id_cobertura_t_b,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,coberturas_t_b.id_organo
							from 
										propiedades_poliza,
										coberturas_t_b 
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
										coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
										coberturas_t_b.id_titular='$f_clientet[id_titular]' and 
										coberturas_t_b.id_organo<=1 and 
(propiedades_poliza.cualidad='GASTOS AMBULATORIOS' or propiedades_poliza.cualidad='PLAN BASICO' or propiedades_poliza.cualidad='PLAN EXCESO')
							order by 
										propiedades_poliza.cualidad");
$r_coberturabe=ejecutar($q_coberturabe) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo "Este Cliente Tambien es Beneficiario "?></td></tr>


<tr>
                <td class="tdtitulos">Nombres y Apellidos del Titular</td>
                <td class="tdcampos"><?php echo "$f_clientet[nombres] $f_clientet[apellidos]";?>
											<?php
												 if ($f_clientet[tipocliente]=='0'){?>
												<a class="tdcamposr"><?php echo " TOMADOR"; ?> </a>
												<?php 
													}
												?></td>
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
                <td class="tdcampos"></td>
	</tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos" ><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura.</td>
<td colspan=3 class="tdcampos">
             
                <select  style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
				  <option value="" > Seleccione una Cobertura</option>
                           <?php
                while($f_coberturabe=asignar_a($r_coberturabe,NULL,PGSQL_ASSOC)){
                $q_organob=("select * from organos where organos.id_organo=$f_coberturabe[id_organo]");
                $r_organob=ejecutar($q_organob);
                $f_organob=asignar_a($r_organob);
                ?>
                <option value="<?php echo $f_coberturabe[id_cobertura_t_b]?>"> <?php echo "$f_coberturabe[cualidad] $f_organob[organo] Cobertura Disponible  $f_coberturabe[monto_actual] " ?>
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
													sucursales.id_sucursal,
													sucursales.sucursal,
													especialidades_medicas.especialidad_medica,
													proveedores.id_proveedor,
													personas_proveedores.*,
													s_p_proveedores.* 
										from 
													especialidades_medicas,
													personas_proveedores,
													s_p_proveedores,
													proveedores,sucursales 
										where 
													proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
													s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
													s_p_proveedores.nomina=1  and
													s_p_proveedores.id_sucursal=sucursales.id_sucursal and                 		
													especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
													s_p_proveedores.activar=1 
													$especialidad1 
										order by 
													personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                <option  value=""> Seleccione el Tipo</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>  
                <option <?php if ($f_p[id_sucursal]==3 || $f_p[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p[id_sucursal]==7) {?> class="option1" <?php } ?> 
value="<?php echo $f_p[id_proveedor]?>"> 
<?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] ($f_p[especialidad_medica]) $f_p[sucursal] 
                $f_p[id_proveedor]"?></option>
                <?php
                }
                ?>
                </select>
          </td>

</tr>


<tr>
                <td colspan=1 class="tdtitulos">* Seleccione Doctor(a) de la Referencia.</td>
<td colspan=2 class="tdcampos">
                <select style="width: 400px;" name="proveedor_ref" class="campos">
<?php $q_p_ref=("select 
													sucursales.id_sucursal,													sucursales.sucursal,													especialidades_medicas.especialidad_medica,													(proveedores.id_proveedor) AS n,													personas_proveedores.*,	s_p_proveedores.* from 									especialidades_medicas,													personas_proveedores,													s_p_proveedores,proveedores,sucursales 	where 								proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and			s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1  and				s_p_proveedores.id_sucursal=sucursales.id_sucursal and				especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
s_p_proveedores.activar=1 
order by personas_proveedores.nombres_prov");
                $r_p_ref=ejecutar($q_p_ref);
                ?>
<option  value=""> Seleccione el Tipo</option>
                <option value="-1">DOCTORES EXTRAMURALES</option>
                <?php
                while($f_p_ref=asignar_a($r_p_ref,NULL,PGSQL_ASSOC)){
                ?>  

                <option <?php if ($f_p_ref[id_sucursal]==3 || $f_p_ref[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p_ref[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p_ref[id_sucursal]==7) {?> class="option1" <?php } ?>
 value="<?php echo $f_p_ref[n]?>"> 
<?php echo "$f_p_ref[nombres_prov] $f_p_ref[apellidos_prov] ($f_p_ref[especialidad_medica]) $f_p_ref[sucursal] 
                $f_p_ref[n]"?></option>
                <?php
                }
                ?>
                </select>
          </td>

</tr>



<tr>        
<td></td>
                 <td class="tdtitulos"><a href="#" OnClick="con_paciente();" class="boton">Consultar Citas Paciente</a></td>
                <td class="tdtitulos"><input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="0"  ><a href="#" OnClick="con_medico();" class="boton">Consultar Citas Medicos</a></td>
                <td></td>
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

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;

$q_clientet=("select 
							clientes.id_cliente,
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							estados_clientes.estado_cliente,
							titulares.id_titular,
							titulares.id_ente,
							entes.nombre,
							tbl_tipos_entes.tipo_ente,
							titulares.tipocliente
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
							tbl_tipos_entes.id_tipo_ente=entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturab=("select 
										coberturas_t_b.id_cobertura_t_b,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,
										coberturas_t_b.id_organo 
							from 
										propiedades_poliza,coberturas_t_b 
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
										coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
										coberturas_t_b.id_titular='$f_clientet[id_titular]' and 
										coberturas_t_b.id_organo<=1 and 
(propiedades_poliza.cualidad='GASTOS AMBULATORIOS' or propiedades_poliza.cualidad='PLAN BASICO' or propiedades_poliza.cualidad='PLAN EXCESO')
							order by 
										propiedades_poliza.cualidad");
$r_coberturab=ejecutar($q_coberturab) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo "$f_clientet[nombres] $f_clientet[apellidos]";?>
											<?php
												 if ($f_clientet[tipocliente]=='0'){?>
												<a class="tdcamposr"><?php echo " TOMADOR"; ?> </a>
												<?php 
													}
												?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
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
                <td class="tdcampos"></td>
	</tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos"><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>
		
		 <tr>
                <td class="tdtitulos">Telefono 1</td>
                <td class="tdcampos"><input class="campos" name="tlf1" value="<?php echo $f_clienteb[telefono_hab]?>"   onkeyUp="return ValNumero(this);" ></td>
		 <td class="tdtitulos">Telefono 2</td>
                <td class="tdcampos"><input class="campos" name="tlf2" value="<?php echo $f_clienteb[telefono_otro]?>"    onkeyUp="return ValNumero(this);" ></td>

        </tr>



<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura.</td>
<td colspan=3 class="tdcampos">
                <select style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
               <option value="" > Seleccione una Cobertura</option>
                <?php
while($f_coberturab=asignar_a($r_coberturab,NULL,PGSQL_ASSOC)){
        $qcualidad = $f_coberturab[cualidad];
        $montocualidad = $f_coberturab[monto_actual];

	if(($qcualidad == 'PLAN BASICO') && ($montocualidad > '1500')){
		  $nomostar = 1;
                  $paso++;
		}
               if(($qcualidad == 'PLAN BASICO') && ($montocualidad <= '500')){
	         $nomostar = 0;
                 $paso++; 
		}
                if($paso>=2){
                   $nomostar = 0; 
                 } 

                if(($nomostar == 1 ) &&($qcualidad == 'PLAN EXCESO')){
 		 $f_coberturab[cualidad] = '';
 		 $f_coberturab[monto_actual] = '';
		 $f_coberturab[id_cobertura_t_b] = 0;
		 $mensajecober="";
		}else{
		 $mensajecober="Cobertura Disponible";
		}
                ?>
                <option value="<?php echo $f_coberturab[id_cobertura_t_b]?>"> <?php echo "$f_coberturab[cualidad]  $mensajecober $f_coberturab[monto_actual] " ?>
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
											especialidades_medicas.especialidad_medica,
											proveedores.id_proveedor,
											personas_proveedores.*,
											s_p_proveedores.* 
								from 
											especialidades_medicas,
											personas_proveedores,
											s_p_proveedores,
											proveedores,
											sucursales 
								where 
											proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
											s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
											s_p_proveedores.nomina=1  and
											s_p_proveedores.id_sucursal=sucursales.id_sucursal and                 		
											especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
											s_p_proveedores.activar=1 
											$especialidad1
								order by 
											personas_proveedores.nombres_prov");
		$r_p=ejecutar($q_p);
		?>
		<option value=""> Seleccione el Tipo</option>
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


<tr>
                <td colspan=1 class="tdtitulos">* Seleccione Doctor(a) de la Referencia.</td>
<td colspan=2 class="tdcampos">
                <select style="width: 400px;" name="proveedor_ref" class="campos">
<?php $q_p_ref=("select 
													sucursales.id_sucursal,													sucursales.sucursal,													especialidades_medicas.especialidad_medica,													(proveedores.id_proveedor) AS n,													personas_proveedores.*,	s_p_proveedores.* from 									especialidades_medicas,													personas_proveedores,													s_p_proveedores,proveedores,sucursales 	where 								proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and			s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1  and				s_p_proveedores.id_sucursal=sucursales.id_sucursal and				especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
s_p_proveedores.activar=1 
order by personas_proveedores.nombres_prov");
                $r_p_ref=ejecutar($q_p_ref);
                ?>
<option  value=""> Seleccione el Tipo</option>
                <option value="-1">DOCTORES EXTRAMURALES</option>
                <?php
                while($f_p_ref=asignar_a($r_p_ref,NULL,PGSQL_ASSOC)){
                ?>  

                <option <?php if ($f_p_ref[id_sucursal]==3 || $f_p_ref[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p_ref[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p_ref[id_sucursal]==7) {?> class="option1" <?php } ?>
 value="<?php echo $f_p_ref[n]?>"> 
<?php echo "$f_p_ref[nombres_prov] $f_p_ref[apellidos_prov] ($f_p_ref[especialidad_medica]) $f_p_ref[sucursal] 
                $f_p_ref[n]"?></option>
                <?php
                }
                ?>
                </select>
          </td>

</tr>



<tr>        
<td></td>
                 <td class="tdtitulos"><a href="#" OnClick="con_paciente();" class="boton">Consultar Citas Paciente</a></td>
                <td class="tdtitulos"><input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="0"  ><a href="#" OnClick="con_medico();" class="boton">Consultar Citas Medicos</a></td>
                <td></td>
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
?>

