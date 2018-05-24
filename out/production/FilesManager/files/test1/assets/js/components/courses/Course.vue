<template>
    <div>
        <v-navigation-drawer
                v-model="drawer"
                app
                left
                clipped

        >
            <v-list dense v-if="course.course">
                <v-list-tile @click="$router.push(`/courses/${course.course.id}`)">
                    <v-list-tile-action>
                        <v-icon>home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ course.course.name }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-group
                        prepend-icon="graphic_eq"
                        no-action
                        v-for="block in course.blocks" :key="block.name"
                >
                    <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ block.name }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                            v-if="block.lessons"
                            v-for="lesson in block.lessons" :key="lesson.name"
                            @click="$router.push(`/courses/${course.course.id}/lessons/${lesson.id}`)"
                            style="position: relative;"
                    >
                         <v-progress-circular v-if="lesson.percent" size="20" :value="lesson.percent" color="success" style="position: absolute; left: 10px"></v-progress-circular>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ lesson.name }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>
        <component :is="what"></component>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Lesson from './Lesson1'
    import Welcome from './Welcome'

    export default {
        data() {
            return {
                drawer: true
            }
        },
        props: {
          what: String
        },
        mounted() {
            this.getCourse(this.$route.params.id)
        },
        components: {
            Lesson,
            Welcome
        },
        computed: {
            ...mapGetters({
                course: 'course',
                loadingCourse: 'loadingCourse'
            })
        },
        methods: {
            ...mapActions([
                'getCourse'
            ]),
        }
    }
</script>