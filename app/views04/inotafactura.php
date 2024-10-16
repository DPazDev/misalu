<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' );
$no_factura=$_REQUEST['id_factura'];
$NumNota=$_REQUEST['num_nota'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_factura=("select * from
                        tbl_facturas,
                        tbl_nota_factura,
                        tbl_series
                      where
                        tbl_facturas.id_factura='$no_factura' and
                        tbl_facturas.id_factura=tbl_nota_factura.id_factura and
                        tbl_nota_factura.num_nota='$NumNota' and
                        tbl_facturas.id_serie=tbl_series.id_serie;");

$r_factura=ejecutar($q_factura);
$f_factura=asignar_a($r_factura);
$numNota=$f_factura['num_nota'];
$serie=$f_factura['id_serie'];
$id_factura=$f_factura['id_factura'];
$NumFactura=$f_factura['numero_factura'];
$TipoNota=$f_factura['tipo_nota'];//1 CREDITO 2) DEBITO
$ConceptoNota=$f_factura['concepto'];
$FechaEmisionNota=$f_factura['fecha_emision'];
$montoNota=$f_factura['monto_nota'];
////insertar 00 en la facture segun sus digitos si lo requiere
////Quedarce con solo numero enteros
$numNotaFat=(int) $numNota;///Trasformar a entero
if($numNotaFat<10)
{$numNotaFat='000'.$numNotaFat;}
else if($numNotaFat>=10 && $numNotaFat<100)
{$numNotaFat='00'.$numNotaFat;}
else if($numNotaFat>=100 && $numNotaFat<1000)
{$numNotaFat='0'.$numNotaFat;}
else{$numNotaFat=$numNotaFat;}
$CondicionPago=$f_factura['condicion_pago'];
///Nota de Debito SERA Siempre a Credito 15 dias
if($TipoNota==2)
  {$CondicionPago=2;}
else
  {$CondicionPago=1;}
if($CondicionPago==2){
	 $fecha_credito="<td align=\"right\" width=\"150\">Cr&eacute;dito A 15 DIAS</td>";
}

//IVA variables
$b_iva= ("select * from variables_globales where  nombre_var='IVA' ");
$t_iva=ejecutar($b_iva);
$q_iva=asignar_a($t_iva);
$iva= $q_iva['cantidad'];
$PorIVA= $q_iva['iva'];
///IGTF VARIAVES
$b_IGTF= ("select * from variables_globales where  nombre_var='IGTF' ");
$t_IGTF=ejecutar($b_IGTF);
$q_IGTF=asignar_a($t_IGTF);
$IGTF= $q_IGTF['cantidad'];
$porcientoIGTF= $q_IGTF['comprasconfig'].' %';
//

$fechaFactura=$FechaEmisionNota;
$IdCambioMoneda=0;
///////////////////////////////////
//consulta de moneda
	$mostrarNotaCambio=0;
  ///BUSCAR SI LA NOTA TIENE UN ID
	$sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and id_moneda_cambio='$IdCambioMoneda' order by fecha_cambio DESC , hora_cambio DESC limit 1";
	$DataCambio=ejecutar($sqlCambio);
	$NumFilas=num_filas($DataCambio);
	if($NumFilas>0)
		{$mostrarNotaCambio=1;
		}else{///BUSCAR EN LA FECHA DE LA NOTA
			$sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and tbl_monedas_cambios.fecha_cambio='$FechaEmisionNota' order by fecha_cambio DESC , hora_cambio DESC limit 1";
			$DataCambio=ejecutar($sqlCambio);
			$NumFilas=num_filas($DataCambio);
			if($NumFilas>0){
				$mostrarNotaCambio=1;
			}else {//MONSTRAR EL PIMER CAMBIO QUE ENCUENTRE EN LAS FECHAS ANTERIORES
        $sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and tbl_monedas_cambios.fecha_cambio<'$FechaEmisionNota' order by fecha_cambio DESC , hora_cambio DESC limit 1";
				$DataCambio=ejecutar($sqlCambio);
				$NumFilas=num_filas($DataCambio);
				if($NumFilas>0){
					$mostrarNotaCambio=1;
				}

			}
		}

if($mostrarNotaCambio==1){
	$cambio   = assoc_a($DataCambio);
	$id_moneda=$cambio['id_moneda'];//ID ASOCIADO A LA MONEDA
	$moneda_valor=$cambio['valor'];//valor cambiario actaul
	$id_cambio=$cambio['id_moneda_cambio'];//id de cambio actual
}


/////////////////////////////DATOS DEL CLIENTE///////////////////////////////
$q_cliente=("select  clientes.id_ciudad as ciudadcliente,clientes.*,titulares.*,entes.*,tbl_procesos_claves.*,procesos.*
from clientes,titulares,entes,tbl_procesos_claves,procesos
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente and titulares.id_cliente=clientes.id_cliente;");
 $r_cliente=ejecutar($q_cliente);
 $f_cliente=asignar_a($r_cliente);

$q_ciudad=("select  ciudad.*,estados.* from ciudad,estados where ciudad.id_ciudad=$f_cliente[ciudadcliente]  and ciudad.id_estado=estados.id_estado;");
		      $r_ciudad=ejecutar($q_ciudad);
		      $f_ciudad=asignar_a($r_ciudad);
/////////////BENEFICIARIOS///////////
$q_bene=("select  * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_cliente[id_beneficiario];");
          $r_bene=ejecutar($q_bene);
		      $f_bene=asignar_a($r_bene);
$q_gasto=("select  tipos_servicios.tipo_servicio,gastos_t_b.id_tipo_servicio,count(gastos_t_b.id_tipo_servicio) from gastos_t_b,tbl_procesos_claves,procesos,tipos_servicios
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura]  and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and
gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>'0' group by  tipos_servicios.tipo_servicio,gastos_t_b.id_tipo_servicio order by tipos_servicios.tipo_servicio  desc;");
          $r_gasto=ejecutar($q_gasto);
		 $moneda=("select * from tbl_oper_multi where  tbl_oper_multi.id_factura=$f_factura[id_factura] ");
		 $tipo_monedas= ejecutar($moneda);
		 $IGTFactivo=0;
		 $montoigtf=0;
		 while($resul_tipo_monedas= asignar_a($tipo_monedas)){
					$id_moneda=$resul_tipo_monedas['id_moneda'];
					if($id_moneda!=1){
						$monto_o_m=$resul_tipo_monedas['monto']+$monto_o_m;
						$IGTFactivo=1;
										 }
				}
    $IGTFactivo=0;
		$montoigtf= $monto_o_m * $IGTF;

?>
<html>
<head>
<title></title>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
</head>
<body>
<?php

////FORMATO uNICO
//MEDIA CARTA VERTICAL
 if($serie <> '0'){
?>
<div id='margen' style="margin-left: 2em;" ><!-- MARGENES DE FACTURA--->
  <!-- TABLA ENCABEZADO--->
<table width="50%" border="0" cellspacing=0 cellpadding=0 >
    <tr>
        <td width="50%" style="padding-top:80px;height: 30px;text-align: right; font-size: 08pt;" valign="top">
              <?php if($TipoNota==1){ ?>
                     <b>NOTA DE CRÉDITO</b>
              <?php }else{?>
              <b>NOTA DE DÉBITO</b>
              <?php } ?>
        </td>
    </tr>

    <tr>
    		<td width="50%" style="padding-top: 01px;height: 30px;text-align: right; font-size: 08pt;" valign="top">
          <?php if($TipoNota==1){ ?><b>Nota de Cédito No.</b><?php }else{?><b>Nota de Débito No.</b><?php }?> <b><?php echo $numNotaFat; ?></b><br/>
          <b>Afecta No. de factura 00<?php echo $NumFactura; ?> <b>Serie <?php echo $f_factura['nomenclatura']; ?></b></b>
        </td>
    </tr>

  	<tr>
  		<td align="left" style="font-size: 08pt">
        Fecha de Emision:
        <?php
             $fh=explode("-",$fechaFactura);
             $fechaE=$fh[2].'-'.$fh[1].'-'.$fh[0];
      			echo "$fechaE";  ?>
      </td>
  	</tr>
</table>
<?php
  if ($f_factura[con_ente]>0 )
      {
      ?>
      <!--------MOSTRAR DATOS DEL ENTE ---->
    <table width="50%" cellspacing=0 cellpadding=0 border=0>
          <tr>
      	     <td width="60%" align="left" style="font-size: 08pt">Nombres o razon social: <?php echo $f_cliente[nombre];?>
      		</tr>
        <?php
        if ($f_cliente[nombre]=="PARTICULAR")
        {	}
      	   else
      	{
          ?>
          <tr>
      		    <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
      	  </tr>
         	<tr>
         		<td width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>
      	  </tr>
          <tr>
             <td width="60%" align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>
          </tr>
      	<?php
      	}
      	?>
        <tr>           <!-------------------LINEA DE SEPARACION----->
          <td   align="center"><hr width="95%"  size=1 color="#070707"></td>
        </tr>
      </table>
		<?php
    }else{
  		?>
      <!-- SI HAY NUMERO DE CLAVE---->
      <?php
        if ($f_cliente[no_clave]>'0'){
        ?>
        <table width="50%" cellspacing=0 cellpadding=0 border=0>
              <tr>
        	         <td width="60%"  colspan=2 align="left" style="font-size: 08pt">Nombre o razon social:<?php echo $f_cliente[nombre];?>
        			</tr>
        		 <tr>
        		     <td align="left" colspan=2 style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
        	   </tr>
           	 <tr>
           		   <td width="60%" colspan=2 align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>
        	   </tr>
             <tr>
           		   <td width="60%" colspan=2 align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>
        	   </tr>
             <tr>           <!-------------------LINEA DE SEPARACION----->
                <td  colspan=2 align="center"><hr width="95%"  size=1 color="#070707"></td>
           	 </tr>
             <!-------------------DATOS DE CLAVE----->
             <tr>
               	  <td width="60%" colspan=2 align="left" style="font-size: 08pt">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
             </tr>
        	   <tr>
              		<td width="60%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]   $f_cliente[apellidos]";?></td>
              		<td align="right">Cédula <?php echo $f_cliente[cedula] ?></td>
             </tr>
                  <?php
            if ($f_cliente[id_beneficiario]>'0') {
                    ?>
             <tr>
                   <td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres]  $f_bene[apellidos]";?></td>
          		     <td align="right">Cedula <?php echo $f_bene[cedula] ?></td>
          	 </tr>
          <?php
            }
            else
            {
              }
        ?>
        </table>
        <?php
    }
   else
   {
   ?>
   <!-- NO HAY NUMERO DE CLAVE---->
  <table width="50%" cellspacing=0 cellpadding=0 border=0>
        <tr>
  	         <td width="80%" align="left" style="font-size: 08pt">Cliente: <?php echo $f_cliente[nombre];?>
  			</tr>
  <?php if ($f_cliente[nombre]=="PARTICULAR")
  				{
  					}
  					else
  					{	?>
  						 	<tr>
  						 		<td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
  					 		</tr>
  				   		<tr>
  				   			<td width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>
  							</tr>

  					<?php
  					}
  					?>
           	<tr>           <!-------------------LINEA DE SEPARACION----->
               <td  align="center"><hr width="95%"  size=1 color="#070707"></td>
          	 </tr>
          </table>
          <!-----------DATOS TITULAR------------>
          <table width="50%" cellspacing=0 cellpadding=0 border=0>
              	<tr>
              		<td width="60%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]"?></td>
              		<td align="right" style="font-size: 08pt">Cedula <?php echo $f_cliente[cedula]; ?></td>
              	</tr>

          </table>
          <?php
          if ($f_cliente[id_beneficiario]>'0') {  ?>
            <!-----------DATOS BENEFICIARIO------------>
                <table width="50%" cellspacing=0 cellpadding=0 border=0>
                  	<tr>
                       <td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>
                  		 <td align="right" style="font-size: 08pt">Cedula <?php echo $f_bene[cedula] ?></td>
                  	</tr>
                </table>
                <?php
            }
                ?>


    <?php
        }
    }
?>
<!-----------CODICION DE PAGO ------------>

<table width="50%" cellspacing=0 cellpadding=0 border=0>
  <!-----------para Nota debito siempre seran a credito y para las notas de credito Contado ------------>
	<tr>
			<td  align="right">Condicion Pago:</td><?php

				if($CondicionPago==1)
					{echo "<td>Contado</td>";}
				else if($CondicionPago==2)
					{echo "$fecha_credito";}
				else if($CondicionPago==3 || $CondicionPago==4 || $CondicionPago==5 || $CondicionPago==6 || $CondicionPago==7 )
					{echo "<td>Contado</td>";}
		?>

		</tr>
</table>

<br>
<!--- ###### /////////////////// DESGLOSE D E GASTOS///////////////////######------>
<table width="50%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
  	<td colspan=2 width="50%" align="left" style="font-size: 08pt">Concepto o Descripcion</td>
  	<td width="10%" align="right" style="font-size: 08pt">Total Bs.S.</td>
	</tr>
	<?php

//////////////////////////BUSCAR CUENTAS A TERCEROS MONTOS RECERVAS///////
/*$q_reserva=("select SUM(CAST(gastos_t_b.monto_reserva AS DOUBLE PRECISION)) as reserva from gastos_t_b,tbl_procesos_claves,procesos where tbl_procesos_claves.id_proceso=procesos.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura] and gastos_t_b.id_proceso=procesos.id_proceso
;");
$r_reserva=ejecutar($q_reserva);
$montoReserva=asignar_a($r_reserva);
$montoReserva=$montoReserva['reserva'];*/
$montoReserva=0;
	 $total=0;

		if  ($f_factura[concepto]<>"") {
        	$total=$f_factura[monto_nota];
		?>
			<tr>
		      <td width="50%" align="left" style="font-size: 07pt"><?php echo $f_factura[concepto]; ?></td>
			    <td width="5%" align="left"></td>
			<td width="10%" align="right" style="font-size: 07pt"><?php echo  montos_print($total)?></td>
		</tr>
		<?php
		}


    ?>
<tr>
		<td colspan=3>
		<br>

		</td>
	</tr>

</table>
		<table width="50%" cellspacing=0 cellpadding=0 border=0>
<?php

$cantidad=explode(".",$total);
$cadenas=count($cantidad);
if ($cantidad[1]<=9) {
$cero=0;}
else
{$cero="";}
$cantidad[1]=substr($cantidad[1],0,2);
$cantida[1]=substr($cantidad[1],0,1);
if ($cantida[1]==0){
	$cero="";
}
//calulo de iva
$totalIva=$total*$iva;
if($montoReserva>0)
{$subtotal=$total-$montoReserva;}////TOTAL menos RESERVA
else{$subtotal=$total;}
?>

			<tr>
				<td  valign="top" width="70%" align="right" style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO Bs.S.</td>
				<td width="180" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($subtotal); echo  $cero;?></td>
			</tr>
			<tr>
				<td  valign="top" width="70%" align="right" style="font-size: 07pt">MONTO TOTAL CUETAS POR TERCEROS.</td>
				<td width="180" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($montoReserva); ?></td>
			</tr>
			<tr>
				<td  valign="top" align="right" style="font-size: 06pt">Base imponible segun alicuota_% Bs.S.
    		</td>
				<td width="180" valign="top" align="right"> </td>
			</tr>
			</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 06pt">Monto Total del impuesto segun alicuota_% Bs.S.</td>
				<td width="180" valign="top" align="right"> </td>
			</tr>

      <?php
				      //calculo de IGTF
					 if($IGTFactivo==1){ ?>
						 <tr>
	 							<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA Bs.S.	</td>
	 							<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($total));  echo  $cero;?></td>
	 					</tr>
						<tr>
							<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido</td>
							<td width="200" valign="top" align="right" style= "font-size: 07pt"><?php echo montos_print(abs($montoigtf));  ?> </td>
						</tr>
						<?php
				 		//fin del calculo IGTF
          } $montoigtf=0; ?>
					<tr>
								<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR Bs.S.</td>
								<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($total + $montoigtf));  echo  $cero;?></td>
					</tr>
				</table>

            <?php
          if($mostrarNotaCambio==1){

						$tazaCambio=$moneda_valor;
            $totalUSD=$total/$tazaCambio;
            $totalUSD=round($totalUSD, 2);
           ?>
					 <br>
           <table width="50%" cellspacing=0 cellpadding=0 border=0 >
           <tr ><td colspan="2" style="font-size: 07pt"><q >A los efectos de lo previsto en el Art. 25 de la ley del Impuesto al Valor Agregado, se expresa los montos de la factura en tasa de cambio establecida por BCV. 1/USD por BS. </q>
      				<br> Tasa de cambio BCV: <?php echo $tazaCambio; ?> BS.  </td></tr>
           <tr><td style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO: USD <?php echo $totalUSD; ?></td></tr>
           <tr><td style="font-size: 07pt">MONTO TOTAL DE LA VENTA USD <?php echo $totalUSD; ?> </td></tr>
					 </table>
<?php
          }
        /* **** Se registra lo que hizo el usuario**** */
 ?>
</div>
<?php $log="Imprimio la nota $numNota de Factura numero $no_factura";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
}
        ?>

</body>
</html>
