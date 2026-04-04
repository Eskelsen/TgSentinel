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
include __DIR__ . '/TgClient.php';

$data = TgClient::treatWebhook();

// error_log(json_encode($data));

if (empty($data['chat_id'])) {
    error_log('Sem chat_id');
}

extract($data);

$tg = new TgClient(TG_TOKEN);

$msg = "Olá, $data! Seu sobrenome é $surname, nome de usuário $username e o seu chat_id é $chat_id";

$response = $tg->sendMessage($chat_id,$msg);

error_log(json_encode($response));
