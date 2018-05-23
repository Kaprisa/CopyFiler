<template>
    <g>
        <path
                v-for="i, index in paths"
                :key="`path_${index}`"
                :d="i"
                stroke-width="0.1"
                :stroke="indicators[index].color"
                fill="transparent"
        ></path>
    </g>
</template>

<script>
    export default {
        name: 'indicators',
        data() {
            return {
                paths: []
            }
        },
        props: {
            indicators: {
                type: Array,
                default: null
            },
            coordinates: Array,
            zero: Number
        },
        watch: {
            indicators(v) {
                this.paths = []
                v.forEach(i => this.add(i.name))
            }
        },
        methods: {
            add(p) {
                let path=`M 0 ${100 - this.zero} `
                this.coordinates.forEach(e => {
                    path += e[p] ? `L ${e.x} ${e[p] - this.zero} ` : `M ${e.x} ${100 - this.zero} `
                })
                this.paths.push(path)
            },
        }
    }
</script>