import { defineStore } from "pinia";
import service from "@/api/orderService";
import axios from 'axios';

class Item {
  constructor(id = 0, userId = null, locationId = null, howManyPeople = 1, howManyDays = 1, orderTime = "") {
    this.id = id;
    this.userId = userId;
    this.locationId = locationId;
    this.howManyPeople = howManyPeople;
    this.howManyDays = howManyDays;
    this.orderTime = orderTime;
  }
}

export const useOrderStore = defineStore("orders", {
  state: () => ({
    item: new Item(),
    items: [],
    loading: false,
    error: null,
  }),

  actions: {
    clearStore() {
      this.items = [];
      this.item = new Item();
    },

    async getAll() {
      this.loading = true;
      try {
        const response = await service.getAll();
        this.items = response.data;
      } catch (err) {
        this.error = "Hiba a letöltés során.";
      } finally {
        this.loading = false;
      }
    },

   async createComplexOrder(orderPayload, selectedExtras) {
  this.loading = true;
  try {
    const response = await service.create(orderPayload);
    const newOrderId = response.data.id;

    const userDataRaw = localStorage.getItem('user_data');
    let token = null;

    if (userDataRaw) {
      const userData = JSON.parse(userDataRaw); 
      token = userData.token; 
    }

    if (selectedExtras && selectedExtras.length > 0) {
      for (const extra of selectedExtras) {
        await axios.post('http://localhost:8000/api/orderServices', { 
            orderId: newOrderId,
            serviceId: extra.id
        }, {
            headers: {
                Authorization: `Bearer ${token}` 
            }
        });
      }
    }
    
    return response.data;
  } catch (error) {
    console.error("Mentési hiba:", error.response?.data || error);
    throw error;
  } finally {
    this.loading = false;
  }
},

    async create(data) {
      this.loading = true;
      try {
        const response = await service.create(data);
        this.item = response.data;
        await this.getAll(); 
        return response.data;
      } catch (err) {
        this.error = "Hiba a mentés során.";
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async delete(id) {
      this.loading = true;
      try {
        await service.delete(id);
        await this.getAll(); 
        return true;
      } catch (err) {
        this.error = "Csak adminisztrátor törölhet!";
        throw err;
      } finally {
        this.loading = false;
      }
    }
  }
});