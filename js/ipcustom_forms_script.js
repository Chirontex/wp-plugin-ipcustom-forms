function ipcustom_forms_input_validation(inputId, inputType)
{
    var input = document.querySelector('#'+inputId);
    var email_regexp = /^[\w]{1}[\w-\.]*@[\w-]+\.[a-z]{2,4}$/i;
    var result = false;

    if (inputType === 'email') {

        if (input.value && email_regexp.test(input.value)) result = true;
        else if (!input.value || !email_regexp.test(input.value)) result = false;

    } else {

        if (input.value) result = true;
        else result = false;

    }

    return result;
}

function ipcustom_forms_button_onoff(buttonId, on)
{
    var button = document.querySelector('#'+buttonId);

    if (on && button.hasAttribute('disabled')) button.removeAttribute('disabled');
    else if (!on && !button.hasAttribute('disabled')) button.setAttribute('disabled', '');

}

function ipcustom_forms_submit(buttonId)
{
    var email = document.querySelector('#e-mail');
    var sender_name = document.querySelector('#sender_name');
    var text_message = document.querySelector('#text_message');
    var key = document.querySelector('#ipf_key').value;
    var key_storage = document.querySelector('#ipf_key_storage').value;
    var button = document.querySelector('#'+buttonId);

    var modal_trigger = document.querySelector('#form-modal-launch');
    var modal_title = document.querySelector('#form-modal-label');
    var modal_body = document.querySelector('#form-modal-body');

    var request;
    var button_prevtext;

    button_prevtext = button.innerHTML;

    button.innerHTML = 'Waiting...';

    if (!button.hasAttribute('disabled')) button.setAttribute('disabled', '');

    if (sender_name && text_message) {

        request = $.ajax({
            url: "/wp-json/ipcustom/v1/forms/contact",
            method: "POST",
            data: {
                email: email.value,
                name: sender_name.value,
                text: text_message.value,
                ipf_key: key,
                ipf_key_storage: key_storage
            },
            dataType: "json"
        });

    } else {

        request = $.ajax({
            url: "/wp-json/ipcustom/v1/forms/subscribe",
            method: "POST",
            data: {
                email: email.value,
                ipf_key: key,
                ipf_key_storage: key_storage
            },
            dataType: "json"
        });

    }

    request.done(function(answer) {

        if (button.hasAttribute('disabled')) button.removeAttribute('disabled');

        button.innerHTML = button_prevtext;

        if (answer['code'] === '0') {

            modal_title.innerHTML = 'Success!';
            modal_body.innerHTML = 'Success!';

        } else {

            modal_title.innerHTML = 'Unexpected answer';
            modal_body.innerHTML = 'Error: '+answer['code']+', '+answer['message'];

        }

        modal_trigger.click();

    });

    request.fail(function(jqXHR, textStatus) {

        if (button.hasAttribute('disabled')) button.removeAttribute('disabled');

        button.innerHTML = button_prevtext;

        modal_title.innerHTML = 'Query error';
        modal_body.innerHTML = textStatus;

        modal_trigger.click();

    });

}