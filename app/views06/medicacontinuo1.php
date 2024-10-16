<?
include ("../../lib/jfunciones.php");
sesion();
$fecha1=$_POST['fechaini'];
$fecha2=$_POST['fechafin'];
$proveedor=$_POST['elproveedor'];
$servicio=$_POST['elservicio'];
$ente=$_POST['elente'];
$estproceso=$_POST['estproceso'];
$tipocliente=$_POST['tipcliente'];
$mediconti=$_POST['tipmedi'];
$buscarinfoad=("select entes.nombre,estados_procesos.estado_proceso from entes,estados_procesos where 
                entes.id_ente=$ente and estados_procesos.id_estado_proceso=$estproceso;");
$repbusinfo=ejecutar($buscarinfoad);
$datainfo=assoc_a($repbusinfo);
$nomente=$datainfo['nombre'];
$nomestproc=$datainfo['estado_proceso'];
      if($tipocliente==3){
           $querytipoclien="and procesos.id_beneficiario<> 0";
           $nomcliente='Beneficiarios';
        }else{
              if($tipocliente==2){
                $querytipoclien="and procesos.id_beneficiario=0";
                $nomcliente='Titulares';
              }else{ 
                    $querytipoclien=""; 
                    $nomcliente='Todos';
                   }
             }
     if($ente==1){
        $queryente="";
      }else{
             $queryente="and titulares.id_ente=$ente";
            }
     if($estproceso==0){
       $queryestproce="";  
     }else{
          $queryestproce="and procesos.id_estado_proceso=$estproceso";  
       }
	  if($mediconti==1){
		    $queryconti="";  
		  }else{
			   if($mediconti==2){
				  $queryconti="and gastos_t_b.continuo='on'";
				}else{  
			       $queryconti="and gastos_t_b.continuo<>'on'";  
				}   
			}   
   $buscarmedi=("select procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario, 
                            procesos.id_admin,estados_procesos.estado_proceso,count(gastos_t_b.id_proceso),
                           admin.nombres,admin.apellidos 
from 
gastos_t_b,procesos,titulares,estados_procesos,admin
where
gastos_t_b.id_proceso=procesos.id_proceso and
gastos_t_b.id_servicio=$servicio 
$queryestproce and
gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
gastos_t_b.id_proveedor=$proveedor 
$querytipoclien and
procesos.id_titular=titulares.id_titular 
$queryente and
procesos.id_estado_proceso=estados_procesos.id_estado_proceso and
procesos.id_admin=admin.id_admin $queryconti
group by procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,procesos.id_admin,estados_procesos.estado_proceso,
admin.nombres,admin.apellidos ;");
$repbuscarmedi=ejecutar($buscarmedi);
$cuantosmedi=num_filas($repbuscarmedi);
  if($cuantosmedi==0){
     echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">No hay informaci&oacute;n en el rango seleccionado!!</td>
         </tr>
        </table>";
 }else{
	  echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">Medicamentos entregados al  ente/s $nomente en el rango del $fecha1 al $fecha2 dirigido a los $nomcliente, cuyo estado proceso es $nomestproc </td>
         </tr>
        </table>"; 
?>
    <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">No. Proceso</th>
        <th class="tdtitulos">Operador.</th>
        <th class="tdtitulos">Estatus.</th>
	    <th class="tdtitulos">Titular.</th>
        <th class="tdtitulos">Beneficiario.</th>
        <th class="tdtitulos">Medicamento.</th>
		<th class="tdtitulos">Cantidad.</th> 
        <th class="tdtitulos">Monto.</th>
        <th class="tdtitulos">Tratm Cont.</th>
		<th class="tdtitulos">Fecha Cont.</th>
	</tr>
	
	    <?php  
		        $i=1;
				$totalgme=0;
				$totalgcost=0;
		        while($datosconti=asignar_a($repbuscarmedi,NULL,PGSQL_ASSOC)){
					$elproceso=$datosconti[id_proceso];
				  	$eltitular=$datosconti[id_titular];
					$elbenefi=$datosconti[id_beneficiario];
					$datatitular=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                                           clientes.id_cliente=titulares.id_cliente and
                                            titulares.id_titular=$eltitular;");
					$repdatatitu=ejecutar($datatitular);		
					$infotitu=assoc_a($repdatatitu);
					$nombreti=$infotitu['nombres'];
					$apelliti=$infotitu['apellidos']; 
					$nomcpltitu="$nombreti $apelliti";
					if($elbenefi>0){
						 $databeni=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios 
                                             where
                                            clientes.id_cliente=beneficiarios.id_cliente and
                                            beneficiarios.id_beneficiario=$elbenefi;");
						 $repdatabeni=ejecutar($databeni);					
						 $infobeni=assoc_a($repdatabeni); 
						 $nombrebeni=$infobeni['nombres'];
						 $apellibeni=$infobeni['apellidos']; 
						 $nomcplbeni="$nombrebeni $apellibeni"; 
						}
					$buscaringastb=("select gastos_t_b.descripcion,gastos_t_b.monto_reserva,
                                                 gastos_t_b.continuo,gastos_t_b.unidades,gastos_t_b.fecha_continuo 
                                                 from gastos_t_b 
                                                where gastos_t_b.id_proceso=$elproceso;");	
					$repbuscagastb=ejecutar($buscaringastb);
					$totalme=0;
					$totalcost=0;
				?>
					<tr>
	                <td class="tdcampos"><label style="color: #000000">[<?echo $i;?>]</label></td>
	                <td class="tdcampos"><?echo $elproceso;?></td> 
	                <td class="tdcampos"><?echo "$datosconti[nombres]  $datosconti[apellidos]";?></td> 
					<td class="tdcampos"><?echo "$datosconti[estado_proceso]";?></td>  
					<td class="tdcampos"><?echo $nomcpltitu;?></td>  
					<td class="tdcampos"><?echo $nomcplbeni;?></td>  
                   </tr>
					<?
					while($datosgstb=asignar_a($repbuscagastb,NULL,PGSQL_ASSOC)){
						if($datosgstb[descripcion]<>''){
						$totalme=$totalme+$datosgstb[unidades];
						$totalmet= $totalmet +$datosgstb[unidades];
						$totalcost=$totalcost+$datosgstb[monto_reserva]; 
						}
						if($datosgstb['continuo']=='on'){
							 $estcon='Si';
							}else{
								  $estcon='No';
								}
		?>
		           
				   <tr>
				         <td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						 <td class="tdcampos"><? if($datosgstb[descripcion]<>''){
						                                    
															echo $datosgstb[descripcion];?></td>
						 <td class="tdcampos"><?echo $datosgstb[unidades];?></td> 
						 <td class="tdcampos"><?echo $datosgstb[monto_reserva];?></td>  
						 <td class="tdcampos"><?echo $estcon;?></td>  
						<td class="tdcampos"><?echo $datosgstb[fecha_continuo ];
						  }
						 ?></td>   
					</tr>
					
		<?        }  
		            $totalgme=$totalgme+$totalme;
					$totalgcost=$totalgcost+$totalcost;
		?>
		            <tr>
					    <td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td> 
						<td class="tdcampos"><label style="color: #000000">Total medicamentos</label></td> 
						<td class="tdcampos"><label style="color: #000000"><?echo $totalme;?></label></td>  
						 <td class="tdcampos"><label style="color: #000000">Total Bs.S.f</label></td> 
						 <td class="tdcampos"><label style="color: #000000"><?echo $totalcost;?></label></td>
						 
					 </tr>
					 <tr>
					     <td class="tdcampos" colspan="12"><HR></td> 
					 </tr> 
		           <? $totalme=0;
					$totalcost=0;
					$i++;
					$nomcpltitu='';
					$nomcplbeni=''; 
		     }?>
	          <tr>
					    <td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td>
						<td class="tdcampos"></td> 
						<td class="tdcampos"><label style="color: #000000">Total general medicamentos</label></td> 
						<td class="tdcampos"><label style="color: #000000"><?echo $totalgme;?></label></td>  
						 <td class="tdcampos"><label style="color: #000000">Total general Bs.S</label></td> 
						 <td class="tdcampos"><label style="color: #000000"><?echo $totalgcost;?></label></td>
					 </tr>  
	 </table> 
<?}?>
