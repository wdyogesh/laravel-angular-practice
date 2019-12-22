import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { AdminService } from './../../../services/admin.service';
import { FormBuilder, FormGroup, FormArray, FormControl } from '@angular/forms';

@Component({
    selector: 'app-modules',
    templateUrl: './modules.component.html',
    styleUrls: ['./modules.component.scss']
})
export class ModulesComponent implements OnInit {

    modulesData: any = [];

    modulesForm: FormGroup;

    constructor(
        private fb: FormBuilder,
        private toastr: ToastrService,
        private adminService: AdminService
    ) { }

    ngOnInit() {
        this.adminService.getAllModules().subscribe(result => {
            this.modulesData = result['data'];
            this.buildModulesForm();
        });
    }

    buildModulesForm() {
        this.modulesForm = this.fb.group({
            modules: new FormArray([])
        });
        this.addCheckboxes();
    }

    addCheckboxes() {
        this.modulesData.map((o, i) => {
            const control = new FormControl(this.modulesData[i].is_active); // if first item set to true, else false
            (this.modulesForm.controls.modules as FormArray).push(control);
        });
    }

    getModules(form) {
        return form.get('modules').controls as FormArray;
    }

    submit() {
        const selected = this.modulesForm.value.modules
        .map((v, i) => v ? {
            id: this.modulesData[i].id,
            value: 1
        } : {
            id: this.modulesData[i].id,
            value: 0
        });
        this.adminService.updateModules(selected).subscribe(result => {
            if (result['status'] === 'success') {
                this.toastr.success('Modules updated successfully');
                this.ngOnInit();
            } else {
                this.toastr.error('Something went wrong.');
            }
        }, (error) => {
            this.toastr.error(error);
        });
    }
}
