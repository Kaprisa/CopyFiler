<template>
    <v-card class="mb-3">
        <v-card-title>
            <h3>Таблицы</h3>
        </v-card-title>
        <v-container fluid grid-list-md>
            <v-data-iterator
                    content-tag="v-layout"
                    row wrap
                    :items="Object.keys(this.tables).map(key => ({ name: key, values: this.tables[key] }))"
                    :rows-per-page-items="rowsPerPageItems"
                    :pagination.sync="pagination"
                    :loading="loading"
            >
                <v-flex slot="item" slot-scope="props" xs12 sm6 md4 lg3>
                    <v-card style="height: 300px; overflow-y: auto;">
                        <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                        <v-divider></v-divider>
                        <v-list dense>
                            <v-list-tile v-for="val in props.item.values" :key="props.item.name + val.name">
                                <v-list-tile-content>{{ val.name }}</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ val.type }}</v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-card>
                </v-flex>
                <template slot="no-data">
                    <div></div>
                </template>
            </v-data-iterator>
        </v-container>
    </v-card>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        name: 'tables',
        computed: mapGetters({
            tables: 'tables',
            loading: 'loadingTables'
        }),
        data () {
            return {
                rowsPerPageItems: [4, 8, 12],
                pagination: {
                    rowsPerPage: 4
                }
            }
        }
    }
</script>