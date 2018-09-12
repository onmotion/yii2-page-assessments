<template>
    <div class="vue-component-root">
        <div class="assessment-container">
            <button class="button close-button" v-if="isFluent" @click="close">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAH6SURBVGhD7Zk7TgMxFEXTgEQPNIg1QMdCIFW2wg5o2Af/jmXw7RBrYAEow7F5N0yUmWhixh4n8pEsT17s+96RSFIwKhQKhc1iOp2esE7tZVKqqtqm9zlrx0phOAnCvti/2cdWToJJPLBX7I/BMpJwQY6UMvSZSYhgGS6d2fAz7HVUGfIXJBzU3tl27dhqcHGcUobcVgnWvh0Lg5wkMuTFkxDkRZUhJ76EIDeKDPfTSQjye5XhXnoJQZ9eZDg/nISg379kODe8hKBvkAzv5yMh6L+SDPX8JARzdJLhdb4SgnmWyrDnLyGYq1GGNWGth4RgvgWZJrKWEMy5VGYtJASDTmzuOah/ro0E8zZ+sB3UZ18AWcOQrRIiexmGcxL3ftoa1Nyf08K3GVt+MgzlJO78lDWo+Q82j51+NAeFYdok3pyEHXPn8pVhiE4Sgrfyk6G5k7j109RokxAcyUeGpk7ixk9RwyT27FgrHB1ehmZtEq9dJARXhpOhSS8SgqvpZQh3Ete+Ww1qLyESgoh0MoRuxZAQRMWXISyqhCAyngwhTuLKp9ag9tynhCC6fxkuJ5UQtOhXhsuXvzF/UHuKKSFo1SjDOrIj3eHSIevDcpJJCFrOyfB8YW+tDpe9DCuphGB+L8MKlxCEHBAY9p+iHqD3sT0WCoXCxjAa/QDuHENY6TckiAAAAABJRU5ErkJggg==">
            </button>
            <transition name="fade"
                        enter-active-class="fadeInRight"
                        leave-active-class="fadeOutLeft"
                        mode="out-in"
                        appear
            >
                <div class="assessment" v-if="currentAssessment" :key="currentAssessmentNumber"
                     style="animation-duration: 0.3s">
                    <div class="item" v-if="visible">
                        <h3 class="title">{{currentAssessment.assessment_question}}</h3>
                        <div class="assessment__values">
                            <transition-group name="star">
                                <i class="icon-star" :class="{voted: currentAssessment.assessment_value >= (i + 1)}"
                                   v-show="star.show" aria-hidden="true" v-for="(star, i) in stars" :key="i"
                                   @click="voteClicked(star, i)"></i>
                            </transition-group>
                        </div>
                        <transition name="fade"
                                    enter-active-class="fadeInUp"
                                    leave-active-class="fadeOutDown"
                                    mode="out-in"
                                    appear
                        >
                            <div class="comment-block" v-if="showCommentBlock" style="animation-duration: 0.3s">
                                <label for="comment"></label>
                                <textarea v-model="currentAssessment.assessment_comment" name="comment" id="comment"
                                          rows="3"
                                          :placeholder="messages.commentPrompt || ''"></textarea>
                                <div class="buttons">
                                    <button class="button button-send"
                                            v-if="currentAssessment.assessment_comment && currentAssessment.assessment_comment.length > 0"
                                            @click="sendRequest"
                                            :disabled="inProgress">
                                        Отправить <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDQ1MS44NDYgNDUxLjg0NyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDUxLjg0NiA0NTEuODQ3OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTM0NS40NDEsMjQ4LjI5MkwxNTEuMTU0LDQ0Mi41NzNjLTEyLjM1OSwxMi4zNjUtMzIuMzk3LDEyLjM2NS00NC43NSwwYy0xMi4zNTQtMTIuMzU0LTEyLjM1NC0zMi4zOTEsMC00NC43NDQgICBMMjc4LjMxOCwyMjUuOTJMMTA2LjQwOSw1NC4wMTdjLTEyLjM1NC0xMi4zNTktMTIuMzU0LTMyLjM5NCwwLTQ0Ljc0OGMxMi4zNTQtMTIuMzU5LDMyLjM5MS0xMi4zNTksNDQuNzUsMGwxOTQuMjg3LDE5NC4yODQgICBjNi4xNzcsNi4xOCw5LjI2MiwxNC4yNzEsOS4yNjIsMjIuMzY2QzM1NC43MDgsMjM0LjAxOCwzNTEuNjE3LDI0Mi4xMTUsMzQ1LjQ0MSwyNDguMjkyeiIgZmlsbD0iIzYwN2Q4YiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                    </button>
                                    <button v-else class="button button-skip" @click="nextQuestion"
                                            :disabled="inProgress">
                                        Пропустить <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDQ1MS44NDYgNDUxLjg0NyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDUxLjg0NiA0NTEuODQ3OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTM0NS40NDEsMjQ4LjI5MkwxNTEuMTU0LDQ0Mi41NzNjLTEyLjM1OSwxMi4zNjUtMzIuMzk3LDEyLjM2NS00NC43NSwwYy0xMi4zNTQtMTIuMzU0LTEyLjM1NC0zMi4zOTEsMC00NC43NDQgICBMMjc4LjMxOCwyMjUuOTJMMTA2LjQwOSw1NC4wMTdjLTEyLjM1NC0xMi4zNTktMTIuMzU0LTMyLjM5NCwwLTQ0Ljc0OGMxMi4zNTQtMTIuMzU5LDMyLjM5MS0xMi4zNTksNDQuNzUsMGwxOTQuMjg3LDE5NC4yODQgICBjNi4xNzcsNi4xOCw5LjI2MiwxNC4yNzEsOS4yNjIsMjIuMzY2QzM1NC43MDgsMjM0LjAxOCwzNTEuNjE3LDI0Mi4xMTUsMzQ1LjQ0MSwyNDguMjkyeiIgZmlsbD0iIzYwN2Q4YiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                    </button>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <transition name="fade"
                                enter-active-class="fadeInUp"
                                leave-active-class="fadeOutDown"
                                mode="out-in"
                                appear
                    >
                        <div class="notify-block" v-if="showNotifyBlock" style="animation-duration: 0.3s">
                            <span :class="notification.type">{{notification.text}}</span>
                        </div>
                    </transition>
                </div>
            </transition>
        </div>

    </div>
</template>

<script>

    export default {
        data: function () {
            let containerId = window.assessmentContainerId;
            let assessments = window[containerId];

            return {
                currentAssessmentNumber: 0,
                assessments: assessments.assessments,
                actions: assessments.actions,
                inProgress: false,
                isFluent: assessments.fluent || false,
                messages: assessments.messages,
                notification: {
                    type: null,
                    text: null,
                },
                visible: true
            }
        },
        methods: {
            voteClicked(star, i) {
                let n = i + 1;
                if (this.inProgress) {
                    return null;
                }
                star.show = false;
                this.$nextTick(function () {
                    star.show = true;
                });
                this.currentAssessment.assessment_value = n;
                this.sendRequest();
            },
            sendRequest() {

                let postData = this.currentAssessment;
                this.$http.post(this.actions.save, postData, {
                    // use before callback
                    before(request) {
                        this.inProgress = true;
                    },
                    headers: {'X-CSRF-Token': yii.getCsrfToken() || null}
                }).then(function (response) {
                    // success callback
                    if (typeof response.data === 'number') {
                        if (!this.currentAssessment) return null;
                        Vue.set(this.currentAssessment, 'assessment_id', response.data);
                        if (!this.showCommentBlock || this.currentAssessment.assessment_comment.length > 0) {
                            this.nextQuestion();
                        } else {
                            this.inProgress = false;
                        }
                    }
                }, function (error) {
                    this.inProgress = false;
                    this.notify(error.data.message || 'Ошибка', 'error');
                    console.log(error.data);
                });
            },
            nextQuestion() {
                let that = this;
                this.inProgress = true;
                if ((this.currentAssessmentNumber + 1) === this.questionsCount) {
                    this.visible = false;
                    this.notify(this.messages.successMessage, 'success');
                    setTimeout(function () {
                        that.currentAssessmentNumber = -1;
                        Vue.set(that.notification, 'text', null);
                        that.inProgress = false;
                    }, 2000);
                } else {
                    setTimeout(function () {
                        ++that.currentAssessmentNumber;
                        that.inProgress = false;
                    }, 400);
                }

            },
            notify(text, type) {
                this.notification.type = type;
                this.notification.text = text;
            },
            close() {
                this.currentAssessment.assessment_is_declined = true;
                this.sendRequest();
                this.$nextTick(function () {
                    this.currentAssessmentNumber = -1;
                });

            },
        },
        computed: {
            questionsCount() {
                return this.assessments.length;
            },
            currentAssessment() {
                return this.assessments[this.currentAssessmentNumber] || false;
            },
            showCommentBlock() {
                return this.currentAssessment.hasOwnProperty('assessment_comment') &&
                    this.currentAssessment.hasOwnProperty('assessment_id');
            },
            showNotifyBlock() {
                return this.notification.text && this.notification.text.length > 0;
            },
            stars() {
                let maxValue = this.currentAssessment.maxValue || 5;
                return Array(maxValue).fill().map(u => ({show: true}));
            }
        },
        components: {}
    }
</script>

<style lang="scss">
    .page-assessment-container {
        min-height: 30px;
        &.fluent {
            min-height: 0;
            position: fixed;
            right: 10px;
            bottom: 10px;
            z-index: 2;
            background: #fff;
            box-shadow: 0 2px 11px rgba(0, 0, 0, 0.12);
            border-radius: 5px;
            max-width: calc(100vw - 20px);
            min-width: 270px;
            background: rgba(0, 0, 0, 0.9);

        }
        & > div {
            max-width: 300px;
            margin: auto;
        }
    }
</style>

<style lang="scss" scoped>
    .fluent {
        .assessment {
            padding: 25px 20px;
        }
        .title {
            color: #ffffff;
            font-size: 19px;
        }
        .button{
            &:hover{
                text-shadow: none;
            }
        }
    }



    .assessment-container {
        overflow: hidden;
    }

    .title {
        letter-spacing: .3px;
        color: #374141;
        font-weight: 300;
        font-size: 21px;
        margin: 0 0 15px;
    }

    .icon-star {
        display: inline-block;
        color: #c3c3c3;
        font-size: 28px;
        letter-spacing: 7px;
        transition: color .3s;
        &.voted {
            color: #f79646;
        }
        &:hover {
            color: #f79646;
            cursor: pointer;
        }
    }

    .assessment {
        text-align: center;
    }

    .comment-block {
        width: 300px;
        max-width: 100%;
        margin: auto;
        label {
            display: none;
        }
        textarea {
            margin-bottom: 5px;
            border-radius: 3px;
            width: 100%;
            border: none;
            border-bottom: 2px solid #8e8e8e;
            padding: 10px;
            transition: border .2s;
            margin-top: 12px;
            resize: vertical;
            background: #E8E8E8;
            outline: none;
            &:focus {
                border-bottom: 2px solid #f79646;
            }
        }
    }

    .buttons {
        text-align: right;
    }

    .button {
        background: none;
        border: none;
        letter-spacing: 1px;
        font-size: 13px;
        outline: none;
        color: #607D8B;
        padding: 0;
        transition: all .3s;
        &:hover {
            color: #506674;
            text-shadow: 0 2px 3px #d6d6d6;
        }
        img{
            width: 9px;
        }
    }
    .close-button {
        position: absolute;
        right: 8px;
        top: 6px;
        z-index: 1;
        img {
            width: 15px;
            opacity: .5;
            transition: opacity .3s;
        }
        &:hover {
            img {
                opacity: 1;
            }
        }
    }

    .notify-block {
        span {
            &.error {
                color: #d50000;
            }
            &.success {
                color: #64dd17;
            }
        }
    }

    /* animations */
    .star-enter-active {
        animation: star-in 0.3s ease;
    }

    @keyframes star-in {
        0% {
            transform: scale(0.9);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

</style>
