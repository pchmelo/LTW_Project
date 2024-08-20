<?php
declare(strict_types = 1);

class Caracteristicas{
    public $caracID;
    public $caracType;
    public $caracValue;
    public $caracImg;

    public function __construct($caracID, $caracType, $caracValue, $caracImg){
        $this->caracID = $caracID;
        $this->caracType = $caracType;
        $this->caracValue = $caracValue;
        $this->caracImg = $caracImg;
    }

    public function create($db){
        $stmt = $db->prepare("INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg) VALUES (?, ?, ?)");
        $stmt->execute(array($this->caracType, $this->caracValue, $this->caracImg));
    }

    public function delete($db) {
        $stmt = $db->prepare("DELETE FROM Characteristics WHERE CharacteristicsID = ?");
        $stmt->execute(array($this->caracID));
    }

    public static function getCaracteristicasByType($db, $type) {
        $stmt = $db->prepare("SELECT * FROM Characteristics WHERE CharacteristicsType = ?");
        $stmt->execute(array($type));
        $caracteristicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($caracteristica) {
            return new Caracteristicas($caracteristica['CharacteristicsID'], $caracteristica['CharacteristicsType'], $caracteristica['CharacteristicsValue'], $caracteristica['CharacteristicsImg']);
        }, $caracteristicas);
    }


    public function update($db) {
        $stmt = $db->prepare("UPDATE Characteristics SET CharacteristicsType = ?, CharacteristicsValue = ?, CharacteristicsImg = ? WHERE CharacteristicsID = ?");
        $stmt->execute(array($this->caracType, $this->caracValue, $this->caracImg, $this->caracID));
    }
    

}

?>
