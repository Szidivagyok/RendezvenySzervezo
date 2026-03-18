<template>
  <div :id="carouselId" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div
        v-for="(image, index) in images"
        :key="index"
        :class="['carousel-item', { active: index === 0 }]"
      >
        <img
          :src="`/kepek/${image.pictureName}`"
          class="d-block w-100 carousel-img"
          alt="Carousel image"
        />
      </div>
    </div>

    <button
      class="carousel-control-prev"
      type="button"
      :data-bs-target="'#' + carouselId"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>

    <button
      class="carousel-control-next"
      type="button"
      :data-bs-target="'#' + carouselId"
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
    // A szülőtől (AboutView) kapott egyedi azonosító
    carouselId: {
      type: String,
      default: "mainCarousel",
    },
    images: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {};
  },
};
</script>

<style scoped>
.carousel-img {
  max-height: 600px;
  max-width: 1400px;
  object-fit: cover; /* Megtartja az arányokat, nem nyújtja a képet */
}

.twinkle-font {
  font-family: "Twinkle Star", cursive;
  font-size: 3rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}
</style>