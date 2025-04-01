function notification() {
  document.getElementById("notification").style.width = "100vw";
  document.getElementById("notification").style.transition = "ease-in-out 0.2s";
  document.getElementById("notification").style.opacity = 1;
  document.getElementById("edit").style.opacity = 0;
  document.getElementById("edit").style.display = "none";
}

function back() {
  document.getElementById("notification").style.width = 0;
  document.getElementById("notification").style.opacity = 0;
  document.getElementById("edit").style.opacity = 1;
  document.getElementById("edit").style.display = "inline";
}

function menu() {
  document.getElementById("logout").style.display = "flex";
  document.getElementById("menu").src = "close.png";
  if (document.getElementById("logout").style.display == "flex") {
    document.getElementById("logout").style.display = "none";
    document.getElementById("menu").src = "menu.png";
  }
}
// let error = document.getElementById("error");
// if (error != null) {
//   error.addEventListener("click", () => {
//     error.style.display = "none";
//   });
// }
function like() {
  document.getElementById("like").src = "Red-heart.png";
}
