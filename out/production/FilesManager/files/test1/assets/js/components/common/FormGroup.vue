<template>
    <v-flex xs12 md6 class="elevation-4 pa-0 mb-3">
        <v-toolbar :color="random(colors)" class="mb-2">
            <v-toolbar-title class="white--text">{{ name }}</v-toolbar-title>
        </v-toolbar>
        <div class="pa-3">
            <v-select
                    v-model="selected"
                    :label="label"
                    chips
                    multiple
                    autocomplete
                    :items="items"
                    item-text="name"
                    item-value="identifier"
                    @input="$emit('changeSelected', selected)"
            >
                <template slot="selection" slot-scope="data">
                    <v-chip
                            @input="data.parent.selectItem(data.item)"
                            class="chip--select-multi"
                            :selected="data.selected"
                            :disabled="data.disabled"
                            :key="JSON.stringify(data.item)"
                    >
                        <v-avatar :class="random(colors)">{{ data.item['name'].slice(0, 1).toUpperCase() }}</v-avatar>
                        {{ data.item['name'] }}
                    </v-chip>
                </template>
            </v-select>
            <v-slider
                    color="teal"
                    :hint="hint"
                    min="1"
                    max="100"
                    thumb-label
                    v-model="percent"
                    @input="$emit('changePercent', percent)"
            />
        </div>
    </v-flex>
</template>

<script>
    import { random } from '../../helpers/random'
    export default {
       name: 'form-group',
        data() {
           return {
               percent: 0,
               selected: [],
               colors: ['amber', 'blue', 'orange', 'pink', 'purple', 'red', 'teal', 'cyan']
           }
        },
       props: {
           items: Array,
           label: "",
           hint: "",
           name: ""
       },
        methods: {
           random
        }
    }
</script>