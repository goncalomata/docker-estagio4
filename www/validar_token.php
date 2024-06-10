<?php

function validartoken(){
    $token = $_COOKIE['token'];

    $token_array = explode('.', $token);
    $header = $token_array[0];
    $payload = $token_array[1];
    $signature = $token_array[2];

    $chave = "GFACCPM1L4NM4T4K3NDR1CKYE";
    $validar_assinatura = hash_hmac('sha256', "$header.$payload", $chave, true);

    $validar_assinatura = base64_encode($validar_assinatura);

    if($signature == $validar_assinatura){
        $dados_token = base64_decode($payload);
        
        $dados_token = json_decode($dados_token);

        if($dados_token->exp > time()){
            return true;
        }else{
            return false;
        }
        return true;
    } else {
        return false;
    }

    return true;
}