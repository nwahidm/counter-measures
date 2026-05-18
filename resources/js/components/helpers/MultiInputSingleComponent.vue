<script setup>
import {onMounted, ref} from "vue";
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
    placeholder: {
        type: String,
        default: 'Lengkapi List'
    }
});
const item = defineModel('item', {default: null});
const dataItem = ref([]);
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

onMounted(() => {
    if (props.value) {
        dataItem.value = JSON.parse(props.value);

        document.querySelector('#' + props.id).value = JSON.stringify(dataItem.value);
    }
});
</script>

<template>
    <div :class="props.classList">
        <label for="item"
               class="fs-6 fw-semibold mb-2 required">{{ capitalizeFirstLetter(props.name) }}</label>
        <table class="table table-sm table-striped table-bordered">
            <thead>
            <tr>
                <th colspan="2" class="text-start">
                    <input class="form-control form-control-sm" :class="{'is-invalid': props.isInvalid}"
                           type="text" :placeholder="placeholder"
                           @keydown.enter.prevent="tambahDataItem" v-model="item"/>
                    <input type="hidden" :name="props.name" :id="props.id"/>
                    <span class="invalid-feedback" v-if="props.isInvalid">{{ props.errorMessage }}</span>
                </th>
                <th class="text-start w-1px" style="vertical-align: top;">
                    <button type="button" class="btn btn-sm btn-success" @click.prevent="tambahDataItem"
                            :disabled="!item">
                        <span class="fas fa-add"></span>
                    </button>
                </th>
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
                <td>{{ item }}</td>
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
