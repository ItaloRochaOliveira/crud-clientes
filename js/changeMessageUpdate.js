let btn = document.querySelector("#button");
let div = document.querySelector("#to-hide-message");

btn.addEventListener("click", () => {
  console.log("funciona");
  div.id = "show-message";

  setTimeout(() => {
    div.setAttribute("id", "to-hide-message");
  }, 5000);
});
