<template>
    <div>
        <v-navigation-drawer
                fixed
                :clipped="$vuetify.breakpoint.lgAndUp"
                app
                v-model="drawer"
        >
            <v-list dense>
                <template v-for="item in items">
                    <v-layout
                            row
                            v-if="item.heading"
                            align-center
                            :key="item.heading"
                    >
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                        <v-flex xs6 class="text-xs-center">
                            <a class="body-2 black--text">EDIT</a>
                        </v-flex>
                    </v-layout>
                    <v-list-group
                            v-else-if="item.children"
                            v-model="item.model"
                            :key="item.text"
                            :prepend-icon="item.model ? item.icon : item['icon-alt']"
                            append-icon=""
                    >
                        <v-list-tile slot="activator">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                                v-for="(child, i) in item.children"
                                :key="i"
                                @click="callAction(child.component)"
                        >
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ child.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else @click="$router.push(item.link)" :key="item.text">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
                <v-list-group
                        prepend-icon="gamepad"
                        no-action
                >
                    <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>База данных</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile @click="$router.push(`/admin/db/sql`)">
                        <v-list-tile-content>
                            <v-list-tile-title>Мастер запросов</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-icon>code</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                color="teal lighten-3"
                dark
                app
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                fixed
        >
            <v-toolbar-title class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-sm-and-down">Панель администрирования</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>apps</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <router-view/>
        </v-content>
        <!--<v-dialog width="50vw" v-model="action.dialog">-->
            <!--<v-component :is="action.component" @error="processError" @success="processSuccess"></v-component>-->
        <!--</v-dialog>-->
        <snackbar :options="snackbar"></snackbar>
    </div>
</template>

<script>
    import Snackbar from '../components/common/Snackbar'
    import { mapActions } from 'vuex'

    export default {
        components: {
            Snackbar
        },
        data: () => ({
            dialog: false,
            drawer: null,
            snackbar: {},
            // action: {
            //     dialog: false,
            //     component: 'task'
            // },
            items: [
                { icon: 'keyboard_arrow_left', text: 'Назад в академию', link: '/' },
                { icon: 'contacts', text: 'Пользователи', link: '/admin/users' },
                { icon: 'history', text: 'История', link: '/admin/history' },
                { icon: 'add', text: 'Создать курс', link: '/courses/add' },
                { icon: 'spellcheck', text: 'Домашние работы', link: '/admin/homework' },
                { icon: 'today', text: 'Задачи', link: '/admin/tasks' },
                { icon: 'palette', text: 'Темы', link: '/admin/themes' },
                { icon: 'photo', text: 'Редактор изображений', link: '/admin/image' },
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     text: 'Задачи',
                //     model: true,
                //     children: [
                //         { icon: 'add', text: 'Создать задачу', component: 'task' }
                //     ]
                // },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'keyboard_arrow_down',
                    text: 'Больше',
                    model: false,
                    children: [
                        { text: 'Something' },
                    ]
                },
                { icon: 'settings', text: 'Настройки', link: '/admin/settings' },
                { icon: 'grade', text: 'Роли', link: '/admin/roles' },
                { icon: 'perm_data_setting', text: 'Разрешения', link: '/admin/permissions' },
                { icon: 'folder', text: 'Файлы', link: '/admin/files' }
            ]
        }),
        mounted() {
            this.getUsers()
        },
        methods: {
            callAction(component) {
                if (component) {
                    // this.action = {
                    //     dialog: true,
                    //     component
                    // }
                }
            },
            ...mapActions([
                'getUsers'
            ]),
            processError({ response }) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
            },
            processSuccess(msg, res, cb = null) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
                if (cb) cb()
            }
        }
    }
</script>


