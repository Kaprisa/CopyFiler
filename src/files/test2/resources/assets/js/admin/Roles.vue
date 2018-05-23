<template>
    <v-container style="position: relative;">
        <v-btn
                color="pink"
                small
                absolute
                right
                top
                dark
                fab
                class="mt-4"
                slot="activator"
                @click="role = {}; dialog=true;"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ role.id ? 'Редактировать' : 'Создать'}} роль</span>
                </v-card-title>
                <v-card-text>
                    <v-text-field
                            prepend-icon="visibility_off"
                            placeholder="Имя"
                            v-model="role.name"
                    ></v-text-field>
                    <v-text-field
                            prepend-icon="visibility"
                            v-model="role.display_name"
                            placeholder="Видимое имя"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn flat @click="save">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-data-table
                :headers="headers"
                :items="roles"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.display_name }}</td>
                <td>{{ props.item.created_at }}</td>
                <td class="justify-center layout px-0">
                    <v-btn icon @click="edit(props.item)">
                        <v-icon color="teal">edit</v-icon>
                    </v-btn>
                    <v-btn icon @click="remove(props.item)">
                        <v-icon color="red">delete</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>
        <snackbar :options="snackbar"></snackbar>
    </v-container>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex'
    import Snackbar from '../components/common/Snackbar'
    import axios from 'axios'

    export default {
        components: {
            Snackbar
        },
        data() {
            return {
                dialog: false,
                snackbar: {},
                role: {},
                headers: [
                    {
                        text: 'Название',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Название для показа',
                        align: 'left',
                        sortable: false,
                        value: 'display_name'
                    },
                    {
                        text: 'Дата создания',
                        align: 'left',
                        value: 'created_at'
                    },
                    { text: 'Действия', align: 'left', value: 'name', sortable: false }
                ]
            }
        },
        mounted() {
            this.getRoles()
        },
        computed: {
            ...mapGetters({
                roles: 'roles'
            })
        },
        methods: {
            ...mapActions([
                'getRoles'
            ]),
            edit(item) {
                this.role = item
                this.dialog = true
            },

            remove(item) {
                if (!item.id) return
                axios.delete(`/api/roles/${item.id}`).then(res => {
                    this.processSuccess('Роль успешно удалена.')
                    this.roles.splice(this.roles.indexOf(item), 1)
                }).catch(this.processError)
            },
            save() {
                this.dialog = false
                axios.post('/api/roles/save', this.role).then(res => {
                    if (!this.role.id) this.roles.push(res.data)
                    this.processSuccess('Роль успешно сохранена!')
                }).catch(this.processError)
            },
            processError({response}) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
            },
            processSuccess(msg) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
            }
        }
    }
</script>