<?php

class Step {

    private $numStep;
    private $description;

    public function __construct($numStep, $description) {
        $this->numStep = $numStep;
        $this->description = $description;
    }

    public function getNumStep() {
        return $this->numStep;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setNumStep($numStep) {
        $this->numStep = $numStep;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}

?>
