import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-user-layout',
    template: `
        <h1>User layout component file</h1>
        <button class="btn btn-primary">Hello</button>
        <router-outlet></router-outlet>
    `,
    styles: []
})

export class UserLayoutComponent implements OnInit {

    constructor() { }

    ngOnInit() {
    }

}
