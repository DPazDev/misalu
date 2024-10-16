<?
sesion();
  
function cargarMatriprimero($matrizprin,$posi,$lapropiedad,$elmontopoli,$ladescripol,$idpoliza){
	//Function para cargar los elementos en la matriz//
	
        $matrizprin[$posi][0]=$lapropiedad;
        $matrizprin[$posi][1]=$elmontopoli;
        $matrizprin[$posi][2]=$ladescripol;
        $matrizprin[$posi][3]=$idpoliza;
        $_SESSION['pasopedido']=$_SESSION['pasopedido']+1;	
        $_SESSION['matriz']=$matrizprin;   
	    return($matrizprin);
}  
?>