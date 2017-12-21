<?php
Class Welcome extends CI_Controller{
    
    var $API ="";
    
    function __construct() {
        parent::__construct();
        // $this->API="https://apps.agungdh.com/api_mahasiswa/";
        $this->API="http://localhost/ci_api_server/";
    }
    
    function index(){
        $data['data'] = json_decode($this->curl->simple_get($this->API));
        $this->load->view('read',$data);
    }
    
    function tambah(){
    	$this->load->view('tambah');
    }

     function ubah($id){
     	// $params = array('id'=> $id);
     	$params['id'] = $id;

        $data['data'] = json_decode($this->curl->simple_get($this->API, $params));
    	$this->load->view('ubah', $data);
    }

    function aksi_tambah(){
            $data = array(
                'npm'      =>  $this->input->post('npm'),
                'nama'=>  $this->input->post('nama'));

            $insert =  $this->curl->simple_post($this->API, $data, array(CURLOPT_BUFFERSIZE => 10)); 
            // var_dump(json_decode($insert));
            // echo "<br>";
            // echo "Nama = " . json_decode($insert)->nama;
            // var_dump($insert);
            redirect(base_url());
    }
    
    function aksi_ubah(){
            $data = array(
                'npm'      =>  $this->input->post('npm'),
                'id'       =>  $this->input->post('id'),
                'nama'=>  $this->input->post('nama'));
            $update =  $this->curl->simple_put($this->API, $data, array(CURLOPT_BUFFERSIZE => 10)); 
            redirect(base_url());
    }
    
    function hapus($id){
            $delete =  $this->curl->simple_delete($this->API, array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
            redirect(base_url());
    }
}