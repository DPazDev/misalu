<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $lospaises=("select pais.id_pais,pais.pais from pais order by pais");
  $verpaises=ejecutar($lospaises);
  $elrif=strtoupper($_POST['rifpc']);
  $temprif=$_POST['rifpc'];
  $parcompar=substr($elrif, 2, 4);
  if(is_numeric($parcompar)){
        $busclip=("select * from clinicas_proveedores,proveedores where proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and clinicas_proveedores.rif=upper('$elrif');");
        $lobusco=ejecutar($busclip);
        $cuantos=num_filas($lobusco);
        $lodatos=assoc_a($lobusco);
       $loservicio=("select  servicios_proveedores.id_servicio_proveedor,servicios_proveedores.servicio_proveedor
                                from servicios_proveedores order by servicios_proveedores.servicio_proveedor");
        $reloservicio=ejecutar($loservicio);

        //echo $elrif;
          if  ($cuantos>=1){
        $paravtipo=$lodatos['id_tipo_proveedor'];
        $IdClinicaProveedor=$lodatos['id_clinica_proveedor'];
        if($lodatos['tipo_proveedor']==0){ $eltipoproves='Extramural';}
                      else {   $eltipoproves='Intramural'; }

        $loservicio=("select  servicios_proveedores.id_servicio_proveedor,servicios_proveedores.servicio_proveedor
                      from servicios_proveedores order by servicios_proveedores.servicio_proveedor");
        $reloservicio=ejecutar($loservicio);
        $tipoprovee=$lodatos['id_act_pro'];
        $laciudad=$lodatos['id_ciudad'];
        $busciudad=("select ciudad.ciudad from ciudad where id_ciudad=$laciudad;");
         $respbusciudad=ejecutar($busciudad);
         $dataciudad=assoc_a($respbusciudad);
         $ciudadnombr=$dataciudad['ciudad'];
          //////////////////////////servicio del proveedor/////////////////
          $sqlservicio=("select * from s_c_proveedores where id_clinica_proveedor='$IdClinicaProveedor';");
          $respservicio=ejecutar($sqlservicio);
          $DataServicio=assoc_a($respservicio);//DATOS DE los SERVICIOS
          $MontoServicio=$DataServicio['monto_servicio_c'];
          $TipoMontoServicio=$DataServicio['tipo_monto_c'];
          	if($TipoMontoServicio==0)//0 es porcentajes
            { $DenominacionMonto='%';  }
            else
            {
              $monedaCambio=("select * from tbl_monedas where id_moneda=$DataServicio[id_moneda];");
              $moneda=ejecutar($monedaCambio);
              $DataMoneda=assoc_a($moneda);
              $DenominacionMonto=$DataMoneda['simbolo'];
            }
            $CostoServicio=$MontoServicio." ".$DenominacionMonto;
       /////--------------------------------------------------------------------/////////////////////////
        ////PROVEEDOR NATURAL o JURIDICO
         echo$buscarprovee=("select actividades_pro.id_tipo_pro,actividades_pro.actividad
                                       from
                                            actividades_pro
                                       where
                                              actividades_pro.id_act_pro=$tipoprovee");

          $repbusprovee=ejecutar($buscarprovee);
          $dataprov=assoc_a($repbusprovee);
          $tipopro=$dataprov['id_tipo_pro'];
            if($tipopro==1)
               $tipopro='Natural';
            else
               $tipopro='Juridica';
          $actpro=$dataprov['actividad'];

              ?>
           <table class="tabla_citas"  cellpadding=0 cellspacing=0>
                <tr>
      	     <td class="tdtitulos">Nombre del Proveedor:</td>
      	     <td class="tdcampos"><?php echo "$lodatos[nombre] ";?></td>
      	 </tr>
               <tr>
      		     <td class="tdtitulos">clasificación del Proveedor:</td>
      	        <td class="tdcampos"><?php echo "$eltipoproves";?></td>
                   <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
                  <td class="tdcampos"> <TEXTAREA COLS=25 ROWS=5 class="tdcampos"><? echo "$lodatos[direccion] ";?></TEXTAREA>
      	     </td>
      	 </tr>
         <tr>
           <td colspan="4" class="tdtitulos"><BR><fieldset> COSTO DEL SERVICIO <div class="tdcampos"><?php echo $CostoServicio; ?></div></fieldset></td></tr>



         <tr>
                   <td class="tdtitulos">Nit:</td>
      	     <td class="tdcampos"><? echo "$lodatos[nit] ";?></td>
      	     <td class="tdtitulos">Tel&eacute;fonos:</td>
      	     <td class="tdcampos"><? echo "$lodatos[telefonos] ";?></td>
      	</tr>
              <tr>
                   <td class="tdtitulos">Correo:</td>
      	     <td class="tdcampos"><? echo "$lodatos[email] ";?></td>
      	     <td class="tdtitulos">P&aacute;gina Web:</td>
      	     <td class="tdcampos"><? echo "$lodatos[url] ";?></td>
             </tr>
             <tr>
            <td class="tdtitulos" colspan="1">Activo:</td>
             <?
                $esactivo=$lodatos['activar'];
                 if($esactivo==1){
             ?>
               <td class="tdcampos"><input type="radio" name="opcion" checked disabled>Si
               <input type="radio" name="opcion" disabled>No</td>
            <?}else{?>
               <td class="tdcampos"><input type="radio" name="opcion" disabled>Si
               <input type="radio" name="opcion" checked disabled>No</td>
            <?}?>
             <td class="tdtitulos" colspan="1">Ciudad:</td>
            <td class="tdcampos"><?echo $ciudadnombr;?></td>
            </tr>
              <tr>
                  <td class="tdtitulos" colspan="1">Tipo de proveedor:</td>
                  <td class="tdcampos">
                             <?echo $tipopro;?>
                  </td>
                  <td class="tdtitulos" colspan="1">Actividad de proveedor:</td>
                  <td class="tdcampos">
                             <?echo $actpro;?>
                  </td>
             </tr>
             <input type="hidden" id="elricli" value="<?php echo $elrif; ?>">
             <br>
             <tr>
              <td title="Modificar Proveedor"><label class="boton" style="cursor:pointer" onclick="modifcli()" >Modificar</label></td>
             </tr>
           </table>
           <div id="finalcli"></div>
       <?

      }else{

        ////////////////////////////////NUEVO PROVEEDOR//////////////////////////////////
          ?>
            <table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
                <tr>
                   <td colspan=2><br><td>
                </tr>
                <tr>
            	 <td class="tdtitulos" colspan="1">Nombre del Proveedor:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="fnoclin" class="campos" ></td>
            	 <td class="tdtitulos" colspan="1">Tel&eacute;fono del Proveedor:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="ftelcli" class="campos" > </td>
                </tr>
                <tr>
                      <td class="tdtitulos" colspan="1">Fax del Proveedor:</td>
            	  <td class="tdcampos"  colspan="1"><input type="text" id="faxclif" class="campos" ></td>
                      <td class="tdtitulos" colspan="1">P&aacute;gina Web:</td>
            	  <td class="tdcampos"  colspan="1"><input type="text" id="pagcli" class="campos" > </td>
                </tr>
                <tr>
                      <td class="tdtitulos" colspan="1">Tipo de proveedor:</td>
            	  <td class="tdcampos"  colspan="1">
                                <select id="tipodeprovee" class="campos" onchange="$('proact').hide(),actividprov(); return false;" style="width: 210px;" >
                                          <option value="0"></option>
                                          <option value="1">Natural</option>
                                          <option value="2">Juridica</option>
                                </select>
                      </td>
                      <td class="tdtitulos" colspan="1">Actividad del proveedor:</td>
                      <td class="tdcampos" colspan="1"><div id="proact"><select disabled="disabled" class="campos" style="width: 130px;" >
            	                               <option value="0">
            				       </select>
            	</div> <div id="actividad"></div></td>

                  </tr>

                <tr>
            	 <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
            	 <td><TEXTAREA COLS=25 ROWS=5 id="dircli" class="campos"></TEXTAREA></td>
               </tr>
               <tr>
                  <td class="tdtitulos" colspan="1">Activo:</td>
                     <td class="tdcampos"><input type="radio" name="opcion" id="op1" value='1' checked >Si
                     <input type="radio" name="opcion" id="op2" value='0'>No</td>
                </tr>
               <tr>
                     <td class="tdtitulos" colspan="1">Nit:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="nitcli" class="campos" ></td>
                     <td class="tdtitulos" colspan="1">Correo Electr&oacute;nico:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="correcli" class="campos" > </td>
               </tr>
               <tr>
                 <td class="tdtitulos" colspan="1">Servicio:</td>
                 <td class="tdcampos"  colspan="1"><select id="servicli" class="campos"  style="width: 210px;" >
                                          <option value="0"></option>
            			      <?php  while($sercli=asignar_a( $reloservicio,NULL,PGSQL_ASSOC)){?>
            			      <option value="<?php echo $sercli[id_servicio_proveedor]?>"> <?php echo "$sercli[servicio_proveedor]"?></option>
            			      <?php
            			             }
            		              ?>
            		              </select>
            	 </td>
                 <td class="tdtitulos" colspan="1">Pa&iacute;s:</td>
            	 <td class="tdcampos"  colspan="1">

                            <select id="paicli" class="campos" onChange="$('prue1').hide(),paises(); return false"  style="width: 210px;" >
                                          <option value="0"></option>
            			      <?php  while($tpais=asignar_a($verpaises,NULL,PGSQL_ASSOC)){?>
            			      <option value="<?php echo $tpais[id_pais]?>"> <?php echo "$tpais[pais]"?></option>
            			      <?php
            			             }

            		              ?>
            		              </select>
            	 </td>

               </tr>
               <tr>
                   <td class="tdtitulos" colspan="1">Estado:</td>
            	 <td class="tdcampos" colspan="1"><div id="prue1"><select disabled="disabled" class="campos" style="width: 210px;" >
            	                               <option value="0">
            				       </select>
            	</div> <div id="laciudad"></div></td>
                  <td class="tdtitulos" colspan="1">Ciudad:</td>
                  <td class="tdcampos" colspan="1"><div id="prue2"><select disabled="disabled" class="campos" style="width: 210px;" >
                                                   <option value="0">
            	                               </select>
                   </div> <div id="laciudad2"></div></td>

                 </tr>


                 <tr><td colspan="4" class="tdtitulos"><BR>
                   <tr><td colspan="4" class="tdtitulos">
                     <BR><fieldset><legend>COSTO DEL SERVICIO</legend>
                       Procentaje o monto del servicio
                       <select id="TipoMonto"  name="TipoMonto" class="campos" style="width:15%;" onChange="PorcentajeMontoservicio(event,this)"  >
                           <option value="0" selected>%</option>
                           <option value="1">Monto</option>
                       </select>

                       <input type="number" id="CostoServicio" name="CostoServicio" class="campos"  style="width:15%;" max="100"  min="0" step="1" size='5' value='40' maxlength="4"  onkeydown="return soloMoneda(event,this)" title="Porcentaje o monto del costo de servicio">

                         <!-- -------------------  SELECION DE LA MONEDA ------------------------- -->
                         <?php $monedasconsulta=("select * from tbl_monedas");
                         $repMonedaConsulta=ejecutar($monedasconsulta);
                         ?>
                             <select id="monedaservicio" class="campos"   >
                                   <option id='porcentaje' disabled value="0" selected >%</option>
                                   <?php  while($moneda=asignar_a($repMonedaConsulta,NULL,PGSQL_ASSOC)){
                                               if($moneda[id_moneda]==$idMoneda){$activomoneda='selected';}else{$activomoneda='';}
                                        ?>
                                               <option value="<?php echo $moneda[id_moneda]?>"  <?php echo $activomoneda;?> > <?php echo "$moneda[simbolo] - $moneda[moneda] "?></option>
                                          <?php
                                                    }
                                          ?>
                                </select>


                     </fieldset>
                   </TD></TR>

                 <tr>
                    <td colspan="0" class="tdtitulos">Procentaje o monto del servicio

                      </td>
                    <td colspan="3" class="tdtitulos">

                    </td>

                 </tr>

               <tr><td colspan="4"><hr></td></tr>
               <tr>
                 <td class="tdtitulos" colspan="1">Cheque a nombre de:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="cheqnom" class="campos" ></td>
                  <td class="tdtitulos" colspan="1">RIF:</td>
            	 <td class="tdcampos"  colspan="1"><input type="text" id="cherif" class="campos" ></td>
               </tr>

               <tr>
                 <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
            	 <td class="tdcampos"  colspan="1"><TEXTAREA COLS=25 ROWS=5 id="dircheq" class="campos"></TEXTAREA></td>

               </tr>
               <tr>
                  <td class="tdtitulos" colspan="1">Es proveedor compras?</td>
                  <td class="tdcampos"  colspan="1"><input type="radio" id="provcom1" name="group1" onClick="provCom()" value="1">Si
                                                                           <input type="radio" id="provcom1a" name="group1" value="2">No
                                                                           <input type="radio" id="provcom1b" name="group1" value="3">Otros
                                                                           </td>
               </tr>
                <tr>
                  <td class="tdtitulos" colspan="1">Tipo de proveedor?</td>
                  <td class="tdcampos"  colspan="1">
                           <input type="radio" id="extram" name="grouptp" value="0" checked>Extramural
                          <input type="radio" id="intram" name="grouptp"  value="1">Intramural
                 </td>
               </tr>
            </table>
            <div id="proveecom"></div>
             <table class="tabla_citas"  cellpadding=0 cellspacing=0>
               <tr>
                 <input type="hidden" id="elrif" value="<?php echo $elrif;?>">
                 <?
                   echo"<td colspan=\"2\" title=\"Guardar proveedor\"><label class=\"boton\" style=\"cursor:pointer\" onClick=\"cargaclini()\" >Guardar</label></td>";
                 ?>
               </tr>
               <tr><div id="final"></div></tr>
            </table>
<?php }

}else{
  ///////////////////////////////////////LISTA DE PROVEEDORES X NOMBRE/////////////////////////////////
	    $busclinicaspro=("select clinicas_proveedores.rif,clinicas_proveedores.nombre, clinicas_proveedores.direccion from
                              clinicas_proveedores where clinicas_proveedores.nombre like(upper('%$temprif%'));");
	   $repbusclinicapro=ejecutar($busclinicaspro);
	   $cuantoclipro=num_filas($repbusclinicapro);
	   if ($cuantoclipro=='0'){
		    echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                         <tr>
                           <td colspan=4 class=\"titulo_seccion\">No existe ning&uacute;n proveedor con la combinaci&oacute;n de letras $temprif
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
						 while($Losprove=asignar_a($repbusclinicapro,NULL,PGSQL_ASSOC)){
							echo"
                                   <tr>
                                       <td colspan=6 class=\"tdcampos\"  title=\"Ver data\" style=\"cursor:pointer\" onclick=\"busclienrif('$Losprove[rif]')\">$Losprove[nombre] $Losprove[rif]</td>
<td class=\"tdcampos\" > $Losprove[direccion]</td>
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
