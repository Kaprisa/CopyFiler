<template>
    <v-container>
       <!-- <tables/>-->
        <query-executor
            url="/api/sql/query"
            theme="light"
            mode="text/x-mysql"
            queryName="SQL"
            example="SELECT * FROM users"
        />
        <query-executor
                url="/api/php/query"
                theme="dark"
                mode="text/x-php"
                queryName="PHP"
                text="@return array $data"
                example="$data = App\User::all();"
        />
    </v-container>
</template>
<script>
    /*import { mapGetters } from 'vuex'*/
    import QueryExecutor from "./QueryExecutor"
   /* import Tables from './Tables'*/
    export default {
        components: {
            QueryExecutor,
            /*Tables*/
        },
        data () {
            return {}
        },
        props: {
            breadcrumbs: Array,
        },
        /*computed: mapGetters({
            tables: 'tables',
        }),*/
    }
</script>