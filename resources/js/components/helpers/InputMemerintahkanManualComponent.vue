<script setup>
import {computed, onMounted, ref} from "vue";
import {capitalizeFirstLetter} from "../../mixins.js";

const props = defineProps({
    classList: {
        type: String,
        required: true
    },
    name: String,
    id: String,
    isInvalid: String,
    errorMessage: String,
    value: String
});
const item = ref({
    nama: '',
    nip: '',
    pangkat: '',
    jabatan: ''
});
const dataItem = ref([]);
const tambahDataItem = () => {
    if (checkInputItem) {
        dataItem.value.push({...item.value});

        item.value.nama = '';
        item.value.nip = '';
        item.value.pangkat = '';
        item.value.jabatan = '';

        document.querySelector('#' + props.id).value = JSON.stringify(dataItem.value);
    }
};
const hapusDataItem = (key) => {
    dataItem.value.splice(key, 1);
};
const checkInputItem = computed(() => {
    return !(item.value.nama === '' || item.value.nip === '' || item.value.pangkat === '' || item.value.jabatan === '');
});

onMounted(() => {
    if (props.value) {
        dataItem.value = JSON.parse(props.value);

        document.querySelector('#' + props.id).value = JSON.stringify(dataItem.value);
    }

    $('.select').select2();
    $('.select').on('select2:select', function (e) {
        item.value = e.params.data.id instanceof Object ? e.params.data.id : JSON.parse(e.params.data.id);

        tambahDataItem();

        item.value = null;

        $('.select').val(null)
    });
});
</script>

<template>
    <div :class="props.classList">
        <label for="item"
               class="fs-6 fw-semibold mb-2 required">{{ capitalizeFirstLetter(props.name) }}</label>
        <table class="table table-sm table-striped table-bordered">
            <thead>
            <tr>
                <th colspan="3" class="text-start">
                    <div class="row mb-2">
                        <div class="col-6">
                            <input class="form-control form-control-sm" type="text" v-model="item.nama"
                                   placeholder="Nama" @keydown.enter.prevent="tambahDataItem"
                                   :class="{'is-invalid': isInvalid}">
                        </div>
                        <div class="col-6">
                            <input class="form-control form-control-sm" type="text" v-model="item.nip"
                                   placeholder="NIP" @keydown.enter.prevent="tambahDataItem"
                                   :class="{'is-invalid': isInvalid}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <input class="form-control form-control-sm" type="text" v-model="item.pangkat"
                                   placeholder="Pangkat" @keydown.enter.prevent="tambahDataItem"
                                   :class="{'is-invalid': isInvalid}">
                        </div>
                        <div class="col-6">
                            <input class="form-control form-control-sm" type="text" v-model="item.jabatan"
                                   placeholder="Jabatan" @keydown.enter.prevent="tambahDataItem"
                                   :class="{'is-invalid': isInvalid}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="d-grid">
                            <button type="button" class="btn btn-sm btn-success" @click.prevent="tambahDataItem"
                                    :disabled="!checkInputItem">
                                <span class="fas fa-add"></span>
                            </button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <input :class="{'is-invalid': isInvalid}" type="hidden" :name="props.name" :id="props.id" />
                        <span class="invalid-feedback" v-if="props.isInvalid">{{ props.errorMessage }}</span>
                    </div>
                </th>
                <!--<th class="text-start w-1px" style="vertical-align: top;">
                    <button type="button" class="btn btn-sm btn-success" @click.prevent="tambahDataItem"
                            :disabled="!item">
                        <span class="fas fa-add"></span>
                    </button>
                </th>-->
            </tr>
            <tr>
                <th class="w-1px">No.</th>
                <th>List {{ capitalizeFirstLetter(props.name) }}</th>
                <th>#</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, key) in dataItem" v-if="dataItem.length > 0">
                <td class="text-end">{{ key + 1 }}.</td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 100px;">Nama</td>
                            <td style="width: 50px;">:</td>
                            <td>{{ item.nama }}</td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>{{ item.nip }}</td>
                        </tr>
                        <tr>
                            <td>Pangkat</td>
                            <td>:</td>
                            <td>{{ item.pangkat }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ item.jabatan }}</td>
                        </tr>
                    </table>
                </td>
                <td class="w-1px">
                    <button type="button" class="btn btn-sm btn-danger" @click.prevent="hapusDataItem(key)">
                        <span class="fas fa-trash"></span>
                    </button>
                </td>
            </tr>
            <tr v-else>
                <td colspan="3" class="text-center">Belum ada item.</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
