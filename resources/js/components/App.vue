<template>

  <div
    v-if="authUser"
    class="flex flex-col flex-1 h-screen overflow-y-hidden">
    
    <Nav />

    <div class="flex flex-1 overflow-y-hidden">

      <Sidebar />

      <div class="overflow-x-hidden w-2/3">
        <router-view :key="$route.fullPath"></router-view>
      </div>
    </div>

  </div>

</template>

<script>
  import Nav from './Nav.vue';
  import Sidebar from './Sidebar.vue';

  import { mapGetters } from 'vuex';

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

    computed: {
      ...mapGetters({
        authUser: 'authUser',
      })
    },

    watch: {
      $route(to, from) {
        this.$store.dispatch('setPageTitle', to.meta.title); // call action in title.js
        console.log('Route changed from ' + from.path + ' to ' + to.path);
      }
    }

  }

</script>

<style scoped>

</style>