<template>
    <textarea ref="editor"></textarea>
</template>
<script>

    import tinymce from 'tinymce'

    import 'tinymce/themes/inlite'
    import 'tinymce/themes/modern'
    import 'tinymce/plugins/paste'
    import 'tinymce/plugins/link'
    import 'tinymce/plugins/autoresize'
    import 'tinymce/plugins/table'
    import 'tinymce/plugins/wordcount'

    export default {
        data() {
            return {

            }
        },
        mounted() {
            tinymce.init({
                target: this.$refs.editor,
                plugins: 'paste link autoresize table wordcount',
            });
        }
    }
</script>