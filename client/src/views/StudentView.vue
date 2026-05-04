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

        <select
          class="form-select ms-2"
          aria-label="Default select example"
          v-model="selectedSchoolclassId"
        >
          <option
            v-for="sItem in schoolClassItems"
            :key="sItem.id"
            :value="sItem.id"
          >
            {{ sItem.osztalyNev }}
          </option>
        </select>
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


    <FormStudent
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

import { useSchoolclassStore } from "@/stores/schoolclassStore";
import { useStudentStore } from "@/stores/studentStore";
import { useSearchStore } from "@/stores/searchStore";
import GenericTable from "@/components/Table/GenericTable.vue";
import ConfirmModal from "@/components/Confirm/ConfirmModal.vue";
import ButtonsCrudCreate from "@/components/Table/ButtonsCrudCreate.vue";
import FormStudent from "@/components/Forms/FormStudent.vue";
export default {

  name: "StudentView",
  components: {
    GenericTable,
    ConfirmModal,
    ButtonsCrudCreate,
    FormStudent,
  },
  watch: {
    searchWord() {
      this.getStudentsBySchoolclassId(
        this.selectedSchoolclassId,
        this.sortColumn,
        this.sortDirection,
      );
    },
    selectedSchoolclassId(value) {
      this.getStudentsBySchoolclassId(
        value,
        this.sortColumn,
        this.sortDirection,
      );
    },
  },
  data() {
    return {

      pageTitle: "Diákok",
      selectedSchoolclassId: null,

      tableColumns: [
        { key: "id", label: "ID", debug: import.meta.env.VITE_DEBUG_MODE },
        { key: "diakNev", label: "---Diáknév---", debug: 2 },
        {
          key: "schoolclassId",
          label: "Osztály ID",
          debug: import.meta.env.VITE_DEBUG_MODE,
        },
        { key: "nemeString", label: "Neme", debug: 2 },
        { key: "iranyitoszam", label: "Irsz.", debug: 2 },
        { key: "lakHelyseg", label: "Település", debug: 2 },
        { key: "lakCim", label: "------Cím------", debug: 2 },
        { key: "szulHelyseg", label: "Szül. hely", debug: 2 },
        { key: "szulDatum", label: "Szül. dátum", debug: 2 },
        { key: "igazolvanyszam", label: "Igazolványszám", debug: 2 },
        { key: "atlag", label: "Átlag", debug: 2 },
        { key: "osztondij", label: "Ösztöndíj", debug: 2 },
        { key: "eletkor", label: "Életkor", debug: 2 },
      ],

      useCollectionStore: useStudentStore,
      isOpenConfirmModal: false,
      toDeleteId: null,
      state: "r", 
      title: "",
    };
  },
  computed: {

    ...mapState(useSchoolclassStore, {
      schoolClassItems: "items",
    }),
    ...mapState(useStudentStore, [
      "item",
      "items",
      "loading",
      "getItemsLength",
      "sortColumn",
      "sortDirection",
    ]),
    ...mapState(useSearchStore, ["searchWord"]),
  },
  methods: {

    ...mapActions(useSchoolclassStore, ["getAllAbc"]),
    ...mapActions(useSearchStore, ["resetSearchWord"]),
    ...mapActions(useStudentStore, [
      "getStudentsBySchoolclassId",
      "clearItem",
      "getById",
      "create",
      "update",
      "delete",
    ]),
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
      console.log(column);
      this.getStudentsBySchoolclassId(this.selectedSchoolclassId, column);
    },
    cancelHandler() {
      console.log("mégsem törlök");
      this.isOpenConfirmModal = false;
      this.state = "r";
    },
    async confirmHandler() {
      try {
        await this.delete(this.toDeleteId, this.selectedSchoolclassId);
      } catch (error) {}
      this.isOpenConfirmModal = false;
      this.state = "r";
    },
    async yesEventFormHandler({ item, done }) {

      try {
        if (this.state == "c") {

          await this.create(item, this.selectedSchoolclassId);
        } else {

          console.log("módosítás előtt");
          
          await this.update(item.id, item, this.selectedSchoolclassId);
          console.log("módsítás után");
          
        }

        this.state = "r";
        done(true);
      } catch (err) {
        console.log("valami hiba");
        

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

    await this.getAllAbc();
    this.selectedSchoolclassId = this.schoolClassItems[0].id;
    await this.getStudentsBySchoolclassId(this.selectedSchoolclassId);
  },
};
</script>

<style></style>
