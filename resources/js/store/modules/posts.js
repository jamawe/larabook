const state = {
  posts: null,
  postsStatus: null,
  postMessage: '',
};

const getters = {
  posts: state => {
    return state.posts;
  },

  newsStatus: state => {
    return {
      postsStatus: state.postsStatus,
    }
  },

  postMessage: state => {
    return state.postMessage;
  },
};

const actions = {
  fetchNewsPosts({commit, state}) {

    commit('setPostsStatus', 'loading');

    axios.get('/api/posts')
      .then(res => {
        commit('setPosts', res.data);
        commit('setPostsStatus', 'success');
      })
      .catch(err => {
        commit('setPostsStatus', 'error');
      });

  },

  fetchUserPosts({commit, dispatch}, userId) {

    commit('setPostsStatus', 'loading'); // Set user status to loading at beginning

    axios.get('/api/users/' + userId + '/posts')
    .then(res => {
      commit('setPosts', res.data);
      commit('setPostsStatus', 'success');
    })
    .catch(err => {
      commit('setPostsStatus', 'error');
    });

  },

  postMessage({commit, state}) {

    commit('setPostsStatus', 'loading');

    axios.post('/api/posts', { body: state.postMessage })
      .then(res => {
        commit('pushPost', res.data);
        commit('setPostsStatus', 'success');
        commit('updateMessage', '');
      })
      .catch(err => {
      });

  },

  likePost({commit, state}, data) {
    axios.post('/api/posts/'+data.postId+'/like')
    .then(res => {
      commit('pushLikes', { likes: res.data, postKey: data.postKey });
      // commit('setPostsStatus', 'success');
    })
    .catch(err => {
    });

  },

  commentPost({commit, state}, data) {
    axios.post('/api/posts/'+data.postId+'/comment', { body: data.body })
    .then(res => {
      commit('pushComments', { comments: res.data, postKey: data.postKey });
    })
    .catch(err => {
    });

  }
};

const mutations = {
  setPosts(state, posts) {
    state.posts = posts;
  },

  setPostsStatus(state, status) {
    state.postsStatus = status;
  },

  updateMessage(state, message) {
    state.postMessage = message;
  },

  pushPost(state, post) {
    state.posts.data.unshift(post); // Adding the new post to the top
  },

  pushLikes(state, data) {
    // Replace the old likes object of a post (located with postKey) with the new object of likes data.likes (which is the response from the server)
    state.posts.data[data.postKey].data.attributes.likes = data.likes;
  },

  pushComments(state, data) {
    state.posts.data[data.postKey].data.attributes.comments = data.comments;
  }
};

export default {
  state, getters, actions, mutations
}