<template>
  <div class="page-wrapper">
    <div class="hero-video-container">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="/media.mp4" type="video/mp4" />
        A böngésződ nem támogatja a videó lejátszást.
      </video>
      <div
        class="hero-overlay d-flex align-items-center justify-content-center"
      >
        <div class="text-center text-white">
          <h1 class="hero-text">Álmodjátok meg, mi valóra váltjuk</h1>
          <p class="hero-subtext">Prémium esküvőszervezés szívvel-lélekkel</p>
        </div>
      </div>
    </div>

    <div class="stats-bar py-5 shadow-sm">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-3 col-6 mb-4 mb-md-0">
            <h3 class="stat-number">150+</h3>
            <p class="stat-label">Boldog Pár</p>
          </div>
          <div class="col-md-3 col-6 mb-4 mb-md-0">
            <h3 class="stat-number">10+</h3>
            <p class="stat-label">Év Tapasztalat</p>
          </div>
          <div class="col-md-3 col-6">
            <h3 class="stat-number">45</h3>
            <p class="stat-label">Kiváló Helyszín</p>
          </div>
          <div class="col-md-3 col-6">
            <h3 class="stat-number">100%</h3>
            <p class="stat-label">Szenvedély</p>
          </div>
        </div>
      </div>
    </div>

    <Carousel />

    <div class="container mt-5">
      <h1 class="twinkle-title text-center">Üdvözlünk az oldalon</h1>

      <div class="row my-5 py-4 text-center">
        <div class="col-md-4 mb-4">
          <div class="feature-box">
            <div class="icon-circle"><i class="bi bi-stars"></i></div>
            <h4>Egyedi Dekó</h4>
            <p>Minden esküvőt a pár személyiségéhez igazítunk.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-box active-feature">
            <div class="icon-circle"><i class="bi bi-shield-check"></i></div>
            <h4>Stresszmentes</h4>
            <p>Ti csak élvezzétek a napot, a többit mi intézzük.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-box">
            <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
            <h4>Precizitás</h4>
            <p>A menetrend nálunk szent és sérthetetlen.</p>
          </div>
        </div>
      </div>

      <section class="about-map-section my-5 py-5">
        <div class="row align-items-stretch">
          <div class="col-lg-6 mb-4 mb-lg-0 d-flex flex-column">
            <h2 class="twinkle-title text-start mb-4">
              Rólunk <i class="bi bi-heart-fill ms-2"></i>
            </h2>
            <div
              class="about-card p-4 shadow-sm flex-grow-1 d-flex flex-column justify-content-center border-glow"
            >
              <p class="lead italic color-purple mb-4">
                "Az Ön álma, a mi hivatásunk."
              </p>
              <p class="description-text">
                Esküvőszervezőként tudjuk, hogy minden pár története egyedi.
                Célunk, hogy levegyük a vállatokról a szervezés terhét, és egy
                felejthetetlen napot varázsoljunk nektek.
              </p>
              <div class="contact-details mt-4">
                <p>
                  <i class="bi bi-geo-alt-fill me-2 color-pink"></i> 1234
                  Budapest, Példa utca 12.
                </p>
                <p>
                  <i class="bi bi-envelope-heart-fill me-2 color-pink"></i>
                  hello@eskuvo.hu
                </p>
                <p>
                  <i class="bi bi-telephone-fill me-2 color-pink"></i> +36 30
                  123 4567
                </p>
              </div>
            </div>
          </div>

          <div class="col-lg-6 d-flex flex-column">
            <div class="title-spacer mb-4 d-none d-lg-block"></div>
            <div class="map-frame shadow-lg flex-grow-1">
              <iframe
                src="https://www.google.com/maps/embed?..."
                width="100%"
                height="100%"
                style="border: 0; min-height: 400px"
                :allowfullscreen="true"
                loading="lazy"
              >
              </iframe>
            </div>
            <div class="text-center mt-3">
              <a
                href="#"
                target="_blank"
                class="btn btn-outline-purple rounded-pill"
              >
                <i class="bi bi-cursor-fill me-2"></i>Útvonaltervezés
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap");

/* HERO OVERLAY */
.hero-video-container {
  position: relative;
  max-height: 500px;
  overflow: hidden;
}
.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
}
.hero-text {
  font-family: "Twinkle Star", cursive;
  font-size: 4.5rem;
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}
.hero-subtext {
  font-size: 1.5rem;
  letter-spacing: 2px;
  text-transform: uppercase;
}

/* STATS BAR */
.stats-bar {
  background: linear-gradient(90deg, #fdf2f8 0%, #f3e8ff 100%);
}
.stat-number {
  font-family: "Twinkle Star", cursive;
  color: #a855f7;
  font-size: 3rem;
  margin-bottom: 0;
}
.stat-label {
  color: #6b7280;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.8rem;
}

/* FEATURE BOXES */
.feature-box {
  padding: 30px;
  border-radius: 20px;
  background: white;
  transition: all 0.3s ease;
  border: 1px solid #f3e8ff;
}
.feature-box:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 20px rgba(168, 85, 247, 0.1);
}
.active-feature {
  border: 2px solid #f5d0fe;
  background: #fffaff;
}
.icon-circle {
  width: 60px;
  height: 60px;
  background: #f3e8ff;
  color: #a855f7;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  font-size: 1.5rem;
}

/* KORÁBBI STÍLUSOK MEGTARTVA ÉS FINOMÍTVA */
.twinkle-title {
  font-family: "Twinkle Star", cursive;
  font-size: 3.5rem;
  color: #8533e4;
}
.about-card {
  background: white;
  border-radius: 20px;
  border-left: 8px solid #f3e8ff;
}
.border-glow {
  box-shadow: 0 0 15px rgba(243, 232, 255, 0.5);
}
.map-frame {
  border-radius: 25px;
  border: 10px solid white;
  overflow: hidden;
}
.title-spacer {
  height: 5.2rem;
}
.btn-outline-purple {
  border: 2px solid #a855f7;
  color: #a855f7;
  font-weight: 600;
  padding: 10px 30px;
}
.btn-outline-purple:hover {
  background: linear-gradient(45deg, #a855f7, #ec4899);
  color: white;
  border: 2px solid transparent;
}
.color-purple {
  color: #a855f7;
}
.color-pink {
  color: #ec4899;
}

@media (max-width: 991px) {
  .hero-text {
    font-size: 2.5rem;
  }
  .title-spacer {
    display: none;
  }
}
</style>