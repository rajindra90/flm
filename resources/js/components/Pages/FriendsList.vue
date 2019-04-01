<template>

    <div class="container">
        <div class="row">

            <!--<span><router-link :to="{name : 'logout'}">Logout</router-link></span>-->
            <div class="navbar navbar-expand-lg navbar-light bg-light">
                <h2>Friend List Manager</h2>

                <table class="float-right">
                    <tr>
                        <!--<td>
                            <form class="form-inline my-2 my-lg-3">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search"
                                       aria-label="Search">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </td>-->
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inviteToFrnds">
                                Invite Friends
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#requestList">
                                Request List
                            </button>
                        </td>

                        <td>
                            <a type="button" class="btn btn-danger">
                                <span><router-link :to="{name : 'logout'}">Logout</router-link></span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <table class="table table-striped" id="table_id">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="friend in friendList" v-bind:key="friend.id">
                    <td>{{friend.first_name}}</td>
                    <td>{{friend.last_name}}</td>
                    <td>{{friend.email}}</td>
                    <td v-if="friend.is_accepted"><span class="badge badge-success">Friend</span></td>
                    <td v-if="!friend.is_accepted"><span class="badge badge-warning">Sent Request</span></td>
                    <td>
                        <button type="button" class="btn btn-danger" @click="deleteFriend(friend.id)">Remove</button>
                    </td>
                </tr>

                </tbody>
            </table>
            <ul class="pagination">
                <li v-bind:class="[{disabled : !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#/friendslist" @click="loadFirendsList(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled"><a class="page-link text-dark" href="#/friendslist">Page
                    {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li v-bind:class="[{disabled : !pagination.next_page_url}]" class="page-item"><a class="page-link"
                                                                                                 href="#/friendslist"
                                                                                                 @click="loadFirendsList(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </div>


        <div class="modal fade" id="inviteToFrnds">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card card-signin my-1">
                            <div class="card-body">
                                <h5 class="card-title text-center">Invite To Your Friend</h5>
                                <div class="alert alert-danger" v-if="has_error && !success">
                                    <p v-if="has_error">{{ error_msg }}</p>
                                </div>
                                <div class="alert alert-success" v-if="!has_error && success">
                                    <p v-if="success">{{ success_msg }}</p>
                                </div>
                                <form class="form-signin" autocomplete="off" @submit.prevent="sendInvite" method="post">
                                    <div class="form-label-group">
                                        <input type="email" id="inputEmail" class="form-control"
                                               placeholder="Email address" v-model="inviteEmail">
                                        <label for="inputEmail">Email address</label>
                                    </div>

                                    <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Send
                                        Request
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="requestList">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body card-signin">
                                <h3 class="card-title text-left">Friend Requests</h3>
                                <table class="table table-striped">
                                    <tr v-for="req in requestList" v-bind:key="req.id">
                                        <td>{{req.first_name}}</td>
                                        <td>{{req.last_name}}</td>
                                        <td>{{req.email}}</td>
                                        <td>
                                            <button type="button" class="btn btn-info"
                                                    @click="acceptRequest(req.id,req.user_id)">
                                                Accept
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        data() {
            return {
                inviteEmail: '',
                error_msg: '',
                success_msg: '',
                has_error: false,
                success: false,
                friendList: [],
                requestList: [],
                friendRequestList: [],
                friend: {
                    id: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    is_accepted: '',
                }, friendRequest: {
                    id: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    is_accepted: '',
                },
                friend_id: '',
                pagination: {}
            }
        },
        created() {
            this.loadFirendsList();
            this.loadFriendsRequestList();
        },
        methods: {
            sendInvite() {
                this.$store.dispatch('sendFriendRequest', {
                    invite_email: this.inviteEmail
                }).then(response => {
                    this.success = true
                    this.has_error = true
                    this.success_msg = 'Friend request has been sent'
                    this.inviteEmail = ''
                }).catch(error => {
                    this.has_error = true
                    this.success = false
                    this.error_msg = error.response.data.message
                })
            },
            loadFirendsList(page_url) {
                page_url = page_url || '/invite'
                this.$store.dispatch('loadFirendsList', page_url).then(response => {
                    this.friendList = response.data.data
                    this.makePagination(response.data.meta, response.data.links)
                })
            },
            loadFriendsRequestList() {
                this.$store.dispatch('loadFriendsRequestList').then(response => {
                    this.requestList = response.data.data.data
                })
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                }

                this.pagination = pagination
            },
            deleteFriend(id) {
                this.$store.dispatch('deleteFriend', id).then(response => {
                    this.loadFirendsList();
                }).catch(error => {
                })
            },
            acceptRequest(id, friend_id) {
                this.$store.dispatch('acceptRequest', {id: id, friend_id: friend_id}).then(response => {
                    this.loadFriendsRequestList();
                    this.loadFirendsList();
                }).catch(error => {
                })
            }
        }
    }

</script>
