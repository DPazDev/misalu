<?php
include ("../../lib/jfunciones.php");
sesion();
$opera1=$_SESSION['toperacion'];
$cedulaclien=$_POST['cliencedul'];
$clientetipo=$_POST['eltipcliente'];
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
if($clientetipo==1){
       $querytente="and entes.id_tipo_ente<>4";
    }else{
        $querytente="and (entes.id_tipo_ente=4 or entes.id_tipo_ente=6)";
    }

$datosclien=("select clientes.id_cliente,clientes.nombres,clientes.apellidos from clientes where clientes.cedula='$cedulaclien';");
$repdatosclien=ejecutar($datosclien);
$cuantoshay=num_filas($repdatosclien);
$tiposervicio=("select tipos_servicios.id_tipo_servicio,tipos_servicios.id_servicio,
tipos_servicios.tipo_servicio from tipos_servicios where
(tipos_servicios.id_tipo_servicio=22 or tipos_servicios.id_tipo_servicio=23 or tipos_servicios.id_tipo_servicio=24);");
$reptiposervi=ejecutar($tiposervicio);
$fecha=date("Y-m-d");
$_SESSION['matriz']=array();
$_SESSION['matrizt']=array();
$_SESSION['pasopedido']=0;

if($cuantoshay==0){
	echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
      <tr>
         <td colspan=8 class=\"titulo_seccion\">Cliente con la c&eacute;dula $cedulaclien no existe !!</td>
       </tr>
     </table>";
}else{

	$infoclient=assoc_a($repdatosclien);
	$nomcompleto="$infoclient[nombres]  $infoclient[apellidos]";
	$idcliente=$infoclient['id_cliente'];
	//para ver si el cliente es un titular
	$buscarToB=("select titulares.id_titular,titulares.id_ente,entes.nombre from titulares,estados_t_b,entes
                           where
                          titulares.id_cliente=$idcliente and titulares.id_titular=estados_t_b.id_titular
                          and estados_t_b.id_estado_cliente=4 and estados_t_b.id_beneficiario=0 and
						  titulares.id_ente=entes.id_ente $querytente;");
    $repbuscarToB=ejecutar($buscarToB);
	$cuantoshayToB=num_filas($repbuscarToB);
	$estitu=1;
    $cuantos1=num_filas($repbuscarToB);
	$mensajeT="Cliente registrado como titular del ente:";
	if($cuantoshayToB==0){
		//es por que es un Beneficiario
		 $buscarinfoBens=("select beneficiarios.id_beneficiario, beneficiarios.id_titular,entes.nombre
from beneficiarios, estados_t_b,entes,titulares
where beneficiarios.id_cliente=$idcliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_titular=beneficiarios.id_titular and
estados_t_b.id_estado_cliente=4 and
beneficiarios.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente $querytente;");
		$repbuscarinfoBen1=ejecutar($buscarinfoBens);
		$estitu=2;
		$cuantos2=num_filas($repbuscarinfoBen1);
		}else{
			//tambien busco a ver si el titular esta com beneficiario
			  $buscarinfoBen=("select beneficiarios.id_beneficiario, beneficiarios.id_titular,entes.nombre
from
beneficiarios,estados_t_b,entes,titulares
where beneficiarios.id_cliente=$idcliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_titular=beneficiarios.id_titular and
estados_t_b.id_estado_cliente=4 and
beneficiarios.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente $querytente;");
             $repbuscarinfoBen=ejecutar($buscarinfoBen);
			  $cuatocoTB=num_filas($repbuscarinfoBen);
              $cuantos3=num_filas($repbuscarinfoBen);
			  if($cuatocoTB==0){
				 $loqhay=0;
				} else{
					$loqhay=1;
					}
			 $esTyB=1;
			}
             if(($cuantos1==0)&&($cuantos2==0)&&($cuantos3==0)){
            echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
       <br>
      <tr>
         <td colspan=8 class=\"titulo_seccion\">No hay informaci&oacute;n</td>
       </tr>
     </table>	";
      }else{ ?>
	<input type=hidden id='quees' value='<?echo $loqhay?>'>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
       <br>
      <tr>
         <td colspan=8 class="titulo_seccion">Datos personales del cliente</td>
       </tr>
     </table>
	 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
      <br>
     <tr>
        <td class="tdtitulos">Nombre completo:</td>
        <td class="tdcampos"><?echo $nomcompleto;?></td>
     </tr>
	<?php
	          if($estitu==1){
				$buscarinforglo=$repbuscarToB;
                                $query2='and coberturas_t_b.id_beneficiario=0';
			  }else{
				        $buscarinforglo=$repbuscarinfoBen1;
                                        $query2='';
				       }
			 $r=1;
       /////////////////////////PLANILALS
       ///////////////////CONSULTAR PLANILLAS ACTIVAS PARA EL USAURIO//////
       $idente=$grupoarti['id_ente'];
       $fechaActual=date('Y-m-d');//fecha Actual
       $fechaAnterior=date('Y-m-d',strtotime("-1 days"));//2 dias antes
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
                      clientes.cedula='$cedulaclien'  and
                      procesos.id_titular=titulares.id_titular and
                      titulares.id_cliente=clientes.id_cliente
                 group by
                       nu_planilla limit 2;");
      $PlanilasActivas=ejecutar($sqlplanillasActivas);
      if($num_planilals=num_filas($PlanilasActivas)>0){
         while($planilas=asignar_a($PlanilasActivas,NULL,PGSQL_ASSOC)){
           $PlanildeUsuario=$planilas['nu_planilla'];
           //consultar si la planilla tiene procesos facturados
           $sqlProcesoFActurados=("select count(*) as cantidaf from tbl_procesos_claves,procesos where procesos.id_proceso=tbl_procesos_claves.id_proceso and procesos.nu_planilla='$PlanildeUsuario';");
             $procesoFacturaPln=ejecutar($sqlProcesoFActurados);
             $CanFactProce=asignar_a($procesoFacturaPln,NULL,PGSQL_ASSOC);
             if($CanFactProce['cantidaf']<1){
                 $PlanilalsActivas.="<a href='#' OnClick=(document.getElementById('nopresupuesto').value='$PlanildeUsuario') class='boton_2' title='numero de presupuestos activo para este cliente'>$PlanildeUsuario</a> ";///Planillas que el Cliente posee Actualmente
               }else{$PlanilalsActivas.='';}
         }
       }

       ////////////////FIN //////////////////////

    while($grupoarti=asignar_a($buscarinforglo,NULL,PGSQL_ASSOC)){

      	  if($estitu>1){
    					$dequienes=("select clientes.nombres,clientes.apellidos from clientes,titulares
                                               where
    										   clientes.id_cliente=titulares.id_cliente and
    										   titulares.id_titular=$grupoarti[id_titular]
                                               ;");
    					$repdequienes=ejecutar($dequienes);
    					$dataquienes=assoc_a($repdequienes);
    					$nomtitquien="$dataquienes[nombres]  $dataquienes[apellidos]";
    					$mensajeT="Cliente registrado como beneficiario al titular $nomtitquien en el ente:";
					  }
                 echo"<tr>
                               <td class=\"tdtitulos\">$mensajeT</td>
                               <td class=\"tdcampos\"> $grupoarti[nombre]</td>
                               <td class=\"tdtitulos\">Seleccionar Cobertura</td>";
                            $paravesT=$grupoarti[id_titular];
                            $paravesB=$grupoarti[id_beneficiario];
                if($paravesB>0){
								          $query2="and coberturas_t_b.id_beneficiario=$paravesB";
								  }
                             $lascobturas=("select coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_propiedad_poliza,
                                            coberturas_t_b.monto_actual,propiedades_poliza.cualidad
                                            from
                                            propiedades_poliza,coberturas_t_b
                                            where
				            coberturas_t_b.id_titular=$paravesT and
				            coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
				            and coberturas_t_b.id_organo<=1 $query2 order by propiedades_poliza.cualidad;");
                             $replascobturas=ejecutar($lascobturas);

                               $selectcober="laspolizas$r";

                               echo"<td class=\"tdcampos\">";
                               echo"<select id=\"$selectcober\" class=\"campos\"  style=\"width: 230px;\" >
			            <option value=\"\"></option>";
			         while($polizason=asignar_a($replascobturas,NULL,PGSQL_ASSOC)){
					echo"<option value=\"$polizason[id_cobertura_t_b]\">
						$polizason[cualidad]---$polizason[monto_actual]
				         </option>";
			          }
					  if($estitu==1){
						 $esun='T';
						 $parbuscarid= $paravesT;
                                                 $ventamensaje="Titular $nomcompleto";
						}else{
							if(($estitu==2)|| ($esTyB==1))
							 $esun='B';
                                                         $ventamensaje="Beneficiario(a) $nomcompleto";
							$parbuscarid= $paravesB;
							}
		         echo"</select>
                          </td>
                          </tr>
                          <tr>
                          <td class=\"tdcampos\"><a href=\"views01/farmaext3.php?tipoc=$esun&idbusq=$parbuscarid&tipooper=GC\" title=\"Ver los gastos del $ventamensaje\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;\">Gastos Clientes</a></td>
                              <td class=\"tdcampos\"><a href=\"views01/farmaext3.php?tipoc=$esun&idbusq=$parbuscarid&tipooper=TC\" title=\"Ver los tratamientos continuo del $ventamensaje\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;\">Tratamiento Continuo</a></td> ";


          			    $r++;
			   if($r>1){
			     echo "<tr><td colspan=7><hr></td></tr>";
			  }
			}//finnnnnnnnnnnnn del primer while





			echo"<input type=\"hidden\" id=\"valor\" value=$r>";
			if($esTyB==1){
				  $tb=1;
				  while($grupoartis=asignar_a($repbuscarinfoBen,NULL,PGSQL_ASSOC)){
					$cualeselTI=$grupoartis['id_titular'];
					$DatoClT=("select clientes.nombres,clientes.apellidos from clientes,titulares
									   where clientes.id_cliente=titulares.id_cliente and
									    titulares.id_titular=$cualeselTI;");
					$repDatoCIT=ejecutar($DatoClT);
					$datosDatoCIT=assoc_a($repDatoCIT);
					$nomcompletCIT="$datosDatoCIT[nombres]  $datosDatoCIT[apellidos]";
                 echo"<tr>
                               <td class=\"tdtitulos\">Titular registrado com beneficiario al <br> Titular $nomcompletCIT del ente:</td>
                               <td class=\"tdcampos\"> $grupoartis[nombre]</td>
                               <td class=\"tdtitulos\">Seleccionar Cobertura</td>
                               <td class=\"tdcampos\">";
                         $buscarlosTcB=("select coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_propiedad_poliza,
                                         coberturas_t_b.monto_actual,propiedades_poliza.cualidad
                                         from
                                         propiedades_poliza,coberturas_t_b
                                         where
                                         coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
                                         and coberturas_t_b.id_organo<=1 and
                                         coberturas_t_b.id_beneficiario=$grupoartis[id_beneficiario] order by propiedades_poliza.cualidad");
                          $repbuscrlosTcB=ejecutar($buscarlosTcB);
						  $cajatb="laspolizastb$tb";
                          echo"<select id=\"$cajatb\" class=\"campos\"  style=\"width: 230px;\" >
			            <option value=\"\"></option>";
			         while($polizasonTB=asignar_a($repbuscrlosTcB,NULL,PGSQL_ASSOC)){
					echo"<option value=\"$polizasonTB[id_cobertura_t_b]\">
						$polizasonTB[cualidad]---$polizasonTB[monto_actual]
				         </option>";
			          }
		         echo"</select>
                         </td>
                          </tr>
                          <tr>
                             <td class=\"tdcampos\"><a href=\"views01/farmaext3.php?tipoc=B&idbusq=$grupoartis[id_beneficiario]&tipooper=GC\" title=\"Ver los gastos del Beneficiario(a) $nomcompleto registrado al titular $nomcompletCIT\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;\">Gastos Clientes</a></td>
                              <td class=\"tdcampos\"><a href=\"views01/farmaext3.php?tipoc=B&idbusq=$grupoartis[id_beneficiario]&tipooper=TC\" title=\"Ver los tratamientos continuo del Beneficiario(a) $nomcompleto registrado al titular $nomcompletCIT\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;\">Tratamiento Continuo</a></td>";
				echo "<tr><td colspan=7><hr></td></tr>";
				$tb++;
				}
				echo"<input type=\"hidden\" id=\"totcb\" value=$tb>";
			}

	?>
	  <tr>
             <td class="tdtitulos">Fecha de Recepci&oacute;n:</td>
             <td class="tdcampos"> <input  type="text" size="10" id="Fini" class="campos" maxlength="10" value="<?echo $fecha?>">
	       <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	       <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
          </tr>
          <tr>
            <td class="tdtitulos">No. Planilla:</td>
            <td class="tdcampos"><input type="text" id="nopresupuesto" class="campos" size="35" onblur="PlanillaUso()"><?php if($PlanilalsActivas!=""){echo"Planillas:".$PlanilalsActivas;}?></td>
          </tr>
          <tr>
            <td class="tdtitulos">Comentario operador:</td>
            <td class="tdcampos"><TEXTAREA COLS=35 ROWS=3 id="comentope" class="campos"></TEXTAREA> <br><br></td>
          </tr>
		  <?php

			    $buscarlosproveedores=("select clinicas_proveedores.nombre,proveedores.id_proveedor
                                                        from
														clinicas_proveedores,proveedores
                                                        where
                                                        clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                                                        and proveedores.tipo_proveedor=1
                                                        and clinicas_proveedores.prov_compra =0
                                                       order by clinicas_proveedores.nombre;");
				$repbuscaproveedores=ejecutar($buscarlosproveedores);
				echo"<tr>
                        <td class=\"tdtitulos\">Proveedor:
                        </td>
                        <td class=\"tdcampos\">
                          <input type='radio' id='cproveedor-intramural' name='clasificaproveedor' value='1'checked onchange='verfiltroproveedores(1)'><label for='CProveedores'>Intramurales</label>
                          <input type='radio' id='cproveedor-extramural' name='clasificaproveedor' value='2' onchange='verfiltroproveedores(2)'> <label for='CProveedores'>Extramural</label>
                          <input type='radio' id='cproveedor-todos' name='clasificaproveedor' value='3' onchange='verfiltroproveedores(3)'> <label for='CProveedores'>Todos</label>
                  <div id='mostrar-provee'>
                        <select id=\"idprovee\" class=\"campos\"  style=\"width: 230px;\" >";?>
                              <?
                                if($opera1=='externa'){?>
			  	 <option value="698">FARMA EXPRESS</option>
                               <?}?>
                                  <option value=""></option>
			         <?php while($losproveedores=asignar_a($repbuscaproveedores,NULL,PGSQL_ASSOC)){
					echo"<option value=\"$losproveedores[id_proveedor]\">
						$losproveedores[nombre]
				         </option>";
			          }

				echo"</select>
        </div>

        <br><br>
                         </td>
                          </tr>";
                  ?>
                  <tr>
                    <td class="tdtitulos">Almacenes/Dependencia:</td>
                    <td class="tdcampos">
            <?php
            $sqlDependencias="select
             tbl_admin_dependencias.id_dependencia,
             tbl_dependencias.dependencia
            from
             tbl_admin_dependencias,tbl_dependencias
            where
             tbl_dependencias.id_dependencia= tbl_admin_dependencias.id_dependencia and
             tbl_admin_dependencias.activar<>'4' and
             tbl_dependencias.esalmacen=1 and
             tbl_admin_dependencias.id_admin=$elid;";
             $Dependencias=ejecutar($sqlDependencias);
            ?>

                        <select id="iddependencia" class="campos"  style="width: 230px;" >
                          <option value="*" selected>Selecione su Dependencia</option>
                          <?php
                          //selecione por defecto si es 1
                          if($cantDepen=num_filas($Dependencias)=='1')
                          {$DependSelec='selected'; }else{$DependSelec='';}

                          while($Dependencia=asignar_a($Dependencias,NULL,PGSQL_ASSOC)){
                                 echo"<option value=\"$Dependencia[id_dependencia]\" $DependSelec>$Dependencia[dependencia]</option>";
                               }?>
                        </select> <br><br>
                    </td>
                </tr>

                  <tr>
                       <td class="tdtitulos">Tipo servicio:</td>
                       <td class="tdcampos">
                         <select id="tiposervi" class="campos"  style="width: 230px;" >
                             <option value="0"></option>
                                 <?while($losservi=asignar_a($reptiposervi,NULL,PGSQL_ASSOC)){
					echo"<option value=\"$losservi[id_tipo_servicio]-$losservi[id_servicio]\">
						$losservi[tipo_servicio]
				         </option>";
			          }

				echo"</select>";?>
                         </td>
                  </tr>
		 <input type="hidden" id="laopera"  value="<?echo $opera1?>">
          <tr>
            <td  title="Procesar orden de m&eacute;dicamento externa"><label class="boton" style="cursor:pointer" onclick="procesarmEH(); return false; " >Procesar</label></td>
          </tr>
	<?}//fin de que si existe un cliente
    }
?>
