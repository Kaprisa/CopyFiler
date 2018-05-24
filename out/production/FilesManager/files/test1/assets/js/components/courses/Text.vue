<template>
    <div v-html="props.text" style="height: 90%; overflow-y: auto;">
    </div>
</template>

<script>
    export default {
        name: 'v-text',
        props: {
            props: Object
        }
    }
</script>