<template>

</template>

<script>
    export default {
        created(){
            this.$store.dispatch('destroyLogOutToken');
            if (!$store.getters.loggedIn) {
                router.push({path: '/login'})
            }
        }
    }
</script>
