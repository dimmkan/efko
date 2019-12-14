<?php

/**
 * Class User Класс "Пользователь". Т.к. создание/редактирование пользователей нет в ТЗ, будем испоьлзовать его
 * только для вызова статических методов и создания объектов класса
 */
class User
{
    /**
     * @var null
     * Поля класса
     */
    public $id = null;
    public $desc = null;
    public $login = null;
    public $password = null;
    public $rules = null;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        if(isset($data['id'])){
            $this->id = $data['id'];
        }
        if(isset($data['desc'])){
            $this->desc = $data['desc'];
        }
        if(isset($data['login'])){
            $this->login = $data['login'];
        }
        if(isset($data['password'])){
            $this->password = $data['password'];
        }
        if(isset($data['rules'])){
            $this->rules = $data['rules'];
        }
    }

    /**
     * Функция проверяет существование пользователя с такой парой логин/пароль в базе
     * @param string $login
     * @param string $password
     * @return bool
     */
    public static function authorizedUser($login = "", $password = ""){
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * "
            . "FROM users WHERE login = :login AND password = :password";
        $st = $conn->prepare($sql);
        $st->bindValue(":login", $login, PDO::PARAM_STR);
        $st->bindValue(":password", $password, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        $conn = null;

        if($row){
            return new User($row);
        }else {
            return null;
        }
    }
}