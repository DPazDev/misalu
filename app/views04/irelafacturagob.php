<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 

$no_factura=$_REQUEST['factura'];

$servicios=$_REQUEST['servicios'];
$serie=$_REQUEST['serie'];
$id_admin= $_SESSION['id_usuario_'.empresa];
//buscamos los datos de la factura
$q_factura=("select * 
		from tbl_facturas,tbl_series
		where tbl_facturas.numero_factura='$no_factura' and 
		      tbl_facturas.id_serie=tbl_series.id_serie and tbl_series.id_serie='$serie'
		      ;");
$r_factura=ejecutar($q_factura);
//if(num_filas($r_factura)==0)	mensaje("No existe una factura con esos parámetros.");
$f_factura=asignar_a($r_factura);
$servicios=$f_factura[servicio];

if($f_factura['condicion_pago']==2 && !empty($f_factura['fecha_credito'])){
	list($ano_c,$mes_c,$dia_c)=explode("-",$f_factura['fecha_credito']);
	$fecha_credito="<td align=\"right\" width=\"150\">A $dia_c DIAS</td>";
}

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

		      

?>
<html>
<head>
<title></title>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
</head>
<body>
<script language="JavaScript">
<!--
function enviar(){
	if(document.jforma.factura.value.length == 0){
		alert("El número de la factura es obligatorio.");
	}else{
		document.jforma.submit();
	}
}

-->
</script>


<div align="center">
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=8 class="titulo">

</td>
</tr>
<tr>
<td colspan=2 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=6 class="titulo">

</td>
<td colspan=4 class="titulo1">
<?php echo "$f_admin[sucursal] "?>
</td>
</tr>

</table>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="70%"></td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right;" valign="top"><b>Relacion de Factura Serie <?php echo $f_factura['nomenclatura']; ?></b> <b>No. de factura 00<?php echo $no_factura; ?></b></td>
	</tr>
</table>
<br><br>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="right">Fecha de Emision:<?php  
		                        list($ano_e,$mes_e,$dia_e)=explode("-",$f_factura['fecha_emision']);
					                        echo "$dia_e/$mes_e/$ano_e";
								
								                ?></td>
	</tr>
</table>

	
	<table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="80%" align="left">Nombres o razon social: GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD
			</tr>
<?php if ($f_cliente[nombre]=="PARTICULAR") 
{
	}
	else
	{
?>
<tr>
		 <td align="left">RIF: G-20000156-9</td>
	 </tr>

   	<tr>
   		<td width="80%" align="left">Direccion: PLAZA BOLIVAR PALACIO DE GOBIERNO ENTRE AV. 2 Y 3 MERIDA EDO MERIDA</td>
                 
	 </tr>
<tr>
   		<td width="80%" align="left">Telefono: 02742525072
                 
	 </tr>
	<?php 
	}
	?> 
     	<tr>
                 <td  colspan=3 align="center"><hr> </hr></td>
	 </tr>
     	<tr>
                 <td  colspan=3 align="left">Concepto Factura:<?php echo $f_factura[concepto]?></td>
	 </tr>
 	<tr>
                 <td  colspan=3 align="center"><hr> </hr></td>
	 </tr>
	</table> 
	
		

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
			<td  align="right">Condicion Pago:<?php 
				if($f_factura['condicion_pago']==1)
					echo "Contado";
				else if($f_factura['condicion_pago']==2)
					echo "Cr&eacute;dito $fecha_credito";	
				else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 || $f_factura['condicion_pago']==6 || $f_factura['condicion_pago']==7 )
					echo "Contado";
		?>
		</td>
		</tr>
</table>



<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>

	
   
   	 <tr>
  
    <td  class="titulo3" ></td>
	<td  class="titulo3" >Titular</td>
        <td  class="titulo3" >Cedula titular</td>
    <td  class="titulo3" >Beneficiario</td>
        <td  class="titulo3" >Cedula Beneficiario</td>
        <td  class="titulo3" >Fecha Cita</td>
	<td   class="titulo3" >Prcoeso o Planilla</td>
    <td class="titulo3" >Ente</td>
	 <td  class="titulo3" >Monto</td>
	<td  class="titulo3" ></strong></td>
	</tr>

 <?php 
 if ($servicios==4){
 $r_factura=pg_query("select 
                                            procesos.id_proceso,
                                            procesos.id_titular,
                                            procesos.id_beneficiario,
                                            clientes.nombres,
                                            clientes.apellidos,
                                            clientes.cedula,
                                            entes.nombre,
                                            tbl_facturas.numero_factura 
                                    from 
                                            procesos,
                                            titulares,
                                            clientes,
                                            entes,
                                            tbl_facturas,
                                            tbl_procesos_claves 
                                    where 
                                            tbl_facturas.id_factura=$f_factura[id_factura] and 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and 
                                            titulares.id_cliente=clientes.id_cliente order by tbl_facturas.numero_factura");
                                           echo "";
}

/* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==6 || $servicios==9) {
    
$r_factura= pg_query("select  
                                            procesos.id_titular,
                                            procesos.id_beneficiario,
                                            clientes.nombres,
                                            clientes.apellidos,
                                            clientes.cedula,
                                            entes.nombre,
                                            procesos.nu_planilla,
                                            count(procesos.nu_planilla) 
                            from 
                                            procesos,
                                            titulares,
                                            clientes,
                                            entes,
                                            tbl_facturas,
                                            tbl_procesos_claves 
                                    
                            where 
                                            tbl_facturas.id_factura=$f_factura[id_factura] and 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and 
                                            titulares.id_cliente=clientes.id_cliente 
                            group by 
                                            procesos.id_titular,
                                            procesos.id_beneficiario,
                                            clientes.nombres,
                                            clientes.apellidos,
                                            clientes.cedula,
                                            entes.nombre,
                                            procesos.nu_planilla

                            order by procesos.nu_planilla");
}

$contador=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	
	$contador++;
?>
   
     
<?php 

$q_beneficiarios=(" select 
                                                    * 
                                            from 
                                                    clientes,
                                                    beneficiarios
                                            where 
                                                    clientes.id_cliente=beneficiarios.id_cliente and
                                                    beneficiarios.id_beneficiario=$f_factura[id_beneficiario]");
$r_beneficiarios=ejecutar($q_beneficiarios);
 
 $f_beneficiarios=asignar_a($r_beneficiarios);
 if ($servicios==4) {
		$r_gastos=pg_query("select 
                                                    gastos_t_b.nombre,
                                                    gastos_t_b.enfermedad,
                                                    gastos_t_b.monto_reserva,
                                                    gastos_t_b.monto_aceptado,
                                                    gastos_t_b.descripcion,
                                                    gastos_t_b.fecha_cita 
                                            from 
                                                    gastos_t_b,
                                                    procesos
                                            where  
                                                    gastos_t_b.id_proceso=procesos.id_proceso and
                                                    procesos.id_proceso='$f_factura[id_proceso]'");
	  	}
 
if ($servicios==6 || $servicios==9) {
		$r_gastos=pg_query("select 
                                                    gastos_t_b.nombre,
                                                    gastos_t_b.enfermedad,
                                                    gastos_t_b.monto_reserva,
                                                    gastos_t_b.monto_aceptado,
                                                    gastos_t_b.descripcion,
                                                    gastos_t_b.fecha_cita 
                                            from 
                                                    gastos_t_b,
                                                    procesos
                                            where  
                                                    gastos_t_b.id_proceso=procesos.id_proceso and
                                                    procesos.nu_planilla='$f_factura[nu_planilla]'");
	  	}
        ?>
    <?php
		$totalmontres=0;
		$totalmontpag=0;
		while($f_gastos=pg_fetch_array($r_gastos, NULL, PGSQL_ASSOC)){
		$fecha_cita=$f_gastos[fecha_cita];
		?>
    <?php
		
		//Monto reserva
		if ($f_factura[id_estado_factura]==3){
			$totalmontres=0;
			}
			else{
		$totalmontres = $totalmontres + ($f_gastos['monto_reserva']);
		
		$totalmontres1 = $totalmontres1 + ($f_gastos['monto_reserva']);
		}

		//Monto Aceptado
		if ($f_factura[id_estado_factura]==3){
			$totalmontpag=0;
			}
			else
			{
		$totalmontpag =	$totalmontpag + ($f_gastos['monto_aceptado']);
		$totalmontpag1 =	$totalmontpag1 + ($f_gastos['monto_aceptado']);
		}
		$no_clave= $f_gastos['no_clave'];
		?>
    
      <?php 
		}
		pg_free_result($r_gastos);
   		?>
      <tr>
	<td  class="datos_cliente" ><?php echo $contador ?></td>  
        <td   class="datos_cliente" ><?php echo "$f_factura[nombres] $f_factura[apellidos]" ?></td>
        <td   class="datos_cliente" ><?php echo "$f_factura[cedula]"?></td>
        <td   class="datos_cliente"> <?php echo "$f_beneficiarios[nombres] $f_beneficiarios[apellidos]";?></td>
        <td  class="datos_cliente"> <?php echo "$f_beneficiarios[cedula]";?></td>
		<td  class="datos_cliente"> <?php echo $fecha_cita ?></td>
	<td   class="datos_cliente"> <?php echo "$f_factura[nu_planilla] $f_factura[id_proceso]"?> </td>
	<td   class="datos_cliente"> <?php echo $f_factura[nombre]?> </td>
	<td    class="datos_cliente"> <?php echo montos_print($totalmontpag)?></td>
      </tr>
   
   
   
    <?php 

}
pg_free_result($r_factura);
if($contador==1){
	$contador="(Un)";
}else{
	$contador="($contador )";
}
?>
<tr>
<td  class="titulo3"></td>
<td   class="titulo3"></td>
<td   class="titulo3"></td>
<td   class="titulo3"></td>
<td  class="titulo3"></td>
<td   class="titulo3"></td>
<td  class="titulo3"></td>
<td  class="titulo3"></td>
<td   class="titulo3">Total</td>
<td class="titulo3" ><?php echo  montos_print($totalmontpag1)?></td>
</tr>
    
	 <tr>
 
      <td height="21" colspan="12" valign="top"><div align="left" class="Estilo3"<strong>&nbsp;</strong><?php echo $contador; ?></strong></td>
    </tr>

 <tr>
      <td height="21" colspan="12" valign="top"><div align="right" class="Estilo3">Firma de <?php echo $f_ente[nombre]?>:_________________</div></td>
     
    
      </tr>
    <tr>
      <td height="21" colspan="3" valign="top"><div align="center" class="Estilo3"></div></td>
      <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3"></div></td>
       <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3"></div></td>
    
      </tr>
   <tr>
      <td height="21" colspan="3" valign="top"><div align="center" class="Estilo5">Elaborado Por:__________ </div></td>
      <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3">Aprobado Por:__________ </div></td>
       <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3">Recibido Por:__________ </div></td>
    </tr>
</table>





<br><br>

			
		</table>		
		<table width="100%" cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td rowspan=2 valign="top" align="left">Son: <?php 
    				
				$cantidad=explode(".",$totalmontpag1);
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




				if($cadenas==2){
				        echo ucwords(numtolet($cantidad[0],"os"))." con ".$cantidad[1]."$cero/100 Bolivares.";
			    	}else{
					echo ucwords(numtolet($cantidad[0],"os"))." Bolivares. ";
				$cero="";
			    	} 
	
				//echo numeros_a_letras($total);
	/* **** Se registra lo que hizo el usuario**** */

$log="Imprimio la Factura numero $no_factura";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
				?></td>
			</tr>
			
			
		</table>		


</div>
</body>
</html>
