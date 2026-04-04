<?php

include __DIR__ . '/Crypt.php';

class Bin
{
	public static function read($token){
        $file = 'data/' . $token . '.bin';
        $key = Crypt::key($token);
		$ctn = is_file($file) ? file_get_contents($file) : false;
        $ctn = ($ctn) ? Crypt::decrypt($ctn,$key) : false;
		return ($ctn) ? json_decode($ctn, 1) : [];
	}

	public static function write($token,$data){
        $file = 'data/' . $token . '.bin';
        $key = Crypt::key($token);
		$ctn = is_string($data) ? $data : json_encode($data);
        $ctn = ($ctn) ? Crypt::encrypt($ctn,$key) : false;
		return ($ctn) ? file_put_contents($file, $ctn) : false;
	}
}
