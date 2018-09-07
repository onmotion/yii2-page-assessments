<template>
    <div class="vue-component-root">
        <notifications group="votes"/>
        <div class="assessment-container">
            <button class="button close-button" v-if="isFluent" @click="close">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADfSURBVFhH7dc7DsIwEIRhHwA4DvQU3AwkxLE4A7egTgMSzEKmiRR5be96G//SKI/qQyRF0mjk1HE+erXBDv/TfIL5YOfflX2CuWNPbCc3NAnGA0XMhJ3kRknWqCYMs0KZYFgryhTDalEuGFaKcsUwLaoLhuVQXTFsDRWCYUtUKIYRdcXCMeyGCeqNhWP4N70wQV2wsJbPjGDCUGsPcAgq9zZ1RWlf7S4oLYa5okoxzAVVi2GmqFYMM0FZYVgTyhrDqlBeGFaM2mPyEeeBYYJ5YPLjVW3no2fqr9bRSF9KX7osZuC7q2PCAAAAAElFTkSuQmCC">
            </button>
            <transition name="fade"
                        enter-active-class="fadeInRight"
                        leave-active-class="fadeOutLeft"
                        mode="out-in"
                        appear
            >
                <div class="assessment" v-if="currentAssessment" :key="currentAssessmentNumber"
                     style="animation-duration: 0.3s">
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
                                      :placeholder="messages.comment || ''"></textarea>
                            <div class="buttons">
                                <button class="button button-send"
                                        v-if="currentAssessment.assessment_comment && currentAssessment.assessment_comment.length > 0"
                                        @click="sendRequest"
                                        :disabled="inProgress">
                                    Отправить ->
                                </button>
                                <button v-else class="button button-skip" @click="nextQuestion" :disabled="inProgress">
                                    Пропустить ->
                                </button>
                            </div>
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
            let assessments = window.assessments;
            let maxValue = assessments.maxValue || 5;
            let stars = Array(maxValue).fill().map(u => ({show: true}));
            console.log(this.parent);
            return {
                currentAssessmentNumber: 0,
                assessments: assessments.assessments,
                actions: assessments.actions,
                bounce: true,
                stars: stars,
                inProgress: false,
                isFluent: assessments.fluent || false,
                messages: window.assessments.messages
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
                        console.log(response.data);
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
                    if (error.data.name) {
                        this.notify('Ошибка', error.data.message || error.data, 'error');
                    }
                    console.log(error.data);
                });
            },
            nextQuestion() {
                let that = this;
                this.inProgress = true;
                setTimeout(function () {
                    ++that.currentAssessmentNumber;
                    that.inProgress = false;
                }, 400);
            },
            notify(title, text, type) {
                this.$notify({
                    group: 'votes',
                    title: title,
                    text: text,
                    type: type,
                });
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
            }
        },
        components: {}
    }
</script>

<style lang="scss">
    .page-assessment-container {
        &.fluent {
            position: fixed;
            right: 10px;
            bottom: 10px;
            z-index: 2;
            background: #fff;
            box-shadow: 0 2px 11px rgba(0, 0, 0, 0.12);
            border-radius: 5px;
            max-width: calc(100vw - 20px);
            min-width: 270px;
        }
    }
</style>

<style lang="scss" scoped>
    .fluent {
        .assessment {
            padding: 20px 10px;
        }
    }
    .close-button{
        position: absolute;
        right: 5px;
        top: 5px;
        img{
            width: 20px;
            opacity: .5;
            transition: opacity .3s;
            &:hover{
                opacity: 1;
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
        &:hover{
            color: #f7c092;
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
                border-bottom: 2px solid #607D8B;
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
