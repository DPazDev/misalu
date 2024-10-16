<?
sesion();
function cargarMatriprimero($matrizprin,$posi,$paren,$des,$eda1,$eda2,$anual,$semes,$trmi,$mensu){
	//Function para cargar los elementos en la matriz//

        $matrizprin[$posi][0]=$paren;
        $matrizprin[$posi][1]=$des;
        $matrizprin[$posi][2]=$eda1;
        $matrizprin[$posi][3]=$eda2;
        $matrizprin[$posi][4]=number_format($anual, 2, '.', '');
        $matrizprin[$posi][5]=number_format($semes, 2, '.', '');
        $matrizprin[$posi][6]=number_format($trmi, 2, '.', '');
        $matrizprin[$posi][7]=number_format($mensu, 2, '.', '');
        $_SESSION['pasopedido1']=$_SESSION['pasopedido1']+1;
        $_SESSION['matriz1']=$matrizprin;
	    return($matrizprin);
}
?>
