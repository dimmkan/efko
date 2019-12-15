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

    public function __construct($data=array())
    {
        if (isset($data['id'])) {
            $this->id = (int)$data['id'];
        }
        if (isset($data['descr'])) {
            $this->descr = $data['descr'];
        }
        if (isset($data['datebeg'])) {
            $publicationDate = explode ( '-', $data['datebeg']);
            if ( count($publicationDate) == 3 ) {
                list ( $y, $m, $d ) = $publicationDate;
                $this->datebeg = mktime( 0, 0, 0, $m, $d, $y );
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
     * @return array
     * Список всех заявок
     */
    public static function getList(){
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT lapps.id, lapps.descr, lapps.datebeg, lapps.dateend, "
                    ."lapps.userid, lapps.fixed, users.desc FROM efko.lapps LEFT JOIN efko.users ON lapps.userid = users.id";
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
}
