<?php

namespace Classes\Utility;

class LineBotPush
{
    /**
     * メッセージ送信
     *
     * @param array $message
     * @return void
     */
    static public function push($message){

        // ヘッダーの作成
        $headers = array('Content-Type: application/json',
        'Authorization: Bearer ' . ACCESS_TOKEN);

        $body = json_encode(array('to' => GROUP_ID,
            'messages'   => array($message)));  // 複数送る場合は、array($mesg1,$mesg2) とする。

        // 送り出し用
        $options = array(CURLOPT_URL            => PUSH_URL,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => $body);
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        curl_exec($curl);
        curl_close($curl);

    }
}