<template>

</template>

<script>
    export default {
        created(){
            this.$store.dispatch('destroyLogOutToken');
        }
    }
</script>
