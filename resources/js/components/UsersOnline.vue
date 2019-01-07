<template>
    <div class="col-md-4">
        <div class="card card-default mb-4">
            <h5 class="card-header">
                <i class="fas fa-users text-success"></i> Users online
            </h5>

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
</template>

<script>
    export default {
        data() {
            return {
                onlineUsers: '',
            }
        },

        mounted() {
            this.onUsersOnline();
        },

        methods: {
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
