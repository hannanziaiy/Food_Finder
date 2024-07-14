// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import ShowRecipe from './pages/recipe/ShowRecipe.vue';
import Panel from './pages/panel/Panel.vue';
import RecipeDetail from './pages/recipe/components/RecipeDetail.vue';
import store from './store';

const routes = [
  { path: '/', redirect: '/recipes' },
  { path: '/recipes', component: ShowRecipe },
  { path: '/panel', component: Panel, meta: { requiresAdmin: true } },
  { path: '/recipes/:title', component: RecipeDetail, props: true },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAdmin)) {
    if (!store.getters.getLoginUser) {
      try {
        await store.dispatch('fetchLoginUser');
      } catch (error) {
        console.error('Error fetching login user:', error);
      }
    }

    const isAdmin =
      store.getters.getLoginUser && store.getters.getLoginUser.isAdmin === 1;
    if (!isAdmin) {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
