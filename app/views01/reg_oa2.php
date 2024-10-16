<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();

//header('Content-Type: text/xml; charset=ISO-8859-1');
$cedula=$_REQUEST['cedula'];
$VarloCambio=$_SESSION['valorcambiario'];
$tiposerv=$_REQUEST['tiposerv'];
$servicio=$_REQUEST['servicio'];
$tipo_cliente=$_REQUEST['tipo_cliente'];
$valorTaza=1;
list($iddelente,$elid_titu,$elid_bene)=explode("@",$_REQUEST['bus_tip_clien']);
///BUSCAR si posee una planilla en el lapso de 3 dias//Franklin monsalve 2022-08-03
//ofrece un boton para usar las planillas actiav para el usuario de 3 dias y que no estasn facturadas
			$fechaActual=date('Y-m-d');//fecha Actual
			$fechaAnterior=date('Y-m-d',strtotime("-1 days"));//2 dias antes
			//planillas que sus procesos no esten facturados

			if($tiposerv==9 || $tiposerv==7 || $tiposerv==6 || $tiposerv==5){
				$PlanilalsActivas='';
			}else{
				$sqlplanillasActivas=("
									select

											procesos.nu_planilla
									from
											 procesos,
											 titulares,
											 clientes

									where
											 nu_planilla<>'0' and
											procesos.fecha_creado  between  '$fechaAnterior' and '$fechaActual' and
											 clientes.cedula='$cedula' and
											 titulares.id_ente=$iddelente and
											 procesos.id_titular=titulares.id_titular and
											 titulares.id_cliente=clientes.id_cliente
									group by
												nu_planilla limit 3;");
			$PlanilasActivas=ejecutar($sqlplanillasActivas);
			if($num_planilals=num_filas($PlanilasActivas)>0){
					while($planilas=asignar_a($PlanilasActivas,NULL,PGSQL_ASSOC)){
						$PlanildeUsuario=$planilas['nu_planilla'];
						//consultar si la planilla tiene procesos facturados
						$sqlProcesoFActurados=("select count(*) as cantidaf from tbl_procesos_claves,procesos where procesos.id_proceso=tbl_procesos_claves.id_proceso and procesos.nu_planilla='$PlanildeUsuario';");
							$procesoFacturaPln=ejecutar($sqlProcesoFActurados);
							$CanFactProce=asignar_a($procesoFacturaPln,NULL,PGSQL_ASSOC);
							if($CanFactProce['cantidaf']<1){
									$PlanilalsActivas.="<a href='#' OnClick=(document.getElementById('numpre').value='$PlanildeUsuario') class='boton_2' title='numero de presupuestos activo para este cliente'>$PlanildeUsuario</a> ";///Planillas que el Cliente posee Actualmente
								}else{$PlanilalsActivas.='';}
					}
				}
				$PlanilalsActivas.='<br><br>';
			}
//no se encontraron planillas
//fin de busqueda de Planilas activas para el usuario


/* **** Se Verifica el Usuario **** */
///////////////CONSULTAR LA MONEDA/////
$sqlMonedasCambios=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo from tbl_monedas where tbl_monedas.id_moneda='1';");
$ModenasCambio=ejecutar($sqlMonedasCambios);
$MCambio=asignar_a($ModenasCambio,NULL,PGSQL_ASSOC);
$TMonedaBS=$MCambio[simbolo];
$TMonedaUSD='$';


$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select * from admin where id_admin='$admin'";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* ***** Ver el tipo de ente Juan Pablo ***** */

echo "<h1> : $cedula tiposerv: $tiposerv servicio: $servicio tipo_cliente: $tipo_cliente </h1>";

$vertipoente = ("select entes.id_tipo_ente from entes where id_ente=$iddelente");
$repvetipoente = ejecutar($vertipoente);
$dattipoente   = assoc_a($repvetipoente);
$elidtipoentees = $dattipoente['id_tipo_ente'];
/* ***** Fin de Ver el tipo de ente Juan Pablo ***** */
$nomostar = 0;
$paso=0;
/* ***** Comienzo la verificacion de permisologia de la cualidad de un plan que se va a activar ***** */
$q_ver_pe=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=18");
$r_ver_pe=ejecutar($q_ver_pe);
$num_filaspe=num_filas($r_ver_pe);

if ($num_filaspe==0){
						$q_ver_pe1=("select * from permisos where permisos.id_admin='$admin' and
												permisos.id_modulo=19");
						$r_ver_pe1=ejecutar($q_ver_pe1);
						$num_filaspe1=num_filas($r_ver_pe1);
						if ($num_filaspe1==0){
												$q_ver_pe2=("select * from permisos where permisos.id_admin='$admin' and
												permisos.id_modulo=20");
												$r_ver_pe2=ejecutar($q_ver_pe2);
												$num_filaspe2=num_filas($r_ver_pe2);
													if ($num_filaspe2==0){
														$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
														}
														else
														{
																$f_ver_pe2=asignar_a($r_ver_pe2);

																if ($f_ver_pe2[permiso]=='1'){
																$cualidad=" ";

																}
																else
																{
																$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
																}
															}
							}
							else
							{
									$f_ver_pe1=asignar_a($r_ver_pe1);

									if ($f_ver_pe1[permiso]=='1'){
									$cualidad="propiedades_poliza.cualidad!='PLAN EXCESO' and ";

									}
									else
									{
									$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
									}
								}

	}
	else
	{

				$f_ver_pe=asignar_a($r_ver_pe);

				if ($f_ver_pe[permiso]=='1'){
								$cualidad="(propiedades_poliza.cualidad='GASTOS AMBULATORIOS' or propiedades_poliza.cualidad='PLAN BASICO' ) and";

				}
				else
				{
				$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and ";
				}
	}
/****** Fin Comienzo la verificacion de permisologia de la cualidad de un plan que se va a activar ***** */

///HOSPITALIZACION
		if ($tiposerv==9)
{

	/* **** busco los numeros de planilla  para saber cual es la ultimo**** */
$q_numpla="select
							procesos.id_proceso,
							procesos.nu_planilla
					from
							procesos,gastos_t_b,
							admin,
							sucursales
					where
							procesos.id_proceso=gastos_t_b.id_proceso and
							gastos_t_b.id_tipo_servicio=$tiposerv and
							procesos.id_admin=admin.id_admin and
							admin.id_sucursal=sucursales.id_sucursal and
							sucursales.id_sucursal='$f_admin[id_sucursal]' and
							procesos.nu_planilla>'0'
					order by
							procesos.id_proceso
					desc limit 1;";
$r_numpla=ejecutar($q_numpla);

if(num_filas($r_numpla)==0)
{
	$numplani="$f_admin[id_ciudad]-$f_admin[id_sucursal]-1";
}
else
{
	$f_numpla=asignar_a($r_numpla);
	list($ciudad,$id_sucursal,$numplani)=explode("-",$f_numpla['nu_planilla']);
	$numplani= $f_admin[id_ciudad]."-".$f_admin[id_sucursal]."-".($numplani + 1);
}
}




if ($tipo_cliente==0){
	$tipo_clientes="and entes.id_tipo_ente<>4 and entes.id_tipo_ente<>6 and entes.id_tipo_ente<>8";
	}
	else
	{

		$tipo_clientes="and (entes.id_tipo_ente=4 or entes.id_tipo_ente=6 or entes.id_tipo_ente=8)";
		}
$fechacreado=date("Y-m-d");

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
							clientes.fecha_nacimiento,
							clientes.celular,
							clientes.telefono_hab,
							clientes.telefono_otro,
							clientes.email,
							estados_clientes.estado_cliente,
							titulares.id_titular,
							titulares.id_ente,
							entes.nombre,
							entes.fecha_inicio_contrato,
							entes.fecha_renovacion_contrato,
							entes.fecha_inicio_contratob,
							entes.fecha_renovacion_contratob,
							entes.id_tipo_ente,
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
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_titular=$elid_titu and
							titulares.id_titular=estados_t_b.id_titular and
							estados_t_b.id_beneficiario=0 and
							estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
							titulares.id_ente=entes.id_ente and
							estados_clientes.id_estado_cliente=4 and
							entes.id_ente=$iddelente and
							tbl_tipos_entes.id_tipo_ente=entes.id_tipo_ente
							$tipo_clientes");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$f_cliente=asignar_a($r_cliente) ;
$num_filas=num_filas($r_cliente);

/* **** busco si es beneficiario **** */

$q_clienteb=("select
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								clientes.fecha_nacimiento,
								clientes.celular,
								clientes.telefono_hab,
								clientes.telefono_otro,
								clientes.email,
								estados_clientes.estado_cliente,
								beneficiarios.id_beneficiario,
								beneficiarios.id_titular,
								entes.nombre,
								entes.id_ente,
								entes.id_tipo_ente
						from
								clientes,
								estados_clientes,
								beneficiarios,
								entes,
								titulares,
								estados_t_b
						where
								clientes.id_cliente=beneficiarios.id_cliente and
								beneficiarios.id_beneficiario=$elid_bene and
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
								beneficiarios.id_titular=titulares.id_titular and
								titulares.id_ente=entes.id_ente and
								estados_clientes.id_estado_cliente=4 $tipo_clientes");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);


?>

<table class="tabla_cabecera5 " border="0" cellpadding=0 cellspacing=0>
<!-- TABLA PRINCIPAL    ----->
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente"?></td></tr>
<?php
/* ***** repita para buscar al titular **** */
$i=0;
$id_tipo_ente=$f_cliente[id_tipo_ente];
$i++;
$q_coberturat=("select
										polizas.id_poliza,
										polizas.nombre_poliza,
										polizas.id_moneda,
										coberturas_t_b.id_cobertura_t_b,
										coberturas_t_b.monto_actual,
										propiedades_poliza.cualidad,
										propiedades_poliza.monto,
										coberturas_t_b.id_propiedad_poliza,
										coberturas_t_b.monto_actual,
										coberturas_t_b.id_organo
							from
										polizas,
										propiedades_poliza,
										coberturas_t_b
							where
										coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and
										$cualidad
										coberturas_t_b.id_beneficiario='$elid_bene' and
										coberturas_t_b.id_titular='$elid_titu' and
										coberturas_t_b.id_organo<=1 and
										polizas.id_poliza=propiedades_poliza.id_poliza
							order by
										propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>


<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular
 <input class="campos" type="hidden" name="proceso"   maxlength=128 size=20 value="0"   >

</td>
		<td class="tdcampos"><?php echo "$f_cliente[nombres] $f_cliente[apellidos]";?>
											<?php
												 if ($f_cliente[tipocliente]=='0'){?>
												<a class="tdcamposr"><?php echo " TOMADOR"; ?> </a>
												<?php
													}
												?></td>
		<td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
	</tr>
<tr>
	<td colspan="4">
	<div id='data-cliente'>
				<?php
				//TELEFONO
				//TELEFONO
				$tlf1=trim($f_cliente[celular]);
				$tlf2=trim($f_cliente[telefono_hab]);
				$tlf3=trim($f_cliente[telefono_otro]);
				$tlf='';
				$t=0;
				if($tlf1==''){$t++;$sp='';}else{$tlf=$tlf1;$sp=' , ';}
				if($tlf2==''){$t++;}else{$tlf.=$sp.$tlf2;$sp=' , ';}
				if($tlf3==''){$t++;}else{$tlf.=$sp.$tlf2;}
				if($tlf=='') {
					$tlf='<span class="tdcamposr"><h2>NO REGISTRADO</h2></span>';
				}else{
					$tlf="$tlf";
				}
				//CORREO
				$Email=trim($f_cliente[email]);
				if($Email=='') {
					$Email='<span class="tdcamposr"><h2>NO REGISTRADO</h2></span>';
				}

				?>
			<table class="" width='100%' border="0" cellpadding=0 cellspacing=0>
				<tr>
					<td class="tdtitulos">Número de telefono:</td>
				  <td class="tdcampos"><?php echo $tlf;?></td>
				  <td class="tdtitulos">correo electronico</td>
				  <td class="tdcampos"><?php echo $Email;?></td>
				</tr>
			</table>
		</div><!-- fin Datos de contacto del Cliente -->
	</td>
</tr>
	<tr>
		<td class="tdtitulos">Ente</td>
	  <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
	  <td class="tdtitulos">Tipo Ente</td>
	  <td class="tdcamposr"><?php echo $f_cliente[tipo_ente]?></td>
	</tr>


	<?php
	if ($num_filasb==0)
	{
		}
		else
		{
			$f_clienteb=asignar_a($r_clienteb);
	?>
<tr>
		<td class="tdtitulos">Nombres y Apellidos del Beneficiario
 <input class="campos" type="hidden" name="proceso"
                                        maxlength=128 size=20 value="0"   >

</td>
		<td class="tdcampos"><?php echo "$f_clienteb[nombres] $f_clienteb[apellidos]";?>
											</td>
		<td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_clienteb[estado_cliente]?></td>
	</tr>

	<tr>
<?php
}
?>
<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select style="width: 200px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos" Onchange="varcober();">
		<option value=""> Seleccione La Cobertura</option>
                <?php
                while($f_coberturat=asignar_a($r_coberturat,NULL,PGSQL_ASSOC)){
                    $qcualidad = $f_coberturat[cualidad];
                    $Coberturatotal = $f_coberturat[monto];//cobertura total de propiedad poliza
										$MontoMaxActExceso=$Coberturatotal*0.10; ///el plan execo se oculto cuando sea mayor al 10% del plan basico
										$MontoMinActExceso=$Coberturatotal*0.02; ///el plan execo se activara al 2% del plan basico
                    $PolizaIdMoneda = $f_coberturat[id_moneda];
                    $PolizaNombre = $f_coberturat[nombre_poliza];


 		    $montocualidad = $f_coberturat[monto_actual];
		 if(($qcualidad == 'PLAN BASICO') && ($montocualidad > $MontoMaxActExceso)){
		   $nomostar = 1;
                    $paso++;
		  }
      if(($qcualidad == 'PLAN BASICO') && ($montocualidad <= $MontoMinActExceso)){
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
    		}//WHILE
    ?>
                </select>
				<?php
				    $url="views01/igastos.php?cedula=$cedula&id_titular=$f_cliente[id_titular]&titular=$titular&fechainicio=$f_cliente[fecha_inicio_contrato]&fechafinal=$f_cliente[fecha_renovacion_contrato]&id_servicio=$servicio";
                        ?> <a href="<?php echo $url; ?>" title="Relacion de Gastos"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;" class="boton">Consultar Gastos</a>
						<?php
						if ($tiposerv==5)
							{
								  $url1="views01/iconpreven.php?cedula=$cedula&id_titular=$f_cliente[id_titular]&titular=$titular&fechainicio=$f_cliente[fecha_inicio_contrato]&fechafinal=$f_cliente[fecha_renovacion_contrato]&id_servicio=$servicio";
								?>
							<a href="<?php echo $url1; ?>" title="Relacion de Consultas Preventivas"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;" class="boton">Consultas Preventivas</a>

						<?php
							}
						?>
							<?php
						if ($tiposerv==11)
							{
								  $url2="views01/iconlentes.php?cedula=$cedula&id_titular=$f_cliente[id_titular]&titular=$titular&fechainicio=$f_cliente[fecha_inicio_contrato]&fechafinal=$f_cliente[fecha_renovacion_contrato]&id_servicio=$servicio";
								?>
							<a href="<?php echo $url2; ?>" title="Relacion de Lentes"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;" class="boton">Ordenes de Lentes</a>

						<?php
							}
							if ($servicio==1)
							{
								$url3="views01/farmaext3.php?tipoc=T&idbusq=$f_cliente[id_titular]&tipooper=TC";
						?>

						<a href="<?php echo $url3; ?>" title="Ver los tratamientos continuo" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;">Tratamiento Continuo</a>
						<?php
							}

						?>


                </td>
        </tr>
				<?php
					//COSNULTAR MONEDA DE POLIZAS
					$MonedaPoliza=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo from tbl_monedas where tbl_monedas.id_moneda='$PolizaIdMoneda';");
					$ModnedaPoli=ejecutar($MonedaPoliza);
					$MPoliza=asignar_a($ModnedaPoli,NULL,PGSQL_ASSOC);
					$MonPolizaSim=$MPoliza[moneda].'('.$MPoliza[simbolo].')';
				?>
				<tr>
					<td class="tdtitulos">PLAN</td>
					<td class="tdcamposr"><?php echo $PolizaNombre?></td>
					<td class="tdtitulos">Montos en:</td>
					<td class="tdcamposr"><?php echo $MonPolizaSim;?></td>
				</tr>

		<tr>
		<td colspan=4 class="tdcamposr" style="text-align:right;" ><?php $url="views01/mod_datos_contacto.php?cedula=$cedula&dv='data-cliente'";
								?> <a href="<?php echo $url; ?>" title="Modificar datos de Contacto"  onclick="Modalbox.show(this.href, {title: this.title, width: 600, height: 400, overlayClose: false}); return false;" class="boton">Modificar datos de contacto</td>
		</tr>
		<tr>
		<td colspan=4><hr></td>
		</tr>

<tr>
                <td colspan=1 class="tdtitulos">Seleccione el Organo</td>
<td colspan=3 class="tdcampos">
                <select  name="organo" class="campos">
                <option value="0"> Sin Organo</option>
                <?php
				 $q_organot=("select * from organos order by organo");
				 $r_organot=ejecutar($q_organot);
                while($f_organot=asignar_a($r_organot,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_organot[id_organo]?>"> <?php echo " $f_organot[organo]   " ?>
                </option>
                <?php
                }
                ?>
                </select>
                </td>
        </tr>
<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador" id="contador"></td>
        </tr>

<!-- FRANKLIN MONSALVE DATOS PARA OFERTA POR TICKET-->

			<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de ticket de Atencion al usuario"?></td></tr>
			<tr>
				<td colspan=4 class="tdtitulos">
					<span><span class="checkbox-JASoft"> <label for="ActivarTicket" title="Usar tike para consulta">Usar Ticket</label> NO<input type="radio" name="ActivarTicket"  id="ActivarTicket2" value="0" onchange="idenput=this.id;ActivaTicket=$F(idenput);idgemelo='ticket'; if(ActivaTicket==null || ActivaTicket==''){gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}else{gemelo=$(idgemelo).disabled=true;$(idgemelo).focus();}"> SI<input type="radio" name="ActivarTicket"  id="ActivarTicket" value="1" onchange="idenput=this.id;ActivaTicket=$F(idenput);idgemelo='ticket'; if(ActivaTicket==null || ActivaTicket==''){gemelo=$(idgemelo).disabled=true;$(idgemelo).focus();}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}"></span></span>
					<input class="campos" type="text" name="ticket" id="ticket" maxlength=128 size=15 value="" disabled="" onchange="reg_oa2_ticke()"> <span>iniciales del plan con el número de ticket</span>
					<div id='ConsutaTicket'></div>
				</td></tr>
<!-- GIN FRANKLIN MONSALVE DATOS PARA OFERTA POR TICKET-->

<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden "?></td></tr>
<tr>

		<td  class="tdtitulos">* Fecha de Recepcion: </td>

		<td>
 						<input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value=<?php echo $fechacreado; ?>>
						<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
						<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
    </td>
		<td class="tdtitulos">
				<?php

				///Hospitalizacion y Emergencia
						if (($servicio==6) || ($servicio==9) )
								{ $BloquearFechaEgreso="disabled='disabled' readonly";
									$valueFecha="value='1900-01-01'";
										?>
	 									* Fecha Egreso
	 									<?php
	 							}
								else
	 								{ $BloquearFechaEgreso="";
										$fechaActual=date("Y-m-d");;
										$valueFecha="value='$fechaActual' ";
										?>
										* Fecha de Cita:
								<?php		}	?>
		</td>
		<td>
 <input  type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10"  <?PHP echo "$valueFecha $BloquearFechaEgreso";?>  onkeypress="return fechasformato(event,this,1);">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>

		<?php
   ////[9]SERVICIOS CLINICOS DE EMERGENCIA, [13]SERVICIOS CLINICOS DE HOSPITALIZACION, [20]SALA DE OBSERVACION, [21]HOSPITALIZACION AMBULATORIA APS,[25]PROCEDIMIENTOS ESPECIALES
	if (($tiposerv==9) || ($tiposerv==13) || ($tiposerv==20) || ($tiposerv==21) || ($tiposerv==25)) {
	?>
<tr>
				<td class="tdtitulos">Numero Presupuesto</td>
        <td class="tdcampos"><input class="campos" type="text" id="numpre" name="numpre" maxlength=128 size=20 value="<?php echo $numplani ?>"  OnChange="verificar_planilla();"   >
                    <a href="#" title="Verificar si el numero de planilla esta registrado a otro usuario" OnClick="verificar_planilla();" class="boton">Verificar</a>
										<?php echo"<br><br>$PlanilalsActivas";?>
				</td>
				<td class="tdtitulos">Hora Ingreso</td>
        <td class="tdcampos">
							<input class="campos" type="text" name="horac" id="horac" maxlength=128 size=15 value="" >
				</td>
</tr>
<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
        <td class="tdcampos" colspan="3">
									<input class="campos" type="text" name="enfermedad" id="Cenfermedad" maxlength=128 size=20 value="">
									<input class="campos" type="hidden" name="decrip" id="Cdecrip" maxlength=128 size=20 value="<?php echo $tiposerv;?>">

								</td>
	</tr>
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
	</tr>
	<tr><td colspan='4' >
		<!-- TABLA DE LISTA DE ESTUDIOS ------------->
		<table class="colortable" border="0" width='100%'>
				<tr>
					<td  class="tdtitulos">Nombre</td>
					<td  class="tdtitulos">Descripcion / USD / PRECIO</td>
					<td  class="tdtitulos">FM	 </td>
					<td  class="tdtitulos">	Monto	</td>

				</tr>

<?php
$i=0;
$ban="";

if ($tiposerv==9)
{
$id_tipo_exam='5';
}
if ($tiposerv==13)
{
$id_tipo_exam='6';
}
if ($tiposerv==20)
{
$id_tipo_exam='9';
}
if ($tiposerv==25)
{
$id_tipo_exam='7';
}

	$q_baremoe="select
										examenes_bl.id_examen_bl,
										examenes_bl.examen_bl,
										tbl_baremos_precios.precio,
										tbl_baremos.id_moneda
								from
										examenes_bl,
										tbl_baremos_entes,
										tbl_baremos_precios,
										tbl_baremos
								where
								examen_bl<>'' and
										examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
										tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
										tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo  and
										tbl_baremos_entes.id_ente=$iddelente and
										examenes_bl.id_tipo_examen_bl=$id_tipo_exam
								order by
										examenes_bl.examen_bl";
$r_baremoe=ejecutar($q_baremoe);
$num_filase=num_filas($r_baremoe);

if ($num_filase==0){
	$num_filase2=1;
     if(($elidtipoentees == 7) || ($elidtipoentees == 2)){

	 $buscidentebare = "select entes.id_ente from entes,tbl_baremos_entes where
                                 entes.id_tipo_ente = 7 and
                                 entes.id_ente = tbl_baremos_entes.id_ente;";
         $repbuscidentebare = ejecutar($buscidentebare);
         $datdelidentbare = assoc_a($repbuscidentebare);
         $identebaremo =  $datdelidentbare['id_ente'];
	 $q_baremoe="select
		examenes_bl.id_examen_bl,
		examenes_bl.examen_bl,
	        tbl_baremos_precios.precio,
					tbl_baremos.id_moneda
	from
		examenes_bl,
		tbl_baremos_entes,
		tbl_baremos_precios,
		tbl_baremos
	where
		examen_bl<>'' and
		examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
		tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
		tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo  and
		tbl_baremos_entes.id_ente=$identebaremo and
    examenes_bl.id_tipo_examen_bl=$id_tipo_exam
	order by
		examenes_bl.examen_bl";
                $r_baremoe=ejecutar($q_baremoe);
								$num_filase2=num_filas($r_baremoe);
      }

			if(($elidtipoentees <> 7) || ($elidtipoentees <> 2 || $num_filase2==0)){
						echo $q_baremoe="select
																*
													from
																examenes_bl
													where
											examen_bl<>'' and
																examenes_bl.id_tipo_examen_bl=$id_tipo_exam
													order by
																examenes_bl.examen_bl";
						$r_baremoe=ejecutar($q_baremoe);
	        }
				}


  while($f_baremoe=asignar_a($r_baremoe,NULL,PGSQL_ASSOC)){
		///////determinar en que moneda esta expresados los montos
				$id_moneda=$f_baremoe['id_moneda'];

$i++;

?>
<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value="<?php echo $f_baremoe[examen_bl]?>"> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="descri_<?php echo $i?>" name="descri" class="campos" >
			<?php if ($tiposerv==20){ ?>

			<option value="SALA DE OBSERVACION">SALA DE OBSERVACION</option>
			<option value="MEDICAMENTOS">MEDICAMENTOS</option>
			<?php
			}
     if ($tiposerv==9){ ?>

				<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
				<option value="MEDICAMENTOS">MEDICAMENTOS</option>
				<?php
			}

			if ($tiposerv==13)
      {

				?>
				<option value="HOSPITALIZACION">HOSPITALIZACION</option>
				<option value="MEDICAMENTOS">MEDICAMENTOS</option>
				<?php
			}

			if ($tiposerv==25)
      {

					?>
					<option value="PROCEDIMIENTOS ESPECIALES">PROCEDIMIENTOS ESPECIALES</option>
					<option value="MEDICAMENTOS">MEDICAMENTOS</option>
					<?php
			}

			$q_especialidad=("select * from especialidades_medicas order by especialidades_medicas.especialidad_medica");
			$r_especialidad=ejecutar($q_especialidad);
	                while($f_especialidad=asignar_a($r_especialidad,NULL,PGSQL_ASSOC)){
			?>

			<option value="<?php echo $f_especialidad[especialidad_medica]?>"> <?php echo $f_especialidad[especialidad_medica]?></option>

			<?php
			}
			?>
		</select>
<?php	if ($num_filase==0) {
 ?>
 <?php
 if(($elidtipoentees <> 7) || ($elidtipoentees <> 2)){

   $preciobaremo= $f_baremoe[honorarios];
	 if($id_moneda==1){//baremos en bs
			 $precioBS=$preciobaremo;
			 $preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
			 $valorTaza=$VarloCambio;//usar Cambio USD * BS
	 }
	 /////POLIZA
	 if($PolizaIdMoneda==2)
	 {///si la poliza es en USD
		 $precio=$preciobaremo;
		 $TMonedaBS=$TMonedaUSD;
		 $valorTaza=1;//usar Cambio USD * USD
	 }else
	 if($PolizaIdMoneda==1)//bs poliza
	 {if($id_moneda==1){$precio=$precioBS;}
		else
		{$precio=Formato_Numeros($preciobaremo*$VarloCambio);}
		$valorTaza=$VarloCambio;//usar Cambio USD * BS
	 }

 }
 if(($elidtipoentees == 7) || ($elidtipoentees == 2)){

   $preciobaremo= $f_baremoe[precio];
	 if($id_moneda==1){//baremos en bs
			 $precioBS=$preciobaremo;
			 $preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
			 $valorTaza=$VarloCambio;//usar Cambio USD * BS
	 }
	 /////POLIZA
	 if($PolizaIdMoneda==2)
	 {///si la poliza es en USD
		 $precio=$preciobaremo;
		 $TMonedaBS=$TMonedaUSD;
		 $valorTaza=1;//usar Cambio USD * usd
	 }else
	 if($PolizaIdMoneda==1)//bs poliza
	 {if($id_moneda==1){$precio=$precioBS;}
		else
		{$precio=Formato_Numeros($preciobaremo*$VarloCambio);}
		$valorTaza=$VarloCambio;//usar Cambio USD * BS
	 }

 }
 ?>

 <input class="campos" type="text" id="cambio_<?php echo $i?>" name="cambio" maxlength=20 size=5 value="<?php echo $preciobaremo;?>"  Oninput="return validarNumero(this);" OnChange="p=$F('cambio_<?php echo $i?>');$('valor_<?php echo $i?>').value=p*<?php echo $valorTaza?>;"><?php echo $TMonedaUSD; ?>
 <input class="campos" type="text" id="valor_<?php echo $i?>" name="valor" maxlength=128 size=10 value="<?php echo $precio;?>"   > <?php echo $TMonedaBS; ?>
<?php
}
else
	{
		$preciobaremo= $f_baremoe[precio];
		if($id_moneda==1){//baremos en bs
			 	$precioBS=$preciobaremo;
				$preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
				$valorTaza=$VarloCambio;//usar Cambio USD * BS
		}
		/////POLIZA
		if($PolizaIdMoneda==2)
		{///si la poliza es en USD
			$precio=$preciobaremo;
			$TMonedaBS=$TMonedaUSD;
			$valorTaza=1;//usar Cambio USD * usd
		}else
		if($PolizaIdMoneda==1)//bs poliza
		{if($id_moneda==1){$precio=$precioBS;}
		 else
		 {$precio=Formato_Numeros($preciobaremo*$VarloCambio);}
		 $valorTaza=$VarloCambio;//usar Cambio USD * BS
		}

?>
<input class="campos" type="text" id="cambio_<?php echo $i?>" name="cambio" maxlength=20 size=5 value="<?php echo $preciobaremo;?>" Oninput="return validarNumero(this);" OnChange="p=$F('cambio_<?php echo $i?>');$('valor_<?php echo $i?>').value=p*<?php echo $valorTaza?>;"><?php echo $TMonedaUSD; ?>
     <input class="campos" type="text" pattern="[0-9]+" id="valor_<?php echo $i?>" name="valor" maxlength=128 size=10 value="<?php echo $precio;?>" ><?php echo $TMonedaBS; ?>

<?php
}
?>
		 </td>
<td  class="tdtitulos">
<input type="text" pattern="[0-9]+" name="" id="factor_<?php echo $i?>"  name="factor" class="campos" value="1" maxlength="3" size="3" required >

		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);" name="checkl" maxlength=128 size=20 value="" >

		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0" OnChange="return validarNumero(this);" >
	</td>


</tr>

                <?php
} //Fin carga de baremos

//cargra en blanco
		$p=$i + 1;
	for( $i=$p; $i<$p*2; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="descri_<?php echo $i?>" name="descri" class="campos" >

				<option value="0"> Seleccione el Tipo de Servicio</option>
				<option value="MEDICAMENTOS"> MEDICAMENTOS</option>
				<option value="INSUMOS"> INSUMOS</option>
				<?php if ($tiposerv==20){ ?>

			<option value="SALA DE OBSERVACION">SALA DE OBSERVACION</option>
			<?php
			}
            if ($tiposerv==9){ ?>

			<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
			<?php
			}
			 if ($tiposerv==13)
            {
			?>
			<option value="HOSPITALIZACION">HOSPITALIZACION</option>
			<?php
			}
			 if ($tiposerv==25)
            {
			?>
			<option value="PROCEDIMIENTOS ESPECIALES">PROCEDIMIENTOS ESPECIALES</option>
			<?php
			}
			?>


		</select>
		<input class="campos" type="text" id="cambio_<?php echo $i?>" name="cambio" maxlength=20 size=5 value="0" Oninput="return validarNumero(this);" OnChange="p=$F('cambio_<?php echo $i?>');$('valor_<?php echo $i?>').value=p*<?php echo $valorTaza?>;"><?php echo $TMonedaUSD; ?>
		<input class="campos" type="text" id="valor_<?php echo $i?>"  name="valor" maxlength=128 size=10 value="0"><?php echo $TMonedaBS; ?>
		 </td>
<td  class="tdtitulos">
	<input type="text" pattern="[0-9]+" name="factor" id="factor_<?php echo $i?>"  class="campos" value="1" maxlength="3" size="3" required >
	<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);"  name="checkl" maxlength=128 size=20 value="">

		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" >
	</td>


</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\"  id=\"conexa\" value=\"$i\">";
	?>
		</td>
	</tr>
</table>

<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos">
									<input class="campos" type="text" name="monto" maxlength=128 size=10 value=""   OnChange="return validarNumero(this);"></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">Calcular Monto</a></td>
</tr>



	<?php

	}
	//[$tiposerv][$servicio] ----------------------------------------------------------------------------------
	//[no 6][4] ORDENES DE LENTES,DEDUCIBLE,PREVENTIVAS,CONSULTAS MEDICAS,CONSULTAS MEDICAS H.M,MONTO NO APROBADO
	//[14] y [15] INGRESOS DE CUENTAS POR TERCEROS
	//[16] y [17] DISPONIBILIDAD
	//[19]INGRESOS DE CUENTAS POR TERCEROS
	//[27]MONTO NO APROBADO
	//[28]DEDUCIBLE
	if (($tiposerv<>6 and $servicio==4) || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17 || $tiposerv==19 || ($tiposerv==27) || ($tiposerv==28)) {

	if ($tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17 || $tiposerv==19 ){


	?>

	<tr>
					<td class="tdtitulos">Numero Presupuesto</td>
              <td class="tdcampos"><input class="campos" type="text" id="numpre" name="numpre"
					maxlength=128 size=20 value="0"  OnChange="verificar_planilla();"   >
                    <a href="#" title="Verificar si el numero de planilla esta registrado a otro usuario" OnClick="verificar_planilla();" class="boton">Verificar</a>
										<?php echo"<br><br>$PlanilalsActivas";?>
								</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>
	<?php
	}
	else
	{
	?>
		<tr>
					<td class="tdtitulos"></td>
              	<td class="tdcampos">
									<input class="campos" type="hidden" name="numpre"	maxlength=128 size=20 value="0"   >
					</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>

	<?php
	}
	?>

   <tr>

				<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos">
									<input class="campos" type="text" name="horac" id="horac" maxlength=128 size=20 value=""   >
								</td>
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value="0"   OnChange="return validarNumero(this);"></td>

	</tr>

	<tr>

	<?php
	
	// Deducible o monto no aprobado
	if ($tiposerv==28 || $tiposerv==27) {
		?>

		<td class="tdtitulos">* Descripcion</td>
		<td class="tdcampos">
			<input class="campos" type="text" name="decrip" maxlength=450 size=20 value="">
		</td>
		</tr>
		
		<tr>
			<td colspan=1 class="tdtitulos">* Número de planilla vinculado</td>
			<td colspan=3 class="tdcampos">
				<input type="text" name="comenope" maxlength=450 size=20 class="campos" placeholder="0-0-000000">
				<input type="hidden" name="conexa" value="0">
			</td>
		</tr>
		
		<?php
	} else {?>


	<td class="tdtitulos">* Cuadro Medico</td>
	<td class="tdcampos">
		<input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value="">
	</td>

	<td class="tdtitulos">* Descripcion</td>
	<td class="tdcampos">
		<input class="campos" type="text" name="decrip" maxlength=450 size=20 value="">
	</td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">Comentario</td>
		<td colspan=3 class="tdcampos">
			<textarea name="comenope" cols=72 rows=2 class="campos"></textarea>
			<input type="hidden" name="conexa" value="0">
		</td>
	</tr>

	<?php
	}
}
if (($tiposerv==6 and $servicio==4) || ($tiposerv==8 and $servicio==6) || ($tiposerv==12 and $servicio==9)) {
		//[6][4]CURATIVAS Y/O EXAMENES ESPECIALES 	//[8][6]CURATIVAS Y/O EXAMENES ESPECIALES	[12][9]CURATIVAS Y/O EXAMENES ESPECIALES

	if (($tiposerv==8 and $servicio==6) || ($tiposerv==12 and $servicio==9)){
	?>

	<tr>
					<td class="tdtitulos">Numero Presupuesto</td>
              	<td class="tdcampos"><input class="campos" type="text" id="numpre" name="numpre" maxlength=128 size=20 value="0"  OnChange="verificar_planilla();"   >
                    <a href="#" title="Verificar si el numero de planilla esta registrado a otro usuario" OnClick="verificar_planilla();" class="boton">Verificar</a>
										<?php echo"<br><br>$PlanilalsActivas";?>
								</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>
	<?php
	}
	else
	{
	?>
		<tr>
					<td class="tdtitulos"></td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="numpre" maxlength=128 size=20 value="0"   >
					</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>

	<?php
	}

	?>


<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Baremos "?></td></tr>
<tr>
	<tr>
		<td colspan=4 class="tdcampos">
		<hr></hr>
		</tr>
           <tr>
<td colspan=1 class="tdcampos"><a href="#" OnClick="buscarexal('<?php echo $iddelente?>','<?php echo $PolizaIdMoneda?>');" class="boton" title="Buscar Examenes de Laboratorios">* Examenes de Laboratorio</a></td>
<td colspan=1 class="tdcampos">
   <select  style="width: 190px;" name="examenes" class="campos" OnChange="buscarexae(<?php echo $iddelente?>,'<?php echo $PolizaIdMoneda?>');" class="boton" title="Buscar Examenes o Estudios Especiales">
                <option value="0">Seleccione  los Examenes Especiales</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipos_imagenologia_bi.tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_texamen[id_tipo_imagenologia_bi]?>">
		<?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>
</td>
<td colspan=1 class="tdtitulo"  >   <?php
                        $url="views01/con_morbis1.php?";
                        ?> <a href="<?php echo $url; ?>" title="Morbilidad de Proveedores Medicos"
onclick="Modalbox.show(this.href,
{title: this.title, width: 800, height: 500, overlayClose: false}); return false;" class="boton">Citas Medico</a></td>

<td colspan=1 class="tdcampos">
<?php
    $url="views01/con_morbis2.php?";
?> <a href="<?php echo $url; ?>" title="Morbilidad de Laboratorios o Otros" onclick="Modalbox.show(this.href,
{title: this.title, width: 800, height: 500, overlayClose: false}); return false;" class="boton">Citas Otros</a>

</td>
</tr>
	<tr>
		<td colspan=4 class="tdcampos">
		<hr></hr>
		</tr>

<tr>
<td colspan=4>
<div id="buscarexa"></div>

</td>
</tr>
<?php
}
?>

	<?php

	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) )
	{ ///// servicio[2]IDENTIFICACION DE IDENTIDAD,servicio[3]CARTA COMPROMISO,servicio[8] VICIOS DE RESFRACCION,servicio[11]MATERNIDAD, servicio[13]CARTA AMBULATORIA,servicio[10]REMBOLSO HCM

	$ban="checked";
	?>
	<tr>
					<td class="tdtitulos"></td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="numpre"	maxlength=128 size=20 value="0"   >
					</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>
   <tr>

				<td colspan=1 class="tdtitulos">* Honorarios Medicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_1"  name="montoh"
					maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);" ><input class="campos" style="visibility:hidden" type="checkbox"  <?php echo $ban ?> id="check_100"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos">* Gastos Clinicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_2" name="montog" maxlength=128 size=20 value="0" OnChange="return validarNumero(this);"   ><input class="campos"  style="visibility:hidden" type="checkbox"  <?php echo $ban ?>  id="check_200" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>
	<tr>

				<td colspan=1 class="tdtitulos">* Otros Gastos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox" <?php echo $ban ?>   id="check_300" name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text"   name="monto" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);" >

	</tr>
	<tr>
				<td  colspan=1  class="tdtitulos">* Numero de Proforma</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="numpro" maxlength=128 size=20 value=""   ></td>
		</tr>
	<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   ></td>
		</tr>

	<tr>

					<td  colspan=1  class="tdtitulos">* Descripcion</td>
              	<td  colspan=3  class="tdcampos"><textarea name="decrip" cols=72 rows=1 class="campos"></textarea></td>

	</tr>
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos">
									<textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos">
			<input class="campos" type="hidden" name="horac" id="horac"	maxlength=128 size=20 value=""   ></td>
									<input type="hidden" name="conexa" value="0">
	</tr>

		<tr>
	<td  class="tdtitulos"></td>
	</tr>
	<br>
	</br>


<?php
}
else
{
?>

  <tr>

				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_1" name="montoh"
					maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"   id="check_100"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   ><input class="campos"  style="visibility:hidden" type="checkbox"   id="check_200" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>
	<tr>

				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"    id="check_300"name="checkl" maxlength=128 size=20 value="">
					</td>
					</tr>
	<tr>
				<td  colspan=1  class="tdtitulos"></td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="hidden" name="numpro" maxlength=128 size=20 value="0"   ></td>
		</tr>

<?php
}
 ////////////////////SERVICIOS CIRUGIA AMBULATORIA//////////////////////
if ($servicio==14 and $tiposerv==18)
{
?>

<tr>
				<td  colspan=1  class="tdtitulos">* Numero de Presupuesto</td>
              	<td  colspan=3  class="tdcampos">
									<input class="campos" type="text" name="numpre" maxlength=128 size=15 value="0"   onkeyUp="return ValNumero(this);" >
									<input class="campos" type="hidden" name="tp" maxlength=128 size=15 value="<?php echo $tiposerv?>"   ></td>
		</tr>
<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos">
									<input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   >
									<input type="text" name="decrip" maxlength=128 size=63 value="" >
								</td>
		</tr>
	<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos">
									<input class="campos" type="hidden" name="horac" maxlength=128 size=20 value=""   ></td>


	</tr>

	<tr><td colspan="4">
		<!-----------------TABLA DE RESULTADOS------------------------>

		<table class="tabla_cabecera5 colortable" border="0" cellpadding=0 cellspacing=0>


	<tr>
		<td colspan=2 class="tdtitulos">Concepto</td>
		<td  class="tdtitulos">Descripcion</td>
		<td  class="tdtitulos">Monto</td>
</tr>
	<?php
	$i=4;
$ban="";
	for( $i=4; $i<24; $i++){

	?>
	<tr>
		<td  colspan=2 class="tdtitulos">
		<select style="width: 300px;" id="nombre_<?php echo $i?>" name="nombre" class="campos" >
				<option value="Seleccione el Tipo de Concepto"> Seleccione el Tipo de Concepto</option>
				<option value="SERVICIO DE ADMINISTRACION"> SERVICIO DE ADMINISTRACION</option>
				<option value="SERVICIO DE HOSPITALIZACIÓN"> SERVICIO DE HOSPITALIZACIÓN</option>
				<option value="SERVICIO DE QUIRÓFANO"> SERVICIO DE QUIRÓFANO</option>
				<option value="INSUMOS MÉDICOS">INSUMOS MÉDICOS</option>
				<option value="HONORARIOS PROFESIONALES"> HONORARIOS PROFESIONALES</option>
				<option value="MEDICAMENTOS"> MEDICAMENTOS</option>
				<option value="MATERIALES"> MATERIALES</option>
				<option value="MATERIALES FUNGIBLES Y DESCARTABLE"> MATERIALES FUNGIBLES Y DESCARTABLE</option>
				<option value="EQUIPOS MEDICOS"> EQUIPOS MEDICOS</option>
				<option value="USO DE SALA"> USO DE SALA</option>
				<option value="PAGOS A TERCEROS"> PAGOS A TERCEROS</option>
			</select> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
			<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"   OnChange="return validarNumero(this);">
			<input class="campos" type="hidden" id="tiposerv_<?php echo $i?>" name="tiposerv"  maxlength=128 size=10 value="18">
			<input class="campos" type="hidden" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value="">
			<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">
		</td>

<td colspan=1 class="tdtitulos"><input class="campos" type="hidden" id="tc_<?php echo $i?>" name="tc" maxlength=128 size=4 value="0">
<input class="campos" type="hidden" id="fechaci_<?php echo $i?>" name="fechaci" maxlength=128 size=4 value="1900-01-01">
<input class="campos" type="hidden" id="fechacf_<?php echo $i?>" name="fechacf" maxlength=128 size=4 value="1900-01-01">
<input class="campos" type="hidden" id="dd_<?php echo $i?>" name="dd" maxlength=128 size=4 value="">
<input class="campos" type="hidden" id="t_<?php echo $i?>" name="t" maxlength=128 size=4 value="">
</td>
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" ></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>
<br>
</br>
<tr>
	<td class="tdtitulos"><input class="campos" type="hidden" id="id_proveedor" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardarra('reg_oa2');" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>
</table>

</td></tr>





<?php
}
else
{
if ($servicio==1)   ///REMBOLSOS-Ambulatorios
{
?>

<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos">
									<input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   >
									<input class="campos" type="hidden" name="numpre" maxlength=128 size=15 value="0"   >
									<input class="campos" type="hidden" name="tp" maxlength=128 size=15 value="0"   >
								</td>
		</tr>
	<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos">
									<textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos"
									<input class="campos" type="hidden" name="horac" maxlength=128 size=20 value=""   ></td>
	</tr>
	<tr>
<td colspan=4 class="tdtitulos"><hr></td>
</tr>
	<tr>
		<td  class="tdtitulos">Nombre
	 </td>
<td  class="tdtitulos">Descripcion
		 </td>
		<td  class="tdtitulos">Factura
		 </td>

		<td  colspan=1 class="tdtitulos">
		Monto      y      Tipo Servicio
		</td>
</tr>
	<?php
	$i=4;
$ban="";
	for( $i=4; $i<24; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
	<td  class="tdtitulos">
		<input class="campos" type="text" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);"  >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value="">
		<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=1 order by tipos_servicios.tipo_servicio");
		$r_tservicio=ejecutar($q_tservicio);
		?>
				<option value=""> Seleccione el Tipo de Servicio</option>
				<?php
		while($f_tservicio=asignar_a($r_tservicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_tservicio[id_tipo_servicio]?>"> <?php echo "$f_tservicio[tipo_servicio]"?>			</option>
		<?php
		}
		?>
		</select>

		</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">Es tto Continuo<select style="width: 40px;" id="tc_<?php echo $i?>" name="tc" class="campos" >

				<option value="0"> No</option>
				<option value="on"> Si</option>

		</select></td>
<td colspan=1 class="tdtitulos">F I<input readonly type="text" size="10" id="fechaci_<?php echo $i?>" name="fechaci" class="campos" maxlength="10" value="1963-01-01">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechaci_<?php echo $i?>', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos">F F<input readonly type="text" size="10" id="fechacf_<?php echo $i?>" name="fechacf" class="campos" maxlength="10" value="1963-01-01">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechacf_<?php echo $i?>', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos"> D D <input class="campos" type="text" id="dd_<?php echo $i?>" name="dd" maxlength=128 size=4 value=""> T <input class="campos" type="text" id="t_<?php echo $i?>" name="t" maxlength=128 size=4 value="">  <a href="javascript: cal(this);" class="boton">      Cal</a></td>
</tr>
<tr>
<td colspan=4 class="tdtitulos"><hr></td>
</tr>
<tr>
<td colspan=4 class="tdtitulos"><hr></td>
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);"></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">Calcular Monto</a></td>
</tr>
<br>
</br>
<tr>
	<td class="tdtitulos"><input class="campos" type="hidden"  id="id_proveedor" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardarra('reg_oa2');" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>


<?php
}
else
{
	if (($servicio==6 && $tiposerv==9 ) || ($servicio==9 && $tiposerv==13) || ($servicio==6 && $tiposerv==20 )  || ($servicio==9 && $tiposerv==21) ){
	?>
	<tr>
	<td colspan=4 class="tdtitulos">


<tr>
		<td class="tdtitulos"><input class="campos" type="hidden" id="id_proveedor" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td class="tdtitulos"></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardareme('reg_oa2');" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>

<?php
}
else
{
 if(($tiposerv<27) || ($tiposerv==25 )){
?>
<tr>
<td colspan=4 class="tdtitulos">

 	<label for="TProveedores"> &iquest;Mostrar Proveedores?</label><br>
	<input type="radio" id="cproveedor-intramural" name="clasificaproveedor" value="1" checked><label for="CProveedores">Intramurales</label>
 	<input type="radio" id="cproveedor-extramural" name="clasificaproveedor" value="2"> <label for="CProveedores">Extramural</label>
  <input type="radio" id="cproveedor-todos" name="clasificaproveedor" value="3"> <label for="CProveedores">Todos</label>
 <br><br><br>
 </td>
</tr>
<tr>
<td colspan=2 class="tdtitulos"><a href="#" id='Proveedor-Persona1' OnClick="buscarprovp();" class="boton">* Seleccione  Proveedor Persona.</a></td>
<td colspan=2 class="tdtitulos"><a href="#" id='Proveedor-Clinica1' OnClick="buscarprovc();" class="boton">* Seleccione  Proveedor Clinica.</a></td>
</tr>
<tr>
<td colspan=4>
<div id="buscarprovp"></div>



<?php
}else{?>

       <td  class="tdtitulos"><a href="#" OnClick="MontonoApro();" class="boton">Guardar</a></td>

       <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />

	<?php }
}
}
}
?>
