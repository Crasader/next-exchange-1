<template>
    <!-- <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button" @click="mobileSidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none mr-auto" type="button" @click="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" v-if="chatEnabled"
                @click="asideToggle">
            <span class="icon-speech"></span>
        </button>
    </header>
    -->
    <header>
        <div class="space1"></div>
        <div class="top-fix1">
            <div class="header">
                <div class="page-line">
                    <div class="header__left">
                        <a href="" class="logo">
                            <img src="img/logo.png" alt="">
                        </a>
                    </div>
                    <div class="header__right">
                        <ul class="dropdown-menu mt-1 px-2 ">
                            <li>&nbsp;</li>
                            <li>This is currently not available.</li>
                            <li>&nbsp;</li>
                        </ul>
                        <a class="mess1" href="javascript:void(null)" data-toggle="dropdown"></a>
                        <div class="people1">
                            <div class="people1__current">
                                <a href="exchange#/profile">
                                    <img src="img/av1.png" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

</template>

<style>
    .header__left {
        width: 156px;
    }

    .header__right > ul.dropdown-menu {
        position: relative;
        top: 60px;
        margin-right: -30px;
    }

    .app-header.navbar {
        height: 1px;
    }

</style>


<script>
    import {EventBus} from '../app';

    export default {
        name: 'header_vue',
        data() {
            return {
                chatEnabled: null
            }
        },
        created() {
            EventBus.$on('chatRoomChange', (chatRoomName) => {
                this.chatEnabled = Boolean(chatRoomName);

                if (!this.chatEnabled) document.body.classList.add('aside-menu-hidden');
            });
        },
        methods: {
            sidebarToggle(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-hidden');
            },
            sidebarMinimize(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-minimized');
            },
            mobileSidebarToggle(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-mobile-show');
            },
            asideToggle(e) {
                e.preventDefault();
                document.body.classList.toggle('aside-menu-hidden');
            }
        }
    }
</script>
