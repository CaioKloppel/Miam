import { User } from "../models/User.js";
import { Recipe } from "../models/Recipe.js";
import { Ingredient } from "../models/Ingredient.js";
import { Step } from "../models/Step.js";

var user = new User(
  null,
  "Carol",
  "CarolCoral",
  "carol@email.com",
  "12345",
  []
);

var steps = [
  new Step(1, "Bata as cenouras, os ovos e o óleo em um liquidificador"),
  new Step(
    2,
    "Despeje a mistura em um recipiente e misture o açúcar e a farinha de trigo peneirada com o fermento."
  ),
  new Step(
    3,
    "Coloque em uma fôrma retangular (20 x 30 cm) untada, e leve ao forno médio (180°C), preaquecido, por 40 minutos."
  ),
];

var ingredients = [
  new Ingredient("cenouras", 3, "entire", true),
  new Ingredient("ovos", 4, "entire", false),
  new Ingredient("óleo", 1 / 2, "cups", true),
  new Ingredient("farinha de trigo", 2 + 1 / 2, "cups", false),
  new Ingredient("açúcar", 2, "cups", true),
  new Ingredient("fermento em pó", 1, "tablespoon", false),
];

user.recipes.push(
  new Recipe(null, "Bolo de cenoura", 1, 4.5, null, steps, ingredients)
);

console.log(user);
