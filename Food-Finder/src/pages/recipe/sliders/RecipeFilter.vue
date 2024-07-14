<template>
  <div style="direction: rtl">
    <h2 class="text-3xl font-bold mb-4 text-gray-800">فیلتر مواد اولیه</h2>
    <ul class="space-y-3">
      <li
        v-for="ingredient in selectedIngredients"
        :key="ingredient.id"
        class="flex items-center justify-between bg-white rounded-lg shadow-md hover:shadow-xl p-4 transition duration-300 ease-in-out"
      >
        <span class="text-xl text-gray-900 flex-1 font-semibold">
          {{ ingredient.name }}
        </span>
        <input
          type="text"
          class="w-40 px-3 py-2 border border-gray-300 rounded-md text-base focus:outline-none focus:border-blue-500 transition duration-300 ease-in-out"
          :placeholder="ingredient.value"
          v-model="ingredient.amount"
        />
        <button
          type="button"
          @click="toggleIngredient(ingredient)"
          class="ml-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm focus:outline-none shadow-md transition duration-300 ease-in-out"
        >
          حذف
        </button>
      </li>
    </ul>

    <div class="mt-6">
      <input
        type="checkbox"
        id="onlyUnitsCheckbox"
        class="form-checkbox h-5 w-5 text-indigo-600"
        v-model="exactMatch"
        @change="applyFilter"
      />
      <label for="onlyUnitsCheckbox" class="ml-2 text-sm text-gray-800"
        >فقط مواد دارای واحد</label
      >
      <p class="mt-2 text-gray-700 text-sm">
        مواد انتخاب شده: {{ selectedIngredients.length }}
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex';

export default {
  computed: {
    ...mapGetters(['selectedIngredients']),
  },
  methods: {
    ...mapActions(['fetchRecipes']),
    ...mapMutations(['setExactMatch', 'setSelectedIngredients']),
    toggleIngredient(ingredient) {
      const index = this.selectedIngredients.findIndex(
        (sel) => sel.name === ingredient.name
      );
      if (index === -1) {
        this.setSelectedIngredients([...this.selectedIngredients, ingredient]);
      } else {
        const updatedIngredients = [...this.selectedIngredients];
        updatedIngredients.splice(index, 1);
        this.setSelectedIngredients(updatedIngredients);
      }
    },
    applyFilter() {
      this.setExactMatch(this.exactMatch);
      this.fetchRecipes();
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
body {
  font-family: 'Roboto', sans-serif;
}

ul li:hover {
  transform: translateY(-4px);
}

div {
  direction: rtl;
}
.text-xl {
  font-size: 1rem;
}

::placeholder {
  font-size: 0.9rem;
  opacity: 0.8;
}
</style>
