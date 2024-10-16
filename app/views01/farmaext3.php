<?
include ("../../lib/jfunciones.php");
sesion();
$eltipocli=$_REQUEST['tipoc'];
$elidBuscar=$_REQUEST['idbusq'];
$operacion=$_REQUEST['tipooper'];
//buscar las fecha de inicio y fin segun cobertura y segun titular o benefi
if($operacion=='TC'){// Es un tratamiento continuo
 if($eltipocli=='T'){     
	     $querfechas=("select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato from entes,titulares where titulares.id_ente=entes.id_ente and titulares.id_titular=$elidBuscar;");
		 $repbusfecha=ejecutar($querfechas); 
		 $datafehcas=assoc_a($repbusfecha); 
		 $fechainicio='2011-01-01';
		 $fechafin= $datafehcas['fecha_renovacion_contrato'];
		$_SESSION['fechacobertura1']=$fechainicio; 
		$_SESSION['fechacobertura2']=$fechafin; 
                 $buscarTC=("select procesos.id_proceso, count(gastos_t_b.id_proceso),admin.nombres,
                             admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                             procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio
                             from
                               procesos,admin,gastos_t_b,servicios,estados_procesos
                             where
			       procesos.id_admin=admin.id_admin and
			       procesos.id_proceso=gastos_t_b.id_proceso and
			       procesos.fecha_recibido between '$fechainicio' and '$fechafin' and
			       procesos.id_titular=$elidBuscar and procesos.id_estado_proceso<>6 and
			       (gastos_t_b.id_servicio=5 or gastos_t_b.id_servicio=7 or gastos_t_b.id_servicio=1) and
			       procesos.id_beneficiario=0 and
                                gastos_t_b.id_servicio=servicios.id_servicio and
			       procesos.id_estado_proceso=estados_procesos.id_estado_proceso
			    group by 
				procesos.id_proceso,admin.nombres,
				admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                                procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio order by procesos.fecha_recibido DESC;");
		 $repuesbuscTC=ejecutar($buscarTC);
                 $cuantosTC=num_filas($repuesbuscTC);
                 
	}else{  
                 
		  $querfechas=("select entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob from entes,titulares,beneficiarios  
                           where titulares.id_titular=beneficiarios.id_titular and beneficiarios.id_beneficiario=$elidBuscar
                           and titulares.id_ente=entes.id_ente;");
		  $repbusfecha=ejecutar($querfechas); 
		  $datafehcas=assoc_a($repbusfecha); 
		  $fechainicio='2011-01-01';
		  $fechafin= $datafehcas['fecha_renovacion_contratob'];   
                  $buscarTC=("select procesos.id_proceso, count(gastos_t_b.id_proceso),admin.nombres,   
                              admin.apellidos,procesos.fecha_recibido,procesos.fecha_recibido,estados_procesos.estado_proceso,
                              procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio
                                from
                               procesos,admin,gastos_t_b,servicios,estados_procesos
                             where
			       procesos.id_admin=admin.id_admin and
			       procesos.id_proceso=gastos_t_b.id_proceso and
			       procesos.fecha_recibido between '$fechainicio' and '$fechafin' and
			       procesos.id_beneficiario=$elidBuscar and procesos.id_estado_proceso<>6 and
			       (gastos_t_b.id_servicio=5 or gastos_t_b.id_servicio=7 or gastos_t_b.id_servicio=1)  and 
                    gastos_t_b.id_servicio=servicios.id_servicio and
			        procesos.id_estado_proceso=estados_procesos.id_estado_proceso
			    group by 
				procesos.id_proceso,admin.nombres,
				admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                                procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio order by procesos.fecha_recibido DESC;");
                 $repuesbuscTC=ejecutar($buscarTC);
                 $cuantosTC=num_filas($repuesbuscTC);         
        }
}//fin del tratamiento contiuno
else{
    
    if($eltipocli=='T'){
	     $querfechas=("select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato from entes,titulares where titulares.id_ente=entes.id_ente and titulares.id_titular=$elidBuscar;");
		 $repbusfecha=ejecutar($querfechas); 
		 $datafehcas=assoc_a($repbusfecha); 
		 $fechainicio='2011-01-01';
		 $fechafin= $datafehcas['fecha_renovacion_contrato'];
		$_SESSION['fechacobertura1']=$fechainicio; 
		$_SESSION['fechacobertura2']=$fechafin; 
        $buscarTC=("select procesos.id_proceso, count(gastos_t_b.id_proceso),admin.nombres,
                             admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                             procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio
                             from
                                  procesos,admin,gastos_t_b,servicios,estados_procesos
                             where
			       procesos.id_admin=admin.id_admin and
			       procesos.id_proceso=gastos_t_b.id_proceso and
                   procesos.fecha_recibido between '$fechainicio' and '$fechafin' and
			       procesos.id_titular=$elidBuscar and (procesos.id_estado_proceso<>6 and procesos.id_estado_proceso<>12 and procesos.id_estado_proceso<>11 and procesos.id_estado_proceso<>10 and procesos.id_estado_proceso<>15) and
			       procesos.id_beneficiario=0 and
                   gastos_t_b.id_servicio=servicios.id_servicio and
			       procesos.id_estado_proceso=estados_procesos.id_estado_proceso
			       group by 
				  procesos.id_proceso,admin.nombres,
				  admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                  procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio order by procesos.fecha_recibido DESC;");
		 $repuesbuscTC=ejecutar($buscarTC);
                 $cuantosTC=num_filas($repuesbuscTC);
        
	}else{  
                 
		  $querfechas=("select entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob from entes,titulares,beneficiarios  
                           where titulares.id_titular=beneficiarios.id_titular and beneficiarios.id_beneficiario=$elidBuscar
                           and titulares.id_ente=entes.id_ente;");
		  $repbusfecha=ejecutar($querfechas); 
		  $datafehcas=assoc_a($repbusfecha); 
		  $fechainicio='2011-01-01';
		  $fechafin= $datafehcas['fecha_renovacion_contratob'];   
                  $buscarTC=("select procesos.id_proceso, count(gastos_t_b.id_proceso),admin.nombres,   
                              admin.apellidos,procesos.fecha_recibido,procesos.fecha_recibido,estados_procesos.estado_proceso,
                              procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio
                                from
                               procesos,admin,gastos_t_b,servicios,estados_procesos
                             where
			       procesos.id_admin=admin.id_admin and
			       procesos.id_proceso=gastos_t_b.id_proceso and
			       procesos.fecha_recibido between '$fechainicio' and '$fechafin' and
			       procesos.id_beneficiario=$elidBuscar and (procesos.id_estado_proceso<>6 and procesos.id_estado_proceso<>12 and procesos.id_estado_proceso<>11 and procesos.id_estado_proceso<>10 and procesos.id_estado_proceso<>15) and
                   gastos_t_b.id_servicio=servicios.id_servicio and
			        procesos.id_estado_proceso=estados_procesos.id_estado_proceso
			    group by 
				procesos.id_proceso,admin.nombres,
				admin.apellidos,procesos.fecha_recibido,estados_procesos.estado_proceso,
                                procesos.comentarios,servicios.servicio,gastos_t_b.id_servicio order by procesos.fecha_recibido DESC;");
                 $repuesbuscTC=ejecutar($buscarTC);
                 $cuantosTC=num_filas($repuesbuscTC);         
        
		}
     
    }
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
 <tr>
    <td class="tdcamposc">Procesos</td>
    <td class="tdcamposc">Est. Pro</td>
    <td class="tdcamposc">Operador</td>
    <td class="tdcamposc">Fecha Recibido </td>
	<td class="tdcamposc">Fecha Final </td>
    <td class="tdcamposc">Concepto </td>
    <td class="tdcamposc">Tra. Cont</td>
    <td class="tdcamposc">Fecha Final TC</td>
    <td class="tdcamposc">Descripcion del Gasto</td>
    <td class="tdcamposc">Cantidad</td>
  </tr>
<?  while($losgastos=asignar_a($repuesbuscTC,NULL,PGSQL_ASSOC)){
   $proceso=$losgastos['id_proceso'];
   $operador="$losgastos[nombres]  $losgastos[apellidos]";
   $fechaproce=$losgastos['fecha_recibido'];
   $cantidad=$losgastos['count'];
   $estadpro=$losgastos['estado_proceso'];
	$servicio=$losgastos['servicio'];   
   $comenta=$losgastos['comentarios'];
   $paidservicio=$losgastos['id_servicio'];
   
?>
  <tr>
   <td class="tdcamposcc"><?php echo $proceso?>&nbsp;</td>
     <?if($paidservicio==1){?>
         <td class="tdcamposcc"><label style="color:#FC0303"><?php echo "$estadpro ($servicio)"?>&nbsp;</label></td>
     <?}else{?>    
         <td class="tdcamposcc"><?php echo "$estadpro ($servicio)"?>&nbsp;</td>
     <?}?>    
   <td class="tdcamposcc"><?php echo $operador?>&nbsp;</td>
   <td class="tdcamposcc"><?php echo $fechaproce?>&nbsp;</td>
   
  <?
      $busgatpro=("select gastos_t_b.unidades,gastos_t_b.descripcion,gastos_t_b.nombre,
                              gastos_t_b.fecha_continuo,gastos_t_b.continuo,fecha_continuo 
                         from 
                              gastos_t_b 
                         where gastos_t_b.id_proceso=$proceso and gastos_t_b.monto_aceptado>'0';");
      $rebusgatpro=ejecutar($busgatpro);
     $c=0;
      while($losme=asignar_a($rebusgatpro,NULL,PGSQL_ASSOC)){
      $elmedica=$losme['descripcion'];
      $cant=$losme['unidades'];
      $concepto=$losme['nombre'];
      $escontinuo=$losme['continuo'];
      $fettcon=$losme['fecha_continuo'];
    if($c==0){ ?>
	<td class="tdcamposcc"><?php echo $losme['fecha_continuo']?>&nbsp;</td>
      <td class="tdcamposcc"><?php echo $concepto?>&nbsp;</td>
       <?if($escontinuo=="on"){?>
         <td class="tdcamposcc"><label style="color:#FC0303">Es&nbsp;</label></td>
     <?}else{?>    
         <td class="tdcamposcc">No&nbsp;</td>
         
     <?$fettcon="";}?> 
     <td class="tdcamposcc"><?php echo $fettcon?>&nbsp;</td>
      <td class="tdcamposcc"><?php echo $elmedica?>&nbsp;</td>
      <td class="tdcamposcc"><?php echo $cant?>&nbsp;</td>
    <?}else{?>
    <td class="tdcamposcc"></td>
  <td class="tdcamposcc"></td>
  <td class="tdcamposcc"></td>
    <td class="tdcamposcc"></td>
   <td class="tdcamposcc"></td>
    <td class="tdcamposcc"><?php echo $concepto?>&nbsp;</td>
    <?if($escontinuo=="on"){?>
         <td class="tdcamposcc"><label style="color:#FC0303">Es&nbsp;</label></td>
     <?}else{?>    
         <td class="tdcamposcc">No&nbsp;</td>
     <?$fettcon="";}?> 
     <td class="tdcamposcc"><?php echo $fettcon?>&nbsp;</td>
   <td class="tdcamposcc"><?php echo $elmedica?>&nbsp;</td>
   <td class="tdcamposcc"><?php echo $cant?>&nbsp;</td>
  <?}?>
  </tr>
  <?$c++;}
  echo" 
     <tr>
       <td class=\"tdcamposcc\">Comentario:</td>
       <td class=\"tdcamposcc\">$comenta</td>
      </tr>
 
     <tr>
      <td class=\"tdcamposcc\" colspan=\"6\"><hr></td>
    </tr>";
  }?>
</table>

