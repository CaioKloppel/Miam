<?php

class Recipe {

    private int $idRecipe;
    private string $name;
    private int $category;
    private int $portions;
    private float $rating;
    private bool $favorite;
    private ?string $image;
    private array $steps;
    private array $ingredients;

    public function __construct(int $idRecipe, string $name, int $category, int $portions, float $rating, bool $favorite, ?string $image, array $steps, array $ingredients) {
        $this->idRecipe = $idRecipe;
        $this->name = $name;
        $this->category = $category;
        $this->portions = $portions;
        $this->rating = $rating;
        $this->favorite = $favorite;
        $this->image = $image;
        $this->steps = $steps;
        $this->ingredients = $ingredients;
    }

    public function getIdRecipe(): int {
        return $this->idRecipe;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCategory(): int {
        return $this->category;
    }

    public function getPortions(): int {
        return $this->portions;
    }

    public function getRating(): float {
        return $this->rating;
    }

    public function getFavorite(): bool {
        return $this->favorite;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getSteps(): array {
        return $this->steps;
    }

    public function getIngredients(): array {
        return $this->ingredients;
    }

    public function setIdRecipe(int $idRecipe): void {
        $this->idRecipe = $idRecipe;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCategory(int $category): void {
        $this->category = $category;
    }

    public function setPortions(int $portions): void {
        $this->portions = $portions;
    }

    public function setRating(float $rating): void {
        $this->rating = $rating;
    }

    public function setFavorite(bool $favorite): void {
        $this->favorite = $favorite;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function setSteps(array $steps): void {
        $this->steps = $steps;
    }

    public function setIngredients(array $ingredients): void {
        $this->ingredients = $ingredients;
    }
}

?>