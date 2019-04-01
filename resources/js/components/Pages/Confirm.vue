<template>
    <div class="container home">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form class="form-signin" autocomplete="off" @submit.prevent="login" method="post">
                            <div class="form-label-group">
                                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" v-model="email">
                                <label for="inputEmail">Email address</label>
                                <span class="help-block" v-if="has_error && errors.email">{{ errors.email }}</span>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="Password" v-model="password">
                                <label for="inputPassword">Password</label>
                                <span class="help-block"
                                      v-if="has_error && errors.password">{{ errors.password }}</span>
                            </div>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Remember password</label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
                            <hr class="my-4">
                            <a class="btn btn-lg btn-danger btn-block text-uppercase" href="#/register">Sign Up</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>
