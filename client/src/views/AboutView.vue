<template>
  <div class="page-wrapper">
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
            <div class="scrollable-list shadow-sm">
              <ul class="list-group">
                <li 
                  class="list-group-item my-pointer" 
                  v-for="location in locationItems" :key="location.id"
                  :class="{ 'active-location': selectedPictureId === location.id }"
                  @click="selectedPictureId = location.id"
                >
                  <i class="bi bi-geo-alt-fill me-2"></i>
                  <span class="location-text">
                    {{ location.cityName }}, {{ location.locationName }}
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-lg-8 col-xl-9">
          <div v-if="item.id != 1" class="intro-content">
            <img
              src="/kepek/location_1_1.jpg"
              class="rounded img-fluid mb-3 shadow-sm main-promo-img"
              alt="Rólunk kép"
            />
            <p class="description-text">Itt találhatók a finom ételeink és a bemutatkozásunk...</p>
          </div>

          <div class="carousel-wrapper shadow">
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
  components: {
    Carousel,
  },
  data() {
    return {
      selectedPictureId: null,
    }
  },
  watch: {
    async selectedPictureId(value) {
        if(value) await this.getLocationpicturesById(value);
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
    if (this.locationItems && this.locationItems.length > 0) {
      this.selectedPictureId = this.locationItems[0].id;
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap');

.page-wrapper {
  background-color: #fffafc; /* Nagyon halvány rózsaszín alapháttér */
}

/* NAVBAR - Gradiens és árnyék */
.menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: linear-gradient(90deg, #fce7f3 0%, #f3e8ff 100%);
  padding: 1rem;
  box-shadow: 0 4px 15px rgba(168, 85, 247, 0.15);
  z-index: 1000;
  border-bottom: 2px solid #f5d0fe;
}

.menu ul {
  display: flex;
  gap: 2.5rem;
  list-style: none;
  margin: 0;
  padding: 0;
  justify-content: center;
}

.nav-link-style {
  text-decoration: none;
  color: #a855f7;
  font-family: 'Twinkle Star', cursive;
  font-size: 1.7rem;
  transition: all 0.3s ease;
}

.nav-link-style:hover {
  color: #db2777;
  transform: translateY(-2px);
}

/* SZEKCIÓK ÉS CÍMEK */
.section {
  min-height: 100vh;
  padding: 8rem 2rem 4rem 2rem;
  border-bottom: 1px dashed #f5d0fe;
}

.twinkle-header {
  font-family: 'Twinkle Star', cursive;
  font-size: 3.8rem;
  background: linear-gradient(45deg, #a855f7, #8533e4);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 2.5rem;
  display: inline-block;
}

/* BAL OLDALI GÖRGETHETŐ LISTA */
.scrollable-list {
  max-height: 500px; /* Itt állítsd be a kívánt magasságot */
  overflow-y: auto;
  border-radius: 15px;
  background: white;
  border: 1px solid #f5d0fe;
}

/* Egyedi görgetősáv (Scrollbar) dizájn */
.scrollable-list::-webkit-scrollbar {
  width: 10px;
}
.scrollable-list::-webkit-scrollbar-track {
  background: #fdf2f8;
  border-radius: 10px;
}
.scrollable-list::-webkit-scrollbar-thumb {
  background: linear-gradient(#d8b4fe, #fda4af);
  border-radius: 10px;
  border: 2px solid #fdf2f8;
}
.scrollable-list::-webkit-scrollbar-thumb:hover {
  background: #a855f7;
}

/* LISTA ELEMEK */
.my-pointer {
  cursor: pointer;
  padding: 1rem;
  transition: all 0.2s ease;
  border-left: 5px solid transparent;
  color: #6b7280;
}

.my-pointer:hover {
  background-color: #fdf2f8;
  padding-left: 1.5rem;
  color: #db2777;
}

.active-location {
  background-color: #f3e8ff !important;
  border-left: 5px solid #a855f7 !important;
  color: #a855f7 !important;
  font-weight: 600;
}

/* JOBB OLDALI TARTALOM */
.carousel-wrapper {
  border-radius: 20px;
  overflow: hidden;
  border: 4px solid white;
}

.main-promo-img {
  max-height: 300px;
  width: 100%;
  object-fit: cover;
}

.description-text {
  color: #7c3aed;
  font-style: italic;
  font-size: 1.1rem;
}
</style>