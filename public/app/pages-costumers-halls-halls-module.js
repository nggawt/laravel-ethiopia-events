(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["pages-costumers-halls-halls-module"],{

/***/ "./src/app/costumers/form-files-proccesor.service.ts":
/*!***********************************************************!*\
  !*** ./src/app/costumers/form-files-proccesor.service.ts ***!
  \***********************************************************/
/*! exports provided: FormFilesProccesorService */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FormFilesProccesorService", function() { return FormFilesProccesorService; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

var FormFilesProccesorService = /** @class */ (function () {
    function FormFilesProccesorService() {
    }
    /* **************************** */
    /* **************************** */
    // constructor( private halls: CustomersDataService, private http: HttpService) { }
    FormFilesProccesorService.prototype.createFilesOb = function (urls) {
        var files = [];
        var pr;
        var _loop_1 = function (url) {
            pr = this_1.getElemSizes(url).then(function (res) {
                var target = url.indexOf('galleries') >= 0 ? url.indexOf('galleries') : url.indexOf('video') >= 0 ? url.indexOf('video') : url.indexOf('loggo');
                res['name'] = url.slice(target + 1).split('/')[1];
                res['exisst'] = true;
                files.push(res);
            }, function (rej) {
                console.log(rej);
            });
        };
        var this_1 = this;
        for (var _i = 0, urls_1 = urls; _i < urls_1.length; _i++) {
            var url = urls_1[_i];
            _loop_1(url);
        }
        return pr.then(function (res) {
            return files;
        });
    };
    FormFilesProccesorService.prototype.getElemSizes = function (elem) {
        var imageUrl = elem;
        var blob = null;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', imageUrl, true);
        xhr.responseType = 'blob';
        var pr = new Promise(function (res, rej) {
            xhr.onload = function () {
                blob = xhr.response;
                return blob ? res(blob) : rej('no data');
            };
        });
        xhr.send();
        return pr;
    };
    FormFilesProccesorService.prototype.formatBytes = function (a) {
        if (0 === a)
            return "0 Bytes";
        var c = 1024, d = 2, e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"], f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f];
    };
    FormFilesProccesorService.prototype.filesValidate = function (arr) {
    };
    FormFilesProccesorService.prototype.filseReader = function (elem) {
        var reader = new FileReader();
        return new Promise(function (rs, rj) {
            reader.onload = function (event) {
                if (event.target.readyState === 2) {
                    elem.src = event.target.result;
                    rs(elem);
                }
                setTimeout(function () {
                    if (event.target.readyState < 2) {
                        rj("your file damaged");
                    }
                }, 3000);
            };
            reader.readAsDataURL(elem);
        });
    };
    FormFilesProccesorService.prototype.removeItem = function (gal, theFile) {
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                gal.splice(idx, 1);
                break;
            }
        }
        return gal;
    };
    FormFilesProccesorService.prototype.fileContains = function (gal, theFile) {
        var isTrueOrFalse = false;
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                isTrueOrFalse = true;
            }
        }
        return isTrueOrFalse;
    };
    FormFilesProccesorService.prototype.createElements = function (elem) {
        var div = document.createElement('DIV');
        var aTag = document.createElement('A');
        var span = document.createElement('SPAN');
        var elemType = elem.type.split("/")[0];
        var itemObj = {};
        aTag.id = elem.id;
        aTag.setAttribute('data-target', elem.target);
        div.id = elem.id;
        div.className = "d-inline-block p-1 m-1 border border-info rounded bg-secondary";
        aTag.className = "close bg-white text-danger";
        span.setAttribute('aria-hidden', 'true');
        span.innerHTML = '&times';
        aTag.appendChild(span);
        div.appendChild(aTag);
        if (elemType === "image") {
            var img = new Image();
            img.src = elem.src;
            // img.className = "border border-info rounded";
            img.setAttribute("height", "90px");
            img.setAttribute("alt", elem.type);
            div.appendChild(img);
        }
        else if (elemType === "video") {
            var video = document.createElement('video');
            var source = document.createElement('source');
            video.controls = true;
            video.height = 90;
            source.type = elem.type;
            source.src = elem.src;
            video.appendChild(source);
            div.appendChild(video);
        }
        itemObj['elemDiv'] = div;
        itemObj['atag'] = aTag;
        itemObj['theElem'] = elem;
        return itemObj;
    };
    FormFilesProccesorService.prototype.handelInputFiles = function (items, costumer, delFiles, existesFiles) {
        var input = new FormData();
        var filesToSend = {
            toUpdate: [],
            toDelete: [],
            size: 0
        };
        var galFiles = {
            loggo: null,
            imgs: [],
            video: null
        };
        /****** handel form files inputs *****/
        var isT = true;
        for (var _i = 0, items_1 = items; _i < items_1.length; _i++) {
            var ii = items_1[_i];
            var extractTargetName = this.getUrl(costumer) + "/" + ii.target + "/" + ii.name.split('.')[0];
            if (ii.target === "galleries") {
                galFiles['imgs'].push(ii);
            }
            if (ii.target === "loggo") {
                galFiles['loggo'] = ii;
            }
            if (ii.target === "video") {
                galFiles['video'] = ii;
            }
            if (existesFiles.indexOf(ii.id) == (-1)) {
                input.append(extractTargetName, ii, ii.name);
                filesToSend['toUpdate'].push(ii);
                filesToSend['size'] += ii.size;
                if (isT)
                    filesToSend.toDelete.push(delFiles[ii.target]);
            }
            isT = false;
        }
        return {
            inputs: input,
            files: galFiles,
            toSend: filesToSend
        };
    };
    FormFilesProccesorService.prototype.getUrl = function (costumer) {
        var company = (costumer['company'] === "ארמונות-לב") ? 'palace-lev' : costumer['company'];
        var urls = costumer['businessType'] + "/" + company;
        return urls;
    };
    FormFilesProccesorService = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Injectable"])({
            providedIn: 'root'
        })
    ], FormFilesProccesorService);
    return FormFilesProccesorService;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/HallsComponent.ts":
/*!*********************************************************!*\
  !*** ./src/app/pages/costumers/halls/HallsComponent.ts ***!
  \*********************************************************/
/*! exports provided: HallsComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallsComponent", function() { return HallsComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};

// import { Location } from '@angular/common';
// import { PagesService } from '../../../pages-service';




var HallsComponent = /** @class */ (function () {
    function HallsComponent(router, route, halls) {
        this.router = router;
        this.route = route;
        this.halls = halls;
        this.path = true;
    }
    HallsComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.route.data.subscribe(function (data) {
            // console.log(data['halls']);
            _this.hallsProps = data['halls'] ? Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(data['halls']) : null;
        });
        this.getCurrentUrl();
    };
    HallsComponent.prototype.getCurrentUrl = function () {
        var _this = this;
        var urlSliced = decodeURIComponent(window.location.pathname).slice(1);
        var urlPath = (urlSliced === 'halls-events' || urlSliced === 'app/halls-events') ? true : false;
        this.urlUnsubscribe = this.router.events.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_2__["filter"])(function (evt) { return evt instanceof _angular_router__WEBPACK_IMPORTED_MODULE_1__["RoutesRecognized"]; }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_2__["map"])(function (_evt) { return decodeURIComponent(_evt["url"]).slice(1); })).subscribe(function (evt) {
            _this.path = (evt === 'halls-events' || evt === 'app/halls-events') ? true : false;
        });
        this.path = urlPath;
    };
    HallsComponent.prototype.onSelectedLink = function (costumer) {
        //this.halls.costumerEmit(costumer);
    };
    HallsComponent.prototype.ngOnDestroy = function () {
        this.urlUnsubscribe.unsubscribe();
    };
    HallsComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-halls',
            template: __webpack_require__(/*! ./halls.component.html */ "./src/app/pages/costumers/halls/halls.component.html"),
            styles: [__webpack_require__(/*! ./halls.component.css */ "./src/app/pages/costumers/halls/halls.component.css")],
            providers: []
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_1__["ActivatedRoute"], _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_3__["CustomersDataService"]])
    ], HallsComponent);
    return HallsComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-about/hall-about.component.css":
/*!***************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-about/hall-about.component.css ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ""

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-about/hall-about.component.html":
/*!****************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-about/hall-about.component.html ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "<hr class=\"d-xl-none\">\r\n<div class=\"row  text-right\">\r\n    <div class=\"col-sm-12\">\r\n        <div class=\"p-3 border border-success\">\r\n            <a class=\"close float-left\" routerLink=\"/אולם-אירועים/ארמונות-לב/מדיה\" type=\"button\" aria-label=\"Close\">\r\n                <span aria-hidden=\"true\">&times;</span>\r\n            </a>\r\n            <div *ngIf=\"(customer$ | async )\" class=\"media\">\r\n\r\n                <img  [src]=\"(customer$ | async )?.loggo\" [alt]=\"(customer$ | async )?.company\">\r\n\r\n                <div class=\"media-body mr-3\">\r\n\r\n                    <h4>\r\n                        {{ (customer$ | async )?.title }}\r\n                    </h4>\r\n                    <p class=\"text-xl-right text-dar p-2\">\r\n                        {{ (customer$ | async )?.discription }}\r\n                    </p>\r\n                    <hr>\r\n\r\n                    <p>\r\n                        <a routerLink=\"../מדיה\" class=\"btn btn-outline-info font-weight-bold\">\r\n                            <i class=\"fa fa-location-arrow\"></i>\r\n                            חזור\r\n                        </a>\r\n                    </p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n\r\n    </div>\r\n</div>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-about/hall-about.component.ts":
/*!**************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-about/hall-about.component.ts ***!
  \**************************************************************************/
/*! exports provided: HallAboutComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallAboutComponent", function() { return HallAboutComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var HallAboutComponent = /** @class */ (function () {
    function HallAboutComponent(hall) {
        this.hall = hall;
    }
    HallAboutComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.hall.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_3__["first"])()).subscribe(function (dt) {
            _this.customer$ = Object(rxjs__WEBPACK_IMPORTED_MODULE_2__["of"])(dt['customer']);
            console.log(dt['customer']);
        });
    };
    HallAboutComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-hall-about',
            template: __webpack_require__(/*! ./hall-about.component.html */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.html"),
            styles: [__webpack_require__(/*! ./hall-about.component.css */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.css")]
        }),
        __metadata("design:paramtypes", [_costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__["CustomersDataService"]])
    ], HallAboutComponent);
    return HallAboutComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html":
/*!**********************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "\n<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" [formGroup]=\"addCostumerForm\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"company\">שם החברה</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.company }}\" class=\"col-11 px-1\" type=\"text\" name=\"company\" id=\"company\"\n            formControlName=\"company\" required #company />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n\n        <div *ngIf=\"f.company.invalid && f.company.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(company)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(company)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(company)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['company']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['company']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['company']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\"> {{ gal['company']  }} </b> \n              </span>\n            </p>\n        </div>\n    </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"contact\">איש קשר</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.contact }}\" class=\"col-11 px-1\" type=\"text\" name=\"contact\" id=\"contact\"\n            formControlName=\"contact\" required #contact />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        \n        <div *ngIf=\"f.contact.invalid && f.contact.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(contact)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(contact)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(contact)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['contact']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['contact']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['contact']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['contact']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"tel\">פלאפון/טלפון</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.tel }}\" type=\"tel\" id=\"tel\" name=\"tel\" class=\"col-11 px-1\" formControlName=\"tel\"\n            [pattern]=\"phoneNum\" required #tel />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.tel.invalid && f.tel.touched\" class=\"invalid-feedback d-block\">* נא למלא שדה בתבנית המתאימה\n          לפאלפון/טלפון\n        </div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(tel)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(tel)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(tel)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['tel']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['tel']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['tel']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['tel']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"address\">כתובת</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.address }}\" class=\"col-11 px-1\" type=\"text\" name=\"address\" id=\"address\"\n            formControlName=\"address\" required #address />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.address.invalid && f.address.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(address)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(address)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(address)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['address']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['address']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['address']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['address']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"email\">אימייל</label>\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.email }}\" class=\"col-11 px-1\" type=\"email\" name=\"email\" id=\"email\" formControlName=\"email\"\n            [pattern]=\"emailPatteren\" required #email />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.email.invalid && f.email.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(email)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(email)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(email)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['email']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['email']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['email']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['email']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- about edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col-sm-12 form-group py-3\">\n        <label for=\"title\">תיאור או כותרת החברה</label>\n\n        <div class=\"inputTypeNumber my-2 textarea\">\n          <textarea value=\"{{ costumer.title }}\" class=\"col-11 px-1\" type=\"text\" name=\"title\" id=\"title\"\n            formControlName=\"title\" required minlength=\"12\" #title>\n          </textarea>\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.title.invalid && f.title.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(title)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(title)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(title)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['title']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['title']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['title']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\"> {{ gal['title']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col-sm-12 form-group border p-3\">\n\n        <label for=\"discription\">אודות החברה</label>\n        <textarea wrap=\"hard\" value=\"{{ costumer.discription | removeWhiteSpace  }}\" class=\"w-100 form-control text-right note\"\n          (mouseenter)=\"textAreaAdjust($event)\" (mouseleave)=\"textAreamouseleave($event)\" type=\"text\" name=\"discription\"\n          id=\"discription\" formControlName=\"discription\" minlength=\"6\" #discription>\n\n        </textarea>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(discription)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(discription)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(discription)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['discription']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['discription']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['discription']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                   'text-danger':gal['type'] === 'errors'}\">\n                    {{ gal['discription']  }} \n                  </b> \n              </span>\n            </p>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"h-100 p-3\">\n        <!-- Chose loggo -->\n        <p class=\"pb-2 m-0\">לוגו החברה</p>\n        <div class=\"form-group border bg-white clearfix\">\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"loggo\">\n            <span>\n              החלף לוגו\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"loggo\" class=\"loggo\" style=\"display: none;\" accept=\"image/*\" required (change)=\"selectedFiles($event,'loggo')\" #loggoFIle/>\n        </div>\n\n        <div class=\"border bg-dark d-flex-row\" #loggo>\n\n          \n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(loggoFIle)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(loggo)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('loggo')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['loggo']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['loggo']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['loggo']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                  'text-danger':gal['type'] === 'errors'}\">{{ gal['loggo']  }} </b> \n              </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n        <p class=\"pb-2 m-0\">סרטון תדמיתי</p>\n        <div class=\"form-group border bg-white clearfix\">\n\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"video\">\n            <span>\n              הוסף סרטון\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n\n          <input type=\"file\" id=\"video\" class=\"video\" style=\"display: none;\" accept=\"video/*\" (change)=\"selectedFiles($event,'video')\" required\n          #vidFile/>\n        </div>\n        \n        <div class=\"border border-success bg-dark d-flex-row\" #video>\n\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(vidFile)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(video)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('video')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <!-- display video messages from server -->\n        <div *ngIf=\"masseges && masseges['video']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['video']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n                <span *ngIf=\"gal['video']\">\n                    <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                    'text-danger':gal['type'] === 'errors'}\">{{ gal['video']  }} </b> \n                </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n          <!-- display messages video from claint -->\n        <!-- <p *ngIf=\"masseges[0] && masseges[0].video\" class=\"alert alert-danger\">{{ masseges[0].video }}</p> -->\n        <!-- gallery -->\n        <p class=\"pb-2 m-0\">גלרית תמונות</p>\n        <div class=\"form-group bg-white border clearfix\">\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"gallery\">\n            <span>\n              הוסף תמונות\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"gallery\" class=\"galleries\" style=\"display: none;\" accept=\"image/*\" required multiple (change)=\"selectedFiles($event,'galleries')\"\n          #galFile/>\n        </div>\n        <div class=\"border border-success bg-dark d-flex-row\" #gall>\n          \n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(galFile)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(gall)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('galleries')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"destroy(gall)\">\n              <span>\n                הסר\n                <i class=\"material-icons text-danger\">\n                    delete_forever\n                </i>\n              </span>\n            </a>\n        </div>\n        <!-- display gallery messages from server -->\n        <div *ngIf=\"masseges && masseges['gallery']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['gallery']\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n                <span *ngIf=\"gal['gallery']\">\n                    <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                    'text-danger':gal['type'] === 'errors'}\">{{ gal['gallery']  }} </b> \n                </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n          <!-- display messages gallery from claint -->\n          <div *ngIf=\"masseges && masseges['files']\" class=\"bg-light p-1 ml-1\">\n              <p *ngFor=\"let gal of masseges['files']\" class=\"bg-light p-1 m-1 d-inline-block\">\n                 \n                <span *ngIf=\"gal['files']\">\n                    <b [ngClass]=\"{'text-danger':gal['files']}\">{{ gal['files']  }} </b> \n                </span>\n              </p>\n          </div>\n      </div>\n\n    </div>\n    <div class=\"col-sm-12\">\n      \n        <div class=\"btn-group w-100 p-3 bg-light\">\n          <button class=\"btn btn-success\" type=\"submit\">העלה</button>\n          <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n          <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        </div>\n      </div>\n\n  </div>\n</form>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.ts":
/*!********************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.ts ***!
  \********************************************************************************/
/*! exports provided: AllEditComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AllEditComponent", function() { return AllEditComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/fesm5/forms.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/fesm5/http.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var src_app_costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! src/app/costumers/form-proccesor.service */ "./src/app/costumers/form-proccesor.service.ts");
/* harmony import */ var src_app_costumers_form_files_proccesor_service__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! src/app/costumers/form-files-proccesor.service */ "./src/app/costumers/form-files-proccesor.service.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};










var AllEditComponent = /** @class */ (function () {
    function AllEditComponent(router, halls, http, valForm, formFiles) {
        this.router = router;
        this.halls = halls;
        this.http = http;
        this.valForm = valForm;
        this.formFiles = formFiles;
        /* ************ valadition **************** */
        this.phoneNum = '^((?=(02|03|04|08|09))[0-9]{2}[0-9]{3}[0-9]{4}|(?=(05|170|180))[0-9]{3}[0-9]{3}[0-9]{4})';
        this.emailPatteren = '^[a-z]+[a-zA-Z_\\d]*@[A-Za-z]{2,10}\.[A-Za-z]{2,3}(?:\.?[a-z]{2})?$';
        this.guard = [];
        this.arrayFlies = [];
        this.masseges = [];
        this.filesSize = 0;
        this.existesFiles = [];
        this.fileToDelete = [];
        this.filesDl = {
            'loggo': [],
            'video': [],
            'galleries': []
        };
    }
    AllEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["find"])(function (val) { return val['customer']['id']; })).subscribe(function (cost) {
            var co = cost['customer'];
            var gal = cost['gallery'];
            var cId = (co && co["user_id"]) ? co["user_id"] : false;
            var authUser = _this.http.authUser;
            var uId = authUser ? authUser["id"] : false;
            // console.log('costumerId: '+ cId + " userId "+ uId);
            if (cId === uId) {
                _this.galleries = gal['image'];
                _this.videos = gal['video'];
                _this.costumer = co;
                _this.apiKey = _this.http.getApiKey();
                _this.formInt();
                _this.galleryInit();
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(true);
            }
            else {
                // let state = decodeURIComponent(this.router.url).split("/");
                // let media = "/"+state[1]+"/"+cost["company"]+"/media";
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(false);
            }
        });
    };
    AllEditComponent.prototype.galleryInit = function () {
        var _this = this;
        var imgs = this.formFiles.createFilesOb(this.galleries);
        var vid = this.formFiles.createFilesOb(this.videos);
        var loggo = this.formFiles.createFilesOb([this.costumer.loggo]);
        imgs.then(function (res) {
            _this.selectedFiles(res, 'galleries');
        });
        vid.then(function (res) {
            _this.selectedFiles(res, 'video');
        });
        loggo.then(function (res) {
            _this.selectedFiles(res, 'loggo');
        });
    };
    AllEditComponent.prototype.update = function (costumer) {
        var _this = this;
        var input = new FormData();
        var comp = costumer['id'];
        if (this.addCostumerForm.controls[comp].status) {
            var controls = this.addCostumerForm.controls[comp];
            var items = this.valForm.validate(controls, this.costumer, comp);
            if (items['status']) {
                input.append('formInputs', JSON.stringify(items['success']));
                this.send(input, 'PATCH');
            }
            else {
                this.masseges = items['errors'];
                this.valForm.resetMessages().then(function (res) {
                    _this.masseges = res;
                });
            }
        }
        // console.log(this.addCostumerForm.controls[comp]);
    };
    AllEditComponent.prototype.inputReset = function (costumer) {
        var comp = costumer['id'];
        this.addCostumerForm.controls[comp].reset();
    };
    AllEditComponent.prototype.default = function (costumer) {
        var comp = costumer.id;
        this.addCostumerForm.controls[comp].setValue(this.costumer[comp]);
    };
    AllEditComponent.prototype.galUpdate = function (elem) {
        var _this = this;
        var _a;
        var files = elem.files;
        if (!files[0]) {
            console.log(elem);
            var el = {};
            el[elem.id] = [(_a = {},
                    _a[elem.id] = elem.id + " לא ביצעת שינויים כלשלהם לקובץ.",
                    _a.type = 'warning',
                    _a)];
            console.log(el);
            this.masseges = el;
            this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
            return;
        }
        var filesInput = this.formFiles.handelInputFiles(files, this.costumer, this.filesDl, this.existesFiles);
        var filesToSend = filesInput['toSend'];
        var method = (filesToSend['toUpdate'].length > 1) ? "PUT" : "PATCH";
        var fd = filesToSend['toDelete'];
        var input = filesInput['inputs'];
        if (fd.length > 0)
            input.append('filesToDelete', JSON.stringify(fd));
        this.send(input, method);
        // this.filesDl['galleries'] = [];
        // console.log(this.checkFileToDelete(files[0].target));
        console.log(filesToSend);
    };
    AllEditComponent.prototype.galReset = function (item) {
        // let el = item.getElementsByTagName('A');
        var childrens = item.querySelectorAll('A');
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            this.unSelectFiles(childrens[ii]);
        }
    };
    AllEditComponent.prototype.galDefault = function (galType) {
        var _this = this;
        var gal = galType === "galleries" ? this.formFiles.createFilesOb(this.galleries) :
            galType === "loggo" ? this.formFiles.createFilesOb([this.costumer.loggo]) :
                galType === "video" ? this.formFiles.createFilesOb(this.videos) : null;
        gal.then(function (res) {
            _this.selectedFiles(res, galType);
        });
    };
    AllEditComponent.prototype.destroy = function (item) {
        console.log(item);
        var el = item.querySelector('A');
        this.unSelectFiles(el);
    };
    AllEditComponent.prototype.canDeactivate = function () {
        if (this.addCostumerForm.dirty || this.addCostumerForm.touched) {
            return confirm("לא שמרתה את הפרטים. תרצה לעזוב דף זה בכל זאת?");
        }
        else {
            return true;
        }
    };
    AllEditComponent.prototype.textAreaAdjust = function (o) {
        var target = o.target;
        target.style.height = "1px";
        target.style.height = (25 + target.scrollHeight) + "px";
    };
    AllEditComponent.prototype.textAreamouseleave = function (o) {
        var target = o.target;
        target.style.height = "1px";
        target.style.height = ((target.scrollHeight) - 50 + '%') + "px";
    };
    Object.defineProperty(AllEditComponent.prototype, "f", {
        get: function () { return this.addCostumerForm.controls; },
        enumerable: true,
        configurable: true
    });
    AllEditComponent.prototype.formInt = function () {
        this.addCostumerForm = new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormGroup"]({
            'company': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.company, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            // 'businessType': new FormControl(this.costumer.businessType, [Validators.required]),
            'title': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.title, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'contact': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.contact, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'tel': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.tel, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'email': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.email, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'address': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.address, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'discription': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.discription, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required])
        });
    };
    AllEditComponent.prototype.onSubmit = function () {
        var _this = this;
        this.masseges = [];
        var err = {
            errors: null
        };
        var handelFiles = this.formFiles.handelInputFiles(this.arrayFlies, this.costumer, this.filesDl, this.existesFiles);
        var files = handelFiles['files'];
        var filesToSend = handelFiles['toSend'];
        var input = handelFiles['inputs'];
        var toDelete = filesToSend['toDelete'];
        var fSize = filesToSend['size'];
        var checkFilesSizes = (Math.round(fSize / Math.pow(1024, 2)) > 6) ? true : false;
        /****** handel form inputs *****/
        var controls = this.addCostumerForm.controls;
        var items = this.valForm.validate(controls, this.costumer);
        var success = items['status'] ? items['success'] : false;
        // console.log(this.filesSize + " : " +Math.round(this.filesSize / Math.pow(1024,2) ));
        // console.log(this.filesSize + " : " +Math.round(Math.pow(1024,2)* 6 ) / (Math.pow(1024,2)));
        // if(files['imgs'].length < 3) err['gallery'] = "גלריית התמונות חייב להכיל לפחות 3 תמונות" ;
        if (checkFilesSizes)
            err['errors'] = ({
                'files': [{
                        files: this.formFiles.formatBytes(fSize) + " : " + "נפח הקבצים גדול מדי.",
                        type: 'warning'
                    }]
            });
        // if(typeof files['loggo'] === 'undefined' || ! files['loggo']) err['loggo'] = "העלה תמונת לוגו או אייקון של החברה.";
        // if(typeof files['video'] === 'undefined' || ! files['video']) err['video']  = "העלה וידאו תדמיתי של החברה.";
        console.log(this.arrayFlies);
        var haveFile = filesToSend['toUpdate'].length > 0;
        if (!err['errors'] && haveFile) {
            input.append('filesToDelete', JSON.stringify(toDelete));
            if (success)
                input.append('formInputs', JSON.stringify(success));
            //this.send(input,'PUT');
        }
        else {
            var filesErrors = err['errors'] ? this.valForm.getMassages(err) : err['errors'];
            var inputErrors = (!items['status'] && !success) ? items['errors'] : false;
            if (filesErrors && inputErrors) {
                for (var ii in filesErrors) {
                    inputErrors[ii] = filesErrors[ii];
                }
            }
            if (!items['status'] && !success) {
                this.masseges = inputErrors;
                this.valForm.resetMessages().then(function (res) {
                    _this.masseges = res;
                });
            }
            else {
                this.masseges = inputErrors;
                this.valForm.resetMessages().then(function (res) {
                    _this.masseges = res;
                });
            }
        }
    };
    AllEditComponent.prototype.allTodefault = function () {
        var controls = this.addCostumerForm.controls;
        for (var ii in controls) {
            if (controls.hasOwnProperty(ii)) {
                if (controls[ii].value !== this.costumer[ii]) {
                    if (this.costumer[ii])
                        controls[ii].setValue(this.costumer[ii]);
                }
            }
        }
    };
    AllEditComponent.prototype.selectedFiles = function (event, elemTarget) {
        var _this = this;
        var files = event.target;
        var targetElement = $('input.' + elemTarget)[0].parentElement.nextElementSibling;
        files = files && files.files ? files.files : event;
        if (!files[0] || this.guard.indexOf(elemTarget) >= 0) {
            console.log("You cant uplaod twise " + elemTarget);
            console.log('this.guard');
            console.log(this.guard);
            return;
        }
        if (elemTarget !== "galleries" && this.guard.indexOf(elemTarget) == -1)
            this.guard.push(elemTarget);
        // let videoType = ['video/3gpp', 'video/H261', 'video/H263', 'video/H264', 'video/JPEG', 'video/mp4', 'video/mpeg'].indexOf(files[0].type);
        // let imageType = ['image/png', 'image/jpeg', 'image/gif'].indexOf(files[0].type);
        for (var _i = 0, files_1 = files; _i < files_1.length; _i++) {
            var theFile = files_1[_i];
            var elemName = theFile.name.split('.')[0];
            theFile.id = elemName + '_' + theFile.size;
            theFile.target = elemTarget;
            if (theFile.exisst)
                this.existesFiles.push(theFile.id);
            if (!this.formFiles.fileContains(this.arrayFlies, theFile)) {
                this.formFiles.filseReader(theFile).then(function (res) {
                    var elemOb = _this.formFiles.createElements(res);
                    elemOb['atag'].addEventListener("click", _this.unSelectFiles.bind(_this));
                    _this.filesSize += elemOb['theElem'].size;
                    _this.arrayFlies.push(elemOb['theElem']);
                    targetElement.appendChild(elemOb['elemDiv']);
                }, function (error) {
                    console.log(error);
                });
            }
        } //END for loop
    };
    AllEditComponent.prototype.unSelectFiles = function (evt) {
        var aTag = evt && evt.target ? evt.target.parentElement : evt;
        // let aTag = (typeof tar != "string" && tar !== "undefined")? evt.target.parentElement:evt;
        // this.fileToDelete = [];
        var div = aTag.parentElement;
        var parent = div.parentElement;
        var childrens = parent.children;
        var index = aTag.getAttribute('data-target');
        if ((this.existesFiles.indexOf(aTag.id) >= 0) && (this.fileToDelete.indexOf(aTag.id) == (-1))) {
            //this.fileToDelete.push(this.findElem(aTag.id, index));
            if ((index == "loggo") || (index == "video") && this.filesDl[index].length === 0)
                this.filesDl[index].push(this.findElem(aTag.id, index));
            if (index == "galleries" && this.filesDl[index].indexOf(aTag.id) == (-1))
                this.filesDl[index].push(this.findElem(aTag.id, index));
        }
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            if (childrens[ii] && aTag.id === childrens[ii].id) {
                var subtractSize = childrens[ii].id.split('_');
                var posSubtrat = subtractSize[subtractSize.length - 1];
                // console.log(index);
                index = ((index == "loggo") || (index == "video")) ? this.guard.indexOf(index) : null;
                this.arrayFlies = this.formFiles.removeItem(this.arrayFlies, childrens[ii]);
                if (index === 0 || index >= 0) {
                    this.guard.splice(index, 1);
                }
                this.filesSize -= posSubtrat;
                parent.removeChild(childrens[ii]);
                break;
            }
        }
    };
    AllEditComponent.prototype.findElem = function (id, target) {
        var ob = this.arrayFlies.find(function (elem) { return elem['id'] == id; });
        //ob = ob? ob: this.arrayFlies.find(elem => elem['id'] == id);
        var item = (ob && target == 'galleries') ? this.galleries.find(function (el) { return el.indexOf(ob.name) >= 0; }) :
            (target == 'video') ? this.videos.find(function (el) { return el.indexOf(ob.name) >= 0; }) : (target == 'loggo') ? this.costumer.loggo : null;
        return item;
    };
    AllEditComponent.prototype.send = function (body, method) {
        var _this = this;
        var updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"] + "? _method=" + method;
        // let updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"]+ "? _method=PUT";
        this.halls.patchData(updaterUrl, body, {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_5__["HttpHeaders"]({
                // 'Content-Type':  'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            })
        })
            .subscribe(function (evt) {
            console.log(evt);
            var msgs = _this.valForm.getMassages(evt);
            _this.masseges = msgs;
            console.log(msgs);
            _this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
        }, function (err) {
            console.log(err);
            if (err["status"] === 401) {
                _this.http.nextIslogged(false);
                window.sessionStorage.removeItem('user_key');
                // this.router.navigate(["/login"]);
                window.location.reload();
                // this.http.allowLogIn.next(true);
                // this.reset();
            }
        });
    };
    AllEditComponent.prototype.reset = function () {
        this.addCostumerForm.reset();
    };
    AllEditComponent.prototype.close = function () {
        this.router.navigate(['../']);
    };
    AllEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-all-edit',
            template: __webpack_require__(/*! ./all-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html"),
            styles: [__webpack_require__(/*! ../../../../../styles/style.component.css */ "./src/app/styles/style.component.css")]
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"],
            _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_2__["CustomersDataService"],
            _http_service__WEBPACK_IMPORTED_MODULE_6__["HttpService"],
            src_app_costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__["FormProccesorService"],
            src_app_costumers_form_files_proccesor_service__WEBPACK_IMPORTED_MODULE_9__["FormFilesProccesorService"]])
    ], AllEditComponent);
    return AllEditComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.css":
/*!*************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.css ***!
  \*************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ".inputTypeNumber{\r\n    height: calc(2.25rem + 2px);\r\n    padding: .375rem .25rem;\r\n    font-size: 1rem;\r\n    line-height: 1.5;\r\n    color: #495057;\r\n    background-color: #fff;\r\n    background-clip: padding-box;\r\n    border: 1px solid #ced4da;\r\n    border-radius: .25rem;\r\n    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;\r\n    position: relative;\r\n}\r\n\r\n.inputTypeNumber input, .inputTypeNumber select{\r\n    border: none;\r\n    outline: none;\r\n}\r\n\r\n.inputTypeNumber textarea{\r\n    border: none;\r\n    outline: none;\r\n    resize: none;\r\n    width: 100%;\r\n    height: 100%;\r\n}\r\n\r\ndiv.textarea{\r\n\r\n    min-height: 100px;\r\n}\r\n\r\ninput:valid + span:after, select:valid + span:after, textarea:valid + span:after {\r\n    content: '✓';\r\n    color: #26b72b;\r\n    padding-left: 5px;\r\n}\r\n\r\ninput:invalid + span:after, select:invalid + span:after,  textarea:invalid + span:after {\r\n    content: '✖';\r\n    color: #f00;\r\n    padding-left: 5px;\r\n}\r\n\r\ntextarea.note {\r\n    \r\n    text-align: right;\r\n    text-indent: 0px;\r\n    /* color: #26b72b; */\r\n    /* word-break: break-all; */\r\n    /* overflow: hidden; */\r\n    white-space: wrap;\r\n    box-sizing:border-box;\r\n    min-height: 40%;\r\n\tdirection:rtl;\r\n\tdisplay:inline;\r\n\tmin-height:30%;\r\n\tline-height:1.5;\r\n\tpadding:15px 15px 30px;\r\n\tborder-radius:3px;\r\n\tborder:1px solid #F7E98D;\r\n\tfont:13px Tahoma, cursive;\r\n\ttransition:box-shadow 0.5s ease;\r\n\tbox-shadow:0 4px 6px rgba(0,0,0,0.1);\r\n\tfont-smoothing:subpixel-antialiased;\r\n\tbackground:linear-gradient(#F9EFAF, #F7E98D);\r\n\tbackground:-webkit-linear-gradient(#F9EFAF, #F7E98D);\r\n}\r\n\r\na {\r\n    cursor: pointer;\r\n}\r\n\r\na.googleFont {\r\n    text-decoration: none;\r\n}\r\n\r\n.material-icons {\r\n    position: relative;\r\n    top: 5px;\r\n}"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.html":
/*!**************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.html ***!
  \**************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" [formGroup]=\"addCostumerForm\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"company\">שם החברה</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.company }}\" class=\"col-11 px-1\" type=\"text\" name=\"company\" id=\"company\"\n            formControlName=\"company\" required #company />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n\n        <div *ngIf=\"f.company.invalid && f.company.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(company)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(company)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(company)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['company']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['company'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['company']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\"> {{ gal['company']  }} </b> \n              </span>\n            </p>\n        </div>\n    </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"contact\">איש קשר</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.contact }}\" class=\"col-11 px-1\" type=\"text\" name=\"contact\" id=\"contact\"\n            formControlName=\"contact\" required #contact />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        \n        <div *ngIf=\"f.contact.invalid && f.contact.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(contact)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(contact)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(contact)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['contact']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['contact'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['contact']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['contact']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"tel\">פלאפון/טלפון</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.tel }}\" type=\"tel\" id=\"tel\" name=\"tel\" class=\"col-11 px-1\" formControlName=\"tel\"\n            [pattern]=\"phoneNum\" required #tel />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.tel.invalid && f.tel.touched\" class=\"invalid-feedback d-block\">* נא למלא שדה בתבנית המתאימה\n          לפאלפון/טלפון\n        </div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(tel)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(tel)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(tel)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['tel']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['tel'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['tel']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['tel']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"address\">כתובת</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.address }}\" class=\"col-11 px-1\" type=\"text\" name=\"address\" id=\"address\"\n            formControlName=\"address\" required #address />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.address.invalid && f.address.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(address)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(address)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(address)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['address']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['address'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['address']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['address']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"email\">אימייל</label>\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.email }}\" class=\"col-11 px-1\" type=\"email\" name=\"email\" id=\"email\" formControlName=\"email\"\n            [pattern]=\"emailPatteren\" required #email />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.email.invalid && f.email.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(email)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(email)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(email)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['email']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['email'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['email']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\">{{ gal['email']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- about edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col-sm-12 form-group py-3\">\n        <label for=\"title\">תיאור או כותרת החברה</label>\n\n        <div class=\"inputTypeNumber my-2 textarea\">\n          <textarea value=\"{{ costumer.title }}\" class=\"col-11 px-1\" type=\"text\" name=\"title\" id=\"title\"\n            formControlName=\"title\" required minlength=\"12\" #title>\n          </textarea>\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.title.invalid && f.title.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(title)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(title)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(title)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['title']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['title'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['title']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success', 'text-danger':gal['type'] === 'errors'}\"> {{ gal['title']  }} </b> \n              </span>\n            </p>\n        </div>\n      </div>\n\n      <div class=\"col-sm-12 form-group border p-3\">\n\n        <label for=\"discription\">אודות החברה</label>\n        <textarea wrap=\"hard\" value=\"{{ costumer.discription | removeWhiteSpace  }}\" class=\"w-100 form-control text-right note\"\n          (mouseenter)=\"textAreaAdjust($event)\" (mouseleave)=\"textAreamouseleave($event)\" type=\"text\" name=\"discription\"\n          id=\"discription\" formControlName=\"discription\" minlength=\"6\" #discription>\n\n        </textarea>\n        \n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(discription)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(discription)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(discription)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['discription']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['discription'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['discription']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                   'text-danger':gal['type'] === 'errors'}\">\n                    {{ gal['discription']  }} \n                  </b> \n              </span>\n            </p>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n\n    <div class=\"col-sm-12\">\n\n      <div class=\"btn-group w-100 p-3 bg-light\">\n        <button class=\"btn btn-success\" type=\"submit\">שמור הכל</button>\n        <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n        <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        <a class=\"btn btn-info\" (click)=\"allTodefault()\">שחזר ברירת מחדל</a>\n      </div>\n    </div>\n\n  </div>\n</form>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.ts":
/*!************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.ts ***!
  \************************************************************************************/
/*! exports provided: BasicEditComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BasicEditComponent", function() { return BasicEditComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/fesm5/forms.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/fesm5/http.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var _costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../../../../costumers/form-proccesor.service */ "./src/app/costumers/form-proccesor.service.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};









//  declare let $:any;
var BasicEditComponent = /** @class */ (function () {
    function BasicEditComponent(router, halls, http, valForm) {
        this.router = router;
        this.halls = halls;
        this.http = http;
        this.valForm = valForm;
        this.phoneNum = '^((?=(02|03|04|08|09))[0-9]{2}[0-9]{3}[0-9]{4}|(?=(05|170|180))[0-9]{3}[0-9]{3}[0-9]{4})';
        this.emailPatteren = '^[a-z]+[a-zA-Z_\\d]*@[A-Za-z]{2,10}\.[A-Za-z]{2,3}(?:\.?[a-z]{2})?$';
        this.masseges = [];
        this.input = new FormData();
    }
    BasicEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["find"])(function (val) { return val['customer']['id']; })).subscribe(function (cost) {
            var co = cost['customer'];
            var cId = (co && co["user_id"]) ? co["user_id"] : false;
            var authUser = _this.http.authUser;
            var uId = authUser ? authUser["id"] : false;
            // console.log('costumerId: '+ cId + " userId "+ uId);
            if (cId === uId) {
                _this.costumer = co;
                _this.apiKey = _this.http.getApiKey();
                _this.formInt();
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(true);
            }
            else {
                // let state = decodeURIComponent(this.router.url).split("/");
                // let media = "/"+state[1]+"/"+cost["company"]+"/media";
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(false);
            }
        });
    };
    BasicEditComponent.prototype.update = function (costumer) {
        var _this = this;
        var comp = costumer['id'];
        var controls = this.addCostumerForm.controls[comp];
        var items = this.valForm.validate(controls, this.costumer, comp);
        if (items['status']) {
            this.send(items['success']);
        }
        else {
            this.masseges = items['errors'];
            this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
        }
    };
    BasicEditComponent.prototype.inputReset = function (costumer) {
        var comp = costumer['id'];
        this.addCostumerForm.controls[comp].reset();
    };
    BasicEditComponent.prototype.default = function (costumer) {
        var comp = costumer.id;
        this.addCostumerForm.controls[comp].setValue(this.costumer[comp]);
    };
    BasicEditComponent.prototype.canDeactivate = function () {
        if (this.addCostumerForm.dirty || this.addCostumerForm.touched) {
            return confirm("לא שמרתה את הפרטים. תרצה לעזוב דף זה בכל זאת?");
        }
        else {
            return true;
        }
    };
    BasicEditComponent.prototype.textAreaAdjust = function (o) {
        var target = o.target;
        target.style.height = "1px";
        target.style.height = (25 + target.scrollHeight) + "px";
    };
    BasicEditComponent.prototype.textAreamouseleave = function (o) {
        var target = o.target;
        target.style.height = "1px";
        target.style.height = ((target.scrollHeight) - 50 + '%') + "px";
    };
    Object.defineProperty(BasicEditComponent.prototype, "f", {
        get: function () { return this.addCostumerForm.controls; },
        enumerable: true,
        configurable: true
    });
    BasicEditComponent.prototype.formInt = function () {
        this.addCostumerForm = new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormGroup"]({
            'company': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.company, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            // 'businessType': new FormControl(this.costumer.businessType, [Validators.required]),
            'title': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.title, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'contact': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.contact, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'tel': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.tel, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'email': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.email, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'address': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.address, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'discription': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](this.costumer.discription, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required])
        });
    };
    BasicEditComponent.prototype.onSubmit = function () {
        var _this = this;
        // this.masseges = [];
        var controls = this.addCostumerForm.controls;
        var items = this.valForm.validate(controls, this.costumer);
        //if(items['status'] === false) this.masseges.push(items['errors']);
        var success = items['success'];
        console.log(success);
        if (success) {
            this.send(success);
        }
        else if (!items['status'] && !success) {
            this.masseges = items['errors'];
            this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
        }
    };
    BasicEditComponent.prototype.reset = function () {
        this.addCostumerForm.reset();
    };
    BasicEditComponent.prototype.close = function () {
        this.router.navigate(['../']);
    };
    BasicEditComponent.prototype.allTodefault = function () {
        var controls = this.addCostumerForm.controls;
        for (var ii in controls) {
            if (controls.hasOwnProperty(ii)) {
                if (controls[ii].value !== this.costumer[ii]) {
                    if (this.costumer[ii])
                        controls[ii].setValue(this.costumer[ii]);
                }
            }
        }
    };
    BasicEditComponent.prototype.send = function (body) {
        var _this = this;
        var updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"] + "? _method=PATCH";
        // let updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"]+ "? _method=PUT";
        this.input.append('formInputs', JSON.stringify(body));
        this.halls.patchData(updaterUrl, this.input, {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_5__["HttpHeaders"]({
                // 'Content-Type':  'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            })
        })
            .subscribe(function (evt) {
            var msgs = _this.valForm.getMassages(evt);
            _this.masseges = msgs;
            _this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
            console.log(msgs);
        }, function (err) {
            console.log(err);
            if (err["status"] === 401) {
                _this.http.nextIslogged(false);
                window.sessionStorage.removeItem('user_key');
                // this.router.navigate(["/login"]);
                _this.http.allowLogIn.next(true);
                // this.reset();
            }
        });
    };
    BasicEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-basic-edit',
            template: __webpack_require__(/*! ./basic-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.html"),
            styles: [__webpack_require__(/*! ./basic-edit.component.css */ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.css")]
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"],
            _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_2__["CustomersDataService"],
            _http_service__WEBPACK_IMPORTED_MODULE_6__["HttpService"],
            _costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__["FormProccesorService"]])
    ], BasicEditComponent);
    return BasicEditComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.html":
/*!**************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/hall-edit.component.html ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "<!-- header edite -->\n<div *ngIf=\"isTrue | async\" class=\"row\">\n  <div class=\"col-sm-12\">\n    <div class=\"shadow-sm\">\n      <h2 class=\"h5 bg-light p-3 rounded\">ערוך דף</h2>\n\n      <div class=\"px-3 pt-2 pb-3\">\n          <a routerLinkActive=\"active\" routerLink=\"basic\" class=\"googleFont ml-1 px-2 py-2 my-2 border rounded text-center\">\n              <span>\n                בסיסי\n    \n                <i class=\"material-icons\">supervisor_account</i>\n              </span>\n            </a>\n        <a routerLinkActive=\"active\" routerLink=\"gallery\" class=\"googleFont ml-1 px-2 py-2 my-2 border rounded text-center\">\n          <span>\n            מדיה\n\n            <i class=\"material-icons\">perm_media</i>\n          </span>\n        </a>\n       \n          <a routerLinkActive=\"active\" routerLink=\"all\" class=\"googleFont ml-1 px-2 py-2 my-2 border rounded text-center\">\n              <span>\n                הכל\n                <i class=\"material-icons\">select_all</i>\n              </span>\n            </a>\n      </div>\n\n    </div>\n  </div>\n\n</div>\n<router-outlet></router-outlet>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.ts":
/*!************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/hall-edit.component.ts ***!
  \************************************************************************/
/*! exports provided: HallEditComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallEditComponent", function() { return HallEditComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var HallEditComponent = /** @class */ (function () {
    function HallEditComponent(http, router, route, halls) {
        this.http = http;
        this.router = router;
        this.route = route;
        this.halls = halls;
    }
    HallEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_5__["find"])(function (val) { return val['customer']['id']; }))
            .subscribe(function (cost) {
            var co = cost['customer'];
            var cId = (co && co["user_id"]) ? co["user_id"] : false;
            // let uId = this.http.authUser["id"];
            var userId = _this.http.authUser;
            var uId = userId ? userId['id'] : false;
            console.log('costumerId: ' + cId + " userId " + uId);
            if (cId === uId) {
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(true);
            }
            else {
                // let state = decodeURIComponent(this.router.url).split("/");
                // let media = "/"+state[1]+"/"+cost["company"]+"/media";
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(false);
                // console.log(media);
                // this.router.navigate([media]);
            }
        });
    };
    HallEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-hall-edit',
            template: __webpack_require__(/*! ./hall-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.html"),
            styles: [__webpack_require__(/*! ../../../../styles/style.component.css */ "./src/app/styles/style.component.css")]
        }),
        __metadata("design:paramtypes", [_http_service__WEBPACK_IMPORTED_MODULE_1__["HttpService"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"], _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_4__["CustomersDataService"]])
    ], HallEditComponent);
    return HallEditComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html":
/*!**************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html ***!
  \**************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "\n<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  \n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"h-100 p-3\">\n        <!-- Chose loggo -->\n        <p class=\"pb-2 m-0\">לוגו החברה</p>\n        <div class=\"form-group border bg-white clearfix\">\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"loggo\">\n            <span>\n              החלף לוגו\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"loggo\" class=\"loggo\" style=\"display: none;\" accept=\"image/*\" required (change)=\"selectedFiles($event,'loggo')\" #loggoFIle/>\n        </div>\n\n        <div class=\"border bg-dark d-flex-row\" #loggo>\n\n          \n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(loggoFIle)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(loggo)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('loggo')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <div *ngIf=\"masseges && masseges['loggo']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['loggo'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n              <span *ngIf=\"gal['loggo']\">\n                  <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                  'text-danger':gal['type'] === 'errors'}\">{{ gal['loggo']  }} </b> \n              </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n        <p class=\"pb-2 m-0\">סרטון תדמיתי</p>\n        <div class=\"form-group border bg-white clearfix\">\n\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"video\">\n            <span>\n              הוסף סרטון\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n\n          <input type=\"file\" id=\"video\" class=\"video\" style=\"display: none;\" accept=\"video/*\" (change)=\"selectedFiles($event,'video')\" required\n          #vidFile/>\n        </div>\n        \n        <div class=\"border border-success bg-dark d-flex-row\" #video>\n\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(vidFile)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(video)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('video')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <!-- display video messages from server -->\n        <div *ngIf=\"masseges && masseges['video']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['video'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n                <span *ngIf=\"gal['video']\">\n                    <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                    'text-danger':gal['type'] === 'errors'}\">{{ gal['video']  }} </b> \n                </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n          <!-- display messages video from claint -->\n        <!-- <p *ngIf=\"masseges[0] && masseges[0].video\" class=\"alert alert-danger\">{{ masseges[0].video }}</p> -->\n        <!-- gallery -->\n        <p class=\"pb-2 m-0\">גלרית תמונות</p>\n        <div class=\"form-group bg-white border clearfix\">\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"gallery\">\n            <span>\n              הוסף תמונות\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"gallery\" class=\"galleries\" style=\"display: none;\" accept=\"image/*\" required multiple (change)=\"selectedFiles($event,'galleries')\"\n          #galFile/>\n        </div>\n        <div class=\"border border-success bg-dark d-flex-row\" #gall>\n          \n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galUpdate(galFile)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galReset(gall)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"galDefault('galleries')\">\n            <span>\n              מקור\n              <i class=\"material-icons text-primary\">\n                redo\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"destroy(gall)\">\n              <span>\n                הסר\n                <i class=\"material-icons text-danger\">\n                    delete_forever\n                </i>\n              </span>\n            </a>\n        </div>\n        <!-- display gallery messages from server -->\n        <div *ngIf=\"masseges && masseges['gallery']\" class=\"bg-light p-1 ml-1\">\n            <p *ngFor=\"let gal of masseges['gallery'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n               \n                <span *ngIf=\"gal['gallery']\">\n                    <b [ngClass]=\"{'text-warning':gal['type'] === 'warning','text-success':gal['type'] === 'success',\n                    'text-danger':gal['type'] === 'errors'}\">{{ gal['gallery']  }} </b> \n                </span>\n              \n              <span *ngIf=\"gal['deletedFiles']\">\n                  <b [ngClass]=\"{'text-warning':gal['deletedFiles']}\">קובץ הוסר:</b> {{ gal['deletedFiles'] }} \n              </span>\n            </p>\n        </div>\n          <!-- display messages gallery from claint -->\n          <div *ngIf=\"masseges && masseges['files']\" class=\"bg-light p-1 ml-1\">\n              <p *ngFor=\"let gal of masseges['files'];let idx = index\" class=\"bg-light p-1 m-1 d-inline-block\">\n                 \n                <span *ngIf=\"gal['files']\">\n                    <b [ngClass]=\"{'text-danger':gal['files']}\">{{ gal['files']  }} </b> \n                </span>\n              </p>\n          </div>\n      </div>\n\n    </div>\n    <div class=\"col-sm-12\">\n      \n        <div class=\"btn-group w-100 p-3 bg-light\">\n          <button class=\"btn btn-success\" type=\"submit\">העלה</button>\n          <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n          <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        </div>\n      </div>\n\n  </div>\n</form>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.ts":
/*!************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.ts ***!
  \************************************************************************************/
/*! exports provided: MediaEditComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MediaEditComponent", function() { return MediaEditComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/fesm5/http.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var src_app_costumers_form_files_proccesor_service__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! src/app/costumers/form-files-proccesor.service */ "./src/app/costumers/form-files-proccesor.service.ts");
/* harmony import */ var src_app_costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! src/app/costumers/form-proccesor.service */ "./src/app/costumers/form-proccesor.service.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};









var MediaEditComponent = /** @class */ (function () {
    function MediaEditComponent(router, halls, http, formFiles, valForm) {
        this.router = router;
        this.halls = halls;
        this.http = http;
        this.formFiles = formFiles;
        this.valForm = valForm;
        this.guard = [];
        this.arrayFlies = [];
        this.masseges = [];
        this.filesSize = 0;
        this.existesFiles = [];
        this.fileToDelete = [];
        this.filesDl = {
            'loggo': [],
            'video': [],
            'galleries': []
        };
    }
    MediaEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_6__["find"])(function (val) { return val['customer']['id']; })).subscribe(function (cost) {
            var co = cost['customer'];
            var gal = cost['gallery'];
            var cId = (co && co["user_id"]) ? co["user_id"] : false;
            var uId = _this.http.authUser["id"];
            if (cId === uId) {
                _this.galleries = gal['image'];
                _this.videos = gal['video'];
                _this.costumer = co;
                _this.apiKey = _this.http.getApiKey();
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_2__["of"])(true);
                _this.galleryInit();
            }
            else {
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_2__["of"])(false);
            }
        });
    };
    MediaEditComponent.prototype.galleryInit = function () {
        var _this = this;
        var imgs = this.formFiles.createFilesOb(this.galleries);
        var vid = this.formFiles.createFilesOb(this.videos);
        var loggo = this.formFiles.createFilesOb([this.costumer.loggo]);
        imgs.then(function (res) {
            _this.selectedFiles(res, 'galleries');
        });
        vid.then(function (res) {
            _this.selectedFiles(res, 'video');
        });
        loggo.then(function (res) {
            _this.selectedFiles(res, 'loggo');
        });
    };
    MediaEditComponent.prototype.canDeactivate = function () {
        /* let resBool = this.http.isLogedIn.pipe().toPromise().then(res => {
            console.log(res);
            
        }); */
        if (this.filesSize >= 1 || this.arrayFlies.length >= 1 || this.arrayFlies.guard >= 1) {
            return confirm("לא שמרתה את הפרטים. תרצה לעזוב דף זה בכל זאת?");
        }
        else {
            return true;
        }
    };
    MediaEditComponent.prototype.galUpdate = function (elem) {
        var _this = this;
        var _a;
        var files = elem.files;
        if (!files[0]) {
            var elmessage = {};
            elmessage[elem.id] = [(_a = {},
                    _a[elem.id] = elem.id + " לא ביצעת שינויים כלשלהם לקובץ.",
                    _a.type = 'warning',
                    _a)];
            this.masseges = elmessage;
            this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
            return;
        }
        var filesInput = this.formFiles.handelInputFiles(files, this.costumer, this.filesDl, this.existesFiles);
        var filesToSend = filesInput['toSend'];
        var method = (filesToSend['toUpdate'].length > 1) ? "PUT" : "PATCH";
        var fd = filesToSend['toDelete'];
        var input = filesInput['inputs'];
        if (fd.length > 0)
            input.append('filesToDelete', JSON.stringify(fd));
        this.send(input, method);
        // this.filesDl['galleries'] = [];
        // console.log(this.checkFileToDelete(files[0].target));
        console.log(filesToSend);
    };
    MediaEditComponent.prototype.galReset = function (item) {
        // let el = item.getElementsByTagName('A');
        var childrens = item.querySelectorAll('A');
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            this.unSelectFiles(childrens[ii]);
        }
    };
    MediaEditComponent.prototype.galDefault = function (galType) {
        var _this = this;
        var gal = galType === "galleries" ? this.formFiles.createFilesOb(this.galleries) :
            galType === "loggo" ? this.formFiles.createFilesOb([this.costumer.loggo]) :
                galType === "video" ? this.formFiles.createFilesOb(this.videos) : null;
        gal.then(function (res) {
            _this.selectedFiles(res, galType);
        });
    };
    MediaEditComponent.prototype.destroy = function (item) {
        console.log(item);
        var el = item.querySelector('A');
        this.unSelectFiles(el);
    };
    MediaEditComponent.prototype.onSubmit = function () {
        var _this = this;
        this.masseges = [];
        var err = {
            errors: null
        };
        var handelFiles = this.formFiles.handelInputFiles(this.arrayFlies, this.costumer, this.filesDl, this.existesFiles);
        var filesToSend = handelFiles['toSend'];
        var input = handelFiles['inputs'];
        var toDelete = filesToSend['toDelete'];
        var fSize = filesToSend['size'];
        var checkFilesSizes = (Math.round(fSize / Math.pow(1024, 2)) > 6) ? true : false;
        // console.log(this.filesSize + " : " +Math.round(this.filesSize / Math.pow(1024,2) ));
        // console.log(this.filesSize + " : " +Math.round(Math.pow(1024,2)* 6 ) / (Math.pow(1024,2)));
        // if(files['imgs'].length < 3) err['gallery'] = "גלריית התמונות חייב להכיל לפחות 3 תמונות" ;
        if (checkFilesSizes)
            err['errors'] = ({
                'files': [{
                        files: this.formFiles.formatBytes(fSize) + " : " + "נפח הקבצים גדול מדי.",
                        type: 'warning'
                    }]
            });
        // if(typeof files['loggo'] === 'undefined' || ! files['loggo']) err['loggo'] = "העלה תמונת לוגו או אייקון של החברה.";
        // if(typeof files['video'] === 'undefined' || ! files['video']) err['video']  = "העלה וידאו תדמיתי של החברה.";
        var haveFile = filesToSend['toUpdate'].length > 0;
        if (!err['errors'] && haveFile) {
            input.append('filesToDelete', JSON.stringify(toDelete));
            this.send(input, 'PUT');
        }
        else {
            var filesErrors = err['errors'] ? this.valForm.getMassages(err) : err['errors'];
            if (err['errors']) {
                this.masseges = filesErrors;
                this.valForm.resetMessages().then(function (res) {
                    _this.masseges = res;
                });
            }
        }
    };
    MediaEditComponent.prototype.selectedFiles = function (event, elemTarget) {
        var _this = this;
        var files = event.target;
        var targetElement = $('input.' + elemTarget)[0].parentElement.nextElementSibling;
        files = files && files.files ? files.files : event;
        if (!files[0] || this.guard.indexOf(elemTarget) >= 0) {
            console.log("You cant uplaod twise " + elemTarget);
            console.log('this.guard');
            console.log(this.guard);
            return;
        }
        if (elemTarget !== "galleries" && this.guard.indexOf(elemTarget) == -1)
            this.guard.push(elemTarget);
        // let videoType = ['video/3gpp', 'video/H261', 'video/H263', 'video/H264', 'video/JPEG', 'video/mp4', 'video/mpeg'].indexOf(files[0].type);
        // let imageType = ['image/png', 'image/jpeg', 'image/gif'].indexOf(files[0].type);
        for (var _i = 0, files_1 = files; _i < files_1.length; _i++) {
            var theFile = files_1[_i];
            var elemName = theFile.name.split('.')[0];
            theFile.id = elemName + '_' + theFile.size;
            theFile.target = elemTarget;
            if (theFile.exisst)
                this.existesFiles.push(theFile.id);
            if (!this.formFiles.fileContains(this.arrayFlies, theFile)) {
                this.formFiles.filseReader(theFile).then(function (res) {
                    var elemOb = _this.formFiles.createElements(res);
                    elemOb['atag'].addEventListener("click", _this.unSelectFiles.bind(_this));
                    _this.filesSize += elemOb['theElem'].size;
                    _this.arrayFlies.push(elemOb['theElem']);
                    targetElement.appendChild(elemOb['elemDiv']);
                }, function (error) {
                    console.log(error);
                });
            }
        } //END for loop
    };
    MediaEditComponent.prototype.unSelectFiles = function (evt) {
        var aTag = evt && evt.target ? evt.target.parentElement : evt;
        // let aTag = (typeof tar != "string" && tar !== "undefined")? evt.target.parentElement:evt;
        // this.fileToDelete = [];
        var div = aTag.parentElement;
        var parent = div.parentElement;
        var childrens = parent.children;
        var index = aTag.getAttribute('data-target');
        if ((this.existesFiles.indexOf(aTag.id) >= 0) && (this.fileToDelete.indexOf(aTag.id) == (-1))) {
            //this.fileToDelete.push(this.findElem(aTag.id, index));
            if ((index == "loggo") || (index == "video") && this.filesDl[index].length === 0)
                this.filesDl[index].push(this.findElem(aTag.id, index));
            if (index == "galleries" && this.filesDl[index].indexOf(aTag.id) == (-1))
                this.filesDl[index].push(this.findElem(aTag.id, index));
        }
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            if (childrens[ii] && aTag.id === childrens[ii].id) {
                var subtractSize = childrens[ii].id.split('_');
                var posSubtrat = subtractSize[subtractSize.length - 1];
                // console.log(index);
                index = ((index == "loggo") || (index == "video")) ? this.guard.indexOf(index) : null;
                this.arrayFlies = this.formFiles.removeItem(this.arrayFlies, childrens[ii]);
                if (index === 0 || index >= 0) {
                    this.guard.splice(index, 1);
                }
                this.filesSize -= posSubtrat;
                parent.removeChild(childrens[ii]);
                break;
            }
        }
    };
    MediaEditComponent.prototype.findElem = function (id, target) {
        var ob = this.arrayFlies.find(function (elem) { return elem['id'] == id; });
        //ob = ob? ob: this.arrayFlies.find(elem => elem['id'] == id);
        var item = (ob && target == 'galleries') ? this.galleries.find(function (el) { return el.indexOf(ob.name) >= 0; }) :
            (target == 'video') ? this.videos.find(function (el) { return el.indexOf(ob.name) >= 0; }) : (target == 'loggo') ? this.costumer.loggo : null;
        return item;
    };
    MediaEditComponent.prototype.reset = function () {
        location.reload();
    };
    MediaEditComponent.prototype.close = function () {
        this.router.navigate(['../']);
    };
    MediaEditComponent.prototype.send = function (body, method) {
        var _this = this;
        var updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"] + "? _method=" + method;
        // let updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"]+ "? _method=PUT";
        this.halls.patchData(updaterUrl, body, {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_3__["HttpHeaders"]({
                // 'Content-Type':  'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            })
        })
            .subscribe(function (evt) {
            console.log(evt);
            var msgs = _this.valForm.getMassages(evt);
            _this.masseges = msgs;
            console.log(msgs);
            _this.valForm.resetMessages().then(function (res) {
                _this.masseges = res;
            });
        }, function (err) {
            if (err["status"] === 401) {
                _this.http.nextIslogged(false);
                window.sessionStorage.removeItem('user_key');
                window.location.reload();
                // this.http.allowLogIn.next(true);
            }
        });
    };
    MediaEditComponent.prototype.sendPatch = function (posterUrl, input) {
        var _this = this;
        this.halls.patchData(posterUrl, input, {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_3__["HttpHeaders"]({
                'Authorization': 'Bearer ' + this.apiKey
            })
        })
            .subscribe(function (evt) {
            console.log(evt);
            // this.masseges.push(evt["massege"]);
            setTimeout(function () {
                // this.router.navigate(["/halls_events"]);
            }, 5000);
        }, function (err) {
            if (err["status"] === 401) {
                _this.http.nextIslogged(false);
                window.sessionStorage.removeItem('user_key');
                // this.router.navigate(["/login"]);
                _this.http.allowLogIn.next(true);
                // this.reset();
            }
        });
    };
    MediaEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-media-edit',
            template: __webpack_require__(/*! ./media-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html"),
            styles: [__webpack_require__(/*! ../../../../../styles/style.component.css */ "./src/app/styles/style.component.css")]
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"], _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_4__["CustomersDataService"], _http_service__WEBPACK_IMPORTED_MODULE_5__["HttpService"], src_app_costumers_form_files_proccesor_service__WEBPACK_IMPORTED_MODULE_7__["FormFilesProccesorService"], src_app_costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_8__["FormProccesorService"]])
    ], MediaEditComponent);
    return MediaEditComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-media/hall-media.component.css":
/*!***************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-media/hall-media.component.css ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ".bg-warning{\r\n    /* background-color: #F6DC2F!important; */\r\n}"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-media/hall-media.component.html":
/*!****************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-media/hall-media.component.html ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "<div *ngIf=\"costumer | async\" class=\"bg-success text-center text-white p-2 rounded\">\n  <h2 >גלריית {{ (costumer | async).company }}</h2>\n  <div class=\"media-slider border border-success bg-dark\">\n    <div id=\"meidiaCarousel\" class=\"carousel slide\" data-ride=\"carousel\">\n      <ol class=\"carousel-indicators p-0\">\n        <li  *ngFor=\"let gallery of galleries;let idx = index\" data-target=\"#meidiaCarousel\" attr.data-slide-to=\"{{ idx }}\" [ngClass]=\"{ 'active': idx === 0 }\" ></li>\n        <!-- <li data-target=\"#meidiaCarousel\" data-slide-to=\"1\"></li>\n        <li data-target=\"#meidiaCarousel\" data-slide-to=\"2\"></li>\n        <li data-target=\"#meidiaCarousel\" data-slide-to=\"3\"></li>\n        <li data-target=\"#meidiaCarousel\" data-slide-to=\"4\"></li> -->\n      </ol>\n      <div class=\"carousel-inner\">\n        <div *ngFor=\"let gallery of galleries;let ii = index\" [ngClass]=\"{ 'carousel-item active': ii === 0,'carousel-item': i !== 0 }\">\n          <img class=\"my-5 mx-0 px-0\" style=\"height:240px\" [src]=\"gallery\" alt=\"{{ gallery | splitText:'/' | splitText:'.':0 }}\" />\n        </div>\n      </div>\n\n      <a class=\"carousel-control-prev\" href=\"#meidiaCarousel\" role=\"button\" data-slide=\"next\">\n        <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>\n        <span class=\"sr-only\">Previous</span>\n      </a>\n      <a class=\"carousel-control-next\" href=\"#meidiaCarousel\" role=\"button\" data-slide=\"prev\">\n        <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>\n        <span class=\"sr-only\">Next</span>\n      </a>\n    </div>\n\n  </div>\n</div>\n<hr>\n<div class=\"bg-light text-right text-success p-2 rounded\">\n  <h2 class=\"col-sm-12\">בחר תמונות</h2>\n\n  <div class=\"border border-success bg-dark d-flex-row\">\n\n    <img style=\"height:90px; cursor: pointer\" class=\"m-1\" *ngFor=\"let gallery of galleries;let ii = index\" [src]=\"gallery\"\n      alt=\"{{ gallery | splitText:'/' | splitText:'.':0 }}\" (click)=\"galActiveItem(ii)\" />\n  </div>\n\n</div>\n<div class=\"bg-success text-center text-white p-2 rounded\">\n  <h2 class=\"col-sm-12\">סרטוני תדמית</h2>\n\n  <div *ngFor=\"let video of videos;let ii = index\" class=\"border border-success bg-dark d-flex-row\">\n    <video controls=true height=\"100%\">\n      <source type=\"video/mp4\" [src]=\"video\" />\n    </video>\n  </div>\n\n</div>\n<div class=\"bg-light text-right text-success p-2 rounded\">\n  <h2 class=\"col-sm-12\">בחר סרטון</h2>\n\n  <div class=\"border border-success bg-dark d-flex-row\">\n\n    <img style=\"height:90px; cursor: pointer\" class=\"m-1\" *ngFor=\"let gallery of galleries;let ii = index\" [src]=\"gallery\"\n      alt=\"{{ gallery | splitText:'/' | splitText:'.':0 }}\" (click)=\"vidActiveItem(ii)\" />\n  </div>\n</div>\n<!-- <img style=\"height:90px; cursor: pointer\" class=\"m-1\" *ngFor=\"let video of videos;let ii = index\" [src]=\"video.video\" alt=\"first-slide1\" /> -->"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-media/hall-media.component.ts":
/*!**************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-media/hall-media.component.ts ***!
  \**************************************************************************/
/*! exports provided: HallMediaComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallMediaComponent", function() { return HallMediaComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var HallMediaComponent = /** @class */ (function () {
    function HallMediaComponent(hall) {
        this.hall = hall;
    }
    ;
    HallMediaComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.hall.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_3__["first"])()).subscribe(function (data) {
            var co = data['customer'];
            var gal = data['gallery'];
            _this.costumer = Object(rxjs__WEBPACK_IMPORTED_MODULE_2__["of"])(co);
            _this.galleries = gal['image'];
            _this.videos = gal['video'];
            $('.carousel').carousel();
        });
    };
    HallMediaComponent.prototype.galActiveItem = function (ii) {
        $(".carousel-indicators")[0].children[ii].click();
    };
    HallMediaComponent.prototype.vidActiveItem = function (ii) {
        //$(".carousel-indicators")[0].children[ii].click();
    };
    HallMediaComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-hall-media',
            template: __webpack_require__(/*! ./hall-media.component.html */ "./src/app/pages/costumers/halls/hall-media/hall-media.component.html"),
            styles: [__webpack_require__(/*! ./hall-media.component.css */ "./src/app/pages/costumers/halls/hall-media/hall-media.component.css")]
        }),
        __metadata("design:paramtypes", [_costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__["CustomersDataService"]])
    ], HallMediaComponent);
    return HallMediaComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall/hall.component.css":
/*!***************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall/hall.component.css ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "div.card .card-footer {\r\n    /* padding-left: 0;\r\n    padding-right: 0; */\r\n\r\n}\r\ndiv.card .card-footer .btn-group{\r\n    padding-left: -10px;\r\n    padding-right: -10px;\r\n\r\n}\r\n/* @media(max-width:1600px){\r\n\r\n    .costum-query {\r\n        width: 100%;\r\n    }\r\n    .costum-query-datil{\r\n        width: 100%;\r\n    } \r\n} */\r\n@media(min-width:1200px){\r\n\r\n    /* .costum-query {\r\n        width: 500px;\r\n    }\r\n    .costum-query-datil{\r\n        width: 1200px;\r\n    } */\r\n    div.card .card-footer {\r\n        padding-left: 0;\r\n        padding-right: 0;\r\n    \r\n    }\r\n\r\n}\r\n@media(min-width:1300px){\r\n\r\n    /* .costum-query {\r\n        width: 500px;\r\n    }\r\n    .costum-query-datil{\r\n        width: 1200px;\r\n    } */\r\n    div.card .card-footer {\r\n        padding-left: 1.25rem;\r\n        padding-right: 1.25rem;\r\n    }\r\n\r\n}\r\n.bg-warning{\r\n    /* background-color: #F6DC2F!important; */\r\n}"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall/hall.component.html":
/*!****************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall/hall.component.html ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "<hr class=\"d-sm-none\">\n<div *ngIf=\"(costumerProps | async)?.company != null\" class=\"row text-right\">\n    <div class=\"costum-query col-xs-12 col-xl-4\">\n        <div class=\"row card-group\">\n\n            <div class=\"col-sm-12 col-md-7 d-md-flex d-xl-block col-xl-12 mb-3\">\n                <div class=\"w-100 border text-center\">\n\n                    <div class=\"shadow-sm bg-light\">\n                        <h5 class=\"p-3 text-success\">{{ (costumerProps | async)?.company }}</h5>\n                    </div>\n                    <img class=\"card-img-top py-3 w-25 m-auto\" [src]=\"(costumerProps | async)?.loggo\" [alt]=\"(costumerProps | async)?.company\" />\n\n                    <div class=\"card-footer col\">\n                        <div class=\"div p-3\">\n                            <a routerLink=\"/halls-events\" class=\"btn btn-outline-success btn-sm\">\n                                <i class=\"fa fa-location-arrow\"></i>\n                                חזור\n                            </a>\n                            <a class=\"btn btn-warning btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\">\n                                <i class=\"fa fa-envelope text-success\"></i>\n\n                                שלח הודעה</a>\n                            <a routerLink=\"media\" class=\"btn btn-danger btn-sm\">מדיה וגלריה</a>\n                            <a routerLink=\"about\" class=\"btn btn-info btn-sm\">אודות</a>\n                            \n                        </div>\n                    </div>\n                </div>\n                <div *ngIf=\"isTrue | async\" class=\"btn-group border mt-2 w-100\">\n                    <div class=\"div p-3\">\n                            <a routerLink=\"/halls-events/{{ (costumerProps | async)?.company | strSpliter  }}/create\" class=\"btn btn-primary btn-sm\">\n                                <!-- <i class=\"fa fa-location-arrow\"></i> -->\n                                + צור דף\n                            </a>\n                            <a routerLink=\"/halls-events/{{ (costumerProps | async)?.company | strSpliter }}/edit/basic\" class=\"btn btn-danger text-light btn-sm\">\n                                <i class=\"fa fa-pencil\"></i>\n                                ערוך דף \n                                <!-- <i class=\"material-icons text-primary\"> edit</i> -->\n                            </a>\n                    </div>\n                    \n                </div>\n            </div>\n\n            <div class=\"col-sm-12 col-md-5 d-md-flex d-xl-block col-xl-12 text-right mb-3\">\n                <div class=\"card  border border-warning\">\n                    <div class=\"card-header bg-warning\">\n                        <h4 class=\"card-title text-center text-light\">\n                            צור קשר\n                        </h4>\n                    </div>\n                    <div class=\"card-body\">\n                        <div class=\"row\">\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> {{ (costumerProps | async)?.contact }}\n                            </div>\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> {{ (costumerProps | async)?.tel }}\n                            </div>\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> {{ (costumerProps | async)?.address }}\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n\n            <div class=\"col-sm-12 col-md-col-xl-12\">\n                <div class=\"border border-danger text-right\">\n                    <div class=\"card-header bg-danger\">\n                        <h4 class=\"text-center text-light\">\n                            {{ (costumerProps | async)?.company }}\n                            <span class=\"font-weight-bold text-warning\">*מבצעים</span>\n                        </h4>\n                    </div>\n                    <div class=\"card-body\">\n                        <div class=\"row\">\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div class=\"costum-query-datil col-xs-12 col-xl-8\">\n        <router-outlet></router-outlet>\n    </div>\n</div>\n\n<!-- (imgLoggo)=\"(costumerProps | async).loggo\" -->\n<!--Contact Modal -->\n\n<div class=\"modal fade bd-example-modal-lg1\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n    <div class=\"modal-dialog modal-lg\">\n\n        <div class=\"border-danger modal-content col-sm-6 mx-auto text-right\">\n            <button type=\"button\" class=\"close float-left\" data-dismiss=\"modal\" aria-label=\"Close\">\n                <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n            </button>\n            <h5 class=\"text-success\">ארמונות לב המחודשים</h5>\n            <div class=\"card-body\">\n                <div class=\"row\">\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> הנהלה\n                    </div>\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> 08-6640450 או 08-664042\n                    </div>\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> אשקלון, ההסתדרות 40 (קניון לב אשקלון)\n                    </div>\n                </div>\n            </div>\n            <div class=\"mb-2\">\n                <a class=\"btn btn-danger btn-sm float-left text-white\" data-dismiss=\"modal\">סגור</a>\n            </div>\n        </div>\n    </div>\n</div>\n\n\n<!-- form model -->\n\n<div class=\"modal fade bd-example-modal-lg container\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n    <div class=\"modal-dialog modal-lg row\">\n        <div class=\"modal-content col-sm-8 mx-auto text-right\">\n\n            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n            </button>\n            <div class=\"p-3\">\n                <h2 class=\"text-right text-success\">שלח הודעה</h2>\n                <p>\n                    לקוחות יקרים, מלאו את הפרטים בשדות הבאים ונחזור אליכם תוך זמן קצר\n                    <span class=\"text-danger\">\n\n                        צוות אתיופיה אירועים.\n                    </span>\n                </p>\n            </div>\n\n            <hr>\n            <form class=\"p-3\">\n                <div class=\"form-group\">\n                    <input type=\"text\" class=\"form-control\" placeholder=\"שם מלא\">\n                </div>\n                <div class=\"form-group\">\n                    <input type=\"city\" class=\"form-control\" placeholder=\"עיר מגורים\">\n\n                </div>\n                <div class=\"form-group\">\n                    <input type=\"email\" class=\"form-control\" id=\"contactEmail\" placeholder=\"אימייל\">\n                </div>\n                <div class=\"form-group\">\n\n                    <select id=\"contactRigion\" class=\"rigion\">\n                        <option selected>אזור</option>\n                        <option value=\"1\">צפון</option>\n                        <option value=\"2\">מרכז והשפלה</option>\n                        <option value=\"3\">דרום</option>\n                    </select>\n                </div>\n\n                <div class=\"form-group\">\n                    <input type=\"tel\" class=\"form-control\" id=\"contactPhone\" placeholder=\"טלפון/פלאפון\">\n                </div>\n\n                <div class=\"form-group\">\n                    <textarea class=\"form-control\" id=\"contactTextarea\" rows=\"3\" placeholder=\"הוסף הודעה\"></textarea>\n                </div>\n                <button type=\"submit\" class=\"btn btn-success btn-sm\">\n                    <i class=\"fa fa-envelope\"></i>\n                    שלח</button>\n                <button type=\"button\" class=\"btn btn-danger btn-sm float-left\" data-dismiss=\"modal\">סגור</button>\n            </form>\n        </div>\n    </div>\n</div>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall/hall.component.ts":
/*!**************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall/hall.component.ts ***!
  \**************************************************************/
/*! exports provided: HallComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallComponent", function() { return HallComponent; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../costumers/customers-data-service */ "./src/app/costumers/customers-data-service.ts");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../http.service */ "./src/app/http.service.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var HallComponent = /** @class */ (function () {
    function HallComponent(halls, router, route, http) {
        this.halls = halls;
        this.router = router;
        this.route = route;
        this.http = http;
    }
    HallComponent.prototype.ngOnInit = function () {
        var _this = this;
        // let pathUrl = this.route.url["value"][0].path;this.route.snapshot.params['id']
        var pathUrl = this.route.snapshot.params['id'];
        this.user = this.http.authUser;
        this.http.userObs.subscribe(function (logged) { _this.checkCostumer(pathUrl, logged); });
    };
    HallComponent.prototype.checkCostumer = function (uri, authUser) {
        var _this = this;
        this.halls.getById(uri).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_3__["first"])(function (param) { return (typeof param === "object") || param === 1; }))
            .subscribe(function (cost) {
            var co = cost['customer'];
            if (co && co['id']) {
                var costumerEmail = co["email"];
                var authEmail = authUser ? authUser['email'] : false;
                if (costumerEmail)
                    _this.costumerProps = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(co);
                if (costumerEmail === authEmail) {
                    _this.http.authUser = authUser;
                    // this.halls.costumerEmit(co);
                    _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(true);
                }
                else {
                    _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(false);
                }
            }
            else {
                setTimeout(function () {
                    var goTo = _this.router.url.split(uri)[0];
                    //this.router.navigate([goTo]);
                }, 100);
            }
        });
    };
    HallComponent.prototype.locSetItem = function (keyName, data) {
        localStorage.setItem(keyName, JSON.stringify(data));
    };
    HallComponent.prototype.getSavedCostumer = function (costumerName) {
        // console.log(("geting costumer from saved rxjs").toUpperCase());
        // this.hallService.costumerEmitter$
        //   .pipe(
        //     first()
        //   )
        //   .subscribe(
        //     res => {
        //       this.costumerProps = res;
        //       if(typeof res.id === "undefined") this.getCostumerByNameFromSever(costumerName);
        //       if((costumerName in localStorage) === false) this.locSetItem(costumerName, res);
        //       // (Object.prototype.hasOwnProperty()) 
        //     },
        //     () => this.getCostumerByNameFromSever(costumerName)
        //   );
    };
    HallComponent.prototype.getCostumerByNameFromSever = function (costumerName) {
        // console.log(("geting costumer from server ").toUpperCase()+ costumerName);
        //this.costumerProps = this.hallService.getById(costumerName);
        // this.hallService.getById(costumerName).subscribe(
        //   (evt) => console.log(evt)
        // );
    };
    HallComponent.prototype.locGetItem = function (keyName) {
        console.log(("geting costumer from saved localStorge").toUpperCase());
        var geTCostumer = JSON.parse(localStorage.getItem(keyName));
        this.costumerProps = geTCostumer;
    };
    HallComponent.prototype.getCostumerName = function () {
        var hallCostumer = decodeURIComponent(this.router.url).slice(1).split('/')[1];
        if (hallCostumer === "ארמונות-לב")
            hallCostumer = hallCostumer.split('-')[0] + " " + hallCostumer.split('-')[1];
        return hallCostumer;
    };
    HallComponent.prototype.ngOnDestroy = function () {
        //this.costtumerSubscriber.unsubscribe();
    };
    HallComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-hall',
            template: __webpack_require__(/*! ./hall.component.html */ "./src/app/pages/costumers/halls/hall/hall.component.html"),
            styles: [__webpack_require__(/*! ./hall.component.css */ "./src/app/pages/costumers/halls/hall/hall.component.css")],
            providers: []
        }),
        __metadata("design:paramtypes", [_costumers_customers_data_service__WEBPACK_IMPORTED_MODULE_1__["CustomersDataService"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"], _http_service__WEBPACK_IMPORTED_MODULE_5__["HttpService"]])
    ], HallComponent);
    return HallComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/halls-routing.module.ts":
/*!***************************************************************!*\
  !*** ./src/app/pages/costumers/halls/halls-routing.module.ts ***!
  \***************************************************************/
/*! exports provided: HallsRoutingModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallsRoutingModule", function() { return HallsRoutingModule; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var _halls_resolver_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./halls-resolver.service */ "./src/app/pages/costumers/halls/halls-resolver.service.ts");
/* harmony import */ var _route_guard_service__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../route-guard.service */ "./src/app/route-guard.service.ts");
/* harmony import */ var _can_deactivate_guard_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../can-deactivate-guard.service */ "./src/app/can-deactivate-guard.service.ts");
/* harmony import */ var _HallsComponent__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./HallsComponent */ "./src/app/pages/costumers/halls/HallsComponent.ts");
/* harmony import */ var _hall_hall_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./hall/hall.component */ "./src/app/pages/costumers/halls/hall/hall.component.ts");
/* harmony import */ var _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./hall-media/hall-media.component */ "./src/app/pages/costumers/halls/hall-media/hall-media.component.ts");
/* harmony import */ var _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./hall-about/hall-about.component */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.ts");
/* harmony import */ var _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./hall-edit/hall-edit.component */ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.ts");
/* harmony import */ var _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./hall-edit/all-edit/all-edit.component */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.ts");
/* harmony import */ var _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./hall-edit/media-edit/media-edit.component */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.ts");
/* harmony import */ var _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./hall-edit/basic-edit/basic-edit.component */ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.ts");
/* harmony import */ var _angular_common__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @angular/common */ "./node_modules/@angular/common/fesm5/common.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};














var hallsRouting = [
    { path: "halls-events", component: _HallsComponent__WEBPACK_IMPORTED_MODULE_5__["HallsComponent"], resolve: { halls: _halls_resolver_service__WEBPACK_IMPORTED_MODULE_2__["HallResolver"] },
        children: [
            {
                path: ":id", component: _hall_hall_component__WEBPACK_IMPORTED_MODULE_6__["HallComponent"], children: [
                    { path: "media", component: _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_7__["HallMediaComponent"] },
                    { path: 'about', component: _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_8__["HallAboutComponent"] },
                    {
                        path: "edit", component: _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_9__["HallEditComponent"], canActivate: [_route_guard_service__WEBPACK_IMPORTED_MODULE_3__["RouteGuardService"]],
                        children: [
                            { path: "all", component: _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_10__["AllEditComponent"], canDeactivate: [_can_deactivate_guard_service__WEBPACK_IMPORTED_MODULE_4__["CanDeactivateGuardService"]] },
                            { path: "gallery", component: _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_11__["MediaEditComponent"], canDeactivate: [_can_deactivate_guard_service__WEBPACK_IMPORTED_MODULE_4__["CanDeactivateGuardService"]] },
                            { path: "basic", component: _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_12__["BasicEditComponent"], canDeactivate: [_can_deactivate_guard_service__WEBPACK_IMPORTED_MODULE_4__["CanDeactivateGuardService"]] }
                        ]
                    }
                ]
            }
        ],
    }
];
var HallsRoutingModule = /** @class */ (function () {
    function HallsRoutingModule() {
    }
    HallsRoutingModule = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["NgModule"])({
            imports: [
                _angular_common__WEBPACK_IMPORTED_MODULE_13__["CommonModule"],
                _angular_router__WEBPACK_IMPORTED_MODULE_1__["RouterModule"].forChild(hallsRouting)
            ],
            exports: [_angular_router__WEBPACK_IMPORTED_MODULE_1__["RouterModule"]]
        })
    ], HallsRoutingModule);
    return HallsRoutingModule;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/halls.component.css":
/*!***********************************************************!*\
  !*** ./src/app/pages/costumers/halls/halls.component.css ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ".btn-group-vertical, .btn-group {\r\n    /* -ms-flex-direction: inherit;\r\n    flex-direction: inherit; \r\n    display: block; */\r\n}\r\n\r\n.material-icons {\r\n    font-size: inherit;\r\n}\r\n\r\n@media(max-width:1500px){\r\n\r\n    /* .costum-query {\r\n        width: 500px;\r\n    }\r\n    .costum-query-datil{\r\n        width: 1200px;\r\n    } */\r\n\r\n}\r\n"

/***/ }),

/***/ "./src/app/pages/costumers/halls/halls.component.html":
/*!************************************************************!*\
  !*** ./src/app/pages/costumers/halls/halls.component.html ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "\n<router-outlet *ngIf=\"! path\"></router-outlet>\n<hr class=\"d-sm-none\">\n<div *ngIf=\"path\" class=\"row text-right\">\n  <div class=\"col-sm-12 mb-3\">\n    <h1 class=\"h2 py-2 pr-3 bg-light text-success rounded\">אולמות אירועים</h1>\n    <p class=\"h5 pr-3 pb-3 shadow-sm\">\n      לקוחות יקרים, לפניכם מגוון אולמי אירועים לבחירתכם תהתקשרו לקבלת פרטים ויעוץ\n      <span class=\"text-danger\">\n        צוות אתיופיה אירועים.\n      </span>\n    </p>\n  </div>\n\n  <div class=\"col-sm-10 col-md-6 col-lg-6 col-xl-4 mb-3 d-flex flex-row align-items-stretch\"\n        *ngFor=\"let hallsProp of (hallsProps | async); let idx = index\">\n    <div class=\"border w-100 d-flex align-items-between flex-column\">\n      <div class=\"py-2 mb-2 px-2 shadow-sm bg-light\">\n        <div class=\"d-flex-column\">\n          <h5 class=\"p-2 m-0 bg-success rounded text-white\">{{ hallsProp.customer.company }} {{ idx+1 }}</h5>\n          <img style=\"max-height: 90px\" src=\"{{ hallsProp.customer.loggo }}\" alt=\"{{ hallsProp.customer.company }}\" class=\"rounded img-fluid p-2 mx-auto\" />\n\n        </div>\n\n      </div>\n      <div class=\"mt-auto p-2 bd-highlight\">\n\n        <div class=\"text-right\">\n\n          <h4 class=\"bg-succes text-success\">\n            {{ hallsProp.customer.title }}\n          </h4>\n          <p class=\"card-text p-1\">\n            {{ (hallsProp.customer.discription.length>40)? (hallsProp.customer.discription | slice:0:130)+'..':(hallsProp.customer.discription) }}\n          </p>\n          <a \n            (click)=\"onSelectedLink(hallsProp.customer)\"\n            routerLink=\"{{ hallsProp.customer.id }}/about\"\n            class=\"text-info font-weight-bold\">\n            המשך לקרוא\n            <i class=\"fa fa-angle-double-left\"></i>\n          </a>\n        </div>\n      </div>\n      <!-- justify-content-center  -->\n      <div class=\"card-footer mt-auto p-2 bd-highlight text-right\">\n        <div class=\"text-center\">\n          <a class=\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg1\">\n            \n            <span class=\"text-right float-right\">\n\n              צור קשר\n            </span>\n            <br />\n            <i class=\"material-icons btn btn-success btn-sm float-left\">\n              contact_phone\n            </i>\n\n          </a>\n          <a class=\"btn btn-warning btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\">\n\n            <span class=\"text-right float-right\">\n\n              שלח הודעה\n            </span>\n            <br />\n            <i class=\"material-icons btn-warning btn-sm float-left\">\n              email\n            </i>\n          </a>\n          <a class=\"btn btn-danger btn-sm\" routerLink=\"{{ hallsProp.customer.id }}/media\"\n              (click)=\"onSelectedLink(hallsProp.customer)\">\n\n            <span class=\"text-right float-right\">\n              עבור לעסק\n            </span>\n            <br />\n            <i class=\"material-icons btn btn-danger btn-sm float-left\">\n              navigate_before\n            </i>\n\n          </a>\n        </div>\n      </div>\n    </div>\n  </div>\n  \n</div>\n\n<!--Contact Modal -->\n\n<div *ngIf=\"path\" class=\"modal fade bd-example-modal-lg1\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n  <div class=\"modal-dialog modal-lg\">\n\n    <div class=\"border-danger modal-content col-sm-6 mx-auto text-right\">\n      <button type=\"button\" class=\"close float-left\" data-dismiss=\"modal\" aria-label=\"Close\">\n        <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n      </button>\n      <h5 class=\"text-success\">ארמונות לב המחודשים</h5>\n      <div class=\"card-body\">\n        <div class=\"row\">\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> הנהלה\n          </div>\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> 08-6640450 או 08-664042\n          </div>\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> אשקלון, ההסתדרות 40 (קניון לב אשקלון)\n          </div>\n        </div>\n      </div>\n      <div class=\"mb-2\">\n        <a class=\"btn btn-danger btn-sm float-left text-white\" data-dismiss=\"modal\">סגור</a>\n      </div>\n    </div>\n  </div>\n</div>\n\n\n<!-- form model -->\n\n<div *ngIf=\"path\" class=\"modal fade bd-example-modal-lg container\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n  <div class=\"modal-dialog modal-lg row\">\n    <div class=\"modal-content col-sm-8 mx-auto text-right\">\n\n      <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n        <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n      </button>\n      <div class=\"p-3\">\n        <h2 class=\"text-right text-success\">שלח הודעה</h2>\n        <p>\n          לקוחות יקרים, מלאו את הפרטים בשדות הבאים ונחזור אליכם תוך זמן קצר\n          <span class=\"text-danger\">\n\n            צוות אתיופיה אירועים.\n          </span>\n        </p>\n      </div>\n\n      <hr>\n      <form class=\"p-3\">\n        <div class=\"form-group\">\n          <input type=\"text\" class=\"form-control\" placeholder=\"שם מלא\">\n        </div>\n        <div class=\"form-group\">\n          <input type=\"city\" class=\"form-control\" placeholder=\"עיר מגורים\">\n\n        </div>\n        <div class=\"form-group\">\n          <input type=\"email\" class=\"form-control\" id=\"email\" placeholder=\"אימייל\">\n        </div>\n        <div class=\"form-group\">\n\n          <select id=\"rigion\" class=\"rigion\">\n            <option selected>אזור</option>\n            <option value=\"1\">צפון</option>\n            <option value=\"2\">מרכז והשפלה</option>\n            <option value=\"3\">דרום</option>\n          </select>\n        </div>\n\n        <div class=\"form-group\">\n          <input type=\"tel\" class=\"form-control\" id=\"phone\" placeholder=\"טלפון/פלאפון\">\n        </div>\n\n        <div class=\"form-group\">\n          <textarea class=\"form-control\" id=\"textarea\" rows=\"3\" placeholder=\"הוסף הודעה\"></textarea>\n        </div>\n        <button type=\"submit\" class=\"btn btn-success btn-sm\">\n          <i class=\"fa fa-envelope\"></i>\n          שלח</button>\n        <button type=\"button\" class=\"btn btn-danger btn-sm float-left\" data-dismiss=\"modal\">בטל וסגור</button>\n      </form>\n    </div>\n  </div>\n</div>"

/***/ }),

/***/ "./src/app/pages/costumers/halls/halls.module.ts":
/*!*******************************************************!*\
  !*** ./src/app/pages/costumers/halls/halls.module.ts ***!
  \*******************************************************/
/*! exports provided: HallsModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HallsModule", function() { return HallsModule; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _HallsComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./HallsComponent */ "./src/app/pages/costumers/halls/HallsComponent.ts");
/* harmony import */ var _hall_hall_component__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./hall/hall.component */ "./src/app/pages/costumers/halls/hall/hall.component.ts");
/* harmony import */ var _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./hall-media/hall-media.component */ "./src/app/pages/costumers/halls/hall-media/hall-media.component.ts");
/* harmony import */ var _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./hall-about/hall-about.component */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.ts");
/* harmony import */ var _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./hall-edit/hall-edit.component */ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.ts");
/* harmony import */ var _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./hall-edit/all-edit/all-edit.component */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.ts");
/* harmony import */ var _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./hall-edit/basic-edit/basic-edit.component */ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.ts");
/* harmony import */ var _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./hall-edit/media-edit/media-edit.component */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.ts");
/* harmony import */ var _halls_routing_module__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./halls-routing.module */ "./src/app/pages/costumers/halls/halls-routing.module.ts");
/* harmony import */ var _shared_pipes_module_pipes_module__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../../shared/pipes-module/pipes-module */ "./src/app/shared/pipes-module/pipes-module.ts");
/* harmony import */ var _shared_form_module_form_module__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../../shared/form-module/form-module */ "./src/app/shared/form-module/form-module.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};












var HallsModule = /** @class */ (function () {
    function HallsModule() {
    }
    HallsModule = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["NgModule"])({
            imports: [
                _shared_form_module_form_module__WEBPACK_IMPORTED_MODULE_11__["AppFormModule"],
                _halls_routing_module__WEBPACK_IMPORTED_MODULE_9__["HallsRoutingModule"],
                _shared_pipes_module_pipes_module__WEBPACK_IMPORTED_MODULE_10__["PipesModule"]
            ],
            declarations: [
                _HallsComponent__WEBPACK_IMPORTED_MODULE_1__["HallsComponent"],
                _hall_hall_component__WEBPACK_IMPORTED_MODULE_2__["HallComponent"],
                _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_3__["HallMediaComponent"],
                _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_4__["HallAboutComponent"],
                _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_5__["HallEditComponent"],
                _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_6__["AllEditComponent"],
                _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_7__["BasicEditComponent"],
                _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_8__["MediaEditComponent"],
            ],
            exports: []
        })
    ], HallsModule);
    return HallsModule;
}());



/***/ }),

/***/ "./src/app/shared/form-module/form-module.ts":
/*!***************************************************!*\
  !*** ./src/app/shared/form-module/form-module.ts ***!
  \***************************************************/
/*! exports provided: AppFormModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AppFormModule", function() { return AppFormModule; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
/* harmony import */ var _angular_common__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/common */ "./node_modules/@angular/common/fesm5/common.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/fesm5/forms.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var AppFormModule = /** @class */ (function () {
    function AppFormModule() {
    }
    AppFormModule = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["NgModule"])({
            imports: [
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormsModule"],
                _angular_common__WEBPACK_IMPORTED_MODULE_1__["CommonModule"],
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["ReactiveFormsModule"],
            ],
            declarations: [],
            exports: [
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormsModule"],
                _angular_common__WEBPACK_IMPORTED_MODULE_1__["CommonModule"],
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["ReactiveFormsModule"]
            ]
        })
    ], AppFormModule);
    return AppFormModule;
}());



/***/ })

}]);
//# sourceMappingURL=pages-costumers-halls-halls-module.js.map