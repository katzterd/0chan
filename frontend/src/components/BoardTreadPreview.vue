<template>
    <div
        class="thread"
        v-bind:class="{ threadwrap: storage.isThreadListVisible() }"
    >
        <ThreadPosts
            :thread="thread.thread"
            :posts="posts"
            :root="thread.opPost"
            :tree="false"
            @reply="onReply"
        >
            <div :class="$style.omittedPosts" slot="omittedPosts">
                <span v-if="thread.skippedPosts > 0">
                    {{ skippedPostsText }} &mdash;
                </span>
                <router-link :to="threadRoute">Перейти к треду</router-link>
                <span v-if="thread.skippedPosts > 0">
                    &vert;
                    <a @click="expandThread"
                        >Развернуть</a
                    >
                </span>
                <span v-if="!(threadExpanded || threadReplied)">
                    &vert; <a @click="updateThread">Обновить</a>
                </span>
            </div>
        </ThreadPosts>
        <a
            :class="$style.omittedPosts"
            slot="omittedPosts"
            v-if="threadExpanded || threadReplied"
            @click="updateThread"
            >Обновить</a
        >
    </div>
</template>

<script>
import Post from "../components/Post.vue";
import ThreadPosts from "../components/ThreadPosts.vue";
import Storage from "../services/Storage";
import Thread from "../services/Thread";
import BusEvents from "../app/BusEvents";

export default {
    props: ["thread"],
    components: {
        Post,
        ThreadPosts,
    },
    data() {
        return {
            storage: Storage,
            noko: null,
            numExpandPosts: 50,
            threadExpanded: false,
            threadReplied: false,
        };
    },
    created() {
        BusEvents.$bus.on("noko", (value) => {
            this.noko = value;
        });
    },
    computed: {
        posts() {
            return [this.thread.opPost, ...this.thread.lastPosts];
        },
        skippedPostsText() {
            let n = this.thread.skippedPosts;
            if (n % 10 == 1 && n % 100 != 11) {
                return `Пропущено ${n} сообщение`;
            } else if (
                n % 10 >= 2 &&
                n % 10 <= 4 &&
                (n % 100 < 10 || n % 100 >= 20)
            ) {
                return `Пропущено ${n} сообщения`;
            } else {
                return `Пропущено ${n} сообщений`;
            }
        },
        threadRoute() {
            const thread = this.thread.thread;
            return {
                name: "thread",
                params: { dir: thread.board.dir, threadId: thread.id },
            };
        },
    },
    methods: {
        onReply(post) {
            this.$emit("reply", post);
            if (this.noko) {
                this.$router.push({
                    name: "thread",
                    params: { dir: post.boardDir, threadId: post.threadId },
                    hash: "#" + post.id,
                });
            } else {
                this.updateThread();
                this.threadReplied = true;
            }
        },
        updateThread() {
            const thread = this.thread.thread;
            const threadId = thread.id;
            const lastReply =
                this.thread.lastPosts[this.thread.lastPosts.length - 1] ||
                this.thread.opPost;
            return Thread.get(threadId, lastReply.id).then((response) => {
                if (response.data.posts.length) {
                    this.thread.lastPosts = this.thread.lastPosts.concat(
                        response.data.posts
                    );
                } else {
                    this.$bus.emit(BusEvents.ALERT_INFO, "Нет новых постов");
                }
            });
        },
        expandThread() {
            const thread = this.thread.thread;
            const threadId = thread.id;
            const loadedPosts = this.thread.lastPosts
                ? this.thread.lastPosts.length
                : 0;
            const postsToExpand = loadedPosts + this.numExpandPosts;
            return Thread.get(threadId, this.thread.opPost.id).then(
                (response) => {
                    const allPosts = response.data.posts;
                    this.thread.lastPosts = allPosts.slice(-postsToExpand);
                    this.thread.skippedPosts = Math.max(
                        0,
                        allPosts.length - postsToExpand
                    );
                    this.threadExpanded = true;
                }
            );
        },
    },
};
</script>

<style module lang="scss" rel="stylesheet/scss">
@import "~assets/styles/_vars";

.omittedPosts {
    font-weight: bold;
    margin-left: 35px;

    @media (max-width: $screen-xs-max) {
        margin-left: 5px;
        padding-left: 5px;
    }
}
</style>
