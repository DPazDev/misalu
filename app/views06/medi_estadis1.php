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
$tipovari=is_numeric($mediconti);
if($tipovari==1){
$busnombre=("select examenes_bl.examen_bl from examenes_bl where examenes_bl.codigo_barra='$mediconti';");
$rpbusnombre=ejecutar($busnombre);
$datnombre=assoc_a($rpbusnombre);
}else{

}
$nombrearti=$datnombre['examen_bl'];
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
    $buscarmedica=("select gastos_t_b.descripcion,count(gastos_t_b.descripcion),titulares.id_ente
from
  gastos_t_b,procesos,titulares,entes
where
 gastos_t_b.id_proceso=procesos.id_proceso and
 procesos.id_titular=titulares.id_titular and
 titulares.id_ente=entes.id_ente and
  gastos_t_b.id_servicio=$servicio $queryestproce and
 gastos_t_b.id_proveedor=$proveedor and
 gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
 gastos_t_b.descripcion='$nombrearti' $querytipoclien $queryente
group by 
gastos_t_b.descripcion,titulares.id_ente order by gastos_t_b.descripcion;");  
$repbuscarmedica=ejecutar($buscarmedica);
$cuantoshaymedi=num_filas($repbuscarmedica);
 if($cuantoshaymedi==0){
     echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">No hay informaci&oacute;n en el rango seleccionado!!</td>
         </tr>
        </table>";
 }else{
    echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
         <tr>
          <td colspan=4 class=\"titulo_seccion\">Control de salida de medicamento en el rango del $fecha1 al $fecha2 </td>
         </tr>
        </table>";
?>
   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Medicamento.</th>
        <th class="tdtitulos">Ente.</th>
	<th class="tdtitulos">Cantidad.</th>
	</tr>
	<?   
	     $totalmeg=0;
             $i=1;
	 while($datosmedici=asignar_a($repbuscarmedica,NULL,PGSQL_ASSOC)){
            $buscarente=("select entes.nombre from entes where entes.id_ente=$datosmedici[id_ente]");
            $querbuscar=ejecutar($buscarente);
            $datente=assoc_a($querbuscar);
            $elente=$datente['nombre'];
            //las unidades
            echo "------------$datosmedici[descripcion]";
             
              $buscarunidades=("select gastos_t_b.unidades
                              from
                                gastos_t_b,procesos,titulares,entes
                             where
                                gastos_t_b.id_proceso=procesos.id_proceso and
                                procesos.id_titular=titulares.id_titular and
                                titulares.id_ente=entes.id_ente and
                                gastos_t_b.id_proveedor=$proveedor and
                                gastos_t_b.fecha_creado between '$fecha1' and '$fecha2' and
                                gastos_t_b.descripcion='$datosmedici[descripcion]' and 
                                titulares.id_ente=$datosmedici[id_ente] and 
                                gastos_t_b.id_servicio=$servicio;");
               $repbuscarunidades=ejecutar($buscarunidades);
               while($launidades=asignar_a($repbuscarunidades,NULL,PGSQL_ASSOC)){
                  $totalmeg=$totalmeg+$launidades['unidades'];
               } 
	?>
	<tr>
	    <td class="tdcampos"><label style="color: #000000">[<?echo $i;?>]</label></td>
	    <td class="tdcampos"><?
		if($datosmedici[descripcion]<>''){
		    echo $datosmedici[descripcion];
		}	 
		?></td> 
            <td class="tdcampos"><?echo $elente;?></td>  
           
           
	    <td class="tdcampos"><?echo $totalmeg;?></td> 
        </tr>
        
       <?
         $i++;
         $totalmeg=0;
            }?>
	<tr>
	    <td class="tdcampos"></td>
            <td class="tdcampos"></td>  
	    <td class="tdcampos"><label style="color: #000000">Total de medicamentos:</label></td> 
	    <td class="tdcampos"><label style="color: #000000"><?echo $totalmeg;?></label></td> 
        </tr>
    </table>
<?}?>
