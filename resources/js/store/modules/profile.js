const state = {
  user: null,
  userStatus: null,
  friendButtonText: null,
};

const getters = {
  user: state => {
    return state.user;
  },
  friendship: state => {
    return state.user.data.attributes.friendship; 
  },
  friendButtonText: state => {
    return state.friendButtonText;
  }
};

const actions = {
  fetchUser({commit, dispatch}, userId) {

    commit('setUserStatus', 'loading'); // Set user status to loading at beginning

    axios.get('/api/users/' + userId) // Get userId via route param from dispatch
      .then(res => {
        commit('setUser', res.data);
        commit('setUserStatus', 'succes'); // If successful request change user status
        dispatch('setFriendButton');
      })
      .catch(err => {
        commit('setUserStatus', 'error'); // If failing request change user status to error
      });

  },

  sendFriendRequest({commit, state}, friendId) {

    commit('setButtonText', 'Loading');

    axios.post('/api/friend-request', { 'friend_id': friendId })
      .then(res => {
        commit('setButtonText', 'Pending Friend Request');
      })
      .catch(error => {
        commit('setButtonText', 'Add Friend');
      });

  },

  setFriendButton({commit, getters}) {
    // No friendship yet, so friend request can be sent
    if (getters.friendship === null) {
      commit('setButtonText', 'Add Friend');
    } // Friend request hasn't been accepted yet
      else if (getters.friendship.data.attributes.confirmed_at === null) {
      commit('setButtonText', 'Pending Friend Request');
    }
  }
};

const mutations = {
  setUser(state, user) { // with user being the requested data
    state.user = user;
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