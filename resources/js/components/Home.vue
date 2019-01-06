<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default mb-4">
                    <div class="card-header">Users</div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Ghali</li>
                        <li class="list-group-item">Ari Mutiara Arief</li>
                        <li class="list-group-item">Indah</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-default mb-4">
                    <div class="card-header">Vocabularies</div>

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

                            <div class="col">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                >
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
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
                vocabularies: [],
                form: new Form({
                    text: ''
                })
            }
        },

        created() {
            this.onGetVocabularies();

            Echo.channel('vocabulary')
                .listen('.vocabulary.created', (e) => {
                    this.vocabularies.push(e.vocabulary)
                })
        },

        methods: {
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
            }
        }
    }
</script>
