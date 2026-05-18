var PlayerControl = function(e) {
    var t = {};

    function n(r) {
        if (t[r]) return t[r].exports;
        var i = t[r] = {
            i: r,
            l: !1,
            exports: {}
        };
        return e[r].call(i.exports, i, i.exports, n), i.l = !0, i.exports
    }
    return n.m = e, n.c = t, n.d = function(e, t, r) {
        n.o(e, t) || Object.defineProperty(e, t, {
            enumerable: !0,
            get: r
        })
    }, n.r = function(e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }, n.t = function(e, t) {
        if (1 & t && (e = n(e)), 8 & t) return e;
        if (4 & t && "object" == typeof e && e && e.__esModule) return e;
        var r = Object.create(null);
        if (n.r(r), Object.defineProperty(r, "default", {
                enumerable: !0,
                value: e
            }), 2 & t && "string" != typeof e)
            for (var i in e) n.d(r, i, function(t) {
                return e[t]
            }.bind(null, i));
        return r
    }, n.n = function(e) {
        var t = e && e.__esModule ? function() {
            return e.default
        } : function() {
            return e
        };
        return n.d(t, "a", t), t
    }, n.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, n.p = "", n(n.s = 3)
}([function(e, t, n) {
    "use strict";
    n.d(t, "h", (function() {
        return r
    })), n.d(t, "e", (function() {
        return m
    })), n.d(t, "c", (function() {
        return h
    })), n.d(t, "b", (function() {
        return p
    })), n.d(t, "d", (function() {
        return d
    })), n.d(t, "a", (function() {
        return f
    })), n.d(t, "g", (function() {
        return u
    })), n.d(t, "f", (function() {
        return c
    }));
    var r = {
            log: function() {},
            error: function() {},
            count: function() {},
            info: function() {}
        },
        i = "Chrome",
        a = "Firefox",
        o = "Edge";

    function s() {
        var e = navigator.userAgent;
        return e.includes("Edge") ? o : e.includes("Firefox") ? a : e.includes("Chrome") ? i : e.includes("Safari") ? "Safari" : e.includes("compatible") && e.includes("MSIE") && e.includes("Opera") ? "IE" : e.includes("Opera") ? "Opera" : ""
    }

    function l(e) {
        return navigator.userAgent.split(e)[1].split(".")[0].slice(1)
    }

    function u() {
        var e, t = s(),
            n = l(t),
            r = !1,
            a = !0;
        switch (t) {
            case i:
                r = n >= 104;
                break;
            default:
                r = !1
        }
        try {
            e = new MediaSource
        } catch (e) {
            r = !1, a = !1
        }
        return e || (r = !1, a = !1), {
            bSupportH265MSE: r,
            bSupportH264MSE: a
        }
    }

    function c() {
        var e = s(),
            t = l(e),
            n = !1,
            r = 0;
        switch (e) {
            case i:
                n = t >= 91, r = 701;
                break;
            case a:
                n = t >= 97, r = 702;
                break;
            case o:
                n = t >= 91, r = 703;
                break;
            default:
                n = 0
        }
        return {
            isVersionCompliance: n,
            browserType: e,
            errorCode: r
        }
    }

    function f() {
        var e = navigator.userAgent.toLowerCase(),
            t = navigator.appName,
            n = null;
        return "Microsoft Internet Explorer" === t || e.indexOf("trident") > -1 || e.indexOf("edge/") > -1 ? (n = "ie", "Microsoft Internet Explorer" === t ? (e = /msie ([0-9]{1,}[\.0-9]{0,})/.exec(e), n += parseInt(e[1])) : e.indexOf("trident") > -1 ? n += 11 : e.indexOf("edge/") > -1 && (n = "edge")) : e.indexOf("safari") > -1 ? n = e.indexOf("chrome") > -1 ? "chrome" : "safari" : e.indexOf("firefox") > -1 && (n = "firefox"), n
    }
    var h = function() {
            function e() {}
            return e.createFromElementId = function(t) {
                for (var n = document.getElementById(t), r = "", i = n.firstChild; i;) 3 === i.nodeType && (r += i.textContent), i = i.nextSibling;
                var a = new e;
                return a.type = n.type, a.source = r, a
            }, e.createFromSource = function(t, n) {
                var r = new e;
                return r.type = t, r.source = n, r
            }, e
        }(),
        d = function(e, t) {
            if ("x-shader/x-fragment" === t.type) this.shader = e.createShader(e.FRAGMENT_SHADER);
            else {
                if ("x-shader/x-vertex" !== t.type) return void error("Unknown shader type: " + t.type);
                this.shader = e.createShader(e.VERTEX_SHADER)
            }
            e.shaderSource(this.shader, t.source), e.compileShader(this.shader), e.getShaderParameter(this.shader, e.COMPILE_STATUS) || error("An error occurred compiling the shaders: " + e.getShaderInfoLog(this.shader))
        },
        p = function() {
            function e(e) {
                this.gl = e, this.program = this.gl.createProgram()
            }
            return e.prototype = {
                attach: function(e) {
                    this.gl.attachShader(this.program, e.shader)
                },
                link: function() {
                    this.gl.linkProgram(this.program)
                },
                use: function() {
                    this.gl.useProgram(this.program)
                },
                getAttributeLocation: function(e) {
                    return this.gl.getAttribLocation(this.program, e)
                },
                setMatrixUniform: function(e, t) {
                    var n = this.gl.getUniformLocation(this.program, e);
                    this.gl.uniformMatrix4fv(n, !1, t)
                }
            }, e
        }(),
        m = function() {
            var e = null;

            function t(e, t, n) {
                this.gl = e, this.size = t, this.texture = e.createTexture(), e.bindTexture(e.TEXTURE_2D, this.texture), this.format = n || e.LUMINANCE, e.texImage2D(e.TEXTURE_2D, 0, this.format, t.w, t.h, 0, this.format, e.UNSIGNED_BYTE, null), e.texParameteri(e.TEXTURE_2D, e.TEXTURE_MAG_FILTER, e.NEAREST), e.texParameteri(e.TEXTURE_2D, e.TEXTURE_MIN_FILTER, e.NEAREST), e.texParameteri(e.TEXTURE_2D, e.TEXTURE_WRAP_S, e.CLAMP_TO_EDGE), e.texParameteri(e.TEXTURE_2D, e.TEXTURE_WRAP_T, e.CLAMP_TO_EDGE)
            }
            return t.prototype = {
                fill: function(e, t) {
                    var n = this.gl;
                    n.bindTexture(n.TEXTURE_2D, this.texture), t ? n.texSubImage2D(n.TEXTURE_2D, 0, 0, 0, this.size.w, this.size.h, this.format, n.UNSIGNED_BYTE, e) : n.texImage2D(n.TEXTURE_2D, 0, this.format, this.size.w, this.size.h, 0, this.format, n.UNSIGNED_BYTE, e)
                },
                bind: function(t, n, r) {
                    var i = this.gl;
                    e || (e = [i.TEXTURE0, i.TEXTURE1, i.TEXTURE2]), i.activeTexture(e[t]), i.bindTexture(i.TEXTURE_2D, this.texture), i.uniform1i(i.getUniformLocation(n.program, r), t)
                }
            }, t
        }()
}, , function(e, t) {
    e.exports = function() {
        return new Worker("./static/WSPlayer/audioTalkWorker.js")
    }
}, function(e, t, n) {
    "use strict";
    n.r(t);
    var r = function(e) {
        var t = [],
            n = {},
            r = e;

        function i() {
            for (var e in t) t[e] = [e.charCodeAt(0), e.charCodeAt(1), e.charCodeAt(2), e.charCodeAt(3)];
            1 == r ? n.FTYP = new Uint8Array([105, 115, 111, 109, 0, 0, 0, 1, 105, 115, 111, 109, 97, 118, 99, 49]) : 2 == r && (n.FTYP = new Uint8Array([105, 115, 111, 109, 0, 0, 2, 0, 105, 115, 111, 109, 105, 115, 111, 50, 97, 118, 99, 49, 109, 112, 52, 49])), n.STSD_PREFIX = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 1]), n.STTS = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0]), n.STSC = n.STCO = n.STTS, n.STSZ = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]), n.HDLR_VIDEO = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0, 118, 105, 100, 101, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 86, 105, 100, 101, 111, 72, 97, 110, 100, 108, 101, 114, 0]), n.HDLR_AUDIO = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0, 115, 111, 117, 110, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 83, 111, 117, 110, 100, 72, 97, 110, 100, 108, 101, 114, 0]), n.DREF = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 12, 117, 114, 108, 32, 0, 0, 0, 1]), n.SMHD = new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0]), n.VMHD = new Uint8Array([0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0])
        }
        t = {
            avc1: [],
            avcC: [],
            btrt: [],
            dinf: [],
            dref: [],
            esds: [],
            ftyp: [],
            hdlr: [],
            mdat: [],
            mdhd: [],
            mdia: [],
            mfhd: [],
            minf: [],
            moof: [],
            moov: [],
            mp4a: [],
            mvex: [],
            mvhd: [],
            sdtp: [],
            stbl: [],
            stco: [],
            stsc: [],
            stsd: [],
            stsz: [],
            stts: [],
            tfdt: [],
            tfhd: [],
            traf: [],
            trak: [],
            trun: [],
            trex: [],
            tkhd: [],
            vmhd: [],
            smhd: [],
            hev1: [],
            hvcC: []
        };
        var a = function(e) {
                for (var t = 8, n = Array.prototype.slice.call(arguments, 1), r = 0; r < n.length; r++) t += n[r].byteLength;
                var i = new Uint8Array(t),
                    a = 0;
                for (i[a++] = t >>> 24 & 255, i[a++] = t >>> 16 & 255, i[a++] = t >>> 8 & 255, i[a++] = 255 & t, i.set(e, a), a += 4, r = 0; r < n.length; r++) i.set(n[r], a), a += n[r].byteLength;
                return i
            },
            o = function(e) {
                return "audio" === e.type ? a(t.stsd, n.STSD_PREFIX, function(e) {
                    return a(t.mp4a, new Uint8Array([0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, (65280 & e.channelcount) >> 8, 255 & e.channelcount, (65280 & e.samplesize) >> 8, 255 & e.samplesize, 0, 0, 0, 0, (65280 & e.samplerate) >> 8, 255 & e.samplerate, 0, 0]), function(e) {
                        var n = e.config,
                            r = n.length,
                            i = new Uint8Array([0, 0, 0, 0, 3, 23 + r, 0, 1, 0, 4, 15 + r, 64, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5].concat([r]).concat(n).concat([6, 1, 2]));
                        return a(t.esds, i)
                    }(e))
                }(e)) : a(t.stsd, n.STSD_PREFIX, function(e) {
                    var n = e.vps || [],
                        i = e.sps || [],
                        o = e.pps || [],
                        s = [],
                        l = [],
                        u = [],
                        c = 0;
                    for (c = 0; c < n.length; c++) s.push((65280 & n[c].byteLength) >>> 8), s.push(255 & n[c].byteLength), s = s.concat(Array.prototype.slice.call(n[c]));
                    for (c = 0; c < i.length; c++) l.push((65280 & i[c].byteLength) >>> 8), l.push(255 & i[c].byteLength), l = l.concat(Array.prototype.slice.call(i[c]));
                    for (c = 0; c < o.length; c++) u.push((65280 & o[c].byteLength) >>> 8), u.push(255 & o[c].byteLength), u = u.concat(Array.prototype.slice.call(o[c]));
                    return 1 == r ? a(t.avc1, new Uint8Array([0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, (65280 & e.width) >> 8, 255 & e.width, (65280 & e.height) >> 8, 255 & e.height, 0, 72, 0, 0, 0, 72, 0, 0, 0, 0, 0, 0, 0, 1, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 24, 17, 17]), a(t.avcC, new Uint8Array([1, e.profileIdc, e.profileCompatibility, e.levelIdc, 255].concat([i.length]).concat(l).concat([o.length]).concat(u)))) : 2 == r ? a(t.hev1, new Uint8Array([0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, (65280 & e.width) >> 8, 255 & e.width, (65280 & e.height) >> 8, 255 & e.height, 0, 72, 0, 0, 0, 72, 0, 0, 0, 0, 0, 0, 0, 1, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 24, 17, 17]), a(t.hvcC, new Uint8Array([1, e.general_profile_flag, (4278190080 & e.general_profile_compatibility_flags) >>> 24, (16711680 & e.general_profile_compatibility_flags) >>> 16, (65280 & e.general_profile_compatibility_flags) >>> 8, 255 & e.general_profile_compatibility_flags, (0xff0000000000 & e.general_constraint_indicator_flags) >>> 40, (0xff00000000 & e.general_constraint_indicator_flags) >>> 32, (4278190080 & e.general_constraint_indicator_flags) >>> 24, (16711680 & e.general_constraint_indicator_flags) >>> 16, (65280 & e.general_constraint_indicator_flags) >>> 8, 255 & e.general_constraint_indicator_flags, e.general_level_idc, 240, 0, 252, 252 | e.chroma_format_idc, 248 | e.bitDepthLumaMinus8, 248 | e.bitDepthChromaMinus8, 0, 0, e.rate_layers_nested_length, 3].concat([32, 0, 1]).concat(s).concat([33, 0, 1]).concat(l).concat([34, 0, 1]).concat(u)))) : void 0
                }(e))
            },
            s = function(e) {
                return a(t.mdia, function(e) {
                    var n = e.timescale,
                        r = e.duration;
                    return a(t.mdhd, new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, n >>> 24 & 255, n >>> 16 & 255, n >>> 8 & 255, 255 & n, r >>> 24 & 255, r >>> 16 & 255, r >>> 8 & 255, 255 & r, 85, 196, 0, 0]))
                }(e), function(e) {
                    var r;
                    return r = "audio" === e.type ? n.HDLR_AUDIO : n.HDLR_VIDEO, a(t.hdlr, r)
                }(e), function(e) {
                    var r;
                    return r = "audio" === e.type ? a(t.smhd, n.SMHD) : a(t.vmhd, n.VMHD), a(t.minf, r, a(t.dinf, a(t.dref, n.DREF)), function(e) {
                        return a(t.stbl, o(e), a(t.stts, n.STTS), a(t.stsc, n.STSC), a(t.stsz, n.STSZ), a(t.stco, n.STCO))
                    }(e))
                }(e))
            };
        return i.prototype = {
            initSegment: function(e) {
                var r = a(t.ftyp, n.FTYP),
                    i = function(e) {
                        var n, r, i = (n = e.timescale, r = e.duration, a(t.mvhd, new Uint8Array([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, n >>> 24 & 255, n >>> 16 & 255, n >>> 8 & 255, 255 & n, r >>> 24 & 255, r >>> 16 & 255, r >>> 8 & 255, 255 & r, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 64, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 255, 255, 255, 255]))),
                            o = function(e) {
                                return a(t.trak, function(e) {
                                    var n = e.id,
                                        r = e.duration,
                                        i = e.width,
                                        o = e.height;
                                    return a(t.tkhd, new Uint8Array([0, 0, 0, 7, 0, 0, 0, 0, 0, 0, 0, 0, n >>> 24 & 255, n >>> 16 & 255, n >>> 8 & 255, 255 & n, 0, 0, 0, 0, r >>> 24 & 255, r >>> 16 & 255, r >>> 8 & 255, 255 & r, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 64, 0, 0, 0, i >>> 8 & 255, 255 & i, 0, 0, o >>> 8 & 255, 255 & o, 0, 0]))
                                }(e), s(e))
                            }(e),
                            l = function(e) {
                                return a(t.mvex, function(e) {
                                    var n = e.id,
                                        r = new Uint8Array([0, 0, 0, 0, n >>> 24 & 255, n >>> 16 & 255, n >>> 8 & 255, 255 & n, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1]);
                                    return a(t.trex, r)
                                }(e))
                            }(e);
                        return a(t.moov, i, o, l)
                    }(e),
                    o = new Uint8Array(r.byteLength + i.byteLength);
                return o.set(r, 0), o.set(i, r.byteLength), o
            },
            mediaSegment: function(e, n, r, i) {
                var o = function(e, n) {
                        return a(t.moof, function(e) {
                            var n = new Uint8Array([0, 0, 0, 0, e >>> 24 & 255, e >>> 16 & 255, e >>> 8 & 255, 255 & e]);
                            return a(t.mfhd, n)
                        }(e), function(e) {
                            var n, r, i;
                            return n = a(t.tfhd, new Uint8Array([0, 2, 0, 0, 0, 0, 0, 1])), r = a(t.tfdt, new Uint8Array([0, 0, 0, 0, e.baseMediaDecodeTime >>> 24 & 255, e.baseMediaDecodeTime >>> 16 & 255, e.baseMediaDecodeTime >>> 8 & 255, 255 & e.baseMediaDecodeTime])), i = function(e, n) {
                                return "audio" === e.type ? audioTrun(e, 72) : function(e, n) {
                                    var r, i = null,
                                        o = null,
                                        s = 0,
                                        l = 72;
                                    if (null === (r = e.samples || [])[0].frameDuration)
                                        for (l += 24 + 4 * r.length, i = trunHeader(r, l), s = 0; s < r.length; s++) o = r[s], i = i.concat([(4278190080 & o.size) >>> 24, (16711680 & o.size) >>> 16, (65280 & o.size) >>> 8, 255 & o.size]);
                                    else
                                        for (i = function(e, t) {
                                                return [0, 0, 3, 5, (4278190080 & e.length) >>> 24, (16711680 & e.length) >>> 16, (65280 & e.length) >>> 8, 255 & e.length, (4278190080 & t) >>> 24, (16711680 & t) >>> 16, (65280 & t) >>> 8, 255 & t, 0, 0, 0, 0]
                                            }(r, l += 24 + 4 * r.length + 4 * r.length), s = 0; s < r.length; s++) o = r[s], i = i.concat([(4278190080 & o.frameDuration) >>> 24, (16711680 & o.frameDuration) >>> 16, (65280 & o.frameDuration) >>> 8, 255 & o.frameDuration, (4278190080 & o.size) >>> 24, (16711680 & o.size) >>> 16, (65280 & o.size) >>> 8, 255 & o.size]);
                                    return a(t.trun, new Uint8Array(i))
                                }(e)
                            }(e), a(t.traf, n, r, i)
                        }(n))
                    }(e, n),
                    s = function(e) {
                        return a(t.mdat, e)
                    }(r),
                    l = null;
                return (l = new Uint8Array(o.byteLength + s.byteLength)).set(o), l.set(s, o.byteLength), l
            }
        }, new i
    };

    function i(e) {
        var t = 16,
            n = 0,
            r = null,
            i = e;

        function o() {
            n = 0, r = new a
        }

        function s(e, t) {
            var r = t,
                i = n + r >> 3;
            return r = n + t & 7, e[i] >> 7 - (7 & r) & 1
        }

        function l(e, t) {
            var r = n >> 3,
                i = 8 * (r + 1) - n;
            if (i < 8)
                for (var a = 0; a < 3; a++) {
                    var o = e[r + a];
                    o = 0 == a ? o >> i << i : 2 == a ? o & 255 >> 8 - i | 1 << i : 0, e.set([o], r + a)
                } else e.set([0], r), e.set([1], r + 1)
        }

        function u(e, t) {
            if (t <= 25) var n = c(e, t);
            else n = c(e, 16) << t - 16 | c(e, t - 16);
            return n
        }

        function c(e, t) {
            var r = 0;
            if (1 === t) r = s(e, 0);
            else
                for (var i = 0; i < t; i++) r = (r << 1) + s(e, i);
            return n += t, r
        }

        function f(e, t) {
            for (var r = 0, i = 0, a = t; n + a < 8 * e.length && !s(e, a++);) r++;
            if (0 === r) return n += 1, 0;
            i = 1 << r;
            for (var o = r - 1; o >= 0; o--, a++) i |= s(e, a) << o;
            return n += 2 * r + 1, i - 1
        }

        function h(e, t) {
            var n = f(e, t);
            return 1 & n ? (n + 1) / 2 : -n / 2
        }

        function d(e) {
            r.put("cpb_cnt_minus1", f(e, 0)), r.put("bit_rate_scale", c(e, 4)), r.put("cpb_size_scale", c(e, 4));
            for (var t = r.get("cpb_cnt_minus1"), n = new Array(t), i = new Array(t), a = new Array(t), o = 0; o <= t; o++) n[o] = f(e, 0), i[o] = f(e, 0), a[o] = c(e, 1);
            r.put("bit_rate_value_minus1", n), r.put("cpb_size_value_minus1", i), r.put("cbr_flag", a), r.put("initial_cpb_removal_delay_length_minus1", c(e, 5)), r.put("cpb_removal_delay_length_minus1", c(e, 5)), r.put("dpb_output_delay_length_minus1", c(e, 5)), r.put("time_offset_length", c(e, 5))
        }
        return o.prototype = {
            parse: function(e) {
                if (n = 0, r.clear(), 1 == i) {
                    r.put("forbidden_zero_bit", c(e, 1)), r.put("nal_ref_idc", c(e, 2)), r.put("nal_unit_type", c(e, 5)), r.put("profile_idc", c(e, 8)), r.put("profile_compatibility", c(e, 8)), r.put("level_idc", c(e, 8)), r.put("seq_parameter_set_id", f(e, 0));
                    var a = r.get("profile_idc");
                    if ((100 === a || 110 === a || 122 === a || 244 === a || 44 === a || 83 === a || 86 === a || 118 === a || 128 === a || 138 === a || 139 === a || 134 === a) && (r.put("chroma_format_idc", f(e, 0)), 3 === r.get("chroma_format_idc") && r.put("separate_colour_plane_flag", c(e, 1)), r.put("bit_depth_luma_minus8", f(e, 0)), r.put("bit_depth_chroma_minus8", f(e, 0)), r.put("qpprime_y_zero_transform_bypass_flag", c(e, 1)), r.put("seq_scaling_matrix_present_flag", c(e, 1)), r.get("seq_scaling_matrix_present_flag"))) {
                        for (var o = 3 !== r.get("chroma_format_idc") ? 8 : 12, s = new Array(o), p = 0; p < o; p++)
                            if (s[p] = c(e, 1), s[p])
                                for (var m = p < 6 ? t : 64, g = 8, y = 8, v = 0; v < m; v++) y && (y = (g + h(e, 0) + 256) % 256), g = 0 === y ? g : y;
                        r.put("seq_scaling_list_present_flag", s)
                    }
                    if (r.put("log2_max_frame_num_minus4", f(e, 0)), r.put("pic_order_cnt_type", f(e, 0)), 0 === r.get("pic_order_cnt_type")) r.put("log2_max_pic_order_cnt_lsb_minus4", f(e, 0));
                    else if (1 === r.get("pic_order_cnt_type")) {
                        r.put("delta_pic_order_always_zero_flag", c(e, 1)), r.put("offset_for_non_ref_pic", h(e, 0)), r.put("offset_for_top_to_bottom_field", h(e, 0)), r.put("num_ref_frames_in_pic_order_cnt_cycle", f(e, 0));
                        for (var w = 0; w < r.get("num_ref_frames_in_pic_order_cnt_cycle"); w++) r.put("num_ref_frames_in_pic_order_cnt_cycle", h(e, 0))
                    }
                    r.put("num_ref_frames", f(e, 0)), r.put("gaps_in_frame_num_value_allowed_flag", c(e, 1)), r.put("pic_width_in_mbs_minus1", f(e, 0)), r.put("pic_height_in_map_units_minus1", f(e, 0)), r.put("frame_mbs_only_flag", c(e, 1)), 0 === r.get("frame_mbs_only_flag") && r.put("mb_adaptive_frame_field_flag", c(e, 1)), r.put("direct_8x8_interence_flag", c(e, 1)), r.put("frame_cropping_flag", c(e, 1)), 1 === r.get("frame_cropping_flag") && (r.put("frame_cropping_rect_left_offset", f(e, 0)), r.put("frame_cropping_rect_right_offset", f(e, 0)), r.put("frame_cropping_rect_top_offset", f(e, 0)), r.put("frame_cropping_rect_bottom_offset", f(e, 0))), r.put("vui_parameters_present_flag", c(e, 1)), r.get("vui_parameters_present_flag") && function(e) {
                        r.put("aspect_ratio_info_present_flag", c(e, 1)), r.get("aspect_ratio_info_present_flag") && (r.put("aspect_ratio_idc", c(e, 8)), 255 === r.get("aspect_ratio_idc") && (l(e), r.put("sar_width", c(e, t)), l(e), r.put("sar_height", c(e, t)))), r.put("overscan_info_present_flag", c(e, 1)), r.get("overscan_info_present_flag") && r.put("overscan_appropriate_flag", c(e, 1)), r.put("video_signal_type_present_flag", c(e, 1)), r.get("video_signal_type_present_flag") && (r.put("video_format", c(e, 3)), r.put("video_full_range_flag", c(e, 1)), r.put("colour_description_present_flag", c(e, 1)), r.get("colour_description_present_flag") && (r.put("colour_primaries", c(e, 8)), r.put("transfer_characteristics", c(e, 8)), r.put("matrix_coefficients", c(e, 8)))), r.put("chroma_loc_info_present_flag", c(e, 1)), r.get("chroma_loc_info_present_flag") && (r.put("chroma_sample_loc_type_top_field", f(e, 0)), r.put("chroma_sample_loc_type_bottom_field", f(e, 0))), r.put("timing_info_present_flag", c(e, 1)), r.get("timing_info_present_flag") && (r.put("num_units_in_tick", c(e, 32)), r.put("time_scale", c(e, 32)), r.put("fixed_frame_rate_flag", c(e, 1))), r.put("nal_hrd_parameters_present_flag", c(e, 1)), r.get("nal_hrd_parameters_present_flag") && d(e), r.put("vcl_hrd_parameters_present_flag", c(e, 1)), r.get("vcl_hrd_parameters_present_flag") && d(e), (r.get("nal_hrd_parameters_present_flag") || r.get("vcl_hrd_parameters_present_flag")) && r.put("low_delay_hrd_flag", c(e, 1)), r.put("pic_struct_present_flag", c(e, 1)), r.put("bitstream_restriction_flag", c(e, 1)), r.get("bitstream_restriction_flag") && (r.put("motion_vectors_over_pic_boundaries_flag", c(e, 1)), r.put("max_bytes_per_pic_denom", f(e, 0)), r.put("max_bits_per_mb_denom", f(e, 0)))
                    }(e)
                } else if (2 == i) {
                    var S = new ArrayBuffer(256),
                        b = new Uint8Array(S);
                    ! function(e, t, n, r) {
                        for (var i = 0, a = 0; i + 2 < t && a + 2 < 256; ++i) 0 == e[i] && 0 == e[i + 1] && 3 == e[i + 2] ? (n[a++] = e[i++], n[a++] = e[i++]) : n[a++] = e[i];
                        for (; i < t && a < 256;) n[a++] = e[i++]
                    }(e, e.length, b);
                    var _ = [],
                        x = [];
                    c(b, 4);
                    var A = c(b, 3);
                    for (r.put("temporalIdNested", c(b, 1)), r.put("general_profile_space", c(b, 2)), r.put("general_tier_flag", c(b, 1)), r.put("general_profile_idc", c(b, 5)), r.put("general_profile_compatibility_flags", u(b, 32)), r.put("general_constraint_indicator_flags", u(P = b, 16) << 32 | u(P, 32)), r.put("general_level_idc", c(b, 8)), p = 0; p < A && p < MAX_SUB_LAYERS; p++) _[p] = c(b, 1), x[p] = c(b, 1);
                    if (A > 0)
                        for (p = A; p < 8; p++) c(b, 2);
                    for (p = 0; p < A && p < MAX_SUB_LAYERS; p++) x[p] && c(b, 8);
                    f(b, 0), r.put("chroma_format_idc", f(b, 0)), f(b, 0), f(b, 0), c(b, 1), f(b, 0), f(b, 0), f(b, 0), f(b, 0), r.put("bitDepthLumaMinus8", f(b, 0) + 8), r.put("bitDepthChromaMinus8", f(b, 0) + 8), S = null, b = null
                }
                var P;
                return !0
            },
            getSizeInfo: function() {
                var e = 0,
                    n = 0;
                0 === r.get("chroma_format_idc") ? e = n = 0 : 1 === r.get("chroma_format_idc") ? e = n = 2 : 2 === r.get("chroma_format_idc") ? (e = 2, n = 1) : 3 === r.get("chroma_format_idc") && (0 === r.get("separate_colour_plane_flag") ? e = n = 1 : 1 === r.get("separate_colour_plane_flag") && (e = n = 0));
                var i = r.get("pic_width_in_mbs_minus1") + 1,
                    a = r.get("pic_height_in_map_units_minus1") + 1,
                    o = (2 - r.get("frame_mbs_only_flag")) * a,
                    s = 0,
                    l = 0,
                    u = 0,
                    c = 0;
                1 === r.get("frame_cropping_flag") && (s = r.get("frame_cropping_rect_left_offset"), l = r.get("frame_cropping_rect_right_offset"), u = r.get("frame_cropping_rect_top_offset"), c = r.get("frame_cropping_rect_bottom_offset"));
                var f = i * t * (o * t);
                return {
                    width: i * t - e * (s + l),
                    height: o * t - n * (2 - r.get("frame_mbs_only_flag")) * (u + c),
                    decodeSize: f
                }
            },
            getSpsValue: function(e) {
                return r.get(e)
            },
            getCodecInfo: function() {
                return r.get("profile_idc").toString(t) + (r.get("profile_compatibility") < 15 ? "0" + r.get("profile_compatibility").toString(t) : r.get("profile_compatibility").toString(t)) + r.get("level_idc").toString(t)
            }
        }, new o
    }
    var a = function() {
        this.map = {}
    };
    a.prototype = {
        put: function(e, t) {
            this.map[e] = t
        },
        get: function(e) {
            return this.map[e]
        },
        containsKey: function(e) {
            return e in this.map
        },
        containsValue: function(e) {
            for (var t in this.map)
                if (this.map[t] === e) return !0;
            return !1
        },
        isEmpty: function(e) {
            return 0 === this.size()
        },
        clear: function() {
            for (var e in this.map) delete this.map[e]
        },
        remove: function(e) {
            delete this.map[e]
        },
        keys: function() {
            var e = new Array;
            for (var t in this.map) e.push(t);
            return e
        },
        values: function() {
            var e = new Array;
            for (var t in this.map) e.push(this.map[t]);
            return e
        },
        size: function() {
            var e = 0;
            for (var t in this.map) e++;
            return e
        }
    };
    var o = function() {
            var e = null,
                t = null,
                n = null,
                r = 0,
                i = 0,
                a = !1,
                o = 0,
                s = 0,
                l = null,
                u = !1,
                c = new Float32Array(8e4),
                f = 0,
                h = null,
                d = 0;

            function p(t, r) {
                var a = r - o;
                if ((a > 200 || a < 0) && (i = 0, f = 0, u = !0, null !== h && h.stop()), i - e.currentTime < 0 && (i = 0), o = r, c = function(e, t, n) {
                        var r = e;
                        return n + t.length >= r.length && (r = new Float32Array(r.length + 8e4)).set(r, 0), r.set(t, n), r
                    }(c, t, f), f += t.length, !u) {
                    var s = 0;
                    if (f / t.length > 1 && (null !== l && (s = l * d), s >= f || null === l)) return void(f = 0);
                    var p = null;
                    (p = e.createBuffer(1, f - s, d)).getChannelData(0).set(c.subarray(s, f)), f = 0, (h = e.createBufferSource()).buffer = p, h.connect(n), i || (i = e.currentTime + .1), h.start(i), i += p.duration
                }
            }

            function m() {}
            return m.prototype = {
                audioInit: function(r, o) {
                    if (i = 0, null !== e) debug.info("Audio context already defined!");
                    else try {
                        return window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.oAudioContext || window.msAudioContext, (e = new AudioContext).onstatechange = function() {
                            "running" === e.state && (a = !0)
                        }, d = o, t = e.createGain(), (n = e.createBiquadFilter()).connect(t), n.type = "lowpass", n.frequency.value = 1500, n.gain.value = 40, t.connect(e.destination), this.setVolume(r), !0
                    } catch (r) {
                        return console.error("Web Audio API is not supported in this web browser! : " + r), !1
                    }
                },
                play: function() {
                    this.setVolume(r)
                },
                stop: function() {
                    r = 0, t.gain.value = 0, i = 0, e = null
                },
                bufferAudio: function(e, t) {
                    a && p(e, 0)
                },
                setVolume: function(e) {
                    r = e;
                    var n = e / 1;
                    n <= 0 ? (t.gain.value = 0, i = 0) : t.gain.value = n >= 1 ? 1 : n
                },
                getVolume: function() {
                    return r
                },
                Mute: function(e) {
                    if (e) t.gain.value = 0, i = 0;
                    else {
                        var n = r / 1;
                        n <= 0 ? (t.gain.value = 0, i = 0) : t.gain.value = n >= 1 ? 1 : n
                    }
                },
                terminate: function() {
                    "closed" !== e.state && (i = 0, a = !1, e.close())
                },
                setBufferingFlag: function(e, t) {
                    "init" === t ? s = e : u && (0 === e || null == e ? l = null : (l = e - s, s = 0), u = !1)
                },
                getBufferingFlag: function() {
                    return u
                },
                setInitVideoTimeStamp: function(e) {
                    s = e
                },
                getInitVideoTimeStamp: function() {
                    return s
                },
                setSamplingRate: function(e) {
                    d = e
                },
                manualResume: function() {
                    e && e.resume()
                }
            }, new m
        },
        s = n(0),
        l = 1e-6;

    function u() {}

    function c() {}

    function f() {}

    function h() {}
    u.prototype = {
        e: function(e) {
            return e < 1 || e > this.elements.length ? null : this.elements[e - 1]
        },
        dimensions: function() {
            return this.elements.length
        },
        modulus: function() {
            return Math.sqrt(this.dot(this))
        },
        eql: function(e) {
            var t = this.elements.length,
                n = e.elements || e;
            if (t != n.length) return !1;
            do {
                if (Math.abs(this.elements[t - 1] - n[t - 1]) > l) return !1
            } while (--t);
            return !0
        },
        dup: function() {
            return u.create(this.elements)
        },
        map: function(e) {
            var t = [];
            return this.each((function(n, r) {
                t.push(e(n, r))
            })), u.create(t)
        },
        each: function(e) {
            var t, n = this.elements.length,
                r = n;
            do {
                t = r - n, e(this.elements[t], t + 1)
            } while (--n)
        },
        toUnitVector: function() {
            var e = this.modulus();
            return 0 === e ? this.dup() : this.map((function(t) {
                return t / e
            }))
        },
        angleFrom: function(e) {
            var t = e.elements || e;
            if (this.elements.length != t.length) return null;
            var n = 0,
                r = 0,
                i = 0;
            if (this.each((function(e, a) {
                    n += e * t[a - 1], r += e * e, i += t[a - 1] * t[a - 1]
                })), r = Math.sqrt(r), i = Math.sqrt(i), r * i == 0) return null;
            var a = n / (r * i);
            return a < -1 && (a = -1), a > 1 && (a = 1), Math.acos(a)
        },
        isParallelTo: function(e) {
            var t = this.angleFrom(e);
            return null === t ? null : t <= l
        },
        isAntiparallelTo: function(e) {
            var t = this.angleFrom(e);
            return null === t ? null : Math.abs(t - Math.PI) <= l
        },
        isPerpendicularTo: function(e) {
            var t = this.dot(e);
            return null === t ? null : Math.abs(t) <= l
        },
        add: function(e) {
            var t = e.elements || e;
            return this.elements.length != t.length ? null : this.map((function(e, n) {
                return e + t[n - 1]
            }))
        },
        subtract: function(e) {
            var t = e.elements || e;
            return this.elements.length != t.length ? null : this.map((function(e, n) {
                return e - t[n - 1]
            }))
        },
        multiply: function(e) {
            return this.map((function(t) {
                return t * e
            }))
        },
        x: function(e) {
            return this.multiply(e)
        },
        dot: function(e) {
            var t = e.elements || e,
                n = 0,
                r = this.elements.length;
            if (r != t.length) return null;
            do {
                n += this.elements[r - 1] * t[r - 1]
            } while (--r);
            return n
        },
        cross: function(e) {
            var t = e.elements || e;
            if (3 != this.elements.length || 3 != t.length) return null;
            var n = this.elements;
            return u.create([n[1] * t[2] - n[2] * t[1], n[2] * t[0] - n[0] * t[2], n[0] * t[1] - n[1] * t[0]])
        },
        max: function() {
            var e, t = 0,
                n = this.elements.length,
                r = n;
            do {
                e = r - n, Math.abs(this.elements[e]) > Math.abs(t) && (t = this.elements[e])
            } while (--n);
            return t
        },
        indexOf: function(e) {
            var t, n = null,
                r = this.elements.length,
                i = r;
            do {
                t = i - r, null === n && this.elements[t] == e && (n = t + 1)
            } while (--r);
            return n
        },
        toDiagonalMatrix: function() {
            return c.Diagonal(this.elements)
        },
        round: function() {
            return this.map((function(e) {
                return Math.round(e)
            }))
        },
        snapTo: function(e) {
            return this.map((function(t) {
                return Math.abs(t - e) <= l ? e : t
            }))
        },
        distanceFrom: function(e) {
            if (e.anchor) return e.distanceFrom(this);
            var t = e.elements || e;
            if (t.length != this.elements.length) return null;
            var n, r = 0;
            return this.each((function(e, i) {
                n = e - t[i - 1], r += n * n
            })), Math.sqrt(r)
        },
        liesOn: function(e) {
            return e.contains(this)
        },
        liesIn: function(e) {
            return e.contains(this)
        },
        rotate: function(e, t) {
            var n, r, i, a, o;
            switch (this.elements.length) {
                case 2:
                    return 2 != (n = t.elements || t).length ? null : (r = c.Rotation(e).elements, i = this.elements[0] - n[0], a = this.elements[1] - n[1], u.create([n[0] + r[0][0] * i + r[0][1] * a, n[1] + r[1][0] * i + r[1][1] * a]));
                case 3:
                    if (!t.direction) return null;
                    var s = t.pointClosestTo(this).elements;
                    return r = c.Rotation(e, t.direction).elements, i = this.elements[0] - s[0], a = this.elements[1] - s[1], o = this.elements[2] - s[2], u.create([s[0] + r[0][0] * i + r[0][1] * a + r[0][2] * o, s[1] + r[1][0] * i + r[1][1] * a + r[1][2] * o, s[2] + r[2][0] * i + r[2][1] * a + r[2][2] * o]);
                default:
                    return null
            }
        },
        reflectionIn: function(e) {
            if (e.anchor) {
                var t = this.elements.slice(),
                    n = e.pointClosestTo(t).elements;
                return u.create([n[0] + (n[0] - t[0]), n[1] + (n[1] - t[1]), n[2] + (n[2] - (t[2] || 0))])
            }
            var r = e.elements || e;
            return this.elements.length != r.length ? null : this.map((function(e, t) {
                return r[t - 1] + (r[t - 1] - e)
            }))
        },
        to3D: function() {
            var e = this.dup();
            switch (e.elements.length) {
                case 3:
                    break;
                case 2:
                    e.elements.push(0);
                    break;
                default:
                    return null
            }
            return e
        },
        inspect: function() {
            return "[" + this.elements.join(", ") + "]"
        },
        setElements: function(e) {
            return this.elements = (e.elements || e).slice(), this
        }
    }, u.create = function(e) {
        return (new u).setElements(e)
    }, u.i = u.create([1, 0, 0]), u.j = u.create([0, 1, 0]), u.k = u.create([0, 0, 1]), u.Random = function(e) {
        var t = [];
        do {
            t.push(Math.random())
        } while (--e);
        return u.create(t)
    }, u.Zero = function(e) {
        var t = [];
        do {
            t.push(0)
        } while (--e);
        return u.create(t)
    }, c.prototype = {
        e: function(e, t) {
            return e < 1 || e > this.elements.length || t < 1 || t > this.elements[0].length ? null : this.elements[e - 1][t - 1]
        },
        row: function(e) {
            return e > this.elements.length ? null : u.create(this.elements[e - 1])
        },
        col: function(e) {
            if (e > this.elements[0].length) return null;
            var t, n = [],
                r = this.elements.length,
                i = r;
            do {
                t = i - r, n.push(this.elements[t][e - 1])
            } while (--r);
            return u.create(n)
        },
        dimensions: function() {
            return {
                rows: this.elements.length,
                cols: this.elements[0].length
            }
        },
        rows: function() {
            return this.elements.length
        },
        cols: function() {
            return this.elements[0].length
        },
        eql: function(e) {
            var t = e.elements || e;
            if (void 0 === t[0][0] && (t = c.create(t).elements), this.elements.length != t.length || this.elements[0].length != t[0].length) return !1;
            var n, r, i, a = this.elements.length,
                o = a,
                s = this.elements[0].length;
            do {
                n = o - a, r = s;
                do {
                    if (i = s - r, Math.abs(this.elements[n][i] - t[n][i]) > l) return !1
                } while (--r)
            } while (--a);
            return !0
        },
        dup: function() {
            return c.create(this.elements)
        },
        map: function(e) {
            var t, n, r, i = [],
                a = this.elements.length,
                o = a,
                s = this.elements[0].length;
            do {
                n = s, i[t = o - a] = [];
                do {
                    r = s - n, i[t][r] = e(this.elements[t][r], t + 1, r + 1)
                } while (--n)
            } while (--a);
            return c.create(i)
        },
        isSameSizeAs: function(e) {
            var t = e.elements || e;
            return void 0 === t[0][0] && (t = c.create(t).elements), this.elements.length == t.length && this.elements[0].length == t[0].length
        },
        add: function(e) {
            var t = e.elements || e;
            return void 0 === t[0][0] && (t = c.create(t).elements), this.isSameSizeAs(t) ? this.map((function(e, n, r) {
                return e + t[n - 1][r - 1]
            })) : null
        },
        subtract: function(e) {
            var t = e.elements || e;
            return void 0 === t[0][0] && (t = c.create(t).elements), this.isSameSizeAs(t) ? this.map((function(e, n, r) {
                return e - t[n - 1][r - 1]
            })) : null
        },
        canMultiplyFromLeft: function(e) {
            var t = e.elements || e;
            return void 0 === t[0][0] && (t = c.create(t).elements), this.elements[0].length == t.length
        },
        multiply: function(e) {
            if (!e.elements) return this.map((function(t) {
                return t * e
            }));
            var t = !!e.modulus;
            if (void 0 === (p = e.elements || e)[0][0] && (p = c.create(p).elements), !this.canMultiplyFromLeft(p)) return null;
            var n, r, i, a, o, s, l = this.elements.length,
                u = l,
                f = p[0].length,
                h = this.elements[0].length,
                d = [];
            do {
                d[n = u - l] = [], r = f;
                do {
                    i = f - r, a = 0, o = h;
                    do {
                        s = h - o, a += this.elements[n][s] * p[s][i]
                    } while (--o);
                    d[n][i] = a
                } while (--r)
            } while (--l);
            var p = c.create(d);
            return t ? p.col(1) : p
        },
        x: function(e) {
            return this.multiply(e)
        },
        minor: function(e, t, n, r) {
            var i, a, o, s = [],
                l = n,
                u = this.elements.length,
                f = this.elements[0].length;
            do {
                s[i = n - l] = [], a = r;
                do {
                    o = r - a, s[i][o] = this.elements[(e + i - 1) % u][(t + o - 1) % f]
                } while (--a)
            } while (--l);
            return c.create(s)
        },
        transpose: function() {
            var e, t, n, r = this.elements.length,
                i = this.elements[0].length,
                a = [],
                o = i;
            do {
                a[e = i - o] = [], t = r;
                do {
                    n = r - t, a[e][n] = this.elements[n][e]
                } while (--t)
            } while (--o);
            return c.create(a)
        },
        isSquare: function() {
            return this.elements.length == this.elements[0].length
        },
        max: function() {
            var e, t, n, r = 0,
                i = this.elements.length,
                a = i,
                o = this.elements[0].length;
            do {
                e = a - i, t = o;
                do {
                    n = o - t, Math.abs(this.elements[e][n]) > Math.abs(r) && (r = this.elements[e][n])
                } while (--t)
            } while (--i);
            return r
        },
        indexOf: function(e) {
            var t, n, r, i = this.elements.length,
                a = i,
                o = this.elements[0].length;
            do {
                t = a - i, n = o;
                do {
                    if (r = o - n, this.elements[t][r] == e) return {
                        i: t + 1,
                        j: r + 1
                    }
                } while (--n)
            } while (--i);
            return null
        },
        diagonal: function() {
            if (!this.isSquare) return null;
            var e, t = [],
                n = this.elements.length,
                r = n;
            do {
                e = r - n, t.push(this.elements[e][e])
            } while (--n);
            return u.create(t)
        },
        toRightTriangular: function() {
            var e, t, n, r, i = this.dup(),
                a = this.elements.length,
                o = a,
                s = this.elements[0].length;
            do {
                if (t = o - a, 0 == i.elements[t][t])
                    for (j = t + 1; j < o; j++)
                        if (0 != i.elements[j][t]) {
                            e = [], n = s;
                            do {
                                r = s - n, e.push(i.elements[t][r] + i.elements[j][r])
                            } while (--n);
                            i.elements[t] = e;
                            break
                        } if (0 != i.elements[t][t])
                    for (j = t + 1; j < o; j++) {
                        var l = i.elements[j][t] / i.elements[t][t];
                        e = [], n = s;
                        do {
                            r = s - n, e.push(r <= t ? 0 : i.elements[j][r] - i.elements[t][r] * l)
                        } while (--n);
                        i.elements[j] = e
                    }
            } while (--a);
            return i
        },
        toUpperTriangular: function() {
            return this.toRightTriangular()
        },
        determinant: function() {
            if (!this.isSquare()) return null;
            var e, t = this.toRightTriangular(),
                n = t.elements[0][0],
                r = t.elements.length - 1,
                i = r;
            do {
                e = i - r + 1, n *= t.elements[e][e]
            } while (--r);
            return n
        },
        det: function() {
            return this.determinant()
        },
        isSingular: function() {
            return this.isSquare() && 0 === this.determinant()
        },
        trace: function() {
            if (!this.isSquare()) return null;
            var e, t = this.elements[0][0],
                n = this.elements.length - 1,
                r = n;
            do {
                e = r - n + 1, t += this.elements[e][e]
            } while (--n);
            return t
        },
        tr: function() {
            return this.trace()
        },
        rank: function() {
            var e, t, n, r = this.toRightTriangular(),
                i = 0,
                a = this.elements.length,
                o = a,
                s = this.elements[0].length;
            do {
                e = o - a, t = s;
                do {
                    if (n = s - t, Math.abs(r.elements[e][n]) > l) {
                        i++;
                        break
                    }
                } while (--t)
            } while (--a);
            return i
        },
        rk: function() {
            return this.rank()
        },
        augment: function(e) {
            var t = e.elements || e;
            void 0 === t[0][0] && (t = c.create(t).elements);
            var n, r, i, a = this.dup(),
                o = a.elements[0].length,
                s = a.elements.length,
                l = s,
                u = t[0].length;
            if (s != t.length) return null;
            do {
                n = l - s, r = u;
                do {
                    i = u - r, a.elements[n][o + i] = t[n][i]
                } while (--r)
            } while (--s);
            return a
        },
        inverse: function() {
            if (!this.isSquare() || this.isSingular()) return null;
            var e, t, n, r, i, a, o, s = this.elements.length,
                l = s,
                u = this.augment(c.I(s)).toRightTriangular(),
                f = u.elements[0].length,
                h = [];
            do {
                i = [], n = f, h[e = s - 1] = [], a = u.elements[e][e];
                do {
                    r = f - n, o = u.elements[e][r] / a, i.push(o), r >= l && h[e].push(o)
                } while (--n);
                for (u.elements[e] = i, t = 0; t < e; t++) {
                    i = [], n = f;
                    do {
                        r = f - n, i.push(u.elements[t][r] - u.elements[e][r] * u.elements[t][e])
                    } while (--n);
                    u.elements[t] = i
                }
            } while (--s);
            return c.create(h)
        },
        inv: function() {
            return this.inverse()
        },
        round: function() {
            return this.map((function(e) {
                return Math.round(e)
            }))
        },
        snapTo: function(e) {
            return this.map((function(t) {
                return Math.abs(t - e) <= l ? e : t
            }))
        },
        inspect: function() {
            var e, t = [],
                n = this.elements.length,
                r = n;
            do {
                e = r - n, t.push(u.create(this.elements[e]).inspect())
            } while (--n);
            return t.join("\n")
        },
        setElements: function(e) {
            var t, n = e.elements || e;
            if (void 0 !== n[0][0]) {
                var r, i, a, o = n.length,
                    s = o;
                this.elements = [];
                do {
                    i = r = n[t = s - o].length, this.elements[t] = [];
                    do {
                        a = i - r, this.elements[t][a] = n[t][a]
                    } while (--r)
                } while (--o);
                return this
            }
            var l = n.length,
                u = l;
            this.elements = [];
            do {
                t = u - l, this.elements.push([n[t]])
            } while (--l);
            return this
        }
    }, c.create = function(e) {
        return (new c).setElements(e)
    }, c.I = function(e) {
        var t, n, r, i = [],
            a = e;
        do {
            i[t = a - e] = [], n = a;
            do {
                r = a - n, i[t][r] = t == r ? 1 : 0
            } while (--n)
        } while (--e);
        return c.create(i)
    }, c.Diagonal = function(e) {
        var t, n = e.length,
            r = n,
            i = c.I(n);
        do {
            t = r - n, i.elements[t][t] = e[t]
        } while (--n);
        return i
    }, c.Rotation = function(e, t) {
        if (!t) return c.create([
            [Math.cos(e), -Math.sin(e)],
            [Math.sin(e), Math.cos(e)]
        ]);
        var n = t.dup();
        if (3 != n.elements.length) return null;
        var r = n.modulus(),
            i = n.elements[0] / r,
            a = n.elements[1] / r,
            o = n.elements[2] / r,
            s = Math.sin(e),
            l = Math.cos(e),
            u = 1 - l;
        return c.create([
            [u * i * i + l, u * i * a - s * o, u * i * o + s * a],
            [u * i * a + s * o, u * a * a + l, u * a * o - s * i],
            [u * i * o - s * a, u * a * o + s * i, u * o * o + l]
        ])
    }, c.RotationX = function(e) {
        var t = Math.cos(e),
            n = Math.sin(e);
        return c.create([
            [1, 0, 0],
            [0, t, -n],
            [0, n, t]
        ])
    }, c.RotationY = function(e) {
        var t = Math.cos(e),
            n = Math.sin(e);
        return c.create([
            [t, 0, n],
            [0, 1, 0],
            [-n, 0, t]
        ])
    }, c.RotationZ = function(e) {
        var t = Math.cos(e),
            n = Math.sin(e);
        return c.create([
            [t, -n, 0],
            [n, t, 0],
            [0, 0, 1]
        ])
    }, c.Random = function(e, t) {
        return c.Zero(e, t).map((function() {
            return Math.random()
        }))
    }, c.Zero = function(e, t) {
        var n, r, i, a = [],
            o = e;
        do {
            a[n = e - o] = [], r = t;
            do {
                i = t - r, a[n][i] = 0
            } while (--r)
        } while (--o);
        return c.create(a)
    }, f.prototype = {
        eql: function(e) {
            return this.isParallelTo(e) && this.contains(e.anchor)
        },
        dup: function() {
            return f.create(this.anchor, this.direction)
        },
        translate: function(e) {
            var t = e.elements || e;
            return f.create([this.anchor.elements[0] + t[0], this.anchor.elements[1] + t[1], this.anchor.elements[2] + (t[2] || 0)], this.direction)
        },
        isParallelTo: function(e) {
            if (e.normal) return e.isParallelTo(this);
            var t = this.direction.angleFrom(e.direction);
            return Math.abs(t) <= l || Math.abs(t - Math.PI) <= l
        },
        distanceFrom: function(e) {
            if (e.normal) return e.distanceFrom(this);
            if (e.direction) {
                if (this.isParallelTo(e)) return this.distanceFrom(e.anchor);
                var t = this.direction.cross(e.direction).toUnitVector().elements,
                    n = this.anchor.elements,
                    r = e.anchor.elements;
                return Math.abs((n[0] - r[0]) * t[0] + (n[1] - r[1]) * t[1] + (n[2] - r[2]) * t[2])
            }
            var i = e.elements || e,
                a = (n = this.anchor.elements, this.direction.elements),
                o = i[0] - n[0],
                s = i[1] - n[1],
                l = (i[2] || 0) - n[2],
                u = Math.sqrt(o * o + s * s + l * l);
            if (0 === u) return 0;
            var c = (o * a[0] + s * a[1] + l * a[2]) / u,
                f = 1 - c * c;
            return Math.abs(u * Math.sqrt(f < 0 ? 0 : f))
        },
        contains: function(e) {
            var t = this.distanceFrom(e);
            return null !== t && t <= l
        },
        liesIn: function(e) {
            return e.contains(this)
        },
        intersects: function(e) {
            return e.normal ? e.intersects(this) : !this.isParallelTo(e) && this.distanceFrom(e) <= l
        },
        intersectionWith: function(e) {
            if (e.normal) return e.intersectionWith(this);
            if (!this.intersects(e)) return null;
            var t = this.anchor.elements,
                n = this.direction.elements,
                r = e.anchor.elements,
                i = e.direction.elements,
                a = n[0],
                o = n[1],
                s = n[2],
                l = i[0],
                c = i[1],
                f = i[2],
                h = t[0] - r[0],
                d = t[1] - r[1],
                p = t[2] - r[2],
                m = l * l + c * c + f * f,
                g = a * l + o * c + s * f,
                y = ((-a * h - o * d - s * p) * m / (a * a + o * o + s * s) + g * (l * h + c * d + f * p)) / (m - g * g);
            return u.create([t[0] + y * a, t[1] + y * o, t[2] + y * s])
        },
        pointClosestTo: function(e) {
            if (e.direction) {
                if (this.intersects(e)) return this.intersectionWith(e);
                if (this.isParallelTo(e)) return null;
                var t = this.direction.elements,
                    n = e.direction.elements,
                    r = t[0],
                    i = t[1],
                    a = t[2],
                    o = n[0],
                    s = n[1],
                    l = n[2],
                    c = a * o - r * l,
                    f = r * s - i * o,
                    d = i * l - a * s,
                    p = u.create([c * l - f * s, f * o - d * l, d * s - c * o]);
                return (m = h.create(e.anchor, p)).intersectionWith(this)
            }
            var m = e.elements || e;
            if (this.contains(m)) return u.create(m);
            var g = this.anchor.elements,
                y = (r = (t = this.direction.elements)[0], i = t[1], a = t[2], g[0]),
                v = g[1],
                w = g[2],
                S = (c = r * (m[1] - v) - i * (m[0] - y), f = i * ((m[2] || 0) - w) - a * (m[1] - v), d = a * (m[0] - y) - r * ((m[2] || 0) - w), u.create([i * c - a * d, a * f - r * c, r * d - i * f])),
                b = this.distanceFrom(m) / S.modulus();
            return u.create([m[0] + S.elements[0] * b, m[1] + S.elements[1] * b, (m[2] || 0) + S.elements[2] * b])
        },
        rotate: function(e, t) {
            void 0 === t.direction && (t = f.create(t.to3D(), u.k));
            var n = c.Rotation(e, t.direction).elements,
                r = t.pointClosestTo(this.anchor).elements,
                i = this.anchor.elements,
                a = this.direction.elements,
                o = r[0],
                s = r[1],
                l = r[2],
                h = i[0] - o,
                d = i[1] - s,
                p = i[2] - l;
            return f.create([o + n[0][0] * h + n[0][1] * d + n[0][2] * p, s + n[1][0] * h + n[1][1] * d + n[1][2] * p, l + n[2][0] * h + n[2][1] * d + n[2][2] * p], [n[0][0] * a[0] + n[0][1] * a[1] + n[0][2] * a[2], n[1][0] * a[0] + n[1][1] * a[1] + n[1][2] * a[2], n[2][0] * a[0] + n[2][1] * a[1] + n[2][2] * a[2]])
        },
        reflectionIn: function(e) {
            if (e.normal) {
                var t = this.anchor.elements,
                    n = this.direction.elements,
                    r = t[0],
                    i = t[1],
                    a = t[2],
                    o = n[0],
                    s = n[1],
                    l = n[2],
                    u = this.anchor.reflectionIn(e).elements,
                    c = r + o,
                    h = i + s,
                    d = a + l,
                    p = e.pointClosestTo([c, h, d]).elements,
                    m = [p[0] + (p[0] - c) - u[0], p[1] + (p[1] - h) - u[1], p[2] + (p[2] - d) - u[2]];
                return f.create(u, m)
            }
            if (e.direction) return this.rotate(Math.PI, e);
            var g = e.elements || e;
            return f.create(this.anchor.reflectionIn([g[0], g[1], g[2] || 0]), this.direction)
        },
        setVectors: function(e, t) {
            if (e = u.create(e), t = u.create(t), 2 == e.elements.length && e.elements.push(0), 2 == t.elements.length && t.elements.push(0), e.elements.length > 3 || t.elements.length > 3) return null;
            var n = t.modulus();
            return 0 === n ? null : (this.anchor = e, this.direction = u.create([t.elements[0] / n, t.elements[1] / n, t.elements[2] / n]), this)
        }
    }, f.create = function(e, t) {
        return (new f).setVectors(e, t)
    }, f.X = f.create(u.Zero(3), u.i), f.Y = f.create(u.Zero(3), u.j), f.Z = f.create(u.Zero(3), u.k), h.prototype = {
        eql: function(e) {
            return this.contains(e.anchor) && this.isParallelTo(e)
        },
        dup: function() {
            return h.create(this.anchor, this.normal)
        },
        translate: function(e) {
            var t = e.elements || e;
            return h.create([this.anchor.elements[0] + t[0], this.anchor.elements[1] + t[1], this.anchor.elements[2] + (t[2] || 0)], this.normal)
        },
        isParallelTo: function(e) {
            var t;
            return e.normal ? (t = this.normal.angleFrom(e.normal), Math.abs(t) <= l || Math.abs(Math.PI - t) <= l) : e.direction ? this.normal.isPerpendicularTo(e.direction) : null
        },
        isPerpendicularTo: function(e) {
            var t = this.normal.angleFrom(e.normal);
            return Math.abs(Math.PI / 2 - t) <= l
        },
        distanceFrom: function(e) {
            if (this.intersects(e) || this.contains(e)) return 0;
            if (e.anchor) {
                var t = this.anchor.elements,
                    n = e.anchor.elements,
                    r = this.normal.elements;
                return Math.abs((t[0] - n[0]) * r[0] + (t[1] - n[1]) * r[1] + (t[2] - n[2]) * r[2])
            }
            var i = e.elements || e;
            return t = this.anchor.elements, r = this.normal.elements, Math.abs((t[0] - i[0]) * r[0] + (t[1] - i[1]) * r[1] + (t[2] - (i[2] || 0)) * r[2])
        },
        contains: function(e) {
            if (e.normal) return null;
            if (e.direction) return this.contains(e.anchor) && this.contains(e.anchor.add(e.direction));
            var t = e.elements || e,
                n = this.anchor.elements,
                r = this.normal.elements;
            return Math.abs(r[0] * (n[0] - t[0]) + r[1] * (n[1] - t[1]) + r[2] * (n[2] - (t[2] || 0))) <= l
        },
        intersects: function(e) {
            return void 0 === e.direction && void 0 === e.normal ? null : !this.isParallelTo(e)
        },
        intersectionWith: function(e) {
            if (!this.intersects(e)) return null;
            if (e.direction) {
                var t = e.anchor.elements,
                    n = e.direction.elements,
                    r = this.anchor.elements,
                    i = ((o = this.normal.elements)[0] * (r[0] - t[0]) + o[1] * (r[1] - t[1]) + o[2] * (r[2] - t[2])) / (o[0] * n[0] + o[1] * n[1] + o[2] * n[2]);
                return u.create([t[0] + n[0] * i, t[1] + n[1] * i, t[2] + n[2] * i])
            }
            if (e.normal) {
                for (var a = this.normal.cross(e.normal).toUnitVector(), o = this.normal.elements, s = (t = this.anchor.elements, e.normal.elements), l = e.anchor.elements, h = c.Zero(2, 2), d = 0; h.isSingular();) d++, h = c.create([
                    [o[d % 3], o[(d + 1) % 3]],
                    [s[d % 3], s[(d + 1) % 3]]
                ]);
                for (var p = h.inverse().elements, m = o[0] * t[0] + o[1] * t[1] + o[2] * t[2], g = s[0] * l[0] + s[1] * l[1] + s[2] * l[2], y = [p[0][0] * m + p[0][1] * g, p[1][0] * m + p[1][1] * g], v = [], w = 1; w <= 3; w++) v.push(d == w ? 0 : y[(w + (5 - d) % 3) % 3]);
                return f.create(v, a)
            }
        },
        pointClosestTo: function(e) {
            var t = e.elements || e,
                n = this.anchor.elements,
                r = this.normal.elements,
                i = (n[0] - t[0]) * r[0] + (n[1] - t[1]) * r[1] + (n[2] - (t[2] || 0)) * r[2];
            return u.create([t[0] + r[0] * i, t[1] + r[1] * i, (t[2] || 0) + r[2] * i])
        },
        rotate: function(e, t) {
            var n = c.Rotation(e, t.direction).elements,
                r = t.pointClosestTo(this.anchor).elements,
                i = this.anchor.elements,
                a = this.normal.elements,
                o = r[0],
                s = r[1],
                l = r[2],
                u = i[0] - o,
                f = i[1] - s,
                d = i[2] - l;
            return h.create([o + n[0][0] * u + n[0][1] * f + n[0][2] * d, s + n[1][0] * u + n[1][1] * f + n[1][2] * d, l + n[2][0] * u + n[2][1] * f + n[2][2] * d], [n[0][0] * a[0] + n[0][1] * a[1] + n[0][2] * a[2], n[1][0] * a[0] + n[1][1] * a[1] + n[1][2] * a[2], n[2][0] * a[0] + n[2][1] * a[1] + n[2][2] * a[2]])
        },
        reflectionIn: function(e) {
            if (e.normal) {
                var t = this.anchor.elements,
                    n = this.normal.elements,
                    r = t[0],
                    i = t[1],
                    a = t[2],
                    o = n[0],
                    s = n[1],
                    l = n[2],
                    u = this.anchor.reflectionIn(e).elements,
                    c = r + o,
                    f = i + s,
                    d = a + l,
                    p = e.pointClosestTo([c, f, d]).elements,
                    m = [p[0] + (p[0] - c) - u[0], p[1] + (p[1] - f) - u[1], p[2] + (p[2] - d) - u[2]];
                return h.create(u, m)
            }
            if (e.direction) return this.rotate(Math.PI, e);
            var g = e.elements || e;
            return h.create(this.anchor.reflectionIn([g[0], g[1], g[2] || 0]), this.normal)
        },
        setVectors: function(e, t, n) {
            if (null === (e = (e = u.create(e)).to3D())) return null;
            if (null === (t = (t = u.create(t)).to3D())) return null;
            if (void 0 === n) n = null;
            else if (null === (n = (n = u.create(n)).to3D())) return null;
            var r, i, a = e.elements[0],
                o = e.elements[1],
                s = e.elements[2],
                l = t.elements[0],
                c = t.elements[1],
                f = t.elements[2];
            if (null !== n) {
                var h = n.elements[0],
                    d = n.elements[1],
                    p = n.elements[2];
                if (0 === (i = (r = u.create([(c - o) * (p - s) - (f - s) * (d - o), (f - s) * (h - a) - (l - a) * (p - s), (l - a) * (d - o) - (c - o) * (h - a)])).modulus())) return null;
                r = u.create([r.elements[0] / i, r.elements[1] / i, r.elements[2] / i])
            } else {
                if (0 === (i = Math.sqrt(l * l + c * c + f * f))) return null;
                r = u.create([t.elements[0] / i, t.elements[1] / i, t.elements[2] / i])
            }
            return this.anchor = e, this.normal = r, this
        }
    }, c.Translation = function(e) {
        var t;
        if (2 === e.elements.length) return (t = c.I(3)).elements[2][0] = e.elements[0], t.elements[2][1] = e.elements[1], t;
        if (3 === e.elements.length) return (t = c.I(4)).elements[0][3] = e.elements[0], t.elements[1][3] = e.elements[1], t.elements[2][3] = e.elements[2], t;
        throw "Invalid length for Translation"
    }, c.prototype.flatten = function() {
        var e = [];
        if (0 === this.elements.length) return [];
        for (var t = 0; t < this.elements[0].length; t++)
            for (var n = 0; n < this.elements.length; n++) e.push(this.elements[n][t]);
        return e
    }, c.prototype.ensure4x4 = function() {
        var e;
        if (4 === this.elements.length && 4 === this.elements[0].length) return this;
        if (this.elements.length > 4 || this.elements[0].length > 4) return null;
        for (e = 0; e < this.elements.length; e++)
            for (var t = this.elements[e].length; t < 4; t++) e === t ? this.elements[e].push(1) : this.elements[e].push(0);
        for (e = this.elements.length; e < 4; e++) 0 === e ? this.elements.push([1, 0, 0, 0]) : 1 === e ? this.elements.push([0, 1, 0, 0]) : 2 === e ? this.elements.push([0, 0, 1, 0]) : 3 === e && this.elements.push([0, 0, 0, 1]);
        return this
    }, c.prototype.make3x3 = function() {
        return 4 !== this.elements.length || 4 !== this.elements[0].length ? null : c.create([
            [this.elements[0][0], this.elements[0][1], this.elements[0][2]],
            [this.elements[1][0], this.elements[1][1], this.elements[1][2]],
            [this.elements[2][0], this.elements[2][1], this.elements[2][2]]
        ])
    }, h.create = function(e, t, n) {
        return (new h).setVectors(e, t, n)
    }, h.XY = h.create(u.Zero(3), u.k), h.YZ = h.create(u.Zero(3), u.i), h.ZX = h.create(u.Zero(3), u.j), h.YX = h.XY, h.ZY = h.YZ, h.XZ = h.ZX;
    var d = u.create,
        p = c.create,
        m = (f.create, h.create, function() {
            function e(e, t, n) {
                s.e.call(this, e, t, n)
            }
            return e.prototype = v(s.e, {
                fill: function(e, t) {
                    var n = this.gl;
                    n.bindTexture(n.TEXTURE_2D, this.texture), t ? n.texSubImage2D(n.TEXTURE_2D, 0, 0, 0, this.size.w, this.size.h, this.format, n.UNSIGNED_BYTE, e) : n.texImage2D(n.TEXTURE_2D, 0, this.format, this.format, n.UNSIGNED_BYTE, e)
                }
            }), e
        }()),
        g = function() {
            var e = s.c.createFromSource("x-shader/x-vertex", w(["attribute vec3 aVertexPosition;", "attribute vec2 aTextureCoord;", "uniform mat4 uMVMatrix;", "uniform mat4 uPMatrix;", "varying highp vec2 vTextureCoord;", "void main(void) {", "  gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.0);", "  vTextureCoord = aTextureCoord;", "}"])),
                t = s.c.createFromSource("x-shader/x-fragment", w(["precision highp float;", "varying highp vec2 vTextureCoord;", "uniform sampler2D texture;", "void main(void) {", "  gl_FragColor = texture2D(texture, vTextureCoord);", "}"]));

            function n(e, t, n) {
                this.canvas = e, this.size = t, this.canvas.width = t.w, this.canvas.height = t.h, this.onInitWebGL(), this.onInitShaders(),
                    function() {
                        var e = [1, 1, 0, -1, 1, 0, 1, -1, 0, -1, -1, 0],
                            t = this.gl;
                        this.quadVPBuffer = t.createBuffer(), t.bindBuffer(t.ARRAY_BUFFER, this.quadVPBuffer), t.bufferData(t.ARRAY_BUFFER, new Float32Array(e), t.STATIC_DRAW), this.quadVPBuffer.itemSize = 3, this.quadVPBuffer.numItems = 4, this.quadVTCBuffer = t.createBuffer(), t.bindBuffer(t.ARRAY_BUFFER, this.quadVTCBuffer), e = [1, 0, 0, 0, 1, 1, 0, 1], t.bufferData(t.ARRAY_BUFFER, new Float32Array(e), t.STATIC_DRAW)
                    }.call(this), n && function() {
                        var e = this.gl;
                        this.framebuffer = e.createFramebuffer(), e.bindFramebuffer(e.FRAMEBUFFER, this.framebuffer), this.framebufferTexture = new s.e(this.gl, this.size, e.RGBA);
                        var t = e.createRenderbuffer();
                        e.bindRenderbuffer(e.RENDERBUFFER, t), e.renderbufferStorage(e.RENDERBUFFER, e.DEPTH_COMPONENT16, this.size.w, this.size.h), e.framebufferTexture2D(e.FRAMEBUFFER, e.COLOR_ATTACHMENT0, e.TEXTURE_2D, this.framebufferTexture.texture, 0), e.framebufferRenderbuffer(e.FRAMEBUFFER, e.DEPTH_ATTACHMENT, e.RENDERBUFFER, t)
                    }.call(this), this.onInitTextures(),
                    function() {
                        var e = this.gl;
                        this.perspectiveMatrix = function(e, t, n, r) {
                            var i = .1 * Math.tan(45 * Math.PI / 360),
                                a = -i;
                            return function(e, t, n, r, i, a) {
                                return p([
                                    [.2 / (t - e), 0, (t + e) / (t - e), 0],
                                    [0, .2 / (r - n), (r + n) / (r - n), 0],
                                    [0, 0, -100.1 / 99.9, -20 / 99.9],
                                    [0, 0, -1, 0]
                                ])
                            }(1 * a, 1 * i, a, i)
                        }(), r.call(this), i.call(this, [0, 0, -2.415]), e.bindBuffer(e.ARRAY_BUFFER, this.quadVPBuffer), e.vertexAttribPointer(this.vertexPositionAttribute, 3, e.FLOAT, !1, 0, 0), e.bindBuffer(e.ARRAY_BUFFER, this.quadVTCBuffer), e.vertexAttribPointer(this.textureCoordAttribute, 2, e.FLOAT, !1, 0, 0), this.onInitSceneTextures(), a.call(this), this.framebuffer && e.bindFramebuffer(e.FRAMEBUFFER, this.framebuffer)
                    }.call(this)
            }

            function r() {
                this.mvMatrix = c.I(4)
            }

            function i(e) {
                (function(e) {
                    this.mvMatrix = this.mvMatrix.x(e)
                }).call(this, c.Translation(d([e[0], e[1], e[2]])).ensure4x4())
            }

            function a() {
                this.program.setMatrixUniform("uPMatrix", new Float32Array(this.perspectiveMatrix.flatten())), this.program.setMatrixUniform("uMVMatrix", new Float32Array(this.mvMatrix.flatten()))
            }
            return n.prototype = {
                toString: function() {
                    return "WebGLCanvas Size: " + this.size
                },
                checkLastError: function(e) {
                    var t = this.gl.getError();
                    if (t !== this.gl.NO_ERROR) {
                        var n = this.glNames[t];
                        n = void 0 !== n ? n + "(" + t + ")" : "Unknown WebGL ENUM (0x" + value.toString(16) + ")", e ? s.h.log("WebGL Error: %s, %s", e, n) : s.h.log("WebGL Error: %s", n), s.h.trace()
                    }
                },
                onInitWebGL: function() {
                    try {
                        this.gl = this.canvas.getContext("webgl")
                    } catch (e) {
                        s.h.log("inInitWebGL error = " + e)
                    }
                    if (this.gl || s.h.error("Unable to initialize WebGL. Your browser may not support it."), !this.glNames)
                        for (var e in this.glNames = {}, this.gl) "number" == typeof this.gl[e] && (this.glNames[this.gl[e]] = e)
                },
                onInitShaders: function() {
                    this.program = new s.b(this.gl), this.program.attach(new s.d(this.gl, e)), this.program.attach(new s.d(this.gl, t)), this.program.link(), this.program.use(), this.vertexPositionAttribute = this.program.getAttributeLocation("aVertexPosition"), this.gl.enableVertexAttribArray(this.vertexPositionAttribute), this.textureCoordAttribute = this.program.getAttributeLocation("aTextureCoord"), this.gl.enableVertexAttribArray(this.textureCoordAttribute)
                },
                onInitTextures: function() {
                    var e = this.gl;
                    e.viewport(0, 0, this.canvas.width, this.canvas.height), this.texture = new s.e(e, this.size, e.RGBA)
                },
                onInitSceneTextures: function() {
                    this.texture.bind(0, this.program, "texture")
                },
                drawScene: function() {
                    this.gl.drawArrays(this.gl.TRIANGLE_STRIP, 0, 4)
                },
                updateVertexArray: function(e) {
                    this.zoomScene(e)
                },
                readPixels: function(e) {
                    var t = this.gl;
                    t.readPixels(0, 0, this.size.w, this.size.h, t.RGBA, t.UNSIGNED_BYTE, e)
                },
                zoomScene: function(e) {
                    r.call(this), i.call(this, [e[0], e[1], e[2]]), a.call(this), this.drawScene()
                },
                setViewport: function(e, t) {
                    var n, r;
                    s.h.log("toWidth=" + e + ",toHeight=" + t), this.gl.drawingBufferWidth < e || this.gl.drawingBufferHeight < t ? (n = this.gl.drawingBufferWidth, r = this.gl.drawingBufferHeight, this.canvas.width = n, this.canvas.height = r) : (n = e, r = t), this.gl.viewport(0, 0, n, r)
                },
                clearCanvas: function() {
                    this.gl.clearColor(0, 0, 0, 1), this.gl.clear(this.gl.DEPTH_BUFFER_BIT | this.gl.COLOR_BUFFER_BIT)
                }
            }, n
        }(),
        y = (v(g, {
            drawCanvas: function(e) {
                this.texture.fill(e), this.drawScene()
            },
            onInitTextures: function() {
                var e = this.gl;
                this.setViewport(this.canvas.width, this.canvas.height), this.texture = new m(e, this.size, e.RGBA)
            },
            initCanvas: function() {
                this.gl.clear(this.gl.DEPTH_BUFFER_BIT | this.gl.COLOR_BUFFER_BIT)
            }
        }), function() {
            var e = s.c.createFromSource("x-shader/x-vertex", w(["attribute vec3 aVertexPosition;", "attribute vec2 aTextureCoord;", "uniform mat4 uMVMatrix;", "uniform mat4 uPMatrix;", "varying highp vec2 vTextureCoord;", "void main(void) {", "  gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.0);", "  vTextureCoord = aTextureCoord;", "}"])),
                t = s.c.createFromSource("x-shader/x-fragment", w(["precision highp float;", "varying highp vec2 vTextureCoord;", "uniform sampler2D YTexture;", "uniform sampler2D UTexture;", "uniform sampler2D VTexture;", "const mat4 YUV2RGB = mat4", "(", " 1.16438, 0.00000, 1.59603, -.87079,", " 1.16438, -.39176, -.81297, .52959,", " 1.16438, 2.01723, 0, -1.08139,", " 0, 0, 0, 1", ");", "void main(void) {", " gl_FragColor = vec4( texture2D(YTexture,  vTextureCoord).x, texture2D(UTexture, vTextureCoord).x, texture2D(VTexture, vTextureCoord).x, 1) * YUV2RGB;", "}"]));

            function n(e, t) {
                this.isPlayStart = !0, g.call(this, e, t)
            }
            return n.prototype = v(g, {
                onInitShaders: function() {
                    this.program = new s.b(this.gl), this.program.attach(new s.d(this.gl, e)), this.program.attach(new s.d(this.gl, t)), this.program.link(), this.program.use(), this.vertexPositionAttribute = this.program.getAttributeLocation("aVertexPosition"), this.gl.enableVertexAttribArray(this.vertexPositionAttribute), this.textureCoordAttribute = this.program.getAttributeLocation("aTextureCoord"), this.gl.enableVertexAttribArray(this.textureCoordAttribute)
                },
                onInitTextures: function() {
                    this.setViewport(this.size.w, this.size.h), this.YTexture = new s.e(this.gl, this.size), this.UTexture = new s.e(this.gl, this.size.getHalfSize()), this.VTexture = new s.e(this.gl, this.size.getHalfSize())
                },
                onInitSceneTextures: function() {
                    this.YTexture.bind(0, this.program, "YTexture"), this.UTexture.bind(1, this.program, "UTexture"), this.VTexture.bind(2, this.program, "VTexture")
                },
                fillYUVTextures: function(e, t, n) {
                    this.YTexture.fill(e), this.UTexture.fill(t), this.VTexture.fill(n), this.drawScene()
                },
                drawCanvas: function(e, t, n) {
                    this.isPlayStart && this.playStartCallback && (this.playStartCallback(), this.isPlayStart = !1), this.YTexture.fill(e), this.UTexture.fill(t), this.VTexture.fill(n), this.drawScene()
                },
                updateVertexArray: function(e) {
                    this.zoomScene(e)
                },
                toString: function() {
                    return "YUVCanvas Size: " + this.size
                },
                initCanvas: function() {
                    this.gl.clear(this.gl.DEPTH_BUFFER_BIT | this.gl.COLOR_BUFFER_BIT)
                },
                setPlayStartCallback: function(e) {
                    this.playStartCallback = e
                }
            }), n
        }());

    function v(e, t) {
        for (var n = Object.create(e.prototype), r = Object.keys(t), i = 0; i < r.length; i++) n[r[i]] = t[r[i]];
        return n
    }

    function w(e) {
        return e.join("\n")
    }
    var S = function(e, t, n) {
            var r = t,
                i = e,
                a = n,
                o = null,
                s = 0,
                l = 0,
                u = !1,
                c = null,
                f = 0,
                h = 0,
                d = !1,
                p = !1,
                m = 1,
                g = function(e) {
                    (function(e) {
                        this.buffer = e, this.previous = null, this.next = null
                    }).call(this, e)
                };

            function v() {
                var e = r || 25;

                function t() {
                    this.first = null, this.size = 0
                }
                return t.prototype = {
                    enqueue: function(t) {
                        this.size >= e && this.clear();
                        var n = new g(t);
                        if (null === this.first) this.first = n;
                        else {
                            for (var r = this.first; null !== r.next;) r = r.next;
                            r.next = n
                        }
                        return this.size += 1, n
                    },
                    dequeue: function() {
                        var e = null;
                        return null !== this.first && (e = this.first, this.first = this.first.next, this.size -= 1), e
                    },
                    clear: function() {
                        for (var e = null; null !== this.first;) e = this.first, this.first = this.first.next, this.size -= 1, e.buffer = null, e = null;
                        this.size = 0, this.first = null
                    }
                }, new t
            }

            function w() {
                c = new v, u = !1
            }
            var S = function(e) {
                    return void 0 !== o && (o.drawCanvas(e.buffer.dataY, e.buffer.dataU, e.buffer.dataV), delete e.buffer, e.buffer = null, e.previous = null, e.next = null, e = null, !0)
                },
                b = function e(t) {
                    if (!0 === u) {
                        if (0 === s || t - s < 200) return 0 === s && (s = t), void(null !== c && (d || null !== (n = c.dequeue()) && null !== n.buffer && null !== n.buffer.dataY && (d = !0, S(n)), window.requestAnimationFrame(e)));
                        if (a) return null !== (n = c.dequeue()) && null !== n.buffer && null !== n.buffer.dataY && S(n), void window.requestAnimationFrame(e);
                        0 === l && (l = t);
                        var n, r = t - l;
                        r > h && null !== (n = c.dequeue()) && null !== n.buffer && null !== n.buffer.dataY && (p && (f = r - h), h = n.buffer.nCostTime, h -= f, S(n), l = t, p = !0), window.requestAnimationFrame(e)
                    }
                };

            function _(e, t) {
                function n(e, t) {
                    n.prototype.w = e, n.prototype.h = t
                }
                return n.prototype = {
                    toString: function() {
                        return "(" + n.prototype.w + ", " + n.prototype.h + ")"
                    },
                    getHalfSize: function() {
                        return new _(n.prototype.w >>> 1, n.prototype.h >>> 1)
                    },
                    length: function() {
                        return n.prototype.w * n.prototype.h
                    }
                }, new n(e, t)
            }
            return w.prototype = {
                draw: function(e, t, n, r) {
                    if (null !== c && !0 === u)
                        if (document.hidden && c.size >= 25) c.clear();
                        else {
                            var i = {};
                            i.dataY = e, i.dataU = t, i.dataV = n, 0 == r && (r = 25);
                            var o = 1e3 / r,
                                s = c.size * o;
                            a || (m = s > 500 ? 1.2 : s < 200 ? .8 : 1), i.nCostTime = 1e3 / r / m, i.nCostTime < 20 && (i.nCostTime = 20), c.enqueue(i)
                        }
                },
                resize: function(e, t) {
                    this.stopRendering(), null !== c && c.clear(), o && (o = null);
                    var n = new _(e, t);
                    (o = new y(i, n)).setPlayStartCallback(this.playStartCallback), n = null, this.startRendering()
                },
                initStartTime: function() {
                    0 === s && this.startRendering()
                },
                startRendering: function() {
                    0 === s && (u = !0, window.requestAnimationFrame(b))
                },
                pause: function() {
                    u = !1
                },
                play: function() {
                    u = !0
                },
                stopRendering: function() {
                    u = !1, s = 0
                },
                setPlaySpeed: function(e) {
                    m = e
                },
                terminate: function() {
                    u = !1, s = 0, null !== c && (c.clear(), c = null), o && o.clearCanvas(), o = null
                },
                getVideoBufferQueueSize: function() {
                    return c.size
                },
                setPlayStartCallback: function(e) {
                    this.playStartCallback = e
                }
            }, new w
        },
        b = function() {
            var e = null,
                t = "",
                n = null,
                r = null,
                i = null,
                a = null,
                o = null,
                l = null,
                u = null,
                c = 1,
                f = {
                    timestamp: 0,
                    timestamp_usec: 0,
                    timezone: 0
                },
                h = {
                    timestamp: 0,
                    timestamp_usec: 0,
                    timezone: 0
                },
                d = null,
                p = !1,
                m = null,
                g = null,
                y = null,
                v = !1,
                w = !0,
                S = 0,
                b = !1,
                _ = [],
                x = .5,
                A = null,
                P = null,
                C = null,
                T = 0,
                M = 0,
                R = !1,
                U = null,
                D = "png",
                E = 1,
                I = Object(s.a)(),
                k = null,
                L = 0,
                F = 0,
                O = 0,
                B = null,
                V = !1,
                N = !1,
                z = [],
                j = {},
                H = 25,
                q = .5,
                Y = !1,
                W = !1;

            function G() {}

            function X() {
                K(), W = !0
            }

            function Z() {
                var e = 0;
                if (null !== m)
                    for (e = 0; e < m.length; e++) C.removeEventListener(m[e].type, m[e].function);
                if (null !== y)
                    for (e = 0; e < y.length; e++) P.removeEventListener(y[e].type, y[e].function);
                if (null !== g)
                    for (e = 0; e < g.length; e++) A.removeEventListener(g[e].type, g[e].function)
            }

            function K() {
                if (null === P || "ended" === P.readyState) return function(e) {
                    (y = []).push({
                        type: "sourceopen",
                        function: X
                    }), y.push({
                        type: "error",
                        function: ne
                    });
                    for (var t = 0; t < y.length; t++) e.addEventListener(y[t].type, y[t].function)
                }(P = new MediaSource), A.src = window.URL.createObjectURL(P), void s.h.log("videoMediaSource::appendInitSegment new MediaSource()");
                if (s.h.log("videoMediaSource::appendInitSegment start"), 0 === P.sourceBuffers.length) {
                    P.duration = 0;
                    var r = null;
                    if (1 == n ? r = 'video/mp4;codecs="avc1.' + t + '"' : 2 == n && (r = 'video/mp4; codecs="hvc1.1.6.L30.B0"'), !MediaSource.isTypeSupported(r)) return s.h.log("not support" + r), void(B && B({
                        errorCode: 101
                    }));
                    ! function(e) {
                        (m = []).push({
                            type: "error",
                            function: re
                        }), m.push({
                            type: "updateend",
                            function: ee
                        }), m.push({
                            type: "update",
                            function: te
                        });
                        for (var t = 0; t < m.length; t++) e.addEventListener(m[t].type, m[t].function)
                    }(C = P.addSourceBuffer(r))
                }
                var i = e();
                null !== i ? (C.appendBuffer(i), s.h.log("videoMediaSource::appendInitSegment end, codecInfo = " + t)) : P.endOfStream("network")
            }

            function Q() {
                A.paused && (i(), v || V || A.play())
            }

            function J() {
                A.paused || w || (s.h.log("pause"), A.pause())
            }

            function $() {
                z.length && function(e) {
                    if (!Y && W && (Y = !0, K()), null !== C && "closed" !== P.readyState && "ended" !== P.readyState) try {
                        if (_.length > 0) return s.h.count("1.segmentWaitDecode.length: " + _.length), _.push(e), void s.h.count("2.segmentWaitDecode.length: " + _.length);
                        C.updating ? (s.h.log("updating.........."), _.push(e)) : (C.appendBuffer(e), V && (j.buffer = e))
                    } catch (e) {
                        s.h.log("videoMediaSource::appendNextMediaSegment error >> initVideo"), _.length = 0, B && B({
                            errorCode: 101
                        })
                    }
                }(z.shift())
            }

            function ee() {}

            function te() {
                _.length > 0 && (s.h.count("1. onSourceUpdate .segmentWaitDecode.length: " + _.length), C.updating || (s.h.count("2. onSourceUpdate .appendBuffer: " + _.length + "  " + _[0].length), C.appendBuffer(_[0]), _.shift()))
            }

            function ne() {
                console.log("videoMediaSource::onSourceError"), l()
            }

            function re() {
                console.log("videoMediaSource::onSourceBufferErrormsg"), l()
            }

            function ie() {
                console.log("videoMediaSource::onError"), J(), B && B({
                    errorCode: 101
                }), l()
            }

            function ae() {
                v = !0, w = !1, N = !0, R || (R = !0, o("PlayStart"))
            }

            function oe() {
                v = !1, w = !0, s.h.log("暂停播放----------------------------------------------")
            }

            function se() {
                var e = parseInt(P.duration, 10),
                    t = parseInt(A.currentTime, 10),
                    n = {
                        timestamp: f.timestamp - c * (e - t + (1 !== c ? 1 : 0)),
                        timestamp_usec: 0,
                        timezone: f.timezone
                    };
                0 === t || isNaN(e) || !p && Math.abs(e - t) > 4 && 1 === c || A.paused || (null === d ? (d = n, a(0, "currentTime")) : (d.timestamp <= n.timestamp && c >= 1 || d.timestamp > n.timestamp && c < 1) && (d = n, ++S > 4 && a(n.timestamp, "currentTime")))
            }

            function le() {
                Q(),
                    function() {
                        if (null !== P) try {
                            if (C && C.buffered.length > 0 && (function() {
                                    var e = 1 * C.buffered.start(C.buffered.length - 1),
                                        t = 1 * C.buffered.end(C.buffered.length - 1);
                                    t - e > 60 && C.remove(e, t - 10)
                                }(), N && !V || A.duration > q && (A.currentTime = (A.duration - q).toFixed(3), q += H < 10 ? .5 : .1), A && A.duration - A.currentTime > 8 && B && B({
                                    errorCode: 101
                                }), b && !p)) {
                                var e = 1 * C.buffered.start(C.buffered.length - 1),
                                    t = 1 * C.buffered.end(C.buffered.length - 1);
                                if ((0 === A.currentTime ? t - e : t - A.currentTime) >= x + .1) {
                                    if (s.h.log("跳秒"), C.updating) return;
                                    var n = t - .1;
                                    A.currentTime = n.toFixed(3)
                                }
                            }
                        } catch (e) {
                            s.h.log("sourceBuffer has been removed")
                        }
                    }()
            }

            function ue() {
                i()
            }

            function ce() {
                Q()
            }

            function fe() {
                if (s.h.log("需要缓冲下一帧"), b = !1, 0 == M) T = Date.now(), M++;
                else {
                    M++;
                    var e = Date.now() - T;
                    s.h.log("diffTime: " + e + "  Count: " + M), M >= 5 && e < 6e4 && x <= 1 && (x += .1, M = 0, T = 0, s.h.log("delay + 0.1 = " + x))
                }
            }

            function he() {
                s.h.log("Can play !")
            }

            function de() {
                s.h.log("Can play without waiting"), b = !0
            }

            function pe() {
                s.h.log("loadedmetadata")
            }

            function me(e, t) {
                for (var n = atob(e.substring("data:image/png;base64,".length)), r = new Uint8Array(n.length), i = 0, a = n.length; i < a; ++i) r[i] = n.charCodeAt(i);
                var o = new Blob([r.buffer], {
                    type: "image/png"
                });
                ge(o, t + ".png")
            }
            G.prototype = {
                init: function(e) {
                    u = Object(s.a)(), s.h.log("videoMediaSource::init browserType = " + u), (A = e).autoplay = "safari" !== u, A.controls = !1, A.preload = "auto",
                        function(e) {
                            (g = []).push({
                                type: "durationchange",
                                function: le
                            }), g.push({
                                type: "playing",
                                function: ae
                            }), g.push({
                                type: "error",
                                function: ie
                            }), g.push({
                                type: "pause",
                                function: oe
                            }), g.push({
                                type: "timeupdate",
                                function: se
                            }), g.push({
                                type: "resize",
                                function: ue
                            }), g.push({
                                type: "seeked",
                                function: ce
                            }), g.push({
                                type: "waiting",
                                function: fe
                            }), g.push({
                                type: "canplaythrough",
                                function: de
                            }), g.push({
                                type: "canplay",
                                function: he
                            }), g.push({
                                type: "loadedmetadata",
                                function: pe
                            });
                            for (var t = 0; t < g.length; t++) e.addEventListener(g[t].type, g[t].function)
                        }(A), K()
                },
                setInitSegmentFunc: function(t) {
                    e = t
                },
                getVideoElement: function() {
                    return A
                },
                setCodecInfo: function(e) {
                    t = e
                },
                setMediaSegment: function(e) {
                    z.push(e), V || $()
                },
                capture: function(e, t) {
                    U && clearInterval(U);
                    var n = document.createElement("canvas");
                    n.width = A.videoWidth, n.height = A.videoHeight;
                    var r = n.getContext("2d");
                    b || "edge" === I ? (r.drawImage(A, 0, 0, n.width, n.height), t && r.drawImage(t, 0, 0, n.width, n.height), me(n.toDataURL(), e)) : U = setInterval((function() {
                        b && (r.drawImage(A, 0, 0, n.width, n.height), t && r.drawImage(t, 0, 0, n.width, n.height), me(n.toDataURL(), e), clearInterval(U))
                    }), 200)
                },
                getCapture: function(e, t, n) {
                    U && clearInterval(U), E = n || 1, D = "png", "jpg" !== t && "jpeg" !== t || (D = "jpeg");
                    var r = document.createElement("canvas"),
                        i = null;
                    return r.width = A.videoWidth, r.height = A.videoHeight, (b || "edge" === I || b) && (r.getContext("2d").drawImage(A, 0, 0, r.width, r.height), i = r.toDataURL("image/" + D, E)), i
                },
                setInitSegment: function() {
                    K()
                },
                setTimeStamp: function(e, t) {
                    r = e
                },
                setVideoSizeCallback: function(e) {
                    i = e
                },
                setAudioStartCallback: function(e) {
                    a = e
                },
                setReStartMSECallback: function(e) {
                    l = e
                },
                getPlaybackTimeStamp: function() {
                    return r
                },
                setSpeedPlay: function(e) {
                    c = e
                },
                setvideoTimeStamp: function(e) {
                    var t = Math.abs(f.timestamp - e.timestamp) > 3;
                    h.timestamp, !0 === t && (S = 0, a((h = e).timestamp, "init"), 0 !== f.timestamp && p && (A.currentTime = P.duration - .1), d = null), f = e
                },
                pause: function() {
                    V = !0, J()
                },
                play: function() {
                    V = !1
                },
                setPlaybackFlag: function(e) {
                    p = e
                },
                setTimeStampInit: function() {
                    d = null, h = {
                        timestamp: 0,
                        timestamp_usec: 0,
                        timezone: 0
                    }
                },
                close: function() {
                    Z(), J()
                },
                setBeginDrawCallback: function(e) {
                    o = e
                },
                setErrorCallback: function(e) {
                    B = e
                },
                terminate: function() {
                    null !== A && (Z(), "open" === P.readyState && (C && P.removeSourceBuffer(C), P.endOfStream()), C = null, P = null, A = null, U && (clearInterval(U), U = null), k && (clearInterval(k), k = null), O = 0, F = 0, L = 0, Y = !1, W = !1)
                },
                getDuration: function() {
                    return A.duration - A.currentTime
                },
                setFPS: function(e) {
                    e && (H = e)
                },
                setRtspOver: function() {
                    A.duration.toFixed(4) - 0 == A.currentTime.toFixed(4) - 0 || (L = parseInt(A.currentTime), F = parseInt(A.duration), k = setInterval((function() {
                        L === parseInt(A.currentTime) && F === parseInt(A.duration) ? O++ > 10 && (k && clearInterval(k), k = null) : parseInt(A.currentTime) >= parseInt(A.duration) ? (k && clearInterval(k), k = null) : (L = parseInt(A.currentTime), F = parseInt(A.duration), O = 0)
                    }), 150))
                },
                getVideoBufferQueueSize: function() {
                    return z.length
                },
                playNextFrame: function() {
                    $()
                },
                getCurFrameInfo: function() {
                    return j.src = function() {
                        var e = document.createElement("canvas");
                        return e.width = A.videoWidth, e.height = A.videoHeight, e.getContext("2d").drawImage(A, 0, 0, e.width, e.height), e.toDataURL()
                    }(), j
                },
                setDecodeType: function(e) {
                    n = e
                }
            };
            var ge = function(e) {
                var t = e.document,
                    n = function() {
                        return e.URL || e.webkitURL || e
                    },
                    r = t.createElementNS("http://www.w3.org/1999/xhtml", "a"),
                    i = "download" in r,
                    a = /constructor/i.test(e.HTMLElement),
                    o = /CriOS\/[\d]+/.test(navigator.userAgent),
                    s = function(t) {
                        (e.setImmediate || e.setTimeout)((function() {
                            throw t
                        }), 0)
                    },
                    l = function(e) {
                        setTimeout((function() {
                            "string" == typeof e ? n().revokeObjectURL(e) : e.remove()
                        }), 4e4)
                    },
                    u = function(e) {
                        return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(e.type) ? new Blob([String.fromCharCode(65279), e], {
                            type: e.type
                        }) : e
                    },
                    c = function(t, c, f) {
                        f || (t = u(t));
                        var h, d = this,
                            p = "application/octet-stream" === t.type,
                            m = function() {
                                ! function(e, t, n) {
                                    for (var r = (t = [].concat(t)).length; r--;) {
                                        var i = e["on" + t[r]];
                                        if ("function" == typeof i) try {
                                            i.call(e, e)
                                        } catch (e) {
                                            s(e)
                                        }
                                    }
                                }(d, "writestart progress write writeend".split(" "))
                            };
                        if (d.readyState = d.INIT, i) return h = n().createObjectURL(t), void setTimeout((function() {
                            r.href = h, r.download = c,
                                function(e) {
                                    var t = new MouseEvent("click");
                                    e.dispatchEvent(t)
                                }(r), m(), l(h), d.readyState = d.DONE
                        }));
                        ! function() {
                            if ((o || p && a) && e.FileReader) {
                                var r = new FileReader;
                                return r.onloadend = function() {
                                    var t = o ? r.result : r.result.replace(/^data:[^;]*;/, "data:attachment/file;");
                                    e.open(t, "_blank") || (e.location.href = t), t = void 0, d.readyState = d.DONE, m()
                                }, r.readAsDataURL(t), void(d.readyState = d.INIT)
                            }
                            h || (h = n().createObjectURL(t)), p ? e.location.href = h : e.open(h, "_blank") || (e.location.href = h), d.readyState = d.DONE, m(), l(h)
                        }()
                    },
                    f = c.prototype;
                return "undefined" != typeof navigator && navigator.msSaveOrOpenBlob ? function(e, t, n) {
                    return t = t || e.name || "download", n || (e = u(e)), navigator.msSaveOrOpenBlob(e, t)
                } : (f.readyState = f.INIT = 0, f.WRITING = 1, f.DONE = 2, f.error = f.onwritestart = f.onprogress = f.onwrite = f.onabort = f.onerror = f.onwriteend = null, function(e, t, n) {
                    return new c(e, t || e.name || "download", n)
                })
            }(window);
            return new G
        },
        _ = function() {
            var e = null,
                t = null,
                n = null,
                r = null,
                i = !1,
                a = null,
                o = {
                    audio: !0,
                    video: !1
                },
                l = null;

            function u() {}
            return u.prototype = {
                init: function() {
                    if (null == e) try {
                        window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.oAudioContext || window.msAudioContext, (e = new AudioContext).onstatechange = function() {
                            s.h.info("Audio Context State changed :: " + e.state)
                        }
                    } catch (e) {
                        return void console.error("Web Audio API is not supported in this web browser! : " + e)
                    }
                },
                initAudioOut: function() {
                    if (null !== t && null !== n || (t = e.createGain(), (n = e.createScriptProcessor(4096, 1, 1)).onaudioprocess = function(e) {
                            if (null !== a) {
                                var t = e.inputBuffer.getChannelData(0);
                                null !== l && !0 === i && l(t)
                            }
                        }, t.connect(n), n.connect(e.destination), r = e.sampleRate, t.gain.value = 1), void 0 === navigator.mediaDevices && (navigator.mediaDevices = {}), void 0 === navigator.mediaDevices.getUserMedia && (navigator.mediaDevices.getUserMedia = function(e, t, n) {
                            var r = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
                            return r ? new Promise((function(t, n) {
                                r.call(navigator, e, t, n)
                            })) : (n(), Promise.reject(new Error("getUserMedia is not implemented in this browser")))
                        }), navigator.mediaDevices.getUserMedia) return navigator.mediaDevices.getUserMedia(o).then((function(n) {
                        a = n, e.createMediaStreamSource(n).connect(t)
                    })).catch((function(e) {
                        s.h.error(e)
                    })), i = !0, r;
                    s.h.error("Cannot open local media stream! :: navigator.mediaDevices.getUserMedia is not defined!")
                },
                controlVolumnOut: function(e) {
                    var n = e / 20 * 2;
                    t.gain.value = n <= 0 ? 0 : n >= 10 ? 10 : n
                },
                stopAudioOut: function() {
                    if (null !== a && i) try {
                        for (var e = a.getAudioTracks(), t = 0, n = e.length; t < n; t++) e[t].stop();
                        i = !1, a = null
                    } catch (e) {
                        s.h.log(e)
                    }
                },
                terminate: function() {
                    this.stopAudioOut(), e.close(), t = null, n = null
                },
                setSendAudioTalkBufferCallback: function(e) {
                    l = e
                }
            }, new u
        },
        x = n(2),
        A = n.n(x),
        P = function(e, t) {
            var n = null,
                r = null,
                i = null;

            function a() {}

            function o(e) {
                var t = {
                    type: "getRtpData",
                    data: e
                };
                n.postMessage(t)
            }

            function s(e) {
                var t = e.data;
                switch (t.type) {
                    case "rtpData":
                        i(t.data)
                }
            }
            return a.prototype = {
                setSendAudioTalkCallback: function(e) {
                    i = e
                },
                talkInit: function(e, t) {
                    var i = {
                        type: "sdpInfo",
                        data: {
                            sdpInfo: e,
                            aacCodecInfo: t,
                            decodeMode: "canvas",
                            mp4Codec: arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null
                        }
                    };
                    try {
                        window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.oAudioContext || window.msAudioContext, (n = new A.a).onmessage = s, null === r && ((r = new _).init(), r.setSendAudioTalkBufferCallback(o)), n.postMessage(i), i = {
                            type: "sampleRate",
                            data: r.initAudioOut()
                        }, n.postMessage(i)
                    } catch (e) {
                        return void console.error("Web Audio API is not supported in this web browser! : " + e)
                    }
                },
                stop: function() {
                    r && (r.terminate(), r = null), n && (n.terminate(), n = null)
                }
            }, new a
        },
        C = function() {
            var e = null,
                t = null,
                n = -100,
                r = "#0000FF";

            function i() {}

            function a(e, t, n) {
                return (t.x - e.x) * (n.y - e.y) - (n.x - e.x) * (t.y - e.y)
            }

            function o(e, t) {
                for (var n = 0, r = 0; r < e.length - 1; r++) e[r].y <= t.y ? e[r + 1].y > t.y && a(e[r], e[r + 1], t) > 0 && ++n : e[r + 1].y <= t.y && a(e[r], e[r + 1], t) < 0 && --n;
                return 0 != n
            }
            return i.prototype = {
                Init: function(n) {
                    t = (e = n).getContext("2d")
                },
                SetCurrentColor: function(e) {
                    r = e
                },
                drawLine: function(e, n) {
                    e.x == n.x && e.y == n.y || (t.beginPath(), t.lineWidth = 2, t.strokeStyle = r, t.moveTo(e.x, e.y), t.lineTo(n.x, n.y), t.stroke(), t.setLineDash([]), t.closePath())
                },
                drawPolyLine: function(e, n) {
                    if (!(n < 2)) {
                        t.beginPath(), t.lineWidth = 2, t.strokeStyle = r;
                        for (var i = 0; i < n; i++) 0 === i ? t.moveTo(e[i].x, e[i].y) : t.lineTo(e[i].x, e[i].y);
                        t.stroke(), t.setLineDash([0, 0]), t.closePath()
                    }
                },
                drawArrow: function(e, t) {
                    var n, r = {},
                        i = {};
                    e.x == t.x && e.y == t.y || (t.x < e.x ? (n = Math.atan((e.y - t.y) / (e.x - t.x)), r.x = t.x + 20 * Math.cos(n - Math.PI / 6), r.y = t.y + 20 * Math.sin(n - Math.PI / 6), i.x = t.x + 20 * Math.cos(n + Math.PI / 6), i.y = t.y + 20 * Math.sin(n + Math.PI / 6)) : t.x > e.x ? (n = Math.atan((e.y - t.y) / (e.x - t.x)), r.x = t.x - 20 * Math.cos(n - Math.PI / 6), r.y = t.y - 20 * Math.sin(n - Math.PI / 6), i.x = t.x - 20 * Math.cos(n + Math.PI / 6), i.y = t.y - 20 * Math.sin(n + Math.PI / 6)) : (n = Math.atan((e.y - t.y) / (e.x - t.x)), r.x = t.x + 20 * Math.cos(n - Math.PI / 6), r.y = t.y + 20 * Math.sin(n - Math.PI / 6), i.x = t.x + 20 * Math.cos(n + Math.PI / 6), i.y = t.y + 20 * Math.sin(n + Math.PI / 6)), this.drawLine(t, r), this.drawLine(t, i))
                },
                drawTrackLine: function(e, t) {
                    if (1 === t || 3 === t) {
                        var n = e.x,
                            r = e.y,
                            i = e.xSize,
                            a = e.ySize,
                            o = new Array(4),
                            s = new Array(8);
                        o[0].x = n - i, o[0].y = r - a, o[1].x = n + i, o[1].y = r - a, o[2].x = n + i, o[2].y = r + a, o[3].x = n - i, o[3].y = r + a, s[0].x = o[0].x + i / 2, s[0].y = o[0].y, s[1].x = o[1].x - i / 2, s[1].y = o[1].y, s[2].x = o[2].x - i / 2, s[2].y = o[2].y, s[3].x = o[3].x + i / 2, s[3].y = o[3].y, s[4].x = o[0].x, s[4].y = o[0].y + a / 2, s[5].x = o[1].x, s[5].y = o[1].y + a / 2, s[6].x = o[2].x, s[6].y = o[2].y - a / 2, s[7].x = o[3].x, s[7].y = o[3].y - a / 2;
                        for (var l = 0; l < 4; l++) this.drawLine(o[l], s[l]), this.drawLine(o[l], s[l + 4]);
                        o = null, s = null
                    } else {
                        var u = new Array(5);
                        for (l = 0; l < 5; l++) u[l] = {};
                        u[0].x = e.x - e.xSize, u[0].y = e.y - e.ySize, u[1].x = e.x + e.xSize, u[1].y = e.y - e.ySize, u[2].x = e.x + e.xSize, u[2].y = e.y + e.ySize, u[3].x = e.x - e.xSize, u[3].y = e.y + e.ySize, u[4].x = e.x - e.xSize, u[4].y = e.y - e.ySize, this.drawPolyLine(u, 5), u = null
                    }
                    var c;
                    3 === t && (n = e.x, r = e.y, c = (i = e.xSize) > (a = e.ySize) ? a : i, (u = new Array(8))[0].x = n - i, u[0].y = r, u[1].x = n - i - c, u[1].y = r, u[2].x = n, u[2].y = r + a, u[3].x = n, u[3].y = r + a + c, u[4].x = n + i, u[4].y = r, u[5].x = n + i + c, u[5].y = r, u[6].x = n, u[6].y = r - a, u[7].x = n, u[7].y = r - a - c, this.drawPolyLine(u, 2), this.drawPolyLine(u + 2, 2), this.drawPolyLine(u + 4, 2), this.drawPolyLine(u + 6, 2), u = null)
                },
                drawDirection: function(e, t, n, r) {
                    var i, a = {},
                        o = {},
                        s = (e.x + t.x) / 2,
                        l = (e.y + t.y) / 2;
                    if (e.x != t.x)
                        if ((i = (e.y - t.y) / (e.x - t.x)) > 0) {
                            var u = Math.atan(i);
                            a.x = s + r * Math.sin(u), a.y = l - r * Math.cos(u), o.x = s - r * Math.sin(u), o.y = l + r * Math.cos(u)
                        } else i < 0 ? (u = Math.atan(-i), a.x = s - r * Math.sin(u), a.y = l - r * Math.cos(u), o.x = s + r * Math.sin(u), o.y = l + r * Math.cos(u)) : (a.x = s, o.x = a.x, a.y = l - r, o.y = a.y + 2 * r);
                    else a.x = e.x - r, o.x = e.x + r, a.y = l, o.y = a.y;
                    this.drawLine(a, o), e.x == t.x ? t.y < e.y ? (1 != n && this.drawArrow(a, o), 0 != n && this.drawArrow(o, a)) : (1 != n && this.drawArrow(o, a), 0 != n && this.drawArrow(a, o)) : e.x < t.x ? (1 != n && this.drawArrow(a, o), 0 != n && this.drawArrow(o, a)) : (1 != n && this.drawArrow(o, a), 0 != n && this.drawArrow(a, o))
                },
                drawRegionDirection: function(e, t, n, r) {
                    var i = {},
                        a = {},
                        s = 0,
                        l = {},
                        u = {};
                    if (null !== e && 0 !== t) {
                        if (i.x = e[0].x, i.y = e[0].y, a.x = e[1].x, a.y = e[1].y, i.x != a.x ? (s = (i.y - a.y) / (i.x - a.x)) > 0 ? (l.x = (i.x + a.x) / 2 + r * Math.sin(Math.atan(s)), l.y = (i.y + a.y) / 2 - r * Math.cos(Math.atan(s)), u.x = (i.x + a.x) / 2 - r * Math.sin(Math.atan(s)), u.y = (i.y + a.y) / 2 + r * Math.cos(Math.atan(s))) : s < 0 ? (l.x = (i.x + a.x) / 2 - r * Math.sin(Math.atan(-s)), l.y = (i.y + a.y) / 2 - r * Math.cos(Math.atan(-s)), u.x = (i.x + a.x) / 2 + r * Math.sin(Math.atan(-s)), u.y = (i.y + a.y) / 2 + r * Math.cos(Math.atan(-s))) : (l.x = (i.x + a.x) / 2, u.x = l.x, l.y = (i.y + a.y) / 2 - r, u.y = l.y + 2 * r) : (l.x = i.x - r, u.x = i.x + r, l.y = (i.y + a.y) / 2, u.y = l.y), this.drawLine(l, u), 2 == n) return this.drawArrow(l, u), void this.drawArrow(u, l);
                        e[e.length] = e[0];
                        var c = o(e, l),
                            f = o(e, u);
                        0 == n && c || 1 == n && f ? this.drawArrow(u, l) : this.drawArrow(l, u)
                    }
                },
                drawText: function(e, n, i) {
                    void 0 !== n[0].x && void 0 !== n[0].y && (t.beginPath(), t.font = "25px Tahoma", t.fillStyle = r, t.textAlign = "left", t.textBaseline = "middle", t.fillText(e, n[0].x, n[0].y), t.closePath())
                },
                getTextAngleCoordinates: function(e, t, n) {
                    for (var r, i = new Array(t), a = 0; a < t; a++) i[a] = {}, i[a].x = e[a].x, i[a].y = -e[a].y;
                    var o, s, l, u = 0,
                        c = -1,
                        f = 0,
                        h = 0;
                    if (0 == n) {
                        for (u = 0; u < t - 1; u++) o = i[u].x - i[u + 1].x, s = i[u].y - i[u + 1].y, (l = Math.sqrt(o * o + s * s)) - h > 1e-4 && (c = u, h = l, f = Math.atan2(s, o));
                        if (-1 == c || h < 0) return void(i = null)
                    } else 2 == nSideConfig && (o = i[0].x - i[1].x, s = i[0].y - i[1].y, l = Math.sqrt(o * o + s * s), c = 0, f = Math.atan2(s, o));
                    var d = 180 * f / Math.PI;
                    return r = 90 == d || -90 == d ? i[c].y > i[c + 1].y ? i[c] : i[c + 1] : i[c].x < i[c + 1].x ? i[c] : i[c + 1], d >= -180 && d < -90 ? d += 180 : d >= 90 && d <= 180 && (d -= 180), i = null, {
                        pPointsDraw: r,
                        nAngleDraw: d
                    }
                },
                drawHalfline: function(e, t) {
                    var n = new Array(2);
                    n[0] = {}, n[1] = {}, n[0].x = e.x, n[0].y = e.y, n[1].x = (3 * e.x + t.x) / 4, n[1].y = (3 * e.y + t.y) / 4, this.drawLine(n[0], n[1]), n[0].x = (e.x + 3 * t.x) / 4, n[0].y = (e.y + 3 * t.y) / 4, n[1].x = t.x, n[1].y = t.y, this.drawLine(n[0], n[1])
                },
                drawBlock: function(e, t) {
                    var n = new Array(5),
                        r = 0;
                    0 == t && (n[0] = {}, n[1] = {}, n[2] = {}, n[3] = {}, n[4] = {}, n[0].x = e.left, n[0].y = e.top, n[1].x = e.right, n[1].y = e.top, n[2].x = e.right, n[2].y = e.bottom, n[3].x = e.left, n[3].y = e.bottom, n[4].x = e.left, n[4].y = e.top, r = 5), this.drawPolyLine(n, r)
                },
                clearCanvasLayer: function() {
                    t.clearRect(0, 0, e.width + 1, e.height + 1)
                },
                TestDraw: function() {
                    n++;
                    var e = t.createLinearGradient(n + 20, n + 20, n + 150, n + 150);
                    e.addColorStop(0, "yellow"), e.addColorStop(1, "red"), t.fillStyle = e, t.fillRect(n, n, 300, 300), t.stroke(), n > 1100 && (n = 0)
                },
                Stop: function() {}
            }, new i
        };

    function T(e) {
        var t, n, r, i, a, o;
        for (t = "", r = e.length, n = 0; n < r;) switch ((i = e[n++]) >> 4) {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
                t += String.fromCharCode(i);
                break;
            case 12:
            case 13:
                a = e[n++], t += String.fromCharCode((31 & i) << 6 | 63 & a);
                break;
            case 14:
                a = e[n++], o = e[n++], t += String.fromCharCode((15 & i) << 12 | (63 & a) << 6 | (63 & o) << 0)
        }
        return t
    }
    var M = function() {
        var e = "#FF0000",
            t = null,
            n = null,
            r = null,
            i = null,
            a = null,
            o = null,
            s = null,
            l = null,
            u = null,
            c = null,
            f = new Array(32),
            h = 0,
            d = new Array(32),
            p = 0,
            m = {};

        function g() {}

        function y(e) {
            var t = "#00FF00";
            switch (e) {
                case 1:
                    t = "#00CCFF";
                    break;
                case 2:
                case 16:
                    t = "#E1D800";
                    break;
                case 17:
                    t = "#24FF00";
                    break;
                case 22:
                    t = "#FF8000";
                    break;
                case 23:
                    t = "#FF80FF";
                    break;
                case 63:
                    t = "#8000FF"
            }
            return t
        }
        return g.prototype = {
            Init: function(e) {
                (t = new C).Init(e)
            },
            setIVSData: function(e, t, g, y) {
                switch (t) {
                    case 4:
                        ! function(e, t) {
                            Date.now(), r || (n = new ArrayBuffer(t), r = new Uint8Array(n), i = new DataView(n)), r.set(Module.HEAPU8.subarray(e, e + t));
                            var a = 0;
                            f[h] = {}, f[h].nRuleID = i.getUint32(0, !0), f[h].nRuleType = i.getUint32(4, !0), f[h].nRuleSubType = i.getUint32(8, !0), f[h].nRuleNameLength = i.getUint32(12, !0);
                            var o = r.slice(16, 16 + f[h].nRuleNameLength);
                            f[h].strRuleName = T(o), f[h].nExcludeRegionNum = i.getUint32(144, !0), f[h].nDirection = i.getInt32(148, !0), f[h].nAlarmNum = i.getUint32(152), f[h].bAlarm = i.getUint8(154), a = 156;
                            for (var s = i.getUint32(a, !0), l = new Array(s), u = 0; u < s; u++) l[u] = {}, l[u].x = i.getUint32(a + 4 + 8 * u, !0), l[u].y = i.getUint32(a + 8 + 8 * u, !0);
                            f[h].DetectLinePoints = l, a = a + 4 + 256;
                            var c = i.getUint32(a, !0),
                                d = new Array(c);
                            for (u = 0; u < c; u++) d[u] = {}, d[u].x = i.getUint32(a + 4 + 8 * u, !0), d[u].y = i.getUint32(a + 8 + 8 * u, !0);
                            f[h].RuleRegionPoints = d, a = a + 4 + 256;
                            var p = i.getUint32(a, !0),
                                m = new Array(p);
                            for (u = 0; u < p; u++) m[u] = {}, m[u].x = i.getUint32(a + 4 + 8 * u, !0), m[u].y = i.getUint32(a + 8 + 8 * u, !0);
                            f[h].DetectRegionPoints = m;
                            var g = a = a + 4 + 256,
                                y = new Array(f[h].nExcludeRegionNum);
                            for (u = 0; u < f[h].nExcludeRegionNum; u++) {
                                var v = i.getUint32(g, !0);
                                y[u] = new Array(v);
                                for (var w = 0; w < v; w++) y[u][w] = {}, y[u][w].x = i.getUint32(g + 4 + 8 * w, !0), y[u][w].y = i.getUint32(g + 8 + 8 * w, !0);
                                g = g + 4 + 256
                            }
                            f[h].ExcludeRegionPoints = y, a += 2600;
                            var S = new Array(2);
                            for (u = 0; u < 2; u++) S[u] = {}, S[u].x = i.getUint32(a + 8 * u, !0), S[u].y = i.getUint32(a + 4 + 8 * u, !0);
                            f[h].DirectionPoints = S, a += 16, f[h].nIntelFlowLength = i.getUint32(a, !0);
                            var b = r.slice(a + 4, a + 4 + f[h].nIntelFlowLength);
                            f[h].strIntelFlow = T(b), a = a + 4 + 256;
                            var _ = i.getUint32(a, !0),
                                x = new Array(_);
                            for (u = 0; u < _; u++) x[u] = {}, x[u].x = i.getUint32(a + 4 + 8 * u, !0), x[u].y = i.getUint32(a + 8 + 8 * u, !0);
                            f[h].EscalatorLeftPoints = x, a = a + 4 + 256;
                            var A = i.getUint32(a, !0),
                                P = new Array(A);
                            for (u = 0; u < A; u++) P[u] = {}, P[u].x = i.getUint32(a + 4 + 8 * u, !0), P[u].y = i.getUint32(a + 8 + 8 * u, !0);
                            f[h].EscalatorRightPoints = P, a = a + 4 + 256, Date.now(), h++
                        }(g, y);
                        break;
                    case 5:
                        ! function(e, t) {
                            d[p] = {}, o || (a = new ArrayBuffer(t), o = new Uint8Array(a), s = new DataView(a)), o.set(Module.HEAPU8.subarray(e, e + t)), d[p].bAlarm = s.getUint8(0), d[p].nObjType = s.getUint32(4, !0);
                            var n = {};
                            n.x = s.getUint16(8, !0), n.y = s.getUint16(10, !0), n.xSize = s.getUint16(12, !0), n.ySize = s.getUint16(14, !0), d[p].ptIvsTrackPoint = n;
                            var r = {};
                            r.x = s.getUint16(16, !0), r.y = s.getUint16(18, !0), r.xSize = s.getUint16(20, !0), r.ySize = s.getUint16(22, !0), d[p].ptSpecialShapePoint = r;
                            for (var i = new Array(2), l = 0; l < 2; l++) i[l] = {}, i[l].x = s.getUint32(24 + 8 * l, !0), i[l].y = s.getUint32(28 + 8 * l, !0);
                            d[p].ptTrackTextPoints = i, d[p].nTrackTextLength = s.getUint32(40, !0);
                            var u = o.slice(44, 44 + d[p].nTrackTextLength);
                            d[p].strTrackTextInfo = T(u);
                            var c = 300;
                            d[p].struAttribute80 = {};
                            var f = {};
                            f.x = s.getUint16(c, !0), f.y = s.getUint16(c + 2, !0), f.xSize = s.getUint16(c + 4, !0), f.ySize = s.getUint16(c + 6, !0), d[p].struAttribute80.ptCarWindowPoint = f, c += 8, d[p].struAttribute84 = {}, d[p].struAttribute84.nPointsNum = s.getUint32(c, !0), c += 4;
                            var h = new Array(d[p].struAttribute84.nPointsNum);
                            for (l = 0; l < d[p].struAttribute84.nPointsNum; l++) h[l] = {}, h[l].x = s.getUint16(c + 8 * l, !0), h[l].y = s.getUint16(c + 2 + 8 * l, !0), h[l].xSize = s.getUint16(c + 4 + 8 * l, !0), h[l].ySize = s.getUint16(c + 6 + 8 * l, !0);
                            d[p].struAttribute84.ptIvsTrackPoints = h, c += 256;
                            var m = new Array(d[p].struAttribute84.nPointsNum);
                            for (l = 0; l < d[p].struAttribute84.nPointsNum; l++) m[l] = {}, m[l].x = s.getUint32(c + 8 * l, !0), m[l].y = s.getUint32(c + 4 + 8 * l, !0);
                            d[p].struAttribute84.ptTrackPoints0 = m, c += 256;
                            var g = new Array(d[p].struAttribute84.nPointsNum);
                            for (l = 0; l < d[p].struAttribute84.nPointsNum; l++) g[l] = {}, g[l].x = s.getUint32(c + 8 * l, !0), g[l].y = s.getUint32(c + 4 + 8 * l, !0);
                            d[p].struAttribute84.ptTrackPoints1 = g, c += 256, d[p].struAttribute8C = {}, d[p].struAttribute8C.nAccessoryNum = s.getUint8(c), d[p].struAttribute8C.nTissueBoxNum = s.getUint8(c + 1), d[p].struAttribute8C.nSunVisorCount = s.getUint8(c + 2), d[p].struAttribute8C.nStandardCount = s.getUint8(c + 3), c += 4;
                            var y = new Array(d[p].struAttribute8C.nAccessoryNum);
                            for (l = 0; l < d[p].struAttribute8C.nAccessoryNum; l++) y[l] = {}, y[l].x = s.getUint16(c + 8 * l, !0), y[l].y = s.getUint16(c + 2 + 8 * l, !0), y[l].xSize = s.getUint16(c + 4 + 8 * l, !0), y[l].ySize = s.getUint16(c + 6 + 8 * l, !0);
                            d[p].struAttribute8C.ptAccessoryPoints = y, c += 256;
                            var v = new Array(d[p].struAttribute8C.nTissueBoxNum);
                            for (l = 0; l < d[p].struAttribute8C.nTissueBoxNum; l++) v[l] = {}, v[l].x = s.getUint16(c + 8 * l, !0), v[l].y = s.getUint16(c + 2 + 8 * l, !0), v[l].xSize = s.getUint16(c + 4 + 8 * l, !0), v[l].ySize = s.getUint16(c + 6 + 8 * l, !0);
                            d[p].struAttribute8C.ptTissueBoxPoints = v, c += 256;
                            var w = new Array(d[p].struAttribute8C.nSunVisorCount);
                            for (l = 0; l < d[p].struAttribute8C.nSunVisorCount; l++) w[l] = {}, w[l].x = s.getUint16(c + 8 * l, !0), w[l].y = s.getUint16(c + 2 + 8 * l, !0), w[l].xSize = s.getUint16(c + 4 + 8 * l, !0), w[l].ySize = s.getUint16(c + 6 + 8 * l, !0);
                            d[p].struAttribute8C.ptSunVisorPoints = w, c += 256;
                            var S = new Array(d[p].struAttribute8C.nStandardCount);
                            for (l = 0; l < d[p].struAttribute8C.nStandardCount; l++) S[l] = {}, S[l].x = s.getUint16(c + 8 * l, !0), S[l].y = s.getUint16(c + 2 + 8 * l, !0), S[l].xSize = s.getUint16(c + 4 + 8 * l, !0), S[l].ySize = s.getUint16(c + 6 + 8 * l, !0);
                            for (d[p].struAttribute8C.ptStandardPoints = S, c += 256, d[p].struAttribute8D = {}, d[p].struAttribute8D.nVirtualCoilNum = s.getUint32(c, !0), c += 4, d[p].struAttribute8D.struVirtualCoil = new Array(d[p].struAttribute8D.nVirtualCoilNum), l = 0; l < d[p].struAttribute8D.nVirtualCoilNum; l++) {
                                d[p].struAttribute8D.struVirtualCoil[l] = {}, d[p].struAttribute8D.struVirtualCoil[l].nFirstCoilNum = s.getUint32(c, !0), c += 4;
                                for (var b = new Array(d[p].struAttribute8D.struVirtualCoil[l].nFirstCoilNum), _ = 0; _ < d[p].struAttribute8D.struVirtualCoil[l].nFirstCoilNum; _++) b[_] = {}, b[_].x = s.getUint32(c + 8 * _, !0), b[_].y = s.getUint32(c + 4 + 8 * _, !0);
                                d[p].struAttribute8D.struVirtualCoil[l].ptFirstCoilPoints = b, c += 64, d[p].struAttribute8D.struVirtualCoil[l].nSecondCoilNum = s.getUint32(c, !0), c += 4;
                                var x = new Array(d[p].struAttribute8D.struVirtualCoil[l].nSecondCoilNum);
                                for (_ = 0; _ < d[p].struAttribute8D.struVirtualCoil[l].nSecondCoilNum; _++) x[_] = {}, x[_].x = s.getUint32(c + 8 * _, !0), x[_].y = s.getUint32(c + 4 + 8 * _, !0);
                                d[p].struAttribute8D.struVirtualCoil[l].ptSecondCoilPoints = x, c += 64;
                                var A = new Array(2);
                                for (_ = 0; _ < 2; _++) A[_] = {}, A[_].x = s.getUint32(c + 8 * _, !0), A[_].y = s.getUint32(c + 4 + 8 * _, !0);
                                d[p].struAttribute8D.struVirtualCoil[l].ptRtailCoilPoints = A, c += 16;
                                var P = new Array(2);
                                for (_ = 0; _ < 2; _++) P[_] = {}, P[_].x = s.getUint32(c + 8 * _, !0), P[_].y = s.getUint32(c + 4 + 8 * _, !0);
                                d[p].struAttribute8D.struVirtualCoil[l].ptStrInfoPoints = P, c += 16, d[p].struAttribute8D.struVirtualCoil[l].nStrInfoLength = s.getUint32(c, !0);
                                var C = o.slice(c + 4, c + 4 + d[p].struAttribute8D.struVirtualCoil[l].nStrInfoLength);
                                d[p].struAttribute8D.struVirtualCoil[l].strInfo = T(C), c += 260
                            }
                            d[p].struAttribute8D.nVirtualCoilNum < 5 && (c += 428 * (5 - d[p].struAttribute8D.nVirtualCoilNum)), d[p].struAttribute90 = {};
                            var M = new Array(2);
                            for (l = 0; l < 2; l++) M[l] = {}, M[l].x = s.getUint32(c + 8 * l, !0), M[l].y = s.getUint32(c + 4 + 8 * l, !0);
                            d[p].struAttribute90.ptTimePoints = M, c += 16, d[p].struAttribute90.nTimeInfoLength = s.getUint32(c, !0);
                            var R = o.slice(c + 4, c + 4 + d[p].struAttribute90.nTimeInfoLength);
                            d[p].struAttribute90.strTimeInfo = T(R), c += 260, p++
                        }(g, y);
                        break;
                    case 6:
                        ! function(e, t) {
                            u || (l = new ArrayBuffer(t), u = new Uint8Array(l), c = new DataView(l)), u.set(Module.HEAPU8.subarray(e, e + t)), m.nBlockNum = c.getUint32(0, !0);
                            for (var n = new Array(m.nBlockNum), r = 0; r < m.nBlockNum; r++) n[r] = {}, n[r].left = c.getUint32(4 + 16 * r, !0), n[r].top = c.getUint32(8 + 16 * r, !0), n[r].right = c.getUint32(12 + 16 * r, !0), n[r].bottom = c.getUint32(16 + 16 * r, !0);
                            m.arrBlockRect = n
                        }(g, y)
                }
            },
            Stop: function() {
                n = null, r = null, i = null, a = null, o = null, s = null, l = null, u = null, c = null, f = null, h = 0, d = null, p = 0, t.clearCanvasLayer()
            },
            drawIVSData: function() {
                t.clearCanvasLayer(),
                    function() {
                        var n = "#0000FF";
                        t.SetCurrentColor(n);
                        for (var r = 0; r < h; r++) {
                            f[r].bAlarm && (n = e, t.SetCurrentColor(n));
                            var i = f[r].DetectLinePoints.length,
                                a = f[r].RuleRegionPoints.length,
                                o = f[r].DetectRegionPoints.length,
                                s = f[r].nRuleType;
                            switch (s) {
                                case 287:
                                    t.drawPolyLine(f[r].DetectLinePoints, i);
                                    for (var l = 1; l < i; l++) t.drawDirection(f[r].DetectLinePoints[l - 1], f[r].DetectLinePoints[l], f[r].nDirection, 60);
                                    t.drawPolyLine(f[r].RuleRegionPoints, a);
                                    break;
                                case 294:
                                    0 != f[r].nAlarmNum && t.drawPolyLine(f[r].RuleRegionPoints, a);
                                    break;
                                case 601:
                                    if (2 == i && (t.drawLine(f[r].DetectLinePoints[0], f[r].DetectLinePoints[1]), t.drawArrow(f[r].DetectLinePoints[0], f[r].DetectLinePoints[1])), f[r].nRuleRegionNum > 0)
                                        for (l = 0; l < a / 2; l++) t.drawLine(f[r].RuleRegionPoints[2 * l], f[r].RuleRegionPoints[2 * l + 1]);
                                    break;
                                case 769:
                                case 770:
                                case 804:
                                    2 == i && t.drawPolyLine(f[r].DetectLinePoints, i), t.drawPolyLine(f[r].RuleRegionPoints, a);
                                    break;
                                case 16384:
                                    break;
                                case 573:
                                    t.drawPolyLine(f[r].EscalatorLeftPoints, f[r].EscalatorLeftPoints.length), t.drawPolyLine(f[r].EscalatorRightPoints, f[r].EscalatorRightPoints.length);
                                    break;
                                default:
                                    t.SetCurrentColor(n);
                                    var u = !1;
                                    i > 0 && (t.drawPolyLine(f[r].DetectLinePoints, i), f[r].nDirection >= 0 && i > 1 && (t.drawDirection(f[r].DetectLinePoints[0], f[r].DetectLinePoints[1], f[r].nDirection, 60), u = !0)), a > 0 && (f[r].RuleRegionPoints[a] = f[r].RuleRegionPoints[0], t.drawPolyLine(f[r].RuleRegionPoints, a + 1), f[r].nDirection >= 0 && a > 1 && (t.drawRegionDirection(f[r].RuleRegionPoints, a, f[r].nDirection, 50), u = !0)), u || f[r].DirectionPoints[0].x == f[r].DirectionPoints[1].x && f[r].DirectionPoints[0].y == f[r].DirectionPoints[1].y || (t.drawLine(f[r].DirectionPoints[0], f[r].DirectionPoints[1]), t.drawArrow(f[r].DirectionPoints[0], f[r].DirectionPoints[1]))
                            }
                            for (o > 0 && (t.SetCurrentColor("#FF5700"), f[r].DetectRegionPoints[o] = f[r].DetectRegionPoints[0], t.drawPolyLine(f[r].DetectRegionPoints, o + 1)), t.SetCurrentColor("#FFFFFF"), l = 0; l < f[r].nExcludeRegionNum; l++) {
                                var c = f[r].ExcludeRegionPoints[l].length;
                                f[r].ExcludeRegionPoints[l][c] = f[r].ExcludeRegionPoints[l][0], t.drawPolyLine(f[r].ExcludeRegionPoints[l], c + 1)
                            }
                            t.SetCurrentColor(n);
                            var d = {};
                            i > 0 ? d = f[r].DetectLinePoints : a > 0 && (d = f[r].RuleRegionPoints), f[r].nIntelFlowLength > 0 && t.drawText(f[r].strIntelFlow, d, d.length), 556 != s && (601 == s || 299 == s || 300 == s ? t.drawText(f[r].strRuleName, f[r].RuleRegionPoints, f[r].RuleRegionPoints.length) : 769 == s || 770 == s ? ptRuleRegionCnt > 0 && t.drawText(f[r].strRuleName, f[r].RuleRegionPoints, f[r].RuleRegionPoints.length) : t.drawText(f[r].strRuleName, d, d.length))
                        }
                        h = 0
                    }(),
                    function() {
                        for (var n = 0; n < p; n++) {
                            var r = y(d[n].nObjType);
                            t.SetCurrentColor(r), d[n].bAlarm && t.SetCurrentColor(e), t.drawTrackLine(d[n].ptIvsTrackPoint, 0), t.drawTrackLine(d[n].ptSpecialShapePoint, 0), t.drawText(d[n].strTrackTextInfo, d[n].ptTrackTextPoints, d[n].ptTrackTextPoints.length), t.drawTrackLine(d[n].struAttribute80.ptCarWindowPoint, 0);
                            for (var i = 0; i < d[n].struAttribute84.nPointsNum; i++) t.drawTrackLine(d[n].struAttribute84.ptIvsTrackPoints[i], 0);
                            for (i = 0; i < d[n].struAttribute8C.nAccessoryNum; i++) t.drawTrackLine(d[n].struAttribute8C.ptAccessoryPoints[i], 0);
                            for (i = 0; i < d[n].struAttribute8C.nTissueBoxNum; i++) t.drawTrackLine(d[n].struAttribute8C.ptTissueBoxPoints[i], 0);
                            for (i = 0; i < d[n].struAttribute8C.nSunVisorCount; i++) t.drawTrackLine(d[n].struAttribute8C.ptSunVisorPoints[i], 0);
                            for (i = 0; i < d[n].struAttribute8C.nStandardCount; i++) t.drawTrackLine(d[n].struAttribute8C.ptStandardPoints[i], 0);
                            for (i = 0; i < d[n].struAttribute8D.nVirtualCoilNum; i++) {
                                t.drawPolyLine(d[n].struAttribute8D.struVirtualCoil[i].ptFirstCoilPoints, d[n].struAttribute8D.struVirtualCoil[i].nFirstCoilNum), t.drawPolyLine(d[n].struAttribute8D.struVirtualCoil[i].ptSecondCoilPoints, d[n].struAttribute8D.struVirtualCoil[i].nSecondCoilNum), t.drawLine(d[n].struAttribute8D.struVirtualCoil[i].ptRtailCoilPoints[0], d[n].struAttribute8D.struVirtualCoil[i].ptRtailCoilPoints[1]);
                                var a = d[n].struAttribute8D.struVirtualCoil[i].strInfo.length;
                                t.drawText(d[n].struAttribute8D.struVirtualCoil[i].strInfo, d[n].struAttribute8D.struVirtualCoil[i].ptStrInfoPoints, a)
                            }
                            t.drawText(d[n].struAttribute90.strTimeInfo, d[n].struAttribute90.ptTimePoints, d[n].struAttribute90.strTimeInfo.length)
                        }
                        p = 0
                    }(),
                    function() {
                        m.nBlockNum;
                        for (var e = 0; e < m.nBlockNum; e++) t.drawBlock(m.arrBlockRect[e], 0)
                    }()
            }
        }, new g
    };

    function R(e) {
        return (R = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
            return typeof e
        } : function(e) {
            return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        })(e)
    }

    function U(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }))), n.push.apply(n, r)
        }
        return n
    }

    function D(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? U(Object(n), !0).forEach((function(t) {
                E(e, t, n[t])
            })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : U(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }))
        }
        return e
    }

    function E(e, t, n) {
        return (t = function(e) {
            var t = function(e, t) {
                if ("object" !== R(e) || null === e) return e;
                var n = e[Symbol.toPrimitive];
                if (void 0 !== n) {
                    var r = n.call(e, "string");
                    if ("object" !== R(r)) return r;
                    throw new TypeError("@@toPrimitive must return a primitive value.")
                }
                return String(e)
            }(e);
            return "symbol" === R(t) ? t : String(t)
        }(t)) in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n, e
    }

    function I(e, t) {
        return t = (t = t.toLowerCase())[0].toUpperCase() + t.substr(1), Object.prototype.toString.call(e) === "[object " + t + "]"
    }

    function k(e, t, n) {
        if (void 0 === n && (n = 2), void 0 === t && (t = 0), (e = e.toString()).length >= n) return e;
        var r = n - e.length;
        return new Array(r).fill(String(t)).join("") + e
    }

    function L(e, t) {
        return void 0 !== e && e ? (t = t || new Date, e = (e = (e = (e = (e = (e = e.replace(/y/gi, k(t.getFullYear()), 0)).replace(/m/gi, k(t.getMonth() + 1), 0)).replace(/d/gi, k(t.getDate()), 0)).replace(/h/gi, k(t.getHours()), 0)).replace(/i/gi, k(t.getMinutes()), 0)).replace(/s/gi, k(t.getSeconds()), 0)) : ""
    }

    function F(e, t) {
        var n = (e = e || {}).nameFormat || ["ymd_his"];
        t = t || new Date;
        var r = "";
        if (I(n, "string")) n = [n, {}];
        else {
            if (!I(n, "array")) return void
            function(e) {
                throw new Error("name format must be string or array")
            }();
            I(n[0], "string") || (n[0] = "ymd_his"), I(n[1], "object") || (n[1] = {})
        }
        var i = n[0].split(/\{(?:[^{}]+)\}/),
            a = n[1];
        n[0].replace(/\{([^{}]*)\}/g, (function(e, t, n) {
            i.shift(), r += L(), r += t in a ? a[t] : e
        }));
        var o = i.shift();
        return r += L(o, t)
    }

    function O(e, t) {
        this.name = e, this.allowUpDateName = !0, this.byteLength = 0, this.options = t, this.startTime = (new Date).toLocaleString()
    }
    O.prototype.setEndTime = function() {
        this.endTime = (new Date).toLocaleString()
    }, O.prototype.updateNameByStream = function(e, t) {
        if (this.allowUpDateName) {
            var n = new Uint8Array(t),
                r = (n[19] << 24) + (n[18] << 16) + (n[17] << 8) + n[16] >>> 0,
                i = "20" + (r >> 26) + "/" + (r >> 22 & 15) + "/" + (r >> 17 & 31) + " " + (r >> 12 & 31) + ":" + (r >> 6 & 63) + ":" + (63 & r);
            this.name = F(e, new Date(i)), this.allowUpDateName = !1, n = null
        }
        t = null
    };
    var B = new function() {
            var e = {
                    count: 0,
                    total: 0,
                    group: []
                },
                t = function() {};
            return t.prototype.add = function(t) {
                e.count++, e.total += t.byteLength, e.group.push(t)
            }, t.prototype.get = function(t) {
                return t in e ? e[t] : e
            }, new t
        },
        V = function() {
            var e = 1048576,
                t = null,
                n = null,
                r = 0,
                i = void 0,
                a = null,
                o = 0,
                s = null;

            function l() {
                this.onMessage = function() {}, this.postMessage = function(e) {
                    this.__onMessage(e)
                }, this.__postMessage = function(e) {
                    this.onMessage(e)
                }
            }
            return l.prototype.__onMessage = function(e) {
                var t = e;
                switch (t.type) {
                    case "init":
                        this.init(t.options);
                        break;
                    case "addBuffer":
                        this.addBuffer(t);
                        break;
                    case "close":
                        this.close()
                }
            }, l.prototype.init = function(t) {
                this.fullSize = t.fullSize || 1 / 0, this.singleSize = t.singleSize + 20 * e || 520 * e, i = "init", s = t.recordName, this.limitOptions = Object.assign({
                    limitBy: "fullSize"
                }, t.limitOptions), this.nameOptions = Object.assign({
                    namedBy: "date",
                    nameFormat: ["ymd_his", {}]
                }, t.nameOptions)
            }, l.prototype._malloc = function(e) {
                t && n && (n = null, t = null), t = new ArrayBuffer(e), n = new DataView(t);
                var r = this.nameOptions,
                    i = "";
                if (null != s) i = s;
                else switch (this.nameOptions.namedBy.toLowerCase()) {
                    case "date":
                        i = F(r);
                        break;
                    default:
                        i = F()
                }
                a = new O(i)
            }, l.prototype._initVideoMem = function() {
                !t && this.singleSize && this._malloc(this.singleSize)
            }, l.prototype.appendVideoBuf = function(t, i, a) {
                var s, l = t.byteLength;
                if ((s = 5 == o ? i + l : r + l) > this.singleSize - 20 * e) this.inNodePlace(), this.addBuffer({
                    buffer: t
                });
                else {
                    if (5 == o) {
                        for (var u = i; u < s; u++) n.setUint8(u, t[u - i]);
                        s > r && (r = s)
                    } else {
                        for (u = r; u < s; u++) n.setUint8(u, t[u - r]);
                        r = s
                    }
                    this.__postMessage({
                        type: "pendding",
                        size: r,
                        total: this.singleSize
                    })
                }
            }, l.prototype.addBuffer = function(e) {
                if ("closed" !== i) {
                    var t = e.buffer,
                        n = e.offset;
                    o = e.recordType, this._initVideoMem(), i = "addBuffer";
                    var a, s = t.length;
                    a = 5 == o ? n + s : r + s, B.get("total") + a > this.fullSize ? this.close() : this.appendVideoBuf(t, n)
                }
            }, l.prototype.inNodePlace = function() {
                if ("addBuffer" === i) {
                    i = "download", a.updateNameByStream(this.nameOptions, t.slice(0, 20)), a.byteLength = r, a.setEndTime(), B.add(a);
                    var e = t.slice(0, r);
                    if (this.reset(), this.__postMessage({
                            type: "download",
                            data: D(D({}, a), {}, {
                                buffer: e
                            })
                        }), e = null, "count" === this.limitOptions.limitBy) {
                        var n = this.limitOptions.count;
                        n && n === B.get("count") && this.close()
                    }
                }
            }, l.prototype.reset = function() {
                r = 0, this._malloc(this.singleSize)
            }, l.prototype.close = function() {
                this.inNodePlace(), "closed" !== i && void 0 !== i && (i = "closed", this.__postMessage({
                    type: "closed",
                    message: "record was closed"
                }), t = null, n = null)
            }, new l
        },
        N = function() {
            var e = 0,
                t = {
                    timestamp: 0,
                    timestamp_usec: 0
                },
                n = null,
                a = null,
                l = null,
                u = !1,
                c = null,
                f = null,
                h = null,
                d = null,
                p = null,
                m = null,
                g = null,
                v = 1,
                w = "",
                _ = !1,
                x = null,
                A = 0,
                C = {
                    id: 1,
                    samples: null,
                    baseMediaDecodeTime: 0
                },
                T = 0,
                R = null,
                U = null,
                D = 0,
                E = 0,
                I = 0,
                k = 1,
                L = null,
                F = 0,
                O = null,
                B = null,
                N = null,
                z = null,
                j = null,
                H = null,
                q = null,
                Y = null,
                W = 0,
                G = null,
                X = null,
                Z = null,
                K = null,
                Q = 0,
                J = 0,
                $ = 0,
                ee = 0,
                te = "",
                ne = !1,
                re = null,
                ie = !1,
                ae = !1,
                oe = null,
                se = null,
                le = null,
                ue = null,
                ce = null,
                fe = null,
                he = null,
                de = null,
                pe = null,
                me = null,
                ge = null,
                ye = null,
                ve = null,
                we = null,
                Se = 0,
                be = 0,
                _e = null,
                xe = null,
                Ae = 0,
                Pe = 0,
                Ce = 0,
                Te = 0,
                Me = !0,
                Re = !1,
                Ue = !1,
                De = !1,
                Ee = 0,
                Ie = 0,
                ke = !1,
                Le = null,
                Fe = null,
                Oe = -1;

            function Be() {}

            function Ve() {
                f.setDecodeType(Te), f.setCodecInfo(w), f.setInitSegmentFunc(je), f.setSpeedPlay(v), f.setFPS(A)
            }

            function Ne() {
                f.setVideoSizeCallback(He), f.setBeginDrawCallback(B), f.setErrorCallback(j), f.setAudioStartCallback(ze), f.setReStartMSECallback(qe)
            }

            function ze(e, t) {}

            function je() {
                return x
            }

            function He() {
                null !== z && z(!1)
            }

            function qe() {
                De = !0, console.log("reStartMSECallback")
            }

            function Ye() {
                f && (f.close(), f.terminate(), f = null), x = null, h = null, d = null, _ = !1, ke = !1, D = 0, C = {
                    id: 1,
                    samples: null,
                    baseMediaDecodeTime: 0
                }, T = 0, k = 1, I = 0, F = 0, E = 0
            }

            function We(e, t) {
                function n(e, t) {
                    n.prototype.w = e, n.prototype.h = t
                }
                return n.prototype = {
                    toString: function() {
                        return "(" + n.prototype.w + ", " + n.prototype.h + ")"
                    },
                    getHalfSize: function() {
                        return new We(n.prototype.w >>> 1, n.prototype.h >>> 1)
                    },
                    length: function() {
                        return n.prototype.w * n.prototype.h
                    }
                }, new n(e, t)
            }
            Be.prototype = {
                Init: function() {
                    var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                    u = t.isTalkService, ae = t.isTalkService, n = t.canvasElem, a = t.videoElem, l = t.ivsCanvasElem;
                    var r = t.isPlayback,
                        i = t.useH265MSE,
                        o = t.useH264MSE,
                        c = Object(s.f)(),
                        h = c.bSupportMultiThread;
                    c.browserType, c.errorCode, Ue = h;
                    var d = Object(s.g)(),
                        p = d.bSupportH265MSE,
                        m = d.bSupportH264MSE;
                    Re = i && p, Me = o && m, Module._PLAY_SetSupportWebMSE(Me, Re);
                    var g = Module._malloc(1);
                    G = new Uint8Array(Module.HEAPU8.buffer, g, 1), Module._PLAY_GetFreePort(G.byteOffset), e = G[0], G = null, Module._free(g), n && this.setIVSCanvasSize(parseInt(n.width), parseInt(n.height));
                    var y = Module._PLAY_SetStreamOpenMode(e, r ? 1 : 0);
                    return Module._PLAY_OpenStream(e, 0, 0, 10485760), (y = Module._PLAY_Play(e, 1)) && (X = Module._malloc(5242880), Z = new Uint8Array(Module.HEAPU8.buffer, X, 5242880), (n || a) && (_e = new S(n, r ? 100 : 25, r), (Re || Me) && ((f = b()).init(a), Ne()))), y
                },
                setIVSCanvasSize: function(t, n) {
                    Module._PLAY_SetCanvaParam(e, t, n)
                },
                GetPlayPort: function() {
                    return e
                },
                InputData: function(t, n) {
                    var r = 0;
                    return Z && (Z.set(n), r = Module._PLAY_InputData(e, Z.byteOffset, n.length)), r
                },
                Pause: function(e) {
                    return Module._PLAY_Pause(e)
                },
                setSpeed: function(t) {
                    var n = Module._PLAY_SetPlaySpeed(e, t);
                    return _e.setPlaySpeed(t), v = t, n
                },
                SetSecurityKey: function(t, n, r, i, a) {
                    var o = Module._malloc(49),
                        s = new Uint8Array(Module.HEAPU8.buffer),
                        l = 0;
                    if (1 == t) n.forEach((function(e, t) {
                        s[o + l >> 0] = e, l++
                    }));
                    else if (2 == t) {
                        var u = new Uint8Array(16);
                        if (s[o + l >> 0] = 1, l++, 0 == a) {
                            for (var c = 0; c > 16; c++) u[c] = 0;
                            a = 16, i = u
                        }
                        i.forEach((function(e, t) {
                            s[o + l >> 0] = e, l++
                        })), n.forEach((function(e, t) {
                            s[o + l] = e, l++
                        })), r = 1 + r + a, u = null
                    } else 3 == t && n.split("").forEach((function(e, t) {
                        s[o + l >> 0] = e.charCodeAt(0), l++
                    }));
                    var f = Module._PLAY_SetSecurityKey(e, o, r);
                    return Module._free(o), f
                },
                StartRecord: function(t) {
                    return Le = new V,
                        function(e, t) {
                            var n = parseInt(1e3) || 500;
                            Le.postMessage({
                                type: "init",
                                options: {
                                    recordName: t,
                                    singleSize: 1048576 * n,
                                    nameOptions: {
                                        namedBy: "date",
                                        nameFormat: ["ymd_his"]
                                    },
                                    limitOptions: {
                                        limitBy: "count",
                                        count: 10
                                    }
                                }
                            }), Le.onMessage = function(e) {
                                switch (e.type) {
                                    case "pendding":
                                        break;
                                    case "download":
                                        ! function(e, t) {
                                            var n = new Blob([t]),
                                                r = document.createElement("a");
                                            r.href = URL.createObjectURL(n), r.download = e, r.click(), URL.revokeObjectURL(r.href), r = null, t = null
                                        }(e.data.name, e.data.buffer);
                                        break;
                                    case "closed":
                                        Le = null
                                }
                            }
                        }(0, t), U = 5, Module._PLAY_StartDataRecord(e, 0, U)
                },
                StopRecord: function() {
                    var t = Module._PLAY_StopDataRecord(e);
                    return Le.postMessage({
                        type: "close"
                    }), Le = null, t
                },
                OpenIVSDraw: function() {
                    return (Fe = new M).Init(l), Module._PLAY_RenderPrivateData(e, 1, 0)
                },
                CloseIVSDraw: function() {
                    var t = Module._PLAY_RenderPrivateData(e, 0, 0);
                    return Fe.Stop(), Fe = null, t
                },
                Stop: function() {
                    var t = Module._PLAY_Stop(e);
                    return 0 == t || (t = Module._PLAY_CloseStream(e), c && (c.stop(), c = null), h = null, d = null, f && (f.close(), f.terminate(), f = null), Z = null, Module._free(X), oe = null, se = null, le = null, ce = null, fe = null, he = null, de = null, pe = null, me = null, ye = null, ve = null, we = 0, ge && (ge.stop(), ge = null, window.wsAudioPlayer = null), Ue ? xe = null : _e && (_e.stopRendering(), _e.terminate(), _e = null), Ae = 0, Pe = 0, Ce = 0, x = null, w = null, De = !1, _ = !1, Fe && (Fe.Stop(), Fe = null), Ie = 0, ke = !1, Ee = 0), t
                },
                FrameDataCallBack: function(e, s, l, c, y, S) {
                    se || (oe = new ArrayBuffer(48), se = new Uint8Array(oe), le = new DataView(oe)), se.set(Module.HEAPU8.subarray(S, S + 48));
                    var P = le.getUint32(0, !0),
                        M = le.getUint8(4),
                        U = le.getUint8(5),
                        B = le.getUint8(6),
                        V = le.getUint8(7),
                        N = le.getUint16(38, !0),
                        z = le.getUint8(40),
                        j = le.getUint8(41),
                        G = le.getUint8(42),
                        X = le.getUint8(43),
                        Z = le.getUint8(44),
                        Le = Date.UTC(N, z, j, G, X, Z) / 1e3;
                    if (t.timestamp = Le, t.timestamp_usec = 0, 1 == M) {
                        if (2 == B || 4 == B || 8 == B ? Te = 1 : 12 == B && (Te = 2), ee = le.getUint16(12, !0), Q = le.getUint16(16, !0), J = le.getUint16(18, !0), 0 == Q || 0 == J) return;
                        if (A = le.getUint8(45), Ie <= 5 && Ie++, 18 == U || 20 == U ? $ = 1 : 0 == U && ($ = 0), (1 == Te && 1 == Me || 2 == Te && 1 == Re) && !$ && 13 != V) {
                            if (! function(e, t) {
                                    return 0 == t || 18 == t || 20 == t || -1 == Oe || e == Oe + 1
                                }(P, U)) return;
                            if (Oe = P, Q == Ae && J == Pe && B == Ce || (0 != Ae && (De = !0), Ae = Q, Pe = J, Ce = B), De && (Ye(), De = !1), 0 == ke && 0 != U) return;
                            if (ue = new ArrayBuffer(y), (K = new Uint8Array(ue)).set(Module.HEAPU8.subarray(s, s + y)), null == h && (h = new i(Te)), null == d && (d = new r(Te)), function(e, t, n) {
                                    for (var r = null, i = e.length, a = [], o = 0; o <= i;)
                                        if (0 == e[o])
                                            if (0 == e[o + 1])
                                                if (1 == e[o + 2]) {
                                                    if (a.push(o), o += 3, 1 == Te) {
                                                        if (5 == (31 & e[o]) || 1 == (31 & e[o])) break
                                                    } else if (2 == Te && (38 == (255 & e[o]) || 2 == (255 & e[o]))) break
                                                } else 0 == e[o + 2] ? o++ : o += 3;
                                    else o += 2;
                                    else o += 1;
                                    var s = 0;
                                    if (1 == Te) {
                                        for (o = 0; o < a.length; o++) switch (r = e.subarray(a[o] + 3, a[o + 1]), 31 & e[a[o] + 3]) {
                                            case 1:
                                            case 5:
                                                s = a[o] - 1, O = e.subarray(s, e.length);
                                                break;
                                            case 7:
                                                h.parse(r), m = r;
                                                break;
                                            case 8:
                                                g = r
                                        }
                                        if (!_) {
                                            _ = !0;
                                            var l = {
                                                id: 1,
                                                width: Q,
                                                height: J,
                                                type: "video",
                                                profileIdc: h.getSpsValue("profile_idc"),
                                                profileCompatibility: 0,
                                                levelIdc: h.getSpsValue("level_idc"),
                                                sps: [m],
                                                pps: [g],
                                                timescale: 1e3,
                                                fps: A
                                            };
                                            x = d.initSegment(l), w = h.getCodecInfo()
                                        }
                                    } else if (2 == Te) {
                                        for (o = 0; o < a.length; o++) switch (r = e.subarray(a[o] + 3, a[o + 1] - 1), 255 & e[a[o] + 3]) {
                                            case 2:
                                            case 38:
                                                s = a[o] - 1, O = e.subarray(s, e.length);
                                                break;
                                            case 64:
                                                p = r;
                                                break;
                                            case 66:
                                                var u = e.subarray(a[o] + 5, a[o + 1] - 1);
                                                h.parse(u), m = r;
                                                break;
                                            case 68:
                                                g = r
                                        }
                                        if (!_) {
                                            _ = !0;
                                            var c = h.getSpsValue("general_profile_space"),
                                                f = h.getSpsValue("general_tier_flag"),
                                                y = h.getSpsValue("general_profile_idc"),
                                                v = h.getSpsValue("temporalIdNested");
                                            h.getSpsValue("general_profile_compatibility_flags"), h.getSpsValue("general_constraint_indicator_flags"), l = {
                                                id: 1,
                                                width: Q,
                                                height: J,
                                                type: "video",
                                                general_profile_flag: c << 6 | f << 5 | y,
                                                general_profile_compatibility_flags: h.getSpsValue("general_profile_compatibility_flags"),
                                                general_constraint_indicator_flags: h.getSpsValue("general_constraint_indicator_flags"),
                                                general_level_idc: h.getSpsValue("general_level_idc"),
                                                chroma_format_idc: h.getSpsValue("chroma_format_idc"),
                                                bitDepthLumaMinus8: h.getSpsValue("bitDepthLumaMinus8"),
                                                bitDepthChromaMinus8: h.getSpsValue("bitDepthChromaMinus8"),
                                                rate_layers_nested_length: 11 | (1 & v) << 2,
                                                vps: [p],
                                                sps: [m],
                                                pps: [g],
                                                timescale: 1e3,
                                                fps: A
                                            }, x = d.initSegment(l)
                                        }
                                    }
                                }(K), 0 == ke) {
                                var Fe = {
                                    decodeMode: "video",
                                    width: Q,
                                    height: J
                                };
                                [2, 4, 8].includes(B) ? Fe.encodeMode = "H264" : 12 === B && (Fe.encodeMode = "H265"), H(Fe), Ee = 1, null == f && (f = b()).init(a), Ne(), Ve(), ke = !0
                            }
                            ne && (ne = !1, f.capture(te, re)), f.setvideoTimeStamp(t),
                                function() {
                                    if (null != O) {
                                        var e = {
                                            duration: Math.round(1 / A * 1e3),
                                            size: O.length,
                                            frame_time_stamp: null,
                                            frameDuration: null
                                        };
                                        e.frameDuration = e.duration, null == C.samples && (C.samples = new Array(1)), C.samples[D++] = e, E += e.frameDuration, I += e.frameDuration;
                                        var t = O.length - 4;
                                        O[0] = (4278190080 & t) >>> 24, O[1] = (16711680 & t) >>> 16, O[2] = (65280 & t) >>> 8, O[3] = 255 & t;
                                        var n = new Uint8Array(O.length + T);
                                        if (0 !== T && n.set(R), n.set(O, T), T = (R = n).length, D % 1 == 0 && 0 !== D) {
                                            if (null !== C.samples[0].frameDuration && (C.baseMediaDecodeTime = 1 === k ? 0 : F, F = E), 1 == v)
                                                for (var r = C.samples.length, i = I / 1, a = 0; a < r; a++) C.samples[a].frameDuration = i;
                                            I = 0, L = d.mediaSegment(k, C, R, C.baseMediaDecodeTime), k++, D = 0, R = null, T = 0, null !== f ? f.setMediaSegment(L) : !1 === _ && (debug.log("workerManager::videoMS error!! recreate videoMS"), Ve())
                                        }
                                    }
                                }(), ue = null, K = null
                        } else {
                            0 != Ee && (Ye(), De = !0, Ae = 0, Pe = 0);
                            var Be = le.getUint16(20, !0);
                            Q == Ae && J == Pe || (Ee = 0, Ae = Q, Pe = J, Ue ? this.resize(Q, J) : _e.resize(Q, J), Fe = {
                                decodeMode: "canvas",
                                width: Q,
                                height: J
                            }, [2, 4, 8].includes(B) ? Fe.encodeMode = "H264" : 12 === B && (Fe.encodeMode = "H265"), H(Fe), ce = null, fe = null, he = null, de = null, pe = null, me = null, ce = new ArrayBuffer(Q * J), de = new Uint8Array(ce), fe = new ArrayBuffer(Q * J / 4), pe = new Uint8Array(fe), he = new ArrayBuffer(Q * J / 4), me = new Uint8Array(he)), ne && (ne = !1, function(e, t) {
                                var r = n.width,
                                    i = n.height,
                                    a = document.createElement("canvas");
                                a.width = r, a.height = i;
                                for (var o = a.getContext("2d"), s = 0; s < e.length; s++) e[s] && o.drawImage(e[s], 0, 0, r, i);
                                for (var l = a.toDataURL(), u = atob(l.substring("data:image/png;base64,".length)), c = new Uint8Array(u.length), f = 0, h = u.length; f < h; ++f) c[f] = u.charCodeAt(f);
                                var d = new Blob([c.buffer], {
                                    type: "image/png"
                                });
                                c = null, Ge(d, t + ".png"), d = null
                            }([n, re], te));
                            var ze = 0;
                            for (ze = 0; ze < J; ze++) de.set(Module.HEAPU8.subarray(s + ze * Be, s + ze * Be + Q), ze * Q);
                            for (ze = 0; ze < J / 2; ze++) pe.set(Module.HEAPU8.subarray(l + ze * Be / 2, l + ze * Be / 2 + Q / 2), ze * Q / 2);
                            for (ze = 0; ze < J / 2; ze++) me.set(Module.HEAPU8.subarray(c + ze * Be / 2, c + ze * Be / 2 + Q / 2), ze * Q / 2);
                            Ue ? xe && xe.drawCanvas(de, pe, me) : _e && _e.draw(de, pe, me, A)
                        }
                        if (W !== Le && (W = Le, Y && Y(W)), !ee && ie) return void q()
                    } else if (2 == M && ae) {
                        if (!u && Ie < 5) {
                            if (A > 5 || 0 == A) return;
                            if (Ie < 2) return
                        }
                        if (le.getUint32(24, !0), le.getUint32(28, !0) > 0) return;
                        var je = le.getUint32(32, !0),
                            He = le.getUint8(36, !0);
                        le.getUint8(37, !0), we != y && (we = y, ye = null, ve = null, ye = new ArrayBuffer(y), ve = new Uint8Array(ye)), ve.set(Module.HEAPU8.subarray(s, s + y));
                        for (var qe = new Int16Array(ve.buffer, ve.byteOffset, ve.byteLength / Int16Array.BYTES_PER_ELEMENT), We = new Float32Array(qe.length), Xe = 0; Xe < qe.length; Xe++) We[Xe] = qe[Xe] / Math.pow(2, 15);
                        je == Se && He == be || (Se = je, be = He, ge && (ge.stop(), ge = null), ge = new o, window.wsAudioPlayer = ge, ge.setSamplingRate(je), null !== ge && (ge.setInitVideoTimeStamp(0), ge.audioInit(1, je) || (ge.stop(), ge = null, window.wsAudioPlayer = null))), ge && ge.bufferAudio(We, 0), qe = null, We = null
                    }
                    Le = null
                },
                setCallback: function(e, t) {
                    switch (e) {
                        case "timeStamp":
                        case "ResolutionChanged":
                            break;
                        case "audioTalk":
                            N = t;
                            break;
                        case "stepRequest":
                        case "metaEvent":
                        case "videoMode":
                            break;
                        case "loadingBar":
                            z = t;
                            break;
                        case "Error":
                            j = t;
                            break;
                        case "PlayStart":
                            B = t, _e && _e.setPlayStartCallback(B);
                            break;
                        case "DecodeStart":
                            H = t;
                            break;
                        case "UpdateCanvas":
                        case "FrameTypeChange":
                        case "MSEResolutionChanged":
                        case "audioChange":
                        case "WorkerReady":
                            break;
                        case "FileOver":
                            q = t;
                            break;
                        case "UpdatePlayingTime":
                            Y = t
                    }
                },
                capture: function(e, t) {
                    te = e, ne = !0, re = t
                },
                setFileOver: function(e) {
                    ie = e, !ee && ie && q()
                },
                mute: function(e) {
                    ge && ge.Mute(e)
                },
                setPlayAudio: function(e) {
                    ae = e
                },
                setVolume: function(e) {
                    ge && ge.setVolume(e)
                },
                talkInit: function(e, t) {
                    c || (c = new P), c.talkInit(e, t), c.setSendAudioTalkCallback(N)
                },
                IVSDataCallBack: function(e, t, n, r) {
                    Fe.setIVSData(e, t, n, r)
                },
                DrawIVSDataCallBack: function(e) {
                    Fe.drawIVSData()
                },
                RecordDataCallBack: function(e, t, n, r) {
                    var i = new ArrayBuffer(n),
                        a = new Uint8Array(i);
                    a.set(Module.HEAPU8.subarray(t, t + n)), Le.postMessage({
                        type: "addBuffer",
                        buffer: a,
                        offset: r,
                        recordType: U
                    }), i = null, a = null
                },
                resize: function(e, t) {
                    xe && (xe = null);
                    var r = new We(e, t);
                    (xe = new y(n, r)).setPlayStartCallback(B), r = null
                }
            };
            var Ge = function(e) {
                var t = e.document,
                    n = function() {
                        return e.URL || e.webkitURL || e
                    },
                    r = t.createElementNS("http://www.w3.org/1999/xhtml", "a"),
                    i = "download" in r,
                    a = /constructor/i.test(e.HTMLElement),
                    o = /CriOS\/[\d]+/.test(navigator.userAgent),
                    s = function(t) {
                        (e.setImmediate || e.setTimeout)((function() {
                            throw t
                        }), 0)
                    },
                    l = function(e) {
                        setTimeout((function() {
                            "string" == typeof e ? n().revokeObjectURL(e) : e.remove()
                        }), 4e4)
                    },
                    u = function(e) {
                        return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(e.type) ? new Blob([String.fromCharCode(65279), e], {
                            type: e.type
                        }) : e
                    },
                    c = function(t, c, f) {
                        f || (t = u(t));
                        var h, d = this,
                            p = "application/octet-stream" === t.type,
                            m = function() {
                                ! function(e, t, n) {
                                    for (var r = (t = [].concat(t)).length; r--;) {
                                        var i = e["on" + t[r]];
                                        if ("function" == typeof i) try {
                                            i.call(e, e)
                                        } catch (e) {
                                            s(e)
                                        }
                                    }
                                }(d, "writestart progress write writeend".split(" "))
                            };
                        if (d.readyState = d.INIT, i) return h = n().createObjectURL(t), void setTimeout((function() {
                            r.href = h, r.download = c, r.dispatchEvent(new MouseEvent("click")), m(), l(h), d.readyState = d.DONE
                        }));
                        ! function() {
                            if ((o || p && a) && e.FileReader) {
                                var r = new FileReader;
                                return r.onloadend = function() {
                                    var t = o ? r.result : r.result.replace(/^data:[^;]*;/, "data:attachment/file;");
                                    e.open(t, "_blank") || (e.location.href = t), t = void 0, d.readyState = d.DONE, m()
                                }, r.readAsDataURL(t), void(d.readyState = d.INIT)
                            }
                            h || (h = n().createObjectURL(t)), p ? e.location.href = h : e.open(h, "_blank") || (e.location.href = h), d.readyState = d.DONE, m(), l(h)
                        }()
                    },
                    f = c.prototype;
                return "undefined" != typeof navigator && navigator.msSaveOrOpenBlob ? function(e, t, n) {
                    return t = t || e.name || "download", n || (e = u(e)), navigator.msSaveOrOpenBlob(e, t)
                } : (f.readyState = f.INIT = 0, f.WRITING = 1, f.DONE = 2, f.error = f.onwritestart = f.onprogress = f.onwrite = f.onabort = f.onerror = f.onwriteend = null, function(e, t, n) {
                    return new c(e, t || e.name || "download", n)
                })
            }(window);
            return new Be
        },
        z = function(e, t) {
            var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
            return function(e) {
                e = e;
                var r = null,
                    i = n.isTalkService,
                    a = null,
                    o = null,
                    l = 0,
                    u = 0,
                    c = !1,
                    f = !1,
                    h = "",
                    d = [],
                    p = 1,
                    m = null,
                    g = {},
                    y = "Options",
                    v = null,
                    w = null,
                    S = null,
                    b = {},
                    _ = t + "&trackID=701",
                    x = null,
                    A = {},
                    P = null,
                    C = 0,
                    T = 0,
                    M = null,
                    R = !1,
                    U = !1,
                    D = Symbol();

                function E() {}

                function I(e, t, n, r, i) {
                    var a = "";
                    switch (e) {
                        case "OPTIONS":
                        case "TEARDOWN":
                        case "GET_PARAMETER":
                        case "SET_PARAMETERS":
                            a = e + " " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\n" + h + "\r\n";
                            break;
                        case "DESCRIBE":
                            a = e + " " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\n" + h, a += "User-Agent: Dahua Rtsp Client/2.0\r\n\r\n", j(), H();
                            break;
                        case "SETUP":
                            a = e + " " + _ + "/trackID=" + t + " RTSP/1.0\r\nCSeq: " + p + "\r\nUser-Agent: Dahua Rtsp Client/2.0\r\n" + h + "Transport: DH/AVP/TCP;unicast;interleaved=" + 2 * t + "-" + (2 * t + 1) + "\r\n", a += 0 != w ? "Session: " + w + "\r\n\r\n" : "\r\n", j(), H();
                            break;
                        case "PLAY":
                            a = e + " " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\nSession: " + w + "\r\n", null != r && 0 != r ? (a += "Range: npt=" + r + "-\r\n", a += h) : a += h, a += i ? "Speed: " + i + "\r\n" : "\r\n", j(), H();
                            break;
                        case "PAUSE":
                            a = e + " " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\nSession: " + w + "\r\n\r\n";
                            break;
                        case "SCALE":
                            a = "PLAY " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\nSession: " + w + "\r\n", a += "Scale: " + r + "\r\n", a += h + "\r\n"
                    }
                    return s.h.log(a), a
                }

                function k(e) {
                    !0 === i && function(e) {
                        null !== r && r.readyState === WebSocket.OPEN ? r.send(e) : (m({
                            symbol: D,
                            errorCode: "504",
                            description: "Talking Failed",
                            place: "SendRtpData"
                        }), console.log("SendRtpData - Websocket does not exist"))
                    }(e)
                }

                function L(e) {
                    s.h.log(e);
                    var t, n = e.search("CSeq: ") + 5;
                    if (p = parseInt(e.slice(n, n + 10)) + 1, 401 === (t = function(e) {
                            var t = {},
                                n = 0,
                                r = 0,
                                i = null,
                                a = (-1 !== e.search("Content-Type: application/sdp") ? e.split("\r\n\r\n")[0] : e).split("\r\n"),
                                o = a[0].split(" ");
                            if (o.length > 2 && (t.ResponseCode = parseInt(o[1]), t.ResponseMessage = o[2]), 200 === t.ResponseCode) {
                                for (n = 1; n < a.length; n++)
                                    if ("Public" === (i = a[n].split(":"))[0]) t.MethodsSupported = i[1].split(",");
                                    else if ("CSeq" === i[0]) t.CSeq = parseInt(i[1]);
                                else if ("Content-Type" === i[0]) t.ContentType = i[1], -1 !== t.ContentType.search("application/sdp") && (t.SDPData = Y(e));
                                else if ("Content-Length" === i[0]) t.ContentLength = parseInt(i[1]);
                                else if ("Content-Base" === i[0]) {
                                    var s = a[n].search("Content-Base:"); - 1 !== s && (t.ContentBase = a[n].substr(s + 13))
                                } else if ("Session" === i[0]) {
                                    var l = i[1].split(";");
                                    t.SessionID = parseInt(l[0])
                                } else if ("Transport" === i[0]) {
                                    var u = i[1].split(";");
                                    for (r = 0; r < u.length; r++) {
                                        var c = u[r].search("interleaved=");
                                        if (-1 !== c) {
                                            var f = u[r].substr(c + 12).split("-");
                                            f.length > 1 && (t.RtpInterlevedID = parseInt(f[0]), t.RtcpInterlevedID = parseInt(f[1]))
                                        }
                                    }
                                } else if ("RTP-Info" === i[0]) {
                                    i[1] = a[n].substr(9);
                                    var h = i[1].split(",");
                                    for (t.RTPInfoList = [], r = 0; r < h.length; r++) {
                                        var d = h[r].split(";"),
                                            p = {},
                                            m = 0;
                                        for (m = 0; m < d.length; m++) {
                                            var g = d[m].search("url="); - 1 !== g && (p.URL = d[m].substr(g + 4)), -1 !== (g = d[m].search("seq=")) && (p.Seq = parseInt(d[m].substr(g + 4)))
                                        }
                                        t.RTPInfoList.push(p)
                                    }
                                }
                            } else if (401 === t.ResponseCode)
                                for (n = 1; n < a.length; n++)
                                    if ("CSeq" === (i = a[n].split(":"))[0]) t.CSeq = parseInt(i[1]);
                                    else if ("WWW-Authenticate" === i[0]) {
                                var y = i[1].split(",");
                                for (r = 0; r < y.length; r++) {
                                    var v = y[r].search("Digest realm=");
                                    if (-1 !== v) {
                                        var w = y[r].substr(v + 13).split('"');
                                        t.Realm = w[1]
                                    }
                                    if (-1 !== (v = y[r].search("nonce="))) {
                                        var S = y[r].substr(v + 6).split('"');
                                        t.Nonce = S[1]
                                    }
                                }
                            }
                            return t
                        }(e)).ResponseCode && "" === h) ! function(e) {
                        var t, n, r = A.username;
                        n = function(e, t, n, r, i, a) {
                            var o, s;
                            return o = hex_md5(e + ":" + r + ":" + t).toLowerCase(), s = hex_md5(a + ":" + n).toLowerCase(), hex_md5(o + ":" + i + ":" + s).toLowerCase()
                        }(r, A.passWord, (t = {
                            Method: y.toUpperCase(),
                            Realm: e.Realm,
                            Nonce: e.Nonce,
                            Uri: _
                        }).Uri, t.Realm, t.Nonce, t.Method), h = 'Authorization: Digest username="' + r + '", realm="' + t.Realm + '",', h += ' nonce="' + t.Nonce + '", uri="' + t.Uri + '", response="' + n + '"', h += "\r\n", F(I("OPTIONS", null))
                    }(t);
                    else if (200 === t.ResponseCode) {
                        if ("Options" === y) return y = "Describe", I("DESCRIBE", null);
                        if ("Describe" === y) {
                            g = Y(e), void 0 !== t.ContentBase && (g.ContentBase = t.ContentBase);
                            var r = 0;
                            for (r = 0; r < g.Sessions.length; r += 1) {
                                var a = {};
                                "JPEG" === g.Sessions[r].CodecMime || "H264" === g.Sessions[r].CodecMime || "H265" === g.Sessions[r].CodecMime || "H264-SVC" == g.Sessions[r].CodecMime ? (a.codecName = g.Sessions[r].CodecMime, "H264-SVC" == g.Sessions[r].CodecMime && (a.codecName = "H264"), "H265" == g.Sessions[r].CodecMime && E.prototype.setLiveMode("canvas"), a.trackID = g.Sessions[r].ControlURL, a.ClockFreq = g.Sessions[r].ClockFreq, a.Port = parseInt(g.Sessions[r].Port), void 0 !== g.Sessions[r].Framerate && (a.Framerate = parseInt(g.Sessions[r].Framerate), x(a.Framerate)), d.push(a)) : "PCMU" === g.Sessions[r].CodecMime || -1 !== g.Sessions[r].CodecMime.search("G726-16") || -1 !== g.Sessions[r].CodecMime.search("G726-24") || -1 !== g.Sessions[r].CodecMime.search("G726-32") || -1 !== g.Sessions[r].CodecMime.search("G726-40") || "PCMA" === g.Sessions[r].CodecMime ? ("PCMU" === g.Sessions[r].CodecMime ? a.codecName = "G.711Mu" : "G726-16" === g.Sessions[r].CodecMime ? a.codecName = "G.726-16" : "G726-24" === g.Sessions[r].CodecMime ? a.codecName = "G.726-24" : "G726-32" === g.Sessions[r].CodecMime ? a.codecName = "G.726-32" : "G726-40" === g.Sessions[r].CodecMime ? a.codecName = "G.726-40" : "PCMA" === g.Sessions[r].CodecMime && (a.codecName = "G.711A"), a.trackID = g.Sessions[r].ControlURL, a.ClockFreq = g.Sessions[r].ClockFreq, a.Port = parseInt(g.Sessions[r].Port), a.Bitrate = parseInt(g.Sessions[r].Bitrate), a.TalkTransType = g.Sessions[r].TalkTransType, d.push(a)) : "mpeg4-generic" === g.Sessions[r].CodecMime || "MPEG4-GENERIC" === g.Sessions[r].CodecMime ? (a.codecName = "mpeg4-generic", a.trackID = g.Sessions[r].ControlURL, a.ClockFreq = g.Sessions[r].ClockFreq, a.Port = parseInt(g.Sessions[r].Port), a.Bitrate = parseInt(g.Sessions[r].Bitrate), d.push(a)) : "vnd.onvif.metadata" === g.Sessions[r].CodecMime ? (a.codecName = "MetaData", a.trackID = g.Sessions[r].ControlURL, a.ClockFreq = g.Sessions[r].ClockFreq, a.Port = parseInt(g.Sessions[r].Port), d.push(a)) : "stream-assist-frame" === g.Sessions[r].CodecMime ? (a.codecName = "stream-assist-frame", a.trackID = g.Sessions[r].ControlURL, a.ClockFreq = g.Sessions[r].ClockFreq, a.Port = parseInt(g.Sessions[r].Port), d.push(a)) : s.h.log("Unknown codec type:", g.Sessions[r].CodecMime, g.Sessions[r].ControlURL)
                            }
                            return v = 0, y = "Setup", I("SETUP", 0)
                        }
                        if ("Setup" === y) return (w = t.SessionID) ? (i && (P.setCallback("audioTalk", k), P.talkInit(d, b)), y = "Play", I("PLAY", null)) : (v += 1, I("SETUP", 0));
                        "Play" === y ? (w = t.SessionID, i || (clearInterval(S), S = setInterval((function() {
                            return F(I("GET_PARAMETER", null))
                        }), 4e4)), y = "Playing") : "Playing" === y || s.h.log("unknown rtsp state:" + y)
                    } else if (503 === t.ResponseCode) {
                        if ("Setup" === y && -1 !== d[v].trackID.search("trackID=t")) return d[v].RtpInterlevedID = -1, d[v].RtcpInterlevedID = -1, v += 1, m({
                            symbol: D,
                            errorCode: "504",
                            description: "Talking Failed",
                            place: "RtspResponseHandler"
                        }), v < d.length ? I("SETUP", d[v].trackID) : (y = "Play", I("PLAY", null));
                        m({
                            symbol: D,
                            errorCode: "503",
                            description: "Service Unavilable"
                        })
                    } else if (404 === t.ResponseCode) {
                        if ("Describe" === y || "Options" === y) return void m({
                            symbol: D,
                            errorCode: "404",
                            description: "rtsp not found"
                        })
                    } else if (457 === t.ResponseCode) return void s.h.log("RTP disconnection detect!!!")
                }

                function F(e) {
                    null != e && null != e && "" != e && (null !== r && r.readyState === WebSocket.OPEN ? (!1 === f && -1 !== e.search("DESCRIBE") && (c = !0, f = !0), null != e && r.send(O(e))) : s.h.log("ws未连接"))
                }

                function O(e) {
                    for (var t = e.length, n = new Uint8Array(new ArrayBuffer(t)), r = 0; r < t; r++) n[r] = e.charCodeAt(r);
                    return n
                }

                function B(t) {
                    if (!e.includes("?serverIp=")) {
                        var n = "https:" === location.protocol;
                        return t.replace("rtsp", n ? "wss" : "ws")
                    }
                    var r = t.split("//")[1].split("/")[0];
                    e = e.slice(0, e.indexOf("serverIp=")) + "serverIp=" + r
                }

                function z(e) {
                    var t = new Uint8Array,
                        n = new Uint8Array(e.data);
                    for ((t = new Uint8Array(n.length)).set(n, 0), l = t.length, C && (clearTimeout(C), C = 0), T && (clearTimeout(T), T = 0); l > 0;)
                        if (36 !== t[0]) {
                            var r = String.fromCharCode.apply(null, t),
                                i = null;
                            if (r.includes("302 Moved")) return B(_ = r.slice(r.indexOf("rtsp://"), r.indexOf("\r\n\r\n"))), E.prototype.disconnect(!1), void E.prototype.connect();
                            (-1 !== r.indexOf("OffLine:File Over") || -1 !== r.indexOf("OffLine:Internal Error") || r.includes("is_session_end: true")) && P.setFileOver(!0), -1 !== r.indexOf("OffLine:KmsUnavailable") && m({
                                symbol: D,
                                errorCode: "203"
                            }), !0 === c ? (i = r.lastIndexOf("\r\n"), c = !1) : i = r.search("\r\n\r\n");
                            var s = r.search("RTSP");
                            if (-1 === s) return void(t = new Uint8Array);
                            if (-1 === i) return void(l = t.length);
                            a = t.subarray(s, i + 6), t = t.subarray(i + 6), F(L(String.fromCharCode.apply(null, a))), l = t.length
                        } else {
                            if (o = t.subarray(0, 6), !(6 + (u = o[2] << 24 | o[3] << 16 | o[4] << 8 | o[5]) <= t.length)) return void(l = t.length);
                            var f = t.subarray(6, u + 6);
                            R && M.postMessage({
                                type: "addBuffer",
                                buffer: f
                            }), q(o, f), t = t.subarray(u + 6), l = t.length
                        }
                }

                function j() {
                    C && clearTimeout(C), C = setTimeout((function() {
                        m({
                            symbol: D,
                            errorCode: "407",
                            description: "Request Timeout"
                        })
                    }), 3e4)
                }

                function H() {
                    T && clearTimeout(T), T = setTimeout((function() {
                        m({
                            symbol: D,
                            errorCode: "408",
                            description: "Short Request Timeout"
                        })
                    }), 3e3)
                }

                function q(e, t) {
                    P && 0 === P.InputData(e, t) && U && q(e, t)
                }

                function Y(e) {
                    var t = {
                            Sessions: []
                        },
                        n = (-1 !== e.search("Content-Type: application/sdp") ? e.split("\r\n\r\n")[1] : e).split("\r\n"),
                        r = 0,
                        i = !1;
                    for (r = 0; r < n.length; r++) {
                        var a = n[r].split("=");
                        if (a.length > 0) switch (a[0]) {
                            case "a":
                                var o = a[1].split(":");
                                if (o.length > 1) {
                                    if ("control" === o[0]) {
                                        var s = n[r].search("control:");
                                        !0 === i ? -1 !== s && (t.Sessions[t.Sessions.length - 1].ControlURL = n[r].substr(s + 8)) : -1 !== s && (t.BaseURL = n[r].substr(s + 8))
                                    } else if ("rtpmap" === o[0]) {
                                        var l = o[1].split(" ");
                                        t.Sessions[t.Sessions.length - 1].PayloadType = l[0];
                                        var u = l[1].split("/");
                                        t.Sessions[t.Sessions.length - 1].CodecMime = u[0], u.length > 1 && (t.Sessions[t.Sessions.length - 1].ClockFreq = u[1])
                                    } else if ("framesize" === o[0]) {
                                        var c = o[1].split(" ");
                                        if (c.length > 1) {
                                            var f = c[1].split("-");
                                            t.Sessions[t.Sessions.length - 1].Width = f[0], t.Sessions[t.Sessions.length - 1].Height = f[1]
                                        }
                                    } else if ("framerate" === o[0]) t.Sessions[t.Sessions.length - 1].Framerate = o[1];
                                    else if ("fmtp" === o[0]) {
                                        var h = n[r].split(" ");
                                        if (h.length < 2) continue;
                                        for (var d = 1; d < h.length; d++) {
                                            var p = h[d].split(";"),
                                                m = 0;
                                            for (m = 0; m < p.length; m++) {
                                                var g = p[m].search("mode=");
                                                if (-1 !== g && (t.Sessions[t.Sessions.length - 1].mode = p[m].substr(g + 5)), -1 !== (g = p[m].search("config=")) && (t.Sessions[t.Sessions.length - 1].config = p[m].substr(g + 7), b.config = t.Sessions[t.Sessions.length - 1].config, b.clockFreq = t.Sessions[t.Sessions.length - 1].ClockFreq, b.bitrate = t.Sessions[t.Sessions.length - 1].Bitrate), -1 !== (g = p[m].search("sprop-vps=")) && (t.Sessions[t.Sessions.length - 1].VPS = p[m].substr(g + 10)), -1 !== (g = p[m].search("sprop-sps=")) && (t.Sessions[t.Sessions.length - 1].SPS = p[m].substr(g + 10)), -1 !== (g = p[m].search("sprop-pps=")) && (t.Sessions[t.Sessions.length - 1].PPS = p[m].substr(g + 10)), -1 !== (g = p[m].search("sprop-parameter-sets="))) {
                                                    var y = p[m].substr(g + 21).split(",");
                                                    y.length > 1 && (t.Sessions[t.Sessions.length - 1].SPS = y[0], t.Sessions[t.Sessions.length - 1].PPS = y[1])
                                                }
                                            }
                                        }
                                    }
                                } else 1 === o.length && ("recvonly" === o[0] ? t.Sessions[t.Sessions.length - 1].TalkTransType = "recvonly" : "sendonly" === o[0] && (t.Sessions[t.Sessions.length - 1].TalkTransType = "sendonly"));
                                break;
                            case "m":
                                var v = a[1].split(" "),
                                    w = {};
                                w.Type = v[0], w.Port = v[1], w.Payload = v[3], t.Sessions.push(w), i = !0;
                                break;
                            case "b":
                                if (!0 === i) {
                                    var S = a[1].split(":");
                                    t.Sessions[t.Sessions.length - 1].Bitrate = S[1]
                                }
                        }
                    }
                    return t
                }
                return E.prototype = {
                    init: function() {
                        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                        P || (P = new N), this.symbol = D, U = e.isPlayback, e.isTalkService = i, P.Init(e) > 0 && (this.nPlayPort = P.GetPlayPort())
                    },
                    connect: function() {
                        r || ((r = new WebSocket(e)).binaryType = "arraybuffer", y = "Options", r.addEventListener("message", z, !1), r.onopen = function() {
                            var e = "OPTIONS " + _ + " RTSP/1.0\r\nCSeq: " + p + "\r\n";
                            e += "User-Agent: Dahua Rtsp Client/2.0\r\n";
                            var t = O(e += "\r\n");
                            r.send(t)
                        }, r.onerror = function(e) {
                            m({
                                symbol: D,
                                errorCode: "202",
                                description: "Open WebSocket Error"
                            })
                        })
                    },
                    disconnect: function() {
                        var e = !(arguments.length > 0 && void 0 !== arguments[0]) || arguments[0];
                        P && e && (P.Stop(), P = null), M && (M = null), F(I("TEARDOWN", null)), clearInterval(S), S = null, null !== r && (r.onerror = null), null !== r && r.readyState === WebSocket.OPEN && (r.close(), r = null, w = null), M && (M = null)
                    },
                    controlPlayer: function(e) {
                        if ("PLAY_SPEED" === e.command) return F(I("PLAY", null, 0, null, e.speed)), F(I("GET_PARAMETER", null)), void P.setSpeed(e.speed);
                        var t = "";
                        switch (e.command, e.command) {
                            case "PLAY":
                                if (y = "Play", null != e.range) {
                                    t = I("PLAY", null, 0, e.range);
                                    break
                                }
                                t = I("PLAY", null);
                                break;
                            case "PAUSE":
                                if ("PAUSE" === y) break;
                                y = "PAUSE", t = I("PAUSE", null);
                                break;
                            case "SCALE":
                                t = I("SCALE", null, 0, e.data);
                                break;
                            case "TEARDOWN":
                                t = I("TEARDOWN", null);
                                break;
                            case "audioPlay":
                                P && P.setPlayAudio(!!e.data);
                                break;
                            case "volume":
                                P && P.setVolume(e.data);
                                break;
                            case "audioSamplingRate":
                                break;
                            case "startLocalRecord":
                                M = new V,
                                    function(e, t) {
                                        var n = parseInt(t) || 500;
                                        M.postMessage({
                                            type: "init",
                                            options: {
                                                singleSize: 1048576 * n,
                                                nameOptions: {
                                                    namedBy: "date",
                                                    nameFormat: ["ymd_his"]
                                                },
                                                limitOptions: {
                                                    limitBy: "count",
                                                    count: 10
                                                }
                                            }
                                        }), M.onMessage = function(t) {
                                            switch (t.type) {
                                                case "pendding":
                                                    break;
                                                case "download":
                                                    ! function(e, t) {
                                                        var n = new Blob([t]),
                                                            r = document.createElement("a");
                                                        r.href = URL.createObjectURL(n), r.download = e + ".mp4", r.click(), URL.revokeObjectURL(r.href), r = null, t = null
                                                    }(e, t.data.buffer);
                                                    break;
                                                case "closed":
                                                    M = null, R = !1
                                            }
                                        }
                                    }(e.data.name, e.data.size), R = !0;
                                break;
                            case "stopLocalRecord":
                                M.postMessage({
                                    type: "close"
                                });
                                break;
                            default:
                                s.h.log("未知指令: " + e.command)
                        }
                        "" != t && F(t)
                    },
                    setLiveMode: function(e) {},
                    setPlayMode: function(e) {},
                    setRTSPURL: function(e) {
                        _ = e
                    },
                    setCallback: function(e, t) {
                        switch (e) {
                            case "GetFrameRate":
                                x = t;
                                break;
                            default:
                                P.setCallback(e, t)
                        }
                        "Error" === e && (m = t)
                    },
                    setUserInfo: function(e, t) {
                        A.username = e, A.passWord = t
                    },
                    capture: function(e, t) {
                        P.capture(e, t)
                    },
                    setLessRate: function(e) {},
                    FrameDataCallBack: function(e, t, n, r, i, a) {
                        P && P.FrameDataCallBack(e, t, n, r, i, a)
                    },
                    setDecryptionResult: function(e, t, n) {
                        P && P.DecryptionResultCallBack(e, t, n)
                    },
                    openIVS: function() {
                        P.OpenIVSDraw()
                    },
                    closeIVS: function() {
                        P.CloseIVSDraw()
                    },
                    setIVSData: function(e, t, n, r) {
                        P.IVSDataCallBack(e, t, n, r)
                    },
                    drawIVSData: function(e) {
                        P.DrawIVSDataCallBack(e)
                    },
                    setIVSCanvasSize: function(e, t) {
                        P.setIVSCanvasSize(e, t)
                    }
                }, new E
            }(e)
        };

    function H(e) {
        return (H = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
            return typeof e
        } : function(e) {
            return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        })(e)
    }

    function q(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }))), n.push.apply(n, r)
        }
        return n
    }

    function Y(e, t, n) {
        return (t = function(e) {
            var t = function(e, t) {
                if ("object" !== H(e) || null === e) return e;
                var n = e[Symbol.toPrimitive];
                if (void 0 !== n) {
                    var r = n.call(e, "string");
                    if ("object" !== H(r)) return r;
                    throw new TypeError("@@toPrimitive must return a primitive value.")
                }
                return String(e)
            }(e);
            return "symbol" === H(t) ? t : String(t)
        }(t)) in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n, e
    }
    window.m_nModuleInitialized = !1, window.Module || (window.Module = {}), Module.onRuntimeInitialized = function() {
        window.m_nModuleInitialized = !0
    };
    var W = function(e) {
        this.wsURL = e.wsURL, this.rtspURL = e.rtspURL, this.isTalkService = e.isTalkService, this.isPlayback = !!e.isPlayback, this.ws = null, this.decodeMode = e.decodeMode, this.useH265MSE = !e.hasOwnProperty("useH265MSE") || !!e.useH265MSE, this.useH264MSE = !e.hasOwnProperty("useH264MSE") || !!e.useH264MSE, this.lessRateCanvas = e.lessRateCanvas || !1, this.nPlayPort = "", this.events = function(e) {
            for (var t = 1; t < arguments.length; t++) {
                var n = null != arguments[t] ? arguments[t] : {};
                t % 2 ? q(Object(n), !0).forEach((function(t) {
                    Y(e, t, n[t])
                })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : q(Object(n)).forEach((function(t) {
                    Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
                }))
            }
            return e
        }({
            ResolutionChanged: function() {},
            PlayStart: function() {},
            DecodeStart: function() {},
            UpdateCanvas: function() {},
            GetFrameRate: function() {},
            FrameTypeChange: function() {},
            Error: function() {},
            MSEResolutionChanged: function() {},
            audioChange: function() {},
            WorkerReady: function() {},
            IvsDraw: function() {},
            FileOver: function() {},
            Waiting: function() {},
            UpdatePlayingTime: function() {}
        }, e.events), this.username = e.username, this.password = e.password
    };
    W.prototype = {
        init: function(e, t, n) {
            for (var r in this.ws = new z(this.wsURL, this.rtspURL), this.ws.init({
                    canvasElem: e,
                    videoElem: t,
                    ivsCanvasElem: n,
                    isPlayback: this.isPlayback,
                    useH265MSE: this.useH265MSE,
                    useH264MSE: this.useH264MSE
                }), this.nPlayPort = this.ws.nPlayPort, this.ws.setLiveMode(this.decodeMode), this.ws.setUserInfo(this.username, this.password), this.ws.setPlayMode(this.isPlayback), this.ws.setLessRate(this.lessRateCanvas), this.events) this.ws.setCallback(r, this.events[r]);
            this.events = null
        },
        connect: function() {
            this.ws && this.ws.connect()
        },
        play: function() {
            this.controlPlayer("PLAY")
        },
        pause: function() {
            this.controlPlayer("PAUSE")
        },
        stop: function() {
            this.controlPlayer("TEARDOWN")
        },
        close: function() {
            this.ws && (this.ws.disconnect(), this.ws = null)
        },
        playByTime: function(e) {
            this.controlPlayer("PLAY", "video", e)
        },
        playSpeed: function(e) {
            this.controlPlayer("PAUSE"), this.controlPlayer("PLAY_SPEED", e)
        },
        playRewind: function() {},
        audioPlay: function() {
            this.controlPlayer("audioPlay", "start")
        },
        audioStop: function() {
            this.controlPlayer("audioPlay", "stop")
        },
        setAudioSamplingRate: function(e) {
            this.controlPlayer("audioSamplingRate", e)
        },
        setAudioVolume: function(e) {
            this.controlPlayer("audioPlay", e)
        },
        startLocalRecord: function(e, t) {
            this.controlPlayer("startLocalRecord", {
                name: e,
                size: t
            })
        },
        stopLocalRecord: function() {
            this.controlPlayer("stopLocalRecord")
        },
        openIVS: function() {
            this.ws && this.ws.openIVS()
        },
        closeIVS: function() {
            this.ws && this.ws.closeIVS()
        },
        setIVSData: function(e, t, n, r) {
            this.ws && this.ws.setIVSData(e, t, n, r)
        },
        drawIVSData: function(e) {
            this.ws && this.ws.drawIVSData(e)
        },
        setIVSCanvasSize: function(e, t) {
            this.ws && this.ws.setIVSCanvasSize(e, t)
        },
        controlPlayer: function(e, t, n) {
            var r;
            r = "video" === t ? {
                command: e,
                range: n || 0
            } : {
                command: e,
                data: t
            }, "PLAY_SPEED" === e && (r = {
                command: e,
                speed: t
            }), this.ws && this.ws.controlPlayer(r)
        },
        setPlayMode: function(e) {
            this.ws && this.ws.setLiveMode(e)
        },
        setPlayPath: function(e) {
            this.ws && this.ws.setRTSPURL(e)
        },
        capture: function(e, t) {
            this.ws && this.ws.capture(e, t)
        },
        setFrameData: function(e, t, n, r, i, a) {
            this.ws && this.ws.FrameDataCallBack(e, t, n, r, i, a)
        },
        setDecryptionResult: function(e, t, n) {
            this.ws && this.ws.setDecryptionResult(e, t, n)
        },
        talk: function(e) {
            if ("on" === e) {
                for (var t in this.ws = new z(this.wsURL, this.rtspURL, {
                        isTalkService: this.isTalkService
                    }), this.ws.init({
                        useH265MSE: this.useH265MSE,
                        useH264MSE: this.useH264MSE
                    }), this.nPlayPort = this.ws.nPlayPort, this.events) this.ws.setCallback(t, this.events[t]);
                this.events = null, this.connect()
            } else this.close()
        },
        on: function(e, t) {
            this.events[e] = t
        }
    }, t.default = W
}]).default;