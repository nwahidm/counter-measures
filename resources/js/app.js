/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import ReportKegiatanPoskoComponent from './components/backoffice/report/ReportKegiatanPoskoComponent.vue';
import MultiInputSingleComponent from "./components/helpers/MultiInputSingleComponent.vue";
import InputMemerintahkanAutoComponent from "./components/helpers/InputMemerintahkanAutoComponent.vue";
import BpnServiceComponent from "./components/backoffice/bpn/BpnServiceComponent.vue";
import InputMemerintahkanManualComponent from "./components/helpers/InputMemerintahkanManualComponent.vue";

app.component('example-component', ExampleComponent);
app.component('report-kegiatan-posko-component', ReportKegiatanPoskoComponent);
app.component('multi-input-single-component', MultiInputSingleComponent);
app.component('input-memerintahkan-auto-component', InputMemerintahkanAutoComponent);
app.component('bpn-service-component', BpnServiceComponent);
app.component('input-memerintahkan-manual-component', InputMemerintahkanManualComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#kt_app_root');
