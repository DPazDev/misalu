<?
   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_POST['rfech1'];
   $fecre2=$_POST['rfech2'];
   $sucrepo=$_POST['resucur'];
   $repservi=$_POST['reservi'];   
   $repprove=$_POST['reprov'];   
   $replogo=$_POST['rrelogo'];   
   $restpro=$_POST['restapro'];
   $cualesp=$_POST['cualpro'];
   $totalgeneral=0;
      if ($cualesp==1){
        $var1="procesos.comentarios";
        $var3="comentarios";
        $var2="select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor=$repprove and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and  s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor";
    }else{
        $var1="gastos_t_b.descripcion";
        $var3="descripcion";
        $var2="select clinicas_proveedores.nombre from clinicas_proveedores,proveedores where proveedores.id_proveedor=$repprove and proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor"; 
    }   
   
$queryprove=($var2);
$resulprove=ejecutar($queryprove);
$dataprove=assoc_a($resulprove);
   if ($cualesp==1){
    $nompr=$dataprove[nombres_prov];
    $apepr=$dataprove[apellidos_prov];
   }else{
     $nompr=$dataprove[nombre];
    }

   if($repservi==100){
	  $queryservi="and (gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9) ";
	}  else{
		   $queryservi="and gastos_t_b.id_servicio=$repservi";
	}
	
	if($restpro==100){
	  $queryestapro="and (procesos.id_estado_proceso <> 13 or procesos.id_estado_proceso <> 14 or procesos.id_estado_proceso <> 6) ";
	}  else{
		$queryestapro="and procesos.id_estado_proceso=$restpro";
	}
   $queryreporte=("select
procesos.nu_planilla,procesos.id_titular,procesos.id_beneficiario
from 
procesos,gastos_t_b,admin  
where 
procesos.fecha_recibido between '$fecre1' and '$fecre2' and 
procesos.id_proceso=gastos_t_b.id_proceso 
$queryestapro and 
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=$sucrepo 
$queryservi  and
procesos.nu_planilla>'3'
group by procesos.nu_planilla,procesos.id_titular,procesos.id_beneficiario;");
$resultarepor=ejecutar($queryreporte);
$totalfi=num_filas($resultarepor);

if ($totalfi==0){
   echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
     <tr>
       <td class=\"titulo_seccion\" colspan=\"7\">No existe informaci&oacute;n en el rango indicado</td>     
     </tr>
</tale>	 ";
}else{
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="tdtitulosd">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	
	<tr> 
	   <td class="tdtituloss"><?php echo $lasucur;?></td>
	</tr>
</tale>	 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	  <td class="tdtituloss">No.</td>
	  <td class="tdtituloss">No. Planilla</td>   
	  <td class="tdtituloss">Procesos</td>     
	  <td class="tdtituloss">Titular</td>   
	  <td class="tdtituloss">Beneficiario</td>    
	  <td class="tdtituloss">Monto (Bs.S)</td>      
	</tr>
	<?
          $i=1;
	  $bsgf=0; 
           while($repprov=asignar_a($resultarepor,NULL,PGSQL_ASSOC)){
             $tolporproceso1=0;
             $tolporproceso2=0; 
             $numplanilla=$repprov[nu_planilla];        
             $elidtitular=$repprov[id_titular];
             $elidbenf=$repprov[id_beneficiario];                
             $nomcompletotitu='';
             $nomcompletobeni=''; 
			 $losprocesos='';  
             $datatitu=("select clientes.nombres,clientes.apellidos from clientes,titulares where 
                         titulares.id_cliente=clientes.id_cliente and
                         titulares.id_titular=$elidtitular");
             $repdatatitu=ejecutar($datatitu);
             $infotitu=assoc_a($repdatatitu);
             $nomcompletotitu="$infotitu[nombres] $infotitu[apellidos]";
             if($elidbenf>0){
                $databenif=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios where 
                         beneficiarios.id_cliente=clientes.id_cliente and
                         beneficiarios.id_beneficiario=$elidbenf");
                $repdatabeni=ejecutar($databenif);
                $infobeni=assoc_a($repdatabeni);
                $nomcompletobeni="$infobeni[nombres] $infobeni[apellidos]";
                 }
                $buscarprocesoplanilla=("select procesos.id_proceso,procesos.nu_planilla from procesos where procesos.nu_planilla='$numplanilla';"); 
				$repbuscarplaproceso=ejecutar($buscarprocesoplanilla);
				$cuantosvan=0;
				while($planillaproc=asignar_a($repbuscarplaproceso,NULL,PGSQL_ASSOC)){
					$planillanum=$planillaproc[nu_planilla];
					$tolporproceso1=0;
				if($planillanum>0){	
					if($cuantosvan==0){
						  $losprocesos=$planillaproc[id_proceso];
						}else{
							 $losprocesos="$losprocesos,$planillaproc[id_proceso]";
						  }
				 $cuantosvan++;		
				}
				  $buscarmontopro=("select gastos_t_b.monto_aceptado from gastos_t_b where 
                                                           gastos_t_b.id_proceso=$planillaproc[id_proceso];");  
						  $repbuscarmonto=ejecutar($buscarmontopro);
				 while($montoproc=asignar_a($repbuscarmonto,NULL,PGSQL_ASSOC)){ 
						       $tolporproceso1=$tolporproceso1+$montoproc[monto_aceptado];
						      } 
				 $tolporproceso2=$tolporproceso2+$tolporproceso1;
				
				}	
                echo" <tr> 
	            <td class=\"tdtituloss\">$i</td>
		        <td class=\"tdtituloss\">$numplanilla</td>   
	            <td class=\"tdtituloss\">$losprocesos</td>     
	            <td class=\"tdtituloss\">$nomcompletotitu</td>   
	            <td class=\"tdtituloss\">$nomcompletobeni</td>    
	            <td class=\"tdtituloss\"> $tolporproceso2</td>      
	        </tr>";
		 $totalgeneral=$totalgeneral+ $tolporproceso2;	
		$i++;
		$tolporproceso2=0;
		$tolporproceso1 =0;
      }?>
	      <tr>
		       <td class="tdtituloss"></td>
		        <td class="tdtituloss"></td>   
	            <td class="tdtituloss"></td>     
	            <td class="tdtituloss"></td>   
	            <td class="tdtituloss">Total:</td>    
	            <td class="tdtituloss"><?echo $totalgeneral;?></td>  
		  
		 </tr>  
        </table>
<?}?>
