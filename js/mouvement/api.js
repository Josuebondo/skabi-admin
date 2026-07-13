class Api {
  static async obtenirMouvements(filters = {}) {
    try {
      const response = await fetch(
        "https://stock.skabi.shop/mouvement/getMouvementsAdminApi",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-API-KEY": "ADMIN_SECRET_2026",
          },
          body: JSON.stringify(filters),
        },
      );

      if (!response.ok) {
        const errorText = await response.text();
        console.error("Status:", response.status);
        console.error("Erreur serveur:", errorText);
        throw new Error("Erreur API");
      }

      return await response.json();
    } catch (error) {
      console.error("Erreur récupération mouvements:", error);
      return null;
    }
  }
}

// ✅ Utilisation
(async () => {
  const mouvements = await Api.obtenirMouvements();

  console.log("Résultat:", mouvements);
})();
