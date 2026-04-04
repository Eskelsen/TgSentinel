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

$tg = new TgClient(TG_TOKEN);

$msg = "Olá, <b>$name</b>! Seu nome de usuário <b>$username</b> e o seu chat_id é <b>$from_id</b>.";

$response = $tg->sendMessage($chat_id,$msg);

error_log(json_encode($response));

# --- #

$message = '{"message_id":2006,"from":{"id":8794645599,"is_bot":true,"first_name":"Sentinel","username":"Sentinel_W_bot"},"chat":{"id":-1002126236997,"title":"Uzbequist\u00e3o do Cocaia","type":"supergroup"},"date":1775278464,"text":"Ol\u00e1, Daniel! Seu nome de usu\u00e1rio u127_200 e o seu chat_id \u00e9 6152438478.","entities":[{"offset":5,"length":6,"type":"bold"},{"offset":33,"length":8,"type":"bold"},{"offset":60,"length":10,"type":"phone_number"},{"offset":60,"length":10,"type":"bold"}]}';

$data = Bin::write($from_id,$message);

var_dump($data);

$trad = Bin::read($from_id);