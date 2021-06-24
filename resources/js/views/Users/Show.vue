<template>
  <div>
    
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
    }

  }
</script>

<style scoped>

</style>