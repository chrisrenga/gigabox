<template>
    <div class="alert-wrap">
        <transition-group
            :name="transition"
            tag="div"
        >
            <div
                v-for="(item, index) in notifications"
                :key="index"
                :class="item.typeObject"
                role="alert"
            >
                <span
                    v-if="displayIcons"
                    :class="item.iconObject"
                ></span>
                <span
                    v-html="item.message"
                ></span>
            </div>
        </transition-group>
    </div>
</template>
<script>
    export default {
        props: {
            timeout: {
                type: Number,
                default: 3000
            },
            transition: {
                type: String,
                default: 'slide-fade'
            },
            types: {
                type: Object,
                default: () => ({
                    base:    'alert',
                    success: 'alert-success',
                    error:   'alert-danger',
                    warning: 'alert-warning',
                    info:    'alert-info'
                })
            },
            displayIcons: {
                type: Boolean,
                default: false
            },
            icons: {
                type: Object,
                default: () => ({
                    base:    'fa',
                    error:   'fa-exclamation-circle',
                    success: 'fa-check-circle',
                    info:    'fa-info-circle',
                    warning: 'fa-exclamation-circle',
                })
            },
        },

        data: () => ({
            notifications: []
        }),

        /**
         * On creation Flash a message if a message exists otherwise listen for
         * flash event from global event bus
         */
        created() {
            Event.$on(
                'flash', (message, type) => this.flash(message, type)
            );
        },

        methods: {
            /**
             * Flash our alert to the screen for the user to see
             * and begin the process to hide it
             *
             * @param message
             * @param type
             */
            flash(message, type) {
                this.notifications.push({
                    message: message,
                    type: type,
                    typeObject: this.classes(this.types, type),
                    iconObject: this.classes(this.icons, type)
                });
                setTimeout(this.hide, this.timeout);
            },

            /**
             * Sets and returns the values needed
             *
             * @param type
             */
            classes(propObject, type) {
                let classes = {};

                if(propObject.hasOwnProperty('base')) {
                    classes[propObject.base] = true;
                }
                if (propObject.hasOwnProperty(type)) {
                    classes[propObject[type]] = true;
                }

                return classes;
            },

            /**
             * Hide Our Alert
             *
             * @param item
             */
            hide(item = this.notifications[0]) {
                let key = this.notifications.indexOf(item);
                this.notifications.splice(key, 1);
            }
        },
    }
</script>
