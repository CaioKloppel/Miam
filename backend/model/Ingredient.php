<?php

class Ingredient {

    private string $name;
    private int $quantity;
    private float $typeQuantity;
    private bool $avaible;

    public function __construct(string $name, int $quantity, float $typeQuantity, bool $avaible) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->typeQuantity = $typeQuantity;
        $this->avaible = $avaible;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getTypeQuantity(): float {
        return $this->typeQuantity;
    }

    public function getAvaible(): bool {
        return $this->avaible;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setTypeQuantity(float $typeQuantity): void {
        $this->typeQuantity = $typeQuantity;
    }

    public function setAvaible(bool $avaible): void {
        $this->avaible = $avaible;
    }
}

?>
