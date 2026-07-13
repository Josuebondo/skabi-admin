function logout() {
  // JSON.parse(sessionStorage.getItem("currentUser")).removeItem("token");
  sessionStorage.removeItem("currentUser");
  sessionStorage.clear();
  window.location.href = "/logout";
}
async function showtoast(message, type = "success") {
  const toast = document.createElement("div");
  toast.className = `toast ${type === "success" ? "toast-success" : "toast-error"}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  new Promise((resolve) => setTimeout(resolve, 3000)).then(() => {
    toast.remove();
  });
}
const logoutBtn = document.getElementById("logoutBtn");
logoutBtn.addEventListener("click", (e) => {
  e.preventDefault();
  logout();
});

// console.log(localStorage.getItem("currentUser"));

// Si pas de token, rediriger vers login
document.addEventListener("onload", async () => {
  const token = sessionStorage.getItem("currentUser").getItem("token");
  if (!token) {
    await showtoast(
      "Veuillez vous connecter pour accéder à cette page",
      "error",
    );
    window.location.href = "/";
  }
});
function generateAvatar() {
  const user = JSON.parse(sessionStorage.getItem("currentUser"));

  const avatarImg = document.getElementById("avatarImg");
  const avatarInitials = document.getElementById("avatarInitials");

  if (!user) return;

  // Si utilisateur a une photo
  if (user.photo) {
    avatarImg.src = `https://stock.skabi.shop` + user.photo;
    avatarImg.classList.remove("hidden");
    avatarInitials.textContent = "";
  } else {
    // Générer initiales
    const name = user.username || "User";
    const initials = name
      .split(" ")
      .map((word) => word[0])
      .join("")
      .substring(0, 2)
      .toUpperCase();

    avatarInitials.textContent = initials;
    avatarImg.classList.add("hidden");
  }
}

generateAvatar();
