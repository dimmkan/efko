<?php
require ('includes/config.php');
try{
    initApplication();
}catch (Exception $e){
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}

function initApplication(){
    session_start();
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
    if($action != "login" && $action != "logout" && !$username){
        login();
        exit;
    }
    switch ($action){
        case 'login':
            login();
            break;
        case 'logout':
            logout();
            break;
        case 'listLA':
            listLeaveApplication();
            break;
        case 'editLeaveApp':
            editLeaveApp();
            break;
        default:
            listLeaveApplication();
    }

}

function login() {
    $results = array();
    if(isset($_POST['login']) && isset($_POST['password'])){
        //Проверим наличие пользователя с такими данными в базе
        $user = User::authorizedUser($_POST['login'], $_POST['password']);
        if (!is_null($user)){
            // Вход прошел успешно: создаем сессию и перенаправляем на страницу с заявками
            $_SESSION['username'] = $user->login;
            $_SESSION['user'] = $user;
            header("Location: index.php");
        }else{
            $results['errorMessage'] = "Неверный логин или пароль, попробуйте еще раз";
            require(TEMPLATE_PATH. "/loginForm.php");
        }
    }else{
        //Данные с формы еще не пришли: выводим форму
        require(TEMPLATE_PATH. "/loginForm.php");
    }
}

function logout() {
    unset($_SESSION['username']);
    header("Location: index.php");
}

function listLeaveApplication(){
    $results = array();
    $data = LeaveApplication::getList();
    $results['leaveApps'] = $data['results'];

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "leaveAppNotFound")
            $results['errorMessage'] = "Ошибка: Заявка не найдена.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Изменения сохранены.";
        }
        if ($_GET['status'] == "articleDeleted") {
            $results['statusMessage'] = "Заявка удалена.";
        }
    }

    require(TEMPLATE_PATH. "/viewLeaveApplication.php");
}

function editLeaveApp(){
    require(TEMPLATE_PATH."/editLeaveApp.php");
}