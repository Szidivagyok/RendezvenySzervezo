<template>
  <div class="page-wrapper">
    <div class="hero-video-container">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="/media.mp4" type="video/mp4" />
        A böngésződ nem támogatja a videó lejátszást.
      </video>
      <div class="hero-overlay d-flex align-items-center justify-content-center">
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

    <div class="flower-overflow-wrapper">
      <img src="/kepek2/virag1.png" alt="dekoráció jobb" class="corner-flower flower-right">
      <img src="/kepek2/virag3.png" alt="dekoráció bal" class="corner-flower flower-left">
    </div>

    <div class="container mt-5 welcome-section">
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
            <div class="about-card p-4 shadow-sm flex-grow-1 d-flex flex-column justify-content-center border-glow">
              <p class="lead italic color-purple mb-4">"Az Ön álma, a mi hivatásunk."</p>
              <p class="description-text">
                Esküvőszervezőként tudjuk, hogy minden pár története egyedi. Célunk, hogy levegyük a vállatokról a szervezés terhét, és egy felejthetetlen napot varázsoljunk nektek.
              </p>
              <div class="contact-details mt-4">
                <p><i class="bi bi-geo-alt-fill me-2 color-pink"></i> 1234 Budapest, Példa utca 12.</p>
                <p><i class="bi bi-envelope-heart-fill me-2 color-pink"></i> hello@eskuvo.hu</p>
                <p><i class="bi bi-telephone-fill me-2 color-pink"></i> +36 30 123 4567</p>
              </div>
            </div>
          </div>

          <div class="col-lg-6 d-flex flex-column h-10">
            <div class="title-spacer mb-5 d-none d-lg-block"></div>
            
            <div class="map-frame shadow-lg flex-grow-1">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2695.565863336767!2d19.0399!3d47.4979!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDfCsDI5JzUyLjQiTiAxOcKwMDInMjMuNiJF!5e0!3m2!1shu!2shu!4v1625000000000!5m2!1shu!2shu" 
                width="100%" 
                height="100%" 
                style="border:0;"
                :allowfullscreen="true" 
                loading="lazy">
              </iframe>
            </div>
            
            <div class="text-center mt-3">
              <a href="https://maps.google.com" target="_blank" class="btn btn-outline-purple rounded-pill">
                <i class="bi bi-cursor-fill me-2"></i>Útvonaltervezés a térképen
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>

    <section class="text-center py-5">
      <div class="container">
        <h2 class="twinkle-title mb-3 fs-2">Megtalálhatóak vagyunk a közösségi médiában</h2>
        <p class="mb-4">
          Kövess minket Instagramon, Facebookon és TikTokon is, hogy ne maradj le a legújabb esküvői inspirációkról!
        </p>

        <div class="d-flex justify-content-center gap-4 fs-3">
          <a href="https://instagram.com" target="_blank" class="color-purple">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="https://facebook.com" target="_blank" class="color-purple">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="https://tiktok.com" target="_blank" class="color-purple">
            <i class="bi bi-tiktok"></i>
          </a>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap");

/* VIRÁG POZÍCIONÁLÁS ALAPOK */
.flower-overflow-wrapper {
  position: relative;
  width: 100%;
  height: 0;
  overflow: visible;
}

.corner-flower {
  position: absolute;
  top: -10px; /* Megtartva a FENTI pozíció */
  width: 500px; /* Megtartva a NAGY méret */
  height: auto;
  z-index: 1; 
  pointer-events: none;
  opacity: 0.95; 
}

/* KÜLÖN POZÍCIÓK A KÉT OLDALRA */
.flower-right {
  right: -20px; /* Megtartva a BALRA tolás a jobb szélétől */
}

.flower-left {
  left: 0; /* MÓDOSÍTVA: Jobbra tolva (-20px-ről 0-ra) */
}

.welcome-section {
  position: relative;
  z-index: 2; 
}

/* HERO ÉS EGYÉB STÍLUSOK */
.hero-video-container { position: relative; max-height: 500px; overflow: hidden; }
.hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.3); }
.hero-text { font-family: "Twinkle Star", cursive; font-size: 4.5rem; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); }
.hero-subtext { font-size: 1.5rem; letter-spacing: 2px; text-transform: uppercase; }

/* STATS BAR */
.stats-bar { background: linear-gradient(90deg, #fdf2f8 0%, #f3e8ff 100%); position: relative; z-index: 3; }
.stat-number { font-family: "Twinkle Star", cursive; color: #a855f7; font-size: 3rem; margin-bottom: 0; }
.stat-label { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }

/* FEATURE BOXES */
.feature-box { padding: 30px; border-radius: 20px; background: white; transition: all 0.3s ease; border: 1px solid #f3e8ff; position: relative; z-index: 2; }
.feature-box:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(168, 85, 247, 0.1); }
.active-feature { border: 2px solid #f5d0fe; background: #fffaff; }
.icon-circle { width: 60px; height: 60px; background: #f3e8ff; color: #a855f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.5rem; }

/* RÓLUNK ÉS TÉRKÉP STÍLUSOK */
.twinkle-title { font-family: "Twinkle Star", cursive; font-size: 3.5rem; color: #8533e4; position: relative; z-index: 2; }
.about-card { background: white; border-radius: 20px; border-left: 8px solid #f3e8ff; }
.border-glow { box-shadow: 0 0 15px rgba(243, 232, 255, 0.5); }
.map-frame { border-radius: 25px; border: 10px solid white; overflow: hidden; height: 100%; }
.title-spacer { height: 5.2rem; }
.btn-outline-purple { border: 2px solid #a855f7; color: #a855f7; font-weight: 600; padding: 10px 30px; }
.btn-outline-purple:hover { background: linear-gradient(45deg, #a855f7, #ec4899); color: white; border: 2px solid transparent; }
.color-purple { color: #a855f7; }
.color-pink { color: #ec4899; }

@media (max-width: 991px) {
  .hero-text { font-size: 2.5rem; }
  .title-spacer { display: none; }
  .corner-flower { width: 280px; top: 0px; }
  .flower-right { right: -10px; }
  .flower-left { left: 0; } /* Mobilon is módosítva */
}
</style>