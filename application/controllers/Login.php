<?php
Class Login extends CI_Controller{
    
    var $API ="";
    
    function __construct() {
        parent::__construct();
        // $this->API="https://apps.agungdh.com/api_mahasiswa/";
        $this->API="http://localhost/ci_api_server/login";
    }
    
    function index(){
        $data['data'] = json_decode($this->curl->simple_get($this->API));
        // var_dump($data['data']);
        if ($data['data'] == null) {
            $this->load->view('login/index_0',$data);
        } else {
            $this->load->view('login/index_1',$data);
        }
    }

    function aksi(){
        $data = array(
            'username'      =>  $this->input->post('username'),
            'password'=>  hash('sha512',$this->input->post('password'))
        );

        $send =  $this->curl->simple_post($this->API, $data, array(CURLOPT_BUFFERSIZE => 10)); 

        // var_dump($send);
    }
    
}