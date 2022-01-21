<template>
    <ul v-if="items">
        <template v-for="(item,i) of items">
            <li v-if="visible(item) && !item.separator" :key="item.label || i"
                :class="[{'layout-menuitem-category': root, 'active-menuitem': activeIndex === i && !item.disabled}]"
                role="none">
                <template v-if="root">
                    <div class="layout-menuitem-root-text">{{ item.label }}</div>
                    <appsubmenu :items="visible(item) && item.items"
                                @menuitem-click="$emit('menuitem-click', $event)"></appsubmenu>
                </template>
                <template v-else>
                    <Link v-if="item.to" :href="item.to"
                          :class="[item.class, 'p-ripple', {'p-disabled': item.disabled}, {'router-link-exact-active':activeIndex === i }]"
                          :style="item.style"
                          @click="onMenuItemClick($event,item,i)" :target="item.target" exact role="menuitem"
                    >
                        <i :class="item.icon"></i>
                        <span>{{ item.label }}</span>
                        <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
                        <span v-if="item.badge" class="menuitem-badge">{{ item.badge }}</span>
                    </Link>
                    <a v-if="!item.to" :href="item.url||'#'" :style="item.style"
                       :class="[item.class, 'p-ripple', {'p-disabled': item.disabled}]"
                       @click="onMenuItemClick($event,item,i)" :target="item.target" role="menuitem">
                        <i :class="item.icon"></i>
                        <span>{{ item.label }}</span>
                        <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
                        <span v-if="item.badge" class="menuitem-badge">{{ item.badge }}</span>
                    </a>
                    <transition name="layout-submenu-wrapper">
                        <appsubmenu v-show="activeIndex === i" :items="visible(item) && item.items"
                                    @menuitem-click="$emit('menuitem-click', $event)"></appsubmenu>
                    </transition>
                </template>
            </li>
            <li class="p-menu-separator" :style="item.style" v-if="visible(item) && item.separator"
                :key="'separator' + i" role="separator"></li>
        </template>
    </ul>
</template>
<script>
import {Link} from '@inertiajs/inertia-vue3';

export default {
    name: 'appsubmenu',
    components: {
        Link
    },
    props: {
        items: Array,
        root: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            activeIndex: this.setInitialIndex()
        }
    },
    methods: {
        setInitialIndex() {
            const currentUrl = window.location.href;
            const currentItem = this.items?.findIndex(element => element.to === currentUrl);

            return currentItem > 0 ? currentItem : 0;
        },
        onMenuItemClick(event, item, index) {

            if (item.disabled) {
                event.preventDefault();
                return;
            }

            if (!item.to && !item.url) {
                event.preventDefault();
            }

            //execute command
            if (item.command) {
                item.command({originalEvent: event, item: item});
            }

            this.activeIndex = index;

            this.$emit('menuitem-click', {
                originalEvent: event,
                item: item
            });
        },
        visible(item) {
            return (typeof item.visible === 'function' ? item.visible() : item.visible !== false);
        }
    }
}
</script>

<style scoped>

</style>
