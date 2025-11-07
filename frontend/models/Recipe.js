export class Recipe {
  constructor(
    idRecipe,
    name,
    category,
    portions,
    rating,
    favorite,
    image,
    steps,
    ingredients
  ) {
    this.idRecipe = idRecipe;
    this.name = name;
    this.category = category;
    this.portions = portions;
    this.rating = rating;
    this.favorite = favorite;
    this.image = image;
    this.steps = steps;
    this.ingredients = ingredients;
  }
}
