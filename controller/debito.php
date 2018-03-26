<?php

require_once '../dao/debitoDAO.php';
require_once '../model/debito.php';
require_once '../util/json.php';
require_once '../dao/passageiroDAO.php';
require_once '../dao/linhaDAO.php';

$array_retorno;

if(isset($_GET["token"]) && isset($_GET["codLin"]) && isset($_GET["val"])){

    $dd = new debitoDAO();
    $pd = new passageiroDAO();
    $ld = new linhaDAO();

    $token = $_GET["token"];
    $codLin = $_GET["codLin"];
    $val = $_GET["val"];
    
    $cardId = $pd->findIdByToken($token);

    if($dd->validaLimite($cardId)){

        if($dd->validaDesconto($cardId,$codLin)){

            $val = $val * 0.5;

        }

        $d = new debito(null,$cardId, $codLin, $val,null);

        if($ld->verificaLinha($codLin)){

            if($dd->insert($d)){

                $id = $dd->InsertIdLast();

                $array_retorno = ["id"=>$id];

            }else{
                
                $array_retorno = ["mensagem"=>"Erro na inserção do débito."];    

            }
        
        }else{

            $array_retorno = ["mensagem"=>"Linha inexistente."];    

        }    
    
    }else{

        $array_retorno = ["mensagem"=>"Limite de passagens/dia atingido!"];

    }

}else{

    $array_retorno = ["mensagem"=>"Informe corretamente os campos token, codLin e val. (Método GET)"];

}

echo getJSON($array_retorno);