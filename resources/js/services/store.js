import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);
axios.defaults.baseURL = 'http://localhost:8000/api'
const store = new Vuex.Store({
    state: {
        token: localStorage.getItem('access_token') || null
    },
    mutations: {
        retrieveToken(state, token) {
            state.token = token
        },
        destroyToken(state) {
            state.token = null
        }
    },
    getters: {
        loggedIn(state) {
            return state.token != null
        }
    },
    actions: {
        destroyLogOutToken(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios.post('/logout').then(response => {
                        localStorage.removeItem('access_token')
                        context.commit('destroyToken')
                        this.$router.push({name: '/'});
                        resolve(response)
                    }).catch(error => {
                        localStorage.removeItem('access_token')
                        context.commit('destroyToken')
                        this.$router.push({name: '/'});
                        reject(error);
                    })
                })
            }
        },
        register(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('/register', {
                    first_name: data.data.first_name,
                    last_name: data.data.last_name,
                    email: data.data.email,
                    password: data.data.password,
                    password_confirmation: data.data.confirm_password
                }).then(response => {
                    const token = response.data.access_token
                    localStorage.setItem('access_token', token)
                    context.commit('retrieveToken', token)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        login(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/login', {
                    email: credentials.data.email,
                    password: credentials.data.password
                }).then(response => {
                    const token = response.data.access_token
                    localStorage.setItem('access_token', token)
                    context.commit('retrieveToken', token)
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        sendFriendRequest(context, data) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            return new Promise((resolve, reject) => {
                axios.post('/invite', {
                    email: data.invite_email
                }).then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        loadFirendsList(context, url) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            return new Promise((resolve, reject) => {
                axios.get(url).then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        loadFriendsRequestList(context, url) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            return new Promise((resolve, reject) => {
                axios.get('/friend/request').then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        deleteFriend(context, id) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            return new Promise((resolve, reject) => {
                axios.post('/friend/delete', {
                    id: id
                }).then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        acceptRequest(context, data) {
            console.log(data.friend_id)
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
            return new Promise((resolve, reject) => {
                axios.post('/friend/accept', {
                    id: data.id, friend_id: data.friend_id
                }).then(response => {
                    resolve(response)
                }).catch(error => {
                    reject(error);
                })
            })
        }
    }
});

export default store