<template>
  <div class="modal fade" ref="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-4">
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold text-dark">{{ title }}</h5>
          <button type="button" class="btn-close" @click="hide"></button>
        </div>
        <div class="modal-body p-4">
          <form @submit.prevent="submit">
            
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-muted">Helyszín megnevezése</label>
              <input 
                type="text" 
                class="form-control form-control-lg" 
                v-model="localItem.locationName" 
                placeholder="Pl. Arany János Rendezvényközpont"
              >
              <div v-if="errors.locationName" class="text-danger mt-1 small">{{ errors.locationName[0] }}</div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Irányítószám</label>
                <input type="number" class="form-control" v-model="localItem.zipCode">
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Város</label>
                <input type="text" class="form-control" v-model="localItem.cityName">
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Utca</label>
                <input type="text" class="form-control" v-model="localItem.street">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Házszám</label>
                <input type="text" class="form-control" v-model="localItem.houseNumber">
              </div>
            </div>

            <hr class="my-4 text-muted opacity-25">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Min. Kapacitás (fő)</label>
                <input type="number" class="form-control" v-model="localItem.minCapacity">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Max. Kapacitás (fő)</label>
                <input type="number" class="form-control" v-model="localItem.maxCapacity">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Ár / Fő (Étkezés)</label>
                <div class="input-group">
                  <input type="number" class="form-control" v-model="localItem.priceSlashPerson">
                  <span class="input-group-text small">Ft/fő</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Terembérlet / Nap</label>
                <div class="input-group">
                  <input type="number" class="form-control" v-model="localItem.roomPriceSlashDay">
                  <span class="input-group-text small">Ft/nap</span>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer border-0 bg-light p-3">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" @click="hide">Mégse</button>
          <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" @click="submit">Helyszín mentése</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as bootstrap from "bootstrap";

export default {
  props: {
    title: String,
    item: Object,
  },
  data() {
    return {
      modalInstance: null,
      localItem: { 
        id: 0, 
        cityName: "", 
        zipCode: null,
        street: "",
        houseNumber: null,
        locationName: "",
        maxCapacity: 0,
        minCapacity: 0,
        priceSlashPerson: 0,
        roomPriceSlashDay: 0
      },
      errors: {},
    };
  },
  watch: {
    item: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          // Itt is mélymásolást használunk, hogy ne az eredeti objektumot módosítsuk gépelés közben
          this.localItem = JSON.parse(JSON.stringify(newVal));
        } else {
          this.resetLocalItem();
        }
      }
    }
  },
  methods: {
    resetLocalItem() {
      this.localItem = { 
        id: 0, cityName: "", zipCode: null, street: "", houseNumber: null,
        locationName: "", maxCapacity: 0, minCapacity: 0,
        priceSlashPerson: 0, roomPriceSlashDay: 0 
      };
    },
    show() {
      this.errors = {};
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
.modal-dialog {
  margin-top: 50px;
}
hr {
  border-top: 1px solid #dee2e6;
}
</style>