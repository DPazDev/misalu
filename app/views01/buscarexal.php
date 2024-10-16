<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$id_ente=$_REQUEST['ente'];
$VarloCambio=$_SESSION['valorcambiario'];
$PolizaIdMoneda=$_REQUEST['monedaPoliza'];
///////////////CONSULTAR LA MONEDA/////
$sqlMonedasCambios=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo from tbl_monedas where tbl_monedas.id_moneda='1';");
$ModenasCambio=ejecutar($sqlMonedasCambios);
$MCambio=asignar_a($ModenasCambio,NULL,PGSQL_ASSOC);
$TMonedaBS=$MCambio[simbolo];
$TMonedaUSD='$';
/* ***** Ver el tipo de ente Juan Pablo ***** */
$vertipoente = ("select entes.id_tipo_ente from entes where id_ente=$id_ente");
$repvetipoente = ejecutar($vertipoente);
$dattipoente   = assoc_a($repvetipoente);
$elidtipoentees = $dattipoente['id_tipo_ente'];
/* ***** Fin de Ver el tipo de ente Juan Pablo ***** */
$q_examen="select
										examenes_bl.id_examen_bl,
										examenes_bl.examen_bl,
										tbl_baremos_precios.precio,
										tbl_baremos.id_moneda
								from
										examenes_bl,
										tbl_baremos_entes,
										tbl_baremos_precios,
										tbl_baremos
								where
								examenes_bl.examen_bl<>'' and
										examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
										tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
										tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo  and
										tbl_baremos_entes.id_ente=$id_ente and
										examenes_bl.id_tipo_examen_bl=3
								order by
										examenes_bl.examen_bl";
$r_examen=ejecutar($q_examen);
$num_filase=num_filas($r_examen);

if ($num_filase==0){
		$num_filase2=1;
	     if(($elidtipoentees == 7) || ($elidtipoentees == 2)){
	 $buscidentebare = "select entes.id_ente from entes,tbl_baremos_entes where
                                 entes.id_tipo_ente = 7 and
                                 entes.id_ente = tbl_baremos_entes.id_ente;";
         $repbuscidentebare = ejecutar($buscidentebare);
         $datdelidentbare = assoc_a($repbuscidentebare);
         $identebares =  $datdelidentbare['id_ente'];
         $q_examen="select examenes_bl.id_examen_bl, examenes_bl.examen_bl, tbl_baremos_precios.precio,tbl_baremos.id_moneda
		              from
		                 examenes_bl, tbl_baremos_entes, tbl_baremos_precios, tbl_baremos
		              where
		              examenes_bl.examen_bl<>'' and
		               examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
		               tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
		               tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo and
		               tbl_baremos_entes.id_ente=$identebares and examenes_bl.id_tipo_examen_bl=3
		               order by examenes_bl.examen_bl";
         $r_examen=ejecutar($q_examen);
				 $num_filase2=num_filas($repbuscidentebare);

      }

			if(($elidtipoentees <> 7) || ($elidtipoentees <> 2 || $num_filase2==0)){
						 $q_examen="select
																 *
													 from
																 examenes_bl
													 where
													 examenes_bl.examen_bl<>'' and
																 examenes_bl.id_tipo_examen_bl=3
													 order by
																 examenes_bl.examen_bl";
						 $r_examen=ejecutar($q_examen);

				}

     					?>
					<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5 colortable"  cellpadding='0' cellspacing='0' border='0'>
<tr> <th colspan='4' style='text-align: center !important;' align='center' class="tdtitulos" ><?php echo "<h2>LABORATORIO</h2>";?></th></tr>

       <?php
$i=0;
$ban="";
 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;
	$valorTaza=1;
                if(($elidtipoentees <> 7) || ($elidtipoentees <> 2) || $num_filase2==0){
									///7)CLIENTES INDIVIDUALES MEDICINA PREPAGADA -2 EMPRESAS PRIVADAS MEDICINA PREPAGADA ---$num_filase2 0 NO EXISTE BAREMO ENTE
									 	            $preciobaremo= $f_examen[honorarios];
																$id_moneda=$f_examen['id_moneda'];
															 if($id_moneda==1){//baremos en bs
														 			 $precioBS=$preciobaremo;
														 			 $preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
																	 $valorTaza=$VarloCambio;//usar Cambio USD * BS
														 	 }
														 	 /////POLIZA
														 	 if($PolizaIdMoneda==2)
														 	 {///si la poliza es en USD
														 		 $monto=$preciobaremo;
														 		 $TMonedaBS=$TMonedaUSD;
																 $valorTaza=1;//usar Cambio USD * BS
														 	 }else
														 	 if($PolizaIdMoneda==1)//bs poliza
														 	 {if($id_moneda==1){$monto=$precioBS;}
														 		else
														 		{$monto=Formato_Numeros($preciobaremo*$VarloCambio);}
																$valorTaza=$VarloCambio;//usar Cambio USD * BS
														 	 }
 	          }
                if((($elidtipoentees == 7) || ($elidtipoentees == 2)) && ($num_filase2!=0)){
  		    								$preciobaremo=$f_examen[precio];
													$id_moneda=$f_examen['id_moneda'];
												 if($id_moneda==1){//baremos en bs
														 $precioBS=$preciobaremo;
														 $preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
														 $valorTaza=$VarloCambio;//usar Cambio USD * BS
												 }
												 /////POLIZA
												 if($PolizaIdMoneda==2)
												 {///si la poliza es en USD
													 $monto=$preciobaremo;
													 $TMonedaBS=$TMonedaUSD;
													 $valorTaza=1;//usar Cambio USD * USB
												 }else
												 if($PolizaIdMoneda==1)//bs poliza
												 {if($id_moneda==1){$monto=$precioBS;}
													else
													{$monto=Formato_Numeros($preciobaremo*$VarloCambio);}
													$valorTaza=$VarloCambio;//usar Cambio USD * BS
												 }
		  					}
	?>
	<tr >
		<td colspan='2' class="tdtitulos" WIDTH='50'><p class="parafosexamenes"><?php echo $f_examen[examen_bl]?></p></td>
	<td colspan='1' class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength='128' size='10' value="<?php echo $f_examen[id_examen_bl]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength='128' size='10' value="<?php echo $f_examen[examen_bl]?>">
		<input class="campos" type="text" id="cambio_<?php echo $i?>"  name="USDexamen" maxlength='128' size="5" value="<?php echo $preciobaremo?>"  Oninput="return validarNumero(this);" OnChange="p=$F('cambio_<?php echo $i?>');$('honorarios_<?php echo $i?>').value=p*<?php echo $valorTaza?>;"><?php echo $TMonedaUSD;?>
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="examen" maxlength='128' size='10' value="<?php echo $monto;?>"   OnChange="return validarNumero(this);" ><?php echo $TMonedaBS;?>
		<input class="campos" type="hidden" id="coment_<?php echo $i?>"  name="coment" maxlength='128' size='10' value=""   >
		</td>
	<td colspan='1' class="tdcampos"><input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" name="checkl" maxlength='128' size='10' value="">
</td>
</tr>

	<?php
	}

	}
	else
	{

		?>
		<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
		<table class="tabla_cabecera5 colortable"  cellpadding=0 cellspacing=0 >
				<tr> <th colspan='4' style='text-align: center !important;' align='center' class="tdtitulos" ><?php echo "<h2>LABORATORIO</h2>";?></th></tr>
		<?php
			$i=0;
			$ban="";
		 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
					$i++;
									$preciobaremo=$f_examen[precio];
									$id_moneda=$f_examen['id_moneda'];
								 if($id_moneda==1){//baremos en bs
										 $precioBS=$preciobaremo;
										 $preciobaremo=Formato_Numeros($preciobaremo/$VarloCambio);
								 }
								 /////POLIZA
							 if($PolizaIdMoneda==2)
								 {///si la poliza es en USD
									 $monto=$preciobaremo;
									 $TMonedaBS=$TMonedaUSD;
								 }else
								 if($PolizaIdMoneda==1)//bs poliza
								 {if($id_moneda==1){$monto=$precioBS;}
									else
									{$monto=Formato_Numeros($preciobaremo*$VarloCambio);}
									}
					?>

					<tr>
						<td colspan='2' class="tdcampos" WIDTH='50'><p class="parafosexamenes"><?php echo $f_examen[examen_bl]?></p></td>
						<td colspan='1' class="tdcampos">
							<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength='128' size='10' value="<?php echo $f_examen[id_examen_bl]?>">
							<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength='128' size='10' value="<?php echo $f_examen[examen_bl]?>" />
							<input class="campos" type="text" id="cambio_<?php echo $i?>"  name="USDexamen" maxlength='128' size="5" value="<?php echo $preciobaremo?>"  Oninput="return validarNumero(this);" readonly='readonly' disabled="disabled" /><?php echo $TMonedaUSD;?>
							<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="examen" maxlength='128' size='10' value="<?php echo $monto?>"  OnChange="return validarNumero(this);" /><?php echo $TMonedaBS;?>
							<input class="campos" type="hidden" id="coment_<?php echo $i?>"  name="coment" maxlength='128' size='10' value=""   />
						</td>
						<td colspan='1' class="tdcampos"><input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" name="checkl" maxlength='128' size='5' value="" /></td>
					</tr>
					<?php
					}
	}

echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
		<tr class="nomostrar">
			<td colspan="4"><hr><br><br></td>
		</tr>

	 <tr class="nomostrar">
			<td  colspan='1' class="tdtitulos">* Monto</td>
		  <td colspan='1'><input class="campos" type="text" name="monto" maxlength='128' size='20' value="0"  OnChange="return validarNumero(this);" ></td>
			<td colspan='2' class="tdcampos nomostrar"><a href="javascript: sumar(this);" class="boton">Calcular Monto</a>	</td>
	 </tr>

	 <tr class="nomostrar">
				<td class="tdtitulos">* Cuadro Medico</td>
        <td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength='128' size='20' value=""   > </td>
        <td class="tdcampos"colspan='2'>Hora Cita:
					<input class="campos" type="text" name="horac" maxlength='128' size='20' value=""   >
					<input class="campos" type="hidden" name="decrip" maxlength='128' size='20' value="EXAMENES DE LABORATORIO"> </td>
    </tr>
		<tr class="nomostrar">
			<td  class="tdtitulos">Comentario</td>
      <td colspan='3' class="tdcampos"><textarea name="comenope" cols='72' rows='2' class="campos"></textarea></td>
		</tr>
		<tr class="nomostrar">
			<td colspan="4"><hr><br><br></td>
		</tr>

</table>
