<template>
  <div class="flex flex-col items-center py-4">

    <NewPost />

    <p v-if="newsStatus.newsPostsStatus === 'loading'">Loading posts...</p>

    <Post
      v-else
      v-for="post in posts.data"
      :key="post.data.post_id"
      :post="post"
    />

  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import NewPost from '../components/NewPost.vue';
  import Post from '../components/Post.vue';


  export default {
    name: 'NewsFeed',

    components: {
      NewPost,
      Post
    },

    mounted() {
      this.$store.dispatch('fetchNewsPosts', )
    },

    computed: {
      ...mapGetters({
        posts: 'newsPosts',
        newsStatus: 'newsStatus'
      })
    }
  }
</script>

<style>

</style>