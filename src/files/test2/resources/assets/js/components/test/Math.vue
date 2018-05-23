<template>
    <div id="editor">
        <textarea v-model="input"></textarea>
        <div v-html="output" id="editor-output"></div>
    </div>
</template>
<script>
    //import MathJax from 'mathjax/MathJax.js'
    function debounce(f, ms) {

        let timer = null;

        return function (...args) {
            const onComplete = () => {
                f.apply(this, args);
                timer = null;
            }

            if (timer) {
                clearTimeout(timer);
            }

            timer = setTimeout(onComplete, ms);
        };
    }
    export default {
        data() {
            return {
                input: '',
                output: ''
            }
        },
        created: function () {
            this.input = '{\\sqrt{2}^\\sqrt{2}}^\\sqrt{2} = 2 \\\\ \\sqrt{2}^{2\\log_{2}{3}} = 3'
        },
        watch: {
            input: debounce(function (e) {
                this.output = '\\(' + this.input + '\\)'
                this.$nextTick(function () {
                    MathJax.Hub.Queue(["Typeset", MathJax.Hub, "editor-output"])
                })
            }, 300)
        },
        methods: {

        }
    }
</script>