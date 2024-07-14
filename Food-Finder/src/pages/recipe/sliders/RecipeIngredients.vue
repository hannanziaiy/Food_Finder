<template>
  <div class="overflow-y-scroll h-[80vh] w-full" style="direction: rtl">
    <h2 class="text-3xl font-bold mb-4 text-gray-800">مواد اولیه</h2>
    <div class="relative mb-4">
      <input
        v-model="searchQuery"
        placeholder="جستجو..."
        @input="filterIngredients"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition duration-300 ease-in-out"
      />
    </div>
    <ul>
      <li
        v-for="ingredient in filteredIngredients"
        :key="ingredient.name"
        :class="{
          'bg-white': true,
          'hover:bg-gray-100': true,
          'bg-cyan-200': isSelectedIngredient(ingredient),
        }"
        @click="toggleIngredient(ingredient)"
        class="cursor-pointer py-2 px-4 mb-2 rounded-md transition duration-300 ease-in-out"
      >
        {{ ingredient.name }}
      </li>
    </ul>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

export default {
  data() {
    return {
      searchQuery: '',
      filteredIngredients: [],
    };
  },
  computed: {
    ...mapGetters(['allRecipes', 'selectedIngredients']),
    uniqueIngredients() {
      let ingredientsSet = new Set();
      let ingredientsArray = [];
      this.allRecipes.forEach((recipe) => {
        recipe.mavad.split(',').forEach((ingredient) => {
          let cleanedIngredient = this.extractNameValue(ingredient.trim());
          if (!ingredientsSet.has(cleanedIngredient.name)) {
            ingredientsSet.add(cleanedIngredient.name);
            ingredientsArray.push(cleanedIngredient);
          }
        });
      });
      return ingredientsArray;
    },
  },
  watch: {
    allRecipes: {
      handler() {
        this.filteredIngredients = this.uniqueIngredients;
      },
      immediate: true,
    },
  },
  created() {
    this.fetchRecipes();
    this.filteredIngredients = this.uniqueIngredients;
  },
  methods: {
    ...mapActions(['fetchRecipes']),
    ...mapMutations(['setSelectedIngredients']),
    extractNameValue(ingredient) {
      const name = ingredient.split(/\d/)[0].trim();
      const value = ingredient.replace(name, '').trim();
      return { name, value };
    },
    filterIngredients() {
      const query = this.searchQuery.toLowerCase();
      this.filteredIngredients = this.uniqueIngredients.filter((ingredient) =>
        ingredient.name.toLowerCase().includes(query)
      );
    },
    toggleIngredient(ingredient) {
      const index = this.selectedIngredients.findIndex(
        (selected) => selected.name === ingredient.name
      );
      if (index === -1) {
        this.selectedIngredients.push(ingredient);
      } else {
        this.selectedIngredients.splice(index, 1);
      }
      this.setSelectedIngredients(this.selectedIngredients);
    },
    isSelectedIngredient(ingredient) {
      return this.selectedIngredients.some(
        (selected) => selected.name === ingredient.name
      );
    },
  },
};
</script>

<style scoped>
ul li:hover {
  transform: translateY(-2px);
}
</style>
