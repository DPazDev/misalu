<?php
include ("../../lib/jfunciones.php");
sesion();
$lacedula=$_REQUEST['ceduclien'];
$buscadclien=("select clientes.id_cliente,clientes.nombres,clientes.apellidos from clientes where clientes.cedula='$lacedula';");
$repbuscaclien=ejecutar($buscadclien);
$cuantosclein=num_filas($repbuscaclien);
if($cuantosclein>=1){
$datoclien=assoc_a($repbuscaclien);
$idcliente=$datoclien[id_cliente];
$nomcompleto="$datoclien[nombres] $datoclien[apellidos] ";
$buscarcomotitu=("select entes.nombre,titulares.id_titular from entes,titulares,estados_t_b 
                              where
                                    titulares.id_ente=entes.id_ente and
                                    titulares.id_titular=estados_t_b.id_titular and
                                    estados_t_b.id_beneficiario=0 and
                                    estados_t_b.id_estado_cliente=4 and
                                    titulares.id_cliente=$idcliente and
                                    (entes.id_tipo_ente<>4 and entes.id_tipo_ente<>6);");
$repbuscomtitu=ejecutar($buscarcomotitu);                        
$cuantostitu=num_filas($repbuscomtitu);
$buscarcomoben=("select entes.nombre,beneficiarios.id_beneficiario,titulares.id_titular 
                               from entes,titulares,beneficiarios,estados_t_b where
                               titulares.id_ente=entes.id_ente and
                               titulares.id_titular=beneficiarios.id_titular and
                               estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
                               estados_t_b.id_estado_cliente=4 and
                               beneficiarios.id_cliente=$idcliente;");
$repbuscomben=ejecutar($buscarcomoben);       
$cuantobenfi=num_filas($repbuscomben);?>
<input type="hidden" id="cuantitu" value="<?echo $cuantostitu?>">
<input type="hidden" id="cuanbenf" value="<?echo $cuantobenfi?>">
<?if($cuantostitu>=1){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Generar declaraci&oacute;n al titular <?echo $nomcompleto?></td>  
     </tr>
    </table>
    <table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">Ente.</th>
        <th class="tdtitulos">Selecci&oacute;n.</th>
        
	</tr>
	<? 
         $e=1;
	      while($losente=asignar_a($repbuscomtitu,NULL,PGSQL_ASSOC)){
              $paso="combo";
              $elttl1=$losente['id_titular'];
              $decsalud=("select declaracion_t.id_declaracion from declaracion_t where declaracion_t.id_titular=$elttl1 limit 1");
              $repuesta=ejecutar($decsalud);
              $cuantdesa=num_filas($repuesta);
    ?>
	<tr>
	    <td class="tdcampos"><?echo $losente[nombre]?></td>
        <td  class="tdcampos" >
            <input type="radio" name="<?echo $paso?>" value="<?echo $losente[id_titular]?>">
         </td> 
         <?if($cuantdesa>=1){?>
          <td  title="Modificar declaraci&oacute;n de salud"><label class="boton" style="cursor:pointer" onclick="ModificarDS('<?echo "$elttl1-t"?>'); return false;" >Modificar</label></td>
        <?} ?>
<?$e++;
}?>   
</table>
<?
}
if($cuantobenfi>=1){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Generar declaraci&oacute;n al beneficiario <?echo $nomcompleto?></td>  
     </tr>
    </table>
    <table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">Ente.</th>
        <th class="tdtitulos">Selecci&oacute;n.</th>
        
	</tr>
	<? 
	      while($losenteb=asignar_a($repbuscomben,NULL,PGSQL_ASSOC)){
              $pasob="combob";
               $elttl1=$losenteb['id_beneficiarios'];
              $decsalud=("select declaracion_t.id_declaracion from declaracion_t where declaracion_t.id_titular=$elttl1 limit 1");
              $repuesta=ejecutar($decsalud);
              $cuantdesa=num_filas($repuesta);
    ?>
	<tr>
	    <td class="tdcampos"><?echo $losenteb[nombre]?></td>
        <td  class="tdcampos" >
            <input type="radio" name="<?echo $pasob?>" value="<?echo $losenteb[id_beneficiario]?>">
         </td> 
         <?if($cuantdesa>=1){?>
          <td  title="Modificar declaraci&oacute;n de salud"><label class="boton" style="cursor:pointer" onclick="ModificarDS('<?echo "$elttl1-b"?>'); return false;" >Modificar</label></td>
        <?} ?>
<?
}?>   
</table>
<?
}
}else{?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Error el cliente no existe!!!</td>  
     </tr>
 </table>
<?}?>