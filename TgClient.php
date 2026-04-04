<?php

class TgClient
{
    const URL_BASE = 'https://api.telegram.org/bot';

    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function sendMessage($chatId, string $message)
    {
        return $this->request('sendMessage', [
            'chat_id' => $chatId,
            'text'    => $message
        ]);
    }

    public function setWebhook(string $webhookUrl)
    {
        return $this->request('setWebhook', [
            'url' => $webhookUrl
        ]);
    }

    public static function treatWebhook()
    {
        $content = file_get_contents('php://input');
        $data = json_decode($content, true);

        if (empty($data)) {
            error_log('TgClient.treatWebhook: ' . $content);
            return false;
        }

        $message = $data['message'] ?? [];

        $chatId = $message['chat']['id'] ?? null;
        if (empty($chatId)) {
            return false;
        }

        $treated = [
            'chat_id'  => $chatId,
            'msg_id'   => $message['message_id'] ?? null,
            'from_id'  => $message['from']['id'] ?? null,
            'is_bot'   => $message['from']['is_bot'] ?? null,
            'name'     => $message['from']['first_name'] ?? null,
            'surname'  => $message['from']['last_name'] ?? null,
            'username' => $message['from']['username'] ?? null,
            'title'    => $message['chat']['title'] ?? null,
            'type'     => $message['chat']['type'] ?? null,
            'date'     => $message['date'] ?? null,
        ];

        if (!empty($message['text'])) {
            $treated['message'] = $message['text'];
            $treated['message_type'] = 'text';
        } elseif (!empty($message['photo'])) {
            $treated['file_json'] = $message['photo'];
            $treated['message_type'] = 'photo';
        } elseif (!empty($message['voice'])) {
            $treated['file_json'] = $message['voice'];
            $treated['message_type'] = 'voice';
        } elseif (!empty($message['video_note'])) {
            $treated['file_json'] = $message['video_note'];
            $treated['message_type'] = 'video_note';
        } else {
            $treated['message'] = "I don't know";
            $treated['message_type'] = 'unknown';
        }

        return $treated;
    }

    private function request(string $resource, array $data = [])
    {
        $url = self::URL_BASE . $this->token . '/' . $resource;

        $data = is_string($data) ? $data : json_encode($data);

        $curl = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ];
        
        curl_setopt_array($curl, [
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      => $data,
            CURLOPT_HTTPHEADER      => $headers
        ]);
        
        $raw = curl_exec($curl);

        $e = curl_error($curl);
        
        curl_close($curl);
        
        if ($e) {
            error_log('TgClient.request: ' . $e);
            return false;
        }

        $response = json_decode($raw, true);

        return $response['result'] ?? false;
    }
}
