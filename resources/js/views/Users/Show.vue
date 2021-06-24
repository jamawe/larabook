<template>
  <div>
    <div class="w-100 h-64 overflow-hidden">
      <img src="https://images.unsplash.com/photo-1624455375091-9746420d4809?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80" alt="User background image" class="object-cover w-full">
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Show',
    
    data() {
      return {
        user: null,
        loading: true,
      }
    },

    mounted() {
      axios.get('/api/users/' + this.$route.params.userId)
        .then(res => {
          this.user = res.data;
        })
        .catch(err => {
          console.log('Unable to fetch user from server.');
        })
        .finally(() => {
          this.loading = false;
        });

        axios.get('/api/posts/' + this.$route.params.userId)
        .then(res => {
          this.posts = res.data;
          this.loading = false;
        })
        .catch(err => {
          console.log('Unable to fetch user\'s posts.');
          this.loading = false;
        });
    }

  }
</script>

<style scoped>

</style>