<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $lossubdiv=("select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivision");
  $ressubdiv=ejecutar($lossubdiv);
  $losestatus=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estado_cliente;");
  $resestatus=ejecutar($losestatus);
  $ciudclien=("select ciudad.ciudad,ciudad.id_estado,ciudad.id_ciudad from ciudad order by ciudad.ciudad");
  $repciudad=ejecutar($ciudclien);
    list($elano,$sucursadmin,$policli,$elid,$numcuncoti)=explode('-',$_REQUEST[lacotiza]);
    if((empty($sucursadmin)) || (empty($policli))){
        $buscaclien=("select tbl_cliente_cotizacion.nombres,tbl_cliente_cotizacion.apellidos,
                tbl_cliente_cotizacion.rif_cedula,tbl_cliente_cotizacion.email,
                tbl_cliente_cotizacion.celular,tbl_cliente_cotizacion.edad,tbl_cliente_cotizacion.no_cotizacion,
                tbl_cliente_cotizacion.genero,tbl_cliente_cotizacion.tipocliente 
               from tbl_cliente_cotizacion where tbl_cliente_cotizacion.rif_cedula='$elano';");
         $repbusclien=ejecutar($buscaclien);      
       }else{
         $buscaclien=("select tbl_cliente_cotizacion.nombres,tbl_cliente_cotizacion.apellidos,
                tbl_cliente_cotizacion.rif_cedula,tbl_cliente_cotizacion.email,
                tbl_cliente_cotizacion.celular,tbl_cliente_cotizacion.edad,tbl_cliente_cotizacion.no_cotizacion,
               tbl_cliente_cotizacion.genero,tbl_cliente_cotizacion.tipocliente 
               from tbl_cliente_cotizacion where tbl_cliente_cotizacion.no_cotizacion='$_REQUEST[lacotiza]';");            
         $repbusclien=ejecutar($buscaclien);      
        }
        $ladatclien=assoc_a($repbusclien);
        $cuanclien=num_filas($repbusclien);
        $nomclien="$ladatclien[nombres] $ladatclien[apellidos]";
        $ceduclien=$ladatclien[rif_cedula];
        $numcotiza=$ladatclien[no_cotizacion];
        $emaclien=$ladatclien[email];
        $celuclien=$ladatclien[celular];
        $edadclien=$ladatclien[edad];
        $generclien=$ladatclien[genero];
        $eltipocliente=$ladatclien[tipocliente];
        if($generclien==0){
            $tipogene=17;
        }else{
             $tipogene=18;
            }
        //buscamos para ver si la persona ha tenido varias cotizaciones
        $cuantacotizacion=("select tbl_cliente_cotizacion.no_cotizacion,tbl_cliente_cotizacion.id_cliente_cotizacion,
polizas.nombre_poliza,tbl_caract_cotizacion.id_poliza from 
   tbl_cliente_cotizacion,polizas,tbl_caract_cotizacion,primas
 where
       tbl_cliente_cotizacion.id_cliente_cotizacion=tbl_caract_cotizacion.id_cliente_cotizacion and
       tbl_cliente_cotizacion.rif_cedula='$ceduclien' and
       tbl_caract_cotizacion.id_poliza=polizas.id_poliza and
       tbl_caract_cotizacion.id_prima=primas.id_prima
       group by 
       tbl_cliente_cotizacion.no_cotizacion,tbl_cliente_cotizacion.id_cliente_cotizacion,
       polizas.nombre_poliza,tbl_caract_cotizacion.id_poliza");
       $repcuancotiza=ejecutar($cuantacotizacion);
        if($cuanclien==0){?>
           <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
         <tr> 
            <td colspan=4 class="titulo_seccion">No existe ninguna informaci&oacute;n!!!!</td>  
        </tr>
         </table>        
     <?}else{?>
<input type="hidden" id="lacedclien"   value="<?echo "$ceduclien"?>">     
<?
$busexiclien=("select clientes.id_cliente,clientes.fecha_nacimiento from clientes where clientes.cedula='$ceduclien';");
$repsiexiclien=ejecutar($busexiclien);
$siexiclien=num_filas($repsiexiclien);
if($siexiclien>=1){
	$dataexclien=assoc_a($repsiexiclien);
	$fechanan=$dataexclien['fecha_nacimiento'];
	$cualclien=$dataexclien['id_cliente'];
	$edadcliente=calcular_edad($fechanan);
	$edadactuali=("update clientes set edad='$edadcliente' where id_cliente=$cualclien;");
	$repedactual=ejecutar($edadactuali);
}?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Datos del Cliente</td>  
     </tr>
 </table>
    <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">C&eacute;dula:</td>
      <td class="tdcampos"><? echo "$ceduclien"?></td>
	 </tr>
     <tr>
	   <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="cliennombre" class="campos" size="30" value="<?echo $ladatclien[nombres]?>"></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clienapellido"  class="campos" size="30" value="<? echo $ladatclien[apellidos]?>"></td>
	 </tr>
     <tr>
         <td class="tdtitulos" colspan="1">Genero:</td>
         <td class="tdcampos"  colspan="1"><select id="cliengenero" class="campos" style="width: 160px;">
           <?if($generclien==0){?>
                <option value="0">Femenino</option>
            <?}else{?>    
                 <option value="1">Masculino</option>     
            <?}?>    
			      <option value="0">Femenino</option>
                  <option value="1">Masculino</option>
			  </select>  </td> 
	    <td class="tdtitulos" colspan="1">Fecha de nacimiento:</td>
        <td class="tdcampos" colspan="1"><input  type="text" size="10" id="fechanaci" class="campos" maxlength="10" value="<? echo "$fechanan" ?>" >
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechanaci', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
      </tr>  
      <tr>
	   <td class="tdtitulos" colspan="1">Tel&eacute;fono:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="clientelefo" class="campos" size="30" value="<?echo $celuclien?>"></td>
       <td class="tdtitulos" colspan="1">Correo:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="cliencorreo"  class="campos" size="30" value="<? echo $emaclien?>"></td>
	 </tr>
      <tr>
	   <td class="tdtitulos" colspan="1">Fecha de inclusi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="feinc" class="campos" maxlength="10" value="<? echo date("Y-m-d") ?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feinc', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
	  <td class="tdtitulos" colspan="1">Estado del Cliente:</td>
       <td class="tdcampos"  colspan="1"><select id="clienestatu" class="campos" style="width: 160px;">
                              <option value="4">ACTIVO</option>
			   <?php  while($verestatu=asignar_a($resestatus,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verestatu[id_estado_cliente]?>"> <?php echo "$verestatu[estado_cliente]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td> 			 
	 </tr>
     <tr>
         <td class="tdtitulos" colspan="1">Ciudad de Origen:</td>
               <td class="tdcampos"  colspan="1"><select id="clienciudad" class="campos" style="width: 160px;">
                              <option value="1">MERIDA</option>
			   <?php  while($verciudad=asignar_a($repciudad,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verciudad[id_ciudad]?>"> <?php echo "$verciudad[ciudad]"?></option>
			   <?php
			  }
			  ?>
			 </select>  
             </td> 
     </tr>
     <tr> 
	   <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliendirr" class="campos"></TEXTAREA></td>
	</tr> 
	<tr>
	   <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliencoment" class="campos"></TEXTAREA></td>
	</tr>
    <tr>
	   <td class="tdtitulos" colspan="1">Sub-divisi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><select id="cliensubd" class="campos" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($versubdi=asignar_a($ressubdiv,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $versubdi[id_subdivision]?>"> <?php echo "$versubdi[subdivision]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td>	 
	</tr> 
    <tr>
         <td class="tdtitulos" colspan="1">Cotización:</td>
               <td class="tdcampos"  colspan="1">
                <select id="cliencotizac" class="campos" style="width: 190px;" onchange="verplanes(this.value,'<?echo $generclien?>','<?echo $ceduclien?>');comisionados();maternidad('<?echo $generclien?>','<?echo $ceduclien?>')">
                              <option value=""></option>
			   <?php  while($vercotiza=asignar_a($repcuancotiza,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo "$vercotiza[id_cliente_cotizacion]-$vercotiza[id_poliza]"?>"> <?php echo "$vercotiza[nombre_poliza] -|- $vercotiza[no_cotizacion]"?></option>
			   <?php
			  }
			  ?>
			 </select>  
             </td> 
     </tr>
<tr>
	<td class="tdtitulos" colspan="1">Comisionado:</td>
  <td class="tdcampos" colspan="1">
    <div id="comisionados">
      <select disabled="disabled" class="campos" style="width: 130px;" >
        <option value="0">
      </select>
</tr>

<tr>
  <td class="tdtitulos" colspan="1">Dirección de Cobro</td>
  <td class="tdcampos" colspan="3">
    <textarea cols=60 rows=2 id="direccioncobro" class="campos"></textarea>
  </td>
</tr>
     
</table>
<div id='sihaymater'></div>
<div id='simater'></div>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='cargafamili'></div>
<?}?>



	  
