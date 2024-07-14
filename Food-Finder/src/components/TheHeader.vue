<template>
  <header class="bg-purple-900 py-4 shadow-md">
    <nav class="container mx-auto flex justify-between items-center px-4">
      <h3 class="text-gray-400 text-2xl font-bold">
        <router-link to="/" class="hover:text-pink-300"
          >Find a Recipe</router-link
        >
      </h3>

      <h1 class="text-orange-200 text-3xl font-bold text-center">
        <router-link to="/" class="hover:text-pink-300"
          >Food Finder</router-link
        >
      </h1>
      <ul class="flex items-center space-x-4 text-orange-200">
        <li v-if="getIsAdmin">
          <h2 class="text-2xl font-bold">
            <router-link to="/panel" class="hover:text-pink-300"
              >رفتن به پنل
            </router-link>
          </h2>
        </li>
        <li v-if="getLoginUser">
          <pre class="text-white">{{ getLoginUser.name }} خوش آمدید</pre>
        </li>
        <li>
          <button
            @click="getLoginUser ? logout() : (showLoginModal = true)"
            class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-400 transition duration-300"
          >
            {{ getLoginUser ? 'خروج از حساب کاربری' : 'ورود' }}
          </button>
        </li>
      </ul>
    </nav>
    <TheLogin v-if="showLoginModal" @close="showLoginModal = false" />
  </header>
</template>

<script>
import TheLogin from './TheLogin.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
  components: {
    TheLogin,
  },
  data() {
    return {
      showLoginModal: false,
    };
  },
  computed: {
    ...mapGetters(['getLoginUser']),
    ...mapGetters(['getIsAdmin']),
  },
  mounted() {
    this.fetchLoginUser();
  },
  methods: {
    ...mapActions(['fetchLoginUser']),
    ...mapActions(['logoutUser']),

    async logout() {
      await this.logoutUser();

      this.fetchLoginUser();
      window.location.reload();
    },
  },
};
</script>
