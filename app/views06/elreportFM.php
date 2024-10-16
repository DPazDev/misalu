<?php
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
   $buscarservi=("select servicios.servicio from servicios where servicios.id_servicio=$repservi;");
   $repbuscarservi=ejecutar($buscarservi);  
   $elservies=assoc_a($repbuscarservi); 
   $nomservies=$elservies['servicio'];
   if($sucrepo==99){
       $querysucursal="";
       }else{
           $querysucursal="and admin.id_sucursal=$sucrepo";
           }
     if($repservi==101){
         $queryservi="and (gastos_t_b.id_servicio=4 or gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9)"; 
     }else{
        if($repservi<100){
	  $queryservi="and gastos_t_b.id_servicio=$repservi ";
	}  else{
		   $queryservi="and (gastos_t_b.id_servicio=8 or gastos_t_b.id_servicio=11 or gastos_t_b.id_servicio=2 or gastos_t_b.id_servicio=3 or gastos_t_b.id_servicio=14)";
		}
} 
 
   if ($cualesp==1){
        $var1="procesos.comentarios";
        $var3="comentarios";
        $var2="select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor=$repprove and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and  s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor";
    }else{
        $var1="gastos_t_b.descripcion";
        $var3="descripcion";
        $var2="select clinicas_proveedores.nombre from clinicas_proveedores,proveedores where proveedores.id_proveedor=$repprove and proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor"; 
    }   
   $queryreporte=("select
procesos.id_proceso,gastos_t_b.id_proveedor,procesos.id_titular,procesos.id_beneficiario,
gastos_t_b.fecha_cita
from 
procesos,gastos_t_b,admin 
where 
gastos_t_b.fecha_cita between '$fecre1' and '$fecre2' and 
procesos.id_proceso=gastos_t_b.id_proceso and 
procesos.id_estado_proceso=$restpro and 
procesos.id_admin=admin.id_admin 
$querysucursal
$queryservi and gastos_t_b.id_proveedor=$repprove group by procesos.id_proceso,gastos_t_b.id_proveedor,procesos.id_titular,procesos.id_beneficiario,
gastos_t_b.fecha_cita ORDER BY procesos.id_proceso DESC;
");

$resultarepor=ejecutar($queryreporte);
$totalfi=num_filas($resultarepor);
$queryprove=($var2);
$resulprove=ejecutar($queryprove);
$dataprove=assoc_a($resulprove);
   if ($cualesp==1){
    $nompr=$dataprove[nombres_prov];
    $apepr=$dataprove[apellidos_prov];
   }else{
     $nompr=$dataprove[nombre];
    }
$querysucur=("select sucursal from sucursales where id_sucursal=$sucrepo");
$ressucur=ejecutar($querysucur);
$datasucur=assoc_a($ressucur);
$lasucur=$datasucur[sucursal];
//echo "$totalfi-----$nompr-----$apepr";
//echo "$fecre1-----$fecre2-----$sucrepo----$repservi---$repprove----$replogo----$restpro---$cualesp";      
if ($totalfi==0){
 echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
     <tr>
       <td class=\"titulo_seccion\" colspan=\"7\">No hay informaci&oacute;n en el rango seleccionado </td>     
     </tr>
</tale>	 ";


}else{
    
    ?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr>
     <td class="titulo_seccion" colspan="7">Relaci&oacute;n  de <? echo $nomservies;?> del provedor <?echo "$nompr $apepr ";?> </td>     
   </tr>
</tale>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="tdtitulosd">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	<tr> 
	   <td class="tdtituloss"><?php echo $lasucur;?></td>
	</tr>
</table>	 	
<?
    $i=1;
    $apunfa=1;  
	$numfac=1;
	$apumorese=1; 
    $apumoacep=1;
    $apunfechafinal=1;
	$apug=1;
	$facguar=1;
    while($repprov=asignar_a($resultarepor,NULL,PGSQL_ASSOC)){
echo"<br><br>";

$divfa="divf".$numfac;
?>
<div id="<?php echo $divfa;?>">

<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	  <td class="tdtituloss">No.</td>
	  <td class="tdtituloss">Proceso</td>   
	  <td class="tdtituloss">Titular</td>   
	  <td class="tdtituloss">Beneficiario</td>    
	  <td class="tdtituloss">No. Factura</td>   
      <td class="tdtituloss">No. Control</td>    
	  <td class="tdtituloss">Fecha Factura Final</td>       
       <td class="tdtituloss">Descripci&oacute;n</td>            
	  <td class="tdtituloss">Monto reserva</td>
      <td class="tdtituloss">Monto aceptado</td>
                             
	</tr>
<?           $nombtitular='';  
             $nomben='';
	     $querytitular=("select clientes.nombres,clientes.apellidos 
                                from clientes,titulares 
                              where titulares.id_titular=$repprov[id_titular] and 
                                    titulares.id_cliente=clientes.id_cliente"); 
	     $restitular=ejecutar($querytitular);
	     $lainftitular=assoc_a($restitular);
             $nombtitular="$lainftitular[nombres] $lainftitular[apellidos]";
		if ($repprov[id_beneficiario]>0){
		 $querybenf=("select clientes.nombres,clientes.apellidos 
                                      from clientes,beneficiarios 
                                      where beneficiarios.id_beneficiario=$repprov[id_beneficiario] and  
                                      beneficiarios.id_cliente=clientes.id_cliente;");
		 $resulbenf=ejecutar($querybenf);
		 $infoben=assoc_a($resulbenf);  
		 $nomben="$infoben[nombres] $infoben[apellidos]";  
		}
		  echo"
                 <tr> 
	            <td class=\"tdtituloss\" width=\"5\">$i</td>
		         <td class=\"tdtituloss\">$repprov[id_proceso]</td>   
	            <td class=\"tdtituloss\">$nombtitular</td>     
	            <td class=\"tdtituloss\">$nomben</td>   
	            <td class=\"tdtituloss\"><input type=\"text\" id=\"factura_$apunfa\" class=\"campos\" onblur=\"ponerfactura()\" size=\"7\"></td> 
                <td class=\"tdtituloss\"><input type=\"text\" id=\"facturacontro_$apunfa\" class=\"campos\" onblur=\"ponercontrol()\" size=\"7\"></td>   
              <td class=\"tdtituloss\"><input type=\"text\" id=\"facfinal_$apunfechafinal\" class=\"campos\" onblur=\"ponerfecha()\"  size=\"10\"></td>
        </tr>
           
            ";
				$elproceso=$repprov[id_proceso];  
                    $buscarlosgastos=("select gastos_t_b.id_gasto_t_b,gastos_t_b.monto_reserva,
                                                               gastos_t_b.monto_aceptado,gastos_t_b.descripcion 
                                                     from 
                                                               gastos_t_b
                                                     where
                                                               gastos_t_b.id_proceso=$repprov[id_proceso];");
                   $repbuscargastos=ejecutar($buscarlosgastos); 
                  $cuanf=1;
                   while($losgastostb=asignar_a($repbuscargastos,NULL,PGSQL_ASSOC)){
                  echo"
                  <tr>
                    <td class=\"tdtituloss\"></td>    
                    <td class=\"tdtituloss\"></td>    
                    <td class=\"tdtituloss\"></td>    
                    <td class=\"tdtituloss\"></td>     
                    <td class=\"tdtituloss\"></td>  
                    <td class=\"tdtituloss\"></td>   
                     <td class=\"tdtituloss\"></td>  
                    <td class=\"tdtituloss\">$losgastostb[descripcion]</td>      
	            <td class=\"tdtituloss\"><input type=\"text\" id=\"montreser_$apumorese\" class=\"campos\" size=\"7\" value=\"$losgastostb[monto_reserva]\"></td>   
                   
	            <td class=\"tdtituloss\"><input type=\"text\" id=\"montacept_$apumoacep\" class=\"campos\" size=\"10\" value=\"$losgastostb[monto_aceptado]\"></td>      
                           
	         </tr>";
			       echo"<input type=\"hidden\" id=\"gastos_$apug\" class=\"campos\" size=\"15\" value=\"$losgastostb[id_gasto_t_b]\">";
                   $apumorese++; 
                   $apumoacep++;
                   $apug++;   
				  $cuanf++;  
                 }
				
		        $i++;
                $apunfa++;
				$apunfechafinal++;
		 echo "<tr>
     
     <td class=\"tdcampos\" title=\"Guardar facturas\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"GuardarFM($numfac,$apumorese,$cuanf,$elproceso)\" >Guardar</label></td>
               </tr>
<tr>
             <td class=\"tdtituloss\"><input type=\"text\" id=\"facturaregi_$numfac\" class=\"campos\" onblur=\"ponerfactura()\" size=\"13\" disabled></td>
          </tr>";		
         echo"</table>";
		$divfag="divg".$facguar; ?>
		<img alt="spinner" id="spinner3" src="../public/images/esperar.gif" style="display:none;" />
		<div id='<?echo $divfag;?>'></div>
	   <? $numfac++;  
	         $facguar++;
			
        }
		$lostfa=$apunfa-1;
		$lasfecha=$apunfechafinal-1;
		echo "<input type=\"hidden\" id=\"totalfac\" class=\"campos\"  value=\"$lostfa\">
                  <input type=\"hidden\" id=\"totalfec\" class=\"campos\" value=\"$apunfechafinal\">";
		
		?>
<?}?>
<div id='quepasa'></div>
