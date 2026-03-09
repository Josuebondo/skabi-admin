import { ApiService } from "./ApiService.js";

const api = new ApiService("https://stock.skabi.shop/");

let bons = [];
let filteredBons = [];
let currentPage = 1;
let pageSize = 32; // Nombre de lignes par page

