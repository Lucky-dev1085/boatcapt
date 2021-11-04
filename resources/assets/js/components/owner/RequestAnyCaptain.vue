<template>
    <section class="section-book-captain">
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Request a Captain</h2>
            <br/>
            <br/>
            <form class="form-md container-440" :action="URL.base+'/owner-request-captain'" method="POST">
                <input type="hidden" name="_token" id="_token" :value="token">
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
                <h4 class="font-weight-bold mx-4">When &amp; Where will the trip BEGIN?</h4>
                <div class="form-group mt-3">
                    <div class="form-group-location">
                        <input id="startLocation" name="startLocation" type="text" class="form-control" required="required" placeholder="Start Location..." />
                        <span class="right-icon fa fa-map-marker"></span>
                    </div>
                </div>
                <div class="form-group form-group-calendar mt-4">
                    <input type="text" name="startTime" id="startTime" class="form-control" placeholder="Start Time...">
                    <span class="right-icon fa fa-calendar"></span>
                </div>
                <h4 class="font-weight-bold mx-4 mt-5">When &amp; Where will the trip END?</h4>
                <div class="form-group mt-3">
                    <div class="form-group-location">
                        <input id="endLocation" name="endLocation" type="text" class="form-control" required="required" placeholder="Return Location..."/>
                        <span class="right-icon fa fa-map-marker"></span>
                    </div>
                </div>
                <div class="form-group form-group-calendar mt-4">
                    <input type="text" name="endTime" id="endTime" class="form-control" placeholder="Return Time...">
                    <span class="right-icon fa fa-calendar"></span>
                </div>
                <h4 class="font-weight-bold mx-4 mt-5">What is the nature of this trip?</h4>
                <div class="div-select mt-3">
                    <select name="tripNature" v-model="tripNature" class="custom-select custom-select-lg" onfocus="(this.style.fontStyle='normal')" onblur="if(!this.value){this.style.fontStyle='italic'}"
                     :style="!tripNature && 'font-style: italic;'" required="required">
                        <option selected="selected" hidden="hidden" disabled="disabled" value="">Choose one...</option>
                        <option value="Lessons">Lessons</option>
                        <option value="Charter">Charter</option>
                        <option value="Booze_Cruise">Booze Cruise</option>
                        <option value="Delivery_Transport">Delivery/Transport</option>
                        <option value="General_Boating_Trip">General Boating Trip</option>
                        <option value="Boat_Move">Boat Move</option>
                        <option value="Boat_Move">Special Occasion</option>
                    </select>
                    <button type="button" class="btn btn-pure dropdown-toggle">
                    </button>
                </div>
                <h4 class="font-weight-bold mx-4 mt-5">Any notes for this captain?</h4>
                <textarea v-model="describe" name="describe" class="form-control mt-3" placeholder="Tell us more about..."></textarea>
                <br/>
                <br/>
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Continue</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'oldInput'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                tripNature: this.oldInput && this.oldInput.tripNature ? this.oldInput.tripNature : '',
                describe: this.oldInput && this.oldInput.describe ? this.oldInput.describe : null,
            }
        },
        mounted() {   
            $('#startTime').datetimepicker({
            });

            $('#endTime').datetimepicker({
            });

            if(this.oldInput && this.oldInput.startTime)
                $('#startTime').val(this.oldInput.startTime);

            if(this.oldInput && this.oldInput.endTime)
                $('#endTime').val(this.oldInput.endTime);

            if(typeof google !== "undefined")
                google.maps.event.addDomListener(window, 'load', this.initialize);  

        },

        created() {
        },

        methods: {

            initialize() {
                if(this.oldInput && this.oldInput.startLocation)
                    $('#startLocation').val(this.oldInput.startLocation);

                if(this.oldInput && this.oldInput.endLocation)
                    $('#endLocation').val(this.oldInput.endLocation);

                var startLocation = document.getElementById('startLocation');
                var start_autocomplete = new google.maps.places.Autocomplete(startLocation);

                var endLocation = document.getElementById('endLocation');
                var end_autocomplete = new google.maps.places.Autocomplete(endLocation);
            }
        }
    }
</script>
