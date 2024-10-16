<?php
include ("../../lib/jfunciones.php");
sesion();

?>
<table id="FacturasComprasProveedores" width="70%">
  <tr>
    <th colspan="2" class="titulo_seccion" aling='center'>TRANSFORMAR FACTURAS DE COMPRAS<br>AL LIBRO DE COMPRAS </th>
  </td>
<tr>
  <td><div class="tdcampos">Fecha de inicio</div><br>
        <input type="text" size="10" id="fechaini" onkeyup="contenidocampo(this)" onkeypress="return fechasformato(event,this,1);" name="fecharini" class="campos" maxlength="10" value="">
        <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechaini', 'yyyy-mm-dd')" title="Show popup calendar">
        <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
  </td>

  <td><div class="tdcampos">Fecha de final</div><br>
        <input type="text" size="10" id="fechafin" onkeyup="contenidocampo(this)" onkeypress="return fechasformato(event,this,1);" name="fecharfin" class="campos" maxlength="10" value="">
        <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechafin', 'yyyy-mm-dd')" title="Show popup calendar">
        <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
  </td>
</tr>
  <tr>
    <td colspan="2">
        <select style="width: 150px;" id="proveedor" name="proveedor" class="campos">
                 <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from
                 clinicas_proveedores,proveedores where
                 clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                 and clinicas_proveedores.prov_compra=1 $proveedor2 order by clinicas_proveedores.nombre");
                 $r_pc=ejecutar($q_pc);
                 ?>
                 <option   value="">El Proveedor Compra</option>
                 <option   value="*">TODOS LOS PROVEEDORES</option>
                 <?php
                 while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                 ?>
                 <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                 <?php
                 }
                 ?>
        </select>
    </td>
  </tr>
  <tr>
    <td colspan="2">
        <a href="#" title="Buscar facturas sin retencion y incorporarlas en libro de compras" onclick="facturasfaltantesLibro();" class="boton">Buscar factuars</a>
     </td>
  </tr>
</table>
