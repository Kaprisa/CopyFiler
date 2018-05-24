<template>
        <!--<v-select
                label="Получатели"
                :items="users"
                v-model="receivers"
                item-text="name"
                item-value="name"
                multiple
                chips
                autocomplete
                class="mb-3"
        >
            <template slot="selection" slot-scope="data">
                <v-chip
                        close
                        @input="data.parent.selectItem(data.item)"
                        :selected="data.selected"
                        class="chip&#45;&#45;select-multi"
                        :key="data.item.email"
                >
                    <v-avatar>
                        <img :src="`/uploads/photos/${data.item.avatar}`">
                    </v-avatar>
                    {{ data.item.name }}
                </v-chip>
            </template>
            <template slot="item" slot-scope="data">
                <v-list-tile-avatar>
                    <img :src="`/uploads/photos/${data.item.avatar}`"/>
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </v-select>-->
    <v-select
            :label="label"
            :items="users"
            v-model="user"
            item-text="name"
            item-value="id"
            autocomplete
            @input="$emit('change', user)"
    >

        <template slot="item" slot-scope="data">
            <v-list-tile-avatar>
                <img :src="`/uploads/users/${data.item.avatar}`"/>
            </v-list-tile-avatar>
            <v-list-tile-content>
                <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-select>

</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        name: 'people-select',
        data() {
            return {
                user: null
            }
        },
        computed: mapGetters({
            users: 'users'
        }),
        props: {
            label: null
        }
    }
</script>