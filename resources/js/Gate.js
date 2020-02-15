export default class Gate{
    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.role_id === 1;
    }

    isClinic(){
        return this.user.role_id === 2;
    }

    getEmail(){
        return this.user.email;
    }
}