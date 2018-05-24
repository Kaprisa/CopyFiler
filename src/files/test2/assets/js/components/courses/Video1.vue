<template>
    <v-card>
        <v-system-bar status color="primary" dark>
            <v-spacer></v-spacer>
            <v-icon>network_wifi</v-icon>
            <v-icon>signal_cellular_null</v-icon>
            <v-icon>battery_full</v-icon>
            <span>12:30</span>
        </v-system-bar>
        <video style="margin: 0 auto;" ref="video" poster="/video/poster.jpg">
            <source v-if="course.course" :src="`/video/${course.course.name}/${props.video}`" type="video/mp4">
            <source v-if="course.course" :src="`/video/${course.course.name}/${props.video}`" type="video/mov">
            Ваш браузер не поддерживает html5 видео.
        </video>
        <v-toolbar card color="primary" >
            <v-icon
                    @click="changePlay()"
                    dense
                    style="cursor: pointer;"
                    size="30px"
            >
                {{ play ? 'pause' : 'play_arrow'}}
            </v-icon>
            <div style="color: white; flex-basis: 50px;" class="body-1">{{ time }}</div>
            <v-progress-linear @click="changeVideoTrack" class="ml-1" color="accent" v-model="val"></v-progress-linear>
            <v-icon @click="fullScreen()" dark>fullscreen</v-icon>
            <v-menu
                    offset-x
                    :close-on-content-click="false"
                    :nudge-width="200"
                    v-model="menu"
            >
                <v-icon slot="activator" dark>settings</v-icon>
                <v-card>
                    <v-list>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>Настройки</v-list-tile-title>
                                <v-list-tile-sub-title>Настройте как вам удобно</v-list-tile-sub-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-btn
                                        icon
                                >
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                    <v-divider></v-divider>
                    <!--<v-list>-->
                        <!--<v-list-tile>-->
                            <!--<v-list-tile-action>-->
                                <!--<v-switch v-model="message" color="purple"></v-switch>-->
                            <!--</v-list-tile-action>-->
                            <!--<v-list-tile-title>Enable messages</v-list-tile-title>-->
                        <!--</v-list-tile>-->
                        <!--<v-list-tile>-->
                            <!--<v-list-tile-action>-->
                                <!--<v-switch v-model="hints" color="purple"></v-switch>-->
                            <!--</v-list-tile-action>-->
                            <!--<v-list-tile-title>Enable hints</v-list-tile-title>-->
                        <!--</v-list-tile>-->
                    <!--</v-list>-->
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="accent" flat @click="menu = false">Ок</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-toolbar>
    </v-card>
</template>
<script>
    import { mapGetters } from 'vuex'
    export default {
        name: 'v-video',
        data() {
            return {
                val: 0,
                play: false,
                time: '00:00',
                menu: false,
                video: null,
                m: false
            }
        },
        mounted() {
            const { video } = this.$refs
            this.video = video
            video.addEventListener('timeupdate', () => {
                if (!this.m) {
                    let currentTime = video.currentTime
                    let minutes = Math.floor( currentTime / 60 )
                    this.time =  `${minutes > 9 ? '' : 0}${minutes}:${currentTime - minutes > 9 ? '' : 0}${Math.floor(currentTime - minutes)}`
                    this.val = (currentTime * 100) / video.duration
                }
            })
            video.addEventListener('ended', () => {
                this.play = false
                this.time = '00:00'
            })
        },
        props: {
            props: Object
        },
        computed: {
            ...mapGetters({
                course: 'course'
            })
        },
        methods: {
            changePlay() {
                if (this.play) {
                    this.video.pause()
                    this.play = false
                } else {
                    this.video.play()
                    this.play = true
                }
            },
            fullScreen() {
                if (this.video.requestFullscreen) {
                    this.video.requestFullscreen();
                } else if (this.video.mozRequestFullScreen) {
                    this.video.mozRequestFullScreen();
                } else if (this.video.webkitRequestFullscreen) {
                    this.video.webkitRequestFullscreen();
                }
            },
            changeVideoTrack(e) {
                let rect = e.target.getBoundingClientRect()
                let a = rect.x
                let b = e.screenX
                let total = rect.width
                let to = (b - a) * 100 / total
                this.m = true
                this.video.pause()
                this.video.currentTime = (this.video.duration * to) / 100
                this.video.play()
            }
        }
    }
</script>

<style lang="sass" scoped>
    .icon
        cursor: pointer
        margin: 5px
</style>
