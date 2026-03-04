<template>
  <div>
      <div class="hero-video-container">
      <video
        autoplay
        muted
        loop
        playsinline
        class="hero-video"
      >
        <source src="/media.mp4" type="video/mp4" />
        A böngésződ nem támogatja a videó lejátszást.
      </video>
    </div>
      <div v-if="loading" class="text-center p-2">Adatok betöltése...</div>
    <div v-else class="d-flex flex-wrap justify-content-center gap-3 p-3 bg-light border-bottom">
      <span v-for="location in items" :key="location.id" class="badge bg-primary">
        {{ location.locationName }} ({{ location.cityName }})
      </span>
    </div>

    <h1>Home</h1>
    <!-- <video autoplay muted loop playsinline width="100%" height="80%">
      <source src="/media.mp4" type="video/mp4" />
      A böngésződ nem támogatja a videó lejátszást.
    </video> -->

    <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label"
        >Email address</label
      >
      <input
        type="email"
        class="form-control"
        id="exampleFormControlInput1"
        placeholder="name@example.com"
      />
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label"
        >Example textarea</label
      >
      <textarea
        class="form-control"
        id="exampleFormControlTextarea1"
        rows="3"
      ></textarea>
    </div>
  </div>

</template>

<script>
import { useLocationStore } from '@/stores/locationStore';
import {mapState, mapActions} from 'pinia';
export default { 
name: "HomeView",
components: {
  
},
computed: {
//módosít
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
    await this.getAll();
    console.log("Megérkeztek a helyszínek:", this.items);
  }
};
</script>

<style scoped>
.video-fit {
  width: 100%;         /* teljes szélesség */       /* magasság arányosan csökken */
  max-height: 300px;   /* beállítod, milyen magas legyen függőlegesen */
  display: block;
  margin: 0 auto;      /* középre igazítás, ha szükséges */
}
</style>