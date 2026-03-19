<template>
  <div>
    <!-- oldal fejléc -->
    <div class="d-flex align-items-center m-0 mb-2">
      <h1>{{ pageTitle }}</h1>
      <div class="d-flex align-items-center m-0 ms-2">
        <!-- loading ikon -->
        <i
          v-if="loading"
          class="bi bi-hourglass-split fs-3 col-auto p-0 pe-1"
        ></i>
        <p class="m-0 ms-2">({{ getItemsLength }})</p>
      </div>
    </div>

    <!-- táblázat -->
    <GenericTable
      :items="items"
      :columns="tableColumns"
      :useCollectionStore="useCollectionStore"
      :cButtonVisible="true"
      :pButtonVisible="false"
      @delete="deleteHandler"
      @update="updateHandler"
      @create="createHandler"
      @sort="sortHandler"
      v-if="items.length > 0"
    />
    

    <div v-else class="m-auto text-center">Nincs találat</div>

    <!-- FORM -->
    <FormService
      ref="form"
      :title="title"
      :item="item"
      @yesEventForm="yesEventFormHandler"
    />

    <!-- CONFIRM -->
    <ConfirmModal
      :isOpenConfirmModal="isOpenConfirmModal"
      @cancel="cancelHandler"
      @confirm="confirmHandler"
    />
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";

// store
import { useServiceStore } from "@/stores/serviceStore";
import { useSearchStore } from "@/stores/searchStore";

// components
import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import FormService from "@/components/Forms/FormService.vue";

export default {
  name: "ServicesView",

  components: {
    GenericTable,
    ConfirmModal,
    FormService,
  },

  data() {
    return {
      pageTitle: "Szolgáltatások",

      tableColumns: [
        { key: "id", label: "ID", debug: import.meta.env.VITE_DEBUG_MODE },
        { key: "name", label: "Megnevezés", debug: 2 },
        { key: "serviceTypeId", label: "Típus", debug: 2 },
        { key: "price", label: "Ár (Ft)", debug: 2 },
      ],

      useCollectionStore: useServiceStore,

      isOpenConfirmModal: false,
      toDeleteId: null,

      state: "r", // r, c, u, d
      title: "",
    };
  },

  computed: {
    ...mapState(useServiceStore, [
      "item",
      "items",
      "loading",
      "getItemsLength",
    ]),
    ...mapState(useSearchStore, ["searchWord"]),
  },

  watch: {
    searchWord() {
      this.getAllSortSearch(this.sortColumn, this.sortDirection);
    },
  },

  methods: {
    ...mapActions(useServiceStore, [
      "getAll",
      "getAllSortSearch",
      "getById",
      "create",
      "update",
      "delete",
      "clearItem",
    ]),

    ...mapActions(useSearchStore, ["resetSearchWord"]),

    // DELETE
    deleteHandler(id) {
      this.state = "d";
      this.toDeleteId = id;
      this.isOpenConfirmModal = true;
    },

    // UPDATE
    updateHandler(id) {
      this.state = "u";
      this.title = "Szolgáltatás módosítása";
      this.getById(id);
      this.$refs.form.show();
    },

    // CREATE
    createHandler() {
      this.state = "c";
      this.title = "Új szolgáltatás";
      this.clearItem();
      this.$refs.form.show();
    },

    // SORT
    sortHandler(column) {
      this.getAllSortSearch(column);
    },

    // CANCEL DELETE
    cancelHandler() {
      this.isOpenConfirmModal = false;
      this.state = "r";
    },

    // CONFIRM DELETE
    async confirmHandler() {
      try {
        await this.delete(this.toDeleteId);
      } catch (error) {}

      this.isOpenConfirmModal = false;
      this.state = "r";
    },

    // FORM SUBMIT
    async yesEventFormHandler({ item, done }) {
      try {
        if (this.state === "c") {
          await this.create(item);
        } else {
          await this.update(item.id, item);
        }

        this.state = "r";
        done(true);
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.$refs.form.setServerErrors(err.response.data.errors);
          done(false);
        } else {
          done(false);
        }
      }
    },
  },

  async mounted() {
    this.resetSearchWord();
    await this.getAll();
  },
};
</script>

<style scoped>
</style>