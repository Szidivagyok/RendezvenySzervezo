<template>
  <div>
    <!-- Navigációs menü -->
    <nav class="menu">
      <ul>
        <li><a href="#etelek" @click.prevent="scrollTo('etelek')">Ételeink</a></li>
        <li><a href="#helyszinek" @click.prevent="scrollTo('helyszinek')">Helyszíneink</a></li>
        <li><a href="#szolgaltatasok" @click.prevent="scrollTo('szolgaltatasok')">Szolgáltatásaink</a></li>
      </ul>
    </nav>

    <!-- Részletek az oldalban -->
    <section id="etelek" class="section">
      <h2>Ételeink  <i class="bi bi-fork-knife"></i></h2>
     <img src="/kepek/kep1.jpg" class="rounded" alt="kepek">
      <p>Itt találhatók a finom ételeink...</p>
    </section>

    <section id="helyszinek" class="section">
      <h2>Helyszíneink<i class="bi bi-houses-fill"></i></h2>
      <p>Itt találhatók a helyszíneink...</p>
    </section>

    <section id="szolgaltatasok" class="section">
      <h2>Szolgáltatásaink <i class="bi bi-ticket-perforated-fill"></i> </h2>
      <p>Itt találhatók a szolgáltatásaink...</p>
    </section>
  </div>
</template>

<script>
export default {
  methods: {
    scrollTo(id) {
      const el = document.getElementById(id)
      if (el) {
        // Smooth scroll
        el.scrollIntoView({ behavior: 'smooth' })
      }
    }
  }
}
</script>

<style>
.menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: #fff;
  padding: 1rem;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.menu ul {
  display: flex;
  gap: 1rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.section {
  min-height: 100vh; /* minden szekció kitölti a képernyőt */
  padding: 5rem 2rem;
  border-bottom: 1px solid #ddd;
}
</style>