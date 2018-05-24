<template>
    <v-dialog v-model="dialog" width="70vw">
        <v-btn slot="activator" color="info" dark>Тест</v-btn>
        <v-card>
            <v-card-title>
                <span class="subheading">Тест</span>
                <v-spacer/>
                <v-btn
                        @click="questions.push({ answers: [], points: 0, active: true }); active = `${questions.length - 1}`;"
                        fab
                        dark
                        small
                        color="info"
                >
                    <v-icon dark>add</v-icon>
                </v-btn>
            </v-card-title>
            <v-tabs
                    v-model="active"
                    color="secondary"
                    dark
                    slider-color="accent"
                    centered
            >
                <v-tab
                        v-for="q, index in questions"
                        :key="`q${index}`"
                        ripple
                >
                    {{ index + 1 }}
                </v-tab>
                <v-tab-item v-for="q, index in questions" class="py-2 px-3" :key="`qb${index}`">
                    <v-select v-model="q.type" label="Тип" :items="types"></v-select>
                    <v-text-field label="Вопрос" v-model="q.text" textarea></v-text-field>
                    <v-text-field label="Комментарий к ответу" v-model="q.comment" textarea></v-text-field>
                    <v-layout>
                        <v-flex xs3 md2><span class="subheading">Оценка</span></v-flex>
                        <v-flex xs9 md10>
                            <rating :value="q.points" :can-edit="true" :process="(v) => q.points = v"></rating>
                        </v-flex>
                    </v-layout>
                    <v-switch label="Активен?" v-model="q.active"></v-switch>
                    <div v-if="q.type === 'task'">
                        <v-text-field label="Контроллер (если нужен)" v-model="q.controller"></v-text-field>
                        <v-text-field placeholder="Если правильных ответов несколько, разделите их ';', например 1+k;k+1" label="Ответ" v-model="q.answer"></v-text-field>
                    </div>
                    <div v-else>
                        <v-card-title>
                            Ответы
                            <v-spacer/>
                            <v-btn
                                    @click="q.answers.push({})"
                                    fab
                                    dark
                                    color="amber"
                                    small
                            >
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-divider/>
                        <v-layout class="py-2" align-content-center align-center row wrap v-for="a, i in q.answers" :key="`q${index}a${i}`">
                            <v-flex xs1>
                                <v-switch
                                        v-model="a.correct"
                                ></v-switch>
                            </v-flex>
                            <v-flex xs11>
                                <v-text-field solo v-model="a.text"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </div>
                </v-tab-item>
            </v-tabs>
            <v-card-actions>
                <v-spacer/>
                <v-btn color="primary" @click="ok()" flat>Ок</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import VCodeMirror from './CodeMirror'
    import Rating from '../common/Rating'
    export default {
        name: 'test-master',
        components: {
           VCodeMirror,
            Rating
        },
        data() {
            return {
                dialog: false,
                active: null,
                //questions: []
            }
        },
        props: {
            types: Array,
            questions: Array
        },
        methods: {
            ok() {
                this.dialog = false
                this.$emit('change', this.questions)
            }
        }
    }
</script>