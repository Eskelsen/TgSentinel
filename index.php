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

error_log(json_encode($data));
