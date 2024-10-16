<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();
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
              border-radius: 4px;
              background: var(--card-footer-color);
              font-size: 18px;
              color: var(--letras-texto-color);
            }

   </style>
<?php
////ACTIVAR UNA TICKERA
///CODIGO
$CodTickera=$_POST['CodTickera'];
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

}

$verificaTikera=("select * from tbl_tickeras where identificador='$inicieles' and nun_tikera='$codigoTiket';");
$VerfTikera=ejecutar($verificaTikera);
$regencontados=num_filas($VerfTikera);
if($regencontados>0)
  {
    while($Tick=asignar_a($VerfTikera,NULL,PGSQL_ASSOC)){
        $id_tickera=$Tick['id_tickera'];
        $TituloTickera=$Tick['titulo_tickera'];
        $SerialTickera=$Tick['serial_tikera'];
        $CantTikeras=$Tick['catidad_tike'];
        $descricion=$Tick['descripcion'];
        $EstadoTickera=$Tick['status'];
      	if($EstadoTickera==0){$statusTikera="INACTIVO";}
      	if($EstadoTickera==1){$statusTikera="ACTIVA";}
      	if($EstadoTickera==2){$statusTikera="AGOTADA";}
      ?>
      <DIV id='tikeras' class="card">
          <div class="card-titulito" id='TituloTicket'><?php echo"$TituloTickera Numero de Tickera $SerialTickera  Estado: $statusTikera";?></div>
          <div id="numeroTicketk"><?php echo $ticket;?></div>
          <div id='serial' class="card-body">
            <div id='cantidad'>Cantidad <?php echo $CantTikeras;?></div>
            <div id='descricion'><?php echo $descricion; ?></div>
            <div id='status'>Estado de la tickera:<?php echo $statusTikera; ?></div>
            <input type="hidden"  id="idtickera" name="idtickera" value="<?php echo $id_tickera;?>" >
          </div>

      </DIV>
        <?php
      }
      $comisionados=("select comisionados.id_comisionado,comisionados.id_admin,comisionados.nombres,comisionados.apellidos
                      from comisionados order by comisionados.nombres;");
      $regcomicionados=ejecutar($comisionados);


if($EstadoTickera==0 )///si tikera se encuentra INACTIVA
{      ?>

      <div id='SelecVendedores' class="tdcampos card-titulito">Vendedores
            <select id="comisionado"  >
              <option  id='0' value='0' data-value="Vendedor" > Seleccione Vendedor</option>

                <?php  while($comision=asignar_a($regcomicionados,NULL,PGSQL_ASSOC)){?>
                    <option  id='<?php echo $comision[id_comisionado]?>' value='<?php echo $comision[id_comisionado]?>' data-value="<?php echo $comision[id_comisionado]?>" > <?php echo "$comision[nombres] $comision[apellidos]"?></option>
                     <?php
                }  ?>
            </select>
      </div>

          <div id="Datos del cliente" class="tdcampos"><label fron="TikeraTitulo">Cedula del Cliente</label><input type="text"  id="cedulacliente" name="cedulacliente" values=""  onchange="regClientetikera()"></div>
      <DIV id='RegistroDatosCliente'></div>

   <?php
 }else{ ?>
   <div id='SelecVendedores' class="tdcampos card-titulito">
         <div> Esta tickera ya se encuentra asignada y activa</div>
   </div>

   <?php
    }///tikera esta inactiva
  }

?>
