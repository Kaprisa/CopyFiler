<template>
    <v-container>
        <v-card height="80vh">
            <v-toolbar card color="amber">
                <v-breadcrumbs>
                    <v-icon slot="divider">forward</v-icon>
                    <v-breadcrumbs-item
                            v-for="item, i in path"
                            :key="item.text"
                            style="cursor: pointer;"
                    >
                        <v-icon color="primary" @click="back(item)" v-if="i === 0">home</v-icon>
                        <span v-else @click="back(item)">{{ item.text }}</span>
                    </v-breadcrumbs-item>
                </v-breadcrumbs>
                <!--<v-spacer/>-->
                <!--<v-tooltip top>-->
                        <!--<v-icon size="30px" dark color="primary" slot="activator" class="mr-2">create_new_folder</v-icon>-->
                    <!--<span>Новая папка</span>-->
                <!--</v-tooltip>-->
                <!--<v-tooltip top>-->
                        <!--<v-icon size="30px" dark color="primary" slot="activator">note_add</v-icon>-->
                    <!--<span>Новый файл</span>-->
                <!--</v-tooltip>-->
            </v-toolbar>
            <v-layout row wrap justify-start class="py-3">
                <div
                        v-for="k, v in items"
                        :key="v"
                        v-if="v !== '..' && v !== '.'"
                        style="flex-basis: 170px;"
                >
                    <div
                            class="layout column align-center"
                            v-if="k.name"
                            @dblclick="read(k)"
                            @click="fileInfo(k)"
                            style="cursor: pointer;"
                            @contextmenu="e => show(e, k)"
                    >
                        <v-avatar size="50px" >
                            <img :src="image_extensions.includes(k.ext) ? k.path : `/svg/files/${Object.keys(extensions).includes(k.ext) || k.ext === 'mp4' ? k.ext : 'text'}.svg`">
                        </v-avatar>
                        <span class="body-1">{{ k.name }}</span>
                    </div>
                    <div
                            class="layout column align-center"
                            v-else-if="typeof v === 'string'"
                            @dblclick="path.push({ text: v, items: k}); items = k;"
                            @click="dirInfo(v)"
                            style="cursor: pointer;"
                    >
                        <v-icon size="50px" fab color="teal accent-3">folder</v-icon>
                        <span class="body-1">{{ v }}</span>
                    </div>
                </div>
            </v-layout>
        </v-card>
        <v-dialog v-model="dialog" width="80vw">
            <v-card :class="file.code ? '' : 'text-xs-center'">
                <v-card-title>
                    <span class="heading">
                        {{ file.name }}
                    </span>
                    <v-spacer/>
                    <v-icon @click="dialog = false" style="cursor: pointer;">close</v-icon>
                </v-card-title>
                <v-code-mirror v-if="file.code" :file="file"></v-code-mirror>
                <img v-else-if="image_extensions.includes(file.ext)" style="width: auto; max-width: 100%; height: auto" :src="file.path" alt="">
                <video style="width: 100%; height: auto" v-else-if="file.ext === 'mp4'" controls autoplay>
                    <source :src="file.path">
                </video>
            </v-card>
        </v-dialog>
        <v-menu
                offset-y
                v-model="showMenu"
                absolute
                :position-x="x"
                :position-y="y"
        >
            <v-list dense>
                <v-list-tile @click="removeFile">
                    <v-list-tile-title>Удалить</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>
        <snackbar :options="snackbar"/>
    </v-container>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex'
    import VCodeMirror from '../components/common/CodeMirror'
    import axios from 'axios'
    import Snackbar from '../components/common/Snackbar'

    export default {
        components: {
            VCodeMirror,
            Snackbar
        },
        data() {
            return {
                dialog: false,
                file: {},
                showMenu: false,
                ed_file: null,
                x: 0,
                y: 0,
                snackbar: {},
                items: [],
                path: [],
                extensions: {
                    sass: 'text/x-sass',
                    html: 'text/html',
                    js: 'text/javascript',
                    css: 'text/css',
                    json: 'application/json',
                    php: 'text/x-php',
                    sh: 'text/x-sh',
                    sql: 'text/x-mysql',
                    vue: 'text/x-vue'
                },
                image_extensions: ['jpg', 'jpeg', 'png', 'svg', 'gif'],
                title: '',
                code:
                    `$page-width:    800px
$sidebar-width: 200px
$primary-color: #eeeeee

body
  font:
    family: sans-serif
    size: 30em
    weight: bold`,
            }
        },
        mounted() {
            this.getDirectories()
        },
        watch: {
            loadingDirectories(v) {
                if (!v) {
                    this.items = this.directories
                    this.path = [{text: '/', items: this.items}]
                }
            }
        },
        computed: {
            ...mapGetters({
                directories: 'directories',
                loadingDirectories: 'loadingDirectories'
            })
        },
        methods: {
            ...mapActions([
                'getDirectories'
            ]),
            back(item) {
                this.items = item.items
                const index = this.path.findIndex(p => p.text === item.text)
                this.path.splice(index + 1, this.path.length - index - 1)
            },
            fileInfo(f) {
                const path = this.formPath(f)
            },
            read(f) {
                this.file = f
                this.file.mode = this.extensions[f.ext]
                if (this.extensions[f.ext]) {
                    axios.post('/api/files/read', {path: f.path}).then(res => {
                        this.file.code = res.data
                        this.dialog = true
                    }).catch(this.processError)
                } else this.dialog = true
            },
            dirInfo(d) {
                const path = this.formPath(d)
            },
            formPath(v) {
                let str = this.path.reduce((carry, item) => {
                    carry += `/${item.text}`
                    return carry
                }, '')
                str += v
                return str
            },
            show(e, f = null) {
                e.preventDefault()
                this.ed_file = f
                this.showMenu = false
                this.x = e.clientX
                this.y = e.clientY
                this.$nextTick(() => {
                    this.showMenu = true
                })
            },
            removeFile() {
                axios.post('/api/files/remove', {path: this.ed_file.path })
                    .then(_ => {
                        this.items.splice(this.items.indexOf(this.ed_file), 1)
                        this.processSuccess('Файл успешно удалён')
                    })
                    .catch(this.processError)
            },
            processError({ response }) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
            },
            processSuccess(msg) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
            }
        }
    }
</script>