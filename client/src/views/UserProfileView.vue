<template>
  <div class="profile-page py-5">
    <div class="container">
      <div class="row g-4 justify-content-center">
        
        <div class="col-lg-4">
          <div class="glass-card profile-sidebar text-center p-4">
            <div class="avatar-container mb-4 mx-auto">
              <div class="avatar-blob"></div>
              <i class="bi bi-person-badge text-white"></i>
            </div>
            
            <h2 class="user-display-name mb-1">{{ userName || "Vendég" }}</h2>
            <div class="user-email-tag mb-4">{{ userEmail }}</div>
            
            <div class="user-stats-box text-start p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="label">Rang:</span>
                <span class="badge-role" :class="isAdmin ? 'admin' : 'user'">
                  {{ userRoleName }}
                </span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="label">Azonosító:</span>
                <span class="value">#{{ userId }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="glass-card main-content p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3 class="section-title">
                <i class="bi bi-calendar-check-fill me-2"></i>Saját foglalásaim
              </h3>
              <div v-if="allOrdersSorted.length" class="order-count-badge">
                {{ allOrdersSorted.length }} foglalás
              </div>
            </div>

            <div v-if="loading" class="loader-wrapper py-5">
              <div class="spinner-grow text-primary" role="status"></div>
              <p>Adatok szinkronizálása...</p>
            </div>

            <div v-else-if="allOrdersSorted.length === 0" class="empty-state py-5 text-center">
              <div class="empty-icon mb-3">
                <i class="bi bi-journal-x"></i>
              </div>
              <p>Még nem adtál le foglalást.</p>
              <router-link to="/orders" class="btn btn-primary-gradient rounded-pill">
                Foglalás indítása
              </router-link>
            </div>

            <div v-else class="order-grid">
              <div
                v-for="order in allOrdersSorted"
                :key="order.id"
                class="order-item-card"
                @click="showDetails(order)"
              >
                <div class="order-card-header">
                  <span class="order-id">#{{ order.id }}</span>
                  <span class="order-date">{{ formatDate(order.orderTime) }}</span>
                </div>
                
                <div class="order-card-body mt-3">
                  <div class="info-pill">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ order.locationName || getLocationName(order.locationId) }}
                  </div>
                  <div class="info-pill">
                    <i class="bi bi-people-fill"></i>
                    {{ order.howManyPeople }} fő
                  </div>
                </div>

                <button
                  v-if="isAdmin"
                  class="btn-delete-mini"
                  @click.stop="confirmDelete(order.id)"
                  title="Törlés"
                >
                  <i class="bi bi-trash3"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Transition name="scale">
      <div v-if="selectedOrder" class="modal-backdrop-blur" @click.self="selectedOrder = null">
        <div class="modern-modal-card">
          <div class="modal-header-gradient">
            <h4>Foglalás részletei</h4>
            <button class="btn-close-white" @click="selectedOrder = null">&times;</button>
          </div>
          
          <div class="modal-body p-4">
            <div class="id-date-bar mb-4">
              <span>Azonosító: <b>#{{ selectedOrder.id }}</b></span>
              <span>Dátum: <b>{{ formatDate(selectedOrder.orderTime) }}</b></span>
            </div>

            <div class="detail-grid">
              <div class="detail-item">
                <label>Helyszín</label>
                <p><i class="bi bi-map me-2"></i>{{ selectedOrder.locationName || "Helyszín betöltése..." }}</p>
              </div>
              <div class="detail-item">
                <label>Létszám</label>
                <p><i class="bi bi-people me-2"></i>{{ selectedOrder.howManyPeople }} fő</p>
              </div>
            </div>

            <hr class="my-4">

            <h6 class="services-title">VÁLASZTOTT SZOLGÁLTATÁSOK</h6>
            <div class="service-tags-container">
              <div class="service-tag food">
                <i class="bi bi-egg-fried"></i>
                <span>{{ getServiceByCategory(selectedOrder, 2) }}</span>
              </div>
              <div class="service-tag music">
                <i class="bi bi-music-note-beamed"></i>
                <span>{{ getServiceByCategory(selectedOrder, 3) }}</span>
              </div>
              <div class="service-tag deco">
                <i class="bi bi-palette"></i>
                <span>{{ getServiceByCategory(selectedOrder, 4) }}</span>
              </div>
            </div>

            <button class="btn btn-dark w-100 rounded-pill mt-4 py-2" @click="selectedOrder = null">
              Bezárás
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import axios from "axios";
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

    userId() { return this.item?.id; },
    userName() { return this.item?.name; },
    userEmail() { return this.item?.email; },
    isAdmin() { return this.item?.role === 1; },
    userRoleName() { return this.isAdmin ? "Adminisztrátor" : "Megrendelő"; },

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
        const response = await axios.get(`http://localhost:8000/api/booking-details/${order.id}`);
        this.selectedOrder = response.data.data;
      } catch (error) {
        console.error("Hiba:", error);
        this.selectedOrder = order;
      }
    },

    getServiceByCategory(order, typeId) {
      if (!order || !order.services) return "Nincs választva";
      const found = order.services.find((s) => s.serviceTypeId == typeId);
      return found ? found.service : "Nem igényelt";
    },

    async confirmDelete(id) {
      if (!this.isAdmin) return;
      if (confirm(`Biztosan törlöd a #${id} foglalást?`)) {
        try { await this.delete(id); } catch (e) { alert("Hiba történt."); }
      }
    },

    getLocationName(id) {
      const loc = this.locations.find((l) => l.id === id);
      return loc ? loc.locationName : `Helyszín #${id}`;
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
    await Promise.all([this.getAll(), this.fetchLocations(), this.fetchServices()]);
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

.profile-page {
  font-family: 'Inter', sans-serif;
  background-color: #f0f2f5;
  min-height: 100vh;
}

/* Glassmorphism kártyák */
.glass-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

/* Avatar animált blob háttérrel */
.avatar-container {
  position: relative;
  width: 120px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-blob {
  position: absolute;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, #4f46e5, #ec4899);
  border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
  animation: blob-anim 7s infinite alternate;
}

@keyframes blob-anim {
  0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
  100% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
}

.avatar-container i {
  position: relative;
  font-size: 4rem;
  z-index: 1;
}

/* Felhasználói infók */
.user-display-name {
  font-weight: 700;
  color: #1e293b;
}

.user-email-tag {
  color: #64748b;
  font-size: 0.9rem;
  background: #f1f5f9;
  display: inline-block;
  padding: 2px 12px;
  border-radius: 15px;
}

.user-stats-box {
  background: #f8fafc;
  border-radius: 15px;
  border: 1px solid #e2e8f0;
}

.badge-role {
  padding: 4px 10px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-role.admin { background: #e0e7ff; color: #4338ca; }
.badge-role.user { background: #fef3c7; color: #92400e; }

/* Foglalás lista */
.section-title {
  font-weight: 700;
  color: #334155;
  font-size: 1.25rem;
}

.order-count-badge {
  background: #4f46e5;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
}

.order-item-card {
  background: white;
  border-radius: 15px;
  padding: 1.25rem;
  border: 1px solid #f1f5f9;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  margin-bottom: 1rem;
}

.order-item-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.06);
  border-color: #4f46e5;
}

.order-id { font-weight: 700; color: #4f46e5; }
.order-date { font-size: 0.85rem; color: #94a3b8; float: right; }

.info-pill {
  display: inline-flex;
  align-items: center;
  background: #f8fafc;
  padding: 5px 12px;
  border-radius: 10px;
  margin-right: 10px;
  font-size: 0.9rem;
  color: #475569;
}

.info-pill i { margin-right: 6px; color: #4f46e5; }

/* Modal */
.modal-backdrop-blur {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.7);
  backdrop-filter: blur(8px);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modern-modal-card {
  background: white;
  width: 95%;
  max-width: 450px;
  border-radius: 25px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
}

.modal-header-gradient {
  background: linear-gradient(45deg, #4f46e5, #9333ea);
  padding: 20px;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-close-white {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
}

.service-tag {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 12px;
  margin-bottom: 10px;
  font-weight: 500;
}

.service-tag.food { background: #fff1f2; color: #be123c; }
.service-tag.music { background: #eff6ff; color: #1d4ed8; }
.service-tag.deco { background: #9df1a4; color: rgba(0, 0, 0, 0.774); }
</style>