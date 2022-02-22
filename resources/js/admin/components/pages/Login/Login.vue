<template>
    <v-layout style="height: inherit" align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Login admin</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field prepend-icon="person" label="Login" type="text" v-model="email"></v-text-field>
                        <v-text-field prepend-icon="lock" label="Password" type="password" v-model="password"></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="login">Login</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        data(){
            return {
                email: '',
                password: ''
            }
        },
        methods: {
            login(){

                api
                    .post('/login', {
                        email: this.email,
                        password: this.password
                    })
                    .then(response => {

                        if (response.data.token) {

                            window.api.defaults.headers.common['admin-api-token'] = response.data.token;
                            localStorage.setItem('token', response.data.token);

                            this.$router.push({
                                name: 'project-list'
                            });
                        }
                    });
            }
        }
    };
</script>