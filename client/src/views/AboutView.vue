<template>
  <div>
    <nav class="menu">
      <ul>
        <li v-for="item in items" :key="item.id">
          <a 
            :href="`#${item.id}`" 
            class="nav-link-style"
            @click.prevent="scrollTo(`${item.id}`)"
          >
            {{ item.serviceTypeName }}
          </a>
        </li>
      </ul>
    </nav>

    <section v-for="item in items" :key="item.id" :id="`${item.id}`" class="section">
      <h2 class="twinkle-header">
        {{ item.serviceTypeName }} <i class="bi bi-stars"></i>
      </h2>

      <div class="row">
        <div class="col-lg-4 col-xxl-3">
          <div v-if="item.id == 1">
            <ul class="list-group shadow-sm">
              <li 
                class="list-group-item my-pointer" 
                v-for="location in locationItems" :key="location.id"
                :class="{ 'active-location': selectedPictureId === location.id }"
                @click="selectedPictureId = location.id"
              >
                <i class="bi bi-geo-alt-fill me-2"></i>
                {{ location.cityName }}, {{ location.locationName }}
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-8 col-xl-9">
          <div v-if="item.id != 1">
            <img
              src="/kepek/location_1_1.jpg"
              class="rounded img-fluid mb-3 shadow"
              alt="Rólunk kép"
            />
            <p class="description-text">Itt találhatók a finom ételeink és a bemutatkozásunk...</p>
          </div>

          <div class="carousel-wrapper">
            <Carousel :images="pictureItems" />
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
    ...mapState(usePictureStore, {pictureItems: 'items'}),
  },
  methods: {
    ...mapActions(useServiceTypeStore, ['getAll']),
    ...mapActions(useLocationStore, {locationGetAll: 'getAll'}),
    ...mapActions(usePictureStore, ['getLocationpicturesById']),
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
    if (this.locationItems.length > 0) {
      this.selectedPictureId = this.locationItems[0].id;
      await this.getLocationpicturesById(this.selectedPictureId);
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap');

/* NAVBAR STÍLUS - Halványlila-rózsaszín átmenet */
.menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: linear-gradient(90deg, #fce7f3 0%, #f3e8ff 100%);
  padding: 1.2rem;
  box-shadow: 0 4px 12px rgba(168, 85, 247, 0.15);
  z-index: 1000;
  border-bottom: 2px solid #f5d0fe;
}

.menu ul {
  display: flex;
  gap: 2rem;
  list-style: none;
  margin: 0;
  padding: 0;
  justify-content: center;
}

.nav-link-style {
  text-decoration: none;
  color: #a855f7; /* Lila */
  font-family: 'Twinkle Star', cursive;
  font-size: 1.6rem;
  transition: all 0.3s ease;
}

.nav-link-style:hover {
  color: #db2777; /* Sötétebb rózsaszín */
  transform: scale(1.1);
}

/* CÍMEK STÍLUSA */
.twinkle-header {
  font-family: 'Twinkle Star', cursive;
  font-size: 3.5rem;
  background: linear-gradient(45deg, #a855f7, #8533e4);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 2rem;
  display: inline-block;
}

.section {
  min-height: 100vh;
  padding: 8rem 2rem 4rem 2rem;
  background-color: #fffafc; /* Nagyon halvány háttér a szekcióknak */
  border-bottom: 1px dashed #f5d0fe;
}

/* LISTA ÉS POINTER */
.my-pointer {
  cursor: pointer;
  transition: all 0.2s ease;
  border-left: 4px solid transparent;
}

.my-pointer:hover {
  background-color: #fdf2f8;
  border-left: 4px solid #ec4899;
  padding-left: 1.5rem;
}

.active-location {
  background-color: #f3e8ff !important;
  border-left: 4px solid #a855f7 !important;
  font-weight: bold;
}

.carousel-wrapper {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
</style>