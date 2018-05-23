<template>
    <v-menu
            lazy
            :close-on-content-click="false"
            v-model="picker"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-right="40"
            max-width="210px"
            min-width="210px"
    >
        <v-text-field
                slot="activator"
                :label="label"
                v-model="color"
                prepend-icon="color_lens"
                class="input-group--focused"
                :color="color"
                readonly
        />
        <div>
            <v-layout row v-for="c, name in colors" :key="name">
                <v-flex
                        v-for="i, s in c"
                        :key="s"
                        :style="`height: 15px; background-color: ${i};`"
                        @click="changeColor(name, i)">
                </v-flex>
            </v-layout>
        </div>
    </v-menu>
</template>

<script>
    import colors from 'vuetify/es5/util/colors'
    export default {
        name: 'color-picker',
        data() {
            return {
                colors,
                color: this.vcolor,
                picker: null
            }
        },
        props: {
            label: String,
            vcolor: String
        },
        methods: {
            changeColor(color, i) {
                this.color = `${color.replace(/[A-Z]/, (s) => `-${s.toLowerCase()}`)}`
                this.$emit('change', i)
            }
        }
    }
</script>