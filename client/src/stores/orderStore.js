import { defineStore } from "pinia";
import { useSearchStore } from "./searchStore";
import service from "@/api/orderService";

// Az Item osztály segít abban, hogy mindig legyen egy tiszta alapobjektumunk
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
    item: new Item(),       // Az éppen szerkesztett vagy frissen leadott rendelés
    items: [],              // Az összes rendelés listája
    loading: false,         // Betöltés jelző (spinnerhez)
    error: null,            // Hibaüzenetek tárolása
    sortColumn: "id",       // Alapértelmezett rendezés
    sortDirection: "asc",
    searchStore: useSearchStore(), // Keresési kulcsszavak elérése
  }),

  getters: {
    // Visszaadja a listában lévő elemek számát
    getItemsLength(state) {
      return state.items.length;
    },
    // Segéd-getter: visszaadja csak az aktuális user rendeléseit
    // (Használata: store.getMyOrders(userId))
    getMyOrders: (state) => (userId) => {
      return state.items.filter(order => order.userId === userId);
    }
  },

  actions: {
    // Alaphelyzetbe állítja az aktuális itemet
    clearItem() {
      this.item = new Item();
    },

    // 1. ÖSSZES LEKÉRÉSE (Egyszerű listázáshoz)
    async getAll() {
      this.loading = true;
      this.error = null;
      try {
        const response = await service.getAll();
        this.items = response.data;
      } catch (err) {
        this.error = err.response?.data?.message || "Hiba a letöltés során.";
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // 2. RENDEZETT ÉS KERESETT LEKÉRÉS (Admin táblázathoz)
    async getAllSortSearch(column = "id", direction = null) {
      this.loading = true;
      this.error = null;
      this.sortColumn = column;
      
      // Ha nincs irány megadva, váltogatja az asc/desc között
      if (!direction) {
        direction = this.sortColumn === column && this.sortDirection === "asc" ? "desc" : "asc";
      }
      this.sortDirection = direction;

      try {
        const response = await service.getAllSortSearch(
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord
        );
        this.items = response.data;
      } catch (err) {
        this.error = err.response?.data?.message || "Szerver hiba a kereséskor.";
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // 3. EGY RENDELÉS LEKÉRÉSE ID ALAPJÁN
    async getById(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await service.getById(id);
        this.item = response.data;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // 4. LÉTREHOZÁS (Ezt hívjuk meg az OrdersView-ban a gombnyomásra!)
    async create(data) {
      this.loading = true;
      this.error = null;
      try {
        const response = await service.create(data);
        // Frissítjük a helyi listát is, hogy azonnal látszódjon a profilban
        await this.getAll(); 
        return response.data;
      } catch (err) {
        this.error = err.response?.data?.errors || "Sikertelen mentés.";
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // 5. MÓDOSÍTÁS
    async update(id, updateData) {
      this.loading = true;
      this.error = null;
      try {
        await service.update(id, updateData);
        await this.getAllSortSearch(this.sortColumn, this.sortDirection);
        return true;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // 6. TÖRLÉS
    async delete(id) {
      this.loading = true;
      this.error = null;
      try {
        await service.delete(id);
        await this.getAllSortSearch(this.sortColumn, this.sortDirection);
        return true;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    }
  },
});