<?php
  include ("../../lib/jfunciones.php");
   sesion();
   $elid=$_SESSION['id_usuario_'.empresa];
   $elus=$_SESSION['nombre_usuario_'.empresa];
   $meses = array('enero','febrero','marzo','abril','mayo','junio','julio',
               'agosto','septiembre','octubre','noviembre','diciembre');
   $anos = array('2014','2015','2016','2017','2018','2019','2020','2021','2022','2012','2023','2024','2025','2026','2027','2028','2029','2030');            
   $r_serie=pg_query("select sucursales.sucursal,tbl_series.* from sucursales,tbl_series where tbl_series.id_sucursal=sucursales.id_sucursal order by tbl_series.nomenclatura");
?>
     <form method="get" onsubmit="return false;" name="procli" id="procli">
        <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	     <tr>
	         <td colspan=4 class="titulo_seccion">Generar Libro de Venta</td>
	     </tr>
	     <tr>
	       <td class="tdtitulos">Seleccione Mes: </td>
	       <td>
	        <select class="campos"  style="width: 200px;"  id="elmes" name="elmes" >
		        <option value="">--Mes--</option>
	       
	            <?php 
	                  $j=1;
	                  for($i=0;$i<=count($meses);$i++){ ?>
						 <option value="<?php echo $j?>"> <?php echo " $meses[$i] " ?> 
					<?php 
					   $j++;
					   } ?>
	       </td>
	       <td class="tdtitulos">Seleccione Año: </td>
	       <td>
	        <select class="campos"  style="width: 200px;"  id="elano" name="elano" >
		        <option value="">--Año--</option>
	       
	            <?php 
	                  
	                  for($z=0;$z<=count($anos);$z++){ ?>
						 <option value="<?php echo "$anos[$z]"?>"> <?php echo " $anos[$z] " ?> 
					<?php 
					   
					   } ?>
	       </td>
	    </tr> 
	    <tr>
	     <td class="tdtitulos">Seleccione la Sucursal: </td>
	  	 <td>
	      <select id="sucursal" style="width: 200px;"  name="sucursal" class="campos">
	      <option value="" >---</option>
	     	<option value="0" >Todas las Sucursales CliniSalud</option>  
            <?php
	            while($f_serie=pg_fetch_array($r_serie, NULL, PGSQL_ASSOC))
		         echo "<option value=\"$f_serie[id_serie]\">Serie $f_serie[nomenclatura]  Sucursal $f_serie[sucursal] </option>";
	            ?>
          </select>
        </td>
        <td class="tdtitulos">Estado de la Factura</td>
		<td class="tdcampos">
	<select id="forma_pago" name="forma_pago" class="campos" style="width: 200px;"  >
	    <option value="" >---</option>
		<option value="0" >Todas</option>
		<option value="*" >Por Cobrar y Pagadas</option>
		<option value="1" >Pagadas</option>
		<option value="2" >Por Cobrar</option>
		<option value="3" >Anuladas</option>
        <option value="4" >Por Facturar</option>
	</select>
	</td>
      </tr>
    </table>  
      <br>
	       <label title="Generar Libro de Venta" class="button tdcampos" style="cursor:pointer" onclick="GLibroVenta()" >Generar Libro</label>
	       <label title="Libro de venta detallado" class="button" style="cursor:pointer" onclick="LibVentaD()" >Libro Detallado</label>
	       <label title="Salir del Proceso" class="button" style="cursor:pointer" onclick="ira()" >Salir</label>
	   

	   <?php
          
          $mesf=$_REQUEST['formes'];
$anof=$_REQUEST['forano'];
$seri=$_REQUEST['forsucursal'];
$estafact=$_REQUEST['forestfact'];
$primerdia = "01";
$ultdia = date("d",(mktime(0,0,0,$mesf+1,1,$anof)-1));

$fechainicio = "$anof-$mesf-$primerdia";
$fechafinal =  "$anof-$mesf-$ultdia";

                 if ($seri==0)
   {
    $id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
    $serie="TODAS LAS SERIES";
    }
    else
    {
    $id_serie="and tbl_series.id_serie=$seri";
    $id_seriep="and ts.id_serie=$seri";
    $q_serie=("select  * from tbl_series where id_serie=$seri");
    $r_serie=ejecutar($q_serie);
    $f_serie=asignar_a($r_serie);
    $serie="SERIE ".$f_serie[nomenclatura];
        }
        
    if($estafact == 0){
     $elestafc ="";
    }else{
      $elestafc ="and id_estado_factura=$estafact"; 
     }



//CONSULTA
$buscfactu = ("select tbl_facturas.id_factura,tbl_facturas.fecha_emision,tbl_facturas.numero_factura,
                                   tbl_facturas.numcontrol, tbl_facturas.id_serie, 
                                   $id_seriep date_part('day',tbl_facturas.fecha_emision) as dia
                                   from tbl_facturas where $id_serie fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal'
                                   $elestafc 
                                   order by fecha_emision,$id_seriep id_factura;");
                                  

$r_dfacturas=ejecutar($buscfactu);

while($lasfacturas=asignar_a($r_dfacturas,NULL,PGSQL_ASSOC)){
   
    $r++;
             echo $lasfacturas[fecha_emision];

}

	   ?>
	   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
       <div id="libventa"></div>
