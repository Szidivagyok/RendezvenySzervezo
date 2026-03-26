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
                <div class="col-6 col-md-6">
                  <label class="form-label">Név</label>
                  <input type="text" v-model="form.name" class="form-control bg-light border-0 shadow-sm" readonly />
                </div>
                <div class="col-6 col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" v-model="form.email" class="form-control bg-light border-0 shadow-sm" readonly />
                </div>
              </div>
            </section>

            <hr />

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-gear-fill"></i> Alap szolgáltatások</h4>
              <div class="mb-3">
                <label class="form-label">Helyszín</label>
                <select v-model="form.location" class="form-select" required>
                  <option :value="null" disabled>Válassz helyszínt...</option>
                  <option v-for="loc in locationItems" :key="loc.id" :value="loc">
                    {{loc.zipCode}} {{ loc.cityName }}, {{ loc.street }} {{loc.houseNumber}} - {{ loc.locationName }}
                  </option>
                </select>
              </div>
            </section>

            <hr />

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-plus-circle-fill"></i> Választható szolgáltatások</h4>
              
              <p class="text-muted small">Kategóriánként (Étel, Zene, Dekoráció) csak egy opció választható.</p>

              <button type="button" @click="resetSelections" class="btn btn-secondary mt-2 mb-4 rounded-pill px-4 btn-sm">
                <i class="bi bi-x-circle"></i> Kijelölések törlése
              </button>

              <div class="custom-service-grid">
                <div v-for="s in serviceItems" :key="s.id" class="service-option">
                  <input
                    type="radio"
                    :id="'radio-' + s.id"
                    :name="s.serviceTypeId"
                    class="d-none"
                    :checked="form.selectedExtras.some((item) => item.id === s.id)"
                    @click="toggleExtra2(s)"
                  />
                  <label
                    :for="'radio-' + s.id"
                    class="service-card p-3 mb-2 d-flex justify-content-between align-items-center"
                    :class="{
                      'color-food-2': s.serviceTypeId == 2,
                      'color-music-3': s.serviceTypeId == 3,
                      'color-decor-4': s.serviceTypeId == 4,
                      'is-selected': isSelected(s)
                    }"
                  >
                    <div class="d-flex align-items-center">
                      <div class="status-dot me-3"></div>
                      <span class="service-name fw-semibold">{{ s.service }}</span>
                    </div>
                    <span class="service-price fw-bold">{{ s.price.toLocaleString() }} Ft</span>
                  </label>
                </div>
              </div>
            </section>

            <hr />

            <section class="mb-4">
              <h4 class="section-title"><i class="bi bi-calendar-event"></i> Időpont és Létszám</h4>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Dátum</label>
                  <input type="date" v-model="form.date" class="form-control" :min="minDate" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Vendégek száma (fő)</label>
                  <input type="number" v-model.number="form.guests" class="form-control" min="1" required />
                </div>
              </div>
            </section>

            <hr />

            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="aszfCheck" v-model="form.acceptAszf" />
              <label class="form-check-label ms-2" for="aszfCheck">
                Kijelentem, hogy az
                <a :href="`${baseURL}/download-aszf`" target="_blank" class="fw-bold text-decoration-underline">
                  Általános Szerződési Feltételeket (ÁSZF)
                </a>
                elolvastam és elfogadom.
              </label>
            </div>

            <button type="submit" class="btn btn-order w-100 py-3 mt-3" :disabled="!form.acceptAszf">
              Foglalás elküldése
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="summary-card p-4 shadow sticky-top" style="top: 100px">
          <h4 class="twinkle-header text-center mb-4">Összegzés</h4>
          <div v-if="form.location" class="summary-details">
            <div class="summary-item d-flex justify-content-between">
              <span>Helyszín alapdíj:</span>
              <strong>{{ Number(form.location.roomPriceSlashDay || 0).toLocaleString() }} Ft</strong>
            </div>
            <div class="summary-item d-flex justify-content-between">
              <span>Helyszín főre ({{ form.guests }} fő):</span>
              <strong>{{ locationGuestTotal.toLocaleString() }} Ft</strong>
            </div>
          </div>

          <div v-if="form.selectedExtras.length > 0" class="mt-3">
            <span class="text-muted small">Választott szolgáltatások:</span>
            <div v-for="extra in form.selectedExtras" :key="extra.id" class="summary-item d-flex justify-content-between small ms-2 mt-1">
              <span>• {{ extra.service }}</span>
              <strong>{{ Number(extra.price).toLocaleString() }} Ft</strong>
            </div>
          </div>

          <hr class="my-4" />

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
import { mapState, mapActions } from "pinia";
import { useLocationStore } from "@/stores/locationStore";
import { useServiceStore } from "@/stores/serviceStore";
import { useOrderStore } from "@/stores/orderStore";
import { useUserLoginLogoutStore } from "@/stores/userLoginLogoutStore";

export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        location: null,
        selectedExtras: [],
        date: "",
        guests: 1,
        acceptAszf: false,
      },
      baseURL: import.meta.env.VITE_API_URL,
      minDate: new Date().toISOString().split("T")[0],
    };
  },
  computed: {
    ...mapState(useLocationStore, { locationItems: "items" }),
    ...mapState(useServiceStore, { allServices: "items" }),
    ...mapState(useUserLoginLogoutStore, { userItem: "item" }),

    serviceItems() {
      return this.allServices
        .filter((s) => s.serviceTypeId === 3 || s.serviceTypeId === 4 || s.serviceTypeId === 2)
        .sort((a, b) => a.serviceTypeId - b.serviceTypeId);
    },

    locationGuestTotal() {
      if (!this.form.location) return 0;
      return Number(this.form.location.priceSlashPerson || 0) * this.form.guests;
    },
    extrasTotal() {
      return this.form.selectedExtras.reduce((sum, item) => sum + Number(item.price || 0), 0);
    },
    roundedTotalCost() {
      const roomBase = this.form.location ? Number(this.form.location.roomPriceSlashDay || 0) : 0;
      const total = roomBase + this.locationGuestTotal + this.extrasTotal;
      return Math.round(total / 100) * 100;
    },
  },
  watch: {
    userItem: {
      immediate: true,
      handler(newUser) {
        if (newUser && newUser.id) {
          this.form.name = newUser.name || "";
          this.form.email = newUser.email || "";
        }
      },
    },
  },
  methods: {
    ...mapActions(useLocationStore, { locationGetAll: "getAll" }),
    ...mapActions(useServiceStore, { serviceGetAll: "getAll" }),

    isSelected(service) {
      return this.form.selectedExtras.some((s) => s.id === service.id);
    },

    toggleExtra2(service) {
      this.form.selectedExtras = this.form.selectedExtras.filter(
        (item) => item.serviceTypeId !== service.serviceTypeId
      );
      this.form.selectedExtras.push(service);
    },

    resetSelections() {
      this.form.selectedExtras = [];
    },

    async submitOrder() {
      try {
        const payload = {
          userId: this.userItem.id,
          locationId: this.form.location.id,
          howManyPeople: this.form.guests,
          howManyDays: 1,
          orderTime: this.form.date,
          is_system: 0,
        };

        const orderStore = useOrderStore();
        await orderStore.createComplexOrder(payload, this.form.selectedExtras);

        alert("Sikeres foglalás!");
        this.$router.push("/userprofil");
      } catch (error) {
        if (error.response?.data?.message) {
          alert(error.response.data.message);
        } else {
          alert("Hiba történt a mentés során!");
        }
      }
    },
  },
  async mounted() {
    await Promise.all([this.locationGetAll(), this.serviceGetAll()]);
  },
};
</script>

<style scoped>
.order-container { min-height: 100vh; background-color: #fffafc; }
.twinkle-header { font-family: "Twinkle Star", cursive; font-size: 3rem; background: linear-gradient(45deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-fill-color: transparent; }
.glass-card { background: rgba(255, 255, 255, 0.9); border-radius: 20px; border: 1px solid #f5d0fe; }
.summary-card { background: white; border-radius: 20px; border: 2px solid #f3e8ff; padding: 25px; }
.section-title { color: #a855f7; font-weight: 600; margin-bottom: 1.2rem; }
.service-card { border: 2px solid; border-radius: 12px; background: white; cursor: pointer; transition: all 0.2s ease-in-out; }
.service-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.status-dot { width: 12px; height: 12px; border-radius: 50%; border: 2px solid; transition: all 0.2s; background-color: white; }

.color-food-2 { color: #ef4444; border-color: #fecaca; }
.color-food-2 .status-dot { border-color: #ef4444; }
.color-music-3 { color: #a855f7; border-color: #ddd6fe; }
.color-music-3 .status-dot { border-color: #a855f7; }
.color-decor-4 { color: #ec4899; border-color: #fbcfe8; }
.color-decor-4 .status-dot { border-color: #ec4899; }

.service-card.is-selected .status-dot { background-color: currentColor; }
.color-food-2.is-selected { background-color: #fef2f2; border-color: #ef4444; }
.color-music-3.is-selected { background-color: #f5f3ff; border-color: #a855f7; }
.color-decor-4.is-selected { background-color: #fdf2f8; border-color: #ec4899; }

.btn-order { background: linear-gradient(90deg, #a855f7, #ec4899); color: white; border: none; border-radius: 10px; font-weight: bold; font-size: 1.2rem; transition: all 0.3s; }
.btn-order:disabled { background: #ccc !important; cursor: not-allowed; opacity: 0.6; }
.price-tag { font-size: 2.2rem; color: #a855f7; font-weight: bold; }
</style>