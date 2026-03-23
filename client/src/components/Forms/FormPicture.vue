<template>
  <div class="modal fade" ref="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-4">
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold text-dark">{{ title }}</h5>
          <button type="button" class="btn-close" @click="hide"></button>
        </div>
        <div class="modal-body p-4">
          <form @submit.prevent="submit">
            
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Kép fájlneve / URL</label>
              <input 
                type="text" 
                class="form-control form-control-lg shadow-sm" 
                v-model="localItem.pictureName" 
                placeholder="Pl. helyszin_01.jpg vagy https://..."
              >
              <div v-if="errors.pictureName" class="text-danger mt-1 small">{{ errors.pictureName[0] }}</div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Hozzárendelés</label>
              <select 
                class="form-select form-select-lg shadow-sm" 
                v-model="localItem.serviceId"
              >
                <option :value="null" disabled>Válassz egy kategóriát...</option>
                <option v-for="s in serviceStore.items" :key="s.id" :value="s.id">
                  {{ s.service }} (ID: {{ s.id }})
                </option>
              </select>
              <div v-if="errors.serviceId" class="text-danger mt-1 small">A hozzárendelés kötelező!</div>
              <p class="text-muted small mt-2">
                <i class="bi bi-info-circle me-1"></i>
                Válaszd ki, hogy ez a kép melyik helyszínhez vagy szolgáltatáshoz (zene, étel, stb.) tartozzon.
              </p>
            </div>

          </form>
        </div>
        <div class="modal-footer border-0 bg-light p-3">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" @click="hide">Mégse</button>
          <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" @click="submit">Kép mentése</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as bootstrap from "bootstrap";
import { useServiceStore } from "@/stores/serviceStore"; // Szolgáltatások Store-ja a lenyílóhoz

export default {
  props: {
    title: String,
    item: Object,
  },
  data() {
    return {
      modalInstance: null,
      serviceStore: useServiceStore(), // Példányosítjuk a szolgáltatás store-t
      localItem: { 
        id: 0, 
        pictureName: "", 
        serviceId: null
      },
      errors: {},
    };
  },
  watch: {
    item: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          // Mélymásolás a reaktivitás megőrzéséhez
          this.localItem = JSON.parse(JSON.stringify(newVal));
        } else {
          this.resetLocalItem();
        }
      }
    }
  },
  methods: {
    resetLocalItem() {
      this.localItem = { id: 0, pictureName: "", serviceId: null };
    },
    async show() {
      this.errors = {};
      // Mielőtt megmutatjuk a modalt, lekérjük a legfrissebb szolgáltatás listát az adatbázisból
      await this.serviceStore.getAll();
      
      this.modalInstance = new bootstrap.Modal(this.$refs.modal);
      this.modalInstance.show();
    },
    hide() {
      this.modalInstance?.hide();
    },
    submit() {
      this.$emit("yesEventForm", {
        item: this.localItem,
        done: (success) => {
          if (success) this.hide();
        },
      });
    },
    setServerErrors(errors) {
      this.errors = errors || {};
    }
  }
};
</script>

<style scoped>
.modal-content {
  overflow: hidden;
}
.form-label {
  margin-bottom: 0.3rem;
  letter-spacing: 0.5px;
}
.form-control:focus, .form-select:focus {
  border-color: #a8b2bd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
}
</style>