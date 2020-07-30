<?php

function ipcustom_forms_permission()
{

    if (isset($_POST['ipf_key']) && isset($_POST['ipf_key_storage'])) {

        session_start(['name' => 'ipcustom_forms_session']);

        if ($_SESSION[$_POST['ipf_key_storage']] === $_POST['ipf_key']) $result = true;
        else $result = false;

        unset($_SESSION[$_POST['ipf_key_storage']]);
    
    } else $result = false;

    return $result;

}
