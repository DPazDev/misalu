<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

?>
<?php
include ("../../lib/jfunciones.php");
sesion();
$elnombarpro=strtoupper($_POST["nombaprox"]);
$elgruplab=$_POST["loslabora"];
$elgruparti=$_POST["elgruparti"];
$codbarr=$_POST["elcodbarra"];
echo"<br>elnombarpro=$elnombarpro elgruplab=$elgruplab elgruparti=$elgruparti <br>";
if($codbarr<>''){
    $queryb="and tbl_insumos.codigo_barras='$codbarr' order by tbl_insumos.codigo_barras";
  }
  if($codbarr=='*'){
      $queryb=" order by tbl_tipos_insumos.tipo_insumo,tbl_insumos.insumo";
    }
if((!empty($elnombarpro)) || ($elgruplab>=1) || ($elgruparti>=1) and (empty($codbarr))){

      //filtrar por nombre
    if(!empty($elnombarpro)){
       $lisProducto="and tbl_insumos.insumo like('%$elnombarpro%')";
     }else{$lisProducto="";}

     ///filtrar por MARCA
     if($elgruplab>=1){
       $lismarca="and tbl_insumos.id_laboratorio=$elgruplab";
     }else{$lismarca="";}

     ///filtrar por TIPO
     if($elgruparti>=1 and (!empty($elgruparti))){
       $lisTipo="and tbl_insumos.id_tipo_insumo=$elgruparti";
     }else{$lisTipo="";}

     $queryb="$lisTipo $lismarca $lisProducto order by tbl_insumos.insumo";

   }



$busquedadarti=("select
tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio,
tbl_tipos_insumos.tipo_insumo,tbl_insumos.codigo_barras,tbl_insumos.activo from
tbl_insumos,tbl_laboratorios,tbl_tipos_insumos where
tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo $queryb;");

$repbusqarti=ejecutar($busquedadarti);
$cuantoshay=num_filas($repbusqarti);
if($cuantoshay==0){
   echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
         <tr>
	   <td colspan=4 class=\"titulo_seccion\">No existen art&iacute;culos cargados!!!</td>
         </tr>
       </table>";
}else{
?>
  <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
         <tr>
	   <td colspan=4 class="titulo_seccion">Art&iacute;culos existentes</td>
         </tr>
       </table>
  <table class="tabla_citas colortable"  cellpadding=0 cellspacing=0>
		   <TR>
		        <TH>Art&iacute;culo</TH>
                        <TH>C&oacute;digo</TH>
		        <TH>Laboratorio</TH>
		        <TH>Tipo insumo</TH>
		        <TH>Bloq</TH>
			<TH>Opci&oacute;n</TH>
		  </TR>
   <?while($losartison=asignar_a($repbusqarti,NULL,PGSQL_ASSOC)){
       $iddelinsumo=$losartison['id_insumo'];
       $nomdelarti=$losartison['insumo'];
       $nomlaborat=$losartison['laboratorio'];
       $tipisumos=$losartison['tipo_insumo'];
       $codbarra=$losartison['codigo_barras'];
       $bloqueo=$losartison['activo'];
       $colorFila='';
       if($bloqueo=='1'){//esta Bloqueado
         $status='NO';
         if(strtoupper(Trim($nomlaborat))==strtoupper('NO APLICA')){
           $colorFila="style='background-color: #abebc6 !important;'";
         }
       }else{$status='SI';
              $colorFila="style='background-color: #ff3333 !important;'";
       }
    ?>
       <tr <?php echo $colorFila;?>>
         <td class="tdcampos"><?echo $nomdelarti?></td>
        <td class="tdcampos"><?echo $codbarra?></td>
         <td class="tdcampos"><?echo $nomlaborat?></td>
         <td class="tdcampos"><?echo $tipisumos?></td>
         <td class="tdcampos" ALIGN="right">-<?echo $status?></td>
         <td  title='Editar el art&iacute;culo'><label class='boton' style='cursor:pointer' onclick="new Effect.Puff('respbusarti'), Editarticulo(<?echo $iddelinsumo?>); return false;">Editar</label></td>
       </tr>
<?}?>
 </table>
<?}?>
