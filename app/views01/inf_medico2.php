<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $srtBuscar=["'",'"',";",",","?"];
  $srtSustiuir=["\'",'\"', "\;","\,"," ? "];
  $proceson=$_REQUEST["elproceso"];
  $buscotipro=("select gastos_t_b.id_servicio from gastos_t_b where gastos_t_b.id_proceso=$proceson limit 1");
  $repbustipro=ejecutar($buscotipro);
  $datbustipro=assoc_a($repbustipro);
  $eltiposervicio=$datbustipro['id_servicio'];
  $diagnostic=strtoupper($_REQUEST["eldiagnos"]);
  $diagnostic=str_replace($srtBuscar,$srtSustiuir,$diagnostic);
  $laboratori=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["ellabora"]));
  $ultrasonid=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["elultra"]));
  $radiologi=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["elradio"]));
  $estudioesp=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["elradio"]));
  $pacipresenta=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["elpresenta"]));
  $indico=str_replace($srtBuscar,$srtSustiuir,strtoupper($_REQUEST["elindica"]));
  $elid=$_SESSION['id_usuario_'.empresa];
  $ELID=$_SESSION['id_usuario_'.empresa];
  $elus=$_SESSION['nombre_usuario_'.empresa];
  $fecha=date("Y-m-d");
  $hora=date("H:i:s");
  //actualizar la tabla gastos_t_b en el campo enfermedad
  if($eltiposervicio!=6){
    echo$actualizogtb=("update gastos_t_b set enfermedad=E'$diagnostic' where id_proceso=$proceson");
    $repactulizogtb=ejecutar($actualizogtb);
  }else{
	   echo$actualizogtb=("update procesos  set comentarios_medico=E'$diagnostic' where id_proceso=$proceson");
    $repactulizogtb=ejecutar($actualizogtb);
    $otraacutu=("update gastos_t_b set fecha_cita='$fecha',hora_cita='$hora' where id_proceso=$proceson");
    $repotracu=ejecutar($otraacutu);
	}
  //actualizar la tabla procesos en el campo id_estado_proceso
  if($eltiposervicio!=6){
  $actualiproceso=("update procesos set id_estado_proceso=7 where id_proceso=$proceson");
  $repaactualiproceso=ejecutar($actualiproceso);
  }
  //ingreso los datos en la tabla tbl_infomedico
  $verexiste=("select tbl_informedico.id_proceso from tbl_informedico where id_proceso=$proceson");
  $repverexiste=ejecutar($verexiste);
  $cuaexiste=num_filas($repverexiste);
  if($cuaexiste==0){
  $datainfomedic=("insert into tbl_informedico(id_proceso,id_admin,diagnostico,laboratorio,ultrasonido,radiologia,estudiosespe,indicandole,presenta)
                   values($proceson,$elid,E'$diagnostic',E'$laboratori',E'$ultrasonid',E'$radiologi',E'$estudioesp', E'$indico',E'$pacipresenta')");
  $repdatinfomedic=ejecutar($datainfomedic);
  //guardamos en la tabla log el registro del informe medico
  $mensaje="El usuario $elus, ha realizado el informe medico al proceso No. $proceson";
  $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
  $inrelo=ejecutar($relog);
  }
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>
     <td class="titulo_seccion">El informe se ha generado exitosamente!!!</td>
 </tr>
 <tr>
   <td  title="Imprimir Informe"><label class="boton" style="cursor:pointer" onclick="ImpInforme(<?echo $proceson?>)" >Imprimir</label></td>
 </tr>
</table>
