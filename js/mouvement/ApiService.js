// api.js

export class ApiService {
  constructor(baseURL) {
    this.baseURL = baseURL;
  }

  // Méthode pour récupérer des données depuis une route spécifique
  async fetchData(endpoint) {
    try {
      const response = await fetch(`${this.baseURL}${endpoint}`);
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Erreur lors de l'appel API :", error);
      return null;
    }
  }

  // Exemple : méthode POST
  async postData(endpoint, header, body) {
    try {
      const response = await fetch(`${this.baseURL}${endpoint}`, {
        method: "POST",
        headers: { "Content-Type": "application/json", ...header },
        body: JSON.stringify(body),
      });
      return await response.json();
    } catch (error) {
      console.error("Erreur POST API :", error);
      return null;
    }
  }
}
