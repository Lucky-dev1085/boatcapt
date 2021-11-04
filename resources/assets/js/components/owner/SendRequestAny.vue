<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">Request Sent!</h2>
            <h5 class="h22 article-subtitle container-520 text-center">Captains{{captainNames}} has been notified of your interest and will reply to your request within 48 hours.</h5>
            <br/>
            <br/>
            <div class="text-center mt-3 mb-4">
                <a :href="URL.base + '/find-captains'" class="btn btn-darkblue btn-size-360">Find More Captains</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['captainList'],

        data() {
            return {
                URL: window.URL,
                captainNames: ''
            }
        },
        mounted() {   

            if(this.captainList.length > 0)
            {
                this.captainNames += "(" + this.captainList[0].firstName;

                for(var i = 1; i < this.captainList.length; i++)
                {
                    this.captainNames += ", " + this.captainList[i].firstName;
                }  
                this.captainNames += ")"
            }
                   
        },

        created() {
        },
    }
</script>
