<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {   
        return view('compareCsv');
    }

    public function uploadCsv()
    {   
        $Dados = $_FILES['filename']['tmp_name'];
        $DadosAntigos = $_FILES['filename2']['tmp_name'];
 
        //tentei usar array_map('str_getcsv', file($Dados)); mas quebrava nas ',' de identação numérica (teve que ser feito por função pra passar o delimitador)

        $arrayCsvDados =  array_map(function($v){return str_getcsv($v, ";");}, file($Dados));
        $arrayCsvDadosAntigos = array_map(function($v){return str_getcsv($v, ";");}, file($DadosAntigos));

        //numero de iterações do for
        $tamanhoMax = count($arrayCsvDados);

        //create arrays de output
        $dadosIguais = [];
        $dadosUpdated = [];
        $dadosNovos = [];


        //loop de verificação de csv linha por linha
        for ($i=0; $i < $tamanhoMax; $i++) { 
            //verifica se o numero da linha existe, se nao existir nos dados antigos, consequentemente já é uma linha nova
            if (!isset($arrayCsvDadosAntigos[$i])) {
               array_push($dadosNovos,$arrayCsvDados[$i]);

            //aqui verifica se os dados da mesma linha são completamente iguais, se forem, guarda como linhas iguais   
            } elseif($arrayCsvDados[$i] === $arrayCsvDadosAntigos[$i]) {
                array_push($dadosIguais, $arrayCsvDados[$i]);

            //caso o numero da linha existe nos dados antigos e não seja totalmente igual aos dados novos, significa que os dados foram atualizados    
            } else {
                array_push($dadosUpdated, $arrayCsvDados[$i]);
            } 
        }

        //somente um output das linhas em novos arrays caso seja preciso manipular novamente
        echo '<pre>';
        echo '<h1><b>Linhas Semelhantes =</h1></b>';
        echo '<br>';
        print_r($dadosIguais);
        echo '<h1><b>Linhas Atualizadas =</h1></b>';
        echo '<br>';
        print_r($dadosUpdated);
        echo '<br>';
        echo '<h1><b>Linhas Novas =</h1></b>';
        print_r($dadosNovos);

    }

    public function teste(){
        echo 'oi';
    }
}
