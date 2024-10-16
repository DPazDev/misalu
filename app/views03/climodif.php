<?
   include ("../../lib/jfunciones.php");
   sesion();
   $rifdcli=$_POST['elrif'];
    echo$busclif=("select clinicas_proveedores.*,proveedores.tipo_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.rif='$rifdcli' and
              clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor;");
   $rebusclif=ejecutar($busclif);
   $datacli=assoc_a($rebusclif);
   $tipoprovee=$datacli['id_act_pro'];
   $queprove=$datacli['prov_compra'];
   $idClinicaProveedor=$datacli['id_clinica_proveedor'];

  $buscarprovee=("select actividades_pro.id_tipo_pro,actividades_pro.id_act_pro,actividades_pro.actividad
                                 from
                                      actividades_pro
                                 where
                                        actividades_pro.id_act_pro=$tipoprovee");
    $repbusprovee=ejecutar($buscarprovee);
    $dataprov=assoc_a($repbusprovee);
    $tipopro=$dataprov['id_tipo_pro'];
      if($tipopro==1)
         $tipoprodf='Natural';//definicion
      else
         $tipoprodf='Juridica';//definicion
    $actpro=$dataprov['actividad'];
    $IdActPro=$dataprov['id_act_pro'];
    ///////////////////////////////////consultar el servio y montos del servicio//////////
$BusServicioClinica=("select * from s_c_proveedores where id_clinica_proveedor=$idClinicaProveedor;");
$reServicioClinica=ejecutar($BusServicioClinica);
$DataServicio=assoc_a($reServicioClinica);
$MontoServicio=$DataServicio['monto_servicio_c'];
$TipoServicio=$DataServicio['tipo_monto_c'];
$idMoneda=$DataServicio['id_moneda'];

//configrurar campo servicio 0 porcentajes 1 Montos
  if($TipoServicio==0)
    { $selecionporct='selected';
      $selecionmonto='';
      $campoMontoservicio="max='100'  min='0' maxlength='4' step='5'  ";
    }
    else
    { $selecionporct='';
      $selecionmonto='selected';
      $campoMontoservicio="max='9999999999999999'  min='0' maxlength='9999999999999999' step='0.01' ";
    }
?>
<table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
    <tr>
       <td colspan=2><br><td>
    </tr>
    <tr>
       <td class="tdtitulos" colspan="1">RIF:</td>
       <td class="tdcampos"  colspan="1"><input type="text" id="nvrif" class="campos" value="<?echo $datacli['rif']?>"></td>
       <td class="tdtitulos" colspan="1">Nit:</td>
       <td class="tdcampos"  colspan="1"><input type="text" id="nvnit" class="campos" value="<?echo $datacli['nit']?>"> </td>
   </tr>
   <tr>
        <td class="tdtitulos" colspan="1">Nombre del Proveedor:</td>
	<td class="tdcampos"  colspan="1"><input type="text" id="nvnomb" class="campos" value="<?echo $datacli[nombre]?>"></td>
	<td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=25 ROWS=5 id="nvdircli" class="campos"><?echo $datacli['direccion']?></TEXTAREA></td>
   </tr>
   <tr>
          <td class="tdtitulos" colspan="1">Tel&eacute;fonos:</td>
	        <td class="tdcampos"  colspan="1"><input type="text" id="nvtelf" class="campos" value="<?echo $datacli[telefonos]?>"></td>
          <td class="tdtitulos" colspan="1">Correo:</td>
          <td class="tdcampos"  colspan="1"><input type="text" id="nvcorr" class="campos" value="<?echo $datacli[email]?>"> </td>
   </tr>
   <tr>
          <td class="tdtitulos" colspan="1">P&aacute;gina Web:</td>
	  <td class="tdcampos"  colspan="1"><input type="text" id="nvpagi" class="campos" value="<?echo $datacli[url]?>"></td>
   </tr>
   <tr>
      <td class="tdtitulos" colspan="1">Activo:</td>
       <?
          $esactivo=$datacli['activar'];
           if($esactivo==1){
       ?>
         <td class="tdcampos"><input type="radio" name="opcion" id="op1" value='1' checked >Si
         <input type="radio" name="opcion" id="op2" value='0'>No</td>
      <?}else{?>
         <td class="tdcampos">
           <input type="radio" name="opcion" id="op1" value='1'>Si
           <input type="radio" name="opcion" id="op2" checked value='0'>No</td>
      <?}?>
    </tr>

    <tr><td colspan="4" class="tdtitulos"><BR><fieldset><legend>COSTO DEL SERVICIO</legend>
      Procentaje o monto del servicio
        <select id="TipoMonto"  name="TipoMonto" class="campos" style="width:15%;" onChange="PorcentajeMontoservicio(event,this)"  >
            <option value="0" <?php echo $selecionporct; ?> >%</option>
            <option value="1" <?php echo $selecionmonto; ?> >Monto</option>
        </select>

        <input type="number" id="CostoServicio" name="CostoServicio" class="campos"  style="width:15%;" <?php echo $campoMontoservicio;?> size='5' value='<?php echo $MontoServicio;?>'   onkeydown="return soloMoneda(event,this)" title="Porcentaje o monto del costo de servicio">

        <!-- -------------------  SELECION DE LA MONEDA ------------------------- -->
        <?php $monedasconsulta=("select * from tbl_monedas");
        $repMonedaConsulta=ejecutar($monedasconsulta);
          if($idMoneda=='0'){$activomoneda='selected';}else{$activomoneda='';}
        ?>
            <select id="monedaservicio" class="campos"   >
                  <option id='porcentaje' disabled value="0" <?php echo $activomoneda;?>>%</option>
                  <?php  while($moneda=asignar_a($repMonedaConsulta,NULL,PGSQL_ASSOC)){
                              if($moneda[id_moneda]==$idMoneda){$activomoneda='selected';}else{$activomoneda='';}
                       ?>
                              <option value="<?php echo $moneda[id_moneda]?>"  <?php echo $activomoneda;?> > <?php echo "$moneda[simbolo] - $moneda[moneda] "?></option>
                         <?php
                                   }
                         ?>
               </select>


    </fieldset></td></tr>

    <tr>
       <td colspan="0" class="tdtitulos"></td>
       <td colspan="3" class="tdtitulos"></td>

    </tr>

  <tr><td colspan="4"><hr></td></tr>






     <tr>
        <td class="tdtitulos" colspan="1">Cheque a nombre de:</td>
	   <td class="tdcampos"  colspan="1"><input type="text" id="cheqano" class="campos" value="<?echo $datacli[nomcheque]?>"></td>
	   <td class="tdtitulos" colspan="1">RIF cheque:</td>
	   <td class="tdcampos"  colspan="1"><input type="text" id="cheqrif" class="campos" value="<?echo $datacli[rifcheque]?>"></td>
   </tr>
    <tr>

	<td class="tdtitulos" colspan="1">Direcci&oacute;n cheque:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=25 ROWS=5 id="cheqdiri" class="campos"><?echo $datacli['direccioncheque']?></TEXTAREA></td>
   </tr>
   <tr>
       	<td class="tdtitulos" colspan="1">Es proveedor compras? </td>
        <td class="tdcampos"  colspan="1">
          <?if($queprove==1){?>
                <input type="radio" id="provcom1" name="group1" value="1" checked>Si
                <input type="radio" id="provcom1a" name="group1" value="0">No
                <input type="radio" id="provcom1b" name="group1" value="2">Otros
           <?}else{if($queprove==0){?>
                 <input type="radio" id="provcom1" name="group1" value="1" >Si
                <input type="radio" id="provcom1a" name="group1" value="0" checked>No
                <input type="radio" id="provcom1b" name="group1" value="2">Otros
           <?}else{?>
                <input type="radio" id="provcom1" name="group1" value="1" >Si
                <input type="radio" id="provcom1a" name="group1" value="0" >No
                <input type="radio" id="provcom1b" name="group1" value="2" checked>Otros
           <?}
           }?>
        </td>
   </tr>
     <tr>
     <td class="tdtitulos" colspan="1">Clasificaci&oacute;n de proveedor?</td>
      <?
      if($datacli['tipo_proveedor']==0){?>
      <td class="tdcampos"  colspan="1">
               <input type="radio" id="extram" name="grouptp" value="0" checked>Extramural
              <input type="radio" id="intram" name="grouptp"  value="1">Intramural
     </td>
     <?}else{?>
           <td class="tdcampos"  colspan="1">
               <input type="radio" id="extram" name="grouptp" value="0" >Extramural
              <input type="radio" id="intram" name="grouptp"  value="1" checked>Intramural
     </td>
     <?}?>
   </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Tipo de proveedor:</td>
	   <td class="tdcampos"  colspan="1">
                    <select id="tipodeprovee" class="campos" onchange="$('proact').innerHTML='',actividprov(); return false;" style="width: 210px;" >
                              <option value="<?echo $tipopro; ?>"><?php echo $tipoprodf; ?></option>
                              <option value="1">Natural</option>
                              <option value="2">Juridica</option>
                    </select>
          </td>
          <td class="tdtitulos" colspan="1">Actividad del proveedor:</td>
          <td class="tdcampos" colspan="1"><div id="proact">
               <?php $buscaractividad=("select actividades_pro.id_act_pro,actividades_pro.actividad
                                 from
                                   actividades_pro
                                 where
                                   actividades_pro.id_tipo_pro=$IdActPro
                                order by
                                   actividades_pro.actividad;");
               $repsbuscaractivi=ejecutar($buscaractividad);
               ?>
                                    <select id="activprovee" class="campos"  style="width: 210px;" >
                                          <option value="<?php echo $IdActPro;?>"><?php echo $actpro;?></option>
                                          <?php  while($lasactivi=asignar_a($repsbuscaractivi,NULL,PGSQL_ASSOC)){?>
                                            <option value="<?php echo $lasactivi[id_act_pro]?>"> <?php echo "$lasactivi[actividad]"?></option>
                                <?php
                                          }
                                ?>
                                    </select>

	</div> <div id="actividad"></div></td>

       </tr>
   <tr>
     <input type="hidden" id="rifve" value="<?php echo $rifdcli;?>">
     <input type="hidden" id="elidcliente" value="<?echo $datacli['id_clinica_proveedor']?>">
        <? echo"<td colspan=\"2\" title=\"Guardar cambios\"><label class=\"boton\" style=\"cursor:pointer\" onClick=\"finalmodif()\" >Guardar</label></td>";
		      ?>
   </tr>

</table>
