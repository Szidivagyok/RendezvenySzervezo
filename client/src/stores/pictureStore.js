import { defineStore } from "pinia";
import { useSearchStore } from "./searchStore";
import service from "@/api/pictureService";

class Item {
  constructor(id = 0, pictureName = "", serviceId = "") {
    this.id = id;
    this.pictureName = pictureName;
    this.serviceId = serviceId;
  }
}

export const usePictureStore = defineStore("pictures", {
  state: () => ({
    item: new Item(),
    items: [new Item()],
    loading: false,
    error: null,
    sortColumn: "id",
    sortDirection: "asc",
    searchStore: useSearchStore(),
  }),
  getters: {
    getItemsLength() { return this.items.length; }
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
        throw err;
      } finally { this.loading = false; }
    },

    // --- Ezekhez NEM NYÚLTUNK, az AboutView miatt ---
    async getById(id) {
      this.loading = true;
      try {
        const response = await service.getById(id);
        this.item = response.data;
      } catch (err) { this.error = err; throw err; }
      finally { this.loading = false; }
    },

    async getLocationpicturesById(id) {
      this.loading = true;
      try {
        const response = await service.getLocationpicturesById(id);
        this.items = response.data;
      } catch (err) { this.error = err; throw err; }
      finally { this.loading = false; }
    },

    async getPicturesByServiceId(id) {
      this.loading = true;
      try {
        const response = await service.getPicturesByServiceId(id);
        this.items = response.data;
      } catch (err) { this.error = err; this.items = []; }
      finally { this.loading = false; }
    },
    // ----------------------------------------------

    async create(data) {
      this.loading = true;
      this.error = null;
      try {
        // Tisztítjuk az adatot a küldés előtt
        const payload = {
          pictureName: data.pictureName,
          serviceId: data.serviceId
        };
        await service.create(payload);
        
        // Frissítjük a listát az admin felületen használt keresési feltételekkel
        const response = await service.getAllSortSearch(
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord
        );
        this.items = response.data;
        return true;
      } catch (err) {
        // Itt figyeltem a specifikus hibaüzenetre, amit írtál
        this.error = err.response?.data?.errors?.pictureName ? err.response.data.errors.pictureName[0] : "Hiba a mentés során";
        return false;
      } finally { this.loading = false; }
    },

    async update(id, updateData) {
      this.loading = true;
      this.error = null;
      try {
        await service.update(id, updateData);
        const response = await service.getAllSortSearch(
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord
        );
        this.items = response.data;
        return true;
      } catch (err) {
        this.error = err;
        return false;
      } finally { this.loading = false; }
    },

    async delete(id) {
      this.loading = true;
      try {
        await service.delete(id);
        const response = await service.getAllSortSearch(
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord
        );
        this.items = response.data;
        return true;
      } catch (err) {
        this.error = err;
        return false;
      } finally { this.loading = false; }
    },
  },
});