"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
var OAuth2;
(function (OAuth2) {
    var Util = /** @class */ (function () {
        function Util() {
        }
        Util.encode = function (obj) {
            return btoa(encodeURIComponent(JSON.stringify(obj)).replace(/%([0-9A-F]{2})/g, function toSolidBytes(match, p1) {
                // @ts-ignore
                return String.fromCharCode('0x' + p1);
            }));
        };
        Util.decode = function (str) {
            return JSON.parse(decodeURIComponent(atob(str).split('').map(function (c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join('')));
        };
        return Util;
    }());
    var Client = /** @class */ (function () {
        function Client(alias, client, uri, scope, responseType) {
            if (responseType === void 0) { responseType = 'token'; }
            this.alias = alias;
            this.client = client;
            this.uri = uri;
            this.scope = scope;
            this.responseType = responseType;
            this.location = new URL(window.location.href);
            this.state = {};
            this.accessTokenUri = this.uri.auth.substr(0, this.uri.auth.lastIndexOf('/'));
            this.alias = 'OAuth2_' + encodeURI(this.alias);
            this.loadState();
            this.checkLoginResponse();
        }
        Client.prototype._getMatchingAliasParameters = function () {
            var state;
            var params;
            if ((state = params = this.location.searchParams)
                && (state = state.get('state'))
                && state.indexOf(this.alias) == 0)
                return params;
            if ((state = this.location.hash)
                && (state = state.substr(1))
                && (state = params = new URLSearchParams(state))
                && (state = state.get('state'))
                && state.indexOf(this.alias) == 0)
                return params;
            return null;
        };
        Client.prototype.saveState = function () {
            localStorage.setItem(this.alias, JSON.stringify(this.state));
            history.pushState(null, '', location.pathname);
        };
        Client.prototype.checkLoginResponse = function () {
            var state = this._getMatchingAliasParameters();
            if (!state)
                return;
            this.state.token = state.get('access_token');
            this.state.code = state.get('code');
            this.saveState();
        };
        Client.prototype.loadState = function () {
            var state = localStorage.getItem(this.alias);
            if (!state)
                return;
            this.state = JSON.parse(state);
        };
        Client.prototype.login = function () {
            var extraInfo = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                extraInfo[_i] = arguments[_i];
            }
            location.href = this.uri.auth + '?' + extraInfo.concat([
                'response_type=' + this.responseType,
                'redirect_uri=' + this.uri.redirect,
                'client_id=' + this.client.id,
                'scope=' + this.scope.join('+'),
                'state=' + this.alias
            ]).join('&');
        };
        Client.prototype.get = function (action, srcParams) {
            if (srcParams === void 0) { srcParams = {}; }
            return __awaiter(this, void 0, void 0, function () {
                var uri, request, result;
                return __generator(this, function (_a) {
                    switch (_a.label) {
                        case 0:
                            if (!this.state.token)
                                throw "Usuário ainda não esta logado";
                            uri = new URL((this.uri.api || '') + '/' + action);
                            srcParams.access_token = this.state.token;
                            Object.keys(srcParams).forEach(function (key) { return uri.searchParams.append(key, srcParams[key]); });
                            return [4 /*yield*/, fetch(uri.toString())];
                        case 1:
                            request = _a.sent();
                            return [4 /*yield*/, request.json()];
                        case 2:
                            result = _a.sent();
                            return [2 /*return*/, result];
                    }
                });
            });
        };
        return Client;
    }());
    OAuth2.Client = Client;
})(OAuth2 || (OAuth2 = {}));
//# sourceMappingURL=ClientOauth2.js.map