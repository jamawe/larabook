<template>

  <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
    
    <Nav />

    <div class="flex flex-1 overflow-y-hidden">

        <Sidebar />

      <div class="overflow-x-hidden w-2/3">
        <router-view></router-view>
      </div>
    </div>

  </div>

</template>

<script>
  import Nav from './Nav.vue';
  import Sidebar     from './Sidebar.vue';

  export default {
    name: 'App',

    components: {
      Nav,
      Sidebar
    },

    created() {
      this.$store.dispatch('setPageTitle', this.$route.meta.title);
      // since to/from not available on inital page load get meta.title from route object
    },

    mounted() {
      this.$store.dispatch('fetchAuthUser'); // call the action in user.js
    },

    watch: {
      $route(to, from) {
        this.$store.dispatch('setPageTitle', to.meta.title); // call action in title.js
      }
    }
  }

</script>

<style scoped>

</style>