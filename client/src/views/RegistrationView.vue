<template>
  <div class="registration-page">
    <div class="registration-card">
      <header class="form-header">
        <div class="icon-wrapper">💍</div>
        <h1>Regisztráció</h1>
        <p>Kezdjük el tervezni a nagy napot!</p>
        <div class="decorative-line"></div>
      </header>

      <UserRegistration
        ref="form"
        @createUser="handlerCreateUser"
        class="custom-form"
      />
    </div>
  </div>
</template>

<script>
import { mapActions } from "pinia";
import { useUserStore } from "@/stores/userStore";
import UserRegistration from '@/components/User/UserRegistration.vue';

export default {
  name: 'RegistrationView',
  components: {
    UserRegistration
  },
  methods: {
    ...mapActions(useUserStore,['createUser']),
    async handlerCreateUser({data, done}){
      console.log(data);
      try {
        await this.createUser(data);
        done(true);
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.$refs.form.setServerErrors(err.response.data.errors);
          done(false);
        } else {
          done(false);
        }
      }
    }
  }
}
</script>

<style scoped>
/* Háttér: Lágy átmenet a púderrózsaszín és a levendula között */
.registration-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #fef1f2 0%, #fae8ff 100%);
  padding: 20px;
  font-family: 'Helvetica Neue', Arial, sans-serif;
}

/* A kártya: Fehér, enyhén áttetsző "glassmorphism" hatás */
.registration-card {
  background: rgba(255, 255, 255, 0.95);
  width: 100%;
  max-width: 450px;
  padding: 40px;
  border-radius: 24px;
  box-shadow: 0 20px 40px rgba(167, 139, 160, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.8);
}

/* Fejléc stílusa */
.form-header {
  text-align: center;
  margin-bottom: 30px;
}

.icon-wrapper {
  font-size: 2rem;
  margin-bottom: 10px;
}

h1 {
  color: #7d5a7a; /* Elegáns mályva lila */
  font-size: 1.8rem;
  font-weight: 300;
  margin-bottom: 8px;
  letter-spacing: 1px;
}

p {
  color: #a68ba5;
  font-size: 0.9rem;
  margin-bottom: 20px;
}

.decorative-line {
  height: 1px;
  width: 60px;
  background: linear-gradient(to right, transparent, #d4a5bc, transparent);
  margin: 0 auto;
}

/* Tipp a UserRegistration komponenshez: 
   Hogy tökéletes legyen az összhang, a gyerek komponensben 
   érdemes az inputokat #fdf2f8 fókusz-színnel és 
   a gombot #d4a5bc háttérszínnel ellátni.
*/
.custom-form :deep(input) {
  border-radius: 10px;
  border: 1px solid #e5d5e0;
  padding: 12px;
  transition: all 0.3s ease;
}

.custom-form :deep(input:focus) {
  border-color: #d4a5bc;
  box-shadow: 0 0 0 3px rgba(212, 165, 188, 0.2);
  outline: none;
}

.custom-form :deep(button) {
  background: linear-gradient(135deg, #d4a5bc 0%, #b392ac 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s;
  width: 100%;
  margin-top: 10px;
}

.custom-form :deep(button:hover) {
  transform: translateY(-1px);
  box-shadow: 0 5px 15px rgba(212, 165, 188, 0.4);
}
</style>