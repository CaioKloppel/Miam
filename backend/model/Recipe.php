<?php

class Recipe {

    private $idRecipe;
    private $name;
    private $category;
    private $portions;
    private $rating;
    private $favorite;
    private $image;
    private $steps;
    private $ingredients;

    public function __construct($idRecipe, $name, $category, $portions, $rating, $favorite, $image, $steps, $ingredients) {
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

    public function getIdRecipe() {
        return $this->idRecipe;
    }

    public function getName() {
        return $this->name;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getPortions() {
        return $this->portions;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getFavorite() {
        return $this->favorite;
    }

    public function getImage() {
        return $this->image;
    }

    public function getSteps() {
        return $this->steps;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function setIdRecipe($idRecipe) {
        $this->idRecipe = $idRecipe;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function setPortions($portions) {
        $this->portions = $portions;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setFavorite($favorite) {
        $this->favorite = $favorite;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setSteps($steps) {
        $this->steps = $steps;
    }

    public function setIngredients($ingredients) {
        $this->ingredients = $ingredients;
    }
}

?>