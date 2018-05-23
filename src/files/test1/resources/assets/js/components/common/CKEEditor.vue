<template>
    <textarea ref="editor"></textarea>
</template>
<script>

    /*import ClassicEditor from '@ckeditor/ckeditor5-editor-classic'*/

    export default {
        data() {
           return {
               editor: null
           }
        },
        props: {
           value: {
               type: String,
               default: ''
           }
        },
        watch: {
            value(v) {
                this.editor.setData(v)
            }
        },
        mounted() {
            ClassicEditor
                .create( this.$refs.editor )
                .then(e => {
                    this.editor = e
                    e.setData(this.value)
                    e.model.document.on( 'change', ( evt, data ) => {
                        this.$emit('change', e.getData())
                    } );
                })
        }
    }
</script>