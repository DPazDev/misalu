<?
include ("../../lib/jfunciones.php");
sesion();
$Admnes=$_POST['adminid'];
$Admarr=explode('@', $Admnes);
$elades=$Admarr[0];
$Noadm=$Admarr[1];
$Adepe=$_POST['depencia'];
$depenopcio = explode('@', $Adepe);
$ladepes= $depenopcio[0]; 
$Nomd= $depenopcio[1];

if ($elades>0){
	$endep=2; 
	$busads=("select tbl_admin_dependencias.id_admin_dependencia,admin.nombres,admin.apellidos,tbl_dependencias.dependencia,tbl_admin_dependencias.activar 
      from admin,tbl_dependencias,tbl_admin_dependencias where
      tbl_admin_dependencias.id_admin=admin.id_admin and
      tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
      tbl_admin_dependencias.id_admin='$elades' order by nombres;");
	  $repbusads=ejecutar($busads);  
	  $numfil=num_filas($repbusads); 
	      if ($numfil>0){  
	          $mensaje="El usuario $Noadm esta registrado en la(s) dependecia(s)" ; 
		  }else{
			  $mensaje="El usuario $Noadm no se encuentra registrado en ninguna dependencia" ;    
			 }	  
	}else{
		$endep=1;
		$busden=("select tbl_admin_dependencias.id_admin_dependencia,admin.nombres,admin.apellidos,tbl_dependencias.dependencia,tbl_admin_dependencias.activar from admin,tbl_dependencias,tbl_admin_dependencias where
      tbl_admin_dependencias.id_admin=admin.id_admin and
      tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
      tbl_admin_dependencias.id_dependencia='$ladepes' order by nombres;");
	 $repbusden=ejecutar($busden); 
     $numfil=num_filas($repbusden);   
	      if($numfil>0){  
		 	  $mensaje="Usuario(s) registrados en la dependencia $Nomd";   
		  }else{
			   $mensaje="No hay usuarios asignados a la dependencia $Nomd";     
		  }	  
	}	
		   echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
           <tr> 
			<td colspan=4 class=\"titulo_seccion\">$mensaje</td>  
          </tr>
          </table>";	
  if (($numfil>0)&&($endep==1)){ 
?>
 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Nombre del usuario.</th>
                 <th class="tdtitulos">Permisolog&iacute;a.</th>
				 <th class="tdtitulos">Opci&oacute;n.</th> 
            </tr>
			<?while($usuDep=asignar_a($repbusden,NULL,PGSQL_ASSOC)){
				   $opActivar=$usuDep['activar'];
				     if($opActivar==1){
                        $opc='Pedidos y Entregas';
                     }else{
                     if($opActivar==2){
                        $opc='Entregas';
                     }else{
                       if($opActivar==3){
                         $opc='Pedidos';
                        }else{
                              $opc='Desactivar';
                             }
                        }
                    }   
				echo"<tr>
                              <td class=\"tdcampos\">$usuDep[nombres] $usuDep[apellidos]</td>
							   <td class=\"tdcampos\"> $opc</td>
                              <td  title=\"Modificar permisolog&iacute;a del usuario\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"modifudep($usuDep[id_admin_dependencia],'$Nomd')\" >Modificar </label></td>
                         </tr>";	
				}?>
 </table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<?}
    if (($numfil>0)&&($endep==2)){ 
?> 
   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Nombre de la dependencia.</th>
                 <th class="tdtitulos">Permisolog&iacute;a.</th>
				  <th class="tdtitulos">Opci&oacute;n.</th>  
            </tr>
			<?while($usuDep=asignar_a($repbusads,NULL,PGSQL_ASSOC)){
				   $opActivar=$usuDep['activar'];
				     if($opActivar==1){
                        $opc='Pedidos y Entregas';
                     }else{
                     if($opActivar==2){
                        $opc='Entregas';
                     }else{
                       if($opActivar==3){
                         $opc='Pedidos';
                        }else{
                              $opc='Desactivar';
                             }
                        }
                    }   
				echo"<tr>
                              <td class=\"tdcampos\">$usuDep[dependencia]</td>
							   <td class=\"tdcampos\"> $opc</td>
                               <td  title=\"Modificar permisolog&iacute;a del usuario\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"modifudep($usuDep[id_admin_dependencia],'$Nomd')\" >Modificar </label></td>
                         </tr>";	
				}?>
 </table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<?}?> 
