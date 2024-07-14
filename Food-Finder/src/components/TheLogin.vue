<template>
  <div
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
  >
    <div class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md relative">
      <button
        @click="$emit('close')"
        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
      >
        &times;
      </button>
      <div class="flex justify-between mb-4">
        <button
          @click="activeTab = 'login'"
          :class="{ 'border-b-2 border-indigo-500': activeTab === 'login' }"
          class="px-4 py-2 focus:outline-none"
        >
          ورود
        </button>
        <button
          @click="activeTab = 'signup'"
          :class="{ 'border-b-2 border-indigo-500': activeTab === 'signup' }"
          class="px-4 py-2 focus:outline-none"
        >
          ثبت نام
        </button>
      </div>
      <div v-if="activeTab === 'login'">
        <h2 class="text-2xl font-bold mb-4">ورود</h2>
        <form @submit.prevent="loginUser">
          <div class="mb-4">
            <label
              for="loginEmail"
              class="block text-sm font-medium text-gray-700"
              >ایمیل</label
            >
            <input
              type="email"
              id="loginEmail"
              v-model="loginEmail"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="mb-4">
            <label
              for="loginPassword"
              class="block text-sm font-medium text-gray-700"
              >رمز عبور</label
            >
            <input
              type="password"
              id="loginPassword"
              v-model="loginPassword"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="flex justify-end">
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              ورود
            </button>
          </div>
        </form>
      </div>
      <div v-else-if="activeTab === 'signup'">
        <h2 class="text-2xl font-bold mb-4">ثبت نام</h2>
        <form @submit.prevent="registerUser">
          <div class="mb-4">
            <label
              for="signupName"
              class="block text-sm font-medium text-gray-700"
              >نام</label
            >
            <input
              type="text"
              id="signupName"
              v-model="signupName"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="mb-4">
            <label
              for="signupEmail"
              class="block text-sm font-medium text-gray-700"
              >ایمیل</label
            >
            <input
              type="email"
              id="signupEmail"
              v-model="signupEmail"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="mb-4">
            <label
              for="signupPassword"
              class="block text-sm font-medium text-gray-700"
              >رمز عبور</label
            >
            <input
              type="password"
              id="signupPassword"
              v-model="signupPassword"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="mb-4">
            <label
              for="confirmPassword"
              class="block text-sm font-medium text-gray-700"
              >تأیید رمز عبور</label
            >
            <input
              type="password"
              id="confirmPassword"
              v-model="confirmPassword"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="flex justify-end">
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              ثبت نام
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { mapActions } from 'vuex';
axios.defaults.withCredentials = true;
export default {
  data() {
    return {
      activeTab: 'login',
      loginEmail: '',
      loginPassword: '',
      signupName: '',
      signupEmail: '',
      signupPassword: '',
      confirmPassword: '',
    };
  },
  methods: {
    ...mapActions(['fetchLoginUser']),

    async loginUser() {
      try {
        const response = await axios.post(
          'http://localhost:80/FOOD-FINDER/api/loginUser.php',
          {
            email: this.loginEmail,
            password: this.loginPassword,
          },
          { withCredentials: true }
        );
        if (response.data.success) {
          this.fetchLoginUser();
          alert('ورود با موفقیت انجام شد');
          this.$emit('close');
        } else {
          alert('ورود ناموفق. لطفا دوباره تلاش کنید');
        }
      } catch (error) {
        console.error('خطا در ورود:', error);
        alert('ورود ناموفق. لطفا دوباره تلاش کنید');
      }
    },

    async registerUser() {
      if (this.signupPassword !== this.confirmPassword) {
        alert('رمز عبور و تأیید رمز عبور مطابقت ندارند');
        return;
      }
      try {
        const response = await axios.post(
          'http://localhost:80/FOOD-FINDER/api/registerUser.php',
          {
            name: this.signupName,
            email: this.signupEmail,
            password: this.signupPassword,
          }
        );
        if (response.data.success) {
          alert('ثبت نام با موفقیت انجام شد');
          this.$emit('close');
        } else {
          alert('خطایی رخ داد. لطفا دوباره تلاش کنید');
        }
      } catch (error) {
        console.error('خطا در ثبت نام:', error);
        alert('خطایی رخ داد. لطفا دوباره تلاش کنید');
      }
    },
  },
};
</script>

<style scoped>
.absolute {
  position: absolute;
}

.top-2 {
  top: 0.5rem;
}

.right-2 {
  right: 0.5rem;
}

.text-gray-500 {
  color: #6b7280;
}

.hover\:text-gray-700:hover {
  color: #374151;
}
</style>
