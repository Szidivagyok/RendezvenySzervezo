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
            <div class="mb-3">
              <label class="form-label">Szolgáltatás</label>

              <select v-model="localItem.name" class="form-select">
                <option value="" disabled>Válassz szolgáltatást</option>

                <option
                  v-for="(name, index) in serviceNames"
                  :key="index"
                  :value="name"
                >
                  {{ name }}
                </option>
              </select>

              <div v-if="errors.name" class="text-danger mt-1">
                <div v-for="(err, i) in errors.name" :key="i">
                  {{ err }}
                </div>
              </div>
            </div>

            <!-- SERVICE TYPE -->
            <div class="mb-3">
              <label class="form-label">Típus ID</label>
              <input
                type="number"
                class="form-control"
                v-model="localItem.serviceTypeId"
              />

              <div v-if="errors.serviceTypeId" class="text-danger mt-1">
                <div v-for="(err, i) in errors.serviceTypeId" :key="i">
                  {{ err }}
                </div>
              </div>
            </div>

            <!-- PRICE -->
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
        name: "",
        serviceTypeId: null,
        price: null,
      },

      errors: {},
      serviceNames: [
        "helyszín",
        "élő zene",
        "dj",
        "két fogásos menü",
        "három fogásos menü",
        "négy fogásos menü",
        "egyszerű dekoráció",
        "közepes dekoráció",
        "dekoratív dekoráció",
      ],
    };
  },

  watch: {
    item: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.localItem = {
            id: newVal.id ?? null,
            name: newVal.name ?? "",
            serviceTypeId: newVal.serviceTypeId ?? null,
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
          if (success) {
            this.hide();
          }
        },
      });
    },

    setServerErrors(errors) {
      this.errors = errors || {};
    },
  },
};
</script>