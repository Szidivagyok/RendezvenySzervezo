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
      Nincs megjeleníthető szolgáltatás.
    </div>

    <FormService
      ref="form"
      :title="title"
      :item="item"
      @yesEventForm="yesEventFormHandler"
      :service_types="serviceTypeStoreItems"
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
import { useServiceStore } from "@/stores/serviceStore";
import { useSearchStore } from "@/stores/searchStore";
import { useServiceTypeStore } from "@/stores/servicetypeStore";
import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import FormService from "@/components/Forms/FormService.vue";
import userService from "@/api/userService";

export default {
  name: "ServicesView",
  components: { GenericTable, ConfirmModal, FormService },
  data() {
    return {
      pageTitle: "Szolgáltatások Kezelése",
      tableColumns: [
        { key: "id", label: "ID", debug: 2 },
        { key: "service", label: "Megnevezés", debug: 2 },
        { key: "serviceTypeId", label: "Típus", debug: 2 },
        { key: "price", label: "Ár (Ft)", debug: 2 },
      ],
      useCollectionStore: useServiceStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r", // 'r' = read, 'c' = create, 'u' = update, 'd' = delete
      title: "",
    
    };
  },
  computed: {
    ...mapState(useServiceStore, ["item", "items", "loading"]),
    ...mapState(useServiceTypeStore, {serviceTypeStoreItems: "items"}),
  },
  methods: {
    ...mapActions(useServiceStore, ["getAll", "getById", "create", "update", "delete", "clearItem"]),
    ...mapActions(useSearchStore, ["resetSearchWord"]),
    ...mapActions(useServiceTypeStore, { getServiceTypeStoreAll: "getAll"}),

    // TÖRLÉS: Megnyitja a ConfirmModal-t
    deleteHandler(id) {
      this.state = "d";
      this.toDeleteId = id;
      this.isOpenConfirmModal = true;
    },

    // MÓDOSÍTÁS: Betölti az adatot és megnyitja a Form-ot
    updateHandler(id) {
      this.state = "u";
      this.title = "Szolgáltatás módosítása";
      this.getById(id);
      this.$refs.form.show();
    },

    // HOZZÁADÁS: Ez fut le a zöld gombra kattintva!
    createHandler() {
      this.state = "c";
      this.title = "Új szolgáltatás hozzáadása";
      this.clearItem(); // Kiüríti a store-ban az aktuális elemet
      this.$refs.form.show(); // Megnyitja az üres űrlapot
    },

    cancelHandler() {
      this.isOpenConfirmModal = false;
    },

    async confirmHandler() {
      await this.delete(this.toDeleteId);
      this.isOpenConfirmModal = false;
    },

    // Mentés gomb kezelése az űrlapon
   async yesEventFormHandler({ item, done }) {
  try {
    let success = false;
    if (this.state === "c") {
      success = await this.create(item); // Várjuk meg a mentést
    } else {
      success = await this.update(item.id, item);
    }

    if (success) {
      done(true); // Csak akkor zárjuk be az ablakot, ha sikerült a mentés
    } else {
      alert("Hiba történt a mentés során. Ellenőrizd az adatokat!");
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

    await this.getServiceTypeStoreAll();
  },
};
</script>