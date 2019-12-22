import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { HttpClient } from '@angular/common/http';
import { ReplaySubject, BehaviorSubject } from 'rxjs';

import { environment } from '../../environments/environment';

@Injectable({
    providedIn: 'root'
})

export class OtherService {

    baseUrl: string;
    userData = new ReplaySubject<Object>();


    constructor(
        private router: Router,
        private toastr: ToastrService,
        private http: HttpClient
    ) {
        this.baseUrl = environment.baseUrl;
        this.userData.next(this.getLocalUserData());
    }

    getLocalUserData() {
        return JSON.parse(localStorage.getItem('authData'));
    }

    getUserData() {
        return this.userData.asObservable();
    }

    getPhoneCodes() {
        return this.http.get(this.baseUrl + 'master/get-phone-codes');
    }

    setUserData(value) {
        localStorage.setItem('authData', JSON.stringify(value));
        this.userData.next(value);
    }

    unAuthorizedUserAccess(error) {
        if (!error.ok && error.status === 401) {
            this.doLogout();
            this.toastr.error('Unauthorized.');
            this.router.navigate(['']);
        }
    }

    doLogout() {
        localStorage.removeItem('authData');
        localStorage.removeItem('authToken');
        this.setUserData(null);
        this.router.navigate(['/']).then(() => {
            this.toastr.success('Logged Out Successfully');
        });
        return true;
    }


}
