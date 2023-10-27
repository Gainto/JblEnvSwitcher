import template from './sw-version.html.twig';
import './sw-version.scss';

const { Component, Mixin } = Shopware;

Component.override('sw-version', {
    template,

    inject: ['envSwitcherApiService'],

    mixins: [
        Mixin.getByName('notification'),
    ],

    data() {
        return {
            currentEnv : null,
            isLoading : false
        };
    },

    created() {
        this.createdComponent();
    },

    computed: {
        isProd(){
            return this.currentEnv === "prod";
        },
        isDev(){
            return this.currentEnv === "dev";
        }
    },

    methods: {
        createdComponent() {
            this.loadCurrentEnv();
        },

        loadCurrentEnv(){
            this.envSwitcherApiService.getCurrentEnvironment()
                .then((res)=> {
                    this.currentEnv = res.environment
                });
        },

        switchEnv(env){
            if(this.isLoading){
                return;
            }

            if(this.currentEnv === env){
                this.createNotificationSuccess({
                    title: "Switch environment",
                    message: env + ' environment is already active'
                });
                return;
            }

            this.isLoading = true;

            this.envSwitcherApiService.switchEnvironment(env)
                .then(()=> {
                    this.createNotificationSuccess({
                        title: "Switch environment",
                        message: "switched environment to " + env + ".\nReloading the Admin."
                    });
                    window.location.reload();
                }).catch((err) => {
                    this.createNotificationError({
                        title: "Switch environment",
                        message: err.response.data.message
                    });
                }).finally(() => {
                    this.isLoading = false;
                })
        }
    }
});
