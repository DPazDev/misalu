<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();
////ACTIVAR UNA TICKERA
$cedclien=$_POST["lacedulaclien"];
$clienteexistentes=("select * from clientes where cedula='$cedclien';");
$dataCliente=ejecutar($clienteexistentes);
$cuantosexisten=num_filas($dataCliente);
if($cuantosexisten>=1){ //cargar data del ciente existente
  $cliente=assoc_a($dataCliente);
  $nombre=$cliente['nombres'];
  $apellidos=$cliente['apellidos'];
  $fecha_nacimiento=$cliente['fecha_nacimiento'];
  $sexualidad=$cliente['sexo'];
  if($sexualidad=='1'){
      $sexo='Masculino';
      $mat='';
  }
  else{$sexo='Femenino';
  $mat='checked';//maternidad
}
  $direcionh=$cliente['direccion_hab'];
  $telefono1=$cliente['telefono_hab'];
  $telefono2=$cliente['telefono_otro'];
  $telefono2=$cliente['celular'];
  $email=$cliente['email'];
  $civil=$cliente['estado_civil'];
  $idciudad=$cliente['id_ciudad'];
  ///ciudad
  $dirsql=("select * from ciudad,estados,pais where ciudad.id_ciudad='$idciudad' and ciudad.id_estado=estados.id_estado and estados.id_pais=pais.id_pais;");
  $datadir=ejecutar($dirsql);
  $ciu=assoc_a($datadir);
  $pais=$ciu['pais'];
  $esta=$ciu['estado'];
  $ciudad=$ciu['estado'];
  $activo='disabled';

}
else
{
  $nombre='';
  $apellidos='';
  $fecha_nacimiento='';
  $sexualidad='';
  $sexo='';
  $direcionh='';
  $telefono1='';
  $telefono2='';
  $celular='';
  $email='';
  $pais='';
  $esta='';
  $ciudad='';
  $activo='';
}

$lospaises=("select pais.id_pais,pais.pais from pais order by pais");
$respaises=ejecutar($lospaises);

?>
<style>
select:disabled{
  background: #ccc;
}
</style>
<table>
<tr>
<td class="tdtitulos" colspan="1">Nombre:</td>
   <td class="tdcampos"  colspan="1"><input  type="text" id="cliennombre" <?php echo $activo; ?> class="campos" size="30" value="<?php echo $nombre;?>"></td>
  <td class="tdtitulos" colspan="1">Apellido:</td>
  <td class="tdcampos" colspan="1"><input  type="text" id="clienapellido" <?php echo $activo; ?> class="campos" size="30" value="<?php echo $apellidos;?>"></td>
</tr>
<tr>
<td class="tdtitulos" colspan="1">Genero:</td>
   <td class="tdcampos"  colspan="1">
     <select name="cliengenero" <?php echo $activo; ?> id="cliengenero" class="campos" onChange="vergenero()" style="width: 100px;">
         <option value="<?php echo $sexualidad;?>"><?php echo $sexo;?></option>
                       <option value="0">Femenino</option>
         <option value="1">Masculino</option>
       </select>
<div id='genero'></div>
</td>
  <td class="tdtitulos" colspan="1">Correo:</td>
  <td class="tdcampos" colspan="1"><input type="text" id="cliencorre"  <?php echo $activo; ?> value="<?php echo $email;?>" class="campos" size="30"></td>
</tr>
<tr>
 <td class="tdtitulos" colspan="1">Tel&eacute;fono habitaci&oacute;n:</td>
  <td class="tdcampos" colspan="1"><input type="text" id="clientf1"  <?php echo $activo; ?> class="campos" size="15" value="<?php echo $telefono1;?>" onkeypress="return SoloNumeros(event)"></td>
  <td class="tdtitulos" colspan="1">Tel&eacute;fono celular:</td>
  <td class="tdcampos" colspan="1"><input type="text" id="clientf2"  <?php echo $activo; ?> class="campos" size="15" value="<?php echo $telefono2;?>"  onkeypress="return SoloNumeros(event)"></td>
</tr>
<tr>
 <td class="tdtitulos" colspan="1">Fecha de nacimiento:</td>
  <td class="tdcampos" colspan="1"><input  type="text" size="10" id="fechanaci" class="campos" value='<?php echo $fecha_nacimiento;?>' maxlength="10">
               <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechanaci', 'yyyy-mm-dd')" title="Ver calendario">
              <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
  <td class="tdtitulos" colspan="1">Estado civil:</td>
  <td class="tdcampos" colspan="1">
    <select id="clienestcivil" <?php echo $activo; ?>class="campos" style="width: 130px;">
                        <option value="<?php echo $civil;?>"><?php echo $civil;?></option>
                         <option value="SOLTERO">Soltero(a)</option>
           <option value="CASADO">Casado(a)</option>
           <option value="DIVORCIADO">Divorciado(a)</option>
           <option value="VIUDO">Viudo(a)</option>
           <option value="CONCUBINO">Concubino(a)</option>
       </select>	</td>
</tr>
<tr>

  <td class="tdtitulos" colspan="1">Pa&iacute;s:</td>
  <td class="tdcampos" colspan="1"><select id="paicli" <?php echo $activo; ?> class="campos" onChange="$('prue1').hide(),paises(); return false"  style="width:130px;" >
                         <option value="<?php echo $pais;?>"><?php echo$pais;?></option>
       <?php  while($verpais=asignar_a($respaises,NULL,PGSQL_ASSOC)){?>
       <option value="<?php echo $verpais[id_pais]?>"> <?php echo "$verpais[pais]"?></option>
       <?php
              }
             ?>
  </select> </td>
<td class="tdtitulos" colspan="1">Estado:</td>
<td class="tdcampos" colspan="1"><div id="prue1">
    <select id='estado' disabled="disabled" class="campos" style="width: 130px;" >
                            <option value="<?php echo $esta;?>"><?php echo $esta;?></option>
          </select>
</div> <div id="laciudad"></div></td>
</tr>
<tr>

<td class="tdtitulos" colspan="1">Ciudad:</td>
 <td class="tdcampos" colspan="1"><div id="prue2"><select id='ciudad' disabled="disabled" class="campos" style="width: 130px;" >
                                  <option value="<?php echo $ciudad;?>"><?php echo $ciudad;?></option>
                            </select>
  </div> <div id="laciudad2"></div></td>
</tr>
<tr>
<td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
  <td class="tdcampos" colspan="3"><TEXTAREA <?php echo $activo; ?> COLS=60 ROWS=2 id="cliendir" class="campos"><?php echo $direcionh;?></TEXTAREA></td>
</tr>
<tr>
<td class="tdtitulos" colspan="1">Comentario:</td>
  <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliencoment" class="campos"></TEXTAREA></td>
</tr>
    <br>
    <tr>
      <td  colspan="4" align='center'>
          <DIV  id="RegistroTickera">
            <div id="titulotiker"><h1>ACTIVAR TICKERA</h1></div>
            <DIV id='activartickera'><label fron="generarticket"><span title="Generar Ticket"  class="boton" style="cursor:pointer" onclick="ActivarTickeras2()" >GENERAR ACTIVACIÓN</span></label></DIV>
          </DIV>
        <td>
    </tr>
  </table>
