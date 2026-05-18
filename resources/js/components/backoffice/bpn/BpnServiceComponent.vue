<script setup>
import {onMounted, ref} from "vue";

const baseUrl = 'https://intranet.atrbpn.go.id';
const token = ref(null);

const props = defineProps({
    clientUsername: {
        type: String,
        required: true
    },
    clientPassword: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    sandi: {
        type: String,
        required: true
    }
});

const input = ref({
    nik: '',
    nama: '',
    tanggallahir: ''
});
const inputError = ref({
    nik: '',
    nama: '',
    tanggallahir: ''
});

const dataLoadingNik = ref(false);
const dataLoadingNama = ref(false);
const data = ref([]);

const getToken = async (id, sandi) => {
    try {
        const response = await axios.post(`/api/badan-pertanahan-nasional/login`, {
            id: id,
            sandi: sandi
        }, {
            auth: {
                username: props.clientUsername,
                password: props.clientPassword
            }
        });

        if (response.status === 200) {
            token.value = response.data;

            localStorage.setItem('bpnToken', JSON.stringify(token.value));
        }
    } catch (error) {
        console.log(error.message);
    }
};

const getDataByNik = async () => {
    dataLoadingNik.value = true;
    inputError.value.nik = '';
    data.value = [];

    try {
        const response = await axios.post(`/api/badan-pertanahan-nasional/get-data-by-nik`, {
            token: token.value.accessToken,
            nik: input.value.nik
        });

        if (response.status === 200) {
            if (Object.hasOwn(response.data, 'message')) {
                inputError.value.nik = response.data.message;
            } else {
                data.value = response.data;
            }
        }
    } catch (error) {
        console.log(error.message);
    } finally {
        dataLoadingNik.value = false;
    }
};

const getDataByNama = async () => {
    dataLoadingNama.value = true;
    inputError.value.nama = '';
    inputError.value.tanggallahir = '';
    data.value = [];

    try {
        const response = await axios.post(`/api/badan-pertanahan-nasional/get-data-by-nama`, {
            token: token.value.accessToken,
            nama: input.value.nama,
            tanggallahir: input.value.tanggallahir
        });

        if (response.status === 200) {
            if (Object.hasOwn(response.data, 'message')) {
                inputError.value.nama = response.data.message;
                inputError.value.tanggallahir = response.data.message;
            } else {
                data.value = response.data;
            }
        }
    } catch (error) {
        console.log(error.message);
    } finally {
        dataLoadingNama.value = false;
    }
};

onMounted(() => {
    console.log('BpnServiceComponent mounted.');

    getToken(props.id, props.sandi);
});
</script>

<template>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar d-flex flex-stack py-4 py-lg-8">
            <div class="d-flex flex-grow-1 flex-stack flex-wrap gap-2 mb-n10" id="kt_toolbar">
                <x-backoffice.section-header heading="Badan Pertanahan Nasional" breadcrumb="manage-role-permission"
                                             icon="fas fa-users"/>
                <div class="d-flex align-items-center w-25">
                    <x-backoffice.notification/>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div class="row" id="rowCheck">
                <div id="formCheck" class="col-12">
                    <div id="notification"></div>
                    <div class="card card-primary">
                        <div class="card-body">
                            <h6 style="color:red">Validasi Data BPN (silahkan pilih dengan metode input NIK
                                atau Nama dengan Tanggal Lahir)</h6><br>
                            <table width="100%">
                                <td width="47%">
                                    <div class="form-group">
                                        <label for="nik" class="fs-6 fw-semibold mb-2 required">Masukkan NIK</label>
                                        <input id="nik" type="text" class="form-control"
                                               :class="{'is-invalid': inputError.nik !== ''}" name="nik"
                                               placeholder="Masukkan Nomor Induk Kependudukan" autocomplete="off"
                                               v-model="input.nik" :disabled="dataLoadingNik"
                                               @keydown.enter.prevent="getDataByNik" required>
                                        <span class="invalid-feedback" v-if="inputError.nik !== ''">{{
                                                inputError.nik
                                            }}</span>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <button id="buttonProcessNik" type="button"
                                                class="btn btn-primary btn-lg btn-block" @click.prevent="getDataByNik"
                                                :disabled="dataLoadingNik">
                                            <span v-if="dataLoadingNik">
                                                <i class="spinner-border spinner-border-sm" role="status"
                                                   aria-hidden="true"></i> Mohon tunggu...
                                            </span>
                                            <span v-else>Cari</span>
                                        </button>
                                    </div>
                                </td>
                                <td width="6%">

                                </td>
                                <td width="47%">
                                    <div class="form-group">
                                        <label for="nama" class="fs-6 fw-semibold mb-2 required">Masukkan Nama</label>
                                        <input id="nama" type="text" class="form-control"
                                               :class="{'is-invalid': inputError.nama !== ''}" name="nama"
                                               placeholder="Masukkan Nama" autocomplete="off"
                                               v-model="input.nama" :disabled="dataLoadingNama"
                                               @keydown.enter.prevent="getDataByNama" required>
<!--                                        <span class="invalid-feedback" v-if="inputError.nama !== ''">{{
                                                inputError.nama
                                            }}</span>-->
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="tanggallahir" class="fs-6 fw-semibold mb-2 required">Masukkan Tanggal Lahir</label>
                                        <input id="tanggallahir" type="date" class="form-control"
                                               :class="{'is-invalid': inputError.tanggallahir !== ''}" name="tanggallahir"
                                               placeholder="Masukkan Tanggal Lahir" autocomplete="off"
                                               v-model="input.tanggallahir" :disabled="dataLoadingNama"
                                               @keydown.enter.prevent="getDataByNama" required>
                                        <span class="invalid-feedback" v-if="inputError.tanggallahir !== ''">{{
                                                inputError.tanggallahir
                                            }}</span>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <button id="buttonProcessNama" type="button"
                                                class="btn btn-primary btn-lg btn-block" @click.prevent="getDataByNama"
                                                :disabled="dataLoadingNama">
                                            <span v-if="dataLoadingNama">
                                                <i class="spinner-border spinner-border-sm" role="status"
                                                   aria-hidden="true"></i> Mohon tunggu...
                                            </span>
                                            <span v-else>Cari</span>
                                        </button>
                                    </div>
                                </td>
                            </table>
                        </div>
                        <div class="card-body" v-if="data.length > 0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped table-rounded">
                                    <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th style="vertical-align: middle;">No.</th>
                                        <th style="vertical-align: middle;">NIK</th>
                                        <th style="vertical-align: middle;">Nama Pemilik</th>
                                        <th style="vertical-align: middle;">Tgl. Lahir</th>
                                        <th style="vertical-align: middle;">No. Hak</th>
                                        <th style="vertical-align: middle;">No. SU</th>
                                        <th style="vertical-align: middle;">Tipe Hak</th>
                                        <th style="vertical-align: middle;">NIB</th>
                                        <th style="vertical-align: middle;">Luas</th>
                                        <th style="vertical-align: middle;">Provinsi</th>
                                        <th style="vertical-align: middle;">Kabupaten</th>
                                        <th style="vertical-align: middle;">Kecamatan</th>
                                        <th style="vertical-align: middle;">Desa</th>
                                        <th style="vertical-align: middle;">Status Hak</th>
                                        <th style="vertical-align: middle;">Status Kepemilikan Bersama</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, key) in data" :key="key">
                                        <td style="vertical-align: top; text-align: right;">{{ key + 1 }}.</td>
                                        <td style="vertical-align: top; text-align: center;">{{ item.nik }}</td>
                                        <td style="vertical-align: top; text-align: left;">{{ item.nama_pemilik }}</td>
                                        <td style="vertical-align: top; text-align: center;">{{
                                                item.tanggallahir
                                            }}
                                        </td>
                                        <td style="vertical-align: top; text-align: center;">{{ item.nomorhak }}</td>
                                        <td style="vertical-align: top; text-align: center;">{{ item.nomorsu }}</td>
                                        <td style="vertical-align: top; text-align: center;">{{ item.tipehak }}</td>
                                        <td style="vertical-align: top; text-align: center;">{{ item.nib }}</td>
                                        <td style="vertical-align: top; text-align: right;">{{ item.luas }}</td>
                                        <td style="vertical-align: top; text-align: left;">{{ item.namapropinsi }}</td>
                                        <td style="vertical-align: top; text-align: left;">{{ item.namakabupaten }}</td>
                                        <td style="vertical-align: top; text-align: left;">{{ item.namakecamatan }}</td>
                                        <td style="vertical-align: top; text-align: left;">{{ item.namadesa }}</td>
                                        <td style="vertical-align: top; text-align: center;">
                                            <span class="badge" :class="{'badge-success': item.statushak === 'aktif', 'badge-danger': item.statushak === 'tidak'}">
                                                {{ item.statushak }}
                                            </span>
                                        </td>
                                        <td style="vertical-align: top; text-align: center;">
                                            <span class="badge" :class="{'badge-success': item.statuskepemilikanbersama === 'ya', 'badge-danger': item.statuskepemilikanbersama === 'tidak'}">
                                                {{ item.statuskepemilikanbersama }}
                                            </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="rowResultContent">

            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="modalDetailNik">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Kependudukan</h5>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="row" id="rowResultContentNikFr">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
