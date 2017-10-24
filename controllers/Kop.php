<?php
/**
* 
*/
class Kop extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	public function postCURL($_url, $_param){
 
        $postData = '';
        foreach($_param as $k => $v)
        {
            $postData .= $k . '='.$v.'&';
        }
        rtrim($postData, '&');
 
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
 
        $output=curl_exec($ch);
 
        curl_close($ch);
 
        return $output;
    }
	function api_curl($url,$post,$method){
        $username="12792860";
        $password="4908773573895678";
        //////////////
        if(strtoupper($method)=='POST'){
            $postdata = http_build_query($post);
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n"
                        .'Content-Length: ' . strlen($postdata) . "\r\n",
                    'content' => $postdata
                )
            );
            if($username && $password)
            {
                $opts['http']['header'] = ("Authorization: Basic " . base64_encode("$username:$password"));
            }
 
            $context = stream_context_create($opts);
            $hasil=file_get_contents($url, false, $context);
            return $hasil;
        }else{
            foreach($post as $key=>$value){
                $isi=$isi."/".$key."/$value/";
            }
            $url=$url.$isi;
            $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
            #echo "<p>$url</p>";
            $hasil=file_get_contents($url, false, $context);
            return $hasil;
        }
    }
    function test(){

    	error_reporting(0);
    	$unit_id = 'UN02006'; //SAINTEK
		$kd_penandatangan_surat = '76'; #Kepala Bagian Tata Usaha
		$kode_klasifikasi = 'KM.00.4'; #'LAPORAN STATUS MAHASISWA', untuk status aktif studi
		#$kode_klasifikasi = 'KS.02'; #'KETATAUSAHAAN', #penggantian ktm

		$kode_jenis_surat = '11'; #'SURAT KETERANGAN'
		
		$data_sk['UNIT_ID'] 				= $unit_id;
		$data_sk['KD_STATUS_SIMPAN'] 		= 2;
		$data_sk['ID_PSD'] 					= $kd_penandatangan_surat;
		$data_sk['ID_KLASIFIKASI_SURAT'] 	= $kode_klasifikasi;
		$data_sk['KD_JENIS_SURAT'] 			= $kode_jenis_surat;
		$data_sk['KD_KEAMANAN_SURAT']		= 'B';
		$data_sk['TGL_SURAT'] 				= date('d/m/Y');
		
		
		$api_url = 'http://service2.uin-suka.ac.id/servtnde/tnde_public/tnde_surat_keluar/penomoran/json';
		
		$parameter	= array('api_kode' 		=> 90002, 
						'api_subkode' 	=> 3, 
						'api_search' 	=> array($data_sk));

		$cekdoang	= $this->api_curl($api_url, $parameter, "POST");
		echo json_encode($cekdoang);
    }
}