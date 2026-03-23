import { defineStore } from "pinia";
import service from "@/api/serviceService";
import { useSearchStore } from "./searchStore";

class Item {
  constructor(id = 0, service = "", serviceTypeId = null, price = 0) {
    this.id = id;
    this.service = service; // Ez a kulcs jelenik meg a táblázatban!
    this.serviceTypeId = serviceTypeId;
    this.price = price;
  }
}

export const useServiceStore = defineStore("services", {
  state: () => ({
    item: new Item(),
    items: [],
    loading: false,
    error: null,
    searchStore: useSearchStore(),
  }),
  getters: {
    getItemsLength: (state) => state.items.length
  },
  actions: {
    clearItem() { this.item = new Item(); },

    async getAll() {
      this.loading = true;
      try {
        const response = await service.getAll();
        this.items = response.data;
      } catch (err) {
        this.error = err;
      } finally {
        this.loading = false;
      }
    },

    async getById(id) {
      this.loading = true;
      try {
        const response = await service.getById(id);
        this.item = response.data;
      } catch (err) {
        this.error = err;
      } finally {
        this.loading = false;
      }
    },

 async create(data) {
  this.loading = true;
  try {
    // A hibaüzenet szerint a backend PONTOSAN ezt a kulcsot várja: serviceTypeId
    const payload = {
      service: data.service,
      price: Number(data.price),
      serviceTypeId: data.serviceTypeId // Ez az, ami hiányzott az SQL-ből!
    };

    console.log("Store küldésre előkészítve:", payload);
    
    await service.create(payload);
    await this.getAll(); 
    return true;
  } catch (err) {
    this.error = err;
    return false;
  } finally {
    this.loading = false;
  }
},

    async update(id, updateData) {
      this.loading = true;
      try {
        await service.update(id, updateData);
        await this.getAll();
        return true;
      } catch (err) {
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
        throw err;
      } finally {
        this.loading = false;
      }
    }
  }
});