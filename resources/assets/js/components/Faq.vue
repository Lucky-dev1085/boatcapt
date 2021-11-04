<template>
    <section class="section-background section-faq">
        <p class="page-title mb-5">FAQ</p>

        <article class="article-modal">
            <h2 class="article-title">Frequently Asked Questions</h2><br /><br />
            <div class="row align-items-center">
                <div class="col-sm-12 pl-sm-3 pr-0">
                    <h5 class="mb-4"><strong>How much does it cost to hire a Captain?</strong><br />Each Captain will quote your trip based upon the information you provide them. I.e. duration, boat, nature of trip etc.</h5>

                    <h5 class="mb-4"><strong>How long has my Captain been boating?</strong><br />
                    Take a look at the Captains bio, this info plus their other experience will be there.  Everyone's background varies, but we all share the same love for boating.</h5>

                    <h5 class="mb-4"><strong>Is there a minimum size boat to hire a Captain?</strong><br />
                    No Minimum’s! We’ve given instruction on vessels as small as 14’.</h5>

                    <h5 class="mb-4"><strong>What is the cancellation policy?</strong><br />
                    Please see our <a :href="URL.base + '/cancellation'">cancellation policy</a>.</h5>

                    <h5 class="mb-4"><strong>Can I hire a Captain for multiple days in a row?</strong><br />
                    Yes, when you are completing your trip info please make sure to enter the start and end date for the entire trip. Under “Nature of this Trip” you can select Boat Trips and include some details of the trip.</h5>

                    <h5 class="mb-4"><strong>What happens if the boat breaks down?</strong><br />
                    This is an unfortunate way to end the day, but it does happen. This is why we ask to make sure you have valid tow coverage to get you home. The Captain is present and still on the clock during this time.</h5>

                    <h5 class="mb-4"><strong>What happens if my trip runs longer than originally expected?</strong><br />
                    There is a Half hour allowance at the end of your trip i.e. if your trip is scheduled to end at 5:00pm and ends at 5:29 there is no extra charge. If the trip goes past that half hour  you get billed at the Captains rate prorated for every half an hour past the trips end.</h5>

                    <h5 class="mb-4"><strong>What defines the trips ending?</strong><br />
                    When the vessel is secure at her berth and the Captain can be relieved of duty.</h5>

                    <h5 class="mb-4"><strong>Do we sell any of your data?</strong><br />
                    Absolutely NOT! All of your information is private.</h5>

                    <h5 class="mb-4"><strong>How do owners make payment?</strong><br />
                    Either by Credit Card or Paypal which will be set up in your profile.</h5>

                    <h5 class="mb-4"><strong>Do the Captains clean the boat after?</strong><br />
                    This is up to each Captain if they want to provide this type of service, please include it in your trip details so they can factor it into their price.</h5>

                    <h5 class="mb-4"><strong>What if the weather doesn’t cooperate with the day of our trip?</strong><br />
                    Safety is the #1 concern! We can reschedule, amend, or refund your trip.</h5>

                    <h5 class="mb-4"><strong>Is there a cost to the owner?</strong><br />
                    The only cost is what is quoted to the owner by the Captain.</h5>

                    <h5 class="mb-4"><strong>Does the Captain have the final say?</strong><br />
                    The Captain does have the final say and can end or amend the trip at any time if safety of the vessel, its crew, and the Captain are at risk.</h5>

                    <h5 class="mb-4"><strong>Do I have to pay for the Captains transportation back if my trip ends in a different port than it started?</strong><br />
                    Yes it is common that the owner bears the cost of getting the Captain back to the port of origin. The Captain should include this in his quote, and it will vary depending on the move.</h5>

                    <h5 class="mb-4"><strong>How do the Captains get paid?</strong><br />
                    Upon completion of the trip Captains will receive payment via Paypal or Check.</h5>
                </div>

            </div>
            <br/>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/register'" class="btn btn-darkblue btn-size-360">Create Free Account</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL
            }
        },
        mounted() {            
        },

        created() {
        },
    }
</script>
