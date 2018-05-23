<template>
    <div>
        <v-btn @click="open">Редактор</v-btn>
        <textarea ref="textarea"></textarea>
    </div>
</template>
<script>
    import Stackedit from 'stackedit-js';
    const stackedit = new Stackedit();
    export default {
        data() {
            return {
             el: null
            }
        },
        mounted() {
            this.el = this.$refs.textarea
            stackedit.on('fileChange', (file) => {
                this.el.value = file.content.text;
            });
        },
        methods: {
            open() {
                stackedit.openFile({
                    name: '/editor/index.md', // with a filename
                    content: {
                        text: this.$refs.textarea.value // and the Markdown content.
                    }
                });
            }
        }
    }
</script>