<?php

class Sections extends DbConnection {

    // public function __construct ($dbConfig, $loginConfig, $passConfig, $optionsConfig) {  
    //     parent::__construct ($dbConfig, $loginConfig, $passConfig, $optionsConfig);
    // }

    public function getSections () {
        $sectionsData = $this->queryCall('SELECT section FROM sections');

        return $sectionsData;
    }

    public function getSubSections ($dataSection) {
        $subSectionData = $this->queryCall(
            'SELECT sub.subSection FROM subSections sub INNER JOIN sections s ON s.id = sub.id_sections AND s.section = :dataSection',
            array(
                array('dataSection', $dataSection, PDO::PARAM_STR))
        );

        return $subSectionData;
    }

    public function getAllAboutSubSection ($dataSection) {
        $nbrSubSections = $this->queryCall(
            'SELECT * FROM subSections sub LEFT JOIN sections s ON s.id = sub.id_sections WHERE :dataSection = s.section',
            array(
                array('dataSection', $dataSection, PDO::PARAM_STR))
        );

        return $nbrSubSection;
    }

}




// class GetSections {

//     protected $host;
//     protected $dbName;
//     protected $user;
//     protected $pwd;
//     protected $options = array();

//     public function __construct ($confHost, $confDbName, $confUser, $confPwd, $confOptions) {  
//         $this->host = $confHost;
//         $this->dbName = $confDbName;
//         $this->user = $confUser;
//         $this->pwd = $confPwd;
//         $this->options = $confOptions;
//     }

//     public function getSections () {
//         $db = $this->dbConnect ();
//         $sections = $db->query('SELECT section FROM sections');

//         return $sections->fetchAll(PDO::FETCH_NUM);
//     }

//     public function getSubSections ($imgSection) {
//         $db = $this->dbConnect ();
//         $req = $db->prepare('SELECT sub.subSection FROM subSections sub JOIN sections s ON s.id = sub.id_sections WHERE :imgSection = s.section');
//         $req->bindParam(':imgSection', $imgSection, PDO::PARAM_STR);
//         $req->execute();
//         $subSection = $req->fetchAll(PDO::FETCH_NUM);

//         return $subSection;
//     }

//     public function getNbrOfSubSection ($imgSection) {
//         $db = $this->dbConnect ();
//         $req = $db->prepare('SELECT COUNT(subSection) FROM subSections sub LEFT JOIN sections s ON s.id = sub.id_sections WHERE :imgSection = s.section');
//         $req->bindParam(':imgSection', $imgSection, PDO::PARAM_STR);
//         $req->execute();
//         $nbrSubSection = $req->fetch(PDO::FETCH_ASSOC);

//         return $nbrSubSection;
//     }

//     public function getConfig () {
//         return $this->config;
//     }

//     protected function dbConnect () {
//         $connect = new PDO("mysql:host=$this->host;dbname=$this->dbName;", $this->user, $this->pwd, $this->options);

//         return $connect;
//     }
// }
?>