<?php

function ipcustom_forms_permission()
{

    if (isset($_POST['ipf_key']) && isset($_POST['ipf_key_storage'])) {

        session_start(['name' => 'ipcustom_forms_session']);

        return ($_SESSION[$_POST['ipf_key_storage']] === $_POST['ipf_key']);
    
    } else return false;

}
