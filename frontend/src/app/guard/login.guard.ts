import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

import { CanActivate, CanActivateChild, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class LoginGuard implements CanActivate, CanActivateChild {

    constructor(
        private router: Router,
        private toastr: ToastrService
    ) {
    }

    canActivate(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot
    ): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {

        const userData = JSON.parse(localStorage.getItem('authData'));

        const token = JSON.parse(localStorage.getItem('authToken'));

        const routerState = state;
        if (userData && token) {
            if (routerState.url.search('login') > -1 || routerState.url.search('signup') > -1) {
                if (userData.role_id === 1) {
                    this.router.navigate(['/admin']).then(() => {
                        this.toastr.warning('You\'re already logged in');
                    });
                    return false;
                } else if (userData.role_id === 2) {
                    this.router.navigate(['/user']).then(() => {
                        this.toastr.warning('You\'re already logged in');
                    });
                    return false;
                }
                return true;
            }
        } else {
            return true;
        }

    }
    canActivateChild(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
        return true;
    }
}
