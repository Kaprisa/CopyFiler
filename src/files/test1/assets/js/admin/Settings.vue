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
                @click="item = {}; dialog=true;"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ item.id ? 'Редактировать' : 'Создать'}} настройки</span>
                </v-card-title>
                <v-card-text>
                    <v-select
                            :items="fieldTypes"
                            label="Тип поля"
                            v-model="item.field_type"
                    ></v-select>
                    <v-text-field
                            label="Название"
                            v-model="item.name"
                    ></v-text-field>
                    <photo-uploader
                            label="Фото"
                            :fileName="item.value"
                            @uploadFile="(file) => item.value = file.name"
                            v-if="item.field_type === 'image'"
                    />
                    <v-text-field
                            v-else
                            label="Значение"
                            v-model="item.value"
                            :textarea="item.field_type === 'textarea'"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="save">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-data-table
                :headers="headers"
                :items="settings"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.value }}</td>
                <td class="justify-center layout px-0">
                    <v-btn icon @click="item = props.item; dialog = true;">
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
    import { mapActions, mapGetters } from 'vuex'
    import PhotoUploader from '../components/common/PhotoUploader'
    import Snackbar from '../components/common/Snackbar'
    import axios from 'axios'

    export default {
        components: {
            PhotoUploader,
            Snackbar
        },
        data: () => ({
            dialog: false,
            snackbar: {},
            fieldTypes: ['input', 'textarea', 'image'],
            headers: [
                { text: 'Название', align: 'left', value: 'name', sortable: false },
                { text: 'Значение', align: 'left', value: 'value', sortable: false },
                { text: 'Действия', align: 'left', value: 'name', sortable: false }
            ],
            item: {}
        }),
        mounted() {
           this.getSettings()
        },
        computed: {
            ...mapGetters({
                settings: 'settings'
            })
        },
        methods: {

            ...mapActions([
                'getSettings'
            ]),

            remove(item) {
                if (!item.id) return
                axios.delete(`/api/settings/${item.id}`).then(res => {
                    this.processSuccess('Настройки успешно удалены.')
                    this.settings.splice(this.settings.indexOf(item), 1)
                }).catch(this.processError)
            },
            save() {
                axios.post('/api/settings/save', this.item).then(res => {
                    if (!this.item.id) this.settings.push(res.data)
                    this.processSuccess('Настройки успешно сохранены!')
                }).catch(this.processError)
            },
            processError({ response }) {
                const errors = response && response.data && response.data.errors
                this.snackbar = {
                    visible: true,
                    text: errors ? errors[Object.keys(errors)[0]] : 'Ошибка сервера',
                    error: true
                }
                this.dialog = false
            },
            processSuccess(msg, cb = null) {
                this.snackbar = {
                    visible: true,
                    text: msg,
                    error: false
                }
                this.dialog = false
                if (cb) cb()
            }
        }
    }
</script>