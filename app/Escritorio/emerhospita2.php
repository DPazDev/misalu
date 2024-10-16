
<?
include ("../../lib/jfunciones.php");
include ("arreglos2.php");
sesion();
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];
$cuanmat=count($matriz);
$artidepene=$_POST['codbarra'];
list($elcodigobarr,$ladepnden)=explode('-',$artidepene);
$cantidadpedi=$_POST['lacantidad'];
$elcomentario=$_POST['elcomentar'];
$elproveedro=$_POST['elprovee'];
$numprecupu=$_POST['elnumproceso'];
$lacoBoT=$_POST['lacobert'];
$fechacargproceso=$_POST['fechacargpro'];
$tiposervi=$_POST['tiposervic'];
$servicio=$_POST['servicios'];
$_SESSION['proveeinterna']=$elproveedro;
$opera1=$_SESSION['toperacion'];
$_SESSION['operaelimina']='emhp';

	$datosprointer=("select 
tbl_insumos.insumo,tbl_insumos_almacen.monto_unidad_publico,
tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo from 
tbl_insumos,tbl_insumos_almacen,tbl_dependencias
where 
tbl_insumos.id_insumo=$elcodigobarr and
tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
tbl_insumos_almacen.id_dependencia=$ladepnden and
tbl_insumos_almacen.id_dependencia = tbl_dependencias.id_dependencia and
tbl_dependencias.activo <> 1;");
	$repdatosprointer=ejecutar($datosprointer);		
	$dataarti=assoc_a($repdatosprointer);
        $nombrearti=$dataarti['insumo'];
        $elidarticulo=$dataarti['id_insumo'];
        $tipoinsumo=$dataarti['id_tipo_insumo'];
        if($tipoinsumo==1){
			$ladependcia=64;
		}else{
			$ladependcia=89;
			}
        $buscarpr=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where id_insumo=$elidarticulo and tbl_insumos_almacen.id_dependencia=$ladependcia;"); 
        $repbuscarpr=ejecutar($buscarpr); 
        $dataprecio=assoc_a($repbuscarpr);  
        $precioarti=$dataprecio['monto_unidad_publico'];
        if($precioarti<=0){
         $buscarpr=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where id_insumo=$elidarticulo and tbl_insumos_almacen.id_dependencia=64");
        $repbuscarpr=ejecutar($buscarpr); 
        $dataprecio=assoc_a($repbuscarpr);  
        $precioarti=$dataprecio['monto_unidad_publico'];
     
         }
        echo "<br> $buscarpr";
        $ladpendencia=$ladepnden;
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
		$lamatix=cargarMatriprimero($matriz,$paso,$nombrearti,$cantidadpedi,$precioarti,$ladpendencia,$elidarticulo); 
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
                  <th class="tdtitulos">Laboratorio/Marca.</th>
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
                          $cant=$lamatix[$i][1];
                          $prec=$lamatix[$i][2];
                          $subt=$lamatix[$i][3];
                          $depen=$lamatix[$i][4];
                          $idarti=$lamatix[$i][5];    
                          $elnombmarca=("select tbl_laboratorios.laboratorio from tbl_laboratorios,tbl_insumos where
                                          tbl_insumos.id_insumo=$idarti and
                                          tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio");
                         $repelnombmarca=ejecutar($elnombmarca);   
                         $datmarca=assoc_a($repelnombmarca);  
                         $lamarcaes=$datmarca['laboratorio'];  
			  $canar=$canar+$cant;  
			   $subgener=$subgener+$subt;  
                   echo"<tr>";
						if(!empty($nom)){
                         echo"<td class=\"tdcampos\">$lin</td> ";
                         }else{$lin--;}
				echo"<td class=\"tdcampos\">$nom</td>
                          <td class=\"tdcampos\">$lamarcaes</td>  
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
			<input type=hidden id='elcomentes' value='<?echo $elcomentario;?>'>
			<input type=hidden id='lacoberturaes' value='<?echo $lacoBoT;?>'>
			<input type=hidden id='fecharecep' value='<?echo $fechacargproceso?>'>
			<input type=hidden id='tiposervi' value='<?echo $tiposervi?>'>
                        <input type=hidden id='servicio' value='<?echo $servicio?>'> 
			<input type=hidden id='totaladescar' value='<?echo $subgener?>'>
                        <input type=hidden id='presupnumero' value='<?echo $numprecupu?>'>
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
      <td  title="Guardar orden de m&eacute;dicamento externa"><label id='emerhoboton' class="boton" style="cursor:pointer" onclick="$('guardmedi').hide(), guardarEEH(); return false; " >Guardar</label></td>
          </tr> 
                       </div>
</table>

<img alt="spinner" id="spinner2" src="../public/images/esperar.gif" style="display:none;" />
<div id='noexistemed'></div>
