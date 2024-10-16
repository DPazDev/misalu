<?php
include ("../../lib/jfunciones.php");
sesion();
$ceduclien=$_REQUEST['cliente'];
 $losnuente=("select entes.id_ente,entes.nombre from entes order by entes.nombre;");
$relosnuente=ejecutar($losnuente);
$buscdatclien=("select clientes.cedula,clientes.nombres,clientes.apellidos,clientes.direccion_hab,clientes.telefono_hab,
                             clientes.telefono_otro,clientes.celular,clientes.id_cliente,clientes.comentarios
                             from clientes where clientes.cedula='$ceduclien';");
$repbuscdatclien=ejecutar($buscdatclien);    
$cuantosclien=num_filas($repbuscdatclien);
$datdatclien=assoc_a($repbuscdatclien);
$mensaje1="$datdatclien[nombres] $datdatclien[apellidos] portador de la c&eacute;dula No. $datdatclien[cedula]";
$idcliente=$datdatclien['id_cliente'];
$elcoment=$datdatclien['comentarios'];
$bushistorial=("select entes.id_ente,entes.nombre,estados_clientes.estado_cliente,titulares.id_titular from
entes,estados_clientes,titulares,clientes,estados_t_b
where 
clientes.cedula='$ceduclien' and
titulares.id_cliente=clientes.id_cliente and
titulares.id_ente=entes.id_ente and
titulares.id_titular=estados_t_b.id_titular and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
estados_t_b.id_beneficiario=0 ;");
$parvsibene=("select entes.id_ente,entes.nombre,estados_clientes.estado_cliente,titulares.id_titular,beneficiarios.id_beneficiario from
entes,estados_clientes,titulares,clientes,estados_t_b,beneficiarios
where 
clientes.id_cliente=$idcliente and
beneficiarios.id_cliente=clientes.id_cliente and
titulares.id_titular=beneficiarios.id_titular and
titulares.id_ente=entes.id_ente and
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente ;");
$repbushist=ejecutar($bushistorial);
$cuantitula=num_filas($repbushist);
$repsibene=ejecutar($parvsibene);
$cuantosbene=num_filas($repsibene);
if($cuantitula==0) {
    $busbeni=("select entes.id_ente,entes.nombre,estados_clientes.estado_cliente,titulares.id_titular,beneficiarios.id_beneficiario from
entes,estados_clientes,titulares,clientes,estados_t_b,beneficiarios
where 
clientes.cedula='$ceduclien' and
beneficiarios.id_cliente=clientes.id_cliente and
titulares.id_titular=beneficiarios.id_titular and
titulares.id_ente=entes.id_ente and
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente ;");
$repbenif=ejecutar($busbeni);
}
$repbushist1=ejecutar($bushistorial);
$cuanhist=num_filas($repbushist);
$arrhijos=array();
$estclien=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente 
                            from estados_clientes 
               where (id_estado_cliente=5 or id_estado_cliente=1 or id_estado_cliente=4 or id_estado_cliente=8 or id_estado_cliente=9 or id_estado_cliente=10) 
               order by estados_clientes.estado_cliente");
 $repesclien=ejecutar($estclien);              
 $repesclien1=ejecutar($estclien); 
?>
<?

if($cuantosclien>=1){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente: <?echo $mensaje1;?></td>  
     </tr>
</table>
<?}else{?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">No existe ning&uacute;n cliente con la c&eacute;dula No. <?echo $ceduclien;?></td>  
     </tr>
</table>
<?}?>
<?if(($cuanhist>=1)&&($cuantosclien>=1)){?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Ente.</th>
		<th class="tdtitulos">Estatus.</th>
	</tr>
	<? $i=1;
	      while($datosente=asignar_a($repbushist,NULL,PGSQL_ASSOC)){
            $arrhijos[$i]=$datosente[id_titular];  
    ?>
	<tr>
	    <td class="tdcampos"><?echo $i;?></td>
		<td class="tdcampos"><?echo $datosente[nombre];?></td> 
		<td class="tdcampos"><?echo $datosente[estado_cliente];?></td> 
   </tr>
<?$i++;
    }
    echo "</table>";
    $cuantti=count($arrhijos);
    $r=1;
    for($j=1;$j<=$cuantti;$j++){
        $eltitu=$arrhijos[$j];
        $buscarbet=("select clientes.cedula,clientes.nombres,clientes.apellidos,estados_clientes.estado_cliente,
       parentesco.parentesco,beneficiarios.id_beneficiario 
from 
  clientes,estados_clientes,titulares,beneficiarios,estados_t_b,parentesco
where 
  titulares.id_titular=beneficiarios.id_titular and
  titulares.id_titular=$eltitu and
  clientes.id_cliente=beneficiarios.id_cliente and
  beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
  beneficiarios.id_parentesco=parentesco.id_parentesco order by clientes.nombres,clientes.apellidos");
     $repbusbet=ejecutar($buscarbet);
     $busnoente=("select entes.nombre from entes,titulares
where 
titulares.id_titular=$eltitu and
titulares.id_ente=entes.id_ente;");
   $repbuselente=ejecutar($busnoente);
   $datbudelente=assoc_a($repbuselente);
   $esdelente=$datbudelente['nombre'];
     $cuantbet=num_filas($repbusbet);
     if(($r==1)&&($cuantbet>=1)){?>
            <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
              <tr> 
                 <td colspan=4 class="titulo_seccion">Datos de los beneficiarios en el ente (<?echo $esdelente?>)</td>  
              </tr>
             </table>
             <table class="tabla_citas"  cellpadding=0 cellspacing=0>
               <tr>
                  <th class="tdtitulos">Nombre.</th>
                  <th class="tdtitulos">C&eacute;dula.</th>
		          <th class="tdtitulos">Estatus.</th>
                  <th class="tdtitulos">Parentesco.</th>
                  <th class="tdtitulos">Selecci&oacute;n.</th>
	         </tr>
             <? 
            $nh=1; 
	      while($losbenefi=asignar_a($repbusbet,NULL,PGSQL_ASSOC)){ 
            $hijost="hijo$nh";
          ?>
	        <tr>
	          <td class="tdcampos"><?echo "$losbenefi[nombres] $losbenefi[apellidos]";?></td>
		      <td class="tdcampos"><?echo $losbenefi['cedula'];?></td> 
		     <td class="tdcampos"><?echo $losbenefi['estado_cliente'];?></td> 
             <td class="tdcampos"><?echo $losbenefi['parentesco'];?></td> 
             <td class="tdcampos"><input type="checkbox" id="<?echo $hijost?>"  value="<?echo $losbenefi['id_beneficiario'];?>">
             </tr>
         <?
             $nh++;  
            }
         }
         echo "</table>";
    }
?>
<input type="hidden" id="totahijos" value="<?echo $nh-1?>">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Datos del cambio</td>  
     </tr>
     </table>
      <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
                 <td class="tdtitulos">Selecione el ente a cambiar:</td>
                 <td class="tdcampos">
                    <select id="lente" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                    <?php  
			         while($ventes=asignar_a($repbushist1,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo "$ventes[id_titular]@$ventes[id_ente]"?>"> <?php echo "$ventes[nombre]"?></option>
			      <?}?>
		        </select>  
             </td>
             <td class="tdtitulos">Pasar a:</td>
      <td class="tdcampos">
       <select id="estaclien" class="campos" >
                <option value=""> </option>
                <?php  while($losesclien=asignar_a( $repesclien,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $losesclien[id_estado_cliente]?>"> <?php echo "$losesclien[estado_cliente]"?></option>
                <?php
                 }?>
         </select>
       </td>
        </tr>
        <tr>
			    <td class="tdtitulos">Comentario:</td>  
			<td class="tdcampos"><TEXTAREA COLS=40 ROWS=7 id="comentitu" class="campos"><?echo $elcoment;?></TEXTAREA></td>  				
		</tr>
        </table>
          <div id='losente'></div>
        <input type="hidden" id="cedulclien" value="<?echo $ceduclien?>">
        <table class="tabla_citas"  cellpadding=0 cellspacing=0>
        <tr>
              <td><label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="procamexindi()" >Procesar Cambio</label></td>                
      </tr>
     </table>
        <?
      if($cuantosbene>=1){
          echo "<br>";
          ?>
      
           <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">El titular esta asignado como beneficiario</td>  
     </tr>
     </table>
     <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
                 <td class="tdtitulos">Selecione el ente a cambiar:</td>
                 <td class="tdcampos">
                    <select id="lenteotb" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                    <?php  
			         while($votentes=asignar_a($repsibene,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo "$votentes[id_beneficiario]"?>"> <?php echo "$votentes[nombre]"?></option>
			      <?}?>
		        </select>  
             </td>
             <td class="tdtitulos">Pasar a:</td>
      <td class="tdcampos">
       <select id="estaclienot" class="campos" >
                <option value=""> </option>
                <?php  while($losesclien1=asignar_a($repesclien1,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $losesclien1[id_estado_cliente]?>"> <?php echo "$losesclien1[estado_cliente]"?></option>
                <?php
                 }?>
         </select>
       </td>
        </tr>
        <tr>
			    <td class="tdtitulos">Comentario:</td>  
				<td class="tdcampos"><TEXTAREA COLS=40 ROWS=7 id="comentituotb" class="campos"><?echo $elcoment;?></TEXTAREA></td>  				
		</tr>
        </table>
        <div id='losente'></div>
        <input type="hidden" id="cedulclien" value="<?echo $ceduclien?>">
        <table class="tabla_citas"  cellpadding=0 cellspacing=0>
        <tr>
              <td><label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="proceottiben()" >Procesar Cambio</label></td>                
      </tr>
     </table>
        <?
        }
     ?>
      
     
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  

<?}else{?>
         <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
       <tr> 
         <td colspan=4 class="titulo_seccion">Datos del cambio</td>  
      </tr>
      </table>
      <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
         <td class="tdtitulos">Pasar a:</td>
         <td class="tdcampos">
             <select id="estaclien" class="campos" >
                <option value=""> </option>
                <?php  while($losesclien=asignar_a( $repesclien,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $losesclien[id_estado_cliente]?>"> <?php echo "$losesclien[estado_cliente]"?></option>
                <?php
                 }?>
         </select>
       </td>
      </tr>
      </table>
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
              <tr> 
                 <td colspan=4 class="titulo_seccion">Datos del beneficiario</td>  
              </tr>
             </table>
      <table class="tabla_citas"  cellpadding=0 cellspacing=0>
               <tr>
                  <th class="tdtitulos">Ente.</th>
                  <th class="tdtitulos">Estatus.</th>
                  <th class="tdtitulos">Titular.</th>
                  <th class="tdtitulos">Selecci&oacute;n.</th>
	         </tr>
             <? 
            $nh=1; 
	      while($benefi=asignar_a($repbenif,NULL,PGSQL_ASSOC)){ 
            $hijost="hijo$nh";
            $idtitular=$benefi['id_titular'];    
            $quititul=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                                         titulares.id_cliente=clientes.id_cliente and
                                         titulares.id_titular=$idtitular");
                                
            $repquititul=ejecutar($quititul);
            $infotitu=assoc_a($repquititul);
            $nomcpleto="$infotitu[nombres] $infotitu[apellidos]";
          ?>
	        <tr>
	          <td class="tdcampos"><?echo "$benefi[nombre]";?></td>
		      <td class="tdcampos"><?echo $benefi['estado_cliente'];?></td> 
		     <td class="tdcampos"><?echo $nomcpleto;?></td>                                                    
             <td class="tdcampos"><input type="checkbox" id="<?echo $hijost?>"  value="<?echo $benefi['id_beneficiario'];?>">
             </tr>
                <?$nh++; 
             }?>
               <input type="hidden" id="totahijos" value="<?echo $nh-1?>">
               <tr>
			    <td class="tdtitulos">Comentario:</td>  
				<td class="tdcampos"><TEXTAREA COLS=40 ROWS=7 id="comentitu" class="campos"><?echo $elcoment;?></TEXTAREA></td>  				
            </tr>
            <tr>
              <td><label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="procamexindi1()" >Procesar Cambio</label></td>                
           </tr>
              </table>
    <?}?>
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
    <div id='exclusionindiv'></div>
