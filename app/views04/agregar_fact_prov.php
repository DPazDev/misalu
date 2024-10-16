<?php
$cuantos=$_POST['cuantos'];
$i=$cuantos+1;
$iva=0;
$ret=0;
$f_admin[id_tipo_admin]=2;
?>

<tr>
    <td colspan=1 class="tdcampos">
      <input class="campos" type="hidden" id="proceso_<?php echo $i?>"  name="proceso" maxlength=128 size=10 value="<?php echo $i ?>" >
      <input class="campos" type="hidden" id="idservicio_<?php echo $i?>"  name="idservicio" maxlength=128 size=10 value="0" >
      <input class="campos" type="text" size=8 id="factura_<?php echo $i?>"  name="factura" maxlength=128 size=10 value="" >
    </td>

    <td colspan=1 class="tdcampos">
      <input class="campos" type="text" size=8 id="controlfactura_<?php echo $i?>"  name="controlfactura" maxlength=128 size=10 value="" >
    </td>

    <td colspan=1 class="tdcampos">
     <input type="text" class="campos" name="fecha_emision" id="fecha_emision_<?php echo $i?>" onkeyup="contenidocampo(this)" onKeyPress="return fechasformato(event,this);" size="10" maxlength="10" >
    </td>
  <input class="camposr" type="hidden" id="honorarios_medicos_<?php echo $i?>"  disabled name="honorarios_medicos"  maxlength=128 size=10 value="0">
  <input class="campos" type="checkbox" <?php echo $ban ?> style="visibility:hidden" id="checkh_<?php echo $i?>" name="checklh" size=20 value="">
    <td colspan=1 class="tdcampos">
      <input class="camposr" type="text" size=8 id="gastos_clinicos_<?php echo $i?>"   name="gastos_clinicos" maxlength=128 size=10 value="">
    </td>
    <td  class="tdcampos">
      <input class="campos" type="checkbox" <?php echo $ban1 ?> id="checkg_<?php echo $i?>" name="checklg" size=20 value="">
      <input class="camposr" type="hidden" id="gc_hm_<?php echo $i?>"  disabled name="gc_hm_" maxlength=128 size=10 value="0">
    </td>
    <td colspan=2 class="tdcampos">
      <input class="camposr" type="text" id="iva_<?php echo $i?>"  disabled name="iva" maxlength=128 size=5 value="<?php echo $iva?>">
      <input class="camposr" type="hidden" id="retiva_<?php echo $i?>"  disabled name="retiva" maxlength=128 size=5 value="<?php echo $iva?>">
      <input class="campos" type="checkbox"  id="checkiva<?php echo $i?>" name="checkiva" size=20 value="">
    </td>
    <td colspan=1 class="tdcampos">
      <input class="camposr" type="text" id="ret_<?php echo $i?>"  disabled name="ret" maxlength=128 size=10 value="<?php echo $ret?>">
    </td>
    <td colspan=1 class="tdcamposr">
      <input class="camposr" type="text" id="honorarios_<?php echo $i?>"  disabled name="honorarios" maxlength=128 size=10 value="0">
    </td>
    <td colspan=1 class="tdcamposr">
      <input class="campos" type="checkbox" id="check_<?php echo $i?>" name="checkl" size=20 value="">
        <?php if ($f_admin[id_tipo_admin]==2 || $f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11)
        {
          ?>
        <select id="tipo_documento_<?php echo $i?>" <?php echo $type;?> name="tipo_documento_<?php echo $i?>" class="campos" style="width: 30px;" OnChange="visiblefactafec(this,<?php echo $i?>);"  >
                         <option value="0">F</option>
                        <option value="1">NC</option>

        </select>
        <input class="camposr" type="text" id="fac_afectada_<?php echo $i?>"  style="visibility:hidden"  name="fac_afectada_" maxlength=128 size=10 value="0">
        <?php
        }
        else
        {
            ?>
        <input class="camposr" type="hidden" id="tipo_documento_<?php echo $i?>"   name="tipo_documento_" maxlength=128 size=10 value="0">
        <input class="camposr" type="hidden" id="fac_afectada_<?php echo $i?>"   name="fac_afectada_" maxlength=128 size=10 value="0">
            <?php
            }
            ?>
    </td>
  </tr>
