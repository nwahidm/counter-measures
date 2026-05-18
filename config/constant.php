<?php

return [
    'bsre_api' => env('BSRE_API', 'http://43.231.129.85:3000'),
    'bsre_client_id' => env('BSRE_CLIENT_ID', 'admin'),
    'bsre_client_secret' => env('BSRE_CLIENT_SECRET', 'qwerty'),

    'portal_asset_url' => env('PORTAL_ASSET_URL', 'https://log-dev.kejaksaanri.id'),
    'metabase' => [
        'url' => env('METABASE_URL', 'https://inteliz-dashboard.kejaksaanri.id/'),
        'secret' => env('METABASE_SECRET', ''),
        'url_general' => env('METABASE_URL_GENERAL', '3'),
        'url_belum_input_posko' => env('METABASE_URL_BELUM_INPUT_POSKO', '4'),
        'url_kirka' => env('METABASE_URL_KIRKA', '5'),
        'url_kegiatan_posko' => env('METABASE_URL_KEGIATAN_POSKO', '6'),
        'url_dct' => env('METABASE_URL_DCT', '7'),
        'url_nphd' => env('METABASE_URL_NPHD', '8'),
        'url_aght' => env('METABASE_URL_AGHT', '9'),
        'url_jaga_desa' => env('METABASE_URL_JAGA_DESA', '10'),
        'url_dpt' => env('METABASE_URL_DPT', '13'),
        'url_kegiatan_posko_detail' => env('METABASE_URL_KEGIATAN_POSKO_DETAIL', '14'),
        'url_logistik_kpu' => env('METABASE_URL_LOGISTIK_KPU', '11'),
        'url_jalan_daerah' => env('METABASE_URL_JALAN_DAERAH', '12'),
        'url_polling' => env('METABASE_URL_POLLING', '44'),
        'perkara_pemilu' => env('METABASE_URL_PERKARA_PEMILU', '56'),
        'perkara_pidum' => env('METABASE_URL_PERKARA_PIDUM', '58'),
        'quick_count' => env('METABASE_QUICK_COUNT', '61'),
    ],
    'storage' => [
        'url' => env('STORAGE_URL', ''),
        'token' => env('STORAGE_TOKEN', '')
    ],
    'sentiment' => [
        'url' => env('SENTIMENT_URL', '')
    ],

    'kinerja_sipede' => [
        'base_url' => env('MYSIMKARI_URL', 'https://api-dev.kejaksaan.go.id/mysimkariv2'),
        'event_create' => env('MYSIMKARI_URL_CREATE_EVENT', '/api/v2/ekinerja/ekinerja'),
        'event_surat_masuk' => env('MYSIMKARI_EVENT_SURAT_MASUK', 'SPD_SRT_MSK'),
        'event_disposisi_surat_masuk' => env('MYSIMKARI_EVENT_DISPOSISI_SURAT_MASUK', 'SPD_DISPO_SRT_MSK'),
        'event_surat_keluar' => env('MYSIMKARI_EVENT_SURAT_KELUAR', 'SPD_SRT_KLR'),
        'event_surat_pribadi_masuk' => env('MYSIMKARI_EVENT_SURAT_PRIBADI_MASUK', 'SPD_SRT_PRBD_MSK'),
        'event_surat_pribadi_keluar' => env('MYSIMKARI_EVENT_SURAT_PRIBADI_KELUAR', 'SPD_SRT_PRBD_KLR'),
        'event_registrasi_esign' => env('MYSIMKARI_EVENT_ESIGN', 'SPD_ESIGN'),
        'event_data_perkara' => env('MYSIMKARI_EVENT_PERKARA', 'SPD_PERKARA')
    ]
];
