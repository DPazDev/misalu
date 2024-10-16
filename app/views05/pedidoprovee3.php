<?
include ("../../lib/jfunciones.php");
include ("arreglos.php");
sesion();
$elartiid=$_POST['nomar'];
$barraco=$_POST['barras'];
if (empty($elartiid)){
  $buscarpocodigo=("select tbl_insumos.id_insumo from tbl_insumos where tbl_insumos.codigo_barras='$barraco';");
  $repbuscarpcodigo=ejecutar($buscarpocodigo);
  $datcodigobarra=assoc_a($repbuscarpcodigo);
  $eliddart=$datcodigobarra['id_insumo'];
  $rr=1;
}
$tipopedi=$_SESSION['pedidodepen'];
$arrdata= explode('@',$elartiid); 
if($rr<>1){
$eliddart=$arrdata[0];
}
$re=$_SESSION['existepedido'];
if($tipopedi==1){
   $elprecio=0;
}else{
	$elprecio=$arrdata[1];
	$pre1=explode('|',$elprecio);
	$elprecio=$pre1[0];
	}
$paso=$_SESSION['pasopedido'];
$lacantidad=$_POST['canti'];
$nomartic=("select tbl_insumos.insumo,tbl_laboratorios.laboratorio from tbl_insumos,tbl_laboratorios where tbl_insumos.id_insumo='$eliddart' 
and tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio;");
$repnomartic=ejecutar($nomartic);
$dataarti=assoc_a($repnomartic);
$nomcoparti=$dataarti['insumo'];
$nomlaborat=$dataarti['laboratorio'];
$matriz=&$_SESSION['matriz'];
$cuanmat=count($matriz);
if (($cuanmat==0) and ($lacantidad==0)){
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">No hay art&iacute;culos para eliminar</td>
     </tr>
</table>";

}else{
	  
      if (($cuanmat>0) and ($lacantidad==0)){
         $laposiciaeliminar=recursiveArraySearch($matriz,$nomcoparti); 		
		 $lamatix=borrarposicion($matriz,$laposiciaeliminar);
		 $cuantomatriz=count($lamatix);
		
     }else{
		/*En este paso es para carga los elmentos en la matriz con la función cargarMatriprimero()*/ 
		$lamatix=cargarMatriprimero($matriz,$paso,$nomcoparti,$nomlaborat,$lacantidad,$elprecio,$eliddart); 
		$cuantomatriz=count($lamatix);
	 } //fin del else de la carga de los elementos en la matriz	
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
                 <th class="tdtitulos">Laboratorio.</th>
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
                
				         echo"<tr>
                            <td class=\"tdcampos\"></td>  
                            <td class=\"tdcampos\"></td>  
                           <th>Totales:</th>  
							<th>$canar</th>  
                            <td class=\"tdcampos\"></td>   
                             <th>$subgener</th>  
                           </tr>
						 ";  
				
           ?>
            <br>
			</table>
			<table class="tabla_citas"  cellpadding=0 cellspacing=0>
			<tr>
			   <td colspan=6><hr></td>  
			</tr>
			<tr>
			   <td colspan=6><label style="color: #ff0000"> Nota: Si el pedido tiene alg&uacute;n comentario, cargarlo al finalizar el pedido.</label> </td>  
			</tr>
			<tr>
			    <td class="tdcampos">Comentario:</td>  
				<td class="tdcampos"><TEXTAREA COLS=65 ROWS=3 id="comentpedi" class="campos"></TEXTAREA></td>                <td class="tdcampos"></td>  
				<td class="tdcampos"></td>
			</tr>
			<br>
            <tr>
                <td><label id="despachoprovee" class="boton" title="Guardar orden de pedido proveedor" style="cursor:pointer" onclick="guarocomprapro(); return false; " >Guardar</label></td>
            </tr>
</table>
<? 
    
  }//fin del else que revisa que en la matriz ya no hay articulos para eliminar;
?> 
