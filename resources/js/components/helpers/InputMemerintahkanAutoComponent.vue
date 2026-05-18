<script setup>
import {onBeforeMount, onMounted, ref} from "vue";
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
    value: String,
    kodeSatker: String
});
const item = defineModel('item', {default: null});
const dataItem = ref([]);
const dataUserList = ref([]);

const tambahDataItem = () => {
    if (item) {
        dataItem.value.push(item.value);
        item.value = null;

        document.querySelector('#' + props.id).value = JSON.stringify(dataItem.value);
    }
};

const hapusDataItem = (key) => {
    dataItem.value.splice(key, 1);
};

const getUserList = async (kodeSatker) => {
    try {
        let url = null;

        if (kodeSatker) {
            url = `/user-list?kode_satker=${kodeSatker}`;
        } else {
            url = `/user-list`;
        }

        const response = await axios.get(url);

        if (response.status === 200) {
            dataUserList.value = response.data;
        }
    } catch (error) {
        console.log(error.message);
    }
};

onBeforeMount(() => {
    getUserList(props.kodeSatker);

    if (props.value) {
        dataItem.value = JSON.parse(props.value);

        document.querySelector('#' + props.id).value = JSON.stringify(dataItem.value);
    }
});

onMounted(() => {
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
                    <select
                        class="form-select form-select-solid select form-select-sm"
                        :class="{'is-invalid': props.isInvalid}" data-control="select2" data-hide-search="true"
                        required="required" v-model="item">
                        <option :value="null">---Pilih Untuk Memerintahkan---</option>
                        <option :value="JSON.stringify(item)" v-for="(item, key) in dataUserList" :key="key">
                            {{ item.jabatan }} - {{ item.name }}
                        </option>
                    </select>
                    <input type="hidden" :name="props.name" :id="props.id"/>
                    <span class="invalid-feedback" v-if="props.isInvalid">{{ props.errorMessage }}</span>
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
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ item.name }}</td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td>{{ item.username }}</td>
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
