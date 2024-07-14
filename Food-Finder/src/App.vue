<template class="container mx-auto">
  <the-header></the-header>
  <router-view></router-view>
</template>

<script>
import TheHeader from './components/TheHeader.vue';

export default {
  name: 'App',
  components: {
    TheHeader,
  },
};
</script>
