<template>
    <v-app light>
        <v-toolbar class="white">
            <!--<img src="/images/logo.png" alt="Quadric.edu" height="60px">-->
            <div class="logo pt-1">
                <div class='glitch-window'><h1 class="glitched">Quadric</h1></div>
                <h1 class="glitched">Quadric</h1>
            </div>
            <v-spacer/>
            <v-btn color="teal" dark href="/academy">Вход</v-btn>
        </v-toolbar>
        <v-content>
            <section>
                <v-parallax src="/images/bg6.jpeg" height="600">
                    <v-layout
                            column
                            align-center
                            justify-center
                            class="white--text"
                    >
                        <img src="/svg/desctop.svg" alt="Quadric.edu" height="200">
                        <h1 class="white--text mb-2 display-1 text-xs-center">Образование online</h1>
                        <div class="subheading mb-3 text-xs-center">Всегда знай, что ты лучший!</div>
                        <div class="subheading mb-3 text-xs-center">Ты способен знать все и обо всем!</div>
                        <div class="subheading mb-3 text-xs-center">А мы тебе поможем!</div>
                        <v-btn
                                class="blue lighten-2 mt-5"
                                dark
                                large
                                href="/academy"
                        >
                            Готов? Поехали!
                        </v-btn>
                    </v-layout>
                </v-parallax>
            </section>
            <section>
                <v-container grid-list-md class="mb-5">
                    <v-layout row wrap>
                        <v-flex d-flex xs12 sm6 md4>
                            <v-card color="blue" dark>
                                <v-card-title primary class="title">Что ты получаешь?</v-card-title>
                                <v-list two-line class="cyan">
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Постоянная поддержка</v-list-tile-title>
                                            <v-list-tile-sub-title>Мы всегда ответим на все твои вопросы
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Учеба в любое удобное время</v-list-tile-title>
                                            <v-list-tile-sub-title>Учись в удобной для тебя обстановке
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Полезное времяпровождение</v-list-tile-title>
                                            <v-list-tile-sub-title>Научись тратить свое время с умом
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Постоянные обновления</v-list-tile-title>
                                            <v-list-tile-sub-title>Наш сайт становится с каждым днем все лучше и
                                                лучше!
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-flex>
                        <v-flex d-flex xs12 md4>
                            <v-layout row wrap>
                                <v-flex d-flex>
                                    <v-card color="amber" dark>
                                        <v-card-media height="100%" src="/images/top/features_1.png">
                                            <v-container fill-height fluid>
                                                <v-layout fill-height>
                                                    <v-flex xs12 align-end flexbox>
                                                        <span class="headline">Учеба без границ</span>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-media>
                                    </v-card>
                                </v-flex>
                                <v-flex d-flex>
                                    <v-card color="green" dark>
                                        <v-card-media height="100%" src="/images/top/features_2.png">
                                            <v-container fill-height fluid>
                                                <v-layout fill-height>
                                                    <v-flex xs12 align-end flexbox>
                                                        <span class="headline">Наблюдай за своим прогрессом!</span>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-media>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex d-flex xs12 md4>
                            <v-layout row wrap>
                                <v-flex d-flex>
                                    <v-card color="green" dark>
                                        <v-card-media height="100%" src="/images/top/features_3.png">
                                            <v-container fill-height fluid>
                                                <v-layout fill-height>
                                                    <v-flex xs12 align-end flexbox>
                                                        <span class="headline">С нами ты достигнешь большего!</span>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-media>
                                    </v-card>
                                </v-flex>
                                <v-flex d-flex>
                                    <v-card color="amber" dark>
                                        <v-card-media height="100%" src="/images/top/features_4.png">
                                            <v-container fill-height fluid>
                                                <v-layout fill-height>
                                                    <v-flex xs12 align-end flexbox>
                                                        <span class="headline">Большое разнообразие тем!</span>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-media>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-container>
            </section>
            <section>
                <v-container grid-list-md fill-height>
                    <v-layout row wrap fill-height>
                        <v-flex xs5>
                            <v-card class="pt-3" height="100%" color="red">
                                <v-card-title class="white--text">
                                    <h1 class="display-1 mb-4">Мы разработали отличные образовательные программы!</h1>
                                </v-card-title>
                                <v-card-text class="white--text body-1">
                                    Данный проект направлен на то чтобы студенты и школьники могли легко получать доступ к самым лучшим учебным материалам.
                                    Для того чтобы найти что-то по-настоящему полезное, нужно потратить много времени, просматривая разные web-страницы и учебники, а время - очень ценная вещь.
                                    Поэтому здесь будет собрано все что нужно - лекции, примеры. Также, вы сможете посмотреть видео-уроки и пройти интересные тесты.
                                </v-card-text>
                                <div class="text-xs-center mt-3">
                                    <v-btn
                                            class="blue lighten-2"
                                            dark
                                            large

                                    >
                                        Узнай больше!
                                    </v-btn>
                                </div>
                            </v-card>
                        </v-flex>
                        <v-flex xs7>
                            <v-carousel>
                                <v-carousel-item v-for="item, i in gallery" :key="i">
                                    <img width="100%" height="100%" :src="`/images/gallery/${item}`" alt="">
                                </v-carousel-item>
                            </v-carousel>
                        </v-flex>
                    </v-layout>
                </v-container>
            </section>
            <v-parallax src="/images/paris.jpeg" class="mb-2">
                <v-layout
                        column
                        align-center
                        justify-center
                        class="white--text"
                >
                    <blockquote class="white--text mb-2 display-3 text-xs-center elegant-shadow">
                        Не все могут преуспеть во всем. Но успех приходит только с самосовершенствованием и решительностью.<br>
                        Так знай что ты можешь все.
                    </blockquote>
                </v-layout>
            </v-parallax>
            <section>
                    <v-layout
                            column
                            wrap
                            class="my-5"
                            align-center
                    >
                        <v-flex xs12 sm4 class="my-3">
                            <div class="text-xs-center">
                                <h2 class="display-3">Наши курсы</h2>
                                <span class="subheading">
                                    Множество замечательных курсов. Любой найдет для себя что-то интересное.
                                </span>
                            </div>
                        </v-flex>
                    </v-layout>
                <v-layout row class="mb-5 mx-5">
                    <v-flex xs4 v-for="c, i in categories" :key="c.name">
                        <v-card>
                            <v-card-media
                                    :src="`/images/${c.image}`"
                                    height="200px"
                            >
                            </v-card-media>
                            <v-card-title primary-title>
                                <div>
                                    <div class="headline">{{ c.name }}</div>
                                </div>
                            </v-card-title>
                            <v-card-actions>
                                <v-btn href="/academy" flat>Подробнее</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn icon @click.native="c.show = !c.show">
                                    <v-icon>{{ c.show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
                                </v-btn>
                            </v-card-actions>
                            <v-slide-y-transition>
                                <v-card-text v-show="c.show">
                                    {{ c.description }}
                                </v-card-text>
                            </v-slide-y-transition>
                        </v-card>
                    </v-flex>
                </v-layout>
            </section>
            <v-parallax src="/images/space1.jpeg" class="mb-2">
                <v-layout
                        column
                        align-center
                        justify-center
                        class="white--text"
                >
                    <blockquote class="white--text mb-2 display-3 text-xs-center elegant-shadow">
                        Когда наука достигает какой-либо вершины, с нее открывается обширная перспектива дальнейшего пути к новым вершинам, открываются новые дороги, по кото­рым наука пойдет дальше
                    </blockquote>
                </v-layout>
            </v-parallax>
            <section>
                <v-container fluid grid-list-md>
                    <v-layout row wrap>
                        <v-flex
                                md3 xs6
                                v-for="card in cards"
                                :key="card.title"
                        >
                            <v-card class="px-1">
                                <v-card-media
                                        :src="card.src"
                                        height="200px"
                                        style="filter: grayscale(50%)"
                                >
                                    <v-container fill-height fluid>
                                        <v-layout fill-height>
                                            <v-flex xs12 align-end flexbox>
                                                <span class="headline white--text elegant-shadow">{{ card.title }}</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-media>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn icon>
                                        <v-icon>favorite</v-icon>
                                    </v-btn>
                                    <v-btn icon>
                                        <v-icon>bookmark</v-icon>
                                    </v-btn>
                                    <v-btn icon>
                                        <v-icon>share</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </section>
            <v-parallax src="/images/space2.jpeg" class="mb-2">
                <v-layout
                        column
                        align-center
                        justify-center
                        class="white--text"
                >
                    <blockquote class="white--text mb-2 display-3 text-xs-center elegant-shadow">
                        Истинная и законная цель всех наук состоит в том, чтобы наделять жизнь человеческую новыми изобретениями и богатствами
                    </blockquote>
                </v-layout>
            </v-parallax>
            <footer>
                <v-layout
                        column
                        align-center
                        justify-center
                        class="white--text my-5"
                >
                    <div class="logo pt-1">
                        <div class='glitch-window'><h1 class="glitched">Quadric</h1></div>
                        <h1 class="glitched">Quadric</h1>
                    </div>
                </v-layout>
                <div class="px-1" style="text-align: right">
                    <span class="caption">Made by Kseniya &copy; 2018</span>
                </div>
            </footer>
        </v-content>
    </v-app>
</template>


<script>
    export default {
        data: () => ({
            gallery: [
                '1.jpeg',
                '2.jpeg',
                '3.jpeg',
                '4.jpeg',
            ],
            cards: [
                {title: 'Следи за своим прогрессом', src: '/images/gr.jpeg'},
                {title: 'Удобный интерфейс', src: '/images/ws.jpeg'},
                {title: 'Слушайте нас в любое время', src: '/images/mobile.jpeg'},
                {title: 'Учеба это интересно', src: '/images/dc.jpeg'}
            ],
            categories: [
                {
                    name: 'Программирование',
                    image: 'programming.jpeg',
                    description: 'Научись создавать уникальные вещи. Программирование - наше все в настоящее время. Программисты могут совершить невероятное.',
                    show: false
                },
                {
                    name: 'Экономика',
                    image: 'economics.jpeg',
                    description: 'Научись расчитывать свою жизнь на 100 шагов вперед. Это несомненно приведет тебя к успеху!',
                    show: false
                },
                {
                    name: 'Математика',
                    image: 'solar.jpeg',
                    description: 'Математика - основа успешной жизни. Она учит нас думать и разрешать самые сложные задачи.',
                    show: false
                }
            ]
        })
    }
</script>