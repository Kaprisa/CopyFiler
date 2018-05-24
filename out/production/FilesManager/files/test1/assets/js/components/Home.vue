<template>
    <v-layout row wrap>
        <v-flex xs12 sm12 md6 lg6 xl4 class="mb-3" v-for="c in courses" :key="c.id">
            <v-card class="mx-1">
                <v-card-media :src="`/uploads/courses/${c.image}`" height="200px">
                </v-card-media>
                <v-card-title primary-title>
                   <h3 class="title mb-0 mr-2">{{ c.name }}</h3>
                    <div v-if="c.editable">
                        <v-icon style="cursor: pointer;" color="info" @click="$router.push(`/courses/${c.id}/edit`)">edit</v-icon>
                        <v-icon style="cursor: pointer;" color="error" @click="removeCourse(c.id)">delete</v-icon>
                    </div>
                    <v-spacer/>
                    <span v-if="c.studied">
                        {{ c.user_points }} / {{ c.points }}
                    </span>
                </v-card-title>
                <v-card-actions>
                    <v-btn dark @click="$router.push(`/courses/${c.id}`)" :disabled="!c.studied" color="secondary">Учиться</v-btn>
                    <v-spacer/>
                    <v-btn
                            dark
                            fab
                            small
                            v-if="!c.studied"
                            @click="userAddCourse(c)"
                            color="accent"
                    >
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn
                            v-else
                            dark
                            fab
                            small
                            @click="userRemoveCourse(c)"
                            color="error"
                    >
                        <v-icon>delete</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
        <snackbar :options="snackbar"></snackbar>
        <confirm :visible="confirm" @ok=""></confirm>
    </v-layout>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import axios from 'axios'
    import Snackbar from './common/Snackbar'
    import Confirm from './common/Confirm'
    export default {
        data() {
            return {
                snackbar: {},
                confirm: false
            }
        },
        mounted() {
          this.getCourses()
        },
        components: {
            Snackbar,
            Confirm
        },
        computed: {
            ...mapGetters({
                courses: 'courses',
                loadingCourses: 'loadingCourses'
            })
        },
        watch: {
            loadingCourses(val) {
                if (!val) {
                    this.courses.forEach(c => c.show = false)
                }
            }
        },
        methods: {
            ...mapActions([
                'getCourses'
            ]),
            userAddCourse(course) {
               axios.post('/api/user/courses/add', { id: course.id }).then(_ => {
                   course.studied = true
                   this.snackbar = {
                       visible: true,
                       text: 'Курс успешно добавлен.',
                       error: false
                   }
               }).catch(this.processError)
            },
            userRemoveCourse(course) {
                axios.delete(`/api/user/courses/${course.id}`).then(_ => {
                    course.studied = false
                    this.snackbar = {
                        visible: true,
                        text: 'Курс успешно удален.',
                        error: false
                    }
                }).catch(this.processError)
            },
            removeCourse(id) {
               axios.delete(`/api/courses/${id}`).then(_ => {
                  this.snackbar = {
                      visible: true,
                      text: 'Курс успешно удален.',
                      error: false
                  }
                  this.courses.splice(this.courses.findIndex(c => c.id === id), 1)
               }).catch(this.processError)
            },
            processError({ response }) {
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