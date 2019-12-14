<?php
try {
    // Включаем полное отображение ошибок
    ini_set("display_errors", true);
    error_reporting(E_ALL);
    date_default_timezone_set("Europe/Moscow");
    // Настройки БД и остальных параметров будем хранить в массиве
    $AppConfiguration = array();
    $AppConfiguration["DB_DSN"] = "mysql:host=localhost;dbname=efko;charset=utf8;" ;
    $AppConfiguration["DB_USERNAME"] = "root";
    $AppConfiguration["DB_PASSWORD"] = "123";
    // Объявление констант, используемых в проекте
    $AppConfiguration["CLASS_PATH"] = "classes";
    $AppConfiguration["TEMPLATE_PATH"] = "templates";
    $AppConfiguration["INCLUDE_PATH"] = "includes";
    // после того, как значения конфигурации определены, создаём для них константы
    defineConstants($AppConfiguration);
    // Подключаем Классы моделей (классы, отвечающие за работу с сущностями базы данных)
    require(CLASS_PATH . "/User.php");
    require(CLASS_PATH . "/LeaveApplication.php");
} catch (Exception $ex) {
    echo "При загрузке конфигураций возникла проблема!<br><br>";
    error_log($ex->getMessage());
}
/**
 * Создаст константы, хранящие настройки приложения
 *
 * @param array $constatsNameAndValues массив, содержащий в качестве ключей имена констант,
 *  которые нужно объявить, а в качестве значений -- знчения этих констант
 */
function defineConstants($constatsNameAndValues)
{
    // обходим массив и определяем нужные константы
    foreach ($constatsNameAndValues as $constName => $constValue) {
        define($constName, $constValue);
    }
}