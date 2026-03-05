<template>
  <div>
    <div class="hero-video-container">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="/media.mp4" type="video/mp4" />
        A böngésződ nem támogatja a videó lejátszást.
      </video>
    </div>

    <Carousel />

   

    <div class="container mt-5">
      <h1 class="twinkle-title text-center">Üdvözlünk az oldalon</h1>

     
    </div>
  </div>
</template>

<script>
import { useLocationStore } from '@/stores/locationStore';
import { mapState, mapActions } from 'pinia';

export default {
  name: "HomeView",
  components: {
  },
  computed: {
    ...mapState(useLocationStore, [
      "item",
      "items",
      "loading",
    ]),
  },
  methods: {
    ...mapActions(useLocationStore, [
      "getAll",
    ]),
  },
  async created() {
    // Adatok lekérése a Pinián keresztül
    await this.getAll();
    console.log("Megérkeztek a helyszínek:", this.items);
  }
};
</script>

<style scoped>
/* Egyedi színek és betűtípus a Home oldalra */
.bg-light-pink {
  background-color: #fff0f5; /* Nagyon halvány rózsaszín háttér a badges sornak */
}

.bg-malyva {
  background-color: #ad1457; /* Sötétebb mályva szín a buborékoknak */
}

.twinkle-title {
  font-family: 'Twinkle Star', cursive;
  font-size: 3.5rem;
  color: #8533e4;
  margin-bottom: 2rem;
}

.hero-video-container {
  width: 100%;
  overflow: hidden;
  max-height: 400px;
}

.hero-video {
  width: 100%;
  height: auto;
  object-fit: cover;
}

.card {
  border: none;
  background-color: rgba(255, 255, 255, 0.8); /* Félig áttetsző fehér kártya */
}
</style>