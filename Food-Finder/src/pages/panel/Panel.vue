<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div
      class="admin-panel max-w-4xl mx-auto p-8 bg-white shadow-lg rounded-lg"
    >
      <h1 class="text-3xl font-bold mb-8 text-center">پنل مدیریت</h1>
      <div class="mb-4">
        <label class="block text-gray-700 text-right">عنوان غذا:</label>
        <input
          v-model="recipeTitle"
          type="text"
          placeholder="عنوان غذا را وارد کنید"
          class="input text-right"
        />
        <span v-if="errors.recipeTitle" class="error">{{
          errors.recipeTitle
        }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right"
          >زمان آماده شدن غذا:</label
        >
        <input
          v-model="prepareTime"
          type="text"
          placeholder="دقیقه"
          class="input text-right"
        />
        <span v-if="errors.prepareTime" class="error">{{
          errors.prepareTime
        }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right">زمان پختن غذا:</label>
        <input
          v-model="cookTime"
          type="text"
          placeholder="دقیقه"
          class="input text-right"
        />
        <span v-if="errors.cookTime" class="error">{{ errors.cookTime }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right"
          >غذا برای این تعداد:</label
        >
        <input
          v-model="nafar"
          type="text"
          placeholder="نفر"
          class="input text-right"
        />
        <span v-if="errors.nafar" class="error">{{ errors.nafar }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right">دسته‌بندی:</label>
        <select v-model="selectedCategory" class="input text-right">
          <option
            v-for="(category, index) in catAndSub[0]"
            :key="index"
            :value="category"
          >
            {{ category }}
          </option>
        </select>
        <span v-if="errors.selectedCategory" class="error">{{
          errors.selectedCategory
        }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right"
          >زیرمجموعه دسته‌بندی:</label
        >
        <select v-model="selectedSubCategory" class="input text-right">
          <option
            v-for="(subCategory, index) in catAndSub[1]"
            :key="index"
            :value="subCategory"
          >
            {{ subCategory }}
          </option>
        </select>
        <span v-if="errors.selectedSubCategory" class="error">{{
          errors.selectedSubCategory
        }}</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right">مواد اولیه:</label>
        <input
          v-model="ingredient"
          type="text"
          placeholder="ماده اولیه را وارد کنید"
          class="input text-right"
        />
        <button
          @click="addIngredient"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 float-right"
        >
          اضافه کردن
        </button>
        <span v-if="errors.ingredients" class="error">{{
          errors.ingredients
        }}</span>
      </div>
      <div class="mb-4">
        <h3 class="text-lg font-bold mb-2 text-right">لیست مواد اولیه:</h3>
        <p class="text-right">{{ ingredients }}</p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-right">طریقه پخت:</label>
        <textarea
          v-model="recipeMethod"
          placeholder="طریقه پخت را وارد کنید"
          class="textarea text-right"
        ></textarea>
        <span v-if="errors.recipeMethod" class="error">{{
          errors.recipeMethod
        }}</span>
      </div>
      <div class="flex space-x-4 justify-center">
        <button
          @click="submitForm"
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        >
          ثبت اطلاعات
        </button>
        <button
          @click="editRecipe"
          class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
        >
          ویرایش دستور پخت
        </button>
        <button
          @click="deleteRecipe"
          class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        >
          حذف دستور پخت
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import axios from 'axios';

export default {
  data() {
    return {
      recipeTitle: '',
      prepareTime: '',
      cookTime: '',
      nafar: '',
      ingredient: '',
      ingredients: '',
      recipeMethod: '',
      selectedCategory: '',
      selectedSubCategory: '',
      errors: {},
    };
  },
  computed: {
    ...mapGetters(['allRecipes']),
    catAndSub() {
      const categories = this.allRecipes.map((recipe) => recipe.cat);
      const subCategories = this.allRecipes.map((recipe) => recipe.sub_cat);
      return [
        Array.from(new Set(categories)),
        Array.from(new Set(subCategories)),
      ];
    },
  },
  methods: {
    ...mapActions(['fetchRecipes']),
    addIngredient() {
      if (this.ingredient.trim()) {
        if (this.ingredients) {
          this.ingredients += ', ' + this.ingredient.trim();
        } else {
          this.ingredients = this.ingredient.trim();
        }
        this.ingredient = '';
      }
    },

    validateForm(isEdit = false) {
      this.errors = {};
      if (!this.recipeTitle) {
        this.errors.recipeTitle = 'عنوان غذا اجباری است';
      } else if (
        !isEdit &&
        this.allRecipes.some((recipe) => recipe.title === this.recipeTitle)
      ) {
        this.errors.recipeTitle = 'عنوان غذا تکراری است';
      } else if (
        isEdit &&
        !this.allRecipes.some((recipe) => recipe.title === this.recipeTitle)
      ) {
        this.errors.recipeTitle = 'عنوان غذا وجود ندارد';
      }
      if (!this.selectedCategory) {
        this.errors.selectedCategory = 'دسته‌بندی اجباری است';
      }
      if (!this.selectedSubCategory) {
        this.errors.selectedSubCategory = 'زیرمجموعه دسته‌بندی اجباری است';
      }
      if (!this.ingredients) {
        this.errors.ingredients = 'مواد اولیه اجباری است';
      }
      if (!this.recipeMethod) {
        this.errors.recipeMethod = 'طریقه پخت اجباری است';
      }
    },
    async submitForm() {
      this.validateForm();
      if (Object.keys(this.errors).length === 0) {
        const data = {
          action: 'submit',
          title: this.recipeTitle,
          prepareTime: this.prepareTime || 'نامشخص',
          cookTime: this.cookTime || 'نامشخص',
          nafar: this.nafar || 'نامشخص',
          category: this.selectedCategory,
          subCategory: this.selectedSubCategory,
          ingredients: this.ingredients,
          method: this.recipeMethod,
        };
        try {
          const response = await axios.post(
            'http://localhost:80/FOOD-FINDER/api/panel.php',
            data
          );
          console.log(response.data);
          alert('اطلاعات با موفقیت ثبت شد');
          this.fetchRecipes();
        } catch (error) {
          console.error(error);
          alert('خطا در ثبت اطلاعات');
        }
      }
    },
    async editRecipe() {
      this.validateForm(true);
      if (Object.keys(this.errors).length === 0) {
        const recipe = this.allRecipes.find(
          (recipe) => recipe.title === this.recipeTitle
        );
        if (recipe) {
          console.log('Recipe ID:', recipe.id);
        }
        const data = {
          action: 'edit',
          id: recipe ? recipe.id : null,
          title: this.recipeTitle,
          prepareTime: this.prepareTime || 'نامشخص',
          cookTime: this.cookTime || 'نامشخص',
          nafar: this.nafar || 'نامشخص',
          category: this.selectedCategory,
          subCategory: this.selectedSubCategory,
          ingredients: this.ingredients,
          method: this.recipeMethod,
        };
        try {
          const response = await axios.post(
            'http://localhost:80/FOOD-FINDER/api/panel.php',
            data
          );
          console.log(response.data);
          alert('دستور پخت با موفقیت ویرایش شد');
          this.fetchRecipes();
        } catch (error) {
          console.error(error);
          alert('خطا در ویرایش دستور پخت');
        }
      }
    },
    async deleteRecipe() {
      this.errors = {};
      if (!this.recipeTitle) {
        this.errors.recipeTitle = 'عنوان غذا اجباری است';
      } else if (
        !this.allRecipes.some((recipe) => recipe.title === this.recipeTitle)
      ) {
        this.errors.recipeTitle = 'عنوان غذا وجود ندارد';
      } else {
        const recipe = this.allRecipes.find(
          (recipe) => recipe.title === this.recipeTitle
        );
        if (recipe) {
          console.log('Recipe ID for Deletion:', recipe.id);
        }
        const data = {
          action: 'delete',
          id: recipe ? recipe.id : null,
        };
        try {
          const response = await axios.post(
            'http://localhost:80/FOOD-FINDER/api/panel.php',
            data
          );
          console.log(response.data);
          alert('دستور پخت با موفقیت حذف شد');
          this.fetchRecipes();
        } catch (error) {
          console.error(error);
          alert('خطا در حذف دستور پخت');
        }
      }
    },
  },
  created() {
    this.fetchRecipes();
  },
};
</script>

<style scoped>
.admin-panel {
  padding: 20px;
}
.admin-panel div {
  margin-bottom: 20px;
}
.input {
  width: 100%;
  padding: 8px;
  margin-top: 8px;
  margin-bottom: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  text-align: right;
}
.textarea {
  width: 100%;
  height: 100px;
  padding: 8px;
  margin-top: 8px;
  margin-bottom: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  text-align: right;
}
.error {
  color: red;
  font-size: 0.875em;
}
</style>
