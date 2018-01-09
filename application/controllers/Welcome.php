<?php
Class Welcome extends CI_Controller{
    
    var $channelAccessToken = 'UPE0Fid2AE/WGdpbKgZHQgX6KnZQ+c5NnxnAJgDdCn/C2wjKDypsu5+eFxQ5S80XvWT7OGEi5osZX7ASfyp9831Ft6Gmt8qeVBjn5Up/IYz3CqU2Xshh/jeDVbzMF/4f98tsVOFBlRin3/PnXHyZUQdB04t89/1O/w1cDnyilFU='; 
    var $channelSecret = '3d289fd286e3a0a3c68da71138cf042b';

    function __construct() {
        parent::__construct();
        $this->load->model('m_welcome');
    }
    
    function test() {
        var_dump($this->m_welcome->list_admin());
    }

    function push(){
        $client     = new LINEBotTiny($this->channelAccessToken, $this->channelSecret);
        $push = array(
                                    // 'to' => 'Ud31491d87b057fc48eaa9ae986f8bbc4', //tika
                                    'to' => 'U0d4965553ebeaf022e205e9056895a46', //agungdh
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                                   
                                                'text' => "test push"
                                            )
                                    )
                                );
        $client->pushMessage($push);
    }

    function index(){
        $client     = new LINEBotTiny($this->channelAccessToken, $this->channelSecret);
        $userId     = $client->parseEvents()[0]['source']['userId'];
        $replyToken = $client->parseEvents()[0]['replyToken'];
        $timestamp  = $client->parseEvents()[0]['timestamp'];
        $message    = $client->parseEvents()[0]['message'];
        $messageid  = $client->parseEvents()[0]['message']['id'];
        $profil = $client->profil($userId);
        $pesan_datang = strtolower($message['text']);
        $pesan_datang_raw = $message['text'];

        if($message['type']=='text')
        {
            if(strpos($pesan_datang, 'hal') !== false)
            {
                $pesan_hal = explode(' ', $pesan_datang);
                if (empty($pesan_hal[1])) {
                    $halaman_saat_ini = $this->m_welcome->lihat_halaman_saat_ini($userId);
                    $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => "Halaman saat ini = " . $halaman_saat_ini->halaman . "\n"
                                                            . "Waktu = " . $halaman_saat_ini->waktu
                                            )
                                    )
                                );
                } else {
                    if ($this->m_welcome->cek_jumlah_sementara($userId) == 0) {
                        $this->m_welcome->tambah_sementara($userId, $pesan_hal[1]);
                    } else {
                        $this->m_welcome->update_sementara($userId, $pesan_hal[1]);
                    }
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => 'Halaman ' . $pesan_hal[1] . ' ?'
                                            )
                                    )
                                );                    
                }
            }
            else
            if($pesan_datang == 'ya')
            {
                if ($this->m_welcome->cek_jumlah_sementara($userId) == 0) {
                        $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => 'Halaman belum diinput' . "\n" . 
                                                            'Input halaman dengan menggunakan perintah "hal {halaman}"' . "\n" . 'Contoh : hal 3'
                                            )
                                    )
                                ); 
                    } else {
                        $this->m_welcome->tambah_fix($userId);
                        $this->m_welcome->hapus_sementara($userId);
                        $halaman_saat_ini = $this->m_welcome->lihat_halaman_saat_ini($userId)->halaman;
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => 'Berhasil' . "\n" .
                                                "Halaman saat ini = " . $halaman_saat_ini
                                            )
                                    )
                                );                    
                    }
            }
            else
            if($pesan_datang == 'cekuser')
            {
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => 'UserID = ' . $userId . "\n" .
                                                            'DisplayName = ' . $profil->displayName
                                            )
                                    )
                                );                    
            }
            else
            if($pesan_datang == 'listuser')
            {
                                $orang = null;
                                foreach ($this->m_welcome->list_user() as $item) {
                                    $orang .= "Nama : ";
                                    $orang .= $client->profil($item->id_user_line)->displayName;
                                    $orang .= "\n";
                                    $orang .= "UserID : ";
                                    $orang .= $userId;
                                    $orang .= "\n";
                                    $orang .= "\n";
                                }
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => $orang
                                            )
                                    )
                                );                    
            }
            else
            if($pesan_datang_raw == 'HAPUS')
            {
                $this->m_welcome->hapus($userId);
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => 'Berhasil menghapus semua data !!!'
                                            )
                                    )
                                );                    
            }
            else
            if($pesan_datang == 'status')
            {
                if ($this->m_welcome->cek_admin($userId) == 1) {
                                $i = 1;
                                $teks = "";
                                foreach ($this->m_welcome->list_user() as $item) {
                                    $nama = $client->profil($item->id_user_line)->displayName;
                                    $halaman = $this->m_welcome->lihat_halaman_saat_ini($item->id_user_line)->halaman;
                                    $waktu = $this->m_welcome->lihat_halaman_saat_ini($item->id_user_line)->waktu;
                                    $teks .= $i . ".) " . $nama . " | " . $halaman . " | " . $waktu ."\n";
                                    $i++;
                                }
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => $teks
                                            )
                                    )
                                );                   
                } else {
                    $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => "Hanya ADMIN yang dapat menggunakan perintah ini !!!"
                                            )
                                    )
                                );   
                } 
            }
            else
            if($pesan_datang == 'listadmin')
            {
                                $i = 1;
                                $teks = "";
                                foreach ($this->m_welcome->list_admin() as $item) {
                                    $nama = $client->profil($item->id_user_line)->displayName;
                                    $teks .= $i . ".) " . $nama . "\n";
                                    $i++;
                                }
                                $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                   
                                                'text' => $teks
                                            )
                                    )
                                );                    
            }
            else{
                // $balas = array(
                //                     'replyToken' => $replyToken,                                                        
                //                     'messages' => array(
                //                         array(
                //                                 'type' => 'text',                   
                //                                 'text' => "Maaf, format penulisan salah !!!\n"
                //                             )
                //                     )
                //                 );
            }
        }else
        {   
            $balas = array(
                                    'replyToken' => $replyToken,                                                        
                                    'messages' => array(
                                        array(
                                                'type' => 'text',                                   
                                                'text' => "Maaf, hanya teks yang dapat kami proses !!!\n"
                                            )
                                    )
                                );
        }
        $client->replyMessage($balas); 
    }
    
}
?>


<?php 
if (!function_exists('hash_equals')) 
{
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));

    function hash_equals($knownString, $userString)
    {
        $strlen = function ($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }

            return strlen($string);
        };

        if (($length = $strlen($knownString)) !== $strlen($userString)) {
            return false;
        }

        $diff = 0;

        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($knownString[$i]) ^ ord($userString[$i]);
        }
        return $diff === 0;
    }
}

class LINEBotTiny
{
    public function __construct($channelAccessToken, $channelSecret)
    {
        $this->channelAccessToken = $channelAccessToken;
        $this->channelSecret = $channelSecret;
    }
    
    


    public function parseEvents()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            error_log("Method not allowed");
            exit();
        }

        $entityBody = file_get_contents('php://input');

        if (strlen($entityBody) === 0) {
            http_response_code(400);
            error_log("Missing request body");
            exit();
        }

        if (!hash_equals($this->sign($entityBody), $_SERVER['HTTP_X_LINE_SIGNATURE'])) {
            http_response_code(400);
            error_log("Invalid signature value");
            exit();
        }

        $data = json_decode($entityBody, true);
        if (!isset($data['events'])) {
            http_response_code(400);
            error_log("Invalid request body: missing events property");
            exit();
        }
        return $data['events'];
    }

    public function replyMessage($message)
    {
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->channelAccessToken,
        );

        $context = stream_context_create(array(
            "http" => array(
                "method" => "POST",
                "header" => implode("\r\n", $header),
                "content" => json_encode($message),
            ),
        ));
        $response = exec_url('https://api.line.me/v2/bot/message/reply',$this->channelAccessToken,json_encode($message));
    }
    
    public function pushMessage($message) 
    {
        
        $response = exec_url('https://api.line.me/v2/bot/message/push',$this->channelAccessToken,json_encode($message));
       
    }
    
    public function profil($userId)
    {
      
        return json_decode(exec_get('https://api.line.me/v2/bot/profile/'.$userId,$this->channelAccessToken));
       
    }

    private function sign($body)
    {
        $hash = hash_hmac('sha256', $body, $this->channelSecret, true);
        $signature = base64_encode($hash);
        return $signature;
    }
}







function exec_get($fullurl,$channelAccessToken)
{
        
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer '.$channelAccessToken,
        );

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);        
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $fullurl);
        
        $returned =  curl_exec($ch);
    
        return($returned);
}



function exec_url($fullurl,$channelAccessToken,$message)
{
        
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer '.$channelAccessToken,
        );

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $message); 
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $fullurl);
        
        $returned =  curl_exec($ch);
    
        return($returned);
}



function exec_url_aja($fullurl)
    {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_URL, $fullurl);
            
            $returned =  curl_exec($ch);
        
            return($returned);
    }
    

