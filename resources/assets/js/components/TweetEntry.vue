<template>
    <li @click="activate" v-bind:class="{ active: isActive }">{{ title | twitterUrl }}</li>
</template>

<script>
export default {
    props: {
        tweetId: {
            required: true
        },
        title: {
            type: String,
            require: true
        }
    },
    data() {
        return {
            isActive: false
        }
    },
    methods: {
        activate() {
            this.isActive = true;
            var that = this;
            _.each(this.$parent.$children, function (el) {
                if (el == that) {
                    return;
                }
                if (el.setActive) {
                    el.setActive(false);
                }
            });
            window.display.clearContent();
            window.display.setText("Loading...");
            axios.get("/tweet/" + this.tweetId)
            .then(function(resp) {
                window.display.setHeading('')
                window.display.setBody(resp.data.html);
                window.display.setFooter("<a href='" + resp.data.url + "'target='_blank'>Read more...</a>");
            });
        },
        setActive(state) {
            this.isActive = state;
        }
    },
    filters: {
        twitterUrl(value) {
            return value.replace(/https\:\/\/t\.co\/.+$/, '');
        }
    }
}
</script>
