/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

window.axios = require('axios');
window.Vue = require('vue');

window.flash = function(message) {
    window.events.$emit('flash',message);
}


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('download-app-component', require('./components/landingpage/DownloadAppComponent.vue').default);
Vue.component('deal-of-the-day-app-component', require('./components/landingpage/DealOfTheDayAppComponent.vue').default);
Vue.component('subscribe-newsletter-component', require('./components/landingpage/SubscribeNewsletterComponent.vue').default);
Vue.component('all-deal-app-component', require('./components/landingpage/AllDealAppComponent.vue').default);
Vue.component('social-follow-component', require('./components/landingpage/SocialFollowComponent.vue').default);
Vue.component('affiliated-app-component', require('./components/landingpage/AffiliatedAppComponent.vue').default);
Vue.component('advertise-app-component', require('./components/landingpage/AdvertiseAppComponent.vue').default);
Vue.component('privacypolicy-app-component', require('./components/landingpage/PrivacypolicyAppComponent.vue').default);
Vue.component('termsconds-app-component', require('./components/landingpage/TermscondsAppComponent.vue').default);
Vue.component('faq-app-component', require('./components/landingpage/FaqAppComponent.vue').default);
Vue.component('blog-app-component', require('./components/landingpage/BlogAppComponent.vue').default);
Vue.component('blog-details-app-component', require('./components/landingpage/BlogDetailsAppComponent.vue').default);
Vue.component('product-listing-component', require('./components/landingpage/ProductListingComponent.vue').default);
Vue.component('product-details-component', require('./components/landingpage/ProductDetailsComponent.vue').default);

// -----------------------------------------------------------------------------------------
Vue.component('why-us-component', require('./components/landingpage/WhyUsComponent.vue').default);
Vue.component('aboutus-component', require('./components/landingpage/AboutusComponent.vue').default);
Vue.component('what-to-expect-component', require('./components/landingpage/WhattoExpectComponent.vue').default);
Vue.component('contactus-component', require('./components/landingpage/ContactusComponent.vue').default);
Vue.component('terms-conditions-model-component', require('./components/landingpage/TermsConditionsModelComponent.vue').default);
Vue.component('privacy-policy-model-component', require('./components/landingpage/PrivacyPolicyModelComponent.vue').default);
Vue.component('footer-component', require('./components/layouts/FooterComponent.vue').default);
Vue.component('header-component', require('./components/layouts/HeaderComponent.vue').default);
Vue.component('flash', require('./components/Flash.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});