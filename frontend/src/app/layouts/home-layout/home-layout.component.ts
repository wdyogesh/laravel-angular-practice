import { Component, OnInit } from '@angular/core';


@Component({
    selector: 'app-home-layout',
    template: `
        <app-home-navbar></app-home-navbar>
            <router-outlet></router-outlet>
    `,
    styleUrls: []
})

export class HomeLayoutComponent implements OnInit {

    constructor() {}
	
	ngOnInit() {}
}
