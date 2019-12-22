import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { HttpConfigInterceptor } from './shared/httpconfig.iterceptor';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgxUiLoaderModule,
         NgxUiLoaderConfig, SPINNER, POSITION, PB_DIRECTION,
         NgxUiLoaderRouterModule,
         NgxUiLoaderHttpModule
       } from 'ngx-ui-loader';
import { ToastrModule } from 'ngx-toastr';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

const ngxUiLoaderConfig: NgxUiLoaderConfig = {
    bgsColor: '#b66dff',
    bgsPosition: POSITION.centerCenter,
    bgsSize: 80,
    bgsType: SPINNER.threeStrings, // background spinner type
    fgsColor: '#b66dff',
    fgsSize: 80,
    fgsType: SPINNER.threeStrings, // foreground spinner type
    pbDirection: PB_DIRECTION.leftToRight, // progress bar direction
    pbColor: '#b66dff',
    pbThickness: 5, // progress bar thickness
};

@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        BrowserAnimationsModule,
        NgxUiLoaderModule.forRoot(ngxUiLoaderConfig),
        NgxUiLoaderRouterModule.forRoot({ showForeground: false }),
        NgxUiLoaderHttpModule.forRoot({ showForeground: true }),
        ToastrModule.forRoot({
            positionClass: 'toast-bottom-right',
        })
    ],
    providers: [
        {
            provide : HTTP_INTERCEPTORS,
            useClass : HttpConfigInterceptor,
            multi: true,
        }
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
