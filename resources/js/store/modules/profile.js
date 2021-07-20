const state = {
  user: null,
  userStatus: null,
};

const getters = {
  user: state => {
    return state.user;
  },
  friendship: state => {
    return state.user.data.attributes.friendship; 
  },
  friendButtonText: (state, getters, rootState) => { // rootState: state of other vuex stores
    // No friendship yet, so friend request can be sent
    if (getters.friendship === null) {
      return 'Add Friend';
    } // Friend request hasn't been accepted yet
      else if (getters.friendship.data.attributes.confirmed_at === null) {
      return 'Pending Friend Request';
    }
  }
};

const actions = {
  fetchUser({commit, dispatch}, userId) {

    commit('setUserStatus', 'loading'); // Set user status to loading at beginning

    axios.get('/api/users/' + userId) // Get userId via route param from dispatch
      .then(res => {
        commit('setUser', res.data);
        commit('setUserStatus', 'succes'); // If successful request change user status
      })
      .catch(err => {
        commit('setUserStatus', 'error'); // If failing request change user status to error
      });

  },

  sendFriendRequest({commit, state}, friendId) {

    commit('setButtonText', 'Loading');

    axios.post('/api/friend-request', { 'friend_id': friendId })
      .then(res => {
        commit('setUserFriendship', res.data);
      })
      .catch(error => {
      });

  },

};

const mutations = {
  setUser(state, user) { // with user being the requested data
    state.user = user;
  },
  setUserFriendship(state, friendship) { // with user being the requested data
    state.user.data.attributes.friendship = friendship;
  },
  setUserStatus(state, status) {
    state.userStatus = status;
  },
  setButtonText(state, text) {
    state.friendButtonText = text;
  }
};

export default {
  state, getters, actions, mutations
}