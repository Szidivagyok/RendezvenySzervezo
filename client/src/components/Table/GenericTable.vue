<template>
  <div
    class="table-responsive my-table-container custom-scrollbar"
    style="max-height: calc(100vh - 300px); overflow-y: auto"
  >
    <table class="table table-hover mx-auto bg-transparent">
      <thead class="sticky-top shadow-sm" style="z-index: 10; top: 0">
        <tr class="align-middle text-center custom-header">
          <th class="px-4 py-3 border-0">Műveletek</th>
          <template v-for="col in columns">
            <th
              class="my-pointer px-4 py-3 border-0"
              v-if="col.debug >= 1"
              :key="col.key"
              @click="$emit('sort', col.key)"
              :class="{ 'my-debug': col.debug == 1 }"
            >
              <div class="d-flex align-items-center justify-content-center text-nowrap">
                <span class="fw-bold small text-uppercase tracking-wider">{{ col.label }}</span>
                <span
                  :class="{ 'opacity-0': sortColumn !== col.key }"
                  class="ms-2 transition-all"
                >
                  {{ sortDirection === "asc" ? "↑" : "↓" }}
                </span>
              </div>
            </th>
          </template>
        </tr>
      </thead>
      <tbody class="border-0">
        <tr
          v-for="item in items"
          :key="item.id"
          @click="onClickRow(item.id)"
          class="align-middle text-center custom-row"
          :class="{ 'row-active shadow-sm': selectedId === item.id }"
        >
          <td class="px-3 py-3 border-0 rounded-start-4">
            <ButtonsCrud
              :id="item.id"
              @delete="$emit('delete', $event)"
              @update="$emit('update', $event)"
              @create="$emit('create', $event)"
              @passwordChange="$emit('passwordChange', $event)"
              :cButtonVisible="cButtonVisible"
              :uButtonVisible="uButtonVisible"
              :dButtonVisible="dButtonVisible"
              :pButtonVisible="pButtonVisible"
            />
          </td>
          <template v-for="(col, index) in columns">
            <td
              v-if="col.debug >= 1"
              :key="col.key"
              class="px-4 py-3 border-0 fw-medium text-secondary"
              :class="{ 
                'my-debug': col.debug == 1,
                'rounded-end-4': index === columns.length - 1 
              }"
            >
              {{ item[col.key] }}
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import ButtonsCrud from "./ButtonsCrud.vue";

export default {
  name: "GenericTable",
  props: {
    items: { type: Array, required: true },
    columns: { type: Array, required: true },
    useCollectionStore: { type: Function, required: true },
    cButtonVisible: { type: Boolean, default: true },
    uButtonVisible: { type: Boolean, default: true },
    dButtonVisible: { type: Boolean, default: true },
    pButtonVisible: { type: Boolean, default: false },
  },
  components: {
    ButtonsCrud,
  },
  data() {
    return {
      selectedId: null,
      store: null,
    };
  },
  created() {
    if (this.useCollectionStore) {
      this.store = this.useCollectionStore();
    }
  },
  computed: {
    sortColumn() {
      return this.store ? this.store.sortColumn : "";
    },
    sortDirection() {
      return this.store ? this.store.sortDirection : "asc";
    },
  },
  methods: {
    onClickRow(id) {
      this.selectedId = id;
    },
  },
};
</script>

<style scoped>
/* A táblázat konténere legyen teljesen áttetsző */
.my-table-container {
  border: none;
  background: transparent !important;
}

/* Kényszerítsük a táblázatot és minden elemét, hogy ne legyen alap fehér háttere */
.table {
  background-color: transparent !important;
  border-collapse: separate;
  border-spacing: 0 8px; /* Térköz a sorok között */
}

/* FEJLÉC: Maradjon sötét, de adjunk neki lekerekítést */
.custom-header {
  background-color: #2d3436 !important; 
  color: #ffffff !important;
}

.custom-header {
  /* Egy kellemes, közepesen halvány lila, ami elüt a háttértől, de nem "üt" */
  background-color: #d1b3ff !important; 
  color: #4b0082 !important; /* Sötétlila szöveg a jobb olvashatóságért */
}

.custom-header th {
  border: none !important;
  background-color: #d1b3ff !important; /* Fixen ugyanaz a lila */
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.85rem;
}

/* Lekerekítések a fejléc szélein */
.custom-header th:first-child {
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}

.custom-header th:last-child {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}

/* SOROK: Itt történik a varázslat */
.custom-row td {
  /* Itt a trükk: rgba-val adunk neki egy nagyon pici pinkes tónust, 
     de alapvetően átlátszó, hogy a body háttere látszódjon */
  background-color: rgba(255, 192, 203, 0.2) !important; 
  border-top: 1px solid rgba(0,0,0,0.05) !important;
  border-bottom: 1px solid rgba(0,0,0,0.05) !important;
  color: #2d3436 !important;
  transition: all 0.2s ease;
}

/* Amikor az egér felette van */
.custom-row:hover td {
  background-color: rgba(255, 192, 203, 0.4) !important;
  transform: translateY(-2px);
}

/* Kijelölt sor */
.row-active td {
  background-color: rgba(255, 192, 203, 0.6) !important;
  font-weight: bold;
}

/* Lekerekítések a sorok szélein */
.custom-row td:first-child {
  border-left: 1px solid rgba(0,0,0,0.05) !important;
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}

.custom-row td:last-child {
  border-right: 1px solid rgba(0,0,0,0.05) !important;
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}

/* Bootstrap felülbírálás: ne legyen fehér csíkozás */
.table-hover tbody tr:hover td {
    color: inherit;
}
</style>