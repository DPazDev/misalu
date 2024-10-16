<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$mesaje=strtoupper($_REQUEST['elmensaje']);
$usuario=$_REQUEST['usuario'];
$iddeprat=$_REQUEST['deparid'];
$cualdepar=("select departamentos.departamento from departamentos where departamentos.id_departamento=$iddeprat;");
$repdepart=ejecutar($cualdepar);
$datdepart=assoc_a($repdepart);
$nombrdpart=$datdepart['departamento'];
$guardomensaje=("insert into messages(id_admin,mensaje,id_departamento) values($usuario,'$mesaje',$iddeprat);");
$repmensaje=ejecutar($guardomensaje);

?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">El mensaje se ha enviado al <?echo $nombrdpart?> exitosamente!!</td>  
        
	</tr>
  </table>
