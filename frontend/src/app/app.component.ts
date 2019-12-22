import { Component, ElementRef } from '@angular/core';

@Component({
    selector: 'app-root',
    template: `<router-outlet>
                    <ngx-ui-loader></ngx-ui-loader>
                </router-outlet>`,
    styleUrls: []
})

export class AppComponent {

    title = 'frontend';

}
