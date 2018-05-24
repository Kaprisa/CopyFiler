<template>
    <v-card class="elevation-12">
        <v-card-media src="/images/new_york.jpeg" height="15vh">
        </v-card-media>
            <v-layout column class="px-3 py-2">
                <v-text-field
                        placeholder="Название"
                        v-model="task.name"
                ></v-text-field>
                <people-select @change="id => task.executor_id = id" label="Ответственный" />
                <v-text-field
                        placeholder="Описание"
                        v-model="task.description"
                        textarea
                ></v-text-field>
                <v-select
                        v-model="task.tags"
                        label="Теги"
                        chips
                        tags
                        clearable
                        :items="tags"
                >
                    <template slot="selection" slot-scope="data">
                        <v-chip
                                @input="data.parent.selectItem(data.item)"
                                class="chip--select-multi"
                                :selected="data.selected"
                                :key="JSON.stringify(data.item)"
                        >
                            <v-avatar class="accent">{{ data.item.slice(0, 1).toUpperCase() }}</v-avatar>
                            {{ data.item }}
                        </v-chip>
                    </template>
                </v-select>
            </v-layout>
        <v-card-actions>
            <v-spacer/>
            <v-btn @click="saveTask" color="teal" dark>Сохранить</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import axios from 'axios'
    import PeopleSelect from '../../components/common/PeopleSelect'

    export default {
        name: 'task',
        components: {
          PeopleSelect
        },
        data() {
            return {
                tags: [],
                task: {
                    tags: [],
                }
            }
        },
        props: {
           category: Number
        },
        methods: {
            saveTask() {
                axios.post(`/api/tasks/${this.category}/add`, this.task)
                    .then(res => this.$emit('success', 'Задача успешно добавлена!', res.data))
                    .catch(err => this.$emit('error', err))
            }
        }
    }

</script>