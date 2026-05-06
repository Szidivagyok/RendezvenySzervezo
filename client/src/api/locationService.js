import apiClient from './axiosClient'; 
const route = '/locations'; 

export default {
  async getAll() {
    return await apiClient.get(`${route}`);
  },

  async getById(id) {
    const url = `${route}/${id}`;
    return await apiClient.get(url);
  },

  async create(data) {
    console.log("LocationService-be érkezett adat:", data);
    return await apiClient.post(`${route}`, data);
  },

  async update(id, data) {
    console.log(`LocationService frissítés (ID: ${id}):`, data);
    return await apiClient.patch(`${route}/${id}`, data);
  },

  async delete(id) {
    return await apiClient.delete(`${route}/${id}`);
  }
};