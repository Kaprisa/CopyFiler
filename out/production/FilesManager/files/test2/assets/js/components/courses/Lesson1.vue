<template>
    <v-container>
        <v-expansion-panel>
            <v-expansion-panel-content :value="false" v-if="lesson.video">
                <div slot="header">Смотреть видео</div>
                <v-video v-if="course.course && lesson.video" :course="course.course.name" :video="lesson.video"></v-video>
            </v-expansion-panel-content>
            <v-expansion-panel-content :value="false" v-if="lesson.text">
                <div slot="header">Конспект лекции</div>
                <div class="pa-3 md" v-html="lesson.text"></div>
            </v-expansion-panel-content>
            <v-expansion-panel-content :value="false" v-if="lesson.examples">
                <div slot="header">Примеры</div>
                <div class="pa-3 md" v-html="lesson.examples"></div>
            </v-expansion-panel-content>
            <v-expansion-panel-content :value="false" v-if="lesson.resources && lesson.resources.length">
                <div slot="header">Дополнительные материалы</div>
                <v-list dense>
                    <v-list-tile avatar v-for="r in lesson.resources" :key="r">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ r }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <div class="text-xs-center">
            <v-dialog v-model="testDialog" width="70vw">
                <v-btn slot="activator" color="accent">Тест</v-btn>
                <v-card>
                    <test :props="{ questions: lesson.questions }"></test>
                </v-card>
            </v-dialog>
        </div>
        <div class="text-xs-center">
            <v-dialog v-model="test2Dialog" fullscreen transition="dialog-bottom-transition">
                <v-btn slot="activator" color="accent">Тест2</v-btn>
                <v-card>
                    <test2 @percent="v => lesson.percent = v" @close="test2Dialog = false" :questions="lesson.questions"></test2>
                </v-card>
            </v-dialog>
        </div>
    </v-container>
</template>

<script>
    import VVideo from './Video'
    import Test from './Test'
    import Test2 from './Test2'
    import { mapActions, mapGetters } from 'vuex'

    import md from '../../markdown'

    export default {
        components: {
            VVideo,
            Test,
            Test2,
        },
        mounted() {
            this.getLesson(this.$route.params.lesson_id)
        },
        data() {
            return {
                drawer: true,
                testDialog: false,
                test2Dialog: false,
            }
        },
        watch: {
          $route(v) {
              const { lesson_id } = v.params
              if (lesson_id) {
                  this.getLesson(lesson_id)
              }
          },
            loadingLesson(val) {
              if (!val) {
                  if (this.lesson.text)
                    this.lesson.text = md.render(this.lesson.text)
                  if (this.lesson.examples)
                      this.lesson.examples = md.render(this.lesson.examples)
              }
            }
        },
        computed: {
            ...mapGetters({
                lesson: 'lesson',
                loadingLesson: 'loadingLesson',
                course: 'course'
            })
        },
        methods: {
            ...mapActions([
                'getLesson'
            ]),
        }
    }
</script>