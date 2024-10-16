<?php
   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fechainicio'];
   $fecre2=$_REQUEST['fechafin'];
   $sucrepo=$_REQUEST['sucursal'];
   $repservi=$_REQUEST['servi'];      
   $replogo=$_REQUEST['logo'];   
   $restpro=$_REQUEST['estado'];
    if($repservi<100){
	  $queryservi="and gastos_t_b.id_servicio=$repservi ";
	}  else{
		   $queryservi="and (gastos_t_b.id_servicio=8 or gastos_t_b.id_servicio=11 or gastos_t_b.id_servicio=2 or gastos_t_b.id_servicio=3 or gastos_t_b.id_servicio=14)";
		}   
     if($sucrepo==100){
      $querysucursal="";
      $lasucur="TODAS LAS SUCURSALES";
    }else{
         $querysucursal="and admin.id_sucursal=$sucrepo";
         $querysucur=("select sucursal from sucursales where id_sucursal=$sucrepo");
         $ressucur=ejecutar($querysucur);
         $datasucur=assoc_a($ressucur);
         $lasucur=$datasucur[sucursal];
    }    
  $queryorden=("select procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,
							count(gastos_t_b.id_proceso)
                            from 
                             procesos,gastos_t_b,admin
							where
							procesos.id_proceso=gastos_t_b.id_proceso and
							procesos.id_estado_proceso=$restpro and
							procesos.fecha_recibido between '$fecre1' and '$fecre2' 
                            $queryservi
							and procesos.id_admin=admin.id_admin 
							$querysucursal
                            group by procesos.id_proceso,procesos.id_titular,
							procesos.id_beneficiario;");
	$repqueryorden=ejecutar($queryorden);
	$cuantosquery=num_filas($repqueryorden);
?> 
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >
<?php
if ($replogo==1){
?>
<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp; </span>
<?php
}
else{
?>
<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp;</span>
<?php
}
?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
     <tr>
       <td class="datos_cliente3" colspan="7"><b>Relaci&oacute;n de Reembolso</b></td>     
     </tr>
</tale>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="fecha">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	
	<tr> 
	   <td class="datos_cliente33"><strong><?php echo $lasucur;?></strong></td>
	</tr>
</tale>	 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	   <td class="tdtituloss">No.</td>
	   <td class="tdtituloss">Orden</td>   
	  <td class="tdtituloss">Titular</td>   
	  <td class="tdtituloss">Beneficiario</td>    
	  <td class="tdtituloss">Monto Reserva (Bs.S.)</td>        
	  <td class="tdtituloss">Monto Aprobado (Bs.S.)</td>     
	</tr>
	<?
	    $i=1;
	   $montoapro=0; 
	   $montreser=0;   
	    while($reprembolso=asignar_a($repqueryorden,NULL,PGSQL_ASSOC)){
			$cuantosrem=$reprembolso['count'];
			$iddelproceso=$reprembolso['id_proceso'];
			$eltitu=$reprembolso['id_titular'];
			$elbeni=$reprembolso['id_beneficiario'];
			$datostitu=("select clientes.nombres,clientes.apellidos from clientes,titulares
                                 where
								 clientes.id_cliente=titulares.id_cliente and
								 titulares.id_titular=$eltitu;");
			$repdatostitu=ejecutar($datostitu);					 
			$datadeltitu=assoc_a($repdatostitu);
			$nomcopltitu=utf8_decode("$datadeltitu[nombres]  $datadeltitu[apellidos]");		
			$nomcoplbeni="";
			if($elbeni>0){
				  $datosbeni=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios
                                         where
										 clientes.id_cliente=beneficiarios.id_cliente and
										 beneficiarios.id_beneficiario=$elbeni;");
				 $repdatosbeni=ejecutar($datosbeni);						 
				 $datasdelbeni=assoc_a($repdatosbeni); 
				 $nomcoplbeni=utf8_decode("$datasdelbeni[nombres] $datasdelbeni[apellidos]"); 
				}
			if($cuantosrem==1){
				$querymonto=("select gastos_t_b.monto_aceptado,gastos_t_b.monto_reserva from gastos_t_b where gastos_t_b.id_proceso=$iddelproceso;");
				$repquerymonto=ejecutar($querymonto);
				$dataquerymonto=assoc_a($repquerymonto);
				$elmontoes=$dataquerymonto['monto_aceptado'];
				$elmontores=$dataquerymonto['monto_reserva'];
			}
			else{
				   $querymontostotal=("select gastos_t_b.monto_aceptado,gastos_t_b.monto_reserva from gastos_t_b where gastos_t_b.id_proceso=$iddelproceso;");
				   $repquerymontostotal=ejecutar($querymontostotal);   
					$sumatoriamonto=0;
					$sumatroreserva=0;
					while($montoacep=asignar_a($repquerymontostotal,NULL,PGSQL_ASSOC)){
						$sumatoriamonto=$sumatoriamonto+$montoacep['monto_aceptado'];
						$elmontoes=$sumatoriamonto;
						$sumatroreserva=$sumatroreserva+$montoacep['monto_reserva'];;
						$elmontores=$sumatroreserva;
						}
				}
				 echo"
            <tr> 
	             <td class=\"tdtituloss\">$i</td>
				<td class=\"tdtituloss\">$iddelproceso</td>   
	            <td class=\"tdtituloss\">$nomcopltitu</td>   
	            <td class=\"tdtituloss\">$nomcoplbeni</td>    
                <td class=\"tdtituloss\">$elmontores</td>
	            <td class=\"tdtituloss\">$elmontoes</td>      
	        </tr>";
		$i++;
		$montoapro=$montoapro+$elmontoes; 
	    $montreser=$montreser+$elmontores;  
		}	
	?>
	    <tr>
	      <td></td>   
		  <td></td>  
		  <td></td>   
		  <td></td>    
		  <td class="tdtituloss">Total monto reserva: <?echo formato_montos($montreser)?></td>  
		  <td class="tdtituloss">Total monto aprobado: <?echo formato_montos($montoapro)?></td> 
	   </tr>
	  <tr>
	
	<tr>
	        <td  class="tdtituloss" ></td>
	        <td  class="tdtituloss" ></td>
			<td  class="tdtituloss" ></td>
			<td  class="tdtituloss" ></td>
			<td  class="tdtituloss" ></td>
			<td  class="tdtituloss" ></td>
			<td  class="tdtituloss" ></td>
     </tr>  
	<tr>
	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=2 class="tdtituloss" >Aprobado Por:____________________</td>
			<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     </tr>   
</table>
