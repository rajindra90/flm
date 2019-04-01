<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign Up</h5>
                        <form class="form-signin" autocomplete="off" @submit.prevent="register" v-if="!success"
                              method="post">
                            <div class="form-label-group">
                                <input type="text" id="firstName" class="form-control" placeholder="First Name"
                                       v-model="firstName">
                                <label for="firstName">First Name</label>
                                <span class="help-block"
                                      v-if="has_error && errors.first_name">{{ errors.first_name }}</span>
                            </div>

                            <div class="form-label-group">
                                <input type="text" id="lastName" class="form-control" placeholder="Last Name"
                                       v-model="lastName">
                                <label for="lastName">Last Name</label>
                                <span class="help-block"
                                      v-if="has_error && errors.last_name">{{ errors.last_name }}</span>
                            </div>

                            <div class="form-label-group">
                                <input type="email" id="inputEmail" class="form-control" placeholder="Email address"
                                       v-model="email">
                                <label for="inputEmail">Email address</label>
                                <span class="help-block" v-if="has_error && errors.email">{{ errors.email }}</span>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                                       v-model="password">
                                <label for="inputPassword">Password</label>
                                <span class="help-block"
                                      v-if="has_error && errors.password">{{ errors.password }}</span>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="confirmPassword" class="form-control"
                                       placeholder="Confirm Password" v-model="confirmPassword">
                                <label for="confirmPassword">Confirm Password</label>
                                <span class="help-block" v-if="has_error && errors.confirm_password">{{ errors.confirm_password }}</span>
                            </div>

                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign Up
                            </button>
                            <hr class="my-4">
                            <a class="btn btn-lg btn-danger btn-block text-uppercase" href="#/">Sign In</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'register',
        data() {
            return {
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                confirmPassword: '',
                has_error: false,
                error: '',
                errors: {},
                success: false
            }
        },
        methods: {
            register() {
                this.$store.dispatch('register', {
                    data: {
                        first_name: this.firstName,
                        last_name: this.lastName,
                        email: this.email,
                        password: this.password,
                        confirm_password: this.confirmPassword
                    }
                }).then(response => {
                    this.success = true
                }).catch(error => {
                    this.has_error = true;
                    this.error = error.response.data.error
                    this.errors = error.response.data.errors || {}
                })
            }
        }
    }

</script>
