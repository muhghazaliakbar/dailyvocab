<template>
    <div class="container">
        <div class="row justify-content-center">
            <users-online/>

            <div class="col-md-8">
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
                                    @keydown="onTyping"
                                >
                            </div>

                            <button
                                type="submit"
                                class="w-25 btn btn-primary"
                                :disabled="form.processing"
                            >
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>

                        <span
                            v-if="form.errors.has('text')"
                            class="form-text text-danger"
                        >
                            {{ form.errors.first('text') }}
                        </span>

                        <span v-if="userTyping" class="font-italic">{{ userTyping }} is typing...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';
    import UsersOnline from './UsersOnline';

    export default {
        components: {
            UsersOnline
        },

        data() {
            return {
                vocabularies: [],
                form: new Form({
                    text: ''
                }),
                userTyping: ''
            }
        },

        mounted() {
            this.onGetVocabularies();
            this.onListenVocabularyChannel();
            this.onListenUserTyping()
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
             * Handle real-time user typing notification
             */
            onListenUserTyping() {
                Echo.private('typing')
                    .listenForWhisper('typing', (e) => {
                        this.userTyping = e.user.name;

                        setTimeout(() => {
                            this.userTyping = '';
                        }, 3000)
                    });
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

            onTyping() {
                let channel = Echo.private('typing');

                setTimeout(() => {
                    channel.whisper('typing', {
                        user: window.app.user,
                        typing: true
                    })
                }, 300)
            }
        }
    }
</script>
