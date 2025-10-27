export class Recipe {
  constructor(idRecipe, name, portions, rating, image, steps, ingredients) {
    this.idRecipe = idRecipe;
    this.name = name;
    this.portions = portions;
    this.rating = rating;
    this.image = image;
    this.steps = steps;
    this.ingredients = ingredients;
  }
}
