<template>
  <div class="login-page">
    <div class="login-box">
      <div class="content-wrapper">
        <header class="form-header">
          <div class="brand-icon">✨</div>
          <h1>Üdvözöljük</h1>
          <p>Kérjük, jelentkezzen be!</p>
        </header>

        <UserLogin @logIn="loginHandler" class="styled-form" />
        
        <div v-if="error" class="error-toast">
          {{ error }}
        </div>

        <div class="footer">
          <p>Még nincs fiókja? <router-link to="/registration">Regisztráció</router-link></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserLogin from "@/components/User/UserLogin.vue";
import { mapActions, mapState } from "pinia";
import { useUserLoginLogoutStore } from "@/stores/userLoginLogoutStore";

export default {
  name: "LoginView",
  components: { UserLogin },
  computed: { ...mapState(useUserLoginLogoutStore, ["error"]) },
  methods: {
    ...mapActions(useUserLoginLogoutStore, ["login"]),
    async loginHandler(user) {
      try {
        await this.login(user);
        this.$router.push("/");
      } catch (error) {
        console.log("Hiba történt a bejelentkezéskor.");
      }
    },
  },
};
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f9fa;
  padding: 20px;
}

.login-box {
  background: #ffffff;
  width: 100%;
  max-width: 420px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid #eee;
}

.content-wrapper { padding: 40px; }

.form-header { text-align: center; margin-bottom: 30px; }
.brand-icon { font-size: 2rem; margin-bottom: 10px; }
h1 { color: #3d343d; font-size: 1.6rem; font-weight: 600; margin: 0; }
p { color: #8e8e8e; font-size: 0.9rem; }

/* --- KRITIKUS JAVÍTÁSOK A KÉK GOMB ELLEN --- */

/* 1. Minden olyan gombot eltüntetünk, ami NEM a submit (LOGIN) gomb */
.styled-form :deep(button:not([type="submit"])),
.styled-form :deep(.btn-primary:not([type="submit"])),
.styled-form :deep(button[style*="background-color: rgb(0, 123, 255)"]), /* Kék szín alapján is */
.styled-form :deep(.btn:not([type="submit"])) {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  height: 0 !important;
  padding: 0 !important;
  margin: 0 !important;
}

/* 2. Fejléc sáv */
.styled-form :deep(.card-header), 
.styled-form :deep(.bg-primary) { 
  background: #7d5a7a !important; 
  color: white !important;
  border-radius: 8px 8px 0 0;
  padding: 12px;
  text-align: center;
  border: none !important;
}

/* 3. Inputok */
.styled-form :deep(input) {
  background-color: #fcfafb !important;
  border: 1px solid #e2d5de !important;
  border-radius: 8px !important;
  padding: 12px !important;
}

/* 4. A LOGIN gomb kinyújtása */
.styled-form :deep(button[type="submit"]) {
  background-color: #3d343d !important;
  color: white !important;
  border-radius: 8px !important;
  width: 100% !important; /* Teljes szélesség */
  padding: 14px !important;
  border: none !important;
  display: block !important;
  margin: 20px 0 0 0 !important;
}

/* Egyéb elemek */
.error-toast { background: #fff5f5; color: #c53030; padding: 12px; border-radius: 8px; margin-top: 20px; text-align: center; }
.footer { margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; font-size: 0.9rem; }
.footer a { color: #7d5a7a; font-weight: 600; text-decoration: none; }
</style>