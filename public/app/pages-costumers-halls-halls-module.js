(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["pages-costumers-halls-halls-module"],{

/***/ "./src/app/costumers/form-proccesor.service.ts":
/*!*****************************************************!*\
  !*** ./src/app/costumers/form-proccesor.service.ts ***!
  \*****************************************************/
/*! exports provided: FormProccesorService */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FormProccesorService", function() { return FormProccesorService; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};

var FormProccesorService = /** @class */ (function () {
    function FormProccesorService() {
    }
    FormProccesorService.prototype.validate = function (inputControllers, costumer, key) {
        var truly = true;
        var err = {};
        var success = {};
        if (key) {
            var inputs = this.checkInputs(inputControllers, key, costumer);
            var inp = inputs['input'];
            var status_1 = inputs['status'];
            success[key] = inp[key];
            err[key] = inp;
            return {
                'success': status_1 ? success : {},
                'errors': status_1 ? success : err,
                'status': status_1
            };
        }
        else {
            for (var ii in inputControllers) {
                var input = this.checkInputs(inputControllers[ii], ii, costumer);
                var inputs = input['input'];
                if (!input.status) {
                    truly = false;
                }
                for (var ii_1 in inputs) {
                    if (ii_1 != 'type')
                        err[ii_1] = inputs;
                }
                if (input.status) {
                    for (var idx in inputs) {
                        if (idx != 'type')
                            success[idx] = inputs[idx];
                    }
                }
            } //END FOR IN LOOP
            return {
                'success': success,
                'errors': err,
                'status': truly
            };
        }
    };
    FormProccesorService.prototype.checkInputs = function (input, ii, costumer) {
        var gnKey = {
            company: "שם חברה",
            contact: "איש קשר",
            email: "אימייל",
            tel: "טלפון",
            address: "כתובת",
            title: "תיאור או כותרת",
            discription: "אודות החברה"
        };
        var inputs = {};
        var isTrue = true;
        var transformHebKey = {};
        if (!gnKey[ii]) {
            return {
                'status': false,
                'input': transformHebKey[ii] = "שגיעה"
            }; //isTrue?inputs:transformHebKey
        }
        if (input.touched && input.valid && input.dirty && costumer[ii] != input.value) {
            inputs[ii] = input.value;
            inputs['type'] = "success";
        }
        else if (costumer[ii] === input.value) {
            //console.log(gnKey[ii]);
            isTrue = false;
            transformHebKey['type'] = "warning";
            transformHebKey[ii] = gnKey[ii] + ": לא ביצעת שינויים כל שהם.";
        }
        else {
            //onsole.log(gnKey[ii]);
            isTrue = false;
            transformHebKey['type'] = "danger";
            transformHebKey[ii] = gnKey[ii] + ": לא תקף.";
        }
        return {
            'status': isTrue,
            'input': isTrue ? inputs : transformHebKey
        }; //isTrue?inputs:transformHebKey;
    };
    FormProccesorService = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Injectable"])({
            providedIn: 'root'
        }),
        __metadata("design:paramtypes", [])
    ], FormProccesorService);
    return FormProccesorService;
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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
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
            console.log(data);
            _this.hallsProps = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(data['halls']['costumers']);
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
        this.halls.costumerEmit(costumer);
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
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_1__["ActivatedRoute"], _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_3__["HallsDataService"]])
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

module.exports = "<hr class=\"d-xl-none\">\r\n<div class=\"row  text-right\">\r\n    <div class=\"col-sm-12\">\r\n        <div class=\"p-3 border border-success\">\r\n            <a class=\"close float-left\" routerLink=\"/אולם-אירועים/ארמונות-לב/מדיה\" type=\"button\" aria-label=\"Close\">\r\n                <span aria-hidden=\"true\">&times;</span>\r\n            </a>\r\n            <div *ngIf=\"((coustumer | async)?.loggo != null) || ((coustumer | async)?.loggo != 'undefined')\" class=\"media\">\r\n\r\n                <img  [src]=\"(coustumer | async)?.loggo\" [alt]=\"(coustumer | async)?.company\">\r\n\r\n                <div class=\"media-body mr-3\">\r\n\r\n                    <h4>\r\n                        ארמונות לב המחודשים 2012.\r\n                    </h4>\r\n                    <p class=\"text-xl-right text-dar p-2\">\r\n\r\n                        אולמות לב השוכנים בקניון לב באשקלון מתגאים בעיצוב חדש, איכותי ויוקרתי מאין כמוהו ובהנהלה חדשה, שמעניקה למקום מניסיונה העשיר.\r\n                        האחים לב, המפורסמים והפעילים בתחום הפקת האירועים בישראל,זה זמן רב מתחייבים לסטנדרטים הכי טובים באחריות.להפיק\r\n                        לכם את האירוע המושלם בשבילכם.\r\n                    </p>\r\n                    <p class=\"text-xl-right text-secondar p-2\">\r\n                        ב\"ארמונות לב\" תוכלו לערוך חתונות, אירועי בר/בת מצווה, בריתות, טקסי חינה, מסיבות רבות משתתפים,ועוד. המקום ממתין לשירותכם,\r\n                        ובמיוחד לשדרוג האירוע שלכם, ציוד הגברה ותאורה ברמה הגבוהה ביותר. התפריט שיוגש באירוע שלכם ייבנה בשיתוף\r\n                        השף המקצועי, המנוסה והמפורסם של המקום, ובהתאם לצרכיכם הייחודיים. האחים לב, שלהם עשרות שנות פעילות\r\n                        בתחום וידע עשיר\r\n                    </p>\r\n                    <p class=\"text-xl-right text-secondar p-2\">\r\n                        בהפקת אירועים, יחד עם הצוות המארח, הידוע ביחסו החם ללקוחות המקום, יעניקו את כל הידע והמקצועית שלהם לכם ולאורחיכם. יחד מייצרים\r\n                        כל אלו תוצאה שאין כמותה ואירוע מושלם ובלתי נשכח! כמו כן מספקים \"ארמונות לב\" שירות קייטרינג משובח\r\n                        עם תפריט מגוון,\r\n                    </p>\r\n                    <p class=\"text-xl-right text-secondar p-2\">\r\n                        איכותי ונהדר עם מיטב המאכלים. השירות כולל משלוח עד הבית. בשטח האולם שב\"ארמונות לב\" מתקיים בערבי חג ובימי שישי יריד מזון שבועי\r\n                        בו נמכרים מאכלים מובחרים וטריים ממגוון סגנונות ועדות ובכל הטעמים, והכול במחירים עממיים. *כל המאכלים\r\n                        של \"ארמונות לב\" בהשגחת הרבנות אשקלון וניתן לקבל גם כשרות בד\"ץ.\r\n                    </p>\r\n                    <hr>\r\n\r\n                    <p>\r\n                        <a routerLink=\"../מדיה\" class=\"btn btn-outline-info font-weight-bold\">\r\n                            <i class=\"fa fa-location-arrow\"></i>\r\n                            חזור\r\n                        </a>\r\n                    </p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n\r\n    </div>\r\n</div>"

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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
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
    function HallAboutComponent(hall, router, route) {
        this.hall = hall;
        this.router = router;
        this.route = route;
    }
    HallAboutComponent.prototype.ngOnInit = function () {
        // let pathUrl = this.route.url["value"][0].path;
        this.coustumer = this.hall.getCostumer();
        /*
        if(hallCostumer === "ארמונות-לב") hallCostumer = hallCostumer.split('-')[0]+ " "+ hallCostumer.split('-')[1];
    
        if(hallCostumer in localStorage){
          this.coustumer = JSON.parse(localStorage.getItem(hallCostumer));
          console.log("From local storage");
        }else {
          this.hall.costumerEmitter$.subscribe(coutumer => this.coustumer = coutumer);
          console.log("From Emitter");
        }
        //  this.hall.getById(hallCostumer)
        */
    };
    HallAboutComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-hall-about',
            template: __webpack_require__(/*! ./hall-about.component.html */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.html"),
            styles: [__webpack_require__(/*! ./hall-about.component.css */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.css")]
        }),
        __metadata("design:paramtypes", [_costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_1__["HallsDataService"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"]])
    ], HallAboutComponent);
    return HallAboutComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.css":
/*!*********************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.css ***!
  \*********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ".inputTypeNumber{\r\n    height: calc(2.25rem + 2px);\r\n    padding: .375rem .25rem;\r\n    font-size: 1rem;\r\n    line-height: 1.5;\r\n    color: #495057;\r\n    background-color: #fff;\r\n    background-clip: padding-box;\r\n    border: 1px solid #ced4da;\r\n    border-radius: .25rem;\r\n    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;\r\n    position: relative;\r\n}\r\n\r\n.inputTypeNumber input, .inputTypeNumber select{\r\n    border: none;\r\n    outline: none;\r\n}\r\n\r\n.inputTypeNumber textarea{\r\n    border: none;\r\n    outline: none;\r\n    resize: none;\r\n    width: 100%;\r\n    height: 100%;\r\n}\r\n\r\ndiv.textarea{\r\n\r\n    min-height: 100px;\r\n}\r\n\r\ninput:valid + span:after, select:valid + span:after, textarea:valid + span:after {\r\n    content: '✓';\r\n    color: #26b72b;\r\n    padding-left: 5px;\r\n}\r\n\r\ninput:invalid + span:after, select:invalid + span:after,  textarea:invalid + span:after {\r\n    content: '✖';\r\n    color: #f00;\r\n    padding-left: 5px;\r\n}\r\n\r\ntextarea.note {\r\n    \r\n    text-align: right;\r\n    text-indent: 0px;\r\n    /* color: #26b72b; */\r\n    /* word-break: break-all; */\r\n    /* overflow: hidden; */\r\n    white-space: wrap;\r\n    box-sizing:border-box;\r\n    min-height: 40%;\r\n\tdirection:rtl;\r\n\tdisplay:inline;\r\n\tmin-height:30%;\r\n\tline-height:1.5;\r\n\tpadding:15px 15px 30px;\r\n\tborder-radius:3px;\r\n\tborder:1px solid #F7E98D;\r\n\tfont:13px Tahoma, cursive;\r\n\ttransition:box-shadow 0.5s ease;\r\n\tbox-shadow:0 4px 6px rgba(0,0,0,0.1);\r\n\tfont-smoothing:subpixel-antialiased;\r\n\tbackground:linear-gradient(#F9EFAF, #F7E98D);\r\n\tbackground:-webkit-linear-gradient(#F9EFAF, #F7E98D);\r\n}\r\n\r\na.googleFont {\r\n    text-decoration: none;\r\n}\r\n\r\n.material-icons {\r\n    position: relative;\r\n    top: 5px;\r\n}"

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html":
/*!**********************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "\n<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" [formGroup]=\"addCostumerForm\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"company\">שם החברה</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.company }}\" class=\"col-11 px-1\" type=\"text\" name=\"company\" id=\"company\" formControlName=\"company\"\n            required />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n\n        <div *ngIf=\"f.company.invalid && f.company.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <a class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </a>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"contact\">איש קשר</label>\n\n        <div class=\"inputTypeNumber\">\n          <input value=\"{{ costumer.contact }}\" class=\"col-11 px-1\" type=\"text\" name=\"contact\" id=\"contact\" formControlName=\"contact\"\n            required />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.contact.invalid && f.contact.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"phone\">פלאפון/טלפון</label>\n\n        <div class=\"inputTypeNumber\">\n          <input value=\"{{ costumer.tel }}\" type=\"tel\" id=\"phone\" name=\"phone\" class=\"col-11 px-1\" formControlName=\"phone\" [pattern]=\"phoneNum\"\n            required />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.phone.invalid && f.phone.touched\" class=\"invalid-feedback d-block\">* נא למלא שדה בתבנית המתאימה לפאלפון/טלפון</div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"address\">כתובת</label>\n\n        <div class=\"inputTypeNumber\">\n          <input value=\"{{ costumer.address }}\" class=\"col-11 px-1\" type=\"text\" name=\"address\" id=\"address\" formControlName=\"address\"\n            required />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.address.invalid && f.address.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"email\">אימייל</label>\n        <div class=\"inputTypeNumber\">\n          <input value=\"{{ costumer.email }}\" class=\"col-11 px-1\" type=\"email\" name=\"email\" id=\"email\" formControlName=\"email\" [pattern]=\"emailPatteren\"\n            required />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.email.invalid && f.email.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- about edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col-sm-12 form-group py-3\">\n        <label for=\"title\">תיאור או כותרת החברה</label>\n\n        <div class=\"inputTypeNumber textarea\">\n          <textarea value=\"{{ costumer.title }}\" class=\"col-11 px-1\" type=\"text\" name=\"title\" id=\"title\" formControlName=\"title\" required\n            minlength=\"12\">\n                          \n                  </textarea>\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.title.invalid && f.title.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n\n      <div class=\"col-sm-12 form-group border p-3\">\n        <label for=\"about\">אודות החברה</label>\n        <textarea wrap=\"hard\" value=\"{{ costumer.discription | removeWhiteSpace  }}\" class=\"w-100 form-control text-right note\" (mouseenter)=\"textAreaAdjust($event)\"\n          (mouseleave)=\"textAreamouseleave($event)\" type=\"text\" name=\"about\" id=\"about\" formControlName=\"about\" required>\n        </textarea>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"h-100 p-3\">\n        <!-- Chose loggo -->\n        <p class=\"pb-2 m-0\">לוגו החברה</p>\n        <div class=\"form-group border bg-white clearfix\">\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"loggo\">\n            <span>\n              החלף לוגו\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"loggo\" style=\"display: none;\" accept=\"image/*\" required (change)=\"selectedFiles($event,'loggo')\" />\n        </div>\n\n        <div class=\"border border-success bg-dark d-flex-row\">\n\n          <img style=\"height:90px; cursor: pointer\" class=\"m-1\" [src]=\"costumer.loggo\" alt=\"{{ costumer.loggo | splitText:'/' | splitText:'.':0 }}\"\n            (click)=\"activeItem(ii)\" />\n        </div>\n\n        <div class=\"btn-group \">\n\n        </div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n        <!-- Chose video -->\n        <p class=\"pb-2 m-0\">סרטון תדמיתי</p>\n        <div class=\"form-group border bg-white clearfix\">\n\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"files2\">\n            <span>\n              הוסף סרטון\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n\n          <input type=\"file\" id=\"files2\" style=\"display: none;\" accept=\"video/*\" (change)=\"selectedFiles($event,'video')\" required\n          />\n        </div>\n        <div *ngFor=\"let video of videos;let ii = index\" class=\"border border-success bg-dark d-flex-row\">\n          <video controls=true height=\"100%\">\n            <source type=\"video/mp4\" [src]=\"video\" />\n          </video>\n        </div>\n        <div class=\"btn-group w-100 bg-white position-relative\">\n\n        </div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n        <!-- Chose image dallery -->\n        <p class=\"pb-2 m-0\">גלרית תמונות</p>\n        <div class=\"form-group bg-white border clearfix\">\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"file\">\n            <span>\n              הוסף תמונות\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"file\" style=\"display: none;\" accept=\"image/*\" required multiple (change)=\"selectedFiles($event,'galleries')\"\n          />\n        </div>\n        <div class=\"border border-success bg-dark d-flex-row\">\n\n          <img style=\"height:90px; cursor: pointer\" class=\"m-1\" *ngFor=\"let gallery of galleries;let ii = index\" [src]=\"gallery\"\n            alt=\"{{ gallery | splitText:'/' | splitText:'.':0 }}\" (click)=\"activeItem(ii)\" />\n        </div>\n        <div class=\"btn-group\">\n\n        </div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n\n      </div>\n\n    </div>\n    <div class=\"col-sm-12\">\n      \n        <div class=\"btn-group w-100 p-3 bg-light\">\n          <button class=\"btn btn-success\" type=\"submit\">העלה</button>\n          <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n          <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        </div>\n      </div>\n\n  </div>\n</form>"

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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/fesm5/forms.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/fesm5/http.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
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
var AllEditComponent = /** @class */ (function () {
    function AllEditComponent(router, halls, http) {
        this.router = router;
        this.halls = halls;
        this.http = http;
        this.phoneNum = '^((?=(02|03|04|08|09))[0-9]{2}[0-9]{3}[0-9]{4}|(?=(05|170|180))[0-9]{3}[0-9]{3}[0-9]{4})';
        this.emailPatteren = '^[a-z]+[a-zA-Z_\\d]*@[A-Za-z]{2,10}\.[A-Za-z]{2,3}(?:\.?[a-z]{2})?$';
        this.arrayFlies = [];
        this.filesSize = 0;
        this.guard = [];
        this.masseges = [];
    }
    AllEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["find"])(function (val) { return val['id']; })).subscribe(function (cost) {
            var cId = (cost && cost["user_id"]) ? cost["user_id"] : false;
            var uId = _this.http.authUser["id"];
            console.log('costumerId: ' + cId + " userId " + uId);
            if (cId === uId) {
                var id = cost.id;
                _this.costumer = cost;
                var gal = _this.halls.getGallery(id);
                _this.galleries = gal['image'];
                _this.videos = gal['video'];
                _this.apiKey = _this.http.getApiKey();
                _this.formInt();
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
    AllEditComponent.prototype.canDeactivate = function () {
        if (this.addCostumerForm.dirty || this.addCostumerForm.touched || this.filesSize >= 1 || this.arrayFlies.length >= 1) {
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
            'company': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'businessType': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'title': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'contact': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'phone': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'email': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'address': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]),
            'about': new _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormControl"](null, [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required])
        });
    };
    AllEditComponent.prototype.onSubmit = function () {
        var _this = this;
        var input = new FormData();
        var posterUrl = "http://ethio:8080/api/auth/costumers";
        var formInputs = this.addCostumerForm.value;
        var urls = formInputs['businessType'] + "/" + formInputs['company'];
        var checkFilesSizes = (Math.round(this.filesSize / Math.pow(1024, 2)) > 6) ? true : false;
        var errors = {};
        this.masseges = [];
        var counter = 0;
        formInputs['loggo'] = null;
        // console.log(this.filesSize + " : " +Math.round(this.filesSize / Math.pow(1024,2) ));
        // console.log(this.filesSize + " : " +Math.round(Math.pow(1024,2)* 6 ) / (Math.pow(1024,2)));
        for (var _i = 0, _a = this.arrayFlies; _i < _a.length; _i++) {
            var ii = _a[_i];
            if (counter <= 3 && ii.target === "galleries")
                counter++;
            var extractTargetName = urls + "/" + ii.target + "/" + ii.name.split('.')[0];
            (ii.target === "loggo") ? formInputs['loggo'] = extractTargetName + '.' + ii.name.split('.')[(ii.name.split('.').length) - 1] : null;
            input.append(extractTargetName, ii, ii.name);
        }
        errors.galleries = (counter < 3) ? errors.galleries = "גלריית התמונות חייב להכיל לפחות 3 תמונות" : null;
        errors.fileSize = (checkFilesSizes) ? errors.fileSize = this.formatBytes(this.filesSize) + " : " + "גודל הקבצים גדולה מדי." : null;
        errors.loggo = (typeof formInputs['loggo'] === 'undefined' || !formInputs['loggo']) ? errors.loggo = "העלה תמונת לוגו או אייקון של החברה." : null;
        errors.formInputs = (!this.addCostumerForm.valid) ? errors.formInputs = "ודא והשלם פרטים נכונים" : null;
        for (var ii in errors) {
            if (errors.hasOwnProperty(ii)) {
                if (errors[ii])
                    this.masseges.push(errors[ii]);
            }
        }
        console.log(this.masseges);
        if (this.masseges.length === 0) {
            console.log(this.apiKey);
            input.append('formInputs', JSON.stringify(formInputs));
            this.halls.storeData(posterUrl, input, {
                headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_5__["HttpHeaders"]({
                    'Authorization': 'Bearer ' + this.apiKey,
                })
            })
                .subscribe(function (evt) {
                console.log(evt);
                _this.masseges.push(evt["massege"]);
                setTimeout(function () {
                    // this.router.navigate(["/halls_events"]);
                }, 5000);
            }, function (err) {
                // this.errors.push(err["error"]["message"]);
                // this.errors.push(err["status"]+" נא להחתחבר מחדש");
                if (err["status"] === 401) {
                    _this.http.nextIslogged(false);
                    window.sessionStorage.removeItem('user_key');
                    _this.router.navigate(["/login"]);
                }
            });
        }
        // ,
        // reportProgress: true,
        // observe: 'events'
        //console.log(this.addCostumerForm.value);
        //rs.subscribe(evt => console.log(evt));
    };
    AllEditComponent.prototype.formatBytes = function (a) {
        if (0 === a)
            return "0 Bytes";
        var c = 1024, d = 2, e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"], f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f];
    };
    AllEditComponent.prototype.selectedFiles = function (event, elemTarget) {
        var _this = this;
        if (!event.target.files[0] || this.guard.indexOf(elemTarget) >= 0) {
            console.log("You cant uplaod twise " + elemTarget);
            return;
        }
        if (elemTarget !== "galleries" && this.guard.indexOf(elemTarget) == -1)
            this.guard.push(elemTarget);
        var files = event.target.files;
        var _loop_1 = function (theFile) {
            var elemName = theFile.name.split('.')[0];
            theFile.id = elemName + '_' + theFile.size;
            theFile.target = elemTarget;
            if (!this_1.fileContains(theFile)) {
                var targetElement_1 = event.target.parentElement.nextElementSibling;
                this_1.filseReader(theFile).then(function (res) {
                    targetElement_1.appendChild(_this.createElements(res));
                }, function (error) {
                    console.log(error);
                });
            }
        };
        var this_1 = this;
        // let videoType = ['video/3gpp', 'video/H261', 'video/H263', 'video/H264', 'video/JPEG', 'video/mp4', 'video/mpeg'].indexOf(files[0].type);
        // let imageType = ['image/png', 'image/jpeg', 'image/gif'].indexOf(files[0].type);
        for (var _i = 0, files_1 = files; _i < files_1.length; _i++) {
            var theFile = files_1[_i];
            _loop_1(theFile);
        }
    };
    AllEditComponent.prototype.fileContains = function (theFile) {
        var isTrueOrFalse = false;
        var gal = this.arrayFlies;
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                isTrueOrFalse = true;
            }
        }
        return isTrueOrFalse;
    };
    AllEditComponent.prototype.unSelectFiles = function (evt) {
        var aTag = evt.target.parentElement;
        var div = aTag.parentElement;
        var parent = div.parentElement;
        var childrens = parent.children;
        var index = aTag.getAttribute('data-target');
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            if (childrens[ii] && aTag.id === childrens[ii].id) {
                var subtractSize = childrens[ii].id.split('_');
                var posSubtrat = subtractSize[subtractSize.length - 1];
                index = (index == "loggo") ? this.guard.indexOf(index) : (index == "video") ? this.guard.indexOf(index) : null;
                this.isExsist(childrens[ii]);
                if (index === 0 || index >= 0) {
                    this.guard.splice(index, 1);
                }
                this.filesSize -= posSubtrat;
                parent.removeChild(childrens[ii]);
                break;
            }
        }
    };
    AllEditComponent.prototype.createElements = function (elem) {
        var div = document.createElement('DIV');
        var aTag = document.createElement('A');
        var span = document.createElement('SPAN');
        var elemType = elem.type.split("/")[0];
        aTag.id = elem.id;
        if (elem.target === "loggo" || elem.target === "video")
            aTag.setAttribute('data-target', elem.target);
        div.id = elem.id;
        this.arrayFlies.push(elem);
        this.filesSize += elem.size;
        aTag.className = "close bg-secondary";
        span.setAttribute('aria-hidden', 'true');
        span.innerHTML = '&times';
        aTag.addEventListener("click", this.unSelectFiles.bind(this));
        aTag.appendChild(span);
        div.appendChild(aTag);
        if (elemType === "image") {
            var img = new Image();
            img.src = elem.src;
            img.className = "py-1 border border-info rounded";
            img.setAttribute("height", "100");
            img.setAttribute("alt", elem.type);
            div.appendChild(img);
        }
        else if (elemType === "video") {
            var video = document.createElement('video');
            var source = document.createElement('source');
            video.controls = true;
            video.height = 100;
            source.type = elem.type;
            source.src = elem.src;
            video.appendChild(source);
            div.appendChild(video);
        }
        return div;
    };
    AllEditComponent.prototype.filseReader = function (elem) {
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
    AllEditComponent.prototype.reset = function () {
        this.addCostumerForm.reset();
    };
    AllEditComponent.prototype.close = function () {
        this.router.navigate(['../']);
    };
    AllEditComponent.prototype.isExsist = function (theFile) {
        var isTrueOrFalse = false;
        var gal = this.arrayFlies;
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                // console.log(gal[idx].id + " : " + theFile.id);
                this.arrayFlies.splice(idx, 1);
                break;
            }
        }
        return isTrueOrFalse;
    };
    AllEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-all-edit',
            template: __webpack_require__(/*! ./all-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.html"),
            styles: [__webpack_require__(/*! ./all-edit.component.css */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.css")]
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"], _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__["HallsDataService"], _http_service__WEBPACK_IMPORTED_MODULE_6__["HttpService"]])
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

module.exports = "<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" [formGroup]=\"addCostumerForm\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"company\">שם החברה</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.company }}\" class=\"col-11 px-1\" type=\"text\" name=\"company\" id=\"company\"\n            formControlName=\"company\" required #company />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div class=\"text-right\">\n          <div *ngIf=\"(masseges['company'])\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['company']['type']\">\n            <p class=\"p-0 m-0\"> * {{ masseges['company']['company'] }} </p>\n          </div>\n        </div>\n\n        <div *ngIf=\"f.company.invalid && f.company.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(company)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(company)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(company)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n        <!-- JSON.stringify(masseges)  -->\n\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"contact\">איש קשר</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.contact }}\" class=\"col-11 px-1\" type=\"text\" name=\"contact\" id=\"contact\"\n            formControlName=\"contact\" required #contact />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div class=\"text-right\">\n            <div *ngIf=\"masseges['contact']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['contact'].type\">\n              <p class=\"p-0 m-0\"> * {{ masseges['contact'].contact }} </p>\n            </div>\n          </div>\n        <div *ngIf=\"f.contact.invalid && f.contact.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(contact)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(contact)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(contact)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"tel\">פלאפון/טלפון</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.tel }}\" type=\"tel\" id=\"tel\" name=\"tel\" class=\"col-11 px-1\" formControlName=\"tel\"\n            [pattern]=\"phoneNum\" required #tel />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.tel.invalid && f.tel.touched\" class=\"invalid-feedback d-block\">* נא למלא שדה בתבנית המתאימה\n          לפאלפון/טלפון</div>\n        <div class=\"text-right\">\n            <div *ngIf=\"masseges['tel']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['tel'].type\">\n                <p class=\"p-0 m-0\"> * {{ masseges['tel'].tel }} </p>\n              </div>\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(tel)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(tel)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(tel)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"address\">כתובת</label>\n\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.address }}\" class=\"col-11 px-1\" type=\"text\" name=\"address\" id=\"address\"\n            formControlName=\"address\" required #address />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.address.invalid && f.address.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"text-right\">\n            <div *ngIf=\"masseges['address']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['address'].type\">\n                <p class=\"p-0 m-0\"> * {{ masseges['address'].address }} </p>\n              </div>\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(address)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(address)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(address)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n\n      <div class=\"col form-group py-2 my-0\">\n        <label for=\"email\">אימייל</label>\n        <div class=\"inputTypeNumber my-2\">\n          <input value=\"{{ costumer.email }}\" class=\"col-11 px-1\" type=\"email\" name=\"email\" id=\"email\" formControlName=\"email\"\n            [pattern]=\"emailPatteren\" required #email />\n\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.email.invalid && f.email.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div  class=\"text-right\">\n            <div *ngIf=\"masseges['email']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['email'].type\">\n                <p class=\"p-0 m-0\"> * {{ masseges['email'].email }} </p>\n              </div>\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(email)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(email)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(email)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- about edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"col-sm-12 form-group py-3\">\n        <label for=\"title\">תיאור או כותרת החברה</label>\n\n        <div class=\"inputTypeNumber my-2 textarea\">\n          <textarea value=\"{{ costumer.title }}\" class=\"col-11 px-1\" type=\"text\" name=\"title\" id=\"title\"\n            formControlName=\"title\" required minlength=\"12\" #title>\n          </textarea>\n          <span class=\"validity float-left\"></span>\n        </div>\n        <div *ngIf=\"f.title.invalid && f.title.touched\" class=\"invalid-feedback d-block\">* שדה חובה</div>\n        <div class=\"text-right\">\n            <div *ngIf=\"masseges['title']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['title'].type\">\n                <p class=\"p-0 m-0\"> * {{ masseges['title'].title }} </p>\n          </div>\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(title)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(title)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(title)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n\n      <div class=\"col-sm-12 form-group border p-3\">\n\n        <label for=\"discription\">אודות החברה</label>\n        <textarea wrap=\"hard\" value=\"{{ costumer.discription | removeWhiteSpace  }}\" class=\"w-100 form-control text-right note\"\n          (mouseenter)=\"textAreaAdjust($event)\" (mouseleave)=\"textAreamouseleave($event)\" type=\"text\" name=\"discription\"\n          id=\"discription\" formControlName=\"discription\" minlength=\"6\" #discription>\n\n        </textarea>\n        <div class=\"text-right\">\n            <div *ngIf=\"masseges['discription']\" [ngClass]=\"'my-1 p-1 alert alert-'+ masseges['discription'].type\">\n                <p class=\"p-0 m-0\"> * {{ masseges['discription'].discription }} </p>\n          </div>\n        </div>\n        <div class=\"my-2\">\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"update(discription)\">\n            <span>\n              עדכן\n              <i class=\"material-icons text-success\">\n                person_add\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"inputReset(discription)\">\n            <span>\n              נקה\n              <i class=\"material-icons text-warning\">\n                clear\n              </i>\n            </span>\n          </a>\n          <a class=\"ml-1 px-2 py-2 my-2 border rounded text-center active\" (click)=\"default(discription)\">\n            <span>\n              מקור\n              <i class=\"material-icons text-danger\">\n                redo\n              </i>\n            </span>\n          </a>\n        </div>\n      </div>\n    </div>\n\n  </div>\n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n\n    <div class=\"col-sm-12\">\n\n      <div class=\"btn-group w-100 p-3 bg-light\">\n        <button class=\"btn btn-success\" type=\"submit\">שמור הכל</button>\n        <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n        <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        <a class=\"btn btn-info\" (click)=\"allTodefault()\">שחזר ברירת מחדל</a>\n      </div>\n    </div>\n\n  </div>\n</form>"

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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
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
        this.masseges = {};
    }
    BasicEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["find"])(function (val) { return val['id']; })).subscribe(function (cost) {
            var cId = (cost && cost["user_id"]) ? cost["user_id"] : false;
            var authUser = _this.http.authUser;
            var uId = authUser ? authUser["id"] : false;
            console.log('costumerId: ' + cId + " userId " + uId);
            if (cId === uId) {
                _this.costumer = cost;
                _this.apiKey = _this.http.getApiKey();
                _this.formInt();
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
    BasicEditComponent.prototype.update = function (costumer) {
        var comp = costumer['id'];
        var controls = this.addCostumerForm.controls[comp];
        var items = this.valForm.validate(controls, this.costumer, comp);
        this.masseges = items['errors'];
        if (items['status'])
            this.send(items['success']);
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
        console.log("canDeactivate called!");
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
        // this.masseges = [];
        var controls = this.addCostumerForm.controls;
        var items = this.valForm.validate(controls, this.costumer);
        //if(items['status'] === false) this.masseges.push(items['errors']);
        console.log(items);
        if (items['status']) {
            //this.send(items); 
        }
        else {
            this.masseges = items['errors'];
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
        var updaterUrl = "http://ethio:8080/api/costumers/" + this.costumer["id"];
        console.log(body);
        this.halls.patchData(updaterUrl, body, {
            headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_5__["HttpHeaders"]({
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            })
        }).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["tap"])(function (res) { return console.log(res); }))
            .subscribe(function (evt) {
            //console.log(evt);
            var errors = evt['errors'];
            var success = evt['success'];
            var msgOb = {};
            if (errors) {
                msgOb['errors'] = {};
                var _loop_1 = function (ii) {
                    msgOb['errors'][ii] = {};
                    errors[ii].forEach(function (element) {
                        msgOb['errors'][ii][ii] = element;
                        msgOb['errors'][ii]['type'] = "warning";
                    });
                };
                for (var ii in errors) {
                    _loop_1(ii);
                }
                _this.masseges = msgOb['errors'];
            }
            if (success) {
                msgOb['success'] = {};
                for (var ii in success) {
                    msgOb['success'][ii] = {};
                    msgOb['success'][ii][ii] = success[ii];
                    msgOb['success'][ii]['type'] = "success";
                }
                _this.masseges = msgOb['success'];
            }
        }, function (err) {
            console.log(err);
            if (err["status"] === 401) {
                console.log("Errrorss 401");
                _this.router.navigate(["/login"]);
                //this.errors.push(err["error"]["message"]);
                //this.errors.push(err["status"]+" נא להחתחבר מחדש");
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
            _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__["HallsDataService"],
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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
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
        this.halls.getCostumer().pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_5__["find"])(function (val) { return val['id']; }))
            .subscribe(function (cost) {
            var cId = (cost && cost["user_id"]) ? cost["user_id"] : false;
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
        __metadata("design:paramtypes", [_http_service__WEBPACK_IMPORTED_MODULE_1__["HttpService"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"], _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_4__["HallsDataService"]])
    ], HallEditComponent);
    return HallEditComponent;
}());



/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.css":
/*!*************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.css ***!
  \*************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ""

/***/ }),

/***/ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html":
/*!**************************************************************************************!*\
  !*** ./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html ***!
  \**************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "\n<!-- *ngIf=\"isTrue\" [formGroup]=\"addCostumerForm\"  -->\n<form *ngIf=\"isTrue | async\" class=\"border border rounded text-right mt-2\" (ngSubmit)=\"onSubmit()\">\n  <!-- basic edite -->\n\n  \n\n  <!-- media and galleries edite -->\n\n  <div class=\"row\">\n    <div class=\"col-sm-12\">\n      <div class=\"h-100 p-3\">\n        <!-- Chose loggo -->\n        <p class=\"pb-2 m-0\">לוגו החברה</p>\n        <div class=\"form-group border bg-white clearfix\">\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"loggo\">\n            <span>\n              החלף לוגו\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"loggo\" style=\"display: none;\" accept=\"image/*\" required (change)=\"selectedFiles($event,'loggo')\" />\n        </div>\n\n        <div class=\"border border-success bg-dark d-flex-row\">\n          <div *ngIf=\"costumer.loggo\" class=\"d-inline\">\n            <a id=\"tzeyur4_598427\" data-target=\"loggo\" class=\"close bg-secondary\"><span aria-hidden=\"true\">×</span></a>\n            <img style=\"height:90px; cursor: pointer\" class=\"m-1\" [src]=\"costumer.loggo\" alt=\"{{ costumer.loggo | splitText:'/' | splitText:'.':0 }}\"\n               />\n          </div>\n        </div>\n        \n        \n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n        <!-- Chose video -->\n        <p class=\"pb-2 m-0\">סרטון תדמיתי</p>\n        <div class=\"form-group border bg-white clearfix\">\n\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"files2\">\n            <span>\n              הוסף סרטון\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n\n          <input type=\"file\" id=\"files2\" style=\"display: none;\" accept=\"video/*\" (change)=\"selectedFiles($event,'video')\" required\n          />\n        </div>\n        \n        <div class=\"border border-success bg-dark d-flex-row\">\n\n          <div *ngFor=\"let video of videos;let ii = index\" class=\"d-inline\">\n            <a id=\"tzeyur4_598427\" data-target=\"loggo\" class=\"close bg-secondary\"><span aria-hidden=\"true\">×</span></a>\n            <video controls=true class=\"w-25\">\n              <source type=\"video/mp4\" [src]=\"video\" />\n            </video>\n          </div>\n        </div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n        <!-- Chose image dallery -->\n        <p class=\"pb-2 m-0\">גלרית תמונות</p>\n        <div class=\"form-group bg-white border clearfix\">\n\n          <span class=\"py-2 px-2 m-0 float-right\">\n            בחר קובץ...\n          </span>\n          <label class=\"border border-primary py-2 px-2 m-0 float-left text-center\" for=\"file\">\n            <span>\n              הוסף תמונות\n              <i class=\"fa fa-search text-success\"></i>\n            </span>\n          </label>\n          <input type=\"file\" id=\"file\" style=\"display: none;\" accept=\"image/*\" required multiple (change)=\"selectedFiles($event,'galleries')\"\n          />\n        </div>\n        <div class=\"border border-success bg-dark d-flex-row\">\n\n          <img style=\"height:90px; cursor: pointer\" class=\"m-1\" *ngFor=\"let gallery of galleries;let ii = index\" [src]=\"gallery\"\n            alt=\"{{ gallery | splitText:'/' | splitText:'.':0 }}\" (click)=\"activeItem(ii)\" />\n        </div>\n        <div class=\"btn-group\">\n\n        </div>\n        <div class=\"col-sm-2 py-1 my-2 border rounded text-center\">\n          <span>\n            עדכן\n            <i class=\"material-icons text-success\">\n              person_add\n            </i>\n          </span>\n        </div>\n\n      </div>\n\n    </div>\n    <div class=\"col-sm-12\">\n      \n        <div class=\"btn-group w-100 p-3 bg-light\">\n          <button class=\"btn btn-success\" type=\"submit\">העלה</button>\n          <a class=\"btn btn-warning\" (click)=\"reset()\">אפס</a>\n          <a class=\"btn btn-danger\" (click)=\"close()\">בטל וסגור</a>\n        </div>\n      </div>\n\n  </div>\n</form>"

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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/common/http */ "./node_modules/@angular/common/fesm5/http.js");
/* harmony import */ var _http_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../../http.service */ "./src/app/http.service.ts");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
/* harmony import */ var _costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../../../../costumers/form-proccesor.service */ "./src/app/costumers/form-proccesor.service.ts");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (undefined && undefined.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




// import { FormGroup, FormControl, Validators } from '@angular/forms';




//  declare let $:any;
var MediaEditComponent = /** @class */ (function () {
    function MediaEditComponent(router, halls, http, valForm) {
        this.router = router;
        this.halls = halls;
        this.http = http;
        this.valForm = valForm;
        this.guard = [];
        this.arrayFlies = [];
        this.masseges = [];
        this.filesSize = 0;
    }
    MediaEditComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.halls.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_6__["find"])(function (val) { return val['id']; })).subscribe(function (cost) {
            var cId = (cost && cost["user_id"]) ? cost["user_id"] : false;
            var uId = _this.http.authUser["id"];
            console.log('costumerId: ' + cId + " userId " + uId);
            if (cId === uId) {
                var id = cost.id;
                var gal = _this.halls.getGallery(id);
                _this.galleries = gal['image'];
                _this.videos = gal['video'];
                _this.costumer = cost;
                _this.apiKey = _this.http.getApiKey();
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
    MediaEditComponent.prototype.canDeactivate = function () {
        if (this.filesSize >= 1 || this.arrayFlies.length >= 1 || this.arrayFlies.guard >= 1) {
            return confirm("לא שמרתה את הפרטים. תרצה לעזוב דף זה בכל זאת?");
        }
        else {
            return true;
        }
    };
    MediaEditComponent.prototype.onSubmit = function () {
        var _this = this;
        var input = new FormData();
        var posterUrl = "http://ethio:8080/api/auth/costumers";
        var formInputs = this.costumer;
        var urls = formInputs['businessType'] + "/" + formInputs['company'];
        var checkFilesSizes = (Math.round(this.filesSize / Math.pow(1024, 2)) > 6) ? true : false;
        var errors = {};
        this.masseges = [];
        var counter = 0;
        // console.log(this.filesSize + " : " +Math.round(this.filesSize / Math.pow(1024,2) ));
        // console.log(this.filesSize + " : " +Math.round(Math.pow(1024,2)* 6 ) / (Math.pow(1024,2)));
        for (var _i = 0, _a = this.arrayFlies; _i < _a.length; _i++) {
            var ii = _a[_i];
            if (counter <= 3 && ii.target === "galleries")
                counter++;
            var extractTargetName = urls + "/" + ii.target + "/" + ii.name.split('.')[0];
            (ii.target === "loggo") ? formInputs['loggo'] = extractTargetName + '.' + ii.name.split('.')[(ii.name.split('.').length) - 1] : null;
            input.append(extractTargetName, ii, ii.name);
        }
        errors.galleries = (counter < 3) ? errors.galleries = "גלריית התמונות חייב להכיל לפחות 3 תמונות" : null;
        errors.fileSize = (checkFilesSizes) ? errors.fileSize = this.formatBytes(this.filesSize) + " : " + "גודל הקבצים גדולה מדי." : null;
        errors.loggo = (typeof formInputs['loggo'] === 'undefined' || !formInputs['loggo']) ? errors.loggo = "העלה תמונת לוגו או אייקון של החברה." : null;
        for (var ii in errors) {
            if (errors.hasOwnProperty(ii)) {
                if (errors[ii])
                    this.masseges.push(errors[ii]);
            }
        }
        console.log(this.masseges);
        if (this.masseges.length === 0) {
            this.halls.storeData(posterUrl, input, {
                headers: new _angular_common_http__WEBPACK_IMPORTED_MODULE_4__["HttpHeaders"]({
                    'Authorization': 'Bearer ' + this.apiKey,
                })
            })
                .subscribe(function (evt) {
                console.log(evt);
                _this.masseges.push(evt["massege"]);
                setTimeout(function () {
                    // this.router.navigate(["/halls_events"]);
                }, 5000);
            }, function (err) {
                if (err["status"] === 401) {
                    _this.http.nextIslogged(false);
                    window.sessionStorage.removeItem('user_key');
                    _this.router.navigate(["/login"]);
                }
            });
        }
    };
    MediaEditComponent.prototype.formatBytes = function (a) {
        if (0 === a)
            return "0 Bytes";
        var c = 1024, d = 2, e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"], f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f];
    };
    MediaEditComponent.prototype.selectedFiles = function (event, elemTarget) {
        var _this = this;
        if (!event.target.files[0] || this.guard.indexOf(elemTarget) >= 0) {
            console.log("You cant uplaod twise " + elemTarget);
            return;
        }
        if (elemTarget !== "galleries" && this.guard.indexOf(elemTarget) == -1)
            this.guard.push(elemTarget);
        var files = event.target.files;
        // let videoType = ['video/3gpp', 'video/H261', 'video/H263', 'video/H264', 'video/JPEG', 'video/mp4', 'video/mpeg'].indexOf(files[0].type);
        // let imageType = ['image/png', 'image/jpeg', 'image/gif'].indexOf(files[0].type);
        console.log(files);
        var _loop_1 = function (theFile) {
            var elemName = theFile.name.split('.')[0];
            theFile.id = elemName + '_' + theFile.size;
            theFile.target = elemTarget;
            if (!this_1.fileContains(theFile)) {
                var targetElement_1 = event.target.parentElement.nextElementSibling;
                this_1.filseReader(theFile).then(function (res) {
                    targetElement_1.appendChild(_this.createElements(res));
                }, function (error) {
                    console.log(error);
                });
            }
        };
        var this_1 = this;
        for (var _i = 0, files_1 = files; _i < files_1.length; _i++) {
            var theFile = files_1[_i];
            _loop_1(theFile);
        }
    };
    MediaEditComponent.prototype.fileContains = function (theFile) {
        var isTrueOrFalse = false;
        var gal = this.arrayFlies;
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                isTrueOrFalse = true;
            }
        }
        return isTrueOrFalse;
    };
    MediaEditComponent.prototype.unSelectFiles = function (evt) {
        var aTag = evt.target.parentElement;
        var div = aTag.parentElement;
        var parent = div.parentElement;
        var childrens = parent.children;
        var index = aTag.getAttribute('data-target');
        for (var ii = 0, len = childrens.length; ii < len; ii++) {
            if (childrens[ii] && aTag.id === childrens[ii].id) {
                var subtractSize = childrens[ii].id.split('_');
                var posSubtrat = subtractSize[subtractSize.length - 1];
                index = (index == "loggo") ? this.guard.indexOf(index) : (index == "video") ? this.guard.indexOf(index) : null;
                this.isExsist(childrens[ii]);
                if (index === 0 || index >= 0) {
                    this.guard.splice(index, 1);
                }
                this.filesSize -= posSubtrat;
                parent.removeChild(childrens[ii]);
                break;
            }
        }
    };
    MediaEditComponent.prototype.createElements = function (elem) {
        var div = document.createElement('DIV');
        var aTag = document.createElement('A');
        var span = document.createElement('SPAN');
        var elemType = elem.type.split("/")[0];
        aTag.id = elem.id;
        if (elem.target === "loggo" || elem.target === "video")
            aTag.setAttribute('data-target', elem.target);
        div.id = elem.id;
        div.className = "d-inline";
        this.arrayFlies.push(elem);
        this.filesSize += elem.size;
        aTag.className = "close bg-secondary";
        span.setAttribute('aria-hidden', 'true');
        span.innerHTML = '&times';
        aTag.addEventListener("click", this.unSelectFiles.bind(this));
        aTag.appendChild(span);
        div.appendChild(aTag);
        if (elemType === "image") {
            var img = new Image();
            img.src = elem.src;
            img.className = "py-1 border border-info rounded";
            img.setAttribute("height", "100");
            img.setAttribute("alt", elem.type);
            div.appendChild(img);
        }
        else if (elemType === "video") {
            var video = document.createElement('video');
            var source = document.createElement('source');
            video.controls = true;
            video.height = 100;
            source.type = elem.type;
            source.src = elem.src;
            video.appendChild(source);
            div.appendChild(video);
        }
        return div;
    };
    MediaEditComponent.prototype.filseReader = function (elem) {
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
    MediaEditComponent.prototype.reset = function () {
        location.reload();
    };
    MediaEditComponent.prototype.close = function () {
        this.router.navigate(['../']);
    };
    MediaEditComponent.prototype.isExsist = function (theFile) {
        var isTrueOrFalse = false;
        var gal = this.arrayFlies;
        for (var idx = 0, len = gal.length; idx < len; idx++) {
            if (gal[idx].id === theFile.id) {
                // console.log(gal[idx].id + " : " + theFile.id);
                this.arrayFlies.splice(idx, 1);
                break;
            }
        }
        return isTrueOrFalse;
    };
    MediaEditComponent = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Component"])({
            selector: 'app-media-edit',
            template: __webpack_require__(/*! ./media-edit.component.html */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.html"),
            styles: [__webpack_require__(/*! ./media-edit.component.css */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.css")]
        }),
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["Router"], _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__["HallsDataService"], _http_service__WEBPACK_IMPORTED_MODULE_5__["HttpService"], _costumers_form_proccesor_service__WEBPACK_IMPORTED_MODULE_7__["FormProccesorService"]])
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
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/router */ "./node_modules/@angular/router/fesm5/router.js");
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rxjs */ "./node_modules/rxjs/_esm5/index.js");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! rxjs/operators */ "./node_modules/rxjs/_esm5/operators/index.js");
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






var HallMediaComponent = /** @class */ (function () {
    function HallMediaComponent(route, hall, http) {
        this.route = route;
        this.hall = hall;
        this.http = http;
    }
    ;
    HallMediaComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.hall.costumerObsever.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_4__["first"])()).subscribe(function (data) {
            // console.log(data);
            var id = data.id;
            _this.costumer = Object(rxjs__WEBPACK_IMPORTED_MODULE_3__["of"])(data);
            var gal = _this.hall.getGallery(id);
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
        __metadata("design:paramtypes", [_angular_router__WEBPACK_IMPORTED_MODULE_1__["ActivatedRoute"], _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_2__["HallsDataService"], _http_service__WEBPACK_IMPORTED_MODULE_5__["HttpService"]])
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

module.exports = "<hr class=\"d-sm-none\">\n<div *ngIf=\"(costumerProps | async)?.company != null\" class=\"row text-right\">\n    <div class=\"costum-query col-xs-12 col-xl-4\">\n        <div class=\"row card-group\">\n\n            <div class=\"col-sm-12 col-md-7 d-md-flex d-xl-block col-xl-12 mb-3\">\n                <div class=\"w-100 border text-center\">\n\n                    <div class=\"shadow-sm bg-light\">\n                        <h5 class=\"p-3 text-success\">{{ (costumerProps | async)?.company }}</h5>\n                    </div>\n                    <img class=\"card-img-top py-3 w-25 m-auto\" [src]=\"(costumerProps | async)?.loggo\" [alt]=\"(costumerProps | async)?.company\" />\n\n                    <div class=\"card-footer col\">\n                        <div class=\"div p-3\">\n                            <a routerLink=\"/halls-events\" class=\"btn btn-outline-success btn-sm\">\n                                <i class=\"fa fa-location-arrow\"></i>\n                                חזור\n                            </a>\n                            <a class=\"btn btn-warning btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\">\n                                <i class=\"fa fa-envelope text-success\"></i>\n\n                                שלח הודעה</a>\n                            <a routerLink=\"media\" class=\"btn btn-danger btn-sm\">מדיה וגלריה</a>\n                            <a routerLink=\"about\" class=\"btn btn-info btn-sm\">אודות</a>\n                            \n                        </div>\n                    </div>\n                </div>\n                <div *ngIf=\"isTrue | async\" class=\"btn-group border mt-2 w-100\">\n                    <div class=\"div p-3\">\n                            <a routerLink=\"/halls-events/{{ (costumerProps | async)?.company | strSpliter  }}/create\" class=\"btn btn-primary btn-sm\">\n                                <!-- <i class=\"fa fa-location-arrow\"></i> -->\n                                + צור דף\n                            </a>\n                            <a routerLink=\"/halls-events/{{ (costumerProps | async)?.company | strSpliter }}/edit/basic\" class=\"btn btn-danger text-light btn-sm\">\n                                <i class=\"fa fa-pencil\"></i>\n                                ערוך דף \n                                <!-- <i class=\"material-icons text-primary\"> edit</i> -->\n                            </a>\n                    </div>\n                    \n                </div>\n            </div>\n\n            <div class=\"col-sm-12 col-md-5 d-md-flex d-xl-block col-xl-12 text-right mb-3\">\n                <div class=\"card  border border-warning\">\n                    <div class=\"card-header bg-warning\">\n                        <h4 class=\"card-title text-center text-light\">\n                            צור קשר\n                        </h4>\n                    </div>\n                    <div class=\"card-body\">\n                        <div class=\"row\">\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> {{ (costumerProps | async)?.contact }}\n                            </div>\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> {{ (costumerProps | async)?.tel }}\n                            </div>\n                            <div class=\"col-sm-12 border-bottom p-2\">\n                                <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> {{ (costumerProps | async)?.address }}\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n\n            <div class=\"col-sm-12 col-md-col-xl-12\">\n                <div class=\"border border-danger text-right\">\n                    <div class=\"card-header bg-danger\">\n                        <h4 class=\"text-center text-light\">\n                            {{ (costumerProps | async)?.company }}\n                            <span class=\"font-weight-bold text-warning\">*מבצעים</span>\n                        </h4>\n                    </div>\n                    <div class=\"card-body\">\n                        <div class=\"row\">\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                            <div class=\"col-sm-4 col-xl-12 border-bottom p-2\">\n                                <p>\n                                    <span class=\"font-weight-bold text-danger rounded p-1\">*</span>\n                                    מבצע מטורף חתונה מעל 500 אורחים ,סוף שבוע האולם במתנה כן זה אמיתי מהרו לסגור עוד היום כי מבצע כזה לא תמצאו בשום מקום אחר.\n                                </p>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div class=\"costum-query-datil col-xs-12 col-xl-8\">\n        <router-outlet></router-outlet>\n    </div>\n</div>\n\n<!-- (imgLoggo)=\"(costumerProps | async).loggo\" -->\n<!--Contact Modal -->\n\n<div class=\"modal fade bd-example-modal-lg1\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n    <div class=\"modal-dialog modal-lg\">\n\n        <div class=\"border-danger modal-content col-sm-6 mx-auto text-right\">\n            <button type=\"button\" class=\"close float-left\" data-dismiss=\"modal\" aria-label=\"Close\">\n                <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n            </button>\n            <h5 class=\"text-success\">ארמונות לב המחודשים</h5>\n            <div class=\"card-body\">\n                <div class=\"row\">\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> הנהלה\n                    </div>\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> 08-6640450 או 08-664042\n                    </div>\n                    <div class=\"col-sm-12 border-bottom p-2\">\n                        <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> אשקלון, ההסתדרות 40 (קניון לב אשקלון)\n                    </div>\n                </div>\n            </div>\n            <div class=\"mb-2\">\n                <a class=\"btn btn-danger btn-sm float-left text-white\" data-dismiss=\"modal\">סגור</a>\n            </div>\n        </div>\n    </div>\n</div>\n\n\n<!-- form model -->\n\n<div class=\"modal fade bd-example-modal-lg container\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n    <div class=\"modal-dialog modal-lg row\">\n        <div class=\"modal-content col-sm-8 mx-auto text-right\">\n\n            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n                <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n            </button>\n            <div class=\"p-3\">\n                <h2 class=\"text-right text-success\">שלח הודעה</h2>\n                <p>\n                    לקוחות יקרים, מלאו את הפרטים בשדות הבאים ונחזור אליכם תוך זמן קצר\n                    <span class=\"text-danger\">\n\n                        צוות אתיופיה אירועים.\n                    </span>\n                </p>\n            </div>\n\n            <hr>\n            <form class=\"p-3\">\n                <div class=\"form-group\">\n                    <input type=\"text\" class=\"form-control\" placeholder=\"שם מלא\">\n                </div>\n                <div class=\"form-group\">\n                    <input type=\"city\" class=\"form-control\" placeholder=\"עיר מגורים\">\n\n                </div>\n                <div class=\"form-group\">\n                    <input type=\"email\" class=\"form-control\" id=\"email\" placeholder=\"אימייל\">\n                </div>\n                <div class=\"form-group\">\n\n                    <select id=\"rigion\" class=\"rigion\">\n                        <option selected>אזור</option>\n                        <option value=\"1\">צפון</option>\n                        <option value=\"2\">מרכז והשפלה</option>\n                        <option value=\"3\">דרום</option>\n                    </select>\n                </div>\n\n                <div class=\"form-group\">\n                    <input type=\"tel\" class=\"form-control\" id=\"phone\" placeholder=\"טלפון/פלאפון\">\n                </div>\n\n                <div class=\"form-group\">\n                    <textarea class=\"form-control\" id=\"textarea\" rows=\"3\" placeholder=\"הוסף הודעה\"></textarea>\n                </div>\n                <button type=\"submit\" class=\"btn btn-success btn-sm\">\n                    <i class=\"fa fa-envelope\"></i>\n                    שלח</button>\n                <button type=\"button\" class=\"btn btn-danger btn-sm float-left\" data-dismiss=\"modal\">סגור</button>\n            </form>\n        </div>\n    </div>\n</div>"

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
/* harmony import */ var _costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../costumers/halls-data-service */ "./src/app/costumers/halls-data-service.ts");
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
        var user = this.http.authUser;
        var urlParams = this.router.url;
        console.log(urlParams);
        this.halls.getById(pathUrl).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_3__["first"])()).subscribe(function (cost) {
            if (cost && cost['id']) {
                var costumerEmail = cost["email"];
                var authEmail = user ? user['email'] : false;
                _this.costumerProps = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(cost);
                if (!authEmail) {
                    _this.userAuth(cost);
                    return;
                }
                if (costumerEmail === authEmail) {
                    _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(true);
                }
                else {
                    _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(false);
                }
            }
            else {
                setTimeout(function () {
                    var goTo = urlParams.split(pathUrl)[0];
                    _this.router.navigate([goTo]);
                }, 400);
                console.log("error navigations!");
            }
        });
    };
    HallComponent.prototype.userAuth = function (cost) {
        var _this = this;
        var costumerEmail = cost["email"];
        this.http.userPromis().then(function (res) {
            var authEmail = res ? res['user']['email'] : false;
            if (res['status'] && costumerEmail === authEmail)
                _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(true);
        }, function (rez) {
            _this.isTrue = Object(rxjs__WEBPACK_IMPORTED_MODULE_4__["of"])(false);
            // this.http.IntendedUri = urlParams;
            console.log(rez);
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
        __metadata("design:paramtypes", [_costumers_halls_data_service__WEBPACK_IMPORTED_MODULE_1__["HallsDataService"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["Router"], _angular_router__WEBPACK_IMPORTED_MODULE_2__["ActivatedRoute"], _http_service__WEBPACK_IMPORTED_MODULE_5__["HttpService"]])
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

module.exports = "\n<router-outlet *ngIf=\"! path\"></router-outlet>\n<hr class=\"d-sm-none\">\n<div *ngIf=\"path\" class=\"row text-right\">\n  <div class=\"col-sm-12 mb-3\">\n    <h1 class=\"h2 py-2 pr-3 bg-light text-success rounded\">אולמות אירועים</h1>\n    <p class=\"h5 pr-3 pb-3 shadow-sm\">\n      לקוחות יקרים, לפניכם מגוון אולמי אירועים לבחירתכם תהתקשרו לקבלת פרטים ויעוץ\n      <span class=\"text-danger\">\n        צוות אתיופיה אירועים.\n      </span>\n    </p>\n  </div>\n\n  <div class=\"col-sm-10 col-md-6 col-lg-6 col-xl-4 mb-3 d-flex flex-row align-items-stretch\"\n        *ngFor=\"let hallsProp of (hallsProps | async); let idx = index\">\n    <div class=\"border w-100 d-flex align-items-between flex-column\">\n      <div class=\"py-2 mb-2 px-2 shadow-sm bg-light\">\n        <div class=\"d-flex-column\">\n          <h5 class=\"p-2 m-0 bg-success rounded text-white\">{{ hallsProp.company }} {{ idx+1 }}</h5>\n          <img style=\"max-height: 90px\" src=\"{{ hallsProp.loggo }}\" alt=\"{{ hallsProp.company }}\" class=\"rounded img-fluid p-2 mx-auto\" />\n\n        </div>\n\n      </div>\n      <div class=\"mt-auto p-2 bd-highlight\">\n\n        <div class=\"text-right\">\n\n          <h4 class=\"bg-succes text-success\">\n            {{ hallsProp.title }}\n          </h4>\n          <p class=\"card-text p-1\">\n            {{ (hallsProp.discription.length>40)? (hallsProp.discription | slice:0:130)+'..':(hallsProp.discription) }}\n          </p>\n          <a \n            (click)=\"onSelectedLink(hallsProp)\"\n            routerLink=\"{{ hallsProp.id }}/about\"\n            class=\"text-info font-weight-bold\">\n            המשך לקרוא\n            <i class=\"fa fa-angle-double-left\"></i>\n          </a>\n        </div>\n      </div>\n      <!-- justify-content-center  -->\n      <div class=\"card-footer mt-auto p-2 bd-highlight text-right\">\n        <div class=\"text-center\">\n          <a class=\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg1\">\n            \n            <span class=\"text-right float-right\">\n\n              צור קשר\n            </span>\n            <br />\n            <i class=\"material-icons btn btn-success btn-sm float-left\">\n              contact_phone\n            </i>\n\n          </a>\n          <a class=\"btn btn-warning btn-sm\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg\">\n\n            <span class=\"text-right float-right\">\n\n              שלח הודעה\n            </span>\n            <br />\n            <i class=\"material-icons btn-warning btn-sm float-left\">\n              email\n            </i>\n          </a>\n          <a class=\"btn btn-danger btn-sm\" routerLink=\"{{ hallsProp.id }}/media\"\n              (click)=\"onSelectedLink(hallsProp)\">\n\n            <span class=\"text-right float-right\">\n              עבור לעסק\n            </span>\n            <br />\n            <i class=\"material-icons btn btn-danger btn-sm float-left\">\n              navigate_before\n            </i>\n\n          </a>\n        </div>\n      </div>\n    </div>\n  </div>\n  \n</div>\n\n<!--Contact Modal -->\n\n<div class=\"modal fade bd-example-modal-lg1\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n  <div class=\"modal-dialog modal-lg\">\n\n    <div class=\"border-danger modal-content col-sm-6 mx-auto text-right\">\n      <button type=\"button\" class=\"close float-left\" data-dismiss=\"modal\" aria-label=\"Close\">\n        <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n      </button>\n      <h5 class=\"text-success\">ארמונות לב המחודשים</h5>\n      <div class=\"card-body\">\n        <div class=\"row\">\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">איש קשר:</span> הנהלה\n          </div>\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">טלפון/פלאפון:</span> 08-6640450 או 08-664042\n          </div>\n          <div class=\"col-sm-12 border-bottom p-2\">\n            <span class=\"font-weight-bold text-dark rounded p-1\">כתובת:</span> אשקלון, ההסתדרות 40 (קניון לב אשקלון)\n          </div>\n        </div>\n      </div>\n      <div class=\"mb-2\">\n        <a class=\"btn btn-danger btn-sm float-left text-white\" data-dismiss=\"modal\">סגור</a>\n      </div>\n    </div>\n  </div>\n</div>\n\n\n<!-- form model -->\n\n<div class=\"modal fade bd-example-modal-lg container\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\n  <div class=\"modal-dialog modal-lg row\">\n    <div class=\"modal-content col-sm-8 mx-auto text-right\">\n\n      <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n        <span class=\"float-left\" aria-hidden=\"true\">&times;</span>\n      </button>\n      <div class=\"p-3\">\n        <h2 class=\"text-right text-success\">שלח הודעה</h2>\n        <p>\n          לקוחות יקרים, מלאו את הפרטים בשדות הבאים ונחזור אליכם תוך זמן קצר\n          <span class=\"text-danger\">\n\n            צוות אתיופיה אירועים.\n          </span>\n        </p>\n      </div>\n\n      <hr>\n      <form class=\"p-3\">\n        <div class=\"form-group\">\n          <input type=\"text\" class=\"form-control\" placeholder=\"שם מלא\">\n        </div>\n        <div class=\"form-group\">\n          <input type=\"city\" class=\"form-control\" placeholder=\"עיר מגורים\">\n\n        </div>\n        <div class=\"form-group\">\n          <input type=\"email\" class=\"form-control\" id=\"email\" placeholder=\"אימייל\">\n        </div>\n        <div class=\"form-group\">\n\n          <select id=\"rigion\" class=\"rigion\">\n            <option selected>אזור</option>\n            <option value=\"1\">צפון</option>\n            <option value=\"2\">מרכז והשפלה</option>\n            <option value=\"3\">דרום</option>\n          </select>\n        </div>\n\n        <div class=\"form-group\">\n          <input type=\"tel\" class=\"form-control\" id=\"phone\" placeholder=\"טלפון/פלאפון\">\n        </div>\n\n        <div class=\"form-group\">\n          <textarea class=\"form-control\" id=\"textarea\" rows=\"3\" placeholder=\"הוסף הודעה\"></textarea>\n        </div>\n        <button type=\"submit\" class=\"btn btn-success btn-sm\">\n          <i class=\"fa fa-envelope\"></i>\n          שלח</button>\n        <button type=\"button\" class=\"btn btn-danger btn-sm float-left\" data-dismiss=\"modal\">בטל וסגור</button>\n      </form>\n    </div>\n  </div>\n</div>"

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
/* harmony import */ var _angular_common__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/common */ "./node_modules/@angular/common/fesm5/common.js");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/forms */ "./node_modules/@angular/forms/fesm5/forms.js");
/* harmony import */ var _HallsComponent__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HallsComponent */ "./src/app/pages/costumers/halls/HallsComponent.ts");
/* harmony import */ var _hall_hall_component__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./hall/hall.component */ "./src/app/pages/costumers/halls/hall/hall.component.ts");
/* harmony import */ var _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./hall-media/hall-media.component */ "./src/app/pages/costumers/halls/hall-media/hall-media.component.ts");
/* harmony import */ var _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./hall-about/hall-about.component */ "./src/app/pages/costumers/halls/hall-about/hall-about.component.ts");
/* harmony import */ var _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./hall-edit/hall-edit.component */ "./src/app/pages/costumers/halls/hall-edit/hall-edit.component.ts");
/* harmony import */ var _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./hall-edit/all-edit/all-edit.component */ "./src/app/pages/costumers/halls/hall-edit/all-edit/all-edit.component.ts");
/* harmony import */ var _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./hall-edit/basic-edit/basic-edit.component */ "./src/app/pages/costumers/halls/hall-edit/basic-edit/basic-edit.component.ts");
/* harmony import */ var _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./hall-edit/media-edit/media-edit.component */ "./src/app/pages/costumers/halls/hall-edit/media-edit/media-edit.component.ts");
/* harmony import */ var _halls_routing_module__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./halls-routing.module */ "./src/app/pages/costumers/halls/halls-routing.module.ts");
/* harmony import */ var _str_spliter_pipe__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../../../str-spliter.pipe */ "./src/app/str-spliter.pipe.ts");
/* harmony import */ var _split_text_pipe__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../../../split-text.pipe */ "./src/app/split-text.pipe.ts");
/* harmony import */ var _remove_white_space_pipe__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../../../remove-white-space.pipe */ "./src/app/remove-white-space.pipe.ts");
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
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["FormsModule"],
                _angular_common__WEBPACK_IMPORTED_MODULE_1__["CommonModule"],
                _angular_forms__WEBPACK_IMPORTED_MODULE_2__["ReactiveFormsModule"],
                _halls_routing_module__WEBPACK_IMPORTED_MODULE_11__["HallsRoutingModule"]
            ],
            declarations: [
                _HallsComponent__WEBPACK_IMPORTED_MODULE_3__["HallsComponent"],
                _hall_hall_component__WEBPACK_IMPORTED_MODULE_4__["HallComponent"],
                _hall_media_hall_media_component__WEBPACK_IMPORTED_MODULE_5__["HallMediaComponent"],
                _hall_about_hall_about_component__WEBPACK_IMPORTED_MODULE_6__["HallAboutComponent"],
                _hall_edit_hall_edit_component__WEBPACK_IMPORTED_MODULE_7__["HallEditComponent"],
                _hall_edit_all_edit_all_edit_component__WEBPACK_IMPORTED_MODULE_8__["AllEditComponent"],
                _hall_edit_basic_edit_basic_edit_component__WEBPACK_IMPORTED_MODULE_9__["BasicEditComponent"],
                _hall_edit_media_edit_media_edit_component__WEBPACK_IMPORTED_MODULE_10__["MediaEditComponent"],
                _str_spliter_pipe__WEBPACK_IMPORTED_MODULE_12__["StrSpliterPipe"],
                _split_text_pipe__WEBPACK_IMPORTED_MODULE_13__["SplitTextPipe"],
                _remove_white_space_pipe__WEBPACK_IMPORTED_MODULE_14__["RemoveWhiteSpacePipe"]
            ],
            exports: []
        })
    ], HallsModule);
    return HallsModule;
}());



/***/ }),

/***/ "./src/app/remove-white-space.pipe.ts":
/*!********************************************!*\
  !*** ./src/app/remove-white-space.pipe.ts ***!
  \********************************************/
/*! exports provided: RemoveWhiteSpacePipe */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "RemoveWhiteSpacePipe", function() { return RemoveWhiteSpacePipe; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

var RemoveWhiteSpacePipe = /** @class */ (function () {
    function RemoveWhiteSpacePipe() {
    }
    RemoveWhiteSpacePipe.prototype.transform = function (value, args) {
        //let text = value.replace(/[\r\n\s]+/g, " ");
        var text = value.replace(/[^\n]\s{4,}/g, "\n");
        // let text = value.replace(/[\s]{4,}/g, " ");
        return text;
    };
    RemoveWhiteSpacePipe = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Pipe"])({
            name: 'removeWhiteSpace'
        })
    ], RemoveWhiteSpacePipe);
    return RemoveWhiteSpacePipe;
}());



/***/ }),

/***/ "./src/app/split-text.pipe.ts":
/*!************************************!*\
  !*** ./src/app/split-text.pipe.ts ***!
  \************************************/
/*! exports provided: SplitTextPipe */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SplitTextPipe", function() { return SplitTextPipe; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

var SplitTextPipe = /** @class */ (function () {
    function SplitTextPipe() {
    }
    SplitTextPipe.prototype.transform = function (value, args, pos) {
        if (value) {
            var spliter = args ? args : ' ';
            var split = value.split(spliter);
            var defaultPos = (split.length - 1);
            var paramPos = (pos || pos === 0) ? pos : defaultPos;
            // console.log(pos);
            var resualt = paramPos ? split[paramPos] : paramPos;
            return resualt;
        }
        return value;
    };
    SplitTextPipe = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Pipe"])({
            name: 'splitText'
        })
    ], SplitTextPipe);
    return SplitTextPipe;
}());

/* transform(value: string , args?: string ): string {

  let Spliter = args? args: ' ';

  return (value.split(Spliter)[1])? value.split(Spliter)[0] + "-"+ value.split(Spliter)[1]: value;
  
} */ 


/***/ }),

/***/ "./src/app/str-spliter.pipe.ts":
/*!*************************************!*\
  !*** ./src/app/str-spliter.pipe.ts ***!
  \*************************************/
/*! exports provided: StrSpliterPipe */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "StrSpliterPipe", function() { return StrSpliterPipe; });
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @angular/core */ "./node_modules/@angular/core/fesm5/core.js");
var __decorate = (undefined && undefined.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

var StrSpliterPipe = /** @class */ (function () {
    function StrSpliterPipe() {
    }
    StrSpliterPipe.prototype.transform = function (value, args) {
        var Spliter = args ? args : ' ';
        return (value.split(Spliter)[1]) ? value.split(Spliter)[0] + "-" + value.split(Spliter)[1] : value;
    };
    StrSpliterPipe = __decorate([
        Object(_angular_core__WEBPACK_IMPORTED_MODULE_0__["Pipe"])({
            name: 'strSpliter'
        })
    ], StrSpliterPipe);
    return StrSpliterPipe;
}());



/***/ })

}]);
//# sourceMappingURL=pages-costumers-halls-halls-module.js.map