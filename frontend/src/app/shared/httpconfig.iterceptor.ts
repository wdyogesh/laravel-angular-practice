import { Injectable } from '@angular/core';
// import { ErrorDialogService } from '../error-dialog/errordialog.service';
import {
    HttpInterceptor,
    HttpRequest,
    HttpResponse,
    HttpHandler,
    HttpEvent,
    HttpErrorResponse
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';

export class HttpConfigInterceptor implements HttpInterceptor {
    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

        let token = JSON.parse(localStorage.getItem('authToken'));

        const authData = JSON.parse(localStorage.getItem('authData'));

        // console.log(new Date().getTime());

        if (authData) {

            const diff = new Date().getTime() - authData.last_access_time;

            // console.log('diff===', diff);

            authData.last_access_time = new Date().getTime();
            localStorage.setItem('authData', JSON.stringify(authData));

            // if (diff > (1000 * 60 * 30)) {
            //     console.log('yes');
            //     localStorage.removeItem('authToken');
            //     localStorage.removeItem('authData');
            //     token = '';
            // }

            request = request.clone({ headers: request.headers.set('Authorization', 'Bearer ' + token.access_token )});
        } else {
            request = request.clone();
        }

        return next.handle(request);
    }
}
