<?php
include ("../../lib/jfunciones.php");
$id_cobertura=strtoupper($_REQUEST['id_cobertura']);
$formp=strtoupper($_REQUEST['formp']);
$monto=Formato_Numeros(strtoupper($_REQUEST['monto']));
$id_proveedor=strtoupper($_REQUEST['id_proveedor']);
$fechare=$_REQUEST['fechare'];
$fecharci=$_REQUEST['fecharci'];
$VarloCambio=$_SESSION['valorcambiario'];
$horac=strtoupper($_REQUEST['horac']);
$enfermedad=strtoupper($_REQUEST['enfermedad']);
$decrip=strtoupper($_REQUEST['decrip']);
$comenope=strtoupper($_REQUEST['comenope']);
$servicio=$_REQUEST['servicio'];
$tiposerv=$_REQUEST['tiposerv'];
$contador=$_REQUEST['contador'];
$conexa=$_REQUEST['conexa'];
$examen1=$_REQUEST['examen1'];
$idexamen1=$_REQUEST['idexamen1'];
$honorarios1=$_REQUEST['honorarios1'];
$coment1=$_REQUEST['coment1'];
$examenes=$_REQUEST['examenes'];
$proceso=$_REQUEST['proceso'];
$montoh=Formato_Numeros($_REQUEST['montoh']);
$montog=Formato_Numeros($_REQUEST['montog']);
$montoo=Formato_Numeros($_REQUEST['montoo']);
$numpro=Formato_Numeros($_REQUEST['numpro']);
$descri1=strtoupper($_REQUEST['descri1']);
$tiposerv1=$_REQUEST['tiposerv1'];
$nombre1=strtoupper($_REQUEST['nombre1']);
$factura1=strtoupper($_REQUEST['factura1']);
$factor1=$_REQUEST['factor1'];
$organo=$_REQUEST['organo'];
$edoproceso=$_REQUEST['edoproceso'];
$numpre=$_REQUEST['numpre'];
$donativo=$_REQUEST['donativo'];
$t1=$_REQUEST['t1'];
$fechacf1=$_REQUEST['fechacf1'];
$fechaci1=$_REQUEST['fechaci1'];
$tc1=$_REQUEST['tc1'];
$tp=$_REQUEST['tp'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];
$fecha_privada="1900-01-01";
$codigot=time();
$codigo=$admin . $codigot;
//campos de activar tickera

$ActivarTicke=$_REQUEST['ActivarTicke'];
$id_tickera=$_REQUEST['idTickera'];
				if($ActivarTicke=='1'){
					$ActivaActualizaCovertura=false;//SE USARA EL TIKECT Y NO SE AFECTA LA COVERTURA
				}else{//SE ACTUALIZARA LA covertura DEL CLIENTE
					$ActivaActualizaCovertura=true;
				}


//echo "<h1> proceso $proceso presupuesto $numpre p: $id_proveedor -- srv: $servicio --- tipSer: $tiposerv</h1><br>";

$q_cobertura="select entes.*,titulares.*,coberturas_t_b.*,polizas.id_moneda
	from
			entes,titulares,coberturas_t_b,propiedades_poliza,polizas
	where
			entes.id_ente=titulares.id_ente and titulares.id_titular=coberturas_t_b.id_titular and coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and propiedades_poliza.id_poliza =polizas.id_poliza and coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
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


	////////////////////consultar proveedor/////////////////////////
	///Montos y porcentaje/////
	$montorecerva=0;
	if($montoh<1){
					$proveedordatosPagos="
					select
					proveedores.id_proveedor,
					proveedores.id_s_p_proveedor,
					proveedores.id_clinica_proveedor,
					proveedores.tipo_proveedor,
					(select
					(id_servicio_proveedor || '-' || monto_servicio_c || '-' || tipo_monto_c || '-' || id_moneda) as datoservicio
					from
					s_c_proveedores,clinicas_proveedores where s_c_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and s_c_proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor limit 1) as dataservi1,
					(select (id_servicio_proveedor || '-'  || monto_servicio_p || '-'  || tipo_monto_p || '-'  || id_moneda) as datoservicio2 from s_p_proveedores where s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor limit 1)as dataservi2
					from
					proveedores
					where
					proveedores.id_proveedor='$id_proveedor';";

					$prov_datos_serv=ejecutar($proveedordatosPagos);
					$ProvServicios=asignar_a($prov_datos_serv);
					if($ProvServicios[id_s_p_proveedor]>'0'){
						$dserv= explode("-", $ProvServicios['dataservi2']);
						$id_servico_pr=$dserv[0];
						$monto_servicio=$dserv[1];
						$tipo_monto=$dserv[2];
						$id_monedaser=$dserv[3];

					}
					else if($ProvServicios[id_clinica_proveedor]>'0'){
						$dserv= explode("-", $ProvServicios['dataservi1']);
						$id_servico_pr=$dserv[0];
						$monto_servicio=$dserv[1];
						$tipo_monto=$dserv[2];
						$id_monedaser=$dserv[3];
					}else{
						$id_servico_pr=1;
						$monto_servicio=0;
						$tipo_monto=1;
						$id_monedaser=1;
					}

					///cambio para la poliza
					$MonedaPoliza=$f_cobertura[id_moneda];
					///cosulto el cambio de la moneda si el id moneda es en usd y si la poliza esta expresada en USD
					if($MonedaPoliza=='2')
					{///la poliza es en usd pasar todos los monto a la moneda PRINCIPAL bs
						$montoBS=$VarloCambio*$monto;
					}
					else{$montoBS=$monto;}
					////Monto Reserva para los proveedores segun porcentaje
					///$tipo_monto=0 porcentaje Si $tipo_monto=1 //monto fijo se usa la moneda
					if($tipo_monto=='0'){
						$porcenta=$monto_servicio/100;
						$montoTerceros=$montoBS*$porcenta;
						///considerar si es en usd o bs
					}

					if($tipo_monto=='1'){///monto fijo
						//moto esta bs usd segun la poliza
						if($id_monedaser>'1')
								{$montoServBS=$monto_servicio*$VarloCambio;}
						else
								{$montoServBS=$monto_servicio;}
						$montoTerceros=$montoServBS;
						///considerar si es en usd o bs
						}
							$montoTerceros=Formato_Numeros($montoTerceros);
						}else{$montoTerceros=$montoh; $montorecerva=$montoh;}


/* **** verificar numero de presupuesto o planilla **** */
if ($numpre>0){
$q_cobertura="select * from coberturas_t_b where coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);
/* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */
$q_cliente=("select             procesos.id_proceso,
                                procesos.id_titular,
                                procesos.id_beneficiario,
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.cedula,
                                admin.nombres as nomadmin,
                                admin.apellidos as   apeadmin,
                                entes.nombre
                        from
                                procesos,
                                titulares,
                                clientes,
                                admin,
                                entes
                        where
                                procesos.nu_planilla='$numpre' and
                                procesos.id_titular=titulares.id_titular and
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente);

while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
				if (($f_cliente[id_titular]==$f_cobertura[id_titular]) and ($f_cliente[id_beneficiario]==$f_cobertura[id_beneficiario]))
            {   }
                else
                {$malregistro++;}
	}

  if ($malregistro>0)
			                {
			/* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */

									$q_cliente=("select procesos.id_proceso,
			                                procesos.id_titular,
			                                procesos.id_beneficiario,
			                                clientes.nombres,
			                                clientes.apellidos,
			                                clientes.cedula,
			                                admin.nombres as nomadmin,
			                                admin.apellidos as   apeadmin,
			                                entes.nombre
			                        from
			                                procesos,
			                                titulares,
			                                clientes,
			                                admin,
			                                entes
			                        where
			                                procesos.nu_planilla='$numpre' and
			                                procesos.id_titular=titulares.id_titular and
			                                titulares.id_cliente=clientes.id_cliente and
			                                procesos.id_admin=admin.id_admin and
																			titulares.id_ente=entes.id_ente");
			$r_cliente=ejecutar($q_cliente);
			$num_filas=num_filas($r_cliente);

			if  ($num_filas==0)
			{
			    ?>

			        <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>


			<tr>		<td colspan=6 class="titulo_seccion">No esta  Asignado el Numero de Planilla o Presupuesto </td>	</tr>
			    </table>

			  <?php
			  }
			  else
			  {

			?>
			<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


			<tr>		<td colspan=7 class="titulo_seccion">
			No Se Registro el Proceso ya que el Numero de Planilla o Presupuesto  esta Asignado al Siguiente Usuario Verificar a Quien le Pertenece  la Planilla y Asignarla a un Solo Usuario.
			</td>	</tr>
				<tr>
					<td class="tdtitulos">Proceso</td>
			        <td class="tdtitulos">Titular</td>
			        <td class="tdtitulos">C&eacute;dula</td>
			        <td class="tdtitulos">Beneficiario</td>
			        <td class="tdtitulos">C&eacute;dula</td>
			        <td class="tdtitulos">Ente</td>
			        <td class="tdtitulos">Analista</td>
			        </tr>
			        <tr>		<td colspan=6 class="tdtitulos"><hr></hr> </td>	</tr>
				<tr>

			        <?php

					while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){

			$q_beneficiario=("select
			                                    *
			                            from
			                                    beneficiarios,
			                                    clientes
			                            where
			                                    beneficiarios.id_cliente=clientes.id_cliente and
			                                    beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]");
			$r_beneficiario=ejecutar($q_beneficiario);
			$f_beneficiario=asignar_a($r_beneficiario);
			?>
			 <tr>		<td colspan=7 class="tdtitulos"><hr></hr> </td>	</tr>
			        	<tr>
					<td class="tdtitulos"><?php echo $f_cliente[id_proceso]?></td>
			        <td class="tdtitulos"><?php echo "$f_cliente[nombres] $f_cliente[apellidos] "?></td>
			        <td class="tdtitulos"><?php echo $f_cliente[cedula]?></td>
			        <td class="tdtitulos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos] "?></td>
			        <td class="tdtitulos"><?php echo $f_beneficiario[cedula] ?></td>
			        <td class="tdtitulos"><?php echo $f_cliente[nombre] ?></td>
			        <td class="tdtitulos"><?php echo "$f_cliente[nomadmin] $f_cliente[apeadmin] "?></td>
			        </tr>

			        <?php
			        }
			        ?>
			</table>


			<?php
			}
	}
  else
    {

if ($edoproceso==13) ///13 ESTADo: EN ESPERA
				{
					$q_cproceso="select * from procesos where procesos.id_proceso='$proceso'";
					$r_cproceso=ejecutar($q_cproceso);
					$f_cproceso=asignar_a($r_cproceso);
					$q_cobertura="select * from entes,titulares,coberturas_t_b where
			 					entes.id_ente=titulares.id_ente and
								titulares.id_titular=coberturas_t_b.id_titular and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
					$r_cobertura=ejecutar($q_cobertura);
					$f_cobertura=asignar_a($r_cobertura);

				if ($f_cobertura[id_beneficiario]==0){
					$fechainicio="$f_cobertura[fecha_inicio_contrato]";
					$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
					}
					else
					{
						$fechainicio="$f_cobertura[fecha_inicio_contratob]";
						$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
					}


			if (($servicio==1) || ($servicio==14 and $tp==18)) {

			if ($servicio==1)
				{
					$orden='REEMBOLSO AMBULATORIO';
				}
				else
				{
					$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

			$q_admin="select * from admin where admin.id_admin='$admin'";
			$r_admin=ejecutar($q_admin);
			$f_admin=asignar_a($r_admin);
			if ($f_admin[id_ciudad]==1)
				{
				$id_proveedor=96;
				}
				else
				{
					if ($f_admin[id_ciudad]==7)
				{
				$id_proveedor=64;
				}
				else
				{
					if ($f_admin[id_ciudad]==5)
				{
					$id_proveedor=928;
				}
				}
				}
			}

			$descri2=split("@",$descri1);
			$tiposerv2=split("@",$tiposerv1);
			$nombre2=split("@",$nombre1);
			$factura2=split("@",$factura1);
			$honorarios2=split("@",$honorarios1);
			$t2=split("@",$t1);
			$fechacf2=split("@",$fechacf1);
			$fechaci2=split("@",$fechaci1);
			$tc2=split("@",$tc1);
			$q="
			begin work;
			";

			for($i=0;$i<=$conexa;$i++){

				$descri=$descri2[$i];
				$tiposerv=$tiposerv2[$i];
				$nombre=$nombre2[$i];
				$factura=$factura2[$i];
				$monto=Formato_Numeros($honorarios2[$i]);
				//$montorecerva=$monto;
				$montorecerva=0;
				$t=$t2[$i];
				$fechacf=$fechacf2[$i];
				$fechaci=$fechaci2[$i];
				$tc=$tc2[$i];
				if(!empty($tiposerv) && $tiposerv>0){
					if ($tc=="on"){
						$fecharci="$fechaci";
						}
					$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
			values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
				}
			}
			///insertar un gastos por SERVICIO DE TERCERO
			if($montoTerceros>0){
				$nombre='SERVICIOS MEDICOS';
				$descri='SERVICIOS POR TERCEROS';
				$montorecerva=$montoTerceros;
				$monto=0.0002;
				$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
				values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$montorecerva','$fecharci','$tc','$t','$fechacf');";
			}

			$q.="
			commit work;
			";
			$r=ejecutar($q);


			}

			if ($servicio==4 || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17  || ($servicio==14 and $tiposerv==19) ){

				/* **** Buscamos las Especialidad **** */
			 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
			especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
			s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
			proveedores.id_proveedor='$id_proveedor'";
			$r_especialidad=ejecutar($q_especialidad);
			$num_filas=num_filas($r_especialidad);
			if ($num_filas==0){
				$q_tiposer="select * from
			tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
			$r_tiposer=ejecutar($q_tiposer);
			$f_tiposer=asignar_a($r_tiposer);
			$especialidad=	$f_tiposer[tipo_servicio];
			$id_especialidad=0;
				}
				else
				{
			$f_especialidad=asignar_a($r_especialidad);
			$especialidad=$f_especialidad[especialidad_medica];
			$id_especialidad=$f_especialidad[id_especialidad_medica];
			}

			/* **** Fin de Buscar Especialidad **** */
			$montorecerva=0;
			$r_gastos="insert into gastos_t_b
			(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
			id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
			values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva1','$monto','$monto','$fecharci','$horac');";
			$f_gastos=ejecutar($r_gastos);

			//CUENTAS POR TERCEROS
			if($montoTerceros>0){
				$nombre='SERVICIOS MEDICOS';
				$decrip='SERVICIOS POR TERCEROS';
				$montorecerva=$montoTerceros;
				$monto=0.0002;
				$r_Servicio="insert into gastos_t_b
				(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
				id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
				values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
				$f_gastos=ejecutar($r_Servicio);
			}
			}



			if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
			{

			$id_examen2=split("@",$idexamen1);
			$examen2=split("@",$examen1);
			$honorarios2=split("@",$honorarios1);
			$coment2=split("@",$coment1);

			$q="
			begin work;
			";
			for($i=0;$i<=$conexa;$i++){
				$id_examen=$id_examen2[$i];
				$examen=$examen2[$i];
				$monto=Formato_Numeros($honorarios2[$i]);
				$montorecerva=0;
				$coment=$coment2[$i];

				if(!empty($id_examen) && $id_examen>0){

					$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
			values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
				}

			}
			//CUENTAS POR TERCEROS
			if($montoTerceros>0){
				$nombre='SERVICIOS MEDICOS';
				$decrip='SERVICIOS POR TERCEROS';
				$montorecerva=$montoTerceros;
				$monto=0.0002;
				$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
				values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";

			}
			$q.="
			commit work;
			";
			$r=ejecutar($q);
			}

			if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10)){

			if ($montog>0){
			$montorecerva=0;
			$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
			values ('$proceso','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
			$f_gastos=ejecutar($r_gastos);
			}
			if ($montoh>0){
				$montorecerva=$montoh;
			$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
			values ('$proceso','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
			$f_gastos1=ejecutar($r_gastos1);
			}
			if ($montoo>0){
				$montorecerva=0;
				$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
			values ('$proceso','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
			$f_gastos2=ejecutar($r_gastos2);
			}
				}
			if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25)  || ($servicio==9 and $tiposerv==21)) {

			if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
					$q_admin="select * from admin where admin.id_admin='$admin'";
					$r_admin=ejecutar($q_admin);
					$f_admin=asignar_a($r_admin);

							if ($f_admin[id_ciudad]==1) //MERIDA
								{
									$id_proveedor=96; ///AMBULATORIO CLINISALUD MERIDA
								}
								else
								{
									if ($f_admin[id_ciudad]==7)//EL VIGIA
										{
											$id_proveedor=64; //AMBULATORIO CLINISALUD VIGIA
										}
										else
										{
											if ($f_admin[id_ciudad]==5) //TOVAR
											{
												$id_proveedor=928; //EMERGENCIA CLINISALUD TOVAR
											}
										}
									}
				}

			$descri2=split("@",$descri1);
			$factor2=split("@",$factor1);
			$nombre2=split("@",$nombre1);
			$honorarios2=split("@",$honorarios1);

			$q="
			begin work;

			";

			for($i=0;$i<=$conexa;$i++){
				$descri=$descri2[$i];
				$factor=$factor2[$i];
				$nombre=$nombre2[$i];
				$montorecerva=0;
				$monto=Formato_Numeros($honorarios2[$i]);
				if(!empty($factor) && $factor>0){
						$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
									values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
				}
			}
			//CUENTAS POR TERCEROS
			if($montoTerceros>0){
				$nombre='SERVICIOS MEDICOS';
				$descri='SERVICIOS POR TERCEROS';
				$montorecerva=$montoTerceros;
				$monto=0.0002;
				$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
								values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";

			}
			$q.="
			commit work;
			";
			$r=ejecutar($q);
			}


			/* **** Actualizo la cobertura**** */
			$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b";
			$r_actualizar=ejecutar($q_actualizar);
			while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

			$monto_actua=0;
			$monto_gastos=0;
			$q_propiedad="select * from propiedades_poliza,coberturas_t_b
			where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
			coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
			$r_propiedad=ejecutar($q_propiedad);
			$f_propiedad=asignar_a($r_propiedad);

			$q_cgastos="select * from gastos_t_b,procesos where
			gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
			procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
			gastos_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
			$r_cgastos=ejecutar($q_cgastos);
			while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
			$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
			}

			if ($f_cobertura[id_beneficiario]==0)
			{
				$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
				$monto_actual=Formato_Numeros($monto_actual);
				}
			else
			{
				$monto_actual= $f_propiedad[monto] - $monto_gastos;
				$monto_actual=Formato_Numeros($monto_actual);
			}

			$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
			coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
			coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
			coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";

			if($ActivaActualizaCovertura==true){
			$fmod_cobertura=ejecutar($mod_cobertura);
			}
			/* **** Actualizo el proceso**** */
			$mod_proceso="update procesos set id_estado_proceso='2' ,fecha_recibido='$fechare',comentarios='$comenope' where procesos.id_proceso='$proceso'";
			$fmod_proceso=ejecutar($mod_proceso);

			}
			/* **** Fin de Actualizar la cobertura**** */
			/* **** Se registra lo que hizo el usuario**** */
			$admin= $_SESSION['id_usuario_'.empresa];

			$log="REGISTRO GASTOS A LA ORDEN EN ESPERA $proceso";
			logs($log,$ip,$admin);

			/* **** Fin de lo que hizo el usuario **** */

			?>



			<?php



			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			///////////////////* **** fin de registrar procesos en estado de espera **** *//////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	}
else
		{ //else proceso en espera
		/* **** registrar mas gastos a un procesos que ya existe **** */
		if ($proceso>0 && $edoproceso<>13){
			//echo "<h1>registrar mas gastos a un procesos que ya existe<br></h1>";
						$q_cproceso="select * from procesos where procesos.id_proceso='$proceso'";
						$r_cproceso=ejecutar($q_cproceso);
						$f_cproceso=asignar_a($r_cproceso);
						/* **** Buscamos las Especialidad **** */
						 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
						especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
						s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
						proveedores.id_proveedor='$id_proveedor'";
						$r_especialidad=ejecutar($q_especialidad);
						$f_especialidad=asignar_a($r_especialidad);
						$especialidad=$f_especialidad[especialidad_medica];
						/* **** Fin de Buscar Especialidad **** */

						$q_cobertura="select * from entes,titulares,coberturas_t_b where
						 entes.id_ente=titulares.id_ente and
						titulares.id_titular=coberturas_t_b.id_titular and
						coberturas_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cobertura=ejecutar($q_cobertura);
						$f_cobertura=asignar_a($r_cobertura);

						if ($f_cobertura[id_beneficiario]==0){
						$fechainicio="$f_cobertura[fecha_inicio_contrato]";
						$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
						}
						else
						{
						$fechainicio="$f_cobertura[fecha_inicio_contratob]";
						$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
						}

						if  (($servicio==1) || ($servicio==14 and $tp==18)) {

						if ($servicio==1)
							{
							$orden='REEMBOLSO AMBULATORIO';
							}
							else
							{
							$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

						$q_admin="select * from admin where admin.id_admin='$admin'";
						$r_admin=ejecutar($q_admin);
						$f_admin=asignar_a($r_admin);
						if ($f_admin[id_ciudad]==1)
							{
							$id_proveedor=96;
							}
							else
							{
								if ($f_admin[id_ciudad]==7)
							{
							$id_proveedor=64;
							}
							else
							{
								if ($f_admin[id_ciudad]==5)
							{
						$id_proveedor=928;
							}
							}
							}
						}

						$descri2=split("@",$descri1);
						$tiposerv2=split("@",$tiposerv1);
						$nombre2=split("@",$nombre1);
						$factura2=split("@",$factura1);
						$honorarios2=split("@",$honorarios1);
						$t2=split("@",$t1);
						$fechacf2=split("@",$fechacf1);
						$fechaci2=split("@",$fechaci1);
						$tc2=split("@",$tc1);
						$q="
						begin work;
						";

						for($i=0;$i<=$conexa;$i++){

							$descri=$descri2[$i];
							$tiposerv=$tiposerv2[$i];
							$nombre=$nombre2[$i];
							$factura=$factura2[$i];
							$monto=Formato_Numeros($honorarios2[$i]);
							$t=$t2[$i];
							$fechacf=$fechacf2[$i];
							$fechaci=$fechaci2[$i];
							$tc=$tc2[$i];
							if(!empty($tiposerv) && $tiposerv>0){
								if ($tc=="on"){
									$fecharci="$fechaci";
									}
								$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
						values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
							}
						}
						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$descri='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
							values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
						}
						$q.="
						commit work;
						";
						$r=ejecutar($q);


						}

						if (($servicio==4 and $tiposerv<>6) || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17  || ($servicio==14 and $tiposerv==19) ){
							$montorecerva=0;
						$r_gastos="insert into gastos_t_b
						(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
						id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
						values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','
						$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
						$f_gastos=ejecutar($r_gastos);
						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$especialidad='SERVICIOS MEDICOS';
							$decrip='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$r_Servicio="insert into gastos_t_b
							(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
							id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
							values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','
							$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
							$f_Servicio=ejecutar($r_Servicio);
						}
						}


						if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
						{

						$id_examen2=split("@",$idexamen1);
						$examen2=split("@",$examen1);
						$honorarios2=split("@",$honorarios1);
						$coment2=split("@",$coment1);

						$q="
						begin work;
						";
						for($i=0;$i<=$conexa;$i++){
							$id_examen=$id_examen2[$i];
							$examen=$examen2[$i];
							$monto=Formato_Numeros($honorarios2[$i]);
							$montorecerva=0;
							$coment=$coment2[$i];
							if(!empty($id_examen) && $id_examen>0){

								$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
						values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
							}
						}
						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$decrip='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
						}

						$q.="
						commit work;
						";
						$r=ejecutar($q);
						}

						if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10)){

						if ($montog>0){
							$montorecerva=0;
						$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
						values ('$proceso','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
						$f_gastos=ejecutar($r_gastos);
						}
						if ($montoh>0){
							$montorecerva=$montoh;
						$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
						values ('$proceso','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
						$f_gastos1=ejecutar($r_gastos1);
						}
						if ($montoo>0){
							$montorecerva=0;
							$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
						values ('$proceso','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
						$f_gastos2=ejecutar($r_gastos2);


							}
							}


						if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25) || ($servicio==9 and $tiposerv==21)) {
							if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
								$q_admin="select * from admin where admin.id_admin='$admin'";
								$r_admin=ejecutar($q_admin);
								$f_admin=asignar_a($r_admin);

								if ($f_admin[id_ciudad]==1)
								{
								$id_proveedor=96;
								}
									else
										{
											if ($f_admin[id_ciudad]==7)
										{
												$id_proveedor=64;
												}
										else
										{
											if ($f_admin[id_ciudad]==5)
										{
													$id_proveedor=928;
											}
										}
									}
						}


						$descri2=split("@",$descri1);
						$factor2=split("@",$factor1);
						$nombre2=split("@",$nombre1);
						$honorarios2=split("@",$honorarios1);

						$q="
						begin work;

						";
						for($i=0;$i<=$conexa;$i++){

							$descri=$descri2[$i];
							$factor=$factor2[$i];
							$nombre=$nombre2[$i];
							$monto=Formato_Numeros($honorarios2[$i]);
							if(!empty($factor) && $factor>0){

								$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
						values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
							}
						}
						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$descri='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
							values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
						}
						$q.="
						commit work;
						";
						$r=ejecutar($q);
						}



						/* **** Actualizo la cobertura**** */
						$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b";
						$r_actualizar=ejecutar($q_actualizar);
						while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

						$monto_actua=0;
						$monto_gastos=0;
						$q_propiedad="select * from propiedades_poliza,coberturas_t_b
						where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
						coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
						$r_propiedad=ejecutar($q_propiedad);
						$f_propiedad=asignar_a($r_propiedad);

						$q_cgastos="select * from gastos_t_b,procesos where
						gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
						procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
						gastos_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
						$r_cgastos=ejecutar($q_cgastos);
						while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
						$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
						}

						if ($f_cobertura[id_beneficiario]==0)
						{
							$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}
						else
						{

						$monto_actual= $f_propiedad[monto] - $monto_gastos;
						$monto_actual=Formato_Numeros($monto_actual);
						}

						$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
						coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
						coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
						coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
						if($ActivaActualizaCovertura==true){
						$fmod_cobertura=ejecutar($mod_cobertura);}

						}
						/* **** Fin de Actualizar la cobertura**** */
						/* **** Se registra lo que hizo el usuario**** */
						$admin= $_SESSION['id_usuario_'.empresa];

						$log="REGISTRO COBERTURA ADICIONAL A LA ORDEN $proceso";
						logs($log,$ip,$admin);

						/* **** Fin de lo que hizo el usuario **** */
						?>





						<?php

/////////////////////////////////////////////////////////////////////////////////////////////////
////* fin de agregar mas gastos a un proceso existente registrar procesos por primera vez **////
////////////////////////////////////////////////////////////////////////////////////////////////

			}
			else
			{

				/* **** registrar procesos por primera vez **** */
				if ($proceso==0){
//					echo "<h1>POR PIMERA VEZ<br></h1>";
					/* **** Buscamos las Especialidad **** */
					$admin= $_SESSION['id_usuario_'.empresa];
					/***** Empezamos a Verificar Que tipo de servicio es Para Procesar la Descarga **** */
					/***** Si es Distinto de Consulta Preventiva**** */

					$q_cobertura="select * from entes,titulares,coberturas_t_b where
				 					entes.id_ente=titulares.id_ente and
									titulares.id_titular=coberturas_t_b.id_titular and
									coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
									$r_cobertura=ejecutar($q_cobertura);
					$f_cobertura=asignar_a($r_cobertura);

					if ($f_cobertura[id_beneficiario]==0){
						$fechainicio="$f_cobertura[fecha_inicio_contrato]";
						$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
					}
					else
					{
						$fechainicio="$f_cobertura[fecha_inicio_contratob]";
						$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
					}

				if ($fechare<$fechainicio) {
					$gastosviejo='1';
				}
				else
				{
				$gastosviejo='0';
				}
		if ($tiposerv==5)
				{//echo"<h1>-<preventivas>-</h1>";
							$q_especialidad="select * from
						especialidades_medicas,s_p_proveedores,proveedores where
						especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
						s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
						proveedores.id_proveedor='$id_proveedor'";
						$r_especialidad=ejecutar($q_especialidad);
						$num_filas=num_filas($r_especialidad);
						if ($num_filas==0){
							$q_tiposer="select * from
						tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
						$r_tiposer=ejecutar($q_tiposer);
						$f_tiposer=asignar_a($r_tiposer);
						$especialidad=	$f_tiposer[tipo_servicio];
						$id_especialidad=0;
							}
							else
							{
								$f_especialidad=asignar_a($r_especialidad);
								$especialidad=$f_especialidad[especialidad_medica];
								$id_especialidad=$f_especialidad[id_especialidad_medica];
							}
						/* **** Fin de Buscar Especialidad **** */

						$orden="CONSULTA PREVENTIVA";
						/* **** Si es Igual a Consulta Preventiva Cargar Orden **** */
						$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,fecha_ent_pri,codigo)
						values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$fecha_privada','$codigo');";
						$f_proceso=ejecutar($r_proceso);

						$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
						$r_cproceso=ejecutar($q_cproceso);
						$f_cproceso=asignar_a($r_cproceso);


						$r_gastos="insert into gastos_t_b
						(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
						id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
						values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','0','
						$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
						$f_gastos=ejecutar($r_gastos);

						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$decrip='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$r_Servicio="insert into gastos_t_b
								(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
								id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
								values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','0','
								$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
								$f_Servicio=ejecutar($r_Servicio);
							}




						$r_preventiva="insert into consultas_preventivas (id_titular,id_beneficiario,id_especialidad_medica,especialidad_medica,fecha_creado,hora_creado,id_proceso)
						values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','$id_especialidad','$especialidad','$fechacreado','$hora','$f_cproceso[id_proceso]');";
						$f_preventiva=ejecutar($r_preventiva);

						$log="REGISTRO LA ORDEN DE PREVENTIVA CON ORDEN NUMERO $f_cproceso[id_proceso]";
						logs($log,$ip,$admin);
						$proceso='$f_cproceso[id_proceso]';

				}
					/* **** Fin de Cargar Consulta Preventiva **** */

						if (($tiposerv==7) || ($tiposerv==11 || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17) || ($servicio==14 and $tiposerv==19) )
						{

							$q_especialidad="select * from
						especialidades_medicas,s_p_proveedores,proveedores where
						especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
						s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
						proveedores.id_proveedor='$id_proveedor'";
						$r_especialidad=ejecutar($q_especialidad);
						$num_filas=num_filas($r_especialidad);
						if ($num_filas==0){
							$q_tiposer="select * from
						tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
						$r_tiposer=ejecutar($q_tiposer);
						$f_tiposer=asignar_a($r_tiposer);
						$especialidad=	$f_tiposer[tipo_servicio];
						$id_especialidad=0;
							}
							else
							{
						$f_especialidad=asignar_a($r_especialidad);
						$especialidad=$f_especialidad[especialidad_medica];
						$id_especialidad=$f_especialidad[id_especialidad_medica];
						}
						/* **** Fin de Buscar Especialidad **** */

						if ($tiposerv==7)
						{
						$orden="CONSULTAS MEDICAS";
						}
						if ($tiposerv==11)
						{
						$orden="ORDEN DE LENTES";
						}

						if ($tiposerv==14 )
						{
						$orden="HONORARIOS MEDICOS DE EMERGENCIA";
						}
						if ($tiposerv==15)
						{
						$orden="HONORARIOS MEDICOS HOSPITALIZACION";
						}

						if ($tiposerv==16 || $tiposerv==17)
						{
						$orden="DISPONIBILIDAD";
						}
						if ($tiposerv==19)
						{
						$orden="CIRUGIA AMBULATORIA HONORARIOS MEDICOS";
						}

						/* **** Si es Igual a Consulta Medica Cargar Orden o cirugia ambulatoria honorarios medicos o disponibilidad o solicitud de procedimiento **** */
						$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,fecha_ent_pri,codigo)
						values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope',' ',' ','$admin','$gastosviejo','$numpre','$fecha_privada','$codigo');";
						$f_proceso=ejecutar($r_proceso);

						$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
						$r_cproceso=ejecutar($q_cproceso);
						$f_cproceso=asignar_a($r_cproceso);


						$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
						values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
						$f_gastos=ejecutar($r_gastos);

						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$decrip='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$r_Servicio="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
							values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
							$f_Servicio=ejecutar($r_Servicio);

							}


						/* **** Actualizo la cobertura**** */
						$q_propiedad="select * from propiedades_poliza,coberturas_t_b
						where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
						coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
						$r_propiedad=ejecutar($q_propiedad);
						$f_propiedad=asignar_a($r_propiedad);

						$q_cgastos="select * from gastos_t_b,procesos where
						gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
						procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
						gastos_t_b.id_cobertura_t_b='$id_cobertura'";
						$r_cgastos=ejecutar($q_cgastos);
						while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
						$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
						}

						if ($f_cobertura[id_beneficiario]==0)
						{
							$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}
						else
						{

						$monto_actual= $f_propiedad[monto] - $monto_gastos;
						$monto_actual=Formato_Numeros($monto_actual);
						}

						$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
						coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
						coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
						coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
						if($ActivaActualizaCovertura==true){
						$fmod_cobertura=ejecutar($mod_cobertura);}


						/* **** Fin de Actualizar la cobertura**** */
						/* **** Se registra lo que hizo el usuario**** */

						$log="REGISTRO LA ORDEN DE $orden CON ORDEN NUMERO $f_cproceso[id_proceso]";
						logs($log,$ip,$admin);
						$proceso=$f_cproceso[id_proceso];

						/* **** Fin de lo que hizo el usuario **** */

				}
							/* **** Fin de Cargar Consulta Medica **** */


							/* **** Si es Igual a Curativa o examenes especiales Cargar Orden **** */

				if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
					{
						if ($tiposerv==6)
						{
						$orden="DE ATENCION";
						}
						if ($tiposerv==8)
						{
						$orden="EMERGENCIAS";
						}
						if ($tiposerv==12)
						{
						$orden="HOSPITALIZACION AMBULATORIA";
						}

						if ($examenes>0){
						$q_texamen=("select * from tipos_imagenologia_bi  where tipos_imagenologia_bi.id_tipo_imagenologia_bi='$examenes'");
						$r_texamen=ejecutar($q_texamen);
						$f_texamen=asignar_a($r_texamen);
						$decrip=$f_texamen[tipo_imagenologia_bi];
						}

						$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
						values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$numpre','$codigo');";
						$f_proceso=ejecutar($r_proceso);
						$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
						$r_cproceso=ejecutar($q_cproceso);
						$f_cproceso=asignar_a($r_cproceso);
						$id_examen2=split("@",$idexamen1);
						$examen2=split("@",$examen1);
						$honorarios2=split("@",$honorarios1);
						$coment2=split("@",$coment1);

						$q="
						begin work;
						";
						for($i=0;$i<=$conexa;$i++){
							$id_examen=$id_examen2[$i];
							$examen=$examen2[$i];
							$monto=Formato_Numeros($honorarios2[$i]);
							$coment=$coment2[$i];
							if(!empty($id_examen) && $id_examen>0){

								$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
						values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
							}
						}
						//CUENTAS POR TERCEROS
						if($montoTerceros>0){
							$nombre='SERVICIOS MEDICOS';
							$decrip='SERVICIOS POR TERCEROS';
							$montorecerva=$montoTerceros;
							$monto=0.0002;
							$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
							}
						$q.="
						commit work;
						";
						$r=ejecutar($q);

						/* **** Actualizo la cobertura**** */
						$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
						and coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
						$r_propiedad=ejecutar($q_propiedad);
						$f_propiedad=asignar_a($r_propiedad);
						$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b='$id_cobertura'";
						$r_cgastos=ejecutar($q_cgastos);
						while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
						$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
						}
						if ($f_cobertura[id_beneficiario]==0)
						{
							$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}
						else
						{

						$monto_actual= $f_propiedad[monto] - $monto_gastos;
						$monto_actual=Formato_Numeros($monto_actual);
						}
						$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
						if($ActivaActualizaCovertura==true){
						$fmod_cobertura=ejecutar($mod_cobertura);}

						/* **** Fin de Actualizar la cobertura**** */
						/* **** Se registra lo que hizo el usuario**** */

						$log="REGISTRO LA ORDEN $orden 	CURATIVAS Y/O EXAMENES ESPECIALES CON ORDEN NUMERO $f_cproceso[id_proceso]";
						logs($log,$ip,$admin);
						$proceso=$f_cproceso[id_proceso];

						/* **** Fin de lo que hizo el usuario **** */

						}
						/* **** Fin de Cargar Curativas o examenes especiales **** */
						/* **** cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */
					if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13) )
						{


								if ($servicio==2)
							{
							$orden="CLAVE DE EMERGENCIA";
							}
								if ($servicio==3)
							{
							$orden="CARTA DE COMPROMISO";
							}
								if ($servicio==8)
							{
							$orden="VICIOS DE REFRECCION";
							}
								if ($servicio==11)
							{
							$orden="MATERNIDAD";
							}
								if ($servicio==13)
							{
							$orden="CARTA AMBULATORIO";
							}
								$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,codigo)
							values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$codigo');";
							$f_proceso=ejecutar($r_proceso);

							$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
							$r_cproceso=ejecutar($q_cproceso);
							$f_cproceso=asignar_a($r_cproceso);

							if ($montog>0){
								$montorecerva=0;
							$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
							$f_gastos=ejecutar($r_gastos);
							}
							if ($montoh>0){
								$montorecerva=$montoh;
							$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
							$f_gastos1=ejecutar($r_gastos1);
							}
							if ($montoo>0){
								$montorecerva=0;
								$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
							$f_gastos2=ejecutar($r_gastos2);


								}


							/* **** Actualizo la cobertura**** */
							$q_propiedad="select * from propiedades_poliza,coberturas_t_b
							where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
							coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
							$r_propiedad=ejecutar($q_propiedad);
							$f_propiedad=asignar_a($r_propiedad);

							$q_cgastos="select * from gastos_t_b,procesos where
							gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
							procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
							gastos_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cgastos=ejecutar($q_cgastos);
							while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
							$monto_gastos= $monto_gastos +
							$f_cgastos[monto_aceptado];
							}

							if ($f_cobertura[id_beneficiario]==0)
							{
								$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}
							else
							{

							$monto_actual= $f_propiedad[monto] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}

							$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
							coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
							coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
							coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
							if($ActivaActualizaCovertura==true){
							$fmod_cobertura=ejecutar($mod_cobertura);}


							/* **** Fin de Actualizar la cobertura**** */
							/* **** Se registra lo que hizo el usuario**** */

							$log="REGISTRO $orden  CON ORDEN NUMERO $f_cproceso[id_proceso]";
							logs($log,$ip,$admin);
							$proceso=$f_cproceso[id_proceso];
							/* **** Fin de lo que hizo el usuario **** */

				}
		/* **** fin de cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */
		/* **** cargar reembolso hcm) **** */
		if ($tiposerv==0 and ($servicio==10) )
				{

							$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,codigo)
							values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo',$codigo);";
							$f_proceso=ejecutar($r_proceso);
							$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
							$r_cproceso=ejecutar($q_cproceso);
							$f_cproceso=asignar_a($r_cproceso);
							if ($montog>0){
								$montorecerva=0;
							$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
							$f_gastos=ejecutar($r_gastos);
							}
							if ($montoh>0){
								$montorecerva=$montoh;
							$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
							$f_gastos1=ejecutar($r_gastos1);
							}
							if ($montoo>0){
								$montorecerva=0;
								$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
							$f_gastos2=ejecutar($r_gastos2);
								}
							/* **** Actualizo la cobertura**** */
							$q_propiedad="select * from propiedades_poliza,coberturas_t_b
							where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
							coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
							$r_propiedad=ejecutar($q_propiedad);
							$f_propiedad=asignar_a($r_propiedad);
							$q_cgastos="select * from gastos_t_b,procesos where
							gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
							procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
							gastos_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cgastos=ejecutar($q_cgastos);
							while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
							}

							if ($f_cobertura[id_beneficiario]==0)
									{
								$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}
							else
							{

							$monto_actual= $f_propiedad[monto] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}

							$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
							coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
							coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
							coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
							if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);
							}

							/* **** Fin de Actualizar la cobertura**** */
							/* **** Se registra lo que hizo el usuario**** */

							$log="REGISTRO EL REEMBOLSO HCM  CON ORDEN NUMERO $f_cproceso[id_proceso]";
							logs($log,$ip,$admin);
							$proceso=$f_cproceso[id_proceso];

							/* **** Fin de lo que hizo el usuario **** */

				}

		/* **** fin de cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */


		/* **** guardar gastos de emeregencia o de hospitalizacion **** */

		if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25)  || ($servicio==9 and $tiposerv==21) )
				{


//						echo 'guardar gastos de emeregencia o de hospitalizacion ';
						if ($tiposerv==9 || $tiposerv==20)
							{
								$orden="GASTOS DE EMERGENCIA";
							}
							if ($tiposerv==13 || $tiposerv==21)
							{
									$orden="HOSPITALIZACION AMBULATORIA";

						}


if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
							$q_admin="select * from admin where id_admin='$admin'";
							$r_admin=ejecutar($q_admin);
							$f_admin=asignar_a($r_admin);

							if ($f_admin[id_ciudad]==1)
								{
									$id_proveedor=96;
								}
								else
								{
									if ($f_admin[id_ciudad]==7)
									{
										$id_proveedor=64;
									}
									else
									{
										if ($f_admin[id_ciudad]==5)
											{
												$id_proveedor=928;
											}
										}
									}
					}
								$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$numpre','$codigo');";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);

								$descri2=split("@",$descri1);
								$factor2=split("@",$factor1);
								$nombre2=split("@",$nombre1);
								$honorarios2=split("@",$honorarios1);

								$q="
								begin work;

								";
								for($i=0;$i<=$conexa;$i++){

									$descri=$descri2[$i];
									$factor=$factor2[$i];
									$nombre=$nombre2[$i];
									$monto=$honorarios2[$i];
									if(!empty($factor) && $factor>0){

								$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita,unidades)
								values ('$f_cproceso[id_proceso]','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac','$factor');";
									}
								}
								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$descri='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0.0002;
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita,unidades)
									values ('$f_cproceso[id_proceso]','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac','$factor');";
										}
								$q.="
								commit work;
								";
								$r=ejecutar($q);


								/* **** Actualizo la cobertura**** */
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
								and coberturas_t_b.id_cobertura_t_b=$id_cobertura";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura";
								$r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
								}

								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
									$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}

								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
								if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);}

								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */

								$log="REGISTRO de $orden  CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];

								/* **** Fin de lo que hizo el usuario **** */
		}

		/* **** fin de guardar gastos de emeregencia o de hospitalizacion **** */
		/* **** guardar reembolso ambulatorio **** */

		if (($servicio==1) || ($servicio==14 and $tp==18))
		{
//				echo "Guardar reembolso ambulatorio";
			if ($servicio==1)
				{
					$orden='REEMBOLSO AMBULATORIO';
				}
				else
				{
						$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

						$q_admin="select * from admin where admin.id_admin=$admin";
						$r_admin=ejecutar($q_admin);
						$f_admin=asignar_a($r_admin);
				if ($f_admin[id_ciudad]==1)
					{
						$id_proveedor=96;
					}
					else
					{
						if ($f_admin[id_ciudad]==7)
						{
							$id_proveedor=64;
						}
						else
						{
							if ($f_admin[id_ciudad]==5)
							{
								$id_proveedor=928;
							}
						}
					}
				}

		$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
		values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$numpre','$codigo');";
		$f_proceso=ejecutar($r_proceso);

		$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
		$r_cproceso=ejecutar($q_cproceso);
		$f_cproceso=asignar_a($r_cproceso);



		$descri2=split("@",$descri1);
		$tiposerv2=split("@",$tiposerv1);
		$nombre2=split("@",$nombre1);
		$factura2=split("@",$factura1);
		$honorarios2=split("@",$honorarios1);
		$t2=split("@",$t1);
		$fechacf2=split("@",$fechacf1);
		$fechaci2=split("@",$fechaci1);
		$tc2=split("@",$tc1);

		$q="
		begin work;
		";
		for($i=0;$i<=$conexa;$i++){

			$descri=$descri2[$i];
			$tiposerv=$tiposerv2[$i];
			$nombre=$nombre2[$i];
			$factura=$factura2[$i];
			$monto=Formato_Numeros($honorarios2[$i]);
			$t=$t2[$i];
			$fechacf=$fechacf2[$i];
			$fechaci=$fechaci2[$i];
			$tc=$tc2[$i];


			if(!empty($tiposerv) && $tiposerv>0){
				if ($tc=="on"){
					$fecharci="$fechaci";
					}
				$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor
		,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
		values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv'
		,'$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
			}
		}
		//CUENTAS POR TERCEROS
		if($montoTerceros>0){
			$nombre='SERVICIOS MEDICOS';
			$descri='SERVICIOS POR TERCEROS';
			$montorecerva=$montoTerceros;
			$monto=0.0002;
					$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor
			,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
			values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv'
			,'$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
		}
		$q.="
		commit work;
		";
		$r=ejecutar($q);


		/* **** Actualizo la cobertura**** */
		$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
		and coberturas_t_b.id_cobertura_t_b=$id_cobertura";
		$r_propiedad=ejecutar($q_propiedad);
		$f_propiedad=asignar_a($r_propiedad);

		$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura";
		$r_cgastos=ejecutar($q_cgastos);
		while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
		$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
		}

		if ($f_cobertura[id_beneficiario]==0)
		{
			$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
			$monto_actual=Formato_Numeros($monto_actual);
			}
		else
		{
		$monto_actual= $f_propiedad[monto] - $monto_gastos;
		$monto_actual=Formato_Numeros($monto_actual);
		}

		$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
		if($ActivaActualizaCovertura==true){
		$fmod_cobertura=ejecutar($mod_cobertura);}

		/* **** Fin de Actualizar la cobertura**** */
		/* **** Se registra lo que hizo el usuario**** */

		$log="REGISTRO $orden CON ORDEN NUMERO $f_cproceso[id_proceso]";
		logs($log,$ip,$admin);
		$proceso=$f_cproceso[id_proceso];

		/* **** Fin de lo que hizo el usuario **** */
		}

		/* **** fin de guardar reembolso **** */
		/* **** modificar el procesos y gastos de este proceso si es donativo **** */

		if ($donativo>=1){
		$mod_prodon="update procesos set id_estado_proceso=1,donativo='$donativo' where
		procesos.id_proceso=$f_cproceso[id_proceso]";
		$fmod_prodon=ejecutar($mod_prodon);
		/* **** modificar el procesos  si es donativo **** */
		}


	}  //FIn if POR PIMERA VEZ


} 	///fin else de agregar mas gastos


}   //FIN de else proceso en espera


?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<?php
if ($edoproceso==13)
{ ///PROCESO EN ESTADO DE ESPERA
	$pro=$proceso;

?>


<tr>
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> que Estaba en Espera se les Registro sus gastos con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso ?>"   ><a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>
</tr>
<tr>
<td colspan=4 class="titulo_seccion">Imprimir </td>
</tr>
<?php
}
if ($proceso>0 && $edoproceso<>13){
	//registrar mas gastos a un procesos que existe estado en ESPERA(13)
	$pro=$proceso;
?>



<tr>
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se les Registro sus gastos o cobertura adicional con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
</tr>
<tr>
<td colspan=4 class="titulo_seccion">Imprimir </td>
</tr>
<?php
}

if ($proceso==0){
	$pro=$f_cproceso[id_proceso];
?>


<tr>
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $f_cproceso[id_proceso] ?> Se Registro con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $f_cproceso[id_proceso]  ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
</tr>
<tr>
<td colspan=4 class="titulo_seccion">Imprimir </td>
</tr>
<?php
}
if ($servicio==1 || $servicio==10){
?>

<tr>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$pro&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			</td>
	</tr>


<?php

}
else
{
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) )
	{
?>

	<tr>
		<td class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$pro'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$pro&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$pro&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
			<?php
			$url="'views01/irecepcion.php?proceso=$pro&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Recibo de Recepcion</a>
				</td>
	</tr>

	<?php
	}
	else
	{
	?>
<tr>
		<td class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$pro&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$pro&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$pro'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/irecepcion.php?proceso=$pro&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Recibo de Recepcion</a>
			<?php
			$url="'views01/iasesoria.php?proceso=$pro&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Asesoria Medica</a>



<?php
			$url="'views01/iordenb.php?proceso=$pro&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a>

			<?php
	if ($f_cproceso[nu_planilla]>=1){

			$url="'views01/isolicitudmedicamento.php?proceso=$f_cproceso[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamento </a>

			<?php
		$url="'views01/iplanilla_ingreso.php?proceso=$f_cproceso[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Planilla de Ingreso </a>


			<?php
			$url="'views01/ipresupuesto.php?proceso=$f_cproceso[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
			<?php
			$url="'views01/ipresupuestop.php?proceso=$f_cproceso[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>

			<?php $url="'views01/ifpresupuestop.php?proceso=$f_cproceso[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>

			<?php }

			if ($f_cproceso[nu_planilla]>=1 and $tp==18){
			$url="'views01/icartaamb.php?proceso=$f_cproceso[nu_planilla]&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Carta Cirugia </a>
		<?php }
			?>



			</td>
	</tr>
<?php
}
}

if ($donativo==1) {
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir Cartas de Donativos</td></tr>
<tr>
		<td  class="tdtitulos"><?php
			$url="'views01/iacta.php?proceso=$pro'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Acta</a>
			<?php
			$url="'views01/isolicitud.php?proceso=$pro'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Solicitud</a>
			</td>
</tr>
<?php
}
if ($num_filasf>0){
?>
<tr> <td colspan=4 class="titulo_seccion"><a href="#" OnClick="reg_factura();" class="boton">Facturacion</a></td></tr>
<?php
}
?>
</table>


              <?php

                        }

}
else
			{


			/* **** busco si el usuario registra factura**** */
			$q_factura="select * from tbl_003 where tbl_003.id_modulo='4' and tbl_003.id_usuario='$admin'";
			$r_factura=ejecutar($q_factura);
			$num_filasf=num_filas($r_factura);
			/* **** fin  busco si el usuario registra factura**** */

			/*echo $id_cobertura;
			echo "**1**";
			echo $monto;
			echo "**2**";
			echo $id_proveedor;
			echo "**3**";
			echo $fechare;
			echo "**4**";
			echo $fecharci;
			echo "**5**";
			echo $horac;
			echo "**6**";
			echo $enfermedad;
			echo "**7**";
			echo $decrip;
			echo "**8**";
			echo $comenope;
			echo "**9**";
			echo $tiposerv;
			echo "**10**";
			echo $contador;*/

			if ($edoproceso==13)
				{/* **** registrar procesos que se encuentren en estado de ESPERA(13) **** */
								$q_cproceso="select * from procesos where procesos.id_proceso='$proceso'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);


								$q_cobertura="select * from entes,titulares,coberturas_t_b where
								 entes.id_ente=titulares.id_ente and
								titulares.id_titular=coberturas_t_b.id_titular and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cobertura=ejecutar($q_cobertura);
								$f_cobertura=asignar_a($r_cobertura);



								if ($f_cobertura[id_beneficiario]==0){
								$fechainicio="$f_cobertura[fecha_inicio_contrato]";
								$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
								}
								else
								{
								$fechainicio="$f_cobertura[fecha_inicio_contratob]";
								$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
								}


								if (($servicio==1) || ($servicio==14 and $tp==18)) {

								if ($servicio==1)
									{
									$orden='REEMBOLSO AMBULATORIO';
									}
									else
									{
									$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

								$q_admin="select * from admin where admin.id_admin='$admin'";
								$r_admin=ejecutar($q_admin);
								$f_admin=asignar_a($r_admin);
								if ($f_admin[id_ciudad]==1)
									{
									$id_proveedor=96;
									}
									else
									{
										if ($f_admin[id_ciudad]==7)
									{
									$id_proveedor=64;
									}
									else
									{
										if ($f_admin[id_ciudad]==5)
									{
								$id_proveedor=928;
									}
									}
									}
								}

								$descri2=split("@",$descri1);
								$tiposerv2=split("@",$tiposerv1);
								$nombre2=split("@",$nombre1);
								$factura2=split("@",$factura1);
								$honorarios2=split("@",$honorarios1);
								$t2=split("@",$t1);
								$fechacf2=split("@",$fechacf1);
								$fechaci2=split("@",$fechaci1);
								$tc2=split("@",$tc1);
								$q="
								begin work;
								";

								for($i=0;$i<=$conexa;$i++){

									$descri=$descri2[$i];
									$tiposerv=$tiposerv2[$i];
									$nombre=$nombre2[$i];
									$factura=$factura2[$i];
									$monto=Formato_Numeros($honorarios2[$i]);
									$t=$t2[$i];
									$fechacf=$fechacf2[$i];
									$fechaci=$fechaci2[$i];
									$tc=$tc2[$i];
									if(!empty($tiposerv) && $tiposerv>0){
										if ($tc=="on"){
											$fecharci="$fechaci";
											}
										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
								values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
									}
								}
								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$descri='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0.0002;
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
							values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
								}
								$q.="
								commit work;
								";
								$r=ejecutar($q);


								}



								if (($servicio==4 and $tiposerv<>6) || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17  || ($servicio==14 and $tiposerv==19) ){

									/* **** Buscamos las Especialidad **** */
								 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
								especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
								s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
								proveedores.id_proveedor='$id_proveedor'";
								$r_especialidad=ejecutar($q_especialidad);
								$num_filas=num_filas($r_especialidad);
								if ($num_filas==0){
									$q_tiposer="select * from
								tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
								$r_tiposer=ejecutar($q_tiposer);
								$f_tiposer=asignar_a($r_tiposer);
								$especialidad=	$f_tiposer[tipo_servicio];
								$id_especialidad=0;
									}
									else
									{
								$f_especialidad=asignar_a($r_especialidad);
								$especialidad=$f_especialidad[especialidad_medica];
								$id_especialidad=$f_especialidad[id_especialidad_medica];
								}

								/* **** Fin de Buscar Especialidad **** */

								$r_gastos="insert into gastos_t_b
								(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
								id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
								values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$monto','$monto','$monto','$fecharci','$horac');";
								$f_gastos=ejecutar($r_gastos);

								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$decrip='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$r_servicio="insert into gastos_t_b
										(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
										id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
										values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$monto','$monto','$monto','$fecharci','$horac');";
										$f_servicio=ejecutar($r_servicio);
									}
								}



								if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
								{

								$id_examen2=split("@",$idexamen1);
								$examen2=split("@",$examen1);
								$honorarios2=split("@",$honorarios1);
								$coment2=split("@",$coment1);

								$q="
								begin work;
								";
								for($i=0;$i<=$conexa;$i++){
									$id_examen=$id_examen2[$i];
									$examen=$examen2[$i];
									$monto=Formato_Numeros($honorarios2[$i]);
									$coment=$coment2[$i];
								    echo $examen;
									if(!empty($id_examen) && $id_examen>0){

										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
									}

								}
								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$decrip='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
										values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
									}
								$q.="
								commit work;
								";
								$r=ejecutar($q);
								}

								if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10)){

								if ($montog>0){
									$montorecerva=0;
								$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$proceso','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
								$f_gastos=ejecutar($r_gastos);
								}
								if ($montoh>0){
									$montorecerva=$montoh;
								$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$proceso','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
								$f_gastos1=ejecutar($r_gastos1);
								}
								if ($montoo>0){
									$montorecerva=0;
									$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$proceso','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
								$f_gastos2=ejecutar($r_gastos2);


									}
									}
								if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25) || ($servicio==9 and $tiposerv==21)) {


								if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
								$q_admin="select * from admin where admin.id_admin='$admin'";
								$r_admin=ejecutar($q_admin);
								$f_admin=asignar_a($r_admin);
								if ($f_admin[id_ciudad]==1)
									{
									$id_proveedor=96;
									}
									else
									{
										if ($f_admin[id_ciudad]==7)
									{
									$id_proveedor=64;
									}
									else
									{
										if ($f_admin[id_ciudad]==5)
									{
								$id_proveedor=928;
									}
									}
									}
								}

								$descri2=split("@",$descri1);
								$factor2=split("@",$factor1);
								$nombre2=split("@",$nombre1);
								$honorarios2=split("@",$honorarios1);

								$q="
								begin work;

								";
								for($i=0;$i<=$conexa;$i++){

									$descri=$descri2[$i];
									$factor=$factor2[$i];
									$nombre=$nombre2[$i];
									$monto=Formato_Numeros($honorarios2[$i]);
									if(!empty($factor) && $factor>0){

										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
								values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
									}
								}
								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$descri='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
										values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
									}
								$q.="
								commit work;
								";
								$r=ejecutar($q);
								}

								/* **** Actualizo la cobertura**** */
								$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b";
								$r_actualizar=ejecutar($q_actualizar);
								while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

								$monto_actua=0;
								$monto_gastos=0;
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b
								where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
								coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where
								gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
								procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
								gastos_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
								$r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos +
								$f_cgastos[monto_aceptado];
								}

								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
									$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}

								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
								coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
								coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
								coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
									if($ActivaActualizaCovertura==true){
										$fmod_cobertura=ejecutar($mod_cobertura);}

								/* **** Actualizo el proceso**** */
								$mod_proceso="update procesos set id_estado_proceso='2' ,fecha_recibido='$fechare',comentarios='$comenope' where procesos.id_proceso='$proceso'";
								$fmod_proceso=ejecutar($mod_proceso);

								}
								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */
								$admin= $_SESSION['id_usuario_'.empresa];

								$log="REGISTRO GASTOS A LA ORDEN EN ESPERA $proceso";
								logs($log,$ip,$admin);


								/* **** Fin de lo que hizo el usuario **** */

								?>



								<?php
						}
					else
						{
							/* **** fin de registrar procesos en estado de espera **** */

							/* **** registrar mas gastos a un procesos que ya existe **** */
							if ($proceso>0 && $edoproceso<>13){
								/* **** registrar mas gastos a un procesos que  existe estado ESPERA(13) **** */
								$q_cproceso="select * from procesos where procesos.id_proceso='$proceso'";
							$r_cproceso=ejecutar($q_cproceso);
							$f_cproceso=asignar_a($r_cproceso);
							/* **** Buscamos las Especialidad **** */
							 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
							especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
							s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
							proveedores.id_proveedor='$id_proveedor'";
							$r_especialidad=ejecutar($q_especialidad);
							$f_especialidad=asignar_a($r_especialidad);
							$especialidad=$f_especialidad[especialidad_medica];
							/* **** Fin de Buscar Especialidad **** */

							$q_cobertura="select * from entes,titulares,coberturas_t_b where
							 entes.id_ente=titulares.id_ente and
							titulares.id_titular=coberturas_t_b.id_titular and
							coberturas_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cobertura=ejecutar($q_cobertura);
							$f_cobertura=asignar_a($r_cobertura);



							if ($f_cobertura[id_beneficiario]==0){
							$fechainicio="$f_cobertura[fecha_inicio_contrato]";
							$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
							}
							else
							{
							$fechainicio="$f_cobertura[fecha_inicio_contratob]";
							$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
							}


							if  (($servicio==1) || ($servicio==14 and $tp==18)) {

							if ($servicio==1)
								{
								$orden='REEMBOLSO AMBULATORIO';
								}
								else
								{
								$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

							$q_admin="select * from admin where admin.id_admin='$admin'";
							$r_admin=ejecutar($q_admin);
							$f_admin=asignar_a($r_admin);
							if ($f_admin[id_ciudad]==1)
								{
								$id_proveedor=96;
								}
								else
								{
									if ($f_admin[id_ciudad]==7)
								{
								$id_proveedor=64;
								}
								else
								{
									if ($f_admin[id_ciudad]==5)
								{
							$id_proveedor=928;
								}
								}
								}
							}

							$descri2=split("@",$descri1);
							$tiposerv2=split("@",$tiposerv1);
							$nombre2=split("@",$nombre1);
							$factura2=split("@",$factura1);
							$honorarios2=split("@",$honorarios1);
							$t2=split("@",$t1);
							$fechacf2=split("@",$fechacf1);
							$fechaci2=split("@",$fechaci1);
							$tc2=split("@",$tc1);
							$q="
							begin work;
							";

							for($i=0;$i<=$conexa;$i++){

								$descri=$descri2[$i];
								$tiposerv=$tiposerv2[$i];
								$nombre=$nombre2[$i];
								$factura=$factura2[$i];
								$monto=Formato_Numeros($honorarios2[$i]);
								$t=$t2[$i];
								$fechacf=$fechacf2[$i];
								$fechaci=$fechaci2[$i];
								$tc=$tc2[$i];
								if(!empty($tiposerv) && $tiposerv>0){
									if ($tc=="on"){
										$fecharci="$fechaci";
										}
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
							values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
								}
							}
							//CUENTAS POR TERCEROS
							if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$descri='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0.0002;
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
							values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
								}
							$q.="
							commit work;
							";
							$r=ejecutar($q);


							}

							if (($servicio==4 and $tiposerv<>6) || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17  || ($servicio==14 and $tiposerv==19) ){

							$r_gastos="insert into gastos_t_b
							(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
							id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
							values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','
							$enfermedad','$id_proveedor','$tiposerv','$servicio','$monto','$monto','$monto','$fecharci');";
							$f_gastos=ejecutar($r_gastos);

							//CUENTAS POR TERCEROS
							if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$decrip='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0.0002;
									$r_gastos="insert into gastos_t_b
									(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
									id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
									values ('$proceso','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','
									$enfermedad','$id_proveedor','$tiposerv','$servicio','$monto','$monto','$monto','$fecharci');";
									$f_gastos=ejecutar($r_gastos);}
							}

							if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
							{

							$id_examen2=split("@",$idexamen1);
							$examen2=split("@",$examen1);
							$honorarios2=split("@",$honorarios1);
							$coment2=split("@",$coment1);

							$q="
							begin work;
							";
							for($i=0;$i<=$conexa;$i++){
								$id_examen=$id_examen2[$i];
								$examen=$examen2[$i];
								$monto=Formato_Numeros($honorarios2[$i]);
								$coment=$coment2[$i];
								if(!empty($id_examen) && $id_examen>0){

									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
								}
							}
							//CUENTAS POR TERCEROS
							if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$decrip='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0.0002;
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita)
							values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci');";
								}
							$q.="
							commit work;
							";
							$r=ejecutar($q);
							}

							if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10)){

							if ($montog>0){
								$montorecerva=0;
							$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$proceso','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
							$f_gastos=ejecutar($r_gastos);
							}
							if ($montoh>0){
								$montorecerva=$montoh;
							$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$proceso','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
							$f_gastos1=ejecutar($r_gastos1);
							}
							if ($montoo>0){
								$montorecerva=0;
								$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
							values ('$proceso','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
							$f_gastos2=ejecutar($r_gastos2);


								}
								}


							if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25) || ($servicio==9 and $tiposerv==21)) {

							if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
							$q_admin="select * from admin where admin.id_admin='$admin'";
							$r_admin=ejecutar($q_admin);
							$f_admin=asignar_a($r_admin);
							if ($f_admin[id_ciudad]==1)
								{
								$id_proveedor=96;
								}
								else
								{
									if ($f_admin[id_ciudad]==7)
								{
								$id_proveedor=64;
								}
								else
								{
									if ($f_admin[id_ciudad]==5)
								{
							$id_proveedor=928;
								}
								}
								}

							}

							$descri2=split("@",$descri1);
							$factor2=split("@",$factor1);
							$nombre2=split("@",$nombre1);
							$honorarios2=split("@",$honorarios1);

							$q="
							begin work;

							";
							for($i=0;$i<=$conexa;$i++){

								$descri=$descri2[$i];
								$factor=$factor2[$i];
								$nombre=$nombre2[$i];
								$monto=Formato_Numeros($honorarios2[$i]);
								if(!empty($factor) && $factor>0){

									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
							values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
								}
							}
							//CUENTAS POR TERCEROS
							if($montoTerceros>0){
									$nombre='SERVICIOS MEDICOS';
									$descri='SERVICIOS POR TERCEROS';
									$montorecerva=$montoTerceros;
									$monto=0;
									$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,unidades)
							values ('$proceso','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$factor');";
								}
							$q.="
							commit work;
							";
							$r=ejecutar($q);
							}



							/* **** Actualizo la cobertura**** */
							$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b";
							$r_actualizar=ejecutar($q_actualizar);
							while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

							$monto_actua=0;
							$monto_gastos=0;
							$q_propiedad="select * from propiedades_poliza,coberturas_t_b
							where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
							coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
							$r_propiedad=ejecutar($q_propiedad);
							$f_propiedad=asignar_a($r_propiedad);

							$q_cgastos="select * from gastos_t_b,procesos where
							gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
							procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
							gastos_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]'";
							$r_cgastos=ejecutar($q_cgastos);
							while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
							$monto_gastos= $monto_gastos +
							$f_cgastos[monto_aceptado];
							}

							if ($f_cobertura[id_beneficiario]==0)
							{
								$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}
							else
							{

							$monto_actual= $f_propiedad[monto] - $monto_gastos;
							$monto_actual=Formato_Numeros($monto_actual);
							}

							$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
							coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
							coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
							coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
							if($ActivaActualizaCovertura==true){
							$fmod_cobertura=ejecutar($mod_cobertura);}

							}
							/* **** Fin de Actualizar la cobertura**** */
							/* **** Se registra lo que hizo el usuario**** */
							$admin= $_SESSION['id_usuario_'.empresa];

							$log="REGISTRO COBERTURA ADICIONAL A LA ORDEN $proceso";
							logs($log,$ip,$admin);

							/* **** Fin de lo que hizo el usuario **** */
							?>





							<?php
							////////////////////////////////////////////////////////////////////////////////////////////////////
							/* **** fin de agregar mas gastos a un proceso existente registrar procesos por primera vez **** */
							////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				else
				{
						if ($proceso==0){

											/////////////////////////////
								/* **** registrar procesos por primera vez **** */
											/////////////////////////////


								/* **** Buscamos las Especialidad **** */
								$admin= $_SESSION['id_usuario_'.empresa];

								/* **** Empezamos a Verificar Que tipo de servicio es Para Procesar la Descarga **** */

								/* **** Si es Distinto de Consulta Preventiva**** */
								$q_cobertura="select * from entes,titulares,coberturas_t_b where
								 entes.id_ente=titulares.id_ente and
								titulares.id_titular=coberturas_t_b.id_titular and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cobertura=ejecutar($q_cobertura);
								$f_cobertura=asignar_a($r_cobertura);

								if ($f_cobertura[id_beneficiario]==0){
								$fechainicio="$f_cobertura[fecha_inicio_contrato]";
								$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
								}
								else
								{
								$fechainicio="$f_cobertura[fecha_inicio_contratob]";
								$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
								}
								if ($fechare<$fechainicio) {
									$gastosviejo='1';
									}
									else
									{
										$gastosviejo='0';
										}
								if ($tiposerv==5)
								{

								$q_especialidad="select * from
								especialidades_medicas,s_p_proveedores,proveedores where
								especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
								s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
								proveedores.id_proveedor='$id_proveedor'";
								$r_especialidad=ejecutar($q_especialidad);
								$num_filas=num_filas($r_especialidad);
								if ($num_filas==0){
									$q_tiposer="select * from
								tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
								$r_tiposer=ejecutar($q_tiposer);
								$f_tiposer=asignar_a($r_tiposer);
								$especialidad=	$f_tiposer[tipo_servicio];
								$id_especialidad=0;
									}
									else
									{
								$f_especialidad=asignar_a($r_especialidad);
								$especialidad=$f_especialidad[especialidad_medica];
								$id_especialidad=$f_especialidad[id_especialidad_medica];
								}
								/* **** Fin de Buscar Especialidad **** */

								$orden="CONSULTA PREVENTIVA";
								/* **** Si es Igual a Consulta Preventiva Cargar Orden **** */
								$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,fecha_ent_pri,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$fecha_privada','$codigo');";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);


								$r_gastos="insert into gastos_t_b
								(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
								id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
								values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','0','
								$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
								$f_gastos=ejecutar($r_gastos);

								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$decrip='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$r_gastos="insert into gastos_t_b
										(id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
										id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
										values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','0','
										$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
										$f_gastos=ejecutar($r_gastos);
									}


								$r_preventiva="insert into consultas_preventivas (id_titular,id_beneficiario,id_especialidad_medica,especialidad_medica,fecha_creado,hora_creado,id_proceso)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','$id_especialidad','$especialidad','$fechacreado','$hora','$f_cproceso[id_proceso]');";
								$f_preventiva=ejecutar($r_preventiva);

								$log="REGISTRO LA ORDEN DE PREVENTIVA CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];

								}
								/* **** Fin de Cargar Consulta Preventiva **** */

								if (($tiposerv==7) || ($tiposerv==11 || $tiposerv==14 || $tiposerv==15 || $tiposerv==16 || $tiposerv==17) || ($servicio==14 and $tiposerv==19) )
								{

								$q_especialidad="select * from
								especialidades_medicas,s_p_proveedores,proveedores where
								especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
								s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
								proveedores.id_proveedor='$id_proveedor'";
								$r_especialidad=ejecutar($q_especialidad);
								$num_filas=num_filas($r_especialidad);
								if ($num_filas==0){
									$q_tiposer="select * from
								tipos_servicios where tipos_servicios.id_tipo_servicio='$tiposerv'";
								$r_tiposer=ejecutar($q_tiposer);
								$f_tiposer=asignar_a($r_tiposer);
								$especialidad=	$f_tiposer[tipo_servicio];
								$id_especialidad=0;
									}
									else
									{
								$f_especialidad=asignar_a($r_especialidad);
								$especialidad=$f_especialidad[especialidad_medica];
								$id_especialidad=$f_especialidad[id_especialidad_medica];
								}
								/* **** Fin de Buscar Especialidad **** */

								if ($tiposerv==7)
								{
								$orden="CONSULTAS MEDICAS";
								}
								if ($tiposerv==11)
								{
								$orden="ORDEN DE LENTES";
								}

								if ($tiposerv==14 )
								{
								$orden="HONORARIOS MEDICOS DE EMERGENCIA";
								}
								if ($tiposerv==15)
								{
								$orden="HONORARIOS MEDICOS HOSPITALIZACION";
								}

								if ($tiposerv==16 || $tiposerv==17)
								{
								$orden="DISPONIBILIDAD";
								}
								if ($tiposerv==19)
								{
								$orden="CIRUGIA AMBULATORIA HONORARIOS MEDICOS";
								}

								/* **** Si es Igual a Consulta Medica Cargar Orden o cirugia ambulatoria honorarios medicos o disponibilidad o solicitud de procedimiento **** */
								$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,fecha_ent_pri,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope',' ',' ','$admin','$gastosviejo','$numpre','$fecha_privada','$codigo');";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);


								$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
								values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
								$f_gastos=ejecutar($r_gastos);

								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$decrip='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
										values ('$f_cproceso[id_proceso]','$organo','$especialidad','$decrip','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
										$f_gastos=ejecutar($r_gastos);
									}


								/* **** Actualizo la cobertura**** */
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b
								where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where
								gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
								procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
								gastos_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
								}

								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
									$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}

								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
								coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
								coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
								coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
								if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);}


								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */

								$log="REGISTRO LA ORDEN DE $orden CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];

								/* **** Fin de lo que hizo el usuario **** */

								}
								/* **** Fin de Cargar Consulta Medica **** */


								/* **** Si es Igual a Curativa o examenes especiales Cargar Orden **** */

								if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12))
								{
								if ($tiposerv==6)
								{
								$orden="DE ATENCION";
								}
								if ($tiposerv==8)
								{
								$orden="EMERGENCIAS";
								}
								if ($tiposerv==12)
								{
								$orden="HOSPITALIZACION AMBULATORIA";
								}


								if ($examenes>0){
								$q_texamen=("select * from tipos_imagenologia_bi  where tipos_imagenologia_bi.id_tipo_imagenologia_bi='$examenes'");
								$r_texamen=ejecutar($q_texamen);
								$f_texamen=asignar_a($r_texamen);
								$decrip=$f_texamen[tipo_imagenologia_bi];

								}

								$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$numpre','$codigo');";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);

								$id_examen2=split("@",$idexamen1);
								$examen2=split("@",$examen1);
								$honorarios2=split("@",$honorarios1);
								$coment2=split("@",$coment1);

								$q="
								begin work;
								";
								for($i=0;$i<=$conexa;$i++){
									$id_examen=$id_examen2[$i];
									$examen=$examen2[$i];
									$monto=Formato_Numeros($honorarios2[$i]);
									$coment=$coment2[$i];
									if(!empty($id_examen) && $id_examen>0){

										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";
									}
								}
								//CUENTAS POR TERCEROS
								if($montoTerceros>0){
										$nombre='SERVICIOS MEDICOS';
										$decrip='SERVICIOS POR TERCEROS';
										$montorecerva=$montoTerceros;
										$monto=0.0002;
										$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,comentarios,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita)
										values ('$f_cproceso[id_proceso]','$organo','$decrip','$examen','$fechacreado','$hora','$coment','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac');";

									}
								$q.="
								commit work;
								";
								$r=ejecutar($q);


								/* **** Actualizo la cobertura**** */
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
								and coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
								}
								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
									$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}
								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
								if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);}

								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */

								$log="REGISTRO LA ORDEN $orden 	CURATIVAS Y/O EXAMENES ESPECIALES CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];

								/* **** Fin de lo que hizo el usuario **** */

								}
								/* **** Fin de Cargar Curativas o examenes especiales **** */


								/* **** cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */
								if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13) ){
									if ($servicio==2)
								{
								$orden="CLAVE DE EMERGENCIA";
								}
									if ($servicio==3)
								{
								$orden="CARTA DE COMPROMISO";
								}
									if ($servicio==8)
								{
								$orden="VICIOS DE REFRECCION";
								}
									if ($servicio==11)
								{
								$orden="MATERNIDAD";
								}
									if ($servicio==13)
								{
								$orden="CARTA AMBULATORIO";
								}
									$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$codigo');";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);

								if ($montog>0){
									$montorecerva=0;
								$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
								$f_gastos=ejecutar($r_gastos);
								}
								if ($montoh>0){
									$montorecerva=$montoh;
								$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
								$f_gastos1=ejecutar($r_gastos1);
								}
								if ($montoo>0){
									$montorecerva=0;
									$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
								$f_gastos2=ejecutar($r_gastos2);


									}


								/* **** Actualizo la cobertura**** */
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b
								where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where
								gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
								procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
								gastos_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos +
								$f_cgastos[monto_aceptado];
								}

								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}

								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
								coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
								coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
								coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
								if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);}


								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */

								$log="REGISTRO $orden  CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];

								/* **** Fin de lo que hizo el usuario **** */

								}

								/* **** fin de cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */


								/* **** cargar reembolso hcm) **** */
								if ($tiposerv==0 and ($servicio==10) ){

									$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,codigo)
								values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo',$codigo);";
								$f_proceso=ejecutar($r_proceso);

								$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
								$r_cproceso=ejecutar($q_cproceso);
								$f_cproceso=asignar_a($r_cproceso);


								if ($montog>0){
									$montorecerva=0;
								$r_gastos="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','GASTOS CLINICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montog','$montog','1','$fecharci');";
								$f_gastos=ejecutar($r_gastos);
								}

								if ($montoh>0){
									$montorecerva=$montoh;
								$r_gastos1="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','HONORARIOS MEDICOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoh','$montoh','0','$fecharci');";
								$f_gastos1=ejecutar($r_gastos1);
								}
								if ($montoo>0){
									$montorecerva=0;
									$r_gastos2="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita)
								values ('$f_cproceso[id_proceso]','$organo','$decrip','OTROS GASTOS','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','0','$servicio','$numpro','$montorecerva','$montoo','$montoo','0','$fecharci');";
								$f_gastos2=ejecutar($r_gastos2);


									}


								/* **** Actualizo la cobertura**** */
								$q_propiedad="select * from propiedades_poliza,coberturas_t_b
								where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
								coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
								$r_propiedad=ejecutar($q_propiedad);
								$f_propiedad=asignar_a($r_propiedad);

								$q_cgastos="select * from gastos_t_b,procesos where
								gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
								procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
								gastos_t_b.id_cobertura_t_b='$id_cobertura'"; $r_cgastos=ejecutar($q_cgastos);
								while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
								$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
								}

								if ($f_cobertura[id_beneficiario]==0)
								{
									$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
									}
								else
								{

								$monto_actual= $f_propiedad[monto] - $monto_gastos;
								$monto_actual=Formato_Numeros($monto_actual);
								}

								$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
								coberturas_t_b.id_cobertura_t_b='$id_cobertura' and
								coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
								coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
								if($ActivaActualizaCovertura==true){
								$fmod_cobertura=ejecutar($mod_cobertura);}


								/* **** Fin de Actualizar la cobertura**** */
								/* **** Se registra lo que hizo el usuario**** */

								$log="REGISTRO EL REEMBOLSO HCM  CON ORDEN NUMERO $f_cproceso[id_proceso]";
								logs($log,$ip,$admin);
								$proceso=$f_cproceso[id_proceso];
								/* **** Fin de lo que hizo el usuario **** */

								}

								/* **** fin de cargar carta de compromiso (maternidad, vicios de refraccion, clave de emergencia,) **** */


								/* **** guardar gastos de emeregencia o de hospitalizacion **** */

								if (($servicio==6 and $tiposerv==9) || ($servicio==9 and $tiposerv==13) || ($servicio==6 and $tiposerv==20) || ($servicio==6 and $tiposerv==25) || ($servicio==9 and $tiposerv==21) )
												{

														$q_admin="select * from admin where id_admin='$admin'";
														$r_admin=ejecutar($q_admin);
														$f_admin=asignar_a($r_admin);

															if ($tiposerv==9 || $tiposerv==20)
																{
																if ($tiposerv==9)
																	{     /* **** busco los numeros de planilla  para saber cual es la ultimo**** */
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

																		$orden="GASTOS DE EMERGENCIA";
																	}
															if ($tiposerv==13 || $tiposerv==21)
															{
																		$orden="HOSPITALIZACION AMBULATORIA";
																	}

								if($id_proveedor=='' || $id_proveedor==0 || $id_proveedor<=0){
																if ($f_admin[id_ciudad]==1)
																	{
																		$id_proveedor=96;
																	}
																	else
																	{
																if ($f_admin[id_ciudad]==7)
																{
																	$id_proveedor=64;
																}
																else
																{
																		if ($f_admin[id_ciudad]==5)
																			{
																				$id_proveedor=928;
																			}
																		}
																	}
														}

														$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
														values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope','',' ','$admin','$gastosviejo','$numplani','$codigo');";
														$f_proceso=ejecutar($r_proceso);

														$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
														$r_cproceso=ejecutar($q_cproceso);
														$f_cproceso=asignar_a($r_cproceso);

														$descri2=split("@",$descri1);
														$factor2=split("@",$factor1);
														$nombre2=split("@",$nombre1);
														$honorarios2=split("@",$honorarios1);

														$q="
														begin work;

														";


														for($i=0;$i<=$conexa;$i++){

															$descri=$descri2[$i];
															$factor=$factor2[$i];
															$nombre=$nombre2[$i];
															$monto=Formato_Numeros($honorarios2[$i]);
															if(!empty($factor) && $factor>0){

																$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita,unidades)
														values ('$f_cproceso[id_proceso]','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac','$factor');";
															}
														}
														//CUENTAS POR TERCEROS
														if($montoTerceros>0){
																$nombre='SERVICIOS MEDICOS';
																$descri='SERVICIOS POR TERCEROS';
																$montorecerva=$montoTerceros;
																$monto=0.0002;
																$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita,unidades)
														values ('$f_cproceso[id_proceso]','$organo','$descri','$nombre','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv','$servicio','$montorecerva','$monto','$monto','$fecharci','$horac','$factor');";

															}
														$q.="
														commit work;
														";
														$r=ejecutar($q);
											/* **** Actualizo la cobertura**** */
														$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
														and coberturas_t_b.id_cobertura_t_b=$id_cobertura";
														$r_propiedad=ejecutar($q_propiedad);
														$f_propiedad=asignar_a($r_propiedad);

														$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura";
														$r_cgastos=ejecutar($q_cgastos);
														while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
															$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
															$monto_gastos=Formato_Numeros($monto_gastos);
														}

														if ($f_cobertura[id_beneficiario]==0)
														{
															$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
															$monto_actual=Formato_Numeros($monto_actual);

															}
														else
														{

														$monto_actual= $f_propiedad[monto] - $monto_gastos;
														$monto_actual=Formato_Numeros($monto_actual);

														}

														$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
														if($ActivaActualizaCovertura==true){
														$fmod_cobertura=ejecutar($mod_cobertura);}

														/* **** Fin de Actualizar la cobertura**** */
														/* **** Se registra lo que hizo el usuario**** */

														$log="REGISTRO de $orden  CON ORDEN NUMERO $f_cproceso[id_proceso]";
														logs($log,$ip,$admin);
														$proceso=$f_cproceso[id_proceso];
														/* **** Fin de lo que hizo el usuario **** */
								}

								/* **** fin de guardar gastos de emeregencia o de hospitalizacion **** */



								/* **** guardar reembolso ambulatorio **** */

							if (($servicio==1) || ($servicio==14 and $tp==18)) {

													if ($servicio==1)
													{
													$orden='REEMBOLSO AMBULATORIO';
													}
													else
													{
													$orden='CIRUGIA AMBULATORIA GASTOS CLINICOS';

												$q_admin="select * from admin where admin.id_admin=$admin";
												$r_admin=ejecutar($q_admin);
												$f_admin=asignar_a($r_admin);
												if ($f_admin[id_ciudad]==1)
													{
													$id_proveedor=96;
													}
													else
													{
														if ($f_admin[id_ciudad]==7)
													{
													$id_proveedor=64;
													}
													else
													{
														if ($f_admin[id_ciudad]==5)
													{
												$id_proveedor=928;
													}
													}
													}
												}

												$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,gasto_viejo,nu_planilla,codigo)
												values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','2','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$gastosviejo','$numpre','$codigo');";
												$f_proceso=ejecutar($r_proceso);

												$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
												$r_cproceso=ejecutar($q_cproceso);
												$f_cproceso=asignar_a($r_cproceso);



												$descri2=split("@",$descri1);
												$tiposerv2=split("@",$tiposerv1);
												$nombre2=split("@",$nombre1);
												$factura2=split("@",$factura1);
												$honorarios2=split("@",$honorarios1);
												$t2=split("@",$t1);
												$fechacf2=split("@",$fechacf1);
												$fechaci2=split("@",$fechaci1);
												$tc2=split("@",$tc1);

												$q="
												begin work;
												";
												for($i=0;$i<=$conexa;$i++){

													$descri=$descri2[$i];
													$tiposerv=$tiposerv2[$i];
													$nombre=$nombre2[$i];
													$factura=$factura2[$i];
													$monto=Formato_Numeros($honorarios2[$i]);
													$t=$t2[$i];
													$fechacf=$fechacf2[$i];
													$fechaci=$fechaci2[$i];
													$tc=$tc2[$i];


													if(!empty($tiposerv) && $tiposerv>0){
														if ($tc=="on"){
															$fecharci="$fechaci";
															}
														$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor
												,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
												values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv'
												,'$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
													}
												}
												//CUENTAS POR TERCEROS
												if($montoTerceros>0){
														$nombre='SERVICIOS MEDICOS';
														$descri='SERVICIOS POR TERCEROS';
														$montorecerva=$montoTerceros;
														$monto=0.0002;
														$q.="insert into gastos_t_b (id_proceso,id_organo,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor
												,id_tipo_servicio,id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,continuo,unidades,fecha_continuo)
												values ('$f_cproceso[id_proceso]','$organo','$nombre','$descri','$fechacreado','$hora','$id_cobertura','$enfermedad','$id_proveedor','$tiposerv'
												,'$servicio','$factura','$montorecerva','$monto','$monto','$fecharci','$tc','$t','$fechacf');";
													}
												$q.="
												commit work;
												";
												$r=ejecutar($q);


												/* **** Actualizo la cobertura**** */
												$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza
												and coberturas_t_b.id_cobertura_t_b=$id_cobertura";
												$r_propiedad=ejecutar($q_propiedad);
												$f_propiedad=asignar_a($r_propiedad);

												$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura";
												$r_cgastos=ejecutar($q_cgastos);
												while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
												$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
												}

												if ($f_cobertura[id_beneficiario]==0)
												{
													$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
													$monto_actual=Formato_Numeros($monto_actual);
													}
												else
												{

												$monto_actual= $f_propiedad[monto] - $monto_gastos;
													$monto_actual=Formato_Numeros($monto_actual);
												}

												$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
												if($ActivaActualizaCovertura==true){
													$fmod_cobertura=ejecutar($mod_cobertura);
													}
												/* **** Fin de Actualizar la cobertura**** */
												/* **** Se registra lo que hizo el usuario**** */

												$log="REGISTRO $orden CON ORDEN NUMERO $f_cproceso[id_proceso]";
												logs($log,$ip,$admin);
												$proceso=$f_cproceso[id_proceso];
												/* **** Fin de lo que hizo el usuario **** */
									}

								/* **** fin de guardar reembolso **** */
								/* **** modificar el procesos y gastos de este proceso si es donativo **** */

									if ($donativo>=1){
									$mod_prodon="update procesos set id_estado_proceso=1,donativo='$donativo' where
									procesos.id_proceso=$f_cproceso[id_proceso]";
									$fmod_prodon=ejecutar($mod_prodon);
									/* **** modificar el procesos  si es donativo **** */
									}
								}
							}
			}
			?>

			<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
			<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

			<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

			<?php
			if ($edoproceso==13)
			{ //ESTADO EN ESPERA
				$pro=$proceso;
			?>


			<tr>
			<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> que Estaba en Espera se les Registro sus gastos con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso ?>"   ><a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>
			</tr>
			<tr>
			<td colspan=4 class="titulo_seccion">Imprimir </td>
			</tr>
			<?php
			}

			if ($proceso>0 && $edoproceso<>13){
				$pro=$proceso;
			?>



			<tr>
			<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se les Registro sus gastos o cobertura adicional con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
			</tr>
			<tr>
			<td colspan=4 class="titulo_seccion">Imprimir </td>
			</tr>
			<?php
			}

			if ($proceso==0){
				$pro=$f_cproceso[id_proceso];
			?>


			<tr>
			<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $f_cproceso[id_proceso] ?> Se Registro con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $f_cproceso[id_proceso]  ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
			</tr>
			<tr>
			<td colspan=4 class="titulo_seccion">Imprimir </td>
			</tr>
			<?php
			}

			if ($servicio==1 || $servicio==10){
			?>

			<tr>
					<td class="tdtitulos"><?php
						$url="'views01/ireembolso.php?proceso=$pro&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
						</td>
				</tr>


			<?php

			}
			else
			{
				if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) )
				{
			?>

				<tr>
					<td class="tdtitulos"><?php
						$url="'views01/irevisionc.php?proceso=$pro'";
						?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
						<?php
						$url="'views01/icarta.php?proceso=$pro&si=1'";
						?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
						<?php
						$url="'views01/icarta.php?proceso=$pro&si=0'";
						?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
						<?php
						$url="'views01/irecepcion.php?proceso=$pro&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Recibo de Recepcion</a>
							</td>
				</tr>

				<?php
				}
				else
				{
				?>
			<tr>
					<td class="tdtitulos"><?php
						$url="'views01/iorden.php?proceso=$pro&si=1'";
						?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
						$url="'views01/iorden.php?proceso=$pro&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
						$url="'views01/irevision.php?proceso=$pro'";
						?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
						<?php
						$url="'views01/irecepcion.php?proceso=$pro&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Recibo de Recepcion</a>
						<?php
						$url="'views01/iasesoria.php?proceso=$pro&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Asesoria Medica</a>



			<?php
						$url="'views01/iordenb.php?proceso=$pro&si=1'";
						?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a>



						<?php
				if ($f_cproceso[nu_planilla]>=1){
						$url="'views01/isolicitudmedicamento.php?proceso=$f_cproceso[nu_planilla]&si=1'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamento </a>
							<?php
					$url="'views01/iplanilla_ingreso.php?proceso=$f_cproceso[nu_planilla]&si=1'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Planilla de Ingreso </a>

						<?php
						$url="'views01/ipresupuesto.php?proceso=$f_cproceso[nu_planilla]&si=1'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
						<?php
						$url="'views01/ipresupuestop.php?proceso=$f_cproceso[nu_planilla]&si=1'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
						<?php $url="'views01/ifpresupuestop.php?proceso=$f_cproceso[nu_planilla]&si=1'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>

						<?php }

						if ($f_cproceso[nu_planilla]>=1 and $tp==18){
						$url="'views01/icartaamb.php?proceso=$f_cproceso[nu_planilla]&si=0'";
						?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Carta Cirugia </a>
					<?php }
						?>



						</td>
				</tr>
			<?php
			}
			}

			if ($donativo==1) {
			?>
			<tr> <td colspan=4 class="titulo_seccion"> Imprimir Cartas de Donativos</td></tr>
			<tr>
					<td  class="tdtitulos"><?php
						$url="'views01/iacta.php?proceso=$pro'";
						?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Acta</a>
						<?php
						$url="'views01/isolicitud.php?proceso=$pro'";
						?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Solicitud</a>
						</td>
			</tr>
			<?php
			}
			if ($num_filasf>0){
			?>
			<tr> <td colspan=4 class="titulo_seccion"><a href="#" OnClick="reg_factura();" class="boton">Facturacion</a></td></tr>
			<?php
			}
			?>
			</table>

			<?php
					if($ActivaActualizaCovertura==false){
								//se usara tikera no se tocara la covertura
								///registrar nuevo ticket para la tickera
								$ConTickera=("select * from tbl_tickeras where id_tickera='$id_tickera';");
								$RespTickera=ejecutar($ConTickera);
								$datatickera=assoc_a($RespTickera);
								$serialTickera=$datatickera['serial_tikera'];
								$catidadTickes=$datatickera['catidad_tike'];
								///consultar tickes
								$ConTicket=("select * from tbl_ticket where id_tickera='$id_tickera';");
								$RespTicket=ejecutar($ConTicket);
								$cuantos=num_filas($RespTicket);
								if($cuantos<=0){
										$ticket=1;
								}else{
										$ticket=$cuantos+1;
										}
										if($decrip==''){$decrip='servicio';}
								$sqlRegTicket="insert into tbl_ticket(ticket,id_tickera,id_proceso,id_servicio,nota,status,serial_ticket)
													values ('$ticket','$id_tickera','$proceso','$servicio','$decrip',0,'$serialTickera')";
								$tiket=ejecutar($sqlRegTicket);
													echo"<h1>REGISTRO DE TICKET</h1>";
													if($cuantos>=$catidadTickes)
													{//la tikera ha esta agotada
													  if($EstadoTickera<>2){
													    //actualizar el estado de la tikera
													    $sqlactivarticket=("update tbl_tickeras set status='2' where id_tickera='$id_tickera';");
													    $VerfTikera=ejecutar($sqlactivarticket);
													   }
													}

								$comentariosticke="Este preceso ha sido usado con el ticket $serialTickera";
									////ACTUALIZAR PROCESO PARA SEÑALAR QUE FUE REGISTRADO POR TICKERA
								$mod_proceso="update procesos set id_estado_proceso='7' ,comentarios='$comentariosticke', no_clave='$serialTickera',nu_planilla='$serialTickera' where procesos.id_proceso='$proceso'";
								$fmod_proceso=ejecutar($mod_proceso);
					}
					///fin usar  tikera

}
}
?>
