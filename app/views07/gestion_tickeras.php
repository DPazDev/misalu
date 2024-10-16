<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");





?>
<style>
.grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr;

}
.grid-item1 {
  text-align: center;
  grid-column-start: 1;
  grid-column-end: 6;
  }
  #GestionTickerasPrincipal div {
  text-align: center;
  }

</style>
<DIV id='GestionTickerasPrincipal' class="grid">
  <div id='TitulosTickeraGestion' class="grid-item1 titulo_seccion">Guestionar Tickeras</div>
  <div id='registroTickeras' class="tdtitulos"><label fron="generarticket"><span title="Generar Ticket"  class="boton" style="cursor:pointer" onclick="CrearTickeras()" >CREAR TICKERAS</span></label></div>
  <div id='activacionTickera' class="tdtitulos"><label fron="generarticket"><span title="Activar Ticket" class="boton" style="cursor:pointer" onclick="Tickerasbuscar()">ACTIVAR TICKERA</span></label></div>
</DIV>
<div id='RegistroTickeras'><div>
<div id='RegistroTickerasCompleta'><div>
