<?php
include ("../../lib/jfunciones.php");
sesion();
$elidentees=$_REQUEST['idente'];
$eltipoente=$_REQUEST['tipoente'];
$nuevaf=$_REQUEST['fechaf'];
$inifif=$_REQUEST['fechai'];
$comision=$_REQUEST['comisionado'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$busucursal=("select admin.id_sucursal from admin where id_admin=$elid");
$repbusucu=ejecutar($busucursal);
$datasuc=assoc_a($repbusucu);
$lasucursal=$datasuc[id_sucursal];

//guardamos todo lo necesario para el contrato
    //guardamos en la tabla tbl_contratos_entes 
    $buscomayor=("select tbl_contratos_entes.id_contrato_ente from tbl_contratos_entes order by 
                               id_contrato_ente desc limit 1;");
     $repmayoy=ejecutar($buscomayor);                          
     $cuantmayor=num_filas($repmayoy);
     if($cuantmayor>=1){
         $datmaynu=assoc_a($repmayoy);
         $elnumayes=$datmaynu[id_contrato_ente]+1;
         $numeromayo=$elnumayes;
    }else{
          $numeromayo=1;
        }
    $ramo="HCM";
    $contratnumero="$ramo-$year-$lasucursal-$eltipoente-$numeromayo";
    $guarcontente=("insert into tbl_contratos_entes(id_ente,estado_contrato,fecha_creado,numero_contrato,comentario,fecha_final_pago,cuotacon,inicialcon,colectivo) 
                                 values($elidentees,'1','$fecha','$contratnumero','S/C','$nuevaf',0,0,1);"); 
    $repguacontente=ejecutar($guarcontente);
    //buscamos el contrato recien guardado!!
    $buscocontrato=("select tbl_contratos_entes.id_contrato_ente,tbl_contratos_entes.numero_contrato from tbl_contratos_entes where
                                   tbl_contratos_entes.id_ente=$elidentees and tbl_contratos_entes.numero_contrato='$contratnumero';");
    $repbuscontrato=ejecutar($buscocontrato);    
    $databuscontrato=assoc_a($repbuscontrato);
    $elcontratoid=$databuscontrato[id_contrato_ente];//----------> ya tenemos el id_contrato_ente;
    $contratonumero=$databuscontrato[numero_contrato];//----------> ya tenemos el numero de contrato;
     //busco mayor recibo contrato
    $mayorecicontrato=("select tbl_recibo_contrato.id_recibo_contrato from tbl_recibo_contrato order by 
                               id_recibo_contrato desc limit 1;");
     $repmayorrecontrato=ejecutar($mayorecicontrato);                          
     $cuantosmayorecontato=num_filas($repmayorrecontrato);
     if($cuantosmayorecontato>=1){
       $datmayorecibocontra=assoc_a($repmayorrecontrato);
       $elrecibomayor=$datmayorecibocontra[id_recibo_contrato]+1;
       $numcontraprima="$year-$elrecibomayor";
    }else{
          $numcontraprima="$year-1";
        }
    //guardamos en la tabla tbl_recibo_contrato
    $guardorecibocontrato=("insert into tbl_recibo_contrato(id_contrato_ente,num_recibo_prima,fecha_ini_vigencia,
                                              fecha_fin_vigencia,fecha_creado,hora_emision,id_comisionado) 
                                             values($elcontratoid,'$numcontraprima','$inifif','$nuevaf','$fecha','$hora',$comision);");
    $reprecibocontrato=ejecutar($guardorecibocontrato);   
    //busco el recibo recien cargado
    $cualrecibo=("select tbl_recibo_contrato.id_recibo_contrato,tbl_recibo_contrato.num_recibo_prima from tbl_recibo_contrato where
                            tbl_recibo_contrato.id_contrato_ente=$elcontratoid and num_recibo_prima='$numcontraprima';");
     $repcualrecibo=ejecutar($cualrecibo);                       
     $datcualrecibo=assoc_a($repcualrecibo);
     $idrecibocontrato=$datcualrecibo[id_recibo_contrato];//-------->ya tenemos el id_recibo_contrato
     $recibonumero=$datcualrecibo[num_recibo_prima];//-------->ya tenemos el recibo de contrato
     //buscamos la poliza del ente
     $bspolente=("select polizas_entes.id_poliza from polizas_entes where polizas_entes.id_ente=$elidentees; ");
     $repbspolente=ejecutar($bspolente);
     $datpolente=assoc_a($repbspolente);
     $lapolente=$datpolente[id_poliza];//ya tenemos la poliza;
     //buscamos la id_prima y los montos para guardar en la tabla de tbl_cract_recibo_primas
     $laprimapoli=("select primas.id_prima,primas.anual,primas.semestral,primas.trimestral,primas.mensual 
                           from primas where id_poliza=$lapolente and id_parentesco=0;");
     $replaprimpoli=ejecutar($laprimapoli);                      
     $primaesdata=assoc_a($replaprimpoli);
     $laprima=$primaesdata['id_prima'];//ya tenemos la id_prima;
     $anual=$primaesdata['anual'];
     $semestr=$primaesdata['semestral'];
     $trimest=$primaesdata['trimestral'];
     $mesul=$primaesdata['mensual'];
     //guardamos la data en la talba tbl_cract_recibo_primas
     $guarcarrecprima=("insert into tbl_caract_recibo_prima(id_recibo_contrato,id_titular,id_beneficiario,id_prima,
                        fecha_creado,monto_prima) 
                        values ($idrecibocontrato,0,0,$laprima,'$fecha','$anual');");
     $repguarcarrecprima=ejecutar($guarcarrecprima);
?>  
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha creado exitosamente el contrato colectivo!!!</td>  
         <td class="titulo_seccion"><label title="Imprimi cuadro recibo colectivo" class="boton" style="cursor:pointer" onclick="Impricolecente('<?echo $contratonumero?>','<?echo $recibonumero?>','<?echo $elidentees?>')" >Imprimir</label></td>
     </tr>
</table>
