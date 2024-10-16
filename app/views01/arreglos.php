<?php
sesion();

function cargarMatriprimero($matrizprin,$posi,$nomarti,$nomla,$lacant,$precart,$fechaContinuo,$idarticulo,$dep,$espc,$CantAlmacen){
	//Function para cargar los elementos en la matriz//


	$actual=count($matrizprin);
	$paso=0;
	if($actual>=1){
		 for($r=0;$r<=$actual;$r++){
					  $posiblenom=$matrizprin[$r][0];
            $Articulo=$matrizprin[$r][6];
            $almacen=$matrizprin[$r][7];
            $servicio=$matrizprin[$r][8];
						if($posiblenom==$nomarti && $Articulo==$idarticulo && $almacen==$dep){
							if($servicio==$espc){
							$canactual=$matrizprin[$r][2];
						  $nueva=$canactual+$lacant;
							if($nueva<0)
							{$nueva=0;}
							$matrizprin[$r][2]=$nueva;
							$precioac=$matrizprin[$r][3];
							$camprecio=$nueva*$precioac;
							$matrizprin[$r][4]=$camprecio;
							$CantAlmacen["$idarticulo"]=$CantAlmacen;
							$paso=1;
							break;
							}
							}else{
								$paso=0;
							}
					}
		}
		if($paso==0){
        $matrizprin[$posi][0]=$nomarti;
        $matrizprin[$posi][1]=$nomla;
        $matrizprin[$posi][2]=$lacant;
        $matrizprin[$posi][3]=$precart;
        $matrizprin[$posi][4]=$lacant*$precart;
        $matrizprin[$posi][5]=$fechaContinuo;
        $matrizprin[$posi][6]=$idarticulo;
        $matrizprin[$posi][7]=$dep;
        $matrizprin[$posi][8]=$espc;
        $matrizprin[$posi][9]=$CantAlmacen;
			     	$_SESSION['pasopedido']=$_SESSION['pasopedido']+1;
     }
		  $_SESSION['matriz']=$matrizprin;
	    return($matrizprin);
}


function recursiveArraySearch($haystack,$needle,$esp)
 { //function que da la posicion donde esta el elemento a eliminar tomando como referencia iddel articulo y al sercio que se le despacha //
         $cuantoshay=count($haystack);
			    for($r=0;$r<=$cuantoshay;$r++){
					  $idarticulo=$haystack[$r][6];//idArticulo
					  $servicio=$haystack[$r][8];//SERVIVIO ID
					  if($idarticulo==$needle){
								if($servicio==$esp){
							   $laposies=$r;
							   break;
							 		}
						  }
					}
		   return($laposies);
 }

function borrarposicion($matrizprin,$eliminarposi){
	   $nuevamatrix=array();
	   $paso=0;
	      for($i=0;$i<=9;$i++){
			    unset($matrizprin[$eliminarposi][$i]);
			  }
		$cuantoshay=count($matrizprin);
		  for($j=0;$j<=$cuantoshay;$j++){
			  $nomarti=$matrizprin[$j][0];
        $nomla=$matrizprin[$j][1];
        $lacant=$matrizprin[$j][2];
        $precart=$matrizprin[$j][3];
			  $subt=$lacant*$precart;
			  $fechaContinuo=$matrizprin[$j][5];
			  $idarticulo=$matrizprin[$j][6];
			  $dep=$matrizprin[$j][7];
			  $espc=$matrizprin[$j][8];
			  $CantAlmacen=$matrizprin[$j][9];
       if(!empty($nomarti)){
          $nuevamatrix[$paso][0]=$nomarti;
				  $nuevamatrix[$paso][1]=$nomla;
					$nuevamatrix[$paso][2]=$lacant;
					$nuevamatrix[$paso][3]=$precart;
					$nuevamatrix[$paso][4]=$subt;
					$nuevamatrix[$paso][5]=$fechaContinuo;
					$nuevamatrix[$paso][6]=$idarticulo;
					$nuevamatrix[$paso][7]=$dep;
					$nuevamatrix[$paso][8]=$espc;
					$nuevamatrix[$paso][9]=$CantAlmacen;
					$paso++;
				}
			}
		$_SESSION['matriz']=$nuevamatrix;
		$actual=count($nuevamatrix);
		if($_SESSION['pasopedido']>$actual){
			$_SESSION['pasopedido']=$_SESSION['pasopedido']-1;
			}
		return($_SESSION['matriz']);
	}


?>
