<template>
    <v-container fluid fill-height @keypress.13="submit()">
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar dark color="teal lighten-3">
                        <v-toolbar-title>{{ action === 'login' ? 'Авторизация' : 'Регистрация'}}</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                    prepend-icon="person"
                                    label="Имя"
                                    type="text"
                                    v-model="user.name"
                                    v-if="action === 'register'"
                            ></v-text-field>
                            <v-text-field
                                    prepend-icon="email"
                                    label="E-Mail"
                                    type="email"
                                    v-model="user.email"
                            ></v-text-field>
                            <v-text-field
                                    prepend-icon="lock"
                                    v-model="user.password"
                                    label="Пароль"
                                    type="password"
                            ></v-text-field>
                            <v-text-field
                                    v-if="action === 'register'"
                                    prepend-icon="lock"
                                    v-model="user.password_confirmation"
                                    label="Повторите пароль"
                                    type="password"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="teal lighten-3" dark @click="$router.push(action === 'login' ? '/register' : '/login')">{{ action === 'login' ? 'Регистрация' : 'Авторизация'}}</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="teal lighten-3" dark @click="submit()">{{ action === 'login' ? 'Войти' : 'Зарегистрироваться'}}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <v-snackbar
                v-model="snackbar"
                absolute
                top
                right
                color="error"
        >
            <span>{{ error }}</span>
            <v-icon dark>error</v-icon>
        </v-snackbar>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                user: {},
                snackbar: false,
                error: ''
            }
        },
        props: {
            action: String
        },
        methods: {
            submit() {
                this.$auth[this.action](this.user).then(res => {
                    this.$router.push('/')
                }).catch(err => {
                    this.error = err
                    this.snackbar = true
                })
            }
        },
    }
</script>