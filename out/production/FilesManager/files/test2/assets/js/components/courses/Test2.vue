<template>
    <v-container>
        <v-toolbar dark color="primary">
            <v-btn icon @click.native="$emit('close'); clear()" dark>
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Тест</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <transition name="fade">
            <v-card class="elevation-12 mb-3 mt-5 pa-2 test__result" v-show="percent !== -1">
                <v-layout justify-space-around align-center>
                    <v-flex xs4 xl2 lg3 md4>
                        <canvas ref="pie" width="150" height="150"></canvas>
                    </v-flex>
                    <v-flex v-if="percent !== -1" xs6 class="layout column justify-space-around align-center">
                        <v-chip>
                            <v-avatar class="secondary"><span class="title" style="color: #ffffff;">%</span></v-avatar>
                            {{ percent }} / 100
                        </v-chip>
                        <v-chip>
                            <v-avatar class="accent"><v-icon dark>euro_symbol</v-icon></v-avatar>
                            {{ points }} / {{ questions.length * 5 }}
                        </v-chip>
                        <v-btn color="primary" @click="clear">Пройти тест заново</v-btn>
                    </v-flex>
                </v-layout>
            </v-card>
        </transition>
        <transition name="fade">
            <div v-if="percent === -1">
                <v-card v-for="q, index in questions" :key="`qb${index}`" class="mb-2 pa-2">
                    <span class="subheading" v-html="md.render(q.text)"></span>
                    <v-layout v-if="q.type.startsWith('test')" v-for="a, i in q.answers" :key="`a${i}`" align-center row class="mb-1 mt-2">
                        <span class="option-input__wrapper">
                            <input class="option-input option-input_check" type="checkbox" v-if="q.type === 'test'" v-model="a.checked">
                            <input class="option-input option-input_radio" type="radio" :name="`radio${index}`" v-else v-model="a.checked">
                        </span>
                        <div>
                            <span style="line-height: 30px;" v-html="md.render(a.text)"></span>
                        </div>
                    </v-layout>
                    <v-text-field v-if="q.type === 'task'" label="Ваш ответ" v-model="q.user_answer"></v-text-field>
                    <v-card-actions v-if="percent !== -1">
                        <v-spacer></v-spacer>
                        <v-icon v-if="q.correct" color="success">check_circle</v-icon>
                        <v-icon v-else color="error">remove_circle</v-icon>
                    </v-card-actions>
                </v-card>
                <div class="text-xs-center">
                    <v-btn color="primary" @click="check">Проверить</v-btn>
                </div>
            </div>
        </transition>
        <snackbar :options="snackbar"></snackbar>
    </v-container>
</template>

<script>
    import Snackbar from '../common/Snackbar'
    import axios from 'axios'
    import md from '../../markdown'
    import Chart from 'chart.js'
    import { mapGetters } from 'vuex'
    export default {
        name: 'test',
        data() {
            return {
                snackbar: {},
                page: 1,
                ex7: 0,
                active: "0",
                answer: '',
                md,
                percent: -1,
                points: 0
            }
        },
        components: {
            Snackbar
        },
        props: {
            questions: Array
        },
        computed: {
            ...mapGetters({
                user: 'user'
            })
        },
        methods: {
            check() {
                const correctCount = this.questions.reduce((carry, q) => {
                    if (q.type === 'task') {
                        if (q.user_answer) {
                            const answers = q.answer.split(';')
                            q.correct = answers.some(a => a.replace(/\s/g,'').toLowerCase() === q.user_answer.replace(/\s/g,'').toLowerCase())
                        } else q.correct = false
                    } else q.correct = q.answers.every(a => Boolean(a.checked) === Boolean(a.correct))
                    if (q.correct) carry ++
                    return carry
                }, 0)
                const length = this.questions.length
                this.percent = Math.round((correctCount * 100) / length)
                this.$emit('percent', this.percent)
                this.points = correctCount * 5
                const pie = this.$refs.pie
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
                                        correctCount,
                                        length - correctCount,
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
                axios.post(`/api/user/test`, { lesson: this.questions[0].lesson_id, percent: this.percent  })
            },
            clear() {
                this.questions.forEach(q => {
                    q.correct = false
                    q.answers.forEach(a => a.checked = false)
                })
                this.percent = -1
            }
        }
    }
</script>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
        opacity: 0;
    }
</style>