<template>
  <div class="mx-auto w-full max-w-7xl grow lg:flex xl:px-2">
    <div class="flex-1 xl:flex xl:border-b xl:border-gray-200">
      <div class="xl:w-80 xl:pl-6 xl:pr-4 xl:py-6 sm:px-6 lg:pl-8">
        <RecipeIngredients></RecipeIngredients>
      </div>

      <div class="flex-1 px-4 py-6 sm:px-6 lg:pl-8 xl:pl-6">
        <RecipeList></RecipeList>
      </div>
    </div>

    <div
      class="hidden lg:block lg:w-96 lg:border-l lg:border-t-0 lg:border-gray-200 lg:px-4 lg:py-6 xl:pr-6"
    >
      <RecipeFilter></RecipeFilter>
    </div>
  </div>
</template>

<script>
import RecipeList from './components/RecipeList.vue';
import RecipeFilter from './sliders/RecipeFilter.vue';
import RecipeIngredients from './sliders/RecipeIngredients.vue';

export default {
  components: {
    RecipeList,
    RecipeFilter,
    RecipeIngredients,
  },
};
</script>
