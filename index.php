<?php
require('includes/config.php');
try {
    initApplication();
} catch (Exception $e) {
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}
/**
 * Роутинг
 */
function initApplication()
{
    session_start();
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
    if ($action != "login" && $action != "logout" && !$username) {
        login();
        exit;
    }
    switch ($action) {
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
        case 'newLeaveApp':
            newLeaveApp();
            break;
        case 'deleteLeaveApp':
            deleteLeaveApp();
            break;
        default:
            listLeaveApplication();
    }

}

/**
 * Логин
 */
function login()
{
    $results = array();
    if (isset($_POST['login']) && isset($_POST['password'])) {
        //Проверим наличие пользователя с такими данными в базе
        $user = User::authorizedUser($_POST['login'], $_POST['password']);
        if (!is_null($user)) {
            // Вход прошел успешно: создаем сессию и перенаправляем на страницу с заявками
            $_SESSION['username'] = $user->login;
            $_SESSION['user'] = $user;
            header("Location: index.php");
        } else {
            $results['errorMessage'] = "Неверный логин или пароль, попробуйте еще раз";
            require(TEMPLATE_PATH . "/loginForm.php");
        }
    } else {
        //Данные с формы еще не пришли: выводим форму
        require(TEMPLATE_PATH . "/loginForm.php");
    }
}

/**
 * Логаут
 */
function logout()
{
    unset($_SESSION['username']);
    header("Location: index.php");
}

/**
 * Вывод списка заявок
 */
function listLeaveApplication()
{
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

    require(TEMPLATE_PATH . "/viewLeaveApplication.php");
}

/**
 * Корректировка заявки
 */
function editLeaveApp()
{
    $results = array();
    $results['formAction'] = "editLeaveApp";
    if (isset($_POST['saveChanges'])) {
        if (!$leaveApp = LeaveApplication::getById((int)$_POST['leaveAppId'])) {
            header("Location: index.php?action=listLA&error=leaveAppNotFound");
            return;
        }
        if (isset($_POST['fixed'])) {
            (int)$_POST['fixed'] = ($_POST['fixed'] == "on" ? 1 : 0);
        } else {
            $_POST['fixed'] = 0;
        }
        $leaveApp->storeFormValues($_POST);
        $leaveApp->update();
        header("Location: index.php?action=listLA&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        header("Location: index.php?action=listLA");
    } else {
        $results['leaveApp'] = LeaveApplication::getById((int)$_GET['leaveAppId']);
        require(TEMPLATE_PATH . "/editLeaveApp.php");
    }

}

/**
 * Создание заявки
 */
function newLeaveApp()
{
    $results = array();
    $results['formAction'] = "newLeaveApp";
    if (isset($_POST['saveChanges'])) {
        $leaveApp = new LeaveApplication();
        $_POST['fixed'] = 0;
        $leaveApp->storeFormValues($_POST);
        $leaveApp->insert();
        header("Location: index.php?action=listLA&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        header("Location: index.php?action=listLA");
    } else {
        $results['leaveApp'] = new LeaveApplication;
        require( TEMPLATE_PATH . "/editLeaveApp.php" );
    }
}

/**
 * Удаление заявки
 */
function deleteLeaveApp(){
    if (!$leaveApp = LeaveApplication::getById((int) $_GET['leaveAppId'])) {
        header("Location: index.php?action=listLA&error=leaveAppNotFound");
        return;
    }
    $leaveApp->delete();
    header("Location: index.php?action=listLA&status=articleDeleted");
}