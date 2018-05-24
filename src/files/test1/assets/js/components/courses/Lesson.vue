<template>
    <div class="perspective">
        <div class="cube"  :style="`transform: translateY(-50px) rotateY(${props.angleY}deg) rotateX(${angleX}deg);`">
            <div v-for="l, index in lessons" :key="index" class="cube__side">
                <v-card v-if="l" class="mb-2 pa-5" height="100%">
                    <component :props="l.props" :is="l.component"></component>
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
    import VVideo from './Video'
    import Test from './Test'
    import VText from './Text'
    import { mapActions, mapGetters } from 'vuex'

    export default {
        components: {
            VVideo,
            Test,
            VText
        },
        mounted() {
            this.getLesson(this.$route.params.lesson_id)
        },
        data() {
            return {
                angleX: 0,
                drawer: true,
                lessons: [
                    {
                        component: 'test',
                        name: 'test',
                        props: {

                        }
                    },
                    {
                        component: 'v-text',
                        name: 'text',
                        props: {

                        }
                    },
                    {
                        component: 'v-text',
                        name: 'text',
                        props: {

                        }
                    },
                    false,
                    false,
                    {
                        component: 'v-video',
                        name: 'Video',
                        props: {
                            src: '/video/v.mp4'
                        }
                    }
                ]
            }
        },
        watch: {
          loadingLesson(val) {
              if (!val) {
                  let o = { questions: 0, text: 1, video: 5 }
                  Object.keys(o).forEach(k => {
                      this.lessons[o[k]].props = { [k]: this.lesson[k] }
                  })
                  //this.lessons[5].props['dir'] = this.course.course.name
                  this.lessons = { ...this.lessons }
              }
          }
        },
        props: {
           props: Object
        },
        computed: {
            ...mapGetters({
                lesson: 'lesson',
                //course: 'course',
                loadingLesson: 'loadingLesson'
            })
        },
        methods: {
            ...mapActions([
                'getLesson'
            ]),
        }
    }
</script>