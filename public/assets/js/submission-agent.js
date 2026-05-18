"use strict";

var submissionStepper;
var verifyStatus = false;

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let submissionStepperEl = document.getElementById('submissionForm');
    let form = submissionStepperEl.querySelector('.bs-stepper-content form')
    let stepperPanList = [].slice.call(submissionStepperEl.querySelectorAll('.bs-stepper-pane'))
    let inputName = document.getElementById('name');
    localStorage.removeItem('verifyOTP');
    
    submissionStepper = new Stepper(submissionStepperEl, {
        linear: !1,
        animation: !0
    });
    
    submissionStepperEl.addEventListener('show.bs-stepper', function (event) {
        form.classList.remove('was-validated')
        let nextStep = event.detail.indexStep
        let currentStep = nextStep
    
        if (currentStep > 0) {
          currentStep--
        }
    
        let stepperPan = stepperPanList[currentStep];

        if (stepperPan.getAttribute('id') === 'bs-personal-data') {
            $('#bs-personal-data').find("input,select,textarea")
            .each(function() {
                if($(this).prop('required') && !$(this).val().length) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }

                if($(this).prop('type') === 'email' && !isValidEmail($(this).val())) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }
            });

            if (!form.classList.contains("was-validated")) {
                isVerified(event);
            }
        }

        if (stepperPan.getAttribute('id') === 'bs-financing-data') {
            $('#bs-financing-data').find("input,select,textarea")
            .each(function() {
                console.log($(this).prop('id') + ' >> << ' + $(this).val());
                if($(this).prop('required') && !$(this).val().length) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }
            });
        }

        if (stepperPan.getAttribute('id') === 'bs-product-data') {
            $('#bs-product-data').find("input,select,textarea")
            .each(function() {
                console.log($(this).prop('id') + ' >> << ' + $(this).val());
                if($(this).prop('required') && !$(this).val().length) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }
            });
        }
    });

    submissionStepperEl.addEventListener('shown.bs-stepper', function (event) {
        form.classList.remove('was-validated')
        let nextStep = event.detail.indexStep
        let currentStep = nextStep
    
        let stepperPan = stepperPanList[currentStep]

        if (stepperPan.getAttribute('id') === 'bs-summary-data') {
            $('#resumeName').html($('#name').val());
            $('#resumeLegalId').html($('#legal_id').val());
            $('#resumePhone').html('+62' + $('#phone').val());
            $('#resumeEmail').html($('#email').val());
            $('#resumeAccountNumber').html($('#account_number').val());
            $('#resumeCardNumber').html($('#card_number').val());
            $('#resumeBusinessType').html($('#business_type option:selected').text());
            $('#resumeBusinessName').html($('#business_name').val());
            $('#resumeOutletPreference').html($('#outlet_id option:selected').text());
        }
    });

    var modalVerifyOTP = document.getElementById('modalVerifyOTP')

    modalVerifyOTP.addEventListener('show.bs.modal', function (event) {
        setCountDownOTP();
        showHidePassword('show_hide_otp');
    });

    function setCountDownOTP() {
        var countDownDate = addSecond(new Date(), 10).getTime();

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $('#countDown').html(`${minutes.toString().padStart(2, '0')} : ${seconds.toString().padStart(2, '0')}`);
                if (distance < 0) {
                    clearInterval(x);
                    $('#countDown').html(`EXPIRED`);
                }
        }, 1000);
    }

    function addSecond(date, second) {
        date.setSeconds(date.getSeconds() + second);
        
        return date;
    }

    function sendVerify() {
        const transactionId = generateUUID();
        const phone = '62' + $('#phone').val();

        $('#btnNextFirstStep').addClass('disabled').html(loadingButton);

        $.ajax({
            type: "POST",
            url: "/agent/generate-otp",
            data: { phone : phone, transaction_id : transactionId }
        })
        .done(function (data) {
            if (data.status == "200") {
                const verifyResult = {
                    status : true,
                    transactionId : transactionId,
                    refference: data.data.refference,
                    phone: phone
                }
                localStorage.setItem('verifyOTP', JSON.stringify(verifyResult));

                $('#subtitleOTP').html(data.data.subtitle);
                $('#modalVerifyOTP').modal('show');
            } else {
                localStorage.removeItem('verifyOTP');
                swal({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .fail(function(data) {
            localStorage.removeItem('verifyOTP');
            swal({
                title: 'Gagal!',
                text: 'Gagal BeKeenin kamu nih, coba lagi nanti ya',
                icon: 'error'
            });
        })
        .always(function(data) {
            $("#btnNextFirstStep").removeClass('disabled').html("Next");
        });
    }

    $('#btnVerifyOTP').click(function(e) {
        if ($('#otp').val().length < 1) {
            alert('Kode OTP required');
            return false;
        }

        $(this).addClass('disabled').html(loadingButton);

        const otp = JSON.parse(localStorage.getItem('verifyOTP'));

        $.ajax({
            type: "POST",
            url: "/agent/validate-otp",
            data: { otp_id : otp.refference, otp_pin : $('#otp').val() }
        })
        .done(function (data) {
            if (data.status == "200") {
                $('#modalVerifyOTP').modal('hide');
                verifyStatus = true;
                submissionStepper.next();
            } else {
                swal({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .fail(function(data) {
            swal({
                title: 'Gagal!',
                text: 'Gagal BeKeenin kamu nih, coba lagi nanti ya',
                icon: 'error'
            });
        })
        .always(function(data) {
            $('#btnVerifyOTP').removeClass('disabled').html("Kirim");
        });
    });

    function isVerified(event) {
        if (!verifyStatus) {
            event.preventDefault();
            submissionStepper.to(1);
            const otp = JSON.parse(localStorage.getItem('verifyOTP'));

            $.ajax({
                type: "POST",
                url: "/agent/is-verified",
                data: { transaction_id : otp?.transactionId, key : '62'+$('#phone').val()},
                async: false
            })
            .done(function (data) {
                if (data.data) {
                    submissionStepper.next();
                } else {
                    sendVerify();
                }
            })
            .fail(function(data) {
                location.reload();
            });
        }
    }

    function isVerifiedAction(isVerify) {
        if (isVerify) {
            submissionStepper.next();
        } else {
            sendVerify();
        }
    }

    $("#phone").on("change paste keyup cut select", function() {
        const otp = JSON.parse(localStorage.getItem('verifyOTP'));
        if (otp != null) {
            if (otp.phone !== $(this).val()) {
                verifyStatus = false;
            } else {
                verifyStatus = true;
            }
        }
     });
})