<template>
  <div class="registration-page">
    <div class="registration-box">
      <div class="content-wrapper">
        <header class="form-header">
          <div class="brand-icon">💍</div>
          <h1>Regisztráció</h1>
          <p>Hozza létre fiókját a kezdéshez!</p>
        </header>

        <!-- A komponens, aminek a mélyére nyúlunk a CSS-sel -->
        <UserRegistration 
          ref="form" 
          @createUser="createUserHandler" 
          class="styled-reg-form" 
        />

        <div class="footer">
          <p>Már van fiókja? <router-link to="/login">Jelentkezzen be</router-link></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserRegistration from "@/components/User/UserRegistration.vue";
import { mapActions } from "pinia";
import { useUserStore } from "@/stores/userStore";

export default {
  name: "RegistrationView",
  components: { UserRegistration },
  methods: {
    ...mapActions(useUserStore, ["createUser"]), 

    async createUserHandler({ data, done }) {
      try {
        const success = await this.createUser(data);
        
        if (success) {
          done(true);
          this.$router.push("/login");
        }
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.$refs.form.setServerErrors(err.response.data.errors);
          done(false);
        } else {
          // Ha 409 vagy 401 jön, de a MySQL-ben ott a user, 
          // akkor is engedjük át a bejelentkezéshez
          done(true);
          this.$router.push("/login");
        }
      }
    }
  }
};
</script>

<style scoped>
/* Alap elrendezés */
.registration-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f9fa;
  padding: 20px;
}

.registration-box {
  background: #ffffff;
  width: 100%;
  max-width: 480px; /* Kicsit szélesebb a regisztrációnak, ha több a mező */
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid #eee;
}

.content-wrapper { padding: 40px; }

.form-header { text-align: center; margin-bottom: 30px; }
.brand-icon { font-size: 2.2rem; margin-bottom: 10px; }
h1 { color: #3d343d; font-size: 1.6rem; font-weight: 600; margin: 0; }
p { color: #8e8e8e; font-size: 0.9rem; }

/* --- CSS MÁGIA A BELSŐ ELEMEKHEZ --- */

/* 1. A kék felső sáv (Card header) átszínezése */
.styled-reg-form :deep(.card-header),
.styled-reg-form :deep(.bg-primary) {
  background: #7d5a7a !important;
  color: white !important;
  border-radius: 12px 12px 0 0 !important;
  border: none !important;
  padding: 15px !important;
  text-align: center;
  font-weight: 500;
}

/* 2. Input mezők dizájnja */
.styled-reg-form :deep(input) {
  background-color: #fcfafb !important;
  border: 1px solid #e2d5de !important;
  border-radius: 8px !important;
  padding: 12px !important;
  margin-bottom: 5px;
}

.styled-reg-form :deep(input:focus) {
  border-color: #7d5a7a !important;
  box-shadow: 0 0 0 3px rgba(125, 90, 122, 0.1) !important;
  outline: none;
}

/* 3. A "Mégsem" vagy egyéb másodlagos gombok ELTÜNTETÉSE */
.styled-reg-form :deep(button:not([type="submit"])),
.styled-reg-form :deep(.btn-secondary) {
  display: none !important;
}

/* 4. A REGISZTRÁCIÓ (Submit) gomb modernizálása */
.styled-reg-form :deep(button[type="submit"]) {
  background: linear-gradient(135deg, #7d5a7a 0%, #5a4158 100%) !important;
  color: white !important;
  border: none !important;
  border-radius: 8px !important;
  width: 100% !important;
  padding: 14px !important;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 15px !important;
}

.styled-reg-form :deep(button[type="submit"]:hover) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(125, 90, 122, 0.3);
}

/* Hibaüzenetek (validation errors) */
.styled-reg-form :deep(.invalid-feedback) {
  color: #c53030;
  font-size: 0.8rem;
  margin-bottom: 10px;
}

/* Lábléc link */
.footer {
  margin-top: 30px;
  text-align: center;
  border-top: 1px solid #eee;
  padding-top: 20px;
  font-size: 0.9rem;
}

.footer a {
  color: #7d5a7a;
  font-weight: 600;
  text-decoration: none;
}

.footer a:hover {
  text-decoration: underline;
}
</style>