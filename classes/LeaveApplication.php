<?php


class LeaveApplication
{
    /**
     * @var
     * Поля класса заявки
     */
    public $id;
    public $descr;
    public $datebeg;
    public $dateend;
    public $userid;
    public $userdescr;
    public $fixed;

    /**
     * LeaveApplication constructor.
     * @param array $data
     */
    public function __construct($data=array())
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['descr'])) {
            $this->descr = $data['descr'];
        }
        if(isset($data['datebeg'])){
            $publicationDate = explode ('-', $data['datebeg']);
            if (count($publicationDate) == 3) {
                list ( $y, $m, $d ) = $publicationDate;
                $this->datebeg = mktime(0, 0, 0, $m, $d, $y);
            }
        }
        if (isset($data['dateend'])) {
            $publicationDate = explode ( '-', $data['dateend']);
            if ( count($publicationDate) == 3 ) {
                list ( $y, $m, $d ) = $publicationDate;
                $this->dateend = mktime( 0, 0, 0, $m, $d, $y );
            }
        }
        if (isset($data['userid'])) {
            $this->userid = (int)$data['userid'];
        }
        if (isset($data['desc'])) {
            $this->userdescr = $data['desc'];
        }
        if (isset($data['fixed'])) {
            $this->fixed = (int)$data['fixed'];
        }
    }

    /**
     * @param $params
     */
    public function storeFormValues($params){
        // Сохраняем все параметры
        $this->__construct($params);
        // Разбираем и сохраняем даты
        if(isset($params['datebeg'])){
            $datebeg = explode ('-', $params['datebeg']);
            if (count($datebeg) == 3) {
                list ($y, $m, $d) = $datebeg;
                $this->datebeg = mktime(0, 0, 0, $m, $d, $y);
            }
        }
        if(isset($params['dateend'])){
            $dateend = explode ('-', $params['dateend']);
            if (count($dateend) == 3) {
                list ($y, $m, $d) = $dateend;
                $this->dateend = mktime(0, 0, 0, $m, $d, $y);
            }
        }
    }
    /**
     * @return array
     * Список всех заявок
     */
    public static function getList(){
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT lapps.id, lapps.descr, lapps.datebeg, lapps.dateend, "
                    ."lapps.userid, lapps.fixed, users.desc FROM efko.lapps LEFT JOIN efko.users ON lapps.userid = users.id ORDER BY lapps.id";
        $st = $conn->prepare($sql);
        $st->execute();

        $list = array();

        while($row = $st->fetch()){
           $list[] = new LeaveApplication($row);
        }
        $conn = null;
        return (array(
           "results" => $list
        ));
    }

    /**
     * @param $id
     * @return LeaveApplication
     */
    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * "
            . "FROM lapps WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;

        if ($row) {
            return new LeaveApplication($row);
        }
    }
    /**
     * Вставить в базу новый объект
     */
    public function insert() {
        if (!is_null( $this->id ) ) trigger_error ( "LeaveApplication::insert(): Объект с таким ID уже есть в базе: $this->id).", E_USER_ERROR );
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "INSERT INTO lapps (descr, datebeg, dateend, userid, fixed) VALUES (:descr, FROM_UNIXTIME(:datebeg), FROM_UNIXTIME(:dateend), :userid, :fixed)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":descr", $this->descr, PDO::PARAM_STR);
        $st->bindValue(":datebeg", $this->datebeg, PDO::PARAM_INT);
        $st->bindValue(":dateend", $this->dateend, PDO::PARAM_INT);
        $st->bindValue(":fixed", $this->fixed, PDO::PARAM_INT);
        $st->bindValue( ":userid", $this->userid, PDO::PARAM_INT );
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }
    /**
     * Обновляем текущий объект в базе данных
     */
    public function update() {
        if (is_null($this->id)) trigger_error ( "LeaveApplication::update(): "
            . "Для объекта не задан идентификатор", E_USER_ERROR );
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE lapps SET descr=:descr, datebeg=FROM_UNIXTIME(:datebeg), dateend=FROM_UNIXTIME(:dateend), fixed=:fixed WHERE id = :id";
        $st = $conn->prepare ( $sql );
        $st->bindValue(":descr", $this->descr, PDO::PARAM_STR);
        $st->bindValue(":datebeg", $this->datebeg, PDO::PARAM_INT);
        $st->bindValue(":dateend", $this->dateend, PDO::PARAM_INT);
        $st->bindValue(":fixed", $this->fixed, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
    /**
     * Удаляем текущий объект из базы данных
     */
    public function delete() {
        if (is_null($this->id))
            trigger_error("LeaveApplication::delete(): "
                . "Для объекта не задан идентификатор", E_USER_ERROR);
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $st = $conn->prepare("DELETE FROM lapps WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

}
