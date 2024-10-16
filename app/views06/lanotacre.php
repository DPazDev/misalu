<?
include ("../../lib/jfunciones.php");
sesion();
$arregdescu=$_REQUEST['arregldes'];
$arregidinsu=$_REQUEST['arreglvalid'];
$valodescu=explode(",",$arregdescu);
$valodidins=explode(",",$arregidinsu);
$notacrenu=$_REQUEST['numnota'];
$comencredi=strtoupper($_REQUEST['comentncre']);
$idordencom=$_REQUEST['lacompraid'];
$contolcre=$_REQUEST['controcre'];
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

$notacredi=("insert into tbl_ordenes_notacre(id_orden_compra,num_nota_credito,cons_nota_credito,comentario) 
                     values($idordencom,'$notacrenu','$contolcre','$comencredi');");
$repnotacredi=ejecutar($notacredi);
//busco la nota credito 
$lanotacre=("select tbl_ordenes_notacre.id_nota_credito from tbl_ordenes_notacre where 
                    tbl_ordenes_notacre.id_orden_compra=$idordencom and tbl_ordenes_notacre.fecha_creado='$fecha';");
$replanotacre=ejecutar($lanotacre);      
$infonotacre=assoc_a($replanotacre);
$lanotacreid=$infonotacre[id_nota_credito];

$cuandeb=count($valodescu);
for($i=0;$i<$cuandeb;$i++){
      $motdebi=$valodescu[$i];
      $idproduc=$valodidins[$i];
      $buscocomp=("select tbl_insumos_ordenes_compras.monto_unidad,tbl_insumos_ordenes_compras.iva,
                                tbl_insumos_ordenes_compras.aumento,tbl_insumos_ordenes_compras.id_insumo 
                                from tbl_insumos_ordenes_compras where tbl_insumos_ordenes_compras.id_orden_compra=$idordencom and 
                                tbl_insumos_ordenes_compras.id_insumo=$idproduc;");
       $repbuscom=ejecutar($buscocomp); 
       $datidcomp=assoc_a($repbuscom);
       $montopro=$datidcomp['monto_unidad'];
       $eliva=$datidcomp['iva'];
       $elaumento=$datidcomp['aumento'];
       $montototal=$montopro*$motdebi;
       $lanotacre=("insert into tbl_insumos_ordenes_notacre(id_nota_credito,id_insumo,cantidad,monto_unidad,monto_producto,iva) 
                                   values($lanotacreid,$idproduc,$motdebi,$montopro,$montototal,$eliva);");

       $replanotacre=ejecutar($lanotacre);                                         
    }
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha generado una nota de credito con el id=$lanotacreid a la id_orden_compra=$idordencom";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha generado exitosamente la nota de credito</td>  
     </tr>
</table>
