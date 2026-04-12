_N_E = (window.webpackJsonp_N_E = window.webpackJsonp_N_E || []).push([
    [7],
    {
        0: function (e, t, r) {
            r("74v/"), (e.exports = r("nOHt"));
        },
        "74v/": function (e, t, r) {
            (window.__NEXT_P = window.__NEXT_P || []).push([
                "/_app",
                function () {
                    return r("hUgY");
                },
            ]);
        },
        Gbh1: function (e, t, r) {},
        HIQL: function (e, t, r) {},
        hUgY: function (e, t, r) {
            "use strict";
            r.r(t);
            var n = r("nKUr"),
                o = r("rePB"),
                c = (r("rMck"), r("HIQL"), r("Gbh1"), r("tbn6")),
                s = r("vDqi"),
                i = r.n(s),
                u = r("q1tI");
            function a(e, t) {
                var r = Object.keys(e);
                if (Object.getOwnPropertySymbols) {
                    var n = Object.getOwnPropertySymbols(e);
                    t &&
                        (n = n.filter(function (t) {
                            return Object.getOwnPropertyDescriptor(e, t).enumerable;
                        })),
                        r.push.apply(r, n);
                }
                return r;
            }
            function p(e) {
                for (var t = 1; t < arguments.length; t++) {
                    var r = null != arguments[t] ? arguments[t] : {};
                    t % 2
                        ? a(Object(r), !0).forEach(function (t) {
                              Object(o.a)(e, t, r[t]);
                          })
                        : Object.getOwnPropertyDescriptors
                        ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(r))
                        : a(Object(r)).forEach(function (t) {
                              Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(r, t));
                          });
                }
                return e;
            }
            var f = function () {
                var e = Object(c.useToasts)().addToast;
                return (
                    Object(u.useEffect)(function () {
                        i.a.interceptors.response.use(
                            function (e) {
                                return e;
                            },
                            function (t) {
                                return i.a.isCancel(t) ? new Promise(function () {}) : (t.message && e(t.message, { appearance: "error", autoDismiss: !1 }), Promise.reject(t));
                            }
                        );
                    }, []),
                    Object(n.jsx)(n.Fragment, {})
                );
            };
            t.default = function (e) {
                var t = e.Component,
                    r = e.pageProps;
                return Object(n.jsxs)(c.ToastProvider, { children: [Object(n.jsx)(f, {}), Object(n.jsx)(t, p({}, r))] });
            };
        },
        rMck: function (e, t, r) {},
    },
    [[0, 0, 2, 1, 3, 4]],
]);
