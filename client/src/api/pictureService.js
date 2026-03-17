import apiClient from './axiosClient';
const route = '/pictures';
 
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
 
  async getLocationpicturesById(id) {
    const url = `locationpictures/${id}`
    return await apiClient.get(url);
  },

  async getPicturesByServiceId(id) {
    // Ez hívja meg a Laravel-t a /api/servicepictures/{id} címen
    return await apiClient.get(`/servicepictures/${id}`);
  },
  // POST: Új rekord posztolás
  async create(data) {
    delete data.id; //id kulcsot kiveszi az objektumból
    return await apiClient.post(`${route}`, data);
  },
 
  // PUT: Módosítás
  async update(id, data) {
    delete data.id; //id kulcsot kiveszi az objektumból
    return await apiClient.patch(`${route}/${id}`, data);
  },
 
  // DELETE: Törlés
  async delete(id) {
    return await apiClient.delete(`${route}/${id}`);
  }
};
 