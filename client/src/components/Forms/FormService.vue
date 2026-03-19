<template>
  <div>
    <div class="modal fade" ref="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- HEADER -->
          <div class="modal-header">
            <h5 class="modal-title">{{ title }}</h5>
            <button type="button" class="btn-close" @click="hide"></button>
          </div>

          <!-- BODY -->
          <div class="modal-body">
            <!-- SZOLGÁLTATÁS TÍPUSOK -->
            <div class="mb-3">
              <label class="form-label">Szolgáltatás</label>
              <select v-model="localItem.serviceTypeId" class="form-select">
                <option value="" disabled>Válassz típust</option>
                <option v-for="type in service_types" :key="type.id" :value="type.id">
                  {{ type.serviceTypeName }}
                </option>
              </select>
            </div>

            <!-- KONKRÉT SZOLGÁLTATÁS / VÁROS -->
            <div class="mb-3">
              <label class="form-label">Szolgáltatás típusok</label>
              <select
                v-model="localItem.serviceId"
                class="form-select"
                :disabled="!localItem.serviceTypeId"
              >
                <option value="" disabled>Válassz szolgáltatást</option>
                <option
                  v-for="service in filteredServices"
                  :key="service.id"
                  :value="service.id"
                >
                  {{ service.name }}
                </option>
              </select>

              <div v-if="errors.serviceId" class="text-danger mt-1">
                <div v-for="(err, i) in errors.serviceId" :key="i">
                  {{ err }}
                </div>
              </div>
            </div>

            <!-- ÁR -->
            <div class="mb-3">
              <label class="form-label">Ár (Ft)</label>
              <input
                type="number"
                class="form-control"
                v-model="localItem.price"
              />

              <div v-if="errors.price" class="text-danger mt-1">
                <div v-for="(err, i) in errors.price" :key="i">
                  {{ err }}
                </div>
              </div>
            </div>
          </div>

          <!-- FOOTER -->
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="hide">Mégse</button>
            <button class="btn btn-primary" @click="submit">Mentés</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as bootstrap from "bootstrap";

export default {
  name: "FormService",

  props: {
    title: String,
    item: Object,
  },

  data() {
    return {
      modalInstance: null,

      localItem: {
        id: null,
        serviceTypeId: null,
        serviceId: null,
        price: null,
      },

      errors: {},

      // service típusok
      service_types: [
        { id: 1, serviceTypeName: "helyszín" },
        { id: 2, serviceTypeName: "étel" },
        { id: 3, serviceTypeName: "szolgáltatás" },
      ],

      // szolgáltatások a típusokhoz
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

      // városok a helyszínhez
      cities: ["Budapest", "Szolnok", "Szeged", "Keszthely", "Székesfehérvár"],
    };
  },

  computed: {
    filteredServices() {
      if (!this.localItem.serviceTypeId) return [];

      // helyszínhez random városok
      if (this.localItem.serviceTypeId === 1) {
        return this.cities
          .sort(() => 0.5 - Math.random())
          .slice(0, 5) // pl. 5 random város
          .map((city, index) => ({ id: index + 1, name: city }));
      }

      // egyéb típusokhoz a szolgáltatások
      return this.services.filter(
        (s) => s.serviceTypeId === this.localItem.serviceTypeId
      );
    },
  },

  watch: {
    item: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.localItem = {
            id: newVal.id ?? null,
            serviceTypeId: newVal.serviceTypeId ?? null,
            serviceId: newVal.serviceId ?? null,
            price: newVal.price ?? null,
          };
        }
      },
    },
  },

  methods: {
    show() {
      this.errors = {};
      this.modalInstance = new bootstrap.Modal(this.$refs.modal);
      this.modalInstance.show();
    },

    hide() {
      if (this.modalInstance) {
        this.modalInstance.hide();
      }
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
    },
  },
};
</script>

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