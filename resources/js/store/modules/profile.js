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
      else if (getters.friendship.data.attributes.confirmed_at === null
        && getters.friendship.data.attributes.friend_id !== rootState.User.user.data.user_id) { // Make sure that the friend requests friend_id (the receiver of the request) is not the authUsers user_id
      return 'Pending Friend Request';
    } else if (getters.friendship.data.attributes.confirmed_at !== null) {
      return '';
    }

    return 'Accept';
  }
};

const actions = {
  fetchUser({commit, dispatch}, userId) {

    commit('setUserStatus', 'loading'); // Set user status to loading at beginning

    axios.get('/api/users/' + userId) // Get userId via route param from dispatch
      .then(res => {
        commit('setUser', res.data);
        commit('setUserStatus', 'success'); // If successful request change user status
      })
      .catch(err => {
        commit('setUserStatus', 'error'); // If failing request change user status to error
      });

  },

  sendFriendRequest({commit, state}, friendId) {

    axios.post('/api/friend-request', { 'friend_id': friendId })
      .then(res => {
        commit('setUserFriendship', res.data);
      })
      .catch(error => {
      });

  },

  acceptFriendRequest({commit, state}, userId) {

    axios.post('/api/friend-request-response', { 'user_id': userId, 'status': 1 })
      .then(res => {
        commit('setUserFriendship', res.data);
      })
      .catch(error => {
      });

  },

  ignoreFriendRequest({commit, state}, userId) {

    axios.delete('/api/friend-request-response/delete', { data: { 'user_id': userId } }) // Axios expects second parameter to be config file
      .then(res => {
        commit('setUserFriendship', null);
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
    state.user.data.attributes.friendship = friendship; // TODO - 37-39
  },
  setUserStatus(state, status) {
    state.userStatus = status;
  },
};

export default {
  state, getters, actions, mutations
}