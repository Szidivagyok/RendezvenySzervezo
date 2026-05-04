<template>
  <div>
    <div class="d-flex align-items-center m-0 mb-2">
      <h1>{{ pageTitle }}</h1>
      <div class="d-flex align-items-center m-0 ms-2">

        <i
          v-if="loading"
          class="bi bi-hourglass-split fs-3 col-auto p-0 pe-1"
        ></i>

        <ButtonsCrudCreate v-if="!loading" @create="createHandler" />
        <p class="m-0 ms-2">({{ getItemsLength }})</p>


        <SetSelectedPerPage
         :useCollectionStore="useCollectionStore" 
        />

         <Pagination
          :useCollectionStore="useCollectionStore"
         />
      </div>
    </div>

    <GenericTable
      :items="items"
      :columns="tableColumns"
      :useCollectionStore="useCollectionStore"
      @delete="deleteHandler"
      @update="updateHandler"
      @create="createHandler"
      @sort="sortHandler"
      v-if="items.length > 0"
    />
    <div v-else style="width: 100px" class="m-auto">Nincs találat</div>


    <FormSport
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

import { useSportStore } from "@/stores/sporsStore";
import { useSearchStore } from "@/stores/searchStore";
import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import ButtonsCrudCreate from "@/components/Table/ButtonsCrudCreate.vue";
import FormSport from "@/components/Forms/FormSport.vue";
import Pagination from "@/components/Pagination/Pagination.vue";
import SetSelectedPerPage from "@/components/Pagination/SetSelectedPerPage.vue";
export default {

  name: "SportView",
  components: {
    GenericTable,
    ConfirmModal,
    ButtonsCrudCreate,
    FormSport,
    Pagination,
    SetSelectedPerPage,
  },
  watch: {
    searchWord() {
      this.getPaging();
    },
  },
  data() {
    return {

      pageTitle: "Sportok",

      tableColumns: [
        { key: "id", label: "ID", debug: import.meta.env.VITE_DEBUG_MODE },
        { key: "sportNev", label: "Sportnév", debug: 2 },
      ],

      useCollectionStore: useSportStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r", 
      title: "",
    };
  },
  computed: {

    ...mapState(useSportStore, [
      "item",
      "items",
      "loading",
      "sortColumn",
      "sortDirection",
      "getItemsLength",
    ]),
    ...mapState(useSearchStore, ["searchWord"]),
  },
  methods: {

    ...mapActions(useSportStore, [
      "getAll",
      "getAllSortSearch",
      "getPaging",
      "setColumn",
      "getById",
      "create",
      "update",
      "delete",
      "clearItem"
    ]),
    ...mapActions(useSearchStore, ["resetSearchWord"]),
    deleteHandler(id) {
      this.state = "d";
      this.isOpenConfirmModal = true;
      this.toDeleteId = id;
    },
    updateHandler(id) {
      this.state = "u";
      this.title = "Adatmódosítás";
      this.getById(id);
      this.$refs.form.show();
      console.log("update:", id);
    },
    createHandler() {
      this.state = "c";
      this.title = "Új adatbevitel";
      this.clearItem();
      this.$refs.form.show();
      console.log("Create:");
    },
    sortHandler(column) {
      console.log(column);
      this.setColumn(column);
    },
    cancelHandler() {
      console.log("mégsem törlök");
      this.isOpenConfirmModal = false;
      this.state = "r";
    },
    async confirmHandler() {
      try {
        await this.delete(this.toDeleteId);
      } catch (error) {}
      this.isOpenConfirmModal = false;
      this.state = "r";
    },
    async yesEventFormHandler({ item, done }) {

      try {
        if (this.state == "c") {

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
    await this.getPaging(1);
  },
};
</script>

<style></style>
