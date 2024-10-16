<?php
include ("../../lib/jfunciones.php");
include ("arreglos.php");
sesion();
$paso=$_SESSION['pasopedido'];
$matriz=&$_SESSION['matriz'];
$cuanmat=count($matriz);
$dataArticulos=$_POST['codbarra'];
list($elcodigobarr,$ladepnden,$cantiactual,$labora)=explode('-',$dataArticulos);
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

//Consultar expresion monetaria de la poliza
$ConsultaSqlpolizaMoneda=("select id_moneda from coberturas_t_b,propiedades_poliza,polizas where
propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
polizas.id_poliza=propiedades_poliza.id_poliza and
id_cobertura_t_b='$lacoBoT'");
$DataPolizaMoneda=ejecutar($ConsultaSqlpolizaMoneda);
$PolMoneda=assoc_a($DataPolizaMoneda);
$PolIdMoneda=$PolMoneda['id_moneda'];
///FIN Monedad exprecion poliza

/////eliminar Articulo////
  if(isset($_POST['EliminarArt']) && $_POST['EliminarArt']==1)
  {$eliminarArticulo=$_POST['EliminarArt'];
   $posicion=$_POST['posicion'];//posciion la matriz a eliminar
   $elcodigobarr=$_POST['idarticulo'];//idArticulo
   $especialmed=$_POST['especmedic'];//id especialidad
   $ladepnden=$_POST['depe'];//dependencia a descontar
   $cantidadpedi=0;//dependencia a descontar

  }
  else
  {$eliminarArticulo=0;}

if($opera1=='externa'){ //para ver si es una orden de medicamentos externa
    $nomartic=("select examenes_bl.id_examen_bl,examenes_bl.examen_bl,examenes_bl.honorarios from examenes_bl where examenes_bl.codigo_barra='$elcodigobarr';");
    $repnomartic=ejecutar($nomartic);
    $cuantosme=num_filas($repnomartic);
    if ($cuantosme>=1){
        $dataarti=assoc_a($repnomartic);
        $nombrearti=$dataarti['examen_bl'];
        $precioarti=$dataarti['honorarios'];
          if($tratamienco==1){
               $nomlaborat='Si';
               $fechatrat=$fechatrat;
           }else{
            	 $nomlaborat='No';
            	 $fechatrat=$fechatrat;
        	}
    }//fin de ver si el codigo de barra existe o no!!

}//fin de las ordenes externas
else{//para ver si son ordenes internas
       $cuantosme=1;
        if($tratamienco==1){
           $nomlaborat='Si';
           $fechatrat=$fechatrat;
        }else{
          $nomlaborat='No';
          $fechatrat=$fechatrat;
          }
        $ConsultaInsumo=("select tbl_insumos.id_insumo, tbl_insumos.insumo, tbl_laboratorios.laboratorio, tbl_insumos.id_tipo_insumo,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.id_dependencia,tbl_insumos_almacen.id_moneda from tbl_insumos,tbl_laboratorios,tbl_insumos_almacen where tbl_insumos.id_insumo=$elcodigobarr and tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
        tbl_insumos_almacen.id_insumo=tbl_insumos.id_insumo and tbl_insumos_almacen.id_dependencia=$ladepnden;");
        $RegistroInsumo=ejecutar($ConsultaInsumo);

//////DATOS DEL INSUMO////
$DatosInsumo=assoc_a($RegistroInsumo);
  $elidarticulo=$DatosInsumo['id_insumo'];
  $nombrearti=$DatosInsumo['insumo'];
  $laboratorio=$DatosInsumo['laboratorio'];//marca
  $tipoinsumo=$DatosInsumo['id_tipo_insumo'];
  $CantAlmacen=$DatosInsumo['cantidad'];
  ///CALCULO DEL CAMBIO
  $idMoneda=$DatosInsumo['id_moneda'];///MONEDA EN LA QUE ESTA EXPRESADA LAS CANTIDADES DE ALMACEN
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
/////BUSCAR EL PRECIO DEL ARTICULO//////////
  if($tipoinsumo=='1'){ $iddepenprecio='64';}//si tipo insumo medicamentos usar el id Medicamentos Ambulatorio Merida(64)
  else{ $iddepenprecio='89';}
  //consultar almacen principales (Medicamentos Ambulatorio Merida y almacen merida 08-02-2022)
       $datosprointer=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where
                    tbl_insumos_almacen.id_insumo=$elcodigobarr and tbl_insumos_almacen.id_dependencia=$iddepenprecio");
       $repdatosprointer=ejecutar($datosprointer);
       $paravesi=num_filas($repdatosprointer);
          if($paravesi>=1){
	          $dataarti=assoc_a($repdatosprointer);
          }else{
                //verificar y mejorar almacen de control e precios Articulos
                 $datosprointer1=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where
                                   tbl_insumos_almacen.id_insumo=$elcodigobarr and tbl_insumos_almacen.id_dependencia=2");
        	       $repdatosprointer1=ejecutar($datosprointer1);
                 $paravesicontrol=num_filas($repdatosprointer1);
                  if($paravesicontrol>=1){
                    $dataarti=assoc_a($repdatosprointer1);
                  }else{//consultar el precio del almacen
                    $datosprointer2=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where
                                      tbl_insumos_almacen.id_insumo=$elcodigobarr and tbl_insumos_almacen.id_dependencia=$ladepnden");
                    $repdatosprointer2=ejecutar($datosprointer2);
                    $dataarti=assoc_a($repdatosprointer2);
                  }
            }

      $precioarti=Formato_Numeros($dataarti['monto_unidad_publico']);
}//fin de las ordenes externas

////CARGAR ARTICULOS
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
  ///APLICAR CONTROL DE CAMBIO
  if($ejecutarCambio==1)
  $precioarti=Formato_Numeros($precioarti*$valorCambio);
  ///POLIZA EN USD Y PRECIOS EN BS
  if($ejecutarCambio==0)
  $precioarti=Formato_Numeros($precioarti/$valorCambio);
    //FIN CONTROL DE PRECIO

  if(($cuanmat>0) and ($cantidadpedi==0)){
    ////Eliminar elementos
    if($eliminarArticulo==1)//eliminar por boton
    {$laposiciaeliminar=$posicion;}
    else
    { $laposiciaeliminar=recursiveArraySearch($matriz,$elcodigobarr,$especialmed);}

      $lamatix=borrarposicion($matriz,$laposiciaeliminar);
		 $cuantomatriz=count($lamatix);

     }else{
         //Agregar Elementos
		/*En este paso es para carga los elmentos en la matriz con la función cargarMatriprimero()*/
		$lamatix=cargarMatriprimero($matriz,$paso,$nombrearti,$nomlaborat,$cantidadpedi,$precioarti,$fechatrat,$elidarticulo,$ladepnden,$especialmed,$CantAlmacen);
//print_r($lamatix);
    $cuantomatriz=count($lamatix);
	   }//fin del else de la carga de los elementos en la matriz
}
echo "<br>";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion">Art&iacute;culos cargados</td>
     </tr>
</table>
<style>
#tabletotales td{
  border: 2px solid #c7c7c7;
 vertical-align:top;
 text-align:right;
}
.izqd td{
 text-align:left !important;
}
.derc td{
 text-align:right!important;
}

</style>
<table id='tabletotales' class="tabla_citas colortable"  cellpadding=0 cellspacing=0>
              <tr>
	               <th class="tdtitulos" style="width: 2%;">Lin.</th>
                 <th class="tdtitulos" style="width: 20%;">Servicio.</th>
                 <th class="tdtitulos" style="width: 30%;">Nombre del art&iacute;culo.</th>
                 <th class="tdtitulos" style="width: 10%;"title="Tratamiento Continuo">Trat. Cont.</th>
                 <th class="tdtitulos" title="Cantidad pedidas(Cantidad disponible-Cantidad total pedidas)">Cant.(Alm-Ped)</th>
                 <th class="tdtitulos" >Precio.</th>
                 <th class="tdtitulos" >Sub-Total.</th>
                 <th class="tdtitulos" style="width: 2%;">opc</th>
              </tr>
            <?
			   $lin=1;
			   $canar=0;
			   $subgener=0;
         $error=0;
         $catiada=array();////almacena las cantidades pedidas por Artivulos
               for($i=0;$i<=$cuantomatriz;$i++){
                          $nom=$lamatix[$i][0];//NOMBRE ARTICULO
                          $lab=$lamatix[$i][1];//TRATAMIESNTO CONTINUO
                          $cant=$lamatix[$i][2];//cantidad
                          $prec=$lamatix[$i][3];//precio
                          $subt=$lamatix[$i][4];//Subototal
                          $fechaC=$lamatix[$i][5];//idArticulo
  						            $idart=$lamatix[$i][6];//idArticulo
						              $dep=$lamatix[$i][7];//fechaContinuo
						              $especialidad=$lamatix[$i][8];//id especialidad
						              $CantidadAlmacen=$lamatix[$i][9];//id especialidad
                          if(isset($catiada["$idart"]))
                          {$pedidos=$catiada["$idart"];}
                          else{$pedidos=0;}
                          $catidadPedido=$pedidos+$cant;
                          $catiada["$idart"]=$catidadPedido;
                          $CantidaRestante=$CantidadAlmacen-$catidadPedido;
                          if($CantidaRestante<0)
                          {$error++;
                           $stile="style='background-color: red !important;'";
                         }else{$stile='';}
                          ////consultar la especialidad
                          $especialidadm=("select especialidades_medicas.id_especialidad_medica,
                                            especialidades_medicas.especialidad_medica
                                         from especialidades_medicas where id_especialidad_medica=$especialidad;");
                          $repespecialidam=ejecutar($especialidadm);
                          $espc=assoc_a($repespecialidam);
                          $nombrespecialida=$espc['especialidad_medica'];
						  $canar=$canar+$cant;
						  $subgener=$subgener+$subt;

              $arrayArticulo="$i-$idart-$dep-$especialidad";
           echo"<tr $stile>";
						if(!empty($nom)){

                  ?>
                  <td class="tdcampos" ><?php echo $lin;?></td>
                  <td class="tdcampos" style="text-align:left;"><?php echo $nombrespecialida;?></td>
                  <td class="tdcampos" style="text-align:left;" ><?php echo $nom;?></td>
                  <td class="tdcampos" style="text-align:left;"><?php echo "$lab($fechaC)";?></td>
                  <td class="tdcampos" style="text-align:left;"><?php echo "$cant($CantidadAlmacen - $catidadPedido)";?></td>
                  <td class="tdcampos"><?php echo "$prec $MonedaSimb";?></td>
                  <td class="tdcampos"><?php echo "$subt $MonedaSimb";?></td>
                    <?php $areglo='';?>
                  <td class="tdcamposi" style="text-align:left;"><span id='borrar$lin' style='cursor:pointer' OnClick='EliminarArtProcepmediC("<?php echo $arrayArticulo;?>",<?php echo $i;?>)' ><img id='del$lin' alt='quitar de la lista este articulo' src='../public/images/del_16.png'/></span></td>
                </tr>
                       <?php
                        }else{$lin--;}

				$lin++;
               }
                 print_r($catiada);


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
			<input type=hidden id='error' value='<?echo $error?>'>
<style>
#tabletotales2 td{
 border: 2px solid #000;
 vertical-align:top;
 text-align:right;
}

</style>
      <table id='' class="tabla_citas"  cellpadding=0 cellspacing=0 >

			<tr>
			   <td style="width: 2%;"></td>
		     <td style="width: 20%;"></td>
		     <td style="width: 29%;"></td>
			   <td style="width: 10%;" class="tdcampos" >Total Cantida<br><br></td>
			   <td style="width: 12%;" class="tdcampos"><?php echo $canar;?></td>
			   <td style="width: 12%;" class="tdcampos">Van <?php echo $MonedaSimb;?></td>
			   <td style="width: 12%; text-align:right;" class="tdcampos"><?php echo $subgener;?></td>
         <td style="width: 3%;"></td>
			</tr>
      <?php if($error==0 && $canar>0){ /*boquea si hay errores */?>
    <div id='guardmedi'>
			<tr>
      <td  title="Guardar orden de m&eacute;dicamento"><label id='botonordmedi' class="boton" style="cursor:pointer" onclick="$('guardmedi').hide(), guardarEX(); return false; " >Guardar</label></td>
          </tr>
          <tr>
            <td><br><br>
            </td>
          </tr>
    </div>
    <?php } ?>
			</table>
<?php }else{
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
