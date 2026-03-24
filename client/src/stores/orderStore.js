import { defineStore } from "pinia";
import service from "@/api/orderService";

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
        // 1. Orders mentése (Fő tábla)
        const response = await service.create(orderPayload);
        
        // 2. ID figyelése (A válaszból kinyerjük a friss ID-t)
        const newOrderId = response.data.id;

        // 3. Szolgáltatások összegyűjtése és 4. szolgáltatások egyenkénti mentése
        if (selectedExtras && selectedExtras.length > 0) {
          for (const extra of selectedExtras) {
            // Itt a kapcsolótáblába (order_services) mentünk
            await axios.post('/api/order_services', {
              orderId: newOrderId,
              serviceId: extra.id
            });
          }
        }
        
        return response.data;
      } catch (error) {
        console.error("Mentési hiba:", error);
        throw error; // Továbbadjuk a View-nak a hibaüzenetet
      } finally {
        this.loading = false;
      }
    },

    // EZ A RÉSZ HIÁNYOZHATOTT VAGY NEM FRISSÍTETT:
    async create(data) {
      this.loading = true;
      try {
        const response = await service.create(data);
        this.item = response.data;
        // FONTOS: Mentés után újra le kell kérni az összeset, 
        // hogy a profil oldaladon látszódjon az új elem!
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
        console.log("orderStore", id);
        
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