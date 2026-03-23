import apiClient from './axiosClient'; 
const route = '/services';

export default {
     // GET: Összes rekord lekérése
  async getAll() {
    return await apiClient.get(`${route}`);
  },

  // GET: Egy rekord (ID alapján)
  async getById(id) {
    const url = `${route}/${id}`
    return await apiClient.get(url);
  },

 // POST: Új rekord posztolás
async create(data) {
  // Nem módosítjuk az eredeti objektumot, csak küldünk egy tiszta másolatot
  console.log("ServiceService-be érkezett adat:", data);
  return await apiClient.post(`${route}`, data);
},

// PUT: Módosítás
async update(id, data) {
  // Módosításnál is csak küldjük az adatot a PATCH kérésben
  return await apiClient.patch(`${route}/${id}`, data);
},
  // DELETE: Törlés
  async delete(id) {
    return await apiClient.delete(`${route}/${id}`);
  }
};
