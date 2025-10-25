async function register() {
  var recipeName = document.getElementById("inpt-name");
  var recipeImg = document.getElementById("inpt-image");

  const formData = new FormData();
  formData.append("id", 1);
  formData.append("name", recipeName.value);
  formData.append("img", recipeImg.files[0]);

  const response = await fetch("../backend/foodRegister.php", {
    method: "POST",
    body: formData,
  });

  var result = await response.json();
  console.log(result);
  alert(`sucess: ${result.sucess}, message: ${result.message}`);
}
