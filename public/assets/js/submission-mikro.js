"use strict";

var submissionStepper;

$(function() {
    let submissionStepperEl = document.getElementById('submissionForm');
    let form = submissionStepperEl.querySelector('.bs-stepper-content form')
    let stepperPanList = [].slice.call(submissionStepperEl.querySelectorAll('.bs-stepper-pane'))
    let inputName = document.getElementById('name');
    
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
    
        let stepperPan = stepperPanList[currentStep]

        if (stepperPan.getAttribute('id') === 'bs-personal-data') {
            $('#bs-personal-data').find("input,select,textarea")
            .each(function() {
                console.log($(this).prop('id') + ' >> << ' + $(this).val());
                if($(this).prop('required') && !$(this).val().length) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }

                if($(this).prop('type') === 'email' && !isValidEmail($(this).val())) {
                    event.preventDefault();
                    form.classList.add('was-validated');
                }
            });
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
            $('#resumeCategory').html($('#category option:selected').text());
            $('#resumeProduct').html($('#product option:selected').text());
            $('#resumePlafond').html('Rp. ' + $('#plafond').val());
            $('#resumeTenor').html($('#tenor option:selected').text());
            $('#resumeInstallmentType').html($('#installment_type option:selected').text());
            $('#resumeSimulationResult').html('Rp ' + $('#simulation').val());
        }
    });
})