import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '../../environments/environment';


@Injectable({
    providedIn: 'root'
})
export class AuthService {

    baseUrl: string;

    constructor(
        private http: HttpClient
    ) {
        this.baseUrl = environment.baseUrl;
    }

    doLogin(params) {
        return this.http.post(this.baseUrl + 'auth/do-authenticate-user', params);
    }

    changePasswordByAdmin(params) {
        return this.http.post(this.baseUrl + 'password/change-password-by-admin', params);
    }

    doSignup(params) {
        return this.http.post(this.baseUrl + 'auth/do-register-user', params);
    }
}
