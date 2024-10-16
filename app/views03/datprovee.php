<?php
include ("../../lib/jfunciones.php");
sesion();
$prove=$_POST["elpp"];
$cemodif=$_POST["lace"];
$nc=$_POST["numero"];//numero de contenedor

echo"<h1>$contd -- $nc<br></h1>";
$buscoelprove=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov
                         from personas_proveedores
                    where personas_proveedores.cedula_prov='$cemodif';");
$rpsbuscoelprove=ejecutar($buscoelprove);
$datdelprove=assoc_a($rpsbuscoelprove);
$ernombre=$datdelprove['nombres_prov'];
$erapellido=$datdelprove['apellidos_prov'];
$busprove=("select s_p_proveedores.id_s_p_proveedor,s_p_proveedores.direccion_prov,sucursales.sucursal,
            s_p_proveedores.id_sucursal,s_p_proveedores.telefonos_prov
            ,servicios_proveedores.id_servicio_proveedor,
            s_p_proveedores.lunes,s_p_proveedores.martes,s_p_proveedores.miercoles,s_p_proveedores.jueves,
            s_p_proveedores.viernes,s_p_proveedores.sabado,s_p_proveedores.domingo,servicios_proveedores.servicio_proveedor,
             ciudad.id_ciudad,
	    ciudad.ciudad,especialidades_medicas.id_especialidad_medica,especialidades_medicas.especialidad_medica,
	    s_p_proveedores.comentarios_prov,s_p_proveedores.horario,
            s_p_proveedores.nomina,personas_proveedores.cedula_prov,s_p_proveedores.activar,proveedores.tipo_proveedor,
            s_p_proveedores.nplunes,s_p_proveedores.npmartes,s_p_proveedores.npmiercole,s_p_proveedores.npjueve,
            s_p_proveedores.npviernes,s_p_proveedores.npsabado,s_p_proveedores.npdomingo,s_p_proveedores.monto_servicio_p,s_p_proveedores.tipo_monto_p,s_p_proveedores.id_moneda
      from
     s_p_proveedores,servicios_proveedores,especialidades_medicas,ciudad,sucursales,personas_proveedores,proveedores
	where
	  servicios_proveedores.id_servicio_proveedor = s_p_proveedores.id_servicio_proveedor and
	  s_p_proveedores.id_ciudad = ciudad.id_ciudad and
          s_p_proveedores.id_sucursal=sucursales.id_sucursal and
	  s_p_proveedores.id_especialidad = especialidades_medicas.id_especialidad_medica and
	  personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
	  s_p_proveedores.id_s_p_proveedor='$prove' and
  s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor order by direccion_prov;");
$rbus=ejecutar($busprove);
$datbus=assoc_a($rbus);
$servi=("select id_servicio_proveedor,servicio_proveedor from servicios_proveedores order by servicio_proveedor");
$ress=ejecutar($servi);
$ciu=("select id_ciudad,ciudad from ciudad order by ciudad;");
$reciu=ejecutar($ciu);
$epmedi=("select id_especialidad_medica,especialidad_medica from especialidades_medicas order by especialidad_medica;");
$remedi=ejecutar($epmedi);
$sucursales=("select sucursales.sucursal,sucursales.id_sucursal from sucursales order by sucursal");
$resucur=ejecutar($sucursales);
$esactivo=$datbus['activar'];
$ellunes=$datbus['lunes'];
$elmart=$datbus['martes'];
$elmier=$datbus['miercoles'];
$eljueve=$datbus['jueves'];
$elviern=$datbus['viernes'];
$elsabad=$datbus['sabado'];
$eldomin=$datbus['domingo'];
////////////chequeo dias///
//lunes
if($ellunes==1){$luneschecked="checked='checked'";}else{$luneschecked="";}
if($elmart==1){$marteschecked="checked='checked'";}else{$marteschecked="";}
if($elmier==1){$miercoleschecked="checked='checked'";}else{$miercoleschecked="";}
if($eljueve==1){$jueveschecked="checked='checked'";}else{$jueveschecked="";}
if($elviern==1){$vierneschecked="checked='checked'";}else{$vierneschecked="";}
if($elsabad==1){$sabadochecked="checked='checked'";}else{$sabadochecked="";}
if($eldomin==1){$domingochecked="checked='checked'";}else{$luneschecked="";}

$dllunes =$datbus['nplunes'];
$dlmartes =$datbus['npmartes'];
$dlmiercoles =$datbus['npmiercole'];
$dljueves =$datbus['npjueve'];
$dlviernes =$datbus['npviernes'];
$dlsabado =$datbus['npsabado'];
$dldomingo =$datbus['npdomingo'];
$dldomingo =$datbus['npdomingo'];
$dldomingo =$datbus['npdomingo'];

$MontoServicio=$datbus['monto_servicio_p'];
$TipoMontoServicio=$datbus['tipo_monto_p'];
$idMoneda=$datbus['id_moneda'];
	if($TipoMontoServicio==0)//0 es porcentajes
  { $DenominacionMonto='%';  }
  else
  { $DenominacionMonto='';  }
  $CostoServicio=$MontoServicio." ".$DenominacionMonto;

  //configrurar campo servicio 0 porcentajes 1 Montos
    if($TipoMontoServicio==0)
      { $selecionporct='selected';
        $selecionmonto='';
        $campoMontoservicio="max='100'  min='0' maxlength='4' step='5'  ";
        $selecioneMoneda='disabled';
      }
      else
      { $selecionporct='';
        $selecionmonto='selected';
        $campoMontoservicio="max='9999999999999999'  min='0' maxlength='9999999999999999' step='0.01' ";
        $selecioneMoneda='';
      }


?>
  <table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
    <tr>
          <td colspan=2><br><td>
   </tr>
   <tr>
     <td class="tdtitulos" colspan="1">C&eacute;dula:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="cedu1<?php echo $nc;?>" class="campos"  value="<? echo $cemodif ?>"></td>
   </tr>
   <tr>
     <td class="tdtitulos" colspan="1">Nombre:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="nombre1m<?php echo $nc;?>" class="campos"  value="<? echo $ernombre ?>"></td>
     <td class="tdtitulos" colspan="1">Apellido:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="apellido1m<?php echo $nc;?>" class="campos"  value="<? echo $erapellido ?>"></td>
   </tr>
   <tr>
    <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
    <td> <TEXTAREA COLS=25 ROWS=5 id="dpro<?php echo $nc;?>" class="campos"><?php echo $datbus[direccion_prov]?></TEXTAREA>
    </td>
  </tr>
  <tr>
     <td class="tdtitulos" colspan="1">Tel&eacute;fono:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="tel1<?php echo $nc;?>" class="campos"  value="<? echo $datbus[telefonos_prov] ?>"></td>
     <td class="tdtitulos" colspan="1">Servicio:</td>
     <td class="tdcampos"  colspan="1"><select id="servmedico<?php echo $nc;?>" class="campos" style="width: 230px;" >
                              <option value="<?php echo $datbus[id_servicio_proveedor]?>"> <?php echo $datbus[servicio_proveedor]?></option>
			   <?php  while($tservi=asignar_a($ress,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $tservi[id_servicio_proveedor]?>"> <?php echo "$tservi[servicio_proveedor]"?></option>
			   <?php
			  }
			  ?>
			 </select>
      </td>
  </tr>
  <tr>
       <td class="tdtitulos" colspan="1">Ciudad:</td>
       <td class="tdcampos" colspan="1"><select id="ciu<?php echo $nc;?>" class="campos" >
                                <option value="<?echo $datbus[id_ciudad]?>"> <?echo $datbus[ciudad] ?></option>
			        <?php  while($tciudad=asignar_a($reciu,NULL,PGSQL_ASSOC)){ ?>
				<option value="<?php echo $tciudad[id_ciudad]?>"> <?php echo "$tciudad[ciudad]"?></option>
			       <?php												                           }
			     ?>
			    </select>
       </td>
       <td class="tdtitulos" colspan="1">Especialidad m&eacute;dica:</td>
       <td class="tdcampos" colspan="1"><select id="esme<?php echo $nc;?>" class="campos" >
                                  <option value="<?echo $datbus[id_especialidad_medica]?>"> <?echo $datbus[especialidad_medica] ?></option>
				  <?php  while($tmedi=asignar_a($remedi,NULL,PGSQL_ASSOC)){ ?>
				  <option value="<?php echo $tmedi[id_especialidad_medica]?>"> <?php echo "$tmedi[especialidad_medica]"?></option>
				 <?php                                                                                                                       }
				?>
				</select>
       </td>
  </tr>
  <tr>
        <td class="tdtitulos" colspan="1">Horario:</td>
         <td>  <TEXTAREA COLS=25 ROWS=4 id="hop<?php echo $nc;?>" class="campos"><?php echo $datbus[horario]?></TEXTAREA>
	 </td>
         <td class="tdtitulos" colspan="1">Comentario:</td>
          <td>  <TEXTAREA COLS=25 ROWS=4 id="coment<?php echo $nc;?>" class="campos"><?php echo $datbus[comentarios_prov]?></TEXTAREA>
         </td>
  </tr>
<tr>
  <td class="tdtitulos" colspan="4">

    <fieldset><legend>COSTO DEL SERVICIO</legend>
      Procentaje o monto del servicio
        <select id="TipoMonto<?php echo $nc;?>"  name="TipoMonto" class="campos" style="width:15%;" onChange="PorcentajeMontoservicio(event,this,'<?php echo $nc;?>')"  >
            <option value="0" <?php echo $selecionporct; ?> >%</option>
            <option value="1" <?php echo $selecionmonto; ?> >Monto</option>
        </select>

        <input type="number" id="CostoServicio<?php echo $nc;?>" name="CostoServicio" class="campos"  style="width:15%;" <?php echo $campoMontoservicio;?> size='5' value='<?php echo $MontoServicio;?>'   onkeydown="return soloMoneda(event,this)" title="Porcentaje o monto del costo de servicio">
        <!-- -------------------  SELECION DE LA MONEDA ------------------------- -->
        <?php $monedasconsulta=("select * from tbl_monedas");
        $repMonedaConsulta=ejecutar($monedasconsulta);
          if($idMoneda=='0'){$activomoneda='selected';}else{$activomoneda='';}
        ?>
            <select id="monedaservicio<?php echo $nc;?>" class="campos"  >
                  <option id='porcentaje' disabled value="0" <?php echo $activomoneda;?>>%</option>
                  <?php  while($moneda=asignar_a($repMonedaConsulta,NULL,PGSQL_ASSOC)){
                              if($moneda[id_moneda]==$idMoneda){$activomoneda='selected';}else{$activomoneda='';}
                       ?>
                               <option value="<?php echo $moneda[id_moneda]?>"  <?php echo $activomoneda;?> > <?php echo "$moneda[simbolo] - $moneda[moneda] "?></option>
                         <?php
                                   }
                         ?>
               </select>

    </fieldset>
    </td>
</tr>


  <tr>
    <td class="tdtitulos" colspan="1">Sucursal:</td>
     <td class="tdcampos"  colspan="1"><select id="lasucur<?php echo $nc;?>" class="campos" >
                              <option value="<?php echo $datbus[id_sucursal]?>"> <?php echo $datbus[sucursal]?></option>
                           <?php  while($lassucur=asignar_a($resucur,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $lassucur[id_sucursal]?>"> <?php echo "$lassucur[sucursal]"?></option>
                           <?php
                          }
                          ?>
                         </select>
    <td class="tdtitulos" colspan="1">Es proveedor nomina:</td>
    <td class="tdcampos"><? $prvn=$datbus[nomina];
                            if ($prvn==1){
			      echo "<input type=\"checkbox\" id=\"pvn$nc\" value=1 checked>Si<br>
			            <input type=\"checkbox\" id=\"pvn$nc\" value=0>No";
			    }else{
			      echo "<input type=\"checkbox\" id=\"pvn$nc\" value=1 >Si<br>
			            <input type=\"checkbox\" id=\"pvn$nc\" value=0 checked>No";
			    }?></td>
  </tr>
   <tr>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Lunes:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="lablunes<?php echo $nc;?>" class="campos"  size= '3' value="<? echo $dllunes ?>"></td>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Martes:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labmarte<?php echo $nc;?>" class="campos"  size= '3' value="<? echo $dlmartes ?>"></td>
   </tr>
    <tr>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Mi&eacute;coles:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labmiercoles<?php echo $nc;?>" class="campos" size= '3' value="<? echo $dlmiercoles ?>"></td>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Jueves:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labjueves<?php echo $nc;?>" class="campos" size= '3' value="<? echo $dljueves ?>"></td>
   </tr>
   <tr>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Viernes:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labviernes<?php echo $nc;?>" class="campos" size= '3' value="<? echo $dlviernes ?>"></td>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) S&aacute;bado:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labsabado<?php echo $nc;?>" class="campos" size= '3' value="<? echo $dlsabado ?>"></td>
   </tr>
   <tr>
     <td class="tdtitulos" colspan="1">Total (P)acientes / (Estudios) Domingo:</td>
     <td class="tdcampos"  colspan="1"><input type="text" id="labdomingo<?php echo $nc;?>" class="campos" size= '3' value="<? echo $dldomingo ?>"></td>
   </tr>
   <tr>
   <td class="tdtitulos" colspan="1">D&iacute;as laborales:</td>
   <td class="tdcampos">

            <input type="checkbox" id="lunes1<?php echo $nc;?>" value='1' <?php echo $luneschecked;?> > Lunes
		    <input type="checkbox" id="martes1<?php echo $nc;?>" value='1' <?php echo $marteschecked;?>>Martes
		    <input type="checkbox" id="miercoles1<?php echo $nc;?>" value='1' <?php echo $miercoleschecked;?>>Mi&eacute;rcoles
			<input type="checkbox" id="jueves1<?php echo $nc;?>" value='1' <?php echo $jueveschecked;?>>Jueves
   </td>
  </tr>
   <tr>
      <td class="tdtitulos" colspan="1"></td>
       <td class="tdcampos">
			<input type="checkbox" id="viernes1<?php echo $nc;?>" value=1 <?php echo $vierneschecked;?> >Viernes
			<input type="checkbox" id="sabado1<?php echo $nc;?>" value=1 <?php echo $sabadochecked;?> >S&aacute;bado
			<input type="checkbox" id="domingo1<?php echo $nc;?>" value=1 <?php echo $domingochecked;?> >Domingo
  </td>
 </tr>
  <tr>
       <td class="tdtitulos" colspan="1">Clasificaci&oacute;n de proveedor?</td>
       <?
         $noopbu1="interext";
         if($datbus['tipo_proveedor']==1){?>
       <td class="tdcampos"  colspan="1">
         <input type="radio" id="extram1<?php echo $nc;?>" name="<?echo $noopbu1?>" value="0" >Indirecto
         <input type="radio" id="intram1<?php echo $nc;?>" name="<?echo $noopbu1?>"  value="1" checked >Directo
       </td>
       <?}else{?>
          <td class="tdcampos"  colspan="1">
            <input type="radio" id="extram1<?php echo $nc;?>" name="<?echo $noopbu1?>" value="0" checked >Indirecto
            <input type="radio" id="intram1<?php echo $nc;?>" name="<?echo $noopbu1?>"  value="1" >Directo
       </td>
       <?}?>
  </tr>
 <tr>
      <td class="tdtitulos" colspan="1">Activo:</td>
       <?
          $opcionra='grupo';
          $noopbu="opcion1";
           if($esactivo==1){
       ?>
         <td class="tdcampos"><input type="radio" name="<?echo $noopbu?>" id="op1<?php echo $nc;?>" value='1' checked >Si
         <input type="radio" name="<?echo $noopbu?>" id="op2<?php echo $nc;?>" value='0'>No</td>
      <?}else{?>
         <td class="tdcampos"><input type="radio" name="<?echo $noopbu?>" id="op1<?php echo $nc;?>" value='1'>Si
         <input type="radio" name="<?echo $noopbu?>" checked value='0' id="op2<?php echo $nc;?>">No</td>
      <?}?>
</tr>
    <input type="hidden" id="cedulpro<?php echo $nc;?>" value="<? echo $cedu1?>">
    <input type="hidden" id="idpr<?php echo $nc;?>" value="<? echo $datbus[id_s_p_proveedor]?>">
    <input type="hidden" id="contene<?php echo $nc;?>" value="<? echo $contd?>">
 <tr>
 <td title="Guardar cambio"><label class="boton" style="cursor:pointer" onclick="gucprove(<?php echo $nc;?>)" >Guardar cambios</label></td>
</tr>
 </table>
