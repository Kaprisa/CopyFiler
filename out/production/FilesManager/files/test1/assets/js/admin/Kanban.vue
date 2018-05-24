<template>
    <v-container fluid>
        <v-layout row >
            <v-flex xs4 sm3 v-for="c, j in categories" :key="c.name">
                <v-card height="80vh" class="droppable">
                    <v-toolbar dark :style="`background-color: ${c.color};`">
                        <v-icon class="mr-1">{{ c.icon }}</v-icon><!--:color="c.color"-->
                        <span>{{ c.name }}</span>
                    </v-toolbar>
                    <v-card v-for="t, i in c.tasks"  :key="t.name" class="ma-2 draggable">
                        <v-card-title><!--@mousedown="start"@mousemove="move"-->
                            <span class="subheading">{{ t.name }}</span>
                            <v-spacer></v-spacer>
                            <v-avatar
                                    size="36px"
                                    slot="activator"
                            >
                                <img
                                        src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
                                >
                            </v-avatar>
                        </v-card-title>
                        <v-card-text>
                            <div v-html="t.description">
                            </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn icon>
                                <v-icon>favorite</v-icon>
                            </v-btn>
                            <v-btn icon color="info">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <v-btn icon color="error" @click="deleteTask(t.id, j, i)">
                                <v-icon>delete</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                    <div class="text-xs-center">
                        <v-btn fab small @click="taskDialog = true; category = c;" color="primary" dark>
                            <v-icon>add</v-icon>
                        </v-btn>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="taskDialog" width="50%">
            <task @success="addTask" :category="category.id"></task>
        </v-dialog>
        <snackbar :options="snackbar"></snackbar>
    </v-container>
</template>

<script>
    //import DragManager from '../DragManager'
    import Task from './actions/Task'
    import Snackbar from '../components/common/Snackbar'
    import { mapActions, mapGetters } from 'vuex'
    export default {
        components: {
            Task,
            Snackbar
        },
        data() {
            return {
                category: 1,
                taskDialog: false,
                draggable: {},
                snackbar: {},
                // tabs: [
                //     {
                //         name: 'Открытые',
                //         color: 'blue',
                //         icon: 'mail',
                //         tasks: [
                //             {
                //                 name: 'hello',
                //                 text: 'Это новая задачка!'
                //             },
                //             {
                //                 name: 'hello2',
                //                 text: 'Это вторая задачка!'
                //             }
                //         ]
                //     },
                //     {
                //         name: 'В процессе',
                //         color: 'green',
                //         icon: 'palette'
                //     },
                //     {
                //         name: 'Завершенные',
                //         color: 'red',
                //         icon: 'add'
                //     }
                // ]
            }
        },
        computed: {
           ...mapGetters({
                categories: 'taskcategories'
            })
        },
        methods: {
            ...mapActions([
                'getTaskCategories'
            ]),
            addTask(text, task) {
                if (!this.category.tasks) this.category.tasks = []
                this.processSuccess(text)
                this.category.tasks.push(task)
                this.taskDialog = false
            },
            deleteTask(id, category_index, index) {
                axios.delete(`/api/tasks/${id}`).then(res => {
                   this.processSuccess('Задача успешно удалена!')
                    this.categories[category_index].tasks.splice(index, 1)
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
            // start(e) {
            //     this.draggable.x = e.pageX
            //     this.draggable.y = e.pageY
            //     this.draggable.e = e.target
            // },
            // move() {
            //     if (!this.draggable.e) return
            // }
        },
        mounted() {
            this.getTaskCategories()
            // DragManager.onDragCancel = function (dragObject) {
            //     dragObject.avatar.rollback();
            // };
            //
            // DragManager.onDragEnd = function (dragObject, dropElem) {
            //     dragObject.elem.style.display = 'none';
            //     dropElem.classList.add('computer-smile');
            //     setTimeout(function () {
            //         dropElem.classList.remove('computer-smile');
            //     }, 200);
            // }
        }
    }
</script>