const state = {
  newsPosts: null,
  newsPostsStatus: null,
  postMessage: '',
};

const getters = {
  newsPosts: state => {
    return state.newsPosts;
  },

  newsStatus: state => {
    return {
      newsPostsStatus: state.newsPostsStatus,
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

  }
};

const mutations = {
  setPosts(state, posts) {
    state.newsPosts = posts;
  },

  setPostsStatus(state, status) {
    state.newsPostsStatus = status;
  },

  updateMessage(state, message) {
    state.postMessage = message;
  },

  pushPost(state, post) {
    state.newsPosts.data.unshift(post); // Adding the new post to the top
  },

  pushLikes(state, data) {
    // Replace the old likes object of a post (located with postKey) with the new object of likes data.likes (which is the response from the server)
    state.newsPosts.data[data.postKey].data.attributes.likes = data.likes;
  }
};

export default {
  state, getters, actions, mutations
}