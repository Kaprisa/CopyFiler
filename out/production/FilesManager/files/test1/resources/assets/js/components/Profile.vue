<template>
    <!--<v-container fluid fill-height>-->
    <div>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8>
                <v-card class="elevation-12 mb-5">
                    <v-card-media src="/images/new_york.jpeg" height="30vh">
                    </v-card-media>
                    <v-layout @keypress.13="saveProfile" style="position: relative;">
                        <v-flex xs5 md3 class="layout column align-center"
                                style="margin-top: 100px; position: relative">
                            <v-avatar
                                    size="150px"
                                    style="position: absolute; top: -175px;"
                            >
                                <input
                                        type="file"
                                        accept="image/*"
                                        @change="uploadAvatar($event.target.files[0])"
                                        class="input-file"
                                >
                                <img
                                        :src="`uploads/users/${user.avatar}`"
                                >
                            </v-avatar>
                            <span class="headline">{{ user ? user.name : ''}}</span>
                            <v-chip>
                                <v-avatar class="success">
                                    <v-icon dark>euro_symbol</v-icon>
                                </v-avatar>
                                {{ user.points }}
                            </v-chip>
                        </v-flex>
                        <v-flex xs7 md9 class="pa-1">
                            <v-layout column>
                                <v-text-field
                                        prepend-icon="account_circle"
                                        placeholder="Имя"
                                        v-model="user.name"
                                ></v-text-field>
                                <v-text-field
                                        prepend-icon="mail"
                                        placeholder="Email"
                                        v-model="user.email"
                                ></v-text-field>
                                <v-text-field
                                        type="tel"
                                        prepend-icon="phone"
                                        placeholder="(000) 000 - 0000"
                                        v-model="user.phone"
                                ></v-text-field>
                                <v-btn @click="saveProfile" color="secondary" dark>Сохранить</v-btn>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
        </v-layout>
        <snackbar :options="snackbar"></snackbar>
        <v-layout row justify-center>
            <v-flex md4 sm6 lg3 xs12 class="mx-3">
                <v-card class="elevation-12">
                    <canvas ref="polar" width="200" height="200"></canvas>
                </v-card>
            </v-flex>
            <v-flex md4 sm6 lg3 xs12 class="mx-3">
                <v-card class="elevation-12">
                    <canvas ref="pie" width="200" height="200"></canvas>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import axios from 'axios'
    import Snackbar from './common/Snackbar'
    import Chart from 'chart.js';

    export default {
        name: 'Profile',
        components: {
            Snackbar
        },
        data() {
            return {
                snackbar: {}
            }
        },
        watch: {
           loadingUser(v) {
               if (!v) {
                   const pie = this.$refs.pie
                   const { correct, attempts, count, points } = this.user.analysis
                   const myPieChart = new Chart(pie,
                       {
                           type: "pie",
                           data: {
                               labels: [
                                   "Правильные ответы",
                                   "Неправилные ответы"
                               ],
                               datasets: [
                                   {
                                       data: [
                                           correct,
                                           attempts,
                                       ],
                                       backgroundColor: [
                                           this.user.theme.colors.success,
                                           this.user.theme.colors.error
                                       ],
                                       hoverBackgroundColor: [
                                           "#00ff00",
                                           "#ff0000"
                                       ]
                                   }
                               ]
                           },
                           options: {}
                       }
                   )
                   const radar = this.$refs.polar
                   const myRadarChart = new Chart(radar, {
                       type: "polarArea",
                       data: {
                           datasets: [
                               {
                                   data: [
                                       points,
                                       count,
                                       correct,
                                       attempts
                                   ],
                                   backgroundColor: [
                                       "#FF6384",
                                       "#4BC0C0",
                                       "#FFCE56",
                                       "#E755ED"
                                   ],
                                   label: "Общая статистика"
                               }
                           ],
                           labels: [
                               "Баллы",
                               "Отвечено вопросов",
                               "Правильных ответов",
                               "Попытки"
                           ]
                       },
                       options: {}}
                   );
               }
           }
        },
        computed: {
            ...mapGetters({
                user: 'user',
                loadingUser: 'loadingUser'
            })
        },
        mounted() {
            this.getUser()
        },
        methods: {
            ...mapActions([
                'getUser'
            ]),
            uploadAvatar(file) {
                const formData = new FormData()
                if (!file) return
                formData.append('avatar', file, file.name)
                axios.post('/api/user/avatar', formData)
                    .then(({data}) => this.user.avatar = data)
                    .catch(this.processError);
            },
            saveProfile() {
                axios.put('/api/user/profile', this.user).then(res => [
                    this.snackbar = {
                        visible: true,
                        text: 'Ваш профиль успешно обновлен!',
                        error: false
                    }
                ]).catch(this.processError)
            },
            processError({response}) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
            }
        }
    }
</script>