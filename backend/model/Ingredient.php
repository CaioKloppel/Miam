<?php

class Ingredient {

    private $name;
    private $quantity;
    private $typeQuantity;
    private $avaible;

    public function __construct($name, $quantity, $typeQuantity, $avaible) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->typeQuantity = $typeQuantity;
        $this->avaible = $avaible;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTypeQuantity() {
        return $this->typeQuantity;
    }

    public function getAvaible() {
        return $this->avaible;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setTypeQuantity($typeQuantity) {
        $this->typeQuantity = $typeQuantity;
    }

    public function setAvaible($avaible) {
        $this->avaible = $avaible;
    }
}

?>
