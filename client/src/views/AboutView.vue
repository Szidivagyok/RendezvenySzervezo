<template>
  <div class="page-wrapper">
    <nav class="menu">
      <ul>
        <li v-for="item in items" :key="item.id">
          <a :href="`#${item.id}`" class="nav-link-style" @click.prevent="scrollTo(`${item.id}`)">
            {{ item.serviceTypeName }}
          </a>
        </li>
      </ul>
    </nav>

    <section v-for="item in items" :key="item.id" :id="`${item.id}`" class="section">
      <h2 class="twinkle-header">
        {{ item.serviceTypeName }}
        <i :class="['bi', item.id == 1 ? 'bi-houses-fill' : (item.id == 2 ? 'bi-egg-fried' : 'bi-ticket')]"></i>
      </h2>

      <div class="row">
        <div class="col-lg-4 col-xxl-3">
          <div class="scrollable-list shadow-sm">
            <ul class="list-group">
              <template v-if="item.id == 1">
                <li class="list-group-item my-pointer" v-for="loc in locationItems" :key="loc.id"
                  :class="{ 'active-location': selectedState[item.id]?.id === loc.id }"
                  @click="selectItem(item.id, loc.id, 'location')">
                  <i class="bi bi-geo-alt-fill me-2"></i> {{ loc.cityName }}, {{ loc.locationName }}
                </li>
              </template>
              <template v-else>
                <li class="list-group-item my-pointer" v-for="serv in getServicesByTypeId(item.id)" :key="serv.id"
                  :class="{ 'active-location': selectedState[item.id]?.id === serv.id }"
                  @click="selectItem(item.id, serv.id, 'service')">
                  <i class="bi bi-egg-fried me-2"></i> {{ serv.service }} 
                </li>
              </template>
            </ul>
          </div>
        </div>

        <div class="col-lg-8 col-xl-9">
          <div class="carousel-wrapper shadow">
          <Carousel 
  :images="sectionImages[item.id] || []" 
  :carouselId="'carousel-' + item.id" 
  :key="item.id + '-' + (selectedState[item.id]?.id || 0)" 
/>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useServiceTypeStore } from "@/stores/servicetypeStore";
import { useLocationStore } from "@/stores/locationStore";
import { useServiceStore } from "@/stores/serviceStore";
import { usePictureStore } from "@/stores/pictureStore";
import Carousel from "@/components/Carousel/Carousel.vue";

export default {
  components: { Carousel },
  data() {
    return {
      selectedState: {}, // Melyik ID van kiválasztva szekciónként: { 1: {id: 5, type: 'location'} }
      sectionImages: {}   // Képek szekciónként: { 1: [...], 2: [...] }
    }
  },
  computed: {
    ...mapState(useServiceTypeStore, ['items']),
    ...mapState(useLocationStore, { locationItems: 'items' }),
    ...mapState(useServiceStore, { serviceItems: 'items' }),
  },
  methods: {
    ...mapActions(useServiceTypeStore, ['getAll']),
    ...mapActions(useLocationStore, { locationGetAll: 'getAll' }),
    ...mapActions(useServiceStore, { serviceGetAll: 'getAll' }),
    ...mapActions(usePictureStore, ['getLocationpicturesById', 'getPicturesByServiceId']),

    getServicesByTypeId(typeId) {
      return this.serviceItems.filter(s => s.serviceTypeId == typeId);
    },

  async selectItem(sectionId, itemId, type) {
  // 1. Megjegyezzük, melyik szekcióban melyik ID az aktív
  this.selectedState = { ...this.selectedState, [sectionId]: { id: itemId, type: type } };

  const pictureStore = usePictureStore();
  
  // 2. Szétválasztjuk: helyszínhez a régi, ételhez az új API kell
  if (type === 'location') {
    await pictureStore.getLocationpicturesById(itemId);
  } else {
    await pictureStore.getPicturesByServiceId(itemId);
  }

  // 3. Mentjük a képeket a szekció saját "fiókjába"
  this.sectionImages = { ...this.sectionImages, [sectionId]: [...pictureStore.items] };
},
    
    scrollTo(id) {
      document.getElementById(id)?.scrollIntoView({ behavior: "smooth" });
    }
  },
  async mounted() {
  await Promise.all([this.getAll(), this.locationGetAll(), this.serviceGetAll()]);

  // Alapértelmezett képek betöltése mind a 3 szekcióhoz az oldalnyitáskor:
  
  // 1. Helyszínek (ID 1)
  if (this.locationItems.length > 0) {
    await this.selectItem(1, this.locationItems[0].id, 'location');
  }

  // 2. Ételek (ID 2)
  const firstFood = this.getServicesByTypeId(2)[0];
  if (firstFood) {
    await this.selectItem(2, firstFood.id, 'service');
  }

  // 3. Szolgáltatások (ID 3)
  const firstService = this.getServicesByTypeId(3)[0];
  if (firstService) {
    await this.selectItem(3, firstService.id, 'service');
  }
}
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap');

.page-wrapper { background-color: #fffafc; }

/* Menü reszponzivitás: mobilon kisebb betűk és tördelés */
.menu { 
  position: fixed; top: 0; left: 0; right: 0; 
  background: linear-gradient(90deg, #fce7f3 0%, #f3e8ff 100%); 
  padding: 0.8rem; z-index: 1000; border-bottom: 2px solid #f5d0fe; 
}
.menu ul { display: flex; gap: 1.5rem; list-style: none; margin: 0; padding: 0; justify-content: center; flex-wrap: wrap; }
.nav-link-style { text-decoration: none; color: #a855f7; font-family: 'Twinkle Star', cursive; font-size: 1.4rem; }

/* Szekciók távolsága */
.section { min-height: 100vh; padding: 6rem 1rem 4rem 1rem; border-bottom: 1px dashed #f5d0fe; }

.twinkle-header { 
  font-family: 'Twinkle Star', cursive; 
  font-size: clamp(2.5rem, 8vw, 3.8rem); /* Rugalmas betűméret */
  background: linear-gradient(45deg, #a855f7, #8533e4); 
  -webkit-background-clip: text; 
  -webkit-text-fill-color: transparent; 
  margin-bottom: 2rem;
}

/* Lista magassága mobilon változik */
.scrollable-list { 
  max-height: 400px; 
  overflow-y: auto; 
  border-radius: 15px; 
  background: white; 
  border: 1px solid #f5d0fe;
  margin-bottom: 1.5rem; /* Távolság a képtől mobilon */
}

/* CAROUSEL RESZPONZIVITÁS */
.carousel-wrapper { 
  border-radius: 20px; 
  overflow: hidden; 
  border: 4px solid white;
  width: 100%;
  position: relative;
  /* Kép méretének kordában tartása */
  max-height: 600px; 
  display: flex;
  align-items: center;
  justify-content: center;
  background: #eee; /* Amíg tölt a kép */
}

/* Biztosítjuk, hogy a Carousel-en belüli img tag-ek (ha ott vannak) reszponzívak legyenek */
:deep(.carousel-item img) {
  width: 100%;
  height: auto;
  object-fit: cover; /* Kitölti a helyet vágás nélkül/vágással */
  aspect-ratio: 16 / 9; /* Fix oldalarány, hogy ne ugráljon az oldal */
}

.my-pointer { cursor: pointer; padding: 1rem; border-left: 5px solid transparent; color: #6b7280; transition: 0.3s; }
.active-location { background-color: #f3e8ff !important; border-left: 5px solid #a855f7 !important; color: #a855f7 !important; font-weight: 600; }

/* MÉDIA LEKÉRDEZÉSEK EXTRA FINOMHANGOLÁSHOZ */
@media (max-width: 991px) {
  .section { padding-top: 5rem; }
  .scrollable-list { max-height: 250px; } /* Mobilon rövidebb lista, hogy látszódjon alatta a kép */
  .nav-link-style { font-size: 1.2rem; }
}

@media (min-width: 1200px) {
  .nav-link-style { font-size: 1.7rem; }
  .scrollable-list { max-height: 600px; }
}
</style>