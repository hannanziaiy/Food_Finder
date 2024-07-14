<template>
  <div class="container mx-auto p-4">
    <h1 class="text-4xl font-bold mb-8 text-center text-purple-800">
      {{ recipe.title }}
    </h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div
        class="bg-white rounded-lg shadow-md p-6 text-center w-full md:w-auto"
      >
        <h2 class="text-2xl font-semibold mb-4 text-purple-800">مواد اولیه</h2>
        <ul class="pl-6 text-lg leading-relaxed text-left">
          <li
            v-for="ingredient in parsedIngredients"
            :key="ingredient"
            class="bg-white shadow-md rounded-md p-1 transition-transform transform hover:-translate-y-1 text-center"
          >
            {{ ingredient }}
          </li>
        </ul>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 md:flex-1">
        <h2 class="text-2xl font-semibold mb-4 text-purple-800 text-right">
          دستور پخت
        </h2>
        <p class="text-lg leading-relaxed text-right">
          {{ recipe.instruction }}
        </p>
        <div class="flex flex-col md:flex-row md:space-x-4 mt-4">
          <div class="mb-2 md:mb-0 flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mr-2 text-purple-800"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 1a9 9 0 110 18 9 9 0 010-18zm0 2a7 7 0 100 14 7 7 0 000-14zm0 4a1 1 0 00-1 1v3a1 1 0 002 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
            <span class="font-semibold">زمان آماده‌سازی:</span>
            {{ recipe.prepare_time }}
          </div>
          <div class="mb-2 md:mb-0 flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mr-2 text-purple-800"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 1a9 9 0 110 18 9 9 0 010-18zm0 2a7 7 0 100 14 7 7 0 000-14zm0 4a1 1 0 00-1 1v3a1 1 0 002 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
            <span class="font-semibold">زمان پخت:</span>
            {{ recipe.cook_time }}
          </div>
          <div class="mb-2 md:mb-0 flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mr-2 text-purple-800"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 1a9 9 0 110 18 9 9 0 010-18zm0 2a7 7 0 100 14 7 7 0 000-14zm0 4a1 1 0 00-1 1v3a1 1 0 002 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
            <span class="font-semibold">مناسب برای:</span>
            {{ recipe.nafar }}
          </div>
        </div>
      </div>
    </div>
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <div class="mb-4">
        <span class="font-semibold text-purple-800">دسته‌بندی:</span>
        {{ recipe.cat }}
      </div>
      <div>
        <span class="font-semibold text-purple-800">زیر دسته‌بندی:</span>
        {{ recipe.sub_cat }}
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  props: ['title'],
  computed: {
    ...mapGetters(['allRecipes']),
    recipe() {
      return this.allRecipes.find(
        (recipe) => this.formatRecipeTitle(recipe.title) === this.title
      );
    },
    parsedIngredients() {
      if (!this.recipe) return [];
      return this.recipe.mavad
        .split(',')
        .map((ingredient) => ingredient.trim());
    },
  },
  methods: {
    ...mapActions(['fetchRecipes']),
    formatRecipeTitle(title) {
      return title.replace(/ /g, '-');
    },
  },
  created() {
    if (!this.recipe) {
      this.fetchRecipes();
    }
  },
};
</script>
