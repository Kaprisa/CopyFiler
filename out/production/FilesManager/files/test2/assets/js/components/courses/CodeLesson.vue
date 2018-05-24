<template>
    <div class="code-lesson">
        <v-card class="code-lesson__learn tab">
            <v-toolbar card color="green lighten-3" dark dense>
                <v-toolbar-title>Learn</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <div class="md pa-4">
                <p data-source-line="1">Скоро здесь будут уроки.</p>
                <p data-source-line="3">Но пока можете поиграть с кодом:)</p>
            </div>
           </v-card>
        <v-card class="tab">
            <v-toolbar card color="blue lighten-3" dark dense>
                <v-toolbar-title>Code</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn v-for="f in files" :key="f.ext" @click="file = f" dark :color="f.color">{{ f.ext }}</v-btn>
            </v-toolbar>
            <v-code-mirror :file="file" @change="c => this.file.code = c"/>
        </v-card>
        <v-card class="code-lesson__browser tab">
            <v-toolbar card color="pink lighten-3" dark dense>
                <v-toolbar-title>Browser</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn fab small dark color="teal" @click="reload"><v-icon>refresh</v-icon></v-btn>
            </v-toolbar>
            <iframe ref="browser" style="height: 86vh;width: 100%;" frameborder="0"></iframe>
        </v-card>
    </div>
</template>

<script>
    import VCodeMirror from '../common/CodeMirror'
    export default {
        components: {
           VCodeMirror
        },
        data() {
            const files = [
                {
                    mode: 'text/html',
                    ext: 'html',
                    code: '<h1>hello</h1>',
                    color: 'blue'
                },
                {
                    mode: 'text/css',
                    ext: 'css',
                    code:
`body { color: #ffffff; }
h1 { font-size: 40px; }`,
                    color: 'red'
                },
                {
                    mode: 'text/javascript',
                    ext: 'js',
                    code: `alert('hello')`,
                    color: 'orange'
                }
            ]
            return {
               tab: '0',
               files,
               file: files[0]
            }
        },
        mounted() {
            this.document = this.$refs.browser.contentWindow.document
            this.document.body.innerHTML = this.file.code
        },
        methods: {
            command(c, b = null) {
                this.document.execCommand(c, false, b)
            },
            reload() {
                const c = this.file.code
                const head = this.document.head || this.document.getElementsByTagext('head')[0]
                if (!this.document.styleSheets.length) {
                    const style = this.document.createElement('style')
                    style.type = 'text/css'
                    head.appendChild(style);
                }
                switch (this.file.ext) {
                    case 'html':
                        this.document.body.innerHTML = c
                        break
                    case 'css':
                        const re = /([^{}]*)\s*{(.*)}/gi;
                        const arr = c.match(re)
                        arr.map(i => i.replace(/\s{2,}/,'').replace('}','').split(/{/)).forEach(i => this.addCSSRule(this.document.styleSheets[0], i[0], i[1]));
                        break
                    case 'js':
                        const script = this.document.createElement('script')
                        script.type = 'text/javascript'
                        script.text = c
                        //script.onerror = (e) => console.log('hello')
                        head.appendChild(script)
                        break
                    default:
                        console.log('hello')
                }

            },
            addCSSRule(sheet, selector, rules, index) {
                if('insertRule' in sheet)
                    sheet.insertRule(`${selector}{${rules}}`, index);
                else if('addRule' in sheet)
                    sheet.addRule(selector, rules, index);
            }
        }
    }
</script>

<style lang="sass">
    .code-lesson
        height: 90vh
        display: flex
        .tab
            flex: 1 1 0
            overflow-y: auto
        .CodeMirror.CodeMirror-wrap
            height: 86vh
</style>