<?
  include ("../../lib/jfunciones.php");
  sesion();
  $eladminid=$_POST['adminid'];
  $ladepenid=$_POST['depedid'];
  $buscdepen=("select dependencia from tbl_dependencias where id_dependencia='$ladepenid';");
  $repbusdepen=ejecutar($buscdepen);
  $datadepen=assoc_a($repbusdepen);
  $lapermies=$_POST['permi'];
  $fecha=date("Y-m-d");
  $hora=date("H:i:s");
  $elid=$_SESSION['id_usuario_'.empresa];
  $elus=$_SESSION['nombre_usuario_'.empresa];
  $buslosidentad=("select  id_admin from tbl_admin_dependencias where id_dependencia='$ladepenid' and id_admin='$eladminid';");
  $repbuslosidentad=ejecutar($buslosidentad);  
  $datidendep=assoc_a($repbuslosidentad);
  $semodif=$_POST['modiusu'];   
    if($semodif>0){
		$modusu=("update  tbl_admin_dependencias set activar='$lapermies' where id_admin='$eladminid' and id_dependencia='$ladepenid';");
		$repmodusu=ejecutar($modusu);
		$quienes=("select nombres,apellidos from admin where id_admin='$eladminid';");  
		$repquienes=ejecutar($quienes);
		$datquien=assoc_a($repquienes);  
		$nomcompleto="$datquien[nombres] $datquien[apellidos]"; 
		echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
           <tr> 
			<td colspan=4 class=\"titulo_seccion\">El usuario $nomcompleto ha sido modificado exitosamente!!</td>  
          </tr>
          </table>";	
	}   
   //ver si el usuario al que se quiere registrar ya esta en la tabla  tbl_admin_dependencias
		  if ( $datidendep[id_admin]==$eladminid)  {
			  $esta=1;  
			  $quienes=("select nombres,apellidos from admin where id_admin='$eladminid';");  
			  $repquienes=ejecutar($quienes);
			  $datquien=assoc_a($repquienes);  
			  $nomcompleto="$datquien[nombres] $datquien[apellidos]"; 
		 }
  //fin de la busquedad

if ($esta!=1){  
  //guardar los datos en la tabla tbl_admin_dependencias
   $insertaddep=("insert into tbl_admin_dependencias(id_dependencia,id_admin,activar,fecha_creado) values('$ladepenid','$eladminid','$lapermies','$fecha');");
   $repinsetaddep=ejecutar($insertaddep);
 //fin de los registros en la tabla tbl_admin_dependencias
 //Buscar a los usuarios que estan registrados en la tabla
} 
   $busaddepen=("select admin.nombres,admin.apellidos,tbl_admin_dependencias.activar,tbl_admin_dependencias.id_admin_dependencia from admin,tbl_admin_dependencias,tbl_dependencias where 
                 tbl_admin_dependencias.id_admin=admin.id_admin and 
                 tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
                 tbl_admin_dependencias.id_dependencia='$ladepenid' order by nombres ;");
   $repbususudepen=ejecutar($busaddepen);
 //
if($esta==1){
	 echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
           <tr> 
			<td colspan=4 class=\"titulo_seccion\">El usuario $nomcompleto ya esta registrado!!</td>  
          </tr>
          </table>";	
} 
?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
      <tr>
         <td colspan=4 class="titulo_seccion">Usuario(s) registrado(s) en la dependencia <?echo $datadepen['dependencia'];?> </td>
       </tr>
   </table>
   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <br>
       <tr>
        <th class="tdtitulos">Nombre del usuario.</th>
        <th class="tdtitulos">Permisolog&iacute;a.</th>
        <th class="tdtitulos">Opci&oacute;n.</th>
       </tr>
     <? while($usuadepend=asignar_a($repbususudepen,NULL,PGSQL_ASSOC)){
        $usuactivar=$usuadepend['activar'];
		$nomad="$usuadepend[nombres] $usuadepend[apellidos]"; 
         if($usuactivar==1){
             $opc='Pedidos y Entregas';
         }else{
                if($usuactivar==2){
                  $opc='Entregas';
                }else{
                       if($usuactivar==3){
                         $opc='Pedidos';
                        }else{
                              $opc='Desactivar';
                             }
                     }
                }
		echo"<tr>
                     <td class=\"tdcampos\">$usuadepend[nombres] $usuadepend[apellidos]</td>
                      <td class=\"tdcampos\">$opc</td>
                     <td  title=\"Modificar permisolog&iacute;a del usuario\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"modifudep($usuadepend[id_admin_dependencia],'$nomad')\" >Modificar </label></td>
                   </tr>
                  ";		
		}
        ?>  
  </table>

