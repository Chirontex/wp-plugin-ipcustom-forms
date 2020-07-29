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
    var hash = document.querySelector('#ipf_hash').value;

    if (sender_name && text_message) {



    } else {

        

    }

}