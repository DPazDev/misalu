<?php
include ("../../lib/jfunciones.php");
sesion();
$ceduclien=$_REQUEST['lacedula'];
$buscdatclien=("select clientes.cedula,clientes.nombres,clientes.apellidos,clientes.direccion_hab,clientes.telefono_hab,
                             clientes.telefono_otro,clientes.celular,clientes.id_cliente 
                             from clientes where clientes.cedula='$ceduclien';");
$repbuscdatclien=ejecutar($buscdatclien);                             
$datdatclien=assoc_a($repbuscdatclien);
$idcliente=$datdatclien['id_cliente'];
$bushistorial=("select entes.nombre,estados_clientes.estado_cliente from
entes,estados_clientes,titulares,clientes,estados_t_b
where 
clientes.id_cliente=$idcliente and
titulares.id_cliente=clientes.id_cliente and
titulares.id_ente=entes.id_ente and
titulares.id_titular=estados_t_b.id_titular and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
estados_t_b.id_beneficiario=0;");
$repbushist=ejecutar($bushistorial);
$cuanhist=num_filas($repbushist);
$historalbeni=("select entes.nombre,estados_clientes.estado_cliente,titulares.id_titular from
entes,beneficiarios,estados_clientes,titulares,clientes,estados_t_b
where 
clientes.id_cliente=$idcliente and
beneficiarios.id_cliente=clientes.id_cliente and
beneficiarios.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente and
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente;");
$rephistbeni=ejecutar($historalbeni);
$cuanthistbeni=num_filas($rephistbeni);
if($cuanthistbeni>=1){
    $mensaje2="Historial del cliente como beneficiario";
}
if($cuanhist>=1){
    $mensaje1="Historial del cliente como titular";
}
?>
<?if($cuanhist>=1){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion"><?echo $mensaje1;?></td>  
     </tr>
</table>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Ente.</th>
		<th class="tdtitulos">Estatus.</th>
	</tr>
	<? $i=1;
	      while($datosente=asignar_a($repbushist,NULL,PGSQL_ASSOC)){?>
	<tr>
	    <td class="tdcampos"><?echo $i;?></td>
		<td class="tdcampos"><?echo $datosente[nombre];?></td> 
		<td class="tdcampos"><?echo $datosente[estado_cliente];?></td> 
   </tr>
<?$i++;
    }
    echo "</table>";
}?>
<?if($cuanthistbeni>=1){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion"><?echo $mensaje2;?></td>  
     </tr>
</table>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Ente.</th>
		<th class="tdtitulos">Estatus.</th>
        <th class="tdtitulos">Titular.</th>
	</tr>
	<? $j=1;
	      while($datoshib=asignar_a($rephistbeni,NULL,PGSQL_ASSOC)){
              $elidtti=$datoshib['id_titular'];
              $nometti=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                                clientes.id_cliente=titulares.id_cliente and
                                titulares.id_titular=$elidtti;");
               $repnomtti=ejecutar($nometti);     
               $dattt=assoc_a($repnomtti);
               $eltitulas="$dattt[nombres] $dattt[apellidos]";
         ?>
	<tr>
	    <td class="tdcampos"><?echo $j;?></td>
		<td class="tdcampos"><?echo $datoshib[nombre];?></td> 
		<td class="tdcampos"><?echo $datoshib[estado_cliente];?></td> 
        <td class="tdcampos"><?echo $eltitulas;?></td> 
   </tr>
<?$j++;
    }
    echo "</table>";
}?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         
         <td colspan=4 class="titulo_seccion">Datos actuales del cliente</td>  
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos" colspan="1">C&eacute;dula:</td>
       <td class="tdcampos"  colspan="1"><input type="text" class="campos" id="cedclien" value="<?echo $datdatclien['cedula'];?>"> </td>  
     </tr>
      <tr>
       <input type="hidden" id="idclien" value="<?echo $datdatclien['id_cliente'];?>">   
     </tr>
	 <tr>
	   <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="cliennombre" class="campos" size="30" value="<? echo $datdatclien[nombres];?>"></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clienapellido"  class="campos" size="30" value="<? echo $datdatclien[apellidos];?>"></td>
	 </tr>   
     <tr>
	   <td class="tdtitulos" colspan="1">Tel&eacute;fono habitaci&oacute;n:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="telefh" class="campos" size="30" value="<? echo $datdatclien[telefono_hab];?>"></td>
       <td class="tdtitulos" colspan="1">Tel&eacute;fono celular:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="telefc"  class="campos" size="30" value="<? echo $datdatclien[celular];?>"></td>
	 </tr> 
     <tr> 
	   <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliendirr" class="campos"><? echo $datdatclien[direccion_hab];?></TEXTAREA></td>
	</tr>
    <tr>
              <td><label title="Editar datos basicos del cliente" id="datosbasicos" class="boton" style="cursor:pointer" onclick="editarbasicos()" >Guardar</label></td>               
    </tr>
 </table>    
 <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
 <div id='repclien'></div>