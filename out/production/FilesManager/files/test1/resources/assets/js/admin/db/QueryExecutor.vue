<template>
    <v-card class="mb-3">
        <v-card-title class="row justify-space-between align-center">
            <h2 class="title text-xs-center mb-3">{{ queryName }} запрос</h2>
            <v-spacer/>
            <span class="body-1 gray">{{ text }}</span>
        </v-card-title>
        <codemirror @change="alert = false" v-model="code" :options="opt"/>
        <v-alert dismissible type="error" v-model="alert">
            {{ error }}
        </v-alert>
        <v-layout row justify-end>
            <v-btn
                    :loading="loading"
                    @click.native="query()"
                    :disabled="loading"
                    color="pink"
                    dark
            >Выполнить</v-btn>
            <v-btn
                    @click.native="reset()"
                    color="blue"
                    dark
            >Сброс</v-btn>
        </v-layout>
        <v-card v-if="my_items">
            <v-card-title>
                Результат {{ my_items.length }} строк. Запрос занял {{ time }} мс.
            </v-card-title>
            <v-data-table
                    :headers="headers"
                    :items="my_items"
                    disable-initial-sort
                    class="elevation-1 mb-3"
                    :loading="loading"
            >
                <template slot="items" slot-scope="props">
                    <td v-for="val, key in props.item" :key="val + Math.random() * 1000">{{ val ? ((val.length < 100 || Number(val)) ? val : 'long') : 'null' }}</td>
                </template>
                <template slot="no-data">
                    <div></div>
                </template>
            </v-data-table>
        </v-card>
    </v-card>
</template>
<script>
    import { codemirror } from 'vue-codemirror-lite'
    import 'codemirror/mode/sql/sql.js'
    import 'codemirror/mode/php/php.js'
    import 'codemirror/addon/selection/active-line.js'
    import 'codemirror/addon/hint/show-hint.js'
    import 'codemirror/addon/hint/show-hint.css'
    import 'codemirror/addon/hint/sql-hint.js'
    import 'codemirror/theme/solarized.css'

    /*import trunk from '../../../helpers/trunk'*/

    import { mapGetters } from 'vuex'

    export default {
        name: 'query-executor',
        components: { codemirror },
        data () {
            return {
                code: this.example,
                error: null,
                alert: false,
                loading: false,
                time: 0,
                /*trunk,*/
                dialog: false,
                opt: {
                    mode: this.mode,
                    styleActiveLine: true,
                    tabSize: 2,
                    lineNumbers: true,
                    lineWrapping: true,
                    theme: `solarized ${this.theme}`,
                    extraKeys: {'Ctrl-Space': 'autocomplete'},
                    matchBrackets: true,
                    viewportMargin: Infinity,
                   /* hintOptions: { tables: this.tables }*/
                },
                headers: [],
                my_items: null,
            }
        },
        props: {
          url: {
              type: String,
              default: '/query'
          },
          theme: {
              type: String,
              default: 'light'
          },
          mode: {
              type: String,
              default: 'text/x-mysql'
          },
          queryName: {
              type: String,
              default: 'SQL'
          },
          text: {
              type: String,
              default: 'autocomplete: CTRL + Space'
          },
          example: {
            type: String,
            default: ''
          }
        },
        methods: {
            query() {
                this.error = null
                this.my_items = null
                this.loading = true
                let time = Date.now()
                axios.post(this.url, { q: this.code }).then(res => {
                    this.time = Date.now() - time
                    const { data } = res
                    this.loading = false
                    if (!Array.isArray(data)) return
                    this.headers = data && data[0] && Object.keys(data[0]).map(key => ({ text: key, value: key, align: 'left' }))
                    this.my_items = data
                }).catch(err => {
                    this.error = err.response ? err.response.data.message : 'Что-то пошло не так..:('
                    this.alert = true
                    this.loading = false
                })
            },
            reset() {
                this.my_items = null
                this.loading = false
                this.code = ''
            }
        },
        /*computed: mapGetters({
            tables: 'tables',
        }),*/
    }

</script>

<style exact>
    .input-group.input-group--selection-controls.switch {
        height: 30px;
    }
</style>