<template>
  <div class="order-container container py-5">
    <h1 class="twinkle-header mb-5 text-center">Rendezvény Foglalás</h1>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="glass-card p-4 shadow">
          <form @submit.prevent="submitOrder">
            
            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-person-fill"></i> Személyes adatok</h4>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Név</label>
                  <input type="text" v-model="form.name" class="form-control" placeholder="Teljes név" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" v-model="form.email" class="form-control" placeholder="pelda@email.com" required>
                </div>
              </div>
            </section>

            <hr>

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-gear-fill"></i> Alap szolgáltatások</h4>
              
              <div class="mb-3">
                <label class="form-label">Helyszín</label>
                <select v-model="form.location" class="form-select" required>
                  <option :value="null" disabled>Válassz helyszínt...</option>
                  <option v-for="loc in locationItems" :key="loc.id" :value="loc">
                    {{ loc.cityName }}, {{ loc.address }} - {{ loc.locationName }}
                  </option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Étel menü</label>
                <select v-model="form.food" class="form-select">
                  <option :value="null">Válassz menüt...</option>
                  <option v-for="food in foodItems" :key="food.id" :value="food">
                    {{ food.service }} ({{ food.price }} Ft/fő)
                  </option>
                </select>
              </div>
            </section>

            <hr>

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-plus-circle-fill"></i> Extra szolgáltatások</h4>
              <p class="text-muted small">Kategóriánként (Zene, Dekoráció) csak egy opció választható, de több kategória kombinálható.</p>
              
              <div class="extra-services-grid">
                <div v-for="s in serviceItems" :key="s.id" class="form-check mb-2 p-2 rounded hover-effect" :class="{'selected-bg': isSelected(s)}">
                  <input 
                    class="form-check-input ms-1" 
                    type="checkbox" 
                    :id="'service-' + s.id"
                    :checked="isSelected(s)"
                    @change="toggleExtra(s)"
                  >
                  <label class="form-check-label ms-2" :for="'service-' + s.id">
                    {{ s.service }} - <strong>{{ s.price }} Ft</strong>
                  </label>
                </div>
              </div>
            </section>

            <hr>

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-calendar-event"></i> Időpont és Létszám</h4>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Dátum</label>
                  <input type="date" v-model="form.date" class="form-control" :min="minDate" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Vendégek száma (fő)</label>
                  <input type="number" v-model.number="form.guests" class="form-control" min="1" required>
                </div>
              </div>
            </section>

            <button type="submit" class="btn btn-order w-100 py-3 mt-3">
              Foglalás elküldése
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="summary-card p-4 shadow sticky-top" style="top: 100px;">
          <h4 class="twinkle-header text-center mb-4">Összegzés</h4>
          
          <div v-if="form.location" class="summary-details">
            <div class="summary-item d-flex justify-content-between">
              <span>Helyszín alapdíj:</span>
              <strong>{{ Number(form.location.roomPriceSlashDay).toLocaleString() }} Ft</strong>
            </div>
            <div class="summary-item d-flex justify-content-between">
              <span>Helyszín főre ({{ form.guests }} fő):</span>
              <strong>{{ locationGuestTotal.toLocaleString() }} Ft</strong>
            </div>
          </div>

          <div v-if="form.food" class="summary-item d-flex justify-content-between mt-2">
            <span>Étel ({{ form.guests }} fő):</span>
            <strong>{{ foodTotal.toLocaleString() }} Ft</strong>
          </div>

          <div v-if="form.selectedExtras.length > 0" class="mt-3">
            <span class="text-muted small">Extra szolgáltatások:</span>
            <div v-for="extra in form.selectedExtras" :key="extra.id" class="summary-item d-flex justify-content-between small ms-2">
              <span>• {{ extra.service }}</span>
              <strong>{{ Number(extra.price).toLocaleString() }} Ft</strong>
            </div>
          </div>
          
          <hr class="my-4">
          
          <div class="total-price d-flex justify-content-between align-items-center">
            <span>Végösszeg:</span>
            <span class="price-tag">{{ roundedTotalCost.toLocaleString() }} Ft</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useLocationStore } from '@/stores/locationStore';
import { useServiceStore } from '@/stores/serviceStore';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        location: null,
        food: null,
        selectedExtras: [], // Itt tároljuk a több kiválasztott extrát
        date: '',
        guests: 1
      },
      minDate: new Date().toISOString().split('T')[0]
    }
  },
  computed: {
    ...mapState(useLocationStore, { locationItems: 'items' }),
    ...mapState(useServiceStore, { allServices: 'items' }),
    
    foodItems() { return this.allServices.filter(s => s.serviceTypeId === 2); },
    serviceItems() { return this.allServices.filter(s => s.serviceTypeId === 3); },

    locationGuestTotal() {
      if (!this.form.location) return 0;
      return Number(this.form.location.priceSlashPerson || 0) * Number(this.form.guests);
    },
    foodTotal() {
      if (!this.form.food) return 0;
      return Number(this.form.food.price || 0) * Number(this.form.guests);
    },
    extrasTotal() {
      return this.form.selectedExtras.reduce((sum, item) => sum + Number(item.price || 0), 0);
    },
    rawTotalCost() {
      const roomBase = this.form.location ? Number(this.form.location.roomPriceSlashDay) : 0;
      return Number(roomBase) + this.locationGuestTotal + this.foodTotal + this.extrasTotal;
    },
    roundedTotalCost() {
      return Math.round(this.rawTotalCost / 100) * 100;
    }
  },
  methods: {
    ...mapActions(useLocationStore, ['getAll']),
    ...mapActions(useServiceStore, { serviceGetAll: 'getAll' }),

    isSelected(service) {
      return this.form.selectedExtras.some(s => s.id === service.id);
    },

    toggleExtra(service) {
      // Logika a kategória szerinti korlátozáshoz
      // Meghatározzuk a kategóriát a név alapján (egyszerűbb, mint az adatbázis átírása)
      let category = '';
      if (service.service.toLowerCase().includes('dekoráció')) category = 'dekor';
      if (service.service.toLowerCase().includes('zene') || service.service.toLowerCase().includes('dj')) category = 'zene';

      const index = this.form.selectedExtras.findIndex(s => s.id === service.id);

      if (index > -1) {
        // Ha már benne van, kivesszük
        this.form.selectedExtras.splice(index, 1);
      } else {
        // Ha nincs benne, először kivesszük az azonos kategóriájúakat (exkluzivitás)
        if (category) {
          this.form.selectedExtras = this.form.selectedExtras.filter(s => {
            const sName = s.service.toLowerCase();
            if (category === 'dekor') return !sName.includes('dekoráció');
            if (category === 'zene') return !sName.includes('zene') && !sName.includes('dj');
            return true;
          });
        }
        this.form.selectedExtras.push(service);
      }
    },
    
    submitOrder() {
      alert(`Köszönjük, ${this.form.name}! A foglalás elküldve. Végösszeg: ${this.roundedTotalCost} Ft`);
    }
  },
  async mounted() {
    await Promise.all([this.getAll(), this.serviceGetAll()]);
  }
}
</script>

<style scoped>
.order-container { min-height: 100vh; background-color: #fffafc; }
.twinkle-header { font-family: 'Twinkle Star', cursive; font-size: 3rem; background: linear-gradient(45deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.glass-card { background: rgba(255, 255, 255, 0.9); border-radius: 20px; border: 1px solid #f5d0fe; }
.summary-card { background: white; border-radius: 20px; border: 2px solid #f3e8ff; padding: 25px; }
.section-title { color: #a855f7; font-weight: 600; margin-bottom: 1.2rem; }
.hover-effect:hover { background-color: #fdf4ff; cursor: pointer; }
.selected-bg { background-color: #f3e8ff; border-left: 4px solid #a855f7; }
.btn-order { background: linear-gradient(90deg, #a855f7, #ec4899); color: white; border: none; border-radius: 10px; font-weight: bold; font-size: 1.2rem; transition: all 0.3s; }
.price-tag { font-size: 2.2rem; color: #a855f7; font-weight: bold; }
.summary-item { font-size: 1.05rem; margin-bottom: 8px; }
</style>