<?php

# Telegram Sentinel

/* 
 * Planejamento de escopo do projeto
 * 
 * Configuração do bot
 * - Instalação do token > configuração de webhooks
 * - Tratamento de retorno de configuração webhook
 * 
 * Registro de usuário
 * - Persistência e atualização
 * - Gerenciamento de usuário
 * - Comandos primitivos
*/

include __DIR__ . '/env.php';
include __DIR__ . '/config.php';
include __DIR__ . '/Bin.php';
include __DIR__ . '/TgClient.php';

$data = TgClient::treatWebhook();

if (empty($data['chat_id'])) {
    error_log('Sem chat_id');
    return;
}

extract($data);

// read related data

// treatment code (!)
// msg fron treatment? token from treatment? [many bots]

$tg = new TgClient(TG_TOKEN);
    $msg = "Olá, <b>$name</b>! Seu nome de usuário <b>$username</b> e o seu chat_id é <b>$from_id</b>.";
$response = $tg->sendMessage($chat_id,$msg);
error_log(json_encode($response));

# --- #

$hashfile = hash('sha1',$from_id); // na verdade, virá do webhook

$ok = Bin::write($hashfile,$data);

var_dump($ok);
