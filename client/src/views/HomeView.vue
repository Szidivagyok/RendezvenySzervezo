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
export default {};
</script>

<style scoped>
.video-fit {
  width: 100%;         /* teljes szélesség */       /* magasság arányosan csökken */
  max-height: 300px;   /* beállítod, milyen magas legyen függőlegesen */
  display: block;
  margin: 0 auto;      /* középre igazítás, ha szükséges */
}
</style>