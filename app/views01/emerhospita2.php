<?php
include ("../../lib/jfunciones.php");
include ("arreglos2.php");
sesion();
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];
$cuanmat=count($matriz);
$artidepene=$_POST['codbarra'];
list($elcodigobarr,$ladepnden,$cantiactual)=explode('-',$artidepene);
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

///Consultar expresion monetaria de la poliza
$ConsultaSqlpolizaMoneda=("select id_moneda from coberturas_t_b,propiedades_poliza,polizas where
propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
polizas.id_poliza=propiedades_poliza.id_poliza and
id_cobertura_t_b='$lacoBoT'");
$DataPolizaMoneda=ejecutar($ConsultaSqlpolizaMoneda);
$PolMoneda=assoc_a($DataPolizaMoneda);
$PolIdMoneda=$PolMoneda['id_moneda'];///Monedad exprecion
//consulta de precios y datos del insumo
$datosprointer=("select
tbl_insumos.insumo,tbl_insumos_almacen.monto_unidad_publico,
tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.id_moneda
from
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
        $CantActArticulo=$dataarti['cantidad'];
				///CALCULO DEL CAMBIO
        $idMoneda=$dataarti['id_moneda'];
				$CosnuSQLCAmbio=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas where tbl_monedas.id_moneda=$idMoneda;");
				$cambioMonedaData=ejecutar($CosnuSQLCAmbio);
				$CacmbioMoneda=assoc_a($cambioMonedaData);
				$valorCambio=$CacmbioMoneda['valor'];///Ultimo CAmbio de moneda
				$monedaCambio=$CacmbioMoneda['moneda'];///Moneda de Cambio
				$SimboloCambio=$CacmbioMoneda['simbolo'];///Simbolo de la moneda
				///cambio a usar segun la poliza
				$ejecutarCambio=1;
					if($PolIdMoneda==1)//Poliza en moneda local
					{	if($PolIdMoneda==$idMoneda)//la expresion de la moneda del producto es el mismo
							{$valorCambio=1;}
							$MonedaSimb="BS";
					}/// la expresion monetaria se mantiene
					else{//poliza en dolares
						if($PolIdMoneda==$idMoneda)//la expresion de la moneda del producto es el mismo
						{$valorCambio=1;
							$MonedaSimb=$SimboloCambio;
						}else{
							///Exprecion
							$valorCambio=$_SESSION['valorcambiario'];
							$MonedaSimb="USD"; $ejecutarCambio=0;
						}


					}

    if($tipoinsumo==1){
			$ladependcia=64;
		}else{
			$ladependcia=89;
			}
		////Estructura de Precio Articulos/////
		///1)usar El Almacen id 2 "COMROL DE PRECIOS ARTICULOS"
		///2)usar El Almacen id 64 FRAMCIA MERIDA o 89 ALMACEN MERIDA
		///3)usar El Almacen ACTUAL DE PEDIDO

        $buscarpr=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where id_insumo=$elidarticulo and tbl_insumos_almacen.id_dependencia=2;");
        $repbuscarpr=ejecutar($buscarpr);
        $dataprecio=assoc_a($repbuscarpr);
        $precioarti=$dataprecio['monto_unidad_publico'];
        if($precioarti<=0){
			        $buscarpr=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where id_insumo=$elidarticulo and tbl_insumos_almacen.id_dependencia=$ladependcia");
			        $repbuscarpr=ejecutar($buscarpr);
			        $dataprecio=assoc_a($repbuscarpr);
			        $precioarti=$dataprecio['monto_unidad_publico'];
					 if($precioarti<=0){
						        $buscarpr=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where id_insumo=$elidarticulo and tbl_insumos_almacen.id_dependencia=$ladepnden");
						        $repbuscarpr=ejecutar($buscarpr);
						        $dataprecio=assoc_a($repbuscarpr);
						        $precioarti=$dataprecio['monto_unidad_publico'];
			         }

         }
		///APLICAR CONTROL DE CAMBIO
		if($ejecutarCambio==1)
		$precioarti=Formato_Numeros($precioarti*$valorCambio);
		///POLIZA EN USD Y PRECIOS EN BS
		if($ejecutarCambio==0)
		$precioarti=Formato_Numeros($precioarti/$valorCambio);
			//FIN CONTROL DE PRECIO

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
				//eliminar de la lista
         	$laposiciaeliminar=recursiveArraySearch($matriz,$nombrearti);
		 	 		$lamatix=borrarposicion($matriz,$laposiciaeliminar);
		 			$cuantomatriz=count($lamatix);
     }else{
			 /*En este paso es para carga los elmentos en la matriz con la función cargarMatriprimero()*/
			 $lamatix=cargarMatriprimero($matriz,$paso,$nombrearti,$cantidadpedi,$precioarti,$ladpendencia,$elidarticulo,$CantActArticulo);
			 $cuantomatriz=count($lamatix);
	 		} //fin del else de la carga de los elementos en la matriz

}	//fin de else de eliminar los productos
echo "<br>";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion">Art&iacute;culos cargados</td>
     </tr>
</table>
<table class="tabla_citas colortable"  cellpadding=0 cellspacing=0>
              <tr>
	         <th class="tdtitulos">Lin.</th>
                 <th class="tdtitulos">Nombre del art&iacute;culo.</th>
                  <th class="tdtitulos">Laboratorio/Marca.</th>
                 <th class="tdtitulos">Cantidad.</th>
                 <th class="tdtitulos">Precio.</th>
                 <th class="tdtitulos">Sub-Total.</th>
                 <th class="tdtitulos">Cant. Restante.</th>
                 <th class="tdtitulos">opc</th>
              </tr>
            <?php
						////carga inicial
			   $lin=1;
			   $canar=0;
			   $subgener=0;
				 $CanActInsumo=0;
			   $canidadrestante=0;
				 $error=0;
				 ////carga inicial
				 $sepuedeguardar=0;//control de numero en negativo de cantidades
			for($i=0;$i<=$cuantomatriz;$i++){
              $nom=$lamatix[$i][0];
              $cant=$lamatix[$i][1];
              $prec=$lamatix[$i][2];
              $subt=$lamatix[$i][3];
              $depen=$lamatix[$i][4];
              $idarti=$lamatix[$i][5];
              $CanActInsumo=$lamatix[$i][6];

              $elnombmarca=("select tbl_laboratorios.laboratorio from tbl_laboratorios,tbl_insumos where
                                          tbl_insumos.id_insumo=$idarti and
                                          tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio");
              $repelnombmarca=ejecutar($elnombmarca);
              $datmarca=assoc_a($repelnombmarca);
              $lamarcaes=$datmarca['laboratorio'];
							//totales
			  			$canar=$canar+$cant;
			  			$TprecioU=$TprecioU+$prec;//precios unitarios
			   			$subgener=$subgener+$subt;
							$resta=$CanActInsumo - $cant;
							if($resta<0)
							{$error++;
								$alerta="style='color: #DC143C;'";
							}else{$alerta='';}
              echo"<tr>";
								if(!empty($nom)){
	             echo"<td class=\"tdcampos\">$lin</td> ";
							 echo"<td class='tdcampos'>$nom</td>
	                	<td class='tdcampos'>$lamarcaes</td>
	                  <td class='tdcampos'>$cant</td>
	                  <td class='tdcampos'>$prec $MonedaSimb</td>
	                  <td class='tdcampos'>$subt $MonedaSimb</td>
	                  <td class='tdcampos'><span id='$lin' $alerta>$resta de ($CanActInsumo)</span></td>
										<td class='tdcampos'><span id='borrar$lin' style='cursor:pointer' OnClick='QuitarArticuloOrdenMedicamentos($idarti,$depen,$CanActInsumo)' ><img id='del$lin' alt='quitar de la lista este articulo' src='../public/images/del_16.png'/></span></td>
                  </tr>";
									if($cant<=0)
									{$sepuedeguardar++;}

									}else{$lin--;}
											 	$lin++;
               }
            ?>


			<tr>
			   <td class="tdcampos" colspan="2"><br><br></td>
		     <td class="tdcampos">TOTAL</td>
			   <td class="tdcampos"><?php echo "$canar"; ?></td>
			   <td class="tdcampos"><?php echo"$TprecioU $MonedaSimb";?></td>
			   <td class="tdcampos"><?echo "$subgener $MonedaSimb";?></td>
			   <td class="tdcampos" colspan="2"><?echo $MonedaSimb;?></td>
			</tr>
			<input type=hidden id='elproveedores' value='<?echo $elproveedro;?>'>
			<input type=hidden id='elcomentes' value='<?echo $elcomentario;?>'>
			<input type=hidden id='lacoberturaes' value='<?echo $lacoBoT;?>'>
			<input type=hidden id='fecharecep' value='<?echo $fechacargproceso?>'>
			<input type=hidden id='tiposervi' value='<?echo $tiposervi?>'>
			<input type=hidden id='servicio' value='<?echo $servicio?>'>
			<input type=hidden id='totaladescar' value='<?echo $subgener?>'>
			<input type=hidden id='presupnumero' value='<?echo $numprecupu?>'>
			<input type=hidden id='almacenresta' value='<?echo $resta?>'>
		</table>

<?php if($canar>0 && $sepuedeguardar==0 && $error==0){
		///si existen errores no mostrar
		//if($error==0){
	?>
		<table class="tabla_citas colortable"  cellpadding=0 cellspacing=0>
                       <div id='guardmedi'>
			<tr>
      <td  title="Guardar orden de m&eacute;dicamento externa"><label id='emerhoboton' class="boton" style="cursor:pointer" onclick="$('guardmedi').hide(), guardarEEH(); return false; " >Guardar</label></td>
          </tr>
                       </div>
</table>
<?php  //}//errores
}else{
	if($canar<=0 && $sepuedeguardar>0 ) $yesta=' Y '; else $yesta='';
	if($canar<=0) $mensajecat=' No hay art&iacute;culos para Cargar '; else $mensajecat='';
	if($sepuedeguardar>0 || $error>0) $artcero='existen art&iacute;culos en 0 o negativos para Cargar, debe quitarlos de la lista'; else $artcero='';



	echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
	     <tr>
	       <br>
	         <td colspan=4 class=\"titulo_seccion\">$mensajecat $yesta $artcero</td>
	     </tr>
	</table>";

}

?>
<img alt="spinner" id="spinner2" src="../public/images/esperar.gif" style="display:none;" />
<div id='noexistemed'></div>
