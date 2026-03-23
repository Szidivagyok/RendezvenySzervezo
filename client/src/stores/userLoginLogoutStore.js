import { defineStore } from "pinia";
import service from "@/api/userLoginLogoutService";
import { useToastStore } from "./toastStore";
import { useOrderStore } from "./orderStore";

export const useUserLoginLogoutStore = defineStore("userLoginLogout", {
  state: () => ({
    item: JSON.parse(localStorage.getItem("user_data")) || null,
    myOrders: [],
    loading: false,
    error: null,
    rolNames: ["Admin", "Megrendelő"],
  }),

  getters: {
    token: (state) => state.item?.token || null,
    role: (state) => state.item?.role || null,
    userId: (state) => state.item?.id || null,
    userName: (state) => state.item?.name || null,
    isLoggedIn: (state) => state.item != null,
    userNameWithRole(state) {
      if (!state.item) return null;
      return `${state.item.name}: ${state.rolNames[state.item.role - 1]}`;
    },
  },

  actions: {
    canAccess(requiredRoles) {
      if (!requiredRoles || requiredRoles.length === 0) return true;
      if (!this.item || !this.isLoggedIn) return false;
      const userRole = Number(this.item.role);
      return requiredRoles.map(Number).includes(userRole);
    },

    // JAVÍTOTT LOGIN: Bejelentkezés után azonnal betölti a rendeléseket
    async login(data) {
      try {
        this.loading = true;
        this.error = null;
        const response = await service.login(data);
        this.item = response.data;
        localStorage.setItem("user_data", JSON.stringify(response.data));

        // Frissítjük a rendeléseket az új felhasználóhoz
        const orderStore = useOrderStore();
        await orderStore.getAll(); // Letölti az aktuális rendeléseket az adatbázisból
        
        return true;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        this.loading = true;
        const orderStore = useOrderStore();
        
        // Kijelentkezéskor csak a memóriát ürítjük (az adatbázisban megmarad!)
        orderStore.clearStore();
        this.myOrders = [];
        
        await service.logout();
      } catch (err) {
        console.error("Szerver oldali logout hiba, de a lokális adatokat töröljük.");
      } finally {
        this.item = null;
        localStorage.removeItem("user_data");
        this.loading = false;
        
        const toastStore = useToastStore();
        toastStore.messages.push("Sikeres kijelentkezés");
        toastStore.show("Success");
      }
    },

    async fetchMyOrders() {
      try {
        this.loading = true;
        const response = await service.getMyOrders();
        this.myOrders = response.data;
      } catch (err) {
        console.error("Hiba a rendelések lekérésekor:", err);
      } finally {
        this.loading = false;
      }
    },

    async getMeRefresh() {
      try {
        const response = await service.getMeRefresh();
        if (this.item) {
          this.item.name = response.data.name;
          this.item.email = response.data.email;
        }
      } catch (err) {
        throw err;
      }
    }
  },
});