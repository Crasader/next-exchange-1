<template>
    <div v-if="isExternalLink">
        <a :href="url">
            <span :class="icon"><b></b></span>
            <i>{{name}}</i>
            <b-badge v-if="badge && badge.text" :variant="badge.variant">
                <i>{{badge.text}}</i>
            </b-badge>
        </a>
    </div>
    <div v-else>
        <router-link :to="url" v-if="name!=='Logout' && have_token">
            <span :class="icon"><b></b></span>
            <i>{{name}}</i>
            <b-badge v-if="badge && badge.text" :variant="badge.variant">
                <i>{{badge.text}}</i>
            </b-badge>
        </router-link>
        <router-link :to="url"
                     v-else-if="(name!=='Logout' && (name==='Markets' || name==='Token Market' || name==='ICO' || name==='Minepool' || name==='Blacklist IP' || name==='User List')) && !have_token">
            <span :class="icon"><b></b></span>
            <i>{{name}}</i>
            <b-badge v-if="badge && badge.text" :variant="badge.variant">
                <i>{{badge.text}}</i>
            </b-badge>
        </router-link>
        <a class="nav-link-disabled" v-else-if="name!=='Logout'">
            <span v-bind:class="icon"><b></b></span>
            <i>{{name}}</i>
        </a>
        <div v-else>
            <a v-bind:href="url">
                <span v-bind:class="icon"><b></b></span>
                <i>{{name}}</i>
            </a>
        </div>
    </div>
</template>

<style>
    .menu1__item a i {
        font-family: Montserrat;
        font-weight: 500;
    }

    .menu1__item a:hover {
        text-decoration: none;
    }

    div i {
        white-space: nowrap;
    }

    .nav-link-disabled {
        padding: 0;
    }


</style>

<script>
    require('../../bootstrap.js');

    export default {
        name: 'sidebar-nav-link',

        props: {

            name: {

                type: String,
                default: ''
            },

            url: {

                type: String,
                default: ''
            },

            icon: {

                type: String,
                default: ''
            },

            badge: {

                type: Object,
                default: () => {
                }
            },

            variant: {

                type: String,
                default: ''
            },

            classes: {
                type: String,
                default: ''
            }

        },

        data() {

            return {

                have_token: ''
            }
        },

        computed: {


            isExternalLink () {

                if (this.url.substring(0, 4) === 'http') {

                    return true
                } else {

                    return false
                }
            }
        },

        created() {

            this.have_token = ConfigJS.get_user().have_token;
        }
    }
</script>
