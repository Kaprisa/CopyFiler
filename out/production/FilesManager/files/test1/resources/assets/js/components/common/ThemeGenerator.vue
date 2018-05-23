<template>
    <v-dialog v-model="dialog" width="60%">
        <v-btn
                color="secondary"
                small
                dark
                fab
                slot="activator"
                @click="dialog=true;"
        >
            <v-icon>palette</v-icon>
        </v-btn>
        <v-card class="elevation-12">
            <v-card-media src="/images/new_york.jpeg" height="15vh">
            </v-card-media>
            <v-layout v-if="user.theme" row wrap class="px-3 py-2" justify-space-around>
                <v-flex xs11 class="layout">
                    <v-text-field
                            placeholder="Название"
                            v-model="user.theme.name"
                    ></v-text-field>
                </v-flex>
                <v-flex class="layout justify-center">
                    <div class="flex xs2 text-xs-center" v-for="v, k in user.theme.colors" :key="k">
                        <span class="body-2">{{ k }}</span>
                        <input type="color" style="height: 70px; width: 100%; border: none" :value="v" @change="(e) => user.theme.colors[k] = e.target.value">
                    </div>
                </v-flex>
                <!--<v-flex xs5 v-for="v, k in user.theme.colors" :key="k">-->
                    <!--<color-picker :vcolor="k" :label="v" @change="(color) => user.theme.colors[k] = color"></color-picker>-->
                <!--</v-flex>-->
            </v-layout>
            <div style="width: 150px; margin: auto;">
                <v-switch
                        label="Темная"
                        v-if="user.theme"
                        v-model="user.theme.dark"
                ></v-switch>
            </div>
            <v-card-actions>
                <v-spacer/>
                <v-btn @click="save" color="primary" dark>Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import ColorPicker from './ColorPicker'
    import { mapGetters} from 'vuex'
    import Snackbar from './Snackbar'
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
                themeMap
            }
        },
        computed: {
            ...mapGetters({
                themes: 'themes',
                user: 'user'
            })
        },
        methods: {

            save() {
                axios.post('/api/user/themes/save', this.user.theme).then(res => {
                    this.processSuccess('Ваша тема успешно сохранена!')
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

            processSuccess(msg) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
                this.dialog = false
            }
        }
    }
</script>