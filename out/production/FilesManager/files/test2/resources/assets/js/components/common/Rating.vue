<template>
    <div style="display: flex;">
        <v-icon
                v-for="i in 5"
                :key="i"
                @click="() => { if (canEdit)  process(i) }"
                :color="i <= value ? 'yellow' : 'gray'"
                style="width: 30px; cursor: pointer;"
        >
            star_rate
        </v-icon>
    </div>
</template>

<script>
    export default {
        name: 'rating',
        props: {
            value: Number,
            canEdit: {
                type: Boolean,
                default: false
            },
            process: {
                type: Function,
                default: null
            }
        }
    }
</script>