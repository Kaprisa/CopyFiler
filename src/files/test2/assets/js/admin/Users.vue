<template>
    <v-container>
        <v-data-table
                :headers="headers"
                :items="users"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                    <span v-for="r in props.item.roles" :key="`${props.item.id} ${r.name}`">{{ r.display_name }}<br></span>
                </td>
                <td>{{ props.item.points }}</td>
                <td>{{ props.item.created_at }}</td>
            </template>
        </v-data-table>
        <v-dialog v-model="dialog" width="800px">
            <v-card>
                <v-card-title
                        class="grey lighten-4 py-4 title"
                >
                    Создать пользователя
                </v-card-title>
                <v-container grid-list-sm class="pa-4">
                    <v-layout row wrap>
                        <v-flex xs12 align-center justify-space-between>
                            <v-layout align-center>
                                <v-avatar size="40px" class="mr-3">
                                    <img
                                            src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png"
                                            alt=""
                                    >
                                </v-avatar>
                                <v-text-field
                                        placeholder="Имя"
                                ></v-text-field>
                            </v-layout>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field
                                    prepend-icon="business"
                                    placeholder="Company"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field
                                    placeholder="Job title"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                    prepend-icon="mail"
                                    placeholder="Email"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                    type="tel"
                                    prepend-icon="phone"
                                    placeholder="(000) 000 - 0000"
                                    mask="phone"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                    prepend-icon="notes"
                                    placeholder="Notes"
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-card-actions>
                    <v-btn flat color="primary">More</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="dialog = false">Cancel</v-btn>
                    <v-btn flat @click="dialog = false">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
    import { mapGetters } from 'vuex'
    export default {
        data() {
            return {
                dialog: false,
                headers: [
                    {
                        text: 'Имя',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'E-Mail',
                        align: 'left',
                        sortable: false,
                        value: 'email'
                    },
                    {
                        text: 'Роли',
                        align: 'left',
                        sortable: false,
                        value: 'email'
                    },
                    {
                        text: 'Баллы',
                        align: 'left',
                        value: 'points'
                    },
                    {
                        text: 'Дата регистрации',
                        align: 'left',
                        value: 'created_at'
                    }
                ]
            }
        },
        computed: {
            ...mapGetters({
                users: 'users'
            })
        }
    }
</script>