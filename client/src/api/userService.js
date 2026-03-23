import apiClient from './axiosClient'; 
const route = '/users';

export default {
  // GET: Összes rekord lekérése
  async getAllSortSearch(column, direction, search='') {
    const route = `/userssortsearch/${column}/${direction}/${search}`
    return await apiClient.get(`${route}`);
  },
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
  },

 // userService.js
async changePassword(id, data) {
  // Az api.php-ban: Route::patch('users/{id}', [UserController::class, 'update'])
  // Fontos a / jel az elején!
  return await apiClient.patch(`/users/${id}`, data);
}
};