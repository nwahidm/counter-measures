"use strict";

var loadingButton = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Loading...';
var loadingState = ' <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

$(".selectpicker-with-search").selectpicker({
    liveSearch : true
});

flatpickr(".flatpickr-with-input", {
    disableMobile: !0,
    allowInput: !0
});

function isValidEmail(emailAddress) {
    var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(emailAddress);
};

function numberWithCommas(x) {
    if (x === null || x === undefined) {
        return 0;
    }
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function showHidePassword(obj) {
    $(`#${obj} i`).click(function(e) {
        e.preventDefault();
        if ($(`#${obj} input`).attr("type") == "text") {
            $(`#${obj} input`).attr('type', 'password');
            $(`#${obj} i`).addClass("fe-eye-off");
            $(`#${obj} i`).removeClass("fe-eye");
        } else if ($(`#${obj} input`).attr("type") == "password") {
            $(`#${obj} input`).attr('type', 'text');
            $(`#${obj} i`).removeClass("fe-eye-off");
            $(`#${obj} i`).addClass("fe-eye");
        }
    })
}

function generateUUID() {
    var d = new Date().getTime();
    var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now() * 1000)) || 0;
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16;
        if (d > 0) {
            r = (d + r) % 16 | 0;
            d = Math.floor(d / 16);
        } else {
            r = (d2 + r) % 16 | 0;
            d2 = Math.floor(d2 / 16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}

function selectPickerTrigger() {
    $(".selectpicker-with-search-manual").selectpicker({
        liveSearch : true
    });
    $(".selectpicker-with-search-manual").selectpicker('refresh');
}