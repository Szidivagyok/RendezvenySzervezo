<template>
  <div>
    <div class="d-flex align-items-center m-0 mb-2">
      <h1>{{ pageTitle }}</h1>
      <div class="d-flex align-items-center m-0 ms-2">
        <i v-if="loading" class="bi bi-hourglass-split fs-3 col-auto p-0 pe-1"></i>
        <ButtonsCrudCreate v-if="!loading" @create="createHandler" />
        <p class="m-0 ms-2">({{ items.length }})</p>
        </div>
    </div>

    <GenericTable
      v-if="items.length > 0"
      :items="items"
      :columns="tableColumns"
      :useCollectionStore="useCollectionStore"
      @delete="deleteHandler"
      @update="updateHandler"
      @create="createHandler"
      @sort="sortHandler"
    />
    <div v-else-if="!loading" class="alert alert-info text-center mt-3">
      Nincs találat a szolgáltatás típusokra.
    </div>

    <FormServiceTypes
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
import { useServiceTypeStore } from "@/stores/servicetypeStore";
import { useSearchStore } from "@/stores/searchStore";
import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import ButtonsCrudCreate from "@/components/Table/ButtonsCrudCreate.vue";
import FormServiceTypes from "@/components/Forms/FormServiceTypes.vue";

export default {
  name: "ServiceTypesView",
  components: {
    GenericTable,
    ConfirmModal,
    ButtonsCrudCreate,
    FormServiceTypes,
  },
  watch: {
    searchWord() {
      // Ha nincs pagináció, akkor sima getAll-t vagy szűrést hívj
      this.getAll(); 
    },
  },
  data() {
    return {
      pageTitle: "Szolgáltatások típusok",
      tableColumns: [
        { key: "id", label: "ID", debug: 2 },
        { key: "serviceTypeName", label: "Megnevezés", debug: 2 },
      ],
      useCollectionStore: useServiceTypeStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r",
      title: "",
    };
  },
  computed: {
    ...mapState(useServiceTypeStore, [
      "item",
      "items",
      "loading",
    ]),
    ...mapState(useSearchStore, ["searchWord"]),
  },
  methods: {
    ...mapActions(useServiceTypeStore, [
      "getAll",
      "getById",
      "create",
      "update",
      "delete",
      "clearItem",
      "setColumn"
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
    },
    createHandler() {
      this.state = "c";
      this.title = "Új adatbevitel";
      this.clearItem();
      this.$refs.form.show();
    },
    sortHandler(column) {
      this.setColumn(column);
      this.getAll(); // Újratöltés rendezés után
    },
    cancelHandler() {
      this.isOpenConfirmModal = false;
      this.state = "r";
    },
    async confirmHandler() {
      await this.delete(this.toDeleteId);
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
        }
        done(false);
      }
    },
  },
  async mounted() {
    this.resetSearchWord();
    await this.getAll();
  },
};
</script>