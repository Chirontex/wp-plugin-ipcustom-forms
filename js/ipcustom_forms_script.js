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

            if (sender_name && text_message) {

                modal_title.innerHTML = 'The letter successfully sent';
                modal_body.innerHTML = 'We will definitely read it and perhaps give an answer.';

            } else {

                modal_title.innerHTML = 'Subscription completed';
                modal_body.innerHTML = 'You have successfully subscribed to the newsletter. We promise not to share your e-mail address with anyone and use it only for its intended purpose.';

            }

        } else {

            if (sender_name && text_message) {

                modal_title.innerHTML = 'Unexpected error';
                modal_body.innerHTML = 'We do not know what happened, but we\'ll definitely figure it out. Try later.';

            } else {

                modal_title.innerHTML = 'Error';
                modal_body.innerHTML = 'An error has occurred. You may be trying to subscribe to an e-mail that has already been used.';

            }

        }

        modal_trigger.click();

    });

    request.fail(function(jqXHR, textStatus) {

        if (button.hasAttribute('disabled')) button.removeAttribute('disabled');

        button.innerHTML = button_prevtext;

        modal_title.innerHTML = 'Failure';
        modal_body.innerHTML = 'Data has already been sent during this session. If you haven\'t submitted anything, please try reloading the page.';

        modal_trigger.click();

    });

}