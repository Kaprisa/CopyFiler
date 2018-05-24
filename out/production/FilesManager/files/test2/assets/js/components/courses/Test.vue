<template>
    <div>
        <v-tabs
                color="secondary"
                dark
                slider-color="accent"
                centered
                show-arrows
                :value="active"
        ><!--                v-model="active"-->
            <v-tab
                    v-for="q, index in props.questions"
                    :key="`q${index}`"
                    ripple
                    :id="`${index}`"
            >
                {{ index + 1 }}
                <v-icon v-if="q.correct" dark>check_circle</v-icon>
            </v-tab>
            <v-tab-item v-for="q, index in props.questions" class="py-2 px-3" :key="`qb${index}`">
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
                <v-text-field @keypress.13="check(q)" v-if="q.type === 'task'" label="Ваш ответ" v-model="q.user_answer"></v-text-field>
                <v-btn
                        @click="q.answers.forEach(a => a.checked = false)"
                        color="info"
                        v-if="q.answered"
                        dark
                >
                    Сброс
                </v-btn>
                <v-btn @click="check(q)" color="success">Проверить</v-btn>
                <v-chip>
                    <v-avatar class="accent"><v-icon dark>euro_symbol</v-icon></v-avatar>
                    {{ q.correct ? q.points : 0 }} / {{ q.points }}
                </v-chip>
                <v-chip v-if="q.attempts">
                    <v-avatar class="error">A</v-avatar>
                    {{ q.attempts }}
                </v-chip>
            </v-tab-item>
        </v-tabs>
        <snackbar :options="snackbar"></snackbar>
    </div>
</template>

<script>
    import Snackbar from '../common/Snackbar'
    import axios from 'axios'
    import md from '../../markdown'
    import RadioGroup from '../common/RadioGroup'
    export default {
        name: 'test',
        data() {
            return {
                snackbar: {},
                page: 1,
                ex7: 0,
                active: "0",
                answer: '',
                md
            }
        },
        components: {
            Snackbar,
            RadioGroup
        },
        props: {
           props: Object
        },
        methods: {
            check(q) {
                let isCorrect = false
                if (q.type === 'task') {
                    const answers = q.answer.split(';')
                    isCorrect = answers.some(a => a.replace(/\s/g,'').toLowerCase() === q.user_answer.replace(/\s/g,'').toLowerCase())
                } else {
                   isCorrect = q.answers.every(a => Boolean(a.checked) === Boolean(a.correct))
                }
                this.snackbar = {
                    visible: true,
                    text: isCorrect ? 'Правильно!' : 'Не правильно:( Подумайте немного.. У вас все получится! ',
                    error: !isCorrect
                };
                if (isCorrect) {
                    q.correct = true
                    this.active = parseInt(this.active) === this.props.questions.length - 1 ? "0" : (parseInt(this.active) + 1).toString();
                } else {
                    q.correct = false;
                    if (q.attempts) q.attempts ++;
                    else q.attempts = 1
                }
                axios.post(`/api/question/${q.id}/answer`, { correct: q.correct, attempts: q.attempts })
            }
        }
    }
</script>