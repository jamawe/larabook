const state = {
  user: null,
  userStatus: null,
};

const getters = {
  authUser: state => {
    return state.user;
  }
};

const actions = {
  fetchAuthUser({commit, state}) {
    axios.get('/api/auth-user')
      .then(res => {
        commit('setAuthUser', res.data); // which 'mutation', which data shall be used
      })
      .catch(err => {
        console.log('Unable to fetch auth user.');
      })
  }
};

const mutations = {
  setAuthUser(state, user) { // with user being the requested data
    state.user = user;
  }
};

export default {
  state, getters, actions, mutations
}