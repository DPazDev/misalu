<?php
function validaBusqueda($parametro)
{
	// Funcion para validar la cadena de busqueda de la lista desplegable
	if(eregi("^[a-zA-Z0-9.@ ]{2,40}$", $parametro)) return TRUE;
	else return FALSE;
}

if(isset($_POST["busqueda"]))
{
	$valor=$_POST["busqueda"];
	if(validaBusqueda($valor))
	{
                 $host = "localhost";
                 $port = "5432";
                 $user = "U_clinisalud_bd";
                 $pass = "Meridenos_05_07";
                 $dbname = "BD_ambulatorios";
                 $conex = pg_connect("host=$host port=$port user=$user password=$pass dbname=$dbname");
                 if (!$conex)
                 {
                    echo "Conexion con la BD ha fallado...";
                    exit;
                 }
		$consulta= pg_query($conex, "SELECT nombres FROM admin WHERE nombres LIKE upper(('%$valor%')) LIMIT 5;");
		
		
		$cantidad=pg_num_rows($consulta);
		if($cantidad==0)
		{
			/* 0: no se vuelve por mas resultados
			vacio: cadena a mostrar, en este caso no se muestra nada */
			echo "0&vacio";
		}
		else
		{
			if($cantidad>20) echo "1&"; 
			else echo "0&";
	
			$cantidad=1;
			while(($registro=pg_fetch_row($consulta)) && $cantidad<=20)
			{
				echo "<div onClick=\"clickLista(this);\" onMouseOver=\"mouseDentro(this);\">".$registro[0]."</div>";
				// Muestro solo 20 resultados de los 22 obtenidos
				$cantidad++;
			}
		}
	}
}
?>
