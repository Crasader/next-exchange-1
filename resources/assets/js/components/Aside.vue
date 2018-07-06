<template>
    <aside class="aside-menu">
        <chat v-if="chat.roomName" :roomName="chat.roomName"></chat>
    </aside>
</template>

<script>
    import Chat from './Chat.vue';
    import {EventBus} from '../app';

    export default {
        name: 'aside_vue',
        components: {
            Chat,
        },
        data() {
            return {
                chat: {
                    roomName: null,
                }
            }
        },
        created() {
            EventBus.$on('chatRoomChange', (chatRoomName) => {
                this.chat.roomName = chatRoomName;
            });
        },
    }
</script>
