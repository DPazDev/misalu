<?
sesion();

function cargarMatriprimero($matrizprin,$posi,$nomarti,$lacant,$precart,$dependenica,$idarticulo){
	//Function para cargar los elementos en la matriz//
	$actual=count($matrizprin);
	$paso=0;
	if($actual>=1){
		 for($r=0;$r<=$actual;$r++){
					  $posiblenom=$matrizprin[$r][0];
					  if($posiblenom==$nomarti){
						   $canactual=$matrizprin[$r][1];
						    $nueva=$canactual+$lacant;   
							$matrizprin[$r][1]=$nueva;
							$precioac=$matrizprin[$r][2];
							$camprecio=$nueva*$precioac;
							$matrizprin[$r][3]=$camprecio;
							$paso=1;
							break;
						  }else{
							$paso=0;
							}  
					}
		}
		if($paso==0){
        $matrizprin[$posi][0]=$nomarti;
        $matrizprin[$posi][1]=$lacant;
        $matrizprin[$posi][2]=$precart;
        $matrizprin[$posi][3]=$lacant*$precart;
        $matrizprin[$posi][4]=$dependenica;
        $matrizprin[$posi][5]=$idarticulo;
        $_SESSION['pasopedido']=$_SESSION['pasopedido']+1;
		
       }
        $_SESSION['matriz']=$matrizprin;   
	    return($matrizprin);
}

function recursiveArraySearch($haystack, $needle)
 //function que da la posicion donde esta el elemento a eliminar//
          {
			   $cuantoshay=count($haystack);
			    for($r=0;$r<=$cuantoshay;$r++){
					  $posiblenom=$haystack[$r][0];
					  if($posiblenom==$needle){
						   $laposies=$r;
						   break;   
						  }  
					}   
		   return($laposies);  
         }
		 
function borrarposicion($matrizprin,$eliminarposi){
	   $nuevamatrix=array();
	   $paso=0;   
	      for($i=0;$i<=5;$i++){
			    unset($matrizprin[$eliminarposi][$i]);  				
			  }
		$cuantoshay=count($matrizprin);
		  for($j=0;$j<=$cuantoshay;$j++){
		         $nomarti=$matrizprin[$j][0];
                  $lacant=$matrizprin[$j][1];
                  $precart=$matrizprin[$j][2];
                  $subtotal=$lacant*$precart;
		          $dependenica=$matrizprin[$j][4];
		          $idarticulo=$matrizprin[$j][5];   
			   if(!empty($nomarti)){
                    $nuevamatrix[$paso][0]=$nomarti;				
			       $nuevamatrix[$paso][1]=$lacant;
			       $nuevamatrix[$paso][2]=$precart;
			       $nuevamatrix[$paso][3]=$subtotal;
			       $nuevamatrix[$paso][4]=$dependenica;
			       $nuevamatrix[$paso][5]=$idarticulo;
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
