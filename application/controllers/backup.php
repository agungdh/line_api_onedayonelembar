<?php


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