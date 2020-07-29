<?php

function ipcustom_forms_permission()
{

    session_start('ipcustom_forms_session');

    if (isset($_POST['ipf_hash'])) return $_POST['ipf_hash'] === $_SESSION['ipcustom_forms_hash'];
    else return false;

}
