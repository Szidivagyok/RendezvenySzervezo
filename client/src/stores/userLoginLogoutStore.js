import { defineStore } from "pinia";
// import { useToastStore } from "@/stores/toastStore";
import service from "@/api/userLoginLogoutService";
import { useToastStore } from "./toastStore";

export const useUserLoginLogoutStore = defineStore("userLoginLogout", {
  //Ezek a változók
  state: () => ({
    item: JSON.parse(localStorage.getItem("user_data")) || null,
    myOrders: [],
    loading: false,
    error: null,
    rolNames: ["Admin", "Megrendelő"],
  }),
  //valamilyen formában visszaadja
  getters: {
    token() {
      if (!this.item) {
        return null;
      }
      return this.item.token;
    },
    role() {
      if (!this.item) {
        return null;
      }
      return this.item.role;
    },
    userName() {
      if (!this.item) {
        return null;
      }
      return this.item.name;
    },
    userNameWithRole() {
      if (!this.item) {
        return null;
      }
      const userInfo = `${this.item.name}: ${this.rolNames[this.item.role - 1]}`;
      return userInfo;
    },
    isLoggedIn() {
      return this.item != null ? true : false;
    },
  },
  //csinál vele valamit
  actions: {
 canAccess(requiredRoles) {
  if (!requiredRoles || requiredRoles.length === 0) return true;
  if (!this.item || !this.isLoggedIn) return false;

  const userRole = Number(this.item.role);
  // Itt a .map(Number) biztosítja, hogy a [1, 2] tömb elemei is biztosan számok legyenek
  return requiredRoles.map(Number).includes(userRole);
},
    async login(data) {
      try {
        this.loading = true;
        this.error = null;
        const response = await service.login(data);
        this.item = response.data;
        localStorage.setItem("user_data", JSON.stringify(response.data));
        // const toastStore = useToastStore();
        // toastStore.messages.push("Sikeres bejelentkezés");
        // toastStore.show("Success");
        return true;
      } catch (err) {
        this.error = err;
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },
    async logout() {
      try {
        this.error = null;
        this.loading = true;
        const response = await service.logout();
        this.item = null;
        // Törlés localStorage-ból
        localStorage.removeItem("user_data");
        const toastStore = useToastStore();
        toastStore.messages.push("Sikeres kijelenkezés");
        toastStore.show("Success");

        return true;
      } catch (err) {
        this.error = err;
        this.item = null;
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },
    async getMeRefresh() {
      try {
        this.error = null;
        this.loading = true;
        const response = await service.getMeRefresh();
        this.item.name = response.data.name;
        this.item.email = response.data.email;
        return true;
      } catch (err) {
        this.error = err;
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },
    async fetchMyOrders() {
  try {
    this.loading = true;
    const response = await service.getMyOrders();
    this.myOrders = response.data; // Elmentjük a listát
  } catch (err) {
    console.error("Hiba a rendelések lekérésekor:", err);
  } finally {
    this.loading = false;
  }
},
  },
});
