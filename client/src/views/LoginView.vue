<template>
  <div class="login-page">
    <div class="login-box">
      <div class="content-wrapper">
        <header class="form-header">
          <div class="brand-icon">✨</div>
          <h1>Üdvözöljük</h1>
          <p>Kérjük, jelentkezzen be!</p>
        </header>

        <!-- A form, aminek a belső kék részeit most átszinezzük -->
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
  overflow: hidden;
}

.content-wrapper { padding: 40px; }

.form-header { text-align: center; margin-bottom: 30px; }
.brand-icon { font-size: 2rem; margin-bottom: 10px; }
h1 { color: #3d343d; font-size: 1.6rem; font-weight: 600; margin: 0; }
p { color: #8e8e8e; font-size: 0.9rem; }

/* --- A KÉK RÉSZEK ÁTDIZÁJNOLÁSA (DEEP) --- */

/* 1. A kék fejléc sáv (Login vagy regisztráció) */
.styled-form :deep(.card-header), 
.styled-form :deep(div[style*="background-color: blue"]), /* Ha inline stílus lenne */
.styled-form :deep(.bg-primary) { 
  background: #7d5a7a !important; /* Mályva lila */
  color: white !important;
  border-radius: 8px 8px 0 0;
  padding: 12px;
  font-weight: 500;
  text-align: center;
  border: none !important;
}

/* 2. Az inputok (hogy ne legyen az a világoskék háttér) */
.styled-form :deep(input) {
  background-color: #fcfafb !important;
  border: 1px solid #e2d5de !important;
  border-radius: 8px !important;
  padding: 12px !important;
}

.styled-form :deep(input:focus) {
  border-color: #7d5a7a !important;
  box-shadow: 0 0 0 3px rgba(125, 90, 122, 0.1) !important;
}

/* 3. A kék Regisztráció gomb az alján */
.styled-form :deep(.btn-primary),
.styled-form :deep(button:not([type="submit"])) {
  background-color: transparent !important;
  color: #7d5a7a !important;
  border: 1px solid #7d5a7a !important;
  border-radius: 8px !important;
  transition: all 0.3s ease;
  font-weight: 600;
}

.styled-form :deep(button:not([type="submit"])):hover {
  background-color: #f4eff2 !important;
  transform: translateY(-1px);
}

/* 4. A sötét LOGIN gomb (finomítás) */
.styled-form :deep(button[type="submit"]) {
  background-color: #3d343d !important;
  border-radius: 8px !important;
  border: none !important;
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 14px !important;
}

.styled-form :deep(button[type="submit"]):hover {
  background-color: #524652 !important;
}

/* --- EGYÉB ELEMEK --- */
.error-toast { background: #fff5f5; color: #c53030; padding: 12px; border-radius: 8px; margin-top: 20px; font-size: 0.85rem; border: 1px solid #fed7d7; text-align: center; }
.footer { margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; font-size: 0.9rem; }
.footer a { color: #7d5a7a; font-weight: 600; text-decoration: none; }
</style>