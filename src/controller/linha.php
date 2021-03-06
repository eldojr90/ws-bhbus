<?php

require_once '../../vendor/autoload.php';

use App\Model\Linha,
    App\Model\DAO\LinhaDAO,
    App\Util\JSON;

$array_retorno;

if(isset($_GET["code"]) && isset($_GET["org"]) && isset($_GET["dest"])){

    $code = $_GET["code"];
    $org = $_GET["org"];
    $dest = $_GET["dest"];

    $ld = new linhaDAO();

    if(!$ld->verificaLinha($code)){

        $l = new linha(null,$code,$org,$dest);

        if($ld->insert($l)){

            $id = $ld->findIdLinha($code);

            $array_retorno = ["id"=>$id];

        }else{
            
            $array_retorno = ["mensagem"=>"Erro na inserção de nova Linha."];    

        }
        

    }else{
        
        $array_retorno = ["mensagem"=>"Linha já existente!"];    

    }

}else{

    $array_retorno = ["mensagem"=>"Informe corretamente os campos code, org, dest. (Método GET)"];

}

echo (new JSON)->getJSON($array_retorno);