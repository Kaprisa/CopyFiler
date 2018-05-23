<template>
    <v-container style="position: relative;">

        <v-dialog v-model="dialog">
            <v-btn
                    color="pink"
                    small
                    absolute
                    right
                    top
                    dark
                    fab
                    class="mt-4"
                    slot="activator"
                    @click="init(); dialog=true;"
            >
                <v-icon>add</v-icon>
            </v-btn>
            <v-card class="elevation-12">
                <v-card-media src="/images/new_york.jpeg" height="15vh">
                </v-card-media>
                <v-layout row wrap class="px-3 py-2" justify-space-around>
                    <v-flex xs11 class="layout">
                        <v-select
                                placeholder="Название"
                                v-model="item.name"
                                :items="themes"
                                v-if="editing"
                        ></v-select>
                        <v-text-field
                                v-else
                                placeholder="Название"
                                v-model="item.name"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs5 v-for="v, k in item.colors" :key="k">
                        <color-picker :vcolor="k" :label="k" @change="(color) => item.colors[k] = color"></color-picker>
                    </v-flex>
                </v-layout>
                <div style="width: 150px; margin: auto;">
                    <v-switch
                            label="Темная"
                            v-model="item.dark"
                    ></v-switch>
                </div>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn @click="save" color="teal" dark>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-data-table
                :headers="headers"
                :items="themes"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.name }}</td>
                <td
                        v-for="c, i in themeMap"
                        :key="`${props.item.name} ${i} ${props.item.colors[c]}`"
                        :style="`background-color: ${props.item.colors[c]};`"
                        class="text-xs-center white--text body-2"
                >
                    {{ props.item.colors[c] }}
                </td>
                <td class="justify-center layout px-0">
                    <v-btn icon @click="item = props.item; dialog = true;">
                        <v-icon color="teal">edit</v-icon>
                    </v-btn>
                    <v-btn icon @click="remove(props.item)">
                        <v-icon color="red">delete</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>
        <snackbar :options="snackbar"></snackbar>
    </v-container>
</template>

<script>
    import ColorPicker from '../components/common/ColorPicker'
    import {mapActions, mapGetters} from 'vuex'
    import Snackbar from '../components/common/Snackbar'
    import axios from 'axios'

    export default {
        components: {
            ColorPicker,
            Snackbar
        },
        data() {
            const themeMap = [
                'primary',
                'secondary',
                'accent',
                'error',
                'warning',
                'info',
                'success'
            ]
            return {
                snackbar: {},
                editing: false,
                dialog: false,
                themeMap,
                headers: [ { text: 'Название', align: 'left', value: 'name', sortable: false } ]
                    .concat(themeMap.map(c => ({ text: c, align: 'center', value: 'name', sortable: false }))),
                item: {}
            }
        },
        mounted() {
            this.init()
            this.getThemes()
        },
        computed: {
            ...mapGetters({
                themes: 'themes'
            })
        },
        methods: {
            ...mapActions([
                'getThemes'
            ]),
            init() {
               this.item = {
                   colors: {
                       primary: '#1976D2',
                       secondary: '#424242',
                       accent: '#82B1FF',
                       error: '#FF5252',
                       info: '#2196F3',
                       success: '#4CAF50',
                       warning: '#FFC107'
                   },
               }
            },
            remove(item) {
                if (!item.id) return
                axios.delete(`/api/themes/${item.id}`).then(res => {
                    this.processSuccess('Тема успешно удалена.')
                    this.themes.splice(this.themes.indexOf(item), 1)
                }).catch(this.processError)
            },
            save() {
                axios.post('/api/themes/save', this.item).then(res => {
                    if (!this.item.id) this.themes.push(res.data)
                    this.processSuccess('Тема успешно сохранена!')
                }).catch(this.processError)
            },
            processError({ response }) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
                this.dialog = false
            },
            processSuccess(msg, cb = null) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
                this.dialog = false
                if (cb) cb()
            }
        }
    }

</script>