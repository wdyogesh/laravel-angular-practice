import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { CanActivate, CanActivateChild, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminService } from '../services/admin.service';

@Injectable({
    providedIn: 'root'
})
export class RoutesGuard implements CanActivate, CanActivateChild {

    constructor(
        private router: Router,
        private toastr: ToastrService,
    ) {

    }
    canActivate(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot):
        Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {

        const modules = JSON.parse(localStorage.getItem('modules'));

        let match = false;
        const routerState = state;

        modules.forEach(module => {
            if (module.children.length > 0) {
                module.children.forEach(element => {
                    if (next.component['name'] === element.component || routerState.url.search(element.route) > -1) {
                        match = true;
                    }
                });
            } else {
                if (next.component['name'] === module.component || routerState.url.search(module.route) > -1) {
                    match = true;
                }
            }
        });

        if (match === false) {
            this.router.navigate(['/admin']).then(() => {
                this.toastr.error('This module is not active.');
            });
            return false;
        }


        return true;
    }
    canActivateChild(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
        return true;
    }

}
