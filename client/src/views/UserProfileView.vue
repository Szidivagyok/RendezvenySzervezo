<template>
  <div class="profile-container container py-5">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="glass-card p-4 text-center shadow-sm">
          <div class="avatar-circle mb-3 mx-auto">
            <i class="bi bi-person-badge display-1 text-primary"></i>
          </div>
          <h2 class="twinkle-header">{{ userName || "Vendég" }}</h2>
          <p class="text-muted small mb-0">{{ userEmail }}</p>
          <hr />
          <div class="info-box text-start">
            <p class="mb-1"><strong>Szerepkör:</strong> {{ userRoleName }}</p>
            <p class="mb-0 text-secondary small">Saját ID: #{{ userId }}</p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="glass-card p-4 shadow-sm">
          <h3 class="section-title mb-4">
            <i class="bi bi-calendar-range me-2"></i>Saját foglalásaim
          </h3>

          <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Adatok letöltése...</p>
          </div>

          <div
            v-else-if="allOrdersSorted.length === 0"
            class="text-center py-5"
          >
            <p class="text-muted">Még nincs leadott foglalásod.</p>
            <router-link to="/orders" class="btn btn-outline-primary"
              >Foglalás indítása</router-link
            >
          </div>

          <div v-else class="order-list">
            <div
              v-for="order in allOrdersSorted"
              :key="order.id"
              class="order-card mb-3 p-3 border-start border-primary border-4 position-relative"
              @click="showDetails(order)"
            >
              <button
                v-if="isAdmin"
                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 shadow-sm admin-btn"
                @click.stop="confirmDelete(order.id)"
              >
                <i class="bi bi-trash3-fill"></i>
              </button>

              <div
                class="d-flex justify-content-between align-items-center pe-5 mb-2"
              >
                <div>
                  <span class="fw-bold text-primary"
                    >#{{ order.id }} Foglalás</span
                  >
                </div>
                <span class="small text-muted">
                  <i class="bi bi-calendar-check me-1"></i
                  >{{ formatDate(order.orderTime) }}
                </span>
              </div>

              <hr class="my-2 opacity-25" />

              <div class="row">
                <div class="col-6">
                  <span class="fw-medium"
                    ><i class="bi bi-people me-2"></i
                    >{{ order.howManyPeople }} fő</span
                  >
                </div>
                <div class="col-6 text-end">
                  <span class="badge bg-light text-dark border fw-normal">
                    {{
                      order.locationName || getLocationName(order.locationId)
                    }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="selectedOrder"
      class="modal-overlay"
      @click.self="selectedOrder = null"
    >
      <div class="glass-card modal-content p-4 shadow-lg">
        <div
          class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-2"
        >
          <h4 class="text-primary mb-0">Foglalás részletei</h4>
          <button class="btn-close" @click="selectedOrder = null"></button>
        </div>

        <div class="mb-3 text-muted small">
          Azonosító: <strong>#{{ selectedOrder.id }}</strong> | Dátum:
          <strong>{{ formatDate(selectedOrder.orderTime) }}</strong>
        </div>

        <div class="detail-item mb-2">
          <i class="bi bi-geo-alt text-primary me-2"></i>
          <strong>Helyszín:</strong>
          {{ selectedOrder.locationName || "Betöltés..." }}
        </div>
        <div class="detail-item mb-3">
          <i class="bi bi-people text-primary me-2"></i>
          <strong>Létszám:</strong> {{ selectedOrder.howManyPeople }} fő
        </div>

        <hr class="my-3" />

        <h6 class="fw-bold mb-3 text-secondary uppercase-label">
          Választott szolgáltatások:
        </h6>

        <div class="service-box mb-2">
          <div class="small text-muted italic">Étel menü:</div>
          <div class="fw-medium">
            <i class="bi bi-egg-fried me-2 text-danger"></i>
            {{ getServiceByCategory(selectedOrder, 2) }}
          </div>
        </div>

        <div class="service-box mb-2">
          <div class="small text-muted italic">Zene / DJ:</div>
          <div class="fw-medium">
            <i class="bi bi-music-note-beamed me-2 text-primary"></i>
            {{ getServiceByCategory(selectedOrder, 3) }}
          </div>
        </div>

        <div class="service-box mb-4">
          <div class="small text-muted italic">Dekoráció:</div>
          <div class="fw-medium">
            <i class="bi bi-palette me-2 text-success"></i>
            {{ getServiceByCategory(selectedOrder, 4) }}
          </div>
        </div>

        <button
          class="btn btn-primary w-100 py-2 shadow-sm"
          @click="selectedOrder = null"
        >
          Bezárás
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios"; // EZ HIÁNYZOTT!
import { mapState, mapActions } from "pinia";
import { useOrderStore } from "@/stores/orderStore";
import { useUserLoginLogoutStore } from "@/stores/userLoginLogoutStore";
import { useLocationStore } from "@/stores/locationStore";
import { useServiceStore } from "@/stores/serviceStore";

export default {
  data() {
    return { selectedOrder: null };
  },
  computed: {
    ...mapState(useUserLoginLogoutStore, ["item"]),
    ...mapState(useOrderStore, ["items", "loading"]),
    ...mapState(useLocationStore, { locations: "items" }),
    ...mapState(useServiceStore, { allServices: "items" }),

    userId() {
      return this.item?.id;
    },
    userName() {
      return this.item?.name;
    },
    userEmail() {
      return this.item?.email;
    },
    isAdmin() {
      return this.item?.role === 1;
    },
    userRoleName() {
      return this.isAdmin ? "Adminisztrátor" : "Megrendelő";
    },

    allOrdersSorted() {
      let filtered = this.items;
      if (!this.isAdmin)
        filtered = this.items.filter((o) => o.userId === this.userId);
      return [...filtered].sort((a, b) => b.id - a.id);
    },
  },
  methods: {
    ...mapActions(useOrderStore, ["getAll", "delete"]),
    ...mapActions(useLocationStore, { fetchLocations: "getAll" }),
    ...mapActions(useServiceStore, { fetchServices: "getAll" }),

    async showDetails(order) {
      try {
        // 1. Lekérjük az adatokat
        const response = await axios.get(
          `http://localhost:8000/api/booking-details/${order.id}`
        );

        // 2. MENTÉS ELŐTT nézd meg a konzolt (F12), hogy lásd mi jön vissza!
        console.log("Backend válasz:", response.data.data);

        this.selectedOrder = response.data.data;
      } catch (error) {
        console.error("Hiba történt:", error);
        this.selectedOrder = order;
      }
    },

    getServiceByCategory(order, typeId) {
      // Ellenőrizzük, hogy van-e egyáltalán order és services tömb
      if (!order || !order.services || !Array.isArray(order.services)) {
        return "Betöltés...";
      }

      // Megkeressük azt a szolgáltatást, aminek a serviceTypeId-ja egyezik
      // Tipp: Használjunk ==-t a === helyett, hátha a backend stringként küldi az ID-t
      const found = order.services.find((s) => s.serviceTypeId == typeId);

      // Ha megtaláltuk, visszaadjuk a nevét, ha nem, akkor a hibaüzenetet
      return found
        ? found.service
        : "Nincs ilyen típusú szolgáltatás választva";
    },

    async confirmDelete(id) {
      if (!this.isAdmin) return;
      if (confirm(`Biztosan törlöd a #${id} foglalást?`)) {
        try {
          await this.delete(id);
        } catch (e) {
          alert("Hiba történt.");
        }
      }
    },

    getLocationName(id) {
      const loc = this.locations.find((l) => l.id === id);
      return loc ? loc.locationName : `Helyszín #${id}`;
    },

    getServiceByCategory(order, typeId) {
      if (!order || !order.services) return "Nincs adat";
      const service = order.services.find((s) => s.serviceTypeId === typeId);
      return service ? service.service : "Nem kért szolgáltatás";
    },

    formatDate(d) {
      if (!d) return "Nincs megadva";
      return new Date(d).toLocaleDateString("hu-HU", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    },
  },
  async mounted() {
    await Promise.all([
      this.getAll(),
      this.fetchLocations(),
      this.fetchServices(),
    ]);
  },
};
</script>

<style scoped>
/* A stílusokat hagyd meg, ahogy voltak */
.profile-container {
  min-height: 85vh;
  background-color: #f8fafc;
}
.glass-card {
  background: white;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}
.section-title {
  color: #4f46e5;
  font-weight: bold;
  border-left: 5px solid #4f46e5;
  padding-left: 15px;
}
.order-card {
  background: #ffffff;
  border: 1px solid #edf2f7;
  cursor: pointer;
  transition: 0.2s;
}
.order-card:hover {
  background: #f1f5f9;
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(3px);
}
.modal-content {
  max-width: 450px;
  width: 90%;
  border-top: 5px solid #4f46e5;
}
.service-box {
  background: #f8fafc;
  padding: 10px 15px;
  border-radius: 8px;
  border-left: 3px solid #cbd5e1;
}
.uppercase-label {
  font-size: 0.75rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: #64748b;
}
.italic {
  font-style: italic;
  font-size: 0.85rem;
}
.info-box {
  background: #f1f5f9;
  padding: 10px;
  border-radius: 8px;
}
.twinkle-header {
  background: linear-gradient(45deg, #4f46e5, #ec4899);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: bold;
}
.avatar-circle {
  width: 100px;
  height: 100px;
  background: #eef2ff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #4f46e5;
}
</style>