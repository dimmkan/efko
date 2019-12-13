<?php
require ('includes/config.php');
try{
    initApplication();
}catch (Exception $e){
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}

function initApplication(){

}