<template>
  <div>
    <div class="hero-video-container">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="/media.mp4" type="video/mp4" />
        A böngésződ nem támogatja a videó lejátszást.
      </video>
    </div>

    <Carousel />

    <div v-if="loading" class="text-center p-2">Adatok betöltése...</div>
    <div v-else class="d-flex flex-wrap justify-content-center gap-3 p-3 bg-light-pink border-bottom">
      <span v-for="location in items" :key="location.id" class="badge bg-malyva">
        {{ location.locationName }} ({{ location.cityName }})
      </span>
    </div>

    <div class="container mt-5">
      <h1 class="twinkle-title text-center">Üdvözlünk a Home oldalon</h1>

      <div class="card p-4 shadow-sm mb-5">
        <div class="mb-3">
          <label for="exampleFormControlInput1" class="form-label">Email address</label>
          <input
            type="email"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="name@example.com"
          />
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Üzenet</label>
          <textarea
            class="form-control"
            id="exampleFormControlTextarea1"
            rows="3"
          ></textarea>
        </div>
      </div>
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
  color: #880e4f;
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