<?php

return [
    'general' => [
        'title_login' => 'Masuk',
        'title_signup' => 'Daftar',
        'title_see_all' => 'Lihat semua',
        'title_select_one' => '- Pilih -',
        'bekeen_now' => 'BeKeen Yuk',
        'product_not_exist' => 'Kategori yang kamu cari belum ada, coba lagi nanti ya',
        'how_it_work' => 'Cara Kerja BeKeen',
        'tagline' => 'Satu platfom untuk ngebekeen semua yang kamu butuhkan.',
        'my_bekeen' => 'BeKeen Ku',
        'list_my_bekeen' => 'Semua pengajuan BeKeen Kamu ada di sini',
        'login_with' => 'atau masuk dengan',
        'register_with' => 'atau daftar dengan',
        'customer' => 'Nasabah',
        'join_date' => 'Tanggal Bergabung',
        'contact' => 'Kontak',
        'address' => 'Alamat',
        'company' => 'Perusahaan',
        'see_detail' => 'Lihat detail',
        'order_not_exist' => 'Kamu belum BeKeen apa-apa nih, ayo bekeen sekarang',
        'select' => 'Pilih',
        'address_not_exist' => 'Data alamat yang kamu cari belum ada, coba menggunakan keyword lain ya',
        'search_postal_code' => 'Masukkan kode pos and tekan Enter',
        'share' => 'Bagikan',
        '404_not_found' => 'Oops! Sorry, yang kamu cari ga ada.',
        '419_expired' => 'Oops! Session expired, silahkan ke beranda',
        '500_servererror' => 'Oops! Ada suatu masalah, kami akan segera memperbaikinya',
        'send' => 'Kirim',
        'enter_validation_code' => 'Masukkan kode validasi'
    ],
    'auth' => [
        'login' => [
            'title' => 'Masuk',
            'subtitle' => 'Belum punya akun?',
            'username' => 'Username',
            'password' => 'Password',
            'forgot_password_title' => 'Lupa password?'
        ],
        'signup' => [
            'title' => 'Daftar',
            'subtitle' => 'Sudah punya akun?',
            'name' => 'Nama',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password'
        ],
        'verify_otp' => [
            'title' => 'Verifikasi OTP',
            'subtitle' => 'Masukkan kode OTP yang telah dikirim ke nomor {phone}',
            'placeholder' => 'Kode OTP'
        ]
    ],
    'landing_page' => [
        'home' => [
            'menu' => 'Beranda'
        ],
        'featured' => [
            'title' => 'Rekomendasi untuk kamu'
        ],
        'product' => [
            'menu' => 'Produk',
            'title' => 'Produk',
            'title_list_product' => 'List Produk',
            'title_all_product' => 'Semua Produk',
            'subtitle' => 'Semua produk yang kamu butuhkan ada di sini',
            'related_title' => 'Produk Terkait'
        ],
        'submission' => [
            'title' => 'Formulir Pengajuan Pembiayaan',
            'subtitle' => 'Yuk lengkapi semua data di bawah ini dengan benar biar kebutuhan kamu cepet diBekeenin',
            'modal_usaha' => [
                'title_personal_data' => 'Data Pribadi',
                'personal_data' => [
                    'name' => "Nama Lengkap",
                    'gender' => "Jenis Kelamin",
                    'gender_data' => [
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan'
                    ],
                    'legal_id' => 'NIK/No KTP',
                    'place_of_birth' => 'Tempat Lahir',
                    'date_of_birth' => 'Tanggal Lahir',
                    'married_status' => 'Status Perkawinan',
                    'phone' => 'No Telpon/Handphone/WA',
                    'email' => 'Email',
                    'address' => 'Alamat Lengkap',
                    'postal_code' => 'Kode Pos',
                    'subdistrict' => 'Desa/Kelurahan',
                    'district' => 'Kecamatan',
                    'city' => 'Kota/Kabupaten',
                    'province' => 'Provinsi',
                    'outlet_preference' => 'Preferensi Cabang'
                ],
                'title_financing_data' => 'Data Keuangan',
                'financing_data' => [
                    'job' => 'Pekerjaan',
                    'monthly_income' => 'Pendapatan Bulanan',
                    'monthly_expenses' => 'Pengeluaran Bulanan'
                ],
                'title_product_data' => 'Data Produk Pembiayaan',
                'product_data' => [
                    'category' => 'Kategori',
                    'product' => 'Produk',
                    'plafond' => 'Plafond',
                    'tenor' => 'Tenor',
                    'installment_type' => 'Pola Angsuran',
                    'interval' => 'Interval',
                    'info_installment_type' => 'Informasi Tipe Angsuran',
                    'simulation_result' => 'Hasil Simulasi'
                ]
            ],
            'agent' => [
                'title_personal_data' => 'Data Pribadi',
                'personal_data' => [
                    'name' => "Nama Lengkap",
                    'gender' => "Jenis Kelamin",
                    'gender_data' => [
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan'
                    ],
                    'legal_id' => 'NIK/No KTP',
                    'phone' => 'Phone Number',
                    'email' => 'Email',
                    'phone' => 'No Handphone',
                    'email' => 'Email',
                    'account_number' => 'Nomor Rekening',
                    'card_number' => 'Nomor Kartu ATM',
                    'pin_card_number' => 'PIN Kartu ATM'
                ],
                'title_business_data' => 'Data Bisnis',
                'business_data' => [
                    'business_type' => "Jenis Bisnis",
                    'business_name' => "Nama Bisnis / Nama Toko",
                    'business_term' => "Lama Bisnis",
                    'business_license' => "Nomor Legalitas Bisnis"
                ],
                'title_address_data' => 'Data Alamat',
                'address_data' => [
                    'address' => 'Alamat Lengkap',
                    'postal_code' => 'Kode Pos',
                    'subdistrict' => 'Desa/Kelurahan',
                    'district' => 'Kecamatan',
                    'city' => 'Kota/Kabupaten',
                    'province' => 'Provinsi',
                    'outlet_preference' => 'Preferensi Cabang'
                ],
                'validation_incorrect_account' => 'Nomor rekening yang didaftarkan tidak sesuai dengan yang terdaftar di kartu'
            ]
        ],
        'lelang' => [
            'menu' => 'e-Katalog',
            'title' => 'Info Lelang & e-Katalog',
            'title_list_lelang' => 'List Info Lelang & e-Katalog',
            'title_all_lelang' => 'Semua Info Lelang & e-Katalog'
        ],
        'simulation' => [
            'menu' => 'Simulasi',
            'title' => 'Simulasi',
            'subtitle' => 'Kamu bisa simulasiin yang mau dibekeen di sini'
        ]
    ],
    'product' => [
        'filter' => [
            'category' => 'Kategori',
            'location' => 'Lokasi & Kota',
            'company_type' => 'Jenis Vendor',
            'limit' => 'Limit'
        ],
        'order' => [
            'newest' => 'Terbaru',
            'high_price' => 'Limit Tertinggi',
            'low_price' => 'Limit Terendah'
        ]
    ],
    'profile' => [
        'label' => 'Profil',
        'update_avatar' => 'Ubah Foto Profil',
        'description' => 'Ayo update profil kamu',
        'description_personal_detail' => 'Update info pribadi dan alamat kamu.',
        'personal_detail' => 'Info Pribadi',
        'button_update_profile' => 'Ubah Profil'
    ],
    'security' => [
        'label' => 'Keamanan',
        'description' => 'Kamu bisa update password di sini.',
        'current_password' => 'Password Lama',
        'new_password' => 'Password Baru',
        'confirm_new_password' => 'Konfirmasi Password Baru',
        'button_update_password' => 'Ubah Password'
    ],
    'notif' => [
        'success_update_avatar' => 'Sukses ubah foto profil',
        'failed_update_avatar' => 'Gagal ubah foto profil',
        'success_update_profile' => 'Sukses ubah profil',
        'failed_update_profile' => 'Gagal ubah profil',
        'failed_update_password_does_not_match' => 'Gagal ubah password, password lama tidak sesuai',
        'success_update_password' => 'Sukses ubah password',
        'failed_update_password' => 'Gagal uabh password',
    ]
];