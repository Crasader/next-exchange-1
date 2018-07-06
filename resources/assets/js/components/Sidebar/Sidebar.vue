<template>
    <div class="content-page__left">
        <div slot="header"></div>
        <ul class="menu1">
            <template v-for="(item, index) in navItems">

                <template v-if="!item.isAdmin">
                    <template v-if="item.title">
                        <SidebarNavTitle :name="item.name" :classes="item.class" :wrapper="item.wrapper"/>
                    </template>
                    <template v-else-if="item.divider">
                        <SidebarNavDivider :classes="item.class"/>
                    </template>
                    <template v-else-if="item.label">
                        <SidebarNavLabel :name="item.name" :url="item.url" :icon="item.icon" :label="item.label"
                                         :classes="item.class"/>
                    </template>
                    <template v-else>
                        <template v-if="item.children">
                            <!-- First level dropdown -->
                            <SidebarNavDropdown :name="item.name" :url="item.url" :icon="item.icon">
                                <template v-for="(childL1, index) in item.children">
                                    <template v-if="childL1.children">
                                        <!-- Second level dropdown -->
                                        <SidebarNavDropdown :name="childL1.name" :url="childL1.url"
                                                            :icon="childL1.icon">
                                            <li class="nav-item" v-for="(childL2, index) in childL1.children">
                                                <SidebarNavLink :name="childL2.name" :url="childL2.url"
                                                                :icon="childL2.icon" :badge="childL2.badge"
                                                                :variant="item.variant"/>
                                            </li>
                                        </SidebarNavDropdown>
                                    </template>
                                    <template v-else>
                                        <SidebarNavItem :classes="item.class">
                                            <SidebarNavLink :name="childL1.name" :url="childL1.url" :icon="childL1.icon"
                                                            :badge="childL1.badge" :variant="item.variant"/>
                                        </SidebarNavItem>
                                    </template>
                                </template>
                            </SidebarNavDropdown>
                        </template>
                        <template v-else>
                            <SidebarNavItem :classes="item.class">
                                <SidebarNavLink :name="item.name" :url="item.url" :icon="item.icon" :badge="item.badge"
                                                :variant="item.variant"/>
                            </SidebarNavItem>
                        </template>
                    </template>
                </template>

                <template v-if="item.isAdmin && user_type == 1">
                    <template v-if="item.title">
                        <SidebarNavTitle :name="item.name" :classes="item.class" :wrapper="item.wrapper"/>
                    </template>
                    <template v-else-if="item.divider">
                        <SidebarNavDivider :classes="item.class"/>
                    </template>
                    <template v-else-if="item.label">
                        <SidebarNavLabel :name="item.name" :url="item.url" :icon="item.icon" :label="item.label"
                                         :classes="item.class"/>
                    </template>
                    <template v-else>
                        <template v-if="item.children">
                            <!-- First level dropdown -->
                            <SidebarNavDropdown :name="item.name" :url="item.url" :icon="item.icon">
                                <template v-for="(childL1, index) in item.children">
                                    <template v-if="childL1.children">
                                        <!-- Second level dropdown -->
                                        <SidebarNavDropdown :name="childL1.name" :url="childL1.url"
                                                            :icon="childL1.icon">
                                            <li class="nav-item" v-for="(childL2, index) in childL1.children">
                                                <SidebarNavLink :name="childL2.name" :url="childL2.url"
                                                                :icon="childL2.icon" :badge="childL2.badge"
                                                                :variant="item.variant"/>
                                            </li>
                                        </SidebarNavDropdown>
                                    </template>
                                    <template v-else>
                                        <SidebarNavItem :classes="item.class">
                                            <SidebarNavLink :name="childL1.name" :url="childL1.url" :icon="childL1.icon"
                                                            :badge="childL1.badge" :variant="item.variant"/>
                                        </SidebarNavItem>
                                    </template>
                                </template>
                            </SidebarNavDropdown>
                        </template>
                        <template v-else>
                            <SidebarNavItem :classes="item.class">
                                <SidebarNavLink :name="item.name" :url="item.url" :icon="item.icon" :badge="item.badge"
                                                :variant="item.variant"/>
                            </SidebarNavItem>
                        </template>
                    </template>
                </template>

            </template>
        </ul>
        <div class="line-open1"></div>
        <slot></slot>
  </div>
</template>
<script>
    import SidebarNavDivider from './SidebarNavDivider'
    import SidebarNavDropdown from './SidebarNavDropdown'
    import SidebarNavLink from './SidebarNavLink'
    import SidebarNavTitle from './SidebarNavTitle'
    import SidebarNavItem from './SidebarNavItem'
    import SidebarNavLabel from './SidebarNavLabel'
    export default {
        name: 'sidebar',
        props: {
            navItems: {
                type: Array,
                required: true,
                default: () => []
            }
        },
        data() {
            return {
                user_type :0,
            }
        },
        components: {
            SidebarNavDivider,
            SidebarNavDropdown,
            SidebarNavLink,
            SidebarNavTitle,
            SidebarNavItem,
            SidebarNavLabel
        },
        methods: {
            handleClick (e) {
                e.preventDefault();
                e.target.parentElement.classList.toggle('open')
            },
            getUser: function() {
                axios.get('api/currentUser').then((res) => {
                    this.user_type = res.data.user_type;
                });
            }
        },
        beforeMount(){
            this.getUser()
        }

    }
</script>


