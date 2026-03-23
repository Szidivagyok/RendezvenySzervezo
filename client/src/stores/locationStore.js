import { defineStore } from "pinia";
import locationService from "@/api/locationService"; // Feltételezve, hogy ez a neve
import { useSearchStore } from "./searchStore";

class Item {
  constructor(
    id = 0, 
    cityName = "", 
    zipCode = null, 
    street = "", 
    houseNumber = "", 
    locationName = "", 
    maxCapacity = 0, 
    minCapacity = 0, 
    priceSlashPerson = 0, 
    roomPriceSlashDay = 0
  ) {
    this.id = id;
    this.cityName = cityName;
    this.zipCode = zipCode;
    this.street = street;
    this.houseNumber = houseNumber;
    this.locationName = locationName;
    this.maxCapacity = maxCapacity;
    this.minCapacity = minCapacity;
    this.priceSlashPerson = priceSlashPerson;
    this.roomPriceSlashDay = roomPriceSlashDay;
  }
}

export const useLocationStore = defineStore("locations", {
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
        const response = await locationService.getAll();
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
        const response = await locationService.getById(id);
        this.item = response.data;
      } catch (err) {
        this.error = err;
      } finally {
        this.loading = false;
      }
    },

 // locationStore.js

async create(data) {
  this.loading = true;
  this.error = null;
  try {
    // Nagyon fontos: Minden mezőt bele kell tenni a payloadba, 
    // amit a Laravel és az adatbázis elvár!
    const payload = {
      locationName: data.locationName || "",
      cityName: data.cityName || "",
      zipCode: data.zipCode ? data.zipCode.toString() : "",
      street: data.street || "",
      houseNumber: data.houseNumber ? data.houseNumber.toString() : "",
      minCapacity: Number(data.minCapacity) || 0,
      maxCapacity: Number(data.maxCapacity) || 0,
      priceSlashPerson: Number(data.priceSlashPerson) || 0,
      roomPriceSlashDay: Number(data.roomPriceSlashDay) || 0
    };

    // DEBUG: Nézzük meg a konzolban, mi indul el a szerverre
    console.log("Küldés folyamatban...", payload);

    const response = await locationService.create(payload);
    
    // Frissítjük a listát, hogy az új elem is benne legyen
    await this.getAll(); 
    return true;
  } catch (err) {
    this.error = err;
    // Kiírjuk a konkrét hibaüzenetet a konzolra a könnyebb javításhoz
    console.error("Hiba a mentés során:", err.response?.data || err);
    return false;
  } finally {
    this.loading = false;
  }
},
   async update(id, data) {
  this.loading = true;
  try {
    const payload = {
      locationName: data.locationName,
      cityName: data.cityName,
      zipCode: data.zipCode ? data.zipCode.toString() : "",
      street: data.street,
      houseNumber: data.houseNumber ? data.houseNumber.toString() : "", // EZ KELL IDE IS!
      minCapacity: Number(data.minCapacity),
      maxCapacity: Number(data.maxCapacity),
      priceSlashPerson: Number(data.priceSlashPerson),
      roomPriceSlashDay: Number(data.roomPriceSlashDay)
    };

    await locationService.update(id, payload);
    await this.getAll(); // Lista frissítése
    return true;
  } catch (err) {
    this.error = err;
    console.error("Módosítási hiba:", err.response?.data || err);
    return false;
  } finally {
    this.loading = false;
  }
},

    async delete(id) {
      this.loading = true;
      try {
        await locationService.delete(id);
        await this.getAll();
        return true;
      } catch (err) {
        this.error = err;
        return false;
      } finally {
        this.loading = false;
      }
    }
  }
});