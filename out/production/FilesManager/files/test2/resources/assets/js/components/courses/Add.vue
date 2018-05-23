<template>
    <v-layout justify-center wrap>
        <v-flex xs12 md10 lg8 class="mb-5">
            <v-card>
                <v-card-text>
                    <v-card-title class="headline">{{ editing ? 'Редактировать' : 'Новый' }} курс</v-card-title>
                    <v-divider></v-divider>
                    <v-text-field
                            label="Название"
                            v-model="course.name"
                            required
                            :rules="[() => !!course.name || 'Укажите название курса']"
                    ></v-text-field>
                    <v-layout>
                        <v-flex xs11>
                            <v-select
                                    autocomplete
                                    label="Категория"
                                    :rules="[() => !!course.category_id || 'Выберите категорию']"
                                    :items="categories"
                                    v-model="course.category_id"
                                    required
                                    item-text="name"
                                    item-value="id"
                            ></v-select>
                        </v-flex>
                        <v-flex xs1>
                            <v-btn @click="categoryDialog = true" fab dark
                                   color="accent">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </v-flex>
                        <v-dialog v-model="categoryDialog" max-width="500px">
                            <v-card>
                                <v-card-title>
                                    <span>Новая категория</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-text-field
                                            label="Название"
                                            v-model="category.name"
                                            required
                                            :rules="[() => !!category.name || 'Укажите название категории']"
                                            @keyup.13="saveCategory()"
                                    ></v-text-field>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn flat @click="categoryDialog = false">Отмена</v-btn>
                                    <v-spacer/>
                                    <v-btn @click="saveCategory()" flat color="primary">Сохранить</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                    <v-switch label="Активен?" v-model="course.active" class="mb-1"></v-switch>
                    <photo-uploader
                            :fileName="course.image"
                            dir="courses"
                            @uploadFile="(file) => course.image = file.name"
                            label="Фото"
                    ></photo-uploader>
                    <my-editor
                            @change="(v) => course.description = v"
                            buttonText="Описание"
                            :value="course.description"
                    ></my-editor>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="saveCourse">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
        <v-flex xs12 md10 lg8 v-if="editing">
            <v-card>
                <v-card-title class="headline">
                    Блоки {{ course.name }}
                    <v-spacer/>
                    <v-btn
                            @click="blocks.push({ name: 'Название', sort: blocks.length + 1, active: true })"
                            fab
                            dark
                            color="secondary"
                    >
                        <v-icon dark>add</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-data-table
                        :headers="headers"
                        :items="blocks"
                        v-if="blocks.length"
                        hide-actions
                >
                    <template slot="items" slot-scope="props">
                        <td>
                            <v-edit-dialog
                                    :return-value.sync="props.item.name"
                                    lazy
                            > {{ props.item.name }}
                                <v-text-field
                                        slot="input"
                                        label="Введите название"
                                        v-model="props.item.name"
                                        single-line
                                ></v-text-field>
                            </v-edit-dialog>
                        </td>
                        <td>{{ props.item.sort }}</td>
                        <td>
                            <v-switch v-model="props.item.active"></v-switch>
                        </td>
                        <td>
                            <v-btn v-if="props.item.id" @click="lessonDialog = true; block_id = props.item.id; getBlock(block_id)"  small fab dark
                                   :color="props.item.sort % 2 === 0 ? 'secondary' : 'info'">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </td>
                        <td>
                            <v-btn @click="saveBlock(props.item)" small fab dark
                                   :color="props.item.sort % 2 === 0 ? 'info' : 'secondary'">
                                <v-icon dark>save</v-icon>
                            </v-btn>
                        </td>
                        <td>
                            <v-btn v-if="props.item.id" @click="deleteBlock(props.item.id)" small fab dark
                                   color="error">
                                <v-icon dark>delete</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
        <snackbar :options="snackbar"></snackbar>
        <v-dialog v-model="lessonDialog" v-if="block" fullscreen transition="dialog-bottom-transition" :overlay="false">
            <v-card>
                <v-toolbar dark color="primary" class="mb-5">
                    <v-btn icon @click.native="lessonDialog = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Уроки {{ course.name }}</v-toolbar-title>
                </v-toolbar>
                <v-layout row justify-space-around>
                    <v-flex xs5 md3>
                        <v-card>
                            <v-card-title><h4>Уроки</h4></v-card-title>
                            <v-divider></v-divider>
                            <v-list v-if="block && block.lessons" dense>
                                <v-list-tile v-for="l in block.lessons" :key="l.name">
                                    <v-list-tile-content>{{ l.name }}</v-list-tile-content>
                                    <v-list-tile-content class="align-end">
                                        <v-icon @click="lesson = l; editingLesson = true;" style="cursor: pointer;">edit</v-icon>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-card>
                    </v-flex>
                    <v-flex xs5>
                        <v-card>
                            <v-card-text>
                                <v-card-title class="headline">{{ editingLesson ? 'Обновить' : 'Новый' }} урок</v-card-title>
                                <v-divider></v-divider>
                                <v-text-field
                                        label="Название"
                                        v-model="lesson.name"
                                        required
                                        :rules="[() => !!lesson.name || 'Укажите название урока']"
                                ></v-text-field>
                                <v-card-title>
                                    Ссылки на дополнительные ресурсы
                                    <v-spacer/>
                                    <v-btn
                                            @click="lesson.resources.push({})"
                                            fab
                                            dark
                                            color="secondary"
                                            small
                                    >
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-card-title>
                                <v-text-field v-for="r, ri in lesson.resources" class="mb-1" :key="`${r.name}${ri}`" solo v-model="r.name"></v-text-field>
                                <v-switch label="Активен?" v-model="lesson.active"></v-switch>
                                <video-uploader
                                        :fileName="lesson.video"
                                        @uploadFile="(file) => lesson.video = file"
                                        :dir="course.name"
                                        label="Видео"
                                ></video-uploader>
                                <my-editor
                                        @change="(v) => lesson.text = v"
                                        buttonText="Текст"
                                        :value="lesson.text"
                                ></my-editor>
                                <my-editor
                                        @change="(v) => lesson.examples = v"
                                        buttonText="Примеры"
                                        :value="lesson.examples"
                                ></my-editor>
                                <test-master :types="add_info.types" :questions="lesson.questions" @change="(questions) => lesson.questions = questions"></test-master>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn v-if="editingLesson" flat @click="initLesson()">Отмена</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" flat @click="saveLesson()">Сохранить</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    //import VEditor from '../common/CKEEditor'
    //import MyEditor from '../common/Editor'
    import MyEditor from '../common/editor/MDEditor'
    import PhotoUploader from '../common/PhotoUploader'
    import VideoUploader from '../common/VideoUploader'
    import TestMaster from '../common/TestMaster'
    import Snackbar from '../common/Snackbar'
    import axios from 'axios'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        components: {
            //VEditor,
            MyEditor,
            PhotoUploader,
            TestMaster,
            Snackbar,
            VideoUploader
        },
        data: () => ({
            course: {
                active: true
            },
            errors: null,
            lessonDialog: false,
            categoryDialog: false,
            lesson: {},
            category: {},
            blocks: [],
            block_id: null,
            snackbar: {},
            editingLesson: false,
            headers: [
                {
                    text: 'Название',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {text: 'Порядок', value: 'sort', align: 'left'},
                {text: 'Активен?', value: 'active', align: 'left'},
                {text: 'Уроки', value: 'name', sortable: false, align: 'left'},
                {text: 'Сохранить', value: 'name', sortable: false, align: 'left'},
                {text: 'Удалить', value: 'name', sortable: false, align: 'left'}
            ],
            items: []
        }),
        mounted() {
            this.getCategories()
            this.getCoursesAddInfo()
            this.initLesson()
            if (this.editing) {
                this.getCourse(this.$route.params.id)
            }
        },
        watch: {
           loadingCourse(val) {
               if (!val) {
                   this.course = this.ed_course.course
                   this.blocks = this.ed_course.blocks
               }
           }
        },
        props: {
            editing: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            ...mapGetters({
                categories: 'categories',
                ed_course: 'course',
                add_info: 'coursesaddinfo',
                loadingCourse: 'loadingCourse',
                block: 'block',
                loadingBlock: 'loadingBlock'
            }),
        },
        methods: {
            ...mapActions([
                'getCategories',
                'getCourse',
                'getCoursesAddInfo',
                'getBlock'
            ]),
            initLesson() {
                this.editingLesson = false
                this.lesson = {
                    resources: [],
                    questions: [],
                    active: true,
                    type: 'test'
                }
            },
            saveCourse() {
                axios.post(`/api/courses/add`, this.course).then(res => {
                    this.processSuccess('Курс успешно добавлен!')
                    this.course.id = res.data
                    this.$router.push(`/courses/${res.data}/edit`)
                }).catch(this.processError)
            },
            saveCategory() {
                axios.post('/api/categories/add', this.category).then(res => {
                    this.processSuccess('Категория успешно добавлена!')
                    this.categories.push(res.data)
                    this.course.category_id = res.data.id
                    this.categoryDialog = false
                }).catch(this.processError)
            },
            saveBlock(block) {
                axios.post(`/api/courses/${this.course.id}/blocks/save`, block).then(res => {
                    //TODO: сделать его с id
                    this.processSuccess('Блок успешно сохранен!')
                    block.id = res.data
                }).catch(this.processError)
            },
            saveLesson() {
                axios.post(`/api/blocks/${this.block_id}/lessons/save`, this.lesson).then(res => {
                    this.processSuccess('Урок успешно сохранен!')
                    if (!this.editingLesson) {
                        this.lesson.id = res.data
                        this.block.lessons.push(this.lesson)
                    }
                    this.initLesson()
                }).catch(this.processError)
            },
            deleteBlock(id) {
               axios.delete(`/api/blocks/${id}`).then(_ => {
                   this.processSuccess('Блок успешно удален.')
                   this.blocks.splice(this.blocks.findIndex(c => c.id === id), 1)
               }).catch(this.processError)
            },
            processError({ response }) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]][0] : 'Ошибка сервера',
                    error: true
                }
            },
            processSuccess(msg) {
                this.snackbar = {
                    visible: true,
                    text: msg
                }
            }
        }
    }
</script>
