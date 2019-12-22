import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

import { CanActivate, CanActivateChild, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class AuthenticationGuard implements CanActivate, CanActivateChild {

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
            if (userData.role_id === 1 && routerState.url.search('admin') > -1) {
                return true;
            } else if (userData.role_id === 3) {
                return true;
            } else if (userData.role_id === 2) {

                if (routerState.url.search('admin') !== -1 || routerState.url.search('superadmin') !== -1) {

                    this.router.navigate(['/login']).then(() => {
                        this.toastr.warning('Unauthorised Access!');
                    });
                    return false;
                }
                return true;
            } else {
                this.router.navigate(['']).then(() => {
                    this.toastr.warning('Unauthorised Access!');
                });
                return false;
            }
        } else {
            localStorage.removeItem('authData');
            localStorage.removeItem('authToken');
            this.router.navigate(['/login']).then(() => {
                this.toastr.warning('Unauthorised Access. Please login first!');
            });
            return false;
        }

    }
    canActivateChild(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
        return true;
    }
}
