import apiClient from './axiosClient'; 

// FIGYELEM: Itt /locations kell legyen, nem /pictures!
const route = '/locations'; 

export default {
  // GET: Összes helyszín lekérése
  async getAll() {
    return await apiClient.get(`${route}`);
  },

  // GET: Egy helyszín lekérése (ID alapján)
  async getById(id) {
    const url = `${route}/${id}`;
    return await apiClient.get(url);
  },

  // POST: Új helyszín mentése
  async create(data) {
    console.log("LocationService-be érkezett adat:", data);
    return await apiClient.post(`${route}`, data);
  },

  // PATCH: Módosítás
  async update(id, data) {
    console.log(`LocationService frissítés (ID: ${id}):`, data);
    return await apiClient.patch(`${route}/${id}`, data);
  },

  // DELETE: Törlés
  async delete(id) {
    return await apiClient.delete(`${route}/${id}`);
  }
};