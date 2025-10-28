<?php

class Step {

    private int $numStep;
    private string $description;

    public function __construct(int $numStep, string $description) {
        $this->numStep = $numStep;
        $this->description = $description;
    }

    public function getNumStep(): int {
        return $this->numStep;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setNumStep(int $numStep): void {
        $this->numStep = $numStep;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }
}

?>
