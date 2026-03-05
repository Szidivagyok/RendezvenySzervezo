<template>
  <div>
    <nav class="menu">
      <ul>
        <li v-for="item in items" :key="item.id">
          <a :href="`#${item.id}`" @click.prevent="scrollTo(`${item.id}`)">{{ item.serviceTypeName }}</a>
        </li>

      </ul>
    </nav>

    <section v-for="item in items" :key="item.id" :id="`${item.id}`" class="section">
      <h2>{{ item.serviceTypeName }} <i class="bi bi-file-person-fill"></i></h2>
      <div class="row">
        <div class="col-3">
           <!-- helyszinek -->
            <div v-if="item.id == 1">
              <ul class="list-group">
                <li  class="list-group-item" v-for="location in locationItems" :key="location.id"
                @click="selectedPictureId = location.id"
                >
                  {{ location.cityName }}
                  {{ location.street }}
                  {{ location.houseNumber }}
                  {{ location.locationName }}
                </li>
              </ul>
            </div>
        </div>
        <div class="col-9">
          <div  v-if="item.id != 1">
            <img
            src="/kepek/locations_1_1.jpg"
            class="rounded img-fluid mb-3"
            alt="Rólunk kép"
            />
            <p>Itt találhatók a finom ételeink és a bemutatkozásunk...</p>

          </div>

           <Carousel v-if="item.id == 1"
           :images="pictureItems"
           />
        </div>
      </div>
    </section>


    
    
  </div>
</template>

<script >
// 1. Importáljuk a komponenst
// import Carousel from "@/components/Carousel/Carousel.vue";
import { mapActions, mapState } from "pinia";
import { useServiceTypeStore } from "@/stores/servicetypeStore";
import { useLocationStore } from "@/stores/locationStore";
import { usePictureStore } from "@/stores/pictureStore";
import Carousel from "@/components/Carousel/Carousel.vue";
export default {
  data() {
    return {
      selectedPictureId: null,
    }
  },
  components: {
    Carousel,
  },
  watch: {
    async selectedPictureId(value) {
        await this.getLocationpicturesById(value);
    }

  },
  computed: {
    ...mapState(useServiceTypeStore, ['items']),
    ...mapState(useLocationStore, {locationItems: 'items'}),
     ...mapState(useLocationStore, {pictureItems: 'items'}),
  },
  methods: {
    ...mapActions(useServiceTypeStore, ['getAll']),
    ...mapActions(useLocationStore, {locationGetAll: 'getAll'}),
     ...mapActions(usePictureStore, {pictureGetAll: 'getLocationpicturesById'}),
    scrollTo(id) {
      const el = document.getElementById(id);
      if (el) {
        el.scrollIntoView({ behavior: "smooth" });
      }
    },
  },
  async mounted(){
    await this.getAll();
    await this.locationGetAll();
    await this.getLocationpicturesById(this.locationItems[0].id);
  }
};

// 2. A görgetés funkció modern script setup-ban
</script>

<style scoped>
/* A scoped stílus biztosítja, hogy ne zavarja be más oldalaknak */
.menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: #fff;
  padding: 1rem;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  z-index: 1000; /* Legyen minden felett */
}

.menu ul {
  display: flex;
  gap: 1rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.section {
  min-height: 100vh;
  padding: 6rem 2rem 2rem 2rem; /* Fent több hely a fix menü miatt */
  border-bottom: 1px solid #ddd;
}

.carousel-container {
  max-width: 1000px; /* Hogy ne legyen túl széles nagy monitoron */
  margin: 0 auto;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.img-fluid {
  max-width: 100%;
  height: auto;
}
</style>