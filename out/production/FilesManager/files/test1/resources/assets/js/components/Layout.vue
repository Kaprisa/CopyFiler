<template>
    <div>
        <v-navigation-drawer
                fixed
                v-model="drawer"
                right
                app
                :dark="user.theme && user.theme.dark === 1"
        >
            <v-list dense>
                <v-list-tile @click="$router.push('/')">
                    <v-list-tile-action>
                        <v-icon>home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Home</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="$router.push('/profile')">
                    <v-list-tile-action>
                        <v-icon>account_circle</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Профиль</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile v-if="user.is_admin" @click="$router.push('/admin')">
                    <v-list-tile-action>
                        <v-icon>widgets</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Панель администрирования</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="$router.push(`/courses`)">
                    <v-list-tile-action>
                        <v-icon>graphic_eq</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Курсы</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="$router.push(`/html`)">
                    <v-list-tile-action>
                        <v-icon>code</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Html</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <!-- <v-list-group
                         prepend-icon="graphic_eq"
                         no-action
                 >
                     <v-list-tile slot="activator">
                         <v-list-tile-content>
                             <v-list-tile-title>Статистика</v-list-tile-title>
                         </v-list-tile-content>
                     </v-list-tile>
                     <v-list-tile @click="$router.push(`/analysis/chart`)">
                         <v-list-tile-content>
                             <v-list-tile-title>График</v-list-tile-title>
                         </v-list-tile-content>
                         <v-list-tile-action>
                             <v-icon>bubble_chart</v-icon>
                         </v-list-tile-action>
                     </v-list-tile>
                 </v-list-group>-->
            </v-list>
        </v-navigation-drawer>
        <v-toolbar color="primary" dark scroll-off-screen app>
            <v-spacer/><!--teal lighten-3-->
            <v-toolbar-title>Quadric</v-toolbar-title>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"/>
        </v-toolbar>
        <v-content>
            <v-container fluid full-height>
                <router-view/>
            </v-container>
        </v-content>
        <v-menu
                offset-x
                :close-on-content-click="false"
                :nudge-width="200"
                v-model="menu"
        >
            <v-btn
                    color="accent"
                    dark
                    fixed
                    bottom
                    right
                    fab
                    slot="activator"
            >
                <v-icon>settings</v-icon>
            </v-btn>
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
                <theme-generator></theme-generator>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" flat @click="menu = false">Ок</v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
        <v-footer color="primary" app>
            <v-spacer/>
            <span class="white--text mr-2">Made by Kseniya &copy; 2018</span>
        </v-footer>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import ThemeGenerator from './common/ThemeGenerator'

    export default {
        name: 'layout',
        data: () => ({
            drawer: true,
            menu: false,
            theme: false,
            showThemes: false
        }),
        components: {
           ThemeGenerator
        },
        mounted() {
            this.getUser()
        },
        watch: {
          loadingUser(v) {
              if (!v) {
                  if (this.user.theme) {
                      this.$vuetify.theme = this.user.theme.colors
                  }
              }
          }
        },
        computed: {
            ...mapGetters({
                user: 'user',
                loadingUser: 'loadingUser'
            })
        },
        methods: {
            ...mapActions([
                'getUser'
            ])
        }
    }
</script>