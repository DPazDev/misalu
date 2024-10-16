<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$busdepenusuario=("select admin.id_departamento from admin where id_admin=$elid;");
$repbusdepend=ejecutar($busdepenusuario);	
$datusudepn=assoc_a($repbusdepend);
$ladepnid=$datusudepn[id_departamento];
$busmensaje=("select admin.nombres,admin.apellidos,messages.fecha_creado,messages.mensaje,messages.id_departamento
  from
    admin,messages
  where
    messages.id_admin=admin.id_admin and
    messages.id_departamento=$ladepnid and
    messages.estatus=0
  order by messages.fecha_creado;");
$repumensaje=ejecutar($busmensaje);  
$cuantmensaj=num_filas($repumensaje);
if($cuantmensaj>=1){
?>
<table align="center" cellpadding=0 cellspacing=0>
   <tr>
      <td class="tdcampos"><a href="views07/mensajessis.php" title="Control de mensajeria" id="botmsj" class="boton" onclick="$('botmsj').hide();Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;">Mensajes (<?echo $cuantmensaj?>)</a></td>
   </tr>
</table>   
<?}
?>
