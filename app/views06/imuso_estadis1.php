<?
include ("../../lib/jfunciones.php");
sesion();
$fecha1=$_REQUEST['fechaini'];
$fecha2=$_REQUEST['fechafin'];
$proveedor=$_REQUEST['elproveedor'];
$servicio=$_REQUEST['elservicio'];
$ente=$_REQUEST['elente'];
$tipocliente=$_REQUEST['tipcliente'];
$buscarinfoad=("select entes.nombre from entes where entes.id_ente=$ente ;");
$repbusinfo=ejecutar($buscarinfoad);
$datainfo=assoc_a($repbusinfo);
$nomente=$datainfo['nombre'];

      if($tipocliente==3){
           $querytipoclien="and procesos.id_beneficiario<>0 and
beneficiarios.id_titular=titulares.id_titular";
           $queryestatb="and estados_t_b.id_beneficiario=procesos.id_beneficiario"; 
           $busq="select count(procesos.id_beneficiario),procesos.id_beneficiario,entes.nombre"; 
           $from="procesos,entes,gastos_t_b,estados_t_b,titulares,beneficiarios";   
           $prtb="and procesos.id_beneficiario=beneficiarios.id_beneficiario";
           $querstb="procesos.id_beneficiario=estados_t_b.id_beneficiario";
           $group="procesos.id_beneficiario,entes.nombre";  
           $nomcliente='Beneficiarios';
        }else{
              if($tipocliente==2){
                $querytipoclien="and procesos.id_beneficiario=0";
                $queryestatb="and estados_t_b.id_beneficiario=0  ";
                $busq="select count(procesos.id_titular),procesos.id_titular,entes.nombre"; 
                $from="procesos,entes,gastos_t_b,estados_t_b,titulares";
                $prtb="and procesos.id_titular=titulares.id_titular ";
                $querstb="procesos.id_titular=estados_t_b.id_titular ";
                $group="procesos.id_titular,entes.nombre";
                $nomcliente='Titulares';
              }else{ 
                    $querytipoclien=""; 
                    $queryestatb="";  
                    $nomcliente='Todos';
                   }
             }
     if($ente==1){
        $queryente="";
      }else{
             $queryente="titulares.id_ente=$ente and";
            }
			   	
    $buscarmedica=("$busq
                    from
                    $from
                    where
                      procesos.id_proceso=gastos_t_b.id_proceso and
                      procesos.fecha_creado between '$fecha1' and '$fecha2' 
                      $prtb and 
                      $querstb  
                      $querytipoclien and
                      titulares.id_ente=entes.id_ente and
                       $queryente 
                      estados_t_b.id_estado_cliente=4 
                      $queryestatb and
                      gastos_t_b.id_servicio=$servicio and
                      procesos.id_estado_proceso=2 and
                      gastos_t_b.id_proveedor=$proveedor 
                      group by
                      $group;");  
$repbuscarmedica=ejecutar($buscarmedica);
$cuantoshaymedi=num_filas($repbuscarmedica);
 if($cuantoshaymedi==0){
     echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"datos_cliente3\">No hay informaci&oacute;n en el rango seleccionado!!</td>
         </tr>
        </table>";
 }else{
    echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"datos_cliente3\">Usuarios que han usado el servicio desde $fecha1 al $fecha2 </td>
         </tr>
        </table>";
?>
   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="datos_cliente3">No.</th>
        <th class="datos_cliente3">Usuario.</th>
        <th class="datos_cliente3">Ente.</th> 
        <th class="datos_cliente3">Visitas.</th>
	</tr>
	<?   
	     $totalmeg=0;
             $i=1;
	 while($datosmedici=asignar_a($repbuscarmedica,NULL,PGSQL_ASSOC)){
	?>
	<tr>
	    <td class="cantidades"><label style="color: #000000">[<?echo $i;?>]</label></td>
	    <td class="cantidades"><?
		if($tipocliente==2){
                    $busnom=("select clientes.nombres,clientes.apellidos from 
                                     clientes,titulares where
                                     clientes.id_cliente=titulares.id_cliente and
                                     titulares.id_titular=$datosmedici[id_titular];");
                    $repbusnom=ejecutar($busnom);
                    $datnombre=assoc_a($repbusnom);
                    $nomcomp="$datnombre[nombres] $datnombre[apellidos]-$datosmedici[id_titular]";
		    echo $nomcomp;
                   /*vistas*/
                             $vistas=("select procesos.id_proceso from procesos where 
                                procesos.id_proceso=gastos_t_b.id_proceso and
                                procesos.id_titular=$datosmedici[id_titular] and
                                procesos.id_beneficiario=0 and
                                procesos.id_titular=estados_t_b.id_titular and
                                estados_t_b.id_estado_cliente=4 and
                                estados_t_b.id_beneficiario=0 and
                                gastos_t_b.id_servicio=7 group by procesos.id_proceso;");
                     
                   $revistas=ejecutar($vistas);
                   $nvisitas=0;
                   $narticulos1=0;
                   while($visibe=asignar_a($revistas,NULL,PGSQL_ASSOC)){
                       $elpro=$visibe[id_proceso];
                       if($elpro>0){
                         $nvisitas=$nvisitas+1;
                       }    
                   }
                  $visitatotal=$visitatotal+$nvisitas;
		}
                if($tipocliente==3){
		    $busnom=("select clientes.nombres,clientes.apellidos from 
                                     clientes,beneficiarios where
                                     clientes.id_cliente=beneficiarios.id_cliente and
                                     beneficiarios.id_beneficiario=$datosmedici[id_beneficiario];");
                    $repbusnom=ejecutar($busnom);
                    $datnombre=assoc_a($repbusnom);
                    $nomcomp="$datnombre[nombres] $datnombre[apellidos]";
		    echo $nomcomp;
                    /*vistas*/
                             $vistas=("select procesos.id_proceso from procesos where 
                                       procesos.id_proceso=gastos_t_b.id_proceso and
                                       procesos.id_beneficiario=$datosmedici[id_beneficiario] and
                                       estados_t_b.id_estado_cliente=4 and
                                       estados_t_b.id_beneficiario=procesos.id_beneficiario and
                                       gastos_t_b.id_servicio=7 group by procesos.id_proceso;");
                     
                   $revistas=ejecutar($vistas);
                   $nvisitas=0;
                   $narticulos1=0;
                   while($visibe=asignar_a($revistas,NULL,PGSQL_ASSOC)){
                       $elpro=$visibe[id_proceso];
                       if($elpro>0){
                         $nvisitas=$nvisitas+1;
                       }    
                   }
                  $visitatotal=$visitatotal+$nvisitas;
		}	 	 
		?></td> 
           <td class="cantidades"><?echo $datosmedici[nombre]?></td>
          <td class="cantidades"></td>
           <td class="cantidades"><?echo $nvisitas?></td> 
           <td class="cantidades"><?
           $narticulos1=0;
           $nvisitas=0;
           ?></td>  
        </tr>
        
       <?$i++;
            }?>
	<tr>
	    <td class="cantidades"></td>
            <td class="cantidades"></td>  
	    <td class="cantidades"><label style="color: #000000">Total visitas:</label></td> 
	    <td class="cantidades"><label style="color: #000000"><?echo $visitatotal;?></label></td> 
        </tr>
    </table>
<?}?>
