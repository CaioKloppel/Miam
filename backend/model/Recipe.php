<?php

require_once __DIR__ . '/Ingredient.php';
require_once __DIR__ . '/Step.php';

class Recipe implements JsonSerializable{

    private int $idRecipe;
    private string $name;
    private string $category;
    private int $portions;
    private float $rating;
    private bool $favorite;
    private ?string $image;
    private array $steps;
    private array $ingredients;

    public function __construct(int $idRecipe, string $name, string $category, int $portions, float $rating, bool $favorite, ?string $image, array $steps, array $ingredients) {
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

    public static function constructFromArray(array $recipeInfo, $mysql=false) : Recipe {
        $recipe = new Recipe(
            $mysql ? (int)$recipeInfo['ID_Food_recipe'] : (isset($recipeInfo['idRecipe']) ? (int)$recipeInfo['idRecipe'] : 0),
            $mysql ? $recipeInfo['recipe_name'] : $recipeInfo['name'],
            $recipeInfo['category'],
            (int)$recipeInfo['portions'],
            (float)$recipeInfo['rating'],
            (bool)$recipeInfo['favorite'],
            $mysql ? $recipeInfo['Food_image'] : $recipeInfo['image'],
            [],
            []
        );

        if ($mysql) return $recipe;

        foreach($recipeInfo['ingredients'] as $ingredient){
            $recipe->setIngredients(
                new Ingredient(
                    $ingredient['name'],
                    (float)$ingredient['quantity'],
                    $ingredient['typeQuantity'],
                    (bool)$ingredient['avaible']
                )
            );
        }

        foreach($recipeInfo['steps'] as $step){
            $recipe->setSteps(
                new Step(
                    (int)$step['numStep'],
                    $step['description']
                )
            );
        }

        return $recipe;
    }

    public function jsonSerialize(): mixed {
        return [
            'idRecipe' => $this->idRecipe,
            'name' => $this->name,
            'category' => $this->category,
            'portions' => $this->portions,
            'rating' => $this->rating,
            'favorite' => $this->favorite,
            'image' => $this->image,
            'steps' => $this->steps,
            'ingredients' => $this->ingredients
        ];
    }

    public function getIdRecipe(): int {
        return $this->idRecipe;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCategory(): string {
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

    public function setCategory(string $category): void {
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

    public function setSteps(Step $steps): void {
        $this->steps[] = $steps;
    }

    public function setIngredients(Ingredient $ingredients): void {
        $this->ingredients[] = $ingredients;
    }
}

?>