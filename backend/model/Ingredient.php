<?php

class Ingredient implements JsonSerializable{

    private string $name;
    private float $quantity;
    private string $typeQuantity;
    private bool $avaible;

    public function __construct(string $name, float $quantity, string $typeQuantity, bool $avaible) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->typeQuantity = $typeQuantity;
        $this->avaible = $avaible;
    }

    public static function constructFromArray(array $ingredientInfo){
        $ingredient = new Ingredient(
            $ingredientInfo['ingredient_name'],
            (float)$ingredientInfo['ingredient_quantity'],
            $ingredientInfo['ingredient_type'],
            (bool)$ingredientInfo['ingredient_available']
        );

        return $ingredient;
    }

    public function jsonSerialize(): mixed {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'typeQuantity' => $this->typeQuantity,
            'avaible' => $this->avaible
        ];
    }

    public function getName(): string {
        return $this->name;
    }

    public function getQuantity(): float {
        return $this->quantity;
    }

    public function getTypeQuantity(): string {
        return $this->typeQuantity;
    }

    public function getAvaible(): bool {
        return $this->avaible;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setQuantity(float $quantity): void {
        $this->quantity = $quantity;
    }

    public function setTypeQuantity(string $typeQuantity): void {
        $this->typeQuantity = $typeQuantity;
    }

    public function setAvaible(bool $avaible): void {
        $this->avaible = $avaible;
    }
}

?>
