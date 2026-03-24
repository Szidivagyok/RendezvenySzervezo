<template>
  <div class="profile-container container py-5">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="glass-card p-4 text-center shadow-sm">
          <div class="avatar-circle mb-3 mx-auto">
            <i class="bi bi-person-badge display-1 text-primary"></i>
          </div>
          <h2 class="twinkle-header">{{ userName || 'Vendég' }}</h2>
          <p class="text-muted small mb-0">{{ userEmail }}</p>
          <hr>
          <div class="info-box text-start">
            <p class="mb-1"><strong>Szerepkör:</strong> {{ userRoleName }}</p>
            <p class="mb-0 text-secondary small">Saját ID: #{{ userId }}</p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="glass-card p-4 shadow-sm">
          <h3 class="section-title mb-4">
            <i class="bi bi-calendar-range me-2"></i>Összes foglalás listája
          </h3>
          
          <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
          </div>

          <div v-else-if="items.length === 0" class="text-center py-5">
            <p class="text-muted">Nincsenek elérhető foglalások.</p>
          </div>

          <div v-else class="order-list">
            <div v-for="order in allOrdersSorted" :key="order.id" 
                 class="order-card mb-3 p-3 border-start border-primary border-4 position-relative"
                 @click="showDetails(order)">
              
              <button 
                v-if="isAdmin" 
                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 shadow-sm admin-btn"
                @click.stop="confirmDelete(order.id)"
              >
                <i class="bi bi-trash3-fill"></i>
              </button>

              <div class="d-flex justify-content-between align-items-center pe-5 mb-2">
                <div>
                  <span class="fw-bold text-primary">#{{ order.id }} Foglalás</span>
                  <span class="ms-3 badge bg-secondary-subtle text-secondary border fw-normal">
                    Ügyfél: #{{ order.userId }}
                  </span>
                </div>
                <span class="small text-muted">
                  <i class="bi bi-clock me-1"></i>{{ formatDate(order.orderTime) }}
                </span>
              </div>
              
              <hr class="my-2 opacity-25">

              <div class="row">
                <div class="col-6">
                  <span class="fw-medium"><i class="bi bi-people me-2"></i>{{ order.howManyPeople }} fő</span>
                </div>
                <div class="col-6 text-end">
                  <span class="badge bg-light text-dark border fw-normal">{{ getLocationName(order.locationId) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedOrder" class="modal-overlay" @click.self="selectedOrder = null">
      <div class="glass-card modal-content p-4 shadow-lg">
        <h4 class="mb-3 text-primary">Rendelés részletei</h4>
        <div class="mb-2"><strong>Foglalás azonosító:</strong> #{{ selectedOrder.id }}</div>
        <div class="mb-2"><strong>Ügyfél azonosító:</strong> #{{ selectedOrder.userId }}</div>
        <div class="mb-2"><strong>Helyszín:</strong> {{ getLocationName(selectedOrder.locationId) }}</div>
        <div class="mb-4"><strong>Létszám:</strong> {{ selectedOrder.howManyPeople }} fő</div>
        <button class="btn btn-primary w-100" @click="selectedOrder = null">Rendben</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useOrderStore } from '@/stores/orderStore';
import { useUserLoginLogoutStore } from '@/stores/userLoginLogoutStore';
import { useLocationStore } from '@/stores/locationStore';

export default {
  data() { return { selectedOrder: null }; },
  computed: {
    ...mapState(useUserLoginLogoutStore, ['item']),
    ...mapState(useOrderStore, ['items', 'loading']),
    ...mapState(useLocationStore, { locations: 'items' }),

    userId() { return this.item?.id; },
    userName() { return this.item?.name; },
    userEmail() { return this.item?.email; },
    isAdmin() { return this.item?.role === 1; },
    userRoleName() { return this.isAdmin ? 'Adminisztrátor' : 'Megrendelő'; },
    
    allOrdersSorted() {
      return [...this.items].sort((a, b) => b.id - a.id);
    }
  },
  methods: {
    ...mapActions(useOrderStore, ['getAll', 'delete']), 
    ...mapActions(useLocationStore, { fetchLocations: 'getAll' }),
    
    showDetails(order) { this.selectedOrder = order; },

    async confirmDelete(id) {
      if (!this.isAdmin) return;
      if (confirm(`ADMIN: Biztosan törlöd a #${id} foglalást?`)) {
        try {
          await this.delete(id);
        } catch (error) {
          alert("Hiba a törlés során.");
        }
      }
    },
    
    getLocationName(id) {
      return this.locations.find(l => l.id === id)?.locationName || `Helyszín #${id}`;
    },
    
    formatDate(d) {
      if (!d) return 'Nincs megadva';
      return new Date(d).toLocaleDateString('hu-HU');
    }
  },
  async mounted() {
    await Promise.all([this.getAll(), this.fetchLocations()]);
  }
}
</script>

<style scoped>
.profile-container { min-height: 85vh; background-color: #f8fafc; }
.glass-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
.section-title { color: #4f46e5; font-weight: bold; border-left: 5px solid #4f46e5; padding-left: 15px; }
.order-card { 
  background: #ffffff; 
  border: 1px solid #edf2f7; 
  cursor: pointer; 
  transition: 0.2s; 
}
.order-card:hover { background: #f1f5f9; transform: translateY(-2px); }

/* A törlés gomb és a tartalom szétválasztása */
.admin-btn { z-index: 5; padding: 5px 10px; }
.pe-5 { padding-right: 4rem !important; }

.modal-overlay { 
  position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
  background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; 
}
.modal-content { max-width: 400px; width: 90%; }
.info-box { background: #f1f5f9; padding: 10px; border-radius: 8px; }
</style>