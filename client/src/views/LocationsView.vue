<template>
  <div class="container py-4">
    <div class="d-flex align-items-center mb-4">
      <h1 class="m-0">{{ pageTitle }}</h1>
      <div class="ms-3 d-flex align-items-center">
        <i v-if="loading" class="bi bi-hourglass-split fs-3 text-primary me-2"></i>
        <span class="badge bg-secondary fs-6" v-if="items">{{ items.length }} db</span>
      </div>
    </div>

    <GenericTable
      v-if="items && items.length > 0"
      :items="items"
      :columns="tableColumns"
      :useCollectionStore="useCollectionStore"
      :cButtonVisible="true" 
      :pButtonVisible="false"
      @delete="deleteHandler"
      @update="updateHandler"
      @create="createHandler"
      @sort="sortHandler"
    />
    
    <div v-else-if="!loading" class="alert alert-info text-center mt-3">
      Nincs megjeleníthető helyszín.
    </div>

    <FormLocation
      ref="form"
      :title="title"
      :item="item"
      @yesEventForm="yesEventFormHandler"
    />

    <ConfirmModal
      :isOpenConfirmModal="isOpenConfirmModal"
      @cancel="cancelHandler"
      @confirm="confirmHandler"
    />
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useLocationStore } from "@/stores/locationStore";
import { useSearchStore } from "@/stores/searchStore";

import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import FormLocation from "@/components/Forms/FormLocation.vue"; // Ez az import jó volt

export default {
  name: "LocationsView",
  components: { 
    GenericTable, 
    ConfirmModal, 
    FormLocation // Regisztrálva van
  },
  data() {
    return {
      pageTitle: "Helyszínek Kezelése",
    tableColumns: [
  { key: "id", label: "ID", debug: 2 },
  { key: "locationName", label: "Helyszín", debug: 2 },
  { key: "cityName", label: "Város", debug: 2 },
  { key: "zipCode", label: "Irányítószám", debug: 2 },
  { key: "street", label: "Utca", debug: 2 },
  { key: "houseNumber", label: "Házszám", debug: 2 },
  { key: "maxCapacity", label: "Max fő", debug: 2 },
  { key: "minCapacity", label: "Min fő", debug: 2 },
  { key: "priceSlashPerson", label: "Ft/fő", debug: 2 },
  { key: "roomPriceSlashDay", label: "Ház/nap", debug: 2 },
],
      useCollectionStore: useLocationStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r", 
      title: "",
    };
  },
  computed: {
    ...mapState(useLocationStore, ["item", "items", "loading"]),
  },
  methods: {
    ...mapActions(useLocationStore, ["getAll", "getById", "create", "update", "delete", "clearItem"]),
    ...mapActions(useSearchStore, ["resetSearchWord"]),

    deleteHandler(id) {
      this.state = "d";
      this.toDeleteId = id;
      this.isOpenConfirmModal = true;
    },

    async updateHandler(id) {
      this.state = "u";
      this.title = "Helyszín módosítása";
      await this.getById(id); // Megvárjuk, amíg betölti az adatokat a store-ba
      this.$refs.form.show(); // Most már létezik a ref és a függvény!
    },

    createHandler() {
      this.state = "c";
      this.title = "Új Helyszín hozzáadása";
      this.clearItem(); 
      this.$refs.form.show();
    },

    cancelHandler() {
      this.isOpenConfirmModal = false;
    },

    async confirmHandler() {
      await this.delete(this.toDeleteId);
      this.isOpenConfirmModal = false;
    },

    async yesEventFormHandler({ item, done }) {
      try {
        let success = false;
        if (this.state === "c") {
          success = await this.create(item);
        } else {
          success = await this.update(item.id, item);
        }

        if (success) {
          done(true);
        } else {
          // Ha a store-ban elmentettük a hibát, átadjuk a formnak
          if (this.error?.response?.data?.errors) {
             this.$refs.form.setServerErrors(this.error.response.data.errors);
          }
          done(false); 
        }
      } catch (err) {
        done(false);
      }
    },

    sortHandler(column) {
      console.log("Rendezés:", column);
    }
  },
  async mounted() {
    this.resetSearchWord();
    await this.getAll();
  },
};
</script>