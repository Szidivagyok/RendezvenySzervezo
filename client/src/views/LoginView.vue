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
      
      <div class="footer-links">
        <div class="divider"></div>
        <span>Már van fiókja? <router-link to="/login">Jelentkezzen be</router-link></span>
      </div>
    </div>
  </div>
</template>

<script>
// ... (a script rész változatlan marad, ahogy kérted)
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
.registration-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #fdfbfb 0%, #f3e8ff 100%);
  padding: 20px;
}

.registration-card {
  background: #ffffff;
  width: 100%;
  max-width: 450px;
  padding: 40px;
  border-radius: 30px;
  box-shadow: 0 15px 35px rgba(125, 90, 122, 0.1);
  border: 1px solid #f3e8ff;
}

.form-header {
  text-align: center;
  margin-bottom: 30px;
}

.icon-wrapper {
  font-size: 2rem;
  margin-bottom: 10px;
}

h1 {
  color: #5a4a58;
  font-size: 1.8rem;
  font-weight: 500;
  margin-bottom: 8px;
}

p {
  color: #9e8fa2;
  font-size: 0.9rem;
}

.decorative-line {
  height: 1px;
  width: 60px;
  background: linear-gradient(to right, transparent, #d4a5bc, transparent);
  margin: 15px auto;
}

/* A gomb és az inputok egységesítése a Login stílusával */
.custom-form :deep(input) {
  background-color: #faf7f9;
  border: 1px solid #efe1eb;
  border-radius: 12px;
  padding: 12px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
}

.custom-form :deep(input:focus) {
  background-color: #fff;
  border-color: #d4a5bc;
  outline: none;
  box-shadow: 0 0 8px rgba(212, 165, 188, 0.2);
}

/* Ez a rész lett átírva a kért sötétebb lilára */
.custom-form :deep(button) {
  background: #7d5a7a; /* Ugyanaz a lila, mint a login gombnál */
  color: white;
  border: none;
  border-radius: 15px;
  padding: 16px;
  width: 100%;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(125, 90, 122, 0.2);
  transition: all 0.3s ease;
  margin-top: 20px;
}

.custom-form :deep(button:hover) {
  background: #6a4c68; /* Sötétebb árnyalat hover esetén */
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(125, 90, 122, 0.3);
}

.footer-links {
  text-align: center;
  margin-top: 25px;
  font-size: 0.85rem;
  color: #9e8fa2;
}

.divider {
  height: 1px;
  background: #f0e6ed;
  margin-bottom: 20px;
}

a {
  color: #d4a5bc;
  text-decoration: none;
  font-weight: 600;
}
</style>