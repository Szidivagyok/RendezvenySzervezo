<template>
  
  <div class="container-fluid my-container my-border mt-2">
    <!-- Head -->
    <Header/>

    <!-- Menü -->
    <Menu class="my-border"/>
    <Breadcrumb class="my-border"/>

    <!-- Ide töltődnek be az oldalak -->
    <RouterView  class="my-border"/>


    <Footer  class="my-border"
      :content="footerContent"
    />
    <ToastContanier/>
  </div>
</template>

<script>

import Menu from './components/Layout/Menu.vue';
import Header from './components/Layout/Header.vue';
import Footer from './components/Layout/Footer.vue';
import Breadcrumb from './components/Layout/Breadcrumb.vue';
import ToastContanier from './components/Message/ToastContanier.vue';
export default {
  components:{
    Menu,
    Header,
    Footer,
    Breadcrumb,
    ToastContanier,
  },
  data() {
    return {
      footerContent: "Ez egy másik lábléc szöveg",
    }
  }
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Twinkle+Star&display=swap');
#app {
  background-color: #fce4ec; /* Halványabb babarózsaszín */
  min-height: 100vh;
  width: 100%;
}

/* Ha azt akarod, hogy a teljes böngészőablak olyan legyen */
body {
  background-color: #fce4ec !important;
  margin: 0;
}


</style>

