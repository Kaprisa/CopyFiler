<template>
    <v-container fluid grid-list-md>
        <v-flex
                v-for="b in course.blocks"
                :key="b.name"
                xs12
                class="mb-2"
        >
            <v-card>
                <v-card-title><h4>{{ b.name }}</h4></v-card-title>
                <v-divider></v-divider>
                <v-list dense>
                    <v-list-tile
                            v-for="l in b.lessons"
                            :key="`l${l.name}`"
                            @click="$router.push(`/courses/${course.course.id}/lessons/${l.id}`)"
                    >
                        <v-list-tile-content>{{ l.name }}</v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-flex>
    </v-container>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        computed: {
            ...mapGetters({
                course: 'course',
                loadingCourse: 'loadingCourse'
            })
        }
    }
</script>