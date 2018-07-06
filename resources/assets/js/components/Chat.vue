<template>
    <div class="chat" v-if="room">
        <div class="chat-header">
            <h3><b>Channel:</b> {{ room.title }}</h3>
        </div>
        <div class="chat-body">
            <chat-log :messages="messages" @scroll-top="loadPrev()"></chat-log>
        </div>
        <div class="chat-footer">
            <chat-input @post="post"></chat-input>
        </div>
    </div>
</template>

<script>
    import ChatInput from './ChatInput.vue';
    import ChatLog from './ChatLog.vue';

    export default {
        name: 'Chat',
        components: {
            ChatInput,
            ChatLog
        },
        props: {
            roomName: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                room: null,
                messages: [],
                users: [],
            }
        },
        watch: {
            roomName: {
                handler: function (newVal, oldVal) {
                    if (oldVal) {
                        Echo.leave(`chat.${this.room.id}`);
                        this.room = null;
                        this.messages = [];
                        this.users = [];
                    }

                    if (newVal) {
                        axios.get(`/chat/room/${newVal}`).then(response => {
                            this.room = response.data;

                            this.loadPrev().then(() => {
                                Echo.join(`chat.${this.room.id}`)
                                    .here((users) => {
                                        this.users = users;
                                    })
                                    .joining((user) => {
                                        this.users.push(user);
                                    })
                                    .leaving((leavingUser) => {
                                        this.users = this.users.filter(user => user.id !== leavingUser.id)
                                    })
                                    .listen('ChatMessageCreated', (event) => {
                                        this.messages.push(event.message);
                                    });
                            });
                        });
                    }
                },
                immediate: true
            },
        },
        methods: {
            post(message) {
                axios.post(`/chat/${this.room.id}/messages`, message);
            },
            loadPrev() {
                return axios.get(`/chat/${this.room.id}/messages`, {
                    params: {
                        offset: this.messages.length,
                        limit: 10,
                    },
                }).then(response => {
                    response.data.forEach(message => {
                        this.messages.unshift(message);
                    });
                });
            }
        },
    }
</script>

<style scoped>
    .chat-header {
        padding: 15px;
    }
    .chat-footer {
        position: absolute;
        bottom: 55px;
        width: 100%;
        height: 55px;
    }
</style>