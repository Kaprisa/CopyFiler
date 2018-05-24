<template>
    <v-container full-height fluid v-on:mousemove="change">
        <v-menu
                offset-x
                :close-on-content-click="false"
                :nudge-width="200"
                v-model="menu"
        >
            <v-btn
                    slot="activator"
                    fab
                    dark
                    color="light-green "
                    absolute
                    top
                    left
                    style="margin-top: -50px;"
            >
                <v-icon dark>add</v-icon>
            </v-btn>
            <v-card>
                <v-layout row>



                </v-layout>
                <v-divider />
                <v-container grid-list-md text-xs-center>

                </v-container>
                <v-card-actions>
                    <v-spacer />
                    <v-btn flat @click="menu = false">Отмена</v-btn>
                    <v-btn color="primary" flat>Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
        <plot
                type="hello"
                :params="params"
                :height="80"
                :mouse="mouse"
        />
    </v-container>
</template>
<script>
    import Plot from './Plot'
    export default {
        components: {
            Plot
        },
        data() {
            return {
                dialog: false,
                fav: true,
                menu: false,
                params: { max: 100, min: -100 },
                mouse: {
                    x: 0,
                    y: 0
                }
            }
        },
        mounted() {

        },
        computed: {

        },

        methods: {
            change(e) {
                this.mouse = {
                    x: e.clientX,
                    y: e.clientY
                }
            }
        }
    }
</script>