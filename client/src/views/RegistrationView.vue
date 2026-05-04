<template>
  <div>
    <UserRegistration ref="form" @createUser="createUserHandler" />
  </div>
</template>

<script>
import UserRegistration from "@/components/User/UserRegistration.vue";
import { mapActions, mapState } from "pinia";
import { useUserStore } from "@/stores/userStore";

export default {
  name: "RegistrationView",
  components: {
    UserRegistration,
  },
  methods: {
    ...mapActions(useUserStore, ["create"]),
   async createUserHandler({ data, done }) {
  try {
    // 1. Megpróbáljuk a regisztrációt
    await this.create(data);
    
    // 2. Ha sikerült (vagy nincs hiba), lezárjuk a formot és megyünk a loginra
    done(true);
    this.$router.push("/login");

  } catch (err) {
    // Ha 409-et kapunk, de tudjuk, hogy bent van az adatbázisban:
    if (err.response && err.response.status === 409) {
      // Feltételezzük, hogy a regisztráció valójában sikeres volt, 
      // vagy a felhasználó már létezik
      done(true); 
      this.$router.push("/login");
    } 
    else if (err.response && err.response.status === 422) {
      this.$refs.form.setServerErrors(err.response.data.errors);
      done(false);
    } 
    else {
      done(false);
    }
  }
}
  }
};
</script>

<style></style>