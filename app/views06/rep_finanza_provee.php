<?php
header("Content-Type: text/html;charset=utf-8");
/* Nombre del Archivo: rep_finanza_provee.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Finanzas de Proveedores  */  


 include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);
if($id_ente==0)	        $condicion_ente="and entes.id_tipo_ente>0";
	else
	$condicion_ente="and entes.id_ente='$id_ente'";


list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}

	
list($id_estado,$estado)=explode("@",$_REQUEST['estapro']);
if($id_estado==0)	        $condicion_estado="and procesos.id_estado_proceso>0";
else if($id_estado=="-01"){
	$condicion_estado="and (procesos.id_estado_proceso!=1 and procesos.id_estado_proceso!=6 and procesos.id_estado_proceso!=13 and procesos.id_estado_proceso!=14 ) ";}

else if($id_estado=="-02"){
	$condicion_estado="and (procesos.id_estado_proceso=7 or procesos.id_estado_proceso=16  ) ";}

else if($id_estado=="-03"){
	$condicion_estado="and (procesos.id_estado_proceso!=14 ) ";}

else
$condicion_estado="and procesos.id_estado_proceso='$id_estado'";


list($proveedor)=explode("@",$_REQUEST['proveedor']);

if($proveedor=='INTRAMURAL'){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='1' and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores";}

if($proveedor=='EXTRAMURAL'){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='0' and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores";}

/*if($proveedor=='*'){
$condicion_proveedor="and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",clinicas_proveedores, proveedores";}

if($proveedor=='TODOS' )   { $condicion_proveedor="and gastos_t_b.id_proveedor>=0";
$prov="";}

if($proveedor=='NINGUNO' )  {  $condicion_proveedor="and gastos_t_b.id_proveedor=0";
$prov="";}*/



/*if($proveedor=="/"){
$condicion_proveedor="and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores,personas_proveedores";}*/

else {
$condicion_proveedor="and proveedores.id_proveedor='$proveedor' and 
                      proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",proveedores";}

/*echo $proveedor."****";*/

$q_rep=("select 
procesos.id_proceso,
gastos_t_b.id_proveedor,
procesos.id_estado_proceso 

 from procesos, gastos_t_b,entes $prov
 where 

procesos.fecha_recibido between '$fecre1' and '$fecre2' and gastos_t_b.id_proceso=procesos.id_proceso $condicion_estado $condicion_ente $condicion_proveedor
 group by
procesos.id_proceso,
gastos_t_b.id_proveedor, 
procesos.id_estado_proceso");

$r_rep=ejecutar($q_rep);

?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" >Reporte Relaci&oacute;n <?php if($id_servicio==0) echo "TODOS LOS SERVICIOS";
else echo "$servicio";?>, <?php if ($tipocliente=="TODOS") echo "TODOS LOS CLIENTES";
				else if($tipocliente=="TITULAR") echo "TITULARES";
				else if($tipocliente=="BENEFICIARIO") echo "BENEFICIARIOS";?> en estado <?php if($id_estado==0) echo"TODOS LOS ESTADOS";
else echo "$estado";?>, <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";  ?></td>     
        </tr>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=0> 
 
	<tr> 
		<td class="tdtitulosd" colspan=13>Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 

</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=0> 
	<tr> 
	   	<td colspan=12 class="tdcampos">ORDEN</td>  
	   	<td colspan=10 class="tdcampos">     </td>  
	   	<td colspan=12 class="tdcampos">MONTO TOTAL</td> 
	   	<td colspan=10 class="tdcampos">     </td>   
		<td colspan=12 class="tdcampos">ORDEN</td>  
		<td colspan=10 class="tdcampos">     </td>  
	   	<td colspan=12 class="tdcampos">PAGADO</td> 
	   	<td colspan=10 class="tdcampos">     </td>  
	   	<td colspan=12 class="tdcampos">NUM. CHEQUE</td> 
	   	<td colspan=10 class="tdcampos">      </td> 
	   	<td colspan=12 class="tdcampos">SIN PAGAR</td> 
	   	<td colspan=10 class="tdcampos">     </td>  
	   	<td colspan=12 class="tdcampos">PROVEEDOR</td> 

<?php

$a=1;

$s=1;

$d=1;

$e=1;

$ch=1;

$f=1;
$HH=0;




$conche=0;
$arreglocheques=array();
$arreglofinal=array();
$hayuno="f";
$cuanfinal=0;

		  $bsf1=0; 
		  $bsf2=0; 


	     while($f_rep=asignar_a($r_rep,NULL,PGSQL_ASSOC)){

  if($a==1){
      $lista="$f_rep[id_proceso]";
  }
    else 
  {
    if($s<3){
      $li="";
    }else{
      $li="<BR>";
      $s=0;
     } 

    $lista="$lista".","."$f_rep[id_proceso]"."$li";
  }

$a++;
$s++;

$q_gastos=("select 
gastos_t_b.monto_aceptado

 from  gastos_t_b
 where 

gastos_t_b.id_proceso='$f_rep[id_proceso]'");

$r_gastos=ejecutar($q_gastos);

	     while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){

$bsf1= $bsf1+($f_gastos[monto_aceptado]);
}	

$q_proc=("select 
facturas_procesos.id_proceso,
facturas_procesos.monto_sin_retencion,
facturas_procesos.monto_con_retencion,
facturas_procesos.id_proveedor, 
facturas_procesos.numero_cheque
 from  facturas_procesos
 where 

facturas_procesos.id_proceso='$f_rep[id_proceso]'   and
facturas_procesos.id_proveedor='$f_rep[id_proveedor]' 
");

$r_proc=ejecutar($q_proc);

     while($f_proc=asignar_a($r_proc,NULL,PGSQL_ASSOC)){

  if($d==1){
      $lista1="$f_proc[id_proceso]";
  }
    else 
  {
    if($e<3){
      $li1="";
    }else{
      $li1="<BR>";
      $e=0;
     } 

    $lista1="$lista1".","."$f_proc[id_proceso]"."$li1";
  }

$cuantoshay=count($arreglocheques); //arreglo para extraer los numeros de cheque

if($cuantoshay <=0 ){
$arreglocheques[$conche]=$f_proc[numero_cheque];

}
else {
  
   for($k=0;$k<=$cuantoshay;$k++){ // for paara recorrer el arreglo 
    $chetem=$arreglocheques[$k];
       if($chetem!=$f_proc[numero_cheque]){
          $hayuno="v";
       }else{
             $hayuno="f";
            }
    }
}

   if($hayuno=="v"){
     $conche=$cuantoshay+1;
     $arreglocheques[$conche]=$f_proc[numero_cheque];
  }
    
  $cuantprincipio = count($arreglocheques);
  $arreglofinal=array_unique($arreglocheques);  //selecciona los numeros de cheque sin los repetidos

/*$planilla1="$f_proc[numero_cheque]"; //se recarga la variable con el valor del select
/*echo $planilla1."****";*/

/*			 
if($planilla1<>$HH){  // compara un valor inicializado en cero con el primer registro tomado del select
$HH=$planilla1;    // se carga la variabke que estaba en cero con el valor del registro
  if($ch==1){
      $cheq1="$f_proc[numero_cheque]";
  }
    else 
  {
    if($f<3){
      $li2="";
    }else{
      $li2="<BR>";
      $f=0;
     } 

    $cheq1="$cheq1".","."$f_proc[numero_cheque]"."$li2";


  }

$ch++;
$f++;


 }*/


$d++;
$e++;

$bsf2= $bsf2+($f_proc[monto_sin_retencion]);
$bsf3= $bsf3+($f_proc[monto_con_retencion]);
$bstotal= $bsf2+$bsf3;



$qpropersona=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor='$f_proc[id_proveedor]' and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor");
$rpropersona=ejecutar($qpropersona);
$dataproper=asignar_a($rpropersona);
$fpropersona="$dataproper[nombres_prov] $dataproper[apellidos_prov]";

$qproclinica=("select clinicas_proveedores.nombre from clinicas_proveedores,proveedores where proveedores.id_proveedor='$f_proc[id_proveedor]' and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor");
$rproclinica=ejecutar($qproclinica);
$dataprocli=asignar_a($rproclinica);
$fproclinica="$dataprocli[nombre]";
}

$db=($bsf1-$bstotal);


}
$plin=0;
$cheq1="";
$cuanfinal = sizeof($arreglofinal);
for($r=0;$r<=$cuantprincipio;$r++){
    $varlarrgl=$arreglofinal[$r];
    if(!empty($varlarrgl)){

if($ch==1){
       $cheq1 = "$varlarrgl";
  }
    else 
  {
    if($f<2){
      $li2="";
    }else{
      $li2="<BR>";
      $f=0;
     } 

       $cheq1 ="$cheq1".","."$varlarrgl"."$li2";


  }

$ch++;
$f++;

  
  }
} 
/*echo $cheq1;*/
 echo"    <tr>
		<td colspan=12 class=\"tdtituloss\">$lista </td>
		<td colspan=10 class=\"tdcamposr\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdcamposr\">&nbsp;&nbsp;  ".montos_print($bsf1).Bs.S." </td>
		<td colspan=10 class=\"tdtituloss\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdtituloss\">$lista1 </td>
		<td colspan=10 class=\"tdtituloss\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdcamposr\">&nbsp;&nbsp; ".montos_print($bstotal).Bs.S." </td>
		<td colspan=10 class=\"tdtituloss\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdtituloss\">$cheq1 </td>
		<td colspan=10 class=\"tdtituloss\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdcamposr\">&nbsp;&nbsp; ".montos_print($db).Bs.S." </td>
		<td colspan=10 class=\"tdtituloss\">&nbsp;&nbsp;&nbsp; </td>
		<td colspan=12 class=\"tdtituloss\">$fpropersona $fproclinica </td>";

?>  

<tr><td colspan=14>&nbsp;</td></tr>
</table>



