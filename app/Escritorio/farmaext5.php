<?
include ("../../lib/jfunciones.php");
include ("arreglos.php");
sesion();
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];
$cuanmat=count($matriz);
$elcodigobarr=$_POST['codbarra'];
$tratamienco=$_POST['tratamien'];
$fechatrat=$_POST['fechatrata'];
$especialmed=$_POST['especmedic'];
$cantidadpedi=$_POST['lacantidad'];
$elcomentario=$_POST['elcomentar'];
$elproveedro=$_POST['elprovee'];
$_SESSION['proveeinterna']=$elproveedro;
$laenfermedad=$_POST['laenferm'];
$opera1=$_SESSION['toperacion'];
$lacoBoT=$_POST['lacobert'];
$fechacargproceso=$_POST['fechacargpro'];
if($opera1=='externa'){//para ver si es una orden de medicamentos externa
$nomartic=("select examenes_bl.id_examen_bl,examenes_bl.examen_bl,examenes_bl.honorarios from examenes_bl where examenes_bl.codigo_barra='$elcodigobarr';");
$repnomartic=ejecutar($nomartic);
$cuantosme=num_filas($repnomartic);
if ($cuantosme>=1){
$dataarti=assoc_a($repnomartic);
$nombrearti=$dataarti['examen_bl'];
$precioarti=$dataarti['honorarios'];
  if($tratamienco==1){
      $nomlaborat='Si';
      $idarticulo=$fechatrat;   
   }else{
	 $nomlaborat='No';
	$idarticulo=$fechatrat;
	}
}//fin de ver si el codigo de barra existe o no!!

}//fin de las ordenes externas
else{//para ver si son ordenes internas
       $cuantosme=1;
        if($tratamienco==1){
           $nomlaborat='Si';
           $idarticulo=$fechatrat;
        }else{
          $nomlaborat='No';
          $idarticulo=$fechatrat;
          }

	$datosprointer=("select tbl_insumos.insumo,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos.id_insumo from 
					tbl_insumos,tbl_insumos_almacen where 
                                        tbl_insumos.id_insumo=$elcodigobarr and
                                        tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
					(tbl_insumos_almacen.id_dependencia=64 or tbl_insumos_almacen.id_dependencia=89)");
       $repdatosprointer=ejecutar($datosprointer);		
       $paravesi=num_filas($repdatosprointer);
         if($paravesi>=1){
	    $dataarti=assoc_a($repdatosprointer);
         }else{
         $datosprointer1=("select tbl_insumos.insumo,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos.id_insumo from 
					tbl_insumos,tbl_insumos_almacen where 
                                        tbl_insumos.id_insumo=$elcodigobarr and
                                        tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
					tbl_insumos_almacen.id_dependencia=2");
	     $repdatosprointer1=ejecutar($datosprointer1);	
         $dataarti=assoc_a($repdatosprointer1);
       } 
        $nombrearti=$dataarti['insumo'];
        $elidarticulo=$dataarti['id_insumo'];
        $precioarti=$dataarti['monto_unidad_publico'];
}//fin de las ordenes internas
if($cuantosme>=1){
//cargamos los arti de la farmacia al arreglo
if (($cuanmat==0) and ($cantidadpedi==0)){
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">No hay art&iacute;culos para eliminar</td>
     </tr>
</table>";

}else{
	  
      if (($cuanmat>0) and ($cantidadpedi==0)){
         $laposiciaeliminar=recursiveArraySearch($matriz,$nombrearti); 		
		 $lamatix=borrarposicion($matriz,$laposiciaeliminar);
		 $cuantomatriz=count($lamatix);
		
     }else{
		/*En este paso es para carga los elmentos en la matriz con la función cargarMatriprimero()*/ 
		$lamatix=cargarMatriprimero($matriz,$paso,$nombrearti,$nomlaborat,$cantidadpedi,$precioarti,$idarticulo,$elidarticulo); 
		$cuantomatriz=count($lamatix);
	 } //fin del else de la carga de los elementos en la matriz	
}//fin de else de eliminar los productos 
echo "<br>";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
       <br> 
         <td colspan=4 class="titulo_seccion">Art&iacute;culos cargados</td>    
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
	         <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">Nombre del art&iacute;culo.</th>
                 <th class="tdtitulos">Tratamiento C.</th>
                 <th class="tdtitulos">Cantidad.</th>
                 <th class="tdtitulos">Precio.</th>
                 <th class="tdtitulos">Sub-Total.</th>
              </tr>
            <?
			   $lin=1;
			   $canar=0;   
			   $subgener=0;
               for($i=0;$i<=$cuantomatriz;$i++){
                          $nom=$lamatix[$i][0];
                          $lab=$lamatix[$i][1];
                          $cant=$lamatix[$i][2];
                          $prec=$lamatix[$i][3];
                          $subt=$lamatix[$i][4];
						  $idart=$lamatix[$i][5];  
						  $canar=$canar+$cant;  
						  $subgener=$subgener+$subt;  
                   echo"<tr>";
						if(!empty($nom)){
                         echo"<td class=\"tdcampos\">$lin</td> ";
                         }else{$lin--;}
				echo"<td class=\"tdcampos\">$nom</td>  
                          <td class=\"tdcampos\">$lab</td> 
                          <td class=\"tdcampos\">$cant</td>  
                          <td class=\"tdcampos\">$prec</td> 
                          <td class=\"tdcampos\">$subt</td>   
                        </tr>
                        
                       ";
							 
				$lin++;	   
               }
            ?>
            <br>
			</table>
			<input type=hidden id='elproveedores' value='<?echo $elproveedro;?>'>
			<input type=hidden id='laenfermedes' value='<?echo $laenfermedad;?>'>
			<input type=hidden id='elcomentes' value='<?echo $elcomentario;?>'>
			<input type=hidden id='lacoberturaes' value='<?echo $lacoBoT;?>'>
			<input type=hidden id='fecharecep' value='<?echo $fechacargproceso?>'>
			<input type=hidden id='especialmedi' value='<?echo $especialmed?>'>
			<input type=hidden id='totaladescar' value='<?echo $subgener?>'>
			<table class="tabla_citas"  cellpadding=0 cellspacing=0>
			<tr>
			   <td></td>
		       <td></td>   
			   <td></td>
			   <td></td>
			   <td class="tdcampos">Van Bs.S:</td>   
			   <td class="tdcampos"><?echo $subgener;?></td>   
			</tr>
                       <div id='guardmedi'>
			<tr>
      <td  title="Guardar orden de m&eacute;dicamento"><label id='botonordmedi' class="boton" style="cursor:pointer" onclick="$('guardmedi').hide(), guardarEX(); return false; " >Guardar</label></td>
          </tr> 
                       </div>
			</table>
<?}else{
echo "
<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">El art&iacute;culo no existe!!!!</td>
         <td class=\"titulo_seccion\" title=\"Incluir m&eacute;dicamento al sistema\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"inclMED(); return false;\" >Incluir</label></td>
     </tr>
</table>
";

}?>
<img alt="spinner" id="spinner2" src="../public/images/esperar.gif" style="display:none;" />
<div id='noexistemed'></div>
