<?
include ("../../lib/jfunciones.php");
sesion();
$sucursal=1;
$fecha1=date("Y-m-d");
$fecha2=("2080-12-01");
$hora=date("H:i:s");
$nombrente=strtoupper($_POST['elentenomb']);
$tpodelente=$_POST['tipentees'];
$elrifente=$_POST['rifentes'];
$elcorrente=strtoupper($_POST['corrente']);
$eltelfente=$_POST['telefo1'];
$elfaxente=$_POST['fax1'];
$direcionetes=strtoupper($_POST['dirccente']);
$ciudadentes=$_POST['ciudadente'];
$quienfue=$_SESSION['esmpresa'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//echo "hola a todos---$fecha1--$nombrente--$tpodelente---$elrifente-----$elcorrente------$eltelfente--$elfaxente--$direcionetes---$ciudadentes";
$buscarelente=("select entes.id_ente from entes where entes.rif='$elrifente' and entes.id_tipo_ente=$tpodelente;");
$repbuscelente=ejecutar($buscarelente);
$cuanenteshay=num_filas($repbuscelente);
$elnombente=$_POST['elnotipente'];
if($cuanenteshay>=1){?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
      <tr>  
         <td colspan=8 class="titulo_seccion">Ya existe el ente <?echo "$nombrente con el RIF: $elrifente en la categoria $elnombente<br>"?><label class="boton" style="cursor:pointer" onclick="reg_empresas()" >Regresar</label></td>   
       </tr> 
   </table>    
<?}else{
$guardarente=("insert into entes(nombre,telefonos,direccion,email,rif,fecha_creado,hora_creado,id_ciudad,
                            fecha_inicio_contrato,fecha_renovacion_contrato,id_sucursal,id_tipo_ente,fecha_inicio_contratob,
                            fecha_renovacion_contratob) 
                  values
                         ('$nombrente','$eltelfente','$direcionetes','$elcorrente','$elrifente','$fecha1','$hora',$ciudadentes,'$fecha1','$fecha2',
                           $sucursal,$tpodelente,'$fecha1','$fecha2');");
 $repgente=ejecutar($guardarente);       
 /*busco el ente guardado*/
 $cualente=("select entes.id_ente from entes where entes.rif='$elrifente' and entes.fecha_creado='$fecha1';");
 $repcualente=ejecutar($cualente);
 $datente=assoc_a($repcualente);
 $numentees=$datente['id_ente'];
 /*fin de la busquedad*/
/*Para poner el codigo del ente*/
 $codente=("select entes.id_ente,entes.fecha_creado,entes.id_tipo_ente from entes where entes.id_ente=$numentees;");
 $repcodente=ejecutar($codente);
 $dataente=assoc_a($repcodente);
 list($ano,$mes)=explode('-',$dataente['fecha_creado']);
 $elcodigente="$numentees-$dataente[id_tipo_ente]-$mes$ano";
 $atcodigo=("update entes set codente='$elcodigente' where id_ente=$numentees;");
 $repatcodigo=ejecutar($atcodigo);
 /*fin de poner el codigo del ente*/
 /**/
 $entpoliza=("insert into polizas_entes (id_ente,id_poliza) 
                       values($numentees,37)");
 $repentpoliza=ejecutar($entpoliza);                      
 /**/
 //**********************************//
//Guardar los datos en la tabla logs;
$mensaje="El usuario $elus, ha creado una nueva empresa con el nombre $nombrente";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),$elid,'$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);

//fin de los registros en la tabla logs;
//**********************************//
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
      <tr>  
         <td colspan=8 class="titulo_seccion">El ente <?$entees?> se ha guardado exitosamente!!</td>   
       </tr> 
       <?
           if($quienfue==1){
               echo"
                      <tr>  
                        <td><label title=\"Registrar cliente\"  class=\"boton\" style=\"cursor:pointer\" onclick=\"\" >Registrar Cliente</label></td>  
                      </tr>";
               }       
        ?>
</table>
<?}?>
