<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("h:i:s A");
$fechaFacturaini=$_POST['FechaIni'];
$fechaFacturafin=$_POST['FechaFin'];
$idproveedor=$_POST['idProvedor'];
if($idproveedor>0)
{
  $consultaproveedor="and proveedores.id_proveedor=$idproveedor";
}
else{$consultaproveedor=" ";}

$sqlOrdenes_factura="select proveedores.id_proveedor,clinicas_proveedores.nombre,clinicas_proveedores.rif,
              clinicas_proveedores.direccion,tbl_ordenes_compras.* from tbl_ordenes_compras,clinicas_proveedores,
              proveedores where
              tbl_ordenes_compras.id_proveedor_insumo=proveedores.id_proveedor and
              clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
tbl_ordenes_compras.fecha_emi_factura between '$fechaFacturaini' and '$fechaFacturafin'
$consultaproveedor
order by tbl_ordenes_compras.fecha_compra";
$rodenes=ejecutar($sqlOrdenes_factura);
    if(num_filas($rodenes)==0){//odenes no encontradas
      ?>
      <TABLE ID='NOENCONTRADOS' class="tabla_citas">
        <Tr>
            <Th class="titulo_seccion">RELACION DE FACTURAS COMPRAS SIN RETENCION DEL <?PHP echo$fechaFacturaini;?> AL <?PHP echo$fechaFacturafin;?></Th>
        </Tr>
        <Tr>
            <Td class="titulo_seccion">Nos se encontraron registros</Td>
        </Tr>
        <Tr>
            <Td class="tdcampos"><a href="#" title="Regresar al inicio" onclick="trasladar_facturasCompras();" class="boton">volver a buscar</a>
         </Td>
        </Tr>
      </TABLE>
      <?PHP
    }else{


        //buscar VARIABLE NECESARIAS

        $q_variable_iva=("select * from variables_globales where id_variable_global=26");
        $r_variable_iva=ejecutar($q_variable_iva);
        $f_variable_iva=asignar_a($r_variable_iva);
        if (($f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11 || $f_admin[id_tipo_admin]==2) and $f_admin[id_sucursal]==1) {
          $q_variable_ret=("select * from variables_globales where id_variable_global=34");
          $r_variable_ret=ejecutar($q_variable_ret);
          $f_variable_ret=asignar_a($r_variable_ret);
        }
        else
        { $q_variable_ret=("select * from variables_globales where id_variable_global=35");
          $r_variable_ret=ejecutar($q_variable_ret);
          $f_variable_ret=asignar_a($r_variable_ret);
        }
        /* **** Fin de buscar las variables globales de iva y retencion**** */
      ?>
      <form id="FormFacturasCompras" name="oa">
            <table class="tabla_citas colortable"  border=2 cellpadding=0 cellspacing=0>
              <tr>
          <td colspan=11 class="titulo_seccion">Facturas Para Este Intervalo de Fechas con
          retenci&oacute;n de iva <input class="campos" type="hidden" id="id_variableretiva"
          name="id_variableretiva" maxlength=128 size=5 value="<?php echo $f_variable_ret[id_variable_global]?>" >
          <input class="campos" type="text" id="variableretiva"  name="variableretiva" maxlength=128 size=5
          value="<?php echo $f_variable_ret[cantidad]?>" >
          <a href="#" OnClick="act_var_glo();" class="boton" title="Actualiza El Valor de Retencion de Iva">
          Actualizar</a></td>
          </tr>
          <tr>
          <td colspan=7 class="tdtitulos"><div id="act_var_glo"></div> </td>
          </tr>
          <tr>
          <td colspan=1 class="tdtitulos" width="2%">N</td>
          <td colspan=1 class="tdtitulos" width="20%">proveedor</td>
          <td colspan=1 class="tdtitulos" width="8%">Factura </td>
          <td colspan=1 class="tdtitulos" width="8%">Fecha de Compra</td>
          <td colspan=1 class="tdtitulos" width="8%">Monto</td>
          <td colspan=1 class="tdtitulos" width="8%">Monto Exento</td>
          <td colspan=1 class="tdtitulos" width="8%">Base Imponible</td>
          <td colspan=1 class="tdtitulos" width="8%">Iva Facturado</td>
          <td colspan=1 class="tdtitulos" width="8%">Iva Retenido</td>
          <td colspan=1 class="tdtitulos" width="8%">% - Descuento </td>
          <td colspan=1 class="tdtitulos" width="14%">Total a Pagar
<a href="#" OnClick="checkAll('FormFacturasCompras');">Selecione todos</a>
<a href="#" OnClick="uncheckAll('FormFacturasCompras');">Quitar todos</a>
          </td>
        </tr>
    <?php
    $i=0;
      while($ornes=asignar_a($rodenes,NULL,PGSQL_ASSOC)){
        $id_orden_compra=$ornes['id_orden_compra'];
        $sqlfactura_proceos="select * from facturas_procesos where facturas_procesos.id_orden_compra='$id_orden_compra'";
        $fac_procesos=ejecutar($sqlfactura_proceos);
        if(num_filas($fac_procesos)==0 and $ornes[nombre]<>'INVENTARIO INICIAL'){
              $i++;
              $rif=$ornes[rif];
              $nombrepro=$ornes[nombre];
              $direccionpro=$ornes[direccion];

              ?>
              <tr>
                <td colspan=1 class="tdcampos"><?php echo $i;?></td>
                <td colspan=1 class="tdcampos"><?php echo $nombrepro;?>
<input type="hidden" id="nombreprov_<?php echo $i?>"  name="nombreprov" maxlength=128  value="<?php echo $nombrepro?>" >
                </td>
              <td colspan=1 class="tdcampos">
                <?php
                          $url="views04/iver_fac_com.php?id_orden_compra=$ornes[id_orden_compra]";
                  ?>
                <a href="<?php echo $url; ?>" title="Relacion de Gastos de la Factura"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;" class="tdcampos"><?php echo $ornes[no_factura]?></a>
                <input class="campos" type="hidden" readonly='readonly' id="factura_<?php echo $i?>"  name="factura" maxlength=128 size=10 value="<?php echo $ornes[no_factura]?>" >
                <input class="campos" type="text"   readonly='readonly' id="confactura_<?php echo $i?>"  name="confactura" maxlength=128 size=10 value="<?php echo $ornes[no_control_fact]?>" title='Numero de Control de Factura'>
                <input class="campos" type="hidden" readonly='readonly' id="idordcom_<?php echo $i?>"  name="idordcom" maxlength=128 size=10 value="<?php echo $ornes[id_orden_compra]?>" >
                <input class="campos" type="hidden" readonly='readonly' id="idproveedor_<?php echo $i?>"  name="idproveedor" maxlength=128 size=10 value="<?php echo $ornes[id_proveedor]?>" >
              </td>
              <td colspan=1 class="tdcampos">
                <?php echo $ornes[fecha_compra]?>
                <input type="hidden" id="fecha_emision_<?php echo $i?>"  name="fecha_emision" maxlength=128  value="<?php echo $ornes[fecha_emi_factura]?>" >
              </td>
              <td colspan=1 class="tdcampos">
              <?php
              $q_compra=("select * from tbl_insumos_ordenes_compras,tbl_ordenes_compras where
              tbl_ordenes_compras.id_orden_compra=tbl_insumos_ordenes_compras.id_orden_compra and
              tbl_ordenes_compras.id_orden_compra=$ornes[id_orden_compra]
              ");
              $r_compra=ejecutar($q_compra);
              $monto_factura=0;
              $mon_fac_con_iva=0;
              $mon_fac_sin_iva=0;
              $base_imponible=0;
              $iva_fact=0;
              $iva_ret=0;
              while($f_compra=asignar_a($r_compra,NULL,PGSQL_ASSOC)){
                if ($f_compra [iva]==1){
                  if($f_compra['ivausado']==NULL or $f_compra['ivausado']=='NULL' or $f_compra['ivausado']=='') {//si el iva es nulo usar iva por defecto
                    $ivaglobal=$f_variable_iva['comprasconfig'];
                    $iva=$ivaglobal/100;
                    }else {
                    $iva=$f_compra['ivausado'];
                    }

                    $mon_fac_con_iva= $mon_fac_con_iva + (($f_compra [monto_producto] * $iva) + $f_compra [monto_producto]);
                    $base_imponible= $base_imponible + $f_compra [monto_producto];
                    $iva_fact=$iva_fact + ($f_compra [monto_producto] * ($iva));
                  }
                  else
                  {
                    $mon_fac_sin_iva= $mon_fac_sin_iva + $f_compra [monto_producto];
                  }
                  $iva_ret= $iva_fact * $f_variable_ret[cantidad];
                  $monto_fac_pag=$mon_fac_sin_iva +$mon_fac_con_iva - $iva_ret;
                  $montodescuento=$f_compra[montodescuento];
                  $descuento=$f_compra[descuento];
                  $monto_factura=($mon_fac_sin_iva + $mon_fac_con_iva);
                }
              if ($montodescuento>'0'){
                  if($ornes['ivausado']==NULL or $ornes['ivausado']=='NULL' or $ornes['ivausado']=='') {//si el iva es nulo usar iva por defecto
                    $ivaglobal=$f_variable_iva['comprasconfig'];
                  $iva=$ivaglobal/100;
                  }else {
                  $iva=$ornes['ivausado'];
                  }
                    $mon_fac_sin_iva = $mon_fac_sin_iva -(($mon_fac_sin_iva * $descuento)/100);
                    $base_imponible = $base_imponible -(($base_imponible * $descuento)/100);
                    $iva_fact=$base_imponible * $iva;
                    $iva_ret= $iva_fact * $f_variable_ret[cantidad];
                    $monto_fac_pag=$base_imponible +$iva_fact - $iva_ret + $mon_fac_sin_iva;
                    $monto_factura=($base_imponible + $iva_fact + $mon_fac_sin_iva) ;
              }

              ?>
                <?php echo number_format($monto_factura ,2,',','')?>
                </td>
              <?php
              /* ****  busco todas los abonos realizdos a las facturas **** */
              $q_fac_pro=("select * from facturas_procesos where facturas_procesos.id_orden_compra=$ornes[id_orden_compra]");
              $r_fac_pro=ejecutar($q_fac_pro);
              $num_filas1=num_filas($r_fac_pro);
              if ($num_filas1==0){
              ?>
                <!--Monto Exento---->
                  <td colspan=1 class="tdcampos">
                    <?php echo number_format($mon_fac_sin_iva  ,2,',','');?>
                  </td>
                  <!--Base Imponible---->
                  <td colspan=1 class="tdcampos"><?php
                    echo number_format($base_imponible  ,2,',','');
                    ?>
                  </td>
                  <!---Iva Facturado--->
                  <td colspan=1 class="tdcampos">
                    <?php
                      echo number_format($iva_fact,2,',','');
                      ?>
                  </td>

              <!---Iva Retenido--->
                  <td colspan=1 class="tdcampos">
                    <?php echo number_format($iva_ret,2,',','');?>
                  </td>
              <!-- Descuento--->
                  <td colspan=1 class="tdcampos">
                  <?php
                  //%descuento
                  echo "(".number_format($descuento,2,',','').")";
                  ///Monto descuento
                  echo number_format($montodescuento,2,',','');
                  ?>
                  </td>
            <!--Total a Pagar-->
                  <td colspan=1 class="tdcamposr">
                        <input class="camposr" type="text" id="montoexento_<?php echo $i?>"  name="montoexento_" maxlength=128 size=10 value="<?php echo $mon_fac_sin_iva?>">
                        <input class="camposr" type="text" id="baseimponible_<?php echo $i?>"  name="baseimponible_" maxlength=128 size=10 value="<?php echo $base_imponible?>">
                        <input class="camposr" type="text" id="iva_fact_<?php echo $i?>"  name="iva_fact_" maxlength=128 size=10 value="<?php echo $iva_fact?>">
                        <input class="camposr" type="text" id="iva_ret_<?php echo $i?>"  name="iva_ret_" maxlength=128 size=10 value="<?php echo $iva_ret?>">
                        <input class="camposr" readonly="readonly" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=5 value="<?php echo $monto_fac_pag?>">
                        <input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" name="checkl" maxlength=128 size=10 value="">
                  </td>
              </tr>

              <?php
              }

          }//fin ifFacturas

        }//fin while
        if($i>0){
          ?>
              <tr>
              <td colspan=8  class="tdtitulos">
                <?php echo "<input type=\"hidden\" id=\"conexa\"name=\"conexa\" value=\"$i\">";?>
              </td>
              <td colspan=1 class="tdtitulos">
              <input class="campos" type="hidden" id="anombrede" size=10 name="anombrede" value="">
              </td>
              <td  colspan=2 class="tdcampos"><input class="campos" type="hidden" id="cedularif" size=10 name="cedularif" value=""></td>

              </tr>

              <tr>
                <td colspan=8 class="tdcamposc">
                  <select id="banco" name="banco" class="campos"  style="width: 100px;"  >
                    <?php $q_banco=("select tbl_bancos.*,bancos.* from tbl_bancos,bancos where tbl_bancos.id_ban=bancos.id_ban and bancos.id_banco=13");
                    $r_banco=ejecutar($q_banco);
                    while($f_banco=asignar_a($r_banco,NULL,PGSQL_ASSOC)){
                      ?>
                      <option value="<?php echo $f_banco[id_banco]?>"><?php echo "$f_banco[nombanco] $f_banco[numero_cuenta] "?></option>
                    <?php
                        }
                    ?>
                  </select>
                  <input class="campos" type="text" id="numcheque" size=10 name="numcheque" value="0">
                  <input class="campos" type="hidden" id="rif"  name="rif" maxlength=128 size=10 value="<?php echo $rif?>" >
                  <input class="campos" type="hidden" id="nombreprov"  name="nombreprov" maxlength=128 size=10 value="<?php echo $nombrepro?>" >
                  <input class="campos" type="hidden" id="direccionprov"  name="direccionprov" maxlength=128 size=10 value="<?php echo $direccionpro?>" >
                </td>
                <td colspan=3 class="tdtitulos">
                  Total Fact<br>
                  iva Ret.<input class="campos" type="text" id="iva_rettt" size=10 name="iva_rettt" value="">
                  <br>
                  moto<input class="campos" type="text" id="monto" size=10 name="monto" value="">
                  <input class="campos" type="hidden" id="tipocuenta" size=10 name="tipocuenta" value="1">
                  <br>
                  <br>
                  <a href="javascript: sumarfacCompras(this);" class="boton"> Calcular</a>
                  <br>
                </td>
              </tr>

              <tr>
              <td colspan=7 class="tdtitulos">
                <p>
                  <a href="#" title="Regresar al inicio" onclick="trasladar_facturasCompras();" class="boton">volver a buscar</a></td>
                </p>
              <td colspan=4 class="tdtitulos">
                <input class="campos" type="hidden" id="motivo" size=40 name="motivo" value="">
                <p>
                <br>
                <a href="#" OnClick="facturasfaltantesLibro2();" class="boton" title="Guardar Cheque">Guardar</a></td>
                <br>
              </p>
              </tr>


              </table>
            </from>
            <div id="MostrarRetFActuras"></div>
            <?php

          }else{ //todas las facturas en el rango de fechas ya se encuentran reguistradas
            ?>
            <Tr>
                <Td colspan='11' class="titulo_seccion">Nos se encontraron registros</Td>
            </Tr>
            <Tr>
                <Td colspan='11' class="tdcampos"><a href="#" title="Regresar al inicio" onclick="trasladar_facturasCompras();" class="boton">volver a buscar</a>
             </Td>
            </Tr>
            <?php
          }


    }//fin if general
?>
