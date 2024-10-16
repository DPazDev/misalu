<?php
include ("../../lib/jfunciones.php");
sesion();
$CodTickera=strtoupper($_POST['numeroTiket']);
$existeceparador=strpos($CodTickera, '-');
if($existeceparador!==false){
  $CodT=explode('-',$CodTickera);
  $inicieles=$CodT[0];
  $code=$CodT[1];
  ///evaluar iniciales
  $codigoTiket=ltrim($code,'0');

}else{
  $CodT=substr($CodTickera,0,2);
  $inicieles=$CodT[0].$CodT[1];
  $code=strtoupper($CodTickera);
  $codigoTiket = ltrim($code,'ABCDFGHIJKLMNÑOPQRSTUVWXYZ0-');
  echo"codigos $codigoTiket";
}
$ConsultaTikera=("select * from tbl_tickeras where identificador='$inicieles' and nun_tikera='$codigoTiket';");
$RespTickera=ejecutar($ConsultaTikera);
$cuantos=num_filas($RespTickera);
if($cuantos<=0)
{	echo "No se encontro el numero de ticket, verifique e intente de nuevo";
}else{

	$dataticket=assoc_a($RespTickera);

	$numerotickera=$dataticket['serial_tikera'];
	$id_tickera=$dataticket['id_tickera'];
	$cantTicket=$dataticket['catidad_tike'];
	$Notaticke=$dataticket['descripcion'];
	$TituloTickera=$dataticket['titulo_tickera'];
	$EstadoTickera=$dataticket['status'];
	if($EstadoTickera==0){$statusTikera="INACTIVO"; $stilostado="style='color:#e32415;'";}
	if($EstadoTickera==1){$statusTikera="ACTIVA";$stilostado="";}
	if($EstadoTickera==2){$statusTikera="AGOTADA";$stilostado="style='color:#e32415;'";}
	$FechaVencimiento=$dataticket['fecha_vencimiento'];
	if($FechaVencimiento=='' || $FechaVencimiento==0){$FechaVencimiento='NO DEFINIDA';}
	//verificar cuanto tikes le quedan
	$ConTicket=("select * from tbl_ticket where id_tickera='$id_tickera';");
	$RespTicket=ejecutar($ConTicket);
	$cuantos=num_filas($RespTicket);
	if($cuantos<=0){
			$ticket=1;
	}else{
			$ticket=$cuantos+1;
			}

if($cuantos>=$cantTicket)
{//la tikera ha esta agotada
  if($EstadoTickera<>2){
    //actualizar el estado de la tikera
    $sqlactivarticket=("update tbl_tickeras set status='2' where id_tickera='$id_tickera';");
    $VerfTikera=ejecutar($sqlactivarticket);
    $EstadoTickera=2;
    $statusTikera="AGOTADA";$stilostado="style='color:#e32415;'";
  }
}
//echo"  numerotickera:  cantTicket:$cantTicket Notaticke=$Notaticke EstadoTickera=$EstadoTickera";


?>
<style>
			 :root{
					--card-color: rgb(169, 204, 227);
					--card-header-color: crimson;
					--card-titulo-color: #559FF0;
					--card-footer-color: #bdf0fa;
					--letras-titulo-color:#26299e;
					--letras-texto-color:#232323;
			 }

			 .card {
				 display: grid;
				 grid-template-columns: repeat(3, 1fr);
				 grid-template-rows: repeat(2, 1fr);
				 grid-column-gap: 0px;
				 grid-row-gap: 0px;
				 grid-template-columns: [first] auto [line2] auto [line3] 50px [end];
				 grid-template-rows: [row1-start] 25% [row1-end] 100px [third-line] auto [last-line];

			 }

			 .card-titulito { grid-area: 1 / 1 / 2 / 3; }
			 #numeroTicketk { grid-area: 1 / 3 / 2 / 4; }
			 .card-body { grid-area: 2 / 1 / 3 / 4; }

			.card{
					padding: 6px;
					border: 2px solid #e5e5e5;
					margin-bottom: 27px;
					}

			#numeroTicketk{
				/*#f5f5f5--*/
					background: var(--card-color) ;
					color: white;
					font-size: 18px;
					line-height: 26px;
					font-weight: bold;
					padding: 5px 5px 5px 10px;
					}
			.card-titulito{
					color: var(--letras-titulo-color);
					font-size: 18px;
					line-height: 26px;
					font-weight: bold;
					background:var(--card-titulo-color);
						}
			.card-body{
							border-radius: 4px;
							background: var(--card-footer-color);
							font-size: 18px;
            color: var(--letras-texto-color);
						}

	 </style>

<div class="card">

   <div class="card-titulito"><?php echo"$TituloTickera Numero de Tickera $numerotickera  Estado: <span id='status'  <?php echo $stilostado;?>$statusTikera";?></div>
	 <div id="numeroTicketk"><?php echo $ticket;?></div>
		<div class="card-body">
			<p>Ticket:<?php echo $ticket;?> de <?php echo $cantTicket;?></p>
			<p><?php echo $Notaticke;?> <?php echo "Fecha de vencimiento: $FechaVencimiento";?> </p>
      <input type="hidden" id='EstadoTickera' name='EstadoTickera' value="<?php echo $EstadoTickera;?>">
      <input type="hidden" id='idtickera' name='idtickera' value="<?php echo $id_tickera;?>">
    </div>

</div>

<?php
}
?>
