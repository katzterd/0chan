Promise.config({
    // Enable warnings
    warnings: true,
    // Enable long stack traces
    longStackTraces: true,
    // Enable cancellation
    cancellation: true,
    // Enable monitoring
    monitoring: true
});

import Vue from 'vue'

// = error catcher
import Raven from 'raven-js';
import RavenVue from 'raven-js/plugins/vue';
if (process.env.NODE_ENV === "production" && process.env.SENTRY_FRONTEND) {
    Raven
        .config(process.env.SENTRY_FRONTEND)
        .addPlugin(RavenVue, Vue)
        .install();
}

import UI from './app/UI'
if (typeof window !== 'undefined') {
    window.onerror = (e) => UI.bsod(e);
    // window.onunhandledrejection = (promise, reason) => {
    //     alert(JSON.stringify(reason));
    //     debugger;
    // }
}

// = setup plugins
import VueBus from 'vue-bus'
Vue.use(VueBus);
Vue.mixin({
    mounted() {
        for (let [ event, callback ] of Object.entries(this.busEvents || {})) {
            this.$bus.on(event, callback);
        }
    },
    beforeDestroy() {
        for (let [ event, callback ] of Object.entries(this.busEvents || {})) {
            this.$bus.off(event, callback);
        }
    },
});
import VueRouter from 'vue-router'
Vue.use(VueRouter);
import Scrollspy from 'vue2-scrollspy';
Vue.use(Scrollspy);
import VueClipboards from 'vue-clipboards';
Vue.use(VueClipboards);
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, { color: '#3CCD9D', failedColor: '#CD5C5C', height: '2px' });

import JsEncrypt from 'jsencrypt/bin/jsencrypt'

Vue.prototype.$jsEncrypt = JsEncrypt;

import VueCryptojs from 'vue-cryptojs'
Vue.use(VueCryptojs)

// = setup filters
import Filters from './app/Filters'
for (let filterName of Object.keys(Filters)) {
    Vue.filter(filterName, Filters[filterName]);
}

// = setup router
import Router from './app/Router'
import App from './components/App.vue'

// = starting
import Session from './services/Session'

try {
    Session.start().then(
        () => {
            window.app = new Vue({
                el: '#app',
                router: Router,
                template: '<App/>',
                components: {App}
            })
        }
    );
} catch (e) {
   UI.bsod(e)
}
