<?php
Class Welcome extends CI_Controller{
    
    var $channelAccessToken; 
    var $channelSecret;

    function __construct() {
        parent::__construct();
        $this->load->model('m_welcome');

        $this->channelAccessToken = 'UPE0Fid2AE/WGdpbKgZHQgX6KnZQ+c5NnxnAJgDdCn/C2wjKDypsu5+eFxQ5S80XvWT7OGEi5osZX7ASfyp9831Ft6Gmt8qeVBjn5Up/IYz3CqU2Xshh/jeDVbzMF/4f98tsVOFBlRin3/PnXHyZUQdB04t89/1O/w1cDnyilFU='; 
        $this->channelSecret = '3d289fd286e3a0a3c68da71138cf042b';
        date_default_timezone_set("Asia/Jakarta");
    }
    
    function test() {
        echo date('Y-m-d H:i:s');
    }

    function push() {
        $client     = new LINEBotTiny($this->channelAccessToken, $this->channelSecret);
        foreach ($this->m_welcome->list_user() as $item) {
            $profil = $client->profil($item->id_line);
            
            $push['to'] = $item->id_line;
            $push['messages'][0]['type'] = 'text';
            $push['messages'][0]['text'] = "test push ke " . $profil->displayName;

            $client->pushMessage($push);
        }
    }

    function push_cron() {
        $client     = new LINEBotTiny($this->channelAccessToken, $this->channelSecret);
        foreach ($this->m_welcome->list_user() as $item) {
            $profil = $client->profil($item->id_line);
            $push['to'] = $item->id_line;
            $push['messages'][0]['type'] = 'text';
            $push['messages'][0]['text'] = "";
            
            $id_user = $this->m_welcome->cek_daftar($item->id_line);
            $halaman_saat_ini = $this->m_welcome->lihat_halaman_saat_ini($id_user);
            $status_ngaji = $this->m_welcome->status_ngaji($id_user);
            $target = $this->m_welcome->lihat_halaman_kemaren($id_user)->halaman + 2;
            if ($status_ngaji == null || $halaman_saat_ini->halaman < $target) {
                $push['messages'][0]['text'] .= "Hari Ini Anda Belum Ngaji !!!\n";
                $push['messages'][0]['text'] .= "Target Ngaji Hari Ini Minimal Sampai Halaman " . $target . "\n";
                $push['messages'][0]['text'] .= "Halaman saat ini = " . $halaman_saat_ini->halaman . "\n";
                $push['messages'][0]['text'] .= "Waktu = " . $halaman_saat_ini->waktu;
                $client->pushMessage($push);
            }

        }
    }

    function index() {
        $client     = new LINEBotTiny($this->channelAccessToken, $this->channelSecret);
        $userId     = $client->parseEvents()[0]['source']['userId'];
        $replyToken = $client->parseEvents()[0]['replyToken'];
        $timestamp  = $client->parseEvents()[0]['timestamp'];
        $message    = $client->parseEvents()[0]['message'];
        $messageid  = $client->parseEvents()[0]['message']['id'];
        $profil = $client->profil($userId);
        $pesan_datang = strtolower($message['text']);
        $pesan_datang_raw = $message['text'];
        $id_user = $this->m_welcome->cek_daftar($userId);
        $halaman_saat_ini = $this->m_welcome->lihat_halaman_saat_ini($id_user);
        $reply['replyToken'] = $replyToken;
        $reply['messages'][0]['type'] = 'text';

        if($message['type']=='text') {
            if ($pesan_datang == 'daftar') { //fungsi daftar user
                $reply['messages'][1]['type'] = 'text';
                $cek_db = $this->m_welcome->cek_daftar($userId);

                if ($cek_db != null) {
                    $reply['messages'][0]['text'] = "Anda Sudah Terdaftar !!!";
                    $reply['messages'][1]['text'] .= "ID User Database = " . $cek_db;
                    $reply['messages'][1]['text'] .= "\n";
                    $reply['messages'][1]['text'] .= "ID User Line = " . $userId;
                } else {
                    $daftar = $this->m_welcome->daftar($userId, date('Y-m-d H:i:s'));
                    $reply['messages'][0]['text'] = "Berhasil Daftar";
                    $reply['messages'][1]['text'] .= "ID User Database = " . $daftar;
                    $reply['messages'][1]['text'] .= "\n";
                    $reply['messages'][1]['text'] .= "ID User Line = " . $userId;
                }
            } elseif ($pesan_datang == 'keluar') { //fungsi keluar (hapus user)
                $cek_db = $this->m_welcome->cek_daftar($userId);
                
                if ($cek_db != null) {
                    if ($this->m_welcome->delete_sementara($id_user)) {
                        if ($this->m_welcome->delete_fix($id_user)) {
                            if ($this->m_welcome->keluar($id_user)) {
                                $reply['messages'][0]['text'] = "Berhasil Keluar";
                            }
                        }                    
                    }
                } else {
                    $reply['messages'][0]['text'] = "Anda Belum Terdaftar !!!";
                }
            } elseif (strpos($pesan_datang, 'hal') !== false) {
                $pesan_hal = explode(' ', $pesan_datang);

                if ($id_user == null) {
                    $reply['messages'][0]['text'] = "Anda Belum Terdaftar !!!";
                } else {
                    if (empty($pesan_hal[1])) {
                        if ($halaman_saat_ini == null) {
                            $reply['messages'][0]['text'] = "Anda Belum Menginput Halaman Terakhir !!!";
                        } else {
                            $reply['messages'][0]['text'] = "";
                            $status_ngaji = $this->m_welcome->status_ngaji($id_user);
                            $target = $this->m_welcome->lihat_halaman_kemaren($id_user)->halaman + 2;
                            if ($status_ngaji == null || $halaman_saat_ini->halaman < $target) {
                                $reply['messages'][0]['text'] .= "Hari Ini Anda Belum Ngaji !!!\n";
                                $reply['messages'][0]['text'] .= "Target Ngaji Hari Ini Minimal Sampai Halaman " . $target . "\n";
                            }
                            $reply['messages'][0]['text'] .= "Halaman saat ini = " . $halaman_saat_ini->halaman . "\n";
                            $reply['messages'][0]['text'] .= "Waktu = " . $halaman_saat_ini->waktu;
                        }
                    } else {
                        if ($this->m_welcome->cek_jumlah_sementara($id_user) == 0) {
                            $this->m_welcome->tambah_sementara($id_user, $pesan_hal[1]);
                        } else {
                            $this->m_welcome->update_sementara($id_user, $pesan_hal[1]);
                        }
                        $halaman_sementara = $this->m_welcome->lihat_halaman_sementara($id_user);
                        $reply['messages'][0]['text'] = 'Halaman ' . $halaman_sementara->halaman . ' ?';
                    }
                }
            } elseif ($pesan_datang == 'ya') {
               if ($id_user == null) {
                    $reply['messages'][0]['text'] = "Anda Belum Terdaftar !!!";
                } else {
                    if ($this->m_welcome->cek_jumlah_sementara($id_user) == 0) {
                        $reply['messages'][0]['text'] = 'Halaman belum diinput' . "\n";
                        $reply['messages'][0]['text'] .= 'Input halaman dengan menggunakan perintah "hal {halaman}"';
                        $reply['messages'][0]['text'] .= "\n" . 'Contoh : hal 3';
                    } else {
                        $this->m_welcome->tambah_fix($id_user, date('Y-m-d H:i:s'));
                        $this->m_welcome->hapus_sementara($id_user);
                        $halaman_saat_ini = $this->m_welcome->lihat_halaman_saat_ini($id_user)->halaman;
                        $reply['messages'][0]['text'] = 'Berhasil' . "\n" . "Halaman saat ini = " . $halaman_saat_ini;                    
                    }  
                } 
            } elseif ($pesan_datang == 'cekuser') {
                $reply['messages'][0]['text'] = 'UserID = ' . $userId . "\n" . 'DisplayName = ' . $profil->displayName;
            } elseif ($pesan_datang == 'listuser') {
                $i = 0;
                $reply['messages'][0]['text'] = "Data User Yang Tergabung\n";   
                foreach ($this->m_welcome->list_user() as $item) {
                    $i++;
                    $profil = $client->profil($item->id_line);
                    $reply['messages'][0]['text'] .= $i . ") " . $profil->displayName . "\n";   
                }
            } elseif ($pesan_datang == 'listadmin') {
                $i = 0;
                $reply['messages'][0]['text'] = "Data Admin\n";   
                foreach ($this->m_welcome->list_admin() as $item) {
                    $i++;
                    $profil = $client->profil($item->id_line);
                    $reply['messages'][0]['text'] .= $i . ") " . $profil->displayName . "\n";   
                }
            }
        } else{
            $reply['messages'][0]['text'] = "Maaf, hanya teks yang dapat kami proses !!!";
        }   

        $client->replyMessage($reply);    
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
    

