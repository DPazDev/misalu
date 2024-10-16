<?php
include ("../../lib/jfunciones.php");
sesion();
$ceduprov=substr($_POST["ceduprovee"],2,2);

 if(is_numeric($ceduprov)){
	  $esnumero=1; 
      $ceduprov=$_POST["ceduprovee"];
	} else{
               $esnumero=2;
                $ceduprov=$_POST["ceduprovee"];
               
                }
 if($esnumero==1){	
$querprov=("select * from  personas_proveedores where cedula_prov='$ceduprov';");
$resprov=ejecutar($querprov);
$numf=num_filas($resprov);?>
 <form method="get" onsubmit="return false;" name="datproper" id="datproper">

<?if ($numf==0){?>
  <input type="hidden" id="cedpnu" value="<? echo $ceduprov ?>">
 <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="nompnue" class="campos" size="30"></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="appepnu"  class="campos" size="30"></td>
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Correo:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="correpnu"  size="30" class="campos" ></td>
       <td class="tdtitulos" colspan="1">Tel&eacute;fono:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="telnu"  size="20" class="campos"></td>
    </tr>
  <tr>
   <?
     echo"<td colspan=\"2\" title=\"Agregar un servicio al proveedor\"><label class=\"boton\" style=\"cursor:pointer\" onClick=\"cargaservi1()\" >Agregar Servicio</label></td>";
   ?>
 </tr>
  <tr>
     <td colspan=4>
	 <div id="pprovee1"> </div>
     </td>
  </tr>
 </table>
  <? }else{ 
  $dataprov=assoc_a($resprov);
  $idpp=$dataprov['id_persona_proveedor'];
  $querysp=("select s_p_proveedores.id_s_p_proveedor,
  s_p_proveedores.direccion_prov,
  s_p_proveedores.telefonos_prov,
  s_p_proveedores.id_sucursal,
  s_p_proveedores.lunes,
  s_p_proveedores.martes,
  s_p_proveedores.miercoles,
  s_p_proveedores.jueves,
  s_p_proveedores.viernes,
  s_p_proveedores.sabado,
  s_p_proveedores.domingo,
  
  servicios_proveedores.servicio_proveedor,
  ciudad.ciudad,
  especialidades_medicas.especialidad_medica,
  s_p_proveedores.comentarios_prov,
  s_p_proveedores.horario,
  s_p_proveedores.nomina,
  s_p_proveedores.activar,
  proveedores.tipo_proveedor,
  s_p_proveedores.nplunes,
  s_p_proveedores.npmartes,
  s_p_proveedores.npmiercole,
  s_p_proveedores.npjueve,
  s_p_proveedores.npviernes,
  s_p_proveedores.npsabado,
  s_p_proveedores.npdomingo
 
  from
  s_p_proveedores,servicios_proveedores,ciudad,especialidades_medicas,proveedores
 where
  servicios_proveedores.id_servicio_proveedor = s_p_proveedores.id_servicio_proveedor and
  s_p_proveedores.id_ciudad = ciudad.id_ciudad and
  s_p_proveedores.id_especialidad = especialidades_medicas.id_especialidad_medica and
  s_p_proveedores.id_persona_proveedor='$idpp' and
  s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor order by direccion_prov;" );
  $rquerysp=ejecutar($querysp);
  ?>
    <input type="hidden" id="cedpnu" value="<? echo $ceduprov ?>">
     <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
     <tr>
      <td class="tdtitulos">Nombres:</td>
      <td class="tdcampos"><? echo "$dataprov[nombres_prov] "?></td>
      <td class="tdtitulos">Apellidos:</td>
      <td class="tdcampos"><? echo "$dataprov[apellidos_prov]"?></td>
     </tr>
    
    <tr>
      <td class="tdtitulos">Correo Electr&oacute;nico:</td>
      <td class="tdcampos"><? echo "$dataprov[email_prov] "?></td>
      <td class="tdtitulos">Num. Tel&eacute;fono:</td>
      <td class="tdcampos"><? echo "$dataprov[celular_prov]"?></td>
    </tr>
   <tr>
   <br>
   <?
   echo"<td colspan=\"2\" title=\"Agregar un nuevo servicio al proveedor\"><label class=\"boton\" style=\"cursor:pointer\" onClick=\"cargaservi($idpp); return false;\" >Agregar Servicio</label></td>";
   echo"<td colspan=\"2\" title=\"Modificar datos fiscales\"><label class=\"boton\" style=\"cursor:pointer\" onClick=\"datfispp()\" >Modificar datos fiscales</label></td>";
   ?>
   </tr>
    <tR><br> </tr>
   <tr>
        <td colspan=4>
	 <div id="fiscales"> </div>
       </td>
  </tr>
  <tr>
        <td colspan=4>
	 <div id="pprovee"> </div>
       </td>
  </tr>
    <tr>
      <td class="titulo_seccion" colspan="4">Servicios Prestados</td>
    </tr>
    <?php
    $a="panel";
    $b="panel";
    $n=1;
    $cpaso=1;
    $cpaso2=1;
     while ($serpp=asignar_a($rquerysp)){ 
     $fin1="panelp".$n;
    ?>
     <tr>
      <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
      <td class="tdcampos" colspan="3"><? echo $serpp[direccion_prov] ?></td>
     </tr>

     <tr>
      <td class="tdtitulos" colspan="1">Tel&eacute;fono:</td>
      <td class="tdcampos" coslpan="1"><? echo $serpp[telefonos_prov] ?></td>
      <td class="tdtitulos" colspan="1">Servicio:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[servicio_proveedor] ?></td>
     </tr>

     <tr>
       <td class="tdtitulos" colspan="1">Ciudad:</td>
       <td class="tdcampos" colspan="1"><? echo $serpp[ciudad] ?></td>
       <td class="tdtitulos" colspan="1">Especialidad m&eacute;dica:</td>
       <td class="tdcampos" colspan="1"><? echo $serpp[especialidad_medica] ?></td>
     </tr>  

     <tr>
       <td class="tdtitulos" colspan="1">Horario:</td>
       <td class="tdcampos" colspan="1"><? echo $serpp[horario] ?></td>
       <td class="tdtitulos" colspan="1">Es proveedor nomina:</td>
       <td class="tdcampos" colspan="1"><? if ($serpp[nomina]==0){
                                 echo "NO";
                                }else{
				 echo "SI";
				} ?></td>
      </tr>
     
     <tr>
       <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="1"><? echo $serpp[comentarios_prov] ?></td>
       <td class="tdtitulos" colspan="1"> Sucursal:</td><td class="tdcampos" colspan="1">
         <?
           $sucurt=$serpp[id_sucursal];
            if ($sucurt>0){
              $busucur=("select sucursales.sucursal from sucursales where id_sucursal=$sucurt;");
              $datsucur=ejecutar($busucur);
              $infosuc=assoc_a($datsucur);
               echo "$infosuc[sucursal]";
              $sucurt=0;
            } 
         ?>       
      </td>
     </tr>
<tr>
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Lunes:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[nplunes] ?></td>
    
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Martes:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npmartes] ?></td>
  </tr>
  <tr>    
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Mi&eacute;coles:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npmiercole] ?></td>
    
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Jueves:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npjueve] ?></td>
  </tr>
  <tr>    
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Viernes:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npviernes] ?></td>
     
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) S&aacute;bado:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npsabado] ?></td>
  </tr>
  <tr>    
      <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Domingo:</td>
      <td class="tdcampos" colspan="1"><? echo $serpp[npdomingo] ?></td>
  </tr> 
	 <tr>
   <td class="tdtitulos" colspan="1">D&iacute;as laborales:</td> 
   <td class="tdcampos">
           <?if($serpp['lunes']==1){?>   
		         <input type="checkbox" id="lunes" value=1 checked disabled> Lunes
			<?}else{?>   	 
			     <input type="checkbox" id="lunes" value=1 disabled> Lunes
			<?}?>   	 
			<?if($serpp['martes']==1){?> 
                   <input type="checkbox" id="Martes" value=1 checked disabled>Martes
			<?}else{?>   	 	   
			        <input type="checkbox" id="Martes" value=1 disabled>Martes
			<?}?>   	 
			<?if($serpp['miercoles']==1){?> 
		       <input type="checkbox" id="Miercoles" value=1 checked disabled>Mi&eacute;rcoles
			<?}else{?>
			     <input type="checkbox" id="Miercoles" value=1 disabled>Mi&eacute;rcoles
			<?}?>   	 
			<?if($serpp['jueves']==1){?> 
			    <input type="checkbox" id="Jueves" value=1 checked disabled>Jueves
			<?}else{?>	
			     <input type="checkbox" id="Jueves" value=1 disabled>Jueves
			<?}?>   	 	 
   </td>				
  </tr>			
   <tr>  
      <td class="tdtitulos" colspan="1"></td>   
       <td class="tdcampos">	
	        <?if($serpp['viernes']==1){?>   
		    	<input type="checkbox" id="Viernes" value=1 checked disabled>Viernes
			<?}else{?>		
			     <input type="checkbox" id="Viernes" value=1 disabled>Viernes 
			<?}?>   	
			<?if($serpp['sabado']==1){?>
			<input type="checkbox" id="Sabado" value=1 checked disabled>S&aacute;bado
			<?}else{?>
			   <input type="checkbox" id="Sabado" value=1 disabled>S&aacute;bado
			 <?}?>    
			   <?if($serpp['domingo']==1){?>
			     <input type="checkbox" id="Domingo" value=1 checked disabled>Domingo
			   <?}else{?>	 
			       <input type="checkbox" id="Domingo" value=1 disabled>Domingo
				<?}?>       
  </td>   
 </tr>
 <tr>
       <td class="tdtitulos" colspan="1">Clasificaci&oacute;n de proveedor?</td>  
       <?
          $opcionra1='grupoac';
          $noopbu1="$opcionra1$cpaso2";          
       if($serpp['tipo_proveedor']==1){?>
       <td class="tdcampos"  colspan="1">
         <input type="radio" id="extram" name="<?echo $noopbu1?>" value="0" disabled>Indirecto
         <input type="radio" id="intram" name="<?echo $noopbu1?>"  value="1" checked disabled>Directo
       </td>
       <?}else{?>
          <td class="tdcampos"  colspan="1">
            <input type="radio" id="extram" name="<?echo $noopbu1?>" value="0" checked disabled>Indirecto
            <input type="radio" id="intram" name="<?echo $noopbu1?>"  value="1" disabled>Directo
       </td>
       <?}?>
  </tr>
 <tr>
      <td class="tdtitulos" colspan="1">Activo:</td>
       <?
          $esactivo=$serpp['activar'];
         
          $opcionra='grupo';
          $noopbu="$opcionra$cpaso";
           if($esactivo==1){
       ?>
         <td class="tdcampos"><input type="radio" name="<?echo $noopbu?>" checked disabled>Si
         <input type="radio" name="<?echo $noopbu?>" disabled>No</td> 
      <?}else{?>
         <td class="tdcampos"><input type="radio" name="<?echo $noopbu?>" disabled>Si
         <input type="radio" name="<?echo $noopbu?>" checked disabled>No</td> 
      <?}?>
</tr>
     <tr>
       <?php
       $fin="panel".$n;
       echo"<td title=\"Modificar registro\"><label class=\"boton\" style=\"cursor:pointer\" onclick=\"edipro($serpp[id_s_p_proveedor],'$fin','$ceduprov')\" >Modificar</label></td>"; ?>
        <td class="tdcampos" colspan="2"></td>   
     </tr>
     <tr>
        <td colspan=4>
          <div id="<?php echo $fin;?>"> </div>
	</td>
     </tr>	
     <tr>
      <td colspan=4><hr></td>
    </tr>
    <?php
    $n++;
    $cpaso++;
    $cpaso2++;
    } ?>
   </table> 
 </form>
<? } 
 } //fin de ver si era numero o no
  else{ //no es numero
     $con=strtoupper($ceduprov);  
	  $buscanombre=("select personas_proveedores.cedula_prov,personas_proveedores.id_persona_proveedor,personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,s_p_proveedores.direccion_prov from personas_proveedores,s_p_proveedores where personas_proveedores.nombres_prov like(upper('%$ceduprov%')) and personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor;");
	$repbuscanom=ejecutar($buscanombre);  
	$totalfilabuscano=num_filas($repbuscanom);
	if($totalfilabuscano==0){
		  echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                         <tr> 
                           <td colspan=4 class=\"titulo_seccion\">No existe ning&uacute;n proveedor con la combinaci&oacute;n de letras $con
</td>  
                         </tr>
                     </table>";
		}else{
			     echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                         <tr> 
                           <td colspan=4 class=\"titulo_seccion\">Proveedores registrados</td>  
                         </tr>
                     </table>";
					 echo"<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
                              <colgroup style=\"width: 5em\" />
                               <colgroup align=\"right\" span=\"3\" style=\"width: 3em\" />
                               <colgroup style=\"width: 7em\" />
                         <tr> 
                              <th colspan=6 class=\"tdtitulos\">Nombre del proveedor.</th>
							  <th colspan=4  class=\"tdtitulos\">Direcci&oacute;n.</th>
                         </tr>
                         <tbody>";
						while($Losprove=asignar_a($repbuscanom,NULL,PGSQL_ASSOC)){
							echo"
                                   <tr>
                                       <td colspan=6 class=\"tdcampos\"  title=\"Ver data\" style=\"cursor:pointer\" onclick=\"buscarprovee11('$Losprove[cedula_prov]')\">$Losprove[nombres_prov] $Losprove[apellidos_prov]</td>  
<td class=\"tdcampos\" > $Losprove[direccion_prov]</td>  
                                   </tr>
									<tr>
                                       <td colspan=7><hr></td>
                                    <tr> 
                                      ";
						}	
                     echo"</tbody></table>"; 
			}
	} 
?>
