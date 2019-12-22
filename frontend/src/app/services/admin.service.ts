import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})

export class AdminService {

    baseUrl: string;

    constructor(
        private http: HttpClient
    ) {
        this.baseUrl = environment.baseUrl + 'admin/';
    }

    getActiveModules() {
        return this.http.get(this.baseUrl + 'active-modules');
    }

    getAllModules() {
        return this.http.get(this.baseUrl + 'all-modules');
    }

    updateModules(params) {
        return this.http.post(this.baseUrl + 'update-modules', params);
    }

    getAllUsers(params) {
        return this.http.post(this.baseUrl + 'user-management/get-all-users/', params);
    }

    getUserDetails(id) {
        return this.http.get(this.baseUrl + 'user-management/get-user-details/' + id);
    }

    getProfile() {
        return this.http.get(this.baseUrl + 'get-profile');
    }

    updateUserData(value) {
        return this.http.post(this.baseUrl + 'user-management/update-profile', value);
    }

    doUpdateProfiePicture(value) {
        return this.http.post(this.baseUrl + 'user-management/do-update-profile-picture', value);
    }

    deleteUserPermanently(id) {
        return this.http.get(this.baseUrl + 'user-management/delete-user-permanently/' + id);
    }

    deleteMultipleUserPermanently(value) {
        return this.http.post(this.baseUrl + 'user-management/delete-multiple-users-permanently', value);
    }

    toggleUser(value) {
        return this.http.post(this.baseUrl + 'user-management/toggle-user', value);
    }

    getUserCount(role_id) {
        return this.http.get(this.baseUrl + 'user-management/get-user-count/' + role_id);
    }

    /*  Admin management */
    getAdminData(params) {
        return this.http.post(this.baseUrl + 'admin-management/get-admin-data/', params);
    }
    getAdminDetail(id) {
        return this.http.get(this.baseUrl + 'user-management/get-user-detail/' + id);
    }

    addAdmin(value) {
        return this.http.post(this.baseUrl + 'admin-management/add-admin', value);
    }

    /*  Email template management */
    getEmailTemplates() {
        return this.http.get(this.baseUrl + 'email/get-email-templates');
    }

    updateEmailTemplate(value) {
        return this.http.post(this.baseUrl + 'email/update-email-template-data', value);
    }

    getEmailTemplateData(title) {
        return this.http.get(this.baseUrl + 'email/get-email-template-data/' + title);
    }

    /*  CMS Management  */

    getCMSContent() {
        return this.http.get(this.baseUrl + 'cms/get-cms-list');
    }

    getCMSDetail(id) {
        return this.http.get(this.baseUrl + 'cms/get-cms-detail/' + id);
    }

    updateCMSDetail(params) {
        return this.http.post(this.baseUrl + 'cms/update-cms-detail', params);
    }

    /**
     * Settings Management
     */

    getKeysMeta() {
        return this.http.get(this.baseUrl + 'setting/get-keys-meta');
    }

    addApiMeta(params) {
        return this.http.post(this.baseUrl + 'setting/add-keys-meta', params);
    }

    getApiDetails(id) {
        return this.http.get(this.baseUrl + 'setting/get-api-details/' + id);
    }

    editApi(params) {
        return this.http.post(this.baseUrl + 'setting/edit-api', params);
    }

    deleteApi(id) {
        return this.http.get(this.baseUrl + 'setting/delete-api/' + id);
    }

    addApiKeys(params) {
        return this.http.post(this.baseUrl + 'setting/add-keys', params);
    }

    getApiKeys(id) {
        return this.http.get(this.baseUrl + 'setting/get-keys/' + id);
    }

    deleteApiKey(id) {
        return this.http.get(this.baseUrl + 'setting/delete-api-key/' + id);
    }

}
