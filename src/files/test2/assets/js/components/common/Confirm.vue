<template>
    <v-dialog v-model="dialog" persistent max-width="290">
        <v-card>
            <v-card-title class="headline">Вы уверены?</v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click="dialog = false">Отмена</v-btn>
                <v-btn color="green darken-1" flat @click="$emit('ok'); dialog = false">Да</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    export default {
        data() {
            return {
                dialog: false
            }
        },
        watch: {
            visible(v) {
               this.dialog = v
            }
        },
        props: {
          visible: Boolean
        }
    }
</script>