<?php

?>
<div id='MostrarEgresos'>
<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$q_p_c=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=11");
$r_p_c=ejecutar($q_p_c);
$f_p_c=asignar_a($r_p_c);


  //sumo 1 año
$fechaactual=date("Y-m-d");
$fechaAnnoAnterio=date("Y-m-d",strtotime($fechaactual."- 5 year"));
  $contar_procesos=("select
                procesos.id_proceso,
                procesos.nu_planilla,
                procesos.id_beneficiario,
                clientes.nombres,
                clientes.apellidos,
                clientes.cedula,
                gastos_t_b.fecha_cita,
                gastos_t_b.hora_cita,
                entes.nombre,
                count(gastos_t_b.id_proceso)
            from
                procesos,
                gastos_t_b,
                admin,
                titulares,
                entes,
                clientes
            where
                procesos.id_proceso=gastos_t_b.id_proceso and
                gastos_t_b.id_tipo_servicio='9' and
                gastos_t_b.fecha_cita>'1900-01-01' and
                procesos.id_admin=admin.id_admin and
                procesos.id_estado_proceso=2 and
                admin.id_sucursal='$f_admin[id_sucursal]' and
                procesos.fecha_recibido>='$fechaAnnoAnterio' and
                procesos.id_titular=titulares.id_titular and
                titulares.id_ente=entes.id_ente and
                titulares.id_cliente=clientes.id_cliente
            group by
                procesos.id_proceso,
                procesos.nu_planilla,
                procesos.id_beneficiario,
                clientes.nombres,
                clientes.apellidos,
                clientes.cedula,
                gastos_t_b.fecha_cita,
                gastos_t_b.hora_cita,
                entes.nombre
            order by
                procesos.id_proceso desc;");
$cont_procesos=ejecutar($contar_procesos);

if(!empty($_POST[pag]))
      $pag=$_POST[pag];
    else $pag=0;
if(!empty($_POST[npag]))
          $Nupag=$_POST[npag];
        else $Nupag=0;
$lim=25;

  /////CONSUTAR DATA
?>

<?php
  $q_procesos=("select
                procesos.id_proceso,
                procesos.nu_planilla,
                procesos.id_beneficiario,
                clientes.nombres,
                clientes.apellidos,
                clientes.cedula,
                gastos_t_b.fecha_cita,
                gastos_t_b.hora_cita,
                entes.nombre,
                count(gastos_t_b.id_proceso)
            from
                procesos,
                gastos_t_b,
                admin,
                titulares,
                entes,
                clientes
            where
                procesos.id_proceso=gastos_t_b.id_proceso and
                gastos_t_b.id_tipo_servicio='9' and
                gastos_t_b.fecha_cita>'1900-01-01' and
                procesos.id_admin=admin.id_admin and
                procesos.id_estado_proceso=2 and
                admin.id_sucursal='$f_admin[id_sucursal]' and
                procesos.fecha_recibido>='$fechaAnnoAnterio' and
                procesos.id_titular=titulares.id_titular and
                titulares.id_ente=entes.id_ente and
                titulares.id_cliente=clientes.id_cliente
            group by
                procesos.id_proceso,
                procesos.nu_planilla,
                procesos.id_beneficiario,
                clientes.nombres,
                clientes.apellidos,
                clientes.cedula,
                gastos_t_b.fecha_cita,
                gastos_t_b.hora_cita,
                entes.nombre
            order by
                procesos.id_proceso desc limit $lim offset $pag");
$r_procesos=ejecutar($q_procesos);

?>
<table class="tabla_cabecera5 colortable" border='0'>
<tr><td colspan=8 class="titulo_seccion">Egresos Por Emergencia </td>	</tr>
<tr>
<td colspan=1 class="tdtitulos">N </td>
<td colspan=1 class="tdtitulos">Titular </td>
<td colspan=1 class="tdtitulos">Cedula T </td>
<td colspan=1 class="tdtitulos"> Beneficiario</td>
<td colspan=1 class="tdtitulos"> Ente</td>
<td colspan=1 class="tdtitulos">Planilla </td>
<td colspan=1 class="tdtitulos">Proceso </td>
<td colspan=1 class="tdtitulos">Fecha Egreso </td>

</tr>
<?php

     while($f_procesos=asignar_a($r_procesos,NULL,PGSQL_ASSOC)){
       $pag++;
    $q_bene=("select
                clientes.nombres,
                clientes.apellidos,
                clientes.cedula
            from
                beneficiarios,
                clientes
            where
                beneficiarios.id_beneficiario=$f_procesos[id_beneficiario] and
                beneficiarios.id_cliente=clientes.id_cliente
            ");
$r_bene=ejecutar($q_bene);
$f_bene=asignar_a($r_bene);
?>

<tr>
<td colspan=1 class="tdcampos"><?php echo "$pag";?> </td>
<td colspan=1 class="tdcampos"><?php echo $f_procesos['nombres']." ".$f_procesos['apellidos']?> </td>
<td colspan=1 class="tdcampos"><?php echo $f_procesos['cedula']?> </td>
<td colspan=1 class="tdcampos"><?php echo $f_bene['nombres']." ".$f_bene['apellidos']?> </td>
<td colspan=1 class="tdcampos"> <?php echo $f_procesos['nombre']?></td>
<td colspan=1 class="tdcampos">
<?php

      $url="'views01/ipresupuestop.php?proceso=".$f_procesos['nu_planilla']."&si=1'";
      //$url="'views01/ipresupuestop.php?proceso=$f_procesos[nu_planilla]&si=1'";
      ?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Ver Registros de la Planilla" class="tdcamposr"> <?php echo $f_procesos['nu_planilla']?></a>
</td>
<td colspan=1 class="tdcamposr"><?php echo $f_procesos['id_proceso']?> </td>
<td colspan=1 class="tdcamposr"> <?php echo $f_procesos['fecha_cita']?></td>
</tr>

<?php
if ($f_p_c['permiso']==1)
{
?>
<tr>
    <td colspan=1  class="tdtitulos">Clave</td>
    <td colspan=2 class="tdcampos"><input class="campos" type="text" id="clave<?php echo $ic?>" name="clave<?php echo $ic?>"  maxlength=128 size=20 Disabled value="<?php echo $f_procesos['no_clave']?>"   ></td>
    <td class="tdtitulos">Fecha Relacion Ente Privado</td>
    <td colspan=2 class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe<?php echo $ic?>" name="dateFieldfe<?php echo $ic?>" class="campos" maxlength="10" value="<?php echo  $f_procesos[fecha_ent_pri]?>">
      <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe<?php echo $ic?>', 'yyyy-mm-dd')" title="Show popup calendar">
      <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
    </td>
    <td colspan=1 class="tdtitulos">
      <a href="#" OnClick="ir_principal2(<?php echo "'$f_procesos[nu_planilla]','$f_procesos[fecha_cita]','$f_procesos[hora_cita]','$ic'"?>);" class="boton" title="Pasar a Candidato a Pago">CP</a>
    </td>

</tr>

<?php
}
?>




<?php
} ?>
</table><!---------EGRESOS POR EMERGENCIA-->


<?php
$NumRegistros = num_filas($cont_procesos);
if($NumRegistros>0){
  if($lim>=$NumRegistros)
    {$pag=0;$numPag=0;}
    else{
      $cantpaginas=$NumRegistros/$lim;
      list($paginas, $decimal) = explode('.', $cantpaginas);
          if($decimal=='0' || $decimal=''){
            $TotaPag=$paginas;
          }else{$TotaPag=$paginas+1;}
      }

      //echo "<h1> $numPag -- pagreg $pag-- total$TotaPag actual $Nupag</h1>";
      echo "<br>";
  if($Nupag>4){$paginainicial=$Nupag-4;}
  if($Nupag<=4){$paginainicial=0;}
  if($Nupag<=0){$paginainicial=0;$actpag=0;}
  else{$actpag=$numPag;}

  $paginainicial=$paginainicial;
  $maxPag=$paginainicial+10;
  if($maxPag>$TotaPag)
    $maxPag=$TotaPag;

    ///calcular pagina Sigiente
        $SigNumPAg=$Nupag+1;
        $SigpagAnte=$pag;
      //  $SigpagAnte=$SigpagAnte+$lim;
    ///calcular pagina anterior
    $AntNumPag=$Nupag;
    $AntpagAnte=$AntNumPag*$lim;
    $AntpagAnte=$AntpagAnte-$lim;
      if($Nupag>0){
        echo "<a href='#'  OnClick='EgresosEmergencia($AntpagAnte,$AntNumPag)' class='boton' title='registros de '><< Anterior</a>";
      }
    for($h=$paginainicial;$h<$maxPag;$h++)
    {
      $actpag=$h*$lim;
      $indpag=$h+1;
      $indInicial=$actpag+1;
      $indfinal=$actpag+$lim;
      if($Nupag==$h)
        {echo "<a href='#'  class='boton_3' title='registros de $indInicial al $indfinal'>$indpag</a>";}
      else
        {echo "<a href='#' OnClick='EgresosEmergencia($actpag,$h)' class='boton' title='registros de $indInicial al $indfinal'>$indpag</a>";}
      $actpag=$actpag+$lim;
    }

    if($SigNumPAg<$TotaPag){
      echo "<a href='#'  OnClick='EgresosEmergencia($SigpagAnte,$SigNumPAg)' class='boton' title='registros de'> Sigiente>> </a>";
    }
  }
  //*/
?>
</div><!---EGRESOS-->
