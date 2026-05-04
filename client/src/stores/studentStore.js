import { defineStore } from "pinia";
// import { useToastStore } from "@/stores/toastStore";
import { useSearchStore } from "./searchStore";
import service from "@/api/studentService";

class Item {
  constructor(
    id = 0,
    diakNev = "",
    schoolclassId = null,
    neme = true,
    iranyitoszam = "",
    lakHelyseg = "",
    lakCim = "",
    szulHelyseg = "",
    szulDatum = null,
    igazolvanyszam = "",
    atlag = null,
    osztondij = 0,
  ) {
    this.id = id;
    this.diakNev = diakNev;
    this.schoolclassId = schoolclassId;
    this.neme = neme;
    this.iranyitoszam = iranyitoszam;
    this.lakHelyseg = lakHelyseg;
    this.lakCim = lakCim;
    this.szulHelyseg = szulHelyseg;
    this.szulDatum = szulDatum;
    this.igazolvanyszam = igazolvanyszam;
    this.atlag = atlag;
    this.osztondij = osztondij;
  }
}

export const useStudentStore = defineStore("student", {
  state: () => ({
    item: new Item(),
    items: [new Item()],
    loading: false,
    error: null,
    sortColumn: "id",
    sortDirection: "asc",
    searchStore: useSearchStore(),
  }),
  getters: {
    getItemsLength() {
      return this.items.length;
    },
  },
  actions: {
    clearItem() {
      this.item = new Item();
    },
    // READ - Összes adat lekérése
    async getStudentsBySchoolclassId(
      schoolclassId,
      column = "id",
      direction = null,
    ) {
      this.loading = true;
      this.error = null;
      this.sortColumn = column;
      if (!direction) {
        direction =
          this.sortColumn === column && this.sortDirection === "asc"
            ? "desc"
            : "asc";
      }
      this.sortDirection = direction;
      try {
        const response = await service.getStudentsBySchoolclassId(
          schoolclassId,
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord,
        );
        this.items = response.data;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async getAll() {
      this.loading = true;
      this.error = null;
      try {
        const response = await service.getAll();
        this.items = response.data;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // READ - Egy adat lekérése
    async getById(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await service.getById(id);
        this.item = response.data;
      } catch (err) {
        this.error = err;
        throw err;
      } finally {
        this.loading = false;
      }
    },

    // CREATE - Új elem hozzáadása
    async create(data, schoolclassId) {
      this.loading = true;
      this.error = null;
      try {
        const newItem = await service.create(data);
        const response = await service.getStudentsBySchoolclassId(
          schoolclassId,
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord,
        );
        this.items = response.data;

        return true;
      } catch (err) {
        this.error = err;
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },

    // 3. UPDATE - Módosítás
    async update(id, updateData, schoolclassId) {
      this.loading = true;
      this.error = null;
      try {
        const updatedItem = await service.update(id, updateData);
        const response = await service.getStudentsBySchoolclassId(
          schoolclassId,
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord,
        );
        this.items = response.data;
        return true;
      } catch (err) {
        this.error = err;
        console.log("student store hiba",err);
        
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },

    // 4. DELETE - Törlés
    async delete(id,schoolclassId) {
      this.loading = true;
      this.error = null;
      try {
        await service.delete(id);
        const response = await service.getStudentsBySchoolclassId(
          schoolclassId,
          this.sortColumn,
          this.sortDirection,
          this.searchStore.searchWord,
        );
        this.items = response.data;
        return true;
      } catch (err) {
        this.error = err;
        throw err;
        return false;
      } finally {
        this.loading = false;
      }
    },
  },
});
