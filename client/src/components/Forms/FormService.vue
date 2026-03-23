<template>
  <div class="modal fade" ref="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-4">
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold text-dark">{{ title }}</h5>
          <button type="button" class="btn-close" @click="hide"></button>
        </div>
        <div class="modal-body p-4">
          <form @submit.prevent="submit">
            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase text-muted">Kategória</label>
              <select v-model="localItem.serviceTypeId" class="form-select form-select-lg">
                <option :value="null" disabled>Válassz típust...</option>
                <option v-for="type in service_types" :key="type.id" :value="type.id">
                  {{ type.serviceTypeName }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase text-muted">Konkrét szolgáltatás</label>
              <input 
                type="text" 
                class="form-control form-control-lg" 
                v-model="localItem.service" 
                list="serviceSuggestions"
                placeholder="Írd be a nevét vagy válassz..."
                :disabled="!localItem.serviceTypeId"
              >
              <datalist id="serviceSuggestions">
                <option v-for="s in filteredServices" :key="s.id" :value="s.name"></option>
              </datalist>
              
              <div v-if="errors.service" class="text-danger mt-1 small">
                <i class="bi bi-exclamation-circle me-1"></i>{{ errors.service[0] }}
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small text-uppercase text-muted">Ár (Ft)</label>
              <div class="input-group">
                <input type="number" class="form-control form-control-lg" v-model="localItem.price">
                <span class="input-group-text">Ft</span>
              </div>
              <div v-if="errors.price" class="text-danger mt-1 small">
                <i class="bi bi-exclamation-circle me-1"></i>{{ errors.price[0] }}
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 bg-light p-3">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" @click="hide">Mégse</button>
          <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" @click="submit">Mentés</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as bootstrap from "bootstrap";

export default {
  props: {
    title: {type:String},
    item: {type: Object},
    service_types: {type: Array}
  },
  data() {
    return {
      modalInstance: null,
      localItem: { 
        id: 0, 
        service: "", 
        serviceTypeId: null, 
        price: 0 
      },
      errors: {},
      // service_types: [
      //   { id: 1, serviceTypeName: "Helyszín" },
      //   { id: 2, serviceTypeName: "étel" },
      //   { id: 3, serviceTypeName: "szolgáltatás" },
      // ],
      // Ezek csak javaslatok lesznek a datalist-ben
      services: [
        { id: 1, serviceTypeId: 2, name: "két fogásos menü" },
        { id: 2, serviceTypeId: 2, name: "három fogásos menü" },
        { id: 3, serviceTypeId: 2, name: "négy fogásos menü" },
        { id: 4, serviceTypeId: 3, name: "élő zene" },
        { id: 5, serviceTypeId: 3, name: "dj" },
        { id: 6, serviceTypeId: 3, name: "egyszerű dekoráció" },
        { id: 7, serviceTypeId: 3, name: "közepes dekoráció" },
        { id: 8, serviceTypeId: 3, name: "dekoratív dekoráció" },
      ],
      cities: ["Budapest", "Szolnok", "Szeged", "Keszthely", "Székesfehérvár"],
    };
  },
  computed: {
    filteredServices() {
      if (!this.localItem.serviceTypeId) return [];
      if (this.localItem.serviceTypeId === 1) {
        return this.cities.map((city, index) => ({ id: 100 + index, name: city }));
      }
      return this.services.filter(s => s.serviceTypeId === this.localItem.serviceTypeId);
    }
  },
  watch: {
    item: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.localItem = JSON.parse(JSON.stringify(newVal));
        }
      }
    }
  },
  methods: {
    show() {
      this.errors = {};
      this.modalInstance = new bootstrap.Modal(this.$refs.modal);
      this.modalInstance.show();
    },
    hide() {
      this.modalInstance?.hide();
    },
    submit() {
      // Itt küldjük el a szülőknek (ServicesView) az adatokat
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
</style>

<style scoped>
.modal-dialog {
  margin-top: 100px; /* desktop: szünet a navbar és a modál között */
}

@media (max-width: 576px) {
  .modal-dialog {
    margin-top: 50px; /* mobilon kisebb szünet */
  }
}
</style>