<?php
include ("../../lib/jfunciones.php");
sesion();
$cedclien=$_POST["lacedulaclien"];
$buscarcliente=("select * from clientes where cedula='$cedclien';");
$resultacliente=ejecutar($buscarcliente);
$daclient=assoc_a($resultacliente);
$nomcompleto=$daclient[nombres];
$apellcompleto=$daclient[apellidos];
$cuantoscliente=num_filas($resultacliente);
$_SESSION['pasoaguardar']=0;
$_SESSION['pasoaguardarbenf']=0;
    if ($cuantoscliente>0){
		$buscarentitu=("select titulares.id_titular,titulares.id_ente,entes.nombre,clientes.nombres,clientes.apellidos,estados_clientes.estado_cliente from titulares,estados_t_b,clientes,entes,estados_clientes where titulares.id_cliente=clientes.id_cliente and clientes.cedula='$cedclien' and titulares.id_titular=estados_t_b.id_titular  and estados_t_b.id_beneficiario=0 and titulares.id_ente=entes.id_ente and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente order by estado_cliente;");
		$respbuscatitu=ejecutar($buscarentitu);
		$cuantostitus=num_filas($respbuscatitu);
		 if ($cuantostitus>=1){
			echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
		 <br>
           <tr>  
		       <td colspan=8 class=\"titulo_seccion\">Cliente $nomcompleto  $apellcompleto ya esta registrado(a) en el sistema como titular</td>   
		   </tr> ";  
		   echo "<input type=\"hidden\" id=\"cedtioente\" value=\"$cedclien\">";   
			while ($lostitson=asignar_a($respbuscatitu,NULL,PGSQL_ASSOC)){
				$tnombre=$lostitson[nombres];
				$tapellido=$lostitson[apellidos];
				$tente=$lostitson[nombre];
				$testatu=$lostitson[estado_cliente];
				echo"<tr>
				    <td colspan=4 class=\"titulo_seccion\">$tente</td>
					<td colspan=4 class=\"titulo_seccion\">$testatu</td>
				</tr>";
			}
			echo"</table>";
			$busctcob=("select  titulares.id_titular,titulares.id_ente,entes.nombre,clientes.nombres,clientes.apellidos,
								  estados_clientes.estado_cliente 
                                  from 
								  titulares,estados_t_b,clientes,entes,estados_clientes,beneficiarios 
                                  where 
                                  beneficiarios.id_cliente=clientes.id_cliente and clientes.cedula='$cedclien' 
                                  and titulares.id_titular=beneficiarios.id_titular  and 
                                  estados_t_b.id_beneficiario=beneficiarios.id_beneficiario  and titulares.id_ente=entes.id_ente 
                                  and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente order by estado_cliente;");
		   $respbtcob=ejecutar($busctcob);		
		   $cuantcob=num_filas($respbtcob);
		      if ($cuantcob>=1){
				   echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
		             <br>
                     <tr>  
		               <td colspan=12 class=\"titulo_seccion\">Cliente $nomcompleto  $apellcompleto esta registrado(a) en el sistema como beneficiario del titular</td>   
					</tr>";
					echo"     
                        <tr>
                           <td colspan=4 class=\"titulo_seccion\">Ente</td>
                           <td colspan=4 class=\"titulo_seccion\">Estatus</td>
                           <td colspan=4 class=\"titulo_seccion\">Titular</td>
					 </tr>"; 
					while ($losticb=asignar_a($respbtcob,NULL,PGSQL_ASSOC)){
						 $tidtitu=$losticb[id_titular];
						   $busnoape=("select clientes.nombres,clientes.apellidos from clientes,titulares where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$tidtitu; ");
						   $repunoape=ejecutar($busnoape);   
						   $datanoape=assoc_a($repunoape);   
						   $nom1= $datanoape[nombres];  
						   $ape1= $datanoape[apellidos];   
				           $tente=$losticb[nombre];
						   $testatu=$losticb[estado_cliente];
				echo"<tr>
				    <td colspan=4 class=\"titulo_seccion\">$tente</td>
					<td colspan=4 class=\"titulo_seccion\">$testatu</td>
                    <td colspan=4 class=\"titulo_seccion\">$nom1  $ape1</td>
				</tr>";
					}	
				
				}   
				$_SESSION["cedula_paso"]=$cedclien;
				echo"</table>";
				echo"
                  <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
                      <br>
	                 <tr>
	                    <td title=\"Ver datos del cliente\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"CliCedula($cedclien)\" >Ver data</label></td>
                        <td title=\"Asignar el titular como beneficiario de otro titular\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"$('clientenuevo').hide(),clienbenf1(); return false;\" >Asignar a otro titular</label></td>
                       <td title=\"Asignar el titular a otro ente\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"$('clientenuevo').hide(),clienotroente(); return false;\" >Asignar a otro ente</label></td>
	                </tr>
                </table>";
			}else{
			    $busben=("select  titulares.id_titular,titulares.id_ente,entes.nombre,clientes.nombres,clientes.apellidos,
								  estados_clientes.estado_cliente 
                                  from 
								  titulares,estados_t_b,clientes,entes,estados_clientes,beneficiarios 
                                  where 
                                  beneficiarios.id_cliente=clientes.id_cliente and clientes.cedula='$cedclien' 
                                  and titulares.id_titular=beneficiarios.id_titular  and 
                                  estados_t_b.id_beneficiario=beneficiarios.id_beneficiario  and titulares.id_ente=entes.id_ente 
                                  and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente order by estado_cliente;");	
               $respbusben=ejecutar($busben);
				echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
		        <br>
                <tr>  
		          <td colspan=12 class=\"titulo_seccion\">Cliente $nomcompleto  $apellcompleto ya esta registrado(a) en el sistema como beneficiario(a)</td>   
		      </tr> ";  
			echo"  <tr>
                           <td colspan=4 class=\"titulo_seccion\">Ente</td>
                           <td colspan=4 class=\"titulo_seccion\">Estatus</td>
                           <td colspan=4 class=\"titulo_seccion\">Titular</td>
					 </tr>"; 
			     while ($losbenson=asignar_a($respbusben,NULL,PGSQL_ASSOC)){
				
				 $tidtitu=$losbenson[id_titular];
				 $busnoape=("select clientes.nombres,clientes.apellidos from clientes,titulares where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$tidtitu; ");
				 $repunoape=ejecutar($busnoape);   
				 $datanoape=assoc_a($repunoape);   
				 $nom1= $datanoape[nombres];  
				 $ape1= $datanoape[apellidos];   
				$tente=$losbenson[nombre];
				$testatu=$losbenson[estado_cliente];
				echo"<tr>
				    <td colspan=4 class=\"titulo_seccion\">$tente</td>
					<td colspan=4 class=\"titulo_seccion\">$testatu</td>
                    <td colspan=4 class=\"titulo_seccion\">$nom1  $ape1</td>
				</tr>";
			}
			echo"</table>";  
			echo"
                  <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
                      <br>
	                 <tr>
	                    <td title=\"Ver datos del cliente\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"CliCedula($cedclien)\" >Ver data</label></td>
                        <td title=\"Registrar el beneficiario como titular\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"BenfTitu($cedclien)\" >Asignar beneficiario como titular</label></td>
                         <td title=\"Registrar el beneficiario a otro titular\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"$('clientenuevo').hide(),clienbenf1(); return false;\" >Asignar beneficiario a otro titular</label></td>
	                </tr>
                </table>";
			}
		}else{?>
	<div id='datacl'>	
		<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
		 <br>
           <tr> 
               <td class="tdtituloc" title="Registrar cliente como titular"><label class="boton" style="cursor:pointer" onclick="$('datacl').hide(),clientitu(); return false;" >Registrar cliente como titular</label>
			     <label class="boton" title="Registrar cliente como beneficiario" style="cursor:pointer" onclick="$('clientenuevo').hide(),clienbenf1(); return false;" >Registrar cliente como beneficiario</label></td>
           </tr>		    
		</table>      
			     <div id="paratitu"></div>
		
	</div>		
			<?}
?>
