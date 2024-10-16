<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-type: application/vnd.ms-excel');//poner cabezera de excel
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
header("Content-Disposition: attachment; filename=relacion_notacredito$numealeatorio.xls");//Esta ya es la hoja excel con el numero aleatorio.xls
header("Pragma: no-cache");//Para que no utili la cahce
header("Expires: 0");

$ente=$_REQUEST[ente];
$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
if ($forma_pago=='*'){
	$tipo_pago="and tbl_notacredito.edo_notacredito>=0";
	}
	else
	{
	$tipo_pago="and tbl_notacredito.edo_notacredito=$forma_pago";
	}
if ($ente==0)
{
	$elente="and titulares.id_ente>=$ente";
	}
	else
	{
		$elente="and titulares.id_ente=$ente";
		}
if ($forma_pago==1)
{
	$descripcion="Pagadas";
	}
	
if ($forma_pago==2)
{
	$descripcion="Por Cobrar";
	}
	
	
if ($forma_pago==3)
{
	$descripcion="Anuladas";
	}
	

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




		if ($sucursal==0)
{
	$id_serie="and tbl_facturas.id_serie>0";
	$serie="Todas Las Series";
	}
	else
	{
	$id_serie="and tbl_facturas.id_serie=$sucursal";
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	
		}
?>

<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
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
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>
<tr>
<td >
<br>
</br>
</td>
</tr>

    <tr>
   
      <?php 
$r_ente=pg_query("select  * from entes where id_ente=$ente");
($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))
?>
       
    </tr>
   
     
   <tr>
  <td height="21" colspan="12" class="titulo_seccion"><div align="center"><strong>Relacion de facturas de <?php echo $f_ente[nombre]?>, Atendidos por consultas, Laboratorios, Radiologia, Estudios especiales y Servicio de emergencias.</strong></div></td>
    </tr>
	<tr>
      <td height="21" colspan="12" class="Estilo3"><div align="right"><strong>Relacion de
                <?php  echo $fechaini ?>
        al <?php echo $fechafin ?></strong></div></td>
    </tr>
    <tr>
	<tr>
      <td height="21" colspan="12" class="Estilo3">Relacion de
                <?php  echo "Serie $serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] Condicion de Pago $descripcion" ?></td>
    </tr>
    <tr>
<td >
<br>
</br>
</td>
</tr>
   	 <tr>
    <td >&nbsp;</td>
    <td  class="titulo3" >Fecha</td>
	<td  class="titulo3" >NC</td>
	<td  class="titulo3" >Num Control</td>
    <td  class="titulo3" >Factura</td>
	<td   class="titulo3" >Num Control</td>
    <td class="titulo3" >Titular</td>
	 <td  class="titulo3" >Cedula</td>
    <td  class="titulo3" >Beneficiario</td>
	<td  class="titulo3" >Monto (Bs.S)</strong></td>
	</tr>

 <?php
 $r_factura=pg_query("select tbl_facturas.fecha_emision,tbl_facturas.numero_factura,tbl_facturas.id_factura,tbl_facturas.id_estado_factura,tbl_notacredito.num_notacredito,tbl_notacredito.montonc,tbl_notacredito.fecha_creado,tbl_notacredito.numcontrolnc,tbl_notacredito.edo_notacredito,count(tbl_procesos_claves.id_factura) from tbl_facturas,tbl_notacredito,tbl_procesos_claves where tbl_facturas.id_factura=tbl_notacredito.id_factura and tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and tbl_procesos_claves.id_proceso=procesos.id_proceso and procesos.id_titular=titulares.id_titular $elente and tbl_notacredito.fecha_creado>='$fechaini' and tbl_notacredito.fecha_creado<='$fechafin'  $id_serie $tipo_pago group by tbl_facturas.fecha_emision,tbl_facturas.numero_factura,tbl_facturas.id_factura,tbl_facturas.id_estado_factura,tbl_notacredito.num_notacredito,tbl_notacredito.montonc,tbl_notacredito.fecha_creado,tbl_notacredito.numcontrolnc,tbl_notacredito.edo_notacredito order by tbl_notacredito.num_notacredito")
?>



   
    <?php    
$contador=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	
	$contador++;
?>
   
     
<?php 

$r_titulares=pg_query(" select titulares.id_titular,clientes.* from clientes where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=procesos.id_titular and procesos.id_proceso=tbl_procesos_claves.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura]");
($f_titulares=pg_fetch_array($r_titulares, NULL, PGSQL_ASSOC));

$r_subdivision=pg_query(" select * from subdivisiones where subdivisiones.id_subdivision=titulares_subdivisiones.id_subdivision and titulares_subdivisiones.id_titular=$f_titulares[id_titular]");
($f_subdivision=pg_fetch_array($r_subdivision, NULL, PGSQL_ASSOC));

$titular= $f_titulares[apellidos];
$r_beneficiarios=pg_query(" select * from clientes where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=procesos.id_beneficiario and procesos.id_proceso=tbl_procesos_claves.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura]");
($f_beneficiarios=pg_fetch_array($r_beneficiarios, NULL, PGSQL_ASSOC));
$beneficiario= $f_beneficiarios[apellidos];


		$r_gastos=pg_query("select tbl_procesos_claves.no_clave,gastos_t_b.nombre,gastos_t_b.enfermedad,gastos_t_b.monto_reserva,gastos_t_b.monto_aceptado,gastos_t_b.descripcion from gastos_t_b,tbl_procesos_claves where gastos_t_b.id_proceso=tbl_procesos_claves.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura]")
	  	?>
    <?php
		$totalmontres=0;
		$totalmontpag=0;
		while($f_gastos=pg_fetch_array($r_gastos, NULL, PGSQL_ASSOC)){
		
		?>
    <?php
		
	//Monto reserva
		if ($f_factura[edo_notacredito]==1){
			$totalmontres=0;
			}
			else{
		$totalmontres = $totalmontres + ($f_gastos['monto_reserva']);
		
		$totalmontres1 = $totalmontres1 + ($f_gastos['monto_reserva']);
		}

		//Monto Aceptado
		if ($f_factura[edo_notacredito]==1){
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
        <td   class="datos_cliente" ><?php echo $f_factura[fecha_emision]; ?></td>
        <td   class="datos_cliente" ><?php echo "*00$f_factura[num_notacredito]"?></td>
		<td   class="datos_cliente" ><?php  echo "00-$f_factura[numcontrolnc]" 
		?></td>
       <td   class="datos_cliente" ><?php echo "*00$f_factura[numero_factura]"?></td>
		<td   class="datos_cliente" ><?php  echo "00-$f_factura[numcontrol]" 
		?></td>
	<td   class="datos_cliente"> <?php echo "$f_titulares[apellidos]  $f_titulares[nombres]"?> </td>
	<td   class="datos_cliente"> <?php echo $f_titulares[cedula]?> </td>
	<td     class="datos_cliente"> <?php echo $f_beneficiarios[apellidos]?> <?php echo $f_beneficiarios[nombres]?> </td>
 	<td    class="datos_cliente"> <?php echo montos_print($totalmontpag)?></td>
      </tr>
   
   
   
    <?php 

}
pg_free_result($r_factura);
if($contador==1){
	$contador="(Una Factura)";
}else{
	$contador="($contador Facturas)";
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

