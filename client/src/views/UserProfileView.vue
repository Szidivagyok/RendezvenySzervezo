<template>
  <div class="profile-container container py-5">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="glass-card p-4 text-center shadow">
          <div class="avatar-circle mb-3 mx-auto">
            <i class="bi bi-person-badge display-1 text-primary"></i>
          </div>
          
          <h2 class="twinkle-header">
            {{ userName || 'Vendég' }}
          </h2>
          
          <p class="text-muted fw-bold">
            <i class="bi bi-envelope-at me-2"></i>{{ userEmail || 'Nincs megadva email' }}
          </p>
          
          <hr>
          
          <div class="text-start info-box">
            <p class="mb-2"><strong><i class="bi bi-shield-lock me-2"></i>Szerepkör:</strong> 
              {{ userRoleName }}
            </p>
            <p class="mb-0 text-secondary small">ID: #00{{ userId }}</p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="glass-card p-4 shadow">
          <h3 class="section-title mb-4">
            <i class="bi bi-bag-heart-fill me-2"></i>Foglalásaim
          </h3>
          
          <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Rendelések betöltése...</p>
          </div>

          <div v-else-if="userOrders.length === 0" class="text-center py-5">
            <i class="bi bi-calendar-x display-4 text-muted"></i>
            <p class="mt-3 fs-5">Még nem küldtél el egy foglalást sem.</p>
            <router-link to="/rendeles" class="btn btn-primary px-4 py-2 mt-2">Foglalás indítása</router-link>
          </div>

          <div v-else class="order-list">
            <div v-for="order in userOrders" :key="order.id" class="order-card mb-3 p-3 border-start border-primary border-4">
              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary">Rendelés #{{ order.id }}</span>
                <span class="badge rounded-pill bg-info text-dark">
                   <i class="bi bi-clock me-1"></i> {{ order.orderTime }}
                </span>
              </div>
              <hr class="my-2">
              <div class="row">
                <div class="col-sm-6">
                  <p class="mb-1 small text-muted">Vendégek száma:</p>
                  <strong><i class="bi bi-people me-1"></i> {{ order.howManyPeople }} fő</strong>
                </div>
                <div class="col-sm-6">
                  <p class="mb-1 small text-muted">Helyszín azonosító:</p>
                  <strong><i class="bi bi-geo-alt me-1"></i> {{ order.locationId }}</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useOrderStore } from '@/stores/orderStore';
import { useUserLoginLogoutStore } from '@/stores/userLoginLogoutStore';

export default {
  name: "UserProfileView",
  computed: {
    // A bejelentkezési store-ból hozzuk be az adatokat (fontos: az 'item' a kulcs!)
    ...mapState(useUserLoginLogoutStore, ['item', 'isLoggedIn']),
    ...mapState(useOrderStore, ['items', 'loading']),

    // Segéd változók a könnyebb olvashatóságért a template-ben
    userId() { return this.item?.id; },
    userName() { return this.item?.name; },
    userEmail() { return this.item?.email; },
    userRoleName() { 
      return this.item?.role === 1 ? 'Adminisztrátor' : 'Megrendelő'; 
    },
    
    // SZŰRÉS: Csak a bejelentkezett felhasználó rendeléseit mutatjuk
    userOrders() {
      if (!this.item) return [];
      // Fontos: ellenőrizd, hogy a backend 'userId' vagy 'user_id' néven küldi az adatot!
      return this.items.filter(order => order.userId === this.item.id);
    }
  },
  methods: {
    ...mapActions(useOrderStore, ['getAll'])
  },
  async mounted() {
    // Frissítjük a rendelések listáját az adatbázisból
    await this.getAll();
  }
}
</script>

<style scoped>
.profile-container { 
  min-height: 85vh; 
  background-color: #fffafc; 
}
.glass-card { 
  background: white; 
  border-radius: 20px; 
  border: 1px solid #f3e8ff; 
  border-bottom: 4px solid #f3e8ff; 
}
/* Ha nincs telepítve a Twinkle Star font, egy szép kurzívra vált */
.twinkle-header { 
  font-family: 'Twinkle Star', 'Brush Script MT', cursive; 
  color: #a855f7; 
  font-size: 2.8rem; 
}
.section-title { 
  color: #a855f7; 
  font-weight: bold; 
  border-left: 5px solid #ec4899; 
  padding-left: 15px; 
}
.order-card { 
  background: #fdf4ff; 
  border: 1px solid #f5d0fe; 
  border-radius: 12px; 
  transition: 0.3s; 
}
.order-card:hover { 
  transform: translateY(-3px); 
  box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
}
.info-box { 
  background: #f8fafc; 
  padding: 15px; 
  border-radius: 10px; 
}
</style>