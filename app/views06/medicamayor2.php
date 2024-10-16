<?
include ("../../lib/jfunciones.php");
sesion();
$fecha1=$_REQUEST['fechaini'];
$fecha2=$_REQUEST['fechafin'];
$proveedor=$_REQUEST['elproveedor'];
$servicio=$_REQUEST['elservicio'];
$ente=$_REQUEST['elente'];
$estproceso=$_REQUEST['estproceso'];
$tipocliente=$_REQUEST['tipcliente'];
$mediconti=$_REQUEST['tipmedi'];
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
		    if($mediconti==1){
		    $queryconti="";  
		  }else{
			   if($mediconti==2){
				  $queryconti="and gastos_t_b.continuo='on'";
				}else{  
			       $queryconti="and gastos_t_b.continuo<>'on'";  
				}   
			}   	
    $buscarmedica=("select gastos_t_b.descripcion,count(gastos_t_b.descripcion)
from 
gastos_t_b,procesos,titulares
where
gastos_t_b.id_proceso=procesos.id_proceso and
gastos_t_b.id_servicio=$servicio and
procesos.id_estado_proceso=$estproceso and
gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
gastos_t_b.id_proveedor=$proveedor
$querytipoclien and
procesos.id_titular=titulares.id_titular $queryente $queryconti
group by gastos_t_b.descripcion order by count desc");  
$repbuscarmedica=ejecutar($buscarmedica);
$cuantoshaymedi=num_filas($repbuscarmedica);
 if($cuantoshaymedi==0){
     echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">No hay informaci&oacute;n en el rango seleccionado!!</td>
         </tr>
        </table>";
 }else{?>
     <LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >
<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp; </span>

 <?  echo"<table class=\"datos_cliente3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">Medicamentos con mayor salida para el ente $nomente en el rango del $fecha1 al $fecha2 dirigido a los $nomcliente, cuyo estado proceso es $nomestproc </td>
         </tr>
        </table>";
?>
   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="datos_cliente3">No.</th>
        <th class="datos_cliente3">Medicamento.</th>
	<th class="datos_cliente3">Cantidad.</th>
	</tr>
	<?   $totalme=0;
	        $totalmeg=0;
             $i=1;
	      while($datosmedici=asignar_a($repbuscarmedica,NULL,PGSQL_ASSOC)){
		
		
	?>
	<tr>
	    <td class="tdcampos"><label style="color: #000000">[<?echo $i;?>]</label></td>
	    <td class="tdcampos"><?
		if($datosmedici[descripcion]<>''){
		    echo $datosmedici[descripcion];
			$buscaruni=("select gastos_t_b.unidades
from 
gastos_t_b,procesos,titulares
where
gastos_t_b.id_proceso=procesos.id_proceso and
gastos_t_b.id_servicio=$servicio and
procesos.id_estado_proceso=$estproceso and
gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
gastos_t_b.id_proveedor=$proveedor
$querytipoclien and
procesos.id_titular=titulares.id_titular $queryente and
gastos_t_b.descripcion='$datosmedici[descripcion]';");
          $repuni=ejecutar($buscaruni);
		while($lasuni=asignar_a($repuni,NULL,PGSQL_ASSOC)){  
		     $totalme=$totalme+$lasuni[unidades]; 
			  
		}	 
		   $totalmeg=$totalmeg+$totalme;
			?></td> 
	    <td class="cantidades"><?echo $totalme;?></td> 
        </tr>
       <?
          
           $i++;
            }
			 $totalme=0;
			}
			?>      
       <tr>
	    <td class="cantidades"></td> 
	    <td class="cantidades"><label style="color: #000000">Total de medicamentos:</label></td> 
	    <td class="cantidades"><label style="color: #000000"><?echo $totalmeg;?></label></td> 
        </tr>
    </table>
<?}?>
