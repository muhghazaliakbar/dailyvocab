<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default mb-4">
                    <h5 class="card-header"><i class="fas fa-users text-success"></i> Users online</h5>

                    <ul class="list-group list-group-flush">
                        <li
                            v-for="user in onlineUsers"
                            :key="user.id"
                            class="list-group-item d-flex flex-row justify-content-between"
                        >
                            <div>{{ user.name }} <span v-if="isCurrentUser(user)" class="text-muted">(You)</span></div>
                            <div><i class="fa fa-circle text-success"></i></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-default mb-4">
                    <h5 class="card-header"><i class="fas fa-book text-info"></i> Vocabularies</h5>

                    <div class="card-body">
                        <span
                            v-for="(vocabulary) in vocabularies"
                            :key="vocabulary.id"
                            class="badge text-capitalize mr-1 text-white h5"
                            :style="`background-color: ${vocabulary.user.color}`"
                        >
                            {{ vocabulary.text }}
                        </span>
                    </div>

                    <div class="card-footer">
                        <form class="form-row" @submit.prevent="onSubmit">
                            <div class="col">
                                <input
                                    v-model="form.text"
                                    type="text"
                                    class="form-control mr-sm-2"
                                    placeholder="Add vocabulary here..."
                                >
                                <span
                                    v-if="form.errors.has('text')"
                                    class="form-text text-danger"
                                >
                                    {{ form.errors.first('text') }}
                                </span>
                            </div>

                            <button
                                type="submit"
                                class="w-25 btn btn-primary"
                                :disabled="form.processing"
                            >
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                onlineUsers: '',
                vocabularies: [],
                form: new Form({
                    text: ''
                })
            }
        },

        mounted() {
            this.onGetVocabularies();
            this.onListenVocabularyChannel();
            this.onUsersOnline();
        },

        methods: {
            /**
             * Handle real-time vocabulary channel event.
             */
            onListenVocabularyChannel() {
                Echo.channel('vocabulary')
                    .listen('.vocabulary.created', (e) => {
                        this.vocabularies.push(e.vocabulary)
                    });
            },

            /**
             * Handle real-time online users event.
             */
            onUsersOnline() {
                Echo.join('online')
                    .here((users) => {
                        this.onlineUsers = users
                    })
                    .joining((user) => {
                        this.onlineUsers.push(user)
                    })
                    .leaving((user) => {
                        this.onlineUsers = this.onlineUsers.filter((onlineUser) => {
                            return onlineUser.id !== user.id
                        })
                    })
            },

            /**
             * Fetch vocabularies data.
             */
            onGetVocabularies() {
                axios.get('/vocabularies')
                    .then((response) => {
                        this.vocabularies = response.data
                    })
                    .catch((error) => {
                        alert('Can not fetch vocabularies data. check the errors in console.');
                        console.error(error)
                    })
            },

            /**
             * Handle on submit vocabulary data.
             */
            onSubmit() {
                this.form.post('/vocabularies')
                    .then((response) => {
                        this.vocabularies.push(response);
                        this.form.text = '';
                    });
            },

            /**
             * Check if user is current user online.
             * 
             * @param user
             * @return {boolean}
             */
            isCurrentUser(user) {
                return window.app.user.id === user.id;
            }
        }
    }
</script>
