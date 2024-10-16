<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha1=$_POST['fechaini'];
$fecha2=$_POST['fechafin'];
$proveedor=$_POST['elproveedor'];

list($servicio,$nom_servicio)=explode("@",$_REQUEST['elservicio']);


if($servicio=="-02"){
	$condicion_servicio="and (gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9 ) ";}
else
$condicion_servicio="and gastos_t_b.id_servicio='$servicio'";


list($ente,$nom_ente)=explode("@",$_REQUEST['elente']);


list($estproceso,$nom_estproceso)=explode("@",$_REQUEST['estproceso']);

$tipocliente=$_POST['tipcliente'];


   list($tipo_insumo,$nom_tipo_insumo)=explode("@",$_REQUEST['insumo']);
echo $tipo_insumo."**+++";


/*$buscarinfoad=("select entes.nombre,estados_procesos.estado_proceso from entes,estados_procesos where 
                entes.id_ente=$ente and estados_procesos.id_estado_proceso=$estproceso;");
$repbusinfo=ejecutar($buscarinfoad);
$datainfo=assoc_a($repbusinfo);
$nomente=$datainfo['nombre'];
$nomestproc=$datainfo['estado_proceso'];*/


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
		   /* if($mediconti==1){
		    $queryconti="";  
		  }else{
			   if($mediconti==2){
				  $queryconti="and gastos_t_b.continuo='on'";
				}else{  
			       $queryconti="and gastos_t_b.continuo<>'on'";  
				}   
			} */  	
    $q_medicam=("select tbl_insumos.insumo,tbl_laboratorios.laboratorio,count(gastos_t_b.unidades)
from 
gastos_t_b,procesos,titulares,tbl_insumos,tbl_laboratorios
where
gastos_t_b.id_proceso=procesos.id_proceso 
$condicion_servicio 
$queryestproce and
gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
gastos_t_b.id_proveedor=$proveedor
$querytipoclien and
tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio and 
procesos.id_titular=titulares.id_titular $queryente 
and gastos_t_b.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_tipo_insumo=$tipo_insumo 
group by tbl_insumos.insumo,tbl_laboratorios.laboratorio order by count desc"); 


 
$r_medicam=ejecutar($q_medicam);
$cuantoshaymedi=num_filas($r_medicam);

echo $q_medicam;


?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="16">Reporte Salida de Medicamentos para el ente <?php echo "$nom_ente"." en el rango de fechas del "."$fecha1"." al "."$fecha2"." los usuarios "."$nomcliente".", estado proceso "."$nom_estproceso";?> </td>     
        </tr>
</table>
	
	<tr> <td colspan=4>&nbsp;</td></tr>



   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">MEDICAMENTO.</th>
        <th class="tdtitulos">LABORATORIO.</th>
	<th class="tdtitulos">CANTIDAD.</th>
	</tr>
<?php
$i=1;
	while($f_medicam=asignar_a($r_medicam)){

$total=$total+$f_medicam[count];

echo  " 
		<tr> 
	        
		<td class=\"tdtitulos\">$i  </td>  
		<td class=\"tdtitulos\">$f_medicam[insumo]  </td>  
		<td class=\"tdtitulos\">$f_medicam[laboratorio]  </td>  
		<td class=\"tdtitulos\">$f_medicam[count]</td>"

;?>
	              
	        </tr>


<?php
$i++; } ?>


     
       <tr>
<tr><td >&nbsp;</td></tr>
	    <td class="tdcampos"></td> 
	    <td class="tdcampos">TOTAL DE MEDICAMENTOS:</label></td> 
	    <td class="tdcampos"><?echo $total;?></label></td> 
        </tr>
    </table>

