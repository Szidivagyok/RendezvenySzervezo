<template>
  <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div
        v-for="(image, index) in images"
        :key="index"
        :class="['carousel-item', { active: index === 0 }]"
      >
        <img
          :src="`/kepek/${image.pictureName}`"
          class="d-block w-100 carousel-img"
         
        />
        <!-- <img
          :src="image.src"
          class="d-block w-100 carousel-img"
          :alt="image.alt"
        /> -->
        <!-- <div class="carousel-caption d-none d-md-block">
          <h5 class="twinkle-font">{{ image.pictureName}}</h5>
        </div> -->
      </div>
    </div>
 
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#mainCarousel"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#mainCarousel"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</template>
 
<script>
export default {
  name: "Carousel",
  props: {
    images: { type: Array,
      default: [
        {
          pictureName: "https://picsum.photos/id/10/1200/400",
          alt: "Helyszín 1",
          title: "Csodás Helyszínek",
        },
        {
          pictureName: "https://picsum.photos/id/20/1200/400",
          alt: "Helyszín 2",
          title: "Unfelejthetetlen Élmények",
        },
        {
          pictureName: "https://picsum.photos/id/48/1200/400",
          alt: "Helyszín 3",
          title: "Profi Szervezés",
        },
      ],
    },
  },
 
  data() {
    return {};
  },
  // Definiáljuk, milyen adatokat vár a komponens (ha később máshonnan jönnének a képek)
};
</script>
 
<style scoped>
.carousel-img {
  max-height: 600px;
  max-width: 1400px; /* Beállíthatod, milyen magas legyen maximum */
  object-fit: cover; /* Ez biztosítja, hogy ne torzuljanak a képek */
}
 
.twinkle-font {
  font-family: "Twinkle Star", cursive;
  font-size: 3rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}
 
 
</style>