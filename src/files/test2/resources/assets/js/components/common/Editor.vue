<template>
    <v-dialog width="50%" v-model="dialog">
        <v-btn slot="activator" color="lime" dark>{{ buttonText }}</v-btn>
        <v-card>
            <div class="layout row mb-1" style="height: 36px;">
                <v-btn-toggle v-model="action">
                    <v-btn @click="command('undo')" flat>
                        <v-icon>undo</v-icon>
                    </v-btn>
                    <v-btn @click="command('redo')" flat>
                        <v-icon>redo</v-icon>
                    </v-btn>
                </v-btn-toggle>

                <v-btn-toggle v-model="action">
                    <v-btn @click="command('copy')" flat>
                        <v-icon>content_copy</v-icon>
                    </v-btn>
                    <v-btn @click="command('cut')" flat>
                        <v-icon>content_cut</v-icon>
                    </v-btn>
                    <v-btn @click="command('paste')" flat>
                        <v-icon>content_paste</v-icon>
                    </v-btn>
                </v-btn-toggle>

                <v-btn-toggle multiple v-model="format">
                    <v-btn @click="command('bold')" flat>
                        <v-icon>format_bold</v-icon>
                    </v-btn>
                    <v-btn @click="command('italic')" flat>
                        <v-icon>format_italic</v-icon>
                    </v-btn>
                    <v-btn @click="command('underline')" flat>
                        <v-icon>format_underlined</v-icon>
                    </v-btn>
                    <v-btn>
                        <v-tooltip top>
                            <input value="#000000" style="width: 36px; height: 36px; border: none;" class="color-input" slot="activator" type="color" v-model="foreColor" @change="command('foreColor', foreColor)">
                            Цвет шрифта
                        </v-tooltip>
                    </v-btn>
                    <v-btn>
                        <v-tooltip top>
                            <input value="#ffffff" style="width: 36px; height: 36px; border: none;" class="color-input" slot="activator" type="color" v-model="hiliteColor" @change="e => command('hiliteColor', hiliteColor)">
                            Цвет выделения
                        </v-tooltip>
                    </v-btn>
                </v-btn-toggle>

                <v-btn-toggle mandatory v-model="align">
                    <v-btn @click="command('justifyLeft')" flat>
                        <v-icon>format_align_left</v-icon>
                    </v-btn>
                    <v-btn @click="command('justifyCenter')" flat>
                        <v-icon>format_align_center</v-icon>
                    </v-btn>
                    <v-btn @click="command('justifyRight')" flat>
                        <v-icon>format_align_right</v-icon>
                    </v-btn>
                    <v-btn @click="command('justifyFull')" flat>
                        <v-icon>format_align_justify</v-icon>
                    </v-btn>
                </v-btn-toggle>

                <v-select
                        :items="fonts"
                        v-model="font"
                        label="Шрифт"
                        autocomplete
                        style="width: 200px;"
                        dense
                        flat
                        solo
                        @change="command('fontName', font)"
                ></v-select>

            </div>
            <div class="layout row" style="height: 36px;">
                <v-btn-toggle mandatory v-model="insert">
                    <v-btn @click="command('insertImage', prompt('Введите URL изображения', ''))" flat>
                        <v-icon>insert_chart</v-icon>
                    </v-btn>
                    <v-btn @click="command('createLink', prompt('Введите URL', 'https://'))" flat>
                        <v-icon>insert_link</v-icon>
                    </v-btn>
                    <v-btn @click="command('insertImage', prompt('Введите URL изображения', ''))" flat>
                        <v-icon>insert_photo</v-icon>
                    </v-btn>
                </v-btn-toggle>
                <v-btn-toggle mandatory v-model="table">
                    <v-btn @click="command('insertUnorderedList')" flat>
                        <v-icon>fa-list-ul</v-icon>
                    </v-btn>
                    <v-btn @click="command('insertOrderedList')" flat>
                        <v-icon>fa-list-ol</v-icon>
                    </v-btn>
                </v-btn-toggle>
                <v-btn-toggle mandatory v-model="ss">
                    <v-btn @click="command('subscript')" flat>
                        <v-icon>fa-subscript</v-icon>
                    </v-btn>
                    <v-btn @click="command('superscript')" flat>
                        <v-icon>fa-superscript</v-icon>
                    </v-btn>
                    <v-btn @click="command('insertHorizontalRule')" flat>
                        hr
                    </v-btn>
                    <v-btn @click="command('insertParagraph')" flat>
                        <v-icon>fa-paragraph</v-icon>
                    </v-btn>
                </v-btn-toggle>
                <v-btn-toggle v-model="a">
                    <v-btn flat @click="command('insertHTML')">
                        <v-tooltip bottom>
                            <span slot="activator">f(x)</span>
                            Вставить формулу
                        </v-tooltip>
                    </v-btn>
                    <v-btn flat>
                        <v-icon>fa-code</v-icon>
                    </v-btn>
                    <v-btn style="padding: 0;">
                        <v-select
                                :items="['h1', 'h2', 'h3', 'h4', 'h5', 'h6']"
                                v-model="h"
                                label="H"
                                dense
                                flat
                                solo
                                @change="command('formatBlock', h)"
                        ></v-select>
                    </v-btn>
                </v-btn-toggle>
            </div>
            <iframe
                    name="frame"
                    ref="editor"
                    :style="`width: 100%; height: 50vh;`"
                    frameborder="0"
            ></iframe>
            <v-divider/>
            <v-card-actions>
                <v-spacer/>
                <v-btn color="lime" dark @click="change()">Ок</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    function debounce(f, ms) {

        let timer = null;

        return function (...args) {
            const onComplete = () => {
                f.apply(this, args);
                timer = null;
            }

            if (timer) {
                clearTimeout(timer);
            }

            timer = setTimeout(onComplete, ms);
        };
    }
    export default {
        name: 'v-editor',
        data() {
            return {
                format: [0, 1, 2],
                font: 'Open Sans',
                align: 0,
                action: 0,
                insert: 0,
                ss: 0,
                table: 0,
                h: 'h1',
                a: 0,
                dialog: false,
                document: null,
                foreColor: '#000000',
                hiliteColor: '#000000',
                fonts: [
                    'Roboto Condensed',
                    'Exo',
                    'Roboto',
                    'Roboto Mono',
                    'Open Sans',
                    'Lato',
                    'Slabo',
                    'Tajawal',
                    'Raleway',
                    'Open Sans Condensed',
                    'Merriweath',
                    'Poppins',
                    'Lora',
                    'Arimo',
                    'Exo 2',
                    'Oxygen',
                    'Cabin'
                ]
            }
        },
        props: {
            buttonText: String,
            value: {
                type: String,
                default: ''
            }
        },
        mounted() {
            const d = this.$refs.editor.contentWindow.document
            this.document = d
            d.open()
            d.write(
                '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">'+
                '<html><head><script src=\'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML\'><\/script><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo|Cabin|Exo|Exo+2|Lato|Lora|Merriweather|Open+Sans|Open+Sans+Condensed:300|Oxygen|Poppins|Raleway|Roboto|Roboto+Condensed|Roboto+Mono|Slabo+27px|Tajawal"><style type="text/css">'+
                'body { font-size: 18px; }'+
                'img { max-width: 50%; height: auto; }'+
                '<\/style><\/head><body>' + this.value + '<\/body><\/html>'
            );
            d.close()
            d.designMode = 'On'
            this.command('insertHTML', this.value)
            this.command('fontName', 'Open Sans')
        },
        methods: {
            command(c, b = null) {
                this.document.execCommand(c, false, b)
                const d = this.document
                debounce(function (e) {
                    d.execCommand('insertHTML', false, '<div id="editor-output">{\\sqrt{2}^\\sqrt{2}}^\\sqrt{2} = 2 \\\\ \\sqrt{2}^{2\\log_{2}{3}} = 3</div>')
                    this.$nextTick(function () {
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub, "editor-output"])
                    })
                })

            },
            change() {
                this.$emit('change', this.document.body.innerHTML)
                this.dialog = false
            },
            prompt(a, b) {
                return prompt(a, b)
            }
        }
    }
</script>