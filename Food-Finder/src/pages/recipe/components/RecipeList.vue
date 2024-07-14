<template>
  <div class="flex flex-col items-center mx-auto p-4 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">لیست دستورهای غذایی</h1>
    <input
      v-model="searchQuery"
      type="text"
      placeholder="جستجو..."
      class="mb-6 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 text-center"
    />
    <ul class="list-none p-0 space-y-2 w-full text-center">
      <li
        v-for="recipe in paginatedRecipes"
        :key="recipe.id"
        class="bg-white shadow-md rounded-md p-4 transition-transform transform hover:-translate-y-1 text-center"
      >
        <router-link
          :to="'/recipes/' + formatRecipeTitle(recipe.title)"
          class="text-gray-600 hover:text-gray-800 hover:underline"
        >
          {{ recipe.title }}
        </router-link>
      </li>
    </ul>
    <nav
      class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-6 w-full"
      aria-label="Pagination"
    >
      <div class="hidden sm:block">
        <p class="text-sm text-gray-700 text-center">
          نمایش
          <span class="font-medium">{{ startItemIndex }}</span>
          تا
          <span class="font-medium">{{ endItemIndex }}</span>
          از
          <span class="font-medium">{{ filteredRecipes.length }}</span>
          نتیجه
        </p>
      </div>
      <div class="flex flex-1 justify-between sm:justify-center items-center">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          صفحه قبل
        </button>
        <span class="mx-3 text-sm font-medium text-gray-700">{{
          currentPage
        }}</span>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          صفحه بعد
        </button>
      </div>
    </nav>
    <p class="mt-4 text-sm text-gray-700 text-center">
      تعداد کل نتایج: {{ filteredRecipes.length }}
    </p>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      currentPage: 1,
      itemsPerPage: 6,
      searchQuery: '',
    };
  },
  computed: {
    ...mapGetters(['allRecipes', 'selectedIngredients', 'exactMatch']),
    filteredRecipes() {
      let recipes = this.allRecipes;

      if (this.selectedIngredients.length > 0) {
        recipes = recipes.filter((recipe) => {
          const recipeIngredients = recipe.mavad.split(',');

          if (this.exactMatch) {
            const selectedIngredientsMap = new Map(
              this.selectedIngredients.map((ingredient) => [
                ingredient.name.toLowerCase(),
                parseFloat(ingredient.amount) || 0.5,
              ])
            );
            return recipeIngredients.every((ingredient) => {
              const ingredientName = ingredient
                .split(/\d/)[0]
                .trim()
                .toLowerCase();

              const newIngredient = ingredient.replace(/\//g, '.');
              const match = newIngredient.match(/\d+(\.\d+)?/);
              const number = match ? parseFloat(match[0]) : 0;

              const requiredAmount = selectedIngredientsMap.get(ingredientName);

              return requiredAmount !== undefined && number <= requiredAmount;
            });
          } else {
            return this.selectedIngredients.every((selectedIngredient) =>
              recipeIngredients.some((ingredient) =>
                new RegExp(selectedIngredient.name, 'i').test(ingredient)
              )
            );
          }
        });
      }

      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        recipes = recipes.filter((recipe) =>
          recipe.title.toLowerCase().includes(query)
        );
      }

      return recipes;
    },
    paginatedRecipes() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredRecipes.slice(start, end);
    },
    startItemIndex() {
      return (this.currentPage - 1) * this.itemsPerPage + 1;
    },
    endItemIndex() {
      return Math.min(
        this.currentPage * this.itemsPerPage,
        this.filteredRecipes.length
      );
    },
    totalPages() {
      return Math.ceil(this.filteredRecipes.length / this.itemsPerPage);
    },
  },
  methods: {
    formatRecipeTitle(title) {
      return title.replace(/\s+/g, '-').toLowerCase();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>
