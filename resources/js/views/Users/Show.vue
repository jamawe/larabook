<template>
  <div class="flex flex-col items-center">
    <div class="relative mb-8">
      <div class="w-100 h-64 overflow-hidden z-10">
        <img src="https://images.unsplash.com/photo-1624455375091-9746420d4809?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80" alt="User background image" class="object-cover w-full">
      </div>

      <div class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20">
        <div class="w-32">
          <img src="https://cdn.pixabay.com/photo/2014/07/09/10/04/man-388104_1280.jpg" alt="User profile image" class="w-32 h-32 object-cover border-4 border-gray-200 rounded-full shadow-lg">
        </div>

        <p class="text-2xl text-gray-100 ml-4">{{ user.data.attributes.name }}</p>
      </div>

      <div class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20">
        <button
          v-if="friendButtonText && friendButtonText !== 'Accept'" 
          class="py-1 px-3 bg-gray-200 rounded"
          @click="$store.dispatch('sendFriendRequest', $route.params.userId)">
            {{ friendButtonText }}
        </button>
        <button
          v-if="friendButtonText && friendButtonText === 'Accept'" 
          class="mr-2 py-1 px-3 bg-blue-500 rounded"
          @click="$store.dispatch('acceptFriendRequest', $route.params.userId)">
            Accept
        </button>
        <button
          v-if="friendButtonText && friendButtonText === 'Accept'" 
          class="py-1 px-3 bg-gray-200 rounded"
          @click="$store.dispatch('ignoreFriendRequest', $route.params.userId)">
            Ignore
        </button>
      </div>
    </div>

    <p v-if="postLoading">Loading posts...</p>

    <Post
      v-else
      v-for="post in posts.data"
      :key="post.data.post_id"
      :post="post"
    />

    <p v-if="!postLoading && posts.data.length < 1">This user has no posts yet. Get started...</p>
  </div>
</template>

<script>
  import Post from '../../components/Post.vue';
  import { mapGetters } from 'vuex';

  export default {
    name: 'Show',

    components: {
      Post,
    },
    
    data() {
      return {
        posts: null,
        postLoading: true,
      }
    },

    mounted() {
      this.$store.dispatch('fetchUser', this.$route.params.userId); // Get userId via route param

      axios.get('/api/users/' + this.$route.params.userId + '/posts')
      .then(res => {
        this.posts = res.data;
      })
      .catch(err => {
        console.log('Unable to fetch user\'s posts.');
      })
      .finally(() => {
        this.postLoading = false;
      });
    },

    computed: {
      ...mapGetters({
        user: 'user',
        friendButtonText: 'friendButtonText',
      })
    },

  }
</script>

<style scoped>

</style>