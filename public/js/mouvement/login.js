const loginBtn = document.getElementById("loginBtn");
const btnText = loginBtn.querySelector("span"); // "Se connecter"
const btnLoader = document.getElementById("btnLoader");
const loginMessage = document.getElementById("loginMessage");

let currentUser = null;
async function login(username, password) {
  // Afficher loader
  loading();
  loginBtn.disabled = true;
  loginMessage.textContent = "";

  try {
    const res = await fetch("/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password }),
    });

    const data = await res.json();
    console.log("Réponse login :", data);
    if (data.success == true) {
      if (data.data.role != "admin") {
        console.log("Rôle de l'utilisateur :", data.data.role);
        loginMessage.textContent =
          "Vous n'avez pas les droits pour accéder à cette page";
        stopLoading();
        setTimeout(() => {
          loginMessage.textContent = "";
        }, 3000);
        return false;
      }
      loginMessage.classList.remove("text-red-600");
      loginMessage.classList.add("text-green-600");
      loginMessage.textContent = "Connexion réussie, redirection...";
      let userData = data.data || data.user; // Support pour les deux formats de réponse
      currentUser = {
        user_id: userData.id,
        nom: userData.nom,
        role: userData.role,
        entrepot: userData.entrepot,
        token: userData.token,
        photo: userData.photo || null,
      };
      sessionStorage.setItem("currentUser", JSON.stringify(userData));
      // Redirection vers /mouvements après 1 seconde
      setTimeout(() => {
        window.location.href = "/mouvements";
      }, 1000);
      stopLoading();
      return true;
    } else {
      loginMessage.classList.remove("text-green-600");
      loginMessage.classList.add("text-red-600");
      loginMessage.textContent = data.message || "Identifiants incorrects";

      stopLoading();
      setTimeout(() => {
        loginMessage.textContent = "";
      }, 3000);
      return false;
    }
  } catch (err) {
    console.error("Erreur login :", err);
    loginMessage.classList.remove("text-green-600");
    loginMessage.classList.add("text-red-600");
    loginMessage.textContent = "Impossible de contacter le serveur";
    stopLoading();
    setTimeout(() => {
      loginMessage.textContent = "";
    }, 3000);
    return false;
  } finally {
    // Masquer loader
    stopLoading();
    loginBtn.disabled = false;
  }
}

// Gestion clic du bouton
loginBtn.addEventListener("click", async (e) => {
  e.preventDefault();

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();

  if (!username || !password) {
    loginMessage.classList.remove("text-green-600");
    loginMessage.classList.add("text-red-600");
    loginMessage.textContent = "Veuillez remplir tous les champs";
    return;
  }

  await login(username, password);
});
async function loading() {
  const btnText = document.getElementById("btnText");
  const btnLoader = document.getElementById("btnLoader");
  btnText.classList.add("hidden");
  btnLoader.classList.add("spin");
  btnLoader.textContent = "autorenew";
  await new Promise((resolve) => setTimeout(resolve, 2000));
  btnText.classList.remove("hidden");
  btnLoader.classList.remove("spin");
  btnLoader.textContent = "arrow_forward";
}

function stopLoading() {
  const btnText = document.getElementById("btnText");
  const btnLoader = document.getElementById("btnLoader");
  btnText.classList.remove("hidden");
  btnLoader.classList.remove("spin");
  btnLoader.textContent = "arrow_forward";
}
