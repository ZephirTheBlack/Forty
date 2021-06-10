let user = document.head.querySelector('meta[name="user"]');

module.exports = {
    computed:{
        currentUser(){
            return JSON.parse(user.content);
        },
        isAuthenticated(){
            return !! user.content;
        },
        isGuest(){
            return ! this.isAuthenticated
        }
    },
    methods:{
        redirectIfGuest(){
            if (this.isGuest) {
                return window.location.href = '/login';
            }
        }
    }
};
