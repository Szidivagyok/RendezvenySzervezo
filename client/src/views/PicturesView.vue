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
      Nincs megjeleníthető kép.
    </div>

    <FormPicture
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
import { usePictureStore } from "@/stores/pictureStore"; // A képek store-ja
import { useSearchStore } from "@/stores/searchStore";

import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import FormPicture from "@/components/Forms/FormPicture.vue"; // Az új kép form

export default {
  name: "PicturesView",
  components: { 
    GenericTable, 
    ConfirmModal, 
    FormPicture 
  },
  data() {
    return {
     pageTitle: "Képek Kezelése",
   tableColumns: [
  { key: "id", label: "ID", debug: 2 },
  { key: "pictureName", label: "Fájlnév", debug: 2 },
  { key: "serviceId", label: "Szolgáltatás ID", debug: 2 },
],
    useCollectionStore: usePictureStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r", 
      title: "",
    };
  },
  computed: {
    ...mapState(usePictureStore, ["item", "items", "loading", "error"]),
  },
  methods: {
    ...mapActions(usePictureStore, ["getAll", "getById", "create", "update", "delete", "clearItem"]),
    ...mapActions(useSearchStore, ["resetSearchWord"]),

    deleteHandler(id) {
      this.state = "d";
      this.toDeleteId = id;
      this.isOpenConfirmModal = true;
    },

    async updateHandler(id) {
      this.state = "u";
      this.title = "Kép módosítása";
      await this.getById(id);
      this.$refs.form.show();
    },

    createHandler() {
      this.state = "c";
      this.title = "Új Kép hozzáadása";
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
          // Hibakezelés a formon, ha a szerver visszadob valamit
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
      console.log("Rendezés (Képek):", column);
    }
  },
  async mounted() {
    this.resetSearchWord();
    await this.getAll();
  },
};
</script>