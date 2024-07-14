import { createStore } from 'vuex';
import axios from 'axios';
axios.defaults.withCredentials = true;

const store = createStore({
  state() {
    return {
      recipes: [],
      ingredients: [],
      selectedIngredients: [],
      exactMatch: false,
      isAdmin: 0,
      loginUser: null,
    };
  },
  getters: {
    allRecipes(state) {
      return state.recipes;
    },
    allIngredients(state) {
      return state.ingredients;
    },
    selectedIngredients(state) {
      return state.selectedIngredients;
    },
    exactMatch(state) {
      return state.exactMatch;
    },
    allUsers(state) {
      return state.users;
    },
    getLoginUser(state) {
      return state.loginUser;
    },
    getIsAdmin(state) {
      return state.isAdmin;
    },
  },
  mutations: {
    setRecipes(state, recipes) {
      state.recipes = recipes;
    },
    setIngredients(state, ingredients) {
      state.ingredients = ingredients;
    },
    setSelectedIngredients(state, ingredients) {
      state.selectedIngredients = ingredients;
    },
    setExactMatch(state, exactMatch) {
      state.exactMatch = exactMatch;
    },
    setLoginUser(state, user) {
      state.loginUser = user;
    },
    setIsAdmin(state) {
      state.isAdmin = state.loginUser.isAdmin;
    },
  },
  actions: {
    async fetchRecipes({ commit }) {
      try {
        const response = await axios.get(
          'http://localhost:80/FOOD-FINDER/api/getData.php',
          { params: { requestType: 'allRecipes' } }
        );
        commit('setRecipes', response.data);
      } catch (error) {
        console.error('خطا در دریافت دستورات غذایی:', error);
      }
    },
    async fetchIngredients({ commit }) {
      try {
        const response = await axios.get(
          'http://localhost:80/FOOD-FINDER/api/getData.php',
          { params: { requestType: 'ingredients' } }
        );
        commit('setIngredients', response.data);
      } catch (error) {
        console.error('خطا در دریافت مواد اولیه:', error);
      }
    },
    async fetchSelectedIngredients({ commit }, selectedIngredients) {
      try {
        const response = await axios.get(
          'http://localhost:80/FOOD-FINDER/api/getData.php',
          {
            params: { requestType: 'selectedIngredients', selectedIngredients },
          }
        );
        commit('setSelectedIngredients', response.data);
      } catch (error) {
        console.error('خطا در دریافت مواد اولیه انتخابی:', error);
      }
    },

    async fetchLoginUser({ commit }) {
      try {
        const response = await axios.get(
          'http://localhost:80/FOOD-FINDER/api/getUserInfo.php',
          {
            params: { requestType: 'allUsers' },
          },
          { withCredentials: true }
        );

        commit('setLoginUser', response.data);
        commit('setIsAdmin');
      } catch (error) {
        console.error('خطا در دریافت کاربران:', error);
      }
    },

    async logoutUser({ commit }) {
      try {
        await axios.post('http://localhost:80/FOOD-FINDER/api/logout.php');
        commit('setLoginUser', null);
      } catch (error) {
        console.error('Error sending data:', error);
      }
    },
  },
});

export default store;
