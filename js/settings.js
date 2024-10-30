(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _preact = require('preact');

var _LicenseEditor = require('./LicenseEditor');

var _LicenseEditor2 = _interopRequireDefault(_LicenseEditor);

var _SettingsEditor = require('./SettingsEditor');

var _SettingsEditor2 = _interopRequireDefault(_SettingsEditor);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Editor = function (_Component) {
    _inherits(Editor, _Component);

    function Editor() {
        _classCallCheck(this, Editor);

        return _possibleConstructorReturn(this, (Editor.__proto__ || Object.getPrototypeOf(Editor)).apply(this, arguments));
    }

    _createClass(Editor, [{
        key: 'render',
        value: function render() {
            var _props = this.props,
                save = _props.save,
                i18n = _props.i18n,
                settings = _props.settings;

            var EditorComponent = save.tab == 'settings' ? _SettingsEditor2.default : _LicenseEditor2.default;
            return (0, _preact.h)(
                'form',
                { method: 'POST', enctype: 'multipart/form-data', id: 'jpibfi-form', action: save.post_url },
                (0, _preact.h)('input', { name: save.action, value: save.nonce, type: 'hidden' }),
                (0, _preact.h)('input', { name: save.tab, value: save.tab, type: 'hidden' }),
                (0, _preact.h)(EditorComponent, { settings: settings, i18n: i18n.editor })
            );
        }
    }]);

    return Editor;
}(_preact.Component);

exports.default = Editor;

},{"./LicenseEditor":2,"./SettingsEditor":3,"preact":6}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = undefined;

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _preact = require('preact');

var _helpers = require('./helpers');

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var LicenseEditor = function (_Component) {
    _inherits(LicenseEditor, _Component);

    function LicenseEditor() {
        _classCallCheck(this, LicenseEditor);

        return _possibleConstructorReturn(this, (LicenseEditor.__proto__ || Object.getPrototypeOf(LicenseEditor)).apply(this, arguments));
    }

    _createClass(LicenseEditor, [{
        key: 'render',
        value: function render() {
            var _props = this.props,
                settings = _props.settings,
                i18n = _props.i18n;

            return (0, _preact.h)(
                'div',
                null,
                (0, _preact.h)(
                    'h2',
                    null,
                    i18n.title
                ),
                (0, _preact.h)(
                    'table',
                    { className: 'form-table' },
                    (0, _preact.h)(
                        'tbody',
                        null,
                        (0, _preact.h)(
                            'tr',
                            null,
                            (0, _preact.h)(
                                'th',
                                null,
                                (0, _preact.h)(
                                    'label',
                                    { htmlFor: 'key' },
                                    settings.key.label
                                )
                            ),
                            (0, _preact.h)(
                                'td',
                                null,
                                (0, _preact.h)(_helpers.TextEditor, { disabled: settings.status.value == 'valid', args: settings.key }),
                                '\xA0',
                                settings.status.value == 'valid' && (0, _preact.h)('span', { style: { color: 'green' }, dangerouslySetInnerHTML: { __html: i18n.active } }),
                                settings.status.value == 'expired' && (0, _preact.h)('span', { style: { color: 'red' }, dangerouslySetInnerHTML: { __html: i18n.expired } }),
                                settings.expires && (0, _preact.h)(
                                    'p',
                                    null,
                                    settings.expires.label
                                )
                            )
                        ),
                        (0, _preact.h)(
                            'tr',
                            null,
                            (0, _preact.h)(
                                'th',
                                null,
                                (0, _preact.h)(
                                    'label',
                                    null,
                                    i18n.action
                                )
                            ),
                            (0, _preact.h)(
                                'td',
                                null,
                                settings.status.value !== 'valid' && (0, _preact.h)('input', { name: i18n.activate_action_name, type: 'submit', className: 'button button-primary', value: i18n.activate }),
                                settings.status.value === 'valid' && (0, _preact.h)('input', { name: i18n.deactivate_action_name, type: 'submit', className: 'button button-secondary', value: i18n.deactivate })
                            )
                        )
                    )
                )
            );
        }
    }]);

    return LicenseEditor;
}(_preact.Component);

exports.default = LicenseEditor;

},{"./helpers":4,"preact":6}],3:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _preact = require('preact');

var _helpers = require('./helpers');

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var SettingsEditor = function (_Component) {
    _inherits(SettingsEditor, _Component);

    function SettingsEditor(props) {
        _classCallCheck(this, SettingsEditor);

        var _this = _possibleConstructorReturn(this, (SettingsEditor.__proto__ || Object.getPrototypeOf(SettingsEditor)).call(this, props));

        _this.state = {
            authorizationMessage: '',
            inProgress: {},
            settings: _extends({}, _this.props.settings)
        };
        _this.revoke = _this.revoke.bind(_this);
        return _this;
    }

    _createClass(SettingsEditor, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.handlePinterestAuthorizationCode();
        }
    }, {
        key: 'onChange',
        value: function onChange(name, value) {
            var newSetting = _extends({}, this.state.settings[name], { value: value });
            this.setState({ settings: _extends({}, this.state.settings, _defineProperty({}, name, newSetting)) });
        }
    }, {
        key: 'handlePinterestAuthorizationCode',
        value: function handlePinterestAuthorizationCode() {
            var _this2 = this;

            var pinterest_code = (0, _helpers.getUrlParameterByName)('pinterest_code');
            if (!pinterest_code || this.state.settings.authorized.value) return;

            var i18n = this.props.i18n;

            this.setState({ inProgress: _extends({}, this.state.inProgress, { authorize: true }) });
            var params = {
                action: i18n.authorization.authorize_action_name,
                _wpnonce: i18n.authorization.authorize_action_nonce,
                pinterest_code: pinterest_code
            };
            (0, _helpers.httpPostRequest)(i18n.authorization.action_url, params).then(function (_ref) {
                var success = _ref.success,
                    _ref$data = _ref.data,
                    message = _ref$data.message,
                    boards = _ref$data.boards;

                if (success) {
                    _this2.setState({
                        settings: _extends({}, _this2.state.settings, {
                            authorized: { value: success },
                            default_board: _extends({}, _this2.state.settings.default_board, { options: boards })
                        }),
                        authorizationMessage: message,
                        inProgress: _extends({}, _this2.state.inProgress, { authorize: false })
                    });
                } else {
                    _this2.setState({
                        inProgress: _extends({}, _this2.state.inProgress, { authorize: false }),
                        authorizationMessage: message
                    });
                }
            }).catch(function (err) {
                _this2.setState({
                    inProgress: _extends({}, _this2.state.inProgress, { authorize: false }),
                    authorizationMessage: '<div class="error">' + err.message + '</div>'
                });
            });
        }
    }, {
        key: 'revoke',
        value: function revoke() {
            var _this3 = this;

            var i18n = this.props.i18n;

            this.setState({
                inProgress: _extends({}, this.state.inProgress, { authorize: true }),
                authorizationMessage: ''
            });
            (0, _helpers.httpPostRequest)(i18n.authorization.action_url, {
                action: i18n.authorization.revoke_action_name,
                _wpnonce: i18n.authorization.revoke_action_nonce
            }).then(function (_ref2) {
                var success = _ref2.success,
                    _ref2$data = _ref2.data,
                    message = _ref2$data.message,
                    boards = _ref2$data.boards;

                if (success) {
                    _this3.setState({
                        authorizationMessage: message,
                        inProgress: _extends({}, _this3.state.inProgress, { authorize: false }),
                        settings: _extends({}, _this3.state.settings, {
                            default_board: _extends({}, _this3.state.settings.default_board, { options: boards, value: '0' }),
                            authorized: { value: false }
                        })
                    });
                } else {
                    _this3.setState({ inProgress: _extends({}, _this3.state.inProgress, { authorize: false }) });
                }
            }).catch(function (err) {
                console.log('error', err);
                _this3.setState({
                    authorizationMessage: '<div class="error">' + err.message + '</div>',
                    inProgress: _extends({}, _this3.state.inProgress, { authorize: false })
                });
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var _this4 = this;

            var i18n = this.props.i18n;
            var _state = this.state,
                inProgress = _state.inProgress,
                settings = _state.settings,
                authorizationMessage = _state.authorizationMessage;

            return (0, _preact.h)(
                'div',
                null,
                (0, _preact.h)(
                    'h2',
                    null,
                    i18n.sections.pinterest
                ),
                (0, _preact.h)(
                    'table',
                    { className: 'form-table' },
                    (0, _preact.h)(
                        'tbody',
                        null,
                        (0, _preact.h)(
                            'tr',
                            null,
                            (0, _preact.h)(
                                'th',
                                null,
                                (0, _preact.h)(
                                    'label',
                                    { 'for': 'login' },
                                    i18n.authorization.label
                                )
                            ),
                            (0, _preact.h)(
                                'td',
                                null,
                                settings.authorized.value && (0, _preact.h)(
                                    'p',
                                    null,
                                    (0, _preact.h)('span', { style: { color: '#0085ba' }, dangerouslySetInnerHTML: { __html: i18n.authorization.authorized } }),
                                    '\xA0',
                                    (0, _preact.h)('button', { type: 'button', onClick: function onClick() {
                                            return _this4.revoke();
                                        }, className: 'button button-secondary',
                                        dangerouslySetInnerHTML: { __html: i18n.authorization.revoke_link } })
                                ),
                                !settings.authorized.value && (0, _preact.h)(
                                    'p',
                                    null,
                                    (0, _preact.h)('span', { dangerouslySetInnerHTML: { __html: i18n.authorization.unauthorized } }),
                                    (0, _preact.h)('a', { className: 'button button-primary', target: '_self',
                                        href: i18n.authorization.login_url,
                                        dangerouslySetInnerHTML: { __html: i18n.authorization.login_link } }),
                                    inProgress.authorize && (0, _preact.h)('span', { className: 'dashicons dashicons-update iaposter-spin' })
                                ),
                                (0, _preact.h)('div', { dangerouslySetInnerHTML: { __html: authorizationMessage } })
                            )
                        ),
                        (0, _preact.h)(_helpers.TableRow, {
                            setting: settings.default_board,
                            onChange: function onChange(e) {
                                return _this4.onChange('default_board', e.target.value);
                            },
                            Editor: _helpers.SelectEditor
                        }),
                        (0, _preact.h)(_helpers.TableRow, {
                            setting: settings.description,
                            onChange: function onChange(e) {
                                return _this4.onChange('description', e.target.value);
                            },
                            Editor: _helpers.TextEditor
                        })
                    )
                ),
                (0, _preact.h)(
                    'h2',
                    null,
                    i18n.sections.post_editor
                ),
                (0, _preact.h)(
                    'table',
                    { className: 'form-table' },
                    (0, _preact.h)(
                        'tbody',
                        null,
                        (0, _preact.h)(_helpers.TableRow, {
                            setting: settings.add_all_images,
                            onChange: function onChange(e) {
                                return _this4.onChange('add_all_images', e.target.value);
                            },
                            Editor: _helpers.CheckboxEditor
                        })
                    )
                ),
                (0, _preact.h)(
                    'h2',
                    null,
                    i18n.sections.misc
                ),
                (0, _preact.h)(
                    'table',
                    { className: 'form-table' },
                    (0, _preact.h)(
                        'tbody',
                        null,
                        (0, _preact.h)(_helpers.TableRow, {
                            setting: settings.delete_logs_after,
                            onChange: function onChange(e) {
                                return _this4.onChange('delete_logs_after', e.target.value);
                            },
                            Editor: _helpers.NumberEditor
                        }),
                        (0, _preact.h)(_helpers.TableRow, {
                            setting: settings.delete_all_data_on_uninstall,
                            onChange: function onChange(e) {
                                return _this4.onChange('delete_all_data_on_uninstall', e.target.value);
                            },
                            Editor: _helpers.CheckboxEditor
                        })
                    )
                ),
                (0, _preact.h)('input', { type: 'submit', className: 'button button-primary', value: i18n.save_changes })
            );
        }
    }]);

    return SettingsEditor;
}(_preact.Component);

exports.default = SettingsEditor;

},{"./helpers":4,"preact":6}],4:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

exports.httpPostRequest = httpPostRequest;
exports.getUrlParameterByName = getUrlParameterByName;
exports.Description = Description;
exports.TableRow = TableRow;
exports.TextEditor = TextEditor;
exports.TextareaEditor = TextareaEditor;
exports.NumberEditor = NumberEditor;
exports.LabeledNumberEditor = LabeledNumberEditor;
exports.SelectEditor = SelectEditor;
exports.CheckboxEditor = CheckboxEditor;

var _preact = require('preact');

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

function httpPostRequest(url, params) {
    var body = Object.keys(params).reduce(function (acc, current) {
        return acc + (acc.length ? '&' : '') + (current + '=' + encodeURIComponent(params[current]));
    }, '');
    return fetch(url, {
        method: 'POST',
        body: body,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8' },
        credentials: 'same-origin'
    }).then(function (response) {
        if (response.ok) return response.json();
        throw new Error('Network response was not ok.');
    });
}

function getUrlParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

function Description(_ref) {
    var text = _ref.text;

    return (0, _preact.h)('p', { className: 'description', dangerouslySetInnerHTML: { __html: text } });
}

function TableRow(_ref2) {
    var Editor = _ref2.Editor,
        setting = _ref2.setting,
        rest = _objectWithoutProperties(_ref2, ['Editor', 'setting']);

    return (0, _preact.h)(
        'tr',
        null,
        (0, _preact.h)(
            'th',
            null,
            (0, _preact.h)(
                'label',
                { htmlFor: setting.key },
                setting.label
            )
        ),
        (0, _preact.h)(
            'td',
            null,
            (0, _preact.h)(Editor, _extends({ args: setting }, rest)),
            (0, _preact.h)(Description, { text: setting.desc })
        )
    );
}

function TextEditor(_ref3) {
    var args = _ref3.args,
        _ref3$className = _ref3.className,
        className = _ref3$className === undefined ? 'regular-text' : _ref3$className,
        otherProps = _objectWithoutProperties(_ref3, ['args', 'className']);

    if (!otherProps.hasOwnProperty('value')) {
        otherProps.defaultValue = args.value;
    }
    return (0, _preact.h)('input', _extends({ type: 'text',
        className: className
    }, otherProps, {
        placeholder: args.placeholder || '',
        name: args.key,
        id: args.key }));
}

function TextareaEditor(_ref4) {
    var args = _ref4.args,
        rest = _objectWithoutProperties(_ref4, ['args']);

    return (0, _preact.h)('textarea', _extends({ rows: '3', cols: '50', className: 'large-text code', value: args.value
    }, rest, {
        id: args.key,
        name: args.key }));
}

function NumberEditor(_ref5) {
    var args = _ref5.args,
        rest = _objectWithoutProperties(_ref5, ['args']);

    if (!rest.hasOwnProperty('value')) {
        rest.defaultValue = args.value;
    }
    return (0, _preact.h)('input', _extends({ type: 'number', className: 'small-text'
    }, rest, {
        id: args.key,
        name: args.key
    }));
}

function LabeledNumberEditor(_ref6) {
    var args = _ref6.args,
        rest = _objectWithoutProperties(_ref6, ['args']);

    return (0, _preact.h)(
        'label',
        { htmlFor: args.key },
        args.text,
        (0, _preact.h)(NumberEditor, _extends({ args: args }, rest)),
        args.unit
    );
}

function SelectEditor(_ref7) {
    var args = _ref7.args,
        rest = _objectWithoutProperties(_ref7, ['args']);

    return (0, _preact.h)(
        'select',
        _extends({ name: args.key, id: args.key }, rest, { value: args.value }),
        Object.keys(args.options).map(function (optKey) {
            return (0, _preact.h)(
                'option',
                { key: optKey, value: optKey },
                args.options[optKey]
            );
        })
    );
}

function CheckboxEditor(_ref8) {
    var args = _ref8.args,
        rest = _objectWithoutProperties(_ref8, ['args']);

    return (0, _preact.h)(
        'span',
        null,
        (0, _preact.h)('input', { type: 'checkbox', name: args.key, id: args.key, defaultChecked: args.value }),
        (0, _preact.h)(
            'label',
            { htmlFor: args.key },
            args.text
        ),
        args.tooltip && (0, _preact.h)('span', { tooltips: true, 'tooltip-template': args.tooltip, className: 'dashicons dashicons-editor-help' }),
        args.addBr && (0, _preact.h)('br', null)
    );
}

},{"preact":6}],5:[function(require,module,exports){
'use strict';

var _preact = require('preact');

var _Editor = require('./Editor');

var _Editor2 = _interopRequireDefault(_Editor);

require('whatwg-fetch');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

jQuery(document).ready(function () {
    (0, _preact.render)((0, _preact.h)(_Editor2.default, window.iaposter_settings), document.getElementById('iaposter-container'));
});

},{"./Editor":1,"preact":6,"whatwg-fetch":7}],6:[function(require,module,exports){
!function() {
    'use strict';
    function VNode() {}
    function h(nodeName, attributes) {
        var lastSimple, child, simple, i, children = EMPTY_CHILDREN;
        for (i = arguments.length; i-- > 2; ) stack.push(arguments[i]);
        if (attributes && null != attributes.children) {
            if (!stack.length) stack.push(attributes.children);
            delete attributes.children;
        }
        while (stack.length) if ((child = stack.pop()) && void 0 !== child.pop) for (i = child.length; i--; ) stack.push(child[i]); else {
            if ('boolean' == typeof child) child = null;
            if (simple = 'function' != typeof nodeName) if (null == child) child = ''; else if ('number' == typeof child) child = String(child); else if ('string' != typeof child) simple = !1;
            if (simple && lastSimple) children[children.length - 1] += child; else if (children === EMPTY_CHILDREN) children = [ child ]; else children.push(child);
            lastSimple = simple;
        }
        var p = new VNode();
        p.nodeName = nodeName;
        p.children = children;
        p.attributes = null == attributes ? void 0 : attributes;
        p.key = null == attributes ? void 0 : attributes.key;
        if (void 0 !== options.vnode) options.vnode(p);
        return p;
    }
    function extend(obj, props) {
        for (var i in props) obj[i] = props[i];
        return obj;
    }
    function cloneElement(vnode, props) {
        return h(vnode.nodeName, extend(extend({}, vnode.attributes), props), arguments.length > 2 ? [].slice.call(arguments, 2) : vnode.children);
    }
    function enqueueRender(component) {
        if (!component.__d && (component.__d = !0) && 1 == items.push(component)) (options.debounceRendering || defer)(rerender);
    }
    function rerender() {
        var p, list = items;
        items = [];
        while (p = list.pop()) if (p.__d) renderComponent(p);
    }
    function isSameNodeType(node, vnode, hydrating) {
        if ('string' == typeof vnode || 'number' == typeof vnode) return void 0 !== node.splitText;
        if ('string' == typeof vnode.nodeName) return !node._componentConstructor && isNamedNode(node, vnode.nodeName); else return hydrating || node._componentConstructor === vnode.nodeName;
    }
    function isNamedNode(node, nodeName) {
        return node.__n === nodeName || node.nodeName.toLowerCase() === nodeName.toLowerCase();
    }
    function getNodeProps(vnode) {
        var props = extend({}, vnode.attributes);
        props.children = vnode.children;
        var defaultProps = vnode.nodeName.defaultProps;
        if (void 0 !== defaultProps) for (var i in defaultProps) if (void 0 === props[i]) props[i] = defaultProps[i];
        return props;
    }
    function createNode(nodeName, isSvg) {
        var node = isSvg ? document.createElementNS('http://www.w3.org/2000/svg', nodeName) : document.createElement(nodeName);
        node.__n = nodeName;
        return node;
    }
    function removeNode(node) {
        var parentNode = node.parentNode;
        if (parentNode) parentNode.removeChild(node);
    }
    function setAccessor(node, name, old, value, isSvg) {
        if ('className' === name) name = 'class';
        if ('key' === name) ; else if ('ref' === name) {
            if (old) old(null);
            if (value) value(node);
        } else if ('class' === name && !isSvg) node.className = value || ''; else if ('style' === name) {
            if (!value || 'string' == typeof value || 'string' == typeof old) node.style.cssText = value || '';
            if (value && 'object' == typeof value) {
                if ('string' != typeof old) for (var i in old) if (!(i in value)) node.style[i] = '';
                for (var i in value) node.style[i] = 'number' == typeof value[i] && !1 === IS_NON_DIMENSIONAL.test(i) ? value[i] + 'px' : value[i];
            }
        } else if ('dangerouslySetInnerHTML' === name) {
            if (value) node.innerHTML = value.__html || '';
        } else if ('o' == name[0] && 'n' == name[1]) {
            var useCapture = name !== (name = name.replace(/Capture$/, ''));
            name = name.toLowerCase().substring(2);
            if (value) {
                if (!old) node.addEventListener(name, eventProxy, useCapture);
            } else node.removeEventListener(name, eventProxy, useCapture);
            (node.__l || (node.__l = {}))[name] = value;
        } else if ('list' !== name && 'type' !== name && !isSvg && name in node) {
            setProperty(node, name, null == value ? '' : value);
            if (null == value || !1 === value) node.removeAttribute(name);
        } else {
            var ns = isSvg && name !== (name = name.replace(/^xlink\:?/, ''));
            if (null == value || !1 === value) if (ns) node.removeAttributeNS('http://www.w3.org/1999/xlink', name.toLowerCase()); else node.removeAttribute(name); else if ('function' != typeof value) if (ns) node.setAttributeNS('http://www.w3.org/1999/xlink', name.toLowerCase(), value); else node.setAttribute(name, value);
        }
    }
    function setProperty(node, name, value) {
        try {
            node[name] = value;
        } catch (e) {}
    }
    function eventProxy(e) {
        return this.__l[e.type](options.event && options.event(e) || e);
    }
    function flushMounts() {
        var c;
        while (c = mounts.pop()) {
            if (options.afterMount) options.afterMount(c);
            if (c.componentDidMount) c.componentDidMount();
        }
    }
    function diff(dom, vnode, context, mountAll, parent, componentRoot) {
        if (!diffLevel++) {
            isSvgMode = null != parent && void 0 !== parent.ownerSVGElement;
            hydrating = null != dom && !('__preactattr_' in dom);
        }
        var ret = idiff(dom, vnode, context, mountAll, componentRoot);
        if (parent && ret.parentNode !== parent) parent.appendChild(ret);
        if (!--diffLevel) {
            hydrating = !1;
            if (!componentRoot) flushMounts();
        }
        return ret;
    }
    function idiff(dom, vnode, context, mountAll, componentRoot) {
        var out = dom, prevSvgMode = isSvgMode;
        if (null == vnode || 'boolean' == typeof vnode) vnode = '';
        if ('string' == typeof vnode || 'number' == typeof vnode) {
            if (dom && void 0 !== dom.splitText && dom.parentNode && (!dom._component || componentRoot)) {
                if (dom.nodeValue != vnode) dom.nodeValue = vnode;
            } else {
                out = document.createTextNode(vnode);
                if (dom) {
                    if (dom.parentNode) dom.parentNode.replaceChild(out, dom);
                    recollectNodeTree(dom, !0);
                }
            }
            out.__preactattr_ = !0;
            return out;
        }
        var vnodeName = vnode.nodeName;
        if ('function' == typeof vnodeName) return buildComponentFromVNode(dom, vnode, context, mountAll);
        isSvgMode = 'svg' === vnodeName ? !0 : 'foreignObject' === vnodeName ? !1 : isSvgMode;
        vnodeName = String(vnodeName);
        if (!dom || !isNamedNode(dom, vnodeName)) {
            out = createNode(vnodeName, isSvgMode);
            if (dom) {
                while (dom.firstChild) out.appendChild(dom.firstChild);
                if (dom.parentNode) dom.parentNode.replaceChild(out, dom);
                recollectNodeTree(dom, !0);
            }
        }
        var fc = out.firstChild, props = out.__preactattr_, vchildren = vnode.children;
        if (null == props) {
            props = out.__preactattr_ = {};
            for (var a = out.attributes, i = a.length; i--; ) props[a[i].name] = a[i].value;
        }
        if (!hydrating && vchildren && 1 === vchildren.length && 'string' == typeof vchildren[0] && null != fc && void 0 !== fc.splitText && null == fc.nextSibling) {
            if (fc.nodeValue != vchildren[0]) fc.nodeValue = vchildren[0];
        } else if (vchildren && vchildren.length || null != fc) innerDiffNode(out, vchildren, context, mountAll, hydrating || null != props.dangerouslySetInnerHTML);
        diffAttributes(out, vnode.attributes, props);
        isSvgMode = prevSvgMode;
        return out;
    }
    function innerDiffNode(dom, vchildren, context, mountAll, isHydrating) {
        var j, c, f, vchild, child, originalChildren = dom.childNodes, children = [], keyed = {}, keyedLen = 0, min = 0, len = originalChildren.length, childrenLen = 0, vlen = vchildren ? vchildren.length : 0;
        if (0 !== len) for (var i = 0; i < len; i++) {
            var _child = originalChildren[i], props = _child.__preactattr_, key = vlen && props ? _child._component ? _child._component.__k : props.key : null;
            if (null != key) {
                keyedLen++;
                keyed[key] = _child;
            } else if (props || (void 0 !== _child.splitText ? isHydrating ? _child.nodeValue.trim() : !0 : isHydrating)) children[childrenLen++] = _child;
        }
        if (0 !== vlen) for (var i = 0; i < vlen; i++) {
            vchild = vchildren[i];
            child = null;
            var key = vchild.key;
            if (null != key) {
                if (keyedLen && void 0 !== keyed[key]) {
                    child = keyed[key];
                    keyed[key] = void 0;
                    keyedLen--;
                }
            } else if (!child && min < childrenLen) for (j = min; j < childrenLen; j++) if (void 0 !== children[j] && isSameNodeType(c = children[j], vchild, isHydrating)) {
                child = c;
                children[j] = void 0;
                if (j === childrenLen - 1) childrenLen--;
                if (j === min) min++;
                break;
            }
            child = idiff(child, vchild, context, mountAll);
            f = originalChildren[i];
            if (child && child !== dom && child !== f) if (null == f) dom.appendChild(child); else if (child === f.nextSibling) removeNode(f); else dom.insertBefore(child, f);
        }
        if (keyedLen) for (var i in keyed) if (void 0 !== keyed[i]) recollectNodeTree(keyed[i], !1);
        while (min <= childrenLen) if (void 0 !== (child = children[childrenLen--])) recollectNodeTree(child, !1);
    }
    function recollectNodeTree(node, unmountOnly) {
        var component = node._component;
        if (component) unmountComponent(component); else {
            if (null != node.__preactattr_ && node.__preactattr_.ref) node.__preactattr_.ref(null);
            if (!1 === unmountOnly || null == node.__preactattr_) removeNode(node);
            removeChildren(node);
        }
    }
    function removeChildren(node) {
        node = node.lastChild;
        while (node) {
            var next = node.previousSibling;
            recollectNodeTree(node, !0);
            node = next;
        }
    }
    function diffAttributes(dom, attrs, old) {
        var name;
        for (name in old) if ((!attrs || null == attrs[name]) && null != old[name]) setAccessor(dom, name, old[name], old[name] = void 0, isSvgMode);
        for (name in attrs) if (!('children' === name || 'innerHTML' === name || name in old && attrs[name] === ('value' === name || 'checked' === name ? dom[name] : old[name]))) setAccessor(dom, name, old[name], old[name] = attrs[name], isSvgMode);
    }
    function collectComponent(component) {
        var name = component.constructor.name;
        (components[name] || (components[name] = [])).push(component);
    }
    function createComponent(Ctor, props, context) {
        var inst, list = components[Ctor.name];
        if (Ctor.prototype && Ctor.prototype.render) {
            inst = new Ctor(props, context);
            Component.call(inst, props, context);
        } else {
            inst = new Component(props, context);
            inst.constructor = Ctor;
            inst.render = doRender;
        }
        if (list) for (var i = list.length; i--; ) if (list[i].constructor === Ctor) {
            inst.__b = list[i].__b;
            list.splice(i, 1);
            break;
        }
        return inst;
    }
    function doRender(props, state, context) {
        return this.constructor(props, context);
    }
    function setComponentProps(component, props, opts, context, mountAll) {
        if (!component.__x) {
            component.__x = !0;
            if (component.__r = props.ref) delete props.ref;
            if (component.__k = props.key) delete props.key;
            if (!component.base || mountAll) {
                if (component.componentWillMount) component.componentWillMount();
            } else if (component.componentWillReceiveProps) component.componentWillReceiveProps(props, context);
            if (context && context !== component.context) {
                if (!component.__c) component.__c = component.context;
                component.context = context;
            }
            if (!component.__p) component.__p = component.props;
            component.props = props;
            component.__x = !1;
            if (0 !== opts) if (1 === opts || !1 !== options.syncComponentUpdates || !component.base) renderComponent(component, 1, mountAll); else enqueueRender(component);
            if (component.__r) component.__r(component);
        }
    }
    function renderComponent(component, opts, mountAll, isChild) {
        if (!component.__x) {
            var rendered, inst, cbase, props = component.props, state = component.state, context = component.context, previousProps = component.__p || props, previousState = component.__s || state, previousContext = component.__c || context, isUpdate = component.base, nextBase = component.__b, initialBase = isUpdate || nextBase, initialChildComponent = component._component, skip = !1;
            if (isUpdate) {
                component.props = previousProps;
                component.state = previousState;
                component.context = previousContext;
                if (2 !== opts && component.shouldComponentUpdate && !1 === component.shouldComponentUpdate(props, state, context)) skip = !0; else if (component.componentWillUpdate) component.componentWillUpdate(props, state, context);
                component.props = props;
                component.state = state;
                component.context = context;
            }
            component.__p = component.__s = component.__c = component.__b = null;
            component.__d = !1;
            if (!skip) {
                rendered = component.render(props, state, context);
                if (component.getChildContext) context = extend(extend({}, context), component.getChildContext());
                var toUnmount, base, childComponent = rendered && rendered.nodeName;
                if ('function' == typeof childComponent) {
                    var childProps = getNodeProps(rendered);
                    inst = initialChildComponent;
                    if (inst && inst.constructor === childComponent && childProps.key == inst.__k) setComponentProps(inst, childProps, 1, context, !1); else {
                        toUnmount = inst;
                        component._component = inst = createComponent(childComponent, childProps, context);
                        inst.__b = inst.__b || nextBase;
                        inst.__u = component;
                        setComponentProps(inst, childProps, 0, context, !1);
                        renderComponent(inst, 1, mountAll, !0);
                    }
                    base = inst.base;
                } else {
                    cbase = initialBase;
                    toUnmount = initialChildComponent;
                    if (toUnmount) cbase = component._component = null;
                    if (initialBase || 1 === opts) {
                        if (cbase) cbase._component = null;
                        base = diff(cbase, rendered, context, mountAll || !isUpdate, initialBase && initialBase.parentNode, !0);
                    }
                }
                if (initialBase && base !== initialBase && inst !== initialChildComponent) {
                    var baseParent = initialBase.parentNode;
                    if (baseParent && base !== baseParent) {
                        baseParent.replaceChild(base, initialBase);
                        if (!toUnmount) {
                            initialBase._component = null;
                            recollectNodeTree(initialBase, !1);
                        }
                    }
                }
                if (toUnmount) unmountComponent(toUnmount);
                component.base = base;
                if (base && !isChild) {
                    var componentRef = component, t = component;
                    while (t = t.__u) (componentRef = t).base = base;
                    base._component = componentRef;
                    base._componentConstructor = componentRef.constructor;
                }
            }
            if (!isUpdate || mountAll) mounts.unshift(component); else if (!skip) {
                if (component.componentDidUpdate) component.componentDidUpdate(previousProps, previousState, previousContext);
                if (options.afterUpdate) options.afterUpdate(component);
            }
            if (null != component.__h) while (component.__h.length) component.__h.pop().call(component);
            if (!diffLevel && !isChild) flushMounts();
        }
    }
    function buildComponentFromVNode(dom, vnode, context, mountAll) {
        var c = dom && dom._component, originalComponent = c, oldDom = dom, isDirectOwner = c && dom._componentConstructor === vnode.nodeName, isOwner = isDirectOwner, props = getNodeProps(vnode);
        while (c && !isOwner && (c = c.__u)) isOwner = c.constructor === vnode.nodeName;
        if (c && isOwner && (!mountAll || c._component)) {
            setComponentProps(c, props, 3, context, mountAll);
            dom = c.base;
        } else {
            if (originalComponent && !isDirectOwner) {
                unmountComponent(originalComponent);
                dom = oldDom = null;
            }
            c = createComponent(vnode.nodeName, props, context);
            if (dom && !c.__b) {
                c.__b = dom;
                oldDom = null;
            }
            setComponentProps(c, props, 1, context, mountAll);
            dom = c.base;
            if (oldDom && dom !== oldDom) {
                oldDom._component = null;
                recollectNodeTree(oldDom, !1);
            }
        }
        return dom;
    }
    function unmountComponent(component) {
        if (options.beforeUnmount) options.beforeUnmount(component);
        var base = component.base;
        component.__x = !0;
        if (component.componentWillUnmount) component.componentWillUnmount();
        component.base = null;
        var inner = component._component;
        if (inner) unmountComponent(inner); else if (base) {
            if (base.__preactattr_ && base.__preactattr_.ref) base.__preactattr_.ref(null);
            component.__b = base;
            removeNode(base);
            collectComponent(component);
            removeChildren(base);
        }
        if (component.__r) component.__r(null);
    }
    function Component(props, context) {
        this.__d = !0;
        this.context = context;
        this.props = props;
        this.state = this.state || {};
    }
    function render(vnode, parent, merge) {
        return diff(merge, vnode, {}, !1, parent, !1);
    }
    var options = {};
    var stack = [];
    var EMPTY_CHILDREN = [];
    var defer = 'function' == typeof Promise ? Promise.resolve().then.bind(Promise.resolve()) : setTimeout;
    var IS_NON_DIMENSIONAL = /acit|ex(?:s|g|n|p|$)|rph|ows|mnc|ntw|ine[ch]|zoo|^ord/i;
    var items = [];
    var mounts = [];
    var diffLevel = 0;
    var isSvgMode = !1;
    var hydrating = !1;
    var components = {};
    extend(Component.prototype, {
        setState: function(state, callback) {
            var s = this.state;
            if (!this.__s) this.__s = extend({}, s);
            extend(s, 'function' == typeof state ? state(s, this.props) : state);
            if (callback) (this.__h = this.__h || []).push(callback);
            enqueueRender(this);
        },
        forceUpdate: function(callback) {
            if (callback) (this.__h = this.__h || []).push(callback);
            renderComponent(this, 2);
        },
        render: function() {}
    });
    var preact = {
        h: h,
        createElement: h,
        cloneElement: cloneElement,
        Component: Component,
        render: render,
        rerender: rerender,
        options: options
    };
    if ('undefined' != typeof module) module.exports = preact; else self.preact = preact;
}();

},{}],7:[function(require,module,exports){
(function(self) {
  'use strict';

  if (self.fetch) {
    return
  }

  var support = {
    searchParams: 'URLSearchParams' in self,
    iterable: 'Symbol' in self && 'iterator' in Symbol,
    blob: 'FileReader' in self && 'Blob' in self && (function() {
      try {
        new Blob()
        return true
      } catch(e) {
        return false
      }
    })(),
    formData: 'FormData' in self,
    arrayBuffer: 'ArrayBuffer' in self
  }

  if (support.arrayBuffer) {
    var viewClasses = [
      '[object Int8Array]',
      '[object Uint8Array]',
      '[object Uint8ClampedArray]',
      '[object Int16Array]',
      '[object Uint16Array]',
      '[object Int32Array]',
      '[object Uint32Array]',
      '[object Float32Array]',
      '[object Float64Array]'
    ]

    var isDataView = function(obj) {
      return obj && DataView.prototype.isPrototypeOf(obj)
    }

    var isArrayBufferView = ArrayBuffer.isView || function(obj) {
      return obj && viewClasses.indexOf(Object.prototype.toString.call(obj)) > -1
    }
  }

  function normalizeName(name) {
    if (typeof name !== 'string') {
      name = String(name)
    }
    if (/[^a-z0-9\-#$%&'*+.\^_`|~]/i.test(name)) {
      throw new TypeError('Invalid character in header field name')
    }
    return name.toLowerCase()
  }

  function normalizeValue(value) {
    if (typeof value !== 'string') {
      value = String(value)
    }
    return value
  }

  function iteratorFor(items) {
    var iterator = {
      next: function() {
        var value = items.shift()
        return {done: value === undefined, value: value}
      }
    }

    if (support.iterable) {
      iterator[Symbol.iterator] = function() {
        return iterator
      }
    }

    return iterator
  }

  function Headers(headers) {
    this.map = {}

    if (headers instanceof Headers) {
      headers.forEach(function(value, name) {
        this.append(name, value)
      }, this)
    } else if (Array.isArray(headers)) {
      headers.forEach(function(header) {
        this.append(header[0], header[1])
      }, this)
    } else if (headers) {
      Object.getOwnPropertyNames(headers).forEach(function(name) {
        this.append(name, headers[name])
      }, this)
    }
  }

  Headers.prototype.append = function(name, value) {
    name = normalizeName(name)
    value = normalizeValue(value)
    var oldValue = this.map[name]
    this.map[name] = oldValue ? oldValue+','+value : value
  }

  Headers.prototype['delete'] = function(name) {
    delete this.map[normalizeName(name)]
  }

  Headers.prototype.get = function(name) {
    name = normalizeName(name)
    return this.has(name) ? this.map[name] : null
  }

  Headers.prototype.has = function(name) {
    return this.map.hasOwnProperty(normalizeName(name))
  }

  Headers.prototype.set = function(name, value) {
    this.map[normalizeName(name)] = normalizeValue(value)
  }

  Headers.prototype.forEach = function(callback, thisArg) {
    for (var name in this.map) {
      if (this.map.hasOwnProperty(name)) {
        callback.call(thisArg, this.map[name], name, this)
      }
    }
  }

  Headers.prototype.keys = function() {
    var items = []
    this.forEach(function(value, name) { items.push(name) })
    return iteratorFor(items)
  }

  Headers.prototype.values = function() {
    var items = []
    this.forEach(function(value) { items.push(value) })
    return iteratorFor(items)
  }

  Headers.prototype.entries = function() {
    var items = []
    this.forEach(function(value, name) { items.push([name, value]) })
    return iteratorFor(items)
  }

  if (support.iterable) {
    Headers.prototype[Symbol.iterator] = Headers.prototype.entries
  }

  function consumed(body) {
    if (body.bodyUsed) {
      return Promise.reject(new TypeError('Already read'))
    }
    body.bodyUsed = true
  }

  function fileReaderReady(reader) {
    return new Promise(function(resolve, reject) {
      reader.onload = function() {
        resolve(reader.result)
      }
      reader.onerror = function() {
        reject(reader.error)
      }
    })
  }

  function readBlobAsArrayBuffer(blob) {
    var reader = new FileReader()
    var promise = fileReaderReady(reader)
    reader.readAsArrayBuffer(blob)
    return promise
  }

  function readBlobAsText(blob) {
    var reader = new FileReader()
    var promise = fileReaderReady(reader)
    reader.readAsText(blob)
    return promise
  }

  function readArrayBufferAsText(buf) {
    var view = new Uint8Array(buf)
    var chars = new Array(view.length)

    for (var i = 0; i < view.length; i++) {
      chars[i] = String.fromCharCode(view[i])
    }
    return chars.join('')
  }

  function bufferClone(buf) {
    if (buf.slice) {
      return buf.slice(0)
    } else {
      var view = new Uint8Array(buf.byteLength)
      view.set(new Uint8Array(buf))
      return view.buffer
    }
  }

  function Body() {
    this.bodyUsed = false

    this._initBody = function(body) {
      this._bodyInit = body
      if (!body) {
        this._bodyText = ''
      } else if (typeof body === 'string') {
        this._bodyText = body
      } else if (support.blob && Blob.prototype.isPrototypeOf(body)) {
        this._bodyBlob = body
      } else if (support.formData && FormData.prototype.isPrototypeOf(body)) {
        this._bodyFormData = body
      } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
        this._bodyText = body.toString()
      } else if (support.arrayBuffer && support.blob && isDataView(body)) {
        this._bodyArrayBuffer = bufferClone(body.buffer)
        this._bodyInit = new Blob([this._bodyArrayBuffer])
      } else if (support.arrayBuffer && (ArrayBuffer.prototype.isPrototypeOf(body) || isArrayBufferView(body))) {
        this._bodyArrayBuffer = bufferClone(body)
      } else {
        throw new Error('unsupported BodyInit type')
      }

      if (!this.headers.get('content-type')) {
        if (typeof body === 'string') {
          this.headers.set('content-type', 'text/plain;charset=UTF-8')
        } else if (this._bodyBlob && this._bodyBlob.type) {
          this.headers.set('content-type', this._bodyBlob.type)
        } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
          this.headers.set('content-type', 'application/x-www-form-urlencoded;charset=UTF-8')
        }
      }
    }

    if (support.blob) {
      this.blob = function() {
        var rejected = consumed(this)
        if (rejected) {
          return rejected
        }

        if (this._bodyBlob) {
          return Promise.resolve(this._bodyBlob)
        } else if (this._bodyArrayBuffer) {
          return Promise.resolve(new Blob([this._bodyArrayBuffer]))
        } else if (this._bodyFormData) {
          throw new Error('could not read FormData body as blob')
        } else {
          return Promise.resolve(new Blob([this._bodyText]))
        }
      }

      this.arrayBuffer = function() {
        if (this._bodyArrayBuffer) {
          return consumed(this) || Promise.resolve(this._bodyArrayBuffer)
        } else {
          return this.blob().then(readBlobAsArrayBuffer)
        }
      }
    }

    this.text = function() {
      var rejected = consumed(this)
      if (rejected) {
        return rejected
      }

      if (this._bodyBlob) {
        return readBlobAsText(this._bodyBlob)
      } else if (this._bodyArrayBuffer) {
        return Promise.resolve(readArrayBufferAsText(this._bodyArrayBuffer))
      } else if (this._bodyFormData) {
        throw new Error('could not read FormData body as text')
      } else {
        return Promise.resolve(this._bodyText)
      }
    }

    if (support.formData) {
      this.formData = function() {
        return this.text().then(decode)
      }
    }

    this.json = function() {
      return this.text().then(JSON.parse)
    }

    return this
  }

  var methods = ['DELETE', 'GET', 'HEAD', 'OPTIONS', 'POST', 'PUT']

  function normalizeMethod(method) {
    var upcased = method.toUpperCase()
    return (methods.indexOf(upcased) > -1) ? upcased : method
  }

  function Request(input, options) {
    options = options || {}
    var body = options.body

    if (input instanceof Request) {
      if (input.bodyUsed) {
        throw new TypeError('Already read')
      }
      this.url = input.url
      this.credentials = input.credentials
      if (!options.headers) {
        this.headers = new Headers(input.headers)
      }
      this.method = input.method
      this.mode = input.mode
      if (!body && input._bodyInit != null) {
        body = input._bodyInit
        input.bodyUsed = true
      }
    } else {
      this.url = String(input)
    }

    this.credentials = options.credentials || this.credentials || 'omit'
    if (options.headers || !this.headers) {
      this.headers = new Headers(options.headers)
    }
    this.method = normalizeMethod(options.method || this.method || 'GET')
    this.mode = options.mode || this.mode || null
    this.referrer = null

    if ((this.method === 'GET' || this.method === 'HEAD') && body) {
      throw new TypeError('Body not allowed for GET or HEAD requests')
    }
    this._initBody(body)
  }

  Request.prototype.clone = function() {
    return new Request(this, { body: this._bodyInit })
  }

  function decode(body) {
    var form = new FormData()
    body.trim().split('&').forEach(function(bytes) {
      if (bytes) {
        var split = bytes.split('=')
        var name = split.shift().replace(/\+/g, ' ')
        var value = split.join('=').replace(/\+/g, ' ')
        form.append(decodeURIComponent(name), decodeURIComponent(value))
      }
    })
    return form
  }

  function parseHeaders(rawHeaders) {
    var headers = new Headers()
    rawHeaders.split(/\r?\n/).forEach(function(line) {
      var parts = line.split(':')
      var key = parts.shift().trim()
      if (key) {
        var value = parts.join(':').trim()
        headers.append(key, value)
      }
    })
    return headers
  }

  Body.call(Request.prototype)

  function Response(bodyInit, options) {
    if (!options) {
      options = {}
    }

    this.type = 'default'
    this.status = 'status' in options ? options.status : 200
    this.ok = this.status >= 200 && this.status < 300
    this.statusText = 'statusText' in options ? options.statusText : 'OK'
    this.headers = new Headers(options.headers)
    this.url = options.url || ''
    this._initBody(bodyInit)
  }

  Body.call(Response.prototype)

  Response.prototype.clone = function() {
    return new Response(this._bodyInit, {
      status: this.status,
      statusText: this.statusText,
      headers: new Headers(this.headers),
      url: this.url
    })
  }

  Response.error = function() {
    var response = new Response(null, {status: 0, statusText: ''})
    response.type = 'error'
    return response
  }

  var redirectStatuses = [301, 302, 303, 307, 308]

  Response.redirect = function(url, status) {
    if (redirectStatuses.indexOf(status) === -1) {
      throw new RangeError('Invalid status code')
    }

    return new Response(null, {status: status, headers: {location: url}})
  }

  self.Headers = Headers
  self.Request = Request
  self.Response = Response

  self.fetch = function(input, init) {
    return new Promise(function(resolve, reject) {
      var request = new Request(input, init)
      var xhr = new XMLHttpRequest()

      xhr.onload = function() {
        var options = {
          status: xhr.status,
          statusText: xhr.statusText,
          headers: parseHeaders(xhr.getAllResponseHeaders() || '')
        }
        options.url = 'responseURL' in xhr ? xhr.responseURL : options.headers.get('X-Request-URL')
        var body = 'response' in xhr ? xhr.response : xhr.responseText
        resolve(new Response(body, options))
      }

      xhr.onerror = function() {
        reject(new TypeError('Network request failed'))
      }

      xhr.ontimeout = function() {
        reject(new TypeError('Network request failed'))
      }

      xhr.open(request.method, request.url, true)

      if (request.credentials === 'include') {
        xhr.withCredentials = true
      }

      if ('responseType' in xhr && support.blob) {
        xhr.responseType = 'blob'
      }

      request.headers.forEach(function(value, name) {
        xhr.setRequestHeader(name, value)
      })

      xhr.send(typeof request._bodyInit === 'undefined' ? null : request._bodyInit)
    })
  }
  self.fetch.polyfill = true
})(typeof self !== 'undefined' ? self : this);

},{}]},{},[5])
