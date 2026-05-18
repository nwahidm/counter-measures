(() => {
    "use strict";
    var e, t, s = {
            312: () => {
                const e = {
                    websocketPorts: {
                        realmonitor: "realmonitor-websocket",
                        playback: "playback-websocket",
                        realmonitor_ws: "9100",
                        playback_ws: "9320"
                    },
                    errorVideoInfo: {
                        101: "播放延时大于8s",
                        201: "当前音频无法播放",
                        202: "websocket连接错误",
                        203: "文件播放完成",
                        401: "该用户无操作权限",
                        404: "请求超时或未找到",
                        405: "播放超时",
                        406: "视频流类型解析失败，请检查通道配置",
                        407: "请求超时",
                        457: "时间设置错误",
                        503: "SETUP服务不可用",
                        504: "对讲失败",
                        701: "Chrome版本低，请升级到最新的Chrome版本",
                        702: "Firefox版本低，请升级到最新的Firefox版本",
                        703: "Edge版本低，请升级到最新的Edge版本",
                        defaultErrorMsg: "播放失败，请检查配置"
                    },
                    errorInfo: {
                        101: "所选通道离线，无法播放",
                        201: "所选通道未查询到录像文件",
                        301: "对讲中，请勿重复开启音频",
                        302: "其他设备对讲中，无法开启音频",
                        303: "其他设备对讲中，无法开启对讲"
                    }
                };

                function t(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                const s = function() {
                    function e(t) {
                        ! function(e, t) {
                            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                        }(this, e), this.$el = null, this.canvasElem = null, this.videoElem = null, this.domId = t.wrapperDomId + "-" + t.index, this.wsPlayer = t.wsPlayer, this.index = t.index, this.firstTime = 0, this.isAudioPlay = !1, this.speed = 1
                    }
                    var s, i;
                    return s = e, (i = [{
                        key: "initDom",
                        value: function() {
                            var e = this.getTemplate(),
                                t = $(e);
                            this.wsPlayer.$wrapper.append(t[0]), this.$el = $("#" + this.domId), this.canvasElem = document.getElementById(this.canvasId) || {}, this.ivsCanvasElem = document.getElementById(this.ivsCanvasId) || {}, this.pztCanvasElem = document.getElementById(this.pztCanvasId) || {}, this.videoElem = document.getElementById(this.videoId);
                            var s = this.wsPlayer.config.showIcons || {};
                            s.streamChangeSelect || $(".select-container", this.$el).css({
                                display: "none"
                            }), s.talkIcon && "real" === this.wsPlayer.type || $(".talk-icon", this.$el).css({
                                display: "none"
                            }), s.audioIcon || $(".audio-icon", this.$el).css({
                                display: "none"
                            }), s.snapshotIcon || $(".capture-icon", this.$el).css({
                                display: "none"
                            }), s.localRecordIcon || $(".record-icon", this.$el).css({
                                display: "none"
                            }), s.closeIcon || $(".close-icon", this.$el).css({
                                display: "none"
                            })
                        }
                    }, {
                        key: "initMouseEvent",
                        value: function() {
                            var e = this;
                            this.$el.click((function(t) {
                                e.wsPlayer.setSelectIndex(e.index), e.$el.siblings().removeClass("selected").addClass("unselected"), e.$el.removeClass("unselected").addClass("selected")
                            })), this.$el.dblclick((function(t) {
                                1 !== e.wsPlayer.options.maxNum && (e.wsPlayer.$el.hasClass("fullplayer") ? e.wsPlayer.setPlayerNum(e.wsPlayer.beforeShowNum) : (e.wsPlayer.beforeShowNum = e.wsPlayer.showNum, e.wsPlayer.setPlayerNum(1)), e.wsPlayer.setSelectIndex(e.index), e.$el.siblings().removeClass("selected").addClass("unselected"), e.$el.removeClass("unselected").addClass("selected"))
                            })), $(".audio-icon", this.$el).click((function(t) {
                                if (e.wsPlayer.isTalking) e.wsPlayer.sendErrorMessage(e.isTalking ? "301" : "302");
                                else {
                                    if (e.isAudioPlay) e.player.setAudioVolume(0), $(t.target).removeClass("on").addClass("off");
                                    else {
                                        if (e.player.isPlayback && (e.speed < .5 || e.speed > 2)) return;
                                        e.player.setAudioVolume(1), e.resumeAudio(), $(t.target).removeClass("off").addClass("on")
                                    }
                                    e.isAudioPlay = !e.isAudioPlay
                                }
                            })), $(".talk-icon", this.$el).click((function(t) {
                                e.wsPlayer.isTalking && !e.isTalking ? e.wsPlayer.sendErrorMessage("303") : e.isTalking ? e.stopTalk() : (e.resumeAudio(), e.wsPlayer.talkIndex = e.index, e.wsPlayer.__startTalk(e.options.channelData))
                            })), $(".capture-icon", this.$el).click((function(t) {
                                e.capturePic()
                            })), $(".close-icon", this.$el).click((function(t) {
                                e.close()
                            })), $(".record-icon", this.$el).click((function(t) {
                                var s = (e.options.channelData || {}).name || "录像";
                                e.isRecording ? (e.isRecording = !1, e.player.stopLocalRecord(), $(t.target).removeClass("recording")) : "playing" === e.status && (e.isRecording = !0, e.player.startLocalRecord("".concat(s, "-").concat(Date.now()), 50), $(t.target).addClass("recording"))
                            }))
                        }
                    }, {
                        key: "resumeAudio",
                        value: function() {
                            if (window.wsAudioPlayer) window.wsAudioPlayer.manualResume("fromTalk");
                            else var e = setInterval((function() {
                                window.wsAudioPlayer && (window.wsAudioPlayer.manualResume("fromTalk"), clearInterval(e))
                            }), 100)
                        }
                    }, {
                        key: "setStatus",
                        value: function() {}
                    }, {
                        key: "play",
                        value: function() {
                            this.player.play(), this.setStatus("playing"), $(".ws-record-play").css({
                                display: "none"
                            }), $(".ws-record-pause").css({
                                display: "block"
                            })
                        }
                    }, {
                        key: "pause",
                        value: function() {
                            this.player.pause(), this.setStatus("pause"), $(".ws-record-pause").css({
                                display: "none"
                            }), $(".ws-record-play").css({
                                display: "block"
                            })
                        }
                    }, {
                        key: "close",
                        value: function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                            this.player && window.wsPlayerManager.unbindPlayer(this.player.nPlayPort), this.wsPlayer.videoClosed(this.index, e), this.setDomVisible($(".play-pause-wrapper", this.$el), !1), this.videoElem.style.display = "none", this.canvasElem.style.display = "none", this.isTalking && this.stopTalk(), this.speed = 1, this.index === this.wsPlayer.selectIndex && (this.wsPlayer.setTimeLine([]), this.wsPlayer.__setPlaySpeed(), $(".ws-record-play").css({
                                display: "block"
                            }), $(".ws-record-pause").css({
                                display: "none"
                            })), this.isRecording && (this.isRecording = !1, this.player.stopLocalRecord(), $(".record-icon", this.$el).removeClass("recording")), this.wsPlayer.config.openIvs && this.player && this.player.closeIVS(), this.spinner && this.spinner.stop(), this.player && this.player.stop(), this.player && this.player.close(), e || (this.player = null, this.options = null), this.setStatus("closed")
                        }
                    }, {
                        key: "capturePic",
                        value: function() {
                            var e = (this.options.channelData || {}).name || "抓图";
                            this.player.capture("".concat(e, "-").concat(Date.now()))
                        }
                    }, {
                        key: "setDomVisible",
                        value: function(e, t) {
                            e && e.css({
                                visibility: t ? "visible" : "hidden"
                            })
                        }
                    }, {
                        key: "updateAdapter",
                        value: function(e) {
                            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                                s = t.width / t.height,
                                i = "video" === (t.decodeMode || this.decodeMode) ? this.videoElem : this.canvasElem,
                                n = i.parentNode;
                            t.decodeMode ? (this.decodeMode = t.decodeMode, this.width = t.width, this.height = t.height) : s = this.width / this.height;
                            var r = "100%",
                                a = "100%";
                            if ("selfAdaption" === e) {
                                var o = n.offsetHeight,
                                    l = n.offsetWidth,
                                    c = l / o;
                                s > c ? a = "".concat(l / s, "px") : s < c && (r = "".concat(o * s, "px")), $(i).css({
                                    width: r,
                                    height: a,
                                    "object-fit": "contain"
                                }), $(this.ivsCanvasElem).css({
                                    width: r,
                                    height: a,
                                    "object-fit": "contain"
                                }), $(this.pztCanvasElem).css({
                                    width: r,
                                    height: a,
                                    "object-fit": "contain"
                                })
                            } else $(i).css({
                                width: r,
                                height: a,
                                "object-fit": "fill"
                            }), $(this.ivsCanvasElem).css({
                                width: r,
                                height: a,
                                "object-fit": "fill"
                            }), $(this.pztCanvasElem).css({
                                width: r,
                                height: a,
                                "object-fit": "fill"
                            });
                            this.player && (this.ivsCanvasElem.width = i.offsetWidth, this.ivsCanvasElem.height = i.offsetHeight, this.player.setIVSCanvasSize(i.offsetWidth, i.offsetHeight), this.pztCanvasElem.width = i.offsetWidth, this.pztCanvasElem.height = i.offsetHeight)
                        }
                    }]) && t(s.prototype, i), e
                }();
                var i = function() {
                        return (i = Object.assign || function(e) {
                            for (var t, s = 1, i = arguments.length; s < i; s++)
                                for (var n in t = arguments[s]) Object.prototype.hasOwnProperty.call(t, n) && (e[n] = t[n]);
                            return e
                        }).apply(this, arguments)
                    },
                    n = {
                        lines: 12,
                        length: 7,
                        width: 5,
                        radius: 10,
                        scale: 1,
                        corners: 1,
                        color: "#000",
                        fadeColor: "transparent",
                        animation: "spinner-line-fade-default",
                        rotate: 0,
                        direction: 1,
                        speed: 1,
                        zIndex: 2e9,
                        className: "spinner",
                        top: "50%",
                        left: "50%",
                        shadow: "0 0 1px transparent",
                        position: "absolute"
                    },
                    r = function() {
                        function e(e) {
                            void 0 === e && (e = {}), this.opts = i(i({}, n), e)
                        }
                        return e.prototype.spin = function(e) {
                            return this.stop(), this.el = document.createElement("div"), this.el.className = this.opts.className, this.el.setAttribute("role", "progressbar"), a(this.el, {
                                    position: this.opts.position,
                                    width: 0,
                                    zIndex: this.opts.zIndex,
                                    left: this.opts.left,
                                    top: this.opts.top,
                                    transform: "scale(" + this.opts.scale + ")"
                                }), e && e.insertBefore(this.el, e.firstChild || null),
                                function(e, t) {
                                    var s = Math.round(t.corners * t.width * 500) / 1e3 + "px",
                                        i = "none";
                                    !0 === t.shadow ? i = "0 2px 4px #000" : "string" == typeof t.shadow && (i = t.shadow);
                                    for (var n = function(e) {
                                            for (var t = /^\s*([a-zA-Z]+\s+)?(-?\d+(\.\d+)?)([a-zA-Z]*)\s+(-?\d+(\.\d+)?)([a-zA-Z]*)(.*)$/, s = [], i = 0, n = e.split(","); i < n.length; i++) {
                                                var r = n[i].match(t);
                                                if (null !== r) {
                                                    var a = +r[2],
                                                        o = +r[5],
                                                        l = r[4],
                                                        c = r[7];
                                                    0 !== a || l || (l = c), 0 !== o || c || (c = l), l === c && s.push({
                                                        prefix: r[1] || "",
                                                        x: a,
                                                        y: o,
                                                        xUnits: l,
                                                        yUnits: c,
                                                        end: r[8]
                                                    })
                                                }
                                            }
                                            return s
                                        }(i), r = 0; r < t.lines; r++) {
                                        var c = ~~(360 / t.lines * r + t.rotate),
                                            d = a(document.createElement("div"), {
                                                position: "absolute",
                                                top: -t.width / 2 + "px",
                                                width: t.length + t.width + "px",
                                                height: t.width + "px",
                                                background: o(t.fadeColor, r),
                                                borderRadius: s,
                                                transformOrigin: "left",
                                                transform: "rotate(" + c + "deg) translateX(" + t.radius + "px)"
                                            }),
                                            p = r * t.direction / t.lines / t.speed;
                                        p -= 1 / t.speed;
                                        var u = a(document.createElement("div"), {
                                            width: "100%",
                                            height: "100%",
                                            background: o(t.color, r),
                                            borderRadius: s,
                                            boxShadow: l(n, c),
                                            animation: 1 / t.speed + "s linear " + p + "s infinite " + t.animation
                                        });
                                        d.appendChild(u), e.appendChild(d)
                                    }
                                }(this.el, this.opts), this
                        }, e.prototype.stop = function() {
                            return this.el && ("undefined" != typeof requestAnimationFrame ? cancelAnimationFrame(this.animateId) : clearTimeout(this.animateId), this.el.parentNode && this.el.parentNode.removeChild(this.el), this.el = void 0), this
                        }, e
                    }();

                function a(e, t) {
                    for (var s in t) e.style[s] = t[s];
                    return e
                }

                function o(e, t) {
                    return "string" == typeof e ? e : e[t % e.length]
                }

                function l(e, t) {
                    for (var s = [], i = 0, n = e; i < n.length; i++) {
                        var r = n[i],
                            a = c(r.x, r.y, t);
                        s.push(r.prefix + a[0] + r.xUnits + " " + a[1] + r.yUnits + r.end)
                    }
                    return s.join(", ")
                }

                function c(e, t, s) {
                    var i = s * Math.PI / 180,
                        n = Math.sin(i),
                        r = Math.cos(i);
                    return [Math.round(1e3 * (e * r + t * n)) / 1e3, Math.round(1e3 * (-e * n + t * r)) / 1e3]
                }

                function d(e) {
                    return (d = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
                    })(e)
                }

                function p(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }

                function u(e, t, s) {
                    return (u = "undefined" != typeof Reflect && Reflect.get ? Reflect.get : function(e, t, s) {
                        var i = function(e, t) {
                            for (; !Object.prototype.hasOwnProperty.call(e, t) && null !== (e = v(e)););
                            return e
                        }(e, t);
                        if (i) {
                            var n = Object.getOwnPropertyDescriptor(i, t);
                            return n.get ? n.get.call(s) : n.value
                        }
                    })(e, t, s || e)
                }

                function h(e, t) {
                    return (h = Object.setPrototypeOf || function(e, t) {
                        return e.__proto__ = t, e
                    })(e, t)
                }

                function y(e, t) {
                    if (t && ("object" === d(t) || "function" == typeof t)) return t;
                    if (void 0 !== t) throw new TypeError("Derived constructors may only return object or undefined");
                    return function(e) {
                        if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                        return e
                    }(e)
                }

                function v(e) {
                    return (v = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
                        return e.__proto__ || Object.getPrototypeOf(e)
                    })(e)
                }
                var f = window.PlayerControl;
                const m = function(t) {
                    ! function(e, t) {
                        if ("function" != typeof t && null !== t) throw new TypeError("Super expression must either be null or a function");
                        e.prototype = Object.create(t && t.prototype, {
                            constructor: {
                                value: e,
                                writable: !0,
                                configurable: !0
                            }
                        }), t && h(e, t)
                    }(l, t);
                    var s, i, n, a, o = (n = l, a = function() {
                        if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                        if (Reflect.construct.sham) return !1;
                        if ("function" == typeof Proxy) return !0;
                        try {
                            return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], (function() {}))), !0
                        } catch (e) {
                            return !1
                        }
                    }(), function() {
                        var e, t = v(n);
                        if (a) {
                            var s = v(this).constructor;
                            e = Reflect.construct(t, arguments, s)
                        } else e = t.apply(this, arguments);
                        return y(this, e)
                    });

                    function l(e) {
                        var t;
                        return function(e, t) {
                            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                        }(this, l), (t = o.call(this, e)).canvasId = "".concat(t.domId, "-livecanvas"), t.ivsCanvasId = "".concat(t.domId, "-ivs-livecanvas"), t.pztCanvasId = "".concat(t.domId, "-pzt-livecanvas"), t.videoId = "".concat(t.domId, "-liveVideo"), t.initDom(), t.defaultStatus = $(".default-status", t.$el), t.error = $(".error", t.$el), t.controller = $(".player-control", t.$el), t.initMouseEvent(), t.setStatus("created"), t
                    }
                    return s = l, (i = [{
                        key: "getTemplate",
                        value: function() {
                            return '\n        <div id="'.concat(this.domId, '" class="wsplayer-item wsplayer-item-').concat(this.index, " ").concat(0 === this.index ? "selected" : "unselected", '">\n            <div class="ws-full-content ws-flex">\n                <canvas id="').concat(this.canvasId, '" class="kind-stream-canvas" kind-channel-id="0" width="800" height="600"></canvas>\n                <video id="').concat(this.videoId, '" class="kind-stream-canvas" kind-channel-id="0" style="display:none" width="800" height="600"></video>\n                <canvas id="').concat(this.ivsCanvasId, '" class="kind-stream-canvas" style="position: absolute" kind-channel-id="0" width="800" height="600"></canvas>\n                <canvas id="').concat(this.pztCanvasId, '" class="kind-stream-canvas" style="display: none; position: absolute" kind-channel-id="0" width="800" height="600"></canvas>\n            </div>\n            <div class="default-status">\n                <img src="./static/WSPlayer/icon/default.png" alt="">\n            </div>\n            <div class="player-control top-control-bar">\n                <div class="stream">\n                    <div class="select-container">\n                        <div class="select-show select">\n                            <div class="code-stream">主码流</div>\n                            \x3c!-- 下拉箭头 --\x3e\n                            <img src="./static/WSPlayer/icon/spread.png" />\n                        </div>\n                        <div class="stream-type" style="display: none">\n                            <ul class="select-ul">\n                                <li optionValue="主码流" stream-type="1" class="stream-type-item">主码流</li>\n                                <li optionValue="辅码流1" stream-type="2" class="stream-type-item">辅码流1</li>\n                                <li optionValue="辅码流2" stream-type="3" class="stream-type-item">辅码流2</li>\n                            </ul>\n                        </div>\n                    </div>\n                    <span class="stream-info"></span>\n                </div>\n                <div class="opt-icons">\n                    <div class="opt-icon talk-icon off" title="对讲"></div>\n                    <div class="opt-icon record-icon" title="Rekam"></div>\n                    <div class="opt-icon audio-icon off" title="Aktifkan Suara"></div>\n                    <div class="opt-icon capture-icon" title="Ambil Gambar"></div>\n                    <div class="opt-icon close-icon" title="关闭"></div>\n                </div>\n            </div>\n            <div class="ws-talking">...</div>\n            <div class="error">\n                <div class="error-message"></div>\n            </div>\n        </div>\n        ')
                        }
                    }, {
                        key: "initMouseEvent",
                        value: function() {
                            var e = this;
                            u(v(l.prototype), "initMouseEvent", this).call(this);
                            var t = this;
                            this.hideTimer = null, this.$el.on("mouseenter mousemove", (function(t) {
                                ["created", "closed"].includes(e.status) || e.setDomVisible($(".player-control", $("#".concat(e.domId))), !0), "playing" !== e.status && "error" !== e.status || e.hideTimer && clearTimeout(e.hideTimer)
                            })), this.$el.on("mouseleave", (function(t) {
                                e.hideTimer = setTimeout((function() {
                                    $(".stream-type", e.$el).hide(), e.setDomVisible($(".player-control", $("#".concat(e.domId))), !1), e.streamSelectShow = !1
                                }), 300)
                            })), this.streamSelectShow = !1, $(".select", this.$el).click((function(t) {
                                e.streamSelectShow ? ($(".stream-type", e.$el).hide(), e.streamSelectShow = !1) : ($(".stream-type", e.$el).show(), e.streamSelectShow = !0)
                            })), $(".stream-type", this.$el).click((function(e) {
                                var s = e.target.getAttribute("stream-type");
                                t.streamType !== s && t.options && t.wsPlayer.changeStreamType(t.options.channelData, s, t.index)
                            }))
                        }
                    }, {
                        key: "setStreamType",
                        value: function(e) {
                            this.streamType = e;
                            var t = $(".stream-type .select-ul")[this.index].children[e - 1];
                            $(".code-stream", this.$el).text($(t).attr("optionValue")), $(t).addClass("stream-type-select").siblings().removeClass("stream-type-select")
                        }
                    }, {
                        key: "setStatus",
                        value: function(t, s) {
                            switch (this.wsPlayer.sendMessage("statusChanged", {
                                    status: t,
                                    windowIndex: this.index
                                }), this.status = t, this.status) {
                                case "created":
                                case "closed":
                                    this.setDomVisible(this.defaultStatus, !0), this.setDomVisible(this.error, !1), this.setDomVisible(this.controller, !1), this.videoElem.src = "", $(".audio-icon", this.$el).removeClass("on").addClass("off");
                                    break;
                                case "ready":
                                case "playing":
                                case "pause":
                                    this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1);
                                    break;
                                case "error":
                                    this.setDomVisible(this.defaultStatus, !1), $(".error-message", this.$el).text(e.errorVideoInfo[s.errorCode] ? e.errorVideoInfo[s.errorCode] : e.errorVideoInfo.defaultErrorMsg), this.setDomVisible(this.error, !0)
                            }
                        }
                    }, {
                        key: "init",
                        value: function(e) {
                            window.m_nModuleInitialized ? (this.options = e, this.player && (this.isAudioPlay && $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.close(!0)), this.spinner && this.spinner.stop(), this.spinner = new r({
                                color: "#ffffff"
                            }).spin(this.$el[0]), this.setStatus("ready"), this.setStreamType(e.streamType), this.createPlayer(e)) : console.error("解码库未初始化完成，请稍后播放！")
                        }
                    }, {
                        key: "startPlay",
                        value: function(e, t) {
                            var s = this;
                            "video" === t.decodeMode ? (s.videoElem.style.display = "", s.canvasElem.style.display = "none") : (s.videoElem.style.display = "none", s.canvasElem.style.display = ""), s.updateAdapter(e.playerAdapter, t), this.width = t.width, this.height = t.height, $(".stream-info", $("#".concat(s.domId))).text("".concat(t.encodeMode ? "".concat(t.encodeMode, ", ") : "").concat(t.width ? "".concat(t.width, "*") : "").concat(t.height ? t.height : ""))
                        }
                    }, {
                        key: "createPlayer",
                        value: function(e) {
                            var t = this,
                                s = this.wsPlayer.config,
                                i = s.useH264MSE,
                                n = s.useH265MSE;
                            this.player = new f({
                                wsURL: e.wsURL,
                                rtspURL: e.rtspURL,
                                useH264MSE: i,
                                useH265MSE: n,
                                events: {
                                    PlayStart: function(e) {
                                        console.log(e), t.spinner.stop(), t.setStatus("playing")
                                    },
                                    DecodeStart: function(s) {
                                        console.log(s), t.startPlay(e, s)
                                    },
                                    GetFrameRate: function(s) {
                                        console.log("GetFrameRate", s), t.startPlay(e, s)
                                    },
                                    Error: function(e) {
                                        if (t.player && t.player.ws && e.symbol === t.player.ws.symbol) {
                                            if ("408" === e.errorCode) return void("2" === t.streamType && t.wsPlayer.changeStreamType(t.options.channelData, "1", t.index));
                                            t.spinner.stop(), console.log("Error: " + JSON.stringify(e)), t.setStatus("error", e)
                                        }
                                    },
                                    FileOver: function(e) {
                                        console.log("FileOver: ", e)
                                    },
                                    UpdatePlayingTime: function(e) {}
                                }
                            }), this.player.init(this.canvasElem, this.videoElem, this.ivsCanvasElem), this.player.connect(), this.wsPlayer.config.openIvs && this.player.openIVS(), window.wsPlayerManager.bindPlayer(this.player.nPlayPort, this.player)
                        }
                    }, {
                        key: "startTalk",
                        value: function(e) {
                            if (window.m_nModuleInitialized) {
                                this.wsPlayer.isTalking = !0, this.isTalking = !0, $(".talk-icon", this.$el).removeClass("off").addClass("on");
                                var t = this,
                                    s = this.wsPlayer.config,
                                    i = s.useH264MSE,
                                    n = s.useH265MSE;
                                this.talkPlayer = new f({
                                    rtspURL: e.rtspURL,
                                    wsURL: this.wsPlayer.__getWSUrl(e.rtspURL, e.serverIp),
                                    isTalkService: !0,
                                    useH264MSE: i,
                                    useH265MSE: n,
                                    events: {
                                        Error: function(e) {
                                            "504" === e.errorCode && (t.stopTalk(), t.wsPlayer.sendMessage("errorInfo", e))
                                        }
                                    }
                                }), this.talkPlayer.talk("on"), window.wsPlayerManager.bindPlayer(this.talkPlayer.nPlayPort, this.talkPlayer), $(".ws-talking", this.$el).css({
                                    visibility: "visible"
                                }), this.player.setAudioVolume(0), $(".audio-icon", this.$el).removeClass("on").addClass("off")
                            } else console.error("解码库未初始化完成，请稍后对讲！")
                        }
                    }, {
                        key: "stopTalk",
                        value: function() {
                            this.talkPlayer && window.wsPlayerManager.unbindPlayer(this.talkPlayer.nPlayPort), this.isTalking && (this.wsPlayer.isTalking = !1, this.isTalking = !1), this.talkPlayer && (this.talkPlayer.talk("off"), this.talkPlayer = null), $(".talk-icon", this.$el).removeClass("on").addClass("off"), $(".ws-talking", this.$el).css({
                                visibility: "hidden"
                            })
                        }
                    }]) && p(s.prototype, i), l
                }(s);

                function w(e) {
                    return (w = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
                        return typeof e
                    } : function(e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
                    })(e)
                }

                function g(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }

                function P(e, t, s) {
                    return (P = "undefined" != typeof Reflect && Reflect.get ? Reflect.get : function(e, t, s) {
                        var i = function(e, t) {
                            for (; !Object.prototype.hasOwnProperty.call(e, t) && null !== (e = S(e)););
                            return e
                        }(e, t);
                        if (i) {
                            var n = Object.getOwnPropertyDescriptor(i, t);
                            return n.get ? n.get.call(s) : n.value
                        }
                    })(e, t, s || e)
                }

                function b(e, t) {
                    return (b = Object.setPrototypeOf || function(e, t) {
                        return e.__proto__ = t, e
                    })(e, t)
                }

                function k(e, t) {
                    if (t && ("object" === w(t) || "function" == typeof t)) return t;
                    if (void 0 !== t) throw new TypeError("Derived constructors may only return object or undefined");
                    return function(e) {
                        if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                        return e
                    }(e)
                }

                function S(e) {
                    return (S = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
                        return e.__proto__ || Object.getPrototypeOf(e)
                    })(e)
                }
                var E = window.PlayerControl;
                const x = function(t) {
                    ! function(e, t) {
                        if ("function" != typeof t && null !== t) throw new TypeError("Super expression must either be null or a function");
                        e.prototype = Object.create(t && t.prototype, {
                            constructor: {
                                value: e,
                                writable: !0,
                                configurable: !0
                            }
                        }), t && b(e, t)
                    }(l, t);
                    var s, i, n, a, o = (n = l, a = function() {
                        if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
                        if (Reflect.construct.sham) return !1;
                        if ("function" == typeof Proxy) return !0;
                        try {
                            return Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], (function() {}))), !0
                        } catch (e) {
                            return !1
                        }
                    }(), function() {
                        var e, t = S(n);
                        if (a) {
                            var s = S(this).constructor;
                            e = Reflect.construct(t, arguments, s)
                        } else e = t.apply(this, arguments);
                        return k(this, e)
                    });

                    function l(e) {
                        var t;
                        return function(e, t) {
                            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                        }(this, l), (t = o.call(this, e)).speed = 1, t.canvasId = "".concat(t.domId, "-recordcanvas"), t.ivsCanvasId = "".concat(t.domId, "-ivs-livecanvas"), t.videoId = "".concat(t.domId, "-recordVideo"), t.curTimestamp = 0, t.initDom(), t.defaultStatus = $(".default-status", t.$el), t.error = $(".error", t.$el), t.controller = $(".player-control", t.$el), t.timeInfo = $(".time-info", t.$el), t.initMouseEvent(), t.setStatus("created"), t
                    }
                    return s = l, (i = [{
                        key: "getTemplate",
                        value: function() {
                            return '\n        <div id="'.concat(this.domId, '" class="wsplayer-item wsplayer-item-').concat(this.index, " ").concat(0 === this.index ? "selected" : "unselected", '">\n            <canvas id="').concat(this.canvasId, '" class="kind-stream-canvas" kind-channel-id="0" width="800" height="600"></canvas>\n            <video id="').concat(this.videoId, '" class="kind-stream-canvas" kind-channel-id="0" style="display:none" width="800" height="600"></video>\n            <canvas id="').concat(this.ivsCanvasId, '" class="kind-stream-canvas" style="position: absolute" kind-channel-id="0" width="800" height="600"></canvas>\n            <div class="default-status">\n                <img src="./static/WSPlayer/icon/default.png" alt="">\n            </div>\n            <div class="player-control top-control-bar">\n                <span class="stream-info"></span>\n                <div class="opt-icons">\n                    <div class="opt-icon record-icon" title="Rekam"></div>\n                    <div class="opt-icon audio-icon off"></div>\n                    <div class="opt-icon capture-icon"></div>\n                    <div class="opt-icon close-icon"></div>\n                </div>\n            </div>\n            <div class="player-control record-control-bar">\n                <div class="wsplayer-progress-bar">\n                    <div class="progress-bar_background"></div>\n                    <div class="progress-bar_hover_light"></div>\n                    <div class="progress-bar_light"></div>\n                </div>\n                <div class="record-control-left">\n                    <div class="opt-icon play-ctrl-btn play-icon play"></div>\n                    <div class="time-info"></div>/<div class="time-long"></div>\n                </div>\n                <div class="record-control-right">\n                    <div class="opt-icon close-icon"></div>\n                </div>\n            </div>\n            <div class="error">\n                <div class="error-message"></div>\n            </div>\n            <div class="play-pause-wrapper">\n                <div class="play-ctrl-btn center-play-icon"></div>\n            </div>\n        </div>\n        ')
                        }
                    }, {
                        key: "initMouseEvent",
                        value: function() {
                            var e = this;
                            P(S(l.prototype), "initMouseEvent", this).call(this), this.hideTimer = null, this.$el.on("mouseenter mousemove", (function(t) {
                                ["created", "closed"].includes(e.status) || e.setDomVisible($(".player-control", $("#".concat(e.domId))), !0), "playing" === e.status ? e.hideTimer && clearTimeout(e.hideTimer) : "ready" === e.status && e.setDomVisible(e.progressBar, !0)
                            })), this.$el.on("mouseleave", (function(t) {
                                "pause" !== e.status && (e.hideTimer = setTimeout((function() {
                                    e.setDomVisible($(".player-control", $("#".concat(e.domId))), !1)
                                }), 300))
                            })), $(".wsplayer-progress-bar", this.$el).on("mousemove", (function(t) {
                                $(".progress-bar_hover_light", e.$el).css({
                                    width: t.offsetX + "px"
                                })
                            })), $(".wsplayer-progress-bar", this.$el).on("mouseleave", (function(t) {
                                $(".progress-bar_hover_light", e.$el).css({
                                    width: 0
                                })
                            })), $(".play-ctrl-btn", this.$el).click((function(t) {
                                "playing" === e.status ? (e.pause(), $(".play-icon", e.$el).removeClass("play").addClass("pause")) : (e.play(), $(".play-icon", e.$el).removeClass("pause").addClass("play"))
                            }))
                        }
                    }, {
                        key: "setStatus",
                        value: function(t, s) {
                            switch (this.wsPlayer.sendMessage("statusChanged", {
                                    status: t,
                                    windowIndex: this.index
                                }), this.status = t, this.status) {
                                case "created":
                                case "closed":
                                    this.setDomVisible(this.defaultStatus, !0), this.setDomVisible(this.error, !1), this.setDomVisible(this.controller, !1), $(".audio-icon", this.$el).removeClass("on").addClass("off");
                                    break;
                                case "ready":
                                    this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1);
                                    break;
                                case "playing":
                                    $("#ws-record-time-box").css({
                                        visibility: "visible"
                                    }), this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1), this.setDomVisible($(".play-pause-wrapper", this.$el), !1);
                                    break;
                                case "pause":
                                    this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1), this.setDomVisible(this.controller, !1), this.setDomVisible($(".play-pause-wrapper", this.$el), !0);
                                    break;
                                case "error":
                                    this.setDomVisible(this.defaultStatus, !1), $(".error-message", this.$el).text(e.errorVideoInfo[s.errorCode] ? e.errorVideoInfo[s.errorCode] : e.errorVideoInfo.defaultErrorMsg), this.setDomVisible(this.error, !0)
                            }
                        }
                    }, {
                        key: "init",
                        value: function(e) {
                            window.m_nModuleInitialized ? (this.options = e, this.player && (this.isAudioPlay && $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.close(!0)), this.spinner && this.spinner.stop(), this.spinner = new r({
                                color: "#ffffff"
                            }).spin(this.$el[0]), this.createPlayer(e)) : console.error("解码库未初始化完成，请稍后播放！")
                        }
                    }, {
                        key: "createPlayer",
                        value: function(e) {
                            var t = this,
                                s = this.wsPlayer.config,
                                i = s.useH264MSE,
                                n = s.useH265MSE;
                            this.player = new E({
                                wsURL: e.wsURL,
                                rtspURL: e.rtspURL,
                                isPlayback: e.isPlayback,
                                useH264MSE: i,
                                useH265MSE: n,
                                events: {
                                    PlayStart: function(s) {
                                        console.log("PlayStart"), t.setStatus("playing"), e.autoPause && (t.pause(), t.setStatus("pause"))
                                    },
                                    DecodeStart: function(s) {
                                        console.log("DecodeStart", s), t.spinner.stop(), "video" === s.decodeMode ? (t.videoElem.style.display = "", t.canvasElem.style.display = "none") : (t.videoElem.style.display = "none", t.canvasElem.style.display = ""), t.updateAdapter(e.playerAdapter, s), $(".stream-info", $("#".concat(t.domId))).text(s.width ? "".concat(s.encodeMode, ", ").concat(s.width, "*").concat(s.height) : s.encodeMode)
                                    },
                                    GetFrameRate: function(e) {
                                        console.log("GetFrameRate: ", e)
                                    },
                                    Error: function(e) {
                                        if (t.player && e.symbol === t.player.ws.symbol) {
                                            if ("408" === e.errorCode) return;
                                            t.spinner.stop(), console.log("Error: " + JSON.stringify(e)), t.setStatus("error", e)
                                        }
                                    },
                                    FileOver: function(e) {
                                        console.log("回放播放完成"), t.close(), t.wsPlayer.playNextRecord(t.index)
                                    },
                                    UpdatePlayingTime: function(e) {
                                        console.log("获取视频时间信息", e), "playing" === t.status && t.wsPlayer.setPlayingTime(t.index, e)
                                    }
                                }
                            }), this.timeLong = e.endTime - e.startTime;
                            var r = this.timeLong % 60,
                                a = parseInt(this.timeLong / 60) % 60,
                                o = parseInt(this.timeLong / 3600) % 60;
                            this.timeLongStr = "".concat(o > 0 ? o + ":" : "").concat(a < 10 ? "0" + a : a, ":").concat(r < 10 ? "0" + r : r), $(".time-long", this.$el).text(this.timeLongStr), this.setStatus("ready"), this.player.init(this.canvasElem, this.videoElem, this.ivsCanvasElem), this.player.connect(), this.wsPlayer.config.openIvs && this.player.openIVS(), window.wsPlayerManager.bindPlayer(this.player.nPlayPort, this.player)
                        }
                    }, {
                        key: "playSpeed",
                        value: function(e) {
                            this.speed = e, (e < .5 || e > 2) && (this.player.setAudioVolume(0), $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.isAudioPlay = !1), this.player && this.player.playSpeed(e)
                        }
                    }]) && g(s.prototype, i), l
                }(s);

                function I(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                const C = function() {
                    function e() {
                        ! function(e, t) {
                            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                        }(this, e), this.wsPlayerList = [], this.portToPlayer = {}, window.cPlusVisibleDecCallBack = this.cPlusVisibleDecCallBack.bind(this), window.cExtraDrawDataCallBack = this.cExtraDrawDataCallBack.bind(this), window.cExtraDrawDrawCallBack = this.cExtraDrawDrawCallBack.bind(this)
                    }
                    var t, s;
                    return t = e, (s = [{
                        key: "cPlusVisibleDecCallBack",
                        value: function(e, t, s, i, n, r) {
                            this.portToPlayer[e] && this.portToPlayer[e].setFrameData(e, t, s, i, n, r)
                        }
                    }, {
                        key: "cExtraDrawDataCallBack",
                        value: function(e, t, s, i) {
                            this.portToPlayer[e] && this.portToPlayer[e].setIVSData(e, t, s, i)
                        }
                    }, {
                        key: "cExtraDrawDrawCallBack",
                        value: function(e) {
                            this.portToPlayer[e] && this.portToPlayer[e].drawIVSData(e)
                        }
                    }, {
                        key: "bindPlayer",
                        value: function(e, t) {
                            this.portToPlayer[e] || (this.portToPlayer[e] = t)
                        }
                    }, {
                        key: "unbindPlayer",
                        value: function(e) {
                            this.portToPlayer[e] = null
                        }
                    }, {
                        key: "addWSPlayer",
                        value: function(e) {
                            this.wsPlayerList.push()
                        }
                    }, {
                        key: "removeWSPlayer",
                        value: function(e) {
                            this.wsPlayerList = this.wsPlayerList.filter((function(t) {
                                return t === e
                            }))
                        }
                    }]) && I(t.prototype, s), e
                }();
                var _ = "Chrome",
                    T = "Firefox",
                    D = "Edge";

                function R(e) {
                    return "[object Object]" === toString.call(e)
                }
                const A = function() {
                        var e, t = (e = navigator.userAgent).includes("Edge") ? D : e.includes("Firefox") ? T : e.includes("Chrome") ? _ : e.includes("Safari") ? "Safari" : e.includes("compatible") && e.includes("MSIE") && e.includes("Opera") ? "IE" : e.includes("Opera") ? "Opera" : "",
                            s = navigator.userAgent.includes("x64") || navigator.userAgent.includes("x86_64") ? 64 : 32,
                            i = function(e) {
                                return navigator.userAgent.split(e)[1].split(".")[0].slice(1)
                            }(t),
                            n = !1,
                            r = 0;
                        switch (t) {
                            case _:
                                n = i >= 91 && 64 === s, r = 701;
                                break;
                            case T:
                                n = i >= 97, r = 702;
                                break;
                            case D:
                                n = i >= 91, r = 703;
                                break;
                            default:
                                n = 0
                        }
                        return {
                            isVersionCompliance: n,
                            browserType: t,
                            errorCode: r
                        }
                    },
                    M = function e() {
                        for (var t = {}, s = 0; s < arguments.length; s++) {
                            var i = arguments[s];
                            for (var n in i) {
                                var r = i[n];
                                R(r) ? t[n] = e(r) : t[n] = r
                            }
                        }
                        return t
                    };

                function L(e, t) {
                    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                }

                function O(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                const V = function() {
                        function e() {
                            var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                                s = arguments.length > 1 ? arguments[1] : void 0;
                            L(this, e), this.el = t.el, this.wsPlayer = s, this.$el = $("#" + this.el), this.$el && !this.$el.children().length && this.__createPanTilt(), this.channel = null, this.setPtzDirection = t.setPtzDirection, this.setPtzCamera = t.setPtzCamera, this.controlSitPosition = t.controlSitPosition, this.mousedownCanvasEvent = this.__mousedownCanvasEvent.bind(this), this.mousemoveCanvasEvent = this.__mousemoveCanvasEvent.bind(this), this.mouseupCanvasEvent = this.__mouseupCanvasEvent.bind(this)
                        }
                        var t, s;
                        return t = e, (s = [{
                            key: "setChannel",
                            value: function(e) {
                                if (this.channel = e, !e) return $(".ws-pan-tilt-mask", this.$el).css({
                                    display: "block"
                                }), void this.__removeCanvasEvent();
                                var t = e.capability;
                                switch (e.cameraType + "") {
                                    case "1":
                                        parseInt(t, 2) & parseInt("100", 2) || parseInt(t, 2) & parseInt("10000000000000000", 2) ? $(".ws-pan-tilt-mask-zoom", this.$el).css({
                                            display: "none"
                                        }) : $(".ws-pan-tilt-mask-zoom", this.$el).css({
                                            display: "block"
                                        }), parseInt(t, 2) & parseInt("10000000000000000", 2) ? ($(".ws-pan-tilt-mask-direction", this.$el).css({
                                            display: "none"
                                        }), this.__removeCanvasEvent()) : $(".ws-pan-tilt-mask-direction", this.$el).css({
                                            display: "block"
                                        }), $(".ws-pan-tilt-mask-aperture", this.$el).css({
                                            display: "block"
                                        });
                                        break;
                                    case "2":
                                        $(".ws-pan-tilt-mask", this.$el).css({
                                            display: "none"
                                        });
                                        break;
                                    default:
                                        $(".ws-pan-tilt-mask", this.$el).css({
                                            display: "block"
                                        }), this.__removeCanvasEvent()
                                }
                            }
                        }, {
                            key: "__createPanTilt",
                            value: function() {
                                var e = this;
                                this.$el.append('\n            <div class="ws-pan-tilt-control">\n                <div class="ws-pan-tilt-circle-wrapper">\n                    \x3c!--云台方向控制--\x3e\n                    <div class="ws-pan-tilt-circle">\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-t.svg" title="上" direct="1"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-rt.svg" title="右上" direct="7"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-r.svg" title="右" direct="4"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-rb.svg" title="右下" direct="8"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-b.svg" title="下" direct="2"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-lb.svg" title="左下" direct="6"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-l.svg" title="左" direct="3"/></div>\n                        <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-lt.svg" title="左上" direct="5"/></div>\n                        <div class="ws-pan-tilt-inner-circle">\n                            <img\n                                class="ws-pan-tilt-pzt-select"\n                                src="./static/WSPlayer/icon/ptz-select.svg"\n                                :title="三维定位"\n                            />\n                        </div>\n                    </div>\n                </div>\n                \n                \x3c!--云台变倍、聚焦、光圈控制--\x3e\n                <div class="cloud-control-wrapper">\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon1.svg" title="变倍-" operateType="1" direct="2"/></div>\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon2.svg" title="变倍+" operateType="1" direct="1"/></div>\n                    <div class="cloud-control-separate"></div>\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon3.svg" title="聚焦-" operateType="2" direct="2"/></div>\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon4.svg" title="聚焦+" operateType="2" direct="1"/></div>\n                    <div class="cloud-control-separate"></div>\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon5.svg" title="光圈-" operateType="3" direct="2"/></div>\n                    <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon6.svg" title="光圈+" operateType="3" direct="1"/></div>\n                </div>\n                \n                \x3c!--遮罩，当通道没有云台功能时，使用遮罩遮住云台按钮--\x3e\n                \x3c!--方向按钮遮罩--\x3e\n                <div class="ws-pan-tilt-mask ws-pan-tilt-mask-direction"></div>\n                \x3c!--变倍、聚焦遮罩--\x3e\n                <div class="ws-pan-tilt-mask ws-pan-tilt-mask-zoom"></div>\n                \x3c!--光圈遮罩--\x3e\n                <div class="ws-pan-tilt-mask ws-pan-tilt-mask-aperture"></div>\n            </div>\n        '), $(".ws-pan-tilt-direction-item img", this.$el).mouseup((function(t) {
                                    e.__setPtzDirection(t.target.getAttribute("direct"), "0")
                                })), $(".ws-pan-tilt-direction-item img", this.$el).mousedown((function(t) {
                                    e.__setPtzDirection(t.target.getAttribute("direct"), "1")
                                })), $(".ws-pan-tilt-control-item img", this.$el).mouseup((function(t) {
                                    e.__setPtzCamera(t.target.getAttribute("operateType"), t.target.getAttribute("direct"), "0")
                                })), $(".ws-pan-tilt-control-item img", this.$el).mousedown((function(t) {
                                    e.__setPtzCamera(t.target.getAttribute("operateType"), t.target.getAttribute("direct"), "1")
                                })), $(".ws-pan-tilt-pzt-select", this.$el).click((function(t) {
                                    e.__openSitPosition()
                                }))
                            }
                        }, {
                            key: "__setPtzDirection",
                            value: function(e, t) {
                                var s = {
                                    project: "PSDK",
                                    method: "DMS.Ptz.OperateDirect",
                                    data: {
                                        direct: e,
                                        command: t,
                                        stepX: "4",
                                        stepY: "4",
                                        channelId: this.channel.id
                                    }
                                };
                                this.setPtzDirection && this.setPtzDirection(s).then().catch((function(e) {
                                    console.error("云台方向控制err:", e)
                                }))
                            }
                        }, {
                            key: "__setPtzCamera",
                            value: function(e, t, s) {
                                var i = {
                                    project: "PSDK",
                                    method: "DMS.Ptz.OperateCamera",
                                    data: {
                                        operateType: e,
                                        direct: t,
                                        command: s,
                                        step: "4",
                                        channelId: this.channel.id
                                    }
                                };
                                this.setPtzCamera && this.setPtzCamera(i).then().catch((function(e) {
                                    console.error("云台方向控制err:", e)
                                }))
                            }
                        }, {
                            key: "__openSitPosition",
                            value: function() {
                                if (this.openSitPositionFlag = !this.openSitPositionFlag, !this.canvasElem) {
                                    var e = this.wsPlayer.playerList,
                                        t = this.wsPlayer.selectIndex;
                                    this.canvasElem = e[t].pztCanvasElem, this.canvasElem.addEventListener("mousedown", this.mousedownCanvasEvent), this.canvasElem.addEventListener("mousemove", this.mousemoveCanvasEvent), this.canvasElem.addEventListener("mouseup", this.mouseupCanvasEvent), this.canvasContext = this.canvasElem.getContext("2d"), this.canvasContext.lineWidth = 2, this.canvasContext.strokeStyle = "#009cff"
                                }
                                this.openSitPositionFlag ? ($(this.canvasElem).css({
                                    display: "block"
                                }), $(".ws-pan-tilt-pzt-select", this.$el).attr({
                                    src: "./static/WSPlayer/icon/ptz-select-hover.svg"
                                })) : ($(this.canvasElem).css({
                                    display: "none"
                                }), $(".ws-pan-tilt-pzt-select", this.$el).attr({
                                    src: "./static/WSPlayer/icon/ptz-select.svg"
                                }))
                            }
                        }, {
                            key: "__mousedownCanvasEvent",
                            value: function(e) {
                                (e.offsetX || e.layerX) && (this.pointX = e.offsetX || e.layerX, this.pointY = e.offsetY || e.layerY, this.startDraw = !0)
                            }
                        }, {
                            key: "__mousemoveCanvasEvent",
                            value: function(e) {
                                if (this.startDraw && (e.offsetX || e.layerX)) {
                                    var t = e.offsetX || e.layerX,
                                        s = e.offsetY || e.layerY,
                                        i = t - this.pointX,
                                        n = s - this.pointY;
                                    this.canvasContext.clearRect(0, 0, this.canvasElem.width, this.canvasElem.height), this.canvasContext.beginPath(), this.canvasContext.strokeRect(this.pointX, this.pointY, i, n)
                                }
                            }
                        }, {
                            key: "__mouseupCanvasEvent",
                            value: function(e) {
                                if (e.offsetX || e.layerX) {
                                    this.startDraw = !1;
                                    var t, s, i = e.offsetX || e.layerX,
                                        n = e.offsetY || e.layerY,
                                        r = "",
                                        a = (i + this.pointX) / 2,
                                        o = (n + this.pointY) / 2,
                                        l = this.canvasElem.width / 2,
                                        c = this.canvasElem.height / 2,
                                        d = Math.abs(i - this.pointX),
                                        p = Math.abs(n - this.pointY),
                                        u = i < this.pointX;
                                    t = 8192 * (a - l) * 2 / this.canvasElem.width, s = 8192 * (o - c) * 2 / this.canvasElem.height, i === this.pointX || n === this.pointY ? r = 0 : (r = this.canvasElem.width * this.canvasElem.height / (d * p), u && (r = -r)), this.canvasContext.clearRect(0, 0, this.canvasElem.width, this.canvasElem.height), this.__controlSitPosition(t, s, r)
                                }
                            }
                        }, {
                            key: "__removeCanvasEvent",
                            value: function() {
                                this.canvasElem && (this.canvasElem.removeEventListener("mousedown", this.mousedownCanvasEvent), this.canvasElem.removeEventListener("mousemove", this.mousemoveCanvasEvent), this.canvasElem.removeEventListener("mouseup", this.mouseupCanvasEvent), this.canvasElem = null, this.canvasContext = null, this.openSitPositionFlag = !1, $(".ws-pan-tilt-pzt-select", this.$el).attr({
                                    src: "./static/WSPlayer/icon/ptz-select.svg"
                                }))
                            }
                        }, {
                            key: "__controlSitPosition",
                            value: function(e, t, s) {
                                var i = {
                                    project: "PSDK",
                                    method: "DMS.Ptz.SitPosition",
                                    data: {
                                        magicId: localStorage.getItem("magicId") || "",
                                        pointX: String(Math.round(e)),
                                        pointY: String(Math.round(t)),
                                        pointZ: String(Math.round(s)),
                                        extend: "1",
                                        channelId: this.channel.id
                                    }
                                };
                                this.controlSitPosition && this.controlSitPosition(i).then((function(e) {})).catch((function(e) {
                                    console.error("三维定位控制err:", e)
                                }))
                            }
                        }]) && O(t.prototype, s), e
                    }(),
                    W = {
                        num: 1,
                        maxNum: 1,
                        showControl: !0,
                        isDynamicLoadLib: !0,
                        onlyLoadSingleLib: !1,
                        useNginxProxy: !1,
                        openIvs: !0,
                        useH264MSE: !0,
                        useH265MSE: !0,
                        showIcons: {
                            streamChangeSelect: !1,
                            talkIcon: !1,
                            localRecordIcon: !0,
                            audioIcon: !0,
                            snapshotIcon: !0,
                            closeIcon: !0
                        }
                    };

                function j(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                var N, z, F, H = function() {
                    function t(e) {
                        if (function(e, t) {
                                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                            }(this, t), !e.type || !e.serverIp) return console.error("type, serverIp 为必传参数，请校验入参"), !1;
                        this.options = e, this.type = e.type, this.config = M(W, e.config), this.serverIp = e.serverIp ? e.serverIp : location.hostname, this.el = e.el, this.fetchChannelAuthority = e.getChannelAuthority, this.$el = $("#" + this.el), this.width = this.$el.attr("width"), this.height = this.$el.attr("height"), this.$el.height("".concat(this.height, "px")), this.$el.width("".concat(this.width, "px")), this.$el.addClass("ws-player"), this.$el.append('<div class="player-wrapper"></div>'), this.$wrapper = $(".player-wrapper", this.$el), this.playerList = [], this.playerAdapter = "selfAdaption", this.canvas = {}, this.ctx = {}, this.showNum = 1, this.maxWindow = 1, this.sendMessage = e.receiveMessageFromWSPlayer || function(e, t) {}, $(this.$el).attr("inited", !0);
                        var s = A(),
                            i = s.isVersionCompliance,
                            n = (s.browserType, s.errorCode, "https:" === location.protocol);
                        switch (this.config.isDynamicLoadLib && this.loadLibDHPlay(n, i), this.setMaxWindow(), this.beforeShowNum = 1, this.type) {
                            case "real":
                                this.createRealPlayer(e);
                                break;
                            case "record":
                                this.createRecordPlayer(e)
                        }
                        this.setSelectIndex(0), this.setPlayerNum(this.config.num), this.setCanvasGetContext(), this.bindUpdatePlayerWindow = this.__updatePlayerWindow.bind(this), window.addEventListener("resize", this.bindUpdatePlayerWindow), window.wsPlayerManager || (window.wsPlayerManager = new C)
                    }
                    var s, i;
                    return s = t, (i = [{
                        key: "setCanvasGetContext",
                        value: function() {
                            var e;
                            window.wsCanvasGetContextSet || (window.wsCanvasGetContextSet = !0, HTMLCanvasElement.prototype.getContext = (e = HTMLCanvasElement.prototype.getContext, function(t, s) {
                                return "webgl" === t && (s = Object.assign({}, s, {
                                    preserveDrawingBuffer: !0
                                })), e.call(this, t, s)
                            }))
                        }
                    }, {
                        key: "setMaxWindow",
                        value: function() {
                            var e = parseInt(this.config.maxNum, 10);
                            this.maxWindow = e > 16 ? 25 : e > 9 ? 16 : e > 4 ? 9 : e > 1 ? 4 : 1
                        }
                    }, {
                        key: "createRealPlayer",
                        value: function() {
                            var e = this;
                            this.config.showControl ? this.__addRealControl() : this.$wrapper.addClass("nocontrol"), Array(this.maxWindow).fill(1).forEach((function(t, s) {
                                var i = new m({
                                    wrapperDomId: e.el,
                                    index: s,
                                    wsPlayer: e
                                });
                                e.playerList.push(i)
                            }))
                        }
                    }, {
                        key: "createRecordPlayer",
                        value: function() {
                            var e = this;
                            this.config.showControl ? (this.__addRecordControl(), this.__addRealControl()) : this.$wrapper.addClass("nocontrol"), Array(this.maxWindow).fill(1).forEach((function(t, s) {
                                var i = new x({
                                    wrapperDomId: e.el,
                                    index: s,
                                    wsPlayer: e
                                });
                                e.playerList.push(i)
                            }))
                        }
                    }, {
                        key: "loadScript",
                        value: function(e) {
                            var t = document.createElement("script");
                            t.src = e, document.head.appendChild(t)
                        }
                    }, {
                        key: "loadLibDHPlay",
                        value: function(e, t) {
                            if (!window.loadLibDHPlayerFlag) {
                                window.loadLibDHPlayerFlag = !0;
                                var s = "./static/WSPlayer/multiThread/libdhplay.js";
                                try {
                                    new SharedArrayBuffer(1)
                                } catch (e) {
                                    s = "./static/WSPlayer/singleThread/libdhplay.js"
                                }
                                e && t && !this.config.onlyLoadSingleLib || (s = "./static/WSPlayer/singleThread/libdhplay.js"), this.loadScript(s)
                            }
                        }
                    }, {
                        key: "playReal",
                        value: function(e) {
                            if (e.rtspURL) {
                                e.wsURL = this.__getWSUrl(e.rtspURL, e.serverIp), e.playerAdapter = this.playerAdapter;
                                var t = this.playerList[e.selectIndex];
                                e.selectIndex + 1 < this.showNum ? this.setSelectIndex(e.selectIndex + 1) : this.selectIndex === e.selectIndex && t && this.setPtzChannel(e.channelData), t && t.init(e)
                            } else console.error("播放实时视频需要传入rtspURL")
                        }
                    }, {
                        key: "playRecord",
                        value: function(e) {
                            var t = this.playerList[e.selectIndex];
                            e.wsURL = this.__getWSUrl(e.rtspURL, e.serverIp), e.playerAdapter = this.playerAdapter, e.isPlayback = !0, e.selectIndex + 1 < this.showNum ? this.setSelectIndex(e.selectIndex + 1) : ($(".ws-record-play").css({
                                display: "none"
                            }), $(".ws-record-pause").css({
                                display: "block"
                            })), t && t.init(e)
                        }
                    }, {
                        key: "capturePic",
                        value: function() {
                            var e = this.playerList[this.selectIndex];
                            e && e.capturePic()
                        }
                    }, {
                        key: "play",
                        value: function() {
                            var e = this.playerList[this.selectIndex];
                            "pause" === e.status && e.play()
                        }
                    }, {
                        key: "pause",
                        value: function() {
                            var e = this.playerList[this.selectIndex];
                            "playing" === e.status && e.pause()
                        }
                    }, {
                        key: "playSpeed",
                        value: function(e, t) {
                            "real" !== this.type ? this.playerList[void 0 === t ? this.selectIndex : t].playSpeed(e) : console.warn("实时预览不支持倍速播放")
                        }
                    }, {
                        key: "setSelectIndex",
                        value: function(e) {
                            var t = this;
                            if (this.selectIndex !== e) {
                                if (this.procedure && this.procedure.setPlayIndex(e), "record" === this.type) {
                                    var s = (this.playerList[e] || {}).status;
                                    "playing" === s && ($(".ws-record-play").css({
                                        display: "none"
                                    }), $(".ws-record-pause").css({
                                        display: "block"
                                    })), ["playing", "pause"].includes(s) ? this.procedure && this.procedure.changeTimeLine(e) : (this.setTimeLine([]), $(".ws-record-pause").css({
                                        display: "none"
                                    }), $(".ws-record-play").css({
                                        display: "block"
                                    })), this.__setPlaySpeed("", e)
                                }
                                this.selectIndex = e, this.setPtzChannel((this.playerList[e].options || {}).channelData), this.playerList.forEach((function(s, i) {
                                    i === e ? s.$el.removeClass("unselected").addClass("selected") : s.$el.removeClass("selected").addClass("unselected"), t.__updateVoice(s, i === e)
                                }))
                            }
                        }
                    }, {
                        key: "setPlayerNum",
                        value: function(e) {
                            var t = this,
                                s = parseInt(e) || 1;
                            s <= 1 ? (s = 1, this.$el.removeClass("screen-split-4 screen-split-9 screen-split-16 screen-split-25"), this.$el.addClass("fullplayer")) : s > 1 && s <= 4 ? (s = 4, this.$el.removeClass("fullplayer screen-split-9 screen-split-16 screen-split-25"), this.$el.addClass("screen-split-4")) : s > 4 && s <= 9 ? (s = 9, this.$el.removeClass("fullplayer screen-split-4 screen-split-16 screen-split-25"), this.$el.addClass("screen-split-9")) : s > 9 && s <= 16 ? (s = 16, this.$el.removeClass("fullplayer screen-split-4 screen-split-9 screen-split-25"), this.$el.addClass("screen-split-16")) : (s = 25, this.$el.removeClass("fullplayer screen-split-4 screen-split-9 screen-split-16"), this.$el.addClass("screen-split-25")), s > this.maxWindow && (s = this.maxWindow), this.showNum !== s && (this.showNum = s, setTimeout((function() {
                                t.__updatePlayerWindow()
                            }), 200))
                        }
                    }, {
                        key: "setPlayerAdapter",
                        value: function(e) {
                            this.playerAdapter !== e && (this.playerAdapter = e, this.__updatePlayerWindow())
                        }
                    }, {
                        key: "setTimeLine",
                        value: function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [];
                            this.timeList = e, this.timeList.length ? $("#ws-record-time-box").css({
                                visibility: "visible"
                            }) : $("#ws-record-time-box").css({
                                visibility: "hidden"
                            }), this.__setTimeRecordArea(e)
                        }
                    }, {
                        key: "setFullScreen",
                        value: function() {
                            var e = this.$el[0].children[0];
                            e.requestFullscreen ? e.requestFullscreen() : e.webkitRequestFullscreen ? e.webkitRequestFullscreen() : e.mozRequestFullScreen ? e.mozRequestFullScreen() : e.msRequestFullscreen && e.msRequestFullscreen()
                        }
                    }, {
                        key: "close",
                        value: function(e) {
                            var t = Number(e),
                                s = this.playerList[t];
                            s ? (s.close(), this.selectIndex === t && this.setTimeLine([])) : (this.setTimeLine([]), this.playerList.forEach((function(e) {
                                e.close()
                            })), window.removeEventListener("resize", this.bindUpdatePlayerWindow))
                        }
                    }, {
                        key: "__addRealControl",
                        value: function() {
                            var e = this;
                            this.$el.append('\n            <div class="ws-control">\n                <div class="ws-flex ws-control-record ws-flex-left">\n                    <div class="ws-ctrl-record-icon ws-record-play" style="display: none" title="播放"></div>\n                    <div class="ws-ctrl-record-icon ws-record-pause" title="暂停"></div>\n                    <div class="ws-ctrl-record-icon ws-record-speed-sub" title="倍速-"></div>\n                    <div class="ws-ctrl-record-icon ws-record-speed-txt">1x</div>\n                    <div class="ws-ctrl-record-icon ws-record-speed-add" title="倍速+"></div>\n                </div>\n                <div class="ws-flex ws-flex-end">\n                    <div class="ws-select-self-adaption">\n                        <div class="ws-select-show select">\n                            <div class="ws-select-show-option">自适应</div>\n                            \x3c!-- 下拉箭头 --\x3e\n                            <img src="./static/WSPlayer/icon/spread.png" />\n                        </div>\n                        <div class="ws-self-adaption-type" style="display: none">\n                            <ul class="ws-select-ul">\n                                <li optionValue="自适应" value="selfAdaption" class="ws-select-type-item">自适应</li>\n                                <li optionValue="拉伸" value="stretching" class="ws-select-type-item">拉伸</li>\n                            </ul>\n                        </div>\n                    </div>\n                    <span class="ws-ctrl-btn-spread"></span>\n                    <div class="ws-ctrl-icon close-all-video" title="一键关闭"></div>\n                    <span class="ws-ctrl-btn-spread"></span>\n                    <div class="ws-ctrl-icon one-screen-icon" title="单屏"></div>\n                    <div class="ws-ctrl-icon four-screen-icon" title="4分屏"></div>\n                    <div class="ws-ctrl-icon nine-screen-icon" title="9分屏"></div>\n                    <div class="ws-ctrl-icon sixteen-screen-icon" title="16分屏"></div>\n                    <div class="ws-ctrl-icon twenty-five-screen-icon" title="25分屏"></div>\n                    <span class="ws-ctrl-btn-spread"></span>\n                    <div class="ws-ctrl-icon full-screen-icon" title="全屏"></div>\n                </div>\n            </div>\n        '), this.maxWindow <= 16 && $(".twenty-five-screen-icon").css({
                                display: "none"
                            }), this.maxWindow <= 9 && $(".sixteen-screen-icon").css({
                                display: "none"
                            }), this.maxWindow <= 4 && $(".nine-screen-icon").css({
                                display: "none"
                            }), 1 === this.maxWindow && ($(".four-screen-icon").css({
                                display: "none"
                            }), $(".one-screen-icon").css({
                                display: "none"
                            })), $(".full-screen-icon", this.$el).click((function() {
                                e.setFullScreen()
                            })), $(".one-screen-icon", this.$el).click((function() {
                                e.setPlayerNum(1)
                            })), $(".four-screen-icon", this.$el).click((function() {
                                e.setPlayerNum(4)
                            })), $(".nine-screen-icon", this.$el).click((function() {
                                e.setPlayerNum(9)
                            })), $(".sixteen-screen-icon", this.$el).click((function() {
                                e.setPlayerNum(16)
                            })), $(".twenty-five-screen-icon", this.$el).click((function() {
                                e.setPlayerNum(25)
                            })), $(".close-all-video", this.$el).click((function() {
                                e.close()
                            })), this.selfAdaptionSelectShow = !1, $(".ws-select-self-adaption", this.$el).click((function(t) {
                                e.selfAdaptionSelectShow ? ($(".ws-self-adaption-type", e.$el).hide(), e.selfAdaptionSelectShow = !1) : ($(".ws-self-adaption-type", e.$el).show(), e.selfAdaptionSelectShow = !0, $(".ws-select-ul .ws-select-type-item").css({
                                    background: "none"
                                }), $(".ws-select-ul [value=".concat(e.playerAdapter, "]")).css({
                                    background: "#1A78EA"
                                }))
                            })), $(".ws-self-adaption-type", this.$el).click((function(t) {
                                var s = t.target.getAttribute("value");
                                e.setPlayerAdapter(s), $(".ws-select-show-option").text(t.target.getAttribute("optionValue"))
                            })), "record" !== this.type && $(".ws-control-record").css({
                                display: "none"
                            }), $(".ws-record-pause", this.$el).click((function(t) {
                                e.pause()
                            })), $(".ws-record-play", this.$el).click((function(t) {
                                e.play()
                            })), $(".ws-record-speed-sub", this.$el).click((function(t) {
                                "playing" === e.playerList[e.selectIndex].status && e.__setPlaySpeed("PREV")
                            })), $(".ws-record-speed-add", this.$el).click((function(t) {
                                "playing" === e.playerList[e.selectIndex].status && e.__setPlaySpeed("NEXT")
                            }))
                        }
                    }, {
                        key: "__setPlaySpeed",
                        value: function(e, t) {
                            var s, i, n = this,
                                r = [{
                                    value: .125,
                                    label: "1/8x"
                                }, {
                                    value: .25,
                                    label: "1/4x"
                                }, {
                                    value: .5,
                                    label: "1/2x"
                                }, {
                                    value: 1,
                                    label: "1x"
                                }, {
                                    value: 2,
                                    label: "2x"
                                }, {
                                    value: 4,
                                    label: "4x"
                                }, {
                                    value: 8,
                                    label: "8x"
                                }],
                                a = this.playerList[void 0 === t ? this.selectIndex : t];
                            r.some((function(o, l) {
                                if (o.value === a.speed) return !(s = r[i = "PREV" === e ? l - 1 : "NEXT" === e ? l + 1 : l]) || (i ? i === r.length - 1 ? $(".ws-record-speed-add", n.$el).css({
                                    cursor: "not-allowed"
                                }) : ($(".ws-record-speed-sub", n.$el).css({
                                    cursor: "pointer"
                                }), $(".ws-record-speed-add", n.$el).css({
                                    cursor: "pointer"
                                })) : $(".ws-record-speed-sub", n.$el).css({
                                    cursor: "not-allowed"
                                }), $(".ws-record-speed-txt", n.$el).text(s.label), "playing" === a.status && n.playSpeed(s.value, t), !0)
                            }))
                        }
                    }, {
                        key: "__addRecordControl",
                        value: function() {
                            var e = this;
                            this.$el.append('\n            <div class="ws-control ws-record-control">\n                <div class="ws-timeline">\n                    <div class="ws-timeline-group"></div>\n                    <div class="ws-timeline-group"></div>\n                </div>\n                \x3c!--当前播放的时间点--\x3e\n                <div id="ws-record-time-box">\n                    <div class=\'ws-record-time\'>\n                        <span></span>\n                    </div>\n                </div>\n                <canvas height="60" id="ws-record-canvas" class="ws-record-area"/>\n            </div>\n        '), this.canvas = document.getElementById("ws-record-canvas"), this.ctx = this.canvas.getContext("2d");
                            var t = $(this.$el[0].getElementsByClassName("ws-timeline-group")[0]),
                                s = $(this.$el[0].getElementsByClassName("ws-timeline-group")[1]);
                            new Array(49).fill(1).forEach((function(e, s) {
                                var i = "ws-time-space ".concat(s % 4 ? "" : "ws-time-space-long");
                                t.append('<span class="'.concat(i, '"></span>'))
                            })), new Array(13).fill(1).forEach((function(e, t) {
                                s.append('<span class="ws-time-point">'.concat("".concat(2 * t, ":00").padStart(5, "0"), "</span>"))
                            })), $(".ws-record-control").mouseenter((function(e) {
                                $(".ws-record-control").append("<div id='ws-cursor'><div class='ws-cursor-time'><span></span></div></div>")
                            })), $(".ws-record-control").mousemove((function(e) {
                                var t = $(".ws-record-control").width(),
                                    s = e.clientX - $(".ws-record-control")[0].getBoundingClientRect().left,
                                    i = new Date(1e3 * (s / t * 24 * 60 * 60 - 28800)),
                                    n = "".concat(i.getHours()).padStart(2, "0"),
                                    r = "".concat(i.getMinutes()).padStart(2, "0"),
                                    a = "".concat(i.getSeconds()).padStart(2, "0"),
                                    o = "".concat(n, ":").concat(r, ":").concat(a);
                                $("#ws-cursor").css("left", s), $("#ws-cursor span").text(o)
                            })), $(".ws-record-control").mouseleave((function(e) {
                                $("#ws-cursor").remove()
                            })), $(".ws-record-control").click((function(t) {
                                if (["playing", "pause"].includes((e.playerList[e.selectIndex] || {}).status)) {
                                    var s = $(".ws-record-control").width(),
                                        i = t.clientX - $(".ws-record-control")[0].getBoundingClientRect().left,
                                        n = parseInt(i / s * 24 * 60 * 60, 10),
                                        r = new Date(1e3 * e.timeList[0].startTime).setHours(0, 0, 0) / 1e3 + n;
                                    e.timeList.some((function(t) {
                                        if (r >= t.startTime && r < t.endTime) return e.clickRecordTimeLine(n), !0
                                    })) || e.clickRecordTimeLine("")
                                }
                            }))
                        }
                    }, {
                        key: "__setTimeRecordArea",
                        value: function() {
                            var e = this,
                                t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [];
                            if (t.length) {
                                var s = $(".ws-record-control").width();
                                this.canvas.width = s;
                                var i = [],
                                    n = [],
                                    r = this.ctx.createLinearGradient(0, 0, 0, 60);
                                r.addColorStop(0, "rgba(77, 201, 233, 0.1)"), r.addColorStop(1, "#1c79f4");
                                var a = this.ctx.createLinearGradient(0, 0, 0, 60);
                                a.addColorStop(0, "rgba(251, 121, 101, 0.1)"), a.addColorStop(1, "#b52c2c"), t.forEach((function(e) {
                                    e.width = (e.endTime - e.startTime) * s / 86400;
                                    var t = new Date(1e3 * e.startTime),
                                        r = t.getHours(),
                                        a = t.getMinutes(),
                                        o = t.getSeconds();
                                    e.left = (3600 * r + 60 * a + o) / 86400 * s, e.isImportant ? n.push(e) : i.push(e)
                                })), i.forEach((function(t) {
                                    e.ctx.clearRect(t.left, 0, t.width, 60), e.ctx.fillStyle = r, e.ctx.fillRect(t.left, 0, t.width, 60)
                                })), n.forEach((function(t) {
                                    e.ctx.clearRect(t.left, 0, t.width, 60), e.ctx.fillStyle = a, e.ctx.fillRect(t.left, 0, t.width, 60)
                                }))
                            } else this.canvas.width = 0
                        }
                    }, {
                        key: "setPlayingTime",
                        value: function(e, t) {
                            if (this.sendMessage("recordTimeChange", t), this.selectIndex === e) {
                                var s = $(".ws-record-control").width(),
                                    i = new Date(t),
                                    n = i.getHours(),
                                    r = i.getMinutes(),
                                    a = i.getSeconds(),
                                    o = (3600 * n + 60 * r + a) / 86400 * s,
                                    l = "".concat(String(n).padStart(2, "0"), ":").concat(String(r).padStart(2, "0"), ":").concat(String(a).padStart(2, "0"));
                                $("#ws-record-time-box").css("left", o), $("#ws-record-time-box span").text(l)
                            }
                        }
                    }, {
                        key: "__getWSUrl",
                        value: function(t, s) {
                            var i = "https:" === location.protocol,
                                n = t.match(/\d{1,3}(\.\d{1,3}){3}/g)[0];
                            n || (n = t.split("//")[1].split(":")[0]);
                            var r = i ? "wss" : "ws";
                            if (i || this.config.useNginxProxy) {
                                var a = "real" === this.type ? e.websocketPorts.realmonitor : e.websocketPorts.playback;
                                return "".concat(r, "://").concat(this.serverIp, "/").concat(a, "?serverIp=").concat(s || n)
                            }
                            var o = "real" === this.type ? e.websocketPorts.realmonitor_ws : e.websocketPorts.playback_ws;
                            return "".concat(r, "://").concat(this.serverIp, ":").concat(o)
                        }
                    }, {
                        key: "__updatePlayerWindow",
                        value: function() {
                            var e = this;
                            this.playerList.forEach((function(t) {
                                t.updateAdapter(e.playerAdapter)
                            })), this.setTimeLine(this.timeList)
                        }
                    }, {
                        key: "__updateVoice",
                        value: function(e, t) {
                            t ? $(".audio-icon", e.$el).hasClass("on") && e.player.setAudioVolume(1) : e.player && e.player.setAudioVolume(0)
                        }
                    }, {
                        key: "__startTalk",
                        value: function(e) {
                            this.procedure && this.procedure.startTalk(e)
                        }
                    }, {
                        key: "changeStreamType",
                        value: function(e, t, s) {
                            this.procedure && this.procedure.playRealVideo([e], t, s)
                        }
                    }, {
                        key: "getRecordList",
                        value: function(e) {
                            this.procedure && this.procedure.getRecordList(e)
                        }
                    }, {
                        key: "clickRecordTimeLine",
                        value: function(e) {
                            e ? this.procedure && this.procedure.clickRecordTimeLine(e) : console.warn("所选时间点无录像")
                        }
                    }, {
                        key: "jumpPlayByTime",
                        value: function(e) {
                            this.procedure && this.procedure.jumpPlayByTime(e)
                        }
                    }, {
                        key: "playNextRecord",
                        value: function(e) {
                            this.sendMessage("recordPlayEnd", (this.playerList[index].options || {}).channelId)
                        }
                    }, {
                        key: "videoClosed",
                        value: function(e, t) {
                            this.sendMessage("closeVideo", {
                                selectIndex: e,
                                changeVideoFlag: t
                            }), this.procedure && this.procedure.videoClosed(e, t)
                        }
                    }, {
                        key: "sendErrorMessage",
                        value: function(t, s) {
                            this.sendMessage("errorInfo", {
                                errorCode: t,
                                errorInfo: e.errorInfo[t],
                                channelList: s
                            })
                        }
                    }, {
                        key: "initPanTilt",
                        value: function(e) {
                            this.panTilt = new V(e, this)
                        }
                    }, {
                        key: "setPtzChannel",
                        value: function(e) {
                            this.panTilt && this.panTilt.setChannel(e)
                        }
                    }]) && j(s.prototype, i), t
                }();
                F = "1.2.4", (z = "version") in(N = H) ? Object.defineProperty(N, z, {
                    value: F,
                    enumerable: !0,
                    configurable: !0,
                    writable: !0
                }) : N[z] = F;
                const U = H;

                function B(e, t) {
                    for (var s = 0; s < t.length; s++) {
                        var i = t[s];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                var X = U;
                U.WSPlayer && (X = U.WSPlayer);
                const q = function() {
                    function e(t) {
                        switch (function(e, t) {
                                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                            }(this, e), this.el = t.el, this.realPlayer = null, this.recordPlayer = null, this.player = null, this.type = "real", this.playNum = 1, this.playIndex = 0, this.currentChannelId = "", this.recordList = [], t.type) {
                            case "real":
                                this.initRealPlayer(t);
                                break;
                            case "record":
                                this.initRecordPlayer(t)
                        }
                    }
                    var t, s;
                    return t = e, (s = [{
                        key: "initRealPlayer",
                        value: function(e) {
                            this.serverIp = e.serverIp, this.serverIp ? (this.playNum = e.num, this.type = "real", this.realPlayer = new X({
                                el: this.el,
                                type: "real",
                                serverIp: this.serverIp,
                                config: {
                                    num: e.num,
                                    maxNum: e.maxNum,
                                    showControl: e.showControl
                                },
                                receiveMessageFromWSPlayer: e.receiveMessageFromWSPlayer || this.__receiveMessageFromWSPlayer.bind(this)
                            }), this.player = this.realPlayer) : console.log("serverIp:", serverIp)
                        }
                    }, {
                        key: "initRecordPlayer",
                        value: function(e) {
                            this.serverIp = e.serverIp, this.serverIp && (this.playNum = e.num, this.type = "record", this.recordPlayer = new X({
                                el: this.el,
                                type: "record",
                                serverIp: this.serverIp,
                                config: {
                                    num: e.num,
                                    maxNum: e.maxNum,
                                    showControl: e.showControl
                                },
                                receiveMessageFromWSPlayer: e.receiveMessageFromWSPlayer || this.__receiveMessageFromWSPlayer.bind(this)
                            }), this.player = this.recordPlayer)
                        }
                    }, {
                        key: "playRealVideo",
                        value: function(e) {
                            this.realPlayer && this.realPlayer.playReal(e)
                        }
                    }, {
                        key: "playRecordVideo",
                        value: function(e) {
                            this.recordPlayer && this.recordPlayer.playRecord(e)
                        }
                    }, {
                        key: "pause",
                        value: function() {
                            this.recordPlayer && this.recordPlayer.pause()
                        }
                    }, {
                        key: "play",
                        value: function() {
                            this.recordPlayer && this.recordPlayer.play()
                        }
                    }, {
                        key: "playSpeed",
                        value: function(e) {
                            this.recordPlayer && this.recordPlayer.playSpeed(e)
                        }
                    }, {
                        key: "close",
                        value: function(e) {
                            this.player && this.player.close(e)
                        }
                    }, {
                        key: "setFullScreen",
                        value: function() {
                            this.player.setFullScreen()
                        }
                    }, {
                        key: "setPlayerAdapter",
                        value: function(e) {
                            this.player.setPlayerAdapter(e)
                        }
                    }, {
                        key: "setPlayerNum",
                        value: function(e) {
                            this.player.setPlayerNum(e)
                        }
                    }, {
                        key: "setSelectIndex",
                        value: function(e) {
                            this.player.setSelectIndex(e)
                        }
                    }, {
                        key: "capturePic",
                        value: function() {
                            this.player.capturePic()
                        }
                    }, {
                        key: "jumpPlayByTime",
                        value: function(e) {
                            this.player.jumpPlayByTime(e)
                        }
                    }, {
                        key: "__receiveMessageFromWSPlayer",
                        value: function(e, t) {
                            switch (e) {
                                case "selectWindowChanged":
                                    this.currentChannelId = t.channelId, this.playIndex = t.playIndex;
                                    break;
                                case "windowNumChanged":
                                    this.playNum = t;
                                    break;
                                case "closeVideo":
                                    t.changeVideoFlag || console.log("窗口".concat(t.selectIndex, "的视频已关闭"))
                            }
                        }
                    }]) && B(t.prototype, s), e
                }();
                window.PLAYER_BOX = {
                    _options: {
                        serverIp: "",
                        autoPause: !1,
                        rtspURL: "",
                        channelId: "",
                        playerAdapter: "selfAdaption",
                        playerType: "real",
                        serverPort: ""
                    },
                    playerManager: void 0,
                    _domain: void 0,
                    setOptions: function(e) {
                        return this._options = Object.assign(this._options, e), this._options
                    },
                    playReal: function(e) {
                        "selfAdaption" === this._options.playerAdapter || "stretching" === this._options.playerAdapter ? this.playerManager.playRealVideo({
                            rtspURL: e,
                            channelId: this._options.channelId,
                            playerAdapter: this._options.playerAdapter,
                            serverIp: this._options.serverIp,
                            selectIndex: 0,
                            autoPause: this._options.autoPause
                        }) : console.log("平铺值_options.playerAdapter不正确，请输入正确的播放平铺值")
                    },
                    playRecord: function(e) {
                        "selfAdaption" === this._options.playerAdapter || "stretching" === this._options.playerAdapter ? this.playerManager.playRecordVideo({
                            rtspURL: e,
                            channelId: this._options.channelId,
                            playerAdapter: this._options.playerAdapter,
                            serverIp: this._options.serverIp,
                            selectIndex: 0,
                            autoPause: this._options.autoPause
                        }) : console.log("平铺值_options.playerAdapter不正确，请输入正确的播放平铺值")
                    },
                    pause: function() {
                        this.playerManager.pause()
                    },
                    continuePlay: function() {
                        this.playerManager.play()
                    },
                    playSpeed: function(e) {
                        this.playerManager.playSpeed(e)
                    },
                    close: function() {
                        this.playerManager.close(0)
                    },
                    setFullScreen: function() {
                        this.playerManager.setFullScreen()
                    },
                    capturePic: function() {
                        this.playerManager.capturePic()
                    },
                    receiveMessageFromWSPlayer: function(e, t) {
                        switch (e) {
                            case "recordTimeChange":
                                t && PLAYER_BOX.sendCurrentTime(t);
                                break;
                            case "recordPlayEnd":
                                PLAYER_BOX.sendrecordPlayEnd(t);
                                break;
                            case "errorInfo":
                                PLAYER_BOX.getError(t)
                        }
                    },
                    sendCurrentTime: function(e) {
                        window.playBox_time = e, window.parent.postMessage({
                            funName: "sendCurrentTime",
                            data: e
                        }, "*")
                    },
                    sendrecordPlayEnd: function(e) {
                        window.parent.postMessage({
                            funName: "sendrecordPlayEnd",
                            data: "".concat(e, "EndPlay")
                        }, "*")
                    },
                    getError: function(e) {
                        console.log("getError", e), window.parent.postMessage({
                            funName: "getError",
                            data: e
                        }, "*")
                    },
                    init: function() {
                        var e;
                        if (this.getUrlParams(), this._options.channelId) {
                            if (null === (e = this.playerManager) || void 0 === e || !e.player) {
                                "real" === this._options.playerType ? this.playerManager = new q({
                                    el: "ws-real-player",
                                    type: "real",
                                    serverIp: this._options.serverIp,
                                    maxNum: 1,
                                    num: 1,
                                    showControl: !1,
                                    receiveMessageFromWSPlayer: this.receiveMessageFromWSPlayer
                                }) : this.playerManager = new q({
                                    el: "ws-real-player",
                                    type: "record",
                                    serverIp: this._options.serverIp,
                                    maxNum: 1,
                                    num: 1,
                                    showControl: !1,
                                    receiveMessageFromWSPlayer: this.receiveMessageFromWSPlayer
                                }), this._domain = "http://".concat(this._options.serverIp).concat(this._options.serverPort ? ":".concat(this._options.serverPort) : ""), console.log("------------ this._domain ----------------", this._domain);
                                var t = this;
                                window.addEventListener("message", (function(e) {
                                    console.log("------------child Listener----------------", e.data);
                                    var s = e.data;
                                    s && s.funName ? t[s.funName] && "function" == typeof t[s.funName] ? t[s.funName](s.params) : console.log("参数异常, 调用方法不存在", s.funName) : console.log("参数异常, funName未传值")
                                }))
                            }
                        } else console.log("请传入channelId")
                    },
                    getUrlParams: function() {
                        var e, t = this,
                            s = null === (e = window.location.search.split("?")[1]) || void 0 === e ? void 0 : e.split("&");
                        s || console.log("url参数错误"), s.forEach((function(e, s) {
                            var i = e.split("=");
                            i && i[0] && i[1] && (t._options[i[0]] = i[1])
                        }))
                    }
                }, window.PLAYER_BOX.init()
            }
        },
        i = {};

    function n(e) {
        var t = i[e];
        if (void 0 !== t) {
            if (void 0 !== t.error) throw t.error;
            return t.exports
        }
        var r = i[e] = {
            exports: {}
        };
        try {
            var a = {
                id: e,
                module: r,
                factory: s[e],
                require: n
            };
            n.i.forEach((function(e) {
                e(a)
            })), r = a.module, a.factory.call(r.exports, r, r.exports, a.require)
        } catch (e) {
            throw r.error = e, e
        }
        return r.exports
    }
    n.m = s, n.c = i, n.i = [], n.hu = e => e + "." + n.h() + ".hot-update.js", n.hmrF = () => "main." + n.h() + ".hot-update.json", n.h = () => "6ae91abc7d9bf7e6fbc4", n.g = function() {
        if ("object" == typeof globalThis) return globalThis;
        try {
            return this || new Function("return this")()
        } catch (e) {
            if ("object" == typeof window) return window
        }
    }(), n.o = (e, t) => Object.prototype.hasOwnProperty.call(e, t), e = {}, t = "wsplayer:", n.l = (s, i, r, a) => {
        if (e[s]) e[s].push(i);
        else {
            var o, l;
            if (void 0 !== r)
                for (var c = document.getElementsByTagName("script"), d = 0; d < c.length; d++) {
                    var p = c[d];
                    if (p.getAttribute("src") == s || p.getAttribute("data-webpack") == t + r) {
                        o = p;
                        break
                    }
                }
            o || (l = !0, (o = document.createElement("script")).charset = "utf-8", o.timeout = 120, n.nc && o.setAttribute("nonce", n.nc), o.setAttribute("data-webpack", t + r), o.src = s), e[s] = [i];
            var u = (t, i) => {
                    o.onerror = o.onload = null, clearTimeout(h);
                    var n = e[s];
                    if (delete e[s], o.parentNode && o.parentNode.removeChild(o), n && n.forEach((e => e(i))), t) return t(i)
                },
                h = setTimeout(u.bind(null, void 0, {
                    type: "timeout",
                    target: o
                }), 12e4);
            o.onerror = u.bind(null, o.onerror), o.onload = u.bind(null, o.onload), l && document.head.appendChild(o)
        }
    }, (() => {
        var e, t, s, i, r = {},
            a = n.c,
            o = [],
            l = [],
            c = "idle";

        function d(e) {
            c = e;
            for (var t = [], s = 0; s < l.length; s++) t[s] = l[s].call(null, e);
            return Promise.all(t)
        }

        function p(e) {
            if (0 === t.length) return e();
            var s = t;
            return t = [], Promise.all(s).then((function() {
                return p(e)
            }))
        }

        function u(e) {
            if ("idle" !== c) throw new Error("check() is only allowed in idle status");
            return d("check").then(n.hmrM).then((function(i) {
                return i ? d("prepare").then((function() {
                    var r = [];
                    return t = [], s = [], Promise.all(Object.keys(n.hmrC).reduce((function(e, t) {
                        return n.hmrC[t](i.c, i.r, i.m, e, s, r), e
                    }), [])).then((function() {
                        return p((function() {
                            return e ? y(e) : d("ready").then((function() {
                                return r
                            }))
                        }))
                    }))
                })) : d(v() ? "ready" : "idle").then((function() {
                    return null
                }))
            }))
        }

        function h(e) {
            return "ready" !== c ? Promise.resolve().then((function() {
                throw new Error("apply() is only allowed in ready status")
            })) : y(e)
        }

        function y(e) {
            e = e || {}, v();
            var t = s.map((function(t) {
                return t(e)
            }));
            s = void 0;
            var n = t.map((function(e) {
                return e.error
            })).filter(Boolean);
            if (n.length > 0) return d("abort").then((function() {
                throw n[0]
            }));
            var r = d("dispose");
            t.forEach((function(e) {
                e.dispose && e.dispose()
            }));
            var a, o = d("apply"),
                l = function(e) {
                    a || (a = e)
                },
                c = [];
            return t.forEach((function(e) {
                if (e.apply) {
                    var t = e.apply(l);
                    if (t)
                        for (var s = 0; s < t.length; s++) c.push(t[s])
                }
            })), Promise.all([r, o]).then((function() {
                return a ? d("fail").then((function() {
                    throw a
                })) : i ? y(e).then((function(e) {
                    return c.forEach((function(t) {
                        e.indexOf(t) < 0 && e.push(t)
                    })), e
                })) : d("idle").then((function() {
                    return c
                }))
            }))
        }

        function v() {
            if (i) return s || (s = []), Object.keys(n.hmrI).forEach((function(e) {
                i.forEach((function(t) {
                    n.hmrI[e](t, s)
                }))
            })), i = void 0, !0
        }
        n.hmrD = r, n.i.push((function(y) {
            var v, f, m, w, g = y.module,
                P = function(s, i) {
                    var n = a[i];
                    if (!n) return s;
                    var r = function(t) {
                            if (n.hot.active) {
                                if (a[t]) {
                                    var r = a[t].parents; - 1 === r.indexOf(i) && r.push(i)
                                } else o = [i], e = t; - 1 === n.children.indexOf(t) && n.children.push(t)
                            } else console.warn("[HMR] unexpected require(" + t + ") from disposed module " + i), o = [];
                            return s(t)
                        },
                        l = function(e) {
                            return {
                                configurable: !0,
                                enumerable: !0,
                                get: function() {
                                    return s[e]
                                },
                                set: function(t) {
                                    s[e] = t
                                }
                            }
                        };
                    for (var u in s) Object.prototype.hasOwnProperty.call(s, u) && "e" !== u && Object.defineProperty(r, u, l(u));
                    return r.e = function(e) {
                        return function(e) {
                            switch (c) {
                                case "ready":
                                    return d("prepare"), t.push(e), p((function() {
                                        return d("ready")
                                    })), e;
                                case "prepare":
                                    return t.push(e), e;
                                default:
                                    return e
                            }
                        }(s.e(e))
                    }, r
                }(y.require, y.id);
            g.hot = (v = y.id, f = g, w = {
                _acceptedDependencies: {},
                _acceptedErrorHandlers: {},
                _declinedDependencies: {},
                _selfAccepted: !1,
                _selfDeclined: !1,
                _selfInvalidated: !1,
                _disposeHandlers: [],
                _main: m = e !== v,
                _requireSelf: function() {
                    o = f.parents.slice(), e = m ? void 0 : v, n(v)
                },
                active: !0,
                accept: function(e, t, s) {
                    if (void 0 === e) w._selfAccepted = !0;
                    else if ("function" == typeof e) w._selfAccepted = e;
                    else if ("object" == typeof e && null !== e)
                        for (var i = 0; i < e.length; i++) w._acceptedDependencies[e[i]] = t || function() {}, w._acceptedErrorHandlers[e[i]] = s;
                    else w._acceptedDependencies[e] = t || function() {}, w._acceptedErrorHandlers[e] = s
                },
                decline: function(e) {
                    if (void 0 === e) w._selfDeclined = !0;
                    else if ("object" == typeof e && null !== e)
                        for (var t = 0; t < e.length; t++) w._declinedDependencies[e[t]] = !0;
                    else w._declinedDependencies[e] = !0
                },
                dispose: function(e) {
                    w._disposeHandlers.push(e)
                },
                addDisposeHandler: function(e) {
                    w._disposeHandlers.push(e)
                },
                removeDisposeHandler: function(e) {
                    var t = w._disposeHandlers.indexOf(e);
                    t >= 0 && w._disposeHandlers.splice(t, 1)
                },
                invalidate: function() {
                    switch (this._selfInvalidated = !0, c) {
                        case "idle":
                            s = [], Object.keys(n.hmrI).forEach((function(e) {
                                n.hmrI[e](v, s)
                            })), d("ready");
                            break;
                        case "ready":
                            Object.keys(n.hmrI).forEach((function(e) {
                                n.hmrI[e](v, s)
                            }));
                            break;
                        case "prepare":
                        case "check":
                        case "dispose":
                        case "apply":
                            (i = i || []).push(v)
                    }
                },
                check: u,
                apply: h,
                status: function(e) {
                    if (!e) return c;
                    l.push(e)
                },
                addStatusHandler: function(e) {
                    l.push(e)
                },
                removeStatusHandler: function(e) {
                    var t = l.indexOf(e);
                    t >= 0 && l.splice(t, 1)
                },
                data: r[v]
            }, e = void 0, w), g.parents = o, g.children = [], o = [], y.require = P
        })), n.hmrC = {}, n.hmrI = {}
    })(), (() => {
        var e;
        n.g.importScripts && (e = n.g.location + "");
        var t = n.g.document;
        if (!e && t && (t.currentScript && (e = t.currentScript.src), !e)) {
            var s = t.getElementsByTagName("script");
            s.length && (e = s[s.length - 1].src)
        }
        if (!e) throw new Error("Automatic publicPath is not supported in this browser");
        e = e.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/"), n.p = e
    })(), (() => {
        var e, t, s, i, r = n.hmrS_jsonp = n.hmrS_jsonp || {
                179: 0
            },
            a = {};

        function o(e) {
            return new Promise(((t, s) => {
                a[e] = t;
                var i = n.p + n.hu(e),
                    r = new Error;
                n.l(i, (t => {
                    if (a[e]) {
                        a[e] = void 0;
                        var i = t && ("load" === t.type ? "missing" : t.type),
                            n = t && t.target && t.target.src;
                        r.message = "Loading hot update chunk " + e + " failed.\n(" + i + ": " + n + ")", r.name = "ChunkLoadError", r.type = i, r.request = n, s(r)
                    }
                }))
            }))
        }

        function l(a) {
            function o(e) {
                for (var t = [e], s = {}, i = t.map((function(e) {
                        return {
                            chain: [e],
                            id: e
                        }
                    })); i.length > 0;) {
                    var r = i.pop(),
                        a = r.id,
                        o = r.chain,
                        c = n.c[a];
                    if (c && (!c.hot._selfAccepted || c.hot._selfInvalidated)) {
                        if (c.hot._selfDeclined) return {
                            type: "self-declined",
                            chain: o,
                            moduleId: a
                        };
                        if (c.hot._main) return {
                            type: "unaccepted",
                            chain: o,
                            moduleId: a
                        };
                        for (var d = 0; d < c.parents.length; d++) {
                            var p = c.parents[d],
                                u = n.c[p];
                            if (u) {
                                if (u.hot._declinedDependencies[a]) return {
                                    type: "declined",
                                    chain: o.concat([p]),
                                    moduleId: a,
                                    parentId: p
                                }; - 1 === t.indexOf(p) && (u.hot._acceptedDependencies[a] ? (s[p] || (s[p] = []), l(s[p], [a])) : (delete s[p], t.push(p), i.push({
                                    chain: o.concat([p]),
                                    id: p
                                })))
                            }
                        }
                    }
                }
                return {
                    type: "accepted",
                    moduleId: e,
                    outdatedModules: t,
                    outdatedDependencies: s
                }
            }

            function l(e, t) {
                for (var s = 0; s < t.length; s++) {
                    var i = t[s]; - 1 === e.indexOf(i) && e.push(i)
                }
            }
            n.f && delete n.f.jsonpHmr, e = void 0;
            var c = {},
                d = [],
                p = {},
                u = function(e) {
                    console.warn("[HMR] unexpected require(" + e.id + ") to disposed module")
                };
            for (var h in t)
                if (n.o(t, h)) {
                    var y, v = t[h],
                        f = !1,
                        m = !1,
                        w = !1,
                        g = "";
                    switch ((y = v ? o(h) : {
                            type: "disposed",
                            moduleId: h
                        }).chain && (g = "\nUpdate propagation: " + y.chain.join(" -> ")), y.type) {
                        case "self-declined":
                            a.onDeclined && a.onDeclined(y), a.ignoreDeclined || (f = new Error("Aborted because of self decline: " + y.moduleId + g));
                            break;
                        case "declined":
                            a.onDeclined && a.onDeclined(y), a.ignoreDeclined || (f = new Error("Aborted because of declined dependency: " + y.moduleId + " in " + y.parentId + g));
                            break;
                        case "unaccepted":
                            a.onUnaccepted && a.onUnaccepted(y), a.ignoreUnaccepted || (f = new Error("Aborted because " + h + " is not accepted" + g));
                            break;
                        case "accepted":
                            a.onAccepted && a.onAccepted(y), m = !0;
                            break;
                        case "disposed":
                            a.onDisposed && a.onDisposed(y), w = !0;
                            break;
                        default:
                            throw new Error("Unexception type " + y.type)
                    }
                    if (f) return {
                        error: f
                    };
                    if (m)
                        for (h in p[h] = v, l(d, y.outdatedModules), y.outdatedDependencies) n.o(y.outdatedDependencies, h) && (c[h] || (c[h] = []), l(c[h], y.outdatedDependencies[h]));
                    w && (l(d, [y.moduleId]), p[h] = u)
                } t = void 0;
            for (var P, b = [], $ = 0; $ < d.length; $++) {
                var k = d[$],
                    S = n.c[k];
                S && (S.hot._selfAccepted || S.hot._main) && p[k] !== u && !S.hot._selfInvalidated && b.push({
                    module: k,
                    require: S.hot._requireSelf,
                    errorHandler: S.hot._selfAccepted
                })
            }
            return {
                dispose: function() {
                    var e;
                    s.forEach((function(e) {
                        delete r[e]
                    })), s = void 0;
                    for (var t, i = d.slice(); i.length > 0;) {
                        var a = i.pop(),
                            o = n.c[a];
                        if (o) {
                            var l = {},
                                p = o.hot._disposeHandlers;
                            for ($ = 0; $ < p.length; $++) p[$].call(null, l);
                            for (n.hmrD[a] = l, o.hot.active = !1, delete n.c[a], delete c[a], $ = 0; $ < o.children.length; $++) {
                                var u = n.c[o.children[$]];
                                u && (e = u.parents.indexOf(a)) >= 0 && u.parents.splice(e, 1)
                            }
                        }
                    }
                    for (var h in c)
                        if (n.o(c, h) && (o = n.c[h]))
                            for (P = c[h], $ = 0; $ < P.length; $++) t = P[$], (e = o.children.indexOf(t)) >= 0 && o.children.splice(e, 1)
                },
                apply: function(e) {
                    for (var t in p) n.o(p, t) && (n.m[t] = p[t]);
                    for (var s = 0; s < i.length; s++) i[s](n);
                    for (var r in c)
                        if (n.o(c, r)) {
                            var o = n.c[r];
                            if (o) {
                                P = c[r];
                                for (var l = [], u = [], h = [], y = 0; y < P.length; y++) {
                                    var v = P[y],
                                        f = o.hot._acceptedDependencies[v],
                                        m = o.hot._acceptedErrorHandlers[v];
                                    if (f) {
                                        if (-1 !== l.indexOf(f)) continue;
                                        l.push(f), u.push(m), h.push(v)
                                    }
                                }
                                for (var w = 0; w < l.length; w++) try {
                                    l[w].call(null, P)
                                } catch (t) {
                                    if ("function" == typeof u[w]) try {
                                        u[w](t, {
                                            moduleId: r,
                                            dependencyId: h[w]
                                        })
                                    } catch (s) {
                                        a.onErrored && a.onErrored({
                                            type: "accept-error-handler-errored",
                                            moduleId: r,
                                            dependencyId: h[w],
                                            error: s,
                                            originalError: t
                                        }), a.ignoreErrored || (e(s), e(t))
                                    } else a.onErrored && a.onErrored({
                                        type: "accept-errored",
                                        moduleId: r,
                                        dependencyId: h[w],
                                        error: t
                                    }), a.ignoreErrored || e(t)
                                }
                            }
                        } for (var g = 0; g < b.length; g++) {
                        var $ = b[g],
                            k = $.module;
                        try {
                            $.require(k)
                        } catch (t) {
                            if ("function" == typeof $.errorHandler) try {
                                $.errorHandler(t, {
                                    moduleId: k,
                                    module: n.c[k]
                                })
                            } catch (s) {
                                a.onErrored && a.onErrored({
                                    type: "self-accept-error-handler-errored",
                                    moduleId: k,
                                    error: s,
                                    originalError: t
                                }), a.ignoreErrored || (e(s), e(t))
                            } else a.onErrored && a.onErrored({
                                type: "self-accept-errored",
                                moduleId: k,
                                error: t
                            }), a.ignoreErrored || e(t)
                        }
                    }
                    return d
                }
            }
        }
        self.webpackHotUpdatewsplayer = (e, s, r) => {
            for (var o in s) n.o(s, o) && (t[o] = s[o]);
            r && i.push(r), a[e] && (a[e](), a[e] = void 0)
        }, n.hmrI.jsonp = function(e, r) {
            t || (t = {}, i = [], s = [], r.push(l)), n.o(t, e) || (t[e] = n.m[e])
        }, n.hmrC.jsonp = function(a, c, d, p, u, h) {
            u.push(l), e = {}, s = c, t = d.reduce((function(e, t) {
                return e[t] = !1, e
            }), {}), i = [], a.forEach((function(t) {
                n.o(r, t) && void 0 !== r[t] && (p.push(o(t)), e[t] = !0)
            })), n.f && (n.f.jsonpHmr = function(t, s) {
                e && !n.o(e, t) && n.o(r, t) && void 0 !== r[t] && (s.push(o(t)), e[t] = !0)
            })
        }, n.hmrM = () => {
            if ("undefined" == typeof fetch) throw new Error("No browser support: need fetch API");
            return fetch(n.p + n.hmrF()).then((e => {
                if (404 !== e.status) {
                    if (!e.ok) throw new Error("Failed to fetch update manifest " + e.statusText);
                    return e.json()
                }
            }))
        }
    })(), n(312)
})();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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U0FBVXBJLEVBQVNnRCxHQUVsRDBELElBQ0N2SCxFQUFvQm1CLEVBQUVvRyxFQUFxQjFHLElBQzVDYixFQUFvQm1CLEVBQUV3RyxFQUFpQjlHLFNBQ1ZwVyxJQUE3QmtkLEVBQWdCOUcsS0FFaEJnRCxFQUFTdmEsS0FBS3dlLEVBQWdCakgsSUFDOUIwRyxFQUFvQjFHLElBQVcsTUFNbkNiLEVBQW9CdUQsS0FBTyxLQUMxQixHQUFxQixvQkFBVmlJLE1BQXVCLE1BQU0sSUFBSXRkLE1BQU0sc0NBQ2xELE9BQU9zZCxNQUFNeEwsRUFBb0J0WixFQUFJc1osRUFBb0JlLFFBQVF6TSxNQUFNbVgsSUFDdEUsR0FBdUIsTUFBcEJBLEVBQVN0b0IsT0FBWixDQUNBLElBQUlzb0IsRUFBU0MsR0FBSSxNQUFNLElBQUl4ZCxNQUFNLG1DQUFxQ3VkLEVBQVNFLFlBQy9FLE9BQU9GLEVBQVNHLGEsR0NuZlE1TCxFQUFvQixNIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vd3NwbGF5ZXIvd2VicGFjay9ydW50aW1lL2xvYWQgc2NyaXB0Iiwid2VicGFjazovL3dzcGxheWVyLy4vc3JjL1dTUGxheWVyL0NPTlNUQU5ULmpzIiwid2VicGFjazovL3dzcGxheWVyLy4vc3JjL1dTUGxheWVyL1BsYXllckl0ZW0uanMiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvLi9zcmMvV1NQbGF5ZXIvc3Bpbi5qcyIsIndlYnBhY2s6Ly93c3BsYXllci8uL3NyYy9XU1BsYXllci9SZWFsUGxheWVyLmpzIiwid2VicGFjazovL3dzcGxheWVyLy4vc3JjL1dTUGxheWVyL1JlY29yZFBsYXllci5qcyIsIndlYnBhY2s6Ly93c3BsYXllci8uL3NyYy9XU1BsYXllci9XU1BsYXllck1hbmFnZXIuanMiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvLi9zcmMvV1NQbGF5ZXIvdXRpbHMuanMiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvLi9zcmMvV1NQbGF5ZXIvUGFuVGlsdC5qcyIsIndlYnBhY2s6Ly93c3BsYXllci8uL3NyYy9XU1BsYXllci9jb25maWcvY29uZmlnLmpzIiwid2VicGFjazovL3dzcGxheWVyLy4vc3JjL1dTUGxheWVyL1dTUGxheWVyLmpzIiwid2VicGFjazovL3dzcGxheWVyLy4vc3JjL1BsYXllck1hbmFnZXIuanMiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvLi9zcmMvaW5kZXguanMiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvd2VicGFjay9ydW50aW1lL2dldCBqYXZhc2NyaXB0IHVwZGF0ZSBjaHVuayBmaWxlbmFtZSIsIndlYnBhY2s6Ly93c3BsYXllci93ZWJwYWNrL3J1bnRpbWUvZ2V0IHVwZGF0ZSBtYW5pZmVzdCBmaWxlbmFtZSIsIndlYnBhY2s6Ly93c3BsYXllci93ZWJwYWNrL3J1bnRpbWUvZ2V0RnVsbEhhc2giLCJ3ZWJwYWNrOi8vd3NwbGF5ZXIvd2VicGFjay9ydW50aW1lL2dsb2JhbCIsIndlYnBhY2s6Ly93c3BsYXllci93ZWJwYWNrL3J1bnRpbWUvaGFzT3duUHJvcGVydHkgc2hvcnRoYW5kIiwid2VicGFjazovL3dzcGxheWVyL3dlYnBhY2svcnVudGltZS9ob3QgbW9kdWxlIHJlcGxhY2VtZW50Iiwid2VicGFjazovL3dzcGxheWVyL3dlYnBhY2svcnVudGltZS9wdWJsaWNQYXRoIiwid2VicGFjazovL3dzcGxheWVyL3dlYnBhY2svcnVudGltZS9qc29ucCBjaHVuayBsb2FkaW5nIiwid2VicGFjazovL3dzcGxheWVyL3dlYnBhY2svc3RhcnR1cCJdLCJzb3VyY2VzQ29udGVudCI6WyJ2YXIgaW5Qcm9ncmVzcyA9IHt9O1xudmFyIGRhdGFXZWJwYWNrUHJlZml4ID0gXCJ3c3BsYXllcjpcIjtcbi8vIGxvYWRTY3JpcHQgZnVuY3Rpb24gdG8gbG9hZCBhIHNjcmlwdCB2aWEgc2NyaXB0IHRhZ1xuX193ZWJwYWNrX3JlcXVpcmVfXy5sID0gKHVybCwgZG9uZSwga2V5LCBjaHVua0lkKSA9PiB7XG5cdGlmKGluUHJvZ3Jlc3NbdXJsXSkgeyBpblByb2dyZXNzW3VybF0ucHVzaChkb25lKTsgcmV0dXJuOyB9XG5cdHZhciBzY3JpcHQsIG5lZWRBdHRhY2g7XG5cdGlmKGtleSAhPT0gdW5kZWZpbmVkKSB7XG5cdFx0dmFyIHNjcmlwdHMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZShcInNjcmlwdFwiKTtcblx0XHRmb3IodmFyIGkgPSAwOyBpIDwgc2NyaXB0cy5sZW5ndGg7IGkrKykge1xuXHRcdFx0dmFyIHMgPSBzY3JpcHRzW2ldO1xuXHRcdFx0aWYocy5nZXRBdHRyaWJ1dGUoXCJzcmNcIikgPT0gdXJsIHx8IHMuZ2V0QXR0cmlidXRlKFwiZGF0YS13ZWJwYWNrXCIpID09IGRhdGFXZWJwYWNrUHJlZml4ICsga2V5KSB7IHNjcmlwdCA9IHM7IGJyZWFrOyB9XG5cdFx0fVxuXHR9XG5cdGlmKCFzY3JpcHQpIHtcblx0XHRuZWVkQXR0YWNoID0gdHJ1ZTtcblx0XHRzY3JpcHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzY3JpcHQnKTtcblxuXHRcdHNjcmlwdC5jaGFyc2V0ID0gJ3V0Zi04Jztcblx0XHRzY3JpcHQudGltZW91dCA9IDEyMDtcblx0XHRpZiAoX193ZWJwYWNrX3JlcXVpcmVfXy5uYykge1xuXHRcdFx0c2NyaXB0LnNldEF0dHJpYnV0ZShcIm5vbmNlXCIsIF9fd2VicGFja19yZXF1aXJlX18ubmMpO1xuXHRcdH1cblx0XHRzY3JpcHQuc2V0QXR0cmlidXRlKFwiZGF0YS13ZWJwYWNrXCIsIGRhdGFXZWJwYWNrUHJlZml4ICsga2V5KTtcblx0XHRzY3JpcHQuc3JjID0gdXJsO1xuXHR9XG5cdGluUHJvZ3Jlc3NbdXJsXSA9IFtkb25lXTtcblx0dmFyIG9uU2NyaXB0Q29tcGxldGUgPSAocHJldiwgZXZlbnQpID0+IHtcblx0XHQvLyBhdm9pZCBtZW0gbGVha3MgaW4gSUUuXG5cdFx0c2NyaXB0Lm9uZXJyb3IgPSBzY3JpcHQub25sb2FkID0gbnVsbDtcblx0XHRjbGVhclRpbWVvdXQodGltZW91dCk7XG5cdFx0dmFyIGRvbmVGbnMgPSBpblByb2dyZXNzW3VybF07XG5cdFx0ZGVsZXRlIGluUHJvZ3Jlc3NbdXJsXTtcblx0XHRzY3JpcHQucGFyZW50Tm9kZSAmJiBzY3JpcHQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChzY3JpcHQpO1xuXHRcdGRvbmVGbnMgJiYgZG9uZUZucy5mb3JFYWNoKChmbikgPT4gKGZuKGV2ZW50KSkpO1xuXHRcdGlmKHByZXYpIHJldHVybiBwcmV2KGV2ZW50KTtcblx0fVxuXHQ7XG5cdHZhciB0aW1lb3V0ID0gc2V0VGltZW91dChvblNjcmlwdENvbXBsZXRlLmJpbmQobnVsbCwgdW5kZWZpbmVkLCB7IHR5cGU6ICd0aW1lb3V0JywgdGFyZ2V0OiBzY3JpcHQgfSksIDEyMDAwMCk7XG5cdHNjcmlwdC5vbmVycm9yID0gb25TY3JpcHRDb21wbGV0ZS5iaW5kKG51bGwsIHNjcmlwdC5vbmVycm9yKTtcblx0c2NyaXB0Lm9ubG9hZCA9IG9uU2NyaXB0Q29tcGxldGUuYmluZChudWxsLCBzY3JpcHQub25sb2FkKTtcblx0bmVlZEF0dGFjaCAmJiBkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKHNjcmlwdCk7XG59OyIsIlxyXG5jb25zdCBDT05TVEFOVCA9IHtcclxuICAgIHdlYnNvY2tldFBvcnRzOiB7XHJcbiAgICAgICAgcmVhbG1vbml0b3I6IFwicmVhbG1vbml0b3Itd2Vic29ja2V0XCIsXHJcbiAgICAgICAgcGxheWJhY2s6IFwicGxheWJhY2std2Vic29ja2V0XCIsXHJcbiAgICAgICAgcmVhbG1vbml0b3Jfd3M6IFwiOTEwMFwiLFxyXG4gICAgICAgIHBsYXliYWNrX3dzOiBcIjkzMjBcIixcclxuICAgIH0sXHJcbiAgICAvLyDmkq3mlL7lmajplJnor6/kv6Hmga9cclxuICAgIGVycm9yVmlkZW9JbmZvOiB7XHJcbiAgICAgICAgMTAxOiBcIuaSreaUvuW7tuaXtuWkp+S6jjhzXCIsXHJcbiAgICAgICAgMjAxOiBcIuW9k+WJjemfs+mikeaXoOazleaSreaUvlwiLFxyXG4gICAgICAgIDIwMjogXCJ3ZWJzb2NrZXTov57mjqXplJnor69cIixcclxuICAgICAgICAyMDM6IFwi5paH5Lu25pKt5pS+5a6M5oiQXCIsXHJcbiAgICAgICAgLy8gMjA0OiBcIui/nuaOpeWksei0pe+8jOivt+ajgOafpeiuvuWkh1wiLFxyXG4gICAgICAgIDQwMTogXCLor6XnlKjmiLfml6Dmk43kvZzmnYPpmZBcIixcclxuICAgICAgICA0MDQ6IFwi6K+35rGC6LaF5pe25oiW5pyq5om+5YiwXCIsXHJcbiAgICAgICAgNDA1OiBcIuaSreaUvui2heaXtlwiLFxyXG4gICAgICAgIDQwNjogXCLop4bpopHmtYHnsbvlnovop6PmnpDlpLHotKXvvIzor7fmo4Dmn6XpgJrpgZPphY3nva5cIixcclxuICAgICAgICA0MDc6IFwi6K+35rGC6LaF5pe2XCIsXHJcbiAgICAgICAgNDU3OiBcIuaXtumXtOiuvue9rumUmeivr1wiLFxyXG4gICAgICAgIDUwMzogXCJTRVRVUOacjeWKoeS4jeWPr+eUqFwiLFxyXG4gICAgICAgIDUwNDogXCLlr7norrLlpLHotKVcIixcclxuICAgICAgICA3MDE6IFwiQ2hyb21l54mI5pys5L2O77yM6K+35Y2H57qn5Yiw5pyA5paw55qEQ2hyb21l54mI5pysXCIsXHJcbiAgICAgICAgNzAyOiBcIkZpcmVmb3jniYjmnKzkvY7vvIzor7fljYfnuqfliLDmnIDmlrDnmoRGaXJlZm9454mI5pysXCIsXHJcbiAgICAgICAgNzAzOiBcIkVkZ2XniYjmnKzkvY7vvIzor7fljYfnuqfliLDmnIDmlrDnmoRFZGdl54mI5pysXCIsXHJcbiAgICAgICAgZGVmYXVsdEVycm9yTXNnOiBcIuaSreaUvuWksei0pe+8jOivt+ajgOafpemFjee9rlwiXHJcbiAgICB9LFxyXG4gICAgLy8g6ZSZ6K+v5L+h5oGvXHJcbiAgICBlcnJvckluZm86IHtcclxuICAgICAgICAvLyDlrp7ml7bpooTop4ggMXh4XHJcbiAgICAgICAgMTAxOiBcIuaJgOmAiemAmumBk+emu+e6v++8jOaXoOazleaSreaUvlwiLFxyXG4gICAgICAgIC8vIOW9leWDj+WbnuaUviAyeHhcclxuICAgICAgICAyMDE6IFwi5omA6YCJ6YCa6YGT5pyq5p+l6K+i5Yiw5b2V5YOP5paH5Lu2XCIsXHJcbiAgICAgICAgLy8g5a+56K6yIDN4eFxyXG4gICAgICAgIDMwMTogXCLlr7norrLkuK3vvIzor7fli7/ph43lpI3lvIDlkK/pn7PpopFcIixcclxuICAgICAgICAzMDI6IFwi5YW25LuW6K6+5aSH5a+56K6y5Lit77yM5peg5rOV5byA5ZCv6Z+z6aKRXCIsXHJcbiAgICAgICAgMzAzOiBcIuWFtuS7luiuvuWkh+WvueiusuS4re+8jOaXoOazleW8gOWQr+WvueiuslwiLFxyXG4gICAgfVxyXG59XHJcblxyXG5leHBvcnQgZGVmYXVsdCBDT05TVEFOVFxyXG4iLCIvKipcclxuICogUGxheWVySXRlbVxyXG4gKi9cclxuY2xhc3MgUGxheWVySXRlbSB7XHJcbiAgLyoqXHJcbiAgICogQHBhcmFtIHsqfSBvcHQud3JhcHBlckRvbUlkIOeItue6p2lkXHJcbiAgICogQHBhcmFtIHsqfSBvcHQuaW5kZXgg57Si5byVXHJcbiAgICovXHJcbiAgY29uc3RydWN0b3Iob3B0KSB7XHJcbiAgICAvLyBkb21cclxuICAgIHRoaXMuJGVsID0gbnVsbDtcclxuICAgIC8vIOaSreaUvueUqOWFg+e0oFxyXG4gICAgdGhpcy5jYW52YXNFbGVtID0gbnVsbDtcclxuICAgIHRoaXMudmlkZW9FbGVtID0gbnVsbDtcclxuICAgIC8vIOavj+S4que7hOS7tueahGRvbeWUr+S4gCBpZFxyXG4gICAgdGhpcy5kb21JZCA9IG9wdC53cmFwcGVyRG9tSWQgKyBcIi1cIiArIG9wdC5pbmRleDtcclxuICAgIC8vIOaJgOWxnueahHdzcGxheWVyXHJcbiAgICB0aGlzLndzUGxheWVyID0gb3B0LndzUGxheWVyO1xyXG4gICAgLy8gaW5kZXjluo/lj7dcclxuICAgIHRoaXMuaW5kZXggPSBvcHQuaW5kZXg7XHJcbiAgICAvLyDnrKzkuIDluKfkuovku7ZcclxuICAgIHRoaXMuZmlyc3RUaW1lID0gMDtcclxuICAgIC8vIGF1ZGlvT25cclxuICAgIHRoaXMuaXNBdWRpb1BsYXkgPSBmYWxzZTtcclxuICAgIC8vIOWAjemAn+aSreaUvlxyXG4gICAgdGhpcy5zcGVlZCA9IDE7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiBAcGFyYW0geyp9IGRvbUlkIOatpOaSreaUvuWZqOeahGlkXHJcbiAgICovXHJcbiAgaW5pdERvbSgpIHtcclxuICAgIGxldCB0ZW1wbGF0ZSA9IHRoaXMuZ2V0VGVtcGxhdGUoKTtcclxuICAgIGxldCBwbGF5ZXIgPSAkKHRlbXBsYXRlKTtcclxuICAgIHRoaXMud3NQbGF5ZXIuJHdyYXBwZXIuYXBwZW5kKHBsYXllclswXSk7XHJcbiAgICB0aGlzLiRlbCA9ICQoXCIjXCIgKyB0aGlzLmRvbUlkKTtcclxuICAgIHRoaXMuY2FudmFzRWxlbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMuY2FudmFzSWQpIHx8IHt9O1xyXG4gICAgdGhpcy5pdnNDYW52YXNFbGVtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5pdnNDYW52YXNJZCkgfHwge307XHJcbiAgICB0aGlzLnB6dENhbnZhc0VsZW0gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLnB6dENhbnZhc0lkKSB8fCB7fTtcclxuICAgIHRoaXMudmlkZW9FbGVtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy52aWRlb0lkKTtcclxuICAgIC8vIOmakOiXj2JhcuS4iueahOaMiemSrlxyXG4gICAgbGV0IHNob3dJY29ucyA9IHRoaXMud3NQbGF5ZXIuY29uZmlnLnNob3dJY29ucyB8fCB7fTtcclxuICAgIGlmICghc2hvd0ljb25zLnN0cmVhbUNoYW5nZVNlbGVjdCkge1xyXG4gICAgICAkKFwiLnNlbGVjdC1jb250YWluZXJcIiwgdGhpcy4kZWwpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgfVxyXG4gICAgLy8g55Sf5Lqn546v5aKD5LiL77yMaHR0cOaXoOazleS9v+eUqOWvueiusuWKn+iDve+8iOa1j+iniOWZqOWkhOS6juWuieWFqOiAg+iZke+8jOemgeeUqGh0dHDljY/orq7kuIvosIPnlKjlqpLkvZNhcGnvvIzml6Dms5XmlLbpm4bpn7PpopHvvIlcclxuICAgIGlmICghc2hvd0ljb25zLnRhbGtJY29uIHx8IHRoaXMud3NQbGF5ZXIudHlwZSAhPT0gXCJyZWFsXCIpIHtcclxuICAgICAgJChcIi50YWxrLWljb25cIiwgdGhpcy4kZWwpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgfVxyXG4gICAgaWYgKCFzaG93SWNvbnMuYXVkaW9JY29uKSB7XHJcbiAgICAgICQoXCIuYXVkaW8taWNvblwiLCB0aGlzLiRlbCkuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgICBpZiAoIXNob3dJY29ucy5zbmFwc2hvdEljb24pIHtcclxuICAgICAgJChcIi5jYXB0dXJlLWljb25cIiwgdGhpcy4kZWwpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgfVxyXG4gICAgaWYgKCFzaG93SWNvbnMubG9jYWxSZWNvcmRJY29uKSB7XHJcbiAgICAgICQoXCIucmVjb3JkLWljb25cIiwgdGhpcy4kZWwpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgfVxyXG4gICAgaWYgKCFzaG93SWNvbnMuY2xvc2VJY29uKSB7XHJcbiAgICAgICQoXCIuY2xvc2UtaWNvblwiLCB0aGlzLiRlbCkuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgfVxyXG4gIC8vIOa3u+WKoOebkeWQrFxyXG4gIGluaXRNb3VzZUV2ZW50KCkge1xyXG4gICAgdGhpcy4kZWwuY2xpY2soKGV2dCkgPT4ge1xyXG4gICAgICB0aGlzLndzUGxheWVyLnNldFNlbGVjdEluZGV4KHRoaXMuaW5kZXgpO1xyXG4gICAgICB0aGlzLiRlbC5zaWJsaW5ncygpLnJlbW92ZUNsYXNzKFwic2VsZWN0ZWRcIikuYWRkQ2xhc3MoXCJ1bnNlbGVjdGVkXCIpO1xyXG4gICAgICB0aGlzLiRlbC5yZW1vdmVDbGFzcyhcInVuc2VsZWN0ZWRcIikuYWRkQ2xhc3MoXCJzZWxlY3RlZFwiKTtcclxuICAgIH0pO1xyXG4gICAgLy8g5Y+M5Ye75pKt5pS+5ZmoXHJcbiAgICB0aGlzLiRlbC5kYmxjbGljaygoZXZ0KSA9PiB7XHJcbiAgICAgIC8vIOWmguaenOacgOWkp+eql+WPo+S4ujHvvIzliJnlj4zlh7vkuI3liIfmjaLlm5vliIblsY9cclxuICAgICAgaWYgKHRoaXMud3NQbGF5ZXIub3B0aW9ucy5tYXhOdW0gPT09IDEpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICAgIH1cclxuICAgICAgaWYgKHRoaXMud3NQbGF5ZXIuJGVsLmhhc0NsYXNzKFwiZnVsbHBsYXllclwiKSkge1xyXG4gICAgICAgIHRoaXMud3NQbGF5ZXIuc2V0UGxheWVyTnVtKHRoaXMud3NQbGF5ZXIuYmVmb3JlU2hvd051bSk7XHJcbiAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgLy8g6K6w5b2V5b2T5YmN5pi+56S655qE56qX5Y+j77yM5b2T5Y+M5Ye75YiH5o2i5ZCO5YiH5Zue5p2lXHJcbiAgICAgICAgdGhpcy53c1BsYXllci5iZWZvcmVTaG93TnVtID0gdGhpcy53c1BsYXllci5zaG93TnVtO1xyXG4gICAgICAgIHRoaXMud3NQbGF5ZXIuc2V0UGxheWVyTnVtKDEpO1xyXG4gICAgICB9XHJcbiAgICAgIHRoaXMud3NQbGF5ZXIuc2V0U2VsZWN0SW5kZXgodGhpcy5pbmRleCk7XHJcbiAgICAgIHRoaXMuJGVsLnNpYmxpbmdzKCkucmVtb3ZlQ2xhc3MoXCJzZWxlY3RlZFwiKS5hZGRDbGFzcyhcInVuc2VsZWN0ZWRcIik7XHJcbiAgICAgIHRoaXMuJGVsLnJlbW92ZUNsYXNzKFwidW5zZWxlY3RlZFwiKS5hZGRDbGFzcyhcInNlbGVjdGVkXCIpO1xyXG4gICAgfSk7XHJcbiAgICAvLyDpn7PpopFcclxuICAgICQoXCIuYXVkaW8taWNvblwiLCB0aGlzLiRlbCkuY2xpY2soKGV2dCkgPT4ge1xyXG4gICAgICAvLyDlr7norrLnmoTml7blgJnvvIzml6Dms5XlvIDlkK/pn7PpopFcclxuICAgICAgaWYgKHRoaXMud3NQbGF5ZXIuaXNUYWxraW5nKSB7XHJcbiAgICAgICAgdGhpcy53c1BsYXllci5zZW5kRXJyb3JNZXNzYWdlKHRoaXMuaXNUYWxraW5nID8gXCIzMDFcIiA6IFwiMzAyXCIpO1xyXG4gICAgICAgIHJldHVybjtcclxuICAgICAgfVxyXG4gICAgICBpZiAodGhpcy5pc0F1ZGlvUGxheSkge1xyXG4gICAgICAgIC8vIOato+WcqOaSreaUvu+8jOWFs+mXreWjsOmfs1xyXG4gICAgICAgIHRoaXMucGxheWVyLnNldEF1ZGlvVm9sdW1lKDApO1xyXG4gICAgICAgICQoZXZ0LnRhcmdldCkucmVtb3ZlQ2xhc3MoXCJvblwiKS5hZGRDbGFzcyhcIm9mZlwiKTtcclxuICAgICAgfSBlbHNlIHtcclxuICAgICAgICAvLyDlvZXlg4/lm57mlL7lj6rmnIkxLzIgMSAy562J5LiJ56eN5YCN6YCf5pSv5oyB5pKt5pS+5aOw6Z+z77yM5YW25LuW5YCN6YCf6ZyA6KaB5YWz6Zet5aOw6Z+zXHJcbiAgICAgICAgaWYgKHRoaXMucGxheWVyLmlzUGxheWJhY2sgJiYgKHRoaXMuc3BlZWQgPCAwLjUgfHwgdGhpcy5zcGVlZCA+IDIpKSB7XHJcbiAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIOacquaSreaUvu+8jOaJk+W8gOWjsOmfs1xyXG4gICAgICAgIHRoaXMucGxheWVyLnNldEF1ZGlvVm9sdW1lKDEpO1xyXG4gICAgICAgIHRoaXMucmVzdW1lQXVkaW8oKTtcclxuICAgICAgICAkKGV2dC50YXJnZXQpLnJlbW92ZUNsYXNzKFwib2ZmXCIpLmFkZENsYXNzKFwib25cIik7XHJcbiAgICAgIH1cclxuICAgICAgdGhpcy5pc0F1ZGlvUGxheSA9ICF0aGlzLmlzQXVkaW9QbGF5O1xyXG4gICAgfSk7XHJcbiAgICAvLyDlr7norrJcclxuICAgICQoXCIudGFsay1pY29uXCIsIHRoaXMuJGVsKS5jbGljaygoZXZ0KSA9PiB7XHJcbiAgICAgIC8vIOWFtuS7lueql+WPo+acieWvueiusuaXtui/m+ihjOaPkOekulxyXG4gICAgICBpZiAodGhpcy53c1BsYXllci5pc1RhbGtpbmcgJiYgIXRoaXMuaXNUYWxraW5nKSB7XHJcbiAgICAgICAgLy8g5YW25LuW6K6+5aSH5a+56K6y5Lit77yM5peg5rOV5byA5ZCv5a+56K6yXHJcbiAgICAgICAgdGhpcy53c1BsYXllci5zZW5kRXJyb3JNZXNzYWdlKFwiMzAzXCIpO1xyXG4gICAgICB9IGVsc2UgaWYgKHRoaXMuaXNUYWxraW5nKSB7XHJcbiAgICAgICAgLy8g5pys56qX5Y+j5Zyo6L+b6KGM5a+56K6y77yM5YiZ5YWz6Zet5a+56K6yXHJcbiAgICAgICAgdGhpcy5zdG9wVGFsaygpO1xyXG4gICAgICB9IGVsc2Uge1xyXG4gICAgICAgIHRoaXMucmVzdW1lQXVkaW8oKTtcclxuICAgICAgICAvLyDmnYPpmZDmjqfliLZcclxuICAgICAgICAvLyB0aGlzLnNldEF1dGhvcml0eShcclxuICAgICAgICAvLyAgIHtcclxuICAgICAgICAvLyAgICAgY2hhbm5lbENvZGU6IHRoaXMub3B0aW9ucy5jaGFubmVsRGF0YVxyXG4gICAgICAgIC8vICAgICAgID8gdGhpcy5vcHRpb25zLmNoYW5uZWxEYXRhLmNoYW5uZWxDb2RlXHJcbiAgICAgICAgLy8gICAgICAgOiB0aGlzLm9wdGlvbnMuY2hhbm5lbElkLFxyXG4gICAgICAgIC8vICAgICBmdW5jdGlvbjogXCIzXCIsXHJcbiAgICAgICAgLy8gICB9LFxyXG4gICAgICAgIC8vICAgKCkgPT4ge1xyXG4gICAgICAgIC8vIOWmguaenOmDveayoeacieWcqOWvueiusu+8jOWImemAmuefpeS4muWKoeWxguiOt+WPluWvueiusnJ0c3BcclxuICAgICAgICB0aGlzLndzUGxheWVyLnRhbGtJbmRleCA9IHRoaXMuaW5kZXg7XHJcbiAgICAgICAgdGhpcy53c1BsYXllci5fX3N0YXJ0VGFsayh0aGlzLm9wdGlvbnMuY2hhbm5lbERhdGEpO1xyXG4gICAgICAgIC8vICAgfSxcclxuICAgICAgICAvLyAgIChlcnIpID0+IHtcclxuICAgICAgICAvLyAgICAgaWYgKGVyci5jb2RlID09PSAxMTAzKSB7XHJcbiAgICAgICAgLy8gICAgICAgdGhpcy53c1BsYXllci5zZW5kRXJyb3JNZXNzYWdlKDQwMSwgeyB0eXBlOiBcInRhbGtcIiB9KTtcclxuICAgICAgICAvLyAgICAgfVxyXG4gICAgICAgIC8vICAgfVxyXG4gICAgICAgIC8vICk7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgLy8g5oqT5Zu+XHJcbiAgICAkKFwiLmNhcHR1cmUtaWNvblwiLCB0aGlzLiRlbCkuY2xpY2soKGV2dCkgPT4ge1xyXG4gICAgICB0aGlzLmNhcHR1cmVQaWMoKTtcclxuICAgIH0pO1xyXG4gICAgLy8g5YWz6Zet6KeG6aKRXHJcbiAgICAkKFwiLmNsb3NlLWljb25cIiwgdGhpcy4kZWwpLmNsaWNrKChldnQpID0+IHtcclxuICAgICAgdGhpcy5jbG9zZSgpO1xyXG4gICAgfSk7XHJcbiAgICAvLyDmnKzlnLDlvZXlg49cclxuICAgICQoXCIucmVjb3JkLWljb25cIiwgdGhpcy4kZWwpLmNsaWNrKChldnQpID0+IHtcclxuICAgICAgbGV0IGNoYW5uZWxOYW1lID0gKHRoaXMub3B0aW9ucy5jaGFubmVsRGF0YSB8fCB7fSkubmFtZSB8fCBcIuW9leWDj1wiO1xyXG4gICAgICBpZiAodGhpcy5pc1JlY29yZGluZykge1xyXG4gICAgICAgIC8vIOe7k+adn+W9leWDj1xyXG4gICAgICAgIHRoaXMuaXNSZWNvcmRpbmcgPSBmYWxzZTtcclxuICAgICAgICB0aGlzLnBsYXllci5zdG9wTG9jYWxSZWNvcmQoKTtcclxuICAgICAgICAkKGV2dC50YXJnZXQpLnJlbW92ZUNsYXNzKFwicmVjb3JkaW5nXCIpO1xyXG4gICAgICB9IGVsc2UgaWYgKHRoaXMuc3RhdHVzID09PSBcInBsYXlpbmdcIikge1xyXG4gICAgICAgIC8vIOadg+mZkOaOp+WItlxyXG4gICAgICAgIC8vIHRoaXMuc2V0QXV0aG9yaXR5KFxyXG4gICAgICAgIC8vICAge1xyXG4gICAgICAgIC8vICAgICBjaGFubmVsQ29kZTogdGhpcy5vcHRpb25zLmNoYW5uZWxEYXRhXHJcbiAgICAgICAgLy8gICAgICAgPyB0aGlzLm9wdGlvbnMuY2hhbm5lbERhdGEuY2hhbm5lbENvZGVcclxuICAgICAgICAvLyAgICAgICA6IHRoaXMub3B0aW9ucy5jaGFubmVsSWQsXHJcbiAgICAgICAgLy8gICAgIGZ1bmN0aW9uOiBcIjhcIixcclxuICAgICAgICAvLyAgIH0sXHJcbiAgICAgICAgLy8gICAoKSA9PiB7XHJcbiAgICAgICAgLy8g5q2j5Zyo5pKt5pS+55qE5pe25YCZ5omN6IO95byA5aeL5b2V5YOPXHJcbiAgICAgICAgLy8g5byA5aeL5b2V5YOPXHJcbiAgICAgICAgdGhpcy5pc1JlY29yZGluZyA9IHRydWU7XHJcbiAgICAgICAgLy8g5byA5aeL5b2V5YOP77yM5Y+C5pWw5piv5q+P5Liq5b2V5YOP5paH5Lu255qE5aSn5bCP77yM5Y2V5L2N5YWGXHJcbiAgICAgICAgdGhpcy5wbGF5ZXIuc3RhcnRMb2NhbFJlY29yZChgJHtjaGFubmVsTmFtZX0tJHtEYXRlLm5vdygpfWAsIDUwKTtcclxuICAgICAgICAkKGV2dC50YXJnZXQpLmFkZENsYXNzKFwicmVjb3JkaW5nXCIpO1xyXG4gICAgICAgIC8vICAgfSxcclxuICAgICAgICAvLyAgIChlcnIpID0+IHtcclxuICAgICAgICAvLyAgICAgaWYgKGVyci5jb2RlID09PSAxMTAzKSB7XHJcbiAgICAgICAgLy8gICAgICAgdGhpcy53c1BsYXllci5zZW5kRXJyb3JNZXNzYWdlKDQwMSwgeyB0eXBlOiBcInJlY29yZFwiIH0pO1xyXG4gICAgICAgIC8vICAgICB9XHJcbiAgICAgICAgLy8gICB9XHJcbiAgICAgICAgLy8gKTtcclxuICAgICAgfVxyXG4gICAgfSk7XHJcbiAgfVxyXG5cclxuICAvLyDmnYPpmZDmjqfliLZcclxuICAvLyAgIHNldEF1dGhvcml0eShwYXJhbXMsIGNhbGxiYWNrLCBlcnJvckNhbGxCYWNrKSB7XHJcbiAgLy8gICAgIGlmICh0aGlzLndzUGxheWVyLmZldGNoQ2hhbm5lbEF1dGhvcml0eSkge1xyXG4gIC8vICAgICAgIHRoaXMud3NQbGF5ZXJcclxuICAvLyAgICAgICAgIC5mZXRjaENoYW5uZWxBdXRob3JpdHkocGFyYW1zKVxyXG4gIC8vICAgICAgICAgLnRoZW4oKHJlcykgPT4ge1xyXG4gIC8vICAgICAgICAgICBpZiAocmVzLmRhdGEucmVzdWx0KSB7XHJcbiAgLy8gICAgICAgICAgICAgY2FsbGJhY2soKTtcclxuICAvLyAgICAgICAgICAgfVxyXG4gIC8vICAgICAgICAgfSlcclxuICAvLyAgICAgICAgIC5jYXRjaCgoZXJyKSA9PiB7XHJcbiAgLy8gICAgICAgICAgIGVycm9yQ2FsbEJhY2soZXJyKTtcclxuICAvLyAgICAgICAgIH0pO1xyXG4gIC8vICAgICB9IGVsc2Uge1xyXG4gIC8vICAgICAgIGNhbGxiYWNrKCk7XHJcbiAgLy8gICAgIH1cclxuICAvLyAgIH1cclxuXHJcbiAgLy8g5byA5ZCv6Z+z6aKRXHJcbiAgLy8g6Iu55p6c5omL5py65pKt5pS+6Z+z6aKR6ZyA6KaB5Zyo54K55Ye75LqL5Lu25Lit5omL5Yqo5byA5ZCvXHJcbiAgcmVzdW1lQXVkaW8oKSB7XHJcbiAgICBpZiAoIXdpbmRvdy53c0F1ZGlvUGxheWVyKSB7XHJcbiAgICAgIGxldCBpbnRlcnZhbElkID0gc2V0SW50ZXJ2YWwoKCkgPT4ge1xyXG4gICAgICAgIGlmICh3aW5kb3cud3NBdWRpb1BsYXllcikge1xyXG4gICAgICAgICAgd2luZG93LndzQXVkaW9QbGF5ZXIubWFudWFsUmVzdW1lKFwiZnJvbVRhbGtcIik7XHJcbiAgICAgICAgICBjbGVhckludGVydmFsKGludGVydmFsSWQpO1xyXG4gICAgICAgIH1cclxuICAgICAgfSwgMTAwKTtcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgIHdpbmRvdy53c0F1ZGlvUGxheWVyLm1hbnVhbFJlc3VtZShcImZyb21UYWxrXCIpO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgLy8g6K6+572u54q25oCBXHJcbiAgc2V0U3RhdHVzKCkge31cclxuICAvKipcclxuICAgKiDmkq3mlL7op4bpopFcclxuICAgKi9cclxuICBwbGF5KCkge1xyXG4gICAgdGhpcy5wbGF5ZXIucGxheSgpO1xyXG4gICAgdGhpcy5zZXRTdGF0dXMoXCJwbGF5aW5nXCIpO1xyXG4gICAgJChcIi53cy1yZWNvcmQtcGxheVwiKS5jc3MoeyBkaXNwbGF5OiBcIm5vbmVcIiB9KTtcclxuICAgICQoXCIud3MtcmVjb3JkLXBhdXNlXCIpLmNzcyh7IGRpc3BsYXk6IFwiYmxvY2tcIiB9KTtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog5pqC5YGc6KeG6aKRXHJcbiAgICovXHJcbiAgcGF1c2UoKSB7XHJcbiAgICB0aGlzLnBsYXllci5wYXVzZSgpO1xyXG4gICAgdGhpcy5zZXRTdGF0dXMoXCJwYXVzZVwiKTtcclxuICAgICQoXCIud3MtcmVjb3JkLXBhdXNlXCIpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgJChcIi53cy1yZWNvcmQtcGxheVwiKS5jc3MoeyBkaXNwbGF5OiBcImJsb2NrXCIgfSk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDlhbPpl63op4bpopFcclxuICAgKiBAcGFyYW0gY2hhbmdlVmlkZW9GbGFnIOaYr+WQpuWboOWIh+aNouWFtuS7luinhumikeWFs+mXreeOsOWcqOinhumikVxyXG4gICAqL1xyXG4gIGNsb3NlKGNoYW5nZVZpZGVvRmxhZyA9IGZhbHNlKSB7XHJcbiAgICB0aGlzLnBsYXllciAmJiB3aW5kb3cud3NQbGF5ZXJNYW5hZ2VyLnVuYmluZFBsYXllcih0aGlzLnBsYXllci5uUGxheVBvcnQpO1xyXG4gICAgdGhpcy53c1BsYXllci52aWRlb0Nsb3NlZCh0aGlzLmluZGV4LCBjaGFuZ2VWaWRlb0ZsYWcpO1xyXG4gICAgLy8g5YWz6Zet6KeG6aKR55qE5pe25YCZ77yM5Lmf6KaB5YWz6Zet5pKt5pS+5oyJ6ZKuXHJcbiAgICB0aGlzLnNldERvbVZpc2libGUoJChcIi5wbGF5LXBhdXNlLXdyYXBwZXJcIiwgdGhpcy4kZWwpLCBmYWxzZSk7XHJcbiAgICAvLyDlhbPpl63op4bpopHlkI7kuZ/pnIDopoHpmpDol4/mkq3mlL7lmahcclxuICAgIHRoaXMudmlkZW9FbGVtLnN0eWxlLmRpc3BsYXkgPSBcIm5vbmVcIjtcclxuICAgIHRoaXMuY2FudmFzRWxlbS5zdHlsZS5kaXNwbGF5ID0gXCJub25lXCI7XHJcbiAgICAvLyDlhbPpl63lr7norrJcclxuICAgIGlmICh0aGlzLmlzVGFsa2luZykge1xyXG4gICAgICB0aGlzLnN0b3BUYWxrKCk7XHJcbiAgICB9XHJcbiAgICAvLyDph43nva7lgI3pgJ9cclxuICAgIHRoaXMuc3BlZWQgPSAxO1xyXG4gICAgLy8g5b2T6YCJ5oup55qE56qX5Y+j5YWz6Zet5Zue5pS+55qE5pe25YCZ5Lmf6ZyA6KaB5riF56m65pe26Ze06L20XHJcbiAgICBpZiAodGhpcy5pbmRleCA9PT0gdGhpcy53c1BsYXllci5zZWxlY3RJbmRleCkge1xyXG4gICAgICB0aGlzLndzUGxheWVyLnNldFRpbWVMaW5lKFtdKTtcclxuICAgICAgdGhpcy53c1BsYXllci5fX3NldFBsYXlTcGVlZCgpO1xyXG4gICAgICAkKFwiLndzLXJlY29yZC1wbGF5XCIpLmNzcyh7IGRpc3BsYXk6IFwiYmxvY2tcIiB9KTtcclxuICAgICAgJChcIi53cy1yZWNvcmQtcGF1c2VcIikuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgICBpZiAodGhpcy5pc1JlY29yZGluZykge1xyXG4gICAgICAvLyDnu5PmnZ/lvZXlg49cclxuICAgICAgdGhpcy5pc1JlY29yZGluZyA9IGZhbHNlO1xyXG4gICAgICB0aGlzLnBsYXllci5zdG9wTG9jYWxSZWNvcmQoKTtcclxuICAgICAgJChcIi5yZWNvcmQtaWNvblwiLCB0aGlzLiRlbCkucmVtb3ZlQ2xhc3MoXCJyZWNvcmRpbmdcIik7XHJcbiAgICB9XHJcbiAgICAvLyDlhbPpl63op4TliJnnur9cclxuICAgIGlmICh0aGlzLndzUGxheWVyLmNvbmZpZy5vcGVuSXZzICYmIHRoaXMucGxheWVyKSB7XHJcbiAgICAgIHRoaXMucGxheWVyLmNsb3NlSVZTKCk7XHJcbiAgICB9XHJcbiAgICAvLyDplIDmr4HigJzliqDovb3kuK3igJ3moLflvI9cclxuICAgIHRoaXMuc3Bpbm5lciAmJiB0aGlzLnNwaW5uZXIuc3RvcCgpO1xyXG4gICAgdGhpcy5wbGF5ZXIgJiYgdGhpcy5wbGF5ZXIuc3RvcCgpO1xyXG4gICAgdGhpcy5wbGF5ZXIgJiYgdGhpcy5wbGF5ZXIuY2xvc2UoKTtcclxuICAgIC8vIOWFs+mXreinhumikeWQjua4heepuuS4gOS6m+aVsOaNrlxyXG4gICAgaWYgKCFjaGFuZ2VWaWRlb0ZsYWcpIHtcclxuICAgICAgdGhpcy5wbGF5ZXIgPSBudWxsO1xyXG4gICAgICB0aGlzLm9wdGlvbnMgPSBudWxsO1xyXG4gICAgfVxyXG4gICAgdGhpcy5zZXRTdGF0dXMoXCJjbG9zZWRcIik7XHJcbiAgfVxyXG5cclxuICBjYXB0dXJlUGljKCkge1xyXG4gICAgbGV0IGNoYW5uZWxOYW1lID0gKHRoaXMub3B0aW9ucy5jaGFubmVsRGF0YSB8fCB7fSkubmFtZSB8fCBcIuaKk+WbvlwiO1xyXG4gICAgdGhpcy5wbGF5ZXIuY2FwdHVyZShgJHtjaGFubmVsTmFtZX0tJHtEYXRlLm5vdygpfWApO1xyXG4gICAgLy8g5p2D6ZmQ5o6n5Yi2XHJcbiAgICAvLyB0aGlzLnNldEF1dGhvcml0eShcclxuICAgIC8vICAge1xyXG4gICAgLy8gICAgIGNoYW5uZWxDb2RlOiB0aGlzLm9wdGlvbnMuY2hhbm5lbERhdGFcclxuICAgIC8vICAgICAgID8gdGhpcy5vcHRpb25zLmNoYW5uZWxEYXRhLmNoYW5uZWxDb2RlXHJcbiAgICAvLyAgICAgICA6IHRoaXMub3B0aW9ucy5jaGFubmVsSWQsXHJcbiAgICAvLyAgICAgZnVuY3Rpb246IFwiNFwiLFxyXG4gICAgLy8gICB9LFxyXG4gICAgLy8gICAoKSA9PiB7XHJcbiAgICAvLyAgICAgLy8g5Y+v5Lyg56ys5LqM5Liq5pm66IO95binRE9N55qE5Y+C5pWw77yM5Zyo5oqT5Zu+5Lit5pi+56S65pm66IO95binXHJcbiAgICAvLyAgICAgLy8gdGhpcy5wbGF5ZXIuY2FwdHVyZShgJHtjaGFubmVsTmFtZX0tJHtEYXRlLm5vdygpfWAsIHRoaXMuaXZzQ2FudmFzRWxlbSk7XHJcbiAgICAvLyAgICAgdGhpcy5wbGF5ZXIuY2FwdHVyZShgJHtjaGFubmVsTmFtZX0tJHtEYXRlLm5vdygpfWApO1xyXG4gICAgLy8gICB9LFxyXG4gICAgLy8gICAoZXJyKSA9PiB7XHJcbiAgICAvLyAgICAgaWYgKGVyci5jb2RlID09PSAxMTAzKSB7XHJcbiAgICAvLyAgICAgICB0aGlzLndzUGxheWVyLnNlbmRFcnJvck1lc3NhZ2UoNDAxLCB7IHR5cGU6IFwiY2FwdHVyZVwiIH0pO1xyXG4gICAgLy8gICAgIH1cclxuICAgIC8vICAgfVxyXG4gICAgLy8gKTtcclxuICB9XHJcbiAgLy8g6K6+572u5YWD57Sg5piv5ZCm5Y+v6KeBXHJcbiAgc2V0RG9tVmlzaWJsZShkb20sIHZpc2libGUpIHtcclxuICAgIGRvbSAmJlxyXG4gICAgICBkb20uY3NzKHtcclxuICAgICAgICB2aXNpYmlsaXR5OiB2aXNpYmxlID8gXCJ2aXNpYmxlXCIgOiBcImhpZGRlblwiLFxyXG4gICAgICB9KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOabtOaWsOaSreaUvuWZqOaYr+iHqumAguW6lOi/mOaYr+aLieS8uFxyXG4gICAqL1xyXG4gIHVwZGF0ZUFkYXB0ZXIocGxheWVyQWRhcHRlciwgZSA9IHt9KSB7XHJcbiAgICAvLyDop4bpopHmtYHliIbovqjnjofplb/lrr3mr5RcclxuICAgIGxldCByYXRpbyA9IGUud2lkdGggLyBlLmhlaWdodDtcclxuICAgIC8vIGVs77ya5pKt5pS+5Zmo6IqC54K5XHJcbiAgICBsZXQgZWwgPVxyXG4gICAgICAoZS5kZWNvZGVNb2RlIHx8IHRoaXMuZGVjb2RlTW9kZSkgPT09IFwidmlkZW9cIlxyXG4gICAgICAgID8gdGhpcy52aWRlb0VsZW1cclxuICAgICAgICA6IHRoaXMuY2FudmFzRWxlbTtcclxuICAgIC8vIOaSreaUvuWZqOeItuiKgueCue+8jOagueaNrueItuiKgueCueWkp+Wwj+adpei/m+ihjOe8qeaUvlxyXG4gICAgbGV0IGVsUGFyZW50ID0gZWwucGFyZW50Tm9kZTtcclxuICAgIGlmIChlLmRlY29kZU1vZGUpIHtcclxuICAgICAgdGhpcy5kZWNvZGVNb2RlID0gZS5kZWNvZGVNb2RlO1xyXG4gICAgICAvLyDlsIbliIbovqjnjoflrZjlgqjotbfmnaVcclxuICAgICAgdGhpcy53aWR0aCA9IGUud2lkdGg7XHJcbiAgICAgIHRoaXMuaGVpZ2h0ID0gZS5oZWlnaHQ7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICByYXRpbyA9IHRoaXMud2lkdGggLyB0aGlzLmhlaWdodDtcclxuICAgIH1cclxuICAgIGxldCB3aWR0aCA9IFwiMTAwJVwiO1xyXG4gICAgbGV0IGhlaWdodCA9IFwiMTAwJVwiO1xyXG4gICAgaWYgKHBsYXllckFkYXB0ZXIgPT09IFwic2VsZkFkYXB0aW9uXCIpIHtcclxuICAgICAgLy8g6Ieq6YCC5bqUXHJcbiAgICAgIGxldCBlbFBhcmVudEhlaWdodCA9IGVsUGFyZW50Lm9mZnNldEhlaWdodDtcclxuICAgICAgbGV0IGVsUGFyZW50V2lkdGggPSBlbFBhcmVudC5vZmZzZXRXaWR0aDtcclxuICAgICAgbGV0IGVsUmF0aW8gPSBlbFBhcmVudFdpZHRoIC8gZWxQYXJlbnRIZWlnaHQ7XHJcbiAgICAgIGlmIChyYXRpbyA+IGVsUmF0aW8pIHtcclxuICAgICAgICBoZWlnaHQgPSBgJHtlbFBhcmVudFdpZHRoIC8gcmF0aW99cHhgO1xyXG4gICAgICB9IGVsc2UgaWYgKHJhdGlvIDwgZWxSYXRpbykge1xyXG4gICAgICAgIHdpZHRoID0gYCR7ZWxQYXJlbnRIZWlnaHQgKiByYXRpb31weGA7XHJcbiAgICAgIH1cclxuICAgICAgJChlbCkuY3NzKHsgd2lkdGgsIGhlaWdodCwgXCJvYmplY3QtZml0XCI6IFwiY29udGFpblwiIH0pO1xyXG4gICAgICAkKHRoaXMuaXZzQ2FudmFzRWxlbSkuY3NzKHsgd2lkdGgsIGhlaWdodCwgXCJvYmplY3QtZml0XCI6IFwiY29udGFpblwiIH0pO1xyXG4gICAgICAkKHRoaXMucHp0Q2FudmFzRWxlbSkuY3NzKHsgd2lkdGgsIGhlaWdodCwgXCJvYmplY3QtZml0XCI6IFwiY29udGFpblwiIH0pO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgLy8g5ouJ5Ly4XHJcbiAgICAgICQoZWwpLmNzcyh7IHdpZHRoLCBoZWlnaHQsIFwib2JqZWN0LWZpdFwiOiBcImZpbGxcIiB9KTtcclxuICAgICAgJCh0aGlzLml2c0NhbnZhc0VsZW0pLmNzcyh7IHdpZHRoLCBoZWlnaHQsIFwib2JqZWN0LWZpdFwiOiBcImZpbGxcIiB9KTtcclxuICAgICAgJCh0aGlzLnB6dENhbnZhc0VsZW0pLmNzcyh7IHdpZHRoLCBoZWlnaHQsIFwib2JqZWN0LWZpdFwiOiBcImZpbGxcIiB9KTtcclxuICAgIH1cclxuICAgIGlmICh0aGlzLnBsYXllcikge1xyXG4gICAgICB0aGlzLml2c0NhbnZhc0VsZW0ud2lkdGggPSBlbC5vZmZzZXRXaWR0aDtcclxuICAgICAgdGhpcy5pdnNDYW52YXNFbGVtLmhlaWdodCA9IGVsLm9mZnNldEhlaWdodDtcclxuICAgICAgdGhpcy5wbGF5ZXIuc2V0SVZTQ2FudmFzU2l6ZShlbC5vZmZzZXRXaWR0aCwgZWwub2Zmc2V0SGVpZ2h0KTtcclxuICAgICAgdGhpcy5wenRDYW52YXNFbGVtLndpZHRoID0gZWwub2Zmc2V0V2lkdGg7XHJcbiAgICAgIHRoaXMucHp0Q2FudmFzRWxlbS5oZWlnaHQgPSBlbC5vZmZzZXRIZWlnaHQ7XHJcbiAgICB9XHJcbiAgfVxyXG59XHJcbmV4cG9ydCBkZWZhdWx0IFBsYXllckl0ZW07XHJcbiIsInZhciBfX2Fzc2lnbiA9ICh0aGlzICYmIHRoaXMuX19hc3NpZ24pIHx8IGZ1bmN0aW9uICgpIHtcbiAgICBfX2Fzc2lnbiA9IE9iamVjdC5hc3NpZ24gfHwgZnVuY3Rpb24odCkge1xuICAgICAgICBmb3IgKHZhciBzLCBpID0gMSwgbiA9IGFyZ3VtZW50cy5sZW5ndGg7IGkgPCBuOyBpKyspIHtcbiAgICAgICAgICAgIHMgPSBhcmd1bWVudHNbaV07XG4gICAgICAgICAgICBmb3IgKHZhciBwIGluIHMpIGlmIChPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwocywgcCkpXG4gICAgICAgICAgICAgICAgdFtwXSA9IHNbcF07XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHQ7XG4gICAgfTtcbiAgICByZXR1cm4gX19hc3NpZ24uYXBwbHkodGhpcywgYXJndW1lbnRzKTtcbn07XG52YXIgZGVmYXVsdHMgPSB7XG4gICAgbGluZXM6IDEyLFxuICAgIGxlbmd0aDogNyxcbiAgICB3aWR0aDogNSxcbiAgICByYWRpdXM6IDEwLFxuICAgIHNjYWxlOiAxLjAsXG4gICAgY29ybmVyczogMSxcbiAgICBjb2xvcjogJyMwMDAnLFxuICAgIGZhZGVDb2xvcjogJ3RyYW5zcGFyZW50JyxcbiAgICBhbmltYXRpb246ICdzcGlubmVyLWxpbmUtZmFkZS1kZWZhdWx0JyxcbiAgICByb3RhdGU6IDAsXG4gICAgZGlyZWN0aW9uOiAxLFxuICAgIHNwZWVkOiAxLFxuICAgIHpJbmRleDogMmU5LFxuICAgIGNsYXNzTmFtZTogJ3NwaW5uZXInLFxuICAgIHRvcDogJzUwJScsXG4gICAgbGVmdDogJzUwJScsXG4gICAgc2hhZG93OiAnMCAwIDFweCB0cmFuc3BhcmVudCcsXG4gICAgcG9zaXRpb246ICdhYnNvbHV0ZScsXG59O1xudmFyIFNwaW5uZXIgPSAvKiogQGNsYXNzICovIChmdW5jdGlvbiAoKSB7XG4gICAgZnVuY3Rpb24gU3Bpbm5lcihvcHRzKSB7XG4gICAgICAgIGlmIChvcHRzID09PSB2b2lkIDApIHsgb3B0cyA9IHt9OyB9XG4gICAgICAgIHRoaXMub3B0cyA9IF9fYXNzaWduKF9fYXNzaWduKHt9LCBkZWZhdWx0cyksIG9wdHMpO1xuICAgIH1cbiAgICAvKipcbiAgICAgKiBBZGRzIHRoZSBzcGlubmVyIHRvIHRoZSBnaXZlbiB0YXJnZXQgZWxlbWVudC4gSWYgdGhpcyBpbnN0YW5jZSBpcyBhbHJlYWR5XG4gICAgICogc3Bpbm5pbmcsIGl0IGlzIGF1dG9tYXRpY2FsbHkgcmVtb3ZlZCBmcm9tIGl0cyBwcmV2aW91cyB0YXJnZXQgYnkgY2FsbGluZ1xuICAgICAqIHN0b3AoKSBpbnRlcm5hbGx5LlxuICAgICAqL1xuICAgIFNwaW5uZXIucHJvdG90eXBlLnNwaW4gPSBmdW5jdGlvbiAodGFyZ2V0KSB7XG4gICAgICAgIHRoaXMuc3RvcCgpO1xuICAgICAgICB0aGlzLmVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIHRoaXMuZWwuY2xhc3NOYW1lID0gdGhpcy5vcHRzLmNsYXNzTmFtZTtcbiAgICAgICAgdGhpcy5lbC5zZXRBdHRyaWJ1dGUoJ3JvbGUnLCAncHJvZ3Jlc3NiYXInKTtcbiAgICAgICAgY3NzKHRoaXMuZWwsIHtcbiAgICAgICAgICAgIHBvc2l0aW9uOiB0aGlzLm9wdHMucG9zaXRpb24sXG4gICAgICAgICAgICB3aWR0aDogMCxcbiAgICAgICAgICAgIHpJbmRleDogdGhpcy5vcHRzLnpJbmRleCxcbiAgICAgICAgICAgIGxlZnQ6IHRoaXMub3B0cy5sZWZ0LFxuICAgICAgICAgICAgdG9wOiB0aGlzLm9wdHMudG9wLFxuICAgICAgICAgICAgdHJhbnNmb3JtOiBcInNjYWxlKFwiICsgdGhpcy5vcHRzLnNjYWxlICsgXCIpXCIsXG4gICAgICAgIH0pO1xuICAgICAgICBpZiAodGFyZ2V0KSB7XG4gICAgICAgICAgICB0YXJnZXQuaW5zZXJ0QmVmb3JlKHRoaXMuZWwsIHRhcmdldC5maXJzdENoaWxkIHx8IG51bGwpO1xuICAgICAgICB9XG4gICAgICAgIGRyYXdMaW5lcyh0aGlzLmVsLCB0aGlzLm9wdHMpO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuICAgIC8qKlxuICAgICAqIFN0b3BzIGFuZCByZW1vdmVzIHRoZSBTcGlubmVyLlxuICAgICAqIFN0b3BwZWQgc3Bpbm5lcnMgbWF5IGJlIHJldXNlZCBieSBjYWxsaW5nIHNwaW4oKSBhZ2Fpbi5cbiAgICAgKi9cbiAgICBTcGlubmVyLnByb3RvdHlwZS5zdG9wID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAodGhpcy5lbCkge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiByZXF1ZXN0QW5pbWF0aW9uRnJhbWUgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgY2FuY2VsQW5pbWF0aW9uRnJhbWUodGhpcy5hbmltYXRlSWQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KHRoaXMuYW5pbWF0ZUlkKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICh0aGlzLmVsLnBhcmVudE5vZGUpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmVsLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5lbCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLmVsID0gdW5kZWZpbmVkO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH07XG4gICAgcmV0dXJuIFNwaW5uZXI7XG59KCkpO1xuZXhwb3J0IHsgU3Bpbm5lciB9O1xuLyoqXG4gKiBTZXRzIG11bHRpcGxlIHN0eWxlIHByb3BlcnRpZXMgYXQgb25jZS5cbiAqL1xuZnVuY3Rpb24gY3NzKGVsLCBwcm9wcykge1xuICAgIGZvciAodmFyIHByb3AgaW4gcHJvcHMpIHtcbiAgICAgICAgZWwuc3R5bGVbcHJvcF0gPSBwcm9wc1twcm9wXTtcbiAgICB9XG4gICAgcmV0dXJuIGVsO1xufVxuLyoqXG4gKiBSZXR1cm5zIHRoZSBsaW5lIGNvbG9yIGZyb20gdGhlIGdpdmVuIHN0cmluZyBvciBhcnJheS5cbiAqL1xuZnVuY3Rpb24gZ2V0Q29sb3IoY29sb3IsIGlkeCkge1xuICAgIHJldHVybiB0eXBlb2YgY29sb3IgPT0gJ3N0cmluZycgPyBjb2xvciA6IGNvbG9yW2lkeCAlIGNvbG9yLmxlbmd0aF07XG59XG4vKipcbiAqIEludGVybmFsIG1ldGhvZCB0aGF0IGRyYXdzIHRoZSBpbmRpdmlkdWFsIGxpbmVzLlxuICovXG5mdW5jdGlvbiBkcmF3TGluZXMoZWwsIG9wdHMpIHtcbiAgICB2YXIgYm9yZGVyUmFkaXVzID0gKE1hdGgucm91bmQob3B0cy5jb3JuZXJzICogb3B0cy53aWR0aCAqIDUwMCkgLyAxMDAwKSArICdweCc7XG4gICAgdmFyIHNoYWRvdyA9ICdub25lJztcbiAgICBpZiAob3B0cy5zaGFkb3cgPT09IHRydWUpIHtcbiAgICAgICAgc2hhZG93ID0gJzAgMnB4IDRweCAjMDAwJzsgLy8gZGVmYXVsdCBzaGFkb3dcbiAgICB9XG4gICAgZWxzZSBpZiAodHlwZW9mIG9wdHMuc2hhZG93ID09PSAnc3RyaW5nJykge1xuICAgICAgICBzaGFkb3cgPSBvcHRzLnNoYWRvdztcbiAgICB9XG4gICAgdmFyIHNoYWRvd3MgPSBwYXJzZUJveFNoYWRvdyhzaGFkb3cpO1xuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgb3B0cy5saW5lczsgaSsrKSB7XG4gICAgICAgIHZhciBkZWdyZWVzID0gfn4oMzYwIC8gb3B0cy5saW5lcyAqIGkgKyBvcHRzLnJvdGF0ZSk7XG4gICAgICAgIHZhciBiYWNrZ3JvdW5kTGluZSA9IGNzcyhkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKSwge1xuICAgICAgICAgICAgcG9zaXRpb246ICdhYnNvbHV0ZScsXG4gICAgICAgICAgICB0b3A6IC1vcHRzLndpZHRoIC8gMiArIFwicHhcIixcbiAgICAgICAgICAgIHdpZHRoOiAob3B0cy5sZW5ndGggKyBvcHRzLndpZHRoKSArICdweCcsXG4gICAgICAgICAgICBoZWlnaHQ6IG9wdHMud2lkdGggKyAncHgnLFxuICAgICAgICAgICAgYmFja2dyb3VuZDogZ2V0Q29sb3Iob3B0cy5mYWRlQ29sb3IsIGkpLFxuICAgICAgICAgICAgYm9yZGVyUmFkaXVzOiBib3JkZXJSYWRpdXMsXG4gICAgICAgICAgICB0cmFuc2Zvcm1PcmlnaW46ICdsZWZ0JyxcbiAgICAgICAgICAgIHRyYW5zZm9ybTogXCJyb3RhdGUoXCIgKyBkZWdyZWVzICsgXCJkZWcpIHRyYW5zbGF0ZVgoXCIgKyBvcHRzLnJhZGl1cyArIFwicHgpXCIsXG4gICAgICAgIH0pO1xuICAgICAgICB2YXIgZGVsYXkgPSBpICogb3B0cy5kaXJlY3Rpb24gLyBvcHRzLmxpbmVzIC8gb3B0cy5zcGVlZDtcbiAgICAgICAgZGVsYXkgLT0gMSAvIG9wdHMuc3BlZWQ7IC8vIHNvIGluaXRpYWwgYW5pbWF0aW9uIHN0YXRlIHdpbGwgaW5jbHVkZSB0cmFpbFxuICAgICAgICB2YXIgbGluZSA9IGNzcyhkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKSwge1xuICAgICAgICAgICAgd2lkdGg6ICcxMDAlJyxcbiAgICAgICAgICAgIGhlaWdodDogJzEwMCUnLFxuICAgICAgICAgICAgYmFja2dyb3VuZDogZ2V0Q29sb3Iob3B0cy5jb2xvciwgaSksXG4gICAgICAgICAgICBib3JkZXJSYWRpdXM6IGJvcmRlclJhZGl1cyxcbiAgICAgICAgICAgIGJveFNoYWRvdzogbm9ybWFsaXplU2hhZG93KHNoYWRvd3MsIGRlZ3JlZXMpLFxuICAgICAgICAgICAgYW5pbWF0aW9uOiAxIC8gb3B0cy5zcGVlZCArIFwicyBsaW5lYXIgXCIgKyBkZWxheSArIFwicyBpbmZpbml0ZSBcIiArIG9wdHMuYW5pbWF0aW9uLFxuICAgICAgICB9KTtcbiAgICAgICAgYmFja2dyb3VuZExpbmUuYXBwZW5kQ2hpbGQobGluZSk7XG4gICAgICAgIGVsLmFwcGVuZENoaWxkKGJhY2tncm91bmRMaW5lKTtcbiAgICB9XG59XG5mdW5jdGlvbiBwYXJzZUJveFNoYWRvdyhib3hTaGFkb3cpIHtcbiAgICB2YXIgcmVnZXggPSAvXlxccyooW2EtekEtWl0rXFxzKyk/KC0/XFxkKyhcXC5cXGQrKT8pKFthLXpBLVpdKilcXHMrKC0/XFxkKyhcXC5cXGQrKT8pKFthLXpBLVpdKikoLiopJC87XG4gICAgdmFyIHNoYWRvd3MgPSBbXTtcbiAgICBmb3IgKHZhciBfaSA9IDAsIF9hID0gYm94U2hhZG93LnNwbGl0KCcsJyk7IF9pIDwgX2EubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgIHZhciBzaGFkb3cgPSBfYVtfaV07XG4gICAgICAgIHZhciBtYXRjaGVzID0gc2hhZG93Lm1hdGNoKHJlZ2V4KTtcbiAgICAgICAgaWYgKG1hdGNoZXMgPT09IG51bGwpIHtcbiAgICAgICAgICAgIGNvbnRpbnVlOyAvLyBpbnZhbGlkIHN5bnRheFxuICAgICAgICB9XG4gICAgICAgIHZhciB4ID0gK21hdGNoZXNbMl07XG4gICAgICAgIHZhciB5ID0gK21hdGNoZXNbNV07XG4gICAgICAgIHZhciB4VW5pdHMgPSBtYXRjaGVzWzRdO1xuICAgICAgICB2YXIgeVVuaXRzID0gbWF0Y2hlc1s3XTtcbiAgICAgICAgaWYgKHggPT09IDAgJiYgIXhVbml0cykge1xuICAgICAgICAgICAgeFVuaXRzID0geVVuaXRzO1xuICAgICAgICB9XG4gICAgICAgIGlmICh5ID09PSAwICYmICF5VW5pdHMpIHtcbiAgICAgICAgICAgIHlVbml0cyA9IHhVbml0cztcbiAgICAgICAgfVxuICAgICAgICBpZiAoeFVuaXRzICE9PSB5VW5pdHMpIHtcbiAgICAgICAgICAgIGNvbnRpbnVlOyAvLyB1bml0cyBtdXN0IG1hdGNoIHRvIHVzZSBhcyBjb29yZGluYXRlc1xuICAgICAgICB9XG4gICAgICAgIHNoYWRvd3MucHVzaCh7XG4gICAgICAgICAgICBwcmVmaXg6IG1hdGNoZXNbMV0gfHwgJycsXG4gICAgICAgICAgICB4OiB4LFxuICAgICAgICAgICAgeTogeSxcbiAgICAgICAgICAgIHhVbml0czogeFVuaXRzLFxuICAgICAgICAgICAgeVVuaXRzOiB5VW5pdHMsXG4gICAgICAgICAgICBlbmQ6IG1hdGNoZXNbOF0sXG4gICAgICAgIH0pO1xuICAgIH1cbiAgICByZXR1cm4gc2hhZG93cztcbn1cbi8qKlxuICogTW9kaWZ5IGJveC1zaGFkb3cgeC95IG9mZnNldHMgdG8gY291bnRlcmFjdCByb3RhdGlvblxuICovXG5mdW5jdGlvbiBub3JtYWxpemVTaGFkb3coc2hhZG93cywgZGVncmVlcykge1xuICAgIHZhciBub3JtYWxpemVkID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwLCBzaGFkb3dzXzEgPSBzaGFkb3dzOyBfaSA8IHNoYWRvd3NfMS5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgdmFyIHNoYWRvdyA9IHNoYWRvd3NfMVtfaV07XG4gICAgICAgIHZhciB4eSA9IGNvbnZlcnRPZmZzZXQoc2hhZG93LngsIHNoYWRvdy55LCBkZWdyZWVzKTtcbiAgICAgICAgbm9ybWFsaXplZC5wdXNoKHNoYWRvdy5wcmVmaXggKyB4eVswXSArIHNoYWRvdy54VW5pdHMgKyAnICcgKyB4eVsxXSArIHNoYWRvdy55VW5pdHMgKyBzaGFkb3cuZW5kKTtcbiAgICB9XG4gICAgcmV0dXJuIG5vcm1hbGl6ZWQuam9pbignLCAnKTtcbn1cbmZ1bmN0aW9uIGNvbnZlcnRPZmZzZXQoeCwgeSwgZGVncmVlcykge1xuICAgIHZhciByYWRpYW5zID0gZGVncmVlcyAqIE1hdGguUEkgLyAxODA7XG4gICAgdmFyIHNpbiA9IE1hdGguc2luKHJhZGlhbnMpO1xuICAgIHZhciBjb3MgPSBNYXRoLmNvcyhyYWRpYW5zKTtcbiAgICByZXR1cm4gW1xuICAgICAgICBNYXRoLnJvdW5kKCh4ICogY29zICsgeSAqIHNpbikgKiAxMDAwKSAvIDEwMDAsXG4gICAgICAgIE1hdGgucm91bmQoKC14ICogc2luICsgeSAqIGNvcykgKiAxMDAwKSAvIDEwMDAsXG4gICAgXTtcbn1cbiIsImltcG9ydCBQbGF5ZXJJdGVtIGZyb20gXCIuL1BsYXllckl0ZW1cIjtcclxuaW1wb3J0IHsgU3Bpbm5lciB9IGZyb20gXCIuL3NwaW5cIjtcclxuaW1wb3J0IENPTlNUQU5UIGZyb20gXCIuL0NPTlNUQU5UXCI7XHJcbmNvbnN0IFBsYXllckNvbnRyb2wgPSB3aW5kb3cuUGxheWVyQ29udHJvbDtcclxuLyogLS0tLS0tLS0tLS0tLS0tLSBQbGF5ZXJJdGVtIC0tLS0tLS0tLS0tLS0tLS0gKi9cclxuY2xhc3MgUmVhbFBsYXllckl0ZW0gZXh0ZW5kcyBQbGF5ZXJJdGVtIHtcclxuICAvKipcclxuICAgKiBAcGFyYW0geyp9IG9wdC53cmFwcGVyRG9tSWQg54i257qnaWRcclxuICAgKiBAcGFyYW0geyp9IG9wdC5pbmRleCDntKLlvJVcclxuICAgKi9cclxuICBjb25zdHJ1Y3RvcihvcHQpIHtcclxuICAgIHN1cGVyKG9wdCk7XHJcbiAgICB0aGlzLmNhbnZhc0lkID0gYCR7dGhpcy5kb21JZH0tbGl2ZWNhbnZhc2A7XHJcbiAgICB0aGlzLml2c0NhbnZhc0lkID0gYCR7dGhpcy5kb21JZH0taXZzLWxpdmVjYW52YXNgO1xyXG4gICAgdGhpcy5wenRDYW52YXNJZCA9IGAke3RoaXMuZG9tSWR9LXB6dC1saXZlY2FudmFzYDtcclxuICAgIHRoaXMudmlkZW9JZCA9IGAke3RoaXMuZG9tSWR9LWxpdmVWaWRlb2A7XHJcbiAgICB0aGlzLmluaXREb20oKTtcclxuICAgIHRoaXMuZGVmYXVsdFN0YXR1cyA9ICQoXCIuZGVmYXVsdC1zdGF0dXNcIiwgdGhpcy4kZWwpO1xyXG4gICAgdGhpcy5lcnJvciA9ICQoXCIuZXJyb3JcIiwgdGhpcy4kZWwpO1xyXG4gICAgdGhpcy5jb250cm9sbGVyID0gJChcIi5wbGF5ZXItY29udHJvbFwiLCB0aGlzLiRlbCk7XHJcbiAgICB0aGlzLmluaXRNb3VzZUV2ZW50KCk7XHJcbiAgICAvKipcclxuICAgICAqIHRoaXMuc3RhdGUg5b2T5YmNUGxheWVy54q25oCBXHJcbiAgICAgKiBjcmVhdGVkLCByZWFkeSwgcGxheWluZywgcGF1c2UsIHN0b3AsIGNsb3NlZCwgZXJyb3JcclxuICAgICAqL1xyXG4gICAgdGhpcy5zZXRTdGF0dXMoXCJjcmVhdGVkXCIpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog5pKt5pS+5Zmo5qih5p2/XHJcbiAgICovXHJcbiAgZ2V0VGVtcGxhdGUoKSB7XHJcbiAgICBsZXQgdGVtcGxhdGUgPSBgXHJcbiAgICAgICAgPGRpdiBpZD1cIiR7dGhpcy5kb21JZH1cIiBjbGFzcz1cIndzcGxheWVyLWl0ZW0gd3NwbGF5ZXItaXRlbS0ke1xyXG4gICAgICB0aGlzLmluZGV4XHJcbiAgICB9ICR7dGhpcy5pbmRleCA9PT0gMCA/IFwic2VsZWN0ZWRcIiA6IFwidW5zZWxlY3RlZFwifVwiPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtZnVsbC1jb250ZW50IHdzLWZsZXhcIj5cclxuICAgICAgICAgICAgICAgIDxjYW52YXMgaWQ9XCIke1xyXG4gICAgICAgICAgICAgICAgICB0aGlzLmNhbnZhc0lkXHJcbiAgICAgICAgICAgICAgICB9XCIgY2xhc3M9XCJraW5kLXN0cmVhbS1jYW52YXNcIiBraW5kLWNoYW5uZWwtaWQ9XCIwXCIgd2lkdGg9XCI4MDBcIiBoZWlnaHQ9XCI2MDBcIj48L2NhbnZhcz5cclxuICAgICAgICAgICAgICAgIDx2aWRlbyBpZD1cIiR7XHJcbiAgICAgICAgICAgICAgICAgIHRoaXMudmlkZW9JZFxyXG4gICAgICAgICAgICAgICAgfVwiIGNsYXNzPVwia2luZC1zdHJlYW0tY2FudmFzXCIga2luZC1jaGFubmVsLWlkPVwiMFwiIG11dGVkIHN0eWxlPVwiZGlzcGxheTpub25lXCIgd2lkdGg9XCI4MDBcIiBoZWlnaHQ9XCI2MDBcIj48L3ZpZGVvPlxyXG4gICAgICAgICAgICAgICAgPGNhbnZhcyBpZD1cIiR7XHJcbiAgICAgICAgICAgICAgICAgIHRoaXMuaXZzQ2FudmFzSWRcclxuICAgICAgICAgICAgICAgIH1cIiBjbGFzcz1cImtpbmQtc3RyZWFtLWNhbnZhc1wiIHN0eWxlPVwicG9zaXRpb246IGFic29sdXRlXCIga2luZC1jaGFubmVsLWlkPVwiMFwiIHdpZHRoPVwiODAwXCIgaGVpZ2h0PVwiNjAwXCI+PC9jYW52YXM+XHJcbiAgICAgICAgICAgICAgICA8Y2FudmFzIGlkPVwiJHtcclxuICAgICAgICAgICAgICAgICAgdGhpcy5wenRDYW52YXNJZFxyXG4gICAgICAgICAgICAgICAgfVwiIGNsYXNzPVwia2luZC1zdHJlYW0tY2FudmFzXCIgc3R5bGU9XCJkaXNwbGF5OiBub25lOyBwb3NpdGlvbjogYWJzb2x1dGVcIiBraW5kLWNoYW5uZWwtaWQ9XCIwXCIgd2lkdGg9XCI4MDBcIiBoZWlnaHQ9XCI2MDBcIj48L2NhbnZhcz5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJkZWZhdWx0LXN0YXR1c1wiPlxyXG4gICAgICAgICAgICAgICAgPGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL2RlZmF1bHQucG5nXCIgYWx0PVwiXCI+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwicGxheWVyLWNvbnRyb2wgdG9wLWNvbnRyb2wtYmFyXCI+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwic3RyZWFtXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInNlbGVjdC1jb250YWluZXJcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInNlbGVjdC1zaG93IHNlbGVjdFwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImNvZGUtc3RyZWFtXCI+5Li756CB5rWBPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8IS0tIOS4i+aLieeureWktCAtLT5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9zcHJlYWQucG5nXCIgLz5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJzdHJlYW0tdHlwZVwiIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPHVsIGNsYXNzPVwic2VsZWN0LXVsXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpIG9wdGlvblZhbHVlPVwi5Li756CB5rWBXCIgc3RyZWFtLXR5cGU9XCIxXCIgY2xhc3M9XCJzdHJlYW0tdHlwZS1pdGVtXCI+5Li756CB5rWBPC9saT5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGkgb3B0aW9uVmFsdWU9XCLovoXnoIHmtYExXCIgc3RyZWFtLXR5cGU9XCIyXCIgY2xhc3M9XCJzdHJlYW0tdHlwZS1pdGVtXCI+6L6F56CB5rWBMTwvbGk+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpIG9wdGlvblZhbHVlPVwi6L6F56CB5rWBMlwiIHN0cmVhbS10eXBlPVwiM1wiIGNsYXNzPVwic3RyZWFtLXR5cGUtaXRlbVwiPui+heeggea1gTI8L2xpPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC91bD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJzdHJlYW0taW5mb1wiPjwvc3Bhbj5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uc1wiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiB0YWxrLWljb24gb2ZmXCIgdGl0bGU9XCLlr7norrJcIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwib3B0LWljb24gcmVjb3JkLWljb25cIiB0aXRsZT1cIuW9leWDj1wiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiBhdWRpby1pY29uIG9mZlwiIHRpdGxlPVwi5aOw6Z+zXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uIGNhcHR1cmUtaWNvblwiIHRpdGxlPVwi5oqT5Zu+XCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uIGNsb3NlLWljb25cIiB0aXRsZT1cIuWFs+mXrVwiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtdGFsa2luZ1wiPi4uLjwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZXJyb3JcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJlcnJvci1tZXNzYWdlXCI+PC9kaXY+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDwvZGl2PlxyXG4gICAgICAgIGA7XHJcbiAgICByZXR1cm4gdGVtcGxhdGU7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDkuovku7bnm5HlkKxcclxuICAgKi9cclxuICBpbml0TW91c2VFdmVudCgpIHtcclxuICAgIHN1cGVyLmluaXRNb3VzZUV2ZW50KCk7XHJcbiAgICBsZXQgc2VsZiA9IHRoaXM7XHJcbiAgICB0aGlzLmhpZGVUaW1lciA9IG51bGw7XHJcbiAgICB0aGlzLiRlbC5vbihcIm1vdXNlZW50ZXIgbW91c2Vtb3ZlXCIsIChldnQpID0+IHtcclxuICAgICAgLy8g6Z2e5Yib5bu65ZKM5YWz6Zet54q25oCB77yM5pi+56S654q25oCB5p2h77yM5Y+v5YWz6Zet6KeG6aKRXHJcbiAgICAgIGlmICghW1wiY3JlYXRlZFwiLCBcImNsb3NlZFwiXS5pbmNsdWRlcyh0aGlzLnN0YXR1cykpIHtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUoJChcIi5wbGF5ZXItY29udHJvbFwiLCAkKGAjJHt0aGlzLmRvbUlkfWApKSwgdHJ1ZSk7XHJcbiAgICAgIH1cclxuICAgICAgaWYgKHRoaXMuc3RhdHVzID09PSBcInBsYXlpbmdcIiB8fCB0aGlzLnN0YXR1cyA9PT0gXCJlcnJvclwiKSB7XHJcbiAgICAgICAgdGhpcy5oaWRlVGltZXIgJiYgY2xlYXJUaW1lb3V0KHRoaXMuaGlkZVRpbWVyKTtcclxuICAgICAgfVxyXG4gICAgfSk7XHJcbiAgICB0aGlzLiRlbC5vbihcIm1vdXNlbGVhdmVcIiwgKGV2dCkgPT4ge1xyXG4gICAgICB0aGlzLmhpZGVUaW1lciA9IHNldFRpbWVvdXQoKCkgPT4ge1xyXG4gICAgICAgICQoXCIuc3RyZWFtLXR5cGVcIiwgdGhpcy4kZWwpLmhpZGUoKTtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUoJChcIi5wbGF5ZXItY29udHJvbFwiLCAkKGAjJHt0aGlzLmRvbUlkfWApKSwgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc3RyZWFtU2VsZWN0U2hvdyA9IGZhbHNlO1xyXG4gICAgICB9LCAzMDApO1xyXG4gICAgfSk7XHJcbiAgICAvLyDngrnlh7vliIfmjaLnoIHmtYFcclxuICAgIHRoaXMuc3RyZWFtU2VsZWN0U2hvdyA9IGZhbHNlO1xyXG4gICAgJChcIi5zZWxlY3RcIiwgdGhpcy4kZWwpLmNsaWNrKChlKSA9PiB7XHJcbiAgICAgIGlmICh0aGlzLnN0cmVhbVNlbGVjdFNob3cpIHtcclxuICAgICAgICAkKFwiLnN0cmVhbS10eXBlXCIsIHRoaXMuJGVsKS5oaWRlKCk7XHJcbiAgICAgICAgdGhpcy5zdHJlYW1TZWxlY3RTaG93ID0gZmFsc2U7XHJcbiAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgJChcIi5zdHJlYW0tdHlwZVwiLCB0aGlzLiRlbCkuc2hvdygpO1xyXG4gICAgICAgIHRoaXMuc3RyZWFtU2VsZWN0U2hvdyA9IHRydWU7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgJChcIi5zdHJlYW0tdHlwZVwiLCB0aGlzLiRlbCkuY2xpY2soKGUpID0+IHtcclxuICAgICAgbGV0IHN0cmVhbVR5cGVWYWx1ZSA9IGUudGFyZ2V0LmdldEF0dHJpYnV0ZShcInN0cmVhbS10eXBlXCIpO1xyXG4gICAgICAvLyDnoIHmtYHlj5HnlJ/kuobliIfmjaLmiY3ov5vooYznoIHmtYHliIfmjaLmk43kvZxcclxuICAgICAgaWYgKHNlbGYuc3RyZWFtVHlwZSAhPT0gc3RyZWFtVHlwZVZhbHVlICYmIHNlbGYub3B0aW9ucykge1xyXG4gICAgICAgIC8vIOmAmuefpeS4muWKoeWxgueggea1geWPkeeUn+WPmOWMllxyXG4gICAgICAgIHNlbGYud3NQbGF5ZXIuY2hhbmdlU3RyZWFtVHlwZShcclxuICAgICAgICAgIHNlbGYub3B0aW9ucy5jaGFubmVsRGF0YSxcclxuICAgICAgICAgIHN0cmVhbVR5cGVWYWx1ZSxcclxuICAgICAgICAgIHNlbGYuaW5kZXhcclxuICAgICAgICApO1xyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOiuvue9rueggea1geexu+Wei1xyXG4gICAqIEBwYXJhbSBzdHJlYW1UeXBlXHJcbiAgICovXHJcbiAgc2V0U3RyZWFtVHlwZShzdHJlYW1UeXBlKSB7XHJcbiAgICB0aGlzLnN0cmVhbVR5cGUgPSBzdHJlYW1UeXBlO1xyXG4gICAgLy8g6I635Y+W6ZyA6KaB6auY5Lqu55qE5YWD57SgXHJcbiAgICBsZXQgdGFyZ2V0ID0gJChcIi5zdHJlYW0tdHlwZSAuc2VsZWN0LXVsXCIpW3RoaXMuaW5kZXhdLmNoaWxkcmVuW1xyXG4gICAgICBzdHJlYW1UeXBlIC0gMVxyXG4gICAgXTtcclxuICAgICQoXCIuY29kZS1zdHJlYW1cIiwgdGhpcy4kZWwpLnRleHQoJCh0YXJnZXQpLmF0dHIoXCJvcHRpb25WYWx1ZVwiKSk7XHJcbiAgICAvLyDnu5npgInkuK3nmoTnoIHmtYHov5vooYzpq5jkuq7mmL7npLrvvIzlkIzml7blsIblhbbku5bkuKTkuKrnoIHmtYHlj5bmtojpq5jkuq5cclxuICAgICQodGFyZ2V0KVxyXG4gICAgICAuYWRkQ2xhc3MoXCJzdHJlYW0tdHlwZS1zZWxlY3RcIilcclxuICAgICAgLnNpYmxpbmdzKClcclxuICAgICAgLnJlbW92ZUNsYXNzKFwic3RyZWFtLXR5cGUtc2VsZWN0XCIpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog6K6+572u54q25oCB77yM5ZCM5pe25o6n5Yi257uE5Lu25pi+56S6XHJcbiAgICogY3JlYXRlZCwgcGxheWluZywgcGF1c2UsIHN0b3AsIGNsb3NlZCwgZXJyb3JcclxuICAgKi9cclxuICBzZXRTdGF0dXMoc3RhdHVzLCBtc2cpIHtcclxuICAgIC8vIOeKtuaAgeaUueWPmOaXtu+8jOWQkeWkluWPkemAgeeKtuaAgeWPmOWKqOaDheWGtVxyXG4gICAgdGhpcy53c1BsYXllci5zZW5kTWVzc2FnZShcInN0YXR1c0NoYW5nZWRcIiwge1xyXG4gICAgICBzdGF0dXMsXHJcbiAgICAgIHdpbmRvd0luZGV4OiB0aGlzLmluZGV4LFxyXG4gICAgfSk7XHJcbiAgICB0aGlzLnN0YXR1cyA9IHN0YXR1cztcclxuICAgIHN3aXRjaCAodGhpcy5zdGF0dXMpIHtcclxuICAgICAgY2FzZSBcImNyZWF0ZWRcIjpcclxuICAgICAgY2FzZSBcImNsb3NlZFwiOlxyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmRlZmF1bHRTdGF0dXMsIHRydWUpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmVycm9yLCBmYWxzZSk7XHJcbiAgICAgICAgdGhpcy5zZXREb21WaXNpYmxlKHRoaXMuY29udHJvbGxlciwgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMudmlkZW9FbGVtLnNyYyA9IFwiXCI7XHJcbiAgICAgICAgJChcIi5hdWRpby1pY29uXCIsIHRoaXMuJGVsKS5yZW1vdmVDbGFzcyhcIm9uXCIpLmFkZENsYXNzKFwib2ZmXCIpO1xyXG4gICAgICAgIGJyZWFrO1xyXG4gICAgICBjYXNlIFwicmVhZHlcIjpcclxuICAgICAgY2FzZSBcInBsYXlpbmdcIjpcclxuICAgICAgY2FzZSBcInBhdXNlXCI6XHJcbiAgICAgICAgdGhpcy5zZXREb21WaXNpYmxlKHRoaXMuZGVmYXVsdFN0YXR1cywgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmVycm9yLCBmYWxzZSk7XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGNhc2UgXCJlcnJvclwiOlxyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmRlZmF1bHRTdGF0dXMsIGZhbHNlKTtcclxuICAgICAgICAkKFwiLmVycm9yLW1lc3NhZ2VcIiwgdGhpcy4kZWwpLnRleHQoXHJcbiAgICAgICAgICBDT05TVEFOVC5lcnJvclZpZGVvSW5mb1ttc2cuZXJyb3JDb2RlXVxyXG4gICAgICAgICAgICA/IENPTlNUQU5ULmVycm9yVmlkZW9JbmZvW21zZy5lcnJvckNvZGVdXHJcbiAgICAgICAgICAgIDogQ09OU1RBTlQuZXJyb3JWaWRlb0luZm9bXCJkZWZhdWx0RXJyb3JNc2dcIl1cclxuICAgICAgICApO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmVycm9yLCB0cnVlKTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgZGVmYXVsdDpcclxuICAgICAgICBicmVhaztcclxuICAgIH1cclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOWIneWni+WMluaSreaUvuWZqFxyXG4gICAqIEBwYXJhbSB7Kn0gb3B0aW9ucy5ydHNwVVJMXHJcbiAgICogQHBhcmFtIHsqfSBvcHRpb25zLmRlY29kZU1vZGUg5Y+v6YCJ5Y+C5pWwXHJcbiAgICogQHBhcmFtIHsqfSBvcHRpb25zLndzVVJMIOWPr+mAieWPguaVsFxyXG4gICAqIEBwYXJhbSB7Kn0gb3B0aW9ucy5zdHJlYW1UeXBlIOeggea1geexu+Wei1xyXG4gICAqIEBwYXJhbSB7Kn0gb3B0aW9ucy5jaGFubmVsSWQg6YCa6YGTaWRcclxuICAgKi9cclxuICBpbml0KG9wdGlvbnMpIHtcclxuICAgIGlmICghd2luZG93Lm1fbk1vZHVsZUluaXRpYWxpemVkKSB7XHJcbiAgICAgIGNvbnNvbGUuZXJyb3IoXCLop6PnoIHlupPmnKrliJ3lp4vljJblrozmiJDvvIzor7fnqI3lkI7mkq3mlL7vvIFcIik7XHJcbiAgICAgIHJldHVybjtcclxuICAgIH1cclxuICAgIHRoaXMub3B0aW9ucyA9IG9wdGlvbnM7XHJcbiAgICBpZiAodGhpcy5wbGF5ZXIpIHtcclxuICAgICAgaWYgKHRoaXMuaXNBdWRpb1BsYXkpIHtcclxuICAgICAgICAvLyDmraPlnKjmkq3mlL7liJnlhbPpl63lo7Dpn7NcclxuICAgICAgICAkKFwiLmF1ZGlvLWljb25cIiwgdGhpcy4kZWwpLnJlbW92ZUNsYXNzKFwib25cIikuYWRkQ2xhc3MoXCJvZmZcIik7XHJcbiAgICAgIH1cclxuICAgICAgdGhpcy5jbG9zZSh0cnVlKTtcclxuICAgIH1cclxuICAgIGlmICh0aGlzLnNwaW5uZXIpIHtcclxuICAgICAgdGhpcy5zcGlubmVyLnN0b3AoKTtcclxuICAgIH1cclxuICAgIHRoaXMuc3Bpbm5lciA9IG5ldyBTcGlubmVyKHtcclxuICAgICAgY29sb3I6IFwiI2ZmZmZmZlwiLFxyXG4gICAgfSkuc3Bpbih0aGlzLiRlbFswXSk7XHJcbiAgICB0aGlzLnNldFN0YXR1cyhcInJlYWR5XCIpO1xyXG4gICAgdGhpcy5zZXRTdHJlYW1UeXBlKG9wdGlvbnMuc3RyZWFtVHlwZSk7XHJcblxyXG4gICAgdGhpcy5jcmVhdGVQbGF5ZXIob3B0aW9ucyk7XHJcbiAgfVxyXG5cclxuICBzdGFydFBsYXkob3B0aW9ucywgZSkge1xyXG4gICAgbGV0IHNlbGYgPSB0aGlzO1xyXG4gICAgaWYgKGUuZGVjb2RlTW9kZSA9PT0gXCJ2aWRlb1wiKSB7XHJcbiAgICAgIHNlbGYudmlkZW9FbGVtLnN0eWxlLmRpc3BsYXkgPSBcIlwiO1xyXG4gICAgICBzZWxmLmNhbnZhc0VsZW0uc3R5bGUuZGlzcGxheSA9IFwibm9uZVwiO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgc2VsZi52aWRlb0VsZW0uc3R5bGUuZGlzcGxheSA9IFwibm9uZVwiO1xyXG4gICAgICBzZWxmLmNhbnZhc0VsZW0uc3R5bGUuZGlzcGxheSA9IFwiXCI7XHJcbiAgICB9XHJcbiAgICAvLyDorr7nva7mi4nkvLjmiJbogIXoh6rpgILlupRcclxuICAgIHNlbGYudXBkYXRlQWRhcHRlcihvcHRpb25zLnBsYXllckFkYXB0ZXIsIGUpO1xyXG4gICAgdGhpcy53aWR0aCA9IGUud2lkdGg7XHJcbiAgICB0aGlzLmhlaWdodCA9IGUuaGVpZ2h0O1xyXG4gICAgLy8g6Iul6KeG6aKR5q2j5Zyo5Yqg6L295Lit77yM5piv5rKh5pyJ5a696auY55qEXHJcbiAgICAkKFwiLnN0cmVhbS1pbmZvXCIsICQoYCMke3NlbGYuZG9tSWR9YCkpLnRleHQoXHJcbiAgICAgIGAke2UuZW5jb2RlTW9kZSA/IGAke2UuZW5jb2RlTW9kZX0sIGAgOiBcIlwifSR7XHJcbiAgICAgICAgZS53aWR0aCA/IGAke2Uud2lkdGh9KmAgOiBcIlwiXHJcbiAgICAgIH0ke2UuaGVpZ2h0ID8gZS5oZWlnaHQgOiBcIlwifWBcclxuICAgICk7XHJcbiAgfVxyXG5cclxuICBjcmVhdGVQbGF5ZXIob3B0aW9ucykge1xyXG4gICAgbGV0IHNlbGYgPSB0aGlzO1xyXG4gICAgY29uc3QgeyB1c2VIMjY0TVNFLCB1c2VIMjY1TVNFIH0gPSB0aGlzLndzUGxheWVyLmNvbmZpZztcclxuICAgIHRoaXMucGxheWVyID0gbmV3IFBsYXllckNvbnRyb2woe1xyXG4gICAgICB3c1VSTDogb3B0aW9ucy53c1VSTCxcclxuICAgICAgcnRzcFVSTDogb3B0aW9ucy5ydHNwVVJMLFxyXG4gICAgICB1c2VIMjY0TVNFLFxyXG4gICAgICB1c2VIMjY1TVNFLFxyXG4gICAgICBldmVudHM6IHtcclxuICAgICAgICAvLyDlvIDlp4vmkq3mlL5cclxuICAgICAgICBQbGF5U3RhcnQ6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhlKTtcclxuICAgICAgICAgIHNlbGYuc3Bpbm5lci5zdG9wKCk7XHJcbiAgICAgICAgICBzZWxmLnNldFN0YXR1cyhcInBsYXlpbmdcIik7XHJcbiAgICAgICAgfSxcclxuICAgICAgICAvLyDlvIDlp4vop6PnoIFcclxuICAgICAgICBEZWNvZGVTdGFydDogKGUpID0+IHtcclxuICAgICAgICAgIGNvbnNvbGUubG9nKGUpO1xyXG4gICAgICAgICAgc2VsZi5zdGFydFBsYXkob3B0aW9ucywgZSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICAvLyDojrflj5bluKfnjodcclxuICAgICAgICBHZXRGcmFtZVJhdGU6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhcIkdldEZyYW1lUmF0ZVwiLCBlKTtcclxuICAgICAgICAgIHNlbGYuc3RhcnRQbGF5KG9wdGlvbnMsIGUpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLy8g5oql6ZSZXHJcbiAgICAgICAgRXJyb3I6IChlKSA9PiB7XHJcbiAgICAgICAgICAvLyDnlLHkuo5lcnJvcumHjOacieW7tui/n+S7u+WKoe+8jOmBv+WFjeW7tui/n+S7u+WKoeinpuWPkeacn+mXtOWIh+aNouinhumikeWvvOiHtOW7tui/n+S7u+WKoeinpuWPkeWIsOaWsOeahOinhumikemHjO+8jOWKoOS4gOS4quWIpOaWrVxyXG4gICAgICAgICAgLy8g5q+P5Liq6KeG6aKR55qEc3ltYm9s6YO95piv5ZSv5LiA55qEXHJcbiAgICAgICAgICBpZiAoXHJcbiAgICAgICAgICAgIHNlbGYucGxheWVyICYmXHJcbiAgICAgICAgICAgIHNlbGYucGxheWVyLndzICYmXHJcbiAgICAgICAgICAgIGUuc3ltYm9sID09PSBzZWxmLnBsYXllci53cy5zeW1ib2xcclxuICAgICAgICAgICkge1xyXG4gICAgICAgICAgICAvLyDmj5DnpLo0MDggM3PotoXml7bvvIzpobXpnaLkuI3mmL7npLrotoXml7ZcclxuICAgICAgICAgICAgaWYgKGUuZXJyb3JDb2RlID09PSBcIjQwOFwiKSB7XHJcbiAgICAgICAgICAgICAgLy8g5aaC5p6c55+t5pe26Ze06LaF5pe277yM5YiZ55Sx6L6F56CB5rWB6Ieq5Yqo5YiH5oiQ5Li756CB5rWBXHJcbiAgICAgICAgICAgICAgaWYgKHNlbGYuc3RyZWFtVHlwZSA9PT0gXCIyXCIpIHtcclxuICAgICAgICAgICAgICAgIHNlbGYud3NQbGF5ZXIuY2hhbmdlU3RyZWFtVHlwZShcclxuICAgICAgICAgICAgICAgICAgc2VsZi5vcHRpb25zLmNoYW5uZWxEYXRhLFxyXG4gICAgICAgICAgICAgICAgICBcIjFcIixcclxuICAgICAgICAgICAgICAgICAgc2VsZi5pbmRleFxyXG4gICAgICAgICAgICAgICAgKTtcclxuICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHNlbGYuc3Bpbm5lci5zdG9wKCk7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKFwiRXJyb3I6IFwiICsgSlNPTi5zdHJpbmdpZnkoZSkpO1xyXG4gICAgICAgICAgICBzZWxmLnNldFN0YXR1cyhcImVycm9yXCIsIGUpO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLy8g5b2V5YOP5paH5Lu25pKt5pS+57uT5p2fXHJcbiAgICAgICAgRmlsZU92ZXI6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhcIkZpbGVPdmVyOiBcIiwgZSk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICAvLyDojrflj5bop4bpopHml7bpl7Tkv6Hmga9cclxuICAgICAgICBVcGRhdGVQbGF5aW5nVGltZTogKGUpID0+IHtcclxuICAgICAgICAgIC8vIGUgLSB0aW1lU3RhbXBcclxuICAgICAgICB9LFxyXG4gICAgICB9LFxyXG4gICAgfSk7XHJcblxyXG4gICAgLy8g5Yid5aeL5YyW5pKt5pS+5ZmoXHJcbiAgICB0aGlzLnBsYXllci5pbml0KHRoaXMuY2FudmFzRWxlbSwgdGhpcy52aWRlb0VsZW0sIHRoaXMuaXZzQ2FudmFzRWxlbSk7XHJcbiAgICAvLyDlvIDlp4vov57mjqV3ZWJzb2NrZXTlubblvIDlp4vmkq3mlL5cclxuICAgIHRoaXMucGxheWVyLmNvbm5lY3QoKTtcclxuICAgIC8vIOW8gOWQr+inhOWImee6v1xyXG4gICAgaWYgKHRoaXMud3NQbGF5ZXIuY29uZmlnLm9wZW5JdnMpIHtcclxuICAgICAgdGhpcy5wbGF5ZXIub3BlbklWUygpO1xyXG4gICAgfVxyXG4gICAgd2luZG93LndzUGxheWVyTWFuYWdlci5iaW5kUGxheWVyKHRoaXMucGxheWVyLm5QbGF5UG9ydCwgdGhpcy5wbGF5ZXIpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog5byA5aeL5a+56K6yXHJcbiAgICogQHBhcmFtIG9wdGlvbnNcclxuICAgKi9cclxuICBzdGFydFRhbGsob3B0aW9ucykge1xyXG4gICAgaWYgKCF3aW5kb3cubV9uTW9kdWxlSW5pdGlhbGl6ZWQpIHtcclxuICAgICAgY29uc29sZS5lcnJvcihcIuino+eggeW6k+acquWIneWni+WMluWujOaIkO+8jOivt+eojeWQjuWvueiusu+8gVwiKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgLy8g6K6+572u5q2j5Zyo5a+56K6y55qE5qCH5b+X5L2NXHJcbiAgICB0aGlzLndzUGxheWVyLmlzVGFsa2luZyA9IHRydWU7XHJcbiAgICB0aGlzLmlzVGFsa2luZyA9IHRydWU7XHJcbiAgICAvLyDlvIDlkK/lr7norrLmjInpkq5cclxuICAgICQoXCIudGFsay1pY29uXCIsIHRoaXMuJGVsKS5yZW1vdmVDbGFzcyhcIm9mZlwiKS5hZGRDbGFzcyhcIm9uXCIpO1xyXG4gICAgbGV0IHNlbGYgPSB0aGlzO1xyXG4gICAgY29uc3QgeyB1c2VIMjY0TVNFLCB1c2VIMjY1TVNFIH0gPSB0aGlzLndzUGxheWVyLmNvbmZpZztcclxuICAgIHRoaXMudGFsa1BsYXllciA9IG5ldyBQbGF5ZXJDb250cm9sKHtcclxuICAgICAgcnRzcFVSTDogb3B0aW9ucy5ydHNwVVJMLFxyXG4gICAgICB3c1VSTDogdGhpcy53c1BsYXllci5fX2dldFdTVXJsKG9wdGlvbnMucnRzcFVSTCwgb3B0aW9ucy5zZXJ2ZXJJcCksXHJcbiAgICAgIGlzVGFsa1NlcnZpY2U6IHRydWUsXHJcbiAgICAgIHVzZUgyNjRNU0UsXHJcbiAgICAgIHVzZUgyNjVNU0UsXHJcbiAgICAgIGV2ZW50czoge1xyXG4gICAgICAgIC8vIOaKpemUmVxyXG4gICAgICAgIEVycm9yOiAoZSkgPT4ge1xyXG4gICAgICAgICAgLy8g5a+56K6y5aSx6LSlXHJcbiAgICAgICAgICBpZiAoZS5lcnJvckNvZGUgPT09IFwiNTA0XCIpIHtcclxuICAgICAgICAgICAgc2VsZi5zdG9wVGFsaygpO1xyXG4gICAgICAgICAgICBzZWxmLndzUGxheWVyLnNlbmRNZXNzYWdlKFwiZXJyb3JJbmZvXCIsIGUpO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgIH0sXHJcbiAgICB9KTtcclxuICAgIHRoaXMudGFsa1BsYXllci50YWxrKFwib25cIik7XHJcbiAgICB3aW5kb3cud3NQbGF5ZXJNYW5hZ2VyLmJpbmRQbGF5ZXIoXHJcbiAgICAgIHRoaXMudGFsa1BsYXllci5uUGxheVBvcnQsXHJcbiAgICAgIHRoaXMudGFsa1BsYXllclxyXG4gICAgKTtcclxuICAgICQoXCIud3MtdGFsa2luZ1wiLCB0aGlzLiRlbCkuY3NzKHsgdmlzaWJpbGl0eTogXCJ2aXNpYmxlXCIgfSk7XHJcbiAgICAvLyDlhbPpl63op4bpopHnmoTpn7PpopFcclxuICAgIHRoaXMucGxheWVyLnNldEF1ZGlvVm9sdW1lKDApO1xyXG4gICAgJChcIi5hdWRpby1pY29uXCIsIHRoaXMuJGVsKS5yZW1vdmVDbGFzcyhcIm9uXCIpLmFkZENsYXNzKFwib2ZmXCIpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog57uT5p2f5a+56K6yXHJcbiAgICovXHJcbiAgc3RvcFRhbGsoKSB7XHJcbiAgICB0aGlzLnRhbGtQbGF5ZXIgJiZcclxuICAgICAgd2luZG93LndzUGxheWVyTWFuYWdlci51bmJpbmRQbGF5ZXIodGhpcy50YWxrUGxheWVyLm5QbGF5UG9ydCk7XHJcbiAgICAvLyDorr7nva7nu5PmnZ/lr7norrLnmoTmoIflv5fkvY1cclxuICAgIGlmICh0aGlzLmlzVGFsa2luZykge1xyXG4gICAgICB0aGlzLndzUGxheWVyLmlzVGFsa2luZyA9IGZhbHNlO1xyXG4gICAgICB0aGlzLmlzVGFsa2luZyA9IGZhbHNlO1xyXG4gICAgfVxyXG4gICAgaWYgKHRoaXMudGFsa1BsYXllcikge1xyXG4gICAgICB0aGlzLnRhbGtQbGF5ZXIudGFsayhcIm9mZlwiKTtcclxuICAgICAgdGhpcy50YWxrUGxheWVyID0gbnVsbDtcclxuICAgIH1cclxuICAgIC8vIOWFs+mXreWvueiusuaMiemSrlxyXG4gICAgJChcIi50YWxrLWljb25cIiwgdGhpcy4kZWwpLnJlbW92ZUNsYXNzKFwib25cIikuYWRkQ2xhc3MoXCJvZmZcIik7XHJcbiAgICAkKFwiLndzLXRhbGtpbmdcIiwgdGhpcy4kZWwpLmNzcyh7IHZpc2liaWxpdHk6IFwiaGlkZGVuXCIgfSk7XHJcbiAgfVxyXG59XHJcblxyXG5leHBvcnQgZGVmYXVsdCBSZWFsUGxheWVySXRlbTtcclxuIiwiaW1wb3J0IFBsYXllckl0ZW0gZnJvbSBcIi4vUGxheWVySXRlbVwiO1xyXG5pbXBvcnQgeyBTcGlubmVyIH0gZnJvbSBcIi4vc3BpblwiO1xyXG5pbXBvcnQgQ09OU1RBTlQgZnJvbSBcIi4vQ09OU1RBTlRcIjtcclxuXHJcbmNvbnN0IFBsYXllckNvbnRyb2wgPSB3aW5kb3cuUGxheWVyQ29udHJvbDtcclxuXHJcbi8qIC0tLS0tLS0tLS0tLS0tLS0gUmVjb3JkUGxheWVySXRlbSAtLS0tLS0tLS0tLS0tLS0tICovXHJcbmNsYXNzIFJlY29yZFBsYXllckl0ZW0gZXh0ZW5kcyBQbGF5ZXJJdGVtIHtcclxuICAvKipcclxuICAgKiBAcGFyYW0geyp9IG9wdC53cmFwcGVyRG9tSWQg54i257qnaWRcclxuICAgKiBAcGFyYW0geyp9IG9wdC5pbmRleCDntKLlvJVcclxuICAgKi9cclxuICBjb25zdHJ1Y3RvcihvcHQpIHtcclxuICAgIHN1cGVyKG9wdCk7XHJcbiAgICAvLyDlgI3pgJ9cclxuICAgIHRoaXMuc3BlZWQgPSAxO1xyXG4gICAgdGhpcy5jYW52YXNJZCA9IGAke3RoaXMuZG9tSWR9LXJlY29yZGNhbnZhc2A7XHJcbiAgICB0aGlzLml2c0NhbnZhc0lkID0gYCR7dGhpcy5kb21JZH0taXZzLWxpdmVjYW52YXNgO1xyXG4gICAgdGhpcy52aWRlb0lkID0gYCR7dGhpcy5kb21JZH0tcmVjb3JkVmlkZW9gO1xyXG4gICAgdGhpcy5jdXJUaW1lc3RhbXAgPSAwO1xyXG4gICAgdGhpcy5pbml0RG9tKCk7XHJcbiAgICB0aGlzLmRlZmF1bHRTdGF0dXMgPSAkKFwiLmRlZmF1bHQtc3RhdHVzXCIsIHRoaXMuJGVsKTtcclxuICAgIHRoaXMuZXJyb3IgPSAkKFwiLmVycm9yXCIsIHRoaXMuJGVsKTtcclxuICAgIHRoaXMuY29udHJvbGxlciA9ICQoXCIucGxheWVyLWNvbnRyb2xcIiwgdGhpcy4kZWwpO1xyXG4gICAgdGhpcy50aW1lSW5mbyA9ICQoXCIudGltZS1pbmZvXCIsIHRoaXMuJGVsKTtcclxuICAgIHRoaXMuaW5pdE1vdXNlRXZlbnQoKTtcclxuICAgIC8qKlxyXG4gICAgICogdGhpcy5zdGF0ZSDlvZPliY1QbGF5ZXLnirbmgIFcclxuICAgICAqIGNyZWF0ZWQsIHJlYWR5LCBwbGF5aW5nLCBwYXVzZSwgc3RvcCwgY2xvc2VkLCBlcnJvclxyXG4gICAgICovXHJcbiAgICB0aGlzLnNldFN0YXR1cyhcImNyZWF0ZWRcIik7XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqIOaSreaUvuWZqOaooeadv1xyXG4gICAqL1xyXG4gIGdldFRlbXBsYXRlKCkge1xyXG4gICAgbGV0IHRlbXBsYXRlID0gYFxyXG4gICAgICAgIDxkaXYgaWQ9XCIke3RoaXMuZG9tSWR9XCIgY2xhc3M9XCJ3c3BsYXllci1pdGVtIHdzcGxheWVyLWl0ZW0tJHtcclxuICAgICAgdGhpcy5pbmRleFxyXG4gICAgfSAke3RoaXMuaW5kZXggPT09IDAgPyBcInNlbGVjdGVkXCIgOiBcInVuc2VsZWN0ZWRcIn1cIj5cclxuICAgICAgICAgICAgPGNhbnZhcyBpZD1cIiR7XHJcbiAgICAgICAgICAgICAgdGhpcy5jYW52YXNJZFxyXG4gICAgICAgICAgICB9XCIgY2xhc3M9XCJraW5kLXN0cmVhbS1jYW52YXNcIiBraW5kLWNoYW5uZWwtaWQ9XCIwXCIgd2lkdGg9XCI4MDBcIiBoZWlnaHQ9XCI2MDBcIj48L2NhbnZhcz5cclxuICAgICAgICAgICAgPHZpZGVvIGlkPVwiJHtcclxuICAgICAgICAgICAgICB0aGlzLnZpZGVvSWRcclxuICAgICAgICAgICAgfVwiIGNsYXNzPVwia2luZC1zdHJlYW0tY2FudmFzXCIga2luZC1jaGFubmVsLWlkPVwiMFwiIG11dGVkIHN0eWxlPVwiZGlzcGxheTpub25lXCIgd2lkdGg9XCI4MDBcIiBoZWlnaHQ9XCI2MDBcIj48L3ZpZGVvPlxyXG4gICAgICAgICAgICA8Y2FudmFzIGlkPVwiJHtcclxuICAgICAgICAgICAgICB0aGlzLml2c0NhbnZhc0lkXHJcbiAgICAgICAgICAgIH1cIiBjbGFzcz1cImtpbmQtc3RyZWFtLWNhbnZhc1wiIHN0eWxlPVwicG9zaXRpb246IGFic29sdXRlXCIga2luZC1jaGFubmVsLWlkPVwiMFwiIHdpZHRoPVwiODAwXCIgaGVpZ2h0PVwiNjAwXCI+PC9jYW52YXM+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJkZWZhdWx0LXN0YXR1c1wiPlxyXG4gICAgICAgICAgICAgICAgPGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL2RlZmF1bHQucG5nXCIgYWx0PVwiXCI+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwicGxheWVyLWNvbnRyb2wgdG9wLWNvbnRyb2wtYmFyXCI+XHJcbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInN0cmVhbS1pbmZvXCI+PC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uc1wiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiByZWNvcmQtaWNvblwiIHRpdGxlPVwi5b2V5YOPXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uIGF1ZGlvLWljb24gb2ZmXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm9wdC1pY29uIGNhcHR1cmUtaWNvblwiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiBjbG9zZS1pY29uXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJwbGF5ZXItY29udHJvbCByZWNvcmQtY29udHJvbC1iYXJcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3c3BsYXllci1wcm9ncmVzcy1iYXJcIj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicHJvZ3Jlc3MtYmFyX2JhY2tncm91bmRcIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicHJvZ3Jlc3MtYmFyX2hvdmVyX2xpZ2h0XCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInByb2dyZXNzLWJhcl9saWdodFwiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicmVjb3JkLWNvbnRyb2wtbGVmdFwiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiBwbGF5LWN0cmwtYnRuIHBsYXktaWNvbiBwbGF5XCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInRpbWUtaW5mb1wiPjwvZGl2Pi88ZGl2IGNsYXNzPVwidGltZS1sb25nXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJyZWNvcmQtY29udHJvbC1yaWdodFwiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJvcHQtaWNvbiBjbG9zZS1pY29uXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJlcnJvclwiPlxyXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImVycm9yLW1lc3NhZ2VcIj48L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJwbGF5LXBhdXNlLXdyYXBwZXJcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJwbGF5LWN0cmwtYnRuIGNlbnRlci1wbGF5LWljb25cIj48L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgYDtcclxuICAgIHJldHVybiB0ZW1wbGF0ZTtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog5LqL5Lu255uR5ZCsXHJcbiAgICovXHJcbiAgaW5pdE1vdXNlRXZlbnQoKSB7XHJcbiAgICBzdXBlci5pbml0TW91c2VFdmVudCgpO1xyXG4gICAgdGhpcy5oaWRlVGltZXIgPSBudWxsO1xyXG4gICAgdGhpcy4kZWwub24oXCJtb3VzZWVudGVyIG1vdXNlbW92ZVwiLCAoZXZ0KSA9PiB7XHJcbiAgICAgIC8vIOmdnuWIm+W7uuWSjOWFs+mXreeKtuaAge+8jOaYvuekuueKtuaAgeadoe+8jOWPr+WFs+mXreinhumikVxyXG4gICAgICBpZiAoIVtcImNyZWF0ZWRcIiwgXCJjbG9zZWRcIl0uaW5jbHVkZXModGhpcy5zdGF0dXMpKSB7XHJcbiAgICAgICAgdGhpcy5zZXREb21WaXNpYmxlKCQoXCIucGxheWVyLWNvbnRyb2xcIiwgJChgIyR7dGhpcy5kb21JZH1gKSksIHRydWUpO1xyXG4gICAgICB9XHJcbiAgICAgIGlmICh0aGlzLnN0YXR1cyA9PT0gXCJwbGF5aW5nXCIpIHtcclxuICAgICAgICB0aGlzLmhpZGVUaW1lciAmJiBjbGVhclRpbWVvdXQodGhpcy5oaWRlVGltZXIpO1xyXG4gICAgICB9IGVsc2UgaWYgKHRoaXMuc3RhdHVzID09PSBcInJlYWR5XCIpIHtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5wcm9ncmVzc0JhciwgdHJ1ZSk7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgdGhpcy4kZWwub24oXCJtb3VzZWxlYXZlXCIsIChldnQpID0+IHtcclxuICAgICAgaWYgKHRoaXMuc3RhdHVzID09PSBcInBhdXNlXCIpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICAgIH1cclxuICAgICAgdGhpcy5oaWRlVGltZXIgPSBzZXRUaW1lb3V0KCgpID0+IHtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUoJChcIi5wbGF5ZXItY29udHJvbFwiLCAkKGAjJHt0aGlzLmRvbUlkfWApKSwgZmFsc2UpO1xyXG4gICAgICB9LCAzMDApO1xyXG4gICAgfSk7XHJcbiAgICAkKFwiLndzcGxheWVyLXByb2dyZXNzLWJhclwiLCB0aGlzLiRlbCkub24oXCJtb3VzZW1vdmVcIiwgKGV2dCkgPT4ge1xyXG4gICAgICAkKFwiLnByb2dyZXNzLWJhcl9ob3Zlcl9saWdodFwiLCB0aGlzLiRlbCkuY3NzKHtcclxuICAgICAgICB3aWR0aDogZXZ0Lm9mZnNldFggKyBcInB4XCIsXHJcbiAgICAgIH0pO1xyXG4gICAgfSk7XHJcbiAgICAkKFwiLndzcGxheWVyLXByb2dyZXNzLWJhclwiLCB0aGlzLiRlbCkub24oXCJtb3VzZWxlYXZlXCIsIChldnQpID0+IHtcclxuICAgICAgJChcIi5wcm9ncmVzcy1iYXJfaG92ZXJfbGlnaHRcIiwgdGhpcy4kZWwpLmNzcyh7XHJcbiAgICAgICAgd2lkdGg6IDAsXHJcbiAgICAgIH0pO1xyXG4gICAgfSk7XHJcbiAgICAkKFwiLnBsYXktY3RybC1idG5cIiwgdGhpcy4kZWwpLmNsaWNrKChldnQpID0+IHtcclxuICAgICAgaWYgKHRoaXMuc3RhdHVzID09PSBcInBsYXlpbmdcIikge1xyXG4gICAgICAgIC8vIOato+WcqOaSreaUvu+8jOaaguWBnOaSreaUvlxyXG4gICAgICAgIHRoaXMucGF1c2UoKTtcclxuICAgICAgICAkKFwiLnBsYXktaWNvblwiLCB0aGlzLiRlbCkucmVtb3ZlQ2xhc3MoXCJwbGF5XCIpLmFkZENsYXNzKFwicGF1c2VcIik7XHJcbiAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgLy8g5pqC5YGc5pKt5pS+54q25oCB77yM5omT5byAXHJcbiAgICAgICAgdGhpcy5wbGF5KCk7XHJcbiAgICAgICAgJChcIi5wbGF5LWljb25cIiwgdGhpcy4kZWwpLnJlbW92ZUNsYXNzKFwicGF1c2VcIikuYWRkQ2xhc3MoXCJwbGF5XCIpO1xyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog6K6+572u54q25oCB77yM5ZCM5pe25o6n5Yi257uE5Lu25pi+56S6XHJcbiAgICogY3JlYXRlZCwgcmVhZHksIHBsYXlpbmcsIHBhdXNlLCBzdG9wLCBjbG9zZWQsIGVycm9yXHJcbiAgICovXHJcbiAgc2V0U3RhdHVzKHN0YXR1cywgbXNnKSB7XHJcbiAgICAvLyDnirbmgIHmlLnlj5jml7bvvIzlkJHlpJblj5HpgIHnirbmgIHlj5jliqjmg4XlhrVcclxuICAgIHRoaXMud3NQbGF5ZXIuc2VuZE1lc3NhZ2UoXCJzdGF0dXNDaGFuZ2VkXCIsIHtcclxuICAgICAgc3RhdHVzLFxyXG4gICAgICB3aW5kb3dJbmRleDogdGhpcy5pbmRleCxcclxuICAgIH0pO1xyXG4gICAgdGhpcy5zdGF0dXMgPSBzdGF0dXM7XHJcbiAgICBzd2l0Y2ggKHRoaXMuc3RhdHVzKSB7XHJcbiAgICAgIGNhc2UgXCJjcmVhdGVkXCI6XHJcbiAgICAgIGNhc2UgXCJjbG9zZWRcIjpcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5kZWZhdWx0U3RhdHVzLCB0cnVlKTtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5lcnJvciwgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmNvbnRyb2xsZXIsIGZhbHNlKTtcclxuICAgICAgICAkKFwiLmF1ZGlvLWljb25cIiwgdGhpcy4kZWwpLnJlbW92ZUNsYXNzKFwib25cIikuYWRkQ2xhc3MoXCJvZmZcIik7XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGNhc2UgXCJyZWFkeVwiOlxyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmRlZmF1bHRTdGF0dXMsIGZhbHNlKTtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5lcnJvciwgZmFsc2UpO1xyXG4gICAgICAgIGJyZWFrO1xyXG4gICAgICBjYXNlIFwicGxheWluZ1wiOlxyXG4gICAgICAgICQoXCIjd3MtcmVjb3JkLXRpbWUtYm94XCIpLmNzcyh7IHZpc2liaWxpdHk6IFwidmlzaWJsZVwiIH0pO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmRlZmF1bHRTdGF0dXMsIGZhbHNlKTtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5lcnJvciwgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSgkKFwiLnBsYXktcGF1c2Utd3JhcHBlclwiLCB0aGlzLiRlbCksIGZhbHNlKTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgY2FzZSBcInBhdXNlXCI6XHJcbiAgICAgICAgdGhpcy5zZXREb21WaXNpYmxlKHRoaXMuZGVmYXVsdFN0YXR1cywgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSh0aGlzLmVycm9yLCBmYWxzZSk7XHJcbiAgICAgICAgdGhpcy5zZXREb21WaXNpYmxlKHRoaXMuY29udHJvbGxlciwgZmFsc2UpO1xyXG4gICAgICAgIHRoaXMuc2V0RG9tVmlzaWJsZSgkKFwiLnBsYXktcGF1c2Utd3JhcHBlclwiLCB0aGlzLiRlbCksIHRydWUpO1xyXG4gICAgICAgIGJyZWFrO1xyXG4gICAgICBjYXNlIFwiZXJyb3JcIjpcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5kZWZhdWx0U3RhdHVzLCBmYWxzZSk7XHJcbiAgICAgICAgJChcIi5lcnJvci1tZXNzYWdlXCIsIHRoaXMuJGVsKS50ZXh0KFxyXG4gICAgICAgICAgQ09OU1RBTlQuZXJyb3JWaWRlb0luZm9bbXNnLmVycm9yQ29kZV1cclxuICAgICAgICAgICAgPyBDT05TVEFOVC5lcnJvclZpZGVvSW5mb1ttc2cuZXJyb3JDb2RlXVxyXG4gICAgICAgICAgICA6IENPTlNUQU5ULmVycm9yVmlkZW9JbmZvW1wiZGVmYXVsdEVycm9yTXNnXCJdXHJcbiAgICAgICAgKTtcclxuICAgICAgICB0aGlzLnNldERvbVZpc2libGUodGhpcy5lcnJvciwgdHJ1ZSk7XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGRlZmF1bHQ6XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICB9XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqIOaSreaUvuW9leWDj1xyXG4gICAqIEBwYXJhbSB7U3RyaW5nfSBvcHRpb25zLmRlY29kZU1vZGUg5Y+v6YCJ5Y+C5pWwIHZpZGVvIHwgY2FudmFzXHJcbiAgICogQHBhcmFtIHtTdHJpbmd9IG9wdGlvbnMud3NVUkwg5Y+v6YCJ5Y+C5pWwXHJcbiAgICogQHBhcmFtIHtGdW5jdGlvbn0gb3B0aW9ucy5yZWNvcmRTb3VyY2UgMj3orr7lpIfvvIwzPeS4reW/g1xyXG4gICAqIHJlY29yZFNvdXJjZSA9PSAyIOiuvuWkh+W9leWDj++8jOaMieeFp+aXtumXtOaWueW8j+aSreaUvlxyXG4gICAqIEBwYXJhbSB7U3RyaW5nfSBvcHRpb25zLnJ0c3BVUkwgU3RyaW5nXHJcbiAgICogQHBhcmFtIHtOdW1iZXIgfCBTdHJpbmd9IG9wdGlvbnMuc3RhcnRUaW1lIOW8gOWni+aXtumXtCDml7bpl7TmiLPmiJbogIUnMjAyMS0wOS0xOCAxNTo0MDowMCfmoLzlvI/nmoTml7bpl7TlrZfnrKbkuLJcclxuICAgKiBAcGFyYW0ge051bWJlciB8IFN0cmluZ30gb3B0aW9ucy5lbmRUaW1lIOe7k+adn+aXtumXtCDml7bpl7TmiLPmiJbogIUnMjAyMS0wOS0xOCAxNTo0MDowMCfmoLzlvI/nmoTml7bpl7TlrZfnrKbkuLJcclxuICAgKiBAcGFyYW0ge0Z1bmN0aW9ufSBvcHRpb25zLnJlbG9hZCDph43mlrDmi4nmtYHnmoTlm57osIPlh73mlbDvvIznlKjkuo7ml7bpl7Tlm57mlL7vvIzov5Tlm55wcm9taXNlXHJcbiAgICogcmVsb2FkKG5ld1N0YXJUaW1lLCBlbmRUaW1lKS50aGVuKG5ld1J0c3BVcmwgPT4geyBwbGF5IGNvbnRpbnVlfSlcclxuICAgKiByZWNvcmRTb3VyY2UgPT0gMyDkuK3lv4PlvZXlg4/vvIzmjInnhafmlofku7bmlrnlvI/mkq3mlL5cclxuICAgKiBAcGFyYW0ge0Z1bmN0aW9ufSBvcHRpb25zLlJlY29yZEZpbGVzIOaWh+S7tuWIl+ihqFxyXG4gICAqIEBwYXJhbSB7RnVuY3Rpb259IG9wdGlvbnMuZ2V0UnRzcCDmlofku7bliJfooahcclxuICAgKiBnZXRSdHNwKGZpbGUpLnRoZW4obmV3UnRzcFVybCA9PiB7IHBsYXkgY29udGludWV9KVxyXG4gICAqL1xyXG4gIGluaXQob3B0aW9ucykge1xyXG4gICAgaWYgKCF3aW5kb3cubV9uTW9kdWxlSW5pdGlhbGl6ZWQpIHtcclxuICAgICAgY29uc29sZS5lcnJvcihcIuino+eggeW6k+acquWIneWni+WMluWujOaIkO+8jOivt+eojeWQjuaSreaUvu+8gVwiKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgdGhpcy5vcHRpb25zID0gb3B0aW9ucztcclxuICAgIGlmICh0aGlzLnBsYXllcikge1xyXG4gICAgICBpZiAodGhpcy5pc0F1ZGlvUGxheSkge1xyXG4gICAgICAgIC8vIOato+WcqOaSreaUvuWImeWFs+mXreWjsOmfs1xyXG4gICAgICAgICQoXCIuYXVkaW8taWNvblwiLCB0aGlzLiRlbCkucmVtb3ZlQ2xhc3MoXCJvblwiKS5hZGRDbGFzcyhcIm9mZlwiKTtcclxuICAgICAgfVxyXG4gICAgICB0aGlzLmNsb3NlKHRydWUpO1xyXG4gICAgfVxyXG4gICAgaWYgKHRoaXMuc3Bpbm5lcikge1xyXG4gICAgICB0aGlzLnNwaW5uZXIuc3RvcCgpO1xyXG4gICAgfVxyXG4gICAgdGhpcy5zcGlubmVyID0gbmV3IFNwaW5uZXIoe1xyXG4gICAgICBjb2xvcjogXCIjZmZmZmZmXCIsXHJcbiAgICB9KS5zcGluKHRoaXMuJGVsWzBdKTtcclxuICAgIHRoaXMuY3JlYXRlUGxheWVyKG9wdGlvbnMpO1xyXG4gIH1cclxuICBjcmVhdGVQbGF5ZXIob3B0aW9ucykge1xyXG4gICAgbGV0IHNlbGYgPSB0aGlzO1xyXG4gICAgY29uc3QgeyB1c2VIMjY0TVNFLCB1c2VIMjY1TVNFIH0gPSB0aGlzLndzUGxheWVyLmNvbmZpZztcclxuICAgIHRoaXMucGxheWVyID0gbmV3IFBsYXllckNvbnRyb2woe1xyXG4gICAgICB3c1VSTDogb3B0aW9ucy53c1VSTCxcclxuICAgICAgcnRzcFVSTDogb3B0aW9ucy5ydHNwVVJMLFxyXG4gICAgICBpc1BsYXliYWNrOiBvcHRpb25zLmlzUGxheWJhY2ssXHJcbiAgICAgIHVzZUgyNjRNU0UsXHJcbiAgICAgIHVzZUgyNjVNU0UsXHJcbiAgICAgIGV2ZW50czoge1xyXG4gICAgICAgIC8vIOW8gOWni+aSreaUvlxyXG4gICAgICAgIFBsYXlTdGFydDogKGUpID0+IHtcclxuICAgICAgICAgIC8vIOW9leWDj+WbnuaUvuiOt+WPluWIsOesrOS4gOW4p+eahOaXtuWAmei/m+ihjOaaguWBnOaSreaUvlxyXG4gICAgICAgICAgY29uc29sZS5sb2coXCJQbGF5U3RhcnRcIik7XHJcbiAgICAgICAgICBzZWxmLnNldFN0YXR1cyhcInBsYXlpbmdcIik7XHJcbiAgICAgICAgICBpZiAob3B0aW9ucy5hdXRvUGF1c2UpIHtcclxuICAgICAgICAgICAgc2VsZi5wYXVzZSgpO1xyXG4gICAgICAgICAgICBzZWxmLnNldFN0YXR1cyhcInBhdXNlXCIpO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLy8g5byA5aeL6Kej56CBXHJcbiAgICAgICAgRGVjb2RlU3RhcnQ6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhcIkRlY29kZVN0YXJ0XCIsIGUpO1xyXG4gICAgICAgICAgc2VsZi5zcGlubmVyLnN0b3AoKTtcclxuICAgICAgICAgIGlmIChlLmRlY29kZU1vZGUgPT09IFwidmlkZW9cIikge1xyXG4gICAgICAgICAgICBzZWxmLnZpZGVvRWxlbS5zdHlsZS5kaXNwbGF5ID0gXCJcIjtcclxuICAgICAgICAgICAgc2VsZi5jYW52YXNFbGVtLnN0eWxlLmRpc3BsYXkgPSBcIm5vbmVcIjtcclxuICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHNlbGYudmlkZW9FbGVtLnN0eWxlLmRpc3BsYXkgPSBcIm5vbmVcIjtcclxuICAgICAgICAgICAgc2VsZi5jYW52YXNFbGVtLnN0eWxlLmRpc3BsYXkgPSBcIlwiO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgICAgLy8g6K6+572u5ouJ5Ly45oiW6ICF6Ieq6YCC5bqUXHJcbiAgICAgICAgICBzZWxmLnVwZGF0ZUFkYXB0ZXIob3B0aW9ucy5wbGF5ZXJBZGFwdGVyLCBlKTtcclxuICAgICAgICAgIC8vIOiLpeinhumikeato+WcqOWKoOi9veS4re+8jOaYr+ayoeacieWuvemrmOeahFxyXG4gICAgICAgICAgJChcIi5zdHJlYW0taW5mb1wiLCAkKGAjJHtzZWxmLmRvbUlkfWApKS50ZXh0KFxyXG4gICAgICAgICAgICBlLndpZHRoID8gYCR7ZS5lbmNvZGVNb2RlfSwgJHtlLndpZHRofSoke2UuaGVpZ2h0fWAgOiBlLmVuY29kZU1vZGVcclxuICAgICAgICAgICk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICAvLyDojrflj5bluKfnjodcclxuICAgICAgICBHZXRGcmFtZVJhdGU6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhcIkdldEZyYW1lUmF0ZTogXCIsIGUpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLy8g5oql6ZSZXHJcbiAgICAgICAgRXJyb3I6IChlKSA9PiB7XHJcbiAgICAgICAgICAvLyDnlLHkuo5lcnJvcumHjOacieW7tui/n+S7u+WKoe+8jOmBv+WFjeW7tui/n+S7u+WKoeinpuWPkeacn+mXtOWIh+aNouinhumikeWvvOiHtOW7tui/n+S7u+WKoeinpuWPkeWIsOaWsOeahOinhumikemHjO+8jOWKoOS4gOS4quWIpOaWrVxyXG4gICAgICAgICAgLy8g5q+P5Liq6KeG6aKR55qEc3ltYm9s6YO95piv5ZSv5LiA55qEXHJcbiAgICAgICAgICBpZiAoc2VsZi5wbGF5ZXIgJiYgZS5zeW1ib2wgPT09IHNlbGYucGxheWVyLndzLnN5bWJvbCkge1xyXG4gICAgICAgICAgICAvLyDmj5DnpLo0MDggM3PotoXml7bvvIzpobXpnaLkuI3mmL7npLrotoXml7ZcclxuICAgICAgICAgICAgaWYgKGUuZXJyb3JDb2RlID09PSBcIjQwOFwiKSB7XHJcbiAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHNlbGYuc3Bpbm5lci5zdG9wKCk7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKFwiRXJyb3I6IFwiICsgSlNPTi5zdHJpbmdpZnkoZSkpO1xyXG4gICAgICAgICAgICBzZWxmLnNldFN0YXR1cyhcImVycm9yXCIsIGUpO1xyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLy8g5paH5Lu25pKt5pS+57uT5p2fXHJcbiAgICAgICAgRmlsZU92ZXI6IChlKSA9PiB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhcIuWbnuaUvuaSreaUvuWujOaIkFwiKTtcclxuICAgICAgICAgIHNlbGYuY2xvc2UoKTtcclxuICAgICAgICAgIHNlbGYud3NQbGF5ZXIucGxheU5leHRSZWNvcmQoc2VsZi5pbmRleCk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICAvLyDojrflj5bop4bpopHml7bpl7Tkv6Hmga9cclxuICAgICAgICBVcGRhdGVQbGF5aW5nVGltZTogKHRpbWVTdGFtcCkgPT4ge1xyXG4gICAgICAgICAgY29uc29sZS5sb2coXCLojrflj5bop4bpopHml7bpl7Tkv6Hmga9cIiwgdGltZVN0YW1wKTtcclxuICAgICAgICAgIHNlbGYuc3RhdHVzID09PSBcInBsYXlpbmdcIiAmJlxyXG4gICAgICAgICAgICBzZWxmLndzUGxheWVyLnNldFBsYXlpbmdUaW1lKHNlbGYuaW5kZXgsIHRpbWVTdGFtcCk7XHJcbiAgICAgICAgfSxcclxuICAgICAgfSxcclxuICAgIH0pO1xyXG4gICAgdGhpcy50aW1lTG9uZyA9IG9wdGlvbnMuZW5kVGltZSAtIG9wdGlvbnMuc3RhcnRUaW1lO1xyXG4gICAgbGV0IHNlY29uZHMgPSB0aGlzLnRpbWVMb25nICUgNjA7XHJcbiAgICBsZXQgbWludXRlcyA9IHBhcnNlSW50KHRoaXMudGltZUxvbmcgLyA2MCkgJSA2MDtcclxuICAgIGxldCBob3VycyA9IHBhcnNlSW50KHRoaXMudGltZUxvbmcgLyAzNjAwKSAlIDYwO1xyXG4gICAgdGhpcy50aW1lTG9uZ1N0ciA9IGAke2hvdXJzID4gMCA/IGhvdXJzICsgXCI6XCIgOiBcIlwifSR7XHJcbiAgICAgIG1pbnV0ZXMgPCAxMCA/IFwiMFwiICsgbWludXRlcyA6IG1pbnV0ZXNcclxuICAgIH06JHtzZWNvbmRzIDwgMTAgPyBcIjBcIiArIHNlY29uZHMgOiBzZWNvbmRzfWA7XHJcbiAgICAkKFwiLnRpbWUtbG9uZ1wiLCB0aGlzLiRlbCkudGV4dCh0aGlzLnRpbWVMb25nU3RyKTtcclxuICAgIHRoaXMuc2V0U3RhdHVzKFwicmVhZHlcIik7XHJcblxyXG4gICAgdGhpcy5wbGF5ZXIuaW5pdCh0aGlzLmNhbnZhc0VsZW0sIHRoaXMudmlkZW9FbGVtLCB0aGlzLml2c0NhbnZhc0VsZW0pO1xyXG4gICAgdGhpcy5wbGF5ZXIuY29ubmVjdCgpO1xyXG4gICAgLy8g5byA5ZCv6KeE5YiZ57q/XHJcbiAgICBpZiAodGhpcy53c1BsYXllci5jb25maWcub3Blbkl2cykge1xyXG4gICAgICB0aGlzLnBsYXllci5vcGVuSVZTKCk7XHJcbiAgICB9XHJcbiAgICB3aW5kb3cud3NQbGF5ZXJNYW5hZ2VyLmJpbmRQbGF5ZXIodGhpcy5wbGF5ZXIublBsYXlQb3J0LCB0aGlzLnBsYXllcik7XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqIOWAjemAn+aSreaUvlxyXG4gICAqIEBwYXJhbSB7TnVtYmVyfSBzcGVlZCDlgI3pgJ9cclxuICAgKi9cclxuICBwbGF5U3BlZWQoc3BlZWQpIHtcclxuICAgIHRoaXMuc3BlZWQgPSBzcGVlZDtcclxuICAgIC8vIOWPquaciTEvMiAxIDLnrYnkuInnp43lgI3pgJ/mlK/mjIHmkq3mlL7lo7Dpn7PvvIzlhbbku5blgI3pgJ/pnIDopoHlhbPpl63lo7Dpn7NcclxuICAgIGlmIChzcGVlZCA8IDAuNSB8fCBzcGVlZCA+IDIpIHtcclxuICAgICAgdGhpcy5wbGF5ZXIuc2V0QXVkaW9Wb2x1bWUoMCk7XHJcbiAgICAgICQoXCIuYXVkaW8taWNvblwiLCB0aGlzLiRlbCkucmVtb3ZlQ2xhc3MoXCJvblwiKS5hZGRDbGFzcyhcIm9mZlwiKTtcclxuICAgICAgdGhpcy5pc0F1ZGlvUGxheSA9IGZhbHNlO1xyXG4gICAgfVxyXG4gICAgdGhpcy5wbGF5ZXIgJiYgdGhpcy5wbGF5ZXIucGxheVNwZWVkKHNwZWVkKTtcclxuICB9XHJcbn1cclxuXHJcbmV4cG9ydCBkZWZhdWx0IFJlY29yZFBsYXllckl0ZW07XHJcbiIsImNsYXNzIFdTUGxheWVyTWFuYWdlciB7XHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLndzUGxheWVyTGlzdCA9IFtdO1xyXG4gICAgICAgIC8vIOerr+WPo+S4jnBsYXllcue7keWumu+8jOWNs2tleSAtIHBvcnTvvIx2YWx1ZSAtIHBsYXllclxyXG4gICAgICAgIHRoaXMucG9ydFRvUGxheWVyID0ge307XHJcbiAgICAgICAgd2luZG93LmNQbHVzVmlzaWJsZURlY0NhbGxCYWNrID0gdGhpcy5jUGx1c1Zpc2libGVEZWNDYWxsQmFjay5iaW5kKHRoaXMpO1xyXG4gICAgICAgIHdpbmRvdy5jRXh0cmFEcmF3RGF0YUNhbGxCYWNrID0gdGhpcy5jRXh0cmFEcmF3RGF0YUNhbGxCYWNrLmJpbmQodGhpcyk7XHJcbiAgICAgICAgd2luZG93LmNFeHRyYURyYXdEcmF3Q2FsbEJhY2sgPSB0aGlzLmNFeHRyYURyYXdEcmF3Q2FsbEJhY2suYmluZCh0aGlzKTtcclxuICAgIH1cclxuXHJcbiAgICAvKipcclxuICAgICAqIOino+eggeW6k+mDveaYr+W8guatpeino+eggeaSreaUvu+8jOiwg+eUqOWFqOWxgOeahGNQbHVzVmlzaWJsZURlY0NhbGxCYWNr5p2l5riy5p+T5pKt5pS+XHJcbiAgICAgKiDlm6DmraTkvb/nlKjmraTmlrnms5XmnaXmjqfliLbkuI3lkIznmoTnoIHmtYHvvIzkvb/nlKjnm7jlr7nlupTnmoTmkq3mlL7lmajov5vooYzmuLLmn5Pmkq3mlL7vvIzovr7liLDnoIHmtYHkuI7mkq3mlL7lmajov5vooYzkuIDkuIDlr7nlupRcclxuICAgICAqIEBwYXJhbSBuUG9ydFxyXG4gICAgICogQHBhcmFtIHBCdWZZXHJcbiAgICAgKiBAcGFyYW0gcEJ1ZlVcclxuICAgICAqIEBwYXJhbSBwQnVmVlxyXG4gICAgICogQHBhcmFtIG5TaXplXHJcbiAgICAgKiBAcGFyYW0gcEZyYW1lSW5mb1xyXG4gICAgICovXHJcbiAgICBjUGx1c1Zpc2libGVEZWNDYWxsQmFjayhuUG9ydCwgcEJ1ZlksIHBCdWZVLCBwQnVmViwgblNpemUsIHBGcmFtZUluZm8pIHtcclxuICAgICAgICB0aGlzLnBvcnRUb1BsYXllcltuUG9ydF0gJiYgdGhpcy5wb3J0VG9QbGF5ZXJbblBvcnRdLnNldEZyYW1lRGF0YShuUG9ydCwgcEJ1ZlksIHBCdWZVLCBwQnVmViwgblNpemUsIHBGcmFtZUluZm8pO1xyXG4gICAgfVxyXG5cclxuICAgIGNFeHRyYURyYXdEYXRhQ2FsbEJhY2soblBvcnQsIG5EYXRhVHlwZSwgcERyYXdEYXRhLCBuRGF0YUxlbikge1xyXG4gICAgICAgIHRoaXMucG9ydFRvUGxheWVyW25Qb3J0XSAmJiB0aGlzLnBvcnRUb1BsYXllcltuUG9ydF0uc2V0SVZTRGF0YShuUG9ydCwgbkRhdGFUeXBlLCBwRHJhd0RhdGEsIG5EYXRhTGVuKTtcclxuICAgIH1cclxuXHJcbiAgICBjRXh0cmFEcmF3RHJhd0NhbGxCYWNrKG5Qb3J0KSB7XHJcbiAgICAgICAgdGhpcy5wb3J0VG9QbGF5ZXJbblBvcnRdICYmIHRoaXMucG9ydFRvUGxheWVyW25Qb3J0XS5kcmF3SVZTRGF0YShuUG9ydCk7XHJcbiAgICB9XHJcblxyXG4gICAgYmluZFBsYXllcihuUG9ydCwgcGxheWVyKSB7XHJcbiAgICAgICAgaWYoIXRoaXMucG9ydFRvUGxheWVyW25Qb3J0XSkge1xyXG4gICAgICAgICAgICB0aGlzLnBvcnRUb1BsYXllcltuUG9ydF0gPSBwbGF5ZXI7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIHVuYmluZFBsYXllcihuUG9ydCkge1xyXG4gICAgICAgIHRoaXMucG9ydFRvUGxheWVyW25Qb3J0XSA9IG51bGw7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkV1NQbGF5ZXIod3NQbGF5ZXIpIHtcclxuICAgICAgICB0aGlzLndzUGxheWVyTGlzdC5wdXNoKCk7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlV1NQbGF5ZXIod3NQbGF5ZXIpIHtcclxuICAgICAgICB0aGlzLndzUGxheWVyTGlzdCA9IHRoaXMud3NQbGF5ZXJMaXN0LmZpbHRlcihpdGVtID0+IGl0ZW0gPT09IHdzUGxheWVyKTtcclxuICAgIH1cclxufVxyXG5cclxuZXhwb3J0IGRlZmF1bHQgV1NQbGF5ZXJNYW5hZ2VyXHJcblxyXG4iLCJjb25zdCB1c2VyQWdlbnRLZXkgPSB7XHJcbiAgICBPcGVyYTogXCJPcGVyYVwiLFxyXG4gICAgQ2hyb21lOiBcIkNocm9tZVwiLFxyXG4gICAgRmlyZWZveDogXCJGaXJlZm94XCIsXHJcbiAgICBFZGdlOiBcIkVkZ2VcIixcclxuICAgIElFOiBcIklFXCIsXHJcbiAgICBTYWZhcmk6IFwiU2FmYXJpXCIsXHJcbn1cclxuXHJcbmZ1bmN0aW9uIGdldEJyb3dzZXJUeXBlKCkge1xyXG4gICAgY29uc3Qge3VzZXJBZ2VudH0gPSBuYXZpZ2F0b3I7XHJcbiAgICAvLyDliKTmlq3mmK/lkKbkuLpFZGdl5rWP6KeI5ZmoXHJcbiAgICBpZih1c2VyQWdlbnQuaW5jbHVkZXMoXCJFZGdlXCIpKSB7XHJcbiAgICAgICAgcmV0dXJuIHVzZXJBZ2VudEtleS5FZGdlO1xyXG4gICAgfVxyXG4gICAgLy8g5Yik5pat5piv5ZCm5Li6RmlyZWZveOa1j+iniOWZqFxyXG4gICAgaWYodXNlckFnZW50LmluY2x1ZGVzKFwiRmlyZWZveFwiKSkge1xyXG4gICAgICAgIHJldHVybiB1c2VyQWdlbnRLZXkuRmlyZWZveDtcclxuICAgIH1cclxuICAgIC8vIOWIpOaWreaYr+WQpuS4ukNocm9tZea1j+iniOWZqFxyXG4gICAgaWYodXNlckFnZW50LmluY2x1ZGVzKFwiQ2hyb21lXCIpKSB7XHJcbiAgICAgICAgcmV0dXJuIHVzZXJBZ2VudEtleS5DaHJvbWU7XHJcbiAgICB9XHJcbiAgICAvLyDliKTmlq3mmK/lkKbkuLpTYWZhcmnmtY/op4jlmahcclxuICAgIGlmKHVzZXJBZ2VudC5pbmNsdWRlcyhcIlNhZmFyaVwiKSkge1xyXG4gICAgICAgIHJldHVybiB1c2VyQWdlbnRLZXkuU2FmYXJpO1xyXG4gICAgfVxyXG4gICAgLy8g5Yik5pat5piv5ZCm5Li6SUXmtY/op4jlmahcclxuICAgIGlmKHVzZXJBZ2VudC5pbmNsdWRlcyhcImNvbXBhdGlibGVcIilcclxuICAgICAgICAmJiB1c2VyQWdlbnQuaW5jbHVkZXMoXCJNU0lFXCIpXHJcbiAgICAgICAgJiYgdXNlckFnZW50LmluY2x1ZGVzKFwiT3BlcmFcIilcclxuICAgICkge1xyXG4gICAgICAgIHJldHVybiB1c2VyQWdlbnRLZXkuSUU7XHJcbiAgICB9XHJcbiAgICAvLyDliKTmlq3mmK/lkKbkuLpPcGVyYea1j+iniOWZqFxyXG4gICAgaWYodXNlckFnZW50LmluY2x1ZGVzKFwiT3BlcmFcIikpIHtcclxuICAgICAgICByZXR1cm4gdXNlckFnZW50S2V5Lk9wZXJhO1xyXG4gICAgfVxyXG4gICAgcmV0dXJuIFwiXCI7XHJcbn1cclxuXHJcbi8vIOiOt+WPlua1j+iniOWZqOS9jeaVsFxyXG5mdW5jdGlvbiBnZXRCcm93c2VyQml0KCkge1xyXG4gICAgcmV0dXJuIG5hdmlnYXRvci51c2VyQWdlbnQuaW5jbHVkZXMoXCJ4NjRcIikgfHwgbmF2aWdhdG9yLnVzZXJBZ2VudC5pbmNsdWRlcyhcIng4Nl82NFwiKSA/IDY0IDogMzI7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGdldEJyb3dzZXJWZXJzaW9uKGJyb3dzZXJUeXBlKSB7XHJcbiAgICBjb25zdCB7dXNlckFnZW50fSA9IG5hdmlnYXRvcjtcclxuICAgIHJldHVybiB1c2VyQWdlbnQuc3BsaXQoYnJvd3NlclR5cGUpWzFdLnNwbGl0KFwiLlwiKVswXS5zbGljZSgxKVxyXG59XHJcblxyXG5mdW5jdGlvbiBjaGVja0Jyb3dzZXIoKSB7XHJcbiAgICBjb25zdCBicm93c2VyVHlwZSA9IGdldEJyb3dzZXJUeXBlKCk7XHJcbiAgICAvLyAzMuS9jea1j+iniOWZqOS5n+mcgOimgeS9v+eUqOWNlee6v+eoi1xyXG4gICAgY29uc3QgYnJvd3NlckJpdCA9IGdldEJyb3dzZXJCaXQoKTtcclxuICAgIGNvbnN0IGJyb3dzZXJWZXJzaW9uID0gZ2V0QnJvd3NlclZlcnNpb24oYnJvd3NlclR5cGUpO1xyXG4gICAgbGV0IGlzVmVyc2lvbkNvbXBsaWFuY2UgPSBmYWxzZTtcclxuICAgIGxldCBlcnJvckNvZGUgPSAwO1xyXG4gICAgc3dpdGNoKGJyb3dzZXJUeXBlKSB7XHJcbiAgICAgICAgY2FzZSB1c2VyQWdlbnRLZXkuQ2hyb21lOlxyXG4gICAgICAgICAgICBpc1ZlcnNpb25Db21wbGlhbmNlID0gYnJvd3NlclZlcnNpb24gPj0gOTEgJiYgYnJvd3NlckJpdCA9PT0gNjQ7XHJcbiAgICAgICAgICAgIGVycm9yQ29kZSA9IDcwMTtcclxuICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgY2FzZSB1c2VyQWdlbnRLZXkuRmlyZWZveDpcclxuICAgICAgICAgICAgaXNWZXJzaW9uQ29tcGxpYW5jZSA9IGJyb3dzZXJWZXJzaW9uID49IDk3O1xyXG4gICAgICAgICAgICBlcnJvckNvZGUgPSA3MDI7XHJcbiAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGNhc2UgdXNlckFnZW50S2V5LkVkZ2U6XHJcbiAgICAgICAgICAgIGlzVmVyc2lvbkNvbXBsaWFuY2UgPSBicm93c2VyVmVyc2lvbiA+PSA5MTtcclxuICAgICAgICAgICAgZXJyb3JDb2RlID0gNzAzO1xyXG4gICAgICAgICAgICBicmVhaztcclxuICAgICAgICBkZWZhdWx0OlxyXG4gICAgICAgICAgICBpc1ZlcnNpb25Db21wbGlhbmNlID0gMDtcclxuICAgIH1cclxuICAgIHJldHVybiB7aXNWZXJzaW9uQ29tcGxpYW5jZSwgYnJvd3NlclR5cGUsIGVycm9yQ29kZX07XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHZhbGlkRnVuY3Rpb24oZm4pIHtcclxuICAgIHJldHVybiB0b1N0cmluZy5jYWxsKGZuKSA9PT0gXCJbb2JqZWN0IEZ1bmN0aW9uXVwiO1xyXG59XHJcblxyXG5mdW5jdGlvbiB2YWxpZE9iamVjdChvYmopIHtcclxuICAgIHJldHVybiB0b1N0cmluZy5jYWxsKG9iaikgPT09IFwiW29iamVjdCBPYmplY3RdXCI7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiDlsIblhaXlj4LnmoTmiYDmnInlr7nosaHlhajpg6jlkIjlubbliLDmlrDlr7nosaHkuK1cclxuICovXHJcbmZ1bmN0aW9uIG1lcmdlT2JqZWN0KCkge1xyXG4gICAgbGV0IHRhcmdldCA9IHt9O1xyXG4gICAgZm9yKGxldCBpID0gMDsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgIGxldCBzb3VyY2UgPSBhcmd1bWVudHNbaV07XHJcbiAgICAgICAgZm9yKGxldCBwcm9wIGluIHNvdXJjZSkge1xyXG4gICAgICAgICAgICBsZXQgdmFsdWUgPSBzb3VyY2VbcHJvcF07XHJcbiAgICAgICAgICAgIGlmKHZhbGlkT2JqZWN0KHZhbHVlKSkge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0W3Byb3BdID0gbWVyZ2VPYmplY3QodmFsdWUpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0W3Byb3BdID0gdmFsdWU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICByZXR1cm4gdGFyZ2V0O1xyXG59XHJcblxyXG5leHBvcnQgZGVmYXVsdCB7XHJcbiAgICBjaGVja0Jyb3dzZXIsXHJcbiAgICB2YWxpZEZ1bmN0aW9uLFxyXG4gICAgbWVyZ2VPYmplY3RcclxufVxyXG4iLCIvKipcclxuICog5LqR5Y+w5Yqf6IO9XHJcbiAqL1xyXG5jbGFzcyBQYW5UaWx0e1xyXG4gICAgY29uc3RydWN0b3Iob3B0aW9ucyA9IHt9LCB3c1BsYXllcikge1xyXG4gICAgICAgIC8vIOS6keWPsOiKgueCuVxyXG4gICAgICAgIHRoaXMuZWwgPSBvcHRpb25zLmVsO1xyXG4gICAgICAgIC8vIHdzUGxheWVy5pKt5pS+57uE5Lu2XHJcbiAgICAgICAgdGhpcy53c1BsYXllciA9IHdzUGxheWVyO1xyXG4gICAgICAgIC8vIOS6keWPsOWFg+e0oFxyXG4gICAgICAgIHRoaXMuJGVsID0gJCgnIycgKyB0aGlzLmVsKVxyXG4gICAgICAgIGlmKHRoaXMuJGVsICYmICF0aGlzLiRlbC5jaGlsZHJlbigpLmxlbmd0aCkge1xyXG4gICAgICAgICAgICB0aGlzLl9fY3JlYXRlUGFuVGlsdCgpO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyDpgInkuK3nmoTnqpflj6PnmoTmraPlnKjmkq3mlL7nmoTpgJrpgZNcclxuICAgICAgICB0aGlzLmNoYW5uZWwgPSBudWxsO1xyXG4gICAgICAgIC8vIOebuOWFs+eahOivt+axguaOpeWPo+aWueazlVxyXG4gICAgICAgIC8vIOS6keWPsOaWueWQkeaOp+WItuaOpeWPo1xyXG4gICAgICAgIHRoaXMuc2V0UHR6RGlyZWN0aW9uID0gb3B0aW9ucy5zZXRQdHpEaXJlY3Rpb247XHJcbiAgICAgICAgLy8g5LqR5Y+w6ZWc5aS05o6n5Yi25o6l5Y+jXHJcbiAgICAgICAgdGhpcy5zZXRQdHpDYW1lcmEgPSBvcHRpb25zLnNldFB0ekNhbWVyYTtcclxuICAgICAgICAvLyDkupHlj7DkuInnu7TlrprkvY3mjqXlj6NcclxuICAgICAgICB0aGlzLmNvbnRyb2xTaXRQb3NpdGlvbiA9IG9wdGlvbnMuY29udHJvbFNpdFBvc2l0aW9uO1xyXG4gICAgICAgIC8vIOS4iee7tOWumuS9jWNhbnZhc+eahOS6i+S7tlxyXG4gICAgICAgIHRoaXMubW91c2Vkb3duQ2FudmFzRXZlbnQgPSB0aGlzLl9fbW91c2Vkb3duQ2FudmFzRXZlbnQuYmluZCh0aGlzKTtcclxuICAgICAgICB0aGlzLm1vdXNlbW92ZUNhbnZhc0V2ZW50ID0gdGhpcy5fX21vdXNlbW92ZUNhbnZhc0V2ZW50LmJpbmQodGhpcyk7XHJcbiAgICAgICAgdGhpcy5tb3VzZXVwQ2FudmFzRXZlbnQgPSB0aGlzLl9fbW91c2V1cENhbnZhc0V2ZW50LmJpbmQodGhpcyk7XHJcbiAgICB9XHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiDorr7nva7pgJrpgZNcclxuICAgICAqIEBwYXJhbSBjaGFubmVsXHJcbiAgICAgKi9cclxuICAgIHNldENoYW5uZWwoY2hhbm5lbCkge1xyXG4gICAgICAgIHRoaXMuY2hhbm5lbCA9IGNoYW5uZWw7XHJcbiAgICAgICAgaWYoIWNoYW5uZWwpIHtcclxuICAgICAgICAgICAgLy8g6YCa6YGT5LiN5a2Y5Zyo77yM5YiZ56aB55So5LqR5Y+wXHJcbiAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1tYXNrJywgdGhpcy4kZWwpLmNzcyh7ZGlzcGxheTogXCJibG9ja1wifSlcclxuICAgICAgICAgICAgdGhpcy5fX3JlbW92ZUNhbnZhc0V2ZW50KCk7XHJcbiAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICB9XHJcbiAgICAgICAgbGV0IGNhcGFiaWxpdHkgPSBjaGFubmVsLmNhcGFiaWxpdHk7XHJcbiAgICAgICAgc3dpdGNoKGNoYW5uZWwuY2FtZXJhVHlwZSArIFwiXCIpIHtcclxuICAgICAgICAgICAgY2FzZSBcIjFcIjogLy8g5p6q5py66YCa6YGTXHJcbiAgICAgICAgICAgICAgICAvLyDmnqrmnLrpgJrpgZPog73lipvpm4bmnInnlLXliqjogZrnhKbmiJbogIXkupHlj7DmjqfliLbml7bvvIzlvIDmlL7lj5jlgI3jgIHlj5jnhKZcclxuICAgICAgICAgICAgICAgIGlmKHBhcnNlSW50KGNhcGFiaWxpdHksIDIpICYgcGFyc2VJbnQoXCIxMDBcIiwgMilcclxuICAgICAgICAgICAgICAgICAgICB8fCBwYXJzZUludChjYXBhYmlsaXR5LCAyKSAmIHBhcnNlSW50KFwiMTAwMDAwMDAwMDAwMDAwMDBcIiwgMilcclxuICAgICAgICAgICAgICAgICkge1xyXG4gICAgICAgICAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1tYXNrLXpvb20nLCB0aGlzLiRlbCkuY3NzKHtkaXNwbGF5OiBcIm5vbmVcIn0pXHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1tYXNrLXpvb20nLCB0aGlzLiRlbCkuY3NzKHtkaXNwbGF5OiBcImJsb2NrXCJ9KVxyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIC8vIOaequacuumAmumBk+iDveWKm+mbhuacieS6keWPsOaOp+WItuaXtu+8jOW8gOaUvuaWueWQkeaOp+WItlxyXG4gICAgICAgICAgICAgICAgaWYocGFyc2VJbnQoY2FwYWJpbGl0eSwgMikgJiBwYXJzZUludChcIjEwMDAwMDAwMDAwMDAwMDAwXCIsIDIpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgJCgnLndzLXBhbi10aWx0LW1hc2stZGlyZWN0aW9uJywgdGhpcy4kZWwpLmNzcyh7ZGlzcGxheTogXCJub25lXCJ9KVxyXG4gICAgICAgICAgICAgICAgICAgIHRoaXMuX19yZW1vdmVDYW52YXNFdmVudCgpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAkKCcud3MtcGFuLXRpbHQtbWFzay1kaXJlY3Rpb24nLCB0aGlzLiRlbCkuY3NzKHtkaXNwbGF5OiBcImJsb2NrXCJ9KVxyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1tYXNrLWFwZXJ0dXJlJywgdGhpcy4kZWwpLmNzcyh7ZGlzcGxheTogXCJibG9ja1wifSlcclxuICAgICAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgICAgICBjYXNlIFwiMlwiOiAvLyDnkIPmnLrpgJrpgZNcclxuICAgICAgICAgICAgICAgIC8vIOeQg+acuumAmumBk++8jOWPr+S7peS9v+eUqOS6keWPsOaJgOacieWKn+iDvVxyXG4gICAgICAgICAgICAgICAgJCgnLndzLXBhbi10aWx0LW1hc2snLCB0aGlzLiRlbCkuY3NzKHtkaXNwbGF5OiBcIm5vbmVcIn0pO1xyXG4gICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgICAgIGRlZmF1bHQ6XHJcbiAgICAgICAgICAgICAgICAvLyDpu5jorqTnpoHnlKjkupHlj7BcclxuICAgICAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1tYXNrJywgdGhpcy4kZWwpLmNzcyh7ZGlzcGxheTogXCJibG9ja1wifSk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLl9fcmVtb3ZlQ2FudmFzRXZlbnQoKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgX19jcmVhdGVQYW5UaWx0KCkge1xyXG4gICAgICAgIHRoaXMuJGVsLmFwcGVuZChgXHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1jb250cm9sXCI+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtY2lyY2xlLXdyYXBwZXJcIj5cclxuICAgICAgICAgICAgICAgICAgICA8IS0t5LqR5Y+w5pa55ZCR5o6n5Yi2LS0+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWNpcmNsZVwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtZGlyZWN0aW9uLWl0ZW1cIj48aW1nIHNyYz1cIi4vc3RhdGljL1dTUGxheWVyL2ljb24vYXJyb3ctdC5zdmdcIiB0aXRsZT1cIuS4ilwiIGRpcmVjdD1cIjFcIi8+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1kaXJlY3Rpb24taXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9hcnJvdy1ydC5zdmdcIiB0aXRsZT1cIuWPs+S4ilwiIGRpcmVjdD1cIjdcIi8+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1kaXJlY3Rpb24taXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9hcnJvdy1yLnN2Z1wiIHRpdGxlPVwi5Y+zXCIgZGlyZWN0PVwiNFwiLz48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWRpcmVjdGlvbi1pdGVtXCI+PGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL2Fycm93LXJiLnN2Z1wiIHRpdGxlPVwi5Y+z5LiLXCIgZGlyZWN0PVwiOFwiLz48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWRpcmVjdGlvbi1pdGVtXCI+PGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL2Fycm93LWIuc3ZnXCIgdGl0bGU9XCLkuItcIiBkaXJlY3Q9XCIyXCIvPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtZGlyZWN0aW9uLWl0ZW1cIj48aW1nIHNyYz1cIi4vc3RhdGljL1dTUGxheWVyL2ljb24vYXJyb3ctbGIuc3ZnXCIgdGl0bGU9XCLlt6bkuItcIiBkaXJlY3Q9XCI2XCIvPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtZGlyZWN0aW9uLWl0ZW1cIj48aW1nIHNyYz1cIi4vc3RhdGljL1dTUGxheWVyL2ljb24vYXJyb3ctbC5zdmdcIiB0aXRsZT1cIuW3plwiIGRpcmVjdD1cIjNcIi8+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1kaXJlY3Rpb24taXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9hcnJvdy1sdC5zdmdcIiB0aXRsZT1cIuW3puS4ilwiIGRpcmVjdD1cIjVcIi8+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1pbm5lci1jaXJjbGVcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbWdcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjbGFzcz1cIndzLXBhbi10aWx0LXB6dC1zZWxlY3RcIlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYz1cIi4vc3RhdGljL1dTUGxheWVyL2ljb24vcHR6LXNlbGVjdC5zdmdcIlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDp0aXRsZT1cIuS4iee7tOWumuS9jVwiXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgXHJcbiAgICAgICAgICAgICAgICA8IS0t5LqR5Y+w5Y+Y5YCN44CB6IGa54Sm44CB5YWJ5ZyI5o6n5Yi2LS0+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY2xvdWQtY29udHJvbC13cmFwcGVyXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWNvbnRyb2wtaXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9wdHotaWNvbjEuc3ZnXCIgdGl0bGU9XCLlj5jlgI0tXCIgb3BlcmF0ZVR5cGU9XCIxXCIgZGlyZWN0PVwiMlwiLz48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtY29udHJvbC1pdGVtXCI+PGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL3B0ei1pY29uMi5zdmdcIiB0aXRsZT1cIuWPmOWAjStcIiBvcGVyYXRlVHlwZT1cIjFcIiBkaXJlY3Q9XCIxXCIvPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjbG91ZC1jb250cm9sLXNlcGFyYXRlXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWNvbnRyb2wtaXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9wdHotaWNvbjMuc3ZnXCIgdGl0bGU9XCLogZrnhKYtXCIgb3BlcmF0ZVR5cGU9XCIyXCIgZGlyZWN0PVwiMlwiLz48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtY29udHJvbC1pdGVtXCI+PGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL3B0ei1pY29uNC5zdmdcIiB0aXRsZT1cIuiBmueEpitcIiBvcGVyYXRlVHlwZT1cIjJcIiBkaXJlY3Q9XCIxXCIvPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjbG91ZC1jb250cm9sLXNlcGFyYXRlXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXBhbi10aWx0LWNvbnRyb2wtaXRlbVwiPjxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9wdHotaWNvbjUuc3ZnXCIgdGl0bGU9XCLlhYnlnIgtXCIgb3BlcmF0ZVR5cGU9XCIzXCIgZGlyZWN0PVwiMlwiLz48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtY29udHJvbC1pdGVtXCI+PGltZyBzcmM9XCIuL3N0YXRpYy9XU1BsYXllci9pY29uL3B0ei1pY29uNi5zdmdcIiB0aXRsZT1cIuWFieWciCtcIiBvcGVyYXRlVHlwZT1cIjNcIiBkaXJlY3Q9XCIxXCIvPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICBcclxuICAgICAgICAgICAgICAgIDwhLS3pga7nvanvvIzlvZPpgJrpgZPmsqHmnInkupHlj7Dlip/og73ml7bvvIzkvb/nlKjpga7nvanpga7kvY/kupHlj7DmjInpkq4tLT5cclxuICAgICAgICAgICAgICAgIDwhLS3mlrnlkJHmjInpkq7pga7nvaktLT5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1tYXNrIHdzLXBhbi10aWx0LW1hc2stZGlyZWN0aW9uXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8IS0t5Y+Y5YCN44CB6IGa54Sm6YGu572pLS0+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtcGFuLXRpbHQtbWFzayB3cy1wYW4tdGlsdC1tYXNrLXpvb21cIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgIDwhLS3lhYnlnIjpga7nvaktLT5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1wYW4tdGlsdC1tYXNrIHdzLXBhbi10aWx0LW1hc2stYXBlcnR1cmVcIj48L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgYClcclxuICAgICAgICAkKCcud3MtcGFuLXRpbHQtZGlyZWN0aW9uLWl0ZW0gaW1nJywgdGhpcy4kZWwpLm1vdXNldXAoZSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuX19zZXRQdHpEaXJlY3Rpb24oZS50YXJnZXQuZ2V0QXR0cmlidXRlKFwiZGlyZWN0XCIpLCBcIjBcIilcclxuICAgICAgICB9KVxyXG4gICAgICAgICQoJy53cy1wYW4tdGlsdC1kaXJlY3Rpb24taXRlbSBpbWcnLCB0aGlzLiRlbCkubW91c2Vkb3duKGUgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLl9fc2V0UHR6RGlyZWN0aW9uKGUudGFyZ2V0LmdldEF0dHJpYnV0ZShcImRpcmVjdFwiKSwgXCIxXCIpXHJcbiAgICAgICAgfSlcclxuICAgICAgICAkKCcud3MtcGFuLXRpbHQtY29udHJvbC1pdGVtIGltZycsIHRoaXMuJGVsKS5tb3VzZXVwKGUgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLl9fc2V0UHR6Q2FtZXJhKGUudGFyZ2V0LmdldEF0dHJpYnV0ZShcIm9wZXJhdGVUeXBlXCIpLCBlLnRhcmdldC5nZXRBdHRyaWJ1dGUoXCJkaXJlY3RcIiksIFwiMFwiKVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgJCgnLndzLXBhbi10aWx0LWNvbnRyb2wtaXRlbSBpbWcnLCB0aGlzLiRlbCkubW91c2Vkb3duKGUgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLl9fc2V0UHR6Q2FtZXJhKGUudGFyZ2V0LmdldEF0dHJpYnV0ZShcIm9wZXJhdGVUeXBlXCIpLCBlLnRhcmdldC5nZXRBdHRyaWJ1dGUoXCJkaXJlY3RcIiksIFwiMVwiKVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgLy8g5byA5ZCv5LiJ57u05a6a5L2NXHJcbiAgICAgICAgJCgnLndzLXBhbi10aWx0LXB6dC1zZWxlY3QnLCB0aGlzLiRlbCkuY2xpY2soZSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuX19vcGVuU2l0UG9zaXRpb24oKTtcclxuICAgICAgICB9KVxyXG4gICAgfVxyXG5cclxuICAgIF9fc2V0UHR6RGlyZWN0aW9uKGRpcmVjdCwgY29tbWFuZCkge1xyXG4gICAgICAgIC8vIOaWueWQke+8mjE95LiK77yMMj3kuIvvvIwzPeW3pu+8jDQ95Y+z77yMNT3lt6bkuIrvvIw2PeW3puS4i++8jDc95Y+z5LiK77yMOD3lj7PkuItcclxuICAgICAgICBjb25zdCBwYXJhbXMgPSB7XHJcbiAgICAgICAgICAgIHByb2plY3Q6ICdQU0RLJyxcclxuICAgICAgICAgICAgbWV0aG9kOiAnRE1TLlB0ei5PcGVyYXRlRGlyZWN0JyxcclxuICAgICAgICAgICAgZGF0YToge1xyXG4gICAgICAgICAgICAgICAgZGlyZWN0LFxyXG4gICAgICAgICAgICAgICAgY29tbWFuZCxcclxuICAgICAgICAgICAgICAgIHN0ZXBYOiAnNCcsXHJcbiAgICAgICAgICAgICAgICBzdGVwWTogJzQnLFxyXG4gICAgICAgICAgICAgICAgY2hhbm5lbElkOiB0aGlzLmNoYW5uZWwuaWRcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgdGhpcy5zZXRQdHpEaXJlY3Rpb24gJiYgdGhpcy5zZXRQdHpEaXJlY3Rpb24ocGFyYW1zKS50aGVuKCkuY2F0Y2goZXJyID0+IHtcclxuICAgICAgICAgICAgY29uc29sZS5lcnJvcign5LqR5Y+w5pa55ZCR5o6n5Yi2ZXJyOicsIGVycik7XHJcbiAgICAgICAgfSlcclxuICAgIH1cclxuXHJcbiAgICBfX3NldFB0ekNhbWVyYShvcGVyYXRlVHlwZSwgZGlyZWN0LCBjb21tYW5kKSB7XHJcbiAgICAgICAgLy8gb3BlcmF0ZVR5cGUg5pON5L2c57G75Z6L77yaMT3lj5jlgI3vvIwyPeWPmOeEpu+8jDM95YWJ5ZyIXHJcbiAgICAgICAgLy8gZGlyZWN0IFx05pa55ZCR77yaMT3lop7liqDvvIwyPeWHj+Wwj1xyXG4gICAgICAgIC8vIGNvbW1hbmQgXHTlkb3ku6TvvJowPeWBnOatou+8jDE95byA5ZCvXHJcbiAgICAgICAgY29uc3QgcGFyYW1zID0ge1xyXG4gICAgICAgICAgICBwcm9qZWN0OiAnUFNESycsXHJcbiAgICAgICAgICAgIG1ldGhvZDogJ0RNUy5QdHouT3BlcmF0ZUNhbWVyYScsXHJcbiAgICAgICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgICAgICAgIG9wZXJhdGVUeXBlLFxyXG4gICAgICAgICAgICAgICAgZGlyZWN0LFxyXG4gICAgICAgICAgICAgICAgY29tbWFuZCxcclxuICAgICAgICAgICAgICAgIHN0ZXA6ICc0JyxcclxuICAgICAgICAgICAgICAgIGNoYW5uZWxJZDogdGhpcy5jaGFubmVsLmlkXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIHRoaXMuc2V0UHR6Q2FtZXJhICYmIHRoaXMuc2V0UHR6Q2FtZXJhKHBhcmFtcykudGhlbigpLmNhdGNoKGVyciA9PiB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoJ+S6keWPsOaWueWQkeaOp+WItmVycjonLCBlcnIpO1xyXG4gICAgICAgIH0pXHJcbiAgICB9XHJcblxyXG4gICAgLy8g5byA5ZCv5LiJ57u05a6a5L2NXHJcbiAgICBfX29wZW5TaXRQb3NpdGlvbigpIHtcclxuICAgICAgICB0aGlzLm9wZW5TaXRQb3NpdGlvbkZsYWcgPSAhdGhpcy5vcGVuU2l0UG9zaXRpb25GbGFnO1xyXG4gICAgICAgIGlmKCF0aGlzLmNhbnZhc0VsZW0pIHtcclxuICAgICAgICAgICAgLy8g6I635Y+W5LiJ57u05a6a5L2N55qEY2FudmFz6IqC54K5XHJcbiAgICAgICAgICAgIGxldCBwbGF5ZXJMaXN0ID0gdGhpcy53c1BsYXllci5wbGF5ZXJMaXN0O1xyXG4gICAgICAgICAgICBsZXQgc2VsZWN0SW5kZXggPSB0aGlzLndzUGxheWVyLnNlbGVjdEluZGV4O1xyXG4gICAgICAgICAgICB0aGlzLmNhbnZhc0VsZW0gPSBwbGF5ZXJMaXN0W3NlbGVjdEluZGV4XS5wenRDYW52YXNFbGVtO1xyXG4gICAgICAgICAgICAvLyDmt7vliqDkuovku7ZcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNFbGVtLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZWRvd25cIiwgdGhpcy5tb3VzZWRvd25DYW52YXNFdmVudClcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNFbGVtLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZW1vdmVcIiwgdGhpcy5tb3VzZW1vdmVDYW52YXNFdmVudClcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNFbGVtLmFkZEV2ZW50TGlzdGVuZXIoXCJtb3VzZXVwXCIsIHRoaXMubW91c2V1cENhbnZhc0V2ZW50KVxyXG4gICAgICAgICAgICB0aGlzLmNhbnZhc0NvbnRleHQgPSB0aGlzLmNhbnZhc0VsZW0uZ2V0Q29udGV4dChcIjJkXCIpO1xyXG4gICAgICAgICAgICB0aGlzLmNhbnZhc0NvbnRleHQubGluZVdpZHRoID0gMjtcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNDb250ZXh0LnN0cm9rZVN0eWxlID0gXCIjMDA5Y2ZmXCI7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGlmKHRoaXMub3BlblNpdFBvc2l0aW9uRmxhZykge1xyXG4gICAgICAgICAgICAkKHRoaXMuY2FudmFzRWxlbSkuY3NzKHtkaXNwbGF5OiBcImJsb2NrXCJ9KVxyXG4gICAgICAgICAgICAkKCcud3MtcGFuLXRpbHQtcHp0LXNlbGVjdCcsIHRoaXMuJGVsKS5hdHRyKHtzcmM6IFwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9wdHotc2VsZWN0LWhvdmVyLnN2Z1wifSlcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAkKHRoaXMuY2FudmFzRWxlbSkuY3NzKHtkaXNwbGF5OiBcIm5vbmVcIn0pXHJcbiAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1wenQtc2VsZWN0JywgdGhpcy4kZWwpLmF0dHIoe3NyYzogXCIuL3N0YXRpYy9XU1BsYXllci9pY29uL3B0ei1zZWxlY3Quc3ZnXCJ9KVxyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBfX21vdXNlZG93bkNhbnZhc0V2ZW50KGUpIHtcclxuICAgICAgICBpZihlLm9mZnNldFggfHwgZS5sYXllclgpIHtcclxuICAgICAgICAgICAgLy8g56Gu5a6a6LW35aeL54K55Y+K57uY5Yi254q25oCBXHJcbiAgICAgICAgICAgIHRoaXMucG9pbnRYID0gZS5vZmZzZXRYIHx8IGUubGF5ZXJYO1xyXG4gICAgICAgICAgICB0aGlzLnBvaW50WSA9IGUub2Zmc2V0WSB8fCBlLmxheWVyWTtcclxuICAgICAgICAgICAgdGhpcy5zdGFydERyYXcgPSB0cnVlO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBfX21vdXNlbW92ZUNhbnZhc0V2ZW50KGUpIHtcclxuICAgICAgICBpZiAodGhpcy5zdGFydERyYXcgJiYgKGUub2Zmc2V0WCB8fCBlLmxheWVyWCkpIHtcclxuICAgICAgICAgICAgY29uc3QgcG9pbnRYID0gZS5vZmZzZXRYIHx8IGUubGF5ZXJYO1xyXG4gICAgICAgICAgICBjb25zdCBwb2ludFkgPSBlLm9mZnNldFkgfHwgZS5sYXllclk7XHJcbiAgICAgICAgICAgIGNvbnN0IHJlYWN0VyA9IChwb2ludFggLSB0aGlzLnBvaW50WCk7XHJcbiAgICAgICAgICAgIGNvbnN0IHJlYWN0SCA9IChwb2ludFkgLSB0aGlzLnBvaW50WSk7XHJcbiAgICAgICAgICAgIC8vIOa4heepuueUu+W4g1xyXG4gICAgICAgICAgICB0aGlzLmNhbnZhc0NvbnRleHQuY2xlYXJSZWN0KDAsIDAsIHRoaXMuY2FudmFzRWxlbS53aWR0aCwgdGhpcy5jYW52YXNFbGVtLmhlaWdodCk7XHJcbiAgICAgICAgICAgIC8vIOW8gOWni+e7mOWItlxyXG4gICAgICAgICAgICB0aGlzLmNhbnZhc0NvbnRleHQuYmVnaW5QYXRoKCk7XHJcbiAgICAgICAgICAgIHRoaXMuY2FudmFzQ29udGV4dC5zdHJva2VSZWN0KHRoaXMucG9pbnRYLCB0aGlzLnBvaW50WSwgcmVhY3RXLCByZWFjdEgpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBfX21vdXNldXBDYW52YXNFdmVudChlKSB7XHJcbiAgICAgICAgaWYgKGUub2Zmc2V0WCB8fCBlLmxheWVyWCkge1xyXG4gICAgICAgICAgICB0aGlzLnN0YXJ0RHJhdyA9IGZhbHNlO1xyXG4gICAgICAgICAgICAvLyDnu5PmnZ/ngrnlnZDmoIdcclxuICAgICAgICAgICAgY29uc3QgcG9pbnRYID0gZS5vZmZzZXRYIHx8IGUubGF5ZXJYO1xyXG4gICAgICAgICAgICBjb25zdCBwb2ludFkgPSBlLm9mZnNldFkgfHwgZS5sYXllclk7XHJcbiAgICAgICAgICAgIC8vIOWumuS5ieaNoueul+WQjueahOWdkOagh1xyXG4gICAgICAgICAgICBsZXQgZFBvaW50WCA9ICcnO1xyXG4gICAgICAgICAgICBsZXQgZFBvaW50WSA9ICcnO1xyXG4gICAgICAgICAgICBsZXQgZFBvaW50WiA9ICcnO1xyXG4gICAgICAgICAgICAvLyDnn6nlvaLmoYbkuK3lv4PngrlcclxuICAgICAgICAgICAgY29uc3QgY2FudmFzQ2VudGVyWCA9IChwb2ludFggKyB0aGlzLnBvaW50WCkgLyAyO1xyXG4gICAgICAgICAgICBjb25zdCBjYW52YXNDZW50ZXJZID0gKHBvaW50WSArIHRoaXMucG9pbnRZKSAvIDI7XHJcbiAgICAgICAgICAgIC8vIOinhumikeeUu+mdouS4reW/g+eCuVxyXG4gICAgICAgICAgICBjb25zdCB2aWRlb0NlbnRlclggPSB0aGlzLmNhbnZhc0VsZW0ud2lkdGggLyAyO1xyXG4gICAgICAgICAgICBjb25zdCB2aWRlb0NlbnRlclkgPSB0aGlzLmNhbnZhc0VsZW0uaGVpZ2h0IC8gMjtcclxuICAgICAgICAgICAgLy8g55+p5b2i5a696auYXHJcbiAgICAgICAgICAgIGNvbnN0IHJlYWN0VyA9IE1hdGguYWJzKHBvaW50WCAtIHRoaXMucG9pbnRYKTtcclxuICAgICAgICAgICAgY29uc3QgcmVhY3RIID0gTWF0aC5hYnMocG9pbnRZIC0gdGhpcy5wb2ludFkpO1xyXG4gICAgICAgICAgICBjb25zdCBiUmV2ZXJzZSA9IHBvaW50WCA8IHRoaXMucG9pbnRYO1xyXG4gICAgICAgICAgICAvLyB4LCB55o2i566XXHJcbiAgICAgICAgICAgIGRQb2ludFggPSAoY2FudmFzQ2VudGVyWCAtIHZpZGVvQ2VudGVyWCkgKiA4MTkyICogMiAvIHRoaXMuY2FudmFzRWxlbS53aWR0aDtcclxuICAgICAgICAgICAgZFBvaW50WSA9IChjYW52YXNDZW50ZXJZIC0gdmlkZW9DZW50ZXJZKSAqIDgxOTIgKiAyIC8gdGhpcy5jYW52YXNFbGVtLmhlaWdodDtcclxuICAgICAgICAgICAgLy8geuWAvOaNoueul1xyXG4gICAgICAgICAgICBpZiAocG9pbnRYID09PSB0aGlzLnBvaW50WCB8fCBwb2ludFkgPT09IHRoaXMucG9pbnRZKVxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICBkUG9pbnRaID0gMDtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIC8vIOmdouenr+avlFxyXG4gICAgICAgICAgICAgICAgZFBvaW50WiA9ICh0aGlzLmNhbnZhc0VsZW0ud2lkdGggKiB0aGlzLmNhbnZhc0VsZW0uaGVpZ2h0KSAvIChyZWFjdFcgKiByZWFjdEgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGJSZXZlcnNlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8g5Y+N5ZCR5qGG6YCJeuS4uui0n+WAvFxyXG4gICAgICAgICAgICAgICAgICAgIGRQb2ludFogPSAtZFBvaW50WjtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyDmuIXnqbrnlLvluINcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNDb250ZXh0LmNsZWFyUmVjdCgwLCAwLCB0aGlzLmNhbnZhc0VsZW0ud2lkdGgsIHRoaXMuY2FudmFzRWxlbS5oZWlnaHQpO1xyXG4gICAgICAgICAgICB0aGlzLl9fY29udHJvbFNpdFBvc2l0aW9uKGRQb2ludFgsIGRQb2ludFksIGRQb2ludFopO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICAvLyDnu5nkuInnu7TlrprkvY3nmoRjYW52YXPljrvpmaTkuovku7ZcclxuICAgIF9fcmVtb3ZlQ2FudmFzRXZlbnQoKSB7XHJcbiAgICAgICAgaWYodGhpcy5jYW52YXNFbGVtKSB7XHJcbiAgICAgICAgICAgIHRoaXMuY2FudmFzRWxlbS5yZW1vdmVFdmVudExpc3RlbmVyKFwibW91c2Vkb3duXCIsIHRoaXMubW91c2Vkb3duQ2FudmFzRXZlbnQpXHJcbiAgICAgICAgICAgIHRoaXMuY2FudmFzRWxlbS5yZW1vdmVFdmVudExpc3RlbmVyKFwibW91c2Vtb3ZlXCIsIHRoaXMubW91c2Vtb3ZlQ2FudmFzRXZlbnQpXHJcbiAgICAgICAgICAgIHRoaXMuY2FudmFzRWxlbS5yZW1vdmVFdmVudExpc3RlbmVyKFwibW91c2V1cFwiLCB0aGlzLm1vdXNldXBDYW52YXNFdmVudClcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNFbGVtID0gbnVsbDtcclxuICAgICAgICAgICAgdGhpcy5jYW52YXNDb250ZXh0ID0gbnVsbDtcclxuICAgICAgICAgICAgdGhpcy5vcGVuU2l0UG9zaXRpb25GbGFnID0gZmFsc2U7XHJcbiAgICAgICAgICAgICQoJy53cy1wYW4tdGlsdC1wenQtc2VsZWN0JywgdGhpcy4kZWwpLmF0dHIoe3NyYzogXCIuL3N0YXRpYy9XU1BsYXllci9pY29uL3B0ei1zZWxlY3Quc3ZnXCJ9KVxyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICAvLyDnu5nlkI7lj7Dlj5HpgIHkuInnu7TlrprkvY3mlbDmja5cclxuICAgIF9fY29udHJvbFNpdFBvc2l0aW9uKGRQb2ludFgsIGRQb2ludFksIGRQb2ludFopIHtcclxuICAgICAgICBjb25zdCBwYXJhbXMgPSB7XHJcbiAgICAgICAgICAgIHByb2plY3Q6ICdQU0RLJyxcclxuICAgICAgICAgICAgbWV0aG9kOiAnRE1TLlB0ei5TaXRQb3NpdGlvbicsXHJcbiAgICAgICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgICAgICAgIG1hZ2ljSWQ6IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCdtYWdpY0lkJykgfHwgJycsXHJcbiAgICAgICAgICAgICAgICBwb2ludFg6IFN0cmluZyhNYXRoLnJvdW5kKGRQb2ludFgpKSxcclxuICAgICAgICAgICAgICAgIHBvaW50WTogU3RyaW5nKE1hdGgucm91bmQoZFBvaW50WSkpLFxyXG4gICAgICAgICAgICAgICAgcG9pbnRaOiBTdHJpbmcoTWF0aC5yb3VuZChkUG9pbnRaKSksXHJcbiAgICAgICAgICAgICAgICBleHRlbmQ6ICcxJyxcclxuICAgICAgICAgICAgICAgIGNoYW5uZWxJZDogdGhpcy5jaGFubmVsLmlkXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIHRoaXMuY29udHJvbFNpdFBvc2l0aW9uICYmIHRoaXMuY29udHJvbFNpdFBvc2l0aW9uKHBhcmFtcykudGhlbihyZXMgPT4ge1xyXG4gICAgICAgICAgICAvLyBpZihyZXMuZGF0YS5yZXN1bHQgJiYgcmVzLmRhdGEucmVzdWx0ID09PSBcIjBcIikge1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IG1lc3NhZ2UgPSBg5LqR5Y+w6KKr55So5oi3JHtyZXMuZGF0YS5sb2NrVXNlci51c2VyTmFtZX3plIHlrppgO1xyXG4gICAgICAgICAgICAvLyAgICAgdGhpcy4kbWVzc2FnZS53YXJuaW5nKG1lc3NhZ2UpO1xyXG4gICAgICAgICAgICAvLyB9XHJcbiAgICAgICAgfSkuY2F0Y2goZXJyID0+IHtcclxuICAgICAgICAgICAgLy8gaWYocmVzLmRhdGEuY29kZSA9PT0gMTEwMykge1xyXG4gICAgICAgICAgICAvLyAgICAgdGhpcy4kbWVzc2FnZS5lcnJvcihg5o6n5Yi25LqR5Y+w5LiJ57u05a6a5L2N5aSx6LSl77ya5oKo5peg5p2D6ZmQ6L+b6KGM5q2k5pON5L2cYCk7XHJcbiAgICAgICAgICAgIC8vIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICByZXMuZGF0YSAmJiByZXMuZGF0YS5jb2RlICYmIHRoaXMuJG1lc3NhZ2UuZXJyb3IodGhpcy4kdChgZXJyb3Ike3Jlcy5kYXRhLmNvZGV9YCkpXHJcbiAgICAgICAgICAgIC8vIH1cclxuICAgICAgICAgICAgY29uc29sZS5lcnJvcign5LiJ57u05a6a5L2N5o6n5Yi2ZXJyOicsIGVycik7XHJcbiAgICAgICAgfSlcclxuICAgIH1cclxufVxyXG5cclxuZXhwb3J0IGRlZmF1bHQgUGFuVGlsdFxyXG4iLCJleHBvcnQgZGVmYXVsdCB7XHJcbiAgLy8g5Yib5bu65pKt5pS+5Zmo5pe25pi+56S66KeG6aKR6Lev5pWwXHJcbiAgbnVtOiAxLFxyXG4gIC8vIOWIm+W7uuacgOWkp+eahOinhumikei3r+aVsFxyXG4gIG1heE51bTogMSxcclxuICAvLyDmmK/lkKbmmL7npLrlt6XlhbfmoI/vvIzpu5jorqTmmL7npLpcclxuICBzaG93Q29udHJvbDogdHJ1ZSxcclxuICAvLyDmmK/lkKbliqjmgIHliqDovb3op6PnoIHlupMg6buY6K6k5Yqo5oCB5Yqg6L29XHJcbiAgLy8g5b2T5Yqo5oCB5Yqg6L295Ye6546w5LiA57O75YiX6Zeu6aKY5pe277yM5Y+v5Lul5pS55oiQ6Z2Z5oCB5Yqg6L29XHJcbiAgaXNEeW5hbWljTG9hZExpYjogdHJ1ZSxcclxuICAvLyDmmK/lkKblj6rliqDovb3ljZXnur/nqIvop6PnoIHlupNcclxuICBvbmx5TG9hZFNpbmdsZUxpYjogZmFsc2UsXHJcbiAgLy8g5piv5ZCm5by65Yi25L2/55Sobmdpbnjku6PnkIbovazlj5HvvIjpu5jorqR3c3PpnIDopoHku6PnkIbovazlj5HvvIx3c+S4jemcgOimgeS7o+eQhui9rOWPke+8iVxyXG4gIHVzZU5naW54UHJveHk6IGZhbHNlLFxyXG4gIC8vIOaYr+WQpuWQr+eUqOinhOWImee6v++8jOm7mOiupOWQr+eUqFxyXG4gIG9wZW5JdnM6IHRydWUsXHJcbiAgLy8gSDI2NOaYr+WQpuS9v+eUqOehrOino++8jOm7mOiupOS9v+eUqOehrOino1xyXG4gIHVzZUgyNjRNU0U6IHRydWUsXHJcbiAgLy8gSDI2NeaYr+WQpuS9v+eUqOehrOino++8jOm7mOiupOS9v+eUqOehrOino1xyXG4gIHVzZUgyNjVNU0U6IHRydWUsXHJcbiAgLy8g5o6n5Yi26KeG6aKR5LiK6Z2iYmFy5qCP5oyJ6ZKu5piv5ZCm5pi+56S6XHJcbiAgc2hvd0ljb25zOiB7XHJcbiAgICBzdHJlYW1DaGFuZ2VTZWxlY3Q6IGZhbHNlLCAvLyDkuLvovoXnoIHmtYHliIfmjaJcclxuICAgIHRhbGtJY29uOiBmYWxzZSwgLy8g5a+56K6y5Yqf6IO95oyJ6ZKuXHJcbiAgICBsb2NhbFJlY29yZEljb246IHRydWUsIC8vIOacrOWcsOW9leWDj+WKn+iDveaMiemSrlxyXG4gICAgYXVkaW9JY29uOiB0cnVlLCAvLyDlvIDlkK/lhbPpl63lo7Dpn7PmjInpkq5cclxuICAgIHNuYXBzaG90SWNvbjogdHJ1ZSwgLy8g5oqT5Zu+5oyJ6ZKuXHJcbiAgICBjbG9zZUljb246IHRydWUsIC8vIOWFs+mXreinhumikeaMiemSrlxyXG4gIH0sXHJcbn07XHJcblxyXG4iLCJpbXBvcnQgQ09OU1RBTlQgZnJvbSBcIi4vQ09OU1RBTlRcIjtcclxuaW1wb3J0IFJlYWxQbGF5ZXJJdGVtIGZyb20gXCIuL1JlYWxQbGF5ZXJcIjtcclxuaW1wb3J0IFJlY29yZFBsYXllckl0ZW0gZnJvbSBcIi4vUmVjb3JkUGxheWVyXCI7XHJcbmltcG9ydCBXU1BsYXllck1hbmFnZXIgZnJvbSBcIi4vV1NQbGF5ZXJNYW5hZ2VyXCI7XHJcbmltcG9ydCB1dGlscyBmcm9tIFwiLi91dGlsc1wiO1xyXG4vLyBpbXBvcnQgUHJvY2VkdXJlIGZyb20gXCIuL1Byb2NlZHVyZVwiO1xyXG5pbXBvcnQgUGFuVGlsdCBmcm9tIFwiLi9QYW5UaWx0XCI7XHJcbmltcG9ydCBjb25maWcgZnJvbSBcIi4vY29uZmlnL2NvbmZpZ1wiO1xyXG5cclxuLyogLS0tLS0tLS0tLS0tLS0tLSBXU1BsYXllciAtLS0tLS0tLS0tLS0tLS0tICovXHJcbmNsYXNzIFdTUGxheWVyIHtcclxuICBzdGF0aWMgdmVyc2lvbiA9IFwiMS4yLjRcIjtcclxuICAvKipcclxuICAgKiDmnoTpgKDlh73mlbBcclxuICAgKiBAcGFyYW0ge1N0cmluZ30gb3B0aW9ucy50eXBlICDlv4XkvKAg57G75Z6LIHJlYWwgfCByZWNvcmRcclxuICAgKiBAcGFyYW0ge1N0cmluZ30gb3B0aW9ucy5zZXJ2ZXJJcCDlv4XkvKAg5pyN5Yqh5ZmoSVBcclxuICAgKiBAcGFyYW0ge2Jvb2xlYW59IG9wdGlvbnMuZWwg5b+F5LygIOaSreaUvuWZqOaJgOWcqOeahOWuueWZqGlkXHJcbiAgICovXHJcbiAgY29uc3RydWN0b3Iob3B0aW9ucykge1xyXG4gICAgaWYgKCFvcHRpb25zLnR5cGUgfHwgIW9wdGlvbnMuc2VydmVySXApIHtcclxuICAgICAgY29uc29sZS5lcnJvcihgdHlwZSwgc2VydmVySXAg5Li65b+F5Lyg5Y+C5pWw77yM6K+35qCh6aqM5YWl5Y+CYCk7XHJcbiAgICAgIHJldHVybiBmYWxzZTtcclxuICAgIH1cclxuICAgIHRoaXMub3B0aW9ucyA9IG9wdGlvbnM7XHJcbiAgICB0aGlzLnR5cGUgPSBvcHRpb25zLnR5cGU7XHJcbiAgICB0aGlzLmNvbmZpZyA9IHV0aWxzLm1lcmdlT2JqZWN0KGNvbmZpZywgb3B0aW9ucy5jb25maWcpO1xyXG4gICAgLy8g5pyN5Yqh5ZmoSVDvvIxuZ2lueOaJgOWcqOeahOacjeWKoeWZqGlwXHJcbiAgICB0aGlzLnNlcnZlcklwID0gb3B0aW9ucy5zZXJ2ZXJJcCA/IG9wdGlvbnMuc2VydmVySXAgOiBsb2NhdGlvbi5ob3N0bmFtZTtcclxuICAgIC8vIOWkhOeQhuaSreaUvua1geeoi1xyXG4gICAgLy8gdGhpcy5wcm9jZWR1cmUgPSBuZXcgUHJvY2VkdXJlKHtcclxuICAgIC8vICAgdHlwZTogdGhpcy50eXBlLFxyXG4gICAgLy8gICBwbGF5ZXI6IHRoaXMsXHJcbiAgICAvLyAgIGdldFJlYWxSdHNwOiBvcHRpb25zLmdldFJlYWxSdHNwLFxyXG4gICAgLy8gICBnZXRSZWNvcmRzOiBvcHRpb25zLmdldFJlY29yZHMsXHJcbiAgICAvLyAgIGdldFJlY29yZFJ0c3BCeVRpbWU6IG9wdGlvbnMuZ2V0UmVjb3JkUnRzcEJ5VGltZSxcclxuICAgIC8vICAgZ2V0UmVjb3JkUnRzcEJ5RmlsZTogb3B0aW9ucy5nZXRSZWNvcmRSdHNwQnlGaWxlLFxyXG4gICAgLy8gICBnZXRUYWxrUnRzcDogb3B0aW9ucy5nZXRUYWxrUnRzcCxcclxuICAgIC8vIH0pO1xyXG4gICAgdGhpcy5lbCA9IG9wdGlvbnMuZWw7XHJcbiAgICAvLyDmnYPpmZDmjqfliLZcclxuICAgIHRoaXMuZmV0Y2hDaGFubmVsQXV0aG9yaXR5ID0gb3B0aW9ucy5nZXRDaGFubmVsQXV0aG9yaXR5O1xyXG4gICAgdGhpcy4kZWwgPSAkKFwiI1wiICsgdGhpcy5lbCk7XHJcbiAgICB0aGlzLndpZHRoID0gdGhpcy4kZWwuYXR0cihcIndpZHRoXCIpO1xyXG4gICAgdGhpcy5oZWlnaHQgPSB0aGlzLiRlbC5hdHRyKFwiaGVpZ2h0XCIpO1xyXG4gICAgdGhpcy4kZWwuaGVpZ2h0KGAke3RoaXMuaGVpZ2h0fXB4YCk7XHJcbiAgICB0aGlzLiRlbC53aWR0aChgJHt0aGlzLndpZHRofXB4YCk7XHJcbiAgICB0aGlzLiRlbC5hZGRDbGFzcyhgd3MtcGxheWVyYCk7XHJcbiAgICAvLyDmt7vliqBwbGF5ZXItd3JhcHBlclxyXG4gICAgdGhpcy4kZWwuYXBwZW5kKGA8ZGl2IGNsYXNzPVwicGxheWVyLXdyYXBwZXJcIj48L2Rpdj5gKTtcclxuICAgIHRoaXMuJHdyYXBwZXIgPSAkKFwiLnBsYXllci13cmFwcGVyXCIsIHRoaXMuJGVsKTtcclxuICAgIHRoaXMucGxheWVyTGlzdCA9IFtdO1xyXG4gICAgdGhpcy5wbGF5ZXJBZGFwdGVyID0gXCJzZWxmQWRhcHRpb25cIjsgLy8gc2VsZkFkYXB0aW9uIOiHqumAguW6lCAgc3RyZXRjaGluZyDmi4nkvLhcclxuICAgIHRoaXMuY2FudmFzID0ge307XHJcbiAgICB0aGlzLmN0eCA9IHt9O1xyXG4gICAgdGhpcy5zaG93TnVtID0gMTsgLy8g5q2j5Zyo5pi+56S655qE6Lev5pWwXHJcbiAgICB0aGlzLm1heFdpbmRvdyA9IDE7IC8vIOWPr+aYvuekuueahOacgOWkp+i3r+aVsFxyXG4gICAgLyoqXHJcbiAgICAgKiBtZXRob2TjgIFkYXRhOiDmlrnms5XlkI3jgIHmlbDmja7ku6Xlj4rkvZznlKjlpoLkuIvvvJpcclxuICAgICAqIHNlbGVjdFdpbmRvd0NoYW5nZWTvvJrliIfmjaLnqpflj6Plm57osIMgICAgZGF0Ye+8mmluZGV46YCJ5oup55qE56qX5Y+j57Si5byVXHJcbiAgICAgKiB3aW5kb3dOdW1DaGFuZ2Vk77ya5pi+56S655qE6Lev5pWw5Y+Y5YyW5Zue6LCDICAgIGRhdGHvvJpudW3mmL7npLrnmoTot6/mlbBcclxuICAgICAqIHN0YXR1c0NoYW5nZWTvvJrop4bpopHnirbmgIHmlLnlj5jlm57osIMgICAgZGF0Ye+8mntzdGF0dXPvvJrop4bpopHnirbmgIHvvIx3aW5kb3dJbmRleO+8mueKtuaAgeWPmOWMlueahOeql+WPo+e0ouW8lX1cclxuICAgICAqL1xyXG4gICAgdGhpcy5zZW5kTWVzc2FnZSA9XHJcbiAgICAgIG9wdGlvbnMucmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIgfHwgZnVuY3Rpb24gKG1ldGhvZCwgZGF0YSkge307IC8vIOe7memhueebruWPkemAgeaVsOaNrlxyXG4gICAgJCh0aGlzLiRlbCkuYXR0cihcImluaXRlZFwiLCB0cnVlKTtcclxuICAgIGxldCB7IGlzVmVyc2lvbkNvbXBsaWFuY2UsIGJyb3dzZXJUeXBlLCBlcnJvckNvZGUgfSA9IHV0aWxzLmNoZWNrQnJvd3NlcigpO1xyXG4gICAgLy8g5Yik5pat5Y2P6K6uXHJcbiAgICBsZXQgaXNIdHRwcyA9IGxvY2F0aW9uLnByb3RvY29sID09PSBcImh0dHBzOlwiO1xyXG4gICAgLy8g6K6+572u5Yqo5oCB5Yqg6L2977yM5YiZ6L+b6KGM5Yqo5oCB5Yqg6L296Kej56CB5bqTXHJcbiAgICAvLyDmoLnmja7njq/looPnmoTlrp7pmYXmg4XlhrXliqDovb3kuI3lkIznmoTop6PnoIHlupNcclxuICAgIHRoaXMuY29uZmlnLmlzRHluYW1pY0xvYWRMaWIgJiZcclxuICAgICAgdGhpcy5sb2FkTGliREhQbGF5KGlzSHR0cHMsIGlzVmVyc2lvbkNvbXBsaWFuY2UpO1xyXG4gICAgLy8g6K6+572u5pyA5aSn5Y+v5pi+56S655qE6Lev5pWwXHJcbiAgICB0aGlzLnNldE1heFdpbmRvdygpO1xyXG4gICAgLy8g5Y+M5Ye75YiH5o2i56qX5Y+j5pe277yM6ZyA6KaB6K6w5b2V5LmL5YmN5pi+56S655qE56qX5Y+jXHJcbiAgICB0aGlzLmJlZm9yZVNob3dOdW0gPSAxO1xyXG4gICAgc3dpdGNoICh0aGlzLnR5cGUpIHtcclxuICAgICAgY2FzZSBcInJlYWxcIjpcclxuICAgICAgICB0aGlzLmNyZWF0ZVJlYWxQbGF5ZXIob3B0aW9ucyk7XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGNhc2UgXCJyZWNvcmRcIjpcclxuICAgICAgICB0aGlzLmNyZWF0ZVJlY29yZFBsYXllcihvcHRpb25zKTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgZGVmYXVsdDpcclxuICAgICAgICBicmVhaztcclxuICAgIH1cclxuICAgIHRoaXMuc2V0U2VsZWN0SW5kZXgoMCk7XHJcbiAgICB0aGlzLnNldFBsYXllck51bSh0aGlzLmNvbmZpZy5udW0pO1xyXG4gICAgdGhpcy5zZXRDYW52YXNHZXRDb250ZXh0KCk7XHJcbiAgICB0aGlzLmJpbmRVcGRhdGVQbGF5ZXJXaW5kb3cgPSB0aGlzLl9fdXBkYXRlUGxheWVyV2luZG93LmJpbmQodGhpcyk7XHJcbiAgICAvLyDmtY/op4jlmajnqpflj6Pkuovku7bvvJrmlLnlj5jliIbovqjnjodcclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFwicmVzaXplXCIsIHRoaXMuYmluZFVwZGF0ZVBsYXllcldpbmRvdyk7XHJcbiAgICBpZiAoIXdpbmRvdy53c1BsYXllck1hbmFnZXIpIHtcclxuICAgICAgd2luZG93LndzUGxheWVyTWFuYWdlciA9IG5ldyBXU1BsYXllck1hbmFnZXIoKTtcclxuICAgIH1cclxuICB9XHJcblxyXG4gIHNldENhbnZhc0dldENvbnRleHQoKSB7XHJcbiAgICAvLyDorr7nva5jYW52YXPlsZ7mgKfvvIznlKjkuo7mraPluLjmipPlm77vvIzkuI3orr7nva7kvJrlr7zoh7TmipPlm77lpLHotKVcclxuICAgIGlmICghd2luZG93LndzQ2FudmFzR2V0Q29udGV4dFNldCkge1xyXG4gICAgICB3aW5kb3cud3NDYW52YXNHZXRDb250ZXh0U2V0ID0gdHJ1ZTtcclxuICAgICAgSFRNTENhbnZhc0VsZW1lbnQucHJvdG90eXBlLmdldENvbnRleHQgPSAoZnVuY3Rpb24gKG9yaWdGbikge1xyXG4gICAgICAgIHJldHVybiBmdW5jdGlvbiAodHlwZSwgYXR0cmlidXRlcykge1xyXG4gICAgICAgICAgaWYgKHR5cGUgPT09IFwid2ViZ2xcIikge1xyXG4gICAgICAgICAgICBhdHRyaWJ1dGVzID0gT2JqZWN0LmFzc2lnbih7fSwgYXR0cmlidXRlcywge1xyXG4gICAgICAgICAgICAgIHByZXNlcnZlRHJhd2luZ0J1ZmZlcjogdHJ1ZSxcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICB9XHJcbiAgICAgICAgICByZXR1cm4gb3JpZ0ZuLmNhbGwodGhpcywgdHlwZSwgYXR0cmlidXRlcyk7XHJcbiAgICAgICAgfTtcclxuICAgICAgfSkoSFRNTENhbnZhc0VsZW1lbnQucHJvdG90eXBlLmdldENvbnRleHQpO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgLy8g6K6+572u5pyA5aSn5Y+v5pi+56S655qE6Lev5pWwXHJcbiAgc2V0TWF4V2luZG93KCkge1xyXG4gICAgbGV0IF9tYXhOdW0gPSBwYXJzZUludCh0aGlzLmNvbmZpZy5tYXhOdW0sIDEwKTtcclxuICAgIGlmIChfbWF4TnVtID4gMTYpIHtcclxuICAgICAgdGhpcy5tYXhXaW5kb3cgPSAyNTtcclxuICAgIH0gZWxzZSBpZiAoX21heE51bSA+IDkpIHtcclxuICAgICAgdGhpcy5tYXhXaW5kb3cgPSAxNjtcclxuICAgIH0gZWxzZSBpZiAoX21heE51bSA+IDQpIHtcclxuICAgICAgdGhpcy5tYXhXaW5kb3cgPSA5O1xyXG4gICAgfSBlbHNlIGlmIChfbWF4TnVtID4gMSkge1xyXG4gICAgICB0aGlzLm1heFdpbmRvdyA9IDQ7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICB0aGlzLm1heFdpbmRvdyA9IDE7XHJcbiAgICB9XHJcbiAgfVxyXG5cclxuICBjcmVhdGVSZWFsUGxheWVyKCkge1xyXG4gICAgaWYgKHRoaXMuY29uZmlnLnNob3dDb250cm9sKSB7XHJcbiAgICAgIHRoaXMuX19hZGRSZWFsQ29udHJvbCgpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgdGhpcy4kd3JhcHBlci5hZGRDbGFzcyhcIm5vY29udHJvbFwiKTtcclxuICAgIH1cclxuICAgIEFycmF5KHRoaXMubWF4V2luZG93KVxyXG4gICAgICAuZmlsbCgxKVxyXG4gICAgICAuZm9yRWFjaCgoaXRlbSwgaW5kZXgpID0+IHtcclxuICAgICAgICBsZXQgcmVhbFBsYXllckl0ZW0gPSBuZXcgUmVhbFBsYXllckl0ZW0oe1xyXG4gICAgICAgICAgd3JhcHBlckRvbUlkOiB0aGlzLmVsLFxyXG4gICAgICAgICAgaW5kZXgsXHJcbiAgICAgICAgICB3c1BsYXllcjogdGhpcyxcclxuICAgICAgICB9KTtcclxuICAgICAgICB0aGlzLnBsYXllckxpc3QucHVzaChyZWFsUGxheWVySXRlbSk7XHJcbiAgICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgY3JlYXRlUmVjb3JkUGxheWVyKCkge1xyXG4gICAgaWYgKHRoaXMuY29uZmlnLnNob3dDb250cm9sKSB7XHJcbiAgICAgIHRoaXMuX19hZGRSZWNvcmRDb250cm9sKCk7XHJcbiAgICAgIHRoaXMuX19hZGRSZWFsQ29udHJvbCgpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgdGhpcy4kd3JhcHBlci5hZGRDbGFzcyhcIm5vY29udHJvbFwiKTtcclxuICAgIH1cclxuICAgIEFycmF5KHRoaXMubWF4V2luZG93KVxyXG4gICAgICAuZmlsbCgxKVxyXG4gICAgICAuZm9yRWFjaCgoaXRlbSwgaW5kZXgpID0+IHtcclxuICAgICAgICBsZXQgcmVjb3JkUGxheWVySXRlbSA9IG5ldyBSZWNvcmRQbGF5ZXJJdGVtKHtcclxuICAgICAgICAgIHdyYXBwZXJEb21JZDogdGhpcy5lbCxcclxuICAgICAgICAgIGluZGV4LFxyXG4gICAgICAgICAgd3NQbGF5ZXI6IHRoaXMsXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgdGhpcy5wbGF5ZXJMaXN0LnB1c2gocmVjb3JkUGxheWVySXRlbSk7XHJcbiAgICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgLy8g5Yqg6L29c2NyaXB05qCH562+XHJcbiAgbG9hZFNjcmlwdChzcmMpIHtcclxuICAgIGxldCBkb20gPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwic2NyaXB0XCIpO1xyXG4gICAgZG9tLnNyYyA9IHNyYztcclxuICAgIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoZG9tKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOWKoOi9veaXoOaPkuS7tuaSreaUvumdmeaAgeaWh+S7tu+8jOWMheaLrOino+eggeW6k1xyXG4gICAqIEBwYXJhbSBpc0h0dHBzIOaYr+WQpuaUr+aMgWh0dHBzXHJcbiAgICogQHBhcmFtIGlzVmVyc2lvbkNvbXBsaWFuY2Ug5rWP6KeI5Zmo54mI5pys5oiW5L2N5pWw5piv5ZCm56ym5ZCI6KaB5rGCXHJcbiAgICovXHJcbiAgbG9hZExpYkRIUGxheShpc0h0dHBzLCBpc1ZlcnNpb25Db21wbGlhbmNlKSB7XHJcbiAgICAvLyDop6PnoIHlupPlj6rog73liqDovb3kuIDmrKFcclxuICAgIGlmICh3aW5kb3cubG9hZExpYkRIUGxheWVyRmxhZykge1xyXG4gICAgICByZXR1cm47XHJcbiAgICB9XHJcbiAgICB3aW5kb3cubG9hZExpYkRIUGxheWVyRmxhZyA9IHRydWU7XHJcbiAgICAvLyDpu5jorqTliqDovb3lpJrnur/nqIvop6PnoIHlupPvvIzmgKfog73lpb3vvIzkvYbmmK/kuI3mlK/mjIFodHRw5Lul5Y+K5a+55rWP6KeI5Zmo54mI5pys6KaB5rGC6auYXHJcbiAgICAvLyA5MeeJiOacrOS7peS4ijY05L2NY2hyb21l5omN6IO955So5aSa57q/56iL6Kej56CB5bqT77yMMzLkvY1jaHJvbWXpnIDopoHnlKjljZXnur/nqIvop6PnoIHlupNcclxuICAgIGxldCBsaWJQYXRoID0gXCIuL3N0YXRpYy9XU1BsYXllci9tdWx0aVRocmVhZC9saWJkaHBsYXkuanNcIjtcclxuICAgIC8vIOWIpOaWreeOr+Wig+aYr+WQpuaUr+aMgVNoYXJlZEFycmF5QnVmZmVy77yM5LiN5pSv5oyB5YiZ5L2/55So5Y2V57q/56iL6Kej56CB5bqT77yI5aSa57q/56iL6ZyA6KaB5L2/55SoU2hhcmVkQXJyYXlCdWZmZXLvvIlcclxuICAgIHRyeSB7XHJcbiAgICAgIG5ldyBTaGFyZWRBcnJheUJ1ZmZlcigxKTtcclxuICAgIH0gY2F0Y2ggKGUpIHtcclxuICAgICAgbGliUGF0aCA9IFwiLi9zdGF0aWMvV1NQbGF5ZXIvc2luZ2xlVGhyZWFkL2xpYmRocGxheS5qc1wiO1xyXG4gICAgfVxyXG4gICAgLy8gaHR0cOaIluiAheS9jueJiOacrOa1j+iniOWZqOWKoOi9veWNlee6v+eoi+ino+eggeW6k1xyXG4gICAgaWYgKCFpc0h0dHBzIHx8ICFpc1ZlcnNpb25Db21wbGlhbmNlIHx8IHRoaXMuY29uZmlnLm9ubHlMb2FkU2luZ2xlTGliKSB7XHJcbiAgICAgIGxpYlBhdGggPSBcIi4vc3RhdGljL1dTUGxheWVyL3NpbmdsZVRocmVhZC9saWJkaHBsYXkuanNcIjtcclxuICAgIH1cclxuICAgIHRoaXMubG9hZFNjcmlwdChsaWJQYXRoKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOaSreaUvuWunuaXtuinhumikVxyXG4gICAqIEBwYXJhbSB7Kn0gb3B0LnJ0c3BVUkwgU3RyaW5nXHJcbiAgICogQHBhcmFtIHsqfSBvcHQud3NVUkwg5Y+v6YCJ5Y+C5pWwXHJcbiAgICogQHBhcmFtIHsqfSBvcHQuY2hhbm5lbElkIOeUqOadpeagh+iusOW9k+WJjeaSreaUvueahOinhumikemAmumBk1xyXG4gICAqIEBwYXJhbSB7Kn0gb3B0LnNlcnZlcklwIGljY+W5s+WPsOeahOWGhee9kWlwIHwgcGFhc+eahOWGhee9kWlwIOeUqOS6jm5naW546L2s5Y+R55qE55uu5qCH5Zyw5Z2AXHJcbiAgICogQHBhcmFtIHsqfSBvcHQucGxheWVyQWRhcHRlciDmi4nkvLggfCDoh6rpgILlupRcclxuICAgKiBAcGFyYW0geyp9IG9wdC5zZWxlY3RJbmRleCDlnKjnrKzlh6DkuKrop4bpopHnqpflj6PkuIrmkq3mlL5cclxuICAgKiBAcGFyYW0geyp9IG9wdC5jaGFubmVsRGF0YSDmraTop4bpopHmiYDlhbPogZTnmoTpgJrpgZPlr7nosaFcclxuICAgKi9cclxuICBwbGF5UmVhbChvcHQpIHtcclxuICAgIGlmICghb3B0LnJ0c3BVUkwpIHtcclxuICAgICAgY29uc29sZS5lcnJvcihcIuaSreaUvuWunuaXtuinhumikemcgOimgeS8oOWFpXJ0c3BVUkxcIik7XHJcbiAgICAgIHJldHVybjtcclxuICAgIH1cclxuICAgIG9wdC53c1VSTCA9IHRoaXMuX19nZXRXU1VybChvcHQucnRzcFVSTCwgb3B0LnNlcnZlcklwKTtcclxuICAgIG9wdC5wbGF5ZXJBZGFwdGVyID0gdGhpcy5wbGF5ZXJBZGFwdGVyO1xyXG4gICAgbGV0IHBsYXllciA9IHRoaXMucGxheWVyTGlzdFtvcHQuc2VsZWN0SW5kZXhdO1xyXG4gICAgLy8g5pKt5pS+5LiA5Liq56qX5Y+j5ZCO77yM6YCJ5Lit55qE56qX5Y+j6Ieq5Yqo6Lez6L2s5Yiw5LiL5LiA5Liq56qX5Y+j5LiKXHJcbiAgICBpZiAob3B0LnNlbGVjdEluZGV4ICsgMSA8IHRoaXMuc2hvd051bSkge1xyXG4gICAgICB0aGlzLnNldFNlbGVjdEluZGV4KG9wdC5zZWxlY3RJbmRleCArIDEpO1xyXG4gICAgfSBlbHNlIGlmICh0aGlzLnNlbGVjdEluZGV4ID09PSBvcHQuc2VsZWN0SW5kZXggJiYgcGxheWVyKSB7XHJcbiAgICAgIC8vIOWmguaenOWImuWlveaYr+acgOWQjuS4gOS4queql+WPo++8jOWImeaXoOmcgOi3s+i9rFxyXG4gICAgICAvLyDorr7nva7kupHlj7DnmoTpgJrpgZNcclxuICAgICAgdGhpcy5zZXRQdHpDaGFubmVsKG9wdC5jaGFubmVsRGF0YSk7XHJcbiAgICB9XHJcbiAgICBwbGF5ZXIgJiYgcGxheWVyLmluaXQob3B0KTtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog5pKt5pS+5b2V5YOPXHJcbiAgICogQHBhcmFtIHtTdHJpbmd9IG9wdGlvbnMuZGVjb2RlTW9kZSDlj6/pgInlj4LmlbAgdmlkZW8gfCBjYW52YXNcclxuICAgKiBAcGFyYW0ge1N0cmluZ30gb3B0aW9ucy53c1VSTCDlj6/pgInlj4LmlbBcclxuICAgKiBAcGFyYW0ge0Z1bmN0aW9ufSBvcHRpb25zLnJlY29yZFNvdXJjZSAyPeiuvuWkh++8jDM95Lit5b+DXHJcbiAgICogcmVjb3JkU291cmNlID09IDIg6K6+5aSH5b2V5YOP77yM5oyJ54Wn5pe26Ze05pa55byP5pKt5pS+XHJcbiAgICogQHBhcmFtIHtTdHJpbmd9IG9wdGlvbnMucnRzcFVSTCBTdHJpbmdcclxuICAgKiBAcGFyYW0ge051bWJlciB8IFN0cmluZ30gb3B0aW9ucy5zdGFydFRpbWUg5byA5aeL5pe26Ze0IOaXtumXtOaIs+aIluiAhScyMDIxLTA5LTE4IDE1OjQwOjAwJ+agvOW8j+eahOaXtumXtOWtl+espuS4slxyXG4gICAqIEBwYXJhbSB7TnVtYmVyIHwgU3RyaW5nfSBvcHRpb25zLmVuZFRpbWUg57uT5p2f5pe26Ze0IOaXtumXtOaIs+aIluiAhScyMDIxLTA5LTE4IDE1OjQwOjAwJ+agvOW8j+eahOaXtumXtOWtl+espuS4slxyXG4gICAqIEBwYXJhbSB7RnVuY3Rpb259IG9wdGlvbnMucmVsb2FkIOmHjeaWsOaLiea1geeahOWbnuiwg+WHveaVsO+8jOeUqOS6juaXtumXtOWbnuaUvu+8jOi/lOWbnnByb21pc2VcclxuICAgKiByZWxvYWQobmV3U3RhclRpbWUsIGVuZFRpbWUpLnRoZW4obmV3UnRzcFVybCA9PiB7IHBsYXkgY29udGludWV9KVxyXG4gICAqIHJlY29yZFNvdXJjZSA9PSAzIOS4reW/g+W9leWDj++8jOaMieeFp+aWh+S7tuaWueW8j+aSreaUvlxyXG4gICAqIEBwYXJhbSB7RnVuY3Rpb259IG9wdGlvbnMuUmVjb3JkRmlsZXMg5paH5Lu25YiX6KGoXHJcbiAgICogQHBhcmFtIHtGdW5jdGlvbn0gb3B0aW9ucy5nZXRSdHNwIOaWh+S7tuWIl+ihqFxyXG4gICAqIGdldFJ0c3AoZmlsZSkudGhlbihuZXdSdHNwVXJsID0+IHsgcGxheSBjb250aW51ZX0pXHJcbiAgICovXHJcbiAgcGxheVJlY29yZChvcHQpIHtcclxuICAgIGxldCBwbGF5ZXIgPSB0aGlzLnBsYXllckxpc3Rbb3B0LnNlbGVjdEluZGV4XTtcclxuICAgIG9wdC53c1VSTCA9IHRoaXMuX19nZXRXU1VybChvcHQucnRzcFVSTCwgb3B0LnNlcnZlcklwKTtcclxuICAgIG9wdC5wbGF5ZXJBZGFwdGVyID0gdGhpcy5wbGF5ZXJBZGFwdGVyO1xyXG4gICAgb3B0LmlzUGxheWJhY2sgPSB0cnVlO1xyXG4gICAgLy8g5pKt5pS+5LiA5Liq56qX5Y+j5ZCO77yM6YCJ5Lit55qE56qX5Y+j6Ieq5Yqo6Lez6L2s5Yiw5LiL5LiA5Liq56qX5Y+j5LiKXHJcbiAgICBpZiAob3B0LnNlbGVjdEluZGV4ICsgMSA8IHRoaXMuc2hvd051bSkge1xyXG4gICAgICB0aGlzLnNldFNlbGVjdEluZGV4KG9wdC5zZWxlY3RJbmRleCArIDEpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgJChcIi53cy1yZWNvcmQtcGxheVwiKS5jc3MoeyBkaXNwbGF5OiBcIm5vbmVcIiB9KTtcclxuICAgICAgJChcIi53cy1yZWNvcmQtcGF1c2VcIikuY3NzKHsgZGlzcGxheTogXCJibG9ja1wiIH0pO1xyXG4gICAgfVxyXG4gICAgcGxheWVyICYmIHBsYXllci5pbml0KG9wdCk7XHJcbiAgfVxyXG4gIGNhcHR1cmVQaWMoKSB7XHJcbiAgICBsZXQgcGxheWVyID0gdGhpcy5wbGF5ZXJMaXN0W3RoaXMuc2VsZWN0SW5kZXhdO1xyXG4gICAgcGxheWVyICYmIHBsYXllci5jYXB0dXJlUGljKCk7XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqIOaSreaUvlxyXG4gICAqL1xyXG4gIHBsYXkoKSB7XHJcbiAgICBsZXQgcGxheWVyID0gdGhpcy5wbGF5ZXJMaXN0W3RoaXMuc2VsZWN0SW5kZXhdO1xyXG4gICAgcGxheWVyLnN0YXR1cyA9PT0gXCJwYXVzZVwiICYmIHBsYXllci5wbGF5KCk7XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqIOaaguWBnOaSreaUvlxyXG4gICAqL1xyXG4gIHBhdXNlKCkge1xyXG4gICAgbGV0IHBsYXllciA9IHRoaXMucGxheWVyTGlzdFt0aGlzLnNlbGVjdEluZGV4XTtcclxuICAgIHBsYXllci5zdGF0dXMgPT09IFwicGxheWluZ1wiICYmIHBsYXllci5wYXVzZSgpO1xyXG4gIH1cclxuICAvKipcclxuICAgKiDlgI3pgJ/mkq3mlL5cclxuICAgKiBAcGFyYW0ge051bWJlcn0gc3BlZWQg5YCN6YCfXHJcbiAgICogQHBhcmFtIHtOdW1iZXJ9IGluZGV4IOeql+WPo+e0ouW8lVxyXG4gICAqL1xyXG4gIHBsYXlTcGVlZChzcGVlZCwgaW5kZXgpIHtcclxuICAgIGlmICh0aGlzLnR5cGUgPT09IFwicmVhbFwiKSB7XHJcbiAgICAgIGNvbnNvbGUud2FybihcIuWunuaXtumihOiniOS4jeaUr+aMgeWAjemAn+aSreaUvlwiKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgbGV0IHBsYXllciA9XHJcbiAgICAgIHRoaXMucGxheWVyTGlzdFtpbmRleCA9PT0gdW5kZWZpbmVkID8gdGhpcy5zZWxlY3RJbmRleCA6IGluZGV4XTtcclxuICAgIHBsYXllci5wbGF5U3BlZWQoc3BlZWQpO1xyXG4gIH1cclxuICAvKipcclxuICAgKiDorr7nva7pgInkuK3nmoTmkq3mlL7lmahcclxuICAgKiBAcGFyYW0geyp9IGluZGV4XHJcbiAgICovXHJcbiAgc2V0U2VsZWN0SW5kZXgoaW5kZXgpIHtcclxuICAgIGlmICh0aGlzLnNlbGVjdEluZGV4ID09PSBpbmRleCkge1xyXG4gICAgICByZXR1cm47XHJcbiAgICB9XHJcbiAgICAvLyDkuJrliqHlsYLlkIzmraXmkq3mlL7nqpflj6PntKLlvJVcclxuICAgIHRoaXMucHJvY2VkdXJlICYmIHRoaXMucHJvY2VkdXJlLnNldFBsYXlJbmRleChpbmRleCk7XHJcbiAgICBpZiAodGhpcy50eXBlID09PSBcInJlY29yZFwiKSB7XHJcbiAgICAgIGxldCBzdGF0dXMgPSAodGhpcy5wbGF5ZXJMaXN0W2luZGV4XSB8fCB7fSkuc3RhdHVzO1xyXG4gICAgICAvLyDmm7TmlrDmmoLlgZzmkq3mlL7mjInpkq5cclxuICAgICAgaWYgKHN0YXR1cyA9PT0gXCJwbGF5aW5nXCIpIHtcclxuICAgICAgICAkKFwiLndzLXJlY29yZC1wbGF5XCIpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgICAgICQoXCIud3MtcmVjb3JkLXBhdXNlXCIpLmNzcyh7IGRpc3BsYXk6IFwiYmxvY2tcIiB9KTtcclxuICAgICAgfVxyXG4gICAgICAvLyDlpoLmnpzpgInkuK3nmoTnqpflj6PmsqHmnInlnKjmkq3mlL7miJbogIXmmoLlgZzvvIzliJnpnIDopoHmuIXnqbrml7bpl7TovbTvvIzku6Xlj4rpmpDol4/ml7bpl7RcclxuICAgICAgaWYgKFtcInBsYXlpbmdcIiwgXCJwYXVzZVwiXS5pbmNsdWRlcyhzdGF0dXMpKSB7XHJcbiAgICAgICAgdGhpcy5wcm9jZWR1cmUgJiYgdGhpcy5wcm9jZWR1cmUuY2hhbmdlVGltZUxpbmUoaW5kZXgpO1xyXG4gICAgICB9IGVsc2Uge1xyXG4gICAgICAgIHRoaXMuc2V0VGltZUxpbmUoW10pO1xyXG4gICAgICAgICQoXCIud3MtcmVjb3JkLXBhdXNlXCIpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgICAgICQoXCIud3MtcmVjb3JkLXBsYXlcIikuY3NzKHsgZGlzcGxheTogXCJibG9ja1wiIH0pO1xyXG4gICAgICB9XHJcbiAgICAgIC8vIOabtOaWsOWAjemAn1xyXG4gICAgICB0aGlzLl9fc2V0UGxheVNwZWVkKFwiXCIsIGluZGV4KTtcclxuICAgIH1cclxuICAgIC8vIOmAmuefpeS4iuWxguS4muWKoe+8jOmAieS4reeahOeql+WPo+e0ouW8leWPkeeUn+S6huWPmOWMllxyXG4gICAgLy8gdGhpcy5zZW5kTWVzc2FnZShcInNlbGVjdFdpbmRvd0NoYW5nZWRcIiwge1xyXG4gICAgLy8gICBjaGFubmVsSWQ6ICh0aGlzLnBsYXllckxpc3RbaW5kZXhdLm9wdGlvbnMgfHwge30pLmNoYW5uZWxJZCxcclxuICAgIC8vICAgcGxheUluZGV4OiBpbmRleCxcclxuICAgIC8vIH0pO1xyXG4gICAgdGhpcy5zZWxlY3RJbmRleCA9IGluZGV4O1xyXG4gICAgLy8g6K6+572u5LqR5Y+w55qE6YCa6YGTXHJcbiAgICB0aGlzLnNldFB0ekNoYW5uZWwoKHRoaXMucGxheWVyTGlzdFtpbmRleF0ub3B0aW9ucyB8fCB7fSkuY2hhbm5lbERhdGEpO1xyXG4gICAgdGhpcy5wbGF5ZXJMaXN0LmZvckVhY2goKGl0ZW0sIGkpID0+IHtcclxuICAgICAgaWYgKGkgPT09IGluZGV4KSB7XHJcbiAgICAgICAgaXRlbS4kZWwucmVtb3ZlQ2xhc3MoXCJ1bnNlbGVjdGVkXCIpLmFkZENsYXNzKFwic2VsZWN0ZWRcIik7XHJcbiAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgaXRlbS4kZWwucmVtb3ZlQ2xhc3MoXCJzZWxlY3RlZFwiKS5hZGRDbGFzcyhcInVuc2VsZWN0ZWRcIik7XHJcbiAgICAgIH1cclxuICAgICAgLy8g5pu05paw5pKt5pS+5aOw6Z+z55qE56qX5Y+j77yM5pyq6YCJ5oup55qE56qX5Y+j5YGc5q2i5pKt5pS+6Z+z6aKRXHJcbiAgICAgIHRoaXMuX191cGRhdGVWb2ljZShpdGVtLCBpID09PSBpbmRleCk7XHJcbiAgICB9KTtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog5o6n5Yi26KeG6aKR5pKt5pS+5Zmo5pi+56S655qE6Lev5pWwXHJcbiAgICogQHBhcmFtIHsqfSBudW1iZXJcclxuICAgKi9cclxuICBzZXRQbGF5ZXJOdW0obnVtYmVyKSB7XHJcbiAgICBsZXQgX251bWJlciA9IHBhcnNlSW50KG51bWJlcikgfHwgMTtcclxuICAgIC8vIOWPquiDveiuvue9riAx44CBNOOAgTnjgIExNuOAgTI15YiG5bGP77yM6K6+572u5YW25LuW5pWw6YeP5YiZ6Ieq6YCC5bqU5YiG6YWNXHJcbiAgICBpZiAoX251bWJlciA8PSAxKSB7XHJcbiAgICAgIC8vIOWNleWxj1xyXG4gICAgICBfbnVtYmVyID0gMTtcclxuICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoXHJcbiAgICAgICAgXCJzY3JlZW4tc3BsaXQtNCBzY3JlZW4tc3BsaXQtOSBzY3JlZW4tc3BsaXQtMTYgc2NyZWVuLXNwbGl0LTI1XCJcclxuICAgICAgKTtcclxuICAgICAgdGhpcy4kZWwuYWRkQ2xhc3MoXCJmdWxscGxheWVyXCIpO1xyXG4gICAgfSBlbHNlIGlmIChfbnVtYmVyID4gMSAmJiBfbnVtYmVyIDw9IDQpIHtcclxuICAgICAgLy8gNOWIhuWxj1xyXG4gICAgICBfbnVtYmVyID0gNDtcclxuICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoXHJcbiAgICAgICAgXCJmdWxscGxheWVyIHNjcmVlbi1zcGxpdC05IHNjcmVlbi1zcGxpdC0xNiBzY3JlZW4tc3BsaXQtMjVcIlxyXG4gICAgICApO1xyXG4gICAgICB0aGlzLiRlbC5hZGRDbGFzcyhcInNjcmVlbi1zcGxpdC00XCIpO1xyXG4gICAgfSBlbHNlIGlmIChfbnVtYmVyID4gNCAmJiBfbnVtYmVyIDw9IDkpIHtcclxuICAgICAgLy8gOeWIhuWxj1xyXG4gICAgICBfbnVtYmVyID0gOTtcclxuICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoXHJcbiAgICAgICAgXCJmdWxscGxheWVyIHNjcmVlbi1zcGxpdC00IHNjcmVlbi1zcGxpdC0xNiBzY3JlZW4tc3BsaXQtMjVcIlxyXG4gICAgICApO1xyXG4gICAgICB0aGlzLiRlbC5hZGRDbGFzcyhcInNjcmVlbi1zcGxpdC05XCIpO1xyXG4gICAgfSBlbHNlIGlmIChfbnVtYmVyID4gOSAmJiBfbnVtYmVyIDw9IDE2KSB7XHJcbiAgICAgIC8vIDE25YiG5bGPXHJcbiAgICAgIF9udW1iZXIgPSAxNjtcclxuICAgICAgdGhpcy4kZWwucmVtb3ZlQ2xhc3MoXHJcbiAgICAgICAgXCJmdWxscGxheWVyIHNjcmVlbi1zcGxpdC00IHNjcmVlbi1zcGxpdC05IHNjcmVlbi1zcGxpdC0yNVwiXHJcbiAgICAgICk7XHJcbiAgICAgIHRoaXMuJGVsLmFkZENsYXNzKFwic2NyZWVuLXNwbGl0LTE2XCIpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgLy8gMjXliIblsY9cclxuICAgICAgX251bWJlciA9IDI1O1xyXG4gICAgICB0aGlzLiRlbC5yZW1vdmVDbGFzcyhcclxuICAgICAgICBcImZ1bGxwbGF5ZXIgc2NyZWVuLXNwbGl0LTQgc2NyZWVuLXNwbGl0LTkgc2NyZWVuLXNwbGl0LTE2XCJcclxuICAgICAgKTtcclxuICAgICAgdGhpcy4kZWwuYWRkQ2xhc3MoXCJzY3JlZW4tc3BsaXQtMjVcIik7XHJcbiAgICB9XHJcbiAgICAvLyDov5vooYzlj4LmlbDmoKHpqozvvIzoi6XlpKfkuo7mnIDlpKfmmL7npLrot6/mlbDvvIzliJnnva7kuLrmnIDlpKfmmL7npLrot6/mlbBcclxuICAgIGlmIChfbnVtYmVyID4gdGhpcy5tYXhXaW5kb3cpIHtcclxuICAgICAgX251bWJlciA9IHRoaXMubWF4V2luZG93O1xyXG4gICAgfVxyXG4gICAgLy8g5Y+C5pWw5qCh6aqM5ZCO6Iul5pi+56S66Lev5pWw5rKh5pyJ5Y+R55Sf5pS55Y+Y77yM5YiZ5LiN5YGa5aSE55CGXHJcbiAgICBpZiAodGhpcy5zaG93TnVtID09PSBfbnVtYmVyKSB7XHJcbiAgICAgIHJldHVybjtcclxuICAgIH1cclxuICAgIHRoaXMuc2hvd051bSA9IF9udW1iZXI7XHJcbiAgICAvLyDpgJrnn6XkuIrlsYLkuJrliqHvvIzmmL7npLrnmoTmkq3mlL7lmajot6/mlbDlj5HnlJ/kuobmlLnlj5hcclxuICAgIC8vIHRoaXMuc2VuZE1lc3NhZ2UoXCJ3aW5kb3dOdW1DaGFuZ2VkXCIsIHRoaXMuc2hvd051bSk7XHJcbiAgICBzZXRUaW1lb3V0KCgpID0+IHtcclxuICAgICAgdGhpcy5fX3VwZGF0ZVBsYXllcldpbmRvdygpO1xyXG4gICAgfSwgMjAwKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOaOp+WItuaSreaUvuWZqOaYr+WQpuiHqumAguW6lFxyXG4gICAqIEBwYXJhbSBwbGF5ZXJBZGFwdGVyXHJcbiAgICovXHJcbiAgc2V0UGxheWVyQWRhcHRlcihwbGF5ZXJBZGFwdGVyKSB7XHJcbiAgICBpZiAodGhpcy5wbGF5ZXJBZGFwdGVyID09PSBwbGF5ZXJBZGFwdGVyKSB7XHJcbiAgICAgIHJldHVybjtcclxuICAgIH1cclxuICAgIHRoaXMucGxheWVyQWRhcHRlciA9IHBsYXllckFkYXB0ZXI7XHJcbiAgICB0aGlzLl9fdXBkYXRlUGxheWVyV2luZG93KCk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDlvZXlg4/lm57mlL7kuK3nmoTml7bpl7TovbRcclxuICAgKiBAcGFyYW0gdGltZUxpc3Q6IFt7c3RhcnRUaW1lOiAxNjUwMDY3MjAwLCBlbmRUaW1lOiAxNjUwMDcwODAwLCBpc0ltcG9ydGFudDogZmFsc2V9LCB7c3RhcnRUaW1lOiAxNjUwMDg1MjAwLCBlbmRUaW1lOiAxNjUwMDkzOTcxLCBpc0ltcG9ydGFudDogdHJ1ZX1dXHJcbiAgICog5byA5aeL5pe26Ze044CB57uT5p2f5pe26Ze044CB5piv5ZCm6YeN6KaBXHJcbiAgICovXHJcbiAgc2V0VGltZUxpbmUodGltZUxpc3QgPSBbXSkge1xyXG4gICAgLy8gdGhpcy50aW1lTGlzdCA9IEpTT04ucGFyc2UoJ1t7XCJzdGFydFRpbWVcIjpcIjE2NTA1NTY4MDBcIixcImVuZFRpbWVcIjpcIjE2NTA1NTY4NjBcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU1NjgwMFwiLFwiZW5kVGltZVwiOlwiMTY1MDU1NjgwMVwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTU2ODAwXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTU2ODMyXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1NTY4MDBcIixcImVuZFRpbWVcIjpcIjE2NTA1NTY5ODNcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU1NjgwMFwiLFwiZW5kVGltZVwiOlwiMTY1MDU1ODEwMVwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTU2ODAxXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTU3NjM3XCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1NTY4NjBcIixcImVuZFRpbWVcIjpcIjE2NTA1NTY4NjdcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU1ODEwN1wiLFwiZW5kVGltZVwiOlwiMTY1MDU2MDMxMFwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTYwMzY4XCIsXCJlbmRUaW1lXCI6XCIxNjUwNTYxMzQwXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1NjEzNDZcIixcImVuZFRpbWVcIjpcIjE2NTA1NjI1MDdcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU2MjUyOVwiLFwiZW5kVGltZVwiOlwiMTY1MDU2NDU3OVwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTY0NTg1XCIsXCJlbmRUaW1lXCI6XCIxNjUwNTY3NjAwXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1Njc2MDBcIixcImVuZFRpbWVcIjpcIjE2NTA1Njc4MTlcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU2NzgyNVwiLFwiZW5kVGltZVwiOlwiMTY1MDU3MTA1OFwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTcxMDY1XCIsXCJlbmRUaW1lXCI6XCIxNjUwNTc0Mjk4XCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1NzQzMDRcIixcImVuZFRpbWVcIjpcIjE2NTA1Nzc1MzdcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU3NzU0M1wiLFwiZW5kVGltZVwiOlwiMTY1MDU4MDc3N1wiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTgwNzgzXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTg0MDE2XCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1ODQwMjNcIixcImVuZFRpbWVcIjpcIjE2NTA1ODcyNTVcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU4NzI2M1wiLFwiZW5kVGltZVwiOlwiMTY1MDU4OTIwMFwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTg5MjAwXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTkwMjg1XCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1OTAyODVcIixcImVuZFRpbWVcIjpcIjE2NTA1OTAzMjBcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU5MDMwOFwiLFwiZW5kVGltZVwiOlwiMTY1MDU5MDQ5NVwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTkwNTAyXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTkyMTIyXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1OTIxMDlcIixcImVuZFRpbWVcIjpcIjE2NTA1OTM0NDBcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU5MzQyOFwiLFwiZW5kVGltZVwiOlwiMTY1MDU5MzczNVwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNTkzNzQxXCIsXCJlbmRUaW1lXCI6XCIxNjUwNTk0ODgxXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA1OTQ4NjhcIixcImVuZFRpbWVcIjpcIjE2NTA1OTY5NzRcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDU5Njk4MVwiLFwiZW5kVGltZVwiOlwiMTY1MDYwMDAwMFwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNjAwMDAwXCIsXCJlbmRUaW1lXCI6XCIxNjUwNjAwMjE0XCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA2MDAyMjFcIixcImVuZFRpbWVcIjpcIjE2NTA2MDM0NTNcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9LHtcInN0YXJ0VGltZVwiOlwiMTY1MDYwMzQ2MFwiLFwiZW5kVGltZVwiOlwiMTY1MDYwNjY5MlwiLFwiaXNJbXBvcnRhbnRcIjpmYWxzZX0se1wic3RhcnRUaW1lXCI6XCIxNjUwNjA2Njk5XCIsXCJlbmRUaW1lXCI6XCIxNjUwNjA5OTMxXCIsXCJpc0ltcG9ydGFudFwiOmZhbHNlfSx7XCJzdGFydFRpbWVcIjpcIjE2NTA2MDk5MzhcIixcImVuZFRpbWVcIjpcIjE2NTA2MTExODdcIixcImlzSW1wb3J0YW50XCI6ZmFsc2V9XScpXHJcbiAgICB0aGlzLnRpbWVMaXN0ID0gdGltZUxpc3Q7XHJcbiAgICAvLyDmoLnmja7mmK/lkKbmmL7npLrml7bpl7TovbTvvIzmnaXmjqfliLbml7bpl7TngrnmmL7npLpcclxuICAgIGlmICh0aGlzLnRpbWVMaXN0Lmxlbmd0aCkge1xyXG4gICAgICAkKFwiI3dzLXJlY29yZC10aW1lLWJveFwiKS5jc3MoeyB2aXNpYmlsaXR5OiBcInZpc2libGVcIiB9KTtcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgICQoXCIjd3MtcmVjb3JkLXRpbWUtYm94XCIpLmNzcyh7IHZpc2liaWxpdHk6IFwiaGlkZGVuXCIgfSk7XHJcbiAgICB9XHJcbiAgICB0aGlzLl9fc2V0VGltZVJlY29yZEFyZWEodGltZUxpc3QpO1xyXG4gIH1cclxuXHJcbiAgLy8g6K6+572u5YWo5bGPXHJcbiAgc2V0RnVsbFNjcmVlbigpIHtcclxuICAgIGxldCB0YXJnZXQgPSB0aGlzLiRlbFswXS5jaGlsZHJlblswXTtcclxuICAgIGlmICh0YXJnZXQucmVxdWVzdEZ1bGxzY3JlZW4pIHtcclxuICAgICAgdGFyZ2V0LnJlcXVlc3RGdWxsc2NyZWVuKCk7XHJcbiAgICB9IGVsc2UgaWYgKHRhcmdldC53ZWJraXRSZXF1ZXN0RnVsbHNjcmVlbikge1xyXG4gICAgICB0YXJnZXQud2Via2l0UmVxdWVzdEZ1bGxzY3JlZW4oKTtcclxuICAgIH0gZWxzZSBpZiAodGFyZ2V0Lm1velJlcXVlc3RGdWxsU2NyZWVuKSB7XHJcbiAgICAgIHRhcmdldC5tb3pSZXF1ZXN0RnVsbFNjcmVlbigpO1xyXG4gICAgfSBlbHNlIGlmICh0YXJnZXQubXNSZXF1ZXN0RnVsbHNjcmVlbikge1xyXG4gICAgICB0YXJnZXQubXNSZXF1ZXN0RnVsbHNjcmVlbigpO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog5YWz6Zet5pKt5pS+5ZmoXHJcbiAgICogQHBhcmFtIGluZGV4IOWFs+mXreaMh+Wumueql+WPo+eahOaSreaUvuWZqO+8jOiLpeaXoOatpOWPguaVsO+8jOWImeWFs+mXreaJgOacieaSreaUvuWZqFxyXG4gICAqL1xyXG4gIGNsb3NlKGluZGV4KSB7XHJcbiAgICBsZXQgX2luZGV4ID0gTnVtYmVyKGluZGV4KTtcclxuICAgIGxldCBwbGF5ZXJJdGVtID0gdGhpcy5wbGF5ZXJMaXN0W19pbmRleF07XHJcbiAgICBpZiAocGxheWVySXRlbSkge1xyXG4gICAgICBwbGF5ZXJJdGVtLmNsb3NlKCk7XHJcbiAgICAgIC8vIOWFs+mXremAieS4reeahOW9leWDj++8jOmcgOimgea4heepuuaXtumXtOi9tFxyXG4gICAgICBpZiAodGhpcy5zZWxlY3RJbmRleCA9PT0gX2luZGV4KSB7XHJcbiAgICAgICAgdGhpcy5zZXRUaW1lTGluZShbXSk7XHJcbiAgICAgIH1cclxuICAgIH0gZWxzZSB7XHJcbiAgICAgIC8vIOWFs+mXreaJgOacieaSreaUvuWZqOeahOWQjOaXtuS5n+a4heepuuaXtumXtOi9tFxyXG4gICAgICB0aGlzLnNldFRpbWVMaW5lKFtdKTtcclxuICAgICAgdGhpcy5wbGF5ZXJMaXN0LmZvckVhY2goKGl0ZW0pID0+IHtcclxuICAgICAgICBpdGVtLmNsb3NlKCk7XHJcbiAgICAgIH0pO1xyXG4gICAgICAvLyDlj5bmtojmtY/op4jlmajnqpflj6Pkuovku7bnm5HlkKxcclxuICAgICAgd2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJyZXNpemVcIiwgdGhpcy5iaW5kVXBkYXRlUGxheWVyV2luZG93KTtcclxuICAgIH1cclxuICB9XHJcblxyXG4gIC8qIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tIOWGhemDqOaWueazlSAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSovXHJcbiAgLyoqXHJcbiAgICog5re75Yqg5a6e5pe25pKt5pS+5o6n5Yi25qCPXHJcbiAgICovXHJcbiAgX19hZGRSZWFsQ29udHJvbCgpIHtcclxuICAgIHRoaXMuJGVsLmFwcGVuZChgXHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1jb250cm9sXCI+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtZmxleCB3cy1jb250cm9sLXJlY29yZCB3cy1mbGV4LWxlZnRcIj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1yZWNvcmQtaWNvbiB3cy1yZWNvcmQtcGxheVwiIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHRpdGxlPVwi5pKt5pS+XCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLWN0cmwtcmVjb3JkLWljb24gd3MtcmVjb3JkLXBhdXNlXCIgdGl0bGU9XCLmmoLlgZxcIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1yZWNvcmQtaWNvbiB3cy1yZWNvcmQtc3BlZWQtc3ViXCIgdGl0bGU9XCLlgI3pgJ8tXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLWN0cmwtcmVjb3JkLWljb24gd3MtcmVjb3JkLXNwZWVkLXR4dFwiPjF4PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLWN0cmwtcmVjb3JkLWljb24gd3MtcmVjb3JkLXNwZWVkLWFkZFwiIHRpdGxlPVwi5YCN6YCfK1wiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtZmxleCB3cy1mbGV4LWVuZFwiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1zZWxlY3Qtc2VsZi1hZGFwdGlvblwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3Mtc2VsZWN0LXNob3cgc2VsZWN0XCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3Mtc2VsZWN0LXNob3ctb3B0aW9uXCI+6Ieq6YCC5bqUPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8IS0tIOS4i+aLieeureWktCAtLT5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbWcgc3JjPVwiLi9zdGF0aWMvV1NQbGF5ZXIvaWNvbi9zcHJlYWQucG5nXCIgLz5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1zZWxmLWFkYXB0aW9uLXR5cGVcIiBzdHlsZT1cImRpc3BsYXk6IG5vbmVcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1bCBjbGFzcz1cIndzLXNlbGVjdC11bFwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsaSBvcHRpb25WYWx1ZT1cIuiHqumAguW6lFwiIHZhbHVlPVwic2VsZkFkYXB0aW9uXCIgY2xhc3M9XCJ3cy1zZWxlY3QtdHlwZS1pdGVtXCI+6Ieq6YCC5bqUPC9saT5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGkgb3B0aW9uVmFsdWU9XCLmi4nkvLhcIiB2YWx1ZT1cInN0cmV0Y2hpbmdcIiBjbGFzcz1cIndzLXNlbGVjdC10eXBlLWl0ZW1cIj7mi4nkvLg8L2xpPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC91bD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJ3cy1jdHJsLWJ0bi1zcHJlYWRcIj48L3NwYW4+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLWN0cmwtaWNvbiBjbG9zZS1hbGwtdmlkZW9cIiB0aXRsZT1cIuS4gOmUruWFs+mXrVwiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwid3MtY3RybC1idG4tc3ByZWFkXCI+PC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ3cy1jdHJsLWljb24gb25lLXNjcmVlbi1pY29uXCIgdGl0bGU9XCLljZXlsY9cIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1pY29uIGZvdXItc2NyZWVuLWljb25cIiB0aXRsZT1cIjTliIblsY9cIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1pY29uIG5pbmUtc2NyZWVuLWljb25cIiB0aXRsZT1cIjnliIblsY9cIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1pY29uIHNpeHRlZW4tc2NyZWVuLWljb25cIiB0aXRsZT1cIjE25YiG5bGPXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLWN0cmwtaWNvbiB0d2VudHktZml2ZS1zY3JlZW4taWNvblwiIHRpdGxlPVwiMjXliIblsY9cIj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cIndzLWN0cmwtYnRuLXNwcmVhZFwiPjwvc3Bhbj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY3RybC1pY29uIGZ1bGwtc2NyZWVuLWljb25cIiB0aXRsZT1cIuWFqOWxj1wiPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIGApO1xyXG4gICAgaWYgKHRoaXMubWF4V2luZG93IDw9IDE2KSB7XHJcbiAgICAgICQoXCIudHdlbnR5LWZpdmUtc2NyZWVuLWljb25cIikuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgICBpZiAodGhpcy5tYXhXaW5kb3cgPD0gOSkge1xyXG4gICAgICAkKFwiLnNpeHRlZW4tc2NyZWVuLWljb25cIikuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgICBpZiAodGhpcy5tYXhXaW5kb3cgPD0gNCkge1xyXG4gICAgICAkKFwiLm5pbmUtc2NyZWVuLWljb25cIikuY3NzKHsgZGlzcGxheTogXCJub25lXCIgfSk7XHJcbiAgICB9XHJcbiAgICBpZiAodGhpcy5tYXhXaW5kb3cgPT09IDEpIHtcclxuICAgICAgJChcIi5mb3VyLXNjcmVlbi1pY29uXCIpLmNzcyh7IGRpc3BsYXk6IFwibm9uZVwiIH0pO1xyXG4gICAgICAkKFwiLm9uZS1zY3JlZW4taWNvblwiKS5jc3MoeyBkaXNwbGF5OiBcIm5vbmVcIiB9KTtcclxuICAgIH1cclxuXHJcbiAgICAkKFwiLmZ1bGwtc2NyZWVuLWljb25cIiwgdGhpcy4kZWwpLmNsaWNrKCgpID0+IHtcclxuICAgICAgdGhpcy5zZXRGdWxsU2NyZWVuKCk7XHJcbiAgICB9KTtcclxuICAgICQoXCIub25lLXNjcmVlbi1pY29uXCIsIHRoaXMuJGVsKS5jbGljaygoKSA9PiB7XHJcbiAgICAgIHRoaXMuc2V0UGxheWVyTnVtKDEpO1xyXG4gICAgfSk7XHJcbiAgICAkKFwiLmZvdXItc2NyZWVuLWljb25cIiwgdGhpcy4kZWwpLmNsaWNrKCgpID0+IHtcclxuICAgICAgdGhpcy5zZXRQbGF5ZXJOdW0oNCk7XHJcbiAgICB9KTtcclxuICAgICQoXCIubmluZS1zY3JlZW4taWNvblwiLCB0aGlzLiRlbCkuY2xpY2soKCkgPT4ge1xyXG4gICAgICB0aGlzLnNldFBsYXllck51bSg5KTtcclxuICAgIH0pO1xyXG4gICAgJChcIi5zaXh0ZWVuLXNjcmVlbi1pY29uXCIsIHRoaXMuJGVsKS5jbGljaygoKSA9PiB7XHJcbiAgICAgIHRoaXMuc2V0UGxheWVyTnVtKDE2KTtcclxuICAgIH0pO1xyXG4gICAgJChcIi50d2VudHktZml2ZS1zY3JlZW4taWNvblwiLCB0aGlzLiRlbCkuY2xpY2soKCkgPT4ge1xyXG4gICAgICB0aGlzLnNldFBsYXllck51bSgyNSk7XHJcbiAgICB9KTtcclxuICAgICQoXCIuY2xvc2UtYWxsLXZpZGVvXCIsIHRoaXMuJGVsKS5jbGljaygoKSA9PiB7XHJcbiAgICAgIHRoaXMuY2xvc2UoKTtcclxuICAgIH0pO1xyXG4gICAgLy8g54K55Ye75YiH5o2i6Ieq6YCC5bqUL+aLieS8uFxyXG4gICAgdGhpcy5zZWxmQWRhcHRpb25TZWxlY3RTaG93ID0gZmFsc2U7XHJcbiAgICAkKFwiLndzLXNlbGVjdC1zZWxmLWFkYXB0aW9uXCIsIHRoaXMuJGVsKS5jbGljaygoZSkgPT4ge1xyXG4gICAgICBpZiAodGhpcy5zZWxmQWRhcHRpb25TZWxlY3RTaG93KSB7XHJcbiAgICAgICAgJChcIi53cy1zZWxmLWFkYXB0aW9uLXR5cGVcIiwgdGhpcy4kZWwpLmhpZGUoKTtcclxuICAgICAgICB0aGlzLnNlbGZBZGFwdGlvblNlbGVjdFNob3cgPSBmYWxzZTtcclxuICAgICAgfSBlbHNlIHtcclxuICAgICAgICAkKFwiLndzLXNlbGYtYWRhcHRpb24tdHlwZVwiLCB0aGlzLiRlbCkuc2hvdygpO1xyXG4gICAgICAgIHRoaXMuc2VsZkFkYXB0aW9uU2VsZWN0U2hvdyA9IHRydWU7XHJcbiAgICAgICAgLy8g6I635Y+W6ZyA6KaB6auY5Lqu55qE5YWD57SgXHJcbiAgICAgICAgJChgLndzLXNlbGVjdC11bCAud3Mtc2VsZWN0LXR5cGUtaXRlbWApLmNzcyh7IGJhY2tncm91bmQ6IFwibm9uZVwiIH0pO1xyXG4gICAgICAgICQoYC53cy1zZWxlY3QtdWwgW3ZhbHVlPSR7dGhpcy5wbGF5ZXJBZGFwdGVyfV1gKS5jc3Moe1xyXG4gICAgICAgICAgYmFja2dyb3VuZDogXCIjMUE3OEVBXCIsXHJcbiAgICAgICAgfSk7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgJChcIi53cy1zZWxmLWFkYXB0aW9uLXR5cGVcIiwgdGhpcy4kZWwpLmNsaWNrKChlKSA9PiB7XHJcbiAgICAgIGxldCBzdHJlYW1UeXBlVmFsdWUgPSBlLnRhcmdldC5nZXRBdHRyaWJ1dGUoXCJ2YWx1ZVwiKTtcclxuICAgICAgdGhpcy5zZXRQbGF5ZXJBZGFwdGVyKHN0cmVhbVR5cGVWYWx1ZSk7XHJcbiAgICAgIC8vIOaVsOaNruWbnuaYvlxyXG4gICAgICAkKGAud3Mtc2VsZWN0LXNob3ctb3B0aW9uYCkudGV4dChlLnRhcmdldC5nZXRBdHRyaWJ1dGUoXCJvcHRpb25WYWx1ZVwiKSk7XHJcbiAgICB9KTtcclxuICAgIGlmICh0aGlzLnR5cGUgIT09IFwicmVjb3JkXCIpIHtcclxuICAgICAgJChcIi53cy1jb250cm9sLXJlY29yZFwiKS5jc3MoeyBkaXNwbGF5OiBcIm5vbmVcIiB9KTtcclxuICAgIH1cclxuICAgIC8vIOaaguWBnOaSreaUvlxyXG4gICAgJChcIi53cy1yZWNvcmQtcGF1c2VcIiwgdGhpcy4kZWwpLmNsaWNrKChlKSA9PiB7XHJcbiAgICAgIHRoaXMucGF1c2UoKTtcclxuICAgIH0pO1xyXG4gICAgLy8g57un57ut5pKt5pS+XHJcbiAgICAkKFwiLndzLXJlY29yZC1wbGF5XCIsIHRoaXMuJGVsKS5jbGljaygoZSkgPT4ge1xyXG4gICAgICB0aGlzLnBsYXkoKTtcclxuICAgIH0pO1xyXG4gICAgLy8g5YCN6YCf5pKt5pS+77yM5YCN6YCfLVxyXG4gICAgJChcIi53cy1yZWNvcmQtc3BlZWQtc3ViXCIsIHRoaXMuJGVsKS5jbGljaygoZSkgPT4ge1xyXG4gICAgICBsZXQgcGxheWVyID0gdGhpcy5wbGF5ZXJMaXN0W3RoaXMuc2VsZWN0SW5kZXhdO1xyXG4gICAgICBwbGF5ZXIuc3RhdHVzID09PSBcInBsYXlpbmdcIiAmJiB0aGlzLl9fc2V0UGxheVNwZWVkKFwiUFJFVlwiKTtcclxuICAgIH0pO1xyXG4gICAgLy8g5YCN6YCf5pKt5pS+77yM5YCN6YCfK1xyXG4gICAgJChcIi53cy1yZWNvcmQtc3BlZWQtYWRkXCIsIHRoaXMuJGVsKS5jbGljaygoZSkgPT4ge1xyXG4gICAgICBsZXQgcGxheWVyID0gdGhpcy5wbGF5ZXJMaXN0W3RoaXMuc2VsZWN0SW5kZXhdO1xyXG4gICAgICBwbGF5ZXIuc3RhdHVzID09PSBcInBsYXlpbmdcIiAmJiB0aGlzLl9fc2V0UGxheVNwZWVkKFwiTkVYVFwiKTtcclxuICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgLy8g6K6+572u5YCN6YCfXHJcbiAgX19zZXRQbGF5U3BlZWQob3B0aW9uLCB3aW5kb3dJbmRleCkge1xyXG4gICAgbGV0IHNwZWVkTGlzdCA9IFtcclxuICAgICAgeyB2YWx1ZTogMC4xMjUsIGxhYmVsOiBcIjEvOHhcIiB9LFxyXG4gICAgICB7IHZhbHVlOiAwLjI1LCBsYWJlbDogXCIxLzR4XCIgfSxcclxuICAgICAgeyB2YWx1ZTogMC41LCBsYWJlbDogXCIxLzJ4XCIgfSxcclxuICAgICAgeyB2YWx1ZTogMSwgbGFiZWw6IFwiMXhcIiB9LFxyXG4gICAgICB7IHZhbHVlOiAyLCBsYWJlbDogXCIyeFwiIH0sXHJcbiAgICAgIHsgdmFsdWU6IDQsIGxhYmVsOiBcIjR4XCIgfSxcclxuICAgICAgeyB2YWx1ZTogOCwgbGFiZWw6IFwiOHhcIiB9LFxyXG4gICAgXTtcclxuICAgIGxldCBwbGF5ZXIgPVxyXG4gICAgICB0aGlzLnBsYXllckxpc3RbXHJcbiAgICAgICAgd2luZG93SW5kZXggPT09IHVuZGVmaW5lZCA/IHRoaXMuc2VsZWN0SW5kZXggOiB3aW5kb3dJbmRleFxyXG4gICAgICBdO1xyXG4gICAgbGV0IHNldFNwZWVkSXRlbSwgc2V0U3BlZWRJbmRleDtcclxuICAgIHNwZWVkTGlzdC5zb21lKChpdGVtLCBpbmRleCkgPT4ge1xyXG4gICAgICBpZiAoaXRlbS52YWx1ZSA9PT0gcGxheWVyLnNwZWVkKSB7XHJcbiAgICAgICAgaWYgKG9wdGlvbiA9PT0gXCJQUkVWXCIpIHtcclxuICAgICAgICAgIHNldFNwZWVkSW5kZXggPSBpbmRleCAtIDE7XHJcbiAgICAgICAgfSBlbHNlIGlmIChvcHRpb24gPT09IFwiTkVYVFwiKSB7XHJcbiAgICAgICAgICBzZXRTcGVlZEluZGV4ID0gaW5kZXggKyAxO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICBzZXRTcGVlZEluZGV4ID0gaW5kZXg7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHNldFNwZWVkSXRlbSA9IHNwZWVkTGlzdFtzZXRTcGVlZEluZGV4XTtcclxuICAgICAgICBpZiAoIXNldFNwZWVkSXRlbSkge1xyXG4gICAgICAgICAgcmV0dXJuIHRydWU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIOiuvue9rum8oOagh+aJi+WKv1xyXG4gICAgICAgIGlmICghc2V0U3BlZWRJbmRleCkge1xyXG4gICAgICAgICAgJChcIi53cy1yZWNvcmQtc3BlZWQtc3ViXCIsIHRoaXMuJGVsKS5jc3MoeyBjdXJzb3I6IFwibm90LWFsbG93ZWRcIiB9KTtcclxuICAgICAgICB9IGVsc2UgaWYgKHNldFNwZWVkSW5kZXggPT09IHNwZWVkTGlzdC5sZW5ndGggLSAxKSB7XHJcbiAgICAgICAgICAkKFwiLndzLXJlY29yZC1zcGVlZC1hZGRcIiwgdGhpcy4kZWwpLmNzcyh7IGN1cnNvcjogXCJub3QtYWxsb3dlZFwiIH0pO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAkKFwiLndzLXJlY29yZC1zcGVlZC1zdWJcIiwgdGhpcy4kZWwpLmNzcyh7IGN1cnNvcjogXCJwb2ludGVyXCIgfSk7XHJcbiAgICAgICAgICAkKFwiLndzLXJlY29yZC1zcGVlZC1hZGRcIiwgdGhpcy4kZWwpLmNzcyh7IGN1cnNvcjogXCJwb2ludGVyXCIgfSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIOWAjemAn+WbnuaYvlxyXG4gICAgICAgICQoXCIud3MtcmVjb3JkLXNwZWVkLXR4dFwiLCB0aGlzLiRlbCkudGV4dChzZXRTcGVlZEl0ZW0ubGFiZWwpO1xyXG4gICAgICAgIC8vIOiuvue9ruWAjemAn1xyXG4gICAgICAgIHBsYXllci5zdGF0dXMgPT09IFwicGxheWluZ1wiICYmXHJcbiAgICAgICAgICB0aGlzLnBsYXlTcGVlZChzZXRTcGVlZEl0ZW0udmFsdWUsIHdpbmRvd0luZGV4KTtcclxuICAgICAgICByZXR1cm4gdHJ1ZTtcclxuICAgICAgfVxyXG4gICAgfSk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDmt7vliqDlvZXlg4/lm57mlL7mjqfliLbmoI9cclxuICAgKi9cclxuICBfX2FkZFJlY29yZENvbnRyb2woKSB7XHJcbiAgICB0aGlzLiRlbC5hcHBlbmQoYFxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwid3MtY29udHJvbCB3cy1yZWNvcmQtY29udHJvbFwiPlxyXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXRpbWVsaW5lXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXRpbWVsaW5lLWdyb3VwXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIndzLXRpbWVsaW5lLWdyb3VwXCI+PC9kaXY+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDwhLS3lvZPliY3mkq3mlL7nmoTml7bpl7TngrktLT5cclxuICAgICAgICAgICAgICAgIDxkaXYgaWQ9XCJ3cy1yZWNvcmQtdGltZS1ib3hcIj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSd3cy1yZWNvcmQtdGltZSc+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxzcGFuPjwvc3Bhbj5cclxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgPGNhbnZhcyBoZWlnaHQ9XCI2MFwiIGlkPVwid3MtcmVjb3JkLWNhbnZhc1wiIGNsYXNzPVwid3MtcmVjb3JkLWFyZWFcIi8+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIGApO1xyXG4gICAgdGhpcy5jYW52YXMgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcIndzLXJlY29yZC1jYW52YXNcIik7XHJcbiAgICB0aGlzLmN0eCA9IHRoaXMuY2FudmFzLmdldENvbnRleHQoXCIyZFwiKTtcclxuICAgIGxldCB3c1RpbWVHcm91cDEgPSAkKFxyXG4gICAgICB0aGlzLiRlbFswXS5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwid3MtdGltZWxpbmUtZ3JvdXBcIilbMF1cclxuICAgICk7XHJcbiAgICBsZXQgd3NUaW1lR3JvdXAyID0gJChcclxuICAgICAgdGhpcy4kZWxbMF0uZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcIndzLXRpbWVsaW5lLWdyb3VwXCIpWzFdXHJcbiAgICApO1xyXG4gICAgLy8g5re75Yqg5pe26Ze06Ze06ZqUXHJcbiAgICBuZXcgQXJyYXkoNDkpLmZpbGwoMSkuZm9yRWFjaCgoaXRlbSwgaW5kZXgpID0+IHtcclxuICAgICAgbGV0IGNsYXNzTmFtZSA9IGB3cy10aW1lLXNwYWNlICR7aW5kZXggJSA0ID8gXCJcIiA6IFwid3MtdGltZS1zcGFjZS1sb25nXCJ9YDtcclxuICAgICAgd3NUaW1lR3JvdXAxLmFwcGVuZChgPHNwYW4gY2xhc3M9XCIke2NsYXNzTmFtZX1cIj48L3NwYW4+YCk7XHJcbiAgICB9KTtcclxuICAgIC8vIOa3u+WKoOaXtumXtOeCuVxyXG4gICAgbmV3IEFycmF5KDEzKS5maWxsKDEpLmZvckVhY2goKGl0ZW0sIGluZGV4KSA9PiB7XHJcbiAgICAgIHdzVGltZUdyb3VwMi5hcHBlbmQoXHJcbiAgICAgICAgYDxzcGFuIGNsYXNzPVwid3MtdGltZS1wb2ludFwiPiR7YCR7aW5kZXggKiAyfTowMGAucGFkU3RhcnQoXHJcbiAgICAgICAgICA1LFxyXG4gICAgICAgICAgXCIwXCJcclxuICAgICAgICApfTwvc3Bhbj5gXHJcbiAgICAgICk7XHJcbiAgICB9KTtcclxuICAgICQoXCIud3MtcmVjb3JkLWNvbnRyb2xcIikubW91c2VlbnRlcigoZSkgPT4ge1xyXG4gICAgICAkKFwiLndzLXJlY29yZC1jb250cm9sXCIpLmFwcGVuZChcclxuICAgICAgICBcIjxkaXYgaWQ9J3dzLWN1cnNvcic+PGRpdiBjbGFzcz0nd3MtY3Vyc29yLXRpbWUnPjxzcGFuPjwvc3Bhbj48L2Rpdj48L2Rpdj5cIlxyXG4gICAgICApO1xyXG4gICAgfSk7XHJcbiAgICAvLyDmt7vliqDpvKDmoIfnp7vlhaXkuovku7ZcclxuICAgICQoXCIud3MtcmVjb3JkLWNvbnRyb2xcIikubW91c2Vtb3ZlKChlKSA9PiB7XHJcbiAgICAgIGxldCB3aWR0aCA9ICQoXCIud3MtcmVjb3JkLWNvbnRyb2xcIikud2lkdGgoKTtcclxuICAgICAgbGV0IGxheWVyWCA9XHJcbiAgICAgICAgZS5jbGllbnRYIC0gJChcIi53cy1yZWNvcmQtY29udHJvbFwiKVswXS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS5sZWZ0O1xyXG4gICAgICBsZXQgZGF0ZSA9IG5ldyBEYXRlKFxyXG4gICAgICAgICgobGF5ZXJYIC8gd2lkdGgpICogMjQgKiA2MCAqIDYwIC0gOCAqIDYwICogNjApICogMTAwMFxyXG4gICAgICApO1xyXG4gICAgICBsZXQgaG91cnMgPSBgJHtkYXRlLmdldEhvdXJzKCl9YC5wYWRTdGFydCgyLCBcIjBcIik7XHJcbiAgICAgIGxldCBtaW51dGVzID0gYCR7ZGF0ZS5nZXRNaW51dGVzKCl9YC5wYWRTdGFydCgyLCBcIjBcIik7XHJcbiAgICAgIGxldCBzZWNvbmRzID0gYCR7ZGF0ZS5nZXRTZWNvbmRzKCl9YC5wYWRTdGFydCgyLCBcIjBcIik7XHJcbiAgICAgIGxldCB0aW1lID0gYCR7aG91cnN9OiR7bWludXRlc306JHtzZWNvbmRzfWA7XHJcbiAgICAgICQoXCIjd3MtY3Vyc29yXCIpLmNzcyhcImxlZnRcIiwgbGF5ZXJYKTtcclxuICAgICAgJChcIiN3cy1jdXJzb3Igc3BhblwiKS50ZXh0KHRpbWUpO1xyXG4gICAgfSk7XHJcbiAgICAkKFwiLndzLXJlY29yZC1jb250cm9sXCIpLm1vdXNlbGVhdmUoKGUpID0+IHtcclxuICAgICAgJChcIiN3cy1jdXJzb3JcIikucmVtb3ZlKCk7XHJcbiAgICB9KTtcclxuICAgIC8vIOeCueWHu+afkOS4quaXtumXtOeCuei/m+ihjOaSreaUvlxyXG4gICAgJChcIi53cy1yZWNvcmQtY29udHJvbFwiKS5jbGljaygoZSkgPT4ge1xyXG4gICAgICAvLyDlj6rmnInpgInkuK3nmoTmkq3mlL7lmajlnKjmkq3mlL7miJbogIXmmoLlgZznirbmgIHvvIzmiY3og73moLnmja7ml7bpl7TovbTpgInmi6nmkq3mlL5cclxuICAgICAgaWYgKFxyXG4gICAgICAgIFtcInBsYXlpbmdcIiwgXCJwYXVzZVwiXS5pbmNsdWRlcyhcclxuICAgICAgICAgICh0aGlzLnBsYXllckxpc3RbdGhpcy5zZWxlY3RJbmRleF0gfHwge30pLnN0YXR1c1xyXG4gICAgICAgIClcclxuICAgICAgKSB7XHJcbiAgICAgICAgbGV0IHdpZHRoID0gJChcIi53cy1yZWNvcmQtY29udHJvbFwiKS53aWR0aCgpO1xyXG4gICAgICAgIGxldCBsYXllclggPVxyXG4gICAgICAgICAgZS5jbGllbnRYIC0gJChcIi53cy1yZWNvcmQtY29udHJvbFwiKVswXS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS5sZWZ0O1xyXG4gICAgICAgIGxldCB0aW1lU3RhbXAgPSBwYXJzZUludCgobGF5ZXJYIC8gd2lkdGgpICogMjQgKiA2MCAqIDYwLCAxMCk7XHJcbiAgICAgICAgLy8g6K6h566X5omA6YCJ5pe26Ze054K55piv5ZCm5pyJ5b2V5YOP77yM5aaC5p6c5pyJ5YiZ6L+U5Zue5pe26Ze054K577yM5ZCm5YiZ6L+U5Zue56m65a2X56ym5LiyXHJcbiAgICAgICAgbGV0IHRpbWUgPVxyXG4gICAgICAgICAgbmV3IERhdGUodGhpcy50aW1lTGlzdFswXS5zdGFydFRpbWUgKiAxMDAwKS5zZXRIb3VycygwLCAwLCAwKSAvIDEwMDAgK1xyXG4gICAgICAgICAgdGltZVN0YW1wO1xyXG4gICAgICAgIGlmIChcclxuICAgICAgICAgICF0aGlzLnRpbWVMaXN0LnNvbWUoKHRpbWVJdGVtKSA9PiB7XHJcbiAgICAgICAgICAgIGlmICh0aW1lID49IHRpbWVJdGVtLnN0YXJ0VGltZSAmJiB0aW1lIDwgdGltZUl0ZW0uZW5kVGltZSkge1xyXG4gICAgICAgICAgICAgIHRoaXMuY2xpY2tSZWNvcmRUaW1lTGluZSh0aW1lU3RhbXApO1xyXG4gICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICB9KVxyXG4gICAgICAgICkge1xyXG4gICAgICAgICAgdGhpcy5jbGlja1JlY29yZFRpbWVMaW5lKFwiXCIpO1xyXG4gICAgICAgIH1cclxuICAgICAgfVxyXG4gICAgfSk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDorr7nva7mnInlvZXlg4/nmoTljLrln59cclxuICAgKiBAcGFyYW0gdGltZUxpc3Q6IFt7c3RhcnRUaW1lOiB4eHgsIGVuZFRpbWU6IHh4eCwgaXNJbXBvcnRhbnQ6IGZhbHNlfV1cclxuICAgKiBzdGFydFRpbWUg5ZKMIGVuZFRpbWUg6YO95piv56eS57qn5pe26Ze05oizXHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICBfX3NldFRpbWVSZWNvcmRBcmVhKHRpbWVMaXN0ID0gW10pIHtcclxuICAgIGlmICh0aW1lTGlzdC5sZW5ndGgpIHtcclxuICAgICAgLy8g6K6+572uY2FudmFz55qE5a695bqmXHJcbiAgICAgIGxldCBib3hXaWR0aCA9ICQoXCIud3MtcmVjb3JkLWNvbnRyb2xcIikud2lkdGgoKTtcclxuICAgICAgdGhpcy5jYW52YXMud2lkdGggPSBib3hXaWR0aDtcclxuXHJcbiAgICAgIC8vIOWwhuacieW9leWDj+eahOWMuuWfn+i/m+ihjOWIhuexu++8jOWFiOe7mOWItuaZrumAmuW9leWDj++8jOWGjee7mOWItuaKpeitpuW9leWDj++8jOmBv+WFjeaZrumAmuW9leWDj+imhuebluaKpeitpuW9leWDj1xyXG4gICAgICBsZXQgcmVjb3JkTGlzdCA9IFtdO1xyXG4gICAgICBsZXQgYWxhcm1SZWNvcmRMaXN0ID0gW107XHJcblxyXG4gICAgICAvLyDmma7pgJrlvZXlg4/muJDlj5joibJcclxuICAgICAgbGV0IHJlY29yZEdyYWRpZW50ID0gdGhpcy5jdHguY3JlYXRlTGluZWFyR3JhZGllbnQoMCwgMCwgMCwgNjApO1xyXG4gICAgICByZWNvcmRHcmFkaWVudC5hZGRDb2xvclN0b3AoMCwgXCJyZ2JhKDc3LCAyMDEsIDIzMywgMC4xKVwiKTtcclxuICAgICAgcmVjb3JkR3JhZGllbnQuYWRkQ29sb3JTdG9wKDEsIFwiIzFjNzlmNFwiKTtcclxuXHJcbiAgICAgIC8vIOaKpeitpuW9leWDj+a4kOWPmOiJslxyXG4gICAgICBsZXQgYWxhcm1SZWNvcmRHcmFkaWVudCA9IHRoaXMuY3R4LmNyZWF0ZUxpbmVhckdyYWRpZW50KDAsIDAsIDAsIDYwKTtcclxuICAgICAgYWxhcm1SZWNvcmRHcmFkaWVudC5hZGRDb2xvclN0b3AoMCwgXCJyZ2JhKDI1MSwgMTIxLCAxMDEsIDAuMSlcIik7XHJcbiAgICAgIGFsYXJtUmVjb3JkR3JhZGllbnQuYWRkQ29sb3JTdG9wKDEsIFwiI2I1MmMyY1wiKTtcclxuXHJcbiAgICAgIHRpbWVMaXN0LmZvckVhY2goKHRpbWVJdGVtKSA9PiB7XHJcbiAgICAgICAgLy8g5b2V5YOP5Yy65Z+f6ZW/5bqmXHJcbiAgICAgICAgdGltZUl0ZW0ud2lkdGggPVxyXG4gICAgICAgICAgKCh0aW1lSXRlbS5lbmRUaW1lIC0gdGltZUl0ZW0uc3RhcnRUaW1lKSAqIGJveFdpZHRoKSAvICgyNCAqIDYwICogNjApO1xyXG4gICAgICAgIGxldCBkYXRlID0gbmV3IERhdGUodGltZUl0ZW0uc3RhcnRUaW1lICogMTAwMCk7XHJcbiAgICAgICAgbGV0IGhvdXJzID0gZGF0ZS5nZXRIb3VycygpO1xyXG4gICAgICAgIGxldCBtaW51dGVzID0gZGF0ZS5nZXRNaW51dGVzKCk7XHJcbiAgICAgICAgbGV0IHNlY29uZHMgPSBkYXRlLmdldFNlY29uZHMoKTtcclxuICAgICAgICB0aW1lSXRlbS5sZWZ0ID1cclxuICAgICAgICAgICgoaG91cnMgKiAzNjAwICsgbWludXRlcyAqIDYwICsgc2Vjb25kcykgLyAoMjQgKiAzNjAwKSkgKiBib3hXaWR0aDtcclxuICAgICAgICBpZiAodGltZUl0ZW0uaXNJbXBvcnRhbnQpIHtcclxuICAgICAgICAgIGFsYXJtUmVjb3JkTGlzdC5wdXNoKHRpbWVJdGVtKTtcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgcmVjb3JkTGlzdC5wdXNoKHRpbWVJdGVtKTtcclxuICAgICAgICB9XHJcbiAgICAgIH0pO1xyXG5cclxuICAgICAgLy8g57uY5Yi25pmu6YCa5b2V5YOPXHJcbiAgICAgIHJlY29yZExpc3QuZm9yRWFjaCgodGltZUl0ZW0pID0+IHtcclxuICAgICAgICB0aGlzLmN0eC5jbGVhclJlY3QodGltZUl0ZW0ubGVmdCwgMCwgdGltZUl0ZW0ud2lkdGgsIDYwKTtcclxuICAgICAgICB0aGlzLmN0eC5maWxsU3R5bGUgPSByZWNvcmRHcmFkaWVudDtcclxuICAgICAgICB0aGlzLmN0eC5maWxsUmVjdCh0aW1lSXRlbS5sZWZ0LCAwLCB0aW1lSXRlbS53aWR0aCwgNjApO1xyXG4gICAgICB9KTtcclxuXHJcbiAgICAgIC8vIOe7mOWItuaKpeitpuW9leWDj1xyXG4gICAgICBhbGFybVJlY29yZExpc3QuZm9yRWFjaCgodGltZUl0ZW0pID0+IHtcclxuICAgICAgICB0aGlzLmN0eC5jbGVhclJlY3QodGltZUl0ZW0ubGVmdCwgMCwgdGltZUl0ZW0ud2lkdGgsIDYwKTtcclxuICAgICAgICB0aGlzLmN0eC5maWxsU3R5bGUgPSBhbGFybVJlY29yZEdyYWRpZW50O1xyXG4gICAgICAgIHRoaXMuY3R4LmZpbGxSZWN0KHRpbWVJdGVtLmxlZnQsIDAsIHRpbWVJdGVtLndpZHRoLCA2MCk7XHJcbiAgICAgIH0pO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgdGhpcy5jYW52YXMud2lkdGggPSAwO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog6L+b5bqm5p2h5pi+56S65b2T5YmN5pKt5pS+55qE5pe26Ze0XHJcbiAgICogQHBhcmFtIHdpbmRvd0luZGV4IOW9k+WJjeaSreaUvueahOeql+WPo1xyXG4gICAqIEBwYXJhbSB0aW1lU3RhbXAg5q2j5Zyo5pKt5pS+55qE5pe26Ze0XHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICBzZXRQbGF5aW5nVGltZSh3aW5kb3dJbmRleCwgdGltZVN0YW1wKSB7XHJcbiAgICB0aGlzLnNlbmRNZXNzYWdlKFwicmVjb3JkVGltZUNoYW5nZVwiLCB0aW1lU3RhbXApO1xyXG4gICAgaWYgKHRoaXMuc2VsZWN0SW5kZXggPT09IHdpbmRvd0luZGV4KSB7XHJcbiAgICAgIGxldCBib3hXaWR0aCA9ICQoXCIud3MtcmVjb3JkLWNvbnRyb2xcIikud2lkdGgoKTtcclxuICAgICAgbGV0IGRhdGUgPSBuZXcgRGF0ZSh0aW1lU3RhbXApO1xyXG4gICAgICBsZXQgaG91cnMgPSBkYXRlLmdldEhvdXJzKCk7XHJcbiAgICAgIGxldCBtaW51dGVzID0gZGF0ZS5nZXRNaW51dGVzKCk7XHJcbiAgICAgIGxldCBzZWNvbmRzID0gZGF0ZS5nZXRTZWNvbmRzKCk7XHJcbiAgICAgIGxldCBsZWZ0ID1cclxuICAgICAgICAoKGhvdXJzICogMzYwMCArIG1pbnV0ZXMgKiA2MCArIHNlY29uZHMpIC8gKDI0ICogMzYwMCkpICogYm94V2lkdGg7XHJcbiAgICAgIGxldCB0aW1lID0gYCR7U3RyaW5nKGhvdXJzKS5wYWRTdGFydCgyLCBcIjBcIil9OiR7U3RyaW5nKG1pbnV0ZXMpLnBhZFN0YXJ0KFxyXG4gICAgICAgIDIsXHJcbiAgICAgICAgXCIwXCJcclxuICAgICAgKX06JHtTdHJpbmcoc2Vjb25kcykucGFkU3RhcnQoMiwgXCIwXCIpfWA7XHJcbiAgICAgICQoXCIjd3MtcmVjb3JkLXRpbWUtYm94XCIpLmNzcyhcImxlZnRcIiwgbGVmdCk7XHJcbiAgICAgICQoXCIjd3MtcmVjb3JkLXRpbWUtYm94IHNwYW5cIikudGV4dCh0aW1lKTtcclxuICAgIH1cclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOagueaNrlJUU1DlnLDlnYDojrflj5Z3c1VybFxyXG4gICAqIEBwYXJhbSB7U3RyaW5nfSBydHNwVXJsXHJcbiAgICogQHBhcmFtIHtTdHJpbmd9IHNlcnZlcklwOiBpY2PlubPlj7DlhoXnvZFJUCB8IHBhYXPlhoXnvZFpcFxyXG4gICAqL1xyXG4gIF9fZ2V0V1NVcmwocnRzcFVybCwgc2VydmVySXApIHtcclxuICAgIC8vIOWIpOaWreWNj+iurlxyXG4gICAgbGV0IGlzSHR0cHMgPSBsb2NhdGlvbi5wcm90b2NvbCA9PT0gXCJodHRwczpcIjtcclxuICAgIGxldCBpcCA9IHJ0c3BVcmwubWF0Y2goL1xcZHsxLDN9KFxcLlxcZHsxLDN9KXszfS9nKVswXTtcclxuICAgIGlmICghaXApIHtcclxuICAgICAgaXAgPSBydHNwVXJsLnNwbGl0KFwiLy9cIilbMV0uc3BsaXQoXCI6XCIpWzBdO1xyXG4gICAgfVxyXG4gICAgbGV0IHByb3RvY29sID0gaXNIdHRwcyA/IFwid3NzXCIgOiBcIndzXCI7XHJcbiAgICBpZiAoaXNIdHRwcyB8fCB0aGlzLmNvbmZpZy51c2VOZ2lueFByb3h5KSB7XHJcbiAgICAgIC8vIGh0dHBz6ZyA6KaBbmdpbnjovazlj5HvvIznu5Xov4dodHRwc+ivgeS5puiupOivgemXrumimFxyXG4gICAgICAvLyDlpoLmnpzojrflj5bkuI3liLDnm7jlhbPnmoTlhoXnvZFpcO+8jOWImeS7jnJ0c3DlnLDlnYDkuIrmiKrlj5ZpcFxyXG4gICAgICBsZXQgd3NzUG9ydCA9XHJcbiAgICAgICAgdGhpcy50eXBlID09PSBcInJlYWxcIlxyXG4gICAgICAgICAgPyBDT05TVEFOVC53ZWJzb2NrZXRQb3J0cy5yZWFsbW9uaXRvclxyXG4gICAgICAgICAgOiBDT05TVEFOVC53ZWJzb2NrZXRQb3J0cy5wbGF5YmFjaztcclxuICAgICAgcmV0dXJuIGAke3Byb3RvY29sfTovLyR7dGhpcy5zZXJ2ZXJJcH0vJHt3c3NQb3J0fT9zZXJ2ZXJJcD0ke1xyXG4gICAgICAgIHNlcnZlcklwIHx8IGlwXHJcbiAgICAgIH1gO1xyXG4gICAgfVxyXG4gICAgLy8gaHR0cOWNj+iuruWwseeUqHdz5Y2P6K6u55u06L+e5rWB5aqS5L2T5pyN5YqhXHJcbiAgICBsZXQgd3NQb3J0ID1cclxuICAgICAgdGhpcy50eXBlID09PSBcInJlYWxcIlxyXG4gICAgICAgID8gQ09OU1RBTlQud2Vic29ja2V0UG9ydHMucmVhbG1vbml0b3Jfd3NcclxuICAgICAgICA6IENPTlNUQU5ULndlYnNvY2tldFBvcnRzLnBsYXliYWNrX3dzO1xyXG4gICAgcmV0dXJuIGAke3Byb3RvY29sfTovLyR7dGhpcy5zZXJ2ZXJJcH06JHt3c1BvcnR9YDtcclxuICB9XHJcbiAgLyoqXHJcbiAgICog5pu05paw5pKt5pS+5Zmo56qX5Y+jXHJcbiAgICovXHJcbiAgX191cGRhdGVQbGF5ZXJXaW5kb3coKSB7XHJcbiAgICB0aGlzLnBsYXllckxpc3QuZm9yRWFjaCgoaXRlbSkgPT4ge1xyXG4gICAgICBpdGVtLnVwZGF0ZUFkYXB0ZXIodGhpcy5wbGF5ZXJBZGFwdGVyKTtcclxuICAgIH0pO1xyXG4gICAgdGhpcy5zZXRUaW1lTGluZSh0aGlzLnRpbWVMaXN0KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOaOp+WItuWjsOmfs+aSreaUvlxyXG4gICAqIEBwYXJhbSBwbGF5ZXJXcmFwcGVyXHJcbiAgICogQHBhcmFtIGNob29zZUZsYWfvvJrlvZPliY3nmoTnqpflj6PmmK/lkKbooqvpgInkuK1cclxuICAgKiBAcHJpdmF0ZVxyXG4gICAqL1xyXG4gIF9fdXBkYXRlVm9pY2UocGxheWVyV3JhcHBlciwgY2hvb3NlRmxhZykge1xyXG4gICAgaWYgKCFjaG9vc2VGbGFnKSB7XHJcbiAgICAgIC8vIOWFs+mXreacquiiq+mAieS4reeahOeql+WPo+eahOWjsOmfs++8jOS9huaYr+aSreaUvuWjsOmfs+eahOWbvuagh+S/neeVmeaYvuekulxyXG4gICAgICBwbGF5ZXJXcmFwcGVyLnBsYXllciAmJiBwbGF5ZXJXcmFwcGVyLnBsYXllci5zZXRBdWRpb1ZvbHVtZSgwKTtcclxuICAgIH0gZWxzZSBpZiAoJChcIi5hdWRpby1pY29uXCIsIHBsYXllcldyYXBwZXIuJGVsKS5oYXNDbGFzcyhcIm9uXCIpKSB7XHJcbiAgICAgIC8vIOiLpeeql+WPo+iiq+mAieS4re+8jOS4lOWjsOmfs+iiq+W8gOWQr++8jOWwseaSreaUvuWjsOmfs1xyXG4gICAgICBwbGF5ZXJXcmFwcGVyLnBsYXllci5zZXRBdWRpb1ZvbHVtZSgxKTtcclxuICAgIH1cclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOW8gOWni+WvueiuslxyXG4gICAqIEBwYXJhbSBjaGFubmVsXHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICBfX3N0YXJ0VGFsayhjaGFubmVsKSB7XHJcbiAgICB0aGlzLnByb2NlZHVyZSAmJiB0aGlzLnByb2NlZHVyZS5zdGFydFRhbGsoY2hhbm5lbCk7XHJcbiAgfVxyXG5cclxuICAvKiAtLS0tLS0tLS0tLS0tLS0tLSDlr7nlpJbmlrnms5UgLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbiAgLyoqXHJcbiAgICog5YiH5o2i56CB5rWBXHJcbiAgICogQHBhcmFtIGNoYW5uZWwg6YCa6YGT5a+56LGhXHJcbiAgICogQHBhcmFtIHN0cmVhbVR5cGUg56CB5rWB57G75Z6LXHJcbiAgICogQHBhcmFtIHNlbGVjdEluZGV4IOmcgOimgeWIh+aNoueggea1geeahOeql+WPo+e0ouW8lVxyXG4gICAqL1xyXG4gIGNoYW5nZVN0cmVhbVR5cGUoY2hhbm5lbCwgc3RyZWFtVHlwZSwgc2VsZWN0SW5kZXgpIHtcclxuICAgIHRoaXMucHJvY2VkdXJlICYmXHJcbiAgICAgIHRoaXMucHJvY2VkdXJlLnBsYXlSZWFsVmlkZW8oW2NoYW5uZWxdLCBzdHJlYW1UeXBlLCBzZWxlY3RJbmRleCk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDojrflj5blvZXlg4/liJfooahcclxuICAgKiBAcGFyYW0gb3B0LmNoYW5uZWxMaXN0IOmAmumBk2lkICDlv4XpgIlcclxuICAgKiBAcGFyYW0gb3B0LnN0YXJ0VGltZSDlvIDlp4vml7bpl7QgIOW/hemAiVxyXG4gICAqIEBwYXJhbSBvcHQuZW5kVGltZSDnu5PmnZ/ml7bpl7QgIOW/hemAiVxyXG4gICAqIEBwYXJhbSBvcHQucmVjb3JkU291cmNlIOW9leWDj+adpea6kCAg5b+F6YCJXHJcbiAgICogQHBhcmFtIG9wdC5zdHJlYW1UeXBlIOeggea1geexu+Wei1xyXG4gICAqIEBwYXJhbSBvcHQucmVjb3JkVHlwZSDlvZXlg4/nsbvlnotcclxuICAgKi9cclxuICBnZXRSZWNvcmRMaXN0KG9wdCkge1xyXG4gICAgdGhpcy5wcm9jZWR1cmUgJiYgdGhpcy5wcm9jZWR1cmUuZ2V0UmVjb3JkTGlzdChvcHQpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog54K55Ye75pe26Ze06L206Lez6L2s5pKt5pS+XHJcbiAgICogQHBhcmFtIHRpbWVTdGFtcFxyXG4gICAqL1xyXG4gIGNsaWNrUmVjb3JkVGltZUxpbmUodGltZVN0YW1wKSB7XHJcbiAgICBpZiAodGltZVN0YW1wKSB7XHJcbiAgICAgIHRoaXMucHJvY2VkdXJlICYmIHRoaXMucHJvY2VkdXJlLmNsaWNrUmVjb3JkVGltZUxpbmUodGltZVN0YW1wKTtcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgIGNvbnNvbGUud2FybihcIuaJgOmAieaXtumXtOeCueaXoOW9leWDj1wiKTtcclxuICAgIH1cclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOagueaNruaXtumXtOi3s+i9rOaSreaUvlxyXG4gICAqIEBwYXJhbSB0aW1lIOW9k+WkqeaXtumXtO+8mkhIOm1tOnNzXHJcbiAgICovXHJcbiAganVtcFBsYXlCeVRpbWUodGltZSkge1xyXG4gICAgdGhpcy5wcm9jZWR1cmUgJiYgdGhpcy5wcm9jZWR1cmUuanVtcFBsYXlCeVRpbWUodGltZSk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDoh6rliqjmkq3mlL7kuIvkuIDmrrXlvZXlg49cclxuICAgKiBAcGFyYW0gc2VsZWN0SW5kZXhcclxuICAgKi9cclxuICBwbGF5TmV4dFJlY29yZChzZWxlY3RJbmRleCkge1xyXG4gICAgdGhpcy5zZW5kTWVzc2FnZShcclxuICAgICAgXCJyZWNvcmRQbGF5RW5kXCIsXHJcbiAgICAgICh0aGlzLnBsYXllckxpc3RbaW5kZXhdLm9wdGlvbnMgfHwge30pLmNoYW5uZWxJZFxyXG4gICAgKTtcclxuICAgIC8vIHRoaXMucHJvY2VkdXJlICYmIHRoaXMucHJvY2VkdXJlLnBsYXlOZXh0UmVjb3JkKHNlbGVjdEluZGV4KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOafkOS4queql+WPo+eahOinhumikeiiq+WFs+mXrVxyXG4gICAqIEBwYXJhbSBzZWxlY3RJbmRleCDop4bpopHlhbPpl63nqpflj6PnmoTntKLlvJVcclxuICAgKiBAcGFyYW0gY2hhbmdlVmlkZW9GbGFnIOaYr+WQpuWboOWIh+aNouWFtuS7luinhumikeiAjOWFs+mXreeOsOWcqOinhumikVxyXG4gICAqL1xyXG4gIHZpZGVvQ2xvc2VkKHNlbGVjdEluZGV4LCBjaGFuZ2VWaWRlb0ZsYWcpIHtcclxuICAgIHRoaXMuc2VuZE1lc3NhZ2UoXCJjbG9zZVZpZGVvXCIsIHtcclxuICAgICAgc2VsZWN0SW5kZXgsXHJcbiAgICAgIGNoYW5nZVZpZGVvRmxhZyxcclxuICAgIH0pO1xyXG4gICAgdGhpcy5wcm9jZWR1cmUgJiYgdGhpcy5wcm9jZWR1cmUudmlkZW9DbG9zZWQoc2VsZWN0SW5kZXgsIGNoYW5nZVZpZGVvRmxhZyk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDlj5HpgIHplJnor6/kv6Hmga9cclxuICAgKiBAcGFyYW0gZXJyb3JDb2RlXHJcbiAgICogQHBhcmFtIGNoYW5uZWxMaXN0XHJcbiAgICovXHJcbiAgc2VuZEVycm9yTWVzc2FnZShlcnJvckNvZGUsIGNoYW5uZWxMaXN0KSB7XHJcbiAgICB0aGlzLnNlbmRNZXNzYWdlKFwiZXJyb3JJbmZvXCIsIHtcclxuICAgICAgZXJyb3JDb2RlLFxyXG4gICAgICBlcnJvckluZm86IENPTlNUQU5ULmVycm9ySW5mb1tlcnJvckNvZGVdLFxyXG4gICAgICBjaGFubmVsTGlzdCxcclxuICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgLy8gLS0tLS0tLS0tLS0tLSDkupHlj7Dnm7jlhbPlip/og70gLS0tLS0tLS0tLS0tLS0tLS0gLy9cclxuICAvKipcclxuICAgKiDliJ3lp4vljJbkupHlj7BcclxuICAgKiBAcGFyYW0gb3B0aW9uc1xyXG4gICAqL1xyXG4gIGluaXRQYW5UaWx0KG9wdGlvbnMpIHtcclxuICAgIHRoaXMucGFuVGlsdCA9IG5ldyBQYW5UaWx0KG9wdGlvbnMsIHRoaXMpO1xyXG4gIH1cclxuXHJcbiAgc2V0UHR6Q2hhbm5lbChjaGFubmVsKSB7XHJcbiAgICB0aGlzLnBhblRpbHQgJiYgdGhpcy5wYW5UaWx0LnNldENoYW5uZWwoY2hhbm5lbCk7XHJcbiAgfVxyXG59XHJcblxyXG5leHBvcnQgeyBXU1BsYXllciB9O1xyXG5cclxuZXhwb3J0IGRlZmF1bHQgV1NQbGF5ZXI7XHJcbiIsIi8vIFdTUGxheWVyIOW3sue7j+azqOWGjOWIsHdpbmRvd+WvueixoeS4re+8jOaXoOmcgOmHjeWkjeW8leeUqOOAguaIluiAheagueaNruWunumZheaDheWGteW8leWFpVxyXG4vLyDosIPor5XmqKHlvI/kuIvkvb/nlKjmupDnoIHml7bpnIDopoHlsIbkuIvpnaLnmoTms6jph4rmlL7lvIBcclxuaW1wb3J0IFdTUGxheWVyIGZyb20gXCIuL1dTUGxheWVyL1dTUGxheWVyXCI7XHJcblxyXG5sZXQgV1NQbGF5ZXJDb25zdHJ1Y3RvciA9IFdTUGxheWVyO1xyXG5pZiAoV1NQbGF5ZXIuV1NQbGF5ZXIpIHtcclxuICBXU1BsYXllckNvbnN0cnVjdG9yID0gV1NQbGF5ZXIuV1NQbGF5ZXI7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBXU1BsYXllcuaYr+aguOW/g+e7hOS7tlxyXG4gKiBBUEkg5bCB6KOF5LqG5o6l5Y+jXHJcbiAqL1xyXG5jbGFzcyBQbGF5ZXJNYW5hZ2VyIHtcclxuICBjb25zdHJ1Y3RvcihvcHQpIHtcclxuICAgIC8vIOaSreaUvuWZqOaJgOWcqOeahOWuueWZqElEXHJcbiAgICB0aGlzLmVsID0gb3B0LmVsO1xyXG4gICAgLy8g5a6e5pe26aKE6KeI5pKt5pS+5ZmoXHJcbiAgICB0aGlzLnJlYWxQbGF5ZXIgPSBudWxsO1xyXG4gICAgLy8g5b2V5YOP5Zue5pS+5pKt5pS+5ZmoXHJcbiAgICB0aGlzLnJlY29yZFBsYXllciA9IG51bGw7XHJcbiAgICB0aGlzLnBsYXllciA9IG51bGw7XHJcbiAgICAvLyDlrp7ml7bpooTop4jov5jmmK/lvZXlg4/lm57mlL7mkq3mlL7lmahcclxuICAgIHRoaXMudHlwZSA9IFwicmVhbFwiO1xyXG4gICAgLy8g56qX5Y+j55qE5pWw6YePXHJcbiAgICB0aGlzLnBsYXlOdW0gPSAxO1xyXG4gICAgLy8g5b2T5YmN6YCJ5Lit55qE56qX5Y+j55qE57Si5byVXHJcbiAgICB0aGlzLnBsYXlJbmRleCA9IDA7XHJcbiAgICAvLyDlvZPliY3pgInkuK3nqpflj6PmraPlnKjmkq3mlL7op4bpopHnmoTpgJrpgZNcclxuICAgIHRoaXMuY3VycmVudENoYW5uZWxJZCA9IFwiXCI7XHJcbiAgICAvLyDntKLlvJXlr7nlupTnqpflj6PvvIzkv53lrZjlvZPml6XlvZXlg4/kv6Hmga9cclxuICAgIHRoaXMucmVjb3JkTGlzdCA9IFtdO1xyXG5cclxuICAgIC8vIC8vIOiOt+WPlui/nuaOpeeahOacjeWKoeeahGlwXHJcbiAgICAvLyBpZiAoXHJcbiAgICAvLyAgIHByb2Nlc3MuZW52Lk5PREVfRU5WID09PSBcImRldmVsb3BtZW50XCIgfHxcclxuICAgIC8vICAgaW1wb3J0Lm1ldGEuZW52LlZJVEVfTk9ERV9FTlYgPT09IFwid2lraVwiXHJcbiAgICAvLyApIHtcclxuICAgIC8vICAgdGhpcy5zZXJ2ZXJJcCA9IGxvY2FsU3RvcmFnZVtcInByb3h5SXBcIl07XHJcbiAgICAvLyB9IGVsc2Uge1xyXG4gICAgLy8gICB0aGlzLnNlcnZlcklwID0gd2luZG93LmxvY2F0aW9uLmhvc3Q7XHJcbiAgICAvLyB9XHJcbiAgICAvLyDlpoLmnpznrKzkuInmlrnlr7nmjqXmnI3liqHmmK9odHRw5Y2P6K6u77yM6YKj5LmIdGhpcy5zZXJ2ZXJJcOWwseW+l+WGmeaIkElDQ+W5s+WPsOeahGlwXHJcbiAgICAvLyB0aGlzLnNlcnZlcklwID0gXCIxMC41NS4zNi4xNTNcIlxyXG5cclxuICAgIC8vIOWIneWni+WMluaSreaUvuWZqFxyXG4gICAgc3dpdGNoIChvcHQudHlwZSkge1xyXG4gICAgICBjYXNlIFwicmVhbFwiOlxyXG4gICAgICAgIHRoaXMuaW5pdFJlYWxQbGF5ZXIob3B0KTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgY2FzZSBcInJlY29yZFwiOlxyXG4gICAgICAgIHRoaXMuaW5pdFJlY29yZFBsYXllcihvcHQpO1xyXG4gICAgICAgIGJyZWFrO1xyXG4gICAgfVxyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog5Yid5aeL5YyW5a6e5pe26aKE6KeI5pKt5pS+5ZmoXHJcbiAgICovXHJcbiAgaW5pdFJlYWxQbGF5ZXIob3B0KSB7XHJcbiAgICB0aGlzLnNlcnZlcklwID0gb3B0LnNlcnZlcklwO1xyXG4gICAgaWYgKCF0aGlzLnNlcnZlcklwKSB7XHJcbiAgICAgIGNvbnNvbGUubG9nKFwic2VydmVySXA6XCIsIHNlcnZlcklwKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgdGhpcy5wbGF5TnVtID0gb3B0Lm51bTtcclxuICAgIHRoaXMudHlwZSA9IFwicmVhbFwiO1xyXG4gICAgdGhpcy5yZWFsUGxheWVyID0gbmV3IFdTUGxheWVyQ29uc3RydWN0b3Ioe1xyXG4gICAgICBlbDogdGhpcy5lbCxcclxuICAgICAgdHlwZTogXCJyZWFsXCIsXHJcbiAgICAgIHNlcnZlcklwOiB0aGlzLnNlcnZlcklwLFxyXG4gICAgICBjb25maWc6IHtcclxuICAgICAgICBudW06IG9wdC5udW0sXHJcbiAgICAgICAgbWF4TnVtOiBvcHQubWF4TnVtLFxyXG4gICAgICAgIHNob3dDb250cm9sOiBvcHQuc2hvd0NvbnRyb2wsIC8vIOm7mOiupOaYr+WQpuaYvuekuuW3peWFt+agj1xyXG4gICAgICB9LFxyXG4gICAgICByZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllcjpcclxuICAgICAgICBvcHQucmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIgfHxcclxuICAgICAgICB0aGlzLl9fcmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIuYmluZCh0aGlzKSxcclxuICAgIH0pO1xyXG4gICAgdGhpcy5wbGF5ZXIgPSB0aGlzLnJlYWxQbGF5ZXI7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDliJ3lp4vljJblvZXlg4/lm57mlL7mkq3mlL7lmahcclxuICAgKi9cclxuICBpbml0UmVjb3JkUGxheWVyKG9wdCkge1xyXG4gICAgdGhpcy5zZXJ2ZXJJcCA9IG9wdC5zZXJ2ZXJJcDtcclxuICAgIGlmICghdGhpcy5zZXJ2ZXJJcCkge1xyXG4gICAgICByZXR1cm47XHJcbiAgICB9XHJcbiAgICB0aGlzLnBsYXlOdW0gPSBvcHQubnVtO1xyXG4gICAgdGhpcy50eXBlID0gXCJyZWNvcmRcIjtcclxuICAgIHRoaXMucmVjb3JkUGxheWVyID0gbmV3IFdTUGxheWVyQ29uc3RydWN0b3Ioe1xyXG4gICAgICBlbDogdGhpcy5lbCxcclxuICAgICAgdHlwZTogXCJyZWNvcmRcIixcclxuICAgICAgc2VydmVySXA6IHRoaXMuc2VydmVySXAsXHJcbiAgICAgIGNvbmZpZzoge1xyXG4gICAgICAgIG51bTogb3B0Lm51bSxcclxuICAgICAgICBtYXhOdW06IG9wdC5tYXhOdW0sXHJcbiAgICAgICAgc2hvd0NvbnRyb2w6IG9wdC5zaG93Q29udHJvbCwgLy8g6buY6K6k5piv5ZCm5pi+56S65bel5YW35qCPXHJcbiAgICAgIH0sXHJcbiAgICAgIHJlY2VpdmVNZXNzYWdlRnJvbVdTUGxheWVyOlxyXG4gICAgICAgIG9wdC5yZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllciB8fFxyXG4gICAgICAgIHRoaXMuX19yZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllci5iaW5kKHRoaXMpLFxyXG4gICAgfSk7XHJcbiAgICB0aGlzLnBsYXllciA9IHRoaXMucmVjb3JkUGxheWVyO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog5pKt5pS+5a6e5pe26aKE6KeI6KeG6aKRXHJcbiAgICogQHBhcmFtIG9wdC5jaGFubmVsTGlzdDoge0FycmF5PE9iamVjdD59IOW/heWhq++8jOmAmumBk+WIl+ihqFxyXG4gICAqIEBwYXJhbSBvcHQuc3RyZWFtVHlwZToge051bWJlcnxTdHJpbmd9IOmAieWhq++8jOeggea1geexu+Wei++8jOS4jeWhq+m7mOiupOaSreaUvui+heeggea1gTHvvIzoi6XkuI3lrZjlnKjovoXnoIHmtYEx77yM5YiZ6Ieq5Yqo5YiH5o2i5Yiw5Li756CB5rWBICAxLeS4u+eggea1gSAyLei+heeggea1gTEgMy3ovoXnoIHmtYEyXHJcbiAgICogQHBhcmFtIG9wdC53aW5kb3dJbmRleDoge051bWJlcn0g6YCJ5aGr77yM5oyH5a6a5LuO5ZOq5Liq56qX5Y+j5byA5aeL5pKt5pS+44CC5LiN5aGr6buY6K6k5LuO6YCJ5Lit55qE56qX5Y+j5byA5aeL5pKt5pS+XHJcbiAgICovXHJcbiAgLy8gY2hhbm5lbExpc3Q6IFt7XHJcbiAgLy8gICAgIGlkOiBjaGFubmVsQ29kZSwgLy8ge1N0cmluZ30g6YCa6YGT57yW56CBIC0tIOeUqOS6jumihOiniO+8jOW/heWhq1xyXG4gIC8vICAgICBpc09ubGluZTogdHJ1ZSwgLy8ge0Jvb2xlYW59IOaYr+WQpuWcqOe6v++8jOmdnuWcqOe6v+aXoOazleaSreaUviAtLSDnlKjkuo7pooTop4jvvIzlv4XloatcclxuICAvLyAgICAgZGV2aWNlQ29kZTogZGV2aWNlQ29kZSwgLy8ge1N0cmluZ30g6K6+5aSH57yW56CBIC0tIOeUqOS6juWvueiusu+8jOWvueiusuW/heWhq++8jOaXoOWvueiusuWKn+iDveWPr+S4jeWhq1xyXG4gIC8vICAgICBkZXZpY2VUeXBlOiBkZXZpY2VUeXBlLCAvLyB7U3RyaW5nfSDorr7lpIfnsbvlnosgLS0g55So5LqO5a+56K6y77yM5a+56K6y5b+F5aGr77yM5peg5a+56K6y5Yqf6IO95Y+v5LiN5aGrXHJcbiAgLy8gICAgIGNoYW5uZWxTZXE6IGNoYW5uZWxTZXEsIC8vIHtTdHJpbmd8TnVtYmVyfSDpgJrpgZPluo/lj7cgLS0g55So5LqO5a+56K6y77yM5a+56K6y5b+F5aGr77yM5peg5a+56K6y5Yqf6IO95Y+v5LiN5aGrXHJcbiAgLy8gICAgIGNhbWVyYVR5cGU6IGNhbWVyYVR5cGUsIC8vIHtTdHJpbmd8TnVtYmVyfSDmkYTlg4/lpLTnsbvlnosgLS0g55So5LqO5LqR5Y+w77yM5LqR5Y+w5b+F5aGr77yM5peg5LqR5Y+w5Yqf6IO95Y+v5LiN5aGrXHJcbiAgLy8gICAgIGNhcGFiaWxpdHk6IGNhcGFiaWxpdHksIC8vIHtTdHJpbmd9IOiDveWKm+mbhiAtLSDnlKjkuo7kupHlj7DvvIzpgInloatcclxuICAvLyB9XVxyXG4gIHBsYXlSZWFsVmlkZW8ob3B0KSB7XHJcbiAgICB0aGlzLnJlYWxQbGF5ZXIgJiYgdGhpcy5yZWFsUGxheWVyLnBsYXlSZWFsKG9wdCk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDmkq3mlL7lvZXlg4/lm57mlL5cclxuICAgKiBAcGFyYW0gb3B0LmNoYW5uZWxMaXN0IHtBcnJheTxPYmplY3Q+fSDpgJrpgZPpm4blkIgg5b+F5aGrXHJcbiAgICogQHBhcmFtIG9wdC5zdGFydFRpbWUge1N0cmluZ3xOdW1iZXJ9IOW8gOWni+aXtumXtCAg5b+F6YCJICB0aW1lc3RhbXDliLDnp5JcclxuICAgKiBAcGFyYW0gb3B0LmVuZFRpbWUge1N0cmluZ3xOdW1iZXJ9IOe7k+adn+aXtumXtCAg5b+F6YCJICB0aW1lc3RhbXDliLDnp5JcclxuICAgKiBAcGFyYW0gb3B0LnJlY29yZFNvdXJjZSB7U3RyaW5nfE51bWJlcn0g5b2V5YOP5p2l5rqQICDlv4XpgIkgMuihqOekuuiuvuWkh+W9leWDjyAgM+ihqOekuuS4reW/g+W9leWDj1xyXG4gICAqIEBwYXJhbSBvcHQuc3RyZWFtVHlwZSB7U3RyaW5nfE51bWJlcn0g56CB5rWB57G75Z6LIOWPr+mAiVxyXG4gICAqIEBwYXJhbSBvcHQucmVjb3JkVHlwZSB7U3RyaW5nfE51bWJlcn0g5b2V5YOP57G75Z6LIOWPr+mAiVxyXG4gICAqL1xyXG4gIC8vIGNoYW5uZWxMaXN0OiBbe1xyXG4gIC8vICAgICBpZDogY2hhbm5lbENvZGUsIC8vIHtTdHJpbmd9IOmAmumBk+e8lueggSAtLSDnlKjkuo7lm57mlL7vvIzlv4XloatcclxuICAvLyB9XVxyXG4gIHBsYXlSZWNvcmRWaWRlbyhvcHQpIHtcclxuICAgIHRoaXMucmVjb3JkUGxheWVyICYmIHRoaXMucmVjb3JkUGxheWVyLnBsYXlSZWNvcmQob3B0KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOW9leWDj+aaguWBnFxyXG4gICAqIOWPquacieato+WcqOaSreaUvueahOW9leWDj+iwg+eUqOaJjeacieaViFxyXG4gICAqL1xyXG4gIHBhdXNlKCkge1xyXG4gICAgdGhpcy5yZWNvcmRQbGF5ZXIgJiYgdGhpcy5yZWNvcmRQbGF5ZXIucGF1c2UoKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOW9leWDj+aaguWBnOWQjuaSreaUvlxyXG4gICAqIOWPquacieaaguWBnOWQjueahOW9leWDj+iwg+eUqOaJjeacieaViFxyXG4gICAqL1xyXG4gIHBsYXkoKSB7XHJcbiAgICB0aGlzLnJlY29yZFBsYXllciAmJiB0aGlzLnJlY29yZFBsYXllci5wbGF5KCk7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDlgI3pgJ/mkq3mlL5cclxuICAgKiBAcGFyYW0ge251bWJlcn0gc3BlZWQg6YCf546HIDAuMTI1IDAuMjUgMC41IDEgMiA0IDgg5YWxN+enjemAn+eOh1xyXG4gICAqL1xyXG4gIHBsYXlTcGVlZChzcGVlZCkge1xyXG4gICAgdGhpcy5yZWNvcmRQbGF5ZXIgJiYgdGhpcy5yZWNvcmRQbGF5ZXIucGxheVNwZWVkKHNwZWVkKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOWFs+mXreaSreaUvuWZqFxyXG4gICAqIEBwYXJhbSB7bnVtYmVyfSBpbmRleCDlj6/pgInvvIzlhbPpl63mjIflrprntKLlvJXnmoTnqpflj6PnmoTmkq3mlL7lmajvvIzkuI3kvKDliJnooajnpLrlhbPpl63miYDmnInmkq3mlL7lmahcclxuICAgKi9cclxuICBjbG9zZShpbmRleCkge1xyXG4gICAgdGhpcy5wbGF5ZXIgJiYgdGhpcy5wbGF5ZXIuY2xvc2UoaW5kZXgpO1xyXG4gIH1cclxuXHJcbiAgLyoqXHJcbiAgICog6K6+572u5YWo5bGPXHJcbiAgICovXHJcbiAgc2V0RnVsbFNjcmVlbigpIHtcclxuICAgIHRoaXMucGxheWVyLnNldEZ1bGxTY3JlZW4oKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOiuvue9rueql+WPo+iHqumAguW6lOi/mOaYr+aLieS8uFxyXG4gICAqIEBwYXJhbSB7c3RyaW5nfSBwbGF5ZXJBZGFwdGVyIHNlbGZBZGFwdGlvbiDoh6rpgILlupQgfCBzdHJldGNoaW5nIOaLieS8uFxyXG4gICAqL1xyXG4gIHNldFBsYXllckFkYXB0ZXIocGxheWVyQWRhcHRlcikge1xyXG4gICAgdGhpcy5wbGF5ZXIuc2V0UGxheWVyQWRhcHRlcihwbGF5ZXJBZGFwdGVyKTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqIOaOp+WItuinhumikeaSreaUvuWZqOaYvuekuueahOi3r+aVsDogMSA0IDkgMTYgMjXvvIzkuI3kvJrotoXov4fmnIDlpKfmmL7npLrot6/mlbBcclxuICAgKiBAcGFyYW0ge251bWJlcn0gbnVtYmVyXHJcbiAgICovXHJcbiAgc2V0UGxheWVyTnVtKG51bWJlcikge1xyXG4gICAgdGhpcy5wbGF5ZXIuc2V0UGxheWVyTnVtKG51bWJlcik7XHJcbiAgfVxyXG5cclxuICAvKipcclxuICAgKiDorr7nva7pgInkuK3nmoTmkq3mlL7lmajnmoTntKLlvJVcclxuICAgKiBAcGFyYW0ge251bWJlcn0gaW5kZXhcclxuICAgKi9cclxuICBzZXRTZWxlY3RJbmRleChpbmRleCkge1xyXG4gICAgdGhpcy5wbGF5ZXIuc2V0U2VsZWN0SW5kZXgoaW5kZXgpO1xyXG4gIH1cclxuICBjYXB0dXJlUGljKCkge1xyXG4gICAgdGhpcy5wbGF5ZXIuY2FwdHVyZVBpYygpO1xyXG4gIH1cclxuICAvKipcclxuICAgKiDlvZXlg4/ot7Povazmkq3mlL5cclxuICAgKiBAcGFyYW0ge3N0cmluZ30gdGltZSBISDptbTpzc+agvOW8j1xyXG4gICAqL1xyXG4gIGp1bXBQbGF5QnlUaW1lKHRpbWUpIHtcclxuICAgIHRoaXMucGxheWVyLmp1bXBQbGF5QnlUaW1lKHRpbWUpO1xyXG4gIH1cclxuXHJcbiAgLy8gLS0tLS0tLS0tLS0tLS0tLS0g5pKt5pS+5Zmo5LqL5Lu2IC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gIF9fcmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIobWV0aG9kLCBkYXRhKSB7XHJcbiAgICBzd2l0Y2ggKG1ldGhvZCkge1xyXG4gICAgICAvLyAtLS0tLS0tLS0tLS0tIOWFrOWFseS6i+S7tiAtLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgY2FzZSBcInNlbGVjdFdpbmRvd0NoYW5nZWRcIjogLy8g6YCJ5Lit55qE56qX5Y+j5Y+R55Sf5pS55Y+YXHJcbiAgICAgICAgdGhpcy5jdXJyZW50Q2hhbm5lbElkID0gZGF0YS5jaGFubmVsSWQ7XHJcbiAgICAgICAgdGhpcy5wbGF5SW5kZXggPSBkYXRhLnBsYXlJbmRleDtcclxuICAgICAgICBicmVhaztcclxuICAgICAgY2FzZSBcIndpbmRvd051bUNoYW5nZWRcIjogLy8g5pKt5pS+5Zmo5pi+56S655qE6Lev5pWw5Y+R55Sf5pS55Y+YXHJcbiAgICAgICAgdGhpcy5wbGF5TnVtID0gZGF0YTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgY2FzZSBcImNsb3NlVmlkZW9cIjogLy8g6KeG6aKR5YWz6ZetXHJcbiAgICAgICAgLy8g54K55Ye75YWz6Zet5oyJ6ZKu5byV5Y+R55qE6KeG6aKR5YWz6Zet6L+b6KGM5o+Q56S6XHJcbiAgICAgICAgLy8g5YiH5o2i6KeG6aKR5byV5Y+R55qE6KeG6aKR5YWz6Zet5LiN6L+b6KGM5o+Q56S6XHJcbiAgICAgICAgaWYgKCFkYXRhLmNoYW5nZVZpZGVvRmxhZykge1xyXG4gICAgICAgICAgY29uc29sZS5sb2coYOeql+WPoyR7ZGF0YS5zZWxlY3RJbmRleH3nmoTop4bpopHlt7LlhbPpl61gKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGNhc2UgXCJzdGF0dXNDaGFuZ2VkXCI6IC8vIOinhumikeeKtuaAgeWPkeeUn+aUueWPmFxyXG4gICAgICAgIGJyZWFrO1xyXG4gICAgICBjYXNlIFwiZXJyb3JJbmZvXCI6IC8vIOmUmeivr+S/oeaBr+aPkOekulxyXG4gICAgICAgIC8vIGRhdGEgPSB7XHJcbiAgICAgICAgLy8gICAgIGVycm9yQ29kZTogeHh4LFxyXG4gICAgICAgIC8vICAgICBlcnJvck1zZzogXCJcIixcclxuICAgICAgICAvLyAgICAgY2hhbm5lbExpc3Q6IFtdLFxyXG4gICAgICAgIC8vIH1cclxuICAgICAgICBicmVhaztcclxuICAgIH1cclxuICB9XHJcbn1cclxuXHJcbmV4cG9ydCBkZWZhdWx0IFBsYXllck1hbmFnZXI7XHJcbiIsIi8vIOS9v+eUqOS6p+WTgeWMheW8leWFpVxyXG5pbXBvcnQgUGxheWVyTWFuYWdlciBmcm9tIFwiLi9QbGF5ZXJNYW5hZ2VyXCI7XHJcbndpbmRvdy5QTEFZRVJfQk9YID0ge1xyXG4gIF9vcHRpb25zOiB7XHJcbiAgICBzZXJ2ZXJJcDogXCJcIiwgLy/lv4XkvKBcclxuICAgIGF1dG9QYXVzZTogZmFsc2UsIC8v5Lyg5YWlcnRzcOWQjuaYr+WQpuiHquWKqOaSreaUvlxyXG4gICAgcnRzcFVSTDogXCJcIiwgLy9ydHNwVVJM5b+F5LygXHJcbiAgICBjaGFubmVsSWQ6IFwiXCIsIC8v55So5L2c5ZSv5LiA5qCH6K+GLOW/heS8oFxyXG4gICAgLy8gbGFuZzogXCJlblwiLCAvL+WbvemZheWMluaPkOekuu+8jOWPr+mAiemhue+8mnpoLGVuXHJcbiAgICBwbGF5ZXJBZGFwdGVyOiBcInNlbGZBZGFwdGlvblwiLCAvL3N0cmV0Y2hpbmdcclxuICAgIHBsYXllclR5cGU6IFwicmVhbFwiLCAvL1xyXG4gICAgc2VydmVyUG9ydDogXCJcIixcclxuICB9LFxyXG4gIHBsYXllck1hbmFnZXI6IHVuZGVmaW5lZCxcclxuICBfZG9tYWluOiB1bmRlZmluZWQsXHJcbiAgc2V0T3B0aW9ucyhvcHRpb25zKSB7XHJcbiAgICB0aGlzLl9vcHRpb25zID0gT2JqZWN0LmFzc2lnbih0aGlzLl9vcHRpb25zLCBvcHRpb25zKTtcclxuICAgIHJldHVybiB0aGlzLl9vcHRpb25zO1xyXG4gIH0sXHJcbiAgcGxheVJlYWwocnRzcFVSTCkge1xyXG4gICAgaWYgKFxyXG4gICAgICAhKFxyXG4gICAgICAgIHRoaXMuX29wdGlvbnMucGxheWVyQWRhcHRlciA9PT0gXCJzZWxmQWRhcHRpb25cIiB8fFxyXG4gICAgICAgIHRoaXMuX29wdGlvbnMucGxheWVyQWRhcHRlciA9PT0gXCJzdHJldGNoaW5nXCJcclxuICAgICAgKVxyXG4gICAgKSB7XHJcbiAgICAgIGNvbnNvbGUubG9nKFwi5bmz6ZO65YC8X29wdGlvbnMucGxheWVyQWRhcHRlcuS4jeato+ehru+8jOivt+i+k+WFpeato+ehrueahOaSreaUvuW5s+mTuuWAvFwiKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG5cclxuICAgIHRoaXMucGxheWVyTWFuYWdlci5wbGF5UmVhbFZpZGVvKHtcclxuICAgICAgcnRzcFVSTCxcclxuICAgICAgY2hhbm5lbElkOiB0aGlzLl9vcHRpb25zLmNoYW5uZWxJZCxcclxuICAgICAgcGxheWVyQWRhcHRlcjogdGhpcy5fb3B0aW9ucy5wbGF5ZXJBZGFwdGVyLFxyXG4gICAgICBzZXJ2ZXJJcDogdGhpcy5fb3B0aW9ucy5zZXJ2ZXJJcCxcclxuICAgICAgc2VsZWN0SW5kZXg6IDAsXHJcbiAgICAgIGF1dG9QYXVzZTogdGhpcy5fb3B0aW9ucy5hdXRvUGF1c2UsXHJcbiAgICB9KTtcclxuICB9LFxyXG4gIHBsYXlSZWNvcmQocnRzcFVSTCkge1xyXG4gICAgaWYgKFxyXG4gICAgICAhKFxyXG4gICAgICAgIHRoaXMuX29wdGlvbnMucGxheWVyQWRhcHRlciA9PT0gXCJzZWxmQWRhcHRpb25cIiB8fFxyXG4gICAgICAgIHRoaXMuX29wdGlvbnMucGxheWVyQWRhcHRlciA9PT0gXCJzdHJldGNoaW5nXCJcclxuICAgICAgKVxyXG4gICAgKSB7XHJcbiAgICAgIGNvbnNvbGUubG9nKFwi5bmz6ZO65YC8X29wdGlvbnMucGxheWVyQWRhcHRlcuS4jeato+ehru+8jOivt+i+k+WFpeato+ehrueahOaSreaUvuW5s+mTuuWAvFwiKTtcclxuICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgdGhpcy5wbGF5ZXJNYW5hZ2VyLnBsYXlSZWNvcmRWaWRlbyh7XHJcbiAgICAgIHJ0c3BVUkwsXHJcbiAgICAgIGNoYW5uZWxJZDogdGhpcy5fb3B0aW9ucy5jaGFubmVsSWQsXHJcbiAgICAgIHBsYXllckFkYXB0ZXI6IHRoaXMuX29wdGlvbnMucGxheWVyQWRhcHRlcixcclxuICAgICAgc2VydmVySXA6IHRoaXMuX29wdGlvbnMuc2VydmVySXAsXHJcbiAgICAgIHNlbGVjdEluZGV4OiAwLFxyXG4gICAgICBhdXRvUGF1c2U6IHRoaXMuX29wdGlvbnMuYXV0b1BhdXNlLFxyXG4gICAgfSk7XHJcbiAgfSxcclxuICBwYXVzZSgpIHtcclxuICAgIHRoaXMucGxheWVyTWFuYWdlci5wYXVzZSgpO1xyXG4gIH0sXHJcbiAgY29udGludWVQbGF5KCkge1xyXG4gICAgdGhpcy5wbGF5ZXJNYW5hZ2VyLnBsYXkoKTtcclxuICB9LFxyXG4gIC8vIOmAn+eOhyAwLjEyNSAwLjI1IDAuNSAxIDIgNCA4IOWFsTfnp43pgJ/njodcclxuICBwbGF5U3BlZWQoc3BlZWQpIHtcclxuICAgIHRoaXMucGxheWVyTWFuYWdlci5wbGF5U3BlZWQoc3BlZWQpO1xyXG4gIH0sXHJcbiAgY2xvc2UoKSB7XHJcbiAgICB0aGlzLnBsYXllck1hbmFnZXIuY2xvc2UoMCk7XHJcbiAgfSxcclxuICBzZXRGdWxsU2NyZWVuKCkge1xyXG4gICAgdGhpcy5wbGF5ZXJNYW5hZ2VyLnNldEZ1bGxTY3JlZW4oKTtcclxuICB9LFxyXG4gIGNhcHR1cmVQaWMoKSB7XHJcbiAgICB0aGlzLnBsYXllck1hbmFnZXIuY2FwdHVyZVBpYygpO1xyXG4gIH0sXHJcbiAgcmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIobWV0aG9kLCBkYXRhKSB7XHJcbiAgICBzd2l0Y2ggKG1ldGhvZCkge1xyXG4gICAgICBjYXNlIFwicmVjb3JkVGltZUNoYW5nZVwiOlxyXG4gICAgICAgIGlmIChkYXRhKSB7XHJcbiAgICAgICAgICBQTEFZRVJfQk9YLnNlbmRDdXJyZW50VGltZShkYXRhKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICAgIGNhc2UgXCJyZWNvcmRQbGF5RW5kXCI6XHJcbiAgICAgICAgUExBWUVSX0JPWC5zZW5kcmVjb3JkUGxheUVuZChkYXRhKTtcclxuICAgICAgICBicmVhaztcclxuICAgICAgY2FzZSBcImVycm9ySW5mb1wiOlxyXG4gICAgICAgIFBMQVlFUl9CT1guZ2V0RXJyb3IoZGF0YSk7XHJcbiAgICAgICAgYnJlYWs7XHJcbiAgICB9XHJcbiAgfSxcclxuICBzZW5kQ3VycmVudFRpbWUodGltZSkge1xyXG4gICAgd2luZG93LnBsYXlCb3hfdGltZSA9IHRpbWU7XHJcbiAgICB3aW5kb3cucGFyZW50LnBvc3RNZXNzYWdlKHsgZnVuTmFtZTogXCJzZW5kQ3VycmVudFRpbWVcIiwgZGF0YTogdGltZSB9LCBcIipcIik7XHJcbiAgfSxcclxuICBzZW5kcmVjb3JkUGxheUVuZChjaGFubmVsSWQpIHtcclxuICAgIHdpbmRvdy5wYXJlbnQucG9zdE1lc3NhZ2UoXHJcbiAgICAgIHsgZnVuTmFtZTogXCJzZW5kcmVjb3JkUGxheUVuZFwiLCBkYXRhOiBgJHtjaGFubmVsSWR9RW5kUGxheWAgfSxcclxuICAgICAgXCIqXCJcclxuICAgICk7XHJcbiAgfSxcclxuICBnZXRFcnJvcihlKSB7XHJcbiAgICBjb25zb2xlLmxvZyhcImdldEVycm9yXCIsIGUpO1xyXG4gICAgd2luZG93LnBhcmVudC5wb3N0TWVzc2FnZSh7IGZ1bk5hbWU6IFwiZ2V0RXJyb3JcIiwgZGF0YTogZSB9LCBcIipcIik7XHJcbiAgfSxcclxuICBpbml0KCkge1xyXG4gICAgdGhpcy5nZXRVcmxQYXJhbXMoKTtcclxuICAgIGlmICghdGhpcy5fb3B0aW9ucy5jaGFubmVsSWQpIHtcclxuICAgICAgY29uc29sZS5sb2coXCLor7fkvKDlhaVjaGFubmVsSWRcIik7XHJcbiAgICAgIHJldHVybjtcclxuICAgIH1cclxuICAgIGlmICh0aGlzLnBsYXllck1hbmFnZXI/LnBsYXllcikgcmV0dXJuO1xyXG4gICAgaWYgKHRoaXMuX29wdGlvbnMucGxheWVyVHlwZSA9PT0gXCJyZWFsXCIpIHtcclxuICAgICAgdGhpcy5wbGF5ZXJNYW5hZ2VyID0gbmV3IFBsYXllck1hbmFnZXIoe1xyXG4gICAgICAgIGVsOiBcIndzLXJlYWwtcGxheWVyXCIsXHJcbiAgICAgICAgdHlwZTogXCJyZWFsXCIsIC8vIHJlYWwgfCByZWNvcmRcclxuICAgICAgICBzZXJ2ZXJJcDogdGhpcy5fb3B0aW9ucy5zZXJ2ZXJJcCwgLy/lv4XkvKBcclxuICAgICAgICBtYXhOdW06IDEsXHJcbiAgICAgICAgbnVtOiAxLFxyXG4gICAgICAgIHNob3dDb250cm9sOiBmYWxzZSwgLy8g6buY6K6k5piv5ZCm5pi+56S65bel5YW35qCPXHJcbiAgICAgICAgcmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXI6IHRoaXMucmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIsXHJcbiAgICAgIH0pO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgdGhpcy5wbGF5ZXJNYW5hZ2VyID0gbmV3IFBsYXllck1hbmFnZXIoe1xyXG4gICAgICAgIGVsOiBcIndzLXJlYWwtcGxheWVyXCIsXHJcbiAgICAgICAgdHlwZTogXCJyZWNvcmRcIiwgLy8gcmVhbCB8IHJlY29yZFxyXG4gICAgICAgIHNlcnZlcklwOiB0aGlzLl9vcHRpb25zLnNlcnZlcklwLCAvL+W/heS8oFxyXG4gICAgICAgIG1heE51bTogMSxcclxuICAgICAgICBudW06IDEsXHJcbiAgICAgICAgc2hvd0NvbnRyb2w6IGZhbHNlLCAvLyDpu5jorqTmmK/lkKbmmL7npLrlt6XlhbfmoI9cclxuICAgICAgICByZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllcjogdGhpcy5yZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllcixcclxuICAgICAgfSk7XHJcbiAgICB9XHJcbiAgICAvLyDms6jlhozosIPnlKjmnI3liqHln59cclxuICAgIHRoaXMuX2RvbWFpbiA9IGBodHRwOi8vJHt0aGlzLl9vcHRpb25zLnNlcnZlcklwfSR7XHJcbiAgICAgIHRoaXMuX29wdGlvbnMuc2VydmVyUG9ydCA/IGA6JHt0aGlzLl9vcHRpb25zLnNlcnZlclBvcnR9YCA6IFwiXCJcclxuICAgIH1gO1xyXG4gICAgY29uc29sZS5sb2coXCItLS0tLS0tLS0tLS0gdGhpcy5fZG9tYWluIC0tLS0tLS0tLS0tLS0tLS1cIiwgdGhpcy5fZG9tYWluKTtcclxuICAgIGNvbnN0IF9zbGVmID0gdGhpcztcclxuICAgIC8vIOWIneWni+WMlnBvc3RNZXNzYWdlO+WklumDqOeItuaOieWGhemDqOaSreaUvuahhlxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXCJtZXNzYWdlXCIsIGZ1bmN0aW9uIChldmVudFJlc3VsdCkge1xyXG4gICAgICBjb25zb2xlLmxvZyhcclxuICAgICAgICBcIi0tLS0tLS0tLS0tLWNoaWxkIExpc3RlbmVyLS0tLS0tLS0tLS0tLS0tLVwiLFxyXG4gICAgICAgIGV2ZW50UmVzdWx0LmRhdGFcclxuICAgICAgKTtcclxuICAgICAgbGV0IHJlc3VsdCA9IGV2ZW50UmVzdWx0LmRhdGE7XHJcbiAgICAgIGlmICghKHJlc3VsdCAmJiByZXN1bHQuZnVuTmFtZSkpIHtcclxuICAgICAgICBjb25zb2xlLmxvZyhcIuWPguaVsOW8guW4uCwgZnVuTmFtZeacquS8oOWAvFwiKTtcclxuICAgICAgICByZXR1cm47XHJcbiAgICAgIH1cclxuICAgICAgaWYgKFxyXG4gICAgICAgICEoX3NsZWZbcmVzdWx0LmZ1bk5hbWVdICYmIHR5cGVvZiBfc2xlZltyZXN1bHQuZnVuTmFtZV0gPT09IFwiZnVuY3Rpb25cIilcclxuICAgICAgKSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCLlj4LmlbDlvILluLgsIOiwg+eUqOaWueazleS4jeWtmOWcqFwiLCByZXN1bHQuZnVuTmFtZSk7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgICB9XHJcbiAgICAgIF9zbGVmW3Jlc3VsdC5mdW5OYW1lXShyZXN1bHQucGFyYW1zKTtcclxuICAgIH0pO1xyXG4gIH0sXHJcblxyXG4gIGdldFVybFBhcmFtcygpIHtcclxuICAgIGNvbnN0IGFycmF5ID0gd2luZG93LmxvY2F0aW9uLnNlYXJjaC5zcGxpdChcIj9cIilbMV0/LnNwbGl0KFwiJlwiKTtcclxuICAgIGlmICghYXJyYXkpIHtcclxuICAgICAgY29uc29sZS5sb2coXCJ1cmzlj4LmlbDplJnor69cIik7XHJcbiAgICB9XHJcbiAgICBhcnJheS5mb3JFYWNoKChpdGVtLCBpbmRleCkgPT4ge1xyXG4gICAgICBjb25zdCBuZXdBcnIgPSBpdGVtLnNwbGl0KFwiPVwiKTtcclxuICAgICAgaWYgKG5ld0FyciAmJiBuZXdBcnJbMF0gJiYgbmV3QXJyWzFdKSB7XHJcbiAgICAgICAgdGhpcy5fb3B0aW9uc1tuZXdBcnJbMF1dID0gbmV3QXJyWzFdO1xyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9LFxyXG59O1xyXG5cclxud2luZG93LlBMQVlFUl9CT1guaW5pdCgpO1xyXG4iLCIvLyBUaGUgbW9kdWxlIGNhY2hlXG52YXIgX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fID0ge307XG5cbi8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG5mdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuXHR2YXIgY2FjaGVkTW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXTtcblx0aWYgKGNhY2hlZE1vZHVsZSAhPT0gdW5kZWZpbmVkKSB7XG5cdFx0aWYgKGNhY2hlZE1vZHVsZS5lcnJvciAhPT0gdW5kZWZpbmVkKSB0aHJvdyBjYWNoZWRNb2R1bGUuZXJyb3I7XG5cdFx0cmV0dXJuIGNhY2hlZE1vZHVsZS5leHBvcnRzO1xuXHR9XG5cdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG5cdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX19bbW9kdWxlSWRdID0ge1xuXHRcdC8vIG5vIG1vZHVsZS5pZCBuZWVkZWRcblx0XHQvLyBubyBtb2R1bGUubG9hZGVkIG5lZWRlZFxuXHRcdGV4cG9ydHM6IHt9XG5cdH07XG5cblx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG5cdHRyeSB7XG5cdFx0dmFyIGV4ZWNPcHRpb25zID0geyBpZDogbW9kdWxlSWQsIG1vZHVsZTogbW9kdWxlLCBmYWN0b3J5OiBfX3dlYnBhY2tfbW9kdWxlc19fW21vZHVsZUlkXSwgcmVxdWlyZTogX193ZWJwYWNrX3JlcXVpcmVfXyB9O1xuXHRcdF9fd2VicGFja19yZXF1aXJlX18uaS5mb3JFYWNoKGZ1bmN0aW9uKGhhbmRsZXIpIHsgaGFuZGxlcihleGVjT3B0aW9ucyk7IH0pO1xuXHRcdG1vZHVsZSA9IGV4ZWNPcHRpb25zLm1vZHVsZTtcblx0XHRleGVjT3B0aW9ucy5mYWN0b3J5LmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIGV4ZWNPcHRpb25zLnJlcXVpcmUpO1xuXHR9IGNhdGNoKGUpIHtcblx0XHRtb2R1bGUuZXJyb3IgPSBlO1xuXHRcdHRocm93IGU7XG5cdH1cblxuXHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuXHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG59XG5cbi8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG5fX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBfX3dlYnBhY2tfbW9kdWxlc19fO1xuXG4vLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuX193ZWJwYWNrX3JlcXVpcmVfXy5jID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fO1xuXG4vLyBleHBvc2UgdGhlIG1vZHVsZSBleGVjdXRpb24gaW50ZXJjZXB0b3Jcbl9fd2VicGFja19yZXF1aXJlX18uaSA9IFtdO1xuXG4iLCIvLyBUaGlzIGZ1bmN0aW9uIGFsbG93IHRvIHJlZmVyZW5jZSBhbGwgY2h1bmtzXG5fX3dlYnBhY2tfcmVxdWlyZV9fLmh1ID0gKGNodW5rSWQpID0+IHtcblx0Ly8gcmV0dXJuIHVybCBmb3IgZmlsZW5hbWVzIGJhc2VkIG9uIHRlbXBsYXRlXG5cdHJldHVybiBcIlwiICsgY2h1bmtJZCArIFwiLlwiICsgX193ZWJwYWNrX3JlcXVpcmVfXy5oKCkgKyBcIi5ob3QtdXBkYXRlLmpzXCI7XG59OyIsIl9fd2VicGFja19yZXF1aXJlX18uaG1yRiA9ICgpID0+IChcIm1haW4uXCIgKyBfX3dlYnBhY2tfcmVxdWlyZV9fLmgoKSArIFwiLmhvdC11cGRhdGUuanNvblwiKTsiLCJfX3dlYnBhY2tfcmVxdWlyZV9fLmggPSAoKSA9PiAoXCI2YWU5MWFiYzdkOWJmN2U2ZmJjNFwiKSIsIl9fd2VicGFja19yZXF1aXJlX18uZyA9IChmdW5jdGlvbigpIHtcblx0aWYgKHR5cGVvZiBnbG9iYWxUaGlzID09PSAnb2JqZWN0JykgcmV0dXJuIGdsb2JhbFRoaXM7XG5cdHRyeSB7XG5cdFx0cmV0dXJuIHRoaXMgfHwgbmV3IEZ1bmN0aW9uKCdyZXR1cm4gdGhpcycpKCk7XG5cdH0gY2F0Y2ggKGUpIHtcblx0XHRpZiAodHlwZW9mIHdpbmRvdyA9PT0gJ29iamVjdCcpIHJldHVybiB3aW5kb3c7XG5cdH1cbn0pKCk7IiwiX193ZWJwYWNrX3JlcXVpcmVfXy5vID0gKG9iaiwgcHJvcCkgPT4gKE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmosIHByb3ApKSIsInZhciBjdXJyZW50TW9kdWxlRGF0YSA9IHt9O1xudmFyIGluc3RhbGxlZE1vZHVsZXMgPSBfX3dlYnBhY2tfcmVxdWlyZV9fLmM7XG5cbi8vIG1vZHVsZSBhbmQgcmVxdWlyZSBjcmVhdGlvblxudmFyIGN1cnJlbnRDaGlsZE1vZHVsZTtcbnZhciBjdXJyZW50UGFyZW50cyA9IFtdO1xuXG4vLyBzdGF0dXNcbnZhciByZWdpc3RlcmVkU3RhdHVzSGFuZGxlcnMgPSBbXTtcbnZhciBjdXJyZW50U3RhdHVzID0gXCJpZGxlXCI7XG5cbi8vIHdoaWxlIGRvd25sb2FkaW5nXG52YXIgYmxvY2tpbmdQcm9taXNlcztcblxuLy8gVGhlIHVwZGF0ZSBpbmZvXG52YXIgY3VycmVudFVwZGF0ZUFwcGx5SGFuZGxlcnM7XG52YXIgcXVldWVkSW52YWxpZGF0ZWRNb2R1bGVzO1xuXG4vLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmUgbm8tdW51c2VkLXZhcnNcbl9fd2VicGFja19yZXF1aXJlX18uaG1yRCA9IGN1cnJlbnRNb2R1bGVEYXRhO1xuXG5fX3dlYnBhY2tfcmVxdWlyZV9fLmkucHVzaChmdW5jdGlvbiAob3B0aW9ucykge1xuXHR2YXIgbW9kdWxlID0gb3B0aW9ucy5tb2R1bGU7XG5cdHZhciByZXF1aXJlID0gY3JlYXRlUmVxdWlyZShvcHRpb25zLnJlcXVpcmUsIG9wdGlvbnMuaWQpO1xuXHRtb2R1bGUuaG90ID0gY3JlYXRlTW9kdWxlSG90T2JqZWN0KG9wdGlvbnMuaWQsIG1vZHVsZSk7XG5cdG1vZHVsZS5wYXJlbnRzID0gY3VycmVudFBhcmVudHM7XG5cdG1vZHVsZS5jaGlsZHJlbiA9IFtdO1xuXHRjdXJyZW50UGFyZW50cyA9IFtdO1xuXHRvcHRpb25zLnJlcXVpcmUgPSByZXF1aXJlO1xufSk7XG5cbl9fd2VicGFja19yZXF1aXJlX18uaG1yQyA9IHt9O1xuX193ZWJwYWNrX3JlcXVpcmVfXy5obXJJID0ge307XG5cbmZ1bmN0aW9uIGNyZWF0ZVJlcXVpcmUocmVxdWlyZSwgbW9kdWxlSWQpIHtcblx0dmFyIG1lID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF07XG5cdGlmICghbWUpIHJldHVybiByZXF1aXJlO1xuXHR2YXIgZm4gPSBmdW5jdGlvbiAocmVxdWVzdCkge1xuXHRcdGlmIChtZS5ob3QuYWN0aXZlKSB7XG5cdFx0XHRpZiAoaW5zdGFsbGVkTW9kdWxlc1tyZXF1ZXN0XSkge1xuXHRcdFx0XHR2YXIgcGFyZW50cyA9IGluc3RhbGxlZE1vZHVsZXNbcmVxdWVzdF0ucGFyZW50cztcblx0XHRcdFx0aWYgKHBhcmVudHMuaW5kZXhPZihtb2R1bGVJZCkgPT09IC0xKSB7XG5cdFx0XHRcdFx0cGFyZW50cy5wdXNoKG1vZHVsZUlkKTtcblx0XHRcdFx0fVxuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0Y3VycmVudFBhcmVudHMgPSBbbW9kdWxlSWRdO1xuXHRcdFx0XHRjdXJyZW50Q2hpbGRNb2R1bGUgPSByZXF1ZXN0O1xuXHRcdFx0fVxuXHRcdFx0aWYgKG1lLmNoaWxkcmVuLmluZGV4T2YocmVxdWVzdCkgPT09IC0xKSB7XG5cdFx0XHRcdG1lLmNoaWxkcmVuLnB1c2gocmVxdWVzdCk7XG5cdFx0XHR9XG5cdFx0fSBlbHNlIHtcblx0XHRcdGNvbnNvbGUud2Fybihcblx0XHRcdFx0XCJbSE1SXSB1bmV4cGVjdGVkIHJlcXVpcmUoXCIgK1xuXHRcdFx0XHRcdHJlcXVlc3QgK1xuXHRcdFx0XHRcdFwiKSBmcm9tIGRpc3Bvc2VkIG1vZHVsZSBcIiArXG5cdFx0XHRcdFx0bW9kdWxlSWRcblx0XHRcdCk7XG5cdFx0XHRjdXJyZW50UGFyZW50cyA9IFtdO1xuXHRcdH1cblx0XHRyZXR1cm4gcmVxdWlyZShyZXF1ZXN0KTtcblx0fTtcblx0dmFyIGNyZWF0ZVByb3BlcnR5RGVzY3JpcHRvciA9IGZ1bmN0aW9uIChuYW1lKSB7XG5cdFx0cmV0dXJuIHtcblx0XHRcdGNvbmZpZ3VyYWJsZTogdHJ1ZSxcblx0XHRcdGVudW1lcmFibGU6IHRydWUsXG5cdFx0XHRnZXQ6IGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0cmV0dXJuIHJlcXVpcmVbbmFtZV07XG5cdFx0XHR9LFxuXHRcdFx0c2V0OiBmdW5jdGlvbiAodmFsdWUpIHtcblx0XHRcdFx0cmVxdWlyZVtuYW1lXSA9IHZhbHVlO1xuXHRcdFx0fVxuXHRcdH07XG5cdH07XG5cdGZvciAodmFyIG5hbWUgaW4gcmVxdWlyZSkge1xuXHRcdGlmIChPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwocmVxdWlyZSwgbmFtZSkgJiYgbmFtZSAhPT0gXCJlXCIpIHtcblx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShmbiwgbmFtZSwgY3JlYXRlUHJvcGVydHlEZXNjcmlwdG9yKG5hbWUpKTtcblx0XHR9XG5cdH1cblx0Zm4uZSA9IGZ1bmN0aW9uIChjaHVua0lkKSB7XG5cdFx0cmV0dXJuIHRyYWNrQmxvY2tpbmdQcm9taXNlKHJlcXVpcmUuZShjaHVua0lkKSk7XG5cdH07XG5cdHJldHVybiBmbjtcbn1cblxuZnVuY3Rpb24gY3JlYXRlTW9kdWxlSG90T2JqZWN0KG1vZHVsZUlkLCBtZSkge1xuXHR2YXIgX21haW4gPSBjdXJyZW50Q2hpbGRNb2R1bGUgIT09IG1vZHVsZUlkO1xuXHR2YXIgaG90ID0ge1xuXHRcdC8vIHByaXZhdGUgc3R1ZmZcblx0XHRfYWNjZXB0ZWREZXBlbmRlbmNpZXM6IHt9LFxuXHRcdF9hY2NlcHRlZEVycm9ySGFuZGxlcnM6IHt9LFxuXHRcdF9kZWNsaW5lZERlcGVuZGVuY2llczoge30sXG5cdFx0X3NlbGZBY2NlcHRlZDogZmFsc2UsXG5cdFx0X3NlbGZEZWNsaW5lZDogZmFsc2UsXG5cdFx0X3NlbGZJbnZhbGlkYXRlZDogZmFsc2UsXG5cdFx0X2Rpc3Bvc2VIYW5kbGVyczogW10sXG5cdFx0X21haW46IF9tYWluLFxuXHRcdF9yZXF1aXJlU2VsZjogZnVuY3Rpb24gKCkge1xuXHRcdFx0Y3VycmVudFBhcmVudHMgPSBtZS5wYXJlbnRzLnNsaWNlKCk7XG5cdFx0XHRjdXJyZW50Q2hpbGRNb2R1bGUgPSBfbWFpbiA/IHVuZGVmaW5lZCA6IG1vZHVsZUlkO1xuXHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCk7XG5cdFx0fSxcblxuXHRcdC8vIE1vZHVsZSBBUElcblx0XHRhY3RpdmU6IHRydWUsXG5cdFx0YWNjZXB0OiBmdW5jdGlvbiAoZGVwLCBjYWxsYmFjaywgZXJyb3JIYW5kbGVyKSB7XG5cdFx0XHRpZiAoZGVwID09PSB1bmRlZmluZWQpIGhvdC5fc2VsZkFjY2VwdGVkID0gdHJ1ZTtcblx0XHRcdGVsc2UgaWYgKHR5cGVvZiBkZXAgPT09IFwiZnVuY3Rpb25cIikgaG90Ll9zZWxmQWNjZXB0ZWQgPSBkZXA7XG5cdFx0XHRlbHNlIGlmICh0eXBlb2YgZGVwID09PSBcIm9iamVjdFwiICYmIGRlcCAhPT0gbnVsbCkge1xuXHRcdFx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGRlcC5sZW5ndGg7IGkrKykge1xuXHRcdFx0XHRcdGhvdC5fYWNjZXB0ZWREZXBlbmRlbmNpZXNbZGVwW2ldXSA9IGNhbGxiYWNrIHx8IGZ1bmN0aW9uICgpIHt9O1xuXHRcdFx0XHRcdGhvdC5fYWNjZXB0ZWRFcnJvckhhbmRsZXJzW2RlcFtpXV0gPSBlcnJvckhhbmRsZXI7XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGhvdC5fYWNjZXB0ZWREZXBlbmRlbmNpZXNbZGVwXSA9IGNhbGxiYWNrIHx8IGZ1bmN0aW9uICgpIHt9O1xuXHRcdFx0XHRob3QuX2FjY2VwdGVkRXJyb3JIYW5kbGVyc1tkZXBdID0gZXJyb3JIYW5kbGVyO1xuXHRcdFx0fVxuXHRcdH0sXG5cdFx0ZGVjbGluZTogZnVuY3Rpb24gKGRlcCkge1xuXHRcdFx0aWYgKGRlcCA9PT0gdW5kZWZpbmVkKSBob3QuX3NlbGZEZWNsaW5lZCA9IHRydWU7XG5cdFx0XHRlbHNlIGlmICh0eXBlb2YgZGVwID09PSBcIm9iamVjdFwiICYmIGRlcCAhPT0gbnVsbClcblx0XHRcdFx0Zm9yICh2YXIgaSA9IDA7IGkgPCBkZXAubGVuZ3RoOyBpKyspXG5cdFx0XHRcdFx0aG90Ll9kZWNsaW5lZERlcGVuZGVuY2llc1tkZXBbaV1dID0gdHJ1ZTtcblx0XHRcdGVsc2UgaG90Ll9kZWNsaW5lZERlcGVuZGVuY2llc1tkZXBdID0gdHJ1ZTtcblx0XHR9LFxuXHRcdGRpc3Bvc2U6IGZ1bmN0aW9uIChjYWxsYmFjaykge1xuXHRcdFx0aG90Ll9kaXNwb3NlSGFuZGxlcnMucHVzaChjYWxsYmFjayk7XG5cdFx0fSxcblx0XHRhZGREaXNwb3NlSGFuZGxlcjogZnVuY3Rpb24gKGNhbGxiYWNrKSB7XG5cdFx0XHRob3QuX2Rpc3Bvc2VIYW5kbGVycy5wdXNoKGNhbGxiYWNrKTtcblx0XHR9LFxuXHRcdHJlbW92ZURpc3Bvc2VIYW5kbGVyOiBmdW5jdGlvbiAoY2FsbGJhY2spIHtcblx0XHRcdHZhciBpZHggPSBob3QuX2Rpc3Bvc2VIYW5kbGVycy5pbmRleE9mKGNhbGxiYWNrKTtcblx0XHRcdGlmIChpZHggPj0gMCkgaG90Ll9kaXNwb3NlSGFuZGxlcnMuc3BsaWNlKGlkeCwgMSk7XG5cdFx0fSxcblx0XHRpbnZhbGlkYXRlOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHR0aGlzLl9zZWxmSW52YWxpZGF0ZWQgPSB0cnVlO1xuXHRcdFx0c3dpdGNoIChjdXJyZW50U3RhdHVzKSB7XG5cdFx0XHRcdGNhc2UgXCJpZGxlXCI6XG5cdFx0XHRcdFx0Y3VycmVudFVwZGF0ZUFwcGx5SGFuZGxlcnMgPSBbXTtcblx0XHRcdFx0XHRPYmplY3Qua2V5cyhfX3dlYnBhY2tfcmVxdWlyZV9fLmhtckkpLmZvckVhY2goZnVuY3Rpb24gKGtleSkge1xuXHRcdFx0XHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5obXJJW2tleV0oXG5cdFx0XHRcdFx0XHRcdG1vZHVsZUlkLFxuXHRcdFx0XHRcdFx0XHRjdXJyZW50VXBkYXRlQXBwbHlIYW5kbGVyc1xuXHRcdFx0XHRcdFx0KTtcblx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRzZXRTdGF0dXMoXCJyZWFkeVwiKTtcblx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0Y2FzZSBcInJlYWR5XCI6XG5cdFx0XHRcdFx0T2JqZWN0LmtleXMoX193ZWJwYWNrX3JlcXVpcmVfXy5obXJJKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcblx0XHRcdFx0XHRcdF9fd2VicGFja19yZXF1aXJlX18uaG1ySVtrZXldKFxuXHRcdFx0XHRcdFx0XHRtb2R1bGVJZCxcblx0XHRcdFx0XHRcdFx0Y3VycmVudFVwZGF0ZUFwcGx5SGFuZGxlcnNcblx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdGNhc2UgXCJwcmVwYXJlXCI6XG5cdFx0XHRcdGNhc2UgXCJjaGVja1wiOlxuXHRcdFx0XHRjYXNlIFwiZGlzcG9zZVwiOlxuXHRcdFx0XHRjYXNlIFwiYXBwbHlcIjpcblx0XHRcdFx0XHQocXVldWVkSW52YWxpZGF0ZWRNb2R1bGVzID0gcXVldWVkSW52YWxpZGF0ZWRNb2R1bGVzIHx8IFtdKS5wdXNoKFxuXHRcdFx0XHRcdFx0bW9kdWxlSWRcblx0XHRcdFx0XHQpO1xuXHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRkZWZhdWx0OlxuXHRcdFx0XHRcdC8vIGlnbm9yZSByZXF1ZXN0cyBpbiBlcnJvciBzdGF0ZXNcblx0XHRcdFx0XHRicmVhaztcblx0XHRcdH1cblx0XHR9LFxuXG5cdFx0Ly8gTWFuYWdlbWVudCBBUElcblx0XHRjaGVjazogaG90Q2hlY2ssXG5cdFx0YXBwbHk6IGhvdEFwcGx5LFxuXHRcdHN0YXR1czogZnVuY3Rpb24gKGwpIHtcblx0XHRcdGlmICghbCkgcmV0dXJuIGN1cnJlbnRTdGF0dXM7XG5cdFx0XHRyZWdpc3RlcmVkU3RhdHVzSGFuZGxlcnMucHVzaChsKTtcblx0XHR9LFxuXHRcdGFkZFN0YXR1c0hhbmRsZXI6IGZ1bmN0aW9uIChsKSB7XG5cdFx0XHRyZWdpc3RlcmVkU3RhdHVzSGFuZGxlcnMucHVzaChsKTtcblx0XHR9LFxuXHRcdHJlbW92ZVN0YXR1c0hhbmRsZXI6IGZ1bmN0aW9uIChsKSB7XG5cdFx0XHR2YXIgaWR4ID0gcmVnaXN0ZXJlZFN0YXR1c0hhbmRsZXJzLmluZGV4T2YobCk7XG5cdFx0XHRpZiAoaWR4ID49IDApIHJlZ2lzdGVyZWRTdGF0dXNIYW5kbGVycy5zcGxpY2UoaWR4LCAxKTtcblx0XHR9LFxuXG5cdFx0Ly9pbmhlcml0IGZyb20gcHJldmlvdXMgZGlzcG9zZSBjYWxsXG5cdFx0ZGF0YTogY3VycmVudE1vZHVsZURhdGFbbW9kdWxlSWRdXG5cdH07XG5cdGN1cnJlbnRDaGlsZE1vZHVsZSA9IHVuZGVmaW5lZDtcblx0cmV0dXJuIGhvdDtcbn1cblxuZnVuY3Rpb24gc2V0U3RhdHVzKG5ld1N0YXR1cykge1xuXHRjdXJyZW50U3RhdHVzID0gbmV3U3RhdHVzO1xuXHR2YXIgcmVzdWx0cyA9IFtdO1xuXG5cdGZvciAodmFyIGkgPSAwOyBpIDwgcmVnaXN0ZXJlZFN0YXR1c0hhbmRsZXJzLmxlbmd0aDsgaSsrKVxuXHRcdHJlc3VsdHNbaV0gPSByZWdpc3RlcmVkU3RhdHVzSGFuZGxlcnNbaV0uY2FsbChudWxsLCBuZXdTdGF0dXMpO1xuXG5cdHJldHVybiBQcm9taXNlLmFsbChyZXN1bHRzKTtcbn1cblxuZnVuY3Rpb24gdHJhY2tCbG9ja2luZ1Byb21pc2UocHJvbWlzZSkge1xuXHRzd2l0Y2ggKGN1cnJlbnRTdGF0dXMpIHtcblx0XHRjYXNlIFwicmVhZHlcIjpcblx0XHRcdHNldFN0YXR1cyhcInByZXBhcmVcIik7XG5cdFx0XHRibG9ja2luZ1Byb21pc2VzLnB1c2gocHJvbWlzZSk7XG5cdFx0XHR3YWl0Rm9yQmxvY2tpbmdQcm9taXNlcyhmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdHJldHVybiBzZXRTdGF0dXMoXCJyZWFkeVwiKTtcblx0XHRcdH0pO1xuXHRcdFx0cmV0dXJuIHByb21pc2U7XG5cdFx0Y2FzZSBcInByZXBhcmVcIjpcblx0XHRcdGJsb2NraW5nUHJvbWlzZXMucHVzaChwcm9taXNlKTtcblx0XHRcdHJldHVybiBwcm9taXNlO1xuXHRcdGRlZmF1bHQ6XG5cdFx0XHRyZXR1cm4gcHJvbWlzZTtcblx0fVxufVxuXG5mdW5jdGlvbiB3YWl0Rm9yQmxvY2tpbmdQcm9taXNlcyhmbikge1xuXHRpZiAoYmxvY2tpbmdQcm9taXNlcy5sZW5ndGggPT09IDApIHJldHVybiBmbigpO1xuXHR2YXIgYmxvY2tlciA9IGJsb2NraW5nUHJvbWlzZXM7XG5cdGJsb2NraW5nUHJvbWlzZXMgPSBbXTtcblx0cmV0dXJuIFByb21pc2UuYWxsKGJsb2NrZXIpLnRoZW4oZnVuY3Rpb24gKCkge1xuXHRcdHJldHVybiB3YWl0Rm9yQmxvY2tpbmdQcm9taXNlcyhmbik7XG5cdH0pO1xufVxuXG5mdW5jdGlvbiBob3RDaGVjayhhcHBseU9uVXBkYXRlKSB7XG5cdGlmIChjdXJyZW50U3RhdHVzICE9PSBcImlkbGVcIikge1xuXHRcdHRocm93IG5ldyBFcnJvcihcImNoZWNrKCkgaXMgb25seSBhbGxvd2VkIGluIGlkbGUgc3RhdHVzXCIpO1xuXHR9XG5cdHJldHVybiBzZXRTdGF0dXMoXCJjaGVja1wiKVxuXHRcdC50aGVuKF9fd2VicGFja19yZXF1aXJlX18uaG1yTSlcblx0XHQudGhlbihmdW5jdGlvbiAodXBkYXRlKSB7XG5cdFx0XHRpZiAoIXVwZGF0ZSkge1xuXHRcdFx0XHRyZXR1cm4gc2V0U3RhdHVzKGFwcGx5SW52YWxpZGF0ZWRNb2R1bGVzKCkgPyBcInJlYWR5XCIgOiBcImlkbGVcIikudGhlbihcblx0XHRcdFx0XHRmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gbnVsbDtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdCk7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiBzZXRTdGF0dXMoXCJwcmVwYXJlXCIpLnRoZW4oZnVuY3Rpb24gKCkge1xuXHRcdFx0XHR2YXIgdXBkYXRlZE1vZHVsZXMgPSBbXTtcblx0XHRcdFx0YmxvY2tpbmdQcm9taXNlcyA9IFtdO1xuXHRcdFx0XHRjdXJyZW50VXBkYXRlQXBwbHlIYW5kbGVycyA9IFtdO1xuXG5cdFx0XHRcdHJldHVybiBQcm9taXNlLmFsbChcblx0XHRcdFx0XHRPYmplY3Qua2V5cyhfX3dlYnBhY2tfcmVxdWlyZV9fLmhtckMpLnJlZHVjZShmdW5jdGlvbiAoXG5cdFx0XHRcdFx0XHRwcm9taXNlcyxcblx0XHRcdFx0XHRcdGtleVxuXHRcdFx0XHRcdCkge1xuXHRcdFx0XHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5obXJDW2tleV0oXG5cdFx0XHRcdFx0XHRcdHVwZGF0ZS5jLFxuXHRcdFx0XHRcdFx0XHR1cGRhdGUucixcblx0XHRcdFx0XHRcdFx0dXBkYXRlLm0sXG5cdFx0XHRcdFx0XHRcdHByb21pc2VzLFxuXHRcdFx0XHRcdFx0XHRjdXJyZW50VXBkYXRlQXBwbHlIYW5kbGVycyxcblx0XHRcdFx0XHRcdFx0dXBkYXRlZE1vZHVsZXNcblx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gcHJvbWlzZXM7XG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRbXSlcblx0XHRcdFx0KS50aGVuKGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRyZXR1cm4gd2FpdEZvckJsb2NraW5nUHJvbWlzZXMoZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdFx0aWYgKGFwcGx5T25VcGRhdGUpIHtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIGludGVybmFsQXBwbHkoYXBwbHlPblVwZGF0ZSk7XG5cdFx0XHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gc2V0U3RhdHVzKFwicmVhZHlcIikudGhlbihmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHVwZGF0ZWRNb2R1bGVzO1xuXHRcdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9KTtcblx0XHRcdFx0fSk7XG5cdFx0XHR9KTtcblx0XHR9KTtcbn1cblxuZnVuY3Rpb24gaG90QXBwbHkob3B0aW9ucykge1xuXHRpZiAoY3VycmVudFN0YXR1cyAhPT0gXCJyZWFkeVwiKSB7XG5cdFx0cmV0dXJuIFByb21pc2UucmVzb2x2ZSgpLnRoZW4oZnVuY3Rpb24gKCkge1xuXHRcdFx0dGhyb3cgbmV3IEVycm9yKFwiYXBwbHkoKSBpcyBvbmx5IGFsbG93ZWQgaW4gcmVhZHkgc3RhdHVzXCIpO1xuXHRcdH0pO1xuXHR9XG5cdHJldHVybiBpbnRlcm5hbEFwcGx5KG9wdGlvbnMpO1xufVxuXG5mdW5jdGlvbiBpbnRlcm5hbEFwcGx5KG9wdGlvbnMpIHtcblx0b3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cblx0YXBwbHlJbnZhbGlkYXRlZE1vZHVsZXMoKTtcblxuXHR2YXIgcmVzdWx0cyA9IGN1cnJlbnRVcGRhdGVBcHBseUhhbmRsZXJzLm1hcChmdW5jdGlvbiAoaGFuZGxlcikge1xuXHRcdHJldHVybiBoYW5kbGVyKG9wdGlvbnMpO1xuXHR9KTtcblx0Y3VycmVudFVwZGF0ZUFwcGx5SGFuZGxlcnMgPSB1bmRlZmluZWQ7XG5cblx0dmFyIGVycm9ycyA9IHJlc3VsdHNcblx0XHQubWFwKGZ1bmN0aW9uIChyKSB7XG5cdFx0XHRyZXR1cm4gci5lcnJvcjtcblx0XHR9KVxuXHRcdC5maWx0ZXIoQm9vbGVhbik7XG5cblx0aWYgKGVycm9ycy5sZW5ndGggPiAwKSB7XG5cdFx0cmV0dXJuIHNldFN0YXR1cyhcImFib3J0XCIpLnRoZW4oZnVuY3Rpb24gKCkge1xuXHRcdFx0dGhyb3cgZXJyb3JzWzBdO1xuXHRcdH0pO1xuXHR9XG5cblx0Ly8gTm93IGluIFwiZGlzcG9zZVwiIHBoYXNlXG5cdHZhciBkaXNwb3NlUHJvbWlzZSA9IHNldFN0YXR1cyhcImRpc3Bvc2VcIik7XG5cblx0cmVzdWx0cy5mb3JFYWNoKGZ1bmN0aW9uIChyZXN1bHQpIHtcblx0XHRpZiAocmVzdWx0LmRpc3Bvc2UpIHJlc3VsdC5kaXNwb3NlKCk7XG5cdH0pO1xuXG5cdC8vIE5vdyBpbiBcImFwcGx5XCIgcGhhc2Vcblx0dmFyIGFwcGx5UHJvbWlzZSA9IHNldFN0YXR1cyhcImFwcGx5XCIpO1xuXG5cdHZhciBlcnJvcjtcblx0dmFyIHJlcG9ydEVycm9yID0gZnVuY3Rpb24gKGVycikge1xuXHRcdGlmICghZXJyb3IpIGVycm9yID0gZXJyO1xuXHR9O1xuXG5cdHZhciBvdXRkYXRlZE1vZHVsZXMgPSBbXTtcblx0cmVzdWx0cy5mb3JFYWNoKGZ1bmN0aW9uIChyZXN1bHQpIHtcblx0XHRpZiAocmVzdWx0LmFwcGx5KSB7XG5cdFx0XHR2YXIgbW9kdWxlcyA9IHJlc3VsdC5hcHBseShyZXBvcnRFcnJvcik7XG5cdFx0XHRpZiAobW9kdWxlcykge1xuXHRcdFx0XHRmb3IgKHZhciBpID0gMDsgaSA8IG1vZHVsZXMubGVuZ3RoOyBpKyspIHtcblx0XHRcdFx0XHRvdXRkYXRlZE1vZHVsZXMucHVzaChtb2R1bGVzW2ldKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH1cblx0fSk7XG5cblx0cmV0dXJuIFByb21pc2UuYWxsKFtkaXNwb3NlUHJvbWlzZSwgYXBwbHlQcm9taXNlXSkudGhlbihmdW5jdGlvbiAoKSB7XG5cdFx0Ly8gaGFuZGxlIGVycm9ycyBpbiBhY2NlcHQgaGFuZGxlcnMgYW5kIHNlbGYgYWNjZXB0ZWQgbW9kdWxlIGxvYWRcblx0XHRpZiAoZXJyb3IpIHtcblx0XHRcdHJldHVybiBzZXRTdGF0dXMoXCJmYWlsXCIpLnRoZW4oZnVuY3Rpb24gKCkge1xuXHRcdFx0XHR0aHJvdyBlcnJvcjtcblx0XHRcdH0pO1xuXHRcdH1cblxuXHRcdGlmIChxdWV1ZWRJbnZhbGlkYXRlZE1vZHVsZXMpIHtcblx0XHRcdHJldHVybiBpbnRlcm5hbEFwcGx5KG9wdGlvbnMpLnRoZW4oZnVuY3Rpb24gKGxpc3QpIHtcblx0XHRcdFx0b3V0ZGF0ZWRNb2R1bGVzLmZvckVhY2goZnVuY3Rpb24gKG1vZHVsZUlkKSB7XG5cdFx0XHRcdFx0aWYgKGxpc3QuaW5kZXhPZihtb2R1bGVJZCkgPCAwKSBsaXN0LnB1c2gobW9kdWxlSWQpO1xuXHRcdFx0XHR9KTtcblx0XHRcdFx0cmV0dXJuIGxpc3Q7XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0XHRyZXR1cm4gc2V0U3RhdHVzKFwiaWRsZVwiKS50aGVuKGZ1bmN0aW9uICgpIHtcblx0XHRcdHJldHVybiBvdXRkYXRlZE1vZHVsZXM7XG5cdFx0fSk7XG5cdH0pO1xufVxuXG5mdW5jdGlvbiBhcHBseUludmFsaWRhdGVkTW9kdWxlcygpIHtcblx0aWYgKHF1ZXVlZEludmFsaWRhdGVkTW9kdWxlcykge1xuXHRcdGlmICghY3VycmVudFVwZGF0ZUFwcGx5SGFuZGxlcnMpIGN1cnJlbnRVcGRhdGVBcHBseUhhbmRsZXJzID0gW107XG5cdFx0T2JqZWN0LmtleXMoX193ZWJwYWNrX3JlcXVpcmVfXy5obXJJKS5mb3JFYWNoKGZ1bmN0aW9uIChrZXkpIHtcblx0XHRcdHF1ZXVlZEludmFsaWRhdGVkTW9kdWxlcy5mb3JFYWNoKGZ1bmN0aW9uIChtb2R1bGVJZCkge1xuXHRcdFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmhtcklba2V5XShcblx0XHRcdFx0XHRtb2R1bGVJZCxcblx0XHRcdFx0XHRjdXJyZW50VXBkYXRlQXBwbHlIYW5kbGVyc1xuXHRcdFx0XHQpO1xuXHRcdFx0fSk7XG5cdFx0fSk7XG5cdFx0cXVldWVkSW52YWxpZGF0ZWRNb2R1bGVzID0gdW5kZWZpbmVkO1xuXHRcdHJldHVybiB0cnVlO1xuXHR9XG59IiwidmFyIHNjcmlwdFVybDtcbmlmIChfX3dlYnBhY2tfcmVxdWlyZV9fLmcuaW1wb3J0U2NyaXB0cykgc2NyaXB0VXJsID0gX193ZWJwYWNrX3JlcXVpcmVfXy5nLmxvY2F0aW9uICsgXCJcIjtcbnZhciBkb2N1bWVudCA9IF9fd2VicGFja19yZXF1aXJlX18uZy5kb2N1bWVudDtcbmlmICghc2NyaXB0VXJsICYmIGRvY3VtZW50KSB7XG5cdGlmIChkb2N1bWVudC5jdXJyZW50U2NyaXB0KVxuXHRcdHNjcmlwdFVybCA9IGRvY3VtZW50LmN1cnJlbnRTY3JpcHQuc3JjXG5cdGlmICghc2NyaXB0VXJsKSB7XG5cdFx0dmFyIHNjcmlwdHMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZShcInNjcmlwdFwiKTtcblx0XHRpZihzY3JpcHRzLmxlbmd0aCkgc2NyaXB0VXJsID0gc2NyaXB0c1tzY3JpcHRzLmxlbmd0aCAtIDFdLnNyY1xuXHR9XG59XG4vLyBXaGVuIHN1cHBvcnRpbmcgYnJvd3NlcnMgd2hlcmUgYW4gYXV0b21hdGljIHB1YmxpY1BhdGggaXMgbm90IHN1cHBvcnRlZCB5b3UgbXVzdCBzcGVjaWZ5IGFuIG91dHB1dC5wdWJsaWNQYXRoIG1hbnVhbGx5IHZpYSBjb25maWd1cmF0aW9uXG4vLyBvciBwYXNzIGFuIGVtcHR5IHN0cmluZyAoXCJcIikgYW5kIHNldCB0aGUgX193ZWJwYWNrX3B1YmxpY19wYXRoX18gdmFyaWFibGUgZnJvbSB5b3VyIGNvZGUgdG8gdXNlIHlvdXIgb3duIGxvZ2ljLlxuaWYgKCFzY3JpcHRVcmwpIHRocm93IG5ldyBFcnJvcihcIkF1dG9tYXRpYyBwdWJsaWNQYXRoIGlzIG5vdCBzdXBwb3J0ZWQgaW4gdGhpcyBicm93c2VyXCIpO1xuc2NyaXB0VXJsID0gc2NyaXB0VXJsLnJlcGxhY2UoLyMuKiQvLCBcIlwiKS5yZXBsYWNlKC9cXD8uKiQvLCBcIlwiKS5yZXBsYWNlKC9cXC9bXlxcL10rJC8sIFwiL1wiKTtcbl9fd2VicGFja19yZXF1aXJlX18ucCA9IHNjcmlwdFVybDsiLCIvLyBubyBiYXNlVVJJXG5cbi8vIG9iamVjdCB0byBzdG9yZSBsb2FkZWQgYW5kIGxvYWRpbmcgY2h1bmtzXG4vLyB1bmRlZmluZWQgPSBjaHVuayBub3QgbG9hZGVkLCBudWxsID0gY2h1bmsgcHJlbG9hZGVkL3ByZWZldGNoZWRcbi8vIFtyZXNvbHZlLCByZWplY3QsIFByb21pc2VdID0gY2h1bmsgbG9hZGluZywgMCA9IGNodW5rIGxvYWRlZFxudmFyIGluc3RhbGxlZENodW5rcyA9IF9fd2VicGFja19yZXF1aXJlX18uaG1yU19qc29ucCA9IF9fd2VicGFja19yZXF1aXJlX18uaG1yU19qc29ucCB8fCB7XG5cdDE3OTogMFxufTtcblxuLy8gbm8gY2h1bmsgb24gZGVtYW5kIGxvYWRpbmdcblxuLy8gbm8gcHJlZmV0Y2hpbmdcblxuLy8gbm8gcHJlbG9hZGVkXG5cbnZhciBjdXJyZW50VXBkYXRlZE1vZHVsZXNMaXN0O1xudmFyIHdhaXRpbmdVcGRhdGVSZXNvbHZlcyA9IHt9O1xuZnVuY3Rpb24gbG9hZFVwZGF0ZUNodW5rKGNodW5rSWQpIHtcblx0cmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcblx0XHR3YWl0aW5nVXBkYXRlUmVzb2x2ZXNbY2h1bmtJZF0gPSByZXNvbHZlO1xuXHRcdC8vIHN0YXJ0IHVwZGF0ZSBjaHVuayBsb2FkaW5nXG5cdFx0dmFyIHVybCA9IF9fd2VicGFja19yZXF1aXJlX18ucCArIF9fd2VicGFja19yZXF1aXJlX18uaHUoY2h1bmtJZCk7XG5cdFx0Ly8gY3JlYXRlIGVycm9yIGJlZm9yZSBzdGFjayB1bndvdW5kIHRvIGdldCB1c2VmdWwgc3RhY2t0cmFjZSBsYXRlclxuXHRcdHZhciBlcnJvciA9IG5ldyBFcnJvcigpO1xuXHRcdHZhciBsb2FkaW5nRW5kZWQgPSAoZXZlbnQpID0+IHtcblx0XHRcdGlmKHdhaXRpbmdVcGRhdGVSZXNvbHZlc1tjaHVua0lkXSkge1xuXHRcdFx0XHR3YWl0aW5nVXBkYXRlUmVzb2x2ZXNbY2h1bmtJZF0gPSB1bmRlZmluZWRcblx0XHRcdFx0dmFyIGVycm9yVHlwZSA9IGV2ZW50ICYmIChldmVudC50eXBlID09PSAnbG9hZCcgPyAnbWlzc2luZycgOiBldmVudC50eXBlKTtcblx0XHRcdFx0dmFyIHJlYWxTcmMgPSBldmVudCAmJiBldmVudC50YXJnZXQgJiYgZXZlbnQudGFyZ2V0LnNyYztcblx0XHRcdFx0ZXJyb3IubWVzc2FnZSA9ICdMb2FkaW5nIGhvdCB1cGRhdGUgY2h1bmsgJyArIGNodW5rSWQgKyAnIGZhaWxlZC5cXG4oJyArIGVycm9yVHlwZSArICc6ICcgKyByZWFsU3JjICsgJyknO1xuXHRcdFx0XHRlcnJvci5uYW1lID0gJ0NodW5rTG9hZEVycm9yJztcblx0XHRcdFx0ZXJyb3IudHlwZSA9IGVycm9yVHlwZTtcblx0XHRcdFx0ZXJyb3IucmVxdWVzdCA9IHJlYWxTcmM7XG5cdFx0XHRcdHJlamVjdChlcnJvcik7XG5cdFx0XHR9XG5cdFx0fTtcblx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmwodXJsLCBsb2FkaW5nRW5kZWQpO1xuXHR9KTtcbn1cblxuc2VsZltcIndlYnBhY2tIb3RVcGRhdGV3c3BsYXllclwiXSA9IChjaHVua0lkLCBtb3JlTW9kdWxlcywgcnVudGltZSkgPT4ge1xuXHRmb3IodmFyIG1vZHVsZUlkIGluIG1vcmVNb2R1bGVzKSB7XG5cdFx0aWYoX193ZWJwYWNrX3JlcXVpcmVfXy5vKG1vcmVNb2R1bGVzLCBtb2R1bGVJZCkpIHtcblx0XHRcdGN1cnJlbnRVcGRhdGVbbW9kdWxlSWRdID0gbW9yZU1vZHVsZXNbbW9kdWxlSWRdO1xuXHRcdFx0aWYoY3VycmVudFVwZGF0ZWRNb2R1bGVzTGlzdCkgY3VycmVudFVwZGF0ZWRNb2R1bGVzTGlzdC5wdXNoKG1vZHVsZUlkKTtcblx0XHR9XG5cdH1cblx0aWYocnVudGltZSkgY3VycmVudFVwZGF0ZVJ1bnRpbWUucHVzaChydW50aW1lKTtcblx0aWYod2FpdGluZ1VwZGF0ZVJlc29sdmVzW2NodW5rSWRdKSB7XG5cdFx0d2FpdGluZ1VwZGF0ZVJlc29sdmVzW2NodW5rSWRdKCk7XG5cdFx0d2FpdGluZ1VwZGF0ZVJlc29sdmVzW2NodW5rSWRdID0gdW5kZWZpbmVkO1xuXHR9XG59O1xuXG52YXIgY3VycmVudFVwZGF0ZUNodW5rcztcbnZhciBjdXJyZW50VXBkYXRlO1xudmFyIGN1cnJlbnRVcGRhdGVSZW1vdmVkQ2h1bmtzO1xudmFyIGN1cnJlbnRVcGRhdGVSdW50aW1lO1xuZnVuY3Rpb24gYXBwbHlIYW5kbGVyKG9wdGlvbnMpIHtcblx0aWYgKF9fd2VicGFja19yZXF1aXJlX18uZikgZGVsZXRlIF9fd2VicGFja19yZXF1aXJlX18uZi5qc29ucEhtcjtcblx0Y3VycmVudFVwZGF0ZUNodW5rcyA9IHVuZGVmaW5lZDtcblx0ZnVuY3Rpb24gZ2V0QWZmZWN0ZWRNb2R1bGVFZmZlY3RzKHVwZGF0ZU1vZHVsZUlkKSB7XG5cdFx0dmFyIG91dGRhdGVkTW9kdWxlcyA9IFt1cGRhdGVNb2R1bGVJZF07XG5cdFx0dmFyIG91dGRhdGVkRGVwZW5kZW5jaWVzID0ge307XG5cblx0XHR2YXIgcXVldWUgPSBvdXRkYXRlZE1vZHVsZXMubWFwKGZ1bmN0aW9uIChpZCkge1xuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0Y2hhaW46IFtpZF0sXG5cdFx0XHRcdGlkOiBpZFxuXHRcdFx0fTtcblx0XHR9KTtcblx0XHR3aGlsZSAocXVldWUubGVuZ3RoID4gMCkge1xuXHRcdFx0dmFyIHF1ZXVlSXRlbSA9IHF1ZXVlLnBvcCgpO1xuXHRcdFx0dmFyIG1vZHVsZUlkID0gcXVldWVJdGVtLmlkO1xuXHRcdFx0dmFyIGNoYWluID0gcXVldWVJdGVtLmNoYWluO1xuXHRcdFx0dmFyIG1vZHVsZSA9IF9fd2VicGFja19yZXF1aXJlX18uY1ttb2R1bGVJZF07XG5cdFx0XHRpZiAoXG5cdFx0XHRcdCFtb2R1bGUgfHxcblx0XHRcdFx0KG1vZHVsZS5ob3QuX3NlbGZBY2NlcHRlZCAmJiAhbW9kdWxlLmhvdC5fc2VsZkludmFsaWRhdGVkKVxuXHRcdFx0KVxuXHRcdFx0XHRjb250aW51ZTtcblx0XHRcdGlmIChtb2R1bGUuaG90Ll9zZWxmRGVjbGluZWQpIHtcblx0XHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0XHR0eXBlOiBcInNlbGYtZGVjbGluZWRcIixcblx0XHRcdFx0XHRjaGFpbjogY2hhaW4sXG5cdFx0XHRcdFx0bW9kdWxlSWQ6IG1vZHVsZUlkXG5cdFx0XHRcdH07XG5cdFx0XHR9XG5cdFx0XHRpZiAobW9kdWxlLmhvdC5fbWFpbikge1xuXHRcdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRcdHR5cGU6IFwidW5hY2NlcHRlZFwiLFxuXHRcdFx0XHRcdGNoYWluOiBjaGFpbixcblx0XHRcdFx0XHRtb2R1bGVJZDogbW9kdWxlSWRcblx0XHRcdFx0fTtcblx0XHRcdH1cblx0XHRcdGZvciAodmFyIGkgPSAwOyBpIDwgbW9kdWxlLnBhcmVudHMubGVuZ3RoOyBpKyspIHtcblx0XHRcdFx0dmFyIHBhcmVudElkID0gbW9kdWxlLnBhcmVudHNbaV07XG5cdFx0XHRcdHZhciBwYXJlbnQgPSBfX3dlYnBhY2tfcmVxdWlyZV9fLmNbcGFyZW50SWRdO1xuXHRcdFx0XHRpZiAoIXBhcmVudCkgY29udGludWU7XG5cdFx0XHRcdGlmIChwYXJlbnQuaG90Ll9kZWNsaW5lZERlcGVuZGVuY2llc1ttb2R1bGVJZF0pIHtcblx0XHRcdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRcdFx0dHlwZTogXCJkZWNsaW5lZFwiLFxuXHRcdFx0XHRcdFx0Y2hhaW46IGNoYWluLmNvbmNhdChbcGFyZW50SWRdKSxcblx0XHRcdFx0XHRcdG1vZHVsZUlkOiBtb2R1bGVJZCxcblx0XHRcdFx0XHRcdHBhcmVudElkOiBwYXJlbnRJZFxuXHRcdFx0XHRcdH07XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKG91dGRhdGVkTW9kdWxlcy5pbmRleE9mKHBhcmVudElkKSAhPT0gLTEpIGNvbnRpbnVlO1xuXHRcdFx0XHRpZiAocGFyZW50LmhvdC5fYWNjZXB0ZWREZXBlbmRlbmNpZXNbbW9kdWxlSWRdKSB7XG5cdFx0XHRcdFx0aWYgKCFvdXRkYXRlZERlcGVuZGVuY2llc1twYXJlbnRJZF0pXG5cdFx0XHRcdFx0XHRvdXRkYXRlZERlcGVuZGVuY2llc1twYXJlbnRJZF0gPSBbXTtcblx0XHRcdFx0XHRhZGRBbGxUb1NldChvdXRkYXRlZERlcGVuZGVuY2llc1twYXJlbnRJZF0sIFttb2R1bGVJZF0pO1xuXHRcdFx0XHRcdGNvbnRpbnVlO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGRlbGV0ZSBvdXRkYXRlZERlcGVuZGVuY2llc1twYXJlbnRJZF07XG5cdFx0XHRcdG91dGRhdGVkTW9kdWxlcy5wdXNoKHBhcmVudElkKTtcblx0XHRcdFx0cXVldWUucHVzaCh7XG5cdFx0XHRcdFx0Y2hhaW46IGNoYWluLmNvbmNhdChbcGFyZW50SWRdKSxcblx0XHRcdFx0XHRpZDogcGFyZW50SWRcblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0cmV0dXJuIHtcblx0XHRcdHR5cGU6IFwiYWNjZXB0ZWRcIixcblx0XHRcdG1vZHVsZUlkOiB1cGRhdGVNb2R1bGVJZCxcblx0XHRcdG91dGRhdGVkTW9kdWxlczogb3V0ZGF0ZWRNb2R1bGVzLFxuXHRcdFx0b3V0ZGF0ZWREZXBlbmRlbmNpZXM6IG91dGRhdGVkRGVwZW5kZW5jaWVzXG5cdFx0fTtcblx0fVxuXG5cdGZ1bmN0aW9uIGFkZEFsbFRvU2V0KGEsIGIpIHtcblx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGIubGVuZ3RoOyBpKyspIHtcblx0XHRcdHZhciBpdGVtID0gYltpXTtcblx0XHRcdGlmIChhLmluZGV4T2YoaXRlbSkgPT09IC0xKSBhLnB1c2goaXRlbSk7XG5cdFx0fVxuXHR9XG5cblx0Ly8gYXQgYmVnaW4gYWxsIHVwZGF0ZXMgbW9kdWxlcyBhcmUgb3V0ZGF0ZWRcblx0Ly8gdGhlIFwib3V0ZGF0ZWRcIiBzdGF0dXMgY2FuIHByb3BhZ2F0ZSB0byBwYXJlbnRzIGlmIHRoZXkgZG9uJ3QgYWNjZXB0IHRoZSBjaGlsZHJlblxuXHR2YXIgb3V0ZGF0ZWREZXBlbmRlbmNpZXMgPSB7fTtcblx0dmFyIG91dGRhdGVkTW9kdWxlcyA9IFtdO1xuXHR2YXIgYXBwbGllZFVwZGF0ZSA9IHt9O1xuXG5cdHZhciB3YXJuVW5leHBlY3RlZFJlcXVpcmUgPSBmdW5jdGlvbiB3YXJuVW5leHBlY3RlZFJlcXVpcmUobW9kdWxlKSB7XG5cdFx0Y29uc29sZS53YXJuKFxuXHRcdFx0XCJbSE1SXSB1bmV4cGVjdGVkIHJlcXVpcmUoXCIgKyBtb2R1bGUuaWQgKyBcIikgdG8gZGlzcG9zZWQgbW9kdWxlXCJcblx0XHQpO1xuXHR9O1xuXG5cdGZvciAodmFyIG1vZHVsZUlkIGluIGN1cnJlbnRVcGRhdGUpIHtcblx0XHRpZiAoX193ZWJwYWNrX3JlcXVpcmVfXy5vKGN1cnJlbnRVcGRhdGUsIG1vZHVsZUlkKSkge1xuXHRcdFx0dmFyIG5ld01vZHVsZUZhY3RvcnkgPSBjdXJyZW50VXBkYXRlW21vZHVsZUlkXTtcblx0XHRcdC8qKiBAdHlwZSB7VE9ET30gKi9cblx0XHRcdHZhciByZXN1bHQ7XG5cdFx0XHRpZiAobmV3TW9kdWxlRmFjdG9yeSkge1xuXHRcdFx0XHRyZXN1bHQgPSBnZXRBZmZlY3RlZE1vZHVsZUVmZmVjdHMobW9kdWxlSWQpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0cmVzdWx0ID0ge1xuXHRcdFx0XHRcdHR5cGU6IFwiZGlzcG9zZWRcIixcblx0XHRcdFx0XHRtb2R1bGVJZDogbW9kdWxlSWRcblx0XHRcdFx0fTtcblx0XHRcdH1cblx0XHRcdC8qKiBAdHlwZSB7RXJyb3J8ZmFsc2V9ICovXG5cdFx0XHR2YXIgYWJvcnRFcnJvciA9IGZhbHNlO1xuXHRcdFx0dmFyIGRvQXBwbHkgPSBmYWxzZTtcblx0XHRcdHZhciBkb0Rpc3Bvc2UgPSBmYWxzZTtcblx0XHRcdHZhciBjaGFpbkluZm8gPSBcIlwiO1xuXHRcdFx0aWYgKHJlc3VsdC5jaGFpbikge1xuXHRcdFx0XHRjaGFpbkluZm8gPSBcIlxcblVwZGF0ZSBwcm9wYWdhdGlvbjogXCIgKyByZXN1bHQuY2hhaW4uam9pbihcIiAtPiBcIik7XG5cdFx0XHR9XG5cdFx0XHRzd2l0Y2ggKHJlc3VsdC50eXBlKSB7XG5cdFx0XHRcdGNhc2UgXCJzZWxmLWRlY2xpbmVkXCI6XG5cdFx0XHRcdFx0aWYgKG9wdGlvbnMub25EZWNsaW5lZCkgb3B0aW9ucy5vbkRlY2xpbmVkKHJlc3VsdCk7XG5cdFx0XHRcdFx0aWYgKCFvcHRpb25zLmlnbm9yZURlY2xpbmVkKVxuXHRcdFx0XHRcdFx0YWJvcnRFcnJvciA9IG5ldyBFcnJvcihcblx0XHRcdFx0XHRcdFx0XCJBYm9ydGVkIGJlY2F1c2Ugb2Ygc2VsZiBkZWNsaW5lOiBcIiArXG5cdFx0XHRcdFx0XHRcdFx0cmVzdWx0Lm1vZHVsZUlkICtcblx0XHRcdFx0XHRcdFx0XHRjaGFpbkluZm9cblx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdGNhc2UgXCJkZWNsaW5lZFwiOlxuXHRcdFx0XHRcdGlmIChvcHRpb25zLm9uRGVjbGluZWQpIG9wdGlvbnMub25EZWNsaW5lZChyZXN1bHQpO1xuXHRcdFx0XHRcdGlmICghb3B0aW9ucy5pZ25vcmVEZWNsaW5lZClcblx0XHRcdFx0XHRcdGFib3J0RXJyb3IgPSBuZXcgRXJyb3IoXG5cdFx0XHRcdFx0XHRcdFwiQWJvcnRlZCBiZWNhdXNlIG9mIGRlY2xpbmVkIGRlcGVuZGVuY3k6IFwiICtcblx0XHRcdFx0XHRcdFx0XHRyZXN1bHQubW9kdWxlSWQgK1xuXHRcdFx0XHRcdFx0XHRcdFwiIGluIFwiICtcblx0XHRcdFx0XHRcdFx0XHRyZXN1bHQucGFyZW50SWQgK1xuXHRcdFx0XHRcdFx0XHRcdGNoYWluSW5mb1xuXHRcdFx0XHRcdFx0KTtcblx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0Y2FzZSBcInVuYWNjZXB0ZWRcIjpcblx0XHRcdFx0XHRpZiAob3B0aW9ucy5vblVuYWNjZXB0ZWQpIG9wdGlvbnMub25VbmFjY2VwdGVkKHJlc3VsdCk7XG5cdFx0XHRcdFx0aWYgKCFvcHRpb25zLmlnbm9yZVVuYWNjZXB0ZWQpXG5cdFx0XHRcdFx0XHRhYm9ydEVycm9yID0gbmV3IEVycm9yKFxuXHRcdFx0XHRcdFx0XHRcIkFib3J0ZWQgYmVjYXVzZSBcIiArIG1vZHVsZUlkICsgXCIgaXMgbm90IGFjY2VwdGVkXCIgKyBjaGFpbkluZm9cblx0XHRcdFx0XHRcdCk7XG5cdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdGNhc2UgXCJhY2NlcHRlZFwiOlxuXHRcdFx0XHRcdGlmIChvcHRpb25zLm9uQWNjZXB0ZWQpIG9wdGlvbnMub25BY2NlcHRlZChyZXN1bHQpO1xuXHRcdFx0XHRcdGRvQXBwbHkgPSB0cnVlO1xuXHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRjYXNlIFwiZGlzcG9zZWRcIjpcblx0XHRcdFx0XHRpZiAob3B0aW9ucy5vbkRpc3Bvc2VkKSBvcHRpb25zLm9uRGlzcG9zZWQocmVzdWx0KTtcblx0XHRcdFx0XHRkb0Rpc3Bvc2UgPSB0cnVlO1xuXHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRkZWZhdWx0OlxuXHRcdFx0XHRcdHRocm93IG5ldyBFcnJvcihcIlVuZXhjZXB0aW9uIHR5cGUgXCIgKyByZXN1bHQudHlwZSk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoYWJvcnRFcnJvcikge1xuXHRcdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRcdGVycm9yOiBhYm9ydEVycm9yXG5cdFx0XHRcdH07XG5cdFx0XHR9XG5cdFx0XHRpZiAoZG9BcHBseSkge1xuXHRcdFx0XHRhcHBsaWVkVXBkYXRlW21vZHVsZUlkXSA9IG5ld01vZHVsZUZhY3Rvcnk7XG5cdFx0XHRcdGFkZEFsbFRvU2V0KG91dGRhdGVkTW9kdWxlcywgcmVzdWx0Lm91dGRhdGVkTW9kdWxlcyk7XG5cdFx0XHRcdGZvciAobW9kdWxlSWQgaW4gcmVzdWx0Lm91dGRhdGVkRGVwZW5kZW5jaWVzKSB7XG5cdFx0XHRcdFx0aWYgKF9fd2VicGFja19yZXF1aXJlX18ubyhyZXN1bHQub3V0ZGF0ZWREZXBlbmRlbmNpZXMsIG1vZHVsZUlkKSkge1xuXHRcdFx0XHRcdFx0aWYgKCFvdXRkYXRlZERlcGVuZGVuY2llc1ttb2R1bGVJZF0pXG5cdFx0XHRcdFx0XHRcdG91dGRhdGVkRGVwZW5kZW5jaWVzW21vZHVsZUlkXSA9IFtdO1xuXHRcdFx0XHRcdFx0YWRkQWxsVG9TZXQoXG5cdFx0XHRcdFx0XHRcdG91dGRhdGVkRGVwZW5kZW5jaWVzW21vZHVsZUlkXSxcblx0XHRcdFx0XHRcdFx0cmVzdWx0Lm91dGRhdGVkRGVwZW5kZW5jaWVzW21vZHVsZUlkXVxuXHRcdFx0XHRcdFx0KTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHRcdGlmIChkb0Rpc3Bvc2UpIHtcblx0XHRcdFx0YWRkQWxsVG9TZXQob3V0ZGF0ZWRNb2R1bGVzLCBbcmVzdWx0Lm1vZHVsZUlkXSk7XG5cdFx0XHRcdGFwcGxpZWRVcGRhdGVbbW9kdWxlSWRdID0gd2FyblVuZXhwZWN0ZWRSZXF1aXJlO1xuXHRcdFx0fVxuXHRcdH1cblx0fVxuXHRjdXJyZW50VXBkYXRlID0gdW5kZWZpbmVkO1xuXG5cdC8vIFN0b3JlIHNlbGYgYWNjZXB0ZWQgb3V0ZGF0ZWQgbW9kdWxlcyB0byByZXF1aXJlIHRoZW0gbGF0ZXIgYnkgdGhlIG1vZHVsZSBzeXN0ZW1cblx0dmFyIG91dGRhdGVkU2VsZkFjY2VwdGVkTW9kdWxlcyA9IFtdO1xuXHRmb3IgKHZhciBqID0gMDsgaiA8IG91dGRhdGVkTW9kdWxlcy5sZW5ndGg7IGorKykge1xuXHRcdHZhciBvdXRkYXRlZE1vZHVsZUlkID0gb3V0ZGF0ZWRNb2R1bGVzW2pdO1xuXHRcdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fLmNbb3V0ZGF0ZWRNb2R1bGVJZF07XG5cdFx0aWYgKFxuXHRcdFx0bW9kdWxlICYmXG5cdFx0XHQobW9kdWxlLmhvdC5fc2VsZkFjY2VwdGVkIHx8IG1vZHVsZS5ob3QuX21haW4pICYmXG5cdFx0XHQvLyByZW1vdmVkIHNlbGYtYWNjZXB0ZWQgbW9kdWxlcyBzaG91bGQgbm90IGJlIHJlcXVpcmVkXG5cdFx0XHRhcHBsaWVkVXBkYXRlW291dGRhdGVkTW9kdWxlSWRdICE9PSB3YXJuVW5leHBlY3RlZFJlcXVpcmUgJiZcblx0XHRcdC8vIHdoZW4gY2FsbGVkIGludmFsaWRhdGUgc2VsZi1hY2NlcHRpbmcgaXMgbm90IHBvc3NpYmxlXG5cdFx0XHQhbW9kdWxlLmhvdC5fc2VsZkludmFsaWRhdGVkXG5cdFx0KSB7XG5cdFx0XHRvdXRkYXRlZFNlbGZBY2NlcHRlZE1vZHVsZXMucHVzaCh7XG5cdFx0XHRcdG1vZHVsZTogb3V0ZGF0ZWRNb2R1bGVJZCxcblx0XHRcdFx0cmVxdWlyZTogbW9kdWxlLmhvdC5fcmVxdWlyZVNlbGYsXG5cdFx0XHRcdGVycm9ySGFuZGxlcjogbW9kdWxlLmhvdC5fc2VsZkFjY2VwdGVkXG5cdFx0XHR9KTtcblx0XHR9XG5cdH1cblxuXHR2YXIgbW9kdWxlT3V0ZGF0ZWREZXBlbmRlbmNpZXM7XG5cblx0cmV0dXJuIHtcblx0XHRkaXNwb3NlOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRjdXJyZW50VXBkYXRlUmVtb3ZlZENodW5rcy5mb3JFYWNoKGZ1bmN0aW9uIChjaHVua0lkKSB7XG5cdFx0XHRcdGRlbGV0ZSBpbnN0YWxsZWRDaHVua3NbY2h1bmtJZF07XG5cdFx0XHR9KTtcblx0XHRcdGN1cnJlbnRVcGRhdGVSZW1vdmVkQ2h1bmtzID0gdW5kZWZpbmVkO1xuXG5cdFx0XHR2YXIgaWR4O1xuXHRcdFx0dmFyIHF1ZXVlID0gb3V0ZGF0ZWRNb2R1bGVzLnNsaWNlKCk7XG5cdFx0XHR3aGlsZSAocXVldWUubGVuZ3RoID4gMCkge1xuXHRcdFx0XHR2YXIgbW9kdWxlSWQgPSBxdWV1ZS5wb3AoKTtcblx0XHRcdFx0dmFyIG1vZHVsZSA9IF9fd2VicGFja19yZXF1aXJlX18uY1ttb2R1bGVJZF07XG5cdFx0XHRcdGlmICghbW9kdWxlKSBjb250aW51ZTtcblxuXHRcdFx0XHR2YXIgZGF0YSA9IHt9O1xuXG5cdFx0XHRcdC8vIENhbGwgZGlzcG9zZSBoYW5kbGVyc1xuXHRcdFx0XHR2YXIgZGlzcG9zZUhhbmRsZXJzID0gbW9kdWxlLmhvdC5fZGlzcG9zZUhhbmRsZXJzO1xuXHRcdFx0XHRmb3IgKGogPSAwOyBqIDwgZGlzcG9zZUhhbmRsZXJzLmxlbmd0aDsgaisrKSB7XG5cdFx0XHRcdFx0ZGlzcG9zZUhhbmRsZXJzW2pdLmNhbGwobnVsbCwgZGF0YSk7XG5cdFx0XHRcdH1cblx0XHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5obXJEW21vZHVsZUlkXSA9IGRhdGE7XG5cblx0XHRcdFx0Ly8gZGlzYWJsZSBtb2R1bGUgKHRoaXMgZGlzYWJsZXMgcmVxdWlyZXMgZnJvbSB0aGlzIG1vZHVsZSlcblx0XHRcdFx0bW9kdWxlLmhvdC5hY3RpdmUgPSBmYWxzZTtcblxuXHRcdFx0XHQvLyByZW1vdmUgbW9kdWxlIGZyb20gY2FjaGVcblx0XHRcdFx0ZGVsZXRlIF9fd2VicGFja19yZXF1aXJlX18uY1ttb2R1bGVJZF07XG5cblx0XHRcdFx0Ly8gd2hlbiBkaXNwb3NpbmcgdGhlcmUgaXMgbm8gbmVlZCB0byBjYWxsIGRpc3Bvc2UgaGFuZGxlclxuXHRcdFx0XHRkZWxldGUgb3V0ZGF0ZWREZXBlbmRlbmNpZXNbbW9kdWxlSWRdO1xuXG5cdFx0XHRcdC8vIHJlbW92ZSBcInBhcmVudHNcIiByZWZlcmVuY2VzIGZyb20gYWxsIGNoaWxkcmVuXG5cdFx0XHRcdGZvciAoaiA9IDA7IGogPCBtb2R1bGUuY2hpbGRyZW4ubGVuZ3RoOyBqKyspIHtcblx0XHRcdFx0XHR2YXIgY2hpbGQgPSBfX3dlYnBhY2tfcmVxdWlyZV9fLmNbbW9kdWxlLmNoaWxkcmVuW2pdXTtcblx0XHRcdFx0XHRpZiAoIWNoaWxkKSBjb250aW51ZTtcblx0XHRcdFx0XHRpZHggPSBjaGlsZC5wYXJlbnRzLmluZGV4T2YobW9kdWxlSWQpO1xuXHRcdFx0XHRcdGlmIChpZHggPj0gMCkge1xuXHRcdFx0XHRcdFx0Y2hpbGQucGFyZW50cy5zcGxpY2UoaWR4LCAxKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly8gcmVtb3ZlIG91dGRhdGVkIGRlcGVuZGVuY3kgZnJvbSBtb2R1bGUgY2hpbGRyZW5cblx0XHRcdHZhciBkZXBlbmRlbmN5O1xuXHRcdFx0Zm9yICh2YXIgb3V0ZGF0ZWRNb2R1bGVJZCBpbiBvdXRkYXRlZERlcGVuZGVuY2llcykge1xuXHRcdFx0XHRpZiAoX193ZWJwYWNrX3JlcXVpcmVfXy5vKG91dGRhdGVkRGVwZW5kZW5jaWVzLCBvdXRkYXRlZE1vZHVsZUlkKSkge1xuXHRcdFx0XHRcdG1vZHVsZSA9IF9fd2VicGFja19yZXF1aXJlX18uY1tvdXRkYXRlZE1vZHVsZUlkXTtcblx0XHRcdFx0XHRpZiAobW9kdWxlKSB7XG5cdFx0XHRcdFx0XHRtb2R1bGVPdXRkYXRlZERlcGVuZGVuY2llcyA9XG5cdFx0XHRcdFx0XHRcdG91dGRhdGVkRGVwZW5kZW5jaWVzW291dGRhdGVkTW9kdWxlSWRdO1xuXHRcdFx0XHRcdFx0Zm9yIChqID0gMDsgaiA8IG1vZHVsZU91dGRhdGVkRGVwZW5kZW5jaWVzLmxlbmd0aDsgaisrKSB7XG5cdFx0XHRcdFx0XHRcdGRlcGVuZGVuY3kgPSBtb2R1bGVPdXRkYXRlZERlcGVuZGVuY2llc1tqXTtcblx0XHRcdFx0XHRcdFx0aWR4ID0gbW9kdWxlLmNoaWxkcmVuLmluZGV4T2YoZGVwZW5kZW5jeSk7XG5cdFx0XHRcdFx0XHRcdGlmIChpZHggPj0gMCkgbW9kdWxlLmNoaWxkcmVuLnNwbGljZShpZHgsIDEpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0sXG5cdFx0YXBwbHk6IGZ1bmN0aW9uIChyZXBvcnRFcnJvcikge1xuXHRcdFx0Ly8gaW5zZXJ0IG5ldyBjb2RlXG5cdFx0XHRmb3IgKHZhciB1cGRhdGVNb2R1bGVJZCBpbiBhcHBsaWVkVXBkYXRlKSB7XG5cdFx0XHRcdGlmIChfX3dlYnBhY2tfcmVxdWlyZV9fLm8oYXBwbGllZFVwZGF0ZSwgdXBkYXRlTW9kdWxlSWQpKSB7XG5cdFx0XHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tW3VwZGF0ZU1vZHVsZUlkXSA9IGFwcGxpZWRVcGRhdGVbdXBkYXRlTW9kdWxlSWRdO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cblx0XHRcdC8vIHJ1biBuZXcgcnVudGltZSBtb2R1bGVzXG5cdFx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGN1cnJlbnRVcGRhdGVSdW50aW1lLmxlbmd0aDsgaSsrKSB7XG5cdFx0XHRcdGN1cnJlbnRVcGRhdGVSdW50aW1lW2ldKF9fd2VicGFja19yZXF1aXJlX18pO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyBjYWxsIGFjY2VwdCBoYW5kbGVyc1xuXHRcdFx0Zm9yICh2YXIgb3V0ZGF0ZWRNb2R1bGVJZCBpbiBvdXRkYXRlZERlcGVuZGVuY2llcykge1xuXHRcdFx0XHRpZiAoX193ZWJwYWNrX3JlcXVpcmVfXy5vKG91dGRhdGVkRGVwZW5kZW5jaWVzLCBvdXRkYXRlZE1vZHVsZUlkKSkge1xuXHRcdFx0XHRcdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fLmNbb3V0ZGF0ZWRNb2R1bGVJZF07XG5cdFx0XHRcdFx0aWYgKG1vZHVsZSkge1xuXHRcdFx0XHRcdFx0bW9kdWxlT3V0ZGF0ZWREZXBlbmRlbmNpZXMgPVxuXHRcdFx0XHRcdFx0XHRvdXRkYXRlZERlcGVuZGVuY2llc1tvdXRkYXRlZE1vZHVsZUlkXTtcblx0XHRcdFx0XHRcdHZhciBjYWxsYmFja3MgPSBbXTtcblx0XHRcdFx0XHRcdHZhciBlcnJvckhhbmRsZXJzID0gW107XG5cdFx0XHRcdFx0XHR2YXIgZGVwZW5kZW5jaWVzRm9yQ2FsbGJhY2tzID0gW107XG5cdFx0XHRcdFx0XHRmb3IgKHZhciBqID0gMDsgaiA8IG1vZHVsZU91dGRhdGVkRGVwZW5kZW5jaWVzLmxlbmd0aDsgaisrKSB7XG5cdFx0XHRcdFx0XHRcdHZhciBkZXBlbmRlbmN5ID0gbW9kdWxlT3V0ZGF0ZWREZXBlbmRlbmNpZXNbal07XG5cdFx0XHRcdFx0XHRcdHZhciBhY2NlcHRDYWxsYmFjayA9XG5cdFx0XHRcdFx0XHRcdFx0bW9kdWxlLmhvdC5fYWNjZXB0ZWREZXBlbmRlbmNpZXNbZGVwZW5kZW5jeV07XG5cdFx0XHRcdFx0XHRcdHZhciBlcnJvckhhbmRsZXIgPVxuXHRcdFx0XHRcdFx0XHRcdG1vZHVsZS5ob3QuX2FjY2VwdGVkRXJyb3JIYW5kbGVyc1tkZXBlbmRlbmN5XTtcblx0XHRcdFx0XHRcdFx0aWYgKGFjY2VwdENhbGxiYWNrKSB7XG5cdFx0XHRcdFx0XHRcdFx0aWYgKGNhbGxiYWNrcy5pbmRleE9mKGFjY2VwdENhbGxiYWNrKSAhPT0gLTEpIGNvbnRpbnVlO1xuXHRcdFx0XHRcdFx0XHRcdGNhbGxiYWNrcy5wdXNoKGFjY2VwdENhbGxiYWNrKTtcblx0XHRcdFx0XHRcdFx0XHRlcnJvckhhbmRsZXJzLnB1c2goZXJyb3JIYW5kbGVyKTtcblx0XHRcdFx0XHRcdFx0XHRkZXBlbmRlbmNpZXNGb3JDYWxsYmFja3MucHVzaChkZXBlbmRlbmN5KTtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0Zm9yICh2YXIgayA9IDA7IGsgPCBjYWxsYmFja3MubGVuZ3RoOyBrKyspIHtcblx0XHRcdFx0XHRcdFx0dHJ5IHtcblx0XHRcdFx0XHRcdFx0XHRjYWxsYmFja3Nba10uY2FsbChudWxsLCBtb2R1bGVPdXRkYXRlZERlcGVuZGVuY2llcyk7XG5cdFx0XHRcdFx0XHRcdH0gY2F0Y2ggKGVycikge1xuXHRcdFx0XHRcdFx0XHRcdGlmICh0eXBlb2YgZXJyb3JIYW5kbGVyc1trXSA9PT0gXCJmdW5jdGlvblwiKSB7XG5cdFx0XHRcdFx0XHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRlcnJvckhhbmRsZXJzW2tdKGVyciwge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdG1vZHVsZUlkOiBvdXRkYXRlZE1vZHVsZUlkLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRlcGVuZGVuY3lJZDogZGVwZW5kZW5jaWVzRm9yQ2FsbGJhY2tzW2tdXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFx0fSBjYXRjaCAoZXJyMikge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRpZiAob3B0aW9ucy5vbkVycm9yZWQpIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRvcHRpb25zLm9uRXJyb3JlZCh7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR0eXBlOiBcImFjY2VwdC1lcnJvci1oYW5kbGVyLWVycm9yZWRcIixcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdG1vZHVsZUlkOiBvdXRkYXRlZE1vZHVsZUlkLFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGVwZW5kZW5jeUlkOiBkZXBlbmRlbmNpZXNGb3JDYWxsYmFja3Nba10sXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRlcnJvcjogZXJyMixcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdG9yaWdpbmFsRXJyb3I6IGVyclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGlmICghb3B0aW9ucy5pZ25vcmVFcnJvcmVkKSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyMik7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyKTtcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRpZiAob3B0aW9ucy5vbkVycm9yZWQpIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0b3B0aW9ucy5vbkVycm9yZWQoe1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdHR5cGU6IFwiYWNjZXB0LWVycm9yZWRcIixcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRtb2R1bGVJZDogb3V0ZGF0ZWRNb2R1bGVJZCxcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkZXBlbmRlbmN5SWQ6IGRlcGVuZGVuY2llc0ZvckNhbGxiYWNrc1trXSxcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRlcnJvcjogZXJyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdFx0aWYgKCFvcHRpb25zLmlnbm9yZUVycm9yZWQpIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyKTtcblx0XHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly8gTG9hZCBzZWxmIGFjY2VwdGVkIG1vZHVsZXNcblx0XHRcdGZvciAodmFyIG8gPSAwOyBvIDwgb3V0ZGF0ZWRTZWxmQWNjZXB0ZWRNb2R1bGVzLmxlbmd0aDsgbysrKSB7XG5cdFx0XHRcdHZhciBpdGVtID0gb3V0ZGF0ZWRTZWxmQWNjZXB0ZWRNb2R1bGVzW29dO1xuXHRcdFx0XHR2YXIgbW9kdWxlSWQgPSBpdGVtLm1vZHVsZTtcblx0XHRcdFx0dHJ5IHtcblx0XHRcdFx0XHRpdGVtLnJlcXVpcmUobW9kdWxlSWQpO1xuXHRcdFx0XHR9IGNhdGNoIChlcnIpIHtcblx0XHRcdFx0XHRpZiAodHlwZW9mIGl0ZW0uZXJyb3JIYW5kbGVyID09PSBcImZ1bmN0aW9uXCIpIHtcblx0XHRcdFx0XHRcdHRyeSB7XG5cdFx0XHRcdFx0XHRcdGl0ZW0uZXJyb3JIYW5kbGVyKGVyciwge1xuXHRcdFx0XHRcdFx0XHRcdG1vZHVsZUlkOiBtb2R1bGVJZCxcblx0XHRcdFx0XHRcdFx0XHRtb2R1bGU6IF9fd2VicGFja19yZXF1aXJlX18uY1ttb2R1bGVJZF1cblx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHR9IGNhdGNoIChlcnIyKSB7XG5cdFx0XHRcdFx0XHRcdGlmIChvcHRpb25zLm9uRXJyb3JlZCkge1xuXHRcdFx0XHRcdFx0XHRcdG9wdGlvbnMub25FcnJvcmVkKHtcblx0XHRcdFx0XHRcdFx0XHRcdHR5cGU6IFwic2VsZi1hY2NlcHQtZXJyb3ItaGFuZGxlci1lcnJvcmVkXCIsXG5cdFx0XHRcdFx0XHRcdFx0XHRtb2R1bGVJZDogbW9kdWxlSWQsXG5cdFx0XHRcdFx0XHRcdFx0XHRlcnJvcjogZXJyMixcblx0XHRcdFx0XHRcdFx0XHRcdG9yaWdpbmFsRXJyb3I6IGVyclxuXHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdGlmICghb3B0aW9ucy5pZ25vcmVFcnJvcmVkKSB7XG5cdFx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyMik7XG5cdFx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyKTtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRpZiAob3B0aW9ucy5vbkVycm9yZWQpIHtcblx0XHRcdFx0XHRcdFx0b3B0aW9ucy5vbkVycm9yZWQoe1xuXHRcdFx0XHRcdFx0XHRcdHR5cGU6IFwic2VsZi1hY2NlcHQtZXJyb3JlZFwiLFxuXHRcdFx0XHRcdFx0XHRcdG1vZHVsZUlkOiBtb2R1bGVJZCxcblx0XHRcdFx0XHRcdFx0XHRlcnJvcjogZXJyXG5cdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0aWYgKCFvcHRpb25zLmlnbm9yZUVycm9yZWQpIHtcblx0XHRcdFx0XHRcdFx0cmVwb3J0RXJyb3IoZXJyKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIG91dGRhdGVkTW9kdWxlcztcblx0XHR9XG5cdH07XG59XG5fX3dlYnBhY2tfcmVxdWlyZV9fLmhtckkuanNvbnAgPSBmdW5jdGlvbiAobW9kdWxlSWQsIGFwcGx5SGFuZGxlcnMpIHtcblx0aWYgKCFjdXJyZW50VXBkYXRlKSB7XG5cdFx0Y3VycmVudFVwZGF0ZSA9IHt9O1xuXHRcdGN1cnJlbnRVcGRhdGVSdW50aW1lID0gW107XG5cdFx0Y3VycmVudFVwZGF0ZVJlbW92ZWRDaHVua3MgPSBbXTtcblx0XHRhcHBseUhhbmRsZXJzLnB1c2goYXBwbHlIYW5kbGVyKTtcblx0fVxuXHRpZiAoIV9fd2VicGFja19yZXF1aXJlX18ubyhjdXJyZW50VXBkYXRlLCBtb2R1bGVJZCkpIHtcblx0XHRjdXJyZW50VXBkYXRlW21vZHVsZUlkXSA9IF9fd2VicGFja19yZXF1aXJlX18ubVttb2R1bGVJZF07XG5cdH1cbn07XG5fX3dlYnBhY2tfcmVxdWlyZV9fLmhtckMuanNvbnAgPSBmdW5jdGlvbiAoXG5cdGNodW5rSWRzLFxuXHRyZW1vdmVkQ2h1bmtzLFxuXHRyZW1vdmVkTW9kdWxlcyxcblx0cHJvbWlzZXMsXG5cdGFwcGx5SGFuZGxlcnMsXG5cdHVwZGF0ZWRNb2R1bGVzTGlzdFxuKSB7XG5cdGFwcGx5SGFuZGxlcnMucHVzaChhcHBseUhhbmRsZXIpO1xuXHRjdXJyZW50VXBkYXRlQ2h1bmtzID0ge307XG5cdGN1cnJlbnRVcGRhdGVSZW1vdmVkQ2h1bmtzID0gcmVtb3ZlZENodW5rcztcblx0Y3VycmVudFVwZGF0ZSA9IHJlbW92ZWRNb2R1bGVzLnJlZHVjZShmdW5jdGlvbiAob2JqLCBrZXkpIHtcblx0XHRvYmpba2V5XSA9IGZhbHNlO1xuXHRcdHJldHVybiBvYmo7XG5cdH0sIHt9KTtcblx0Y3VycmVudFVwZGF0ZVJ1bnRpbWUgPSBbXTtcblx0Y2h1bmtJZHMuZm9yRWFjaChmdW5jdGlvbiAoY2h1bmtJZCkge1xuXHRcdGlmIChcblx0XHRcdF9fd2VicGFja19yZXF1aXJlX18ubyhpbnN0YWxsZWRDaHVua3MsIGNodW5rSWQpICYmXG5cdFx0XHRpbnN0YWxsZWRDaHVua3NbY2h1bmtJZF0gIT09IHVuZGVmaW5lZFxuXHRcdCkge1xuXHRcdFx0cHJvbWlzZXMucHVzaChsb2FkVXBkYXRlQ2h1bmsoY2h1bmtJZCwgdXBkYXRlZE1vZHVsZXNMaXN0KSk7XG5cdFx0XHRjdXJyZW50VXBkYXRlQ2h1bmtzW2NodW5rSWRdID0gdHJ1ZTtcblx0XHR9XG5cdH0pO1xuXHRpZiAoX193ZWJwYWNrX3JlcXVpcmVfXy5mKSB7XG5cdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5mLmpzb25wSG1yID0gZnVuY3Rpb24gKGNodW5rSWQsIHByb21pc2VzKSB7XG5cdFx0XHRpZiAoXG5cdFx0XHRcdGN1cnJlbnRVcGRhdGVDaHVua3MgJiZcblx0XHRcdFx0IV9fd2VicGFja19yZXF1aXJlX18ubyhjdXJyZW50VXBkYXRlQ2h1bmtzLCBjaHVua0lkKSAmJlxuXHRcdFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8oaW5zdGFsbGVkQ2h1bmtzLCBjaHVua0lkKSAmJlxuXHRcdFx0XHRpbnN0YWxsZWRDaHVua3NbY2h1bmtJZF0gIT09IHVuZGVmaW5lZFxuXHRcdFx0KSB7XG5cdFx0XHRcdHByb21pc2VzLnB1c2gobG9hZFVwZGF0ZUNodW5rKGNodW5rSWQpKTtcblx0XHRcdFx0Y3VycmVudFVwZGF0ZUNodW5rc1tjaHVua0lkXSA9IHRydWU7XG5cdFx0XHR9XG5cdFx0fTtcblx0fVxufTtcblxuX193ZWJwYWNrX3JlcXVpcmVfXy5obXJNID0gKCkgPT4ge1xuXHRpZiAodHlwZW9mIGZldGNoID09PSBcInVuZGVmaW5lZFwiKSB0aHJvdyBuZXcgRXJyb3IoXCJObyBicm93c2VyIHN1cHBvcnQ6IG5lZWQgZmV0Y2ggQVBJXCIpO1xuXHRyZXR1cm4gZmV0Y2goX193ZWJwYWNrX3JlcXVpcmVfXy5wICsgX193ZWJwYWNrX3JlcXVpcmVfXy5obXJGKCkpLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG5cdFx0aWYocmVzcG9uc2Uuc3RhdHVzID09PSA0MDQpIHJldHVybjsgLy8gbm8gdXBkYXRlIGF2YWlsYWJsZVxuXHRcdGlmKCFyZXNwb25zZS5vaykgdGhyb3cgbmV3IEVycm9yKFwiRmFpbGVkIHRvIGZldGNoIHVwZGF0ZSBtYW5pZmVzdCBcIiArIHJlc3BvbnNlLnN0YXR1c1RleHQpO1xuXHRcdHJldHVybiByZXNwb25zZS5qc29uKCk7XG5cdH0pO1xufTtcblxuLy8gbm8gb24gY2h1bmtzIGxvYWRlZFxuXG4vLyBubyBqc29ucCBmdW5jdGlvbiIsIi8vIG1vZHVsZSBjYWNoZSBhcmUgdXNlZCBzbyBlbnRyeSBpbmxpbmluZyBpcyBkaXNhYmxlZFxuLy8gc3RhcnR1cFxuLy8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG52YXIgX193ZWJwYWNrX2V4cG9ydHNfXyA9IF9fd2VicGFja19yZXF1aXJlX18oMzEyKTtcbiJdLCJuYW1lcyI6WyJpblByb2dyZXNzIiwiZGF0YVdlYnBhY2tQcmVmaXgiLCJ3ZWJzb2NrZXRQb3J0cyIsInJlYWxtb25pdG9yIiwicGxheWJhY2siLCJyZWFsbW9uaXRvcl93cyIsInBsYXliYWNrX3dzIiwiZXJyb3JWaWRlb0luZm8iLCJkZWZhdWx0RXJyb3JNc2ciLCJlcnJvckluZm8iLCJQbGF5ZXJJdGVtIiwib3B0IiwidGhpcyIsIiRlbCIsImNhbnZhc0VsZW0iLCJ2aWRlb0VsZW0iLCJkb21JZCIsIndyYXBwZXJEb21JZCIsImluZGV4Iiwid3NQbGF5ZXIiLCJmaXJzdFRpbWUiLCJpc0F1ZGlvUGxheSIsInNwZWVkIiwidGVtcGxhdGUiLCJnZXRUZW1wbGF0ZSIsInBsYXllciIsIiQiLCIkd3JhcHBlciIsImFwcGVuZCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJjYW52YXNJZCIsIml2c0NhbnZhc0VsZW0iLCJpdnNDYW52YXNJZCIsInB6dENhbnZhc0VsZW0iLCJwenRDYW52YXNJZCIsInZpZGVvSWQiLCJzaG93SWNvbnMiLCJjb25maWciLCJzdHJlYW1DaGFuZ2VTZWxlY3QiLCJjc3MiLCJkaXNwbGF5IiwidGFsa0ljb24iLCJ0eXBlIiwiYXVkaW9JY29uIiwic25hcHNob3RJY29uIiwibG9jYWxSZWNvcmRJY29uIiwiY2xvc2VJY29uIiwiY2xpY2siLCJldnQiLCJzZXRTZWxlY3RJbmRleCIsInNpYmxpbmdzIiwicmVtb3ZlQ2xhc3MiLCJhZGRDbGFzcyIsImRibGNsaWNrIiwib3B0aW9ucyIsIm1heE51bSIsImhhc0NsYXNzIiwic2V0UGxheWVyTnVtIiwiYmVmb3JlU2hvd051bSIsInNob3dOdW0iLCJpc1RhbGtpbmciLCJzZW5kRXJyb3JNZXNzYWdlIiwic2V0QXVkaW9Wb2x1bWUiLCJ0YXJnZXQiLCJpc1BsYXliYWNrIiwicmVzdW1lQXVkaW8iLCJzdG9wVGFsayIsInRhbGtJbmRleCIsIl9fc3RhcnRUYWxrIiwiY2hhbm5lbERhdGEiLCJjYXB0dXJlUGljIiwiY2xvc2UiLCJjaGFubmVsTmFtZSIsIm5hbWUiLCJpc1JlY29yZGluZyIsInN0b3BMb2NhbFJlY29yZCIsInN0YXR1cyIsInN0YXJ0TG9jYWxSZWNvcmQiLCJEYXRlIiwibm93Iiwid2luZG93Iiwid3NBdWRpb1BsYXllciIsIm1hbnVhbFJlc3VtZSIsImludGVydmFsSWQiLCJzZXRJbnRlcnZhbCIsImNsZWFySW50ZXJ2YWwiLCJwbGF5Iiwic2V0U3RhdHVzIiwicGF1c2UiLCJjaGFuZ2VWaWRlb0ZsYWciLCJ3c1BsYXllck1hbmFnZXIiLCJ1bmJpbmRQbGF5ZXIiLCJuUGxheVBvcnQiLCJ2aWRlb0Nsb3NlZCIsInNldERvbVZpc2libGUiLCJzdHlsZSIsInNlbGVjdEluZGV4Iiwic2V0VGltZUxpbmUiLCJfX3NldFBsYXlTcGVlZCIsIm9wZW5JdnMiLCJjbG9zZUlWUyIsInNwaW5uZXIiLCJzdG9wIiwiY2FwdHVyZSIsImRvbSIsInZpc2libGUiLCJ2aXNpYmlsaXR5IiwicGxheWVyQWRhcHRlciIsImUiLCJyYXRpbyIsIndpZHRoIiwiaGVpZ2h0IiwiZWwiLCJkZWNvZGVNb2RlIiwiZWxQYXJlbnQiLCJwYXJlbnROb2RlIiwiZWxQYXJlbnRIZWlnaHQiLCJvZmZzZXRIZWlnaHQiLCJlbFBhcmVudFdpZHRoIiwib2Zmc2V0V2lkdGgiLCJlbFJhdGlvIiwic2V0SVZTQ2FudmFzU2l6ZSIsIl9fYXNzaWduIiwiT2JqZWN0IiwiYXNzaWduIiwidCIsInMiLCJpIiwibiIsImFyZ3VtZW50cyIsImxlbmd0aCIsInAiLCJwcm90b3R5cGUiLCJoYXNPd25Qcm9wZXJ0eSIsImNhbGwiLCJhcHBseSIsImRlZmF1bHRzIiwibGluZXMiLCJyYWRpdXMiLCJzY2FsZSIsImNvcm5lcnMiLCJjb2xvciIsImZhZGVDb2xvciIsImFuaW1hdGlvbiIsInJvdGF0ZSIsImRpcmVjdGlvbiIsInpJbmRleCIsImNsYXNzTmFtZSIsInRvcCIsImxlZnQiLCJzaGFkb3ciLCJwb3NpdGlvbiIsIlNwaW5uZXIiLCJvcHRzIiwic3BpbiIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJ0cmFuc2Zvcm0iLCJpbnNlcnRCZWZvcmUiLCJmaXJzdENoaWxkIiwiYm9yZGVyUmFkaXVzIiwiTWF0aCIsInJvdW5kIiwic2hhZG93cyIsImJveFNoYWRvdyIsInJlZ2V4IiwiX2kiLCJfYSIsInNwbGl0IiwibWF0Y2hlcyIsIm1hdGNoIiwieCIsInkiLCJ4VW5pdHMiLCJ5VW5pdHMiLCJwdXNoIiwicHJlZml4IiwiZW5kIiwicGFyc2VCb3hTaGFkb3ciLCJkZWdyZWVzIiwiYmFja2dyb3VuZExpbmUiLCJiYWNrZ3JvdW5kIiwiZ2V0Q29sb3IiLCJ0cmFuc2Zvcm1PcmlnaW4iLCJkZWxheSIsImxpbmUiLCJub3JtYWxpemVTaGFkb3ciLCJhcHBlbmRDaGlsZCIsImRyYXdMaW5lcyIsInJlcXVlc3RBbmltYXRpb25GcmFtZSIsImNhbmNlbEFuaW1hdGlvbkZyYW1lIiwiYW5pbWF0ZUlkIiwiY2xlYXJUaW1lb3V0IiwicmVtb3ZlQ2hpbGQiLCJ1bmRlZmluZWQiLCJwcm9wcyIsInByb3AiLCJpZHgiLCJub3JtYWxpemVkIiwic2hhZG93c18xIiwieHkiLCJjb252ZXJ0T2Zmc2V0Iiwiam9pbiIsInJhZGlhbnMiLCJQSSIsInNpbiIsImNvcyIsIlBsYXllckNvbnRyb2wiLCJSZWFsUGxheWVySXRlbSIsImluaXREb20iLCJkZWZhdWx0U3RhdHVzIiwiZXJyb3IiLCJjb250cm9sbGVyIiwiaW5pdE1vdXNlRXZlbnQiLCJzZWxmIiwiaGlkZVRpbWVyIiwib24iLCJpbmNsdWRlcyIsInNldFRpbWVvdXQiLCJoaWRlIiwic3RyZWFtU2VsZWN0U2hvdyIsInNob3ciLCJzdHJlYW1UeXBlVmFsdWUiLCJnZXRBdHRyaWJ1dGUiLCJzdHJlYW1UeXBlIiwiY2hhbmdlU3RyZWFtVHlwZSIsImNoaWxkcmVuIiwidGV4dCIsImF0dHIiLCJtc2ciLCJzZW5kTWVzc2FnZSIsIndpbmRvd0luZGV4Iiwic3JjIiwiQ09OU1RBTlQiLCJlcnJvckNvZGUiLCJtX25Nb2R1bGVJbml0aWFsaXplZCIsInNldFN0cmVhbVR5cGUiLCJjcmVhdGVQbGF5ZXIiLCJjb25zb2xlIiwidXBkYXRlQWRhcHRlciIsImVuY29kZU1vZGUiLCJ1c2VIMjY0TVNFIiwidXNlSDI2NU1TRSIsIndzVVJMIiwicnRzcFVSTCIsImV2ZW50cyIsIlBsYXlTdGFydCIsImxvZyIsIkRlY29kZVN0YXJ0Iiwic3RhcnRQbGF5IiwiR2V0RnJhbWVSYXRlIiwiRXJyb3IiLCJ3cyIsInN5bWJvbCIsIkpTT04iLCJzdHJpbmdpZnkiLCJGaWxlT3ZlciIsIlVwZGF0ZVBsYXlpbmdUaW1lIiwiaW5pdCIsImNvbm5lY3QiLCJvcGVuSVZTIiwiYmluZFBsYXllciIsInRhbGtQbGF5ZXIiLCJfX2dldFdTVXJsIiwic2VydmVySXAiLCJpc1RhbGtTZXJ2aWNlIiwidGFsayIsIlJlY29yZFBsYXllckl0ZW0iLCJjdXJUaW1lc3RhbXAiLCJ0aW1lSW5mbyIsInByb2dyZXNzQmFyIiwib2Zmc2V0WCIsImF1dG9QYXVzZSIsInBsYXlOZXh0UmVjb3JkIiwidGltZVN0YW1wIiwic2V0UGxheWluZ1RpbWUiLCJ0aW1lTG9uZyIsImVuZFRpbWUiLCJzdGFydFRpbWUiLCJzZWNvbmRzIiwibWludXRlcyIsInBhcnNlSW50IiwiaG91cnMiLCJ0aW1lTG9uZ1N0ciIsInBsYXlTcGVlZCIsIldTUGxheWVyTWFuYWdlciIsIndzUGxheWVyTGlzdCIsInBvcnRUb1BsYXllciIsImNQbHVzVmlzaWJsZURlY0NhbGxCYWNrIiwiYmluZCIsImNFeHRyYURyYXdEYXRhQ2FsbEJhY2siLCJjRXh0cmFEcmF3RHJhd0NhbGxCYWNrIiwiblBvcnQiLCJwQnVmWSIsInBCdWZVIiwicEJ1ZlYiLCJuU2l6ZSIsInBGcmFtZUluZm8iLCJzZXRGcmFtZURhdGEiLCJuRGF0YVR5cGUiLCJwRHJhd0RhdGEiLCJuRGF0YUxlbiIsInNldElWU0RhdGEiLCJkcmF3SVZTRGF0YSIsImZpbHRlciIsIml0ZW0iLCJ1c2VyQWdlbnRLZXkiLCJ2YWxpZE9iamVjdCIsIm9iaiIsInRvU3RyaW5nIiwidXNlckFnZW50IiwiYnJvd3NlclR5cGUiLCJuYXZpZ2F0b3IiLCJicm93c2VyQml0IiwiYnJvd3NlclZlcnNpb24iLCJzbGljZSIsImdldEJyb3dzZXJWZXJzaW9uIiwiaXNWZXJzaW9uQ29tcGxpYW5jZSIsIm1lcmdlT2JqZWN0Iiwic291cmNlIiwidmFsdWUiLCJQYW5UaWx0IiwiX19jcmVhdGVQYW5UaWx0IiwiY2hhbm5lbCIsInNldFB0ekRpcmVjdGlvbiIsInNldFB0ekNhbWVyYSIsImNvbnRyb2xTaXRQb3NpdGlvbiIsIm1vdXNlZG93bkNhbnZhc0V2ZW50IiwiX19tb3VzZWRvd25DYW52YXNFdmVudCIsIm1vdXNlbW92ZUNhbnZhc0V2ZW50IiwiX19tb3VzZW1vdmVDYW52YXNFdmVudCIsIm1vdXNldXBDYW52YXNFdmVudCIsIl9fbW91c2V1cENhbnZhc0V2ZW50IiwiX19yZW1vdmVDYW52YXNFdmVudCIsImNhcGFiaWxpdHkiLCJjYW1lcmFUeXBlIiwibW91c2V1cCIsIl9fc2V0UHR6RGlyZWN0aW9uIiwibW91c2Vkb3duIiwiX19zZXRQdHpDYW1lcmEiLCJfX29wZW5TaXRQb3NpdGlvbiIsImRpcmVjdCIsImNvbW1hbmQiLCJwYXJhbXMiLCJwcm9qZWN0IiwibWV0aG9kIiwiZGF0YSIsInN0ZXBYIiwic3RlcFkiLCJjaGFubmVsSWQiLCJpZCIsInRoZW4iLCJlcnIiLCJvcGVyYXRlVHlwZSIsInN0ZXAiLCJvcGVuU2l0UG9zaXRpb25GbGFnIiwicGxheWVyTGlzdCIsImFkZEV2ZW50TGlzdGVuZXIiLCJjYW52YXNDb250ZXh0IiwiZ2V0Q29udGV4dCIsImxpbmVXaWR0aCIsInN0cm9rZVN0eWxlIiwibGF5ZXJYIiwicG9pbnRYIiwicG9pbnRZIiwib2Zmc2V0WSIsImxheWVyWSIsInN0YXJ0RHJhdyIsInJlYWN0VyIsInJlYWN0SCIsImNsZWFyUmVjdCIsImJlZ2luUGF0aCIsInN0cm9rZVJlY3QiLCJkUG9pbnRYIiwiZFBvaW50WSIsImRQb2ludFoiLCJjYW52YXNDZW50ZXJYIiwiY2FudmFzQ2VudGVyWSIsInZpZGVvQ2VudGVyWCIsInZpZGVvQ2VudGVyWSIsImFicyIsImJSZXZlcnNlIiwiX19jb250cm9sU2l0UG9zaXRpb24iLCJyZW1vdmVFdmVudExpc3RlbmVyIiwibWFnaWNJZCIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJTdHJpbmciLCJwb2ludFoiLCJleHRlbmQiLCJyZXMiLCJudW0iLCJzaG93Q29udHJvbCIsImlzRHluYW1pY0xvYWRMaWIiLCJvbmx5TG9hZFNpbmdsZUxpYiIsInVzZU5naW54UHJveHkiLCJXU1BsYXllciIsInV0aWxzIiwibG9jYXRpb24iLCJob3N0bmFtZSIsImZldGNoQ2hhbm5lbEF1dGhvcml0eSIsImdldENoYW5uZWxBdXRob3JpdHkiLCJjYW52YXMiLCJjdHgiLCJtYXhXaW5kb3ciLCJyZWNlaXZlTWVzc2FnZUZyb21XU1BsYXllciIsImlzSHR0cHMiLCJwcm90b2NvbCIsImxvYWRMaWJESFBsYXkiLCJzZXRNYXhXaW5kb3ciLCJjcmVhdGVSZWFsUGxheWVyIiwiY3JlYXRlUmVjb3JkUGxheWVyIiwic2V0Q2FudmFzR2V0Q29udGV4dCIsImJpbmRVcGRhdGVQbGF5ZXJXaW5kb3ciLCJfX3VwZGF0ZVBsYXllcldpbmRvdyIsIm9yaWdGbiIsIndzQ2FudmFzR2V0Q29udGV4dFNldCIsIkhUTUxDYW52YXNFbGVtZW50IiwiYXR0cmlidXRlcyIsInByZXNlcnZlRHJhd2luZ0J1ZmZlciIsIl9tYXhOdW0iLCJfX2FkZFJlYWxDb250cm9sIiwiQXJyYXkiLCJmaWxsIiwiZm9yRWFjaCIsInJlYWxQbGF5ZXJJdGVtIiwiX19hZGRSZWNvcmRDb250cm9sIiwicmVjb3JkUGxheWVySXRlbSIsImhlYWQiLCJsb2FkTGliREhQbGF5ZXJGbGFnIiwibGliUGF0aCIsIlNoYXJlZEFycmF5QnVmZmVyIiwibG9hZFNjcmlwdCIsInNldFB0ekNoYW5uZWwiLCJ3YXJuIiwicHJvY2VkdXJlIiwic2V0UGxheUluZGV4IiwiY2hhbmdlVGltZUxpbmUiLCJfX3VwZGF0ZVZvaWNlIiwibnVtYmVyIiwiX251bWJlciIsInRpbWVMaXN0IiwiX19zZXRUaW1lUmVjb3JkQXJlYSIsInJlcXVlc3RGdWxsc2NyZWVuIiwid2Via2l0UmVxdWVzdEZ1bGxzY3JlZW4iLCJtb3pSZXF1ZXN0RnVsbFNjcmVlbiIsIm1zUmVxdWVzdEZ1bGxzY3JlZW4iLCJfaW5kZXgiLCJOdW1iZXIiLCJwbGF5ZXJJdGVtIiwic2V0RnVsbFNjcmVlbiIsInNlbGZBZGFwdGlvblNlbGVjdFNob3ciLCJzZXRQbGF5ZXJBZGFwdGVyIiwib3B0aW9uIiwic2V0U3BlZWRJdGVtIiwic2V0U3BlZWRJbmRleCIsInNwZWVkTGlzdCIsImxhYmVsIiwic29tZSIsImN1cnNvciIsIndzVGltZUdyb3VwMSIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJ3c1RpbWVHcm91cDIiLCJwYWRTdGFydCIsIm1vdXNlZW50ZXIiLCJtb3VzZW1vdmUiLCJjbGllbnRYIiwiZ2V0Qm91bmRpbmdDbGllbnRSZWN0IiwiZGF0ZSIsImdldEhvdXJzIiwiZ2V0TWludXRlcyIsImdldFNlY29uZHMiLCJ0aW1lIiwibW91c2VsZWF2ZSIsInJlbW92ZSIsInNldEhvdXJzIiwidGltZUl0ZW0iLCJjbGlja1JlY29yZFRpbWVMaW5lIiwiYm94V2lkdGgiLCJyZWNvcmRMaXN0IiwiYWxhcm1SZWNvcmRMaXN0IiwicmVjb3JkR3JhZGllbnQiLCJjcmVhdGVMaW5lYXJHcmFkaWVudCIsImFkZENvbG9yU3RvcCIsImFsYXJtUmVjb3JkR3JhZGllbnQiLCJpc0ltcG9ydGFudCIsImZpbGxTdHlsZSIsImZpbGxSZWN0IiwicnRzcFVybCIsImlwIiwid3NzUG9ydCIsIndzUG9ydCIsInBsYXllcldyYXBwZXIiLCJjaG9vc2VGbGFnIiwic3RhcnRUYWxrIiwicGxheVJlYWxWaWRlbyIsImdldFJlY29yZExpc3QiLCJqdW1wUGxheUJ5VGltZSIsImNoYW5uZWxMaXN0IiwicGFuVGlsdCIsInNldENoYW5uZWwiLCJXU1BsYXllckNvbnN0cnVjdG9yIiwiUGxheWVyTWFuYWdlciIsInJlYWxQbGF5ZXIiLCJyZWNvcmRQbGF5ZXIiLCJwbGF5TnVtIiwicGxheUluZGV4IiwiY3VycmVudENoYW5uZWxJZCIsImluaXRSZWFsUGxheWVyIiwiaW5pdFJlY29yZFBsYXllciIsIl9fcmVjZWl2ZU1lc3NhZ2VGcm9tV1NQbGF5ZXIiLCJwbGF5UmVhbCIsInBsYXlSZWNvcmQiLCJQTEFZRVJfQk9YIiwiX29wdGlvbnMiLCJwbGF5ZXJUeXBlIiwic2VydmVyUG9ydCIsInBsYXllck1hbmFnZXIiLCJfZG9tYWluIiwic2V0T3B0aW9ucyIsInBsYXlSZWNvcmRWaWRlbyIsImNvbnRpbnVlUGxheSIsInNlbmRDdXJyZW50VGltZSIsInNlbmRyZWNvcmRQbGF5RW5kIiwiZ2V0RXJyb3IiLCJwbGF5Qm94X3RpbWUiLCJwYXJlbnQiLCJwb3N0TWVzc2FnZSIsImZ1bk5hbWUiLCJnZXRVcmxQYXJhbXMiLCJfc2xlZiIsImV2ZW50UmVzdWx0IiwicmVzdWx0IiwiYXJyYXkiLCJzZWFyY2giLCJuZXdBcnIiLCJfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX18iLCJfX3dlYnBhY2tfcmVxdWlyZV9fIiwibW9kdWxlSWQiLCJjYWNoZWRNb2R1bGUiLCJleHBvcnRzIiwibW9kdWxlIiwiZXhlY09wdGlvbnMiLCJmYWN0b3J5IiwiX193ZWJwYWNrX21vZHVsZXNfXyIsInJlcXVpcmUiLCJoYW5kbGVyIiwibSIsImMiLCJodSIsImNodW5rSWQiLCJoIiwiaG1yRiIsImciLCJnbG9iYWxUaGlzIiwiRnVuY3Rpb24iLCJvIiwibCIsInVybCIsImRvbmUiLCJrZXkiLCJzY3JpcHQiLCJuZWVkQXR0YWNoIiwic2NyaXB0cyIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwiY2hhcnNldCIsInRpbWVvdXQiLCJuYyIsIm9uU2NyaXB0Q29tcGxldGUiLCJwcmV2IiwiZXZlbnQiLCJvbmVycm9yIiwib25sb2FkIiwiZG9uZUZucyIsImZuIiwiY3VycmVudENoaWxkTW9kdWxlIiwiYmxvY2tpbmdQcm9taXNlcyIsImN1cnJlbnRVcGRhdGVBcHBseUhhbmRsZXJzIiwicXVldWVkSW52YWxpZGF0ZWRNb2R1bGVzIiwiY3VycmVudE1vZHVsZURhdGEiLCJpbnN0YWxsZWRNb2R1bGVzIiwiY3VycmVudFBhcmVudHMiLCJyZWdpc3RlcmVkU3RhdHVzSGFuZGxlcnMiLCJjdXJyZW50U3RhdHVzIiwibmV3U3RhdHVzIiwicmVzdWx0cyIsIlByb21pc2UiLCJhbGwiLCJ3YWl0Rm9yQmxvY2tpbmdQcm9taXNlcyIsImJsb2NrZXIiLCJob3RDaGVjayIsImFwcGx5T25VcGRhdGUiLCJobXJNIiwidXBkYXRlIiwidXBkYXRlZE1vZHVsZXMiLCJrZXlzIiwiaG1yQyIsInJlZHVjZSIsInByb21pc2VzIiwiciIsImludGVybmFsQXBwbHkiLCJhcHBseUludmFsaWRhdGVkTW9kdWxlcyIsImhvdEFwcGx5IiwicmVzb2x2ZSIsIm1hcCIsImVycm9ycyIsIkJvb2xlYW4iLCJkaXNwb3NlUHJvbWlzZSIsImRpc3Bvc2UiLCJhcHBseVByb21pc2UiLCJyZXBvcnRFcnJvciIsIm91dGRhdGVkTW9kdWxlcyIsIm1vZHVsZXMiLCJsaXN0IiwiaW5kZXhPZiIsImhtckkiLCJobXJEIiwibWUiLCJfbWFpbiIsImhvdCIsInJlcXVlc3QiLCJhY3RpdmUiLCJwYXJlbnRzIiwiY3JlYXRlUHJvcGVydHlEZXNjcmlwdG9yIiwiY29uZmlndXJhYmxlIiwiZW51bWVyYWJsZSIsImdldCIsInNldCIsImRlZmluZVByb3BlcnR5IiwicHJvbWlzZSIsInRyYWNrQmxvY2tpbmdQcm9taXNlIiwiY3JlYXRlUmVxdWlyZSIsIl9hY2NlcHRlZERlcGVuZGVuY2llcyIsIl9hY2NlcHRlZEVycm9ySGFuZGxlcnMiLCJfZGVjbGluZWREZXBlbmRlbmNpZXMiLCJfc2VsZkFjY2VwdGVkIiwiX3NlbGZEZWNsaW5lZCIsIl9zZWxmSW52YWxpZGF0ZWQiLCJfZGlzcG9zZUhhbmRsZXJzIiwiX3JlcXVpcmVTZWxmIiwiYWNjZXB0IiwiZGVwIiwiY2FsbGJhY2siLCJlcnJvckhhbmRsZXIiLCJkZWNsaW5lIiwiYWRkRGlzcG9zZUhhbmRsZXIiLCJyZW1vdmVEaXNwb3NlSGFuZGxlciIsInNwbGljZSIsImludmFsaWRhdGUiLCJjaGVjayIsImFkZFN0YXR1c0hhbmRsZXIiLCJyZW1vdmVTdGF0dXNIYW5kbGVyIiwic2NyaXB0VXJsIiwiaW1wb3J0U2NyaXB0cyIsImN1cnJlbnRTY3JpcHQiLCJyZXBsYWNlIiwiY3VycmVudFVwZGF0ZUNodW5rcyIsImN1cnJlbnRVcGRhdGUiLCJjdXJyZW50VXBkYXRlUmVtb3ZlZENodW5rcyIsImN1cnJlbnRVcGRhdGVSdW50aW1lIiwiaW5zdGFsbGVkQ2h1bmtzIiwiaG1yU19qc29ucCIsIndhaXRpbmdVcGRhdGVSZXNvbHZlcyIsImxvYWRVcGRhdGVDaHVuayIsInJlamVjdCIsImVycm9yVHlwZSIsInJlYWxTcmMiLCJtZXNzYWdlIiwiYXBwbHlIYW5kbGVyIiwiZ2V0QWZmZWN0ZWRNb2R1bGVFZmZlY3RzIiwidXBkYXRlTW9kdWxlSWQiLCJvdXRkYXRlZERlcGVuZGVuY2llcyIsInF1ZXVlIiwiY2hhaW4iLCJxdWV1ZUl0ZW0iLCJwb3AiLCJwYXJlbnRJZCIsImNvbmNhdCIsImFkZEFsbFRvU2V0IiwiYSIsImIiLCJmIiwianNvbnBIbXIiLCJhcHBsaWVkVXBkYXRlIiwid2FyblVuZXhwZWN0ZWRSZXF1aXJlIiwibmV3TW9kdWxlRmFjdG9yeSIsImFib3J0RXJyb3IiLCJkb0FwcGx5IiwiZG9EaXNwb3NlIiwiY2hhaW5JbmZvIiwib25EZWNsaW5lZCIsImlnbm9yZURlY2xpbmVkIiwib25VbmFjY2VwdGVkIiwiaWdub3JlVW5hY2NlcHRlZCIsIm9uQWNjZXB0ZWQiLCJvbkRpc3Bvc2VkIiwibW9kdWxlT3V0ZGF0ZWREZXBlbmRlbmNpZXMiLCJvdXRkYXRlZFNlbGZBY2NlcHRlZE1vZHVsZXMiLCJqIiwib3V0ZGF0ZWRNb2R1bGVJZCIsImRlcGVuZGVuY3kiLCJkaXNwb3NlSGFuZGxlcnMiLCJjaGlsZCIsImNhbGxiYWNrcyIsImVycm9ySGFuZGxlcnMiLCJkZXBlbmRlbmNpZXNGb3JDYWxsYmFja3MiLCJhY2NlcHRDYWxsYmFjayIsImsiLCJkZXBlbmRlbmN5SWQiLCJlcnIyIiwib25FcnJvcmVkIiwib3JpZ2luYWxFcnJvciIsImlnbm9yZUVycm9yZWQiLCJtb3JlTW9kdWxlcyIsInJ1bnRpbWUiLCJqc29ucCIsImFwcGx5SGFuZGxlcnMiLCJjaHVua0lkcyIsInJlbW92ZWRDaHVua3MiLCJyZW1vdmVkTW9kdWxlcyIsInVwZGF0ZWRNb2R1bGVzTGlzdCIsImZldGNoIiwicmVzcG9uc2UiLCJvayIsInN0YXR1c1RleHQiLCJqc29uIl0sInNvdXJjZVJvb3QiOiIifQ==