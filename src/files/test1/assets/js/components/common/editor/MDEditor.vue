<template>
    <v-dialog width="80vw" persistent v-model="dialog" :fullscreen="fullscreen">
        <v-btn slot="activator" color="lime" dark>{{ buttonText }}</v-btn>
        <v-card style="width: 100%;">
            <ul style="display: flex; justify-content: center;">
                <li v-for="group, i in actions" :key="i" style="display: flex; justify-content: center;">
                    <!--class="d-flex row align-center"-->
                        <span
                                v-for="k, v in group"
                                :key="v"
                                @click="(e) => action(e, v)"
                        >
                            <button class="editor_btn">
                                <v-icon :size="15" v-if="k.substring(0, 2) === 'fa'">{{ k }}</v-icon>
                                <span v-else>{{ k }}</span>
                            </button>
                        </span>
                    <span v-if="i < actions.length - 1" class="editor_devider"></span>
                </li>
            </ul>
            <v-layout style="height: 80vh; position: relative;">
                <v-flex :class="showCode && showPreview ? 'xs6' : 'xs12'" v-if="showCode" style="overflow-y: auto">
                    <codemirror ref="codemirror" v-model="code" :options="opt"></codemirror>
                </v-flex>
                <v-flex :class="showCode && showPreview ? 'xs6' : 'xs12'" style="padding: 5px; overflow-y: auto;" v-if="showPreview">
                    <div class="md pl-3" v-html="result" style="overflow-y: auto"></div>
                </v-flex>
            </v-layout>
            <v-divider/>
            <v-card-actions>
                <v-spacer/>
                <v-btn color="lime" dark @click="change()">Ок</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>
<script>
    import md from '../../../markdown'
    import {codemirror} from 'vue-codemirror-lite'
    import 'codemirror/mode/markdown/markdown.js'
    import 'codemirror/addon/selection/active-line.js'
    //import 'codemirror/theme/mdn-like.css'
    import 'codemirror/theme/paraiso-light.css'

    //const d3 = require('d3')
    //import d3node from 'd3-node'

    import actions from './actions'

    export default {
        components: {
            codemirror
        },
        watch: {
            code(val) {
                this.result = md.render(val)

                // md.mermaid.init(undefined, document.querySelectorAll('.mermaid'))
                // document.querySelectorAll('.chartjs').forEach(element => {
                //     try {
                //         new Chart(element, JSON.parse(element.textContent))
                //     } catch (e) {
                //         element.outerHTML = `<pre>Chart.js complains: "${e}"</pre>`
                //     }
                // })
                //{ d3 }
            },
            value(val) {
                if (val) {
                    this.code = val
                }
            }
        },
        props: {
            buttonText: String,
            value: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                dialog: false,
                fullscreen: false,
                preview: true,
                result: '',
                ol: 1,
                prev_c: null,
                code: '',
                actions,
                editor: null,
                showCode: true,
                showPreview: true,
                count: actions.reduce((c, i) => c += Object.keys(i).length, 0),
                opt: {
                    mode: 'text/x-markdown',
                    styleActiveLine: true,
                    tabSize: 2,
                    lineWrapping: true,
                    theme: 'paraiso-light',
                    matchBrackets: true,
                    viewportMargin: Infinity,
                    highlightFormatting: true
                }
            }
        },
        mounted() {
            this.editor = this.$refs.codemirror.editor
        },
        methods: {
            change() {
                this.$emit('change', this.code)
                this.dialog = false
            },
            action(e, v) {
                e.preventDefault()
                if (this.prev_c === 'list-ol') {
                    this.ol++
                } else {
                    this.ol = 1
                    this.prev_c = v
                }
                switch (v) {
                    case 'undo':
                        this.editor.execCommand('undo')
                        break
                    case 'redo':
                        this.editor.execCommand('redo')
                        break
                    case 'bold':
                        this.wrap('**')
                        break
                    case 'del':
                        this.wrap('~~')
                        break
                    case 'italic':
                        this.wrap('*')
                        break
                    case 'quote':
                        this.insert_start('>')
                        break
                    case 'ucwords':
                        console.log('ucwords')
                        break
                    case 'uppercase':
                        console.log('uppercase')
                        break
                    case 'lowercase':
                        console.log('lowercase')
                        break
                    case 'h1':
                        this.insert_start('#')
                        break
                    case 'h2':
                        this.insert_start('##')
                        break
                    case 'h3':
                        this.insert_start('###')
                        break
                    case 'h4':
                        this.insert_start('####')
                        break
                    case 'h5':
                        this.insert_start('#####')
                        break
                    case 'h6':
                        this.insert_start('######')
                        break
                    case 'list-ul':
                        this.insert_start('-')
                        break
                    case 'list-ol':
                        this.insert_start(`${this.ol}.`)
                        break
                    case 'hr':
                        break
                    case 'link':
                        console.log('link')
                        break
                    case 'image':
                        console.log('image')
                        break
                    case 'code':
                        this.wrap('`')
                        break
                    case 'table':
                        console.log('table')
                        break
                    case 'datetime':
                        console.log('datetime')
                        break
                    case 'emoji':
                        console.log('emoji')
                        break
                    case 'pagebreak':
                        console.log('pagebreak')
                        break
                    case 'formula':
                        this.wrap('$$')
                        break
                    case 'watch':
                        this.showCode = !this.showCode
                        break
                    case 'preview':
                        this.showPreview = !this.showPreview
                        break
                    case 'fullscreen':
                        this.fullscreen = !this.fullscreen
                        break
                    case 'clear':
                        //this.editor.clear()
                        break
                    case 'search':
                        console.log('search')
                        break
                    case 'help':
                        console.log('help')
                        break
                    case 'info':
                        console.log('info')
                        break
                    default:
                        console.log('a')
                }
            },
            wrap(w) {
                const c = this.editor.getCursor()
                this.code += w + w
                this.editor.focus()
                setTimeout(() => {
                    this.editor.setCursor({line: c.line, ch: c.ch + w.length})
                }, 10)
            },
            insert_start(w, next = true) {
                let c = this.editor.getCursor()
                this.editor.focus()
                //this.code = this.code.substr(0, )
                setTimeout(() => {
                    this.editor.setCursor({line: next ? c.line : c.line + 1, ch: 0})
                    // this.editor.execCommand('newLineAndIndent')
                    // this.editor.execCommand('goLineDown')
                    this.editor.replaceSelection(w + ' ')
                    this.editor.execCommand('goLineEnd')
                }, 10)
            }
        }
    }
</script>

<style lang="sass">
    .editor
        &_btn
            outline: none
            border: none
            text-align: center
            padding: 3px
            margin: 2px
            //flex: 1 1 0
            width: 30px
            height: 30px
            color: rgba(0, 0, 0, .54)
            font-weight: bold
            transition: (background-color .2s linear, border .2s linear)
            &:hover
                background-color: #eee
                border: 1px solid #333
        &_devider
            width: 1px
            max-width: 1px
            flex-basis: 1px
            height: 36px
            margin: 3px
            background-color: rgba(0, 0, 0, .3)
            &:last-child
                margin-right: 0
    .CodeMirror.CodeMirror-wrap
        height: 80vh
        overflow-y: auto
</style>