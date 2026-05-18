<?php

use App\Models\ContentType;

/******* BACKOFFICE ********/

/* Dashboard */
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('<i class="ki-outline ki-home text-gray-700 fs-6"></i>', route('dashboard'));
});

/* DCT */
Breadcrumbs::for('dct', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage DCT', route('dct.index'));
});

Breadcrumbs::for('dct-create', function ($trail) {
    $trail->parent('dct');
    $trail->push('Create DCT', route('dct.index'));
});

Breadcrumbs::for('dct-edit', function ($trail) {
    $trail->parent('dct');
    $trail->push('Edit DCT', route('dct.index'));
});

Breadcrumbs::for('dct-show', function ($trail) {
    $trail->parent('dct');
    $trail->push('Show DCT', route('dct.index'));
});

/* POSKO */
Breadcrumbs::for('posko', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Posko', route('posko.index'));
});

Breadcrumbs::for('posko-create', function ($trail) {
    $trail->parent('posko');
    $trail->push('Create Posko', route('posko.index'));
});

Breadcrumbs::for('posko-edit', function ($trail) {
    $trail->parent('posko');
    $trail->push('Edit Posko', route('posko.index'));
});

Breadcrumbs::for('posko-show', function ($trail) {
    $trail->parent('posko');
    $trail->push('Show Posko', route('posko.index'));
});

/* PASLON */
Breadcrumbs::for('paslon', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Paslon', route('paslon.index'));
});

Breadcrumbs::for('paslon-create', function ($trail) {
    $trail->parent('paslon');
    $trail->push('Create Paslon', route('paslon.index'));
});

Breadcrumbs::for('paslon-edit', function ($trail) {
    $trail->parent('paslon');
    $trail->push('Edit Paslon', route('paslon.index'));
});

Breadcrumbs::for('paslon-show', function ($trail) {
    $trail->parent('paslon');
    $trail->push('Show Paslon', route('paslon.index'));
});

/* Master */
Breadcrumbs::for('master-jenis-pemilihan', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Jenis Pemilihan', route('master.jenis-pemilihan.index'));
});

Breadcrumbs::for('master-jenis-pemilihan-create', function ($trail) {
    $trail->parent('master-jenis-pemilihan');
    $trail->push('Tambah Jenis Pemilihan', '#');
});

Breadcrumbs::for('master-jenis-pemilihan-edit', function ($trail) {
    $trail->parent('master-jenis-pemilihan');
    $trail->push('Ubah Jenis Pemilihan', '#');
});

/* Wilayah */
Breadcrumbs::for('master-wilayah', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Wilayah', route('master.wilayah.index'));
});

Breadcrumbs::for('master-wilayah-create', function ($trail) {
    $trail->parent('master-wilayah');
    $trail->push('Tambah Wilayah', '#');
});

Breadcrumbs::for('master-wilayah-edit', function ($trail) {
    $trail->parent('master-wilayah');
    $trail->push('Ubah Wilayah', '#');
});


/* Wilayah Satker */
Breadcrumbs::for('master-wilayah-satker', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Wilayah Satker', route('master.wilayah-satker.index'));
});

Breadcrumbs::for('master-wilayah-satker-create', function ($trail) {
    $trail->parent('master-wilayah-satker');
    $trail->push('Tambah Wilayah Satker', '#');
});

Breadcrumbs::for('master-wilayah-satker-edit', function ($trail) {
    $trail->parent('master-wilayah-satker');
    $trail->push('Ubah Wilayah Satker', '#');
});









Breadcrumbs::for('master-satker', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Satker', route('master.satker.index'));
});

Breadcrumbs::for('master-satker-create', function ($trail) {
    $trail->parent('master-satker');
    $trail->push('Tambah Satker', '#');
});

Breadcrumbs::for('master-satker-edit', function ($trail) {
    $trail->parent('master-satker');
    $trail->push('Ubah Satker', '#');
});

Breadcrumbs::for('master-tahun', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Tahun', route('master.tahun.index'));
});

Breadcrumbs::for('master-tahun-create', function ($trail) {
    $trail->parent('master-tahun');
    $trail->push('Tambah Tahun', '#');
});

Breadcrumbs::for('master-tahun-edit', function ($trail) {
    $trail->parent('master-tahun');
    $trail->push('Ubah Tahun', '#');
});

Breadcrumbs::for('master-partai', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Partai', route('master.partai.index'));
});

Breadcrumbs::for('master-partai-create', function ($trail) {
    $trail->parent('master-partai');
    $trail->push('Tambah Partai', '#');
});

Breadcrumbs::for('master-partai-edit', function ($trail) {
    $trail->parent('master-partai');
    $trail->push('Ubah Partai', '#');
});

Breadcrumbs::for('master-capres', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Capres', route('master-data.capres.index'));
});

Breadcrumbs::for('master-capres-create', function ($trail) {
    $trail->parent('master-capres');
    $trail->push('Tambah Capres', '#');
});

Breadcrumbs::for('master-capres-edit', function ($trail) {
    $trail->parent('master-capres');
    $trail->push('Ubah Capres', '#');
});

Breadcrumbs::for('master-capres-tahun', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Capres Tahun', route('master-data.capres-tahun.index'));
});

Breadcrumbs::for('master-capres-tahun-create', function ($trail) {
    $trail->parent('master-capres-tahun');
    $trail->push('Tambah Capres Tahun', '#');
});

Breadcrumbs::for('master-capres-tahun-edit', function ($trail) {
    $trail->parent('master-capres-tahun');
    $trail->push('Ubah Capres Tahun', '#');
});

Breadcrumbs::for('master-capres-partai-tahun', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Capres Partai Tahun', route('master-data.capres-partai-tahun.index'));
});

Breadcrumbs::for('master-capres-partai-tahun-create', function ($trail) {
    $trail->parent('master-capres-partai-tahun');
    $trail->push('Tambah Capres Partai Tahun', '#');
});

Breadcrumbs::for('master-capres-partai-tahun-edit', function ($trail) {
    $trail->parent('master-capres-partai-tahun');
    $trail->push('Ubah Capres Partai Tahun', '#');
});

Breadcrumbs::for('master-agama', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Agama', route('master.agama.index'));
});

Breadcrumbs::for('master-agama-create', function ($trail) {
    $trail->parent('master-agama');
    $trail->push('Tambah Agama', '#');
});

Breadcrumbs::for('master-agama-edit', function ($trail) {
    $trail->parent('master-agama');
    $trail->push('Ubah Agama', '#');
});

Breadcrumbs::for('master-pekerjaan', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Pekerjaan', route('master.pekerjaan.index'));
});

Breadcrumbs::for('master-pekerjaan-create', function ($trail) {
    $trail->parent('master-pekerjaan');
    $trail->push('Tambah Pekerjaan', '#');
});

Breadcrumbs::for('master-pekerjaan-edit', function ($trail) {
    $trail->parent('master-pekerjaan');
    $trail->push('Ubah Pekerjaan', '#');
});

Breadcrumbs::for('master-provinsi', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Provinsi', route('master.provinsi.index'));
});

Breadcrumbs::for('master-provinsi-create', function ($trail) {
    $trail->parent('master-provinsi');
    $trail->push('Tambah Provinsi', '#');
});

Breadcrumbs::for('master-provinsi-edit', function ($trail) {
    $trail->parent('master-provinsi');
    $trail->push('Ubah Provinsi', '#');
});

Breadcrumbs::for('master-kota', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Kota', route('master.kota.index'));
});

Breadcrumbs::for('master-kota-create', function ($trail) {
    $trail->parent('master-kota');
    $trail->push('Tambah Kota', '#');
});

Breadcrumbs::for('master-kota-edit', function ($trail) {
    $trail->parent('master-kota');
    $trail->push('Ubah Kota', '#');
});

Breadcrumbs::for('master-kecamatan', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Kecamatan', route('master.kecamatan.index'));
});

Breadcrumbs::for('master-kecamatan-create', function ($trail) {
    $trail->parent('master-kecamatan');
    $trail->push('Tambah Kecamatan', '#');
});

Breadcrumbs::for('master-kecamatan-edit', function ($trail) {
    $trail->parent('master-kecamatan');
    $trail->push('Ubah Kecamatan', '#');
});

Breadcrumbs::for('master-desa', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Desa', route('master.desa.index'));
});

Breadcrumbs::for('master-desa-create', function ($trail) {
    $trail->parent('master-desa');
    $trail->push('Tambah Desa', '#');
});

Breadcrumbs::for('master-desa-edit', function ($trail) {
    $trail->parent('master-desa');
    $trail->push('Ubah Desa', '#');
});

Breadcrumbs::for('master-perkara', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Master Perkara', route('master.perkara.index'));
});

/* Profile */
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', route('profile'));
});

/* Role */
Breadcrumbs::for('manage-role-role', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Role', route('manage-role.role.index'));
});

/* Permission */
Breadcrumbs::for('manage-role-permission', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Permission', route('manage-role.permission.index'));
});

/* Permission */
Breadcrumbs::for('manage-role-rolepermission', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Role Permission', route('manage-role.rolepermission.index'));
});

/* User */
Breadcrumbs::for('user', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage User', route('user.index'));
});

Breadcrumbs::for('user-create', function ($trail) {
    $trail->parent('user');
    $trail->push('Create User', route('user.index'));
});

Breadcrumbs::for('user-edit', function ($trail) {
    $trail->parent('user');
    $trail->push('Edit User', route('user.index'));
});

/* Kirka */
Breadcrumbs::for('kirka', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Kirka Capres dan Cawapres', route('kirka.index'));
});

Breadcrumbs::for('kirka-create', function ($trail) {
    $trail->parent('kirka');
    $trail->push('Tambah Kirka', route('kirka.create'));
});

/* Kirka Polling*/
Breadcrumbs::for('kirka-polling', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Kirka Polling Presentase Capres dan Cawapres', route('kirka-polling.index'));
});

Breadcrumbs::for('kirka-polling-create', function ($trail) {
    $trail->parent('kirka-polling');
    $trail->push('Tambah Kirka Polling', route('kirka-polling.create'));
});

/* REPORT */
Breadcrumbs::for('report-kegiatan-posko', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Laporan Kegiatan Posko', route('report.kegiatanPosko'));
});


/* NPHD */
Breadcrumbs::for('nphd', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('NPHD', route('nphd.index'));
});

Breadcrumbs::for('nphd-create', function ($trail) {
    $trail->parent('nphd');
    $trail->push('Tambah NPHD', '#');
});

Breadcrumbs::for('nphd-edit', function ($trail) {
    $trail->parent('nphd');
    $trail->push('Tambah NPHD', '#');
});

/* AGHT */
Breadcrumbs::for('aght', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('AGHT', route('aght.index'));
});

Breadcrumbs::for('aght-create', function ($trail) {
    $trail->parent('aght');
    $trail->push('Tambah AGHT', '#');
});

Breadcrumbs::for('aght-edit', function ($trail) {
    $trail->parent('aght');
    $trail->push('Tambah AGHT', '#');
});

Breadcrumbs::for('aght-show', function ($trail) {
    $trail->parent('aght');
    $trail->push('Detail AGHT', '#');
});

/* LOGISTIK */
Breadcrumbs::for('logistik-kpu', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Logistik KPU', route('logistik-kpu.index'));
});

Breadcrumbs::for('logistik-kpu-create', function ($trail) {
    $trail->parent('logistik-kpu');
    $trail->push('Tambah Logistik KPU', '#');
});

Breadcrumbs::for('logistik-kpu-edit', function ($trail) {
    $trail->parent('logistik-kpu');
    $trail->push('Tambah Logistik KPU', '#');
});

Breadcrumbs::for('logistik-kpu-show', function ($trail) {
    $trail->parent('logistik-kpu');
    $trail->push('Detail Logistik KPU', '#');
});

/* JALAN DAERAH */
Breadcrumbs::for('jalan-daerah', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Konektifitas Jalan Daerah', route('jalan-daerah.index'));
});

Breadcrumbs::for('jalan-daerah-create', function ($trail) {
    $trail->parent('jalan-daerah');
    $trail->push('Tambah Konektifitas Jalan Daerah', '#');
});

Breadcrumbs::for('jalan-daerah-edit', function ($trail) {
    $trail->parent('jalan-daerah');
    $trail->push('Tambah Konektifitas Jalan Daerah', '#');
});

Breadcrumbs::for('jalan-daerah-show', function ($trail) {
    $trail->parent('jalan-daerah');
    $trail->push('Detail Konektifitas Jalan Daerah', '#');
});

Breadcrumbs::for('jalan-daerah-report', function ($trail) {
    $trail->parent('jalan-daerah');
    $trail->push('Report Konektifitas Jalan Daerah', '#');
});