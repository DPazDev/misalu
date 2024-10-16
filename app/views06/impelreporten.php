<?
   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['rfech1'];
   $fecre2=$_REQUEST['rfech2'];
   $sucrepo=$_REQUEST['resucur'];
   $repservi=$_REQUEST['reservi'];   
   $repprove=$_REQUEST['reprov'];   
   $replogo=$_REQUEST['rrelogo'];   
   $restpro=$_REQUEST['restapro'];
   $cualesp=$_REQUEST['cualpro'];
   $totalgeneral=0;
   $buscarinfo=("select sucursales.sucursal from sucursales where sucursales.id_sucursal=$sucrepo;");   
   $repinfo1=ejecutar($buscarinfo);   
   $datainfo1=assoc_a($repinfo1);
   $lasucur=$datainfo1[sucursal];
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
	    $elservicio='Todos';   
	}  else{
		   $queryservi="and gastos_t_b.id_servicio=$repservi";
		   $info3=("select servicios.servicio from servicios where id_servicio=$repservi;");   
		   $repinfo3=ejecutar($info3);   
		   $datainfo3=assoc_a($repinfo3);
		   $elservicio=$datainfo3[servicio]; 
	}
	
	if($restpro==100){
	  $queryestapro="and (procesos.id_estado_proceso <> 13 or procesos.id_estado_proceso <> 14 or procesos.id_estado_proceso <> 6) ";
	  $elestadoproc='Todos';  
	}  else{
		$queryestapro="and procesos.id_estado_proceso=$restpro";
		$info2=("select estados_procesos.estado_proceso from estados_procesos where 
                       estados_procesos.id_estado_proceso=$restpro;");
		$repinfo2=ejecutar($info2);		
		$datainfo2=assoc_a($repinfo2);
		$elestadoproc=$datainfo2[estado_proceso];
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
$totalfi=num_filas($resultarepor);?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >

<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp; </span>

<?if ($totalfi==0){?>
   <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
     <tr>
       <td class="datos_cliente3" colspan="7"><b>No existe informaci&oacute;n en el rango indicado</b></td>     
     </tr>
</tale>	 
  <?}else{
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="datos_cliente3"> <?php echo "Relaci&oacute;n para el servicio $elservicio, para la sucursal $lasucur, para los procesos en estado $elestadoproc";?></td>
	</tr> 
</tale>	 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="fecha">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
</tale>	 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	  <td class="datos_cliente3">No.</td>
	  <td class="datos_cliente3">No. Planilla</td>   
	 <td class="datos_cliente3">Procesos</td>     
	  <td class="datos_cliente3">Titular</td>   
	  <td class="datos_cliente3">Beneficiario</td>    
	  <td class="datos_cliente3">Monto (Bs.S)</td>      
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
	            <td class=\"cantidades\">$i</td>
		        <td class=\"cantidades\">$numplanilla</td>   
	            <td class=\"cantidades\">$losprocesos</td>     
	            <td class=\"cantidades\">$nomcompletotitu</td>   
	            <td class=\"cantidades\">$nomcompletobeni</td>    
	            <td class=\"cantidades\"> $tolporproceso2</td>      
	        </tr>";
		 $totalgeneral=$totalgeneral+ $tolporproceso2;	
		$i++;
		$tolporproceso2=0;
		$tolporproceso1 =0;
      }?>
	      <tr>
		       <td class="cantidades"></td>
		       <td class="cantidades"></td>   
	            <td class="cantidades"></td>     
	           <td class="cantidades"></td>   
	            <td class="cantidades">Total:</td>    
	           <td class="cantidades"><?echo $totalgeneral;?></td>  
		  
		 </tr> 
		<tr>
	        <td class="cantidades"></td>
		       <td class="cantidades"></td>   
	            <td class="cantidades"></td>     
	           <td class="cantidades"></td>  
			<td class="cantidades"></td>     
	           <td class="cantidades"></td> 
     </tr>   
		<tr>
	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=2 class="tdtituloss" >Aprobado Por:____________________</td>
			<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     </tr>    
        </table>
<?}?>
