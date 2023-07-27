let btn = document.querySelector(".material-symbols-outlined");

btn.addEventListener("click", function () {
  let input = document.querySelector("#senha");
  if (input.getAttribute("type") == "password") {
    input.setAttribute("type", "text");

    btn.innerHTML = "visibility_off";
  } else {
    input.setAttribute("type", "password");

    btn.innerHTML = "visibility";
  }
});
