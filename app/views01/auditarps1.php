<?
include ("../../lib/jfunciones.php");
sesion();
require_once '../../lib/Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('CP1251');
$arreglo=array();
$arreglonsta=array();
$qcliente=strtoupper($_REQUEST['elclient']);
header('Content-type: application/vnd.ms-excel');
$numealeatorio=rand(2,99);
header("Content-Disposition: attachment; filename=archivo$numealeatorio.xls");
header("Pragma: no-cache");
header("Expires: 0");
$filename = 'plansalud.xls';
$creatable=("CREATE TEMP TABLE posiusuario(cedulaclien varchar(50), nomcompleto varchar(200));");
$reptable=ejecutar($creatable);
if (file_exists($filename)) {
    $data->read($filename);
error_reporting(E_ALL ^ E_NOTICE);
if($qcliente=='T'){
?>
<table  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Auditor&iacute;a de clientes Plan Salud (Titulares)</td>  
     </tr>
</table>
<br>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Cliente.</th>
                 <th class="tdtitulos">C&eacute;dula.</th>
				 <th class="tdtitulos">Tipo Cliente.</th> 
				 <th class="tdtitulos">Ente.</th> 
				 <th class="tdtitulos">Estatus.</th> 
              </tr>   
<?$apuntador=1; 
  for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		$cedula=$data->sheets[0]['cells'][$i][3];
		$nombre=$data->sheets[0]['cells'][$i][4];
		$apelli=$data->sheets[0]['cells'][$i][5];
		$nombrecp="$nombre $apelli";
			
	}
 if(!empty($cedula)){	
	 if(is_numeric($cedula)){
	   $arreglo[$apuntador][0]=$cedula;
	   $arreglo[$apuntador][1]=$nombrecp;
	   $apuntador++;
     } 
 } 
}
for($j=1;$j<=$apuntador-1;$j++){
  $cedulaclien=$arreglo[$j][0];
  $nomcopleclien=$arreglo[$j][1];	  
  $guardodt=("insert into posiusuario(cedulaclien,nomcompleto) values ('$cedulaclien','$nomcopleclien') ");
  $repguardodt=ejecutar($guardodt);
}
 $cuatnos=("select posiusuario.cedulaclien,posiusuario.nomcompleto from posiusuario");
 $repcuanto=ejecutar($cuatnos);
 while($elcliente=asignar_a($repcuanto,NULL,PGSQL_ASSOC)){
   $lacedulaclien=$elcliente[cedulaclien];
   $lanombre=$elcliente[posiusuario];
   $busdatprin=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula from clientes where clientes.cedula='$lacedulaclien';");
   $repdataprin=ejecutar($busdatprin);
   $cuantosdp=num_filas($repdataprin);
      if($cuantosdp>=1){
		  $datclien=assoc_a($repdataprin);
		  $elidclien=$datclien['id_cliente'];
		  $nomcompelto="$datclien[nombres] $datclien[apellidos]";
		  $lacedula="$datclien[cedula]";
		  $versititular=("select titulares.id_titular,titulares.id_cliente,titulares.fecha_creado,entes.nombre,
		                  estados_clientes.estado_cliente
                          from
                            titulares,entes,estados_t_b,estados_clientes
                          where
                            titulares.id_titular=estados_t_b.id_titular and
                            estados_t_b.id_beneficiario=0 and
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
                            titulares.id_ente=entes.id_ente and
                            titulares.id_cliente=$elidclien;");
        $repverelcliente=ejecutar($versititular);
        $cuantosestitular=num_filas($repverelcliente);
        if($cuantosestitular>=1){
		  $tipocliente="Titular";
		  while($datost=asignar_a($repverelcliente,NULL,PGSQL_ASSOC)){?>
			   <tr>
				   <td class="tdcampos"><?echo $nomcompelto?></td>
				   <td class="tdcampos"><?echo $lacedula?></td>				   
				   <td class="tdcampos"><?echo $tipocliente?></td>	
				   <td class="tdcampos"><?echo $datost[nombre]?></td>		
				   <td class="tdcampos"><?echo $datost[estado_cliente]?></td>	    
			  </tr>
			  <?}
		}
   }		
  } 
 } 
 if($qcliente=='B'){?>
 <table  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Auditor&iacute;a de clientes Plan Salud (Beneficiarios)</td>  
     </tr>
</table>
<br>

 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr style="background: #77AADD;">
                 <th class="tdtitulos">Cliente.</th>
                 <th class="tdtitulos">C&eacute;dula.</th>
				 <th class="tdtitulos">Tipo Cliente.</th> 
				 <th class="tdtitulos">Ente.</th> 
				 <th class="tdtitulos">Estatus.</th> 
				 <th class="tdtitulos">Titular.</th>
				 <th class="tdtitulos">C&eacute;dula.</th>
				 <th class="tdtitulos">Parentesco.</th>
              </tr>   
<?$apuntador=1; 
  for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		$cedula=$data->sheets[0]['cells'][$i][3];
		$nombre=$data->sheets[0]['cells'][$i][4];
		$apelli=$data->sheets[0]['cells'][$i][5];
		$nombrecp="$nombre $apelli";
			
	}
 if(!empty($cedula)){	
	 if(is_numeric($cedula)){
	   $arreglo[$apuntador][0]=$cedula;
	   $arreglo[$apuntador][1]=$nombrecp;
	   $apuntador++;
     } 
 } 
}
for($j=1;$j<=$apuntador-1;$j++){
  $cedulaclien=$arreglo[$j][0];
  $nomcopleclien=$arreglo[$j][1];	  
  $guardodt=("insert into posiusuario(cedulaclien,nomcompleto) values ('$cedulaclien','$nomcopleclien') ");
  $repguardodt=ejecutar($guardodt);
}
 $cuatnos=("select posiusuario.cedulaclien,posiusuario.nomcompleto from posiusuario");
 $repcuanto=ejecutar($cuatnos);
 while($elcliente=asignar_a($repcuanto,NULL,PGSQL_ASSOC)){
   $lacedulaclien=$elcliente[cedulaclien];
   $lanombre=$elcliente[posiusuario];
   $busdatprin=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula from clientes where clientes.cedula='$lacedulaclien';");
   $repdataprin=ejecutar($busdatprin);
   $cuantosdp=num_filas($repdataprin);
      if($cuantosdp>=1){
		  $datclien=assoc_a($repdataprin);
		  $elidclien=$datclien['id_cliente'];
		  $nomcompelto="$datclien[nombres] $datclien[apellidos]";
		  $lacedula="$datclien[cedula]";
	      $tipocliente="Beneficiario";	
		  $versibenefi=("select beneficiarios.id_beneficiario,beneficiarios.id_cliente,
		                 beneficiarios.fecha_creado,entes.nombre,estados_clientes.estado_cliente,
		                 beneficiarios.id_titular,beneficiarios.id_parentesco
                        from
                          titulares,entes,estados_t_b,estados_clientes,beneficiarios
                        where
                          beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                          estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
                          beneficiarios.id_titular=titulares.id_titular and
                          titulares.id_ente=entes.id_ente and
                          beneficiarios.id_cliente=$elidclien;");
          $repversibeni=ejecutar($versibenefi);    
          while($datost=asignar_a($repversibeni,NULL,PGSQL_ASSOC)){
			  $elidtularb=$datost[id_titular];
			  $elidparen=$datost[id_parentesco];
			  $datparentesc=("select clientes.nombres,clientes.apellidos,clientes.cedula,parentesco.parentesco 
			                  from
			                    clientes,parentesco,titulares
			                  where
			                    titulares.id_cliente=clientes.id_cliente and
			                    parentesco.id_parentesco=$elidparen and
			                    titulares.id_titular=$elidtularb");
			 $repdatparetesc=ejecutar($datparentesc);
			 $dataparen=assoc_a($repdatparetesc);                   
			 $elnomtitular="$dataparen[nombres] $dataparen[apellidos]";
			 $elcedula=$dataparen['cedula'];
			 $elparentes=$dataparen['parentesco'];
			  ?>
			   <tr>
				   <td class="tdcampos"><?echo $nomcompelto?></td>
				   <td class="tdcampos"><?echo $lacedula?></td>				   
				   <td class="tdcampos"><?echo $tipocliente?></td>	
				   <td class="tdcampos"><?echo $datost[nombre]?></td>		
				   <td class="tdcampos"><?echo $datost[estado_cliente]?></td>	    
				   <td class="tdcampos"><?echo $elnomtitular?></td>	    
				   <td class="tdcampos"><?echo $elcedula?></td>	
				   <td class="tdcampos"><?echo $elparentes?></td>    
			  </tr>
			<? }
	  }			 
	 } 
 }
 if($qcliente=='C'){
	 $nsta=1;?>
 <table  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Auditor&iacute;a de clientes Plan Salud (Titulares y Beneficiarios)</td>  
     </tr>
</table>
<br>

	 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Cliente.</th>
                 <th class="tdtitulos">C&eacute;dula.</th>
				 <th class="tdtitulos">Tipo Cliente.</th> 
				 <th class="tdtitulos">Ente.</th> 
				 <th class="tdtitulos">Estatus.</th> 
				 <th class="tdtitulos">Titular.</th>
				 <th class="tdtitulos">C&eacute;dula.</th>
				 <th class="tdtitulos">Parentesco.</th>
              </tr>   
<?$apuntador=1; 
  for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		$cedula=$data->sheets[0]['cells'][$i][3];
		$nombre=$data->sheets[0]['cells'][$i][4];
		$apelli=$data->sheets[0]['cells'][$i][5];
		$nombrecp="$nombre $apelli";
			
	}
 if(!empty($cedula)){	
	 if(is_numeric($cedula)){
	   $arreglo[$apuntador][0]=$cedula;
	   $arreglo[$apuntador][1]=$nombrecp;
	   $apuntador++;
     } 
 } 
}
for($j=1;$j<=$apuntador-1;$j++){
  $cedulaclien=$arreglo[$j][0];
  $nomcopleclien=$arreglo[$j][1];	  
  $guardodt=("insert into posiusuario(cedulaclien,nomcompleto) values ('$cedulaclien','$nomcopleclien') ");
  $repguardodt=ejecutar($guardodt);
}
 $cuatnos=("select posiusuario.cedulaclien,posiusuario.nomcompleto from posiusuario");
 $repcuanto=ejecutar($cuatnos);
 while($elcliente=asignar_a($repcuanto,NULL,PGSQL_ASSOC)){
   $lacedulaclien=$elcliente[cedulaclien];
   $lanombre=$elcliente[nomcompleto];
   $busdatprin=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula from clientes where clientes.cedula='$lacedulaclien';");
   $repdataprin=ejecutar($busdatprin);
   $cuantosdp=num_filas($repdataprin);
      if($cuantosdp>=1){
		  $datclien=assoc_a($repdataprin);
		  $elidclien=$datclien['id_cliente'];
		  $nomcompelto="$datclien[nombres] $datclien[apellidos]";
		  $lacedula="$datclien[cedula]";
		  $versititular=("select titulares.id_titular,titulares.id_cliente,titulares.fecha_creado,entes.nombre,
		                  estados_clientes.estado_cliente
                          from
                            titulares,entes,estados_t_b,estados_clientes
                          where
                            titulares.id_titular=estados_t_b.id_titular and
                            estados_t_b.id_beneficiario=0 and
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
                            titulares.id_ente=entes.id_ente and
                            titulares.id_cliente=$elidclien;");
        $repverelcliente=ejecutar($versititular);
        $cuantosestitular=num_filas($repverelcliente);
        if($cuantosestitular>=1){
		  $tipocliente="Titular";
		  while($datost=asignar_a($repverelcliente,NULL,PGSQL_ASSOC)){?>
			   <tr>
				   <td class="tdcampos"><?echo $nomcompelto?></td>
				   <td class="tdcampos"><?echo $lacedula?></td>				   
				   <td class="tdcampos"><?echo $tipocliente?></td>	
				   <td class="tdcampos"><?echo $datost[nombre]?></td>		
				   <td class="tdcampos"><?echo $datost[estado_cliente]?></td>	    
				   <td class="tdcampos"></td>	    
				   <td class="tdcampos"></td>	    
				   <td class="tdcampos"></td>	    
			  </tr>
		 <? }
		}else{
		  $tipocliente="Beneficiario";	
		  $versibenefi=("select beneficiarios.id_beneficiario,beneficiarios.id_cliente,
		                 beneficiarios.fecha_creado,entes.nombre,estados_clientes.estado_cliente,
		                 beneficiarios.id_parentesco,beneficiarios.id_titular
                        from
                          titulares,entes,estados_t_b,estados_clientes,beneficiarios
                        where
                          beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                          estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
                          beneficiarios.id_titular=titulares.id_titular and
                          titulares.id_ente=entes.id_ente and
                          beneficiarios.id_cliente=$elidclien;");
          $repversibeni=ejecutar($versibenefi);    
          while($datost=asignar_a($repversibeni,NULL,PGSQL_ASSOC)){
		      $elidtularb=$datost[id_titular];
			  $elidparen=$datost[id_parentesco];
			  $datparentesc=("select clientes.nombres,clientes.apellidos,clientes.cedula,parentesco.parentesco 
			                  from
			                    clientes,parentesco,titulares
			                  where
			                    titulares.id_cliente=clientes.id_cliente and
			                    parentesco.id_parentesco=$elidparen and
			                    titulares.id_titular=$elidtularb");
			 $repdatparetesc=ejecutar($datparentesc);
			 $dataparen=assoc_a($repdatparetesc);                   
			 $elnomtitular="$dataparen[nombres] $dataparen[apellidos]";
			 $elcedula=$dataparen['cedula'];
			 $elparentes=$dataparen['parentesco'];	  
			  ?>
			   <tr>
				   <td class="tdcampos"><?echo $nomcompelto?></td>
				   <td class="tdcampos"><?echo $lacedula?></td>				   
				   <td class="tdcampos"><?echo $tipocliente?></td>	
				   <td class="tdcampos"><?echo $datost[nombre]?></td>		
				   <td class="tdcampos"><?echo $datost[estado_cliente]?></td>	    
				   <td class="tdcampos"><?echo $elnomtitular?></td>	    
				   <td class="tdcampos"><?echo $elcedula?></td>	
				   <td class="tdcampos"><?echo $elparentes?></td>    
			  </tr>
			<? }	
		}
	 }else{
		
		 $arreglonsta[$nsta][0]=$lacedulaclien;
		 $arreglonsta[$nsta][1]=$lanombre;
		 $nsta++; 
		 }
   }	 
   if($nsta>1){?>
    <table  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td></td>  
     </tr>
     <tr> 	 
         <td></td>  
     </tr>
     <tr> 	 
         <td></td>  
     </tr>
     <tr> 	 
         <td></td>  
     </tr>
     <tr> 	 
         <td></td>  
     </tr>
     <tr> 	 
         <td></td>  
     </tr>
    </table>
    <table  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=8 class="titulo_seccion">Listado de clientes que no aparecen en nuestro sistema</td>  
     </tr>
    </table>
     <br>
	 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
        <tr>
            <th class="tdtitulos">Cliente.</th>
            <th class="tdtitulos">C&eacute;dula.</th>
        </tr>  
    <? for($k=1;$k<=$nsta-1;$k++){
	
		
       $cedulanoes=$arreglonsta[$k][0];
       $nomnoes=$arreglonsta[$k][1];?>  
        <tr>
				   <td class="tdcampos"><?echo $cedulanoes?></td>
				   <td class="tdcampos"><?echo $nomnoes?></td>	    
			  </tr>
   <?}?>
    </table>         
  <?}
  }	 
}else {
  echo"  
   <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class=\"titulo_seccion\">No existe ningun archivo para cargar!!</td>  
     </tr>
</table>";
}?>
