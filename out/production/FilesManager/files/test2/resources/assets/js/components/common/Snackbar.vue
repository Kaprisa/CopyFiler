<template>
    <v-snackbar
            v-model="options.visible"
            top
            absolute
            :color="options.error ? 'error' : 'success'"
    >
        <span>{{ options.text }}</span>
        <v-icon dark>{{ options.error ? 'error' : 'check_circle'}}</v-icon>
    </v-snackbar>
</template>

<script>
    export default {
        name: 'snackbar',
        props: {
            options: Object
        }
    }
</script>