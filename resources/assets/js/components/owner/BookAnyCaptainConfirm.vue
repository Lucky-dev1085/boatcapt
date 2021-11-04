<template>
    <section class="section-book-captain">
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Request a Captain</h2>
            <h5 class="article-subtitle">Trip ID #{{ tripInfo.tripId }}</h5>
            <form :action="URL.base+'/request-any-captain'" method="POST">
                <input type="hidden" name="_token" id="_token" :value="token">
                <input type="hidden" name="tripId" id="tripId" :value="tripInfo.tripId">
                <h3 class="article-title my-5">Trip Summary</h3>
                <div class="row">
                    <div class="col mb-1">
                        <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <div v-for="msg in message.body">
                                {{msg}}
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <h4 class="font-rockwell font-weight-bold color-blackblue">Starts:</h4>
                    </div>
                    <div class="col-9 px-0">
                        <h4>
                            {{ tripInfo.startLocation }}
                            <br/>
                            {{ tripInfo.startTime }}
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <h4 class="font-rockwell font-weight-bold color-blackblue">Ends:</h4>
                    </div>
                    <div class="col-9 px-0">
                        <h4>
                            {{ tripInfo.endLocation }}
                            <br/>
                            {{ tripInfo.endTime }}
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <h4 class="font-rockwell font-weight-bold color-blackblue">Notes:</h4>
                    </div>
                    <div class="col-9 px-0">
                        <h4 v-br="tripInfo.describe">
                        </h4>
                    </div>
                </div>
                <br/>
                <div class="row mt-3">
                    <div class="col-4 text-right">
                        <label class="m-switch m-switch--sm m-switch--icon m-switch--info">
                            <input type="checkbox" required="required">
                            <span></span>
                        </label>
                    </div>
                    <div class="col-8 px-0">
                        <h5>
                            I agree to the
                            <a :href="URL.base+'/terms'" class="link-underline">Terms of Use</a>
                        </h5>
                    </div>
                </div>
                <br/>
                <div class="text-center mt-3 mb-4">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Request Capts</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'tripInfo'],

        data() {
            return {
                URL: window.URL,
                token: window.token
            }
        },
        mounted() {            
        },

        created() {
        },
    }
</script>
