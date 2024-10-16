<?php
   include ("../../lib/jfunciones.php");
   sesion();
  
   $fecre1=$_POST['lfe1'];
   $fecre2=$_POST['lfe2'];
   $cente=$_POST['lente']; 
   $tipcliente=$_POST['ltipclien'];
   $stcliente=$_POST['lestclien'];
   echo $tipcliente;
   $dataescli=("select estados_clientes.estado_cliente 
                  from estados_clientes where id_estado_cliente=$stcliente;");
   $repdataescli=ejecutar($dataescli);
   $datestado=assoc_a($repdataescli);
   $tipestado=strtolower($datestado['estado_cliente']);
   if($tipcliente==1){
      if($stcliente<>4){ 
       $querytipcli1="titulares.id_titular=estados_t_b.id_titular and
                    titulares.id_titular=registros_exclusiones.id_titular and
                    clientes.id_cliente=titulares.id_cliente and
                    registros_exclusiones.id_beneficiario=0 and
                    estados_t_b.id_beneficiario=0 and
                     registros_exclusiones.fecha_creado between '$fecre1' and '$fecre2' and
                   registros_exclusiones.id_estado_cliente=estados_t_b.id_estado_cliente and 
                   estados_clientes.id_estado_cliente=registros_exclusiones.id_estado_cliente and
                  estados_t_b.id_estado_cliente=$stcliente";
                    $regex="registros_exclusiones.fecha_inclusion,registros_exclusiones.fecha_exclusion";
                    $tabla1=",registros_exclusiones,";
                    
      }else{
           $querytipcli1="titulares.id_titular=estados_t_b.id_titular and                   
                    clientes.id_cliente=titulares.id_cliente and
                    titulares.fecha_creado between '$fecre1' and '$fecre2' and
                    estados_t_b.id_beneficiario=0 and
                    estados_t_b.id_estado_cliente=$stcliente and 
                    estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente ";
                    $regex="titulares.fecha_creado";
                    $tabla1=",";
                    
          }              
   }else{
       if($stcliente<>4){
         if($tipcliente==2){
             $querytipcli1="
                    clientes.id_cliente=beneficiarios.id_cliente and 
                    beneficiarios.id_titular=titulares.id_titular and 
                    beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
                    beneficiarios.id_beneficiario=registros_exclusiones.id_beneficiario and 
                    estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente and
                    estados_t_b.id_estado_cliente=$stcliente and
                    registros_exclusiones.fecha_creado between '$fecre1' and '$fecre2'"; 
                    $regex="registros_exclusiones.fecha_inclusion,registros_exclusiones.fecha_exclusion";
                    $tabla1=",beneficiarios,registros_exclusiones,";
           }
        }else{
               $querytipcli1="
                    clientes.id_cliente=beneficiarios.id_cliente and
                    beneficiarios.id_titular=titulares.id_titular and
                    beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                    beneficiarios.fecha_creado between '$fecre1' and '$fecre2' and
                    estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente and 
                     estados_t_b.id_estado_cliente=$stcliente"; 
                    $regex="beneficiarios.fecha_creado";
                    $tabla1=",beneficiarios,";
            }
    }
   
    if($cente==0){
         $queryente="and titulares.id_ente=entes.id_ente";
         $tabla2=",entes";
         $campo2=",entes.nombre as elente";
         $ordenar="entes.nombre,";
         $columente="Ente";
    }else{
         $queryente="and titulares.id_ente=$cente";
         $tabla2="";
         $campo2="";
         $ordenar="";
        }
   $labusqueda=("select clientes.nombres,clientes.apellidos,clientes.cedula,
                 $regex,
                 estados_clientes.estado_cliente$campo2
                 from 
                   clientes $tabla1 estados_t_b,titulares,estados_clientes $tabla2
                 where
                   $querytipcli1
                   $queryente
                    
                  order by $ordenar clientes.nombres,clientes.apellidos;");    
              
 $replabusqueda=ejecutar($labusqueda);
 $totalfi=num_filas($replabusqueda);
if ($totalfi==0){
 echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
     <tr>
       <td class=\"titulo_seccion\" colspan=\"7\">No hay informaci&oacute;n en el rango seleccionado </td>     
     </tr>
</tale>	 ";


}else{?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="tdtitulosd">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
</table>	 	

<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	  <td class="tdtituloss">No.</td>
	  <td class="tdtituloss">Nombre.</td>   
	  <td class="tdtituloss">Apellido.</td>   
	  <td class="tdtituloss">C&eacute;dula.</td>    
	  <td class="tdtituloss">Estado.</td>   
     <td class="tdtituloss">Fecha <?echo $tipestado?>.</td>  
     <?
     if($cente==0){
         echo "<td class=\"tdtituloss\">$columente</td>  ";
         }
     ?>
	</tr>
<?
 $i=1;
  while($repestitubene=asignar_a($replabusqueda,NULL,PGSQL_ASSOC)){?>
  <tr> 
   <td class="tdtituloss" width="5"><?echo $i?></td>
   <td class="tdtituloss"><?echo $repestitubene['nombres']?></td>
   <td class="tdtituloss"><?echo $repestitubene['apellidos']?></td>
   <td class="tdtituloss"><?echo $repestitubene['cedula']?></td>
   <td class="tdtituloss"><?echo $repestitubene['estado_cliente']?></td>
   <td class="tdtituloss"><?
                            if($repestitubene['estado_cliente']=='ACTIVO'){
                              echo $repestitubene['fecha_creado'];
                           }else{
                              echo $repestitubene['fecha_exclusion']; 
                              }
                         ?></td>
    <?
     if($cente==0){
         echo "<td class=\"tdtituloss\">$repestitubene[elente]</td>  ";
         }
     ?>
  </tr>	
  <? 
     $i++;
    }?>
    <tr>
     <td colspan=7 class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/titubeniestados2.php?fechainicio=$fecre1&fechafin=$fecre2&cualente=$cente&tipclien=$tipcliente&estclien=$stcliente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
			</td>
   </tr>
</table>
 <? }
  ?>
  
