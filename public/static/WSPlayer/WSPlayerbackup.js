var __defProp = Object.defineProperty,
    __defNormalProp = (e, t, s) => t in e ? __defProp(e, t, {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: s
    }) : e[t] = s,
    __publicField = (e, t, s) => (__defNormalProp(e, "symbol" != typeof t ? t + "" : t, s), s);
! function(e, t) {
    "object" == typeof exports && "undefined" != typeof module ? t(exports) : "function" == typeof define && define.amd ? define(["exports"], t) : t((e = "undefined" != typeof globalThis ? globalThis : e || self).WSPlayer = {})
}(this, (function(e) {
    "use strict";
    const t = {
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
    class s {
        constructor(e) {
            this.$el = null, this.canvasElem = null, this.videoElem = null, this.domId = e.wrapperDomId + "-" + e.index, this.wsPlayer = e.wsPlayer, this.index = e.index, this.firstTime = 0, this.isAudioPlay = !1, this.speed = 1
        }
        initDom() {
            let e = this.getTemplate(),
                t = $(e);
            this.wsPlayer.$wrapper.append(t[0]), this.$el = $("#" + this.domId), this.canvasElem = document.getElementById(this.canvasId) || {}, this.ivsCanvasElem = document.getElementById(this.ivsCanvasId) || {}, this.pztCanvasElem = document.getElementById(this.pztCanvasId) || {}, this.videoElem = document.getElementById(this.videoId);
            let s = this.wsPlayer.config.showIcons || {};
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
        initMouseEvent() {
            this.$el.click((e => {
                this.wsPlayer.setSelectIndex(this.index), this.$el.siblings().removeClass("selected").addClass("unselected"), this.$el.removeClass("unselected").addClass("selected")
            })), this.$el.dblclick((e => {
                1 !== this.wsPlayer.options.maxNum && (this.wsPlayer.$el.hasClass("fullplayer") ? this.wsPlayer.setPlayerNum(this.wsPlayer.beforeShowNum) : (this.wsPlayer.beforeShowNum = this.wsPlayer.showNum, this.wsPlayer.setPlayerNum(1)), this.wsPlayer.setSelectIndex(this.index), this.$el.siblings().removeClass("selected").addClass("unselected"), this.$el.removeClass("unselected").addClass("selected"))
            })), $(".audio-icon", this.$el).click((e => {
                if (this.wsPlayer.isTalking) this.wsPlayer.sendErrorMessage(this.isTalking ? "301" : "302");
                else {
                    if (this.isAudioPlay) this.player.setAudioVolume(0), $(e.target).removeClass("on").addClass("off");
                    else {
                        if (this.player.isPlayback && (this.speed < .5 || this.speed > 2)) return;
                        this.player.setAudioVolume(1), this.resumeAudio(), $(e.target).removeClass("off").addClass("on")
                    }
                    this.isAudioPlay = !this.isAudioPlay
                }
            })), $(".talk-icon", this.$el).click((e => {
                this.wsPlayer.isTalking && !this.isTalking ? this.wsPlayer.sendErrorMessage("303") : this.isTalking ? this.stopTalk() : (this.resumeAudio(), this.setAuthority({
                    channelCode: this.options.channelData ? this.options.channelData.channelCode : this.options.channelId,
                    function: "3"
                }, (() => {
                    this.wsPlayer.talkIndex = this.index, this.wsPlayer.__startTalk(this.options.channelData)
                }), (e => {
                    1103 === e.code && this.wsPlayer.sendErrorMessage(401, {
                        type: "talk"
                    })
                })))
            })), $(".capture-icon", this.$el).click((e => {
                let t = (this.options.channelData || {}).name || "抓图";
                this.setAuthority({
                    channelCode: this.options.channelData ? this.options.channelData.channelCode : this.options.channelId,
                    function: "4"
                }, (() => {
                    this.player.capture(`${t}-${Date.now()}`)
                }), (e => {
                    1103 === e.code && this.wsPlayer.sendErrorMessage(401, {
                        type: "capture"
                    })
                }))
            })), $(".close-icon", this.$el).click((e => {
                this.close()
            })), $(".record-icon", this.$el).click((e => {
                let t = (this.options.channelData || {}).name || "录像";
                this.isRecording ? (this.isRecording = !1, this.player.stopLocalRecord(), $(e.target).removeClass("recording")) : "playing" === this.status && this.setAuthority({
                    channelCode: this.options.channelData ? this.options.channelData.channelCode : this.options.channelId,
                    function: "8"
                }, (() => {
                    this.isRecording = !0, this.player.startLocalRecord(`${t}-${Date.now()}`, 50), $(e.target).addClass("recording")
                }), (e => {
                    1103 === e.code && this.wsPlayer.sendErrorMessage(401, {
                        type: "record"
                    })
                }))
            }))
        }
        setAuthority(e, t, s) {
            this.wsPlayer.fetchChannelAuthority ? this.wsPlayer.fetchChannelAuthority(e).then((e => {
                e.data.result && t()
            })).catch((e => {
                s(e)
            })) : t()
        }
        resumeAudio() {
            if (window.wsAudioPlayer) window.wsAudioPlayer.manualResume("fromTalk");
            else {
                let e = setInterval((() => {
                    window.wsAudioPlayer && (window.wsAudioPlayer.manualResume("fromTalk"), clearInterval(e))
                }), 100)
            }
        }
        setStatus() {}
        play() {
            this.player.play(), this.setStatus("playing"), $(".ws-record-play").css({
                display: "none"
            }), $(".ws-record-pause").css({
                display: "block"
            })
        }
        pause() {
            this.player.pause(), this.setStatus("pause"), $(".ws-record-pause").css({
                display: "none"
            }), $(".ws-record-play").css({
                display: "block"
            })
        }
        close(e = !1) {
            this.player && window.wsPlayerManager.unbindPlayer(this.player.nPlayPort), this.wsPlayer.videoClosed(this.index, e), this.setDomVisible($(".play-pause-wrapper", this.$el), !1), this.videoElem.style.display = "none", this.canvasElem.style.display = "none", this.isTalking && this.stopTalk(), this.speed = 1, this.index === this.wsPlayer.selectIndex && (this.wsPlayer.setTimeLine([]), this.wsPlayer.__setPlaySpeed(), $(".ws-record-play").css({
                display: "block"
            }), $(".ws-record-pause").css({
                display: "none"
            })), this.isRecording && (this.isRecording = !1, this.player.stopLocalRecord(), $(".record-icon", this.$el).removeClass("recording")), this.wsPlayer.config.openIvs && this.player && this.player.closeIVS(), this.spinner && this.spinner.stop(), this.player && this.player.stop(), this.player && this.player.close(), e || (this.player = null, this.options = null), this.setStatus("closed")
        }
        setDomVisible(e, t) {
            e && e.css({
                visibility: t ? "visible" : "hidden"
            })
        }
        updateAdapter(e, t = {}) {
            let s = t.width / t.height,
                i = "video" === (t.decodeMode || this.decodeMode) ? this.videoElem : this.canvasElem,
                a = i.parentNode;
            t.decodeMode ? (this.decodeMode = t.decodeMode, this.width = t.width, this.height = t.height) : s = this.width / this.height;
            let l = "100%",
                r = "100%";
            if ("selfAdaption" === e) {
                let e = a.offsetHeight,
                    t = a.offsetWidth,
                    n = t / e;
                s > n ? r = t / s + "px" : s < n && (l = e * s + "px"), $(i).css({
                    width: l,
                    height: r,
                    "object-fit": "contain"
                }), $(this.ivsCanvasElem).css({
                    width: l,
                    height: r,
                    "object-fit": "contain"
                }), $(this.pztCanvasElem).css({
                    width: l,
                    height: r,
                    "object-fit": "contain"
                })
            } else $(i).css({
                width: l,
                height: r,
                "object-fit": "fill"
            }), $(this.ivsCanvasElem).css({
                width: l,
                height: r,
                "object-fit": "fill"
            }), $(this.pztCanvasElem).css({
                width: l,
                height: r,
                "object-fit": "fill"
            });
            this.player && (this.ivsCanvasElem.width = i.offsetWidth, this.ivsCanvasElem.height = i.offsetHeight, this.player.setIVSCanvasSize(i.offsetWidth, i.offsetHeight), this.pztCanvasElem.width = i.offsetWidth, this.pztCanvasElem.height = i.offsetHeight)
        }
    }
    var i = globalThis && globalThis.__assign || function() {
            return (i = Object.assign || function(e) {
                for (var t, s = 1, i = arguments.length; s < i; s++)
                    for (var a in t = arguments[s]) Object.prototype.hasOwnProperty.call(t, a) && (e[a] = t[a]);
                return e
            }).apply(this, arguments)
        },
        a = {
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
        l = function() {
            function e(e) {
                void 0 === e && (e = {}), this.opts = i(i({}, a), e)
            }
            return e.prototype.spin = function(e) {
                return this.stop(), this.el = document.createElement("div"), this.el.className = this.opts.className, this.el.setAttribute("role", "progressbar"), r(this.el, {
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
                        for (var a = function(e) {
                                for (var t = /^\s*([a-zA-Z]+\s+)?(-?\d+(\.\d+)?)([a-zA-Z]*)\s+(-?\d+(\.\d+)?)([a-zA-Z]*)(.*)$/, s = [], i = 0, a = e.split(","); i < a.length; i++) {
                                    var l = a[i].match(t);
                                    if (null !== l) {
                                        var r = +l[2],
                                            n = +l[5],
                                            o = l[4],
                                            c = l[7];
                                        0 !== r || o || (o = c), 0 !== n || c || (c = o), o === c && s.push({
                                            prefix: l[1] || "",
                                            x: r,
                                            y: n,
                                            xUnits: o,
                                            yUnits: c,
                                            end: l[8]
                                        })
                                    }
                                }
                                return s
                            }(i), l = 0; l < t.lines; l++) {
                            var c = ~~(360 / t.lines * l + t.rotate),
                                d = r(document.createElement("div"), {
                                    position: "absolute",
                                    top: -t.width / 2 + "px",
                                    width: t.length + t.width + "px",
                                    height: t.width + "px",
                                    background: n(t.fadeColor, l),
                                    borderRadius: s,
                                    transformOrigin: "left",
                                    transform: "rotate(" + c + "deg) translateX(" + t.radius + "px)"
                                }),
                                h = l * t.direction / t.lines / t.speed;
                            h -= 1 / t.speed;
                            var p = r(document.createElement("div"), {
                                width: "100%",
                                height: "100%",
                                background: n(t.color, l),
                                borderRadius: s,
                                boxShadow: o(a, c),
                                animation: 1 / t.speed + "s linear " + h + "s infinite " + t.animation
                            });
                            d.appendChild(p), e.appendChild(d)
                        }
                    }(this.el, this.opts), this
            }, e.prototype.stop = function() {
                return this.el && ("undefined" != typeof requestAnimationFrame ? cancelAnimationFrame(this.animateId) : clearTimeout(this.animateId), this.el.parentNode && this.el.parentNode.removeChild(this.el), this.el = void 0), this
            }, e
        }();

    function r(e, t) {
        for (var s in t) e.style[s] = t[s];
        return e
    }

    function n(e, t) {
        return "string" == typeof e ? e : e[t % e.length]
    }

    function o(e, t) {
        for (var s = [], i = 0, a = e; i < a.length; i++) {
            var l = a[i],
                r = c(l.x, l.y, t);
            s.push(l.prefix + r[0] + l.xUnits + " " + r[1] + l.yUnits + l.end)
        }
        return s.join(", ")
    }

    function c(e, t, s) {
        var i = s * Math.PI / 180,
            a = Math.sin(i),
            l = Math.cos(i);
        return [Math.round(1e3 * (e * l + t * a)) / 1e3, Math.round(1e3 * (-e * a + t * l)) / 1e3]
    }
    const d = window.PlayerControl;
    class h extends s {
        constructor(e) {
            super(e), this.canvasId = `${this.domId}-livecanvas`, this.ivsCanvasId = `${this.domId}-ivs-livecanvas`, this.pztCanvasId = `${this.domId}-pzt-livecanvas`, this.videoId = `${this.domId}-liveVideo`, this.initDom(), this.defaultStatus = $(".default-status", this.$el), this.error = $(".error", this.$el), this.controller = $(".player-control", this.$el), this.initMouseEvent(), this.setStatus("created")
        }
        getTemplate() {
            return `
                <div id="${this.domId}" class="wsplayer-item wsplayer-item-${this.index} ${0===this.index?"selected":"unselected"}">
                    <div class="ws-full-content ws-flex">
                        <canvas id="${this.canvasId}" class="kind-stream-canvas" kind-channel-id="0" width="800" height="600"></canvas>
                        <video id="${this.videoId}" class="kind-stream-canvas" kind-channel-id="0" muted style="display:none" width="800" height="600"></video>
                        <canvas id="${this.ivsCanvasId}" class="kind-stream-canvas" style="position: absolute" kind-channel-id="0" width="800" height="600"></canvas>
                        <canvas id="${this.pztCanvasId}" class="kind-stream-canvas" style="display: none; position: absolute" kind-channel-id="0" width="800" height="600"></canvas>
                    </div>
                    <div class="default-status">
                        <img src="./static/WSPlayer/icon/default.png" alt="">
                    </div>
                    <div class="player-control top-control-bar">
                        <div class="stream">
                            <div class="select-container">
                                <div class="select-show select">
                                    <div class="code-stream">主码流</div>
                                    <!-- 下拉箭头 -->
                                    <img src="./static/WSPlayer/icon/spread.png" />
                                </div>
                                <div class="stream-type" style="display: none">
                                    <ul class="select-ul">
                                        <li optionValue="主码流" stream-type="1" class="stream-type-item">主码流</li>
                                        <li optionValue="辅码流1" stream-type="2" class="stream-type-item">辅码流1</li>
                                        <li optionValue="辅码流2" stream-type="3" class="stream-type-item">辅码流2</li>
                                    </ul>
                                </div>
                            </div>
                            <span class="stream-info"></span>
                        </div>
                        <div class="opt-icons">
                            <div class="opt-icon talk-icon off" title="对讲"></div>
                            <div class="opt-icon record-icon" title="Rekam"></div>
                            <div class="opt-icon audio-icon off" title="Aktifkan Suara"></div>
                            <div class="opt-icon capture-icon" title="Ambil Gambar"></div>
                            <div class="opt-icon close-icon" title="关闭"></div>
                        </div>
                    </div>
                    <div class="ws-talking">对讲中...</div>
                    <div class="error">
                        <div class="error-message"></div>
                    </div>
                </div>
            `
        }
        initMouseEvent() {
            super.initMouseEvent();
            let e = this;
            this.hideTimer = null, this.$el.on("mouseenter mousemove", (e => {
                ["created", "closed"].includes(this.status) || this.setDomVisible($(".player-control", $(`#${this.domId}`)), !0), "playing" !== this.status && "error" !== this.status || this.hideTimer && clearTimeout(this.hideTimer)
            })), this.$el.on("mouseleave", (e => {
                this.hideTimer = setTimeout((() => {
                    $(".stream-type", this.$el).hide(), this.setDomVisible($(".player-control", $(`#${this.domId}`)), !1), this.streamSelectShow = !1
                }), 300)
            })), this.streamSelectShow = !1, $(".select", this.$el).click((e => {
                this.streamSelectShow ? ($(".stream-type", this.$el).hide(), this.streamSelectShow = !1) : ($(".stream-type", this.$el).show(), this.streamSelectShow = !0)
            })), $(".stream-type", this.$el).click((t => {
                let s = t.target.getAttribute("stream-type");
                e.streamType !== s && e.options && e.wsPlayer.changeStreamType(e.options.channelData, s, e.index)
            }))
        }
        setStreamType(e) {
            this.streamType = e;
            let t = $(".stream-type .select-ul")[this.index].children[e - 1];
            $(".code-stream", this.$el).text($(t).attr("optionValue")), $(t).addClass("stream-type-select").siblings().removeClass("stream-type-select")
        }
        setStatus(e, s) {
            switch (this.wsPlayer.sendMessage("statusChanged", {
                    status: e,
                    windowIndex: this.index
                }), this.status = e, this.status) {
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
                    this.setDomVisible(this.defaultStatus, !1), $(".error-message", this.$el).text(t.errorVideoInfo[s.errorCode] ? t.errorVideoInfo[s.errorCode] : t.errorVideoInfo.defaultErrorMsg), this.setDomVisible(this.error, !0)
            }
        }
        init(e) {
            window.m_nModuleInitialized ? (this.options = e, this.player && (this.isAudioPlay && $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.close(!0)), this.spinner && this.spinner.stop(), this.spinner = new l({
                color: "#ffffff"
            }).spin(this.$el[0]), this.setStatus("ready"), this.setStreamType(e.streamType), this.createPlayer(e)) : console.error("解码库未初始化完成，请稍后播放！")
        }
        startPlay(e, t) {
            let s = this;
            "video" === t.decodeMode ? (s.videoElem.style.display = "", s.canvasElem.style.display = "none") : (s.videoElem.style.display = "none", s.canvasElem.style.display = ""), s.updateAdapter(e.playerAdapter, t), this.width = t.width, this.height = t.height, $(".stream-info", $(`#${s.domId}`)).text(`${t.encodeMode?`${t.encodeMode}, `:""}${t.width?`${t.width}*`:""}${t.height?t.height:""}`)
        }
        createPlayer(e) {
            let t = this;
            const {
                useH264MSE: s,
                useH265MSE: i
            } = this.wsPlayer.config;
            this.player = new d({
                wsURL: e.wsURL,
                rtspURL: e.rtspURL,
                useH264MSE: s,
                useH265MSE: i,
                events: {
                    PlayStart: e => {
                        console.log("开始播放", e), t.spinner.stop(), t.setStatus("playing")
                    },
                    DecodeStart: s => {
                        console.log("开始解码", s), t.startPlay(e, s)
                    },
                    GetFrameRate: s => {
                        console.log("GetFrameRate", s), t.startPlay(e, s)
                    },
                    Error: e => {
                        if (t.player && t.player.ws && e.symbol === t.player.ws.symbol) {
                            if ("408" === e.errorCode) return void("2" === t.streamType && t.wsPlayer.changeStreamType(t.options.channelData, "1", t.index));
                            t.spinner.stop(), console.log("Error: " + JSON.stringify(e)), t.setStatus("error", e)
                        }
                    },
                    FileOver: e => {
                        console.log("FileOver: ", e)
                    },
                    UpdatePlayingTime: e => {}
                }
            }), this.player.init(this.canvasElem, this.videoElem, this.ivsCanvasElem), this.player.connect(), this.wsPlayer.config.openIvs && this.player.openIVS(), window.wsPlayerManager.bindPlayer(this.player.nPlayPort, this.player)
        }
        startTalk(e) {
            if (!window.m_nModuleInitialized) return void console.error("解码库未初始化完成，请稍后对讲！");
            this.wsPlayer.isTalking = !0, this.isTalking = !0, $(".talk-icon", this.$el).removeClass("off").addClass("on");
            let t = this;
            const {
                useH264MSE: s,
                useH265MSE: i
            } = this.wsPlayer.config;
            this.talkPlayer = new d({
                rtspURL: e.rtspURL,
                wsURL: this.wsPlayer.__getWSUrl(e.rtspURL, e.serverIp),
                isTalkService: !0,
                useH264MSE: s,
                useH265MSE: i,
                events: {
                    Error: e => {
                        "504" === e.errorCode && (t.stopTalk(), t.wsPlayer.sendMessage("errorInfo", e))
                    }
                }
            }), this.talkPlayer.talk("on"), window.wsPlayerManager.bindPlayer(this.talkPlayer.nPlayPort, this.talkPlayer), $(".ws-talking", this.$el).css({
                visibility: "visible"
            }), this.player.setAudioVolume(0), $(".audio-icon", this.$el).removeClass("on").addClass("off")
        }
        stopTalk() {
            this.talkPlayer && window.wsPlayerManager.unbindPlayer(this.talkPlayer.nPlayPort), this.isTalking && (this.wsPlayer.isTalking = !1, this.isTalking = !1), this.talkPlayer && (this.talkPlayer.talk("off"), this.talkPlayer = null), $(".talk-icon", this.$el).removeClass("on").addClass("off"), $(".ws-talking", this.$el).css({
                visibility: "hidden"
            })
        }
    }
    const p = window.PlayerControl;
    class y extends s {
        constructor(e) {
            super(e), this.speed = 1, this.canvasId = `${this.domId}-recordcanvas`, this.ivsCanvasId = `${this.domId}-ivs-livecanvas`, this.videoId = `${this.domId}-recordVideo`, this.curTimestamp = 0, this.initDom(), this.defaultStatus = $(".default-status", this.$el), this.error = $(".error", this.$el), this.controller = $(".player-control", this.$el), this.timeInfo = $(".time-info", this.$el), this.initMouseEvent(), this.setStatus("created")
        }
        getTemplate() {
            return `
                <div id="${this.domId}" class="wsplayer-item wsplayer-item-${this.index} ${0===this.index?"selected":"unselected"}">
                    <canvas id="${this.canvasId}" class="kind-stream-canvas" kind-channel-id="0" width="800" height="600"></canvas>
                    <video id="${this.videoId}" class="kind-stream-canvas" kind-channel-id="0" muted style="display:none" width="800" height="600"></video>
                    <canvas id="${this.ivsCanvasId}" class="kind-stream-canvas" style="position: absolute" kind-channel-id="0" width="800" height="600"></canvas>
                    <div class="default-status">
                        <img src="./static/WSPlayer/icon/default.png" alt="">
                    </div>
                    <div class="player-control top-control-bar">
                        <span class="stream-info"></span>
                        <div class="opt-icons">
                            <div class="opt-icon record-icon" title="Rekam"></div>
                            <div class="opt-icon audio-icon off"></div>
                            <div class="opt-icon capture-icon"></div>
                            <div class="opt-icon close-icon"></div>
                        </div>
                    </div>
                    <div class="player-control record-control-bar">
                        <div class="wsplayer-progress-bar">
                            <div class="progress-bar_background"></div>
                            <div class="progress-bar_hover_light"></div>
                            <div class="progress-bar_light"></div>
                        </div>
                        <div class="record-control-left">
                            <div class="opt-icon play-ctrl-btn play-icon play"></div>
                            <div class="time-info"></div>/<div class="time-long"></div>
                        </div>
                        <div class="record-control-right">
                            <div class="opt-icon close-icon"></div>
                        </div>
                    </div>
                    <div class="error">
                        <div class="error-message"></div>
                    </div>
                    <div class="play-pause-wrapper">
                        <div class="play-ctrl-btn center-play-icon"></div>
                    </div>
                </div>
            `
        }
        initMouseEvent() {
            super.initMouseEvent(), this.hideTimer = null, this.$el.on("mouseenter mousemove", (e => {
                ["created", "closed"].includes(this.status) || this.setDomVisible($(".player-control", $(`#${this.domId}`)), !0), "playing" === this.status ? this.hideTimer && clearTimeout(this.hideTimer) : "ready" === this.status && this.setDomVisible(this.progressBar, !0)
            })), this.$el.on("mouseleave", (e => {
                "pause" !== this.status && (this.hideTimer = setTimeout((() => {
                    this.setDomVisible($(".player-control", $(`#${this.domId}`)), !1)
                }), 300))
            })), $(".wsplayer-progress-bar", this.$el).on("mousemove", (e => {
                $(".progress-bar_hover_light", this.$el).css({
                    width: e.offsetX + "px"
                })
            })), $(".wsplayer-progress-bar", this.$el).on("mouseleave", (e => {
                $(".progress-bar_hover_light", this.$el).css({
                    width: 0
                })
            })), $(".play-ctrl-btn", this.$el).click((e => {
                "playing" === this.status ? (this.pause(), $(".play-icon", this.$el).removeClass("play").addClass("pause")) : (this.play(), $(".play-icon", this.$el).removeClass("pause").addClass("play"))
            }))
        }
        setStatus(e, s) {
            switch (this.wsPlayer.sendMessage("statusChanged", {
                    status: e,
                    windowIndex: this.index
                }), this.status = e, this.status) {
                case "created":
                case "closed":
                    this.setDomVisible(this.defaultStatus, !0), this.setDomVisible(this.error, !1), this.setDomVisible(this.controller, !1), $(".audio-icon", this.$el).removeClass("on").addClass("off");
                    break;
                case "ready":
                    this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1);
                    break;
                case "playing":
                    $("#ws-record-time-box").csslibdhplay.js({
                        visibility: "visible"
                    }), this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1), this.setDomVisible($(".play-pause-wrapper", this.$el), !1);
                    break;
                case "pause":
                    this.setDomVisible(this.defaultStatus, !1), this.setDomVisible(this.error, !1), this.setDomVisible(this.controller, !1), this.setDomVisible($(".play-pause-wrapper", this.$el), !0);
                    break;
                case "error":
                    this.setDomVisible(this.defaultStatus, !1), $(".error-message", this.$el).text(t.errorVideoInfo[s.errorCode] ? t.errorVideoInfo[s.errorCode] : t.errorVideoInfo.defaultErrorMsg), this.setDomVisible(this.error, !0)
            }
        }
        init(e) {
            window.m_nModuleInitialized ? (this.options = e, this.player && (this.isAudioPlay && $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.close(!0)), this.spinner && this.spinner.stop(), this.spinner = new l({
                color: "#ffffff"
            }).spin(this.$el[0]), this.createPlayer(e)) : console.error("解码库未初始化完成，请稍后播放！")
        }
        createPlayer(e) {
            let t = this;
            const {
                useH264MSE: s,
                useH265MSE: i
            } = this.wsPlayer.config;
            this.player = new p({
                wsURL: e.wsURL,
                rtspURL: e.rtspURL,
                isPlayback: e.isPlayback,
                useH264MSE: s,
                useH265MSE: i,
                events: {
                    PlayStart: e => {
                        console.log("PlayStart"), t.setStatus("playing")
                    },
                    DecodeStart: s => {
                        console.log("DecodeStart", s), t.spinner.stop(), "video" === s.decodeMode ? (t.videoElem.style.display = "", t.canvasElem.style.display = "none") : (t.videoElem.style.display = "none", t.canvasElem.style.display = ""), t.updateAdapter(e.playerAdapter, s), $(".stream-info", $(`#${t.domId}`)).text(s.width ? `${s.encodeMode}, ${s.width}*${s.height}` : s.encodeMode)
                    },
                    GetFrameRate: e => {
                        console.log("GetFrameRate: ", e)
                    },
                    Error: e => {
                        if (t.player && e.symbol === t.player.ws.symbol) {
                            if ("408" === e.errorCode) return;
                            t.spinner.stop(), console.log("Error: " + JSON.stringify(e)), t.setStatus("error", e)
                        }
                    },
                    FileOver: e => {
                        console.log("回放播放完成"), t.close(), t.wsPlayer.playNextRecord(t.index)
                    },
                    UpdatePlayingTime: e => {
                        "playing" === t.status && t.wsPlayer.__setPlayingTime(t.index, e)
                    }
                }
            }), this.timeLong = e.endTime - e.startTime;
            let a = this.timeLong % 60,
                l = parseInt(this.timeLong / 60) % 60,
                r = parseInt(this.timeLong / 3600) % 60;
            this.timeLongStr = `${r>0?r+":":""}${l<10?"0"+l:l}:${a<10?"0"+a:a}`, $(".time-long", this.$el).text(this.timeLongStr), this.setStatus("ready"), this.player.init(this.canvasElem, this.videoElem, this.ivsCanvasElem), this.player.connect(), this.wsPlayer.config.openIvs && this.player.openIVS(), window.wsPlayerManager.bindPlayer(this.player.nPlayPort, this.player)
        }
        playSpeed(e) {
            this.speed = e, (e < .5 || e > 2) && (this.player.setAudioVolume(0), $(".audio-icon", this.$el).removeClass("on").addClass("off"), this.isAudioPlay = !1), this.player && this.player.playSpeed(e)
        }
    }
    class m {
        constructor() {
            this.wsPlayerList = [], this.portToPlayer = {}, window.cPlusVisibleDecCallBack = this.cPlusVisibleDecCallBack.bind(this), window.cExtraDrawDataCallBack = this.cExtraDrawDataCallBack.bind(this), window.cExtraDrawDrawCallBack = this.cExtraDrawDrawCallBack.bind(this)
        }
        cPlusVisibleDecCallBack(e, t, s, i, a, l) {
            this.portToPlayer[e] && this.portToPlayer[e].setFrameData(e, t, s, i, a, l)
        }
        cExtraDrawDataCallBack(e, t, s, i) {
            this.portToPlayer[e] && this.portToPlayer[e].setIVSData(e, t, s, i)
        }
        cExtraDrawDrawCallBack(e) {
            this.portToPlayer[e] && this.portToPlayer[e].drawIVSData(e)
        }
        bindPlayer(e, t) {
            this.portToPlayer[e] || (this.portToPlayer[e] = t)
        }
        unbindPlayer(e) {
            this.portToPlayer[e] = null
        }
        addWSPlayer(e) {
            this.wsPlayerList.push()
        }
        removeWSPlayer(e) {
            this.wsPlayerList = this.wsPlayerList.filter((t => t === e))
        }
    }
    const u = "Chrome",
        v = "Firefox",
        w = "Edge",
        g = function(e) {
            return "[object Function]" === toString.call(e)
        },
        f = function e() {
            let t = {};
            for (let i = 0; i < arguments.length; i++) {
                let a = arguments[i];
                for (let i in a) {
                    let l = a[i];
                    s = l, "[object Object]" === toString.call(s) ? t[i] = e(l) : t[i] = l
                }
            }
            var s;
            return t
        },
        P = {
            clientType: "WINPC",
            clientMac: "30:9c:23:79:40:08",
            clientPushId: "",
            project: "PSDK",
            method: "MTS.Video.StartVideo",
            data: {
                optional: "/admin/API/MTS/Video/StartVideo",
                dataType: "3",
                streamType: "2",
                channelId: "",
                trackId: ""
            }
        },
        S = {
            clientType: "WINPC",
            clientMac: "30:9c:23:79:40:08",
            clientPushId: "",
            project: "PSDK",
            method: "MTS.Audio.StartTalk",
            data: {
                optional: "/admin/API/MTS/Audio/StartTalk?token=ff93dabe5d754ea8acb0a95dbe6c4a0f",
                source: "",
                deviceCode: "",
                talkType: "1",
                target: "",
                audioBit: 16,
                audioType: 2,
                broadcastChannels: "",
                sampleRate: 8e3,
                talkmode: "",
                channelSeq: "0"
            }
        },
        b = {
            clientType: "WINPC",
            clientMac: "30:9c:23:79:40:08",
            clientPushId: "",
            project: "PSDK",
            method: "SS.Record.QueryRecords",
            data: {
                cardNo: "",
                optional: "/admin/API/SS/Record/QueryRecords",
                diskPath: "",
                startIndex: "",
                streamType: "0",
                recordType: "0",
                recordSource: "3",
                endIndex: "",
                startTime: "",
                endTime: "",
                channelId: ""
            }
        },
        I = {
            clientType: "WINPC",
            clientMac: "30:9c:23:79:40:08",
            clientPushId: "",
            project: "PSDK",
            method: "SS.Playback.StartPlaybackByTime",
            data: {
                nvrId: "",
                optional: "/admin/API/SS/Playback/StartPlaybackByTime",
                recordType: "0",
                recordSource: "1",
                streamType: "1",
                channelId: "",
                startTime: "",
                endTime: ""
            }
        },
        x = {
            clientType: "WINPC",
            clientMac: "30:9c:23:79:40:08",
            clientPushId: "",
            project: "PSDK",
            method: "SS.Playback.StartPlaybackByFile",
            data: {
                ssId: "",
                optional: "/evo-apigw/admin/API/SS/Playback/StartPlaybackByFile",
                startTime: "",
                endTime: "",
                fileName: "",
                diskId: "",
                nvrId: "",
                recordSource: "",
                channelId: "",
                playbackMode: "0",
                streamId: ""
            }
        };
    class C {
        constructor(e) {
            this.realPlayer = null, this.recordPlayer = null, "real" === e.type ? this.realPlayer = e.player : this.recordPlayer = e.player, this.playIndex = 0, this.recordList = [], this.getRealRtsp = e.getRealRtsp, this.getRecords = e.getRecords, this.getRecordRtspByTime = e.getRecordRtspByTime, this.getRecordRtspByFile = e.getRecordRtspByFile, this.getTalkRtsp = e.getTalkRtsp
        }
        getCurrentRtsp(e) {
            let t = e.split("|");
            return t.find((e => e.includes(window.location.hostname))) || t[0]
        }
        openSomeWindow(e) {
            let t = this.realPlayer || this.recordPlayer;
            e > t.showNum && (e < t.maxWindow ? this.playNum = e > 16 ? 25 : e > 9 ? 16 : e > 4 ? 9 : 4 : this.playNum = t.maxWindow, t.setPlayerNum(this.playNum))
        }
        playRealVideo(e, t = "2", s, i = !1) {
            let a = [],
                l = [];
            e.forEach((e => {
                e.isOnline ? a.push(e) : l.push(e)
            })), l.length && this.realPlayer.sendErrorMessage(101, l), a.length && (g(this.getRealRtsp) ? (this.openSomeWindow(a.length), a.map(((e, l) => {
                let r = this.playIndex;
                a.length > 1 ? r = this.playIndex + l : s > -1 && (r = s || 0), P.data.streamType = t, P.data.channelId = e.id, this.getRealRtsp(JSON.parse(JSON.stringify(P))).then((s => {
                    s.rtspURL = this.getCurrentRtsp(s.url) + "?token=" + s.token, this.realPlayer.playReal({
                        selectIndex: r,
                        serverIp: s.innerIp,
                        rtspURL: s.rtspURL,
                        channelId: e.id,
                        channelData: e,
                        streamType: t
                    })
                }), (t => {
                    i ? console.error("接口获取失败") : this.playRealVideo([e], "1", r, !0)
                }))
            }))) : console.error("getRealRtsp 需要传入正确的请求实时预览接口的方法"))
        }
        startTalk(e) {
            g(this.getTalkRtsp) ? (S.data.deviceCode = e.deviceCode, S.data.audioBit = e.audioBit || 16, S.data.sampleRate = e.sampleRate || 8e3, [1, 6, 10, 43].includes(e.deviceType) && (S.data.talkType = 2, S.data.channelSeq = e.channelSeq), this.getTalkRtsp(JSON.parse(JSON.stringify(S))).then((e => {
                let t = this.getCurrentRtsp(e.url) + "?token=" + e.token;
                this.realPlayer.playerList[this.realPlayer.talkIndex].startTalk({
                    rtspURL: t,
                    serverIp: e.innerIp
                })
            })).catch((e => {
                this.realPlayer.sendErrorMessage(401, {
                    type: "talk"
                })
            }))) : console.error("getRealRtsp 需要传入正确的请求对讲接口的方法")
        }
        getRecordList(e) {
            if (!g(this.getRecords)) return void console.error("getRecords 需要传入正确的请求录像接口的方法");
            b.data.streamType = e.streamType || "0", b.data.recordType = e.recordType || "0", b.data.recordSource = e.recordSource, b.data.startTime = e.startTime, b.data.endTime = e.endTime;
            let t = e.channelList.length > 1 ? 0 : this.playIndex;
            e.channelList.forEach((s => {
                b.data.channelId = s.id, this.getRecords(JSON.parse(JSON.stringify(b))).then((i => {
                    let a = i.records || [];
                    if (!a.length) return this.recordPlayer.sendErrorMessage(201, [s]), void console.warn("所选通道未查询到录像文件");
                    e.channelList.length > 1 && this.openSomeWindow(t + 1), this.getRecordRtsp({
                        ...e,
                        channel: s
                    }, a.map((e => (e.isImportant = "2" === e.recordType, e))), !e.isUpdateRecords, t), t++
                }))
            }))
        }
        getRecordRtsp(e, t, s = !0, i) {
            let a = null,
                l = t[0].recordSource || e.recordSource;
            if (2 === Number(l)) {
                if (!g(this.getRecordRtspByTime)) return void console.error("getRecordRtspByTime 需要传入正确的请求录像接口的方法");
                I.data.streamType = e.streamType || "0", I.data.recordType = e.recordType || "0", I.data.recordSource = l, I.data.startTime = e.startTime, I.data.endTime = e.endTime, I.data.channelId = e.channel.id, a = this.getRecordRtspByTime(JSON.parse(JSON.stringify(I)))
            } else if (3 === Number(l)) {
                if (!g(this.getRecordRtspByFile)) return void console.error("getRecordRtspByFile 需要传入正确的请求录像接口的方法");
                let s = t[0];
                x.data = {
                    ssId: s.ssId,
                    optional: "/evo-apigw/admin/API/SS/Playback/StartPlaybackByFile",
                    startTime: s.startTime,
                    endTime: s.endTime,
                    fileName: s.recordName,
                    diskId: s.diskId,
                    nvrId: "",
                    recordSource: s.recordSource ? s.recordSource : "3",
                    channelId: e.channel.id,
                    playbackMode: "0",
                    streamId: s.streamId
                }, a = this.getRecordRtspByFile(JSON.parse(JSON.stringify(x)))
            }
            a && a.then((a => {
                if (a.channelId = e.channel.id, a.rtspURL = this.getCurrentRtsp(a.url) + "?token=" + a.token, !a.rtspURL) return this.recordPlayer.sendErrorMessage(201, [e.channel]), void console.warn("所选通道未查询到录像文件");
                this.recordPlay(a, i);
                let l = this.recordList[i];
                if (s) this.recordList[i] = {
                    ...e,
                    recordList: t,
                    recordIndex: 0,
                    isPlaying: !0
                };
                else {
                    let e = t[0].recordName;
                    l.recordIndex = l.recordList.findIndex((t => t.recordName === e)), l.isPlaying = !0
                }
                this.playIndex === i && (s || (t = l.recordList, l.isPlaying = !0), this.setTimeLine(t))
            })).catch((e => {
                console.log(e)
            }))
        }
        recordPlay(e, t) {
            this.recordPlayer.playRecord({
                ...e,
                serverIp: e.innerIp,
                selectIndex: t
            })
        }
        setTimeLine(e) {
            this.recordPlayer.setTimeLine(e)
        }
        clickRecordTimeLine(e) {
            let t = this.recordList[this.playIndex],
                s = t.startTime;
            s = new Date(1e3 * s).setHours(0), s = new Date(s).setMinutes(0), s = new Date(s).setSeconds(0) / 1e3 + e;
            let i = {
                channelList: [t.channel],
                startTime: s,
                endTime: t.endTime,
                recordSource: t.recordSource,
                isUpdateRecords: !0
            };
            this.getRecordList(i)
        }
        playNextRecord(e) {
            if (!g(this.getRecordRtspByFile)) return void console.error("getRecordRtspByFile 需要传入正确的请求录像接口的方法");
            let t = this.recordList[e];
            t.recordIndex++, t.isPlaying = !0;
            let s = t.recordList[t.recordIndex];
            s && (x.data = {
                ssId: s.ssId,
                optional: "/evo-apigw/admin/API/SS/Playback/StartPlaybackByFile",
                startTime: s.startTime,
                endTime: s.endTime,
                fileName: s.recordName,
                diskId: s.diskId,
                nvrId: "",
                recordSource: s.recordSource,
                channelId: s.channelId,
                playbackMode: "0",
                streamId: s.streamId
            }, this.getRecordRtspByFile(JSON.parse(JSON.stringify(x))).then((s => {
                if (s.rtspURL = this.getCurrentRtsp(s.url) + "?token=" + s.token, !s.rtspURL) return this.recordPlayer.sendErrorMessage(201, [t.channel]), void console.warn("所选通道未查询到录像文件");
                this.recordPlay(s, e), this.setTimeLine(t.recordList)
            })))
        }
        changeTimeLine(e) {
            let t = this.recordList[e];
            t && t.isPlaying && this.setTimeLine(t.recordList)
        }
        videoClosed(e, t) {
            this.recordList[e] && (this.recordList[e].isPlaying = !1)
        }
        setPlayIndex(e) {
            this.playIndex = e
        }
        jumpPlayByTime(e) {
            let t = e.split(":"),
                s = 60 * (t[0] || 0) * 60 + 60 * (t[1] || 0) + 1 * (t[2] || 0);
            this.clickRecordTimeLine(s)
        }
    }
    class k {
        constructor(e = {}, t) {
            this.el = e.el, this.wsPlayer = t, this.$el = $("#" + this.el), this.$el && !this.$el.children().length && this.__createPanTilt(), this.channel = null, this.setPtzDirection = e.setPtzDirection, this.setPtzCamera = e.setPtzCamera, this.controlSitPosition = e.controlSitPosition, this.mousedownCanvasEvent = this.__mousedownCanvasEvent.bind(this), this.mousemoveCanvasEvent = this.__mousemoveCanvasEvent.bind(this), this.mouseupCanvasEvent = this.__mouseupCanvasEvent.bind(this)
        }
        setChannel(e) {
            if (this.channel = e, !e) return $(".ws-pan-tilt-mask", this.$el).css({
                display: "block"
            }), void this.__removeCanvasEvent();
            let t = e.capability;
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
        __createPanTilt() {
            this.$el.append(`
                <div class="ws-pan-tilt-control">
                    <div class="ws-pan-tilt-circle-wrapper">
                        <!-- 云台方向控制 -->
                        <div class="ws-pan-tilt-circle">
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-t.svg" title="上" direct="1"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-rt.svg" title="右上" direct="7"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-r.svg" title="右" direct="4"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-rb.svg" title="右下" direct="8"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-b.svg" title="下" direct="2"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-lb.svg" title="左下" direct="6"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-l.svg" title="左" direct="3"/></div>
                            <div class="ws-pan-tilt-direction-item"><img src="./static/WSPlayer/icon/arrow-lt.svg" title="左上" direct="5"/></div>
                            <div class="ws-pan-tilt-inner-circle">
                                <img class="ws-pan-tilt-pzt-select" src="./static/WSPlayer/icon/ptz-select.svg" title="三维定位"/>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 云台变倍、聚焦、光圈控制 -->
                    <div class="cloud-control-wrapper">
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon1.svg" title="变倍-" operateType="1" direct="2"/></div>
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon2.svg" title="变倍+" operateType="1" direct="1"/></div>
                        <div class="cloud-control-separate"></div>
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon3.svg" title="聚焦-" operateType="2" direct="2"/></div>
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon4.svg" title="聚焦+" operateType="2" direct="1"/></div>
                        <div class="cloud-control-separate"></div>
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon5.svg" title="光圈-" operateType="3" direct="2"/></div>
                        <div class="ws-pan-tilt-control-item"><img src="./static/WSPlayer/icon/ptz-icon6.svg" title="光圈+" operateType="3" direct="1"/></div>
                    </div>
                    
                    <!-- 遮罩，当通道没有云台功能时，使用遮罩遮住云台按钮 -->
                    <!-- 方向按钮遮罩 -->
                    <div class="ws-pan-tilt-mask ws-pan-tilt-mask-direction"></div>
                    <!-- 变倍、聚焦遮罩 -->
                    <div class="ws-pan-tilt-mask ws-pan-tilt-mask-zoom"></div>
                    <!-- 光圈遮罩 -->
                    <div class="ws-pan-tilt-mask ws-pan-tilt-mask-aperture"></div>
                </div>
            `), $(".ws-pan-tilt-direction-item img", this.$el).mouseup((e => {
                this.__setPtzDirection(e.target.getAttribute("direct"), "0")
            })), $(".ws-pan-tilt-direction-item img", this.$el).mousedown((e => {
                this.__setPtzDirection(e.target.getAttribute("direct"), "1")
            })), $(".ws-pan-tilt-control-item img", this.$el).mouseup((e => {
                this.__setPtzCamera(e.target.getAttribute("operateType"), e.target.getAttribute("direct"), "0")
            })), $(".ws-pan-tilt-control-item img", this.$el).mousedown((e => {
                this.__setPtzCamera(e.target.getAttribute("operateType"), e.target.getAttribute("direct"), "1")
            })), $(".ws-pan-tilt-pzt-select", this.$el).click((e => {
                this.__openSitPosition()
            }))
        }
        __setPtzDirection(e, t) {
            const s = {
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
            this.setPtzDirection && this.setPtzDirection(s).then().catch((e => {
                console.error("云台方向控制err:", e)
            }))
        }
        __setPtzCamera(e, t, s) {
            const i = {
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
            this.setPtzCamera && this.setPtzCamera(i).then().catch((e => {
                console.error("云台方向控制err:", e)
            }))
        }
        __openSitPosition() {
            if (this.openSitPositionFlag = !this.openSitPositionFlag, !this.canvasElem) {
                let e = this.wsPlayer.playerList,
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
        __mousedownCanvasEvent(e) {
            (e.offsetX || e.layerX) && (this.pointX = e.offsetX || e.layerX, this.pointY = e.offsetY || e.layerY, this.startDraw = !0)
        }
        __mousemoveCanvasEvent(e) {
            if (this.startDraw && (e.offsetX || e.layerX)) {
                const t = e.offsetX || e.layerX,
                    s = e.offsetY || e.layerY,
                    i = t - this.pointX,
                    a = s - this.pointY;
                this.canvasContext.clearRect(0, 0, this.canvasElem.width, this.canvasElem.height), this.canvasContext.beginPath(), this.canvasContext.strokeRect(this.pointX, this.pointY, i, a)
            }
        }
        __mouseupCanvasEvent(e) {
            if (e.offsetX || e.layerX) {
                this.startDraw = !1;
                const t = e.offsetX || e.layerX,
                    s = e.offsetY || e.layerY;
                let i = "",
                    a = "",
                    l = "";
                const r = (t + this.pointX) / 2,
                    n = (s + this.pointY) / 2,
                    o = this.canvasElem.width / 2,
                    c = this.canvasElem.height / 2,
                    d = Math.abs(t - this.pointX),
                    h = Math.abs(s - this.pointY),
                    p = t < this.pointX;
                i = 8192 * (r - o) * 2 / this.canvasElem.width, a = 8192 * (n - c) * 2 / this.canvasElem.height, t === this.pointX || s === this.pointY ? l = 0 : (l = this.canvasElem.width * this.canvasElem.height / (d * h), p && (l = -l)), this.canvasContext.clearRect(0, 0, this.canvasElem.width, this.canvasElem.height), this.__controlSitPosition(i, a, l)
            }
        }
        __removeCanvasEvent() {
            this.canvasElem && (this.canvasElem.removeEventListener("mousedown", this.mousedownCanvasEvent), this.canvasElem.removeEventListener("mousemove", this.mousemoveCanvasEvent), this.canvasElem.removeEventListener("mouseup", this.mouseupCanvasEvent), this.canvasElem = null, this.canvasContext = null, this.openSitPositionFlag = !1, $(".ws-pan-tilt-pzt-select", this.$el).attr({
                src: "./static/WSPlayer/icon/ptz-select.svg"
            }))
        }
        __controlSitPosition(e, t, s) {
            const i = {
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
            this.controlSitPosition && this.controlSitPosition(i).then((e => {})).catch((e => {
                console.error("三维定位控制err:", e)
            }))
        }
    }
    const T = {
        num: 1,
        maxNum: 25,
        showControl: !0,
        isDynamicLoadLib: !0,
        onlyLoadSingleLib: !1,
        useNginxProxy: !1,
        openIvs: !0,
        useH264MSE: !0,
        useH265MSE: !0,
        showIcons: {
            streamChangeSelect: !0,
            talkIcon: !0,
            localRecordIcon: !0,
            audioIcon: !0,
            snapshotIcon: !0,
            closeIcon: !0
        }
    };
    class R {
        constructor(e) {
            if (!e.type || !e.serverIp) return console.error("type, serverIp 为必传参数，请校验入参"), !1;
            this.options = e, this.type = e.type, this.config = f(T, e.config), this.serverIp = e.serverIp ? e.serverIp : location.hostname, this.procedure = new C({
                type: this.type,
                player: this,
                getRealRtsp: e.getRealRtsp,
                getRecords: e.getRecords,
                getRecordRtspByTime: e.getRecordRtspByTime,
                getRecordRtspByFile: e.getRecordRtspByFile,
                getTalkRtsp: e.getTalkRtsp
            }), this.el = e.el, this.fetchChannelAuthority = e.getChannelAuthority, this.$el = $("#" + this.el), this.width = this.$el.attr("width"), this.height = this.$el.attr("height"), this.$el.height(`${this.height}px`), this.$el.width(`${this.width}px`), this.$el.addClass("ws-player"), this.$el.append('<div class="player-wrapper"></div>'), this.$wrapper = $(".player-wrapper", this.$el), this.playerList = [], this.playerAdapter = "selfAdaption", this.canvas = {}, this.ctx = {}, this.showNum = 1, this.maxWindow = 1, this.sendMessage = e.receiveMessageFromWSPlayer || function(e, t) {}, $(this.$el).attr("inited", !0);
            let {
                isVersionCompliance: t,
                browserType: s,
                errorCode: i
            } = function() {
                const e = function() {
                        const {
                            userAgent: e
                        } = navigator;
                        return e.includes("Edge") ? w : e.includes("Firefox") ? v : e.includes("Chrome") ? u : e.includes("Safari") ? "Safari" : e.includes("compatible") && e.includes("MSIE") && e.includes("Opera") ? "IE" : e.includes("Opera") ? "Opera" : ""
                    }(),
                    t = navigator.userAgent.includes("x64") || navigator.userAgent.includes("x86_64") ? 64 : 32,
                    s = function(e) {
                        const {
                            userAgent: t
                        } = navigator;
                        return t.split(e)[1].split(".")[0].slice(1)
                    }(e);
                let i = !1,
                    a = 0;
                switch (e) {
                    case u:
                        i = s >= 91 && 64 === t, a = 701;
                        break;
                    case v:
                        i = s >= 97, a = 702;
                        break;
                    case w:
                        i = s >= 91, a = 703;
                        break;
                    default:
                        i = 0
                }
                return {
                    isVersionCompliance: i,
                    browserType: e,
                    errorCode: a
                }
            }(), a = "https:" === location.protocol;
            switch (this.config.isDynamicLoadLib && this.loadLibDHPlay(a, t), this.setMaxWindow(), this.beforeShowNum = 1, this.type) {
                case "real":
                    this.createRealPlayer(e);
                    break;
                case "record":
                    this.createRecordPlayer(e)
            }
            this.setSelectIndex(0), this.setPlayerNum(this.config.num), this.setCanvasGetContext(), this.bindUpdatePlayerWindow = this.__updatePlayerWindow.bind(this), window.addEventListener("resize", this.bindUpdatePlayerWindow), window.wsPlayerManager || (window.wsPlayerManager = new m)
        }
        setCanvasGetContext() {
            var e;
            window.wsCanvasGetContextSet || (window.wsCanvasGetContextSet = !0, HTMLCanvasElement.prototype.getContext = (e = HTMLCanvasElement.prototype.getContext, function(t, s) {
                return "webgl" === t && (s = Object.assign({}, s, {
                    preserveDrawingBuffer: !0
                })), e.call(this, t, s)
            }))
        }
        setMaxWindow() {
            let e = parseInt(this.config.maxNum, 10);
            this.maxWindow = e > 16 ? 25 : e > 9 ? 16 : e > 4 ? 9 : e > 1 ? 4 : 1
        }
        createRealPlayer() {
            this.config.showControl ? this.__addRealControl() : this.$wrapper.addClass("nocontrol"), Array(this.maxWindow).fill(1).forEach(((e, t) => {
                let s = new h({
                    wrapperDomId: this.el,
                    index: t,
                    wsPlayer: this
                });
                this.playerList.push(s)
            }))
        }
        createRecordPlayer() {
            this.config.showControl ? (this.__addRecordControl(), this.__addRealControl()) : this.$wrapper.addClass("nocontrol"), Array(this.maxWindow).fill(1).forEach(((e, t) => {
                let s = new y({
                    wrapperDomId: this.el,
                    index: t,
                    wsPlayer: this
                });
                this.playerList.push(s)
            }))
        }
        loadScript(e) {
            let t = document.createElement("script");
            t.src = e, document.head.appendChild(t)
        }
        loadLibDHPlay(e, t) {
            if (window.loadLibDHPlayerFlag) return;
            window.loadLibDHPlayerFlag = !0;
            let s = "./static/WSPlayer/multiThread/libdhplay.js";
            try {
                new SharedArrayBuffer(1)
            } catch (e) {
                s = "./static/WSPlayer/singleThread/libdhplay.js"
            }
            e && t && !this.config.onlyLoadSingleLib || (s = "./static/WSPlayer/singleThread/libdhplay.js"), this.loadScript(s)
        }
        playReal(e) {
            if (!e.rtspURL) return void console.error("播放实时视频需要传入rtspURL");
            e.wsURL = this.__getWSUrl(e.rtspURL, e.serverIp), e.playerAdapter = this.playerAdapter;
            let t = this.playerList[e.selectIndex];
            e.selectIndex + 1 < this.showNum ? this.setSelectIndex(e.selectIndex + 1) : this.selectIndex === e.selectIndex && t && this.setPtzChannel(e.channelData), t && t.init(e)
        }
        playRecord(e) {
            let t = this.playerList[e.selectIndex];
            e.wsURL = this.__getWSUrl(e.rtspURL, e.serverIp), e.playerAdapter = this.playerAdapter, e.isPlayback = !0, e.selectIndex + 1 < this.showNum ? this.setSelectIndex(e.selectIndex + 1) : ($(".ws-record-play").css({
                display: "none"
            }), $(".ws-record-pause").css({
                display: "block"
            })), t && t.init(e)
        }
        play() {
            let e = this.playerList[this.selectIndex];
            "pause" === e.status && e.play()
        }
        pause() {
            let e = this.playerList[this.selectIndex];
            "playing" === e.status && e.pause()
        }
        playSpeed(e, t) {
            "real" !== this.type ? this.playerList[void 0 === t ? this.selectIndex : t].playSpeed(e) : console.warn("实时预览不支持倍速播放")
        }
        setSelectIndex(e) {
            if (this.selectIndex !== e) {
                if (this.procedure && this.procedure.setPlayIndex(e), "record" === this.type) {
                    let t = (this.playerList[e] || {}).status;
                    "playing" === t && ($(".ws-record-play").css({
                        display: "none"
                    }), $(".ws-record-pause").css({
                        display: "block"
                    })), ["playing", "pause"].includes(t) ? this.procedure && this.procedure.changeTimeLine(e) : (this.setTimeLine([]), $(".ws-record-pause").css({
                        display: "none"
                    }), $(".ws-record-play").css({
                        display: "block"
                    })), this.__setPlaySpeed("", e)
                }
                this.sendMessage("selectWindowChanged", {
                    channelId: (this.playerList[e].options || {}).channelId,
                    playIndex: e
                }), this.selectIndex = e, this.setPtzChannel((this.playerList[e].options || {}).channelData), this.playerList.forEach(((t, s) => {
                    s === e ? t.$el.removeClass("unselected").addClass("selected") : t.$el.removeClass("selected").addClass("unselected"), this.__updateVoice(t, s === e)
                }))
            }
        }
        setPlayerNum(e) {
            let t = parseInt(e) || 1;
            t <= 1 ? (t = 1, this.$el.removeClass("screen-split-4 screen-split-9 screen-split-16 screen-split-25"), this.$el.addClass("fullplayer")) : t > 1 && t <= 4 ? (t = 4, this.$el.removeClass("fullplayer screen-split-9 screen-split-16 screen-split-25"), this.$el.addClass("screen-split-4")) : t > 4 && t <= 9 ? (t = 9, this.$el.removeClass("fullplayer screen-split-4 screen-split-16 screen-split-25"), this.$el.addClass("screen-split-9")) : t > 9 && t <= 16 ? (t = 16, this.$el.removeClass("fullplayer screen-split-4 screen-split-9 screen-split-25"), this.$el.addClass("screen-split-16")) : (t = 25, this.$el.removeClass("fullplayer screen-split-4 screen-split-9 screen-split-16"), this.$el.addClass("screen-split-25")), t > this.maxWindow && (t = this.maxWindow), this.showNum !== t && (this.showNum = t, this.sendMessage("windowNumChanged", this.showNum), setTimeout((() => {
                this.__updatePlayerWindow()
            }), 200))
        }
        setPlayerAdapter(e) {
            this.playerAdapter !== e && (this.playerAdapter = e, this.__updatePlayerWindow())
        }
        setTimeLine(e = []) {
            this.timeList = e, this.timeList.length ? $("#ws-record-time-box").css({
                visibility: "visible"
            }) : $("#ws-record-time-box").css({
                visibility: "hidden"
            }), this.__setTimeRecordArea(e)
        }
        setFullScreen() {
            let e = this.$el[0].children[0];
            e.requestFullscreen ? e.requestFullscreen() : e.webkitRequestFullscreen ? e.webkitRequestFullscreen() : e.mozRequestFullScreen ? e.mozRequestFullScreen() : e.msRequestFullscreen && e.msRequestFullscreen()
        }
        close(e) {
            let t = Number(e),
                s = this.playerList[t];
            s ? (s.close(), this.selectIndex === t && this.setTimeLine([])) : (this.setTimeLine([]), this.playerList.forEach((e => {
                e.close()
            })), window.removeEventListener("resize", this.bindUpdatePlayerWindow))
        }
        __addRealControl() {
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
            })), $(".full-screen-icon", this.$el).click((() => {
                this.setFullScreen()
            })), $(".one-screen-icon", this.$el).click((() => {
                this.setPlayerNum(1)
            })), $(".four-screen-icon", this.$el).click((() => {
                this.setPlayerNum(4)
            })), $(".nine-screen-icon", this.$el).click((() => {
                this.setPlayerNum(9)
            })), $(".sixteen-screen-icon", this.$el).click((() => {
                this.setPlayerNum(16)
            })), $(".twenty-five-screen-icon", this.$el).click((() => {
                this.setPlayerNum(25)
            })), $(".close-all-video", this.$el).click((() => {
                this.close()
            })), this.selfAdaptionSelectShow = !1, $(".ws-select-self-adaption", this.$el).click((e => {
                this.selfAdaptionSelectShow ? ($(".ws-self-adaption-type", this.$el).hide(), this.selfAdaptionSelectShow = !1) : ($(".ws-self-adaption-type", this.$el).show(), this.selfAdaptionSelectShow = !0, $(".ws-select-ul .ws-select-type-item").css({
                    background: "none"
                }), $(`.ws-select-ul [value=${this.playerAdapter}]`).css({
                    background: "#1A78EA"
                }))
            })), $(".ws-self-adaption-type", this.$el).click((e => {
                let t = e.target.getAttribute("value");
                this.setPlayerAdapter(t), $(".ws-select-show-option").text(e.target.getAttribute("optionValue"))
            })), "record" !== this.type && $(".ws-control-record").css({
                display: "none"
            }), $(".ws-record-pause", this.$el).click((e => {
                this.pause()
            })), $(".ws-record-play", this.$el).click((e => {
                this.play()
            })), $(".ws-record-speed-sub", this.$el).click((e => {
                "playing" === this.playerList[this.selectIndex].status && this.__setPlaySpeed("PREV")
            })), $(".ws-record-speed-add", this.$el).click((e => {
                "playing" === this.playerList[this.selectIndex].status && this.__setPlaySpeed("NEXT")
            }))
        }
        __setPlaySpeed(e, t) {
            let s, i, a = [{
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
                l = this.playerList[void 0 === t ? this.selectIndex : t];
            a.some(((r, n) => {
                if (r.value === l.speed) return i = "PREV" === e ? n - 1 : "NEXT" === e ? n + 1 : n, s = a[i], !s || (i ? i === a.length - 1 ? $(".ws-record-speed-add", this.$el).css({
                    cursor: "not-allowed"
                }) : ($(".ws-record-speed-sub", this.$el).css({
                    cursor: "pointer"
                }), $(".ws-record-speed-add", this.$el).css({
                    cursor: "pointer"
                })) : $(".ws-record-speed-sub", this.$el).css({
                    cursor: "not-allowed"
                }), $(".ws-record-speed-txt", this.$el).text(s.label), "playing" === l.status && this.playSpeed(s.value, t), !0)
            }))
        }
        __addRecordControl() {
            this.$el.append('\n            <div class="ws-control ws-record-control">\n                <div class="ws-timeline">\n                    <div class="ws-timeline-group"></div>\n                    <div class="ws-timeline-group"></div>\n                </div>\n                \x3c!--当前播放的时间点--\x3e\n                <div id="ws-record-time-box">\n                    <div class=\'ws-record-time\'>\n                        <span></span>\n                    </div>\n                </div>\n                <canvas height="60" id="ws-record-canvas" class="ws-record-area"/>\n            </div>\n        '), this.canvas = document.getElementById("ws-record-canvas"), this.ctx = this.canvas.getContext("2d");
            let e = $(this.$el[0].getElementsByClassName("ws-timeline-group")[0]),
                t = $(this.$el[0].getElementsByClassName("ws-timeline-group")[1]);
            new Array(49).fill(1).forEach(((t, s) => {
                let i = "ws-time-space " + (s % 4 ? "" : "ws-time-space-long");
                e.append(`<span class="${i}"></span>`)
            })), new Array(13).fill(1).forEach(((e, s) => {
                t.append(`<span class="ws-time-point">${(2*s+":00").padStart(5,"0")}</span>`)
            })), $(".ws-record-control").mouseenter((e => {
                $(".ws-record-control").append("<div id='ws-cursor'><div class='ws-cursor-time'><span></span></div></div>")
            })), $(".ws-record-control").mousemove((e => {
                let t = $(".ws-record-control").width(),
                    s = e.clientX - $(".ws-record-control")[0].getBoundingClientRect().left,
                    i = new Date(1e3 * (s / t * 24 * 60 * 60 - 28800)),
                    a = `${`${i.getHours()}`.padStart(2,"0")}:${`${i.getMinutes()}`.padStart(2,"0")}:${`${i.getSeconds()}`.padStart(2,"0")}`;
                $("#ws-cursor").css("left", s), $("#ws-cursor span").text(a)
            })), $(".ws-record-control").mouseleave((e => {
                $("#ws-cursor").remove()
            })), $(".ws-record-control").click((e => {
                if (["playing", "pause"].includes((this.playerList[this.selectIndex] || {}).status)) {
                    let t = $(".ws-record-control").width(),
                        s = e.clientX - $(".ws-record-control")[0].getBoundingClientRect().left,
                        i = parseInt(s / t * 24 * 60 * 60, 10),
                        a = new Date(1e3 * this.timeList[0].startTime).setHours(0, 0, 0) / 1e3 + i;
                    this.timeList.some((e => {
                        if (a >= e.startTime && a < e.endTime) return this.clickRecordTimeLine(i), !0
                    })) || this.clickRecordTimeLine("")
                }
            }))
        }
        __setTimeRecordArea(e = []) {
            if (e.length) {
                let t = $(".ws-record-control").width();
                this.canvas.width = t;
                let s = [],
                    i = [],
                    a = this.ctx.createLinearGradient(0, 0, 0, 60);
                a.addColorStop(0, "rgba(77, 201, 233, 0.1)"), a.addColorStop(1, "#1c79f4");
                let l = this.ctx.createLinearGradient(0, 0, 0, 60);
                l.addColorStop(0, "rgba(251, 121, 101, 0.1)"), l.addColorStop(1, "#b52c2c"), e.forEach((e => {
                    e.width = (e.endTime - e.startTime) * t / 86400;
                    let a = new Date(1e3 * e.startTime),
                        l = a.getHours(),
                        r = a.getMinutes(),
                        n = a.getSeconds();
                    e.left = (3600 * l + 60 * r + n) / 86400 * t, e.isImportant ? i.push(e) : s.push(e)
                })), s.forEach((e => {
                    this.ctx.clearRect(e.left, 0, e.width, 60), this.ctx.fillStyle = a, this.ctx.fillRect(e.left, 0, e.width, 60)
                })), i.forEach((e => {
                    this.ctx.clearRect(e.left, 0, e.width, 60), this.ctx.fillStyle = l, this.ctx.fillRect(e.left, 0, e.width, 60)
                }))
            } else this.canvas.width = 0
        }
        __setPlayingTime(e, t) {
            if (this.selectIndex === e) {
                let e = $(".ws-record-control").width(),
                    s = new Date(t),
                    i = s.getHours(),
                    a = s.getMinutes(),
                    l = s.getSeconds(),
                    r = (3600 * i + 60 * a + l) / 86400 * e,
                    n = `${String(i).padStart(2,"0")}:${String(a).padStart(2,"0")}:${String(l).padStart(2,"0")}`;
                $("#ws-record-time-box").css("left", r), $("#ws-record-time-box span").text(n)
            }
        }
        __getWSUrl(e, s) {
            let i = "https:" === location.protocol,
                a = e.match(/\d{1,3}(\.\d{1,3}){3}/g)[0];
            a || (a = e.split("//")[1].split(":")[0]);
            let l = i ? "wss" : "ws";
            if (i || this.config.useNginxProxy) {
                let e = "real" === this.type ? t.websocketPorts.realmonitor : t.websocketPorts.playback;
                return `${l}://${this.serverIp}/${e}?serverIp=${s||a}`
            }
            let r = "real" === this.type ? t.websocketPorts.realmonitor_ws : t.websocketPorts.playback_ws;
            return `${l}://${this.serverIp}:${r}`
        }
        __updatePlayerWindow() {
            this.playerList.forEach((e => {
                e.updateAdapter(this.playerAdapter)
            })), this.setTimeLine(this.timeList)
        }
        __updateVoice(e, t) {
            t ? $(".audio-icon", e.$el).hasClass("on") && e.player.setAudioVolume(1) : e.player && e.player.setAudioVolume(0)
        }
        __startTalk(e) {
            this.procedure && this.procedure.startTalk(e)
        }
        playRealVideo(e, t = "2", s) {
            this.procedure && this.procedure.playRealVideo(e, t, s)
        }
        changeStreamType(e, t, s) {
            this.procedure && this.procedure.playRealVideo([e], t, s)
        }
        getRecordList(e) {
            this.procedure && this.procedure.getRecordList(e)
        }
        clickRecordTimeLine(e) {
            e ? this.procedure && this.procedure.clickRecordTimeLine(e) : console.warn("所选时间点无录像")
        }
        jumpPlayByTime(e) {
            this.procedure && this.procedure.jumpPlayByTime(e)
        }
        playNextRecord(e) {
            this.procedure && this.procedure.playNextRecord(e)
        }
        videoClosed(e, t) {
            this.sendMessage("closeVideo", {
                selectIndex: e,
                changeVideoFlag: t
            }), this.procedure && this.procedure.videoClosed(e, t)
        }
        sendErrorMessage(e, s) {
            this.sendMessage("errorInfo", {
                errorCode: e,
                errorInfo: t.errorInfo[e],
                channelList: s
            })
        }
        initPanTilt(e) {
            this.panTilt = new k(e, this)
        }
        setPtzChannel(e) {
            this.panTilt && this.panTilt.setChannel(e)
        }
    }
    __publicField(R, "version", "1.2.4"), e.WSPlayer = R, e.default = R, Object.defineProperties(e, {
        __esModule: {
            value: !0
        },
        [Symbol.toStringTag]: {
            value: "Module"
        }
    })
}));